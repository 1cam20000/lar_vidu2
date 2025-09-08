@extends('layouts.admin')

@section('title','Chi tiết người dùng')

@section('content')
<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>User Detail</h3>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back</a>
  </div>

  <div class="card p-4 shadow-sm mb-3">
    <div class="row mb-2">
      <div class="col-md-3 text-muted">ID:</div>
      <div class="col-md-9">{{ $user->id }}</div>
    </div>
    <div class="row mb-2">
      <div class="col-md-3 text-muted">Name:</div>
      <div class="col-md-9">{{ $user->name }}</div>
    </div>
    <div class="row mb-2">
      <div class="col-md-3 text-muted">Email:</div>
      <div class="col-md-9">{{ $user->email }}</div>
    </div>
    <div class="row mb-2">
      <div class="col-md-3 text-muted">Role:</div>
      <div class="col-md-9">{{ $user->role }}</div>
    </div>
    <div class="row mb-2">
      <div class="col-md-3 text-muted">Created at:</div>
      <div class="col-md-9">{{ $user->created_at->format('d/m/Y H:i') }}</div>
    </div>
    <div class="row mb-2">
      <div class="col-md-3 text-muted">Updated at:</div>
      <div class="col-md-9">{{ $user->updated_at->format('d/m/Y H:i') }}</div>
    </div>
  </div>

  <a href="{{ route('admin.users.edit',$user) }}" class="btn btn-warning">Edit</a>
  <form action="{{ route('admin.users.destroy',$user) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa người dùng này?');">
    @csrf
    @method('DELETE')
    <button class="btn btn-danger">Delete</button>
  </form>
</div>
@endsection
