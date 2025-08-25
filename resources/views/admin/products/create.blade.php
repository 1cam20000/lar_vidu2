@extends('layouts.admin')

@section('content')
<div class="container mt-4">
  <h3>Create Product</h3>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
  @endif

  <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
      <label class="form-label">Category</label>
      <select name="category_id" class="form-select" required>
        <option value="">-- Chọn danh mục --</option>
        @foreach($categories as $c)
          <option value="{{ $c->id }}" @selected(old('category_id')==$c->id)>{{ $c->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Name</label>
      <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
    </div>

    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label">Quantity</label>
        <input type="number" name="quantity" min="0" class="form-control" value="{{ old('quantity',0) }}" required>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Price (VNĐ)</label>
        <input type="number" name="price" min="0" class="form-control" value="{{ old('price',0) }}" required>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Image</label>
        <input type="file" name="image" class="form-control" accept="image/*">
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Features (ngăn cách bằng dấu phẩy)</label>
      <textarea name="features" rows="2" class="form-control" placeholder="Ví dụ: chất liệu cotton, màu xanh, freesize">{{ old('features') }}</textarea>
    </div>

    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back</a>
    <button class="btn btn-primary">Submit</button>
  </form>
</div>
@endsection
