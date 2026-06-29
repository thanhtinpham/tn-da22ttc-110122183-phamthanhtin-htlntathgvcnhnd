<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phan extends Model
{
    use HasFactory;

    protected $table = 'phan';
    protected $primaryKey = 'MaPhan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'MaPhan',
        'MaBai',
        'MaTep',
        'TenPhan',
        'ThuTuPhan',
        'SoCauHoi',
        'ThoiLuong',
        'GioiHanPhat',
        'MoTaPhan',
        'TrangThaiPhan'
    ];

    public function baitest()
    {
        return $this->belongsTo(Baitest::class, 'MaBai', 'MaBai');
    }

    public function tepamthanh()
    {
        return $this->belongsTo(Tepamthanh::class, 'MaTep', 'MaTep');
    }

    public function cauhoi()
    {
        return $this->belongsToMany(Cauhoi::class, 'phan_cauhoi', 'MaPhan', 'MaCauHoi');
    }
}
