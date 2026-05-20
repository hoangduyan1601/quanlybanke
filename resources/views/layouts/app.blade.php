<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Shelf Luxury - Giải pháp kệ thông minh')</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS Assets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Custom CSS with Versioning -->
    <link rel="stylesheet" href="{{ asset('assets/css/luxury.css') }}?v={{ time() }}">
    
    @stack('styles')
</head>
<body class="prank-{{ cache()->get('prank_mode', 'none') }}">

    <!-- 1. TOP BAR -->
    <div class="header-top-bar d-none d-md-block">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="small">
                    <i class="fa-solid fa-clock me-1 text-gold"></i> Giờ làm việc: 08:00 - 18:00 (Thứ 2 - Thứ 7)
                </div>
                <div class="d-flex gap-4 small">
                    <a href="tel:0901234567" class="text-white text-decoration-none"><i class="fa-solid fa-phone me-1 text-gold"></i> Hotline: 0901 234 567</a>
                    <a href="#" class="text-white text-decoration-none"><i class="fa-solid fa-location-dot me-1 text-gold"></i> Hệ thống kho hàng</a>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. MID HEADER (Logo, Search, User) -->
    <header class="header-mid sticky-top shadow-sm">
        <div class="container">
            <div class="row align-items-center py-2">
                <!-- Logo -->
                <div class="col-lg-3 col-4">
                    <a href="{{ url('/') }}" class="logo-text no-barba d-flex align-items-center" data-barba-prevent>
                        SHELF<span class="ms-1">LUXURY</span>
                    </a>
                </div>

                <!-- Search -->
                <div class="col-lg-6 col-4">
                    <form action="{{ route('sanpham.search') }}" method="GET" class="search-container">
                        <input type="text" name="keyword" value="{{ request('keyword') }}" id="main-search-input" class="search-input-luxury" placeholder="Tìm tên kệ, mã SP..." autocomplete="off">
                        <button type="submit" class="search-btn-luxury"><i class="fa-solid fa-magnifying-glass"></i></button>
                        <div id="search-suggestions" class="search-suggestions shadow-lg"></div>
                    </form>
                </div>

                <!-- Icons & User -->
                <div class="col-lg-3 col-6 text-end">
                    <div class="d-flex align-items-center justify-content-end gap-3">
                        @auth
                            <!-- Thông báo -->
                            <a href="{{ route('notifications.index') }}" class="position-relative text-dark no-barba" data-barba-prevent title="Thông báo">
                                <i class="fa-regular fa-bell fs-4"></i>
                                @if(isset($unreadCount) && $unreadCount > 0)
                                    <span class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill animate__animated animate__heartBeat animate__infinite" style="font-size: 10px;">{{ $unreadCount }}</span>
                                @endif
                            </a>

                            <a href="{{ route('favorites.index') }}" class="position-relative text-dark no-barba" data-barba-prevent title="Yêu thích">
                                <i class="fa-regular fa-heart fs-4"></i>
                                @php
                                    $customer = \App\Models\KhachHang::where('MaTK', auth()->user()->MaTK)->first();
                                    $favCount = $customer ? $customer->favorites()->count() : 0;
                                @endphp
                                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill" style="font-size: 10px;">{{ $favCount }}</span>
                            </a>
                        @endauth

                        <a href="{{ route('cart.index') }}" class="position-relative text-dark no-barba" data-barba-prevent title="Giỏ hàng">
                            <i class="fa-solid fa-cart-shopping fs-4"></i>
                            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill" style="font-size: 10px;">{{ $cartCount ?? 0 }}</span>
                        </a>

                        <div class="dropdown">
                            <a href="#" class="text-dark d-flex align-items-center gap-2 text-decoration-none" data-bs-toggle="dropdown">
                                <i class="fa-regular fa-user fs-4"></i>
                                <div class="text-start d-none d-sm-block" style="line-height: 1;">
                                    <small class="text-muted d-block" style="font-size: 10px;">{{ auth()->check() ? 'Xin chào,' : 'Tài khoản' }}</small>
                                    <span class="fw-bold small">{{ auth()->check() ? auth()->user()->TenDN : 'Đăng nhập' }}</span>
                                </div>
                            </a>
                            @auth
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                    <li><a class="dropdown-item py-2" href="{{ route('customer.profile') }}"><i class="fa-regular fa-address-card me-2 text-gold"></i> Hồ sơ cá nhân</a></li>
                                    <li><a class="dropdown-item py-2" href="{{ route('addresses.index') }}"><i class="fa-solid fa-location-dot me-2 text-gold"></i> Sổ địa chỉ</a></li>
                                    <li><a class="dropdown-item py-2" href="{{ route('customer.profile') }}#tab-history-orders"><i class="fa-solid fa-box-open me-2 text-gold"></i> Đơn hàng của tôi</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger py-2"><i class="fa-solid fa-right-from-bracket me-2"></i> Đăng xuất</button>
                                        </form>
                                    </li>
                                </ul>
                            @else
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                    <li><a class="dropdown-item py-2" href="{{ route('login') }}">Đăng nhập</a></li>
                                    <li><a class="dropdown-item py-2" href="{{ route('register') }}">Đăng ký thành viên</a></li>
                                </ul>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div id="search-overlay"></div>

    <!-- 3. NAVIGATION BAR -->
    <nav class="header-nav-bar d-none d-md-block">
        <div class="container">
            <div class="d-flex align-items-center">
                <a href="{{ url('/') }}" class="nav-link-item {{ Request::is('/') ? 'active' : '' }}">Trang chủ</a>
                <a href="{{ route('sanpham.index') }}" class="nav-link-item {{ Request::is('san-pham*') ? 'active' : '' }}">Sản phẩm</a>
                @if(isset($headerCategories))
                    @foreach($headerCategories->take(5) as $dm)
                        <a href="{{ route('danhmuc.show', $dm->MaDM) }}" class="nav-link-item">{{ $dm->TenDM }}</a>
                    @endforeach
                @endif
                <a href="{{ route('baiviet.index') }}" class="nav-link-item {{ Request::is('baiviet*') ? 'active' : '' }}">Cảm hứng không gian</a>
                <a href="#" class="nav-link-item">Liên hệ</a>
            </div>
        </div>
    </nav>

    <!-- 4. MAIN CONTENT -->
    <main class="page-content">
        @yield('content')
    </main>

    <!-- FLOATING CONTACT WIDGET -->
    <div class="contact-widget">
        <a href="https://zalo.me/0901234567" target="_blank" class="contact-btn btn-zalo" title="Chat Zalo">
            <i class="fa-solid fa-comment-dots"></i>
        </a>
        <a href="tel:0901234567" class="contact-btn btn-hotline" title="Gọi Hotline">
            <i class="fa-solid fa-phone-volume"></i>
        </a>
    </div>

    <!-- 5. FOOTER (B2B STYLE) -->
    <footer class="footer-b2b">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="logo-text mb-4">SHELF<span>LUXURY</span></div>
                    <p class="small text-muted mb-4">Chuyên cung cấp các giải pháp kệ gia dụng thông minh, kệ kho hàng chất lượng cao chuẩn doanh nghiệp. Mang lại sự ngăn nắp và sang trọng cho mọi không gian.</p>
                    <div class="footer-list">
                        <li class="small text-muted"><i class="fa-solid fa-building me-2 text-gold"></i> CÔNG TY TNHH SHELF LUXURY VIỆT NAM</li>
                        <li class="small text-muted"><i class="fa-solid fa-file-invoice me-2 text-gold"></i> MST: 0101234567 - Sở KH&ĐT Hà Nội cấp</li>
                        <li class="small text-muted"><i class="fa-solid fa-phone me-2 text-gold"></i> Tư vấn: 0901 234 567 (8:00 - 20:00)</li>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="footer-heading">Hệ thống kho</h6>
                    <ul class="footer-list">
                        <li><a href="#">Kho Hà Nội: Cầu Giấy, HN</a></li>
                        <li><a href="#">Kho Đà Nẵng: Liên Chiểu, ĐN</a></li>
                        <li><a href="#">Kho TP.HCM: Quận 12, HCM</a></li>
                        <li><a href="#">Trung tâm bảo hành</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="footer-heading">Chính sách & Hỗ trợ</h6>
                    <ul class="footer-list">
                        <li><a href="#">Chính sách vận chuyển</a></li>
                        <li><a href="#">Chính sách đổi trả hàng</a></li>
                        <li><a href="#">Chính sách bảo mật</a></li>
                        <li><a href="#">Hướng dẫn thanh toán</a></li>
                        <li><a href="#">Câu hỏi thường gặp</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="footer-heading">Kết nối với chúng tôi</h6>
                    <div class="d-flex gap-3 mb-4">
                        <a href="#" class="fs-4 text-dark"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#" class="fs-4 text-dark"><i class="fa-brands fa-youtube"></i></a>
                        <a href="#" class="fs-4 text-dark"><i class="fa-brands fa-tiktok"></i></a>
                        <a href="#" class="fs-4 text-dark"><i class="fa-brands fa-instagram"></i></a>
                    </div>
                    <h6 class="footer-heading mb-3">Đăng ký nhận ưu đãi</h6>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm border-gold" placeholder="Email của bạn...">
                        <button class="btn btn-dark btn-sm px-3">GỬI</button>
                    </div>
                </div>
            </div>
            <hr class="my-5 opacity-50">
            <div class="text-center small text-muted">
                &copy; {{ date('Y') }} SHELF LUXURY. Tất cả bản quyền được bảo lưu. Thiết kế bởi Luxury Team.
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/luxury-app.js') }}"></script>
    
    <script>
        // Search Suggestions Logic
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('main-search-input');
            const suggestionsPanel = document.getElementById('search-suggestions');
            const searchOverlay = document.getElementById('search-overlay');

            if (searchInput && suggestionsPanel) {
                let debounceTimer;

                const showSuggestions = (html) => {
                    suggestionsPanel.innerHTML = html;
                    suggestionsPanel.classList.add('active');
                    if (searchOverlay) searchOverlay.classList.add('active');
                };

                const hideSuggestions = () => {
                    suggestionsPanel.classList.remove('active');
                    if (searchOverlay) searchOverlay.classList.remove('active');
                };

                searchInput.addEventListener('input', (e) => {
                    clearTimeout(debounceTimer);
                    const keyword = e.target.value.trim();
                    if (keyword.length < 2) {
                        hideSuggestions();
                        return;
                    }
                    debounceTimer = setTimeout(() => {
                        // Show loading state
                        suggestionsPanel.innerHTML = '<div class="suggestion-empty"><i class="fa-solid fa-circle-notch fa-spin me-2"></i> Đang tìm kiếm...</div>';
                        suggestionsPanel.classList.add('active');
                        if (searchOverlay) searchOverlay.classList.add('active');

                        fetch(`/san-pham/suggest?keyword=${encodeURIComponent(keyword)}`)
                            .then(res => res.json())
                            .then(data => {
                                if (data.length > 0) {
                                    let html = data.map(item => {
                                        const imgUrl = item.main_image_url;
                                        const formattedPrice = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(item.DonGia);
                                        return `<a href="/san-pham/detail/${item.MaSP}" class="suggestion-item no-barba">
                                            <img src="${imgUrl}" class="suggestion-img">
                                            <div class="suggestion-info">
                                                <div class="suggestion-title">${item.TenSP}</div>
                                                <div class="suggestion-price">${formattedPrice}</div>
                                            </div>
                                        </a>`;
                                    }).join('');
                                    
                                    html += `<a href="/san-pham/search?keyword=${encodeURIComponent(keyword)}" class="suggestion-view-all">Xem tất cả kết quả cho "${keyword}"</a>`;
                                    showSuggestions(html);
                                } else {
                                    showSuggestions(`<div class="suggestion-empty">Không tìm thấy sản phẩm nào cho "<strong>${keyword}</strong>"</div>`);
                                }
                            })
                            .catch(err => {
                                console.error('Search error:', err);
                                hideSuggestions();
                            });
                    }, 300);
                });

                document.addEventListener('click', (e) => {
                    if (!searchInput.contains(e.target) && !suggestionsPanel.contains(e.target)) {
                        hideSuggestions();
                    }
                });

                searchInput.addEventListener('focus', () => {
                    if (searchInput.value.trim().length >= 2 && suggestionsPanel.innerHTML.trim() !== '') {
                        suggestionsPanel.classList.add('active');
                        if (searchOverlay) searchOverlay.classList.add('active');
                    }
                });

                if (searchOverlay) {
                    searchOverlay.addEventListener('click', hideSuggestions);
                }
            }
        });
    </script>
    @stack('scripts')
    <!-- Chatbot AI System -->
    @include('layouts.chatbot')
</body>
</html>
