@extends('layouts.admin')

@section('title','Báo cáo')

@section('content')
<div class="container mt-4">
  <h3>📊 Báo cáo doanh thu</h3>

  <h5 class="mt-3">Doanh thu 7 ngày gần nhất</h5>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Ngày</th>
        <th>Tổng doanh thu</th>
      </tr>
    </thead>
    <tbody>
      @foreach($dailyRevenue as $r)
      <tr>
        <td>{{ $r->date }}</td>
        <td>{{ number_format($r->total) }} VNĐ</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <h5 class="mt-3">Doanh thu 6 tháng gần nhất</h5>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Tháng</th>
        <th>Tổng doanh thu</th>
      </tr>
    </thead>
    <tbody>
      @foreach($monthlyRevenue as $r)
      <tr>
        <td>{{ $r->month }}/{{ $r->year }}</td>
        <td>{{ number_format($r->total) }} VNĐ</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <h5 class="mt-3">Trạng thái giao hàng</h5>
  <ul>
    @foreach($shippingStats as $s)
      <li>{{ $s->shipping_status }}: {{ $s->total }} đơn</li>
    @endforeach
  </ul>
</div>
@endsection
