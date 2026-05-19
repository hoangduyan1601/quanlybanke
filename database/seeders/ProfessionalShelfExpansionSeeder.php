<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SanPham;
use App\Models\DanhMuc;
use App\Models\NhaSanXuat;
use App\Models\ChiTietSanPham;
use App\Models\HinhAnhSanPham;
use App\Models\SanPhamVariant;
use Illuminate\Support\Str;

class ProfessionalShelfExpansionSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Kệ Siêu Thị
            [
                'TenSP' => 'Kệ Siêu Thị Lưng Tôn Liền Đơn',
                'DonGia' => 850000,
                'TenDM' => 'Kệ Siêu Thị',
                'TenNXB' => 'Vinatech Group',
                'MoTa' => 'Kệ đơn lưng tôn liền cao cấp, chắc chắn, thẩm mỹ cao.',
                'specs' => ['ChatLieu' => 'Sắt sơn tĩnh điện', 'TaiTrong' => '70kg/tầng', 'SoTang' => '5 Tầng', 'MauSac' => 'Trắng', 'KichThuoc' => '90 x 180 cm']
            ],
            [
                'TenSP' => 'Kệ Siêu Thị Lưng Tôn Đục Lỗ Đôi',
                'DonGia' => 1550000,
                'TenDM' => 'Kệ Siêu Thị',
                'TenNXB' => 'Vinatech Group',
                'MoTa' => 'Kệ đôi giữa nhà lưng tôn đục lỗ, đa năng trong việc treo móc hàng.',
                'specs' => ['ChatLieu' => 'Thép cán nguội', 'TaiTrong' => '65kg/tầng', 'SoTang' => '4 Tầng (2 mặt)', 'MauSac' => 'Đen', 'KichThuoc' => '120 x 150 cm']
            ],
            // Kệ Kho Hàng
            [
                'TenSP' => 'Kệ Kho Nặng Heavy Duty',
                'DonGia' => 5500000,
                'TenDM' => 'Kệ Kho Hàng',
                'TenNXB' => 'Cơ khí Việt',
                'MoTa' => 'Kệ tải trọng nặng cho pallet, giải pháp tối ưu cho kho lớn.',
                'specs' => ['ChatLieu' => 'Thép chịu lực cao', 'TaiTrong' => '1500kg/tầng', 'SoTang' => '3 Tầng', 'MauSac' => 'Xanh - Cam', 'KichThuoc' => '270 x 110 x 300 cm']
            ],
            [
                'TenSP' => 'Kệ Sắt V Lỗ 5 Tầng Màu Đen',
                'DonGia' => 580000,
                'TenDM' => 'Kệ Kho Hàng',
                'TenNXB' => 'Kệ Sắt Thăng Long',
                'MoTa' => 'Kệ sắt V lỗ đa năng màu đen carbon sang trọng, bền bỉ.',
                'specs' => ['ChatLieu' => 'Sắt V lỗ', 'TaiTrong' => '100kg/tầng', 'SoTang' => '5 Tầng', 'MauSac' => 'Đen nhám', 'KichThuoc' => '100 x 40 x 200 cm']
            ],
            // Kệ Gia Dụng
            [
                'TenSP' => 'Kệ Giày Gỗ 5 Tầng Hiện Đại',
                'DonGia' => 450000,
                'TenDM' => 'Kệ Gia Dụng',
                'TenNXB' => 'Cơ khí Hòa Phát',
                'MoTa' => 'Kệ đựng giày dép bằng gỗ công nghiệp, thiết kế nhỏ gọn.',
                'specs' => ['ChatLieu' => 'Gỗ MDF', 'TaiTrong' => '30kg', 'SoTang' => '5 Tầng', 'MauSac' => 'Vân gỗ sáng', 'KichThuoc' => '60 x 30 x 85 cm']
            ],
            [
                'TenSP' => 'Kệ Để Lò Vi Sóng 3 Tầng Khung Sắt',
                'DonGia' => 750000,
                'TenDM' => 'Kệ Gia Dụng',
                'TenNXB' => 'Cơ khí Hòa Phát',
                'MoTa' => 'Kệ bếp đa năng, khung sắt chắc chắn, mặt gỗ chống ẩm.',
                'specs' => ['ChatLieu' => 'Khung sắt - Mặt gỗ', 'TaiTrong' => '50kg', 'SoTang' => '3 Tầng', 'MauSac' => 'Đen - Nâu', 'KichThuoc' => '60 x 40 x 120 cm']
            ],
            [
                'TenSP' => 'Kệ Treo Quần Áo Chữ A',
                'DonGia' => 320000,
                'TenDM' => 'Kệ Gia Dụng',
                'TenNXB' => 'Cơ khí Hòa Phát',
                'MoTa' => 'Giá treo quần áo phong cách tối giản, dễ dàng lắp ráp.',
                'specs' => ['ChatLieu' => 'Gỗ thông tự nhiên', 'TaiTrong' => '20kg', 'SoTang' => '1 Thanh treo 1 kệ dưới', 'MauSac' => 'Gỗ tự nhiên', 'KichThuoc' => '80 x 45 x 150 cm']
            ],
            // Kệ Văn Phòng
            [
                'TenSP' => 'Kệ Hồ Sơ Thép 4 Tầng',
                'DonGia' => 1100000,
                'TenDM' => 'Kệ Văn Phòng',
                'TenNXB' => 'Kệ Sắt Thăng Long',
                'MoTa' => 'Kệ đựng hồ sơ tài liệu chuyên dụng cho văn phòng công ty.',
                'specs' => ['ChatLieu' => 'Thép sơn tĩnh điện', 'TaiTrong' => '50kg/tầng', 'SoTang' => '4 Tầng', 'MauSac' => 'Ghi xám', 'KichThuoc' => '100 x 45 x 180 cm']
            ],
            [
                'TenSP' => 'Kệ Trang Trí Khung Lục Giác',
                'DonGia' => 650000,
                'TenDM' => 'Kệ Văn Phòng',
                'TenNXB' => 'Cơ khí Hòa Phát',
                'MoTa' => 'Kệ treo tường trang trí hình lục giác, tạo điểm nhấn cho văn phòng.',
                'specs' => ['ChatLieu' => 'Gỗ công nghiệp', 'TaiTrong' => '10kg', 'SoTang' => 'Combo 3 khung', 'MauSac' => 'Trắng - Đen', 'KichThuoc' => '30 x 30 cm']
            ],
            // Phụ Kiện Kệ
            [
                'TenSP' => 'Móc Treo Hàng Siêu Thị 20cm',
                'DonGia' => 5000,
                'TenDM' => 'Phụ Kiện Kệ',
                'TenNXB' => 'Vinatech Group',
                'MoTa' => 'Móc treo đơn cài lưng lưới, dùng trưng bày phụ kiện, đồ lặt vặt.',
                'specs' => ['ChatLieu' => 'Inox', 'TaiTrong' => '2kg', 'SoTang' => '1', 'MauSac' => 'Bạc', 'KichThuoc' => 'Dài 20 cm']
            ],
            [
                'TenSP' => 'Rào Chắn Kệ Siêu Thị',
                'DonGia' => 25000,
                'TenDM' => 'Phụ Kiện Kệ',
                'TenNXB' => 'Vinatech Group',
                'MoTa' => 'Rào chắn mặt trước kệ, ngăn hàng hóa rơi đổ.',
                'specs' => ['ChatLieu' => 'Sắt sơn tĩnh điện', 'TaiTrong' => 'N/A', 'SoTang' => '1', 'MauSac' => 'Trắng', 'KichThuoc' => 'Dài 90 cm']
            ],
            [
                'TenSP' => 'Bánh Xe Kệ Đa Năng (Bộ 4 cái)',
                'DonGia' => 180000,
                'TenDM' => 'Phụ Kiện Kệ',
                'TenNXB' => 'Kệ Sắt Thăng Long',
                'MoTa' => 'Bộ bánh xe chịu lực, có khóa, dùng cho kệ sắt V lỗ.',
                'specs' => ['ChatLieu' => 'Cao su - Thép', 'TaiTrong' => '200kg/bộ', 'SoTang' => 'N/A', 'MauSac' => 'Đen', 'KichThuoc' => 'Đường kính 75mm']
            ],
        ];

        foreach ($products as $p) {
            $dm = DanhMuc::where('TenDM', $p['TenDM'])->first();
            $nxb = NhaSanXuat::where('TenNXB', $p['TenNXB'])->first();

            $sanPham = SanPham::create([
                'TenSP' => $p['TenSP'],
                'DonGia' => $p['DonGia'],
                'MaDM' => $dm ? $dm->MaDM : 1,
                'MaNXB' => $nxb ? $nxb->MaNXB : 1,
                'MoTa' => $p['MoTa'],
                'SoLuong' => rand(50, 500),
                'HinhAnh' => 'assets/images/placeholder_ke.jpg',
                'NgayCapNhat' => now(),
                'TrangThai' => 1,
                'SoLuongDaBan' => rand(5, 1000)
            ]);

            ChiTietSanPham::create(array_merge($p['specs'], [
                'MaSP' => $sanPham->MaSP,
                'NoiDungChiTiet' => 'Mô tả chi tiết đang được cập nhật bởi hệ thống chuyên nghiệp.'
            ]));

            HinhAnhSanPham::create([
                'MaSP' => $sanPham->MaSP,
                'DuongDan' => 'assets/images/placeholder_ke.jpg',
                'LaAnhChinh' => 1
            ]);

            // Variants
            SanPhamVariant::create([
                'MaSP' => $sanPham->MaSP,
                'SKU' => strtoupper(Str::slug($p['TenSP'])) . '-' . strtoupper(Str::random(3)),
                'MauSac' => $p['specs']['MauSac'] ?? 'Tiêu chuẩn',
                'GiaNiemYet' => $p['DonGia'],
                'GiaKhuyenMai' => $p['DonGia'] * 0.95,
                'SoLuongTon' => rand(20, 100),
            ]);
        }
    }
}
