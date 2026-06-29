<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loaicauhoi extends Model
{
    use HasFactory;

    protected $table = 'LOAICAUHOI';
    protected $primaryKey = 'MaLoai';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'MaLoai',
        'TenLoai',
        'MoTaLoai'
    ];

    public function cauhoi()
    {
        return $this->hasMany(Cauhoi::class, 'MaLoai', 'MaLoai');
    }
}
