<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capdonghe extends Model
{
    use HasFactory;

    protected $table = 'capdonghe';
    protected $primaryKey = 'MaCDN';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'MaCDN',
        'TenCDN',
        'MoTaCDN'
    ];

    public function baitests()
    {
        return $this->hasMany(Baitest::class, 'MaCDN', 'MaCDN');
    }
}
