<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Xephang extends Model
{
    use HasFactory;

    protected $table = 'XEPHANG';
    protected $primaryKey = 'MaXH';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'MaXH',
        'TenXH'
    ];

    public function tientrinhs()
    {
        return $this->hasMany(Tientrinh::class, 'MaXH', 'MaXH');
    }
}
