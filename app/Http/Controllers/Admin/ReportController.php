<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Doanh thu theo ngày
        $dailyRevenue = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_price) as total')
        )
            ->where('status', '!=', 'đã hủy')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get();

        // Doanh thu theo tháng
        $monthlyRevenue = Order::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total_price) as total')
        )
            ->where('status', '!=', 'đã hủy')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get();

        // Doanh thu theo trạng thái giao hàng
        $shippingStats = Order::select('shipping_status', DB::raw('COUNT(*) as total'))
            ->groupBy('shipping_status')
            ->get();

        return view('admin.reports.index', compact('dailyRevenue', 'monthlyRevenue', 'shippingStats'));
    }
}
