@extends('layouts.user')

@section('title','Danh mục sản phẩm')

@section('content')
<h3 class="mb-3">Danh mục sản phẩm</h3>
<ul class="list-group">
  @foreach($categories as $c)
    <li class="list-group-item">{{ $c->name }}</li>
  @endforeach
</ul>
@endsection
