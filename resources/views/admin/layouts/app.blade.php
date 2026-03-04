<!DOCTYPE html>
<html>
<head>
    <title>Admin - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid">
    <div class="row min-vh-100">

        <!-- Sidebar -->
        <div class="col-md-2 bg-dark text-white p-3">

            <h4 class="mb-4">ADMIN PANEL</h4>

            <ul class="nav flex-column">

                <li class="nav-item mb-2">
                    <a href="{{ route('admin.dashboard') }}" 
                    class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'fw-bold bg-secondary rounded' : '' }}">
                         Dashboard
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="{{ route('admin.categories.index') }}" 
                       class="nav-link text-white  {{ request()->routeIs('admin.categories.*') ? 'fw-bold bg-secondary rounded' : '' }}">
                         Danh mục
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="{{ route('admin.brands.index') }}" 
                       class="nav-link text-white {{ request()->routeIs('admin.brands.*') ? 'fw-bold bg-secondary rounded' : '' }}">
                         Thương hiệu
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="{{ route('admin.products.index') }}" 
                       class="nav-link text-white {{ request()->routeIs('admin.products.*') ? 'fw-bold bg-secondary rounded' : '' }}">
                         Sản phẩm
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="#" class="nav-link text-white {{ request()->routeIs('admin.orders.*') ? 'fw-bold bg-secondary rounded' : '' }}">
                         Đơn hàng
                    </a>
                </li>

                <li class="nav-item mt-4">
                    <a href="{{ route('home') }}" class="nav-link text-warning">
                         Về trang chủ
                    </a>
                </li>

            </ul>
        </div>

        <!-- Content -->
        <div class="col-md-10 p-4 bg-light">
           @if(session('success'))
             <div class="alert alert-success">
                 {{ session('success') }}
                     </div>
            @endif
            @yield('content')
        </div>

    </div>
</div>

</body>
</html>