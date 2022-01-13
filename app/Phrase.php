<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Phrase extends Model
{
    protected $fillable = ['id', 'phrase', 'checked', 'added', 'cerificated', 'imported'];
}
