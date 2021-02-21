<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    use HasFactory;

    protected $table = 'spots';

    protected $fillable = [
        'name',
        'explanation',
        'address',
        'image',
        'latitude',
        'longitude'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * このスポットをお気に入り中のユーザー
     */
    public function user_favorites()
    {
        return $this->belongsToMany(Spot::class, 'spot_favorite', 'spot_id', 'user_id')->withTimestamps();
    }
}
