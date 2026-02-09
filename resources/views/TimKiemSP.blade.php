@extends('master.main')

@section('title', 'Tìm Kiếm')

@section('content')
<div class="container-sm container_product mb-4 mt-4">
    <div
        class="header-container d-flex flex-md-row flex-column justify-content-between align-items-md-end align-items-start mb-3">
        <h1 class="card-group-title-main fw-bold text-nowrap me-4">Tìm kiếm</h1>
        <div class="filter-group">
            <span class="filter-option"><i class="bi bi-sort-down">Sắp xếp theo:</i></span>
            <form id="filterForm" class="d-flex" action="{{ route('search') }}" method="GET">
                <input type="hidden" name="query" value="{{ $query }}">
                <select class="form-select me-2" name="sort_by"
                    onchange="document.getElementById('filterForm').submit();">
                    <option value="price_asc" {{ $sort_by == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                    <option value="price_desc" {{ $sort_by == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                    <option value="sold_desc" {{ $sort_by == 'sold_desc' ? 'selected' : '' }}>Bán chạy nhất</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Product Cards -->
    <div class="card_group row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">
        @foreach ($titles as $title)
        <div class="col">
            <div class="product p-20 mb-20 rounded w-auto bg-white" data-product-id="{{ $title->id }}">
                <a href="{{ route('sale.showProductDetails', ['product_title_id' => $title->id]) }}"
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
@endsection

@push('styles')
<link href="{{ asset('assets/css/VanHoc_DanhMuc.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('assets/js/VanHoc_DanhMuc.js') }}"></script>
@endpush