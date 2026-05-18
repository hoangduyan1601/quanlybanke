@extends('layouts.admin')

@section('title', 'Manage Variants - ' . $product->TenSP)

@section('content')
<div class="d-md-flex align-items-center justify-content-between mb-4">
    <div>
        <h3 class="mb-0 fw-bold">Quản Lý Biến Thể (SKU)</h3>
        <p class="text-muted small mb-0">Sản phẩm: <strong>{{ $product->TenSP }}</strong></p>
    </div>
    <div class="mt-3 mt-md-0">
        <a href="{{ route('admin.sanpham.index') }}" class="btn btn-luxury-outline">
            <i class="fas fa-arrow-left me-2"></i> Quay lại sản phẩm
        </a>
        <button type="button" class="btn btn-luxury-primary ms-2" data-bs-toggle="modal" data-bs-target="#modalVariant" onclick="openModalThem()">
            <i class="fas fa-plus me-2"></i> Thêm Biến Thể
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3">SKU</th>
                    <th class="py-3">Màu Sắc</th>
                    <th class="py-3">Kích Thước</th>
                    <th class="py-3">Số Tầng</th>
                    <th class="py-3 text-center">Tồn Kho</th>
                    <th class="py-3 text-end">Giá Niêm Yết</th>
                    <th class="pe-4 py-3 text-end">Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($product->variants as $variant)
                    <tr>
                        <td class="ps-4 fw-bold">#{{ $variant->SKU }}</td>
                        <td>{{ $variant->MauSac ?? '-' }}</td>
                        <td>{{ $variant->KichThuoc ?? '-' }}</td>
                        <td>{{ $variant->SoTang ?? '-' }}</td>
                        <td class="text-center">
                            <span class="badge {{ $variant->SoLuongTon > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill px-3">
                                {{ $variant->SoLuongTon }}
                            </span>
                        </td>
                        <td class="text-end fw-bold text-dark">{{ number_format($variant->GiaNiemYet) }}₫</td>
                        <td class="pe-4 text-end">
                            <button class="btn btn-sm btn-light rounded-pill px-3 me-2" 
                                    onclick="openModalSua('{{ $variant->MaVariant }}', '{{ $variant->SKU }}', '{{ $variant->MauSac }}', '{{ $variant->KichThuoc }}', '{{ $variant->SoTang }}', '{{ $variant->SoLuongTon }}', '{{ $variant->GiaNiemYet }}', '{{ $variant->GiaKhuyenMai }}', '{{ $variant->GiaNhap }}')">
                                <i class="fas fa-edit text-warning"></i>
                            </button>
                            <form action="{{ route('admin.sanpham.variants.destroy', $variant->MaVariant) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light rounded-pill px-3 text-danger" onclick="return confirm('Xác nhận xóa biến thể này?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">Sản phẩm này chưa có biến thể nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Thêm/Sửa Variant -->
<div class="modal fade" id="modalVariant" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 p-4 pb-0">
                <h5 class="fw-bold" id="modalTitle">Cập Nhật Biến Thể</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form method="post" id="formVariant" action="{{ route('admin.sanpham.variants.store', $product->MaSP) }}">
                    @csrf
                    <div id="methodField"></div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted mb-2">MÃ SKU <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded-pill" id="inputSKU" name="SKU" required placeholder="Ví dụ: KE-BEP-DEN-01">
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted mb-2">MÀU SẮC</label>
                            <input type="text" class="form-control rounded-pill" id="inputMau" name="MauSac" placeholder="Đen, Trắng, Gỗ...">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="small fw-bold text-muted mb-2">KÍCH THƯỚC</label>
                            <input type="text" class="form-control rounded-pill" id="inputKichThuoc" name="KichThuoc" placeholder="60x40cm...">
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold text-muted mb-2">SỐ TẦNG</label>
                            <input type="number" class="form-control rounded-pill" id="inputSoTang" name="SoTang">
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold text-muted mb-2">TỒN KHO <span class="text-danger">*</span></label>
                            <input type="number" class="form-control rounded-pill" id="inputTon" name="SoLuongTon" required value="0">
                        </div>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="small fw-bold text-muted mb-2">GIÁ NHẬP (VỐN)</label>
                            <input type="number" class="form-control rounded-pill" id="inputGiaNhap" name="GiaNhap">
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold text-muted mb-2">GIÁ NIÊM YẾT <span class="text-danger">*</span></label>
                            <input type="number" class="form-control rounded-pill" id="inputGiaNiemYet" name="GiaNiemYet" required>
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold text-muted mb-2">GIÁ KHUYẾN MÃI</label>
                            <input type="number" class="form-control rounded-pill" id="inputGiaKM" name="GiaKhuyenMai">
                        </div>
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
        document.getElementById('modalTitle').textContent = 'Thêm Biến Thể Mới';
        document.getElementById('btnSubmitText').textContent = 'Thêm mới';
        document.getElementById('formVariant').action = "{{ route('admin.sanpham.variants.store', $product->MaSP) }}";
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('inputSKU').value = '';
        document.getElementById('inputMau').value = '';
        document.getElementById('inputKichThuoc').value = '';
        document.getElementById('inputSoTang').value = '';
        document.getElementById('inputTon').value = '0';
        document.getElementById('inputGiaNhap').value = '';
        document.getElementById('inputGiaNiemYet').value = '';
        document.getElementById('inputGiaKM').value = '';
    }
    
    function openModalSua(id, sku, mau, kichthuoc, sotang, ton, niemyet, km, nhap) {
        document.getElementById('modalTitle').textContent = 'Sửa Biến Thể SKU';
        document.getElementById('btnSubmitText').textContent = 'Cập nhật';
        document.getElementById('formVariant').action = "/admin/sanpham/variants/" + id;
        document.getElementById('methodField').innerHTML = '@method("PUT")';
        document.getElementById('inputSKU').value = sku;
        document.getElementById('inputMau').value = mau;
        document.getElementById('inputKichThuoc').value = kichthuoc;
        document.getElementById('inputSoTang').value = sotang;
        document.getElementById('inputTon').value = ton;
        document.getElementById('inputGiaNhap').value = nhap;
        document.getElementById('inputGiaNiemYet').value = niemyet;
        document.getElementById('inputGiaKM').value = km;
        const modal = new bootstrap.Modal(document.getElementById('modalVariant'));
        modal.show();
    }
</script>
@endsection
