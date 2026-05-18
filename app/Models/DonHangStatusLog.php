<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonHangStatusLog extends Model
{
    protected $table = 'donhang_status_logs';
    protected $fillable = [
        'MaDH',
        'UserID',
        'HanhDong',
        'GhiChu'
    ];

    public function donhang()
    {
        return $this->belongsTo(DonHang::class, 'MaDH', 'MaDH');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'id');
    }
}

