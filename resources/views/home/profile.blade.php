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
            <!-- Sidebar Navigation - Luxury Style -->
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
                            <h5 class="fw-bold mb-1 font-luxury">{{ $customer->HoTen }}</h5>
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
                @if(!request()->has('tab') || request('tab') == 'profile')
                    <!-- Profile Information Section -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                        <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center border-start border-gold border-4">
                            <div>
                                <h4 class="fw-bold mb-0 font-luxury text-dark">Hồ Sơ Cá Nhân</h4>
                                <p class="text-muted small mb-0">Quản lý thông tin tài khoản và bảo mật</p>
                            </div>
                            <button class="btn btn-gold rounded-pill px-4 btn-sm fw-bold ls-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                <i class="fa-solid fa-pen-nib me-2"></i>CHỈNH SỬA
                            </button>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="info-card p-3 rounded-4 bg-white border h-100 transition-all">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="icon-box-sm bg-light text-gold rounded-circle me-3"><i class="fa-solid fa-user"></i></div>
                                            <small class="text-muted text-uppercase fw-bold ls-1" style="font-size: 0.65rem;">Họ và tên</small>
                                        </div>
                                        <p class="fw-bold text-dark mb-0 ps-5">{{ $customer->HoTen }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-card p-3 rounded-4 bg-white border h-100 transition-all">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="icon-box-sm bg-light text-gold rounded-circle me-3"><i class="fa-solid fa-envelope"></i></div>
                                            <small class="text-muted text-uppercase fw-bold ls-1" style="font-size: 0.65rem;">Email liên hệ</small>
                                        </div>
                                        <p class="fw-bold text-dark mb-0 ps-5">{{ $customer->Email }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-card p-3 rounded-4 bg-white border h-100 transition-all">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="icon-box-sm bg-light text-gold rounded-circle me-3"><i class="fa-solid fa-phone"></i></div>
                                            <small class="text-muted text-uppercase fw-bold ls-1" style="font-size: 0.65rem;">Số điện thoại</small>
                                        </div>
                                        <p class="fw-bold text-dark mb-0 ps-5">{{ $customer->SDT }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-card p-3 rounded-4 bg-white border h-100 transition-all">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="icon-box-sm bg-light text-gold rounded-circle me-3"><i class="fa-solid fa-location-arrow"></i></div>
                                            <small class="text-muted text-uppercase fw-bold ls-1" style="font-size: 0.65rem;">Địa chỉ chính</small>
                                        </div>
                                        <p class="fw-bold text-dark mb-0 ps-5 text-truncate" title="{{ $customer->DiaChi }}">{{ $customer->DiaChi }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics / Quick Overview -->
                    <div class="row g-4">
                        <div class="col-md-4">
                            <a href="{{ route('customer.profile') }}?tab=orders#active-orders-tab" class="text-decoration-none">
                                <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100 text-center transition-all hover-luxury">
                                    <div class="icon-box bg-primary-soft text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 65px; height: 60px;">
                                        <i class="fa-solid fa-truck-fast fs-4"></i>
                                    </div>
                                    <h3 class="fw-bold mb-1 text-dark">{{ $ordersInProgress->count() }}</h3>
                                    <p class="text-muted small mb-0 fw-bold ls-1 text-uppercase">Đang xử lý</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('customer.profile') }}?tab=orders#completed-orders-tab" class="text-decoration-none">
                                <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100 text-center transition-all hover-luxury">
                                    <div class="icon-box bg-success-soft text-success rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 65px; height: 60px;">
                                        <i class="fa-solid fa-circle-check fs-4"></i>
                                    </div>
                                    <h3 class="fw-bold mb-1 text-dark">{{ $ordersCompleted->where('TrangThaiDH', 'DaGiao')->count() }}</h3>
                                    <p class="text-muted small mb-0 fw-bold ls-1 text-uppercase">Đã hoàn thành</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('customer.profile') }}?tab=reviews" class="text-decoration-none">
                                <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100 text-center transition-all hover-luxury">
                                    <div class="icon-box bg-warning-soft text-warning rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 65px; height: 60px;">
                                        <i class="fa-solid fa-star fs-4"></i>
                                    </div>
                                    <h3 class="fw-bold mb-1 text-dark">{{ $reviews->count() ?? 0 }}</h3>
                                    <p class="text-muted small mb-0 fw-bold ls-1 text-uppercase">Đánh giá của bạn</p>
                                </div>
                            </a>
                        </div>
                    </div>
                @elseif(request('tab') == 'reviews')
                    <!-- Reviews Section -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                        <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center border-start border-gold border-4">
                            <div>
                                <h4 class="fw-bold mb-0 font-luxury text-dark">Đánh Giá Của Tôi</h4>
                                <p class="text-muted small mb-0">Những cảm nhận thực tế về chất lượng sản phẩm</p>
                            </div>
                            <span class="badge bg-dark rounded-pill px-3 py-2 fw-bold ls-1">{{ $reviews->count() }} PHẢN HỒI</span>
                        </div>
                        <div class="card-body p-4 pt-0">
                            @forelse($reviews as $review)
                                <div class="review-luxury-card p-4 rounded-4 bg-white border mb-4 transition-all hover-shadow">
                                    <div class="d-flex flex-column flex-md-row gap-4">
                                        <div class="review-product-img-wrapper" style="width: 100px; flex-shrink: 0;">
                                            @php
                                                $sp = $review->sanpham;
                                                $imgUrl = 'https://via.placeholder.com/100x150';
                                                if ($sp) {
                                                    if ($sp->HinhAnh && strpos($sp->HinhAnh, 'http') === 0) {
                                                        $imgUrl = $sp->HinhAnh;
                                                    } elseif ($sp->HinhAnh) {
                                                        $imgUrl = asset('assets/images/products/' . $sp->HinhAnh);
                                                    } elseif ($sp->main_image_url) {
                                                        $imgUrl = $sp->main_image_url;
                                                    }
                                                }
                                            @endphp
                                            <img src="{{ $imgUrl }}" class="img-fluid rounded-3 shadow-sm border bg-light" alt="{{ $sp->TenSP ?? 'Product' }}" style="height: 120px; object-fit: contain; width: 100%;">
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex flex-wrap justify-content-between align-items-start mb-2 gap-2">
                                                <h6 class="fw-bold mb-0 fs-5"><a href="{{ route('sanpham.detail', $review->MaSP) }}" class="text-dark text-decoration-none hover-gold transition-all">{{ $sp->TenSP ?? 'Sản phẩm đã xóa' }}</a></h6>
                                                <div class="text-warning rating-stars-luxury">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fa-{{ $i <= $review->SoSao ? 'solid' : 'regular' }} fa-star fs-6"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center gap-3 mb-3">
                                                <small class="text-muted"><i class="fa-solid fa-calendar-day me-1 text-gold-dark"></i> {{ $review->created_at->format('d/m/Y H:i') }}</small>
                                                <span class="badge bg-success-soft text-success extra-small fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Đã xác thực mua hàng</span>
                                            </div>
                                            <div class="p-4 bg-light rounded-4 position-relative">
                                                <i class="fa-solid fa-quote-left position-absolute top-0 start-0 m-2 opacity-10 fs-2"></i>
                                                <p class="mb-0 text-dark lh-lg font-italic" style="font-size: 0.95rem;">{{ $review->NoiDung }}</p>
                                            </div>
                                            @if($review->HinhAnhDG)
                                                <div class="mt-3 d-flex gap-2">
                                                    <img src="{{ asset($review->HinhAnhDG) }}" class="rounded-3 shadow-sm border transition-all hover-scale cursor-pointer" style="width: 100px; height: 100px; object-fit: cover;" onclick="viewImageFull('{{ asset($review->HinhAnhDG) }}')">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <div class="mb-4">
                                        <div class="icon-circle bg-light text-muted mx-auto d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                            <i class="fa-regular fa-message fs-1 opacity-25"></i>
                                        </div>
                                    </div>
                                    <h5 class="fw-bold text-muted font-luxury">Bạn Chưa Có Đánh Giá Nào</h5>
                                    <p class="text-muted small px-5">Mỗi đánh giá của bạn là một bước tiến giúp chúng tôi hoàn thiện hơn. Hãy chia sẻ trải nghiệm về sản phẩm bạn đã mua nhé!</p>
                                    <a href="{{ route('customer.profile') }}?tab=orders" class="btn btn-dark rounded-pill px-5 py-2 fw-bold mt-3 shadow-sm ls-1">XEM ĐƠN HÀNG ĐÃ MUA</a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @elseif(request('tab') == 'orders')
                    <!-- Order Management Section -->
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
                        <div class="card-header bg-white border-0 p-0">
                            <ul class="nav nav-pills nav-fill border-bottom p-2" id="orderTabs" role="tablist">
                                <li class="nav-item">
                                    <button class="nav-link active py-3 fw-bold text-uppercase ls-1 rounded-pill" id="active-orders-tab" data-bs-toggle="pill" data-bs-target="#active-orders" type="button">
                                        <i class="fa-solid fa-spinner-third me-2"></i>Đang xử lý <span class="badge bg-gold-dark ms-2">{{ $ordersInProgress->count() }}</span>
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link py-3 fw-bold text-uppercase ls-1 rounded-pill" id="completed-orders-tab" data-bs-toggle="pill" data-bs-target="#completed-orders" type="button">
                                        <i class="fa-solid fa-clock-rotate-left me-2"></i>Lịch sử mua hàng
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <!-- Active Orders -->
                            <div class="tab-pane fade show active p-4" id="active-orders">
                                @forelse($ordersInProgress as $order)
                                    <div class="order-luxury-card p-4 rounded-4 bg-white border mb-4 shadow-sm position-relative overflow-hidden transition-all hover-shadow">
                                        <div class="order-accent-status position-absolute top-0 start-0 h-100" style="width: 5px; background: var(--gold-primary);"></div>
                                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3 border-bottom pb-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="order-id-badge bg-dark text-white rounded-3 px-3 py-2 fw-bold">#ORD-{{ $order->MaDH }}</div>
                                                <div>
                                                    <div class="text-muted extra-small text-uppercase fw-bold ls-1 mb-0">Ngày đặt hàng</div>
                                                    <div class="small fw-bold text-dark">{{ date('d/m/Y H:i', strtotime($order->NgayDat)) }}</div>
                                                </div>
                                            </div>
                                            <div>
                                                @php
                                                    $s = match($order->TrangThaiDH) {
                                                        'ChoThanhToan' => ['bg-danger-soft text-danger', 'Chờ thanh toán', 'fa-credit-card'],
                                                        'ChoXacNhan' => ['bg-warning-soft text-warning', 'Chờ xác nhận', 'fa-clock'],
                                                        'DaXacNhan'  => ['bg-info-soft text-info', 'Đã xác nhận', 'fa-check-double'],
                                                        'DangGiao'   => ['bg-primary-soft text-primary', 'Đang giao hàng', 'fa-truck-fast'],
                                                        default      => ['bg-secondary-soft text-secondary', $order->TrangThaiDH, 'fa-circle-dot']
                                                    };
                                                @endphp
                                                <div class="badge-luxury {{ $s[0] }} d-inline-flex align-items-center px-3 py-2 rounded-pill fw-bold small">
                                                    <i class="fa-solid {{ $s[2] }} me-2"></i>{{ $s[1] }}
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row align-items-center">
                                            <div class="col-md-7">
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-circle bg-light text-gold-dark rounded-4 d-flex align-items-center justify-content-center me-4 shadow-sm" style="width: 60px; height: 60px;">
                                                        <i class="fa-solid fa-wallet fs-4"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-muted small text-uppercase fw-bold ls-1 mb-1">Tổng số tiền thanh toán</div>
                                                        <div class="fw-bold fs-3 text-dark font-luxury">
                                                            @php
                                                                $soTienCanThu = max(0, $order->TongTien - ($order->SoTienDaThanhToan ?? 0));
                                                            @endphp
                                                            {{ number_format($order->TongTien, 0, ',', '.') }}₫
                                                            @if($soTienCanThu == 0)
                                                                <span class="badge bg-success-soft text-success ms-2 p-1" style="font-size: 0.6rem;"><i class="fa-solid fa-check me-1"></i>ĐÃ THANH TOÁN</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 text-md-end mt-4 mt-md-0 d-flex flex-column flex-sm-row justify-content-md-end gap-2">
                                                <button onclick="viewOrderDetail({{ $order->MaDH }})" class="btn btn-dark rounded-pill px-4 py-2 fw-bold small ls-1 shadow-sm">
                                                    CHI TIẾT ĐƠN HÀNG
                                                </button>
                                                @if(in_array($order->TrangThaiDH, ['ChoThanhToan', 'ChoXacNhan']))
                                                    <form action="{{ route('orders.cancel', $order->MaDH) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng cao cấp này?')" class="no-barba d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-danger rounded-pill px-4 py-2 fw-bold small ls-1 h-100">HỦY ĐƠN</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5 bg-white rounded-4 border">
                                        <div class="mb-4">
                                            <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" alt="Empty" style="width: 130px; opacity: 0.6;" class="mb-3">
                                        </div>
                                        <h5 class="fw-bold text-muted font-luxury">Không Có Đơn Hàng Đang Xử Lý</h5>
                                        <p class="text-muted small px-4">Hãy bắt đầu hành trình kiến tạo không gian sống bằng những mẫu kệ tinh tế nhất của Shelf Luxury.</p>
                                        <a href="{{ route('sanpham.index') }}" class="btn btn-gold rounded-pill px-5 py-3 fw-bold mt-3 shadow-sm ls-1">MUA SẮM NGAY</a>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Completed Orders -->
                            <div class="tab-pane fade p-4" id="completed-orders">
                                <div class="table-responsive rounded-4 border">
                                    <table class="table table-hover align-middle mb-0 table-luxury">
                                        <thead class="bg-dark text-white">
                                            <tr class="text-uppercase small fw-bold ls-1">
                                                <th class="ps-4 py-3">Mã đơn hàng</th>
                                                <th class="py-3">Ngày giao dịch</th>
                                                <th class="py-3">Tổng giá trị</th>
                                                <th class="py-3">Trạng thái</th>
                                                <th class="text-center py-3">Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($ordersCompleted as $order)
                                                <tr>
                                                    <td class="ps-4 py-4">
                                                        <span class="fw-bold text-dark">#ORD-{{ $order->MaDH }}</span>
                                                    </td>
                                                    <td class="small text-muted fw-medium">{{ date('d/m/Y', strtotime($order->NgayDat)) }}</td>
                                                    <td class="fw-bold text-dark font-luxury">{{ number_format($order->TongThanhToan, 0, ',', '.') }}₫</td>
                                                    <td>
                                                        @php
                                                            $s = match($order->TrangThaiDH) {
                                                                'DaGiao' => ['bg-success-soft text-success', 'Hoàn thành'],
                                                                'DaHuy'  => ['bg-danger-soft text-danger', 'Đã hủy'],
                                                                default  => ['bg-light text-muted', $order->TrangThaiDH]
                                                            };
                                                        @endphp
                                                        <span class="badge {{ $s[0] }} px-3 py-2 rounded-pill fw-bold" style="font-size: 0.7rem;">{{ $s[1] }}</span>
                                                    </td>
                                                    <td class="text-center pe-4">
                                                        <div class="d-flex justify-content-center gap-2">
                                                            <button onclick="viewOrderDetail({{ $order->MaDH }})" class="btn btn-sm btn-outline-dark rounded-pill px-3 fw-bold ls-1">XEM LẠI</button>
                                                            @if($order->TrangThaiDH === 'DaGiao' && $order->TrangThaiVanChuyen !== 'TraHang')
                                                                <button onclick="openReturnModal({{ $order->MaDH }}, {{ $order->TongThanhToan }})" class="btn btn-sm btn-gold-outline rounded-pill px-3 fw-bold ls-1">TRẢ HÀNG</button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-5">
                                                        <div class="opacity-50 mb-3"><i class="fa-solid fa-history fs-1"></i></div>
                                                        <p class="text-muted fw-bold">Bạn chưa có giao dịch nào hoàn thành.</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Trả hàng - Redesign -->
