<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SanPham;
use App\Models\SanPhamVariant;
use App\Models\KhachHang;
use App\Models\DonHang;
use App\Models\ChiTietDonHang;
use App\Models\LichSuNhapHang;
use App\Models\ChiTietNhapHang;
use App\Models\NhaCungCap;
use App\Models\DonViVanChuyen;
use App\Models\DonHangStatusLog;
use App\Models\DonTraHang;
use App\Models\DanhGia;
use App\Models\KhuyenMai;
use App\Models\TaiKhoan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EnterpriseDataSeeder extends Seeder
{
    public function run()
    {
        echo "Starting Enterprise Data Seeder (3 Years Simulation)...\n";
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DonHangStatusLog::truncate();
        DonTraHang::truncate();
        DanhGia::truncate();
        ChiTietDonHang::truncate();
        DonHang::truncate();
        ChiTietNhapHang::truncate();
        LichSuNhapHang::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Ensure Base Setup
        $adminID = TaiKhoan::where('VaiTro', 'Admin')->first()?->MaTK ?? 1;

        if (DonViVanChuyen::count() < 3) {
            DonViVanChuyen::create(['TenDVVC' => 'Giao Hàng Nhanh (GHN)', 'SDT' => '19001234', 'TrangThai' => 1]);
            DonViVanChuyen::create(['TenDVVC' => 'Giao Hàng Tiết Kiệm (GHTK)', 'SDT' => '19005678', 'TrangThai' => 1]);
            DonViVanChuyen::create(['TenDVVC' => 'Viettel Post', 'SDT' => '19009999', 'TrangThai' => 1]);
        }

        if (KhuyenMai::count() == 0) {
            KhuyenMai::create([
                'TenKM' => 'Khai trương 2024',
                'PhanTramGiam' => 10,
                'NgayBatDau' => '2024-01-01 00:00:00',
                'NgayKetThuc' => '2024-01-31 23:59:59',
                'LoaiKM' => 'TatCa',
                'MaGiamGia' => 'HELLO2024'
            ]);
            KhuyenMai::create([
                'TenKM' => 'Black Friday 2025',
                'PhanTramGiam' => 20,
                'NgayBatDau' => '2025-11-20 00:00:00',
                'NgayKetThuc' => '2025-11-30 23:59:59',
                'LoaiKM' => 'TatCa',
                'MaGiamGia' => 'BF2025'
            ]);
        }

        $sanphams = SanPham::all();
        $khachhangs = KhachHang::all();
        $nccs = NhaCungCap::all();
        $dvvcs = DonViVanChuyen::all();
        $khuyenmais = KhuyenMai::all();

        if ($sanphams->isEmpty() || $khachhangs->isEmpty() || $nccs->isEmpty()) {
            echo "Missing basic data (Products, Customers, or Suppliers). Please run InitialDataSeeder first.\n";
            return;
        }

        // 2. Ensure every product has variants
        foreach ($sanphams as $sp) {
            if ($sp->variants()->count() == 0) {
                SanPhamVariant::create([
                    'MaSP' => $sp->MaSP,
                    'SKU' => 'SKU-' . $sp->MaSP . '-STD',
                    'MauSac' => 'Tiêu chuẩn',
                    'KichThuoc' => '70x30x120cm',
                    'SoTang' => 4,
                    'GiaNhap' => $sp->DonGia * 0.5,
                    'GiaNiemYet' => $sp->DonGia,
                    'SoLuongTon' => 50,
                ]);
            }
        }

        $startDate = Carbon::create(2024, 1, 1);
        $endDate = Carbon::now();
        $currentDate = $startDate->copy();

        $totalProfit = 0;
        $orderCount = 0;

        while ($currentDate->lte($endDate)) {
            // --- INVENTORY ARRIVAL (Every Monday) ---
            if ($currentDate->dayOfWeek == Carbon::MONDAY) {
                $nhap = LichSuNhapHang::create([
                    'NgayNhap' => $currentDate->copy()->addHours(rand(8, 10)),
                    'MaNCC' => $nccs->random()->MaNCC,
                    'TongTienNhap' => 0
                ]);

                $tongTienNhap = 0;
                $numToPick = rand(5, 12);
                $spToUpdate = $sanphams->random(min($numToPick, $sanphams->count()));
                foreach ($spToUpdate as $sp) {
                    $variant = $sp->variants->first();
                    $slNhap = rand(50, 150);
                    $giaNhap = $variant ? $variant->GiaNhap : $sp->DonGia * 0.5;

                    ChiTietNhapHang::create([
                        'MaNhap' => $nhap->MaNhap,
                        'MaSP' => $sp->MaSP,
                        'SoLuongNhap' => $slNhap,
                        'DonGiaNhap' => $giaNhap
                    ]);

                    $tongTienNhap += ($slNhap * $giaNhap);
                    $sp->increment('SoLuong', $slNhap);
                    if ($variant) $variant->increment('SoLuongTon', $slNhap);
                }
                $nhap->update(['TongTienNhap' => $tongTienNhap]);
            }

            // --- DAILY ORDERS (2 to 5 per day) ---
            $ordersPerDay = rand(2, 5);
            for ($i = 0; $i < $ordersPerDay; $i++) {
                $kh = $khachhangs->random();
                $dvvc = $dvvcs->random();
                
                // Status Logic
                $randStatus = rand(1, 100);
                $trangThaiDH = 'DaGiao';
                $trangThaiTT = 'DaThanhToan';
                $trangThaiVC = 'DaGiao';
                
                // Recent orders might still be processing
                if ($currentDate->diffInDays($endDate) < 5) {
                    if ($randStatus <= 20) {
                        $trangThaiDH = 'ChoXacNhan';
                        $trangThaiTT = 'ChuaThanhToan';
                        $trangThaiVC = 'ChuaGiao';
                    } elseif ($randStatus <= 40) {
                        $trangThaiDH = 'DangGiao';
                        $trangThaiTT = 'DaThanhToan';
                        $trangThaiVC = 'DangGiao';
                    }
                }
                
                // Cancelled orders (5% chance)
                if ($randStatus > 95) {
                    $trangThaiDH = 'DaHuy';
                    $trangThaiTT = 'ChuaThanhToan';
                    $trangThaiVC = 'ChuaGiao';
                }

                $phiShip = rand(30, 60) * 1000;
                $orderDate = $currentDate->copy()->addHours(rand(8, 22))->addMinutes(rand(0, 59));

                $donHang = DonHang::create([
                    'MaKH' => $kh->MaKH,
                    'NgayDat' => $orderDate,
                    'TongTien' => 0,
                    'TrangThai' => $trangThaiDH, // Legacy column
                    'TongTienHang' => 0,
                    'PhiShip' => $phiShip,
                    'SoTienGiam' => 0,
                    'TongThanhToan' => 0,
                    'TrangThaiDH' => $trangThaiDH,
                    'TrangThaiThanhToan' => $trangThaiTT,
                    'TrangThaiVanChuyen' => $trangThaiVC,
                    'MaDVVC' => $dvvc->MaDVVC,
                    'MaVanDon' => ($trangThaiDH != 'ChoXacNhan') ? 'SHELF' . strtoupper(Str::random(10)) : null,
                    'PhuongThucThanhToan' => rand(0, 1) ? 'TienMat' : 'ChuyenKhoan',
                    'DiaChiGiao' => $kh->DiaChi ?? 'Địa chỉ khách hàng',
                    'DiaChiGiaoHang' => $kh->DiaChi ?? 'Địa chỉ khách hàng', // Legacy column
                    'SoTienDaThanhToan' => 0
                ]);

                $tongTienHang = 0;
                $totalCost = 0;
                $spTrongDon = $sanphams->random(rand(1, 2));
                foreach ($spTrongDon as $sp) {
                    $slBan = rand(1, 2);
                    if ($sp->SoLuong >= $slBan) {
                        ChiTietDonHang::create([
                            'MaDH' => $donHang->MaDH,
                            'MaSP' => $sp->MaSP,
                            'SoLuong' => $slBan,
                            'DonGia' => $sp->DonGia,
                            'ThanhTien' => $slBan * $sp->DonGia
                        ]);
                        $tongTienHang += ($slBan * $sp->DonGia);
                        
                        $variant = $sp->variants->first();
                        $totalCost += ($slBan * ($variant ? $variant->GiaNhap : $sp->DonGia * 0.5));

                        if ($trangThaiDH == 'DaGiao' || $trangThaiDH == 'DangGiao') {
                            $sp->decrement('SoLuong', $slBan);
                            $sp->increment('SoLuongDaBan', $slBan);
                            if ($variant) $variant->decrement('SoLuongTon', $slBan);
                        }
                    }
                }

                if ($tongTienHang == 0) {
                    $donHang->delete();
                    continue;
                }

                // Apply discount if exists for this period
                $soTienGiam = 0;
                $kmActive = $khuyenmais->where('NgayBatDau', '<=', $orderDate)
                                      ->where('NgayKetThuc', '>=', $orderDate)
                                      ->first();
                if ($kmActive) {
                    $soTienGiam = $tongTienHang * ($kmActive->PhanTramGiam / 100);
                    $donHang->update(['MaKM' => $kmActive->MaKM]);
                }

                $tongThanhToan = $tongTienHang + $phiShip - $soTienGiam;
                $donHang->update([
                    'TongTien' => $tongThanhToan,
                    'TongTienHang' => $tongTienHang,
                    'SoTienGiam' => $soTienGiam,
                    'TongThanhToan' => $tongThanhToan,
                    'SoTienDaThanhToan' => ($trangThaiTT == 'DaThanhToan') ? $tongThanhToan : 0
                ]);

                // Order Logs
                DonHangStatusLog::create([
                    'MaDH' => $donHang->MaDH,
                    'UserID' => null, // Customer placed it
                    'HanhDong' => "Đặt hàng",
                    'GhiChu' => "Khách hàng đặt hàng thành công",
                    'created_at' => $orderDate
                ]);

                if ($trangThaiDH != 'ChoXacNhan') {
                    DonHangStatusLog::create([
                        'MaDH' => $donHang->MaDH,
                        'UserID' => $adminID,
                        'HanhDong' => "Xác nhận đơn hàng",
                        'GhiChu' => "Hệ thống tự động xác nhận",
                        'created_at' => $orderDate->copy()->addMinutes(rand(10, 60))
                    ]);
                }

                if ($trangThaiDH == 'DaGiao') {
                    DonHangStatusLog::create([
                        'MaDH' => $donHang->MaDH,
                        'UserID' => $adminID,
                        'HanhDong' => "Hoàn tất đơn hàng",
                        'GhiChu' => "Giao hàng thành công",
                        'created_at' => $orderDate->copy()->addDays(rand(2, 4))
                    ]);

                    // Profit calculation for simulation tracking
                    $profit = $tongThanhToan - $totalCost - $phiShip; // Assuming phiShip is cost to carrier
                    $totalProfit += $profit;

                    // Reviews (30% chance)
                    if (rand(1, 10) <= 3) {
                        DanhGia::create([
                            'MaSP' => $spTrongDon->first()->MaSP,
                            'MaKH' => $kh->MaKH,
                            'SoSao' => rand(4, 5),
                            'NoiDung' => 'Kệ rất chắc chắn, giao hàng đúng hẹn. Vote 5 sao!',
                            'created_at' => $orderDate->copy()->addDays(rand(4, 7))
                        ]);
                    }

                    // Returns (2% chance)
                    if (rand(1, 100) <= 2) {
                        DonTraHang::create([
                            'MaDH' => $donHang->MaDH,
                            'LyDo' => 'Sản phẩm có vết trầy xước nhẹ ở góc kệ',
                            'SoTienHoan' => $tongThanhToan,
                            'TrangThaiTra' => 'DaHoanTien',
                            'created_at' => $orderDate->copy()->addDays(rand(2, 4))
                        ]);
                        $donHang->update(['TrangThaiDH' => 'TraHang', 'TrangThaiThanhToan' => 'DaHoanTien']);
                        // Revert Sold count
                        foreach ($donHang->chiTietDonHangs as $ct) {
                            $ct->sanpham->decrement('SoLuongDaBan', $ct->SoLuong);
                        }
                    }
                }
                $orderCount++;
            }

            if ($currentDate->day == 1) {
                echo "Processed: " . $currentDate->format('M Y') . "\n";
            }

            $currentDate->addDay();
        }

        echo "\nFinal Results:\n";
        echo "Total Orders: " . $orderCount . "\n";
        echo "Total Simulated Profit: " . number_format($totalProfit) . " VND\n";
        echo "Enterprise data simulation completed!\n";
    }
}
