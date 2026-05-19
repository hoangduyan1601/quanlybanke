<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Fix chitietgiohang (which partially migrated)
        if (Schema::hasColumn('chitietgiohang', 'id_new')) {
            Schema::table('chitietgiohang', function (Blueprint $table) {
                $table->dropPrimary(['MaGH', 'MaSP']);
            });
            Schema::table('chitietgiohang', function (Blueprint $table) {
                $table->increments('id')->first();
                $table->dropColumn('id_new');
            });
            if (!Schema::hasColumn('chitietgiohang', 'MaVariant')) {
                Schema::table('chitietgiohang', function (Blueprint $table) {
                    $table->unsignedInteger('MaVariant')->nullable()->after('MaSP');
                });
            }
            Schema::table('chitietgiohang', function (Blueprint $table) {
                $table->foreign('MaVariant')->references('MaVariant')->on('sanpham_variants')->onDelete('set null');
            });
        }

        // Fix chitietdonhang
        if (!Schema::hasColumn('chitietdonhang', 'MaVariant')) {
            Schema::table('chitietdonhang', function (Blueprint $table) {
                $table->unsignedInteger('id_new')->nullable()->first();
                $table->unsignedInteger('MaVariant')->nullable()->after('MaSP');
            });

            DB::statement('SET @count = 0;');
            DB::statement('UPDATE chitietdonhang SET id_new = (@count := @count + 1);');

            Schema::table('chitietdonhang', function (Blueprint $table) {
                $table->dropPrimary(['MaDH', 'MaSP']);
            });

            Schema::table('chitietdonhang', function (Blueprint $table) {
                $table->increments('id')->first();
                $table->dropColumn('id_new');
                $table->foreign('MaVariant')->references('MaVariant')->on('sanpham_variants')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::table('chitietgiohang', function (Blueprint $table) {
            $table->dropForeign(['MaVariant']);
            $table->dropColumn(['id', 'MaVariant']);
            $table->primary(['MaGH', 'MaSP']);
        });

        Schema::table('chitietdonhang', function (Blueprint $table) {
            $table->dropForeign(['MaVariant']);
            $table->dropColumn(['id', 'MaVariant']);
            $table->primary(['MaDH', 'MaSP']);
        });
    }
};
