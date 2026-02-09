<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Header UI</title>
    <link href="{{ asset('assets/css/layout/partials/Header_Employee.css') }}" rel="stylesheet">
</head>

<body>
    <header class="header">
        <nav class="navbar navbar-header navbar-expand-lg navbar-white bg-white">
            <div class="container-fluid d-flex justify-content-between align-items-center" id="Logo">
                <button class="btn navbar-toggler me-2 d-lg-none" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <i class="bi bi-list"></i>
                </button>

                <div class="offcanvas offcanvas-start d-lg-none" data-bs-scroll="true" data-bs-backdrop="false"
                    tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
                    <div class="offcanvas-header">
                        <h4 class="offcanvas-title" id="offcanvasScrollingLabel">MENU</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="list-unstyled components">
                            <li>
                                <a href="{{ route('admin.index') }}">TỔNG QUAN</a>
                            </li>
                            <li>
                                <a data-bs-toggle="collapse" href="#productSubmenu" aria-expanded="false"
                                    class="dropdown-toggle">
                                    SẢN PHẨM
                                </a>
                                <ul class="collapse list-unstyled" id="productSubmenu">
                                    <li><a href="{{ route('product.index') }}">Danh mục sản phẩm</a></li>
                                    <li><a>Khuyến mãi</a></li>
                                </ul>
                            </li>
                            <li>
                                <a data-bs-toggle="collapse" href="#transactionSubmenu" aria-expanded="false"
                                    class="dropdown-toggle">
                                    GIAO DỊCH
                                </a>
                                <ul class="collapse list-unstyled" id="transactionSubmenu">
                                    <li><a>Phiếu đặt hàng</a></li>
                                    <li><a>Nhập hàng</a></li>
                                </ul>
                            </li>
                            <li>
                                <a data-bs-toggle="collapse" href="#partnerSubmenu" aria-expanded="false"
                                    class="dropdown-toggle">
                                    ĐỐI TÁC & NGUỒN LỰC
                                </a>
                                <ul class="collapse list-unstyled" id="partnerSubmenu">
                                    <li><a>Khách hàng</a></li>
                                    <li><a>Nhà cung cấp</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="{{ route('admin.salesReport') }}">BÁO CÁO</a>
                            </li>
                            <li>
                                <a>BÁN HÀNG</a>
                            </li>
                            <li>
                                <a href="{{ route('account.profile') }}">TÀI KHOẢN</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.logout') }}">ĐĂNG XUẤT</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <a class="navbar-brand">
                    <img src="{{ asset('uploads/logo/Favicon.png') }}" alt="" width="40" height="40">
                    <span class="company-name">Chapter One</span>
                </a>
                <div class="collapse navbar-collapse justify-content-end" id="navbarMenu">
                    <ul class="navbar-nav d-flex align-items-center">
                        <li class="nav-item me-3 d-flex align-items-center">
                            <span class="branch-label me-1">Chi nhánh trung tâm:</span>
                            <button class="nav-link icon-button" id="branchButton">
                                <i class="bi bi-geo-alt"></i>
                            </button>
                        </li>

                        <li class="nav-item me-3">
                            <button class="nav-link icon-button" id="emailButton">
                                <i class="bi bi-envelope"></i>
                            </button>
                        </li>

                        <li class="nav-item me-3">
                            <button class="nav-link icon-button" id="settingsButton">
                                <i class="bi bi-gear"></i>
                            </button>
                        </li>

                        <li class="nav-item d-flex align-items-center ">
                            <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item">Profile</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.logout') }}">
                                        Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

</body>

</html>