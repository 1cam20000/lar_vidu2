@extends('layouts.admin')

@section('content')
<div class="container mt-4">
  <h3>Show Category</h3>

  <div class="card">
    <div class="card-body">
      <p><strong>ID:</strong> {{ $category->id }}</p>
      <p><strong>Name:</strong> {{ $category->name }}</p>
      @if($category->description)
        <p><strong>Description:</strong> {{ $category->description }}</p>
      @endif
      <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Back</a>
      <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">Edit</a>
    </div>
  </div>
</div>
@endsection
