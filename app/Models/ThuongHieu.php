<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThuongHieu extends Model
{
    protected $table = 'thuonghieu';
    protected $primaryKey = 'Mathuonghieu';
    public $timestamps = false;

    protected $fillable = [
        'Tenthuonghieu',
        'NgaySinh',
        'QuocTich',
        'MoTa',
        'AnhDaiDien',
    ];

    public function sanphams()
    {
        return $this->belongsToMany(SanPham::class, 'sanpham_thuonghieu', 'Mathuonghieu', 'MaSP')->withPivot('VaiTro');
    }
}
