<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonTraHang extends Model
{
    protected $table = 'don_tra_hangs';
    protected $primaryKey = 'MaTraHang';
    
    protected $fillable = [
        'MaDH',
        'LyDo',
        'HinhAnhMinhChung',
        'SoTienHoan',
        'TrangThaiDHTra'
    ];

    public function donhang()
    {
        return $this->belongsTo(DonHang::class, 'MaDH', 'MaDH');
    }
}

