@extends('layouts.app')

@section('content')
<div class="profile-page bg-light min-vh-100 py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Sidebar Navigation -->
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden sticky-top" style="top: 100px;">
                    <div class="card-body p-0">
                        <!-- User Brief -->
                        <div class="p-4 text-center border-bottom bg-white">
                            <div class="avatar-container mb-3 position-relative d-inline-block">
                                <div class="avatar-placeholder rounded-circle bg-dark text-white d-flex align-items-center justify-content-center shadow" style="width: 80px; height: 80px;">
                                    <i class="fa-solid fa-user-tie fs-1"></i>
                                </div>
                                <span class="position-absolute bottom-0 end-0 bg-success border border-white border-2 rounded-circle p-2" title="Đang hoạt động"></span>
                            </div>
                            <h5 class="fw-bold mb-1">{{ $khachHang->HoTen ?? 'Khách hàng' }}</h5>
                            <p class="text-muted small mb-0 text-uppercase ls-1">Khách hàng thân thiết</p>
                        </div>
                        
                        <!-- Navigation Menu -->
                        <div class="list-group list-group-flush p-2">
                            <a href="{{ route('customer.profile') }}" class="list-group-item list-group-item-action border-0 rounded-3 py-3 d-flex align-items-center mb-1 {{ Request::is('profile') && !request()->has('tab') ? 'active bg-dark text-white shadow-sm' : '' }}">
                                <i class="fa-solid fa-address-card me-3 {{ Request::is('profile') && !request()->has('tab') ? '' : 'text-gold' }}"></i>
                                <span class="fw-semibold">Hồ sơ cá nhân</span>
                            </a>
                            <a href="{{ route('addresses.index') }}" class="list-group-item list-group-item-action border-0 rounded-3 py-3 d-flex align-items-center mb-1 {{ Request::is('addresses*') ? 'active bg-dark text-white shadow-sm' : '' }}">
                                <i class="fa-solid fa-map-location-dot me-3 {{ Request::is('addresses*') ? '' : 'text-gold' }}"></i>
                                <span class="fw-semibold">Sổ địa chỉ</span>
                            </a>
                            <a href="{{ route('customer.profile') }}?tab=orders" class="list-group-item list-group-item-action border-0 rounded-3 py-3 d-flex align-items-center mb-1 {{ request('tab') == 'orders' ? 'active bg-dark text-white shadow-sm' : '' }}">
                                <i class="fa-solid fa-box-open me-3 {{ request('tab') == 'orders' ? '' : 'text-gold' }}"></i>
                                <span class="fw-semibold">Đơn hàng của tôi</span>
                            </a>
                            <a href="{{ route('customer.profile') }}?tab=reviews" class="list-group-item list-group-item-action border-0 rounded-3 py-3 d-flex align-items-center mb-1 {{ request('tab') == 'reviews' ? 'active bg-dark text-white shadow-sm' : '' }}">
                                <i class="fa-solid fa-star me-3 {{ request('tab') == 'reviews' ? '' : 'text-gold' }}"></i>
                                <span class="fw-semibold">Đánh giá của tôi</span>
                            </a>
                            <a href="{{ route('customer.change_password') }}" class="list-group-item list-group-item-action border-0 rounded-3 py-3 d-flex align-items-center mb-1 {{ Request::is('change-password') ? 'active bg-dark text-white shadow-sm' : '' }}">
                                <i class="fa-solid fa-key me-3 {{ Request::is('change-password') ? '' : 'text-gold' }}"></i>
                                <span class="fw-semibold">Đổi mật khẩu</span>
                            </a>
                            <a href="{{ route('favorites.index') }}" class="list-group-item list-group-item-action border-0 rounded-3 py-3 d-flex align-items-center mb-1 {{ Request::is('favorites*') ? 'active bg-dark text-white shadow-sm' : '' }}">
                                <i class="fa-solid fa-heart me-3 {{ Request::is('favorites*') ? '' : 'text-gold' }}"></i>
                                <span class="fw-semibold">Yêu thích</span>
                            </a>
                            <div class="border-top my-2 mx-2"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="list-group-item list-group-item-action border-0 rounded-3 py-3 d-flex align-items-center text-danger">
                                    <i class="fa-solid fa-right-from-bracket me-3"></i>
                                    <span class="fw-semibold">Đăng xuất</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-heart me-2 text-gold"></i> Bộ sưu tập yêu thích</h4>
                    <span class="badge bg-white text-dark border px-3 py-2 rounded-pill fw-bold">{{ $favorites->count() }} sản phẩm</span>
                </div>

                @if($favorites->count() > 0)
                    <div class="row g-4">
                        @foreach($favorites as $sp)
                        <div class="col-md-4 col-sm-6" id="fav-item-{{ $sp->MaSP }}">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 transition-all hover-shadow">
                                <div class="position-relative overflow-hidden group" style="height: 250px;">
                                    <img src="{{ $sp->main_image_url }}" class="card-img-top w-100 h-100 object-fit-cover transition-all group-hover-scale" alt="{{ $sp->TenSP }}">
                                    <div class="position-absolute top-0 end-0 p-3">
                                        <button onclick="toggleFavorite({{ $sp->MaSP }}, this, true)" class="btn btn-white btn-sm rounded-circle shadow-sm text-danger" title="Xóa khỏi yêu thích">
                                            <i class="fa-solid fa-heart"></i>
                                        </button>
                                    </div>
                                    <div class="position-absolute bottom-0 start-0 w-100 p-3 bg-gradient-dark-transparent opacity-0 group-hover-opacity-100 transition-all">
                                        <button onclick="addToCartIndex({{ $sp->MaSP }})" class="btn btn-gold btn-sm w-100 rounded-pill fw-bold shadow-sm">
                                            <i class="fa-solid fa-cart-plus me-1"></i> THÊM GIỎ HÀNG
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="extra-small text-muted text-uppercase ls-1 fw-bold">{{ $sp->danhmuc->TenDM ?? 'Kệ thông minh' }}</span>
                                    </div>
                                    <h6 class="fw-bold mb-2 h-text-truncate"><a href="{{ route('sanpham.detail', $sp->MaSP) }}" class="text-dark text-decoration-none">{{ $sp->TenSP }}</a></h6>
                                    
                                    <div class="mt-3 d-flex align-items-center justify-content-between">
                                        <div>
                                            @if($sp->khuyen_mai_active)
                                                <span class="text-muted extra-small text-decoration-line-through d-block">{{ number_format($sp->DonGia, 0, ',', '.') }}₫</span>
                                                <span class="text-danger fw-bold fs-5">{{ number_format($sp->gia_hien_tai, 0, ',', '.') }}₫</span>
                                            @else
                                                <span class="text-dark fw-bold fs-5">{{ number_format($sp->DonGia, 0, ',', '.') }}₫</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('sanpham.detail', $sp->MaSP) }}" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold small">CHI TIẾT</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                        <div class="mb-4">
                            <img src="https://cdn-icons-png.flaticon.com/512/3596/3596091.png" alt="Empty" style="width: 150px; opacity: 0.3;">
                        </div>
                        <h4 class="fw-bold text-muted">Bộ sưu tập của bạn đang trống</h4>
                        <p class="text-muted small">Hãy lưu lại những sản phẩm bạn yêu thích để dễ dàng mua sắm sau này.</p>
                        <a href="{{ route('sanpham.index') }}" class="btn btn-gold rounded-pill px-5 py-3 fw-bold mt-3 shadow-sm text-uppercase ls-1">KHÁM PHÁ CỬA HÀNG</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleFavorite(maSP, btn, isPage) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch(`{{ route('favorites.toggle') }}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify({ maSP: maSP })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'removed') {
                if(isPage) {
                    const item = document.getElementById(`fav-item-${maSP}`);
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        item.remove();
                        if(document.querySelectorAll('[id^="fav-item-"]').length === 0) {
                            location.reload();
                        }
                    }, 300);
                }
            }
        });
    }

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
                location.reload(); 
            }
        });
    }
</script>
@endpush

<style>
    .ls-1 { letter-spacing: 1px; }
    .transition-all { transition: all 0.3s ease; }
    .hover-shadow:hover { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important; transform: translateY(-5px); }
    .group-hover-scale { transition: transform 0.5s ease; }
    .group:hover .group-hover-scale { transform: scale(1.1); }
    .bg-gradient-dark-transparent { background: linear-gradient(to top, rgba(0,0,0,0.7), transparent); }
    .opacity-0 { opacity: 0; }
    .group-hover-opacity-100 { opacity: 1; }
    .h-text-truncate { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 3rem; }
    .btn-white { background: white; border: none; }
    .btn-white:hover { background: #f8f9fa; }
</style>
@endsection
