<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FishingType extends Model
{
    use HasFactory;

    protected $table = 'fishing_types';

    protected $fillable = [
        'fishing_type_name',
        'fishing_type_image',
        'content',
    ];

    /**
     *釣りスポット
     */
    public function spots(): BelongsToMany
    {
        return $this->belongsToMany(Spot::class, 'spot_fishing_type')->withTimestamps();
    }
}
