<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 釣りスポット
     */
    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }
}
