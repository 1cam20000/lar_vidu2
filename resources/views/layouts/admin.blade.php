<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Admin • MyShop')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  @stack('styles')
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="{{ route('admin.dashboard') }}">MyShop Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div id="adminNav" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
             href="{{ route('admin.dashboard') }}">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
             href="{{ route('admin.categories.index') }}">Categories</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
             href="{{ route('admin.products.index') }}">Products</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.orders.index') }}">Đơn hàng</a>
        </li>
      </ul>

      <ul class="navbar-nav ms-auto">
        <li class="nav-item d-none d-lg-block">
          <a class="nav-link" href="{{ route('welcome') }}">Xem cửa hàng</a>
        </li>

        @auth
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
              {{ Auth::user()->name ?? 'Admin' }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="{{ route('welcome') }}">Về trang khách</a></li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <form action="{{ route('logout') }}" method="POST" class="px-3">
                  @csrf
                  <button type="submit" class="btn btn-danger w-100">Đăng xuất</button>
                </form>
              </li>
            </ul>
          </li>
        @endauth
      </ul>
    </div>
  </div>
</nav>

<main class="container py-4">
  {{-- Flash messages --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @yield('content')
</main>

<footer class="border-top py-3 bg-white">
  <div class="container small text-muted">
    © {{ date('Y') }} MyShop Admin • Váy công sở
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
