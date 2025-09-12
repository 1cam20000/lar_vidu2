@extends('layouts.admin')

@section('title','Quản lý người dùng')

@section('content')
<div class="container mt-4">
  <h3>👥 Danh sách người dùng</h3>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">➕ Thêm mới</a>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>#</th>
        <th>Tên</th>
        <th>Email</th>
        <th>Vai trò</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach($users as $u)
      <tr>
        <td>{{ $u->id }}</td>
        <td>{{ $u->name }}</td>
        <td>{{ $u->email }}</td>
        <td>{{ $u->role }}</td>
        <td>
          <a href="{{ route('admin.users.edit',$u) }}" class="btn btn-sm btn-warning">Sửa</a>
          <form action="{{ route('admin.users.destroy',$u) }}" method="POST" class="d-inline">
            @csrf @method('DELETE')
            <button onclick="return confirm('Xóa người dùng này?')" class="btn btn-sm btn-danger">Xóa</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  {{ $users->links() }}
</div>
@endsection
