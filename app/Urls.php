<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Urls extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'phrase', 'city', 'position', 'url', 'type', 'checked', 'selected', 'email',
    ];
}
