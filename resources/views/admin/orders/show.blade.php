@extends('layouts.admin')

@section('title','Chi tiết đơn hàng')

@section('content')
<div class="container mt-4">
  <h3>Đơn hàng #{{ $order->id }}</h3>
  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

  <p><strong>Người đặt:</strong> {{ $order->user->name ?? '' }} ({{ $order->user->email ?? '' }})</p>
  <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
  <p><strong>SĐT:</strong> {{ $order->phone }}</p>
  <p><strong>Phương thức:</strong> {{ strtoupper($order->payment_method) }}</p>
  <p><strong>Trạng thái hiện tại:</strong> {{ $order->status }}</p>
  <p><strong>Tổng tiền:</strong> {{ number_format($order->total_price) }} VNĐ</p>

  {{-- Form cập nhật trạng thái --}}
  <form method="POST" action="{{ route('admin.orders.update',$order) }}" class="mb-3">
    @csrf
    @method('PUT')
    <div class="row g-2">
      <div class="col-md-6">
        <select name="status" class="form-select">
          <option value="chờ thanh toán" @selected($order->status=='chờ thanh toán')>Chờ thanh toán</option>
          <option value="đã đặt (COD)" @selected($order->status=='đã đặt (COD)')>Đã đặt (COD)</option>
          <option value="đã thanh toán (MoMo)" @selected($order->status=='đã thanh toán (MoMo)')>Đã thanh toán (MoMo)</option>
          <option value="đang giao" @selected($order->status=='đang giao')>Đang giao</option>
          <option value="hoàn tất" @selected($order->status=='hoàn tất')>Hoàn tất</option>
          <option value="đã hủy" @selected($order->status=='đã hủy')>Đã hủy</option>
        </select>
      </div>
      <div class="col-md-3">
        <button type="submit" class="btn btn-primary">Cập nhật</button>
      </div>
    </div>
  </form>

  <h5>Sản phẩm:</h5>
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

  <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">⬅ Quay lại</a>
</div>
@endsection
