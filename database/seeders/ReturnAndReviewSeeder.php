<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DonHang;
use App\Models\DonTraHang;
use App\Models\DanhGia;
use App\Models\SanPham;
use App\Models\KhachHang;

class ReturnAndReviewSeeder extends Seeder
{
    public function run()
    {
        $donHangs = DonHang::where('TrangThaiDH', 'DaGiao')->take(10)->get();
        $sanPhams = SanPham::all();
        $khachHangs = KhachHang::all();

        if ($donHangs->isEmpty() || $sanPhams->isEmpty() || $khachHangs->isEmpty()) {
            return;
        }

        // Tạo Đơn Trả Hàng
        foreach ($donHangs->take(5) as $donHang) {
            DonTraHang::create([
                'MaDH' => $donHang->MaDH,
                'LyDo' => 'Sản phẩm bị trầy xước trong quá trình vận chuyển.',
                'HinhAnhMinhChung' => 'assets/images/products/sp1.jpg', // Placeholder
                'SoTienHoan' => $donHang->TongThanhToan,
                'TrangThaiTra' => 'ChoDuyet',
            ]);
        }

        // Tạo Đánh Giá
        foreach ($donHangs as $donHang) {
            $sp = $sanPhams->random();
            DanhGia::create([
                'MaSP' => $sp->MaSP,
                'MaKH' => $donHang->MaKH,
                'SoSao' => rand(3, 5),
                'NoiDung' => 'Sản phẩm rất đẹp, chất lượng tuyệt vời! Giao hàng nhanh.',
                'HinhAnhDG' => 'assets/images/products/sp2.jpg', // Placeholder
            ]);
        }
    }
}
