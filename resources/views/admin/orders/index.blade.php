@extends('layouts.admin')

@section('title','Quản lý đơn hàng')

@section('content')
<div class="container mt-4">
  <h3>📦 Tất cả đơn hàng</h3>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table table-bordered table-hover">
    <thead class="table-light">
      <tr>
        <th>#</th>
        <th>Người đặt</th>
        <th>Tổng tiền</th>
        <th>Thanh toán</th>
        <th>Giao hàng</th>
        <th>Ngày đặt</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach($orders as $order)
      <tr>
        <td>{{ $order->id }}</td>
        <td>{{ $order->user->name ?? 'N/A' }} <br> <small>{{ $order->user->email ?? '' }}</small></td>
        <td>{{ number_format($order->total_price) }} VNĐ</td>
        <td>{{ $order->status }}</td>
        <td>
          {{-- Form đổi trạng thái giao hàng --}}
          <form method="POST" action="{{ route('admin.orders.update',$order) }}">
            @csrf
            @method('PUT')
            <select name="shipping_status" class="form-select form-select-sm" onchange="this.form.submit()">
              <option value="not_shipped" @selected($order->shipping_status=='not_shipped')>Chưa giao</option>
              <option value="packaged"    @selected($order->shipping_status=='packaged')>Đã đóng gói</option>
              <option value="shipping"    @selected($order->shipping_status=='shipping')>Đang vận chuyển</option>
              <option value="completed"   @selected($order->shipping_status=='completed')>Hoàn tất</option>
              <option value="cancelled"   @selected($order->shipping_status=='cancelled')>Đã hủy</option>
            </select>
          </form>
        </td>
        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
        <td>
          <a href="{{ route('admin.orders.show',$order) }}" class="btn btn-sm btn-info">Chi tiết</a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
