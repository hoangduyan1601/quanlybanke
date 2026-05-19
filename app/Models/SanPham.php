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

    protected $appends = ['main_image_url'];

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

    public function danhgias()
    {
        return $this->hasMany(DanhGia::class, 'MaSP', 'MaSP');
    }

    public function getAverageRatingAttribute()
    {
        return $this->danhgias()->avg('SoSao') ?: 0;
    }

    public function getReviewCountAttribute()
    {
        return $this->danhgias()->count();
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

    public function getMainImageUrlAttribute()
    {
        $displayImage = $this->HinhAnh;
        if (empty($displayImage) && $this->hinhanhsanpham->count() > 0) {
            $displayImage = $this->hinhanhsanpham->first()->DuongDan;
        }

        if (empty($displayImage)) {
            // Mặc định ảnh kệ nếu không có ảnh
            return 'https://images.unsplash.com/photo-1594488630399-bf8351a1ee4d?q=80&w=300&auto=format&fit=crop';
        }

        if (filter_var($displayImage, FILTER_VALIDATE_URL)) {
            return $displayImage;
        }

        return asset('assets/images/products/' . $displayImage);
    }
}


