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
      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title mb-2">Đơn hàng</h5>
            <p class="text-muted small mb-3">Quản lý các đơn hàng của khách.</p>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">Tới Orders</a>
          </div>
        </div>
      </div>
        <div class="col-md-4">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="card-title mb-2">Báo cáo</h5>
              <p class="text-muted small mb-3">Xem các báo cáo thống kê.</p>
              <a href="{{ route('admin.reports.index') }}" class="btn btn-primary">Tới Reports</a>
            </div>
          </div>
        </div>
          <div class="col-md-4">
            <div class="card shadow-sm">
              <div class="card-body">
                <h5 class="card-title mb-2">Người dùng</h5>
                <p class="text-muted small mb-3">Quản lý tài khoản người dùng.</p>
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Tới Users</a>
              </div>
            </div>
          </div>
  </div>
</div>
@endsection
