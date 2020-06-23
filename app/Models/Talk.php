<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Talk extends Model
{
    protected $fillable = ['uuid', 'topic', 'description', 'date', 'time'];
}
