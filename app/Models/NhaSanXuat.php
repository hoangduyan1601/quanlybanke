<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhaSanXuat extends Model
{
    protected $table = 'nhasanxuat';
    protected $primaryKey = 'MaNXB';
    public $timestamps = false;

    protected $fillable = [
        'TenNXB',
        'DiaChi',
        'SDT',
        'Email',
    ];

    public function sanphams()
    {
        return $this->hasMany(SanPham::class, 'MaNXB', 'MaNXB');
    }
}

