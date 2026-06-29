<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tepamthanh extends Model
{
    use HasFactory;

    protected $table = 'tepamthanh';
    protected $primaryKey = 'MaTep';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'MaTep',
        'DuongDan',
        'TGTep',
        'GioiHanPhat',
        'TenTep'
    ];

    public function phans()
    {
        return $this->hasMany(Phan::class, 'MaTep', 'MaTep');
    }
}
