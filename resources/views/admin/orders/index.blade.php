@extends('layouts.admin')

@section('title','Quản lý đơn hàng')

@section('content')
<div class="container mt-4">
  <h3>📦 Tất cả đơn hàng</h3>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>#</th>
        <th>Người đặt</th>
        <th>Tổng tiền</th>
        <th>Phương thức</th>
        <th>Trạng thái</th>
        <th>Ngày đặt</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach($orders as $order)
      <tr>
        <td>{{ $order->id }}</td>
        <td>{{ $order->user->name ?? 'N/A' }} ({{ $order->user->email ?? '' }})</td>
        <td>{{ number_format($order->total_price) }} VNĐ</td>
        <td>{{ strtoupper($order->payment_method) }}</td>
        <td>{{ $order->status }}</td>
        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
        <td><a href="{{ route('admin.orders.show',$order) }}" class="btn btn-sm btn-info">Chi tiết</a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
