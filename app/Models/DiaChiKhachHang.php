<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiaChiKhachHang extends Model
{
    protected $table = 'diachi_khachhang';
    protected $primaryKey = 'MaDC';
    public $timestamps = false;

    protected $fillable = [
        'MaKH',
        'HoTenNguoiNhan',
        'SDTNguoiNhan',
        'DiaChiChiTiet',
        'PhuongXa',
        'QuanHuyen',
        'TinhThanh',
        'MacDinh'
    ];

    public function khachhang()
    {
        return $this->belongsTo(KhachHang::class, 'MaKH', 'MaKH');
    }
}

