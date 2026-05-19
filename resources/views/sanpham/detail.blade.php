@extends('layouts.app')

@push('styles')
<style>
    .luxury-description {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #2d3436;
    }
    .luxury-description h2, .luxury-description h3, .luxury-description h4 {
        font-family: 'Playfair Display', serif;
        color: var(--gold-dark);
        margin-top: 2.5rem;
        margin-bottom: 1.5rem;
        font-weight: 700;
        position: relative;
        display: inline-block;
    }
    .luxury-description h2::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 40px;
        height: 2px;
        background: var(--gold-primary);
    }
    .luxury-description p {
        line-height: 1.8;
        margin-bottom: 1.5rem;
        font-size: 1.05rem;
        text-align: justify;
    }
    .luxury-description ul, .luxury-description ol {
        margin-bottom: 1.5rem;
        padding-left: 1.5rem;
    }
    .luxury-description li {
        margin-bottom: 0.5rem;
    }
    .luxury-description img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 2rem 0;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .feature-highlight-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin: 3rem 0;
    }
    .feature-highlight-item {
        background: #fdfbf7;
        padding: 25px;
        border-radius: 15px;
        border: 1px solid rgba(175, 146, 69, 0.1);
        transition: all 0.3s ease;
        text-align: center;
    }
    .feature-highlight-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(175, 146, 69, 0.1);
        border-color: var(--gold-primary);
    }
    .feature-highlight-item i {
        font-size: 1.8rem;
        color: var(--gold-primary);
        margin-bottom: 15px;
        display: block;
    }
    .feature-highlight-item span {
        font-weight: 700;
        display: block;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #2d3436;
    }
    .luxury-quote {
        font-style: italic;
        border-left: 4px solid var(--gold-primary);
        padding-left: 25px;
        margin: 3rem 0;
        font-size: 1.2rem;
        color: var(--gold-dark);
        font-family: 'Playfair Display', serif;
    }
    
    /* Related Products Alignment Fix */
    .product-card {
        background: #fff;
        border: 1px solid var(--border-soft);
        border-radius: 15px;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
        border-color: var(--gold-primary);
        box-shadow: var(--shadow-md);
    }
    .product-card .img-box {
        aspect-ratio: 1/1;
        background: #fdfbf7;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        overflow: hidden;
    }
    .product-card .img-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.5s ease;
    }
    .product-card:hover .img-box img {
        transform: scale(1.1);
    }
    .product-card .product-name {
        color: var(--text-main);
        transition: color 0.3s ease;
    }
    .product-card:hover .product-name {
        color: var(--gold-primary);
    }
    .product-card .btn-outline-light {
        border-color: var(--gold-primary) !important;
        color: var(--gold-primary) !important;
    }
    .product-card .btn-outline-light:hover {
        background: var(--gold-primary) !important;
        color: #fff !important;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-5">
        <ol class="breadcrumb small text-uppercase" style="letter-spacing: 1px;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-muted hover-gold">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('sanpham.index') }}" class="text-muted hover-gold">Sản phẩm</a></li>
            <li class="breadcrumb-item active" style="color: var(--gold-primary)">{{ $product->TenSP }}</li>
        </ol>
    </nav>

    <div class="row min-vh-75 mb-5 align-items-start">
        <div class="col-lg-6 position-relative mb-4 mb-lg-0">
            <!-- Hiệu ứng ánh sáng phía sau -->
            <div class="position-absolute top-50 start-50 translate-middle" style="width: 300px; height: 300px; background: var(--gold-primary); filter: blur(150px); opacity: 0.15; z-index: -1;"></div>
            
            <!-- Product Image Container -->
            <div class="d-flex align-items-center justify-content-center bg-white rounded-4 shadow-sm border p-4" style="height: 500px;">
                <img id="mainImage" src="{{ $product->main_image_url }}" 
                     class="img-fluid" style="max-height: 100%; object-fit: contain;">
            </div>
                 
            @if ($product->hinhanhsanpham->isNotEmpty() || $product->variants->whereNotNull('HinhAnh')->isNotEmpty())
            <div class="d-flex gap-3 justify-content-center mt-4 overflow-auto py-2">
                <img src="{{ $product->main_image_url }}" 
                      class="img-thumbnail rounded-3 border-gold" width="70" height="70" style="cursor:pointer; object-fit: cover;" onclick="changeImage(this, this.src)">
                @foreach($product->hinhanhsanpham as $img)
                    <img src="{{ $img->url }}" 
                         class="img-thumbnail rounded-3 border-0" width="70" height="70" style="cursor:pointer; object-fit: cover; opacity: 0.6;" onclick="changeImage(this, this.src)">
                @endforeach
                @foreach($product->variants->whereNotNull('HinhAnh') as $v)
                    @php
                        $vUrl = filter_var($v->HinhAnh, FILTER_VALIDATE_URL) ? $v->HinhAnh : asset('assets/images/products/' . $v->HinhAnh);
                    @endphp
                    <img src="{{ $vUrl }}" 
                         class="img-thumbnail rounded-3 border-0" width="70" height="70" style="cursor:pointer; object-fit: cover; opacity: 0.6;" onclick="changeImage(this, this.src)">
                @endforeach
            </div>
            @endif
        </div>

        <div class="col-lg-6">
            <div class="product-info-panel p-lg-5 p-4 rounded-4 shadow-sm bg-white border">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge bg-gold-soft text-gold px-3 py-2 rounded-pill small fw-bold">{{ $product->danhmuc->TenDM ?? 'Kệ gia dụng' }}</span>
                    <div class="text-warning small">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= round($product->average_rating) ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                        @endfor
                        <span class="ms-2 text-muted">({{ $product->review_count }} đánh giá)</span>
                    </div>
                </div>
                
                <h1 class="font-luxury display-6 mb-3 text-dark fw-bold">{{ $product->TenSP }}</h1>
                
                <div class="d-flex align-items-center gap-3 mb-4 small text-muted">
                    <span><i class="fa-solid fa-tag me-2 text-gold"></i>Thương hiệu: <strong>{{ $product->thuong_hieu_string ?: 'Chính hãng' }}</strong></span>
                    <span>|</span>
                    <span><i class="fa-solid fa-check-circle me-2 text-success"></i>Tình trạng: <strong>{{ $product->SoLuong > 0 ? 'Còn hàng' : 'Hết hàng' }}</strong></span>
                </div>

                <div class="mb-4 p-4 rounded-3" style="background: #f8f9fa;">
                    @if($product->khuyen_mai_active)
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="text-muted text-decoration-line-through fs-5">{{ number_format($product->DonGia, 0, ',', '.') }}₫</span>
                            <span class="badge bg-danger rounded-pill">-{{ (int)$product->khuyen_mai_active->PhanTramGiam }}%</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="display-5 fw-bold text-danger">{{ number_format($product->gia_hien_tai, 0, ',', '.') }} <small class="fs-4">VNĐ</small></span>
                            <span class="text-muted small"><i class="fa-solid fa-cart-shopping me-1 text-gold"></i>Đã bán {{ (int)$product->SoLuongDaBan }} sản phẩm</span>
                        </div>
                    @else
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="display-5 fw-bold text-dark">{{ number_format($product->DonGia, 0, ',', '.') }} <small class="fs-4">VNĐ</small></span>
                            <span class="text-muted small"><i class="fa-solid fa-cart-shopping me-1 text-gold"></i>Đã bán {{ (int)$product->SoLuongDaBan }} sản phẩm</span>
                        </div>
                    @endif
                </div>

                @if($product->variants->isNotEmpty())
                <div class="mb-4" id="variant-selection-area">
                    <label class="fw-bold mb-3 text-dark d-block">Lựa chọn phiên bản <span class="text-danger">*</span>:</label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($product->variants as $variant)
                        <div class="variant-option">
                            @php
                                $promoPercent = 0;
                                if ($product->khuyen_mai_active) {
                                    $promoPercent = (int)$product->khuyen_mai_active->PhanTramGiam;
                                }
                                
                                // Ưu tiên áp dụng % giảm giá của chương trình khuyến mãi đang chạy lên giá niêm yết của biến thể
                                if ($promoPercent > 0) {
                                    $displayPrice = $variant->GiaNiemYet * (1 - ($promoPercent / 100));
                                } else {
                                    // Nếu không có KM chung, mới sử dụng giá KM riêng của biến thể (nếu có)
                                    $displayPrice = ($variant->GiaKhuyenMai && $variant->GiaKhuyenMai > 0) 
                                        ? $variant->GiaKhuyenMai 
                                        : $variant->GiaNiemYet;
                                }
                            @endphp
                            <input type="radio" class="btn-check" name="variant_id" id="v-{{ $variant->MaVariant }}" 
                                   value="{{ $variant->MaVariant }}"
                                   data-price="{{ number_format($variant->GiaNiemYet, 0, ',', '.') }}"
                                   data-promo-percent="{{ $promoPercent }}"
                                   data-final-price="{{ number_format($displayPrice, 0, ',', '.') }}"
                                   data-image="{{ $variant->HinhAnh ? (Str::startsWith($variant->HinhAnh, 'http') ? $variant->HinhAnh : asset('assets/images/products/' . $variant->HinhAnh)) : '' }}"
                                   data-stock="{{ $variant->SoLuongTon }}"
                                   onchange="onVariantChange(this)">
                            <label class="btn btn-outline-dark px-3 py-2 rounded-3 small" for="v-{{ $variant->MaVariant }}">
                                {{ $variant->MauSac }} {{ $variant->KichThuoc ? ' - ' . $variant->KichThuoc : '' }} {{ $variant->SoTang ? ' (' . $variant->SoTang . ' tầng)' : '' }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                    <div id="variant-error" class="text-danger extra-small mt-2" style="display:none">Vui lòng chọn một phiên bản sản phẩm!</div>
                </div>
                @endif
                
                <p class="lh-lg mb-5 text-muted" style="font-size: 0.95rem;">
                    {{ $product->MoTaNgan ?: $product->MoTa }}
                </p>
                
                <div class="d-flex align-items-center gap-3 mb-4">
                    @if($product->SoLuong > 0)
                        <div class="input-group rounded-3 overflow-hidden border" style="width: 140px; height: 58px;">
                            <button class="btn btn-link text-dark text-decoration-none px-3" type="button" onclick="updateQty(-1)"><i class="fa-solid fa-minus"></i></button>
                            <input type="number" id="qty" class="form-control text-center bg-transparent text-dark border-0 fw-bold" value="1" min="1" max="{{ $product->SoLuong }}" readonly>
                            <button class="btn btn-link text-dark text-decoration-none px-3" type="button" onclick="updateQty(1)"><i class="fa-solid fa-plus"></i></button>
                        </div>
                        
                        <button onclick="addToCart({{ $product->MaSP }})" id="btnAddToCart" class="btn btn-dark py-3 flex-grow-1 fs-6 fw-bold ls-1 rounded-3 h-100">
                            THÊM VÀO GIỎ HÀNG
                        </button>
                        
                        <button onclick="toggleFavorite({{ $product->MaSP }}, this)" class="btn btn-outline-dark py-3 px-4 rounded-3 {{ $product->is_favorite ? 'active' : '' }}">
                            <i class="{{ $product->is_favorite ? 'fa-solid text-danger' : 'fa-regular' }} fa-heart fs-5"></i>
                        </button>
                    @else
                        <button class="btn btn-outline-secondary py-3 flex-grow-1 fs-6" disabled>
                            Tạm hết hàng
                        </button>
                    @endif
                </div>

                <div class="row g-2">
                    <div class="col-6 col-md-4">
                        <div class="p-2 border rounded-3 text-center small text-muted">
                            <i class="fa-solid fa-truck-fast d-block mb-1 text-gold fs-5"></i> Giao hàng 2-4 ngày
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="p-2 border rounded-3 text-center small text-muted">
                            <i class="fa-solid fa-shield-halved d-block mb-1 text-gold fs-5"></i> Bảo hành 12 tháng
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="p-2 border rounded-3 text-center small text-muted">
                            <i class="fa-solid fa-arrows-rotate d-block mb-1 text-gold fs-5"></i> Đổi trả 7 ngày
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Description Details -->
    <div class="row mt-5 pt-5">
        <div class="col-lg-8">
            <ul class="nav nav-tabs border-0 gap-4 mb-4" id="productTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active fw-bold border-0 px-0 text-dark position-relative" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-pane" type="button">MÔ TẢ CHI TIẾT</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-bold border-0 px-0 text-dark position-relative" id="review-tab" data-bs-toggle="tab" data-bs-target="#review-pane" type="button">ĐÁNH GIÁ ({{ $product->review_count }})</button>
                </li>
            </ul>
            
            <div class="tab-content" id="productTabContent">
                <div class="tab-pane fade show active" id="desc-pane">
                    <div class="p-lg-5 p-4 rounded-4 mb-4 bg-white shadow-sm border luxury-description">
                        @if($product->MaSP == 16)
                            <div class="luxury-intro mb-5">
                                <h2 class="display-6 fw-bold mb-4">Kiến Tạo Không Gian Sống Đẳng Cấp</h2>
                                <p class="lead fs-5 text-muted mb-4">Sự kết hợp hoàn hảo giữa công năng hiện đại và tính thẩm mỹ tinh tế, mang lại trải nghiệm sống thượng lưu cho ngôi nhà của bạn.</p>
                            </div>

                            <div class="feature-highlight-grid">
                                <div class="feature-highlight-item">
                                    <i class="fa-solid fa-gem"></i>
                                    <span>Chất liệu Cao Cấp</span>
                                </div>
                                <div class="feature-highlight-item">
                                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                                    <span>Thiết kế Tinh Xảo</span>
                                </div>
                                <div class="feature-highlight-item">
                                    <i class="fa-solid fa-shield-heart"></i>
                                    <span>Độ bền Vĩnh Cửu</span>
                                </div>
                                <div class="feature-highlight-item">
                                    <i class="fa-solid fa-leaf"></i>
                                    <span>Thân thiện Môi trường</span>
                                </div>
                            </div>

                            <div class="luxury-content">
                                <h3>Vẻ Đẹp Đến Từ Sự Đơn Giản</h3>
                                <p>Sản phẩm không chỉ là một vật dụng gia đình thông thường, mà là một tác phẩm nghệ thuật đại diện cho triết lý thiết kế tối giản. Từng đường nét được trau chuốt tỉ mỉ bởi những nghệ nhân hàng đầu, sử dụng công nghệ xử lý bề mặt tiên tiến nhất hiện nay.</p>
                                
                                <div class="luxury-quote">
                                    "Sự sang trọng không nằm ở sự phô trương, mà nằm ở sự thấu hiểu và tinh tế trong từng chi tiết nhỏ nhất."
                                </div>

                                <h3>Trải Nghiệm Đỉnh Cao</h3>
                                <p>Với kết cấu thông minh, sản phẩm mang lại khả năng tối ưu hóa không gian một cách tuyệt vời. Tải trọng vượt trội cùng hệ thống ngăn chứa linh hoạt giúp bạn dễ dàng sắp xếp cuộc sống một cách khoa học và phong cách nhất.</p>
                                
                                <ul class="list-unstyled mt-4">
                                    <li class="mb-3 d-flex align-items-center"><i class="fa-solid fa-check text-gold me-3"></i> <span>Chống trầy xước và bám bẩn tuyệt đối</span></li>
                                    <li class="mb-3 d-flex align-items-center"><i class="fa-solid fa-check text-gold me-3"></i> <span>Khả năng chịu lực lên tới 100kg</span></li>
                                    <li class="mb-3 d-flex align-items-center"><i class="fa-solid fa-check text-gold me-3"></i> <span>Dễ dàng vệ sinh và bảo dưỡng</span></li>
                                </ul>
                            </div>
                        @else
                            <div class="content-text lh-lg text-dark">
                                {!! $product->chiTiet->NoiDungChiTiet ?? $product->MoTa !!}
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="tab-pane fade" id="review-pane">
                    <div class="p-lg-5 p-4 rounded-4 mb-4 bg-white shadow-sm border">
                        <div class="mb-5 pb-4 border-bottom">
                            <h4 class="fw-bold mb-4">Để lại đánh giá của bạn</h4>
                            @auth
                                @if($canReview)
                                <form action="{{ route('sanpham.review', $product->MaSP) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Chọn số sao</label>
                                        <div class="rating-input text-warning fs-4">
                                            <input type="radio" name="SoSao" value="5" id="star5" class="d-none" required><label for="star5" style="cursor:pointer"><i class="fa-regular fa-star"></i></label>
                                            <input type="radio" name="SoSao" value="4" id="star4" class="d-none"><label for="star4" style="cursor:pointer" class="ms-1"><i class="fa-regular fa-star"></i></label>
                                            <input type="radio" name="SoSao" value="3" id="star3" class="d-none"><label for="star3" style="cursor:pointer" class="ms-1"><i class="fa-regular fa-star"></i></label>
                                            <input type="radio" name="SoSao" value="2" id="star2" class="d-none"><label for="star2" style="cursor:pointer" class="ms-1"><i class="fa-regular fa-star"></i></label>
                                            <input type="radio" name="SoSao" value="1" id="star1" class="d-none"><label for="star1" style="cursor:pointer" class="ms-1"><i class="fa-regular fa-star"></i></label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nội dung nhận xét</label>
                                        <textarea name="NoiDung" class="form-control rounded-3" rows="4" placeholder="Cảm nhận của bạn về sản phẩm..." required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Hình ảnh thực tế (nếu có)</label>
                                        <input type="file" name="HinhAnhDG" class="form-control rounded-3" accept="image/*" onchange="previewReviewImage(this)">
                                        <div id="review-image-preview" class="mt-2 d-none">
                                            <img src="" class="img-thumbnail" style="max-height: 150px;">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-dark px-4 py-2 rounded-pill fw-bold">GỬI ĐÁNH GIÁ</button>
                                </form>
                                @else
                                    @php
                                        $khachHang = \App\Models\KhachHang::where('MaTK', auth()->user()->MaTK)->first();
                                        $alreadyReviewed = $khachHang ? \App\Models\DanhGia::where('MaSP', $product->MaSP)->where('MaKH', $khachHang->MaKH)->exists() : false;
                                    @endphp

                                    @if($alreadyReviewed)
                                    <div class="alert alert-success border-0 rounded-4 p-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <i class="fa-solid fa-check-circle fs-3 text-success"></i>
                                            <div>
                                                <h6 class="fw-bold mb-1">Bạn đã đánh giá sản phẩm này</h6>
                                                <p class="mb-0 small">Cảm ơn bạn đã để lại nhận xét. Mỗi khách hàng chỉ có thể đánh giá một lần cho mỗi sản phẩm.</p>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <div class="alert alert-info border-0 rounded-4 p-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <i class="fa-solid fa-circle-info fs-3 text-info"></i>
                                            <div>
                                                <h6 class="fw-bold mb-1">Chưa thể đánh giá sản phẩm</h6>
                                                <p class="mb-0 small">Bạn chỉ có thể đánh giá những sản phẩm đã mua và nhận hàng thành công tại cửa hàng chúng tôi.</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endif
                            @else
                            <div class="alert alert-light border text-center py-4 rounded-4">
                                <p class="mb-3">Vui lòng đăng nhập để đánh giá sản phẩm này.</p>
                                <a href="{{ route('login') }}" class="btn btn-gold px-4 py-2 rounded-pill fw-bold">ĐĂNG NHẬP NGAY</a>
                            </div>
                            @endauth
                        </div>

                        <div class="reviews-list">
                            @forelse($reviews as $dg)
                            <div class="review-item mb-4 pb-4 border-bottom">
                                <div class="d-flex justify-content-between mb-2">
                                    <h6 class="fw-bold mb-0">{{ $dg->khachhang->HoTen }}</h6>
                                    <div class="text-warning small">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="{{ $i <= $dg->SoSao ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-muted small mb-2"><i class="fa-regular fa-clock me-1"></i> {{ $dg->created_at ? $dg->created_at->format('d/m/Y') : 'Vừa xong' }}</p>
                                <p class="mb-2 text-dark">{{ $dg->NoiDung }}</p>
                                @if($dg->HinhAnhDG)
                                <div class="mb-0">
                                    <img src="{{ asset($dg->HinhAnhDG) }}" class="img-thumbnail rounded-3 shadow-sm" style="max-height: 120px; cursor: pointer" onclick="viewImageFull('{{ asset($dg->HinhAnhDG) }}')">
                                </div>
                                @endif
                            </div>
                            @empty
                            <p class="text-center text-muted py-4">Chưa có đánh giá nào cho sản phẩm này.</p>
                            @endforelse

                            <div class="mt-4">
                                {{ $reviews->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <h3 class="font-luxury mb-4 text-dark fs-4 fw-bold">Thông số kỹ thuật</h3>
            <div class="p-4 rounded-4 bg-white shadow-sm border">
                <table class="table table-borderless text-dark small mb-0">
                    <tbody>
                        @if($product->chiTiet->ChatLieu ?? false)
                        <tr>
                            <td class="ps-0 text-muted" width="45%">Chất liệu</td>
                            <td class="fw-bold text-end text-dark">{{ $product->chiTiet->ChatLieu }}</td>
                        </tr>
                        @endif
                        @if($product->chiTiet->KichThuoc ?? false)
                        <tr>
                            <td class="ps-0 text-muted">Kích thước</td>
                            <td class="fw-bold text-end text-dark">{{ $product->chiTiet->KichThuoc }}</td>
                        </tr>
                        @endif
                        @if($product->chiTiet->TaiTrong ?? false)
                        <tr>
                            <td class="ps-0 text-muted">Tải trọng</td>
                            <td class="fw-bold text-end text-dark">{{ $product->chiTiet->TaiTrong }}</td>
                        </tr>
                        @endif
                        @if($product->chiTiet->SoTang ?? false)
                        <tr>
                            <td class="ps-0 text-muted">Số tầng</td>
                            <td class="fw-bold text-end text-dark">{{ $product->chiTiet->SoTang }} tầng</td>
                        </tr>
                        @endif
                        @if($product->chiTiet->MauSac ?? false)
                        <tr>
                            <td class="ps-0 text-muted">Màu sắc</td>
                            <td class="fw-bold text-end text-dark">{{ $product->chiTiet->MauSac }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="ps-0 text-muted">Nhà sản xuất</td>
                            <td class="fw-bold text-end text-dark">{{ $product->NhaSanXuat->TenNXB ?? 'Chính hãng' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->isNotEmpty())
    <div class="mt-5 pt-5 border-top" style="border-color: rgba(212,175,55,0.1)!important;">
        <h3 class="font-luxury text-center mb-5">Khám Phá Thêm</h3>
        <div class="row g-4 row-cols-2 row-cols-md-4 bento-grid">
            @foreach($relatedProducts as $sp)
            <div class="col">
                <div class="product-card">
                    <a href="{{ route('sanpham.detail', $sp->MaSP) }}" class="img-box">
                        <img src="{{ $sp->main_image_url }}" style="max-height: 100%; max-width: 100%; object-fit: contain; filter: drop-shadow(0 10px 15px rgba(0,0,0,0.5));">
                    </a>
                    <div class="p-3 d-flex flex-column flex-grow-1">
                        <a href="{{ route('sanpham.detail', $sp->MaSP) }}" class="product-name text-decoration-none fw-bold mb-2" style="font-size: 14px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 40px;">{{ $sp->TenSP }}</a>
                        <div class="mt-auto">
                            @if($sp->khuyen_mai_active)
                                <div class="text-muted extra-small text-decoration-line-through">{{ number_format($sp->DonGia, 0, ',', '.') }}₫</div>
                                <div class="fw-bold fs-5 mb-3" style="color: var(--gold-light);">{{ number_format($sp->gia_hien_tai, 0, ',', '.') }}₫</div>
                            @else
                                <div class="fw-bold fs-5 mb-3" style="color: var(--gold-light);">{{ number_format($sp->DonGia, 0, ',', '.') }}₫</div>
                            @endif
                            <a href="{{ route('sanpham.detail', $sp->MaSP) }}" class="btn btn-outline-light w-100 py-2 fs-6" style="border-color: rgba(255,255,255,0.2);">Xem ngay</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Modal xem ảnh full -->
<div class="modal fade" id="imageFullModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0 text-center">
                <img src="" id="fullImageContent" class="img-fluid rounded-4">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewReviewImage(input) {
        const previewDiv = document.getElementById('review-image-preview');
        const previewImg = previewDiv.querySelector('img');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewDiv.classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            previewDiv.classList.add('d-none');
        }
    }

    function viewImageFull(src) {
        document.getElementById('fullImageContent').src = src;
        const myModal = new bootstrap.Modal(document.getElementById('imageFullModal'));
        myModal.show();
    }

    function changeImage(element, src) {
        document.getElementById('mainImage').src = src;
        // Reset styles for all thumbs
        document.querySelectorAll('.img-thumbnail').forEach(img => {
            img.style.opacity = '0.6';
            img.style.boxShadow = 'none';
        });
        element.style.opacity = '1';
        element.style.boxShadow = '0 0 10px var(--gold-primary)';
    }

    function updateQty(change) {
        let qtyInput = document.getElementById('qty');
        let currentQty = parseInt(qtyInput.value);
        let maxQty = parseInt(qtyInput.getAttribute('max')); 
        
        let newQty = currentQty + change;
        
        if (newQty >= 1 && newQty <= maxQty) {
            qtyInput.value = newQty;
        } else if (newQty > maxQty) {
            alert("Chỉ còn " + maxQty + " sản phẩm!");
        }
    }

    function onVariantChange(input) {
        let price = input.dataset.price;
        let finalPrice = input.dataset.finalPrice;
        let promoPercent = parseInt(input.dataset.promoPercent || 0);
        let image = input.dataset.image;
        let stock = parseInt(input.dataset.stock);
        let errorDiv = document.getElementById('variant-error');
        
        if (errorDiv) errorDiv.style.display = 'none';

        const priceContainer = document.querySelector('.mb-4.p-4.rounded-3');
        if (priceContainer) {
            if (promoPercent > 0) {
                priceContainer.innerHTML = `
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="text-muted text-decoration-line-through fs-5">${price}₫</span>
                        <span class="badge bg-danger rounded-pill">-${promoPercent}%</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="display-5 fw-bold text-danger">${finalPrice} <small class="fs-4">VNĐ</small></span>
                        <span class="text-muted small"><i class="fa-solid fa-cart-shopping me-1 text-gold"></i>Đã bán {{ (int)$product->SoLuongDaBan }} sản phẩm</span>
                    </div>
                `;
            } else {
                priceContainer.innerHTML = `
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="display-5 fw-bold text-dark">${price} <small class="fs-4">VNĐ</small></span>
                        <span class="text-muted small"><i class="fa-solid fa-cart-shopping me-1 text-gold"></i>Đã bán {{ (int)$product->SoLuongDaBan }} sản phẩm</span>
                    </div>
                `;
            }
        }
        
        if (image) {
            document.getElementById('mainImage').src = image;
        }

        const qtyInput = document.getElementById('qty');
        if (qtyInput) {
            qtyInput.setAttribute('max', stock);
            if (parseInt(qtyInput.value) > stock) {
                qtyInput.value = stock > 0 ? stock : 1;
            }
        }

        const btnAdd = document.getElementById('btnAddToCart');
        if (btnAdd) {
            if (stock <= 0) {
                btnAdd.disabled = true;
                btnAdd.innerText = 'PHIÊN BẢN HẾT HÀNG';
            } else {
                btnAdd.disabled = false;
                btnAdd.innerText = 'THÊM VÀO GIỎ HÀNG';
            }
        }
    }

    function addToCart(id) {
        const variantInput = document.querySelector('input[name="variant_id"]:checked');
        const hasVariants = document.getElementById('variant-selection-area') !== null;
        const errorDiv = document.getElementById('variant-error');
        const variantArea = document.getElementById('variant-selection-area');
        
        if (hasVariants && !variantInput) {
            if (errorDiv) {
                errorDiv.style.display = 'block';
                errorDiv.classList.add('animate__animated', 'animate__shakeX');
                setTimeout(() => errorDiv.classList.remove('animate__shakeX'), 500);
            }
            if (variantArea) {
                variantArea.style.padding = '10px';
                variantArea.style.backgroundColor = 'rgba(220, 53, 69, 0.05)';
                variantArea.style.borderRadius = '10px';
                variantArea.style.border = '1px solid #dc3545';
            }
            variantArea.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        if (variantArea) {
            variantArea.style.padding = '0';
            variantArea.style.backgroundColor = 'transparent';
            variantArea.style.border = 'none';
        }
        if (errorDiv) errorDiv.style.display = 'none';

        const variantId = variantInput ? variantInput.value : null;
        const qty = document.getElementById('qty').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`{{ url('/cart/add') }}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify({ id: id, variant_id: variantId, qty: qty })
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                if(confirm('Đã thêm sản phẩm vào giỏ hàng! Đến giỏ hàng ngay?')) {
                    if(typeof barba !== 'undefined') barba.go("{{ url('/cart') }}");
                    else window.location.href = "{{ url('/cart') }}";
                }
                const cartBadge = document.getElementById('cart-count-badge');
                if (cartBadge) {
                    cartBadge.innerText = data.cartCount;
                    cartBadge.classList.remove('d-none');
                }
            } else if(data.status === 'login_required') {
                window.location.href = "{{ route('login') }}";
            } else {
                alert(data.message);
            }
        })
        .catch(err => console.error(err));
    }

    function toggleFavorite(maSP, btn) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch(`{{ route('favorites.toggle') }}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify({ maSP: maSP })
        })
        .then(res => {
            if (res.status === 401) {
                window.location.href = "{{ route('login') }}";
                return;
            }
            return res.json();
        })
        .then(data => {
            if (data.status === 'added') {
                btn.classList.add('active');
                btn.querySelector('i').className = 'fa-solid fa-heart text-danger fs-5';
            } else if (data.status === 'removed') {
                btn.classList.remove('active');
                btn.querySelector('i').className = 'fa-regular fa-heart fs-5';
            }
        });
    }

    // Star rating logic
    document.querySelectorAll('.rating-input label').forEach(label => {
        label.addEventListener('mouseover', function() {
            let starId = this.getAttribute('for');
            let starValue = parseInt(starId.replace('star', ''));
            highlightStars(starValue);
        });
        
        label.addEventListener('click', function() {
            let starId = this.getAttribute('for');
            let starValue = parseInt(starId.replace('star', ''));
            setSelectedStars(starValue);
        });
    });

    document.querySelector('.rating-input').addEventListener('mouseleave', function() {
        let selectedInput = document.querySelector('.rating-input input:checked');
        if (selectedInput) {
            highlightStars(parseInt(selectedInput.value));
        } else {
            highlightStars(0);
        }
    });

    function highlightStars(value) {
        for (let i = 1; i <= 5; i++) {
            let starIcon = document.querySelector(`label[for="star${i}"] i`);
            if (i <= value) {
                starIcon.className = 'fa-solid fa-star';
            } else {
                starIcon.className = 'fa-regular fa-star';
            }
        }
    }

    function setSelectedStars(value) {
        highlightStars(value);
    }
</script>
@endpush
@endsection
