@extends('layouts.admin')

@section('title','Quản lý người dùng')

@section('content')
<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Users</h3>
    <a href="{{ route('admin.users.create') }}" class="btn btn-success">+ New User</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table table-bordered align-middle">
    <thead class="table-light">
      <tr>
        <th style="width:80px">ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th style="width:200px">Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse($users as $u)
      <tr>
        <td>{{ $u->id }}</td>
        <td><strong>{{ $u->name }}</strong></td>
        <td>{{ $u->email }}</td>
        <td>{{ $u->role }}</td>
        <td>
          <a href="{{ route('admin.users.show', $u) }}" class="btn btn-sm btn-info">Show</a>
          <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-warning">Edit</a>
          <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa người dùng này?');">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-danger">Delete</button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="5" class="text-center text-muted">Chưa có người dùng.</td></tr>
      @endforelse
    </tbody>
  </table>

  <div class="d-flex justify-content-end">
    {{ $users->links() }}
  </div>
</div>
@endsection
