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
            <!-- Sidebar Navigation - Luxury Style (Synced with Profile) -->
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden sticky-top" style="top: 100px; border-top: 4px solid var(--gold-primary) !important;">
                    <div class="card-body p-0">
                        <!-- User Brief -->
                        <div class="p-4 text-center border-bottom bg-white">
                            <div class="avatar-container mb-3 position-relative d-inline-block">
                                <div class="avatar-placeholder rounded-circle bg-dark text-white d-flex align-items-center justify-content-center shadow-lg" style="width: 90px; height: 90px; border: 3px solid var(--gold-light);">
                                    <i class="fa-solid fa-user-tie fs-1"></i>
                                </div>
                                <span class="position-absolute bottom-0 end-0 bg-success border border-white border-2 rounded-circle p-2 shadow-sm" title="Đang hoạt động"></span>
                            </div>
                            <h5 class="fw-bold mb-1 font-luxury">{{ $khachHang->HoTen }}</h5>
                            <div class="badge bg-gold-soft text-gold rounded-pill px-3 py-1 extra-small fw-bold ls-1">KẾ THÀNH VIÊN</div>
                        </div>
                        
                        <!-- Navigation Menu -->
                        <div class="list-group list-group-flush p-3 nav-luxury">
                            <a href="{{ route('customer.profile') }}" class="list-group-item list-group-item-action border-0 rounded-3 py-3 d-flex align-items-center mb-2 {{ Request::is('profile') && !request()->has('tab') ? 'active' : '' }}">
                                <div class="icon-box-sm me-3 d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-id-card-alt"></i>
                                </div>
                                <span class="fw-bold small text-uppercase ls-1">Hồ sơ cá nhân</span>
                            </a>
                            <a href="{{ route('addresses.index') }}" class="list-group-item list-group-item-action border-0 rounded-3 py-3 d-flex align-items-center mb-2 {{ Request::is('addresses*') ? 'active' : '' }}">
                                <div class="icon-box-sm me-3 d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-map-marked-alt"></i>
                                </div>
                                <span class="fw-bold small text-uppercase ls-1">Sổ địa chỉ</span>
                            </a>
                            <a href="{{ route('customer.profile') }}?tab=orders" class="list-group-item list-group-item-action border-0 rounded-3 py-3 d-flex align-items-center mb-2 {{ request('tab') == 'orders' ? 'active' : '' }}">
                                <div class="icon-box-sm me-3 d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-box-open"></i>
                                </div>
                                <span class="fw-bold small text-uppercase ls-1">Đơn hàng</span>
                            </a>
                            <a href="{{ route('customer.profile') }}?tab=reviews" class="list-group-item list-group-item-action border-0 rounded-3 py-3 d-flex align-items-center mb-2 {{ request('tab') == 'reviews' ? 'active' : '' }}">
                                <div class="icon-box-sm me-3 d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-star-half-stroke"></i>
                                </div>
                                <span class="fw-bold small text-uppercase ls-1">Đánh giá</span>
                            </a>
                            <a href="{{ route('favorites.index') }}" class="list-group-item list-group-item-action border-0 rounded-3 py-3 d-flex align-items-center mb-2 {{ Request::is('favorites*') ? 'active' : '' }}">
                                <div class="icon-box-sm me-3 d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-heart"></i>
                                </div>
                                <span class="fw-bold small text-uppercase ls-1">Yêu thích</span>
                            </a>
                            
                            <div class="border-top my-3 opacity-25"></div>
                            
                            <form action="{{ route('logout') }}" method="POST" class="no-barba">
                                @csrf
                                <button type="submit" class="list-group-item list-group-item-action border-0 rounded-3 py-3 d-flex align-items-center text-danger logout-btn">
                                    <div class="icon-box-sm me-3 d-flex align-items-center justify-content-center bg-danger-soft text-danger">
                                        <i class="fa-solid fa-power-off"></i>
                                    </div>
                                    <span class="fw-bold small text-uppercase ls-1">Đăng xuất</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="col-lg-9">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-white border-0 p-4 d-flex flex-wrap justify-content-between align-items-center border-start border-gold border-4 gap-3">
                        <div>
                            <h4 class="fw-bold mb-0 font-luxury text-dark">Sổ Địa Chỉ Giao Hàng</h4>
                            <p class="text-muted small mb-0">Lưu lại các địa chỉ để đặt hàng nhanh chóng hơn</p>
                        </div>
                        <button type="button" class="btn btn-dark px-4 rounded-pill fw-bold small ls-1 shadow-sm h-100" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                            <i class="fa-solid fa-plus me-2 text-gold"></i> THÊM ĐỊA CHỈ MỚI
                        </button>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="row g-4">
                            @forelse($addresses as $address)
                            <div class="col-md-6">
                                <div class="address-luxury-card p-4 rounded-4 bg-white border h-100 transition-all hover-luxury position-relative overflow-hidden {{ $address->MacDinh ? 'border-gold shadow-gold-soft' : 'shadow-sm' }}">
                                    @if($address->MacDinh)
                                        <div class="ribbon-luxury">MẶC ĐỊNH</div>
                                    @endif
                                    
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-box-sm bg-light text-dark rounded-circle me-3"><i class="fa-solid fa-user-tag small"></i></div>
                                            <h6 class="fw-bold mb-0 text-dark fs-5">{{ $address->HoTenNguoiNhan }}</h6>
                                        </div>
                                        
                                        <div class="dropdown">
                                            <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 overflow-hidden p-0">
                                                @if(!$address->MacDinh)
                                                <li>
                                                    <form action="{{ route('addresses.setDefault', $address->MaDC) }}" method="POST" class="no-barba">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item py-3 small fw-bold">
                                                            <i class="fa-solid fa-star me-2 text-gold"></i> ĐẶT LÀM MẶC ĐỊNH
                                                        </button>
                                                    </form>
                                                </li>
                                                <li><hr class="dropdown-divider m-0 opacity-10"></li>
                                                <li>
                                                    <form action="{{ route('addresses.destroy', $address->MaDC) }}" method="POST" onsubmit="return confirm('Xác nhận xóa địa chỉ này khỏi sổ địa chỉ của bạn?')" class="no-barba">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item py-3 small text-danger fw-bold">
                                                            <i class="fa-solid fa-trash-can me-2"></i> XÓA ĐỊA CHỈ
                                                        </button>
                                                    </form>
                                                </li>
                                                @else
                                                <li class="dropdown-item py-3 small text-muted text-center fw-bold bg-light disabled">
                                                    ĐỊA CHỈ ƯU TIÊN
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <div class="p-3 bg-light rounded-4 border-0 mb-2">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fa-solid fa-phone-volume me-3 text-gold"></i>
                                            <span class="small fw-bold text-dark">{{ $address->SDTNguoiNhan }}</span>
                                        </div>
                                        <div class="d-flex align-items-start">
                                            <i class="fa-solid fa-location-dot me-3 text-gold mt-1"></i>
                                            <p class="small mb-0 text-dark fw-medium lh-lg">
                                                {{ $address->DiaChiChiTiet }}<br>
                                                <span class="text-muted">{{ $address->PhuongXa }}, {{ $address->QuanHuyen }}</span><br>
                                                <span class="text-muted">{{ $address->TinhThanh }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12 text-center py-5">
                                <div class="empty-state p-5">
                                    <div class="icon-circle bg-light text-muted mx-auto mb-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 120px; height: 120px;">
                                        <i class="fa-solid fa-map-location-dot fs-1 opacity-25"></i>
                                    </div>
                                    <h5 class="fw-bold text-muted font-luxury">Sổ Địa Chỉ Hiện Đang Trống</h5>
                                    <p class="text-muted small px-md-5 mb-4">Hãy thêm địa chỉ giao hàng của bạn để trải nghiệm mua sắm trở nên nhanh chóng và đẳng cấp hơn.</p>
                                    <button class="btn btn-gold rounded-pill px-5 py-3 fw-bold shadow-sm ls-1" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                        <i class="fa-solid fa-plus me-2"></i> THÊM ĐỊA CHỈ ĐẦU TIÊN
                                    </button>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thêm Địa Chỉ - Luxury Redesign -->
<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header border-0 p-4 bg-dark text-white border-bottom border-gold border-4">
                <h5 class="modal-title fw-bold text-uppercase ls-1 font-luxury">Thêm địa chỉ mới</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('addresses.store') }}" method="POST" class="no-barba">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label extra-small fw-bold text-muted ls-1">HỌ TÊN NGƯỜI NHẬN</label>
                            <input type="text" name="HoTenNguoiNhan" class="form-control rounded-pill px-4 py-2 border shadow-none" placeholder="Nguyễn Văn A" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label extra-small fw-bold text-muted ls-1">SỐ ĐIỆN THOẠI</label>
                            <input type="text" name="SDTNguoiNhan" class="form-control rounded-pill px-4 py-2 border shadow-none" placeholder="09xx xxx xxx" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label extra-small fw-bold text-muted ls-1">TỈNH / THÀNH PHỐ</label>
                            <input type="text" name="TinhThanh" class="form-control rounded-pill px-4 py-2 border shadow-none" placeholder="Ví dụ: Hà Nội" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label extra-small fw-bold text-muted ls-1">QUẬN / HUYỆN</label>
                            <input type="text" name="QuanHuyen" class="form-control rounded-pill px-4 py-2 border shadow-none" placeholder="Ví dụ: Cầu Giấy" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label extra-small fw-bold text-muted ls-1">PHƯỜNG / XÃ</label>
                            <input type="text" name="PhuongXa" class="form-control rounded-pill px-4 py-2 border shadow-none" placeholder="Ví dụ: Dịch Vọng" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label extra-small fw-bold text-muted ls-1">ĐỊA CHỈ CHI TIẾT</label>
                            <textarea name="DiaChiChiTiet" class="form-control rounded-4 px-4 py-3 border shadow-none" rows="3" placeholder="Số nhà, tên đường..." required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0 bg-white">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold small ls-1 border" data-bs-dismiss="modal">HỦY BỎ</button>
                    <button type="submit" class="btn btn-dark rounded-pill px-5 fw-bold small ls-1 shadow-sm">LƯU ĐỊA CHỈ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    :root {
        --gold-primary: #af9245;
        --gold-light: #d4af37;
        --gold-soft: rgba(175, 146, 69, 0.1);
        --text-main: #2d3436;
        --bg-soft: #f8f9fa;
    }

    .font-luxury { font-family: 'Playfair Display', serif; }
    .ls-1 { letter-spacing: 1px; }
    .extra-small { font-size: 0.65rem; }
    
    /* Sidebar (Synced) */
    .nav-luxury .list-group-item {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid transparent !important;
        color: #64748b;
    }
    .nav-luxury .list-group-item:hover {
        background-color: var(--gold-soft);
        color: var(--gold-primary);
        padding-left: 1.5rem !important;
    }
    .nav-luxury .list-group-item.active {
        background-color: var(--text-main) !important;
        color: white !important;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .icon-box-sm {
        width: 35px;
        height: 35px;
        border-radius: 10px;
        background: var(--bg-soft);
        font-size: 0.85rem;
    }
    .nav-luxury .list-group-item.active .icon-box-sm {
        background: var(--gold-primary) !important;
        color: white !important;
    }

    /* Address Cards */
    .address-luxury-card { border: 1px solid #edf2f7; position: relative; }
    .hover-luxury:hover {
        border-color: var(--gold-primary) !important;
        box-shadow: 0 15px 30px rgba(175, 146, 69, 0.1) !important;
        transform: translateY(-5px);
    }
    .shadow-gold-soft { box-shadow: 0 10px 25px rgba(175, 146, 69, 0.15) !important; }
    
    .ribbon-luxury {
        position: absolute;
        top: 15px;
        right: -35px;
        background: var(--gold-primary);
        color: white;
        padding: 5px 40px;
        font-size: 0.6rem;
        font-weight: 800;
        letter-spacing: 2px;
        transform: rotate(45deg);
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        z-index: 1;
    }

    .bg-danger-soft { background: #fef2f2; }
    .transition-all { transition: all 0.3s ease; }
    
    .dropdown-item:hover { background-color: var(--gold-soft); color: var(--gold-primary); }
    .dropdown-item.text-danger:hover { background-color: #fff1f2; color: #dc3545; }
    
    .form-control:focus { border-color: var(--gold-primary); box-shadow: 0 0 0 0.25rem var(--gold-soft); }
</style>
@endsection
