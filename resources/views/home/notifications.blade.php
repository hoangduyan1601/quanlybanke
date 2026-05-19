@extends('layouts.app')

@section('title', 'Thông báo của tôi - Shelf Luxury')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-5 pb-3 border-bottom">
                <div>
                    <h2 class="font-luxury display-6 fw-bold mb-1">Thông Báo</h2>
                    <p class="text-muted mb-0">Cập nhật những tin tức mới nhất về đơn hàng và ưu đãi dành riêng cho bạn.</p>
                </div>
                @if($notifications->count() > 0)
                <button onclick="markAllAsRead()" class="btn btn-outline-dark rounded-pill px-4 py-2 small fw-bold ls-1">
                    <i class="fa-solid fa-check-double me-2"></i> ĐÁNH DẤU TẤT CẢ ĐÃ ĐỌC
                </button>
                @endif
            </div>

            <!-- Notifications List -->
            <div class="notifications-wrapper">
                @forelse($notifications as $tb)
                <div id="noti-{{ $tb->MaTB }}" 
                     class="notification-card p-4 rounded-4 mb-3 transition {{ $tb->TrangThaiDoc ? 'bg-light opacity-75' : 'bg-white shadow-sm border-start border-4 border-gold shadow-hover' }}"
                     style="cursor: pointer;"
                     onclick="readNotification({{ $tb->MaTB }}, '{{ $tb->LienKet ?: '#' }}')">
                    
                    <div class="d-flex align-items-start gap-3">
                        <div class="noti-icon p-3 rounded-circle {{ $tb->TrangThaiDoc ? 'bg-secondary text-white' : 'bg-gold-soft text-gold' }}">
                            <i class="fa-solid {{ $tb->LoaiTB == 'DonHang' ? 'fa-box-open' : 'fa-bolt' }} fs-4"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h5 class="fw-bold mb-0 {{ $tb->TrangThaiDoc ? 'text-muted' : 'text-dark' }}">{{ $tb->TieuDe }}</h5>
                                <small class="text-muted"><i class="fa-regular fa-clock me-1"></i> {{ date('d/m/Y H:i', strtotime($tb->NgayGui)) }}</small>
                            </div>
                            <p class="mb-0 {{ $tb->TrangThaiDoc ? 'text-muted' : 'text-secondary' }}">{{ $tb->NoiDung }}</p>
                            
                            @if(!$tb->TrangThaiDoc)
                            <div class="mt-2">
                                <span class="badge bg-gold-soft text-gold rounded-pill px-3 py-1 extra-small fw-bold">MỚI</span>
                            </div>
                            @endif
                        </div>
                        <div class="text-end">
                            <i class="fa-solid fa-chevron-right text-muted small"></i>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fa-solid fa-bell-slash display-1 text-light"></i>
                    </div>
                    <h4 class="fw-bold text-muted">Bạn chưa có thông báo nào</h4>
                    <p class="text-muted mb-4">Mọi tin tức quan trọng sẽ được hiển thị tại đây.</p>
                    <a href="{{ route('home') }}" class="btn btn-gold rounded-pill px-5 py-3 fw-bold ls-1">QUAY LẠI TRANG CHỦ</a>
                </div>
                @endforelse

                <!-- Pagination -->
                <div class="mt-5 d-flex justify-content-center">
                    {{ $notifications->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .notification-card {
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    .shadow-hover:hover {
        transform: translateX(5px);
        box-shadow: 0 10px 25px rgba(175, 146, 69, 0.1) !important;
        border-color: var(--gold-primary);
    }
    .extra-small {
        font-size: 0.65rem;
    }
    .bg-gold-soft {
        background-color: rgba(175, 146, 69, 0.1);
    }
    .text-gold {
        color: var(--gold-primary);
    }
    .border-gold {
        border-color: var(--gold-primary) !important;
    }
</style>
@endpush

@push('scripts')
<script>
    function readNotification(id, link) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch(`/notifications/mark-as-read/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        }).finally(() => {
            window.location.href = link;
        });
    }

    function markAllAsRead() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        if(confirm('Bạn có muốn đánh dấu tất cả thông báo là đã đọc?')) {
            fetch(`/notifications/mark-all-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            }).then(() => {
                location.reload();
            });
        }
    }
</script>
@endpush
@endsection