<div class="modal fade" id="returnModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <form id="returnForm" action="" method="POST" enctype="multipart/form-data" class="no-barba">
                @csrf
                <div class="p-4 bg-warning text-dark border-bottom border-warning border-4">
                    <h5 class="font-luxury fw-bold mb-0 text-uppercase ls-1">YÊU CẦU TRẢ HÀNG / HOÀN TIỀN</h5>
                </div>
                <div class="modal-body p-4 bg-white">
                    <div class="p-3 rounded-4 bg-warning-soft text-dark mb-4 border border-warning border-opacity-10">
                        <p class="small mb-0">Bạn đang yêu cầu hoàn tiền cho đơn hàng <strong id="returnOrderIdText"></strong>. Số tiền hoàn dự kiến: <strong id="returnAmountText" class="text-danger fs-5"></strong></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted ls-1 text-uppercase">Lý do trả hàng <span class="text-danger">*</span></label>
                        <select name="LyDo" class="form-select rounded-pill px-4 py-2 border" required>
                            <option value="">-- Chọn lý do chính đáng --</option>
                            <option value="Sản phẩm lỗi/hỏng hóc">Sản phẩm bị trầy xước / hỏng hóc do vận chuyển</option>
                            <option value="Giao sai sản phẩm">Giao không đúng màu sắc / kích thước đã chọn</option>
                            <option value="Sản phẩm không đúng mô tả">Chất lượng sản phẩm thực tế không như hình</option>
                            <option value="Khác">Lý do chủ quan khác</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted ls-1 text-uppercase">Mô tả tình trạng chi tiết <span class="text-danger">*</span></label>
                        <textarea name="NoiDung" class="form-control rounded-4 px-4 py-3 border" rows="3" placeholder="Vui lòng mô tả cụ thể vấn đề bạn gặp phải để chúng tôi xử lý nhanh nhất..." required></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold text-muted ls-1 text-uppercase">Minh chứng hình ảnh</label>
                        <div class="upload-area p-4 border-2 border-dashed rounded-4 text-center cursor-pointer hover-bg-light transition-all">
                            <input type="file" name="HinhAnhMinhChung" class="form-control d-none" id="returnImgInput" accept="image/*" onchange="previewReturnImage(this)">
                            <label for="returnImgInput" class="w-100 h-100 cursor-pointer mb-0">
                                <div id="upload-placeholder">
                                    <i class="fa-solid fa-cloud-arrow-up fs-2 text-gold mb-2"></i>
                                    <p class="small text-muted mb-0 fw-bold">Chọn ảnh từ thiết bị của bạn</p>
                                    <p class="extra-small text-muted opacity-75">Tải lên ảnh sản phẩm lỗi để được ưu tiên xử lý</p>
                                </div>
                                <img id="return-preview" class="img-thumbnail d-none" style="max-height: 150px;">
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0 bg-white">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold small ls-1 border" data-bs-dismiss="modal">HỦY BỎ</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-5 fw-bold small ls-1 shadow-sm">GỬI YÊU CẦU NGAY</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Chi tiết đơn hàng - Receipt Style -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div id="orderContent">
                <!-- Load bằng AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Chỉnh sửa hồ sơ - Elegant -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <form action="{{ route('customer.profile.update') }}" method="POST" class="no-barba">
                @csrf
                <div class="p-4 bg-dark text-white border-bottom border-gold border-4">
                    <h5 class="font-luxury fw-bold mb-0 text-uppercase ls-1">CẬP NHẬT THÔNG TIN CÁ NHÂN</h5>
                </div>
                <div class="modal-body p-4 bg-white">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted ls-1">HỌ VÀ TÊN</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user text-gold"></i></span>
                            <input type="text" name="HoTen" class="form-control rounded-end-pill px-4 py-2 bg-light border-start-0" value="{{ $customer->HoTen }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted ls-1">SỐ ĐIỆN THOẠI</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-phone text-gold"></i></span>
                            <input type="text" name="SDT" class="form-control rounded-end-pill px-4 py-2 bg-light border-start-0" value="{{ $customer->SDT }}" required>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold text-muted ls-1">ĐỊA CHỈ THƯỜNG TRÚ</label>
                        <textarea name="DiaChi" class="form-control rounded-4 px-4 py-3 bg-light" rows="3" required placeholder="Nhập địa chỉ chính xác để thuận tiện giao hàng...">{{ $customer->DiaChi }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0 bg-white">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold small ls-1 border" data-bs-dismiss="modal">ĐÓNG</button>
                    <button type="submit" class="btn btn-dark rounded-pill px-5 fw-bold small ls-1 shadow-sm">LƯU THAY ĐỔI</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    :root {
        --gold-primary: #af9245;
        --gold-light: #d4af37;
        --gold-dark: #8e762a;
        --gold-soft: rgba(175, 146, 69, 0.1);
        --bg-soft: #f8f9fa;
        --text-main: #2d3436;
    }

    .font-luxury { font-family: 'Playfair Display', serif; }
    .ls-1 { letter-spacing: 1px; }
    .extra-small { font-size: 0.65rem; }
    
    /* Sidebar Luxury */
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
        font-size: 0.9rem;
    }
    .nav-luxury .list-group-item.active .icon-box-sm {
        background: var(--gold-primary) !important;
        color: white !important;
    }
    
    /* KPI Cards */
    .hover-luxury:hover {
        border: 1px solid var(--gold-primary) !important;
        box-shadow: 0 15px 30px rgba(175, 146, 69, 0.15) !important;
        transform: translateY(-5px);
    }
    .bg-primary-soft { background: #eef2ff; }
    .bg-success-soft { background: #f0fdf4; }
    .bg-warning-soft { background: #fffbeb; }
    .bg-danger-soft { background: #fef2f2; }
    .bg-info-soft { background: #f0f9ff; }
    
    /* Info Cards */
    .info-card:hover {
        background-color: var(--bg-soft) !important;
        border-color: var(--gold-primary) !important;
    }

    /* Tabs Luxury */
    .nav-pills .nav-link { color: #64748b; background: transparent; transition: all 0.3s; }
    .nav-pills .nav-link.active { 
        background: var(--text-main) !important; 
        color: white !important; 
        box-shadow: 0 5px 15px rgba(0,0,0,0.15); 
    }
    
    /* Review Card */
    .review-luxury-card { border: 1px solid #edf2f7; }
    .rating-stars-luxury { color: #f1c40f; }
    .hover-scale:hover { transform: scale(1.05); }
    
    /* Order Detail Styling */
    .receipt-header { background: #1a1a1a; color: white; padding: 2.5rem; position: relative; overflow: hidden; }
    .order-item-img { width: 55px; height: 75px; object-fit: contain; border-radius: 8px; background: white; padding: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .table-luxury tbody tr:hover { background-color: var(--gold-soft); }
    .btn-gold-outline { border: 1px solid var(--gold-primary); color: var(--gold-primary); }
    .btn-gold-outline:hover { background: var(--gold-primary); color: white; }

    .status-timeline { position: relative; }
    .timeline-dot { width: 14px; height: 14px; border-radius: 50%; margin-top: 5px; z-index: 2; position: relative; border: 3px solid white; box-shadow: 0 0 0 2px var(--gold-primary); }
    .timeline-line { position: absolute; left: 6px; top: 20px; bottom: -30px; width: 2px; background: #e9ecef; z-index: 1; }
    
    /* Paid Stamp Effect */
    .paid-stamp {
        position: absolute;
        top: 20px;
        right: 40px;
        width: 140px;
        height: 140px;
        border: 4px double #198754;
        color: #198754;
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 0.95rem;
        text-transform: uppercase;
        transform: rotate(-25deg);
        opacity: 0.8;
        z-index: 10;
        background: rgba(255,255,255,0.05);
        box-shadow: 0 0 0 5px rgba(25, 135, 84, 0.05);
        animation: stampIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    @keyframes stampIn {
        from { transform: rotate(-25deg) scale(2.5); opacity: 0; }
        to { transform: rotate(-25deg) scale(1); opacity: 0.8; }
    }

    .border-dashed { border-style: dashed !important; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const handleHash = () => {
            const hash = window.location.hash;
            if (hash === '#tab-history-orders' || hash === '#orders' || hash === '#completed-orders-tab') {
                const completedTab = document.getElementById('completed-orders-tab');
                if (completedTab) {
                    const tab = new bootstrap.Tab(completedTab);
                    tab.show();
                }
            } else if (hash === '#active-orders-tab') {
                const activeTab = document.getElementById('active-orders-tab');
                if (activeTab) {
                    const tab = new bootstrap.Tab(activeTab);
                    tab.show();
                }
            }
        };

        handleHash();
        window.addEventListener('hashchange', handleHash);
    });

    function previewReturnImage(input) {
        const placeholder = document.getElementById('upload-placeholder');
        const preview = document.getElementById('return-preview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                placeholder.classList.add('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function viewImageFull(src) {
        // Có thể dùng modal xem ảnh full hoặc open window
        window.open(src, '_blank');
    }

    function openReturnModal(id, amount) {
        const modal = new bootstrap.Modal(document.getElementById('returnModal'));
        document.getElementById('returnOrderIdText').innerText = '#' + id;
        document.getElementById('returnAmountText').innerText = Number(amount).toLocaleString('vi-VN') + '₫';
        document.getElementById('returnForm').action = `/orders/return/${id}`;
        modal.show();
    }

    function viewOrderDetail(id) {
        const modal = new bootstrap.Modal(document.getElementById('orderModal'));
        document.getElementById('orderContent').innerHTML = `
            <div class="p-5 text-center bg-white rounded-4">
                <div class="spinner-grow text-gold mb-3" role="status"></div>
                <h5 class="font-luxury fw-bold text-dark">Đang khởi tạo hóa đơn chi tiết...</h5>
                <p class="text-muted small">Mọi dữ liệu của bạn đều được bảo mật tuyệt đối</p>
            </div>`;
        modal.show();

        fetch(`/orders/detail/${id}`)
            .then(res => res.json())
            .then(order => {
                const date = new Date(order.NgayDat).toLocaleString('vi-VN');
                const statusMap = {
                    'ChoThanhToan': 'Chờ thanh toán', 'ChoXacNhan': 'Chờ xác nhận', 'DaXacNhan': 'Đã xác nhận', 'DangGiao': 'Đang giao', 'DaGiao': 'Đã giao', 'DaHuy': 'Đã hủy'
                };
                
                const isFullyPaid = Number(order.SoTienDaThanhToan) >= Number(order.TongTien);
                const hasPaidSomething = Number(order.SoTienDaThanhToan) > 0;

                let html = `
                    <div class="receipt-header">
                        ${isFullyPaid ? `
                            <div class="paid-stamp">
                                <i class="fa-solid fa-certificate fs-1 mb-2"></i>
                                <span>ĐÃ THANH TOÁN</span>
                                <small style="font-size: 0.5rem; letter-spacing: 2px;">SHELF LUXURY</small>
                            </div>
                        ` : ''}
                        <div class="d-flex justify-content-between align-items-start position-relative z-2">
                            <div>
                                <h3 class="font-luxury fw-bold mb-1 text-uppercase ls-1">HÓA ĐƠN CHI TIẾT</h3>
                                <p class="mb-0 opacity-75 small">Mã đơn hàng: <span class="fw-bold">#ORD-${order.MaDH}</span></p>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="row mt-5 position-relative z-2">
                            <div class="col-6 border-start border-gold border-3 ps-4">
                                <small class="d-block opacity-50 text-uppercase fw-bold ls-1 mb-1" style="font-size:0.6rem;">Thời gian đặt hàng</small>
                                <span class="fw-bold">${date}</span>
                            </div>
                            <div class="col-6 text-end">
                                <small class="d-block opacity-50 text-uppercase fw-bold ls-1 mb-1" style="font-size:0.6rem;">Trạng thái đơn hàng</small>
                                <span class="badge bg-white text-dark fw-bold px-3 py-2 rounded-pill shadow-sm">${statusMap[order.TrangThaiDH] || order.TrangThaiDH}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body p-4 bg-white">
                        ${hasPaidSomething ? `
                            <div class="alert alert-success border-0 rounded-4 p-3 mb-4 d-flex align-items-center shadow-sm" style="background: #f0fdf4;">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                    <i class="fa-solid fa-shield-check fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-success small text-uppercase ls-1">Xác nhận thanh toán</div>
                                    <div class="extra-small text-muted">Chúng tôi đã nhận được <strong>${Number(order.SoTienDaThanhToan).toLocaleString('vi-VN')}₫</strong>. Cảm ơn quý khách!</div>
                                </div>
                            </div>
                        ` : ''}

                        <div class="row mb-5 g-4">
                            <div class="col-md-6">
                                <div class="p-4 bg-light rounded-4 h-100 border shadow-sm transition-all hover-luxury">
                                    <h6 class="fw-bold mb-3 text-dark small text-uppercase ls-1 d-flex align-items-center"><i class="fa-solid fa-location-dot me-2 text-gold"></i> Địa chỉ nhận hàng</h6>
                                    <div class="fw-bold text-dark mb-1">${order.khach_hang?.HoTen || 'Khách hàng'}</div>
                                    <div class="text-secondary small mb-2"><i class="fa-solid fa-phone me-1 opacity-50"></i> ${order.khach_hang?.SDT || 'N/A'}</div>
                                    <div class="text-secondary small lh-base"><i class="fa-solid fa-map-pin me-1 opacity-50"></i> ${order.DiaChiGiaoHang}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-4 bg-light rounded-4 h-100 border shadow-sm transition-all hover-luxury">
                                    <h6 class="fw-bold mb-3 text-dark small text-uppercase ls-1 d-flex align-items-center"><i class="fa-solid fa-credit-card me-2 text-gold"></i> Phương thức giao dịch</h6>
                                    <div class="fw-bold text-dark mb-1">
                                        ${order.PhuongThucThanhToan === 'TienMat' ? 'Thanh toán tiền mặt (COD)' : 
                                          (order.PhuongThucThanhToan === 'VNPay' ? 'Ví điện tử VNPay' : 'Chuyển khoản trực tiếp')}
                                    </div>
                                    <div class="text-secondary small">Vận chuyển: Giao hàng tiêu chuẩn</div>
                                    <div class="text-success small fw-bold mt-2 d-flex align-items-center"><i class="fa-solid fa-truck-fast me-2"></i> Miễn phí vận chuyển</div>
                                </div>
                            </div>
                        </div>

                        <h6 class="fw-bold mb-3 text-dark small text-uppercase ls-1 px-2">Chi tiết sản phẩm đã chọn</h6>
                        <div class="table-responsive px-2">
                            <table class="table align-middle table-borderless">
                                <thead>
                                    <tr class="text-muted small border-bottom">
                                        <th class="py-3">Mô tả sản phẩm</th>
                                        <th class="text-center py-3">Số lượng</th>
                                        <th class="text-end py-3">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${(order.chi_tiet_don_hangs).map(item => {
                                        const sp = item.san_pham;
                                        const variant = item.variant;
                                        let imgUrl = 'https://via.placeholder.com/100x150';
                                        if (sp) {
                                            if (sp.HinhAnh && sp.HinhAnh.startsWith('http')) {
                                                imgUrl = sp.HinhAnh;
                                            } else if (sp.HinhAnh) {
                                                imgUrl = '/assets/images/products/' + sp.HinhAnh;
                                            } else if (sp.main_image_url) {
                                                imgUrl = sp.main_image_url;
                                            }
                                        }
                                        if (variant && variant.HinhAnh) {
                                            imgUrl = variant.HinhAnh.startsWith('http') ? variant.HinhAnh : '/assets/images/products/' + variant.HinhAnh;
                                        }
                                        return `
                                        <tr class="border-bottom-dashed">
                                            <td class="py-4">
                                                <div class="d-flex align-items-center">
                                                    <img src="${imgUrl}" class="order-item-img me-3 border">
                                                    <div>
                                                        <div class="fw-bold text-dark small">${sp?.TenSP || 'Sản phẩm cao cấp'}</div>
                                                        ${variant ? `<div class="extra-small text-muted bg-white border d-inline-block px-2 rounded-pill mt-1">${variant.MauSac}${variant.KichThuoc ? ' - ' . variant.KichThuoc : ''}</div>` : ''}
                                                        <div class="text-muted extra-small mt-1">Đơn giá: ${Number(item.DonGia).toLocaleString('vi-VN')}₫</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center fw-bold text-dark">x${item.SoLuong}</td>
                                            <td class="text-end fw-bold text-dark font-luxury">${Number(item.ThanhTien).toLocaleString('vi-VN')}₫</td>
                                        </tr>
                                    `}).join('')}
                                </tbody>
                            </table>
                        </div>

                        <div class="p-4 bg-dark text-white rounded-4 mt-5 shadow-lg border-bottom border-gold border-3">
                            <div class="d-flex justify-content-between mb-2 opacity-50 extra-small fw-bold text-uppercase ls-1">
                                <span>Giá trị hàng hóa</span>
                                <span>${(Number(order.TongTien) + Number(order.SoTienGiam || 0)).toLocaleString('vi-VN')}₫</span>
                            </div>
                            ${order.SoTienGiam > 0 ? `
                                <div class="d-flex justify-content-between mb-2 text-warning extra-small fw-bold text-uppercase ls-1">
                                    <span>Đặc quyền ưu đãi (Mã giảm giá)</span>
                                    <span>-${Number(order.SoTienGiam).toLocaleString('vi-VN')}₫</span>
                                </div>
                            ` : ''}
                            
                            <div class="pt-3 border-top border-secondary border-opacity-25 mt-3">
                                <div class="d-flex justify-content-between mb-3 align-items-center">
                                    <span class="fw-bold text-uppercase ls-1 extra-small opacity-50">TỔNG GIÁ TRỊ GIAO DỊCH</span>
                                    <span class="fw-bold fs-4 font-luxury text-gold-light">${Number(order.TongTien).toLocaleString('vi-VN')}₫</span>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-2 text-success extra-small fw-bold text-uppercase ls-1">
                                    <span>Số tiền đã ghi nhận</span>
                                    <span>${Number(order.SoTienDaThanhToan || 0).toLocaleString('vi-VN')}₫</span>
                                </div>
                                <div class="d-flex justify-content-between pt-2">
                                    <span class="fw-bold text-uppercase ls-1 small text-warning">SỐ TIỀN CẦN THANH TOÁN THÊM</span>
                                    <span class="fw-bold fs-3 text-warning font-luxury">${Math.max(0, Number(order.TongTien) - Number(order.SoTienDaThanhToan || 0)).toLocaleString('vi-VN')}₫</span>
                                </div>
                            </div>
                        </div>

                        <!-- Status History - Luxury Timeline -->
                        <div class="mt-5 pt-4 border-top">
                            <h6 class="fw-bold mb-4 text-dark small text-uppercase ls-1 px-2 d-flex align-items-center"><i class="fa-solid fa-clock-rotate-left me-2 text-gold"></i> Lịch sử xử lý đơn hàng</h6>
                            <div class="status-timeline px-4">
                                ${(order.status_logs || []).map((log, index) => `
                                    <div class="d-flex gap-4 mb-4 position-relative">
                                        ${index < (order.status_logs.length - 1) ? '<div class="timeline-line"></div>' : ''}
                                        <div class="timeline-dot bg-gold"></div>
                                        <div class="flex-grow-1 p-3 bg-light rounded-4 border shadow-sm-hover transition-all">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <div class="fw-bold text-dark small">${log.HanhDong}</div>
                                                <div class="text-muted extra-small"><i class="fa-regular fa-clock me-1"></i>${new Date(log.created_at).toLocaleString('vi-VN')}</div>
                                            </div>
                                            ${log.GhiChu ? `<div class="extra-small font-italic text-secondary mt-1 border-start border-3 border-gold ps-2">${log.GhiChu}</div>` : ''}
                                        </div>
                                    </div>
                                `).join('')}
                                ${order.status_logs && order.status_logs.length === 0 ? '<div class="p-4 bg-light rounded-4 text-center text-muted extra-small">Chưa có bản ghi lịch sử cho đơn hàng này.</div>' : ''}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-2 bg-white gap-2">
                        <button class="btn btn-light rounded-pill px-4 fw-bold small ls-1 border" data-bs-dismiss="modal">ĐÓNG LẠI</button>
                        ${['ChoThanhToan', 'ChoXacNhan'].includes(order.TrangThaiDH) ? `
                            <form action="/orders/cancel/${order.MaDH}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn hàng này? Thao tác này không thể khôi phục.')" class="no-barba">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                <button type="submit" class="btn btn-outline-danger rounded-pill px-4 fw-bold small ls-1">HỦY ĐƠN</button>
                            </form>
                        ` : ''}
                        <button class="btn btn-dark rounded-pill px-5 fw-bold small ls-1 shadow-sm" onclick="window.print()">
                            <i class="fa-solid fa-print me-2 text-gold"></i> IN HÓA ĐƠN
                        </button>
                    </div>
                `;
                document.getElementById('orderContent').innerHTML = html;
            });
    }
</script>
@endsection
