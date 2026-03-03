<!DOCTYPE html>
<html>
<head>
    <title>@yield('title') - Cửa Hàng Tạp Hóa </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">🛒 GROCERY STORE</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link active" href="{{ route('home') }}">Trang chủ</a>
                <a class="nav-link" href="#">Giỏ hàng</a>
                <a class="nav-link" href="#">Đăng nhập</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        @yield('content')
    </div>
    
    <footer class="text-center mt-5 py-3 text-muted">
        <hr>
        <p>&copy; 2026 - Hệ thống bán hàng</p>
    </footer>
</body>
</html>