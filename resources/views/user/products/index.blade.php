@extends('layouts.user')

@section('title','Danh sách sản phẩm')

@section('content')
<h3 class="mb-3">Danh sách sản phẩm</h3>
<div class="row g-3">
  @foreach($products as $p)
    <div class="col-md-3">
      <div class="card h-100 shadow-sm">
        @if($p->image)
          <img src="{{ asset($p->image) }}" class="card-img-top" alt="{{ $p->name }}" style="height:180px;object-fit:cover">
        @endif
        <div class="card-body">
          <h5 class="card-title">{{ $p->name }}</h5>
          <p class="card-text text-muted small">{{ number_format($p->price) }} VNĐ</p>
          <a href="{{ route('user.products.show',$p) }}" class="btn btn-sm btn-primary">Xem chi tiết</a>
        </div>
      </div>
    </div>
  @endforeach
</div>

<div class="mt-3">
  {{ $products->links() }}
</div>
@endsection
