<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpotImage extends Model
{
    use HasFactory;

    protected $table = 'spot_images';


    protected $fillable = [
        'spot_image',
    ];

    /**
     * 釣りスポット
     */
    public function spot(): BelongsTo
    {
        return $this->belongsTo(Spot::class);
    }
}
