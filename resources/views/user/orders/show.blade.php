@extends('layouts.user')

@section('title','Chi tiết đơn hàng')

@section('content')
<div class="container mt-4">
  <h3>Đơn hàng #{{ $order->id }}</h3>

  <p><strong>Tên:</strong> {{ $order->name }}</p>
  <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
  <p><strong>SĐT:</strong> {{ $order->phone }}</p>
  <p><strong>Phương thức:</strong> {{ strtoupper($order->payment_method) }}</p>
  <p><strong>Trạng thái:</strong> {{ $order->status }}</p>
  <p><strong>Tổng tiền:</strong> {{ number_format($order->total_price) }} VNĐ</p>

  <h5>Chi tiết sản phẩm:</h5>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Sản phẩm</th>
        <th>SL</th>
        <th>Giá</th>
        <th>Thành tiền</th>
      </tr>
    </thead>
    <tbody>
      @foreach($order->items as $item)
      <tr>
        <td>{{ $item->product->name }}</td>
        <td>{{ $item->quantity }}</td>
        <td>{{ number_format($item->price) }} VNĐ</td>
        <td>{{ number_format($item->price * $item->quantity) }} VNĐ</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <a href="{{ route('user.orders.index') }}" class="btn btn-secondary">⬅ Quay lại</a>
</div>
@endsection
