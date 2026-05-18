@extends('layouts.admin')

@section('title', 'Shipping Unit Management')

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
            <h2 class="fw-bold mb-1">Quản Lý Đơn Vị Vận Chuyển</h2>
            <p class="mb-0 text-white-50">Quản lý các đối tác giao hàng của hệ thống</p>
        </div>
        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalDVVC" onclick="openModalThem()">
            <i class="fas fa-plus me-2"></i> Thêm Đơn Vị
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold" width="10%">#ID</th>
                        <th class="py-3 text-uppercase small fw-bold">Tên Đơn Vị</th>
                        <th class="py-3 text-uppercase small fw-bold">Số Điện Thoại</th>
                        <th class="py-3 text-uppercase small fw-bold">Trạng Thái</th>
                        <th class="pe-4 py-3 text-uppercase small fw-bold text-end">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $item)
                        <tr>
                            <td class="ps-4 text-muted">#{{ $item->MaDVVC }}</td>
                            <td><span class="fw-bold text-dark">{{ $item->TenDVVC }}</span></td>
                            <td class="text-muted">{{ $item->SDT }}</td>
                            <td>
                                @if($item->TrangThai)
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3">Đang hợp tác</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3">Ngừng hợp tác</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <button class="btn btn-sm btn-light rounded-pill px-3 me-2" onclick="openModalSua('{{ $item->MaDVVC }}', '{{ addslashes($item->TenDVVC) }}', '{{ $item->SDT }}', '{{ $item->TrangThai }}')">
                                    <i class="fas fa-edit text-warning me-1"></i> Sửa
                                </button>
                                <form action="{{ route('admin.donvivanchuyen.destroy', $item->MaDVVC) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light rounded-pill px-3 text-danger" onclick="return confirm('Xác nhận xóa đơn vị này?')">
                                        <i class="fas fa-trash me-1"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Thêm/Sửa -->
<div class="modal fade" id="modalDVVC" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 p-4 pb-0">
                <h5 class="fw-bold" id="modalTitle">Cập Nhật Đơn Vị</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form method="post" id="formDVVC" action="{{ route('admin.donvivanchuyen.store') }}">
                    @csrf
                    <div id="methodField"></div>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-2">TÊN ĐƠN VỊ</label>
                        <input type="text" class="form-control rounded-pill" id="inputTen" name="TenDVVC" required>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-2">SỐ ĐIỆN THOẠI</label>
                        <input type="text" class="form-control rounded-pill" id="inputSDT" name="SDT" required>
                    </div>
                    <div class="mb-4">
                        <label class="small fw-bold text-muted mb-2">TRẠNG THÁI</label>
                        <select class="form-select rounded-pill" id="inputTrangThai" name="TrangThai">
                            <option value="1">Đang hợp tác</option>
                            <option value="0">Ngừng hợp tác</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 rounded-pill py-2 fw-bold shadow-sm">
                        <span id="btnSubmitText">Xác Nhận Lưu</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openModalThem() {
        document.getElementById('modalTitle').textContent = 'Thêm Đơn Vị Vận Chuyển';
        document.getElementById('btnSubmitText').textContent = 'Thêm mới';
        document.getElementById('formDVVC').action = "{{ route('admin.donvivanchuyen.store') }}";
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('inputTen').value = '';
        document.getElementById('inputSDT').value = '';
        document.getElementById('inputTrangThai').value = '1';
    }
    
    function openModalSua(id, ten, sdt, status) {
        document.getElementById('modalTitle').textContent = 'Sửa Đơn Vị Vận Chuyển';
        document.getElementById('btnSubmitText').textContent = 'Cập nhật';
        document.getElementById('formDVVC').action = "/admin/donvivanchuyen/" + id;
        document.getElementById('methodField').innerHTML = '@method("PUT")';
        document.getElementById('inputTen').value = ten;
        document.getElementById('inputSDT').value = sdt;
        document.getElementById('inputTrangThai').value = status;
        const modal = new bootstrap.Modal(document.getElementById('modalDVVC'));
        modal.show();
    }
</script>
@endsection
