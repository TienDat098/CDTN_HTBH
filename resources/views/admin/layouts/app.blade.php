<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - @yield('title')</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Vite: dùng cho Echo, Pusher, Alpine, CSS chung --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background-color: #f8f9fa;
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            background-color: #ffffff;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
            z-index: 1000;
            min-height: 100vh;
        }

        .sidebar-logo {
            min-height: 80px;
        }

        .sidebar-menu .nav-link {
            color: #4a5568;
            font-weight: 500;
            padding: 12px 20px;
            transition: 0.2s;
            border-radius: 0;
            cursor: pointer;
        }

        .sidebar-menu .nav-link:hover,
        .sidebar-menu .nav-link.active {
            color: #0d6efd;
            background-color: #f8f9fa;
        }

        .sidebar-menu .nav-link i {
            font-size: 1rem;
        }

        /* ================= SUBMENU CUSTOM ================= */
        .sidebar-submenu {
            display: none;
            background-color: #ffffff;
        }

        .sidebar-submenu.open {
            display: block;
        }

        .submenu .nav-link {
            color: #718096;
            font-weight: 400;
            padding: 9px 20px 9px 48px;
            font-size: 0.95rem;
        }

        .submenu .nav-link:hover,
        .submenu .nav-link.active {
            color: #0d6efd;
            background-color: #f8f9fa;
            font-weight: 500;
        }

        /* Mũi tên */
        .sidebar-toggle .toggle-icon {
            transition: transform 0.25s ease;
        }

        .sidebar-toggle[aria-expanded="true"] .toggle-icon {
            transform: rotate(90deg);
        }

        /* Tiêu đề nhóm AI */
        .sidebar-section-title {
            color: #4a5568;
            font-size: 0.85rem;
            font-weight: 700;
            padding: 12px 20px 6px 20px;
            text-transform: uppercase;
        }

        /* Khu vực xem website */
        .sidebar-footer {
            border-top: 1px solid #e9ecef;
        }

        /* Badge tin nhắn */
        #unread-badge {
            font-size: 0.75rem;
        }

        /* Hiệu ứng rung badge */
        @keyframes shakeBadge {
            0% { transform: translateX(0); }
            25% { transform: translateX(-3px); }
            50% { transform: translateX(3px); }
            75% { transform: translateX(-3px); }
            100% { transform: translateX(0); }
        }

        /* Nội dung bên phải */
        .admin-content {
            overflow-y: auto;
            height: 100vh;
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>

<div class="container-fluid p-0">
    <div class="row g-0 min-vh-100">

        {{-- ================= SIDEBAR ================= --}}
        <div class="col-md-2 sidebar d-flex flex-column">

            <div class="sidebar-logo p-4 border-bottom text-center d-flex align-items-center justify-content-center">
                <h3 class="fw-bold text-primary m-0">
                    <i class="bi bi-person-bounding-box me-2"></i>Admin Panel
                </h3>
            </div>

            <ul class="nav flex-column sidebar-menu flex-grow-1 pt-3">

                {{-- DASHBOARD --}}
                <li class="nav-item mb-1">
                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>

                {{-- TIN NHẮN HỖ TRỢ --}}
                <li class="nav-item mb-1">
                    <a href="{{ route('admin.chat') ?? '#' }}"
                       class="nav-link {{ request()->routeIs('admin.chat') ? 'active' : '' }} d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-chat-dots-fill me-2 text-success"></i> Tin nhắn hỗ trợ
                        </div>
                        <span id="unread-badge" class="badge bg-danger rounded-pill d-none">0</span>
                    </a>
                </li>

                {{-- ================= SẢN PHẨM & KHO ================= --}}
                @php
                    $isProductActive = request()->routeIs(
                        'admin.categories.*',
                        'admin.brands.*',
                        'admin.products.*',
                        'admin.inventory.*'
                    );
                @endphp

                <li class="nav-item mb-1">
                    <a class="nav-link sidebar-toggle d-flex justify-content-between align-items-center {{ $isProductActive ? 'text-primary' : '' }}"
                       href="#menuProduct"
                       role="button"
                       aria-expanded="{{ $isProductActive ? 'true' : 'false' }}">
                        <span>
                            <i class="bi bi-box-seam me-2 text-success"></i> Sản phẩm & Kho
                        </span>
                        <i class="bi bi-caret-right-fill small toggle-icon"></i>
                    </a>

                    <div class="sidebar-submenu {{ $isProductActive ? 'open' : '' }}" id="menuProduct">
                        <ul class="nav flex-column submenu">

                            @if(Auth::user()->role === 'admin')
                                <li class="nav-item">
                                    <a href="{{ route('admin.categories.index') }}"
                                       class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                                        <i class="bi bi-chevron-right me-1 small"></i> Danh mục
                                    </a>
                                </li>
                            @endif

                            @if(Auth::user()->role === 'admin')
                                <li class="nav-item">
                                    <a href="{{ route('admin.brands.index') }}"
                                       class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                                        <i class="bi bi-chevron-right me-1 small"></i> Thương hiệu
                                    </a>
                                </li>
                            @endif

                            <li class="nav-item">
                                <a href="{{ route('admin.products.index') }}"
                                   class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                                    <i class="bi bi-chevron-right me-1 small"></i> Tất cả sản phẩm
                                </a>
                            </li>

                            @if(Auth::user()->role === 'admin')
                                <li class="nav-item">
                                    <a href="{{ route('admin.inventory.index') }}"
                                       class="nav-link {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                                        <i class="bi bi-chevron-right me-1 small"></i> Lịch sử Kho
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </div>
                </li>

                {{-- ================= BÁN HÀNG ================= --}}
                @php
                    $isSaleActive = request()->routeIs(
                        'admin.orders.*',
                        'admin.promotions.*'
                    );
                @endphp

                <li class="nav-item mb-1">
                    <a class="nav-link sidebar-toggle d-flex justify-content-between align-items-center {{ $isSaleActive ? 'text-primary' : '' }}"
                       href="#menuSale"
                       role="button"
                       aria-expanded="{{ $isSaleActive ? 'true' : 'false' }}">
                        <span>
                            <i class="bi bi-cart3 me-2 text-warning"></i> Bán hàng
                        </span>
                        <i class="bi bi-caret-right-fill small toggle-icon"></i>
                    </a>

                    <div class="sidebar-submenu {{ $isSaleActive ? 'open' : '' }}" id="menuSale">
                        <ul class="nav flex-column submenu">

                            <li class="nav-item">
                                <a href="{{ route('admin.orders.index') }}"
                                   class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                                    <i class="bi bi-chevron-right me-1 small"></i> Đơn hàng
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.promotions.index') }}"
                                   class="nav-link {{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}">
                                    <i class="bi bi-chevron-right me-1 small"></i> Mã giảm giá
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>

                {{-- ================= NỘI DUNG WEB ================= --}}
                @php
                    $isContentActive = request()->routeIs(
                        'admin.blogs.*',
                        'admin.contacts.*'
                    );
                @endphp

                <li class="nav-item mb-1">
                    <a class="nav-link sidebar-toggle d-flex justify-content-between align-items-center {{ $isContentActive ? 'text-primary' : '' }}"
                       href="#menuContent"
                       role="button"
                       aria-expanded="{{ $isContentActive ? 'true' : 'false' }}">
                        <span>
                            <i class="bi bi-window-stack me-2 text-info"></i> Nội dung Web
                        </span>
                        <i class="bi bi-caret-right-fill small toggle-icon"></i>
                    </a>

                    <div class="sidebar-submenu {{ $isContentActive ? 'open' : '' }}" id="menuContent">
                        <ul class="nav flex-column submenu">

                            <li class="nav-item">
                                <a href="{{ route('admin.blogs.index') }}"
                                   class="nav-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">
                                    <i class="bi bi-chevron-right me-1 small"></i> Tin tức / Bài viết
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.contacts.index') }}"
                                   class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                                    <i class="bi bi-chevron-right me-1 small"></i> Liên hệ
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>

                {{-- ================= QUẢN LÝ TÀI KHOẢN + AI ================= --}}
                @if(Auth::user()->role === 'admin')

                    <li class="nav-item mb-1 mt-2">
                        <a href="{{ route('admin.users.index') }}"
                           class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="bi bi-people-fill me-2 text-secondary"></i> Quản lý Tài khoản
                        </a>
                    </li>

                    <li class="nav-item mb-1 mt-2">
                        <div class="sidebar-section-title">Hệ thống trợ lý AI</div>
                    </li>

                    <li class="nav-item mb-1">
                        <a href="{{ route('admin.ai.history') }}"
                           class="nav-link {{ request()->routeIs('admin.ai.history') ? 'active' : '' }}">
                            <i class="bi bi-calendar2-range me-2 text-info"></i> Lịch sử Chatbot
                        </a>
                    </li>

                    <li class="nav-item mb-1">
                        <a href="{{ route('admin.ai.settings') }}"
                           class="nav-link {{ request()->routeIs('admin.ai.settings') ? 'active' : '' }}">
                            <i class="bi bi-cpu me-2 text-success"></i> Huấn luyện AI
                        </a>
                    </li>

                @endif

            </ul>

            {{-- XEM WEBSITE --}}
            <div class="sidebar-footer p-3 mb-3">
                <a href="{{ route('home') }}"
                   class="nav-link text-dark fw-bold d-flex align-items-center"
                   target="_blank">
                    <i class="bi bi-globe me-2 fs-5"></i> Xem Website
                </a>
            </div>
        </div>

        {{-- ================= MAIN CONTENT ================= --}}
        <div class="col-md-10 p-4 admin-content">

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

{{-- Bootstrap JS CDN: giữ lại để dùng modal/dropdown/toast nếu cần.
     Sidebar KHÔNG dùng Bootstrap collapse nữa nên không bị xung đột. --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

{{-- Sidebar JS custom --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggles = document.querySelectorAll('.sidebar-toggle');

    toggles.forEach(function (toggle) {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();

            const targetSelector = this.getAttribute('href');
            const targetMenu = document.querySelector(targetSelector);

            if (!targetMenu) return;

            const isOpen = targetMenu.classList.contains('open');

            if (isOpen) {
                targetMenu.classList.remove('open');
                this.setAttribute('aria-expanded', 'false');
            } else {
                targetMenu.classList.add('open');
                this.setAttribute('aria-expanded', 'true');
            }
        });
    });
});
</script>

{{-- Realtime badge tin nhắn --}}
<script type="module">
    document.addEventListener('DOMContentLoaded', function () {
        if (!window.Echo) {
            console.warn('Echo chưa được khởi tạo.');
            return;
        }

        window.Echo.channel('chat-channel')
            .listen('.message.sent', (e) => {
                const currentUserId = {{ auth()->id() ?? 'null' }};

                if (e.message.sender_id !== currentUserId) {
                    const badge = document.getElementById('unread-badge');

                    if (badge) {
                        let currentCount = parseInt(badge.innerText) || 0;

                        badge.innerText = currentCount + 1;
                        badge.classList.remove('d-none');

                        badge.style.animation = 'shakeBadge 0.5s';

                        setTimeout(() => {
                            badge.style.animation = '';
                        }, 500);
                    }
                }
            });
    });
</script>

</body>
</html>