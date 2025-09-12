@extends('layouts.user')

@section('content')
<div class="container mt-5">
  <h3>Xác thực email</h3>
  <p>Vui lòng kiểm tra hộp thư để xác thực tài khoản trước khi tiếp tục.</p>

  @if (session('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
  @endif
  @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <form method="POST" action="{{ route('verification.send') }}" class="mt-3">
    @csrf
    <button class="btn btn-primary" type="submit">Gửi lại email xác thực</button>
  </form>

  <div class="mt-3">
    <a href="{{ route('logout') }}"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
      Đăng xuất
    </a>
    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="d-none">@csrf</form>
  </div>
</div>
@endsection
