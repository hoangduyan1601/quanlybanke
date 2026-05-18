<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SanPham;
use App\Models\ThuongHieu;
use Illuminate\Support\Facades\DB;

class AssignAuthorsSeeder extends Seeder
{
    public function run(): void
    {
        $products = SanPham::all();
        $authors = ThuongHieu::all();

        if ($authors->isEmpty()) {
            // Nếu chưa có tác giả nào, tạo một vài tác giả mẫu
            $authors = collect([
                ThuongHieu::create(['TenThuongHieu' => 'Haruki Murakami', 'QuocTich' => 'Nhật Bản']),
                ThuongHieu::create(['TenThuongHieu' => 'Dale Carnegie', 'QuocTich' => 'Mỹ']),
                ThuongHieu::create(['TenThuongHieu' => 'Nguyễn Nhật Ánh', 'QuocTich' => 'Việt Nam']),
                ThuongHieu::create(['TenThuongHieu' => 'Paulo Coelho', 'QuocTich' => 'Brazil']),
                ThuongHieu::create(['TenThuongHieu' => 'Higashino Keigo', 'QuocTich' => 'Nhật Bản']),
            ]);
        }

        foreach ($products as $product) {
            // Chỉ gán nếu sản phẩm chưa có tác giả
            if ($product->ThuongHieus()->count() === 0) {
                // Gán ngẫu nhiên 1 tác giả
                $randomAuthors = $authors->random(rand(1, min(2, $authors->count())));
                foreach ($randomAuthors as $author) {
                    $product->ThuongHieus()->attach($author->MaThuongHieu, ['VaiTro' => 'Tác giả chính']);
                }
            }
        }
    }
}
