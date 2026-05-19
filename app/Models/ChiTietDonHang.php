<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietDonHang extends Model
{
    protected $table = 'chitietdonhang';
    public $timestamps = false;

    protected $fillable = [
        'MaDH',
        'MaSP',
        'MaVariant',
        'SoLuong',
        'DonGia',
        'ThanhTien',
    ];

    public function donHang()
    {
        return $this->belongsTo(DonHang::class, 'MaDH', 'MaDH');
    }

    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'MaSP', 'MaSP');
    }

    public function variant()
    {
        return $this->belongsTo(SanPhamVariant::class, 'MaVariant', 'MaVariant');
    }
}

