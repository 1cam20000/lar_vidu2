@extends('layouts.admin')

@section('content')
<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Products</h3>
    <a href="{{ route('admin.products.create') }}" class="btn btn-success">+ New Product</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table table-bordered align-middle">
    <thead class="table-light">
      <tr>
        <th style="width:80px">ID</th>
        <th style="width:80px">Image</th>
        <th>Name</th>
        <th>Category</th>
        <th class="text-end" style="width:140px">Price</th>
        <th style="width:200px">Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse($products as $p)
      <tr>
        <td>{{ $p->id }}</td>
        <td>
          @if($p->image)
            <img src="{{ asset($p->image) }}" alt="" class="img-thumbnail" style="width:60px;height:60px;object-fit:cover">
          @endif
        </td>
        <td>
          <strong>{{ $p->name }}</strong>
          <div class="small text-muted">Qty: {{ $p->quantity }}</div>
        </td>
        <td>{{ $p->category->name ?? 'N/A' }}</td>
        <td class="text-end">{{ number_format($p->price) }} VNĐ</td>
        <td>
          <a href="{{ route('admin.products.show', $p) }}" class="btn btn-sm btn-info">Show</a>
          <a href="{{ route('admin.products.edit', $p) }}" class="btn btn-sm btn-warning">Edit</a>
          <form action="{{ route('admin.products.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Xoá sản phẩm này?');">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-danger">Delete</button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="6" class="text-center text-muted">Chưa có sản phẩm.</td></tr>
      @endforelse
    </tbody>
  </table>

  <div class="d-flex justify-content-end">
    {{ $products->links() }}
  </div>
</div>
@endsection
