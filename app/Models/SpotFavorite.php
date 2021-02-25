<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpotFavorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'spot_id','user_id','spot_favorite'
    ];
}
