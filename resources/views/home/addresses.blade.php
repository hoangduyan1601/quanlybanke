@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="avatar-lg bg-gold-soft text-gold rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-user fs-1"></i>
                        </div>
                        <h5 class="fw-bold mb-1">{{ Auth::user()->TenDangNhap }}</h5>
                        <p class="text-muted small mb-0">Thành viên từ {{ Auth::user()->created_at ? Auth::user()->created_at->format('m/Y') : 'n/a' }}</p>
                    </div>
                    <div class="list-group list-group-flush small">
                        <a href="{{ route('customer.profile') }}" class="list-group-item list-group-item-action border-0 px-0 py-3 d-flex align-items-center">
                            <i class="fa-solid fa-user-gear me-3 text-gold"></i> Hồ sơ cá nhân
                        </a>
                        <a href="{{ route('addresses.index') }}" class="list-group-item list-group-item-action border-0 px-0 py-3 d-flex align-items-center active bg-transparent text-gold fw-bold">
                            <i class="fa-solid fa-location-dot me-3"></i> Sổ địa chỉ
                        </a>
                        <a href="{{ route('favorites.index') }}" class="list-group-item list-group-item-action border-0 px-0 py-3 d-flex align-items-center">
                            <i class="fa-solid fa-heart me-3 text-gold"></i> Sản phẩm yêu thích
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="font-luxury mb-0">Sổ Địa Chỉ</h3>
                <button type="button" class="btn btn-dark px-4 rounded-pill fw-bold small" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                    <i class="fa-solid fa-plus me-2"></i> THÊM ĐỊA CHỈ MỚI
                </button>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="row g-4">
                @forelse($addresses as $address)
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 {{ $address->MacDinh ? 'border-start border-gold border-4' : '' }}">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $address->HoTenNguoiNhan }}</h6>
                                    @if($address->MacDinh)
                                        <span class="badge bg-gold-soft text-gold small px-2 py-1 rounded-pill">Mặc định</span>
                                    @endif
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                        @if(!$address->MacDinh)
                                        <li>
                                            <form action="{{ route('addresses.setDefault', $address->MaDC) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item py-2 small">Đặt làm mặc định</button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('addresses.destroy', $address->MaDC) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item py-2 small text-danger">Xóa địa chỉ</button>
                                            </form>
                                        </li>
                                        @else
                                        <li class="dropdown-item py-2 small text-muted">Địa chỉ mặc định</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <p class="small text-muted mb-2">
                                <i class="fa-solid fa-phone me-2"></i> {{ $address->SDTNguoiNhan }}
                            </p>
                            <p class="small mb-0 text-dark lh-base">
                                <i class="fa-solid fa-location-dot me-2 text-gold"></i>
                                {{ $address->DiaChiChiTiet }}, {{ $address->PhuongXa }}, {{ $address->QuanHuyen }}, {{ $address->TinhThanh }}
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <div class="mb-3 text-muted">
                        <i class="fa-solid fa-map-location-dot fs-1"></i>
                    </div>
                    <p class="text-muted">Bạn chưa có địa chỉ nào trong sổ địa chỉ.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Modal Thêm Địa Chỉ -->
<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="modal-title fw-bold">Thêm địa chỉ nhận hàng mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('addresses.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Họ tên người nhận</label>
                        <input type="text" name="HoTenNguoiNhan" class="form-control rounded-3" placeholder="Ví dụ: Nguyễn Văn A" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Số điện thoại</label>
                        <input type="text" name="SDTNguoiNhan" class="form-control rounded-3" placeholder="Ví dụ: 0912345678" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tỉnh / Thành phố</label>
                        <input type="text" name="TinhThanh" class="form-control rounded-3" placeholder="Ví dụ: Hà Nội" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Quận / Huyện</label>
                        <input type="text" name="QuanHuyen" class="form-control rounded-3" placeholder="Ví dụ: Cầu Giấy" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Phường / Xã</label>
                        <input type="text" name="PhuongXa" class="form-control rounded-3" placeholder="Ví dụ: Dịch Vọng" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Địa chỉ chi tiết</label>
                        <input type="text" name="DiaChiChiTiet" class="form-control rounded-3" placeholder="Ví dụ: Số 123, đường Xuân Thủy" required>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-gold rounded-pill px-4 fw-bold">LƯU ĐỊA CHỈ</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
