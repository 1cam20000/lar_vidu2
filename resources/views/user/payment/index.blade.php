@extends('layouts.user')

@section('title','Thanh toán')

@section('content')
<div class="container mt-4">
  <h3>🛒 Thanh toán</h3>

  @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

  {{-- Danh sách sản phẩm trong giỏ --}}
  <table class="table table-bordered mt-3">
    <thead>
      <tr>
        <th>Sản phẩm</th>
        <th>Số lượng</th>
        <th>Giá</th>
        <th>Thành tiền</th>
      </tr>
    </thead>
    <tbody>
      @foreach($cart->items as $item)
      <tr>
        <td>{{ $item->product->name }}</td>
        <td>{{ $item->quantity }}</td>
        <td>{{ number_format($item->product->price) }} VNĐ</td>
        <td>{{ number_format($item->product->price * $item->quantity) }} VNĐ</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <h4 class="text-end">Tổng tiền: {{ number_format($cart->total()) }} VNĐ</h4>

  {{-- Form nhập thông tin người nhận & chọn phương thức --}}
  <form method="POST" action="{{ route('user.payment.store') }}">
    @csrf
    <div class="mb-3">
      <label>Họ tên</label>
      <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Địa chỉ</label>
      <input type="text" name="address" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Số điện thoại</label>
      <input type="text" name="phone" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Phương thức thanh toán</label>
      <select name="payment_method" class="form-select" required>
        <option value="cod">Thanh toán khi nhận hàng (COD)</option>
        <option value="momo">Thanh toán qua MoMo</option>
      </select>
    </div>

    <button type="submit" class="btn btn-success">Đặt hàng</button>
    <a href="{{ route('user.cart.index') }}" class="btn btn-secondary">⬅ Quay lại giỏ hàng</a>
  </form>
</div>
@endsection
