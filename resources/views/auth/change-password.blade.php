@extends('layouts.app')

@section('content')
<div class="profile-page bg-light min-vh-100 py-5">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

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
                            <h5 class="fw-bold mb-1">{{ $khachHang->HoTen }}</h5>
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
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-0 p-4">
                        <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-shield-halved me-2 text-gold"></i> Bảo mật tài khoản</h5>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <div class="alert alert-info border-0 rounded-4 p-3 mb-4 d-flex align-items-center" style="background: #f0f9ff;">
                            <i class="fa-solid fa-circle-info fs-4 me-3 text-info"></i>
                            <p class="mb-0 small text-info-emphasis fw-medium">Để đảm bảo an toàn, vui lòng sử dụng mật khẩu mạnh bao gồm cả chữ và số.</p>
                        </div>

                        <form action="{{ route('customer.update_password') }}" method="POST" class="row g-4 no-barba">
                            @csrf
                            <div class="col-md-8">
                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-muted text-uppercase ls-1">Mật khẩu hiện tại</label>
                                    <div class="position-relative">
                                        <input type="password" name="current_password" class="form-control rounded-pill px-4 @error('current_password') is-invalid @enderror" placeholder="Nhập mật khẩu đang sử dụng" required>
                                        <i class="fa-solid fa-lock position-absolute top-50 end-0 translate-middle-y me-4 text-muted opacity-50"></i>
                                        @error('current_password')
                                            <div class="invalid-feedback ms-4">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-muted text-uppercase ls-1">Mật khẩu mới</label>
                                    <div class="position-relative">
                                        <input type="password" name="new_password" class="form-control rounded-pill px-4 @error('new_password') is-invalid @enderror" placeholder="Tối thiểu 6 ký tự" required>
                                        <i class="fa-solid fa-key position-absolute top-50 end-0 translate-middle-y me-4 text-muted opacity-50"></i>
                                        @error('new_password')
                                            <div class="invalid-feedback ms-4">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-muted text-uppercase ls-1">Xác nhận mật khẩu mới</label>
                                    <div class="position-relative">
                                        <input type="password" name="new_password_confirmation" class="form-control rounded-pill px-4" placeholder="Nhập lại mật khẩu mới" required>
                                        <i class="fa-solid fa-check-double position-absolute top-50 end-0 translate-middle-y me-4 text-muted opacity-50"></i>
                                    </div>
                                </div>

                                <div class="mt-5">
                                    <button type="submit" class="btn btn-dark rounded-pill px-5 py-2 fw-bold ls-1 shadow-sm">
                                        CẬP NHẬT MẬT KHẨU
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 1px; }
    .transition-all { transition: all 0.3s ease; }
</style>
@endsection
