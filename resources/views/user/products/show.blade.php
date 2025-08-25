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
    <a href="{{ route('user.products.index') }}" class="btn btn-secondary">Quay lại</a>
  </div>
</div>
@endsection
