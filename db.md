# THIẾT KẾ CƠ SỞ DỮ LIỆU WEBSITE QUẢN LÝ BÁN KỆ GIA DỤNG (BẢN DOANH NGHIỆP)

## I. KIẾN TRÚC HỆ THỐNG
Hệ thống được thiết kế theo chuẩn TMĐT hiện đại, hỗ trợ:
- **Product Variants (SKU)**: Quản lý đa biến thể (Màu sắc, kích thước, số tầng).
- **Multi-Status Tracking**: Tách biệt trạng thái Đơn hàng, Thanh toán và Vận chuyển.
- **Inventory Management**: Quản lý kho chi tiết theo biến thể.
- **Reverse Logistics**: Quy trình đổi trả hàng.

---

## II. DANH SÁCH CÁC BẢNG

### Nhóm 1: Sản phẩm & Kho
1.  **danhmuc**: Danh mục loại kệ.
2.  **thuonghieu**: Thương hiệu.
3.  **sanpham**: Thông tin chung (Sản phẩm cha).
4.  **sanpham_variants**: Biến thể chi tiết (SKU - Sản phẩm con).
5.  **chi_tiet_san_pham**: Thông số kỹ thuật chung.
6.  **hinhanhsanpham**: Thư viện ảnh.
7.  **nhacungcap**: Nhà cung cấp.
8.  **lichsunhaphang** & **chitietnhaphang**: Quản lý nhập kho.

### Nhóm 2: Người dùng & Khách hàng
9.  **users**: Tài khoản (phân quyền RBAC).
10. **khachhang**: Thông tin khách hàng.
11. **diachi_khachhang**: Sổ địa chỉ.
12. **yeuthich**: Danh sách yêu thích.

### Nhóm 3: Bán hàng & Vận hành
13. **donhang**: Quản lý đơn hàng tổng thể.
14. **chitietdonhang**: Sản phẩm trong đơn (liên kết với Variant).
15. **donhang_status_log**: Nhật ký thay đổi trạng thái đơn hàng.
16. **don_vi_van_chuyen**: Đối tác giao hàng.
17. **don_tra_hang**: Quản lý đổi trả/hoàn tiền.
18. **giohang** & **chitietgiohang**: Giỏ hàng.

### Nhóm 4: Marketing & Tương tác
19. **khuyenmai**: Mã giảm giá, chương trình ưu đãi.
20. **danhgia**: Đánh giá thực tế từ khách hàng.
21. **thongbao**: Hệ thống thông báo đa kênh.
22. **baiviet**: Blog, hướng dẫn lắp đặt.
23. **chat_messages**: Chăm sóc khách hàng.

---

## III. CHI TIẾT CÁC BẢNG QUAN TRỌNG (NÂNG CẤP)

### 1. Bảng sanpham (Sản phẩm cha)
| Tên cột | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- |
| MaSP | int unsigned | Khóa chính |
| TenSP | varchar(255) | Tên sản phẩm chính |
| Slug | varchar(255) | SEO URL |
| MaDM | int unsigned | Khóa ngoại |
| MaTH | int unsigned | Khóa ngoại |
| MoTaNgan | text | |
| MoTaChiTiet | longtext | |
| TrangThai | tinyint | 1: Đang bán, 0: Ngừng bán |

### 2. Bảng sanpham_variants (Biến thể - SKU)
*Đây là nơi quản lý giá và kho thực tế.*
| Tên cột | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- |
| MaVariant | int unsigned | Khóa chính |
| MaSP | int unsigned | Khóa ngoại (sanpham) |
| SKU | varchar(50) | Mã định danh duy nhất (Ví dụ: KE-BEP-DEN-5T) |
| MauSac | varchar(100) | |
| KichThuoc | varchar(100) | |
| SoTang | int | |
| GiaNhap | decimal(15,2) | Dùng để tính lợi nhuận |
| GiaNiemYet | decimal(15,2) | Giá bán gốc |
| GiaKhuyenMai | decimal(15,2) | Giá sau giảm |
| SoLuongTon | int | Số lượng thực tế trong kho |
| HinhAnh | varchar(255) | Ảnh riêng cho biến thể màu sắc này |

### 3. Bảng donhang (Quản lý đa trạng thái)
| Tên cột | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- |
| MaDH | int unsigned | Khóa chính |
| MaKH | int unsigned | Khóa ngoại |
| NgayDat | datetime | |
| TongTienHang | decimal(15,2) | |
| PhiShip | decimal(15,2) | |
| SoTienGiam | decimal(15,2) | |
| TongThanhToan | decimal(15,2) | |
| TrangThaiDH | enum | ChoXacNhan, DangXuLy, HoanThanh, DaHuy |
| TrangThaiThanhToan | enum | ChuaThanhToan, DaThanhToan, DaHoanTien |
| TrangThaiVanChuyen | enum | ChuaGiao, DangGiao, DaGiao, TraHang |
| MaDVVC | int unsigned | Khóa ngoại (don_vi_van_chuyen) |
| MaVanDon | varchar(100) | Tra cứu trên web GHTK/GHN |
| PTThanhToan | varchar(50) | COD, VNPAY, BankTransfer |

### 4. Bảng donhang_status_log (Audit Trail)
*Giúp doanh nghiệp kiểm soát nhân viên nào đã sửa đơn hàng.*
| Tên cột | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- |
| id | int unsigned | Khóa chính |
| MaDH | int unsigned | Khóa ngoại |
| UserID | bigint unsigned | Người thực hiện thay đổi |
| HanhDong | varchar(255) | Ví dụ: "Xác nhận đơn", "Đổi trạng thái sang Đang giao" |
| GhiChu | text | Lý do thay đổi (đặc biệt khi Hủy đơn) |
| Created_at | timestamp | |

### 5. Bảng don_tra_hang (Hậu mãi)
| Tên cột | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- |
| MaTraHang | int unsigned | Khóa chính |
| MaDH | int unsigned | Khóa ngoại |
| LyDo | text | |
| HinhAnhMinhChung | varchar(255) | Ảnh hàng lỗi/móp méo |
| SoTienHoan | decimal(15,2) | |
| TrangThaiTra | enum | ChoDuyet, DaNhanHangTra, DaHoanTien, TuChoi |
| Created_at | timestamp | |

### 6. Bảng don_vi_van_chuyen
| Tên cột | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- |
| MaDVVC | int unsigned | Khóa chính |
| TenDVVC | varchar(255) | Giao Hàng Tiết Kiệm, Viettel Post... |
| SDT | varchar(20) | |
| TrangThai | tinyint | 1: Đang hợp tác |

---

## IV. CÁC LƯU Ý KHI TRIỂN KHAI CHO DOANH NGHIỆP
1.  **Tính nhất quán dữ liệu**: Khi xóa một `sanpham`, phải kiểm tra các `sanpham_variants` và `chitietdonhang` để tránh lỗi dữ liệu mồ côi (Restricted Delete).
2.  **Hiệu năng**: Các cột như `Slug`, `MaVanDon`, `SKU` cần được đánh **Index** để tìm kiếm cực nhanh khi số lượng đơn hàng lên đến hàng chục nghìn.
3.  **Bảo mật**: Mật khẩu trong bảng `users` phải được Hash bằng thuật toán mạnh (BCrypt/Argon2). Lưu vết IP và UserAgent trong bảng `donhang_status_log` để chống gian lận.
4.  **Lợi nhuận**: Hệ thống có `GiaNhap` trong bảng `sanpham_variants` giúp doanh nghiệp tính toán chính xác lợi nhuận (Profit = Doanh thu - Giá vốn - PhiShip).
