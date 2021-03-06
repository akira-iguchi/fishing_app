<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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

    /**
     * 釣りスポット
     */
    public function spots()
    {
        return $this->hasMany(Spot::class);
    }

    public function getCountSpotsAttribute(): int
    {
        return $this->spots->count();
    }

    /**
     * お気に入りスポット
     */
    public function favoriteSpots()
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
    public function spot_comments()
    {
        return $this->hasMany(SpotComment::class);
    }

    public function getCountSpotCommentsAttribute(): int
    {
        return $this->spot_comments->count();
    }

    /**
     * フォロー
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'followee_id', 'follower_id')->withTimestamps();
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followee_id')->withTimestamps();
    }

    public function isFollowedBy(?User $user): bool
    {
        return $user
            ? (bool)$this->followers->where('id', $user->id)->count()
            : false;
    }

    public function getCountFollowersAttribute(): int
    {
        return $this->followers->count();
    }

    public function getCountFollowingsAttribute(): int
    {
        return $this->followings->count();
    }
}
