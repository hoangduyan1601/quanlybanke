# TÀI LIỆU CẤU TRÚC CHI TIẾT DATABASE - WEBSITE BÁN KỆ GIA DỤNG (BẢN DOANH NGHIỆP)

Tài liệu này cung cấp chi tiết toàn bộ các cột, kiểu dữ liệu và ý nghĩa của **28 bảng** trong hệ thống.

---

## NHÓM 1: QUẢN TRỊ NGƯỜI DÙNG & PHÂN QUYỀN

### 1. Bảng `users` (Tài khoản chuẩn Laravel)
| Tên cột | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- |
| id | bigint unsigned | Khóa chính, tự tăng |
| name | varchar(255) | Tên hiển thị người dùng |
| email | varchar(255) | Email đăng nhập (duy nhất) |
| email_verified_at | timestamp | Thời gian xác thực email |
| password | varchar(255) | Mật khẩu mã hóa BCrypt |
| role | varchar(50) | Vai trò: admin, staff, customer |
| remember_token | varchar(100) | Token duy trì đăng nhập |
| created_at / updated_at | timestamp | Thời gian tạo và cập nhật |

### 2. Bảng `taikhoan` (Tài khoản hệ thống bổ trợ)
| Tên cột | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- |
| MaTK | int unsigned | Khóa chính, tự tăng |
| TenDangNhap | varchar(255) | Tên đăng nhập |
| MatKhau | varchar(255) | Mật khẩu |
| VaiTro | varchar(255) | Vai trò hệ thống |
| TrangThai | int | 1: Hoạt động, 0: Khóa |

### 3. Bảng `khachhang` (Thông tin khách hàng)
| Tên cột | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- |
| MaKH | int unsigned | Khóa chính, tự tăng |
| HoTen | varchar(255) | Họ và tên khách hàng |
| Email | varchar(255) | Email liên lạc |
| SDT | varchar(255) | Số điện thoại |
| DiaChi | varchar(255) | Địa chỉ chính |
| NgayDangKy | datetime | Ngày tham gia hệ thống |
| MaTK | int unsigned | Khóa ngoại (taikhoan) |

### 4. Bảng `diachi_khachhang` (Sổ địa chỉ nhận hàng)
| Tên cột | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- |
| MaDC | int unsigned | Khóa chính, tự tăng |
| MaKH | int unsigned | Khóa ngoại (khachhang) |
| HoTenNguoiNhan | varchar(255) | Tên người nhận hàng |
| SDTNguoiNhan | varchar(20) | Số điện thoại nhận hàng |
| DiaChiChiTiet | varchar(255) | Số nhà, tên đường |
| PhuongXa | varchar(100) | Phường/Xã |
| QuanHuyen | varchar(100) | Quận/Huyện |
| TinhThanh | varchar(100) | Tỉnh/Thành phố |
| MacDinh | tinyint | 1: Là địa chỉ mặc định |

---

## NHÓM 2: QUẢN LÝ SẢN PHẨM & KHO (PRODUCT & INVENTORY)

### 5. Bảng `danhmuc` (Danh mục loại kệ)
| Tên cột | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- |
| MaDM | int unsigned | Khóa chính, tự tăng |
| TenDM | varchar(255) | Tên danh mục (Kệ bếp, Kệ tivi...) |
| MoTa | text | Mô tả loại kệ |

### 6. Bảng `thuonghieu` (Thương hiệu/Hãng)
| Tên cột | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- |
| Mathuonghieu | int unsigned | Khóa chính, tự tăng |
| Tenthuonghieu | varchar(255) | Tên hãng sản xuất |
| QuocTich | varchar(255) | Quốc gia xuất xứ |
| MoTa | text | Thông tin thương hiệu |
| AnhDaiDien | varchar(255) | Logo thương hiệu |

### 7. Bảng `sanpham` (Sản phẩm chính - Parent)
| Tên cột | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- |
| MaSP | int unsigned | Khóa chính, tự tăng |
| TenSP | varchar(255) | Tên kệ gia dụng |
| DonGia | decimal(15,2) | Giá bán cơ bản |
| SoLuong | int | Tổng tồn kho tất cả biến thể |
| MoTa | text | Mô tả tổng quan |
| HinhAnh | varchar(255) | Ảnh đại diện sản phẩm |
| MaDM | int unsigned | Khóa ngoại (danhmuc) |
| MaNXB | int unsigned | Khóa ngoại (nhasanxuat) |
| NgayCapNhat | datetime | Lần sửa cuối |
| SoLuongDaBan | int | Tổng số lượng đã bán |
| Slug | varchar(255) | Đường dẫn chuẩn SEO |
| MoTaNgan | text | Mô tả ngắn gọn |
| TrangThai | tinyint | 1: Kinh doanh, 0: Ngừng bán |

