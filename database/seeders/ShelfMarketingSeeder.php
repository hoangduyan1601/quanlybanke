<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ThuongHieu;
use App\Models\BaiViet;
use App\Models\SanPham;
use App\Models\KhachHang;
use App\Models\ThongBao;
use App\Models\YeuThich;
use App\Models\DiaChiKhachHang;
use App\Models\TaiKhoan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ShelfMarketingSeeder extends Seeder
{
    public function run()
    {
        echo "Seeding Shelf Marketing Data...\n";

        // 1. Brands (ThuongHieu)
        $brands = [
            ['Tenthuonghieu' => 'Hòa Phát', 'QuocTich' => 'Việt Nam', 'MoTa' => 'Tập đoàn nội thất hàng đầu Việt Nam.'],
            ['Tenthuonghieu' => 'Vinatech', 'QuocTich' => 'Việt Nam', 'MoTa' => 'Chuyên gia giải trí lưu trữ siêu thị.'],
            ['Tenthuonghieu' => 'Onetech', 'QuocTich' => 'Việt Nam', 'MoTa' => 'Sản xuất kệ kho hàng tiêu chuẩn ISO.'],
            ['Tenthuonghieu' => 'IKEA', 'QuocTich' => 'Thụy Điển', 'MoTa' => 'Nội thất lắp ráp thông minh.'],
        ];

        foreach ($brands as $b) {
            ThuongHieu::updateOrCreate(['Tenthuonghieu' => $b['Tenthuonghieu']], $b);
        }

        $allBrands = ThuongHieu::all();
        $products = SanPham::all();

        // 2. Link Products to Brands
        foreach ($products as $sp) {
            if ($sp->ThuongHieus()->count() == 0) {
                $sp->ThuongHieus()->attach($allBrands->random()->Mathuonghieu, ['VaiTro' => 'Nhà sản xuất chính']);
            }
        }

        // 3. Blog Articles (BaiViet)
        $articles = [
            [
                'TieuDe' => 'Cách sắp xếp kệ bếp gọn gàng cho diện tích nhỏ',
                'TomTat' => 'Bí quyết tối ưu không gian bếp bằng các loại kệ đa năng hiện đại.',
                'NoiDung' => '<p>Không gian bếp nhỏ hẹp thường khiến các bà nội trợ đau đầu. Việc sử dụng kệ chén bát thông minh giúp bạn tiết kiệm đến 50% diện tích mặt bếp...</p>',
                'HinhAnh' => 'assets/images/articles/kitchen_shelf.jpg'
            ],
            [
                'TieuDe' => 'Xu hướng thiết kế kệ tivi phòng khách 2026',
                'TomTat' => 'Cập nhật những mẫu kệ tivi treo tường và kệ tivi gỗ tự nhiên đang làm mưa làm gió.',
                'NoiDung' => '<p>Năm 2026, phong cách tối giản (Minimalism) tiếp tục thống trị. Các mẫu kệ tivi với đường nét thanh mảnh, màu sắc trung tính được ưa chuộng...</p>',
                'HinhAnh' => 'assets/images/articles/tv_shelf_trend.jpg'
            ],
            [
                'TieuDe' => 'Lợi ích của việc sử dụng kệ sắt V lỗ trong kho hàng',
                'TomTat' => 'Tại sao kệ sắt V lỗ là lựa chọn số 1 cho các kho hàng vừa và nhỏ?',
                'NoiDung' => '<p>Kệ sắt V lỗ không chỉ rẻ mà còn vô cùng bền bỉ. Khả năng tháo lắp linh hoạt giúp bạn dễ dàng điều chỉnh khoảng cách các tầng...</p>',
                'HinhAnh' => 'assets/images/articles/warehouse_shelf.jpg'
            ]
        ];

        $adminTK = TaiKhoan::where('VaiTro', 'admin')->first();
        foreach ($articles as $art) {
            BaiViet::updateOrCreate(
                ['Slug' => Str::slug($art['TieuDe'])],
                array_merge($art, [
                    'MaTK' => $adminTK ? $adminTK->MaTK : 1,
                    'NgayDang' => now(),
                    'TrangThai' => 1
                ])
            );
        }

        // 4. Notifications (ThongBao)
        $khachhangs = KhachHang::all();
        foreach ($khachhangs as $kh) {
            ThongBao::create([
                'MaKH' => $kh->MaKH,
                'TieuDe' => 'Chào mừng bạn đến với Shelf Luxury',
                'NoiDung' => 'Cảm ơn bạn đã đăng ký tài khoản. Khám phá ngay các mẫu kệ mới nhất của chúng tôi.',
                'NgayGui' => now(),
                'TrangThaiDoc' => 0
            ]);
        }

        // 5. Favorites (YeuThich)
        foreach ($khachhangs as $kh) {
            $favProducts = $products->random(rand(1, 3));
            foreach ($favProducts as $fp) {
                DB::table('yeuthich')->insertOrIgnore([
                    'MaKH' => $kh->MaKH,
                    'MaSP' => $fp->MaSP,
                    'NgayThem' => now()
                ]);
            }
        }

        // 6. Address (DiaChiKhachHang) - if missing
        foreach ($khachhangs as $kh) {
            if ($kh->MaTK) {
                DiaChiKhachHang::updateOrCreate(
                    ['MaKH' => $kh->MaKH, 'MacDinh' => 1],
                    [
                        'HoTenNguoiNhan' => $kh->HoTen,
                        'SDTNguoiNhan' => $kh->SDT,
                        'DiaChiChiTiet' => $kh->DiaChi,
                        'PhuongXa' => 'Phường 1',
                        'QuanHuyen' => 'Quận 1',
                        'TinhThanh' => 'TP. Hồ Chí Minh'
                    ]
                );
            }
        }

        echo "Shelf Marketing Data seeded successfully!\n";
    }
}
