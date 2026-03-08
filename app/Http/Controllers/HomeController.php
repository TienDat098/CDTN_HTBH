<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::with(['images', 'stock'])
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->paginate(8);

        return view('home', compact('products'));
    }
}