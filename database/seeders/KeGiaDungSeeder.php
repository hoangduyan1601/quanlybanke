<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SanPham;
use App\Models\DanhMuc;
use App\Models\NhaSanXuat;
use App\Models\ChiTietSanPham;
use App\Models\HinhAnhSanPham;
use App\Models\SanPhamVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KeGiaDungSeeder extends Seeder
{
    public function run(): void
    {
        // Xóa dữ liệu cũ liên quan đến sản phẩm
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        SanPhamVariant::truncate();
        ChiTietSanPham::truncate();
        HinhAnhSanPham::truncate();
        SanPham::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $products = [
            // Kệ Siêu Thị
            [
                'TenSP' => 'Kệ Siêu Thị Đơn Lưng Lưới',
                'DonGia' => 650000,
                'TenDM' => 'Kệ Siêu Thị',
                'TenNXB' => 'Vinatech Group',
                'MoTa' => 'Kệ đơn áp tường, lưng lưới thoáng đãng, phù hợp cửa hàng tạp hóa.',
                'specs' => [
                    'ChatLieu' => 'Sắt sơn tĩnh điện',
                    'TaiTrong' => '50kg/tầng',
                    'SoTang' => '5 Tầng',
                    'MauSac' => 'Trắng',
                    'KichThuoc' => '90 x 150 cm',
                    'NoiDungChiTiet' => 'Kệ siêu thị đơn vinatech được sản xuất trên dây chuyền công nghệ hiện đại, độ bền cao.'
                ]
            ],
            [
                'TenSP' => 'Kệ Siêu Thị Đôi Giữa Nhà',
                'DonGia' => 1250000,
                'TenDM' => 'Kệ Siêu Thị',
                'TenNXB' => 'Vinatech Group',
                'MoTa' => 'Kệ đôi trưng bày giữa nhà, tối ưu không gian diện tích.',
                'specs' => [
                    'ChatLieu' => 'Thép cán nguội',
                    'TaiTrong' => '60kg/tầng',
                    'SoTang' => '4 Tầng (2 mặt)',
                    'MauSac' => 'Xám trắng',
                    'KichThuoc' => '120 x 180 cm',
                    'NoiDungChiTiet' => 'Sản phẩm chịu lực tốt, thiết kế hiện đại, dễ dàng lắp đặt và di chuyển.'
                ]
            ],
            // Kệ Kho Hàng
            [
                'TenSP' => 'Kệ Sắt V Lỗ Đa Năng 4 Tầng',
                'DonGia' => 480000,
                'TenDM' => 'Kệ Kho Hàng',
                'TenNXB' => 'Kệ Sắt Thăng Long',
                'MoTa' => 'Kệ sắt V lỗ đa năng, phù hợp để hồ sơ, hàng hóa nhẹ.',
                'specs' => [
                    'ChatLieu' => 'Sắt V lỗ sơn tĩnh điện',
                    'TaiTrong' => '80kg/tầng',
                    'SoTang' => '4 Tầng',
                    'MauSac' => 'Ghi xám',
                    'KichThuoc' => '100 x 40 x 150 cm',
                    'NoiDungChiTiet' => 'Kệ tháo lắp linh hoạt, thay đổi khoảng cách giữa các tầng dễ dàng.'
                ]
            ],
            [
                'TenSP' => 'Kệ Kho Trung Tải 3 Tầng',
                'DonGia' => 2850000,
                'TenDM' => 'Kệ Kho Hàng',
                'TenNXB' => 'Cơ khí Việt',
                'MoTa' => 'Kệ trung tải chuyên dụng cho kho hàng công nghiệp.',
                'specs' => [
                    'ChatLieu' => 'Thép Omega sơn tĩnh điện',
                    'TaiTrong' => '300kg/tầng',
                    'SoTang' => '3 Tầng',
                    'MauSac' => 'Xanh - Cam',
                    'KichThuoc' => '200 x 60 x 200 cm',
                    'NoiDungChiTiet' => 'Kệ chịu lực cực tốt, dùng cho kho hàng điện tử, phụ tùng ô tô.'
                ]
            ],
            // Kệ Gia Dụng
            [
                'TenSP' => 'Kệ Chén Bát Thông Minh 2 Tầng',
                'DonGia' => 890000,
                'TenDM' => 'Kệ Gia Dụng',
                'TenNXB' => 'Cơ khí Hòa Phát',
                'MoTa' => 'Kệ để bát đĩa trên bồn rửa, tiết kiệm không gian nhà bếp.',
                'specs' => [
                    'ChatLieu' => 'Inox 304 không gỉ',
                    'TaiTrong' => '20kg',
                    'SoTang' => '2 Tầng',
                    'MauSac' => 'Đen carbon',
                    'KichThuoc' => '85 x 32 x 52 cm',
                    'NoiDungChiTiet' => 'Thiết kế thông minh, có khay hứng nước, giá để dao thớt kèm theo.'
                ]
            ],
            [
                'TenSP' => 'Kệ Tivi Gỗ Sồi Hiện Đại',
                'DonGia' => 3500000,
                'TenDM' => 'Kệ Gia Dụng',
                'TenNXB' => 'Cơ khí Hòa Phát',
                'MoTa' => 'Kệ tivi phòng khách sang trọng, phong cách Scandinavian.',
                'specs' => [
                    'ChatLieu' => 'Gỗ sồi tự nhiên',
                    'TaiTrong' => '100kg',
                    'SoTang' => '1 Tầng 2 ngăn kéo',
                    'MauSac' => 'Vàng nhạt',
                    'KichThuoc' => '160 x 40 x 45 cm',
                    'NoiDungChiTiet' => 'Gỗ đã qua xử lý chống mối mọt, cong vênh, bề mặt phủ bóng chuyên nghiệp.'
                ]
            ],
            // Kệ Văn Phòng
            [
                'TenSP' => 'Kệ Sách Gỗ MDF 5 Tầng',
                'DonGia' => 950000,
                'TenDM' => 'Kệ Văn Phòng',
                'TenNXB' => 'Cơ khí Hòa Phát',
                'MoTa' => 'Kệ sách đứng trang trí văn phòng hoặc phòng làm việc.',
                'specs' => [
                    'ChatLieu' => 'Gỗ MDF phủ Melamine',
                    'TaiTrong' => '15kg/tầng',
                    'SoTang' => '5 Tầng',
                    'MauSac' => 'Vân gỗ nâu',
                    'KichThuoc' => '60 x 24 x 180 cm',
                    'NoiDungChiTiet' => 'Thiết kế tối giản, hiện đại, dễ dàng phối hợp với các không gian nội thất.'
                ]
            ],
            [
                'TenSP' => 'Kệ Hồ Sơ Di Động',
                'DonGia' => 1500000,
                'TenDM' => 'Kệ Văn Phòng',
                'TenNXB' => 'Kệ Sắt Thăng Long',
                'MoTa' => 'Kệ sắt có bánh xe, thuận tiện di chuyển trong văn phòng.',
                'specs' => [
                    'ChatLieu' => 'Thép sơn tĩnh điện',
                    'TaiTrong' => '40kg/tầng',
                    'SoTang' => '3 Tầng',
                    'MauSac' => 'Trắng sứ',
                    'KichThuoc' => '80 x 35 x 110 cm',
                    'NoiDungChiTiet' => 'Bánh xe có khóa chốt, khung kệ chắc chắn, bền đẹp theo thời gian.'
                ]
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
                'SoLuong' => rand(50, 200),
                'HinhAnh' => 'assets/images/placeholder_ke.jpg',
                'NgayCapNhat' => now(),
                'TrangThai' => 1,
                'SoLuongDaBan' => rand(10, 500)
            ]);

            // Chi tiết sản phẩm
            ChiTietSanPham::create(array_merge($p['specs'], ['MaSP' => $sanPham->MaSP]));

            // Hình ảnh chính
            HinhAnhSanPham::create([
                'MaSP' => $sanPham->MaSP,
                'DuongDan' => 'assets/images/placeholder_ke.jpg',
                'LaAnhChinh' => 1
            ]);

            // Variants (Màu sắc)
            $colors = ['Trắng', 'Đen', 'Xám', 'Vân Gỗ'];
            foreach (array_slice($colors, 0, rand(2, 4)) as $color) {
                SanPhamVariant::create([
                    'MaSP' => $sanPham->MaSP,
                    'SKU' => strtoupper(Str::slug($p['TenSP'])) . '-' . strtoupper(Str::random(3)),
                    'MauSac' => $color,
                    'GiaNiemYet' => $p['DonGia'],
                    'GiaKhuyenMai' => $p['DonGia'] * 0.9,
                    'SoLuongTon' => rand(10, 50),
                ]);
            }
        }
    }
}
