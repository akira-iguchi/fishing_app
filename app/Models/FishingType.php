<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FishingType extends Model
{
    use HasFactory;

    protected $table = 'fishing_types';

    protected $fillable = [
        'fishing_type_name',
        'content',
    ];
}
