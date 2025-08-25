@extends('layouts.admin')

@section('content')
<div class="container mt-4">
  <h3>Show Product</h3>
  <div class="card">
    <div class="card-body">
      @if($product->image)
        <img src="{{ asset($product->image) }}" class="float-end ms-3 mb-3" style="width:150px;height:150px;object-fit:cover">
      @endif
      <p><strong>ID:</strong> {{ $product->id }}</p>
      <p><strong>Name:</strong> {{ $product->name }}</p>
      <p><strong>Category:</strong> {{ $product->category->name ?? 'N/A' }}</p>
      <p><strong>Quantity:</strong> {{ $product->quantity }}</p>
      <p><strong>Price:</strong> {{ number_format($product->price) }} VNĐ</p>
      @if($product->description)
        <p><strong>Description:</strong> {{ $product->description }}</p>
      @endif
      @if($product->features)
        <p><strong>Features:</strong>
          @if(is_array($product->features))
            {{ implode(', ', $product->features) }}
          @else
            {{ $product->features }}
          @endif
        </p>
      @endif
      <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back</a>
      <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">Edit</a>
    </div>
  </div>
</div>
@endsection
