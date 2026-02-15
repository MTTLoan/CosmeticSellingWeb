<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy tất cả các loại sách (book types)
        $productTypes = DB::table('producttypes')->get();

        // Nhóm producttypes theo category
        $categories = $productTypes->groupBy('category');

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

            $productTitles[] = [
                'id' => $productType->id,  // Thêm ID
                'name' => $productType->name,  // Subtype name
                'titles' => $titles
            ];
        }

        return view('home.index', compact('productTitles', 'categories'));
    }
}