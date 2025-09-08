@extends('layouts.admin')

@section('title','Chi tiết người dùng')

@section('content')
<div class="container mt-4">
  <h3>👤 Thông tin người dùng</h3>

  <p><strong>ID:</strong> {{ $user->id }}</p>
  <p><strong>Họ tên:</strong> {{ $user->name }}</p>
  <p><strong>Email:</strong> {{ $user->email }}</p>
  <p><strong>Vai trò:</strong> {{ $user->role }}</p>
  <p><strong>Ngày tạo:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
  <p><strong>Cập nhật:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</p>

  <a href="{{ route('admin.users.edit',$user) }}" class="btn btn-warning">Sửa</a>
  <form action="{{ route('admin.users.destroy',$user) }}" method="POST" class="d-inline">
    @csrf
    @method('DELETE')
    <button class="btn btn-danger" onclick="return confirm('Xóa người dùng này?')">Xóa</button>
  </form>
  <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">⬅ Quay lại</a>
</div>
@endsection
