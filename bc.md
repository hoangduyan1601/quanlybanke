# BÁO CÁO ĐỒ ÁN TỐT NGHIỆP: HỆ THỐNG THƯƠNG MẠI ĐIỆN TỬ LUXURY FURNITURE

## 1. Giới thiệu tổng quan
Hệ thống là một nền tảng thương mại điện tử hiện đại chuyên doanh về nội thất cao cấp, được xây dựng trên nền tảng **Laravel Framework (PHP)** kết hợp với giao diện người dùng tối ưu hóa trải nghiệm (UX/UI) theo phong cách sang trọng. Hệ thống tích hợp đầy đủ các quy trình từ quản lý sản phẩm, đơn hàng đến thanh toán tự động và hỗ trợ khách hàng thông qua trí tuệ nhân tạo (AI).

## 2. Các chức năng nổi bật (Highlights)

### 🚀 Hệ thống Thanh toán Tự động (Fintech Integration)
Đây là điểm nhấn công nghệ quan trọng nhất của đồ án:
- **Tích hợp Cổng thanh toán VNPay:** Cho phép khách hàng thanh toán qua thẻ ATM, Visa/MasterCard hoặc ví điện tử với quy trình bảo mật chuẩn quốc tế.
- **Thanh toán VietQR Tự động (Webhook):** Hệ thống tự động tạo mã QR kèm nội dung chuyển khoản định sẵn. Khi khách hàng chuyển tiền, hệ thống nhận tín hiệu (Webhook) và tự động xác nhận đơn hàng trong vài giây mà không cần sự can thiệp của con người.

### 🤖 Trợ lý ảo AI (Gemini AI Integration)
- Tích hợp **Google Gemini API** để xây dựng Chatbot thông minh.
- Hỗ trợ giải đáp thắc mắc về sản phẩm, tư vấn trang trí nội thất và hỗ trợ quy trình mua hàng 24/7.
- Khả năng ghi nhớ ngữ cảnh hội thoại giúp trải nghiệm khách hàng trở nên tự nhiên.

### 📊 Hệ thống Quản trị Doanh nghiệp (Admin Dashboard)
- Dashboard chuyên nghiệp thống kê doanh thu, đơn hàng và tăng trưởng theo thời gian thực.
- Quản lý kho hàng thông minh: Tự động giảm tồn kho khi có đơn hàng mới và cảnh báo khi hàng sắp hết.
- Quản lý biến thể sản phẩm (Variant): Hỗ trợ đa dạng thuộc tính như màu sắc, kích thước cho từng mã sản phẩm.

## 3. Các chức năng cần thiết khác

### Đối với Khách hàng (Frontend)
- **Tìm kiếm & Lọc nâng cao:** Tìm kiếm theo tên, danh mục, thương hiệu và gợi ý sản phẩm thông minh.
- **Giỏ hàng & Checkout:** Quy trình thanh toán tối ưu, hỗ trợ áp dụng mã giảm giá (Promotions) và quản lý sổ địa chỉ (Address Book).
- **Trang cá nhân (Profile):** Theo dõi trạng thái đơn hàng (Chờ thanh toán, Đang giao, Đã giao, Đã hủy), lịch sử đánh giá sản phẩm.
- **Yêu thích & Đánh giá:** Lưu lại các sản phẩm quan tâm và để lại phản hồi sau khi mua hàng.

### Đối với Quản trị viên (Backend/Admin)
- **Quản lý nội dung:** Bài viết tin tức, banner khuyến mãi.
- **Hệ thống Thông báo:** Tự động gửi thông báo hệ thống và Email (via SMTP) cho khách hàng khi trạng thái đơn hàng thay đổi.
- **Quản lý nhập hàng:** Theo dõi lịch sử nhập hàng từ các nhà cung cấp.

## 4. Công nghệ sử dụng (Tech Stack)
- **Backend:** Laravel 11.x, PHP 8.2+
- **Database:** MySQL
- **Frontend:** Blade Template, CSS3 (Custom Luxury Style), JavaScript/jQuery
- **API/Integration:** VNPay API, Google Gemini API, VietQR Webhook
- **Tiện ích:** SMTP Mail Server, Laravel Notification.

## 5. Kết luận
Đồ án không chỉ dừng lại ở một trang web bán hàng cơ bản mà đã tiến tới mô hình **Fintech & AI**, giải quyết bài toán tự động hóa vận hành cho doanh nghiệp, giảm thiểu sai sót con người và tăng tốc độ phục vụ khách hàng.
