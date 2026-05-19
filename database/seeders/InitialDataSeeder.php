<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DanhMuc;
use App\Models\NhaSanXuat;
use App\Models\NhaCungCap;
use App\Models\TaiKhoan;
use App\Models\KhachHang;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Danh mục Kệ
        $danhmucs = [
            ['TenDM' => 'Kệ Siêu Thị', 'MoTa' => 'Kệ trưng bày hàng hóa trong siêu thị, cửa hàng tiện lợi.'],
            ['TenDM' => 'Kệ Kho Hàng', 'MoTa' => 'Kệ tải trọng nặng dùng trong kho xưởng, logistics.'],
            ['TenDM' => 'Kệ Gia Dụng', 'MoTa' => 'Kệ nhà bếp, kệ phòng khách, kệ đa năng dùng trong gia đình.'],
            ['TenDM' => 'Kệ Văn Phòng', 'MoTa' => 'Kệ hồ sơ, kệ sách, kệ trang trí văn phòng.'],
            ['TenDM' => 'Phụ Kiện Kệ', 'MoTa' => 'Móc treo, thanh ngang, rào chắn và các phụ kiện đi kèm.'],
        ];
        foreach ($danhmucs as $dm) {
            DanhMuc::updateOrCreate(['TenDM' => $dm['TenDM']], $dm);
        }

        // 2. Nhà sản xuất Kệ
        $nxbs = [
            ['TenNXB' => 'Cơ khí Việt', 'DiaChi' => 'Lô MG1, Đường số 1, KCN Đức Hòa 1, Long An'],
            ['TenNXB' => 'Kệ Sắt Thăng Long', 'DiaChi' => 'Số 18 Dương Đình Nghệ, Cầu Giấy, Hà Nội'],
            ['TenNXB' => 'Vinatech Group', 'DiaChi' => 'Tầng 8 Tòa nhà Đại Phát, Ngõ 82 Duy Tân, Hà Nội'],
            ['TenNXB' => 'Cơ khí Hòa Phát', 'DiaChi' => 'KCN Như Quỳnh, Văn Lâm, Hưng Yên'],
        ];
        foreach ($nxbs as $nxb) {
            NhaSanXuat::updateOrCreate(['TenNXB' => $nxb['TenNXB']], $nxb);
        }

        // 3. Nhà cung cấp vật tư
        $nccs = [
            ['TenNCC' => 'Tập đoàn Hòa Phát', 'SDT' => '02436282011', 'Email' => 'thep@hoaphat.com.vn'],
            ['TenNCC' => 'Thép Việt Nhật', 'SDT' => '02439425666', 'Email' => 'sales@vinakyoei.com.vn'],
            ['TenNCC' => 'Sơn Tĩnh Điện AkzoNobel', 'SDT' => '02838230560', 'Email' => 'interpon.vietnam@akzonobel.com'],
        ];
        foreach ($nccs as $ncc) {
            NhaCungCap::updateOrCreate(['TenNCC' => $ncc['TenNCC']], $ncc);
        }

        // 4. Tài khoản Admin & Khách hàng mẫu
        $adminTK = TaiKhoan::updateOrCreate(
            ['TenDangNhap' => 'admin'],
            [
                'MatKhau' => 'admin123', // Tắt mã hóa
                'VaiTro' => 'admin',
                'TrangThai' => 1
            ]
        );

        $userTK = TaiKhoan::updateOrCreate(
            ['TenDangNhap' => 'user'],
            [
                'MatKhau' => 'user123', // Tắt mã hóa
                'VaiTro' => 'user',
                'TrangThai' => 1
            ]
        );

        // 5. Khách hàng
        KhachHang::updateOrCreate(
            ['Email' => 'user@example.com'],
            [
                'HoTen' => 'Nguyễn Văn Người Dùng',
                'SDT' => '0987654321',
                'DiaChi' => '123 Đường Láng, Đống Đa, Hà Nội',
                'MaTK' => $userTK->MaTK
            ]
        );

        KhachHang::updateOrCreate(
            ['Email' => 'khachhang1@gmail.com'],
            [
                'HoTen' => 'Trần Thị Khách',
                'SDT' => '0912345678',
                'DiaChi' => '456 Lê Lợi, Quận 1, TP.HCM',
                'MaTK' => null
            ]
        );
    }
}
