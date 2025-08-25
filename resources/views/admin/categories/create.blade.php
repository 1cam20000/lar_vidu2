@extends('layouts.admin')

@section('content')
<div class="container mt-4">
  <h3>Create Category</h3>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
  @endif

  <form action="{{ route('admin.categories.store') }}" method="POST">
    @csrf
    <div class="mb-3">
      <label class="form-label">Name</label>
      <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Description (optional)</label>
      <textarea name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
    </div>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Back</a>
    <button class="btn btn-primary">Submit</button>
  </form>
</div>
@endsection
