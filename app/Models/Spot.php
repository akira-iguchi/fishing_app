<?php

namespace App\Models;

use App\Traits\TagNameTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 釣りスポットの画像
     */
    public function spotImages(): HasMany
    {
        return $this->hasMany(SpotImage::class);
    }

    public function firstSpotImage()
    {
        return $this->spotImages->first()->spot_image;
    }

    /**
     * 釣りスポットのコメント
     */
    public function spotComments(): HasMany
    {
        return $this->hasMany(SpotComment::class);
    }

    public function getCountSpotCommentsAttribute(): int
    {
        return $this->spotComments->count();
    }

    /**
     * お気に入り
     */
    public function spotFavorites(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'spot_favorite')->withTimestamps();
    }

    public function isLikedBy(?User $user): bool
    {
        return $user
            ? (bool)$this->spotFavorites->where('id', $user->id)->count()
            : false;
    }

    public function getCountSpotFavoritesAttribute(): int
    {
        return $this->spotFavorites->count();
    }

    /**
     * タグ
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    /**
     * 釣り方
     */
    public function fishingTypes(): BelongsToMany
    {
        return $this->belongsToMany(FishingType::class, 'spot_fishing_type')->withTimestamps();
    }
}
