<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
class CategoryController extends Controller
{
    public function show($slug)
{
   
        $category = Category::where('slug', $slug)->where('status', 1)->firstOrFail();

        
        $products = $category->products()
            ->with(['brand', 'stock']) 
            ->where('status', 1)
            ->orderBy('id', 'desc') 
            ->paginate(12); 

        return view('category.show', compact('category', 'products'));
}
}
