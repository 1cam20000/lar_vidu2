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
    public function charts()
    {
        $paid = ['đã thanh toán (MoMo)', 'đã đặt (COD)', 'hoàn tất'];

        /* 1) Doanh thu theo danh mục */
        $byCat = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->whereIn('orders.status', $paid)
            ->selectRaw('COALESCE(categories.name, CONCAT("Danh mục #", products.category_id)) AS name')
            ->selectRaw('SUM(order_items.price * order_items.quantity) AS revenue')
            ->groupBy('name')
            ->orderByDesc('revenue')
            ->get();

        $catLabels  = $byCat->pluck('name')->toArray();
        $catRevenue = $byCat->pluck('revenue')->map(fn($v) => (float)$v)->toArray();

        /* 2) Doanh thu theo ngày (30 ngày gần nhất) */
        $startDay = now()->subDays(29)->startOfDay();
        $byDate = \App\Models\Order::whereIn('status', $paid)
            ->where('created_at', '>=', $startDay)
            ->selectRaw('DATE(created_at) d, SUM(total_price) revenue')
            ->groupBy('d')->orderBy('d')->get()->keyBy('d');

        $revDateLabels = [];
        $revDateData = [];
        for ($i = 0; $i < 30; $i++) {
            $d = $startDay->copy()->addDays($i)->toDateString();
            $revDateLabels[] = $d;
            $revDateData[] = (float)($byDate[$d]->revenue ?? 0);
        }

        /* 3) Doanh thu theo tháng (12 tháng gần nhất) */
        $startMonth = now()->subMonths(11)->startOfMonth();
        $byMonth = \App\Models\Order::whereIn('status', $paid)
            ->where('created_at', '>=', $startMonth)
            ->selectRaw("DATE_FORMAT(created_at,'%Y-%m') ym, SUM(total_price) revenue")
            ->groupBy('ym')->orderBy('ym')->get()->keyBy('ym');

        $revMonthLabels = [];
        $revMonthData = [];
        for ($i = 0; $i < 12; $i++) {
            $m = $startMonth->copy()->addMonths($i);
            $key = $m->format('Y-m');
            $revMonthLabels[] = $m->format('m/Y');
            $revMonthData[] = (float)($byMonth[$key]->revenue ?? 0);
        }

        /* 4) Doanh thu theo năm */
        $byYear = \App\Models\Order::whereIn('status', $paid)
            ->selectRaw('YEAR(created_at) y, SUM(total_price) revenue')
            ->groupBy('y')->orderBy('y')->get();

        $revYearLabels = $byYear->pluck('y')->toArray();
        $revYearData = $byYear->pluck('revenue')->map(fn($v) => (float)$v)->toArray();

        /* 5) Doanh thu theo phương thức thanh toán */
        $paymentMethodLabels = ['MoMo', 'COD'];
        $paymentMethodRevenue = [
            (float)\App\Models\Order::where('payment_method', 'MoMo')
                ->whereIn('status', ['đã thanh toán (MoMo)', 'hoàn tất'])
                ->sum('total_price'),
            (float)\App\Models\Order::where('payment_method', 'COD')
                ->whereIn('status', ['đã đặt (COD)', 'hoàn tất'])
                ->sum('total_price'),
        ];

        return view('admin.reports.charts', compact(
            'catLabels',
            'catRevenue',
            'revDateLabels',
            'revDateData',
            'revMonthLabels',
            'revMonthData',
            'revYearLabels',
            'revYearData',
            'paymentMethodLabels',
            'paymentMethodRevenue'
        ));
    }
}
