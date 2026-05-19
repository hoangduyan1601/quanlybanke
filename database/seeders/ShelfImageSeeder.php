<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SanPham;
use App\Models\HinhAnhSanPham;
use Illuminate\Support\Facades\DB;

class ShelfImageSeeder extends Seeder
{
    public function run()
    {
        $products = SanPham::all();

        // Danh sách ảnh thực tế từ Unsplash cho từng loại kệ
        $supermarketImages = [
            'https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=1000&auto=format&fit=crop', // Kệ siêu thị nhiều tầng
            'https://images.unsplash.com/photo-1534452203293-494d7ddbf7e0?q=80&w=1000&auto=format&fit=crop', // Kệ trưng bày
            'https://images.unsplash.com/photo-1604719312566-8912e9227c6a?q=80&w=1000&auto=format&fit=crop', // Kệ hàng thực phẩm
            'https://images.unsplash.com/photo-1583258292688-d0213dc5a3a8?q=80&w=1000&auto=format&fit=crop'  // Góc siêu thị
        ];

        $warehouseImages = [
            'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=1000&auto=format&fit=crop', // Kệ kho nặng
            'https://images.unsplash.com/photo-1587293852726-70cdb56c2866?q=80&w=1000&auto=format&fit=crop', // Logistics shelf
            'https://images.unsplash.com/photo-1565891741441-64926e441838?q=80&w=1000&auto=format&fit=crop', // Kho hàng công nghiệp
            'https://images.unsplash.com/photo-1504148455328-c376907d081c?q=80&w=1000&auto=format&fit=crop'  // Kệ chứa pallet
        ];

        $homeImages = [
            'https://images.unsplash.com/photo-1594488630399-bf8351a1ee4d?q=80&w=1000&auto=format&fit=crop', // Kệ gỗ gia đình
            'https://images.unsplash.com/photo-1591190282059-0026e6378411?q=80&w=1000&auto=format&fit=crop', // Kệ trang trí phòng khách
            'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?q=80&w=1000&auto=format&fit=crop', // Kệ bếp
            'https://images.unsplash.com/photo-1517705008128-361805f42e86?q=80&w=1000&auto=format&fit=crop'  // Kệ đa năng
        ];

        $officeImages = [
            'https://images.unsplash.com/photo-1505409628601-edc9af17fda6?q=80&w=1000&auto=format&fit=crop', // Kệ sách văn phòng
            'https://images.unsplash.com/photo-1506439773649-6e0eb8cfb237?q=80&w=1000&auto=format&fit=crop', // Tủ kệ hồ sơ
            'https://images.unsplash.com/photo-1544413647-ad6717a2a49a?q=80&w=1000&auto=format&fit=crop', // Kệ trang trí công sở
            'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=1000&auto=format&fit=crop'  // Không gian làm việc
        ];

        $accessoryImages = [
            'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?q=80&w=1000&auto=format&fit=crop', // Bánh xe/linh kiện
            'https://images.unsplash.com/photo-1585338107529-13afc5f02586?q=80&w=1000&auto=format&fit=crop', // Móc treo/phụ kiện sắt
            'https://images.unsplash.com/photo-1513519245088-0e12902e35ca?q=80&w=1000&auto=format&fit=crop', // Giá treo nhỏ
            'https://images.unsplash.com/photo-1610419330103-68d6d6783857?q=80&w=1000&auto=format&fit=crop'  // Phụ kiện cơ khí
        ];

        foreach ($products as $sp) {
            $categoryName = $sp->danhmuc->TenDM ?? '';
            
            // Chọn bộ sưu tập ảnh dựa trên danh mục
            if (stripos($categoryName, 'Siêu Thị') !== false) {
                $imagePool = $supermarketImages;
            } elseif (stripos($categoryName, 'Kho Hàng') !== false) {
                $imagePool = $warehouseImages;
            } elseif (stripos($categoryName, 'Văn Phòng') !== false) {
                $imagePool = $officeImages;
            } elseif (stripos($categoryName, 'Phụ Kiện') !== false) {
                $imagePool = $accessoryImages;
            } else {
                $imagePool = $homeImages;
            }

            // Shuffle ảnh để tránh trùng lặp cho các sản phẩm cùng loại
            shuffle($imagePool);
            
            $mainImage = $imagePool[0];

            // 1. Cập nhật ảnh chính trong bảng sanpham
            $sp->update(['HinhAnh' => $mainImage]);

            // 2. Cập nhật bảng hinhanhsanpham
            HinhAnhSanPham::where('MaSP', $sp->MaSP)->delete();
            
            // Thêm ảnh chính
            HinhAnhSanPham::create([
                'MaSP' => $sp->MaSP,
                'DuongDan' => $mainImage,
                'LaAnhChinh' => 1
            ]);

            // Thêm tối đa 3 ảnh phụ từ bộ sưu tập
            $count = 0;
            foreach ($imagePool as $img) {
                if ($img === $mainImage) continue;
                if ($count >= 3) break;

                HinhAnhSanPham::create([
                    'MaSP' => $sp->MaSP,
                    'DuongDan' => $img,
                    'LaAnhChinh' => 0
                ]);
                $count++;
            }
        }
    }
}
