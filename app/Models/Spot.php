<?php

namespace App\Models;

use App\Traits\TagNameTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Spot extends Model
{
    use HasFactory;
    use TagNameTrait;

    protected $table = 'spots';

    protected $fillable = [
        'spot_name',
        'explanation',
        'address',
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
     * 釣りスポットの画像
     */
    public function spot_images()
    {
        return $this->hasMany(SpotImage::class);
    }

    public function first_spot_image()
    {
        return $this->spot_images->first()->spot_image;
    }

    /**
     * 釣りスポットのコメント
     */
    public function spot_comments()
    {
        return $this->hasMany(SpotComment::class);
    }

    public function getCountSpotCommentsAttribute(): int
    {
        return $this->spot_comments->count();
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

    /**
     * 釣り方
     */
    public function fishing_types()
    {
        return $this->belongsToMany(FishingType::class, 'spot_fishing_type')->withTimestamps();
    }
}
