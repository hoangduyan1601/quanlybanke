<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sanpham', function (Blueprint $table) {
            if (!Schema::hasColumn('sanpham', 'Slug')) {
                $table->string('Slug')->nullable()->after('TenSP');
            }
            if (!Schema::hasColumn('sanpham', 'MoTaNgan')) {
                $table->text('MoTaNgan')->nullable()->after('Slug');
            }
            if (!Schema::hasColumn('sanpham', 'TrangThai')) {
                $table->tinyInteger('TrangThai')->default(1)->after('SoLuongDaBan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sanpham', function (Blueprint $table) {
            $table->dropColumn(['Slug', 'MoTaNgan', 'TrangThai']);
        });
    }
};
