<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Branch;

class AdminController extends Controller
{

    public function index(Request $request)
    {
        $branchId = $request->input('branch_id', 'all');
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();
        $lastMonth = now()->subMonth()->toDateString();
        $user = auth('web')->user();

        // Nếu không phải admin hoặc director, chỉ lấy chi nhánh hiện tại
        if (!in_array($user->role, ['director', 'admin'])) {
            $branchId = $user->branch_id;
        }

        $query = Order::query();

        // Lọc theo chi nhánh
        if ($branchId !== 'all') {
            $query->whereHas('employee', function ($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            });
        }

        $orderDataToday = $query->select(DB::raw('SUM(total_price) as total_sales, COUNT(id) as total_orders'))
            ->whereDate('created_at', $today)
            ->first();

        $orderDataYesterday = Order::query()
            ->select(DB::raw('SUM(total_price) as total_sales'))
            ->whereDate('created_at', $yesterday)
            ->when($branchId !== 'all' || !in_array($user->role, ['director', 'admin']), function ($query) use ($branchId, $user) {
                $query->whereHas('employee', function ($query) use ($branchId, $user) {
                    if ($branchId !== 'all') {
                        $query->where('branch_id', $branchId);
                    } else {
                        $query->where('branch_id', $user->branch_id);
                    }
                });
            })
            ->first();

        $orderDataLastMonth = Order::query()
            ->select(DB::raw('SUM(total_price) as total_sales'))
            ->whereDate('created_at', $lastMonth)
            ->when($branchId !== 'all' || !in_array($user->role, ['director', 'admin']), function ($query) use ($branchId, $user) {
                $query->whereHas('employee', function ($query) use ($branchId, $user) {
                    if ($branchId !== 'all') {
                        $query->where('branch_id', $branchId);
                    } else {
                        $query->where('branch_id', $user->branch_id);
                    }
                });
            })
            ->first();

        $percentChangeYesterday = $orderDataYesterday->total_sales > 0
            ? (($orderDataToday->total_sales - $orderDataYesterday->total_sales) / $orderDataYesterday->total_sales) * 100
            : ($orderDataToday->total_sales > 0 ? 100 : 0);

        $percentChangeLastMonth = $orderDataLastMonth->total_sales > 0
            ? (($orderDataToday->total_sales - $orderDataLastMonth->total_sales) / $orderDataLastMonth->total_sales) * 100
            : ($orderDataToday->total_sales > 0 ? 100 : 0);


        $salesByDay = Order::query()
            ->select(DB::raw('DAY(created_at) as day, SUM(total_price) as total_sales'))
            ->whereMonth('created_at', now()->month)
            ->when($branchId !== 'all' || !in_array($user->role, ['director', 'admin']), function ($query) use ($branchId, $user) {
                $query->whereHas('employee', function ($query) use ($branchId, $user) {
                    if ($branchId !== 'all') {
                        $query->where('branch_id', $branchId);
                    } else {
                        $query->where('branch_id', $user->branch_id);
                    }
                });
            })
            ->groupBy(DB::raw('DAY(created_at)'))
            ->get();

        $salesByWeek = Order::query()
            ->select(DB::raw('WEEKOFYEAR(created_at) as week, SUM(total_price) as total_sales'))
            ->whereYear('created_at', now()->year)
            ->when($branchId !== 'all' || !in_array($user->role, ['director', 'admin']), function ($query) use ($branchId, $user) {
                $query->whereHas('employee', function ($query) use ($branchId, $user) {
                    if ($branchId !== 'all') {
                        $query->where('branch_id', $branchId);
                    } else {
                        $query->where('branch_id', $user->branch_id);
                    }
                });
            })
            ->groupBy(DB::raw('WEEKOFYEAR(created_at)'))
            ->get();

        $salesByMonth = Order::query()
            ->select(DB::raw('MONTH(created_at) as month, SUM(total_price) as total_sales'))
            ->whereYear('created_at', now()->year)
            ->when($branchId !== 'all' || !in_array($user->role, ['director', 'admin']), function ($query) use ($branchId, $user) {
                $query->whereHas('employee', function ($query) use ($branchId, $user) {
                    if ($branchId !== 'all') {
                        $query->where('branch_id', $branchId);
                    } else {
                        $query->where('branch_id', $user->branch_id);
                    }
                });
            })
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        $branches = Branch::all();

        if ($request->ajax()) {
            return response()->json([
                'orderDataToday' => $orderDataToday,
                'percentChangeYesterday' => $percentChangeYesterday,
                'percentChangeLastMonth' => $percentChangeLastMonth,
                'salesByDay' => $salesByDay,
                'salesByWeek' => $salesByWeek,
                'salesByMonth' => $salesByMonth,
            ]);
        }

        return view('admin.index', array_merge(
            compact('orderDataToday', 'percentChangeYesterday', 'percentChangeLastMonth', 'branches', 'branchId'),
            [
                'salesByDay' => $salesByDay,
                'salesByWeek' => $salesByWeek,
                'salesByMonth' => $salesByMonth,
            ]
        ));
    }

