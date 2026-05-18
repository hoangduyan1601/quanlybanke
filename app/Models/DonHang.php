<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonHang extends Model
{
    protected $table = 'donhang';
    protected $primaryKey = 'MaDH';
    public $timestamps = false;

    protected $fillable = [
        'MaKH',
        'NgayDat',
        'TongTienHang',
        'PhiShip',
        'SoTienGiam',
        'TongThanhToan',
        'TrangThaiDH',
        'TrangThaiThanhToan',
        'TrangThaiVanChuyen',
        'MaDVVC',
        'MaVanDon',
        'PTThanhToan',
        'MaKM',
        'DiaChiGiao',
        'GhiChu',
        'SoTienDaThanhToan'
    ];

    public function khachhang()
    {
        return $this->belongsTo(KhachHang::class, 'MaKH', 'MaKH');
    }

    public function chiTietDonHangs()
    {
        return $this->hasMany(ChiTietDonHang::class, 'MaDH', 'MaDH');
    }

    public function statusLogs()
    {
        return $this->hasMany(DonHangStatusLog::class, 'MaDH', 'MaDH');
    }

    public function traHang()
    {
        return $this->hasOne(DonTraHang::class, 'MaDH', 'MaDH');
    }

    public function donViVanChuyen()
    {
        return $this->belongsTo(DonViVanChuyen::class, 'MaDVVC', 'MaDVVC');
    }
}
