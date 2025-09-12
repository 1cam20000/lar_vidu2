@extends('layouts.user')

@section('title',$product->name)

@section('content')
<div class="row">
  <div class="col-md-5">
    @if($product->image)
      <img src="{{ asset($product->image) }}" class="img-fluid rounded" alt="{{ $product->name }}">
    @endif
  </div>
  <div class="col-md-7">
    <h3>{{ $product->name }}</h3>
    <p class="text-muted">Giá: {{ number_format($product->price) }} VNĐ</p>
    <p>{{ $product->description }}</p>
    @if($product->features)
      <ul>
        @foreach(is_array($product->features) ? $product->features : json_decode($product->features,true) as $f)
          <li>{{ $f }}</li>
        @endforeach
      </ul>
    @endif
    @if(Auth::check())
      <form action="{{ route('user.cart.add',$product) }}" method="POST" class="mt-3">
        @csrf
        <div class="input-group" style="max-width:200px;">
          <input type="number" name="quantity" class="form-control" value="1" min="1">
          <button class="btn btn-success">Thêm vào giỏ</button>
        </div>
      </form>
    @else
      <p><a href="{{ route('login') }}">Đăng nhập</a> để mua hàng</p>
    @endif
    <a href="{{ route('user.products.index') }}" class="btn btn-secondary">Quay lại</a>
  </div>
</div>
@endsection
