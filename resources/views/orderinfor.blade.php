@extends('master.main')

@section('title', 'Thông tin đơn hàng')

@section('content')
<div class="form-container">
    <div class="form-header">
        <div class="form-title">Đơn hàng</div>
    </div>

    @csrf
    <div class="order-container">
        <!-- Order Section -->
        @foreach ($orders->groupBy(function($order) {
        return \Carbon\Carbon::parse($order->created_at)->format('Y-m-d');
        }) as $date => $groupedOrders)
        <div class="order-date-group mb-4">
            <h4 class="order-date">{{ $date }}</h4>
            @foreach ($groupedOrders as $order)
            <div class="order-item border rounded p-3 mb-3 position-relative">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="order-status">
                        <span class="badge bg-info"
                            style="font-size: 20px; font-weight: bold;">{{ $order->status }}</span>
                    </div>
                </div>
                <hr style="margin-top: 40px;">
                @foreach ($order->orderDetail as $detail)
                <div class="d-flex mb-2 align-items-center">
                    <div class="order-image">
                        @if($detail->product->images->isNotEmpty())
                        <img src="{{ asset($detail->product->images->first()->url) }}" alt="Product Image"
                            class="img-thumbnail" style="width: 100px; height: auto;">
                        @else
                        <img src="{{ asset('uploads/products/default.png') }}" alt="Product Image" class="img-thumbnail"
                            style="width: 100px; height: auto;">
                        @endif
                    </div>
                    <div class="order-details flex-grow-1 ms-3">
                        <h5>{{ $detail->product->productTitle->name }}</h5>
                        <p>Số lượng: {{ $detail->quantity }}</p>
                    </div>
                    <div class="order-price ms-auto">
                        <p class="fw-bold">{{ number_format($detail->price, 0, ',', '.') }} VND</p>
                    </div>
                </div>
                @endforeach
                <hr>
                <div class="order-total d-flex justify-content-end mt-3">
                    <h5>Thành tiền: {{ number_format($order->total_price, 0, ',', '.') }} VND</h5>
                </div>
                <div class="order-actions d-flex justify-content-end mt-3">
                    @if($order->status == 'Đã xác nhận')
                    <form method="POST" action="{{ route('order.cancelorder', $order->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-danger">Hủy đơn hàng</button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/orderinfor.css') }}">
@endpush
@push('scripts')
@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        icon: 'success',
        title: 'Thành công',
        text: `{{ session('success') }}`,
    });
});
</script>
@endif
@if(session('error'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        icon: 'error',
        title: 'Lỗi',
        text: `{{ session('error') }}`,
    });
});
</script>
@endif

<script src="{{ asset('assets/js/orderinfor.js') }}"></script>
@endpush