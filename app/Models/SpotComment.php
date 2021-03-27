<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpotComment extends Model
{
    use HasFactory;

    protected $table = 'spot_comments';

    protected $fillable = [
        'comment',
        'comment_image',
    ];

    /**
     * ユーザー
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 釣りスポット
     */
    public function spot(): BelongsTo
    {
        return $this->belongsTo(Spot::class);
    }
}
