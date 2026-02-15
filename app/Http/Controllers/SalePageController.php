<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SalePageController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function search(Request $request)
    {
        $query = $request->input('query');
        $sort_by = $request->input('sort_by', 'price_asc');

        // Tìm kiếm các tiêu đề sách dựa trên từ khóa
        $titles = DB::table('producttitles')
            ->join('products', 'producttitles.id', '=', 'products.product_title_id')
            ->leftJoin('order_details', 'products.id', '=', 'order_details.product_id')
            ->join('images', 'products.id', '=', 'images.product_id')
            ->where('producttitles.name', 'LIKE', "%{$query}%")
            ->select(
                'producttitles.id',
                'producttitles.name',
                'producttitles.author',
                DB::raw('MIN(products.unit_price) as unit_price'),
                DB::raw('COALESCE(SUM(order_details.quantity), 0) as sold_quantity'),
                DB::raw('MIN(images.url) as image_url')
            )
            ->groupBy(
                'producttitles.id',
                'producttitles.name',
                'producttitles.author',
                'producttitles.product_type_id'
            );

        // Áp dụng sắp xếp
        switch ($sort_by) {
            case 'price_asc':
                $titles->orderBy('unit_price', 'asc');
                break;
            case 'price_desc':
                $titles->orderBy('unit_price', 'desc');
                break;
            case 'sold_desc':
                $titles->orderBy('sold_quantity', 'desc');
                break;
        }

        $titles = $titles->get();

        return view('TimKiemSP', compact('titles', 'query', 'sort_by'));
    }

    public function showProductDetails($product_title_id)
    {
        $producttitle = DB::table('producttitles')->where('id', $product_title_id)->first();

        $products = DB::table('products')
            ->where('product_title_id', $product_title_id)
            ->select(
                'id',
                'publishing_year',
                'unit_price',
                'cost',
                'color',
                'quantity',
                'capacity'
            )
            ->orderBy('publishing_year', 'asc')
            ->get();

        $images = DB::table('images')
            ->whereIn('product_id', $products->pluck('id'))
            ->get();

        $review_score = DB::table('reviews')
            ->whereIn('product_id', $products->pluck('id')->toArray())
            ->select(
                DB::raw('AVG(score) as review_score'),
                DB::raw('COUNT(*) as review_count')
            )
            ->first();

        $customer_reviews = DB::table('reviews')
            ->join('orders', 'reviews.order_id', '=', 'orders.id')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->whereIn('product_id', $products->pluck('id')->toArray())
            ->select(
                'customers.name as customer_name',
                'reviews.score as review_score',
                'reviews.description as review_comment', // Đảm bảo rằng tên cột đúng
                'reviews.created_at as review_date'
            )
            ->get();

        return view('ChiTietSanPham', compact('producttitle', 'products', 'images', 'review_score', 'customer_reviews'));
    }

    public function showProductByType(Request $request, $producttype_id)
    {
        // Lấy tên thể loại (product type name)
        $producttypeName = DB::table('producttypes')
            ->select('name')
            ->where('id', $producttype_id)
            ->first()
            ->name ?? 'Danh mục sản phẩm';

        $sort_by = $request->input('sort_by', 'name_asc');

        // Query lấy danh sách sản phẩm theo producttype_id (giống hệt logic home page)
        $titles = DB::table('producttitles')
            ->join('products', 'producttitles.id', '=', 'products.product_title_id')
            ->leftJoin('order_details', 'products.id', '=', 'order_details.product_id')
            ->join('images', 'products.id', '=', 'images.product_id')
            ->where('producttitles.product_type_id', $producttype_id)
            ->select(
                'producttitles.id',
                'producttitles.name',
                DB::raw('MIN(products.unit_price) as unit_price'),
                DB::raw('COALESCE(SUM(order_details.quantity), 0) as sold_quantity'),
                DB::raw('MIN(images.url) as image_url')
            )
            ->groupBy(
                'producttitles.id',
                'producttitles.name',
                'producttitles.product_type_id'
            );

        // Áp dụng sắp xếp
        switch ($sort_by) {
            case 'price_asc':
                $titles->orderBy('unit_price', 'asc');
                break;
            case 'price_desc':
                $titles->orderBy('unit_price', 'desc');
                break;
            case 'sold_desc':
                $titles->orderBy('sold_quantity', 'desc');
                break;
            case 'name_asc':
            default:
                $titles->orderBy('producttitles.name', 'asc');
                break;
        };

        $titles = $titles->get();

        return view('VanHoc_DanhMuc', compact('producttypeName', 'titles', 'sort_by', 'producttype_id'));
    }

    // app/Http/Controllers/SalePageController.php (only the method)

}