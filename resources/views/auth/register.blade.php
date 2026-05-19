<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký thành viên | Shelf Luxury</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --gold-primary: #af9245;
            --gold-light: #d4af37;
            --gold-dark: #8c7336;
            --bg-deep: #0a0a0a;
            --text-main: #1a1a1a;
            --jakarta: 'Plus Jakarta Sans', sans-serif;
            --playfair: 'Playfair Display', serif;
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: var(--jakarta);
            background-color: #fff;
        }

        .auth-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Left Side: Image Content */
        .auth-side-image {
            flex: 1;
            position: relative;
            background: var(--bg-deep);
            display: none; /* Hidden on mobile */
            position: sticky;
            top: 0;
            height: 100vh;
        }
        @media (min-width: 992px) {
            .auth-side-image { display: block; }
        }

        .auth-side-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.6;
        }

        .image-overlay-content {
            position: absolute;
            top: 50%;
            left: 10%;
            transform: translateY(-50%);
            color: #fff;
            z-index: 2;
            max-width: 500px;
        }

        .image-overlay-content h1 {
            font-family: var(--playfair);
            font-size: 3.5rem;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 1.5rem;
        }

        .image-overlay-content h1 span {
            color: var(--gold-primary);
        }

        .image-overlay-content p {
            font-size: 1rem;
            color: rgba(255,255,255,0.7);
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 700;
        }

        /* Right Side: Form Content */
        .auth-side-form {
            flex: 1.5;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem;
            position: relative;
        }

        @media (max-width: 576px) {
            .auth-side-form { padding: 2rem; padding-top: 5rem; }
        }

        .form-container {
            width: 100%;
            max-width: 650px;
            margin: 0 auto;
        }

        .back-to-home {
            position: absolute;
            top: 40px;
            right: 40px;
            color: var(--text-main);
            text-decoration: none;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
            z-index: 10;
        }

        .back-to-home:hover {
            color: var(--gold-primary);
        }

        .form-header {
            margin-bottom: 3rem;
        }

        .form-header h2 {
            font-family: var(--playfair);
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 0.5rem;
        }

        .form-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 800;
            color: #999;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: none;
            border-bottom: 2px solid #f0f0f0;
            border-radius: 0;
            padding: 0.8rem 0;
            font-weight: 500;
            color: var(--text-main);
            transition: all 0.3s;
            font-size: 0.95rem;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--gold-primary);
        }

        .btn-luxury {
            background: var(--text-main);
            color: #fff;
            padding: 1.2rem;
            border-radius: 0;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            border: none;
            transition: all 0.4s;
            width: 100%;
        }

        .btn-luxury:hover {
            background: var(--gold-primary);
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(175, 146, 111, 0.2);
        }

        .auth-footer {
            margin-top: 3rem;
            text-align: center;
            font-weight: 500;
            color: #888;
        }

        .auth-footer a {
            color: var(--text-main);
            text-decoration: none;
            font-weight: 800;
            margin-left: 5px;
            border-bottom: 2px solid var(--gold-primary);
            transition: all 0.3s;
        }

        .auth-footer a:hover {
            color: var(--gold-primary);
        }

        .alert-custom {
            border-radius: 0;
            border: none;
            background: rgba(220, 53, 69, 0.05);
            color: #dc3545;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 2.5rem;
        }

        .brand-dot { color: var(--gold-primary); }

        .success-box {
            text-align: center;
            padding: 3rem;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: #f0fdf4;
            color: #16a34a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 2rem;
        }
    </style>
</head>
<body>

    <div class="auth-wrapper">
        <!-- Left Side: Image Content -->
        <div class="auth-side-image">
            <img src="https://images.unsplash.com/photo-1594026112284-02bb6f3352fe?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Luxury Shelf">
            <div class="image-overlay-content">
                <p>Đồng hành cùng bạn</p>
                <h1>Kiến Tạo Đẳng Cấp <span>Riêng</span></h1>
            </div>
        </div>

        <!-- Right Side: Form Content -->
        <div class="auth-side-form">
            <a href="{{ url('/') }}" class="back-to-home">
                <i class="fas fa-long-arrow-alt-left me-2"></i> Trang chủ
            </a>

            <div class="form-container">
                @if (isset($success) && $success)
                    <div class="success-box animate__animated animate__fadeIn">
                        <div class="success-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <h2 class="font-luxury mb-3">Chào mừng thành viên mới!</h2>
                        <p class="text-muted mb-5">Tài khoản của bạn đã được khởi tạo thành công. Hãy bắt đầu hành trình mua sắm đẳng cấp cùng Shelf Luxury ngay bây giờ.</p>
                        <a href="{{ route('login') }}" class="btn btn-luxury px-5">Đăng nhập ngay</a>
                    </div>
                @else
                    <div class="form-header">
                        <h2>Đăng Ký Thành Viên<span class="brand-dot">.</span></h2>
                        <p class="text-muted">Gia nhập cộng đồng tinh hoa của Shelf Luxury.</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-custom p-3">
                            @foreach ($errors->all() as $error)
                                <div class="d-flex align-items-center mb-1">
                                    <i class="fas fa-exclamation-circle me-2"></i> {{ $error }}
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Tên đăng nhập</label>
                                    <input type="text" name="username" class="form-control" placeholder="username" value="{{ old('username') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Họ và tên</label>
                                    <input type="text" name="fullname" class="form-control" placeholder="Họ và tên của bạn" value="{{ old('fullname') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="example@email.com" value="{{ old('email') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="text" name="phone" class="form-control" placeholder="09xx xxx xxx" value="{{ old('phone') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Mật khẩu</label>
                                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Xác nhận mật khẩu</label>
                                    <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <button type="submit" class="btn btn-luxury">Đăng ký thành viên</button>
                        </div>
                    </form>

                    <div class="auth-footer">
                        <span>Đã là thành viên?</span>
                        <a href="{{ route('login') }}">Đăng nhập tại đây</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- GSAP Animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tl = gsap.timeline({ defaults: { ease: "power4.out" } });
            
            tl.from(".auth-side-image", { duration: 1.5, xPercent: -100 })
              .from(".image-overlay-content", { duration: 1, opacity: 0, x: -50 }, "-=0.5")
              .from(".auth-side-form", { duration: 1, opacity: 0, x: 50 }, "-=1")
              .from(".form-header h2, .form-header p", { duration: 0.8, y: 30, opacity: 0, stagger: 0.1 }, "-=0.3")
              .from(".row > div, .mt-5", { duration: 0.6, y: 20, opacity: 0, stagger: 0.05 }, "-=0.4");
        });
    </script>
</body>
</html>