### 8. Bảng `sanpham_variants` (Biến thể chi tiết - SKU)
| Tên cột | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- |
| MaVariant | int unsigned | Khóa chính, tự tăng |
| MaSP | int unsigned | Khóa ngoại (sanpham) |
| SKU | varchar(50) | Mã quản lý kho (unique) |
| MauSac | varchar(100) | Màu sắc biến thể |
| KichThuoc | varchar(100) | Kích thước biến thể |
| SoTang | int | Số tầng của kệ |
| GiaNhap | decimal(15,2) | Giá vốn (để tính lợi nhuận) |
| GiaNiemYet | decimal(15,2) | Giá bán gốc chưa giảm |
| GiaKhuyenMai | decimal(15,2) | Giá bán thực tế |
| SoLuongTon | int | Số lượng tồn kho cho biến thể này |
| HinhAnh | varchar(255) | Ảnh riêng cho biến thể |

### 9. Bảng `chi_tiet_san_pham` (Thông số kỹ thuật)
| Tên cột | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- |
| MaSP | int unsigned | Khóa ngoại chính (sanpham) |
| ChatLieu | varchar(255) | Gỗ, Nhựa PP, Thép sơn tĩnh điện... |
| KichThuoc | varchar(255) | Kích thước phủ bì |
| TaiTrong | varchar(255) | Sức chịu tải tối đa |
| SoTang | varchar(255) | Số tầng chi tiết |
| MauSac | varchar(255) | Màu sắc phối hợp |
| NoiDungChiTiet | longtext | Bài viết giới thiệu chi tiết |

### 10. Bảng `sanpham_thuonghieu` (Bảng trung gian)
| Tên cột | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- |
| MaSP | int unsigned | Khóa ngoại |
| Mathuonghieu | int unsigned | Khóa ngoại |
| VaiTro | varchar(255) | Ví dụ: "Nhà cung cấp chính" |

### 11. Bảng `hinhanhsanpham` (Thư viện ảnh)
| Tên cột | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- |
| MaHinh | int unsigned | Khóa chính |
| MaSP | int unsigned | Khóa ngoại |
| DuongDan | varchar(255) | Đường dẫn file ảnh |
| LaAnhChinh | tinyint | 1: Ảnh đại diện |

---

## NHÓM 3: BÁN HÀNG & VẬN HÀNH (ORDERS & LOGISTICS)

### 12. Bảng `donhang` (Quản lý đơn hàng)
| Tên cột | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- |
| MaDH | int unsigned | Khóa chính, tự tăng |
| NgayDat | datetime | Thời điểm đặt hàng |
| TongTienHang | decimal(15,2) | Tổng tiền sản phẩm |
| PhiShip | decimal(15,2) | Phí vận chuyển |
| SoTienGiam | decimal(15,2) | Số tiền được giảm giá |
| TongThanhToan | decimal(15,2) | Số tiền cuối cùng phải trả |
| TrangThaiDH | enum | ChoXacNhan, DangXuLy, HoanThanh, DaHuy |
| TrangThaiThanhToan | enum | ChuaThanhToan, DaThanhToan, DaHoanTien |
| TrangThaiVanChuyen | enum | ChuaGiao, DangGiao, DaGiao, TraHang |
| MaDVVC | int unsigned | Khóa ngoại (don_vi_van_chuyens) |
| MaVanDon | varchar(100) | Mã tra cứu vận chuyển |
| PTThanhToan | varchar(255) | COD, VNPAY, Bank Transfer... |
| MaKH | int unsigned | Khóa ngoại (khachhang) |
| DiaChiGiao | text | Địa chỉ giao hàng cuối cùng |
| GhiChu | text | Ghi chú từ khách hàng |
| SoTienDaThanhToan | decimal(15,2) | Số tiền thực nhận |

### 13. Bảng `chitietdonhang` (Sản phẩm trong đơn)
| Tên cột | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- |
| MaDH | int unsigned | Khóa ngoại |
| MaSP | int unsigned | Khóa ngoại |
| SoLuong | int | Số lượng mua |
| DonGia | decimal(15,2) | Giá bán tại thời điểm mua |
| ThanhTien | decimal(15,2) | SoLuong * DonGia |

