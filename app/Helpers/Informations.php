<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;

use App\Config;

class Informations extends Model
{
    /**
     * Show parser position
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public static function index(string $param)
    {
        $query = Config::select('value')->where('parameter', $param)->first();
        return $query['value'];
    }
}
