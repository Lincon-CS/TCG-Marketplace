<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $guarded = [];

    // Add these two properties:
    public $incrementing = false;
    protected $keyType = 'string';
}