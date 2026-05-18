<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SanPham extends Model
{
    protected $table = 'sanpham';
    protected $primaryKey = 'MaSP';
    public $timestamps = false;

    protected $fillable = [
        'TenSP',
        'DonGia',
        'SoLuong',
        'MoTa',
        'HinhAnh',
        'MaDM',
        'MaNXB', // Khớp với migration: nhasanxuat dùng MaNXB
        'NgayCapNhat',
        'SoLuongDaBan',
        'Slug', // Bổ sung cho SEO chuẩn doanh nghiệp
        'MoTaNgan',
        'TrangThai'
    ];

    public function danhmuc()
    {
        return $this->belongsTo(DanhMuc::class, 'MaDM', 'MaDM');
    }

    public function NhaSanXuat()
    {
        return $this->belongsTo(NhaSanXuat::class, 'MaNXB', 'MaNXB');
    }

    public function ThuongHieus()
    {
        // Khớp với migration: sanpham_thuonghieu (MaSP, Mathuonghieu)
        return $this->belongsToMany(ThuongHieu::class, 'sanpham_thuonghieu', 'MaSP', 'Mathuonghieu');
    }

    public function variants()
    {
        return $this->hasMany(SanPhamVariant::class, 'MaSP', 'MaSP');
    }

    public function hinhanhsanpham()
    {
        return $this->hasMany(HinhAnhSanPham::class, 'MaSP', 'MaSP');
    }

    public function chiTiet()
    {
        return $this->hasOne(ChiTietSanPham::class, 'MaSP', 'MaSP');
    }

    public function chiTietDonHangs()
    {
        return $this->hasMany(ChiTietDonHang::class, 'MaSP', 'MaSP');
    }

    public function chiTietNhapHangs()
    {
        return $this->hasMany(ChiTietNhapHang::class, 'MaSP', 'MaSP');
    }

    public function chiTietGioHangs()
    {
        return $this->hasMany(ChiTietGioHang::class, 'MaSP', 'MaSP');
    }

    public function favorites()
    {
        return $this->belongsToMany(KhachHang::class, 'yeuthich', 'MaSP', 'MaKH')->withPivot('NgayThem');
    }

    public function getThuongHieuStringAttribute()
    {
        // Khớp với migration: Tenthuonghieu
        return $this->ThuongHieus ? $this->ThuongHieus->pluck('Tenthuonghieu')->implode(', ') : '';
    }

    public function getIsFavoriteAttribute()
    {
        if (Auth::check()) {
            // Kiểm tra bảng taikhoan khớp với migration
            $customer = KhachHang::where('MaTK', Auth::user()->MaTK ?? Auth::id())->first();
            if ($customer) {
                return $this->favorites()->where('khachhang.MaKH', $customer->MaKH)->exists();
            }
        }
        return false;
    }

    public function getKhuyenMaiActiveAttribute()
    {
        $now = now();
        $kmDanhMuc = \App\Models\KhuyenMai::where('MaDM', $this->MaDM)
            ->where('LoaiKM', 'DanhMuc')
            ->where('NgayBatDau', '<=', $now)
            ->where('NgayKetThuc', '>=', $now)
            ->orderBy('PhanTramGiam', 'desc')
            ->first();

        $kmTatCa = \App\Models\KhuyenMai::where('LoaiKM', 'TatCa')
            ->where('NgayBatDau', '<=', $now)
            ->where('NgayKetThuc', '>=', $now)
            ->orderBy('PhanTramGiam', 'desc')
            ->first();

        return $kmDanhMuc ?: $kmTatCa;
    }

    public function getGiaHienTaiAttribute()
    {
        $km = $this->khuyen_mai_active;
        if ($km) {
            return $this->DonGia * (1 - $km->PhanTramGiam / 100);
        }
        return $this->DonGia;
    }
}


