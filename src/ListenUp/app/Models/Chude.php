<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chude extends Model
{
    use HasFactory;

    protected $table = 'chude';
    protected $primaryKey = 'MaCD';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'MaCD',
        'TenCD',
        'MoTa'
    ];

    /**
     * Map topic names to FontAwesome icons.
     */
    public function getIconClassAttribute()
    {
        $name = trim(mb_strtolower($this->TenCD, 'UTF-8'));
        switch ($name) {
            case 'travel':
            case 'du lịch':
            case 'du lich':
                return 'fas fa-plane-departure';
            case 'food':
            case 'đồ ăn':
            case 'do an':
            case 'ẩm thực':
            case 'am thuc':
                return 'fas fa-utensils';
            case 'school':
            case 'trường học':
            case 'truong hoc':
            case 'học tập':
            case 'hoc tap':
                return 'fas fa-graduation-cap';
            case 'work':
            case 'công việc':
            case 'cong viec':
            case 'sự nghiệp':
            case 'su nghiep':
                return 'fas fa-briefcase';
            case 'health':
            case 'sức khỏe':
            case 'suc khoe':
                return 'fas fa-heartbeat';
            case 'technology':
            case 'công nghệ':
            case 'cong nghe':
                return 'fas fa-laptop-code';
            case 'sports':
            case 'thể thao':
            case 'the thao':
                return 'fas fa-running';
            case 'music':
            case 'âm nhạc':
            case 'am nhac':
                return 'fas fa-music';
            case 'movie':
            case 'phim ảnh':
            case 'phim anh':
            case 'điện ảnh':
            case 'dien anh':
                return 'fas fa-film';
            case 'shopping':
            case 'mua sắm':
            case 'mua sam':
                return 'fas fa-shopping-cart';
            case 'comunicate':
            case 'communicate':
            case 'giao tiếp':
            case 'giao tiep':
                return 'fas fa-comments';
            default:
                return 'fas fa-headphones';
        }
    }

    /**
     * Map topic names to Bootstrap/Tailwind style color presets.
     */
    public function getColorClassAttribute()
    {
        $name = trim(mb_strtolower($this->TenCD, 'UTF-8'));
        switch ($name) {
            case 'travel':
            case 'du lịch':
            case 'du lich':
                return 'blue';
            case 'food':
            case 'đồ ăn':
            case 'do an':
            case 'ẩm thực':
            case 'am thuc':
                return 'orange';
            case 'school':
            case 'trường học':
            case 'truong hoc':
            case 'học tập':
            case 'hoc tap':
                return 'indigo';
            case 'work':
            case 'công việc':
            case 'cong viec':
            case 'sự nghiệp':
            case 'su nghiep':
                return 'slate';
            case 'health':
            case 'sức khỏe':
            case 'suc khoe':
                return 'rose';
            case 'technology':
            case 'công nghệ':
            case 'cong nghe':
                return 'cyan';
            case 'sports':
            case 'thể thao':
            case 'the thao':
                return 'emerald';
            case 'music':
            case 'âm nhạc':
            case 'am nhac':
                return 'violet';
            case 'movie':
            case 'phim ảnh':
            case 'phim anh':
            case 'điện ảnh':
            case 'dien anh':
                return 'pink';
            case 'shopping':
            case 'mua sắm':
            case 'mua sam':
                return 'amber';
            case 'comunicate':
            case 'communicate':
            case 'giao tiếp':
            case 'giao tiep':
                return 'purple';
            default:
                return 'purple';
        }
    }

    public function baitests()
    {
        return $this->hasMany(Baitest::class, 'MaCD', 'MaCD');
    }
}

