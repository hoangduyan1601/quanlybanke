<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donhang', function (Blueprint $table) {
            // Tách biệt các loại doanh thu và phí
            if (!Schema::hasColumn('donhang', 'TongTienHang')) {
                $table->decimal('TongTienHang', 15, 2)->default(0)->after('NgayDat');
            }
            if (!Schema::hasColumn('donhang', 'PhiShip')) {
                $table->decimal('PhiShip', 15, 2)->default(0)->after('TongTienHang');
            }
            if (!Schema::hasColumn('donhang', 'TongThanhToan')) {
                $table->decimal('TongThanhToan', 15, 2)->default(0)->after('SoTienGiam');
            }

            // Hệ thống 3 lớp trạng thái
            if (!Schema::hasColumn('donhang', 'TrangThaiDH')) {
                $table->string('TrangThaiDH')->default('ChoXacNhan')->after('TongThanhToan');
            }
            if (!Schema::hasColumn('donhang', 'TrangThaiThanhToan')) {
                $table->string('TrangThaiThanhToan')->default('ChuaThanhToan')->after('TrangThaiDH');
            }
            if (!Schema::hasColumn('donhang', 'TrangThaiVanChuyen')) {
                $table->string('TrangThaiVanChuyen')->default('ChuaGiao')->after('TrangThaiThanhToan');
            }

            // Thông tin vận chuyển
            if (!Schema::hasColumn('donhang', 'MaDVVC')) {
                $table->unsignedInteger('MaDVVC')->nullable()->after('TrangThaiVanChuyen');
                $table->foreign('MaDVVC')->references('MaDVVC')->on('don_vi_van_chuyens')->onDelete('set null');
            }
            if (!Schema::hasColumn('donhang', 'MaVanDon')) {
                $table->string('MaVanDon', 100)->nullable()->after('MaDVVC');
            }
            if (!Schema::hasColumn('donhang', 'DiaChiGiao')) {
                $table->text('DiaChiGiao')->nullable()->after('MaVanDon');
            }
            if (!Schema::hasColumn('donhang', 'GhiChu')) {
                $table->text('GhiChu')->nullable()->after('DiaChiGiao');
            }
        });
    }

    public function down(): void
    {
        Schema::table('donhang', function (Blueprint $table) {
            $table->dropForeign(['MaDVVC']);
            $table->dropColumn([
                'TongTienHang', 'PhiShip', 'TongThanhToan', 
                'TrangThaiDH', 'TrangThaiThanhToan', 'TrangThaiVanChuyen',
                'MaDVVC', 'MaVanDon', 'DiaChiGiao', 'GhiChu'
            ]);
        });
    }
};
