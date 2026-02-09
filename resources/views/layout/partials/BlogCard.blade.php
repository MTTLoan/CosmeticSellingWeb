@extends('master.main')

@section('title', 'Product Card')

@section('content')
<div class="container mt-4">
    <!-- Product Card -->
    <div class="col">
        <div class="card custom-card">
            <img src="{{ asset($product['image']) }}" class="card-img-top" alt="ảnh sản phẩm">
            <div class="card-body">
                <h5 class="card-title">Review sản phẩm:</h5>
                <p class="card-text">{{ $product['name'] }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('css/layout/partials/BlogCard.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('js/layout/partials/BlogCard.js') }}"></script>
@endpush