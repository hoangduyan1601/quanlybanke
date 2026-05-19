@extends('layouts.admin')

@section('title', 'Author Management - Creative Hub')

@section('content')
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        padding: 2rem;
        border-radius: 1.5rem;
        color: white;
        margin-bottom: 2rem;
    }
    .author-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
</style>

<div class="container-fluid p-0">
    <div class="dashboard-header d-md-flex align-items-center justify-content-between shadow-sm">
        <div>
            <h2 class="fw-bold mb-1 text-white">Quản Lý Thương Hiệu</h2>
            <p class="mb-0 text-white-50">Danh sách các thương hiệu và bộ sưu tập sản phẩm</p>
        </div>
        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalThuongHieu" onclick="openModalThem()">
            <i class="fas fa-plus me-2 text-primary"></i> Thêm Thương Hiệu
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <form method="get" action="{{ route('admin.thuonghieu.index') }}" class="row g-3">
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-text bg-light border-0 rounded-start-pill"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-0 rounded-end-pill" placeholder="Tìm tên thương hiệu, quốc tịch..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-dark w-100 rounded-pill">Tìm kiếm</button>
            </div>
        </form>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold">Thương Hiệu</th>
                        <th class="py-3 text-uppercase small fw-bold">Quốc Tịch</th>
                        <th class="py-3 text-uppercase small fw-bold">Mô Tả Ngắn</th>
                        <th class="pe-4 py-3 text-uppercase small fw-bold text-end">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $item)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $item->AnhDaiDien ? asset('assets/images/ThuongHieu/' . $item->AnhDaiDien) : 'https://via.placeholder.com/50' }}" class="author-avatar me-3">
                                    <div>
                                        <div class="fw-bold text-dark">{{ $item->Tenthuonghieu }}</div>
                                        <small class="text-muted">ID: #TH{{ $item->Mathuonghieu }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark border px-3 rounded-pill fw-normal">{{ $item->QuocTich ?: 'N/A' }}</span></td>
                            <td class="text-muted small">{{ Str::limit($item->MoTa, 80) }}</td>
                            <td class="pe-4 text-end">
                                <button class="btn btn-sm btn-light rounded-pill px-3 me-1" onclick="openModalSua('{{ $item->Mathuonghieu }}', '{{ addslashes($item->Tenthuonghieu) }}', '{{ $item->NgaySinh }}', '{{ $item->QuocTich }}', '{{ addslashes($item->MoTa) }}')">
                                    <i class="fas fa-edit text-warning"></i>
                                </button>
                                <form action="{{ route('admin.thuonghieu.destroy', ['id' => $item->Mathuonghieu]) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light rounded-pill px-3 text-danger" onclick="return confirm('Xác nhận xóa hồ sơ thương hiệu này?')">
                                        <i class="fas fa-trash-alt"></i>
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

<!-- Modal Thêm/Sửa -->
<div class="modal fade" id="modalThuongHieu" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 p-4 pb-0">
                <h5 class="fw-bold" id="modalTitle">Hồ Sơ Thương Hiệu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form method="post" id="formThuongHieu" action="{{ route('admin.thuonghieu.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div id="methodField"></div>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-2">TÊN THƯƠNG HIỆU</label>
                        <input type="text" class="form-control rounded-pill" id="inputTen" name="ten" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted mb-2">NĂM THÀNH LẬP</label>
                            <input type="number" class="form-control rounded-pill" id="inputNS" name="ngaysinh" placeholder="Ví dụ: 1990">
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted mb-2">QUỐC GIA</label>
                            <input type="text" class="form-control rounded-pill" id="inputQT" name="quoctich">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-2">LOGO THƯƠNG HIỆU</label>
                        <input type="file" class="form-control rounded-pill" name="anh" accept="image/*">
                    </div>
                    <div class="mb-4">
                        <label class="small fw-bold text-muted mb-2">MÔ TẢ CHI TIẾT</label>
                        <textarea class="form-control rounded-4" id="inputMota" name="mota" rows="4"></textarea>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 rounded-pill py-2 fw-bold shadow-sm">
                        <span id="btnSubmitText">Lưu Thông Tin</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openModalThem() {
        document.getElementById('modalTitle').textContent = 'Thêm Thương Hiệu Mới';
        document.getElementById('btnSubmitText').textContent = 'Xác nhận';
        document.getElementById('formThuongHieu').action = "{{ route('admin.thuonghieu.store') }}";
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('inputTen').value = '';
        document.getElementById('inputNS').value = '';
        document.getElementById('inputQT').value = '';
        document.getElementById('inputMota').value = '';
    }
    
    function openModalSua(id, ten, ns, qt, mota) {
        document.getElementById('modalTitle').textContent = 'Cập Nhật Thương Hiệu';
        document.getElementById('btnSubmitText').textContent = 'Cập nhật';
        document.getElementById('formThuongHieu').action = "/admin/ThuongHieu/" + id;
        document.getElementById('methodField').innerHTML = '@method("PUT")';
        document.getElementById('inputTen').value = ten;
        document.getElementById('inputNS').value = ns;
        document.getElementById('inputQT').value = qt;
        document.getElementById('inputMota').value = mota;
        const modal = new bootstrap.Modal(document.getElementById('modalThuongHieu'));
        modal.show();
    }
</script>
@endsection






