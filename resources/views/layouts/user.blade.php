<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','MyShop - Váy công sở')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="{{ route('welcome') }}">MyShop</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUser">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarUser">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="{{ route('user.products.index') }}">Sản phẩm</a></li>
        {{-- <li class="nav-item"><a class="nav-link" href="{{ route('user.categories.index') }}">Danh mục</a></li> --}}
        @auth
          <li class="nav-item"><a class="nav-link" href="{{ route('user.cart.index') }}">Giỏ hàng</a></li>
        @endauth
        <li class="nav-item">
          <a class="nav-link" href="{{ route('user.orders.index') }}">Lịch sử đơn hàng</a>
        </li>
      </ul>
      <ul class="navbar-nav ms-auto">
        @auth
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
              {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              @if(Auth::user()->role === 'admin')
                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Quản trị</a></li>
                <li><hr class="dropdown-divider"></li>
              @endif
              <li>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="dropdown-item text-danger">Đăng xuất</button>
                </form>
              </li>
            </ul>
          </li>
        @else
          <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Đăng nhập</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Đăng ký</a></li>
        @endauth
      </ul>
    </div>
  </div>
</nav>

<main class="container py-4">
  @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
