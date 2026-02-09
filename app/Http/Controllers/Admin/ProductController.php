<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductTitle;
use App\Models\ProductType;
use App\Models\ChangeLog;
use App\Models\Image;
use App\Models\Supplier;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    use AuthorizesRequests;
    // use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    // public function __construct()
    // {
    //     // Tự động áp dụng các kiểm tra quyền cho các hành động tiêu chuẩn
    //     $this->authorizeResource(Product::class, 'product');
    // }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd(auth('web')->user());
        $query = Product::with(['productTitle.productType']);

        $employee = auth('web')->user();

        //filter và search
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->whereHas('productTitle', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('author', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('filter_bookType')) {
            $filterBookType = $request->input('filter_bookType');
            $query->whereHas('productTitle.productType', function ($q) use ($filterBookType) {
                $q->whereIn('id', $filterBookType);
            });
        }

        if ($request->filled('filter_quantity')) {
            $filterQuantity = $request->input('filter_quantity');
            $query->where('quantity', '>=', $filterQuantity);
        }

        if ($employee->role === 'admin') {
            // Admin xem tất cả sách
            $products = $query->orderBy('id', 'asc')->paginate(20);
        } else {
            // Staff và Branch Manager chỉ xem sách thuộc chi nhánh của mình
            $products = Product::join('products_branches', 'products.id', '=', 'products_branches.product_id')
                ->where('products_branches.branch_id', $employee->branch_id)
                ->select('products.*', 'products_branches.quantity as branch_quantity')
                ->orderBy('id', 'asc')->paginate(20);
        }

        $productTypes = ProductType::all(); // Lấy tất cả các thể loại sách để hiển thị trong form tìm kiếm
        return view('admin.book.index', compact('products', 'productTypes'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Kiểm tra quyền 'create' trên model Book
        $this->authorize('create', Product::class);

        $productTypes = ProductType::orderBy('id', 'asc')->select('id', 'name')->get();
        $suppliers = Supplier::orderBy('id', 'asc')->select('id', 'name')->get();
        return view('admin.book.create', compact('productTypes', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_price' => 'integer|min:1|gt:cost',
            'cost' => 'integer|min:1',
            'publishing_year' => 'integer|min:1',
            'capacity' => 'integer|min:1',
            'images.*' => 'file|mimes:jpeg,png,jpg,gif,svg',
        ], [
            'unit_price.integer' => 'Giá bán phải là số nguyên',
            'unit_price.min' => 'Giá bán phải lớn hơn 0',
            'unit_price.gt' => 'Giá bán phải lớn hơn giá vốn',
            'cost.integer' => 'Giá vốn phải là số nguyên',
            'cost.min' => 'Giá vốn phải lớn hơn 0',
            'publishing_year.integer' => 'Năm xuất bản phải là số nguyên',
            'publishing_year.min' => 'Năm xuất bản phải lớn hơn 0',
            'capacity.integer' => 'Dung tích phải là số nguyên',
            'capacity.min' => 'Dung tích phải lớn hơn 0',
            'images.*.file' => 'Ảnh phải là file',
            'images.*.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg, gif, svg',
        ]);

        try {
            // Kiểm tra xem tên sách đã tồn tại trong BookTitle chưa
            $productTitle = ProductTitle::firstOrCreate([
                'name' => $request->name,
                'author' => $request->author,
                'description' => $request->description,
                'product_type_id' => $request->product_type_id,
                'supplier_id' => $request->supplier_id,
            ]);

            // Thêm mới Book
            $product = Product::create([
                'quantity' => 0,
                'unit_price' => $request->unit_price,
                'cost' => $request->cost,
                'publishing_year' => $request->publishing_year,
                'capacity' => $request->capacity,
                'color' => $request->color,
                'product_title_id' => $productTitle->id
            ]);

            // Xử lý upload ảnh
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $image_name = $image->hashName();
                    $image->move(public_path('uploads/products'), $image_name);

                    // Thêm mới Image
                    Image::create([
                        'url' => 'uploads/products/' . $image_name,
                        'product_id' => $product->id,
                    ]);
                }
            }

            return redirect()->route('book.create')->with('success', 'Sản phẩm đã được thêm thành công.');
        } catch (\Exception $e) {
            Log::error('Lỗi: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Lỗi xảy ra, vui lòng thử lại.']);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $this->authorize('view', $product);

        $product->load('productTitle', 'productTitle.productType', 'productTitle.suppliers', 'images');
        return view('admin.book.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        $productTypes = ProductType::orderBy('id', 'asc')->select('id', 'name')->get();
        $suppliers = Supplier::orderBy('id', 'asc')->select('id', 'name')->get();
        return view('admin.book.edit', compact('productTypes', 'suppliers', 'product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'unit_price' => 'integer|min:1|gt:cost',
            'cost' => 'integer|min:1',
            'publishing_year' => 'integer|min:1',
            'capacity' => 'integer|min:1',
            'images.*' => 'file|mimes:jpeg,png,jpg,gif,svg',
        ], [
            'unit_price.integer' => 'Giá bán phải là số nguyên',
            'unit_price.min' => 'Giá bán phải lớn hơn 0',
            'unit_price.gt' => 'Giá bán phải lớn hơn giá vốn',
            'cost.integer' => 'Giá vốn phải là số nguyên',
            'cost.min' => 'Giá vốn phải lớn hơn 0',
            'publishing_year.integer' => 'Năm xuất bản phải là số nguyên',
            'publishing_year.min' => 'Năm xuất bản phải lớn hơn 0',
            'capacity.integer' => 'Dung tích phải là số nguyên',
            'capacity.min' => 'Dung tích phải lớn hơn 0',
            'images.*.file' => 'Ảnh phải là file',
            'images.*.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg, gif, svg',
        ]);

        try {
            // Cập nhật ProductTitle
            $productTitle = $product->productTitle;
            $productTitle->update([
                'name' => $request->name,
                'author' => $request->author,
                'description' => $request->description,
                'product_type_id' => $request->product_type_id,
                'supplier_id' => $request->supplier_id,
            ]);

            // Cập nhật Product
            $product->update([
                'unit_price' => $request->unit_price,
                'cost' => $request->cost,
                'publishing_year' => $request->publishing_year,
                'capacity' => $request->capacity,
                'color' => $request->color,
            ]);

            // Xử lý upload ảnh
            if ($request->hasFile('images')) {
                // Xóa ảnh cũ
                foreach ($product->images as $image) {
                    unlink(public_path($image->url));
                    $image->delete();
                }

                // Thêm ảnh mới
                foreach ($request->file('images') as $image) {
                    $image_name = $image->hashName();
                    $image->move(public_path('uploads/products'), $image_name);

                    // Thêm mới Image
                    Image::create([
                        'url' => 'uploads/products/' . $image_name,
                        'product_id' => $product->id,
                    ]);
                }
            }

            return redirect()->route('book.edit', ['book' => $product->id])->with('success', 'Sản phẩm đã được sửa thành công.');
        } catch (\Exception $e) {
            Log::error('Lỗi: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Lỗi xảy ra, vui lòng thử lại.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product = Product::findOrFail($product->id);
        $product->delete();

        ChangeLog::create([
            'table_name' => 'products',
            'row_id' => $product->id,
            'column_name' => null,
            'old_value' => json_encode($product->toArray()),
            'new_value' => null,
            'changed_by' => Auth::id(),
            'operation_type' => 'delete',
            'changed_at' => now(),
        ]);

        return redirect()->route('book.index')->with('success', 'Xóa sách và các ảnh liên quan thành công.');
    }

    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();

        ChangeLog::create([
            'table_name' => 'products',
            'row_id' => $product->id,
            'column_name' => null,
            'old_value' => null,
            'new_value' => json_encode($product->toArray()),
            'changed_by' => Auth::id(),
            'operation_type' => 'restore',
            'changed_at' => now(),
        ]);

        return redirect()->route('books.index')->with('success', 'Book restored successfully.');
    }
}