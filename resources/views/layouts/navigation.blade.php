<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">

        <a class="navbar-brand" href="/">Laravel Shop</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}">Trang chủ</a>
            </li>

        @auth
        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'staff')

        <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
        Trang quản trị
        </a>
        </li>

        @endif
        @endauth

        </ul>

        <ul class="navbar-nav">

    @auth

        <li class="nav-item">
            <span class="nav-link">
                Xin chào {{ Auth::user()->name }}
            </span>
        </li>
        <li class="nav-item">
        <form method="POST" action="{{ route('logout') }}">
    @csrf
            <button class="btn btn-danger btn-sm mt-1">Đăng xuất</button>
        </form>
        </li>

        @else

        <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">Đăng nhập</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('register') }}">Đăng ký</a>
        </li>

    @endauth

        </ul>

        </div>

        </div>
</nav>