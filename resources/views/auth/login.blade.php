@extends('layouts.user')

@section('content')
<div class="container mt-4">
  <h3>Đăng nhập</h3>

  @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
  @if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">
      @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul></div>
  @endif

  <form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="mb-3">
      <label>Email</label>
      <input type="email" class="form-control" name="email" required>
    </div>
    <div class="mb-3">
      <label>Mật khẩu</label>
      <input type="password" class="form-control" name="password" required>
    </div>
    <button class="btn btn-primary">Đăng nhập</button>
  </form>
</div>
@endsection
