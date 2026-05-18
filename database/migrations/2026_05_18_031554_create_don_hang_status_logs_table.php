<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donhang_status_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('MaDH');
            $table->unsignedBigInteger('UserID')->nullable();
            $table->string('HanhDong');
            $table->text('GhiChu')->nullable();
            $table->timestamps();

            $table->foreign('MaDH')->references('MaDH')->on('donhang')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donhang_status_logs');
    }
};
