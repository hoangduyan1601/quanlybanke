@extends('layouts.admin')

@section('title', 'Product Review Management')

@section('content')
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        padding: 2rem;
        border-radius: 1.5rem;
        color: white;
        margin-bottom: 2rem;
    }
    .review-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
    }
</style>

<div class="container-fluid p-0">
    <div class="dashboard-header d-md-flex align-items-center justify-content-between shadow-sm">
        <div>
            <h2 class="fw-bold mb-1">Quản Lý Đánh Giá</h2>
            <p class="mb-0 text-white-50">Xem và kiểm duyệt các phản hồi từ khách hàng</p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.danhgia.index') }}" method="GET" class="row g-3">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Tìm theo sản phẩm, khách hàng, nội dung..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="rating" class="form-select bg-light">
                        <option value="">Tất cả đánh giá</option>
                        @for($i=5; $i>=1; $i--)
                            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} sao</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 rounded-3">Lọc</button>
                </div>
                <div class="col-md-2 text-end">
                    <a href="{{ route('admin.danhgia.index') }}" class="btn btn-light w-100 rounded-3"><i class="fas fa-sync-alt me-1"></i> Làm mới</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold" width="5%">#ID</th>
                        <th class="py-3 text-uppercase small fw-bold" width="20%">Khách Hàng</th>
                        <th class="py-3 text-uppercase small fw-bold" width="20%">Sản Phẩm</th>
                        <th class="py-3 text-uppercase small fw-bold" width="10%">Đánh Giá</th>
                        <th class="py-3 text-uppercase small fw-bold">Nội Dung</th>
                        <th class="py-3 text-uppercase small fw-bold" width="10%">Ngày Gửi</th>
                        <th class="pe-4 py-3 text-uppercase small fw-bold text-end">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $item)
                        <tr>
                            <td class="ps-4 text-muted">#{{ $item->MaDG }}</td>
                            <td>
                                <div class="fw-bold">{{ $item->khachhang->HoTen ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $item->khachhang->Email ?? '' }}</small>
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 200px;" title="{{ $item->sanpham->TenSP ?? 'N/A' }}">{{ $item->sanpham->TenSP ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <div class="text-warning">
                                    @for($i=1; $i<=5; $i++)
                                        <i class="{{ $i <= $item->SoSao ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                                    @endfor
                                </div>
                            </td>
                            <td>
                                <div class="small">{{ $item->NoiDung }}</div>
                                @if($item->HinhAnhDG)
                                    <div class="mt-2">
                                        <a href="{{ asset($item->HinhAnhDG) }}" target="_blank">
                                            <img src="{{ asset($item->HinhAnhDG) }}" class="review-img shadow-sm rounded">
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td class="small text-muted">
                                {{ $item->created_at ? $item->created_at->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td class="pe-4 text-end">
                                <form action="{{ route('admin.danhgia.destroy', $item->MaDG) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light rounded-pill px-3 text-danger" onclick="return confirm('Xác nhận xóa đánh giá này?')">
                                        <i class="fas fa-trash me-1"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-light border-top">
            {{ $list->links() }}
        </div>
    </div>
</div>
@endsection
