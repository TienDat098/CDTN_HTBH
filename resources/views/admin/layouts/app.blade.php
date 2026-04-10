<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        /* Tùy chỉnh làm đẹp Sidebar */
        .sidebar { background-color: #ffffff; box-shadow: 2px 0 5px rgba(0,0,0,0.05); z-index: 1000; }
        .sidebar-menu .nav-link { color: #4a5568; font-weight: 500; padding: 10px 20px; transition: 0.2s; }
        .sidebar-menu .nav-link:hover, .sidebar-menu .nav-link.active { color: #0d6efd; background-color: #f8f9fa; }
        
        /* Menu con (Submenu) */
        .submenu .nav-link { color: #718096; font-weight: 400; padding: 8px 20px 8px 45px; font-size: 0.95rem; }
        .submenu .nav-link:hover, .submenu .nav-link.active { color: #0d6efd; font-weight: 500; }
        
        /* Hiệu ứng mũi tên xoay */
        .nav-link[data-bs-toggle="collapse"] .toggle-icon { transition: transform 0.3s; }
        .nav-link[data-bs-toggle="collapse"][aria-expanded="true"] .toggle-icon { transform: rotate(90deg); }
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <div class="row g-0 min-vh-100">

        <div class="col-md-2 sidebar d-flex flex-column">
            
            <div class="p-4 border-bottom text-center">
                <h3 class="fw-bold text-primary m-0">
                    <i class="bi bi-person-bounding-box me-2"></i>Admin Panel
                </h3>
            </div>

            <ul class="nav flex-column sidebar-menu flex-grow-1 pt-3">
                
                <li class="nav-item mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>

                @php $isProductActive = request()->routeIs('admin.categories.*', 'admin.brands.*', 'admin.products.*', 'admin.inventory.*'); @endphp
                <li class="nav-item mb-1">
                    <a class="nav-link d-flex justify-content-between align-items-center {{ $isProductActive ? 'text-primary' : '' }}" 
                       data-bs-toggle="collapse" href="#menuProduct" role="button" aria-expanded="{{ $isProductActive ? 'true' : 'false' }}">
                        <span><i class="bi bi-box-seam me-2 text-success"></i> Sản phẩm & Kho</span>
                        <i class="bi bi-caret-right-fill small toggle-icon"></i>
                    </a>
                    <div class="collapse {{ $isProductActive ? 'show' : '' }}" id="menuProduct">
                        <ul class="nav flex-column submenu">
                            <li class="nav-item">
                                <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                                    <i class="bi bi-chevron-right me-1 small"></i> Danh mục
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.brands.index') }}" class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                                    <i class="bi bi-chevron-right me-1 small"></i> Thương hiệu
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                                    <i class="bi bi-chevron-right me-1 small"></i> Tất cả sản phẩm
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.inventory.index') }}" class="nav-link {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                                    <i class="bi bi-chevron-right me-1 small"></i> Lịch sử Kho
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                @php $isSaleActive = request()->routeIs('admin.orders.*', 'admin.promotions.*'); @endphp
                <li class="nav-item mb-1">
                    <a class="nav-link d-flex justify-content-between align-items-center {{ $isSaleActive ? 'text-primary' : '' }}" 
                       data-bs-toggle="collapse" href="#menuSale" role="button" aria-expanded="{{ $isSaleActive ? 'true' : 'false' }}">
                        <span><i class="bi bi-cart3 me-2 text-warning"></i> Bán hàng</span>
                        <i class="bi bi-caret-right-fill small toggle-icon"></i>
                    </a>
                    <div class="collapse {{ $isSaleActive ? 'show' : '' }}" id="menuSale">
                        <ul class="nav flex-column submenu">
                            <li class="nav-item">
                                <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                                    <i class="bi bi-chevron-right me-1 small"></i> Đơn hàng
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.promotions.index') }}" class="nav-link {{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}">
                                    <i class="bi bi-chevron-right me-1 small"></i> Mã giảm giá
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                @php $isContentActive = request()->routeIs('admin.blogs.*', 'admin.contacts.*'); @endphp
                <li class="nav-item mb-1">
                    <a class="nav-link d-flex justify-content-between align-items-center {{ $isContentActive ? 'text-primary' : '' }}" 
                       data-bs-toggle="collapse" href="#menuContent" role="button" aria-expanded="{{ $isContentActive ? 'true' : 'false' }}">
                        <span><i class="bi bi-window-stack me-2 text-info"></i> Nội dung Web</span>
                        <i class="bi bi-caret-right-fill small toggle-icon"></i>
                    </a>
                    <div class="collapse {{ $isContentActive ? 'show' : '' }}" id="menuContent">
                        <ul class="nav flex-column submenu">
                            <li class="nav-item">
                                <a href="{{ route('admin.blogs.index') }}" class="nav-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">
                                    <i class="bi bi-chevron-right me-1 small"></i> Tin tức / Bài viết
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.contacts.index') }}" class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                                    <i class="bi bi-chevron-right me-1 small"></i> Liên hệ
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item mb-1 mt-2">
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill me-2 text-secondary"></i> Quản lý Tài khoản
                    </a>
                </li>

            </ul>

            <div class="p-3 border-top mb-3">
                <a href="{{ route('home') }}" class="nav-link text-dark fw-bold d-flex align-items-center" target="_blank">
                    <i class="bi bi-globe me-2 fs-5"></i> Xem Website
                </a>
            </div>
        </div>

        <div class="col-md-10 p-4 bg-light" style="overflow-y: auto; height: 100vh;">
            @if(session('success'))
                <div class="alert alert-success shadow-sm border-0 border-start border-5 border-success">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger shadow-sm border-0 border-start border-5 border-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>