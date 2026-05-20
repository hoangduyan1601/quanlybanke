@extends('layouts.app')

@section('content')
<!-- 1. HERO SLIDER -->
<div id="homeHero" class="carousel slide carousel-fade" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#homeHero" data-bs-slide-to="0" class="active" aria-current="true"></button>
        <button type="button" data-bs-target="#homeHero" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#homeHero" data-bs-slide-to="2"></button>
    </div>
    <div class="carousel-inner">
        <!-- Slide 1: Enterprise focus -->
        <div class="carousel-item active" style="height: 650px;">
            <div class="hero-overlay" style="background: linear-gradient(90deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0.2) 100%), url('https://images.unsplash.com/photo-1586023492125-27b2c045efd7?q=80&w=2000&auto=format&fit=crop') center/cover;"></div>
            <div class="container h-100 d-flex align-items-center">
                <div class="hero-content text-white" style="max-width: 650px;">
                    <h5 class="text-gold fw-bold text-uppercase mb-3 tracking-widest animate__animated animate__fadeInDown">Giải pháp lưu trữ thông minh</h5>
                    <h1 class="display-3 fw-900 mb-4 animate__animated animate__fadeInLeft">HỆ THỐNG KỆ <br> <span class="text-gold">CHUẨN DOANH NGHIỆP</span></h1>
                    <p class="lead mb-5 opacity-90 animate__animated animate__fadeInUp animate__delay-1s">Chịu tải cực lớn, độ bền trên 10 năm. Thiết kế tối ưu cho kho bãi và showroom hiện đại. Lắp đặt trọn gói toàn quốc.</p>
                    <div class="d-flex gap-3 animate__animated animate__fadeInUp animate__delay-1s">
                        <a href="{{ route('sanpham.index') }}" class="btn btn-gold btn-lg px-5 py-3 fw-bold rounded-0">XEM BÁO GIÁ</a>
                        <a href="#" class="btn btn-outline-light btn-lg px-5 py-3 fw-bold rounded-0">TƯ VẤN NGAY</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 2: Luxury Home focus -->
        <div class="carousel-item" style="height: 650px;">
            <div class="hero-overlay" style="background: linear-gradient(270deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0.2) 100%), url('https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?q=80&w=2000&auto=format&fit=crop') center/cover;"></div>
            <div class="container h-100 d-flex align-items-center justify-content-end">
                <div class="hero-content text-white text-end" style="max-width: 650px;">
                    <h5 class="text-gold fw-bold text-uppercase mb-3 tracking-widest">Nâng tầm không gian sống</h5>
                    <h1 class="display-3 fw-900 mb-4">KỆ GIA DỤNG <br> <span class="text-gold">SANG TRỌNG</span></h1>
                    <p class="lead mb-5 opacity-90">Sơn tĩnh điện cao cấp, chống gỉ sét tuyệt đối. Sự kết hợp hoàn hảo giữa công năng và tính thẩm mỹ cho ngôi nhà của bạn.</p>
                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{ route('sanpham.index') }}" class="btn btn-gold btn-lg px-5 py-3 fw-bold rounded-0">BỘ SƯU TẬP 2024</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 3: Promotion focus -->
        <div class="carousel-item" style="height: 650px;">
            <div class="hero-overlay" style="background: radial-gradient(circle, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.9) 100%), url('https://images.unsplash.com/photo-1517502884422-41eaead166d4?q=80&w=2000&auto=format&fit=crop') center/cover;"></div>
            <div class="container h-100 d-flex align-items-center justify-content-center text-center">
                <div class="hero-content text-white" style="max-width: 800px;">
                    <h5 class="text-gold fw-bold text-uppercase mb-3 tracking-widest">Ưu đãi độc quyền tháng 5</h5>
                    <h1 class="display-2 fw-900 mb-4">GIẢM ĐẾN <span class="text-gold">30%</span> <br> TẤT CẢ SẢN PHẨM</h1>
                    <p class="lead mb-5 opacity-90">Miễn phí vận chuyển nội thành cho đơn hàng từ 5.000.000đ. Bảo hành 5 năm lỗi 1 đổi 1.</p>
                    <a href="{{ route('sanpham.index') }}" class="btn btn-light btn-lg px-5 py-3 fw-bold rounded-0">MUA SẮM NGAY</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#homeHero" data-bs-slide="prev">
        <span class="carousel-control-prev-icon p-3 bg-dark rounded-circle" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#homeHero" data-bs-slide="next">
        <span class="carousel-control-next-icon p-3 bg-dark rounded-circle" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<!-- 1.1 FEATURES BAR -->
