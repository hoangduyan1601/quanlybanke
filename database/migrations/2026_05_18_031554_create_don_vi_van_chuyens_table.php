<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('don_vi_van_chuyens', function (Blueprint $table) {
            $table->increments('MaDVVC');
            $table->string('TenDVVC');
            $table->string('SDT', 20)->nullable();
            $table->tinyInteger('TrangThai')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('don_vi_van_chuyens');
    }
};
