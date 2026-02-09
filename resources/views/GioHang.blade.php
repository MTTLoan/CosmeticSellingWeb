@extends('master.main')

@section('title', 'Giỏ hàng')

@section('content')
<div class="form-container container-sm p-4 mt-4 mb-4">
    <div class="row" style="height: 100vh;">
        <!-- Phần cart items bên trái -->
        <div class="cart-items-container col-md-9 pe-md-4">
            <div class="form-header">
                <span class="form-title fs-4 fs-md-2 fw-bold">Giỏ hàng</span>
            </div>
            @foreach ($cartItems as $item)
            <div class="cart-item d-flex mt-3 align-items-center justify-content-between" id="item{{ $item->id }}">
                <div class="d-flex justify-content-start" style="width: 60%;">
                    @if($item->product->images->isNotEmpty())
                    <img src="{{ $item->product->images->first()->url }}" alt="{{ $item->product->productTitle->name }}"
                        class="cart-item-image">
                    @else
                    <img src="{{ asset('uploads/products/default.png') }}" alt="Default Image" class="cart-item-image">
                    @endif
                    <div>
                        <div class="cart-item-name">{{ $item->product->productTitle->name }}</div>
                        <!-- Loại bìa -->
                        <div class="cart-item-ver">{{ $item->product->publishing_year }} - {{ $item->product->color }}
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between" style="width: 40%;">
                    <div class="cart-item-quantity d-flex mx-md-3 mx-2 ">
                        <button onclick="changeQuantity('decrease', {{ $item->id }})">-</button>
                        <input type="text" value="{{ $item->quantity }}" id="quantity{{ $item->id }}"
                            onchange="updateItemTotal({{ $item->id }})">
                        <button onclick="changeQuantity('increase', {{ $item->id }})">+</button>
                    </div>
                    <div class="cart-item-price d-flex flex-md-row flex-column">
                        <span>{{ number_format($item->product->unit_price) }} đ</span>
                        <span class="cart-item-total-price ms-md-3" id="total-price{{ $item->id }}"
                            data-unit-price="{{ $item->product->unit_price }}">
                            {{ number_format($item->quantity * $item->product->unit_price) }} đ
                        </span>
                    </div>
                    <span class="remove-item mx-md-3 mx-2" onclick="removeItem('{{ $item->id }}')">Xóa</span>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Phần thông tin đơn hàng bên phải -->
        <div class="cart-summary bg-white col-md-3 mt-4 mt-md-0">
            <div class="fs-5 fw-bold text-center pb-2 border-bottom">Thông tin đơn hàng</div>
            <div class="">
                <div class="d-flex justify-content-between mt-3">
                    <strong>Số lượng:</strong>
                    <span id="total-quantity">{{ $totalQuantity }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <strong>Tổng:</strong>
                    <span id="total-price">{{ number_format($totalPrice, 0, ',', '.') }} đ</span>
                </div>
            </div>
            <a href="{{ route('order.create') }}" class="text-decoration-none text-white btn-checkout mt-3 w-100">Thanh
                toán</a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('assets/css/GioHang.css') }}" rel="stylesheet">
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
<script src="{{ asset('assets/js/GioHang.js') }}"></script>
@endpush