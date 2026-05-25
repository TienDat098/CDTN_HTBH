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
            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('category.show', $category->slug) }}">
                <i class="bi bi-tag"></i>
                {{ $category->name }}
            </a>
            </li>

            @endforeach

        </ul>
       

    </div>


    <!-- Logo -->
    <a class="navbar-brand fw-bold fs-3 text-primary me-4" href="/">

        WebTapHoa
    </a>

          <!-- Search Box -->
<div class="d-flex flex-grow-1 justify-content-center"> 
    <div class="search-wrapper position-relative w-100" style="max-width: 500px;">
        <form action="{{ route('search.index') }}" method="GET" class="search-box">
            <input type="text" name="keyword" id="search-input" placeholder="Tìm kiếm sản phẩm..." autocomplete="off">
            <button type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>
        <!-- Khung gợi ý được đưa ra ngoài form -->
        <div id="search-suggest" class="search-suggest"></div>
    </div>
</div>





    <!-- Account -->
    <!-- Account -->
        <div class="nav-icon dropdown">
            @auth
                <a href="#" class="icon-box dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle text-warning"></i>
                    <div>
                        <small>Xin chào,</small><br>
                        <span class="fw-bold">{{ Auth::user()->name }}</span>
                    </div>
                </a>
                
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                    <!-- 1. Menu dành riêng cho Admin/Staff -->
                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'staff')
                        <li>
                            <a class="dropdown-item py-2 fw-bold text-danger bg-light" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i>Trang quản trị (Admin)
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                    @endif
                    
                    <!-- 2. Menu dùng chung cho TẤT CẢ mọi người (Hồ sơ & Đơn mua) -->
                    <li>
                        <a class="dropdown-item py-2 fw-bold text-primary" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person-vcard me-2"></i>Hồ sơ cá nhân
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2 fw-bold text-secondary" href="{{ route('profile.orders') }}">
                            <i class="bi bi-box-seam me-2"></i>Lịch sử đơn mua
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    
                    <!-- Nút Đăng xuất -->
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item py-2 text-danger" type="submit">
                                <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                            </button>
                        </form>
                    </li>
                </ul>

            @else
                <a href="{{ route('login') }}" class="icon-box">
                    <i class="bi bi-person"></i>
                    <div>
                        <small>Đăng nhập / Đăng ký</small><br>
                        <span>Tài khoản</span>
                    </div>
                </a>
            @endauth
        </div>


    <!-- Cart -->
    <div class="nav-icon">
            <a href="{{ route('cart.index') }}" class="icon-box cart-box ms-4"> <div class="cart-icon me-2">
                    <i class="bi bi-cart fs-3"></i> <span class="cart-count">
                        {{ session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : 0 }}
                    </span>
                </div>

                <div>
                    <small>Giỏ hàng</small><br>
                    <span>của bạn</span>
                </div>
            </a>
        </div>

  </div>
</nav>

    <div class="bottom-menu bg-white border-bottom shadow-sm d-none d-lg-block">
    <div class="container">
        <ul class="nav justify-content-center">
            <li class="nav-item">
                <a class="nav-link active text-uppercase fw-bold" href="/">Trang chủ</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-uppercase fw-bold" href="{{ route('product.index') }}">Sản phẩm</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('livestream.index') ? 'active text-danger fw-bold' : '' }}"
                href="{{ route('livestream.index') }}">
                    <i class="bi bi-broadcast-pin me-1"></i> LIVESTREAM
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-uppercase fw-bold" href="{{ route('blogs.index') }}">Blog</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-uppercase fw-bold" href="{{ route('contacts.index') }}">Liên hệ</a>
            </li>
        </ul>
    </div>
</div>


<style>
   /* Navbar đỏ */
.main-navbar{
    background:#eff6ff;
    padding:12px 0;
    border-bottom:1px solid #dbeafe;
}


/* Nút danh mục */
.category-btn{
    background:#dbeafe;
    color:#1e3a8a;
    text-decoration:none;
    padding:8px 12px;
    border-radius:6px;
    font-weight:600;
}

