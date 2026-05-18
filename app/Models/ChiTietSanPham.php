<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietSanPham extends Model
{
    protected $table = 'chi_tiet_san_pham';
    protected $primaryKey = 'MaSP';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'MaSP',
        'ChatLieu',
        'KichThuoc',
        'TaiTrong',
        'SoTang',
        'MauSac',
        'NoiDungChiTiet',
    ];

    public function sanpham()
    {
        return $this->belongsTo(SanPham::class, 'MaSP', 'MaSP');
    }
}