    public function salesReport(Request $request)
    {
        $startDate = $request->input('startDate');
        $user = auth('web')->user();

        $query = Order::query();
        $query = Order::select(
            DB::raw('DATE(orders.created_at) as sale_date'),
            'branches.name as branch',
            'orders.id as transaction_id',
            'producttitles.name as product_title',
            DB::raw('SUM(order_details.quantity) as quantity'),
            DB::raw('SUM(order_details.price) as revenue')
        )
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('producttitles', 'products.product_title_id', '=', 'producttitles.id')
            ->join('employees', 'orders.employee_id', '=', 'employees.id')
            ->join('branches', 'employees.branch_id', '=', 'branches.id')
            ->groupBy('sale_date', 'branch', 'transaction_id', 'product_title');

        // Lọc theo chi nhánh của tài khoản đăng nhập nếu không phải là director hoặc admin
        if (!in_array($user->role, ['director', 'admin'])) {
            $query->where('employees.branch_id', $user->branch_id);
        }

        if ($startDate) {
            $query->whereDate('orders.created_at', $startDate);
        }

        $salesData = $query->paginate(20);

        return view('admin.salesReport', compact('salesData', 'startDate'));
    }

    public function exportSalesReport(Request $request)
    {
        $startDate = $request->input('startDate');
        $user = auth('web')->user();

        $query = Order::select(
            DB::raw('DATE(orders.created_at) as sale_date'),
            'branches.name as branch',
            'orders.id as transaction_id',
            'producttitles.name as product_title',
            DB::raw('SUM(order_details.quantity) as quantity'),
            DB::raw('SUM(order_details.price) as revenue')
        )
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('producttitles', 'products.product_title_id', '=', 'producttitles.id')
            ->join('employees', 'orders.employee_id', '=', 'employees.id')
            ->join('branches', 'employees.branch_id', '=', 'branches.id')
            ->groupBy('sale_date', 'branch', 'transaction_id', 'product_title');

        if (!in_array($user->role, ['director', 'admin'])) {
            $query->where('employees.branch_id', $user->branch_id);
        }

        if ($startDate) {
            $query->whereDate('orders.created_at', $startDate);
        }

        $salesData = $query->get();

        return response()->json($salesData);
    }


    public function login()
    {
        return view('admin.login');
    }

    public function checkLogin(Request $req)
    {
        $validated = $req->validate([
            'name' => 'required|exists:employees,name', // Kiểm tra tên tài khoản
            'password' => 'required',
        ]);

        $data = $req->only('name', 'password');

        // Sử dụng key đúng trong auth()->attempt()
        if (auth('web')->attempt(['name' => $data['name'], 'password' => $data['password']])) {
            if (auth('web')->check() && auth('web')->user()->email_verified_at == null) {
                auth('web')->logout();
                return response()->json([
                    'success' => false,
                    'message' => 'Tài khoản chưa được xác thực.',
                ], 403);
            }

            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập thành công.',
                'user' => auth('web')->user(),
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Sai tên tài khoản hoặc mật khẩu.',
        ], 422);
    }

    public function logout()
    {
        auth('web')->logout();
        return redirect()->route('admin.login')->with('ok', 'logouted');
    }
}