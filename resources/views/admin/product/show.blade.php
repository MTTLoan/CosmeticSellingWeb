@extends('master.admin')

@section('title', 'Chi tiết sản phẩm')

@section('content')
<div class="container-show container-sm p-0">
    <div class="content p-4 bg-white">
        <div class="title fs-1 fw-bold">
            <h2>Xem thông tin Sản phẩm</h2>
            <hr />
        </div>
        <div class="form_xemsanpham border p-4 rounded">
            <div class="row g-3">
                <div class="col-md-12 p-2">Mã mỹ phẩm: {{ $product->id }} </div>
                <div class="col-md-6 p-2">Loại mỹ phẩm: {{ $product->productTitle->productType->name }} </div>
                <div class="col-md-6 p-2">Dung tích: {{ $product->capacity }} </div>
                <div class="col-md-6 p-2">Tên mỹ phẩm: {{ $product->productTitle->name }} </div>
                <div class="col-md-6 p-2">Thương hiệu: {{ $product->productTitle->author }} </div>
                <div class="col-md-6 p-2">Nhà cung cấp: {{ $product->productTitle->suppliers->name }} </div>
                <div class="col-md-6 p-2">Năm sản xuất: {{ $product->publishing_year }} </div>
                <div class="col-md-6 p-2">Màu sắc: {{ $product->color }} </div>
                <div class="col-md-6 p-2">Chi phí: {{ $product->cost }} </div>
                <div class="col-md-6 p-2">Đơn giá: {{ $product->unit_price }} </div>
                <div class="col-md-6 p-2">Số lượng: {{ $product->quantity }} </div>
                <div class="col-12 p-2">
                    Mô tả: {{ $product->productTitle->description }}
                </div>
                <div class="col-md-12 p-2">Hình ảnh:
                    @foreach($product->images as $image)
                    <img src="{{ asset($image->url) }}" alt="Hình ảnh" class="img-fixed-size" />
                    @endforeach
                </div>
                <div class="group_btn d-flex justify-content-end p-2">
                    <button type="button" class="btn btn_exit" id="btnExit">
                        Thoát
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('assets/css/admin/product/show.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('assets/js/admin/product/show.js') }}"></script>
@endpush