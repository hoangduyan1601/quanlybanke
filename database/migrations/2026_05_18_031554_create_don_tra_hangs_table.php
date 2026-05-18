<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('don_tra_hangs', function (Blueprint $table) {
            $table->increments('MaTraHang');
            $table->unsignedInteger('MaDH');
            $table->text('LyDo')->nullable();
            $table->string('HinhAnhMinhChung')->nullable();
            $table->decimal('SoTienHoan', 15, 2)->default(0);
            $table->enum('TrangThaiTra', ['ChoDuyet', 'DaNhanHangTra', 'DaHoanTien', 'TuChoi'])->default('ChoDuyet');
            $table->timestamps();

            $table->foreign('MaDH')->references('MaDH')->on('donhang')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('don_tra_hangs');
    }
};
