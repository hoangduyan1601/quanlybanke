<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sanpham_variants', function (Blueprint $table) {
            $table->increments('MaVariant');
            $table->unsignedInteger('MaSP');
            $table->string('SKU', 50)->unique();
            $table->string('MauSac', 100)->nullable();
            $table->string('KichThuoc', 100)->nullable();
            $table->integer('SoTang')->nullable();
            $table->decimal('GiaNhap', 15, 2)->default(0);
            $table->decimal('GiaNiemYet', 15, 2)->default(0);
            $table->decimal('GiaKhuyenMai', 15, 2)->nullable();
            $table->integer('SoLuongTon')->default(0);
            $table->string('HinhAnh')->nullable();
            $table->timestamps();

            $table->foreign('MaSP')->references('MaSP')->on('sanpham')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sanpham_variants');
    }
};
