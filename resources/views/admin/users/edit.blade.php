@extends('layouts.admin')

@section('title','Sửa người dùng')

@section('content')
<div class="container mt-4">
  <h3>✏️ Sửa người dùng</h3>

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.users.update',$user) }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label class="form-label">Họ tên</label>
      <input type="text" name="name" class="form-control" value="{{ old('name',$user->name) }}" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" value="{{ old('email',$user->email) }}" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Mật khẩu (để trống nếu không đổi)</label>
      <input type="password" name="password" class="form-control">
    </div>

    <div class="mb-3">
      <label class="form-label">Xác nhận mật khẩu</label>
      <input type="password" name="password_confirmation" class="form-control">
    </div>

    <div class="mb-3">
      <label class="form-label">Vai trò</label>
      <select name="role" class="form-select" required>
        <option value="user" @selected(old('role',$user->role)=='user')>User</option>
        <option value="admin" @selected(old('role',$user->role)=='admin')>Admin</option>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Cập nhật</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Hủy</a>
  </form>
</div>
@endsection
