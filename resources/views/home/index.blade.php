@extends('master.main')

@section('title', 'Trang chủ')

@section('content')
<div class="container-fluid-product">
    <div class="row">
        <!-- Sidebar - Categories -->
        <aside class="col-md-3 col-lg-3 category-menu border rounded p-0 bg-white d-none d-lg-block">
            <div class="category-header bg-light">
                <h5 class="fw-medium">
                    <i class="bi bi-list"></i> DANH MỤC
                </h5>
            </div>

            <ul class="list-group">
                <li class="list-group-item">
                    <a href="#VanHoc" class="d-block p-1">VĂN HỌC</a>
                </li>
                <li class="list-group-item">
                    <a href="#SachThieuNhi" class="d-block p-1">SÁCH THIẾU NHI</a>
                </li>
                <li class="list-group-item">
                    <a href="#PhatTrienBanThan" class="d-block p-1">PHÁT TRIỂN BẢN THÂN</a>
                </li>
                <li class="list-group-item">
                    <a href="#SachGiaoDuc" class="d-block p-1">SÁCH GIÁO DỤC</a>
                </li>
                <li class="list-group-item">
                    <a href="#KinhDoanh" class="d-block p-1">KINH DOANH</a>
                </li>
                <li class="list-group-item">
                    <a href="#NgoaiNgu" class="d-block p-1">NGOẠI NGỮ</a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <div class="col-lg-9 px-3">
            <!-- Button Section with Border -->
            <div class="button-section border rounded d-flex justify-content-between mb-3 p-1 d-none d-md-flex">
                @if(auth('cus')->check())
                <button class="btn me-3"><a href="{{ route('order.orderinfor') }}"
                        class="text-black text-decoration-none">ĐƠN HÀNG</a></button>
                @endif
                <button class="btn me-3" data-target="Voucher"><a href="{{ route('discounts.list') }}"
                        class="text-black text-decoration-none">VOUCHER</a>
                </button>
                <button class="btn me-3" data-target="Review">REVIEW</button>
                <button class="btn " data-target="Blog">BLOG</button>
            </div>

            <!-- Image Section -->
            <div class="container p-0">
                <div class="row">
                    <!-- Column 1 (Banner Image) - Cột 1 chiếm 2 dòng -->
                    <div class="col-lg-9 main_banner">
                        <img src="{{ asset('uploads/banner/Banner.png') }}" alt="Banner Image"
                            class="img-fluid rounded img-responsive">
                    </div>
                    <!-- Column 2, Row 1 (Book 1) -->
                    <div class="col-lg-3 book-cell ps-0">
                        <img src="{{ asset('uploads/banner/Banner1.jpg') }}" alt="Book 1"
                            class="img-fluid img-responsive rounded pb-3">
                        <img src="{{ asset('uploads/banner/Banner2.jpg') }}" alt="Book 2"
                            class="img-fluid img-responsive rounded ">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loop through each book category and display books -->
    @foreach ($productTitles as $category => $titles)
    <div class="category-section" id="{{ $category }}">
        <h4 class="card-group-title">
            <span class="card-group-title-main">{{ mb_strtoupper($category, 'UTF-8')  }}</span>
            <a href="#" class="card-group-link">Xem thêm ></a>
        </h4>

        <!-- Product Cards -->
        <div class="card_group row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">
            @foreach ($titles as $title)
            <div class="col">
                <div class="product p-20 mb-20 rounded w-auto bg-white" data-product-id="{{ $title->id }}">
                    <a href="{{ route('sale.showBookDetails', ['product_title_id' => $title->id]) }}"
                        style="text-decoration: none; color: inherit;">
                        <div class="image_container d-flex align-items-center justify-content-center">
                            <img src="{{ asset($title->image_url) }}" alt="product" class="img-fluid img_book" />
                        </div>
                    </a>
                    <h5 class="fw-bold my-2" id="price">{{ number_format($title->unit_price, 0, ',', '.') }} đ</h5>
                    <p class="mb-2" id="title">{{ $title->name }}</p>
                    <div class="d-flex  p-0 justify-content-between align-content-center">
                        <span id="sales">Đã bán {{ $title->sold_quantity }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    <h4 class="card-group-title">
        <span class="card-group-title-main" data-category="Blog">Blog</span>
        <a href="#" class="card-group-link-blog">Xem thêm ></a>
    </h4>

    <!-- Blog Cards -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <!-- Blog content goes here -->
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('assets/css/home/index.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('assets/js/home/index.js') }}"></script>
@endpush