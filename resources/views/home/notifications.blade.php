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
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h4 class="fw-bold mb-1 text-dark"><i class="fa-solid fa-bell me-2 text-gold"></i> Thông báo của tôi</h4>
                        <p class="text-muted small mb-0">Cập nhật những tin tức mới nhất về đơn hàng và ưu đãi.</p>
                    </div>
                    @if($notifications->count() > 0)
                        <button onclick="markAllAsRead()" class="btn btn-outline-dark rounded-pill px-4 py-2 small fw-bold ls-1">
                            ĐÁNH DẤU TẤT CẢ ĐÃ ĐỌC
                        </button>
                    @endif
                </div>

                <div class="notifications-list">
                    @forelse($notifications as $tb)
                        <div class="notification-card p-4 rounded-4 mb-3 transition-all hover-shadow {{ $tb->TrangThaiDoc ? 'bg-white opacity-75' : 'bg-white shadow-sm border-start border-gold border-4' }}"
                             style="cursor: pointer;"
                             onclick="readNotification({{ $tb->MaTB }}, '{{ $tb->LienKet ?: '#' }}')">
                            
                            <div class="d-flex align-items-start gap-4">
                                <div class="noti-icon p-3 rounded-circle {{ $tb->TrangThaiDoc ? 'bg-light text-muted' : 'bg-gold-soft text-gold' }}">
                                    <i class="fa-solid {{ $tb->LoaiTB == 'DonHang' ? 'fa-box-open' : 'fa-bolt' }} fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="fw-bold mb-0 {{ $tb->TrangThaiDoc ? 'text-muted' : 'text-dark' }} fs-5">{{ $tb->TieuDe }}</h6>
                                        <small class="text-muted"><i class="fa-regular fa-clock me-1"></i> {{ date('d/m/Y H:i', strtotime($tb->NgayGui)) }}</small>
                                    </div>
                                    <p class="mb-0 {{ $tb->TrangThaiDoc ? 'text-muted' : 'text-secondary fw-medium' }} lh-base">{{ $tb->NoiDung }}</p>
                                    
                                    @if(!$tb->TrangThaiDoc)
                                        <div class="mt-2">
                                            <span class="badge bg-gold text-white rounded-pill px-3 py-1 extra-small fw-bold">MỚI</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-end align-self-center">
                                    <i class="fa-solid fa-chevron-right text-muted opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-100 bg-white rounded-4 shadow-sm">
                            <div class="mb-4">
                                <i class="fa-solid fa-bell-slash display-1 text-light"></i>
                            </div>
                            <h4 class="fw-bold text-muted">Bạn chưa có thông báo nào</h4>
                            <p class="text-muted small">Mọi tin tức quan trọng sẽ được hiển thị tại đây.</p>
                            <a href="{{ route('home') }}" class="btn btn-gold rounded-pill px-5 py-3 fw-bold ls-1 mt-3 shadow-sm">QUAY LẠI TRANG CHỦ</a>
                        </div>
                    @endforelse

                    <div class="mt-5 d-flex justify-content-center">
                        {{ $notifications->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 1px; }
    .bg-gold-soft { background-color: rgba(175, 146, 69, 0.1); }
    .py-100 { padding-top: 100px; padding-bottom: 100px; }
    .extra-small { font-size: 0.65rem; }
    .transition-all { transition: all 0.3s ease; }
    .hover-shadow:hover { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important; transform: translateX(5px); }
</style>

<script>
    function readNotification(id, link) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch(`/notifications/mark-as-read/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        }).finally(() => {
            if(link && link !== '#' && link !== '') {
                window.location.href = link;
            } else {
                location.reload();
            }
        });
    }

    function markAllAsRead() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        if(confirm('Bạn có muốn đánh dấu tất cả thông báo là đã đọc?')) {
            fetch(`/notifications/mark-all-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            }).then(() => {
                location.reload();
            });
        }
    }
</script>
@endsection
