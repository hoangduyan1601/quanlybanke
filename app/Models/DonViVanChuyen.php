<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonViVanChuyen extends Model
{
    protected $table = 'don_vi_van_chuyens';
    protected $primaryKey = 'MaDVVC';

    protected $fillable = [
        'TenDVVC',
        'SDT',
        'TrangThai'
    ];

    public function donhangs()
    {
        return $this->hasMany(DonHang::class, 'MaDVVC', 'MaDVVC');
    }
}


