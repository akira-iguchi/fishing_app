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

    /**
     * ユーザー
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 釣りスポットのコメント
     */
    public function spot_comments()
    {
        return $this->hasMany(SpotComment::class);
    }

    /**
     * お気に入り
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

    /**
     * タグ
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }
}
