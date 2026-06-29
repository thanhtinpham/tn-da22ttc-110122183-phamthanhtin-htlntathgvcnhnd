<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tientrinh extends Model
{
    use HasFactory;

    protected $table = 'TienTrinh';
    // This table has composite primary key (MaBanDo, UserID, MaXH)
    // Laravel doesn't support composite keys well, but we can specify one or just ignore PK if not needed for updates.
    protected $primaryKey = ['MaBanDo', 'UserID', 'MaXH'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'MaBanDo',
        'UserID',
        'MaXH',
        'ViTri',
        'KetQuaMan'
    ];

    public function bandophieuluu()
    {
        return $this->belongsTo(Bandophieuluu::class, 'MaBanDo', 'MaBanDo');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }

    public function xephang()
    {
        return $this->belongsTo(Xephang::class, 'MaXH', 'MaXH');
    }
}
