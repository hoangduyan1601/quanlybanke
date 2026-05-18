<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SanPhamVariant extends Model
{
    protected $table = 'sanpham_variants';
    protected $primaryKey = 'MaVariant';
    public $timestamps = true;

    protected $fillable = [
        'MaSP',
        'SKU',
        'MauSac',
        'KichThuoc',
        'SoTang',
        'GiaNhap',
        'GiaNiemYet',
        'GiaKhuyenMai',
        'SoLuongTon',
        'HinhAnh',
    ];

    public function sanpham()
    {
        return $this->belongsTo(SanPham::class, 'MaSP', 'MaSP');
    }
}

