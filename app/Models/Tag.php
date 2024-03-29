<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'tags';

    protected $fillable = [
        'tag_name',
    ];

    protected $appends = [
        'hashtag',
        'count_spots',
    ];

    public function getHashtagAttribute(): string
    {
        return '#' . $this->tag_name;
    }

    /**
     * 釣りスポット
     */
    public function spots(): BelongsToMany
    {
        return $this->belongsToMany(Spot::class)->withTimestamps();
    }

    public function getCountSpotsAttribute(): int
    {
        return $this->spots->count();
    }
}
