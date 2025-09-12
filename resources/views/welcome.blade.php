@extends('layouts.user')

@section('content')
<div class="container mt-4">
  <h3>Chào mừng tới Shop Váy Công Sở</h3>
  <p>Đây là trang chào mừng dành cho khách hàng.</p>

  <div class="mt-3">
    <a class="btn btn-primary" href="{{ route('user.products.index') }}">
      Xem sản phẩm
    </a>
    <a class="btn btn-outline-secondary" href="{{ route('user.categories.index') }}">
      Xem danh mục
    </a>
  </div>
</div>
@endsection
