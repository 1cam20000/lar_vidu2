@extends('layouts.user')

@section('title','Giỏ hàng')

@section('content')
<div class="container mt-4">
  <h3>Giỏ hàng của bạn</h3>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if($cart->items->isEmpty())
    <p>Chưa có sản phẩm nào trong giỏ.</p>
    <a href="{{ route('user.products.index') }}" class="btn btn-primary">
      Bắt đầu mua hàng
    </a>
  @else
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Sản phẩm</th>
          <th>Giá</th>
          <th>Số lượng</th>
          <th>Thành tiền</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($cart->items as $item)
          <tr>
            <td>{{ $item->product->name }}</td>
            <td>{{ number_format($item->product->price) }} VNĐ</td>
            <td>
              <form method="POST" action="{{ route('user.cart.update',$item) }}">
                @csrf
                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" style="width:70px">
                <button class="btn btn-sm btn-primary">Cập nhật</button>
              </form>
            </td>
            <td>{{ number_format($item->product->price * $item->quantity) }} VNĐ</td>
            <td>
              <form method="POST" action="{{ route('user.cart.remove',$item) }}">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger">Xóa</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <h4>Tổng tiền: {{ number_format($cart->total()) }} VNĐ</h4>

    <div class="d-flex gap-2">
      <form method="POST" action="{{ route('user.cart.clear') }}">
        @csrf
        @method('DELETE')
        <button class="btn btn-warning">Xóa toàn bộ giỏ</button>
      </form>

      <a href="{{ route('user.products.index') }}" class="btn btn-success">
        Tiếp tục mua hàng
      </a>
    </div>
  @endif
</div>
@endsection
