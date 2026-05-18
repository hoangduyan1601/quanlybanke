<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KeGiaDungSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Xóa dữ liệu cũ để làm sạch
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('sanpham_variants')->truncate();
        DB::table('chi_tiet_san_pham')->truncate();
        DB::table('sanpham_thuonghieu')->truncate();
        DB::table('hinhanhsanpham')->truncate();
        DB::table('sanpham')->truncate();
        DB::table('danhmuc')->truncate();
        DB::table('thuonghieu')->truncate();
        DB::table('nhasanxuat')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Tạo Danh mục kệ
        $danhmucs = [
            ['TenDM' => 'Kệ Nhà Bếp', 'MoTa' => 'Các loại kệ để bát đĩa, hũ gia vị, lò vi sóng'],
            ['TenDM' => 'Kệ Phòng Khách', 'MoTa' => 'Kệ tivi, kệ trang trí, kệ sách nghệ thuật'],
            ['TenDM' => 'Kệ Đa Năng', 'MoTa' => 'Kệ sắt v lỗ, kệ nhựa lắp ghép thông minh'],
            ['TenDM' => 'Kệ Nhà Tắm', 'MoTa' => 'Kệ góc, kệ treo tường chống gỉ'],
        ];
        foreach ($danhmucs as $dm) {
            DB::table('danhmuc')->insert($dm);
        }

        // 3. Tạo Thương hiệu & Nhà sản xuất
        $thuonghieus = ['Duy Tân', 'Hòa Phát', 'IKEA', 'Index Living', 'Song Long'];
        foreach ($thuonghieus as $th) {
            DB::table('thuonghieu')->insert([
                'Tenthuonghieu' => $th,
                'QuocTich' => 'Việt Nam',
                'MoTa' => 'Thương hiệu gia dụng uy tín'
            ]);
            DB::table('nhasanxuat')->insert([
                'TenNXB' => 'Công ty ' . $th,
                'DiaChi' => 'Hà Nội, Việt Nam'
            ]);
        }

        // 4. Tạo Sản phẩm mẫu
        $products = [
            [
                'TenSP' => 'Kệ Chén Bát Thông Minh Trên Bồn Rửa',
                'DonGia' => 850000,
                'MaDM' => 1,
                'MaNXB' => 1,
                'MoTa' => 'Chất liệu thép carbon sơn tĩnh điện cao cấp, không gỉ sét.',
                'Slug' => 'ke-chen-bat-thong-minh'
            ],
            [
                'TenSP' => 'Kệ Tivi Gỗ Hiện Đại',
                'DonGia' => 2500000,
                'MaDM' => 2,
                'MaNXB' => 2,
                'MoTa' => 'Phong cách tối giản, phù hợp phòng khách hiện đại.',
                'Slug' => 'ke-tivi-go-hien-dai'
            ],
            [
                'TenSP' => 'Kệ Sắt Đa Năng 5 Tầng V Lỗ',
                'DonGia' => 450000,
                'MaDM' => 3,
                'MaNXB' => 2,
                'MoTa' => 'Chịu tải lên đến 100kg mỗi tầng.',
                'Slug' => 'ke-sat-da-nang-5-tang'
            ]
        ];

        foreach ($products as $p) {
            $id = DB::table('sanpham')->insertGetId(array_merge($p, [
                'SoLuong' => 100,
                'HinhAnh' => 'assets/images/placeholder_ke.jpg',
                'NgayCapNhat' => now(),
                'TrangThai' => 1
            ]));

            // Chi tiết kỹ thuật
            DB::table('chi_tiet_san_pham')->insert([
                'MaSP' => $id,
                'ChatLieu' => 'Thép sơn tĩnh điện / Gỗ MDF',
                'TaiTrong' => '50-100kg',
                'SoTang' => '3-5 Tầng',
                'MauSac' => 'Đen / Trắng / Vân gỗ',
                'NoiDungChiTiet' => 'Sản phẩm dễ dàng lắp đặt, bảo hành 12 tháng.'
            ]);

            // Biến thể (Variants)
            DB::table('sanpham_variants')->insert([
                [
                    'MaSP' => $id,
                    'SKU' => strtoupper($p['Slug']) . '-D',
                    'MauSac' => 'Đen',
                    'GiaNiemYet' => $p['DonGia'],
                    'GiaKhuyenMai' => $p['DonGia'] * 0.9,
                    'SoLuongTon' => 50,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'MaSP' => $id,
                    'SKU' => strtoupper($p['Slug']) . '-T',
                    'MauSac' => 'Trắng',
                    'GiaNiemYet' => $p['DonGia'],
                    'GiaKhuyenMai' => $p['DonGia'] * 0.9,
                    'SoLuongTon' => 50,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }
}
