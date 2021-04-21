<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_name',
        'email',
        'password',
        'image',
        'introduction',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    protected $appends = [
        // 'followed_by',
        // 'count_spots',
        // 'count_favorite_spots',
        // 'count_followings',
        // 'count_followers',
    ];

    /**
     * 釣りスポット
     */
    public function spots(): HasMany
    {
        return $this->hasMany(Spot::class);
    }

    public function getCountSpotsAttribute(): int
    {
        return $this->spots->count();
    }

    /**
     *  イベント
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * お気に入りスポット
     */
    public function favoriteSpots(): BelongsToMany
    {
        return $this->belongsToMany(Spot::class, 'spot_favorite')->withTimestamps();
    }

    public function getCountFavoriteSpotsAttribute(): int
    {
        return $this->favoriteSpots->count();
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
     * フォロー
     */
    public function followings(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followee_id')->withTimestamps();
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'followee_id', 'follower_id')->withTimestamps();
    }

    public function getFollowedByAttribute(): bool
    {
        if (Auth::guest()) {
            return false;
        }

        return $this->followers->contains(Auth::user());
    }

    public function getCountFollowingsAttribute(): int
    {
        return $this->followings->count();
    }

    public function getCountFollowersAttribute(): int
    {
        return $this->followers->count();
    }
}
