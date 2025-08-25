@extends('layouts.user')

@section('title','Đơn hàng của tôi')

@section('content')
<div class="container mt-4">
  <h3>📦 Đơn hàng của tôi</h3>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

  @if($orders->isEmpty())
    <p>Bạn chưa có đơn hàng nào.</p>
    <a href="{{ route('user.products.index') }}" class="btn btn-primary">🛍 Bắt đầu mua hàng</a>
  @else
    <table class="table table-bordered mt-3">
      <thead>
        <tr>
          <th>#</th>
          <th>Ngày đặt</th>
          <th>Tổng tiền</th>
          <th>Thanh toán</th>
          <th>Trạng thái</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($orders as $order)
        <tr>
          <td>{{ $order->id }}</td>
          <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
          <td>{{ number_format($order->total_price) }} VNĐ</td>
          <td>{{ strtoupper($order->payment_method) }}</td>
          <td>{{ $order->status }}</td>
          <td><a href="{{ route('user.orders.show',$order) }}" class="btn btn-sm btn-info">Chi tiết</a></td>
        </tr>
        @endforeach
      </tbody>
    </table>
  @endif
</div>
@endsection
