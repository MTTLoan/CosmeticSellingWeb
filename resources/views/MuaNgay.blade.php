@extends('master.main')
@section('title', 'Thông tin giao hàng')
@section('content')

<div class="shipping_infor_container body-container">
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
    <form id="personal-info-form" class="form-container" action="{{ route('order.buyNowCreate') }}" method="POST">
        @csrf
        <div>
            <div class="form-header">
                <div class="form-title">Thông tin giao hàng</div>
            </div>
            <div>
                <div class="form-group">
                    <label for="fullname">Họ và tên (*)</label>
                    <input type="text" class="form-control @error('fullname') is-invalid @enderror" id="fullname"
                        value="{{ $customer->fullname }}" name="fullname" required readonly>
                    @error('fullname')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="phone_number">Số điện thoại (*)</label>
                    <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number"
                        value="{{ $customer->phone_number }}" name="phone_number" required readonly>
                    @error('phone_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="province">Tỉnh, thành</label>
                    <select id="province" name="province" class="form-select @error('province') is-invalid @enderror"
                        required>
                        <option>Chọn</option>
                    </select>
                    @error('province')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="district">Quận, huyện</label>
                    <select id="district" name="district" class="form-select @error('district') is-invalid @enderror"
                        required>
                        <option>Chọn</option>
                    </select>
                    @error('district')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="ward">Phường, xã</label>
                    <select id="ward" name="ward" class="form-select @error('ward') is-invalid @enderror" required>
                        <option>Chọn</option>
                    </select>
                    @error('ward')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="address">Địa chỉ</label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address"
                        value="{{ $customer->address }}" name="address" required>
                    @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-header">
                <div class="form-title">Phương thức thanh toán</div>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" checked>
                <label class="form-check-label" for="flexRadioDefault1">
                    Thanh toán bằng tiền mặt khi nhận hàng
                </label>
            </div>
            <div class="form-actions">
                <a type="button" class="btn btn-back" id="goToCart" href="{{ route('cart.index') }}">
                    <i class="bi bi-caret-left-fill"></i> Về giỏ hàng
                </a>
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="{{ $quantity }}">
                <input type="hidden" name="discount_id" id="discount-id">
                <input type="hidden" name="total_price" value="{{ $totalPrice }}">
                <button type="submit" class="btn btn-finish" id="completeOrder">Hoàn tất đơn hàng</button>
            </div>
        </div>
    </form>
    <div class="form-container container_right">
        <div class="discount-container">
            <input type="text" class="discount-code" placeholder="Mã giảm giá">
            <button type="button" class="apply-discount">Sử dụng</button>
        </div>
        <div class="cart-item-label">
            <div class="cart-item-name">Sản phẩm</div>
            <div class="cart-item-quantity">Số lượng</div>
            <div class="cart-item-price">
                <span class="cart-item-total-price">Giá tiền</span>
            </div>
        </div>

        <div class="cart-item">
            <div class="cart-item-name">{{ $product->productTitle->name }}</div>
            <div class="cart-item-quantity">{{ $quantity }}</div>
            <div class="cart-item-price">
                <span class="cart-item-total-price">{{ number_format($quantity * $product->unit_price) }} đ</span>
            </div>
        </div>

        <div class="cart-item">
            <div class="cart-item-name">Phí vận chuyển</div>
            <div class="cart-item-price">
                <span class="cart-item-total-price">15.000 đ</span>
            </div>
        </div>

        <div class="cart-item">
            <div class="cart-item-name">Mã giảm giá</div>
            <div class="cart-item-price">
                <span class="cart-item-total-price discount-amount"></span>
            </div>
        </div>

        <div class="total">
            <strong>TỔNG CỘNG: </strong> <span id="total-price"
                data-total-price="{{ $totalPrice + 15000 }}">{{ number_format($totalPrice + 15000, 0, ',', '.') }}
                đ</span>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('assets/css/ThongTinGiaoHang.css') }}" rel="stylesheet">
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
<script>
const customerProvince = "{{ $customer->province }}";
const customerDistrict = "{{ $customer->district }}";
const customerWard = "{{ $customer->ward }}";
const checkDiscountUrl = "{{ route('check.discount') }}";
</script>
<script src="{{ asset('assets/js/MuaNgay.js') }}"></script>
@endpush