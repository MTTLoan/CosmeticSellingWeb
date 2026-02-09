<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header UI</title>
    <link href="{{ asset('assets/css/layout/partials/NavBar.css') }}" rel="stylesheet">
</head>

<body>
    <div class="d-none d-lg-block navbar-container">
        <nav class="navbar navbar-expand-md navbar-style">
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <!-- Tổng quan with eye icon -->
                        <li class="nav-item">
                            <a class="nav-link nav-link-style" href="{{ route('admin.index') }}" id="overviewButton">
                                <i class="bi bi-eye"></i> Tổng quan
                            </a>
                        </li>

                        <!-- Tài khoản (chỉ dành cho admin) -->
                        @if (auth('web')->user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link nav-link-style" id="overviewButton">
                                <i class="bi bi-person"></i> Tài khoản
                            </a>
                        </li>
                        @endif

                        <!-- Dropdown 1 -->
                        @if (in_array(auth('web')->user()->role, ['admin', 'branch_manager', 'staff']))
                        <li class="nav-item dropdown dropdown-style">
                            <a class="nav-link nav-link-style dropdown-toggle" id="navDropdown1" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-book"></i> Sản phẩm
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navDropdown1">
                                <li>
                                    <a class="dropdown-item" href="{{ route('product.index') }}"
                                        id="productCategoryButton">
                                        <i class="bi bi-list-ul"></i> Danh mục sản phẩm
                                    </a>
                                </li>
                                @if (in_array(auth('web')->user()->role, ['admin', 'branch_manager']))
                                <li>
                                    <a class="dropdown-item" id="promotionButton" href="{{ route('discount.index') }}">
                                        <i class="bi bi-tags"></i> Khuyến mãi
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" id="reviewButton">
                                        <i class="bi bi-star-fill"></i> Đánh giá
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif

                        <!-- Giao dịch (chỉ dành cho staff và branch_manager) -->
                        @if (in_array(auth('web')->user()->role, ['staff', 'branch_manager']))
                        <li class="nav-item dropdown">
                            <a class="nav-link nav-link-style dropdown-toggle" id="navDropdown2" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-card-checklist"></i> Giao dịch
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navDropdown2">
                                <li>
                                    <a class="dropdown-item" id="orderSlipButton">
                                        <i class="bi bi-journal-check"></i> Phiếu đặt hàng
                                    </a>
                                </li>
                                @if (auth('web')->user()->role === 'branch_manager')
                                <li>
                                    <a class="dropdown-item" id="importButton">
                                        <i class="bi bi-box-seam"></i> Nhập hàng
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif

                        <!-- Dropdown 3 -->
                        @if (in_array(auth('web')->user()->role, ['admin', 'branch_manager', 'staff']))
                        <li class="nav-item dropdown">
                            <a class="nav-link nav-link-style dropdown-toggle" id="navDropdown3" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-people"></i> Đối tác & Nguồn lực
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navDropdown3">
                                <li>
                                    <a class="dropdown-item" id="customerButton">
                                        <i class="bi bi-person"></i> Khách hàng
                                    </a>
                                </li>
                                @if (in_array(auth('web')->user()->role, ['admin', 'branch_manager']))
                                <li>
                                    <a class="dropdown-item" id="supplierButton">
                                        <i class="bi bi-building"></i> Nhà cung cấp
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" id="employeeButton">
                                        <i class="bi bi-person-workspace"></i> staff
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif

                        <!-- Báo cáo (chỉ dành cho branch_manager và diẻctor) -->
                        @if (in_array(auth('web')->user()->role, ['branch_manager', 'director']))
                        <li class="nav-item">
                            <a class="nav-link nav-link-style" href="{{ route('admin.salesReport') }}"
                                id="overviewButton">
                                <i class="bi bi-eye"></i> Báo cáo
                            </a>
                        </li>
                        @endif

                        @if (auth('web')->user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link nav-link-style" id="overviewButton"
                                href="{{ route('change-logs.index') }}">
                                <i class="bi bi-clock-history"></i> Lịch sử
                            </a>
                        </li>
                        @endif
                    </ul>

                    <!-- Bán hàng (chỉ dành cho staff và branch_manager) -->
                    @if (in_array(auth('web')->user()->role, ['staff', 'branch_manager']))
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link nav-link-style" id="salesButton">
                                <i class="bi bi-basket"></i> Bán hàng
                            </a>
                        </li>
                    </ul>
                    @endif
                </div>
            </div>
        </nav>
    </div>

</body>

</html>