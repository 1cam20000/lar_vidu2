@extends('layouts.admin') {{-- hoặc layouts.user nếu bạn đã có --}}

@section('content')
<div class="container mt-4">
  <h3>Welcome to Shop Váy</h3>
  <p>Trang chào mừng. Từ đây bạn có thể vào quản trị Categories.</p>
  <a class="btn btn-primary" href="{{ route('admin.categories.index') }}">Quản trị Categories</a>
</div>
@endsection