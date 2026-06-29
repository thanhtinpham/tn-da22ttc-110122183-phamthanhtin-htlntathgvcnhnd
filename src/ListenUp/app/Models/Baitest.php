<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Baitest extends Model
{
    use HasFactory;

    protected $table = 'baitest';
    protected $primaryKey = 'MaBai';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'MaBai',
        'MaBanDo',
        'MaCD',
        'MaCDN',
        'TenBai',
        'TongSoPhan',
        'TongThoiLuong',
        'TrangThaiBai',
        'MoTa',
        'TuKhoaHangDoc',
        'AnhTroChoi',
        'SoManhGhep'
    ];

    public function bandophieuluu()
    {
        return $this->belongsTo(Bandophieuluu::class, 'MaBanDo', 'MaBanDo');
    }

    public function chude()
    {
        return $this->belongsTo(Chude::class, 'MaCD', 'MaCD');
    }

    public function capdonghe()
    {
        return $this->belongsTo(Capdonghe::class, 'MaCDN', 'MaCDN');
    }

    public function phan()
    {
        return $this->hasMany(Phan::class, 'MaBai', 'MaBai')->orderBy('ThuTuPhan', 'asc');
    }

    public function getSoCauHoiAttribute()
    {
        return $this->phan->sum('SoCauHoi');
    }

    public function getCauhoiAttribute()
    {
        return $this->phan->flatMap->cauhoi;
    }
}
