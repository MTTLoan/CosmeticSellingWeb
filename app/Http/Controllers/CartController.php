<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Hiển thị giỏ hàng.
     */
    public function index()
    {
        if (!auth('cus')->check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để xem giỏ hàng.');
        }

        $userId = auth('cus')->id();

        // Lấy các item trong giỏ hàng và ảnh đầu tiên của mỗi cuốn sách
        $cartItems = Cart::with(['product' => function ($query) {
            $query->with(['productTitle', 'images' => function ($query) {
                $query->orderBy('id')->limit(1); // Lấy ảnh đầu tiên
            }]);
        }])
            ->where('customer_id', $userId)
            ->get();

        $totalQuantity = $cartItems->sum('quantity');
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->unit_price;
        });

        return view('GioHang', compact('cartItems', 'totalQuantity', 'totalPrice'));
    }
    /**
     * Thêm sản phẩm vào giỏ hàng.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::where('customer_id', auth('cus')->id())
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($cartItem) {
            // Nếu sản phẩm đã tồn tại trong giỏ hàng, cập nhật số lượng
            $cartItem->quantity += $validated['quantity'];
            $cartItem->save();
        } else {
            // Nếu sản phẩm chưa tồn tại trong giỏ hàng, tạo mới
            $cartItem = Cart::create([
                'customer_id' => auth('cus')->id(),
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
            ]);
        }

        return response()->json(['success' => true, 'cartItem' => $cartItem]);
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::where('id', $id)->where('customer_id', auth('cus')->id())->firstOrFail();
        $cartItem->update(['quantity' => $validated['quantity']]);

        return response()->json(['success' => true, 'cartItem' => $cartItem]);
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng.
     */
    public function destroy($id)
    {
        $cartItem = Cart::where('id', $id)->where('customer_id', auth('cus')->id())->firstOrFail();
        $cartItem->delete();

        return response()->json(['success' => true]);
    }
}