<section class="features-bar py-4 bg-white shadow-sm position-relative" style="margin-top: -50px; z-index: 10;">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-3 border-end">
                <div class="d-flex align-items-center justify-content-center gap-3">
                    <i class="fa-solid fa-truck-fast fs-2 text-gold"></i>
                    <div class="text-start">
                        <h6 class="mb-0 fw-bold">Giao hàng nhanh</h6>
                        <small class="text-muted">Nội thành trong 2h</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 border-end">
                <div class="d-flex align-items-center justify-content-center gap-3">
                    <i class="fa-solid fa-shield-halved fs-2 text-gold"></i>
                    <div class="text-start">
                        <h6 class="mb-0 fw-bold">Bảo hành 5 năm</h6>
                        <small class="text-muted">Lỗi 1 đổi 1 tận nhà</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 border-end">
                <div class="d-flex align-items-center justify-content-center gap-3">
                    <i class="fa-solid fa-screwdriver-wrench fs-2 text-gold"></i>
                    <div class="text-start">
                        <h6 class="mb-0 fw-bold">Miễn phí lắp đặt</h6>
                        <small class="text-muted">Cho đơn hàng dự án</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex align-items-center justify-content-center gap-3">
                    <i class="fa-solid fa-headset fs-2 text-gold"></i>
                    <div class="text-start">
                        <h6 class="mb-0 fw-bold">Hỗ trợ 24/7</h6>
                        <small class="text-muted">Kỹ thuật viên chuyên nghiệp</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. PREMIUM CATEGORY GRID -->
<section class="py-5 bg-white">
    <div class="container pt-5">
        <div class="section-premium-title text-center mx-auto" style="max-width: 600px;">
            <h5 class="text-gold fw-bold text-uppercase mb-2" style="font-size: 14px; letter-spacing: 2px;">Danh mục nổi bật</h5>
            <h2 class="mb-4">GIẢI PHẠP THEO KHÔNG GIAN</h2>
            <div class="title-line mx-auto mb-5"></div>
        </div>
        <div class="row g-4 justify-content-center">
            @php
                $catImages = [
                    'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?q=80&w=1000&auto=format&fit=crop', // Kitchen
                    'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?q=80&w=1000&auto=format&fit=crop', // Living room
                    'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?q=80&w=1000&auto=format&fit=crop', // Warehouse
                    'https://images.unsplash.com/photo-1517502884422-41eaead166d4?q=80&w=1000&auto=format&fit=crop', // Office
                    'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?q=80&w=1000&auto=format&fit=crop'  // Bathroom
                ];
            @endphp
            @foreach($danhmucs->take(5) as $index => $dm)
            <div class="col-lg-2 col-md-4 col-6">
                <a href="{{ route('danhmuc.show', $dm->MaDM) }}" class="category-box-modern shadow-sm">
                    <div class="img-container">
                        <img src="{{ $catImages[$index % count($catImages)] }}" alt="{{ $dm->TenDM }}">
                    </div>
                    <div class="content">
                        <h6 class="fw-bold mb-1">{{ $dm->TenDM }}</h6>
                        <div class="count small opacity-75">100+ sản phẩm</div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- 3. HOT PRODUCTS BLOCK (PREMIUM CARD) -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div class="section-premium-title mb-0">
                <h5 class="text-gold fw-bold text-uppercase mb-2" style="font-size: 14px; letter-spacing: 2px;">Lựa chọn hàng đầu</h5>
                <h2 class="mb-0">SẢN PHẨM BÁN CHẠY NHẤT</h2>
            </div>
            <a href="{{ route('sanpham.index') }}" class="btn btn-outline-dark btn-sm rounded-0 px-4 py-2 text-uppercase fw-bold">Xem tất cả <i class="fa-solid fa-arrow-right ms-2"></i></a>
        </div>
        <div class="row g-4">
            @foreach($bestSellers->take(8) as $sp)
            <div class="col-xl-3 col-md-4 col-6">
                <div class="product-card-premium shadow-sm h-100 d-flex flex-column bg-white border-0 overflow-hidden">
                    <div class="img-wrap position-relative">
                        @if($sp->khuyen_mai_active)
                            <span class="badge-sale">-{{ (int)$sp->khuyen_mai_active->PhanTramGiam }}%</span>
                        @endif
                        <div class="action-buttons">
                            <button class="btn-action" title="Yêu thích"><i class="fa-regular fa-heart"></i></button>
                            <button class="btn-action" title="Xem nhanh"><i class="fa-solid fa-eye"></i></button>
                        </div>
                        <a href="{{ route('sanpham.detail', $sp->MaSP) }}" class="d-block h-100 w-100 p-4">
                            <img src="{{ $sp->main_image_url }}" 
                                 alt="{{ $sp->TenSP }}" class="img-fluid main-img">
                        </a>
                    </div>
                    <div class="info-wrap p-4 text-center">
                        <small class="text-uppercase text-muted fw-bold mb-2 d-block tracking-widest" style="font-size: 10px;">{{ $sp->danhmuc->TenDM ?? 'Gia dụng' }}</small>
                        <h6 class="fw-bold mb-3"><a href="{{ route('sanpham.detail', $sp->MaSP) }}" class="text-dark">{{ $sp->TenSP }}</a></h6>
                        
                        <div class="price-wrap mb-4">
                            <span class="price-current fw-bold text-danger fs-5">{{ number_format($sp->gia_hien_tai, 0, ',', '.') }}₫</span>
                            @if($sp->khuyen_mai_active)
                                <span class="price-old text-muted text-decoration-line-through ms-2 small">{{ number_format($sp->DonGia, 0, ',', '.') }}₫</span>
                            @endif
                        </div>
                        
                        <button onclick="addToCartIndex({{ $sp->MaSP }})" class="btn btn-dark w-100 rounded-0 py-3 text-uppercase fw-bold small shadow-sm hover-gold-bg">
                            <i class="fa-solid fa-cart-plus me-2"></i> THÊM VÀO GIỎ
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- 4. CTA BANNER -->
<section class="cta-banner py-5" style="background: fixed url('https://images.unsplash.com/photo-1584622650111-993a426fbf0a?q=80&w=2000&auto=format&fit=crop') center/cover;">
    <div class="overlay py-5" style="background: rgba(0,0,0,0.6);">
        <div class="container text-center text-white py-5">
            <h2 class="display-5 fw-800 mb-4">THIẾT KỆ THEO YÊU CẦU DỰ ÁN</h2>
            <p class="lead mb-5 mx-auto" style="max-width: 700px;">Chúng tôi cung cấp dịch vụ đo đạc, thiết kế 3D và thi công lắp đặt trọn gói hệ thống kệ kho, kệ siêu thị cho doanh nghiệp của bạn.</p>
            <a href="#" class="btn btn-gold btn-lg px-5 py-3 fw-bold rounded-0">LIÊN HỆ TƯ VẤN KỸ THUẬT</a>
        </div>
    </div>
</section>

<!-- 5. LATEST NEWS / PROJECTS -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="section-premium-title text-center mx-auto mb-5" style="max-width: 600px;">
            <h5 class="text-gold fw-bold text-uppercase mb-2" style="font-size: 14px; letter-spacing: 2px;">Tin tức & Xu hướng</h5>
            <h2 class="mb-4">CẢM HỨNG KHÔNG GIAN</h2>
            <div class="title-line mx-auto"></div>
        </div>
        <div class="row g-4">
            @foreach($latestArticles->take(3) as $bv)
            <div class="col-md-4">
                <div class="blog-card shadow-sm h-100 border-0 overflow-hidden">
                    <div class="img-wrap position-relative overflow-hidden">
                        <img src="{{ $bv->HinhAnh ? (Str::startsWith($bv->HinhAnh, 'http') ? $bv->HinhAnh : asset($bv->HinhAnh)) : 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=1000&auto=format&fit=crop' }}" class="img-fluid w-100" style="height: 250px; object-fit: cover; transition: 0.6s;">
                        <div class="date-badge">{{ \Carbon\Carbon::parse($bv->NgayDang)->format('d/m') }}</div>
                    </div>
                    <div class="p-4 bg-white">
                        <h5 class="fw-bold mb-3"><a href="{{ route('baiviet.show', $bv->Slug) }}" class="text-dark">{{ $bv->TieuDe }}</a></h5>
                        <p class="small text-muted mb-4">{{ Str::limit($bv->TomTat, 120) }}</p>
                        <a href="{{ route('baiviet.show', $bv->Slug) }}" class="text-gold fw-bold text-uppercase small tracking-widest text-decoration-none border-bottom border-gold pb-1">Đọc tiếp <i class="fa-solid fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@push('scripts')
<script>
    function addToCartIndex(id) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch(`{{ url('/cart/add') }}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify({ id: id, qty: 1 })
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                alert('Đã thêm sản phẩm vào giỏ hàng!');
                location.reload(); // Refresh to update cart badge in header
            } else if(data.status === 'login_required') {
                window.location.href = "{{ route('login') }}";
            } else {
                alert(data.message);
            }
        });
    }
</script>
@endpush
@endsection
