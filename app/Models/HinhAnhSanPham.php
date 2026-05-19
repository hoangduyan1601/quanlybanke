<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HinhAnhSanPham extends Model
{
    protected $table = 'hinhanhsanpham';
    protected $primaryKey = 'MaHinh';
    public $timestamps = false;

    protected $fillable = [
        'MaSP',
        'DuongDan',
        'LaAnhChinh',
    ];

    public function getUrlAttribute()
    {
        if (filter_var($this->DuongDan, FILTER_VALIDATE_URL)) {
            return $this->DuongDan;
        }
        return asset('assets/images/products/' . $this->DuongDan);
    }
}

