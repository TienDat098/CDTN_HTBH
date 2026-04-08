<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
    public function index(){
        $totalUsers = User::count();
        $totalCategories = Category::count();
        $totalBrands = Brand::count();
        $totalProducts = Product::count();
        $totalStock = ProductStock::sum('quantity');

        //  Tổng doanh thu 
        $totalRevenue = Order::sum('total_price');

        //  Doanh thu hôm nay 
        $todayRevenue = Order::whereDate('created_at', today())
                        ->sum('total_price');

        //  Doanh thu 7 ngày 
        $revenueByDay = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_price) as total')
            )
            ->where('created_at','>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        //  Doanh thu theo tháng 
        $revenueByMonth = Order::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_price) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        //  Top sản phẩm bán chạy
        $topProducts = DB::table('order_items')
            ->join('products','products.id','=','order_items.product_id')
            ->select(
                'products.name',
                DB::raw('SUM(order_items.quantity) as total')
            )
            ->groupBy('products.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        //ton kho thap
         $lowStockProducts = ProductStock::where('quantity','<',10)
        ->join('products','product_stocks.product_id','=','products.id')
        ->select('products.name','product_stocks.quantity')
        ->get();
        

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalCategories',
            'totalBrands',
            'totalProducts',
            'totalStock',
            'totalRevenue',
            'todayRevenue',
            'revenueByDay',
            'revenueByMonth',
            'topProducts',
            'lowStockProducts'
        ));

    }

       public function revenueReport(Request $request)
    {
        $type = $request->input('type', 'day'); 
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $query = Order::where('status', 'completed');

        // Lọc theo khoảng thời gian
        if ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        // Nhóm dữ liệu tùy theo Loại báo cáo
        if ($type == 'month') {
            // Nhóm theo Năm-Tháng (VD: 2026-04) để sort chuẩn xác nhất
            $query->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as date_group'),
                DB::raw('COUNT(id) as total_orders'),
                DB::raw('SUM(final_total) as total_revenue'), // Chú ý: Nên dùng final_total thay vì total_price
                DB::raw('MIN(final_total) as min_order'),
                DB::raw('MAX(final_total) as max_order')
            )->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
             ->orderBy('date_group', 'desc');
             
        } elseif ($type == 'year') {
            $query->select(
                DB::raw('YEAR(created_at) as date_group'),
                DB::raw('COUNT(id) as total_orders'),
                DB::raw('SUM(final_total) as total_revenue'),
                DB::raw('MIN(final_total) as min_order'),
                DB::raw('MAX(final_total) as max_order')
            )->groupBy(DB::raw('YEAR(created_at)'))
             ->orderBy('date_group', 'desc');
             
        } else {
            // Mặc định là nhóm theo Ngày
            $query->select(
                DB::raw('DATE(created_at) as date_group'),
                DB::raw('COUNT(id) as total_orders'),
                DB::raw('SUM(final_total) as total_revenue'),
                DB::raw('MIN(final_total) as min_order'),
                DB::raw('MAX(final_total) as max_order')
            )->groupBy(DB::raw('DATE(created_at)'))
             ->orderBy('date_group', 'desc');
        }

        $reports = $query->get();

        return view('admin.reports.revenue', compact('reports', 'type', 'fromDate', 'toDate'));
    }
       
}
