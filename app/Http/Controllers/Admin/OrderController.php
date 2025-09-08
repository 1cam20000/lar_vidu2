<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        // Lấy danh sách đơn hàng kèm user + items
        $orders = Order::with(['items.product', 'user'])->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'user']);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status'          => 'nullable|string|max:50',
            'shipping_status' => 'nullable|in:not_shipped,packaged,shipping,completed,cancelled',
        ]);

        if ($request->filled('status')) {
            $order->status = $request->status;
        }
        if ($request->filled('shipping_status')) {
            $order->shipping_status = $request->shipping_status;
        }

        $order->save();

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
    }
}