.category-btn:hover{
    background:#bfdbfe;
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

.search-box {
    display: flex;
    background: white;
    border-radius: 6px;
    overflow: hidden; 
    border: 1px solid #dbeafe;
    width: 100%;
}

.search-box input {
    border: none;
    padding: 10px 15px;
    width: 100%;
    outline: none;
}

.search-box button {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 0 20px;
    font-weight: 600;
}


/* Icon account cart */
.icon-box{
    display:flex;
    align-items:center;
    gap:8px;
    color:#1e3a8a;
    text-decoration:none;
    margin-left:20px;
}

.icon-box:hover{
    color:#3b82f6;
}

/* Cart */
.cart-icon{
    position:relative;
    display: inline-block;
}

.cart-count{
    position:absolute;
    top:-5px;
    right:-12px;
    background:#3b82f6;
    color:white;
    font-size:11px;
    font-weight: bold;
    border-radius:50%;
    padding:2px 6px;
    min-width: 18px;
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



/*nav chỗ đăng nhập*/

.nav-icon .dropdown-menu {
    z-index: 1050 !important;
}


@media (min-width: 992px) {
    .dropdown:hover .dropdown-menu {
        display: block;
        margin-top: 0;
        top: 100%; 
    }
    
    
    .dropdown:hover .dropdown-menu::before {
        content: "";
        position: absolute;
        top: -15px; 
        left: 0;
        width: 100%;
        height: 15px;
        background: transparent;
    }
}


.dropdown-item form {
    margin: 0;
}
.dropdown-item form button {
    background: transparent;
    border: none;
    width: 100%;
    text-align: left;
    padding: 0;
}

/*search suggest*/
.search-suggest {
    position: absolute;
    top: calc(100% + 5px); /* Nằm ngay dưới thanh search, cách 5px */
    left: 0;
    width: 100%;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    z-index: 9999; 
    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); /* Đổ bóng nổi bật */
    max-height: 400px;
    overflow-y: auto;
    display: none; 
}

/* Từng item trong danh sách */
.search-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 15px;
    cursor: pointer;
    border-bottom: 1px solid #f3f4f6;
    text-decoration: none;
    transition: background-color 0.2s;
}

.search-item:last-child {
    border-bottom: none;
}

.search-item:hover {
    background: #f8fafc;
}
.search-item img {
    width: 45px;
    height: 45px;
    object-fit: contain; /* Giữ nguyên tỷ lệ ảnh */
    border-radius: 4px;
    background-color: #fff;
    border: 1px solid #eee;
}

/* Thông tin sản phẩm */
.search-item-info {
    display: flex;
    flex-direction: column;
    flex: 1;
    overflow: hidden;
}

.search-item-info .product-name {
    font-size: 14px;
    color: #333;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis; /* Thêm dấu ... nếu tên quá dài */
    margin-bottom: 4px;
}

.search-item-info .price {
    color: #dc3545; /* Màu đỏ cho giá */
    font-weight: 700;
    font-size: 13px;
}


/* CSS cho thanh menu dưới */
.bottom-menu {
    padding: 0;
}

.bottom-menu .nav-item {
    margin: 0 20px;
}

.bottom-menu .nav-link {
    color: #333; /* Màu chữ đen xám */
    font-size: 15px;
    padding: 12px 15px;
    transition: color 0.2s ease;
}

.bottom-menu .nav-link:hover, 
.bottom-menu .nav-link.active {
    color: #dc3545 !important; /* Đổi màu đỏ khi hover giống mẫu */
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('search-input');
    const box = document.getElementById('search-suggest');

    if(!input) return;

    input.addEventListener('keyup', function() {
        let keyword = this.value.trim();

        if(keyword.length < 2){
            box.style.display = 'none';
            box.innerHTML = '';
            return;
        }

        // CHÚ Ý: Dùng dấu backtick (`) để bọc URL và HTML
        fetch(`/search/suggest?keyword=${keyword}`)
            .then(res => res.json())
            .then(data => {
                let html = '';
                if(data.length > 0) {
                    data.forEach(item => {
                        let formattedPrice = new Intl.NumberFormat('vi-VN').format(item.price);
                        
                        html += `
                            <div class="search-item" onclick="window.location='/san-pham/${item.slug}'">
                                <img src="${item.thumbnail}" onerror="this.src='/images/no-image.png'">
                                <div class="search-item-info">
                                    <span class="product-name">${item.name}</span>
                                    <span class="price">${formattedPrice} đ</span>
                                </div>
                            </div>
                        `;                    
                    });
                    box.innerHTML = html;
                    box.style.display = 'block';
                }
                 else {
                    box.innerHTML = `<div class="p-3 text-muted text-center"><small>Không tìm thấy sản phẩm</small></div>`;
                    box.style.display = 'block';
                }
            })
            .catch(err => console.error("Lỗi search:", err));
    });

    // Ẩn box khi click ra ngoài
    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !box.contains(e.target)) {
            box.style.display = 'none';
        }
    });
});
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>