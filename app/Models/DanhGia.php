<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhGia extends Model
{
    protected $table = 'danhgia';
    protected $primaryKey = 'MaDG';

    protected $fillable = [
        'MaSP',
        'MaKH',
        'SoSao',
        'NoiDung',
        'HinhAnhDG'
    ];

    public function sanpham()
    {
        return $this->belongsTo(SanPham::class, 'MaSP', 'MaSP');
    }

    public function khachhang()
    {
        return $this->belongsTo(KhachHang::class, 'MaKH', 'MaKH');
    }
}

