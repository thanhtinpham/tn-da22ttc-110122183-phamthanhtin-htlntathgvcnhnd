<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phuongancauhoi extends Model
{
    use HasFactory;

    protected $table = 'phuongancauhoi';
    protected $primaryKey = 'MaPA';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'MaPA',
        'MaCauHoi',
        'NDPA',
        'DapAn',
        'Diem',
        'HinhAnh'
    ];

    public function cauhoi()
    {
        return $this->belongsTo(Cauhoi::class, 'MaCauHoi', 'MaCauHoi');
    }
}
