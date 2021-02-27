<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    public function spot_favorites()
    {
        return $this->belongsToMany(User::class, 'spot_favorite')->withTimestamps();
    }

    public function isLikedBy(?User $user): bool
    {
        return $user
            ? (bool)$this->spot_favorites->where('id', $user->id)->count()
            : false;
    }

    public function getCountSpotFavoritesAttribute(): int
    {
        return $this->spot_favorites->count();
    }
}
