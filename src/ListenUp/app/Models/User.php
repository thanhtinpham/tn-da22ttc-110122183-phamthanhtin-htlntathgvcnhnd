<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'UserID';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = null; // No UpdatedAt in SQL, or we can add it later

    protected $fillable = [
        'UserID',
        'UserName',
        'Email',
        'google_id',
        'Password',
        'Role',
        'Status',
        'CreatedAt',
        'LastLoginAt',
        'AnhDaiDien',
        'SDT',
        'GioiTinh',
        'NgaySinh',
        'TongDiem',
        'DiemMan',
        'Vien',
        'preferred_accent',
        'preferred_speed',
        'learning_goal',
        'current_level',
        'daily_target_time'
    ];

    protected $hidden = [
        'Password',
        'remember_token',
    ];

    protected $casts = [
        'NgaySinh' => 'datetime',
        'CreatedAt' => 'datetime',
        'LastLoginAt' => 'datetime',
    ];

    /**
     * Override password field for authentication
     */
    public function getAuthPassword()
    {
        return $this->Password;
    }

    // Kiểm tra role
    public function isAdmin()
    {
        return strtolower($this->Role) === 'admin';
    }

    public function isUser()
    {
        return strtolower($this->Role) === 'user';
    }

    // Quan hệ với kết quả luyện tập
    public function results()
    {
        return $this->hasMany(Chitietlambai::class, 'UserID', 'UserID');
    }

    // Quan hệ với tiến độ học tập
    public function progress()
    {
        return $this->hasMany(Tientrinh::class, 'UserID', 'UserID');
    }

}