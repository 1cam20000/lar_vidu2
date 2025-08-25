@extends('layouts.user')

@section('content')
<div class="container mt-4">
  <h3>Đăng ký</h3>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">
      @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul></div>
  @endif

  <form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="mb-3">
      <label>Họ tên</label>
      <input class="form-control" name="name" value="{{ old('name') }}" required>
    </div>
    <div class="mb-3">
      <label>Email</label>
      <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
    </div>
    <div class="mb-3">
      <label>Mật khẩu</label>
      <input type="password" class="form-control" name="password" required>
    </div>
    <div class="mb-3">
      <label>Xác nhận mật khẩu</label>
      <input type="password" class="form-control" name="password_confirmation" required>
    </div>
    <button class="btn btn-success">Đăng ký</button>
  </form>
</div>
@endsection
