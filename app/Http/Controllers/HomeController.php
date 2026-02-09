<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    //
    // public function index()
    // {
    //     // Lấy tất cả các loại sách (book types)
    //     $productTypes = DB::table('producttypes')
    //         ->select('producttypes.id as producttype_id', 'producttypes.name as producttype_name')
    //         ->get();

    //     $productTitles = [];

    //     foreach ($productTypes as $productType) {
    //         // Lấy thông tin sách cho mỗi loại sách, bao gồm giá, số lượng bán ra, và ảnh của sách có id nhỏ nhất
    //         $titles = DB::table('producttitles')
    //             ->join('products', 'producttitles.id', '=', 'products.product_title_id')
    //             ->leftJoin('order_details', 'products.id', '=', 'order_details.product_id')
    //             ->leftJoin('images', 'products.id', '=', 'images.product_id')
    //             ->select(
    //                 'producttitles.id as product_title_id',
    //                 'producttitles.name as product_title_name',
    //                 'producttitles.author',
    //                 'products.unit_price',
    //                 DB::raw('COALESCE(SUM(order_details.quantity), 0) as sold_quantity'),
    //                 DB::raw('MIN(images.url) as image_url') // Lấy ảnh có id nhỏ nhất
    //             )
    //             ->where('producttitles.product_type_id', $productType->producttype_id)
    //             ->groupBy(
    //                 'producttitles.id',
    //                 'producttitles.name',
    //                 'producttitles.author',
    //                 'products.unit_price'
    //             )
    //             ->orderBy('producttitles.id', 'asc')
    //             ->limit(10) // Lấy tối đa 10 sách cho mỗi loại sách
    //             ->get();

    //         // Thêm các book titles vào mảng theo tên loại sách
    //         $productTitles[$productType->producttype_name] = $titles;
    //     }

    //     // Trả về view với dữ liệu bookTitles
    //     return view('home.index', compact('productTitles'));
    // }

    public function index()
    {
        // Lấy tất cả các loại sách (book types)
        $productTypes = DB::table('producttypes')->get();

        $productTitles = [];

        foreach ($productTypes as $productType) {
            $titles = DB::table('producttitles')
                ->join('products', 'producttitles.id', '=', 'products.product_title_id')
                ->leftJoin('order_details', 'products.id', '=', 'order_details.product_id')
                ->join('images', 'products.id', '=', 'images.product_id')
                ->where('producttitles.product_type_id', $productType->id)
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
                )
                ->orderBy('producttitles.name', 'asc')
                ->limit(10) // Lấy tối đa 10 sách cho mỗi loại sách
                ->get();

            $productTitles[$productType->name] = $titles;
        }

        return view('home.index', compact('productTitles'));
    }
}