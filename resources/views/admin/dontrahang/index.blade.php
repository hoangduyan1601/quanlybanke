@extends('layouts.admin')

@section('title', 'Return Management')

@section('content')
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        padding: 2rem;
        border-radius: 1.5rem;
        color: white;
        margin-bottom: 2rem;
    }
</style>

<div class="container-fluid p-0">
    <div class="dashboard-header d-md-flex align-items-center justify-content-between shadow-sm">
        <div>
            <h2 class="fw-bold mb-1">Quản Lý Đổi Trả</h2>
            <p class="mb-0 text-white-50">Xử lý các yêu cầu trả hàng và hoàn tiền từ khách hàng</p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.dontrahang.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Tìm theo Mã Trả, Mã ĐH, Tên KH..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select bg-light">
                        <option value="">Tất cả trạng thái</option>
                        <option value="ChoDuyet" {{ request('status') == 'ChoDuyet' ? 'selected' : '' }}>Chờ duyệt</option>
                        <option value="DaNhanHangTra" {{ request('status') == 'DaNhanHangTra' ? 'selected' : '' }}>Đã nhận hàng trả</option>
                        <option value="DaHoanTien" {{ request('status') == 'DaHoanTien' ? 'selected' : '' }}>Đã hoàn tiền</option>
                        <option value="TuChoi" {{ request('status') == 'TuChoi' ? 'selected' : '' }}>Từ chối</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 rounded-3">Lọc</button>
                </div>
                <div class="col-md-1">
                    <a href="{{ route('admin.dontrahang.index') }}" class="btn btn-light w-100 rounded-3"><i class="fas fa-sync-alt"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold">Mã Trả</th>
                        <th class="py-3 text-uppercase small fw-bold">Mã ĐH</th>
                        <th class="py-3 text-uppercase small fw-bold">Khách Hàng</th>
                        <th class="py-3 text-uppercase small fw-bold">Minh Chứng</th>
                        <th class="py-3 text-uppercase small fw-bold">Lý Do</th>
                        <th class="py-3 text-uppercase small fw-bold">Số Tiền Hoàn</th>
                        <th class="py-3 text-uppercase small fw-bold">Trạng Thái</th>
                        <th class="pe-4 py-3 text-uppercase small fw-bold text-end">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $item)
                        <tr>
                            <td class="ps-4 text-muted">#TH{{ $item->MaTraHang }}</td>
                            <td><a href="{{ route('admin.donhang.show', $item->MaDH) }}" class="fw-bold">#{{ $item->MaDH }}</a></td>
                            <td>
                                <div>{{ $item->donhang->khachHang->HoTen ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $item->donhang->khachHang->SDT ?? '' }}</small>
                            </td>
                            <td>
                                @if($item->HinhAnhMinhChung)
                                    <a href="{{ asset($item->HinhAnhMinhChung) }}" target="_blank">
                                        <img src="{{ asset($item->HinhAnhMinhChung) }}" class="rounded shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                    </a>
                                @else
                                    <span class="text-muted small">N/A</span>
                                @endif
                            </td>
                            <td>
                                <div class="small text-truncate" style="max-width: 200px;" title="{{ $item->LyDo }}">{{ $item->LyDo }}</div>
                            </td>
                            <td class="fw-bold text-danger">{{ number_format($item->SoTienHoan) }}₫</td>
                            <td>
                                @php
                                    $statusClasses = [
                                        'ChoDuyet' => 'bg-warning-subtle text-warning',
                                        'DaNhanHangTra' => 'bg-info-subtle text-info',
                                        'DaHoanTien' => 'bg-success-subtle text-success',
                                        'TuChoi' => 'bg-danger-subtle text-danger',
                                    ];
                                    $statusLabels = [
                                        'ChoDuyet' => 'Chờ duyệt',
                                        'DaNhanHangTra' => 'Đã nhận hàng',
                                        'DaHoanTien' => 'Đã hoàn tiền',
                                        'TuChoi' => 'Từ chối',
                                    ];
                                @endphp
                                <span class="badge {{ $statusClasses[$item->TrangThaiTra] ?? 'bg-secondary' }} rounded-pill px-3">
                                    {{ $statusLabels[$item->TrangThaiTra] ?? $item->TrangThaiTra }}
                                </span>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="dropdown d-inline">
                                    <button class="btn btn-sm btn-light rounded-pill px-3 dropdown-toggle" data-bs-toggle="dropdown">
                                        Cập nhật
                                    </button>
                                    <ul class="dropdown-menu shadow border-0">
                                        <li>
                                            <form action="{{ route('admin.dontrahang.update_status', $item->MaTraHang) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="ChoDuyet">
                                                <button type="submit" class="dropdown-item small">Chờ duyệt</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.dontrahang.update_status', $item->MaTraHang) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="DaNhanHangTra">
                                                <button type="submit" class="dropdown-item small">Đã nhận hàng</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.dontrahang.update_status', $item->MaTraHang) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="DaHoanTien">
                                                <button type="submit" class="dropdown-item small text-success fw-bold">Hoàn tiền</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.dontrahang.update_status', $item->MaTraHang) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="TuChoi">
                                                <button type="submit" class="dropdown-item small text-danger">Từ chối</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                                <form action="{{ route('admin.dontrahang.destroy', $item->MaTraHang) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light rounded-pill px-3 text-danger" onclick="return confirm('Xác nhận xóa yêu cầu này?')">
                                        <i class="fas fa-trash"></i>
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
