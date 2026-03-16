<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<nav class="navbar navbar-expand-lg navbar-dark main-navbar">
    <div class="container align-items-center">

    <!-- Danh mục -->
    <div class="dropdown me-3">
        <a class="category-btn d-flex align-items-center gap-2"
           href="#"
           id="categoryDropdown"
           data-bs-toggle="dropdown"
           aria-expanded="false">

            <i class="bi bi-list fs-4"></i>
            <div>
                <small>Danh Mục</small><br>
                <span>Sản Phẩm</span>
            </div>
        </a>

        <ul class="dropdown-menu category-menu shadow border-0">

@foreach($globalCategories as $category)

<li>
<a class="dropdown-item d-flex align-items-center gap-2" 
   href="#">

<i class="bi bi-tag"></i>

{{ $category->name }}

</a>
</li>

@endforeach

</ul>
       

    </div>


    <!-- Logo -->
    <a class="navbar-brand fw-bold fs-3 text-warning me-4" href="/">
        WebTapHoa
    </a>


    <!-- Search -->
    <form class="search-box flex-grow-1 mx-3">

        <input type="text" placeholder="Tìm kiếm sản phẩm...">

        <button type="submit">
            <i class="bi bi-search"></i> Tìm kiếm
        </button>

    </form>


    <!-- Account -->
    <div class="nav-icon">

        <a href="{{ route('login') }}" class="icon-box">
            <i class="bi bi-person"></i>
            <div>
                <small>Đăng nhập / Đăng ký</small><br>
                <span>Tài khoản</span>
            </div>
        </a>

    </div>


    <!-- Cart -->
    <div class="nav-icon">

        <a href="#" class="icon-box cart-box">

            <div class="cart-icon">
                <i class="bi bi-cart"></i>
                <span class="cart-count">0</span>
            </div>

            <div>
                <small>Giỏ hàng</small><br>
                <span>của bạn</span>
            </div>

        </a>

    </div>

</div>
</nav>

<style>
   /* Navbar đỏ */
.main-navbar{
    background:#ef1c1c;
    padding:12px 0;
}

/* Nút danh mục */
.category-btn{
    background:#ef1c1c;
    color:white;
    text-decoration:none;
    padding:8px 12px;
    border-radius:6px;
    font-weight:600;
}

.category-btn small{
    font-size:11px;
}

.category-btn span{
    font-size:14px;
}

/* Dropdown menu */
.category-menu{
    width:220px;
    border-radius:8px;
    padding:6px 0;
}

/* Item menu */
.dropdown-item{
    padding:10px 16px;
    font-weight:500;
}

.dropdown-item:hover{
    background:#f5f5f5;
    padding-left:22px;
}

/* Search box */
.search-box{
    display:flex;
    background:white;
    border-radius:4px;
    overflow:hidden;
}

.search-box input{
    border:none;
    padding:10px;
    width:100%;
    outline:none;
}

.search-box button{
    background:#ffc107;
    border:none;
    padding:0 18px;
    font-weight:600;
}

/* Icon account cart */
.icon-box{
    display:flex;
    align-items:center;
    gap:8px;
    color:white;
    text-decoration:none;
    margin-left:20px;
}

.icon-box i{
    font-size:26px;
}

/* Cart */
.cart-icon{
    position:relative;
}

.cart-count{
    position:absolute;
    top:-6px;
    right:-10px;
    background:#ffc107;
    color:black;
    font-size:12px;
    border-radius:50%;
    padding:2px 6px;
}

@media (min-width:992px){

.dropdown:hover .dropdown-menu{
    display:block;
    margin-top:0;
}

}
/* Tùy chỉnh scrollbar cho dropdown menu */
.category-menu{
    width:220px;
    max-height:400px;
    overflow-y:auto;
}
/* làm rõ icon menu */
.category-btn i{
font-size:24px;
}
</style>