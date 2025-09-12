@extends('layouts.admin')

@section('content')
<div class="container mt-4">
  <h3>Bảng điều khiển (Admin)</h3>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="row g-3">
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title mb-2">Danh mục</h5>
          <p class="text-muted small mb-3">Quản lý các loại váy công sở.</p>
          <a href="{{ route('admin.categories.index') }}" class="btn btn-primary">Tới Categories</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title mb-2">Sản phẩm</h5>
          <p class="text-muted small mb-3">Quản lý danh sách sản phẩm.</p>
          <a href="{{ route('admin.products.index') }}" class="btn btn-primary">Tới Products</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
