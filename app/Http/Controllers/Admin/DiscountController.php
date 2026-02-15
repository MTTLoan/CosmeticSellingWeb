<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Discount;
use App\Models\BranchDiscount;
use App\Models\Employee;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DiscountController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $employee = auth('web')->user();
        $query = Discount::query();

        // Áp dụng bộ lọc
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->where('start_date', '<=', $request->start_date)
                ->where('end_date', '>=', $request->end_date);
        }

        if ($request->filled('filter_value')) {
            $query->where('starting_price', '>=', $request->filter_value);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        // Phân trang kết quả cho quản lý chi nhánh hoặc admin
        if ($employee->role === 'admin') {
            // Admin có thể xem tất cả các khuyến mãi
            $discounts = $query->paginate(20);
        } else {
            // Nhân viên và Quản lý chi nhánh chỉ có thể xem các khuyến mãi của chi nhánh của họ
            $discounts = $query->join('branches_discounts', 'discounts.id', '=', 'branches_discounts.discount_id')
                ->where('branches_discounts.branch_id', $employee->branch_id)
                ->select('discounts.*')
                ->paginate(20);
        }

        // Truyền các khuyến mãi đã phân trang tới view
        return view('admin.discount.index', compact('discounts'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
   
    // Show the form for creating a new discount
    public function create()
{
    $branches = Branch::all(); // Lấy tất cả các chi nhánh
    return view('admin.discount.create', compact('branches'));
}

    // Store a newly created discount
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:discounts',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|string',
            'value' => 'required|numeric',
            'starting_price' => 'required|numeric',
        ]);

        // Create the discount
        $discount = Discount::create([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'type' => $validated['type'],
            'value' => $validated['value'],
            'starting_price' => $validated['starting_price'],
        ]);

        // Attach branches to the discount
        if (Auth::user()->role === 'admin') {
            $discount->branches()->attach($request->branch_id);
        } else {
            $discount->branches()->attach(Auth::user()->branch_id);
        }

        // Redirect to the discount index page with a success message
        return redirect()->route('discount.index')->with('success', 'Khuyến mãi đã được tạo thành công.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Discount $discount)
    {
        $branches = $discount->branches; // Lấy các chi nhánh liên quan đến khuyến mãi
        return view('admin.discount.show', compact('discount', 'branches'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discount $discount)
    {
        $this->authorize('update', $discount);
        $branches = Branch::all(); // Lấy tất cả các chi nhánh
        return view('admin.discount.edit', compact('discount', 'branches'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Discount $discount)
{
    // Validate the request data
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:255|unique:discounts,code,' . $discount->id,
        'start_date' => 'required|date|before_or_equal:end_date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'type' => 'required|string',
        'branch_id' => 'required|array',
        'branch_id.*' => 'exists:branches,id',
        'value' => 'required|numeric',
        'starting_price' => 'required|numeric',
    ]);

    // Update the discount
    $discount->update([
        'name' => $validated['name'],
        'code' => $validated['code'],
        'start_date' => $validated['start_date'],
        'end_date' => $validated['end_date'],
        'type' => $validated['type'],
        'value' => $validated['value'],
        'starting_price' => $validated['starting_price'],
    ]);

    // Sync branches to the discount
    $discount->branches()->sync($validated['branch_id']);

    // Redirect to the discount index page with a success message
    return redirect()->route('discount.index')->with('success', 'Khuyến mãi đã được cập nhật thành công.');
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discount $discount)
    {
        $this->authorize('delete', $discount);
        try {
            // Xóa khuyến mãi
            $discount->delete();

            return redirect()->route('discount.index')->with('success', 'Xóa khuyến mãi thành công.');
        } catch (\Exception $e) {
            Log::error('Lỗi: ' . $e->getMessage());
            return redirect()->route('discount.index')->with('error', 'Xóa khuyến mãi thất bại.');
        }
    }

    public function checkDiscount(Request $request)
    {
        $code = $request->input('code');
        $totalPrice = $request->input('total_price');

        $discount = Discount::where('code', $code)
            ->where('starting_price', '<=', $totalPrice)
            ->where('end_date', '>=', now())
            ->first();

        if ($discount) {
            return response()->json([
                'success' => true,
                'discount' => $discount->value,
                'discount_id' => $discount->id,
                'message' => 'Mã giảm giá hợp lệ.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.'
            ]);
        }
    }

    public function listDiscounts()
{
    $discounts = Discount::where('type', 'Website')
        ->where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->get();
    return view('LayVoucher', compact('discounts'));
}
}