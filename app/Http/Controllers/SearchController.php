<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        
        // Tìm kiếm sản phẩm phân trang
        $products = Product::where('name', 'like', "%$keyword%")
            ->paginate(12);

        return view('search_results', compact('products', 'keyword'));
    }

    public function suggest(Request $request)
    {
        $keyword = $request->keyword;

        $products = Product::where('name', 'like', "%$keyword%")
            ->select('id', 'name', 'sell_price as price', 'slug', 'image') 
            ->limit(5)
            ->get()
            ->map(function($product) {
                
                $product->thumbnail = $product->image ? asset('storage/' . $product->image) : asset('images/no-image.png'); 
                return $product;
            });

        return response()->json($products);
    }
}