@extends('layouts.admin')

@section('title', 'Sổ Địa Chỉ - ' . $customer->HoTen)

@section('content')
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        padding: 2rem;
        border-radius: 1.5rem;
        color: white;
        margin-bottom: 2rem;
    }
    .address-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid #f1f5f9;
    }
    .address-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    .badge-default {
        background: #ecfdf5;
        color: #059669;
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
    }
</style>

<div class="container-fluid p-0">
    <div class="dashboard-header d-md-flex align-items-center justify-content-between shadow-sm">
        <div>
            <h2 class="fw-bold mb-1">Sổ Địa Chỉ Khách Hàng</h2>
            <p class="mb-0 text-white-50">Khách hàng: <strong>{{ $customer->HoTen }}</strong> | Email: {{ $customer->Email }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.khachhang.index') }}" class="btn btn-outline-light rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i> Quay lại
            </a>
            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalAddress">
                <i class="fas fa-plus me-2"></i> Thêm Địa Chỉ
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        @forelse($customer->diaChis as $dc)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 rounded-4 address-card p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-map-marker-alt text-danger me-2"></i>
                            <h5 class="fw-bold mb-0">{{ $dc->HoTenNguoiNhan }}</h5>
                        </div>
                        @if($dc->MacDinh)
                            <span class="badge-default"><i class="fas fa-check-circle me-1"></i> Mặc định</span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <div class="small text-muted mb-1"><i class="fas fa-phone-alt me-2"></i> {{ $dc->SDTNguoiNhan }}</div>
                        <div class="text-dark">
                            {{ $dc->DiaChiChiTiet }}<br>
                            {{ $dc->PhuongXa }}, {{ $dc->QuanHuyen }}, {{ $dc->TinhThanh }}
                        </div>
                    </div>

                    <div class="mt-auto pt-3 border-top d-flex justify-content-end">
                        <form action="{{ route('admin.khachhang.addresses.destroy', [$customer->MaKH, $dc->MaDC]) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa địa chỉ này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger p-0 text-decoration-none">
                                <i class="far fa-trash-alt me-1"></i> Xóa địa chỉ
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                    <div class="mb-3">
                        <i class="fas fa-map-marked-alt fa-3x text-light"></i>
                    </div>
                    <h5 class="text-muted">Chưa có địa chỉ nào được lưu</h5>
                    <p class="text-muted small">Hãy nhấn vào nút "Thêm Địa Chỉ" để tạo địa chỉ giao hàng đầu tiên cho khách hàng này.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Thêm Địa Chỉ -->
<div class="modal fade" id="modalAddress" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <form action="{{ route('admin.khachhang.addresses.store', $customer->MaKH) }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0 ps-4 pt-4">
                    <h5 class="fw-bold mb-0">Thêm Địa Chỉ Mới</h5>
                    <button type="button" class="btn-close me-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Họ Tên Người Nhận</label>
                        <input type="text" name="HoTenNguoiNhan" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Số Điện Thoại</label>
                        <input type="text" name="SDTNguoiNhan" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Địa Chỉ Chi Tiết (Số nhà, tên đường)</label>
                        <input type="text" name="DiaChiChiTiet" class="form-control rounded-3" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Tỉnh/Thành</label>
                            <input type="text" name="TinhThanh" class="form-control rounded-3" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Quận/Huyện</label>
                            <input type="text" name="QuanHuyen" class="form-control rounded-3" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Phường/Xã</label>
                            <input type="text" name="PhuongXa" class="form-control rounded-3" required>
                        </div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="MacDinh" value="1" id="flexSwitchDefault">
                        <label class="form-check-label" for="flexSwitchDefault">Đặt làm địa chỉ mặc định</label>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-dark rounded-pill px-4">Lưu Địa Chỉ</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
