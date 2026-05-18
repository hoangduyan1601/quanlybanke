<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('danhgia', function (Blueprint $table) {
            $table->increments('MaDG');
            $table->unsignedInteger('MaSP');
            $table->unsignedInteger('MaKH');
            $table->tinyInteger('SoSao');
            $table->text('NoiDung')->nullable();
            $table->string('HinhAnhDG')->nullable();
            $table->timestamps();

            $table->foreign('MaSP')->references('MaSP')->on('sanpham')->onDelete('cascade');
            $table->foreign('MaKH')->references('MaKH')->on('khachhang')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('danhgia');
    }
};
