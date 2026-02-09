@extends('master.main')

@section('title', "Voucher")

@section('content')

<div class="voucher_container container-sm p-0">
    <div class="content p-4 bg-white rounded">
        <div class="title fs-1 fw-bold pb-4">
            <h1>Voucher</h1>
        </div>
        <div class="list">
            @foreach ($discounts as $discount)
            <div class="col coupon d-flex justify-content-between mb-2 rounded">
                <div class="icon_coupon align-content-center rounded-start">
                    <img src="{{ asset('uploads/images/promo-code.png') }}" alt="Voucher" class="img-fluid" />
                </div>
                <div class="coupon_code justify-content-start p-3">
                    <p class="fw-bold">{{ $discount->name }}</p>
                    <p>{{ $discount->description }}</p>
                    <p>Giảm ngay {{ number_format($discount->value) }} VNĐ khi mua mỹ phẩm bất kỳ trị giá từ
                        {{ number_format($discount->starting_price) }} VNĐ trở lên! Cùng Chapter One khám phá những câu
                        chuyện mới!
                    </p>
                    <p>Ghi chú: Voucher có giá trị từ ngày {{ $discount->start_date }} đến {{ $discount->end_date }}.
                    </p>
                </div>
                <div class="buttons p-3 d-flex align-content-center justify-content-center rounded-end">
                    <p class="fw-bold text-center">{{ $discount->code }}</p>
                    <button class="btn btn_copy text-nowrap" onclick="copyToClipboard('{{ $discount->code }}')">
                        Sao chép
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('assets/css/LayVoucher.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script>
function copyToClipboard(code) {
    navigator.clipboard.writeText(code).then(function() {
        alert('Đã sao chép mã: ' + code);
    }, function(err) {
        console.error('Không thể sao chép mã: ', err);
    });
}
</script>
@endpush