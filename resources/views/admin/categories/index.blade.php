@extends('layouts.admin')

@section('content')
<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Categories</h3>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-success">+ New Category</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table table-bordered">
    <thead class="table-light">
      <tr>
        <th style="width: 80px">ID</th>
        <th>Name</th>
        <th style="width: 200px">Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse($categories as $category)
        <tr>
          <td>{{ $category->id }}</td>
          <td>
            <strong>{{ $category->name }}</strong>
            @if($category->description)
              <div class="text-muted small">{{ $category->description }}</div>
            @endif
          </td>
          <td>
            <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-sm btn-info">Show</a>
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning">Edit</a>
            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Xoá danh mục này?');">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-danger">Delete</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="3" class="text-center text-muted">Chưa có danh mục.</td></tr>
      @endforelse
    </tbody>
  </table>

  <div class="d-flex justify-content-end">
    {{ $categories->links() }}
  </div>
</div>
@endsection
