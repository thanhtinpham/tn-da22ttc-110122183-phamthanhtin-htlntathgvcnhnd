<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bandophieuluu extends Model
{
    use HasFactory;

    protected $table = 'bandophieuluu';
    protected $primaryKey = 'MaBanDo';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'MaBanDo',
        'TenBanDo',
        'YeuCauBanDo',
        'TrangThaiBanDo',
        'HinhAnh'
    ];

    public function baitests()
    {
        return $this->hasMany(Baitest::class, 'MaBanDo', 'MaBanDo');
    }
}