### 14. Bảng `donhang_status_log` (Nhật ký đơn hàng)
| Tên cột | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- |
| id | int unsigned | Khóa chính |
| MaDH | int unsigned | Khóa ngoại |
| UserID | bigint unsigned | Người thực hiện (admin/nhân viên) |
| HanhDong | varchar(255) | Mô tả hành động (Xác nhận, Hủy...) |
| GhiChu | text | Lý do (Ví dụ: Hủy do hết hàng) |
| created_at | timestamp | Thời gian thực hiện |

### 15. Bảng `don_vi_van_chuyens` (Đối tác giao hàng)
| Tên cột | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- |
| MaDVVC | int unsigned | Khóa chính |
| TenDVVC | varchar(255) | Ví dụ: GHTK, GHN, Viettel Post |
| SDT | varchar(20) | Số điện thoại liên hệ |
| TrangThai | tinyint | 1: Đang hợp tác, 0: Ngừng |

### 16. Bảng `don_tra_hangs` (Hậu mãi/Đổi trả)
| Tên cột | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- |
| MaTraHang | int unsigned | Khóa chính |
| MaDH | int unsigned | Khóa ngoại |
| LyDo | text | Lý do trả hàng |
| HinhAnhMinhChung | varchar(255) | Ảnh bằng chứng hàng lỗi |
| SoTienHoan | decimal(15,2) | Số tiền trả lại khách |
| TrangThaiTra | enum | ChoDuyet, DaNhanHangTra, DaHoanTien, TuChoi |

### 17. Bảng `giohang` & 18. Bảng `chitietgiohang`
- Lưu trữ giỏ hàng hiện tại của khách hàng đăng nhập.
- `MaGH`, `MaKH`, `NgayTao`
- `MaGH`, `MaSP`, `SoLuong`, `DonGiaTamTinh`

---

## NHÓM 4: CUNG ỨNG & NHẬP HÀNG (SUPPLY CHAIN)

### 19. Bảng `nhacungcap` (Nhà cung cấp vật tư/kệ)
- `MaNCC`, `TenNCC`, `SDT`, `DiaChi`, `Email`

### 20. Bảng `nhasanxuat` (Nhà sản xuất/Hãng)
- `MaNXB` (Khóa chính), `TenNXB`, `DiaChi`, `SDT`, `Email`

### 21. Bảng `lichsunhaphang` & 22. Bảng `chitietnhaphang`
- Theo dõi các đợt nhập kệ về kho, đơn giá nhập để tính toán giá vốn và lợi nhuận.

---

## NHÓM 5: MARKETING & TƯƠNG TÁC (INTERACTION)

### 23. Bảng `khuyenmai` (Chương trình ưu đãi)
| Tên cột | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- |
| MaKM | int unsigned | Khóa chính |
| TenKM | varchar(255) | |
| PhanTramGiam | decimal(5,2) | |
| NgayBatDau / NgayKetThuc | datetime | |
| DieuKienToiThieu | decimal(15,2) | Giá trị đơn tối thiểu |
| MaGiamGia | varchar(255) | Mã Code nhập lúc thanh toán |

### 24. Bảng `danhgia` (Đánh giá & Review)
| Tên cột | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- |
| MaDG | int unsigned | Khóa chính |
| MaSP | int unsigned | |
| MaKH | int unsigned | |
| SoSao | tinyint | 1 đến 5 sao |
| NoiDung | text | Nhận xét của khách |
| HinhAnhDG | varchar(255) | Ảnh thực tế khách chụp |

### 25. Bảng `baiviet` (Tin tức/Blog)
| Tên cột | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- |
| MaBV | int unsigned | Khóa chính |
| TieuDe | varchar(255) | |
| Slug | varchar(255) | URL SEO (duy nhất) |
| TomTat | text | |
| NoiDung | longtext | Nội dung bài viết |
| MaTK | int unsigned | Người viết bài |

### 26. Bảng `yeuthich` (Sản phẩm yêu thích)
- `MaKH`, `MaSP`, `NgayThem`

### 27. Bảng `thongbao` (Thông báo người dùng)
- Gửi thông báo về đơn hàng, khuyến mãi mới cho khách hàng.

### 28. Bảng `chat_messages` (Chatbot & Hỗ trợ)
| Tên cột | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- |
| id | bigint | Khóa chính |
| MaKH | int | Khách hàng đăng nhập |
| session_id | varchar(255) | Khách vãng lai |
| message | text | Nội dung chat |
| sender | enum | user, ai, admin |
| is_read | tinyint | |
