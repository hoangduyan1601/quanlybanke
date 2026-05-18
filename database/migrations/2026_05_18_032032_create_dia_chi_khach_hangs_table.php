<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diachi_khachhang', function (Blueprint $table) {
            $table->increments('MaDC');
            $table->unsignedInteger('MaKH');
            $table->string('HoTenNguoiNhan');
            $table->string('SDTNguoiNhan', 20);
            $table->string('DiaChiChiTiet');
            $table->string('PhuongXa', 100)->nullable();
            $table->string('QuanHuyen', 100)->nullable();
            $table->string('TinhThanh', 100)->nullable();
            $table->tinyInteger('MacDinh')->default(0);
            
            $table->foreign('MaKH')->references('MaKH')->on('khachhang')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diachi_khachhang');
    }
};
