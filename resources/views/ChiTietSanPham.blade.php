@extends('master.main')

@section('title', 'Chi tiết sản phẩm')

@section('content')
<div class="container_DetailsProduct container-fluid-sm">
    <!-- Frame Sản phẩm chính-->
    <div class="row container_product m-0 g-4 bg-white">
        <div class="col-md-5 bg-white px-md-5 m-0 p-0">
            <div class="main-image text-center mb-4">
                <img id="mainImage" src="{{ asset($images->first()->url) }}" alt="Main Image" class="img-fluid" />
            </div>
            <div class="thumbnail-container d-flex justify-content-center gap-2 py-2">
                @foreach ($images as $image)
                @if (!$loop->first)
                <div class="thumbnail">
                    <img src="{{ asset($image->url) }}" alt="Thumbnail" onclick="updateMainImage(this)"
                        class="img-fluid" />
                </div>
                @endif
                @endforeach
            </div>
        </div>

        <!-- Thông tin sản phẩm -->
        <div class="col-md-7 info_product px-md-2 px-4 pb-4">
            <h2 class="fw-bold fs-2 mb-4" id="title">{{ $producttitle->name }}</h2>
            <div class="description_main_product p-0">
                <div class="gap-5">
                    <div class="">
                        <div class="d-flex align-content-center">
                            <span class="material-symbols-outlined kid_star">kid_star</span>
                            <span class="material-symbols-outlined kid_star">kid_star</span>
                            <span class="material-symbols-outlined kid_star">kid_star</span>
                            <span class="material-symbols-outlined kid_star">kid_star</span>
                            <span class="material-symbols-outlined kid_star me-2">kid_star</span>
                            <span>({{ $review_score->review_count ?? 0 }} đánh giá)</span>
                        </div>
                    </div>
                    <div class="">
                        <p>&nbsp;</p>
                        <p>Thương hiệu: {{ $producttitle->author }}</p>
                        <p>Dung tích: {{ $products->first()->capacity ?? 'N/A' }}</p>
                        <p>Mô tả: {{ $producttitle->description }}</p>
                    </div>
                </div>
                <hr />
                <h1 class="fw-bold text-danger" id="priceDisplay">
                    {{ number_format($products->first()->unit_price ?? 0, 0, '', '.') }} đ
                </h1>
                <hr />
                <h4>Chọn phiên bản:</h4>
                <div class="d-flex gap-3 mb-4">
                    @foreach ($products as $product)
                    <button class="btn btn-outline-primary version-btn" data-product-id="{{ $product->id }}"
                        data-price="{{ $product->unit_price }}" data-capacity="{{ $product->capacity }}">
                        {{ $product->color }}
                    </button>
                    @endforeach
                </div>
            </div>
            <div class="d-flex gap-3 mb-4">
                <label for="quantity" class="form-label">Số lượng:</label>
                <div class="quantity-control">
                    <button type="button" class="btn-decrease" onclick="decreaseQuantity()">-</button>
                    <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="1"
                        max="100" readonly>
                    <button type="button" class="btn-increase" onclick="increaseQuantity()">+</button>
                </div>
            </div>
            <div class="d-flex gap-3 mb-4">
                <button class="btn btn_AddCart" id="btnAddCart">Thêm vào giỏ hàng</button>
                <button class="btn btn_buyNow" id="btnBuyNow">Mua ngay</button>
            </div>
        </div>
    </div>

    <!-- Frame 2 -->
    <div class="container_description container-sm p-0" id="content_description">
        <div class="col-md-12 description bg-white mb-20" id="description">
            <div id="content">
                <!-- Nội dung mặc định -->
                <div id="descriptionContent" class="d-flex flex-column gap-35">
                    <p class="fs-5 fw-bold my-4">Thông tin sản phẩm</p>
                    <p>
                        Thương hiệu: {{ $producttitle->author }}<br />
                        Dung tích: {{ $products->first()->capacity ?? 'N/A' }}<br /><br />
                        {{ $producttitle->description }}
                    </p>
                    <p class="fs-5 fw-bold my-4">Hình ảnh sản phẩm</p>
                    @foreach ($images as $image)
                    <img src="{{ asset($image->url) }}" alt="product" class="img-fluid px-md-5" />
                    @endforeach
                </div>
                <!-- Nội dung khi click vào Đánh giá -->
                <div id="feedbackContent" class="d-flex flex-column gap-35" style="display: none;">
                    <p class="fs-5 fw-bold my-4">Đánh giá sản phẩm</p>
                    @foreach ($customer_reviews as $customer_review)
                    <hr />
                    <div class="row comment my-4">
                        <div class="col-2">
                            @php
                            $reviewDate = $customer_review->review_date ?? '2020-01-01';
                            $customerReviewScore = $customer_review->review_score ?? 0;
                            $customerName = $customer_review->customer_name ?? 'Khách hàng ẩn danh';
                            @endphp
                            <p id="name">{{ $customerName }}</p>
                            <p id="date">{{ $reviewDate }}</p>
                        </div>
                        <div class="col-10 d-flex flex-column gap-2">
                            <div>
                                @for ($i = 0; $i < $customerReviewScore; $i++) <span
                                    class="material-symbols-outlined kid_star">kid_star</span>
                                    @endfor
                            </div>
                            <p id="comment">{{ $customer_review->review_comment }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<form id="buyNowForm" action="{{ route('order.buyNow') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="product_id" id="buyNowProductId">
    <input type="hidden" name="quantity" id="buyNowQuantity">
</form>
@endsection

@push('styles')
<link href="{{ asset('assets/css/ChiTietSP.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('assets/js/ChiTietSP.js') }}"></script>
@endpush