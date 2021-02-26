<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    use HasFactory;

    protected $table = 'spots';

    protected $fillable = [
        'spot_name',
        'explanation',
        'address',
        'spot_image',
        'latitude',
        'longitude'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function spot_comments()
    {
        return $this->hasMany(SpotComment::class);
    }

    /**
     * このスポットをお気に入り中のユーザー
     */
    public function user_favorites()
    {
        return $this->belongsToMany(Spot::class, 'spot_favorite', 'spot_id', 'user_id')->withTimestamps();
    }
}
