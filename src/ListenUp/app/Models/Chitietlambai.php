<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chitietlambai extends Model
{
    use HasFactory;

    protected $table = 'CHITIETLAMBAI';
    protected $primaryKey = 'MaCTLB';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'MaCTLB',
        'UserID',
        'SoLanLam',
        'MaBai',
        'ThoiGianLam',
        'SoCauDung',
        'TongSoCau',
        'CreatedAt'
    ];

    public function baitest()
    {
        return $this->belongsTo(Baitest::class, 'MaBai', 'MaBai');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }

    public function bandophieuluu()
    {
        return $this->belongsToMany(Bandophieuluu::class, 'choi', 'MaCTLB', 'MaBanDo');
    }

    public function cauhoi()
    {
        return $this->belongsToMany(Cauhoi::class, 'ket qua', 'MaCTLB', 'MaCauHoi')->withPivot('KetQuaChon');
    }
}
