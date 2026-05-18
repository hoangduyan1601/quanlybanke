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

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold">Mã Trả</th>
                        <th class="py-3 text-uppercase small fw-bold">Mã ĐH</th>
                        <th class="py-3 text-uppercase small fw-bold">Khách Hàng</th>
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
                                <span class="badge {{ $statusClasses[$item->TrangThaiDHTra] ?? 'bg-secondary' }} rounded-pill px-3">
                                    {{ $statusLabels[$item->TrangThaiDHTra] ?? $item->TrangThaiDHTra }}
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
