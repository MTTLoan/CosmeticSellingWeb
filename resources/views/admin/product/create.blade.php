@extends('master.admin')

@section('title', 'Tạo sản phẩm')

@section('content')
<div class="container-create container-sm p-0">
    <div class="content p-4 bg-white">
        <div class="title fs-1 fw-bold">
            <h2>Thêm mới Sản phẩm</h2>
            <hr />
        </div>
        <form id="productForm" method="POST" enctype="multipart/form-data" action="{{ route('product.store') }}">
            @csrf
            <div class="form_themsanpham border p-4 rounded">
                <div class="row g-3">
                    <div class="col-md-6 p-2">
                        <label for="product_type_id" class="form-label">Loại mỹ phẩm <i
                                class="text-danger">(*)</i></label>
                        <select name="product_type_id" id="product_type_id"
                            class="form-select @error('product_type_id') is-invalid @enderror" required>
                            <option value="" selected>Chọn loại mỹ phẩm...</option>
                            @foreach ($productTypes as $productType)
                            <option value="{{ $productType->id }}"
                                {{ old('product_type_id') == $productType->id ? 'selected' : '' }}>
                                {{ $productType->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('product_type_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 p-2">
                        <label for="capacity" class="form-label">Dung tích <i class="text-danger">(*)</i></label>
                        <input type="number" class="form-control @error('capacity') is-invalid @enderror"
                            name="capacity" id="capacity" placeholder="Nhập vào dung tích" value="{{ old('capacity') }}"
                            required />
                        @error('capacity')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 p-2">
                        <label for="name" class="form-label">Tên mỹ phẩm <i class="text-danger">(*)</i></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                            id="name" placeholder="Nhập vào tên mỹ phẩm" value="{{ old('name') }}" required />
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 p-2">
                        <label for="author" class="form-label">Thương hiệu <i class="text-danger">(*)</i></label>
                        <input type="text" class="form-control @error('author') is-invalid @enderror" name="author"
                            id="author" placeholder="Nhập vào thương hiệu" value="{{ old('author') }}" required />
                        @error('author')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 p-2">
                        <label for="supplier_id" class="form-label">Nhà cung cấp <i class="text-danger">(*)</i></label>
                        <select name="supplier_id" id="supplier_id"
                            class="form-select @error('supplier_id') is-invalid @enderror" required>
                            <option value="" selected>Chọn nhà cung cấp...</option>
                            @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}"
                                {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 p-2">
                        <label for="publishing_year" class="form-label">Năm sản xuất <i
                                class="text-danger">(*)</i></label>
                        <select name="publishing_year" id="publishing_year"
                            class="form-select @error('publishing_year') is-invalid @enderror" required>
                            <option value="" selected>Chọn năm sản xuất...</option>
                            @for ($year = date('Y'); $year >= 1800; $year--)
                            <option value="{{ $year }}" {{ old('publishing_year') == $year ? 'selected' : '' }}>
                                {{ $year }}</option>
                            @endfor
                        </select>
                        @error('publishing_year')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 p-2">
                        <label for="cost" class="form-label">Chi phí <i class="text-danger">(*)</i></label>
                        <input type="number" class="form-control @error('cost') is-invalid @enderror" name="cost"
                            id="cost" placeholder="Nhập vào chi phí" value="{{ old('cost') }}" required />
                        @error('cost')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 p-2">
                        <label for="unit_price" class="form-label">Đơn giá <i class="text-danger">(*)</i></label>
                        <input type="number" class="form-control @error('unit_price') is-invalid @enderror"
                            name="unit_price" id="unit_price" placeholder="Nhập vào đơn giá"
                            value="{{ old('unit_price') }}" required />
                        @error('unit_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 p-2">
                        <label for="color" class="form-label">Màu sắc <i class="text-danger">(*)</i></label>
                        <input type="text" class="form-control @error('color') is-invalid @enderror" name="color"
                            id="color" placeholder="Nhập vào màu sắc" value="{{ old('color') }}" required />
                        @error('color')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 p-2">
                        <label for="description" class="form-label">Mô tả <i class="text-danger">(*)</i></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" name="description"
                            id="description" rows="3"
                            placeholder="Mô tả cụ thể tại đây">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 p-2">
                        <label for="image" class="form-label">Hình ảnh <i class="text-danger">(*)</i></label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" name="images[]"
                            id="image" multiple required />
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="image-preview" class="mt-2"></div>
                    </div>
                    <div class="group_btn d-flex justify-content-end p-2">
                        <button class="btn btn_cancel me-3" id="btnCancel" type="button">
                            Hủy
                        </button>
                        <button type="submit" class="btn btn_save" id="btnSave">
                            Lưu
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection


@push('styles')
<link href="{{ asset('assets/css/admin/product/create.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('assets/js/admin/product/create.js') }}"></script>

@if ($errors->any())
<script>
Swal.fire({
    icon: 'error',
    title: 'Thêm thất bại',
    html: `
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
});
</script>
@endif

@if (session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Thêm thành công',
    text: "{{ session('success') }}",
}).then(function() {
    window.location.href = "{{ route('product.index') }}";
});
</script>
@endif
@endpush