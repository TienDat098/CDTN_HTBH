<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductStock;
class DashboardController extends Controller
{
    public function index(){
        $totalUsers = User::count();
        $totalCategories = Category::count();
        $totalBrands = Brand::count();
        $totalProducts = Product::count();
        $totalStock = ProductStock::sum('quantity');

        return view('admin.dashboard', compact('totalUsers', 'totalCategories', 'totalBrands', 'totalProducts', 'totalStock')); 

    }
}
