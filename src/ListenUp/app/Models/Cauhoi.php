<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cauhoi extends Model
{
    use HasFactory;

    protected $table = 'cauhoi';
    protected $primaryKey = 'MaCauHoi';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'MaCauHoi',
        'MaLoai',
        'NDCauHoi',
        'TrangThaiCauHoi',
        'ViTriGiao'
    ];

    public function loaicauhoi()
    {
        return $this->belongsTo(Loaicauhoi::class, 'MaLoai', 'MaLoai');
    }

    public function phuongancauhoi()
    {
        return $this->hasMany(Phuongancauhoi::class, 'MaCauHoi', 'MaCauHoi');
    }

    public function phans()
    {
        return $this->belongsToMany(Phan::class, 'phan_cauhoi', 'MaCauHoi', 'MaPhan');
    }
}
