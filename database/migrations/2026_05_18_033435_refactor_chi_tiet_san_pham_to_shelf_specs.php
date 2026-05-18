<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chi_tiet_san_pham', function (Blueprint $table) {
            // Đổi tên các cột từ sách sang kệ
            if (Schema::hasColumn('chi_tiet_san_pham', 'SoTrang')) {
                $table->renameColumn('SoTrang', 'ChatLieu');
            }
            if (Schema::hasColumn('chi_tiet_san_pham', 'LoaiBia')) {
                $table->renameColumn('LoaiBia', 'TaiTrong');
            }
            if (Schema::hasColumn('chi_tiet_san_pham', 'TrongLuong')) {
                $table->renameColumn('TrongLuong', 'SoTang');
            }
            if (Schema::hasColumn('chi_tiet_san_pham', 'NamXuatBan')) {
                $table->renameColumn('NamXuatBan', 'MauSac');
            }
        });

        // Đảm bảo kiểu dữ liệu phù hợp (string thay vì int)
        Schema::table('chi_tiet_san_pham', function (Blueprint $table) {
            $table->string('ChatLieu')->nullable()->change();
            $table->string('TaiTrong')->nullable()->change();
            $table->string('SoTang')->nullable()->change();
            $table->string('MauSac')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('chi_tiet_san_pham', function (Blueprint $table) {
            $table->renameColumn('ChatLieu', 'SoTrang');
            $table->renameColumn('TaiTrong', 'LoaiBia');
            $table->renameColumn('SoTang', 'TrongLuong');
            $table->renameColumn('MauSac', 'NamXuatBan');
        });
    }
};
