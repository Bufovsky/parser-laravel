<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class City extends Model
{
    protected $fillable = ['id', 'phrase', 'city', 'checked', 'created_at'];
}
