<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use App\Phrase;

class Breadcrumbs extends Model
{
    /**
     * Generate URL to breadcrumbs
     *
     * @param  $path full path of url
     * @param  $i deep of breadcrumb
     * @return mixed
     */
    public static function generateUrl(array $path, int $m)
    {
        $main = $path[0] .'//'. $path[2];

        for ($i = 3; $i <= $m; $i++) {
            if ($m == 3) {$path[$i] .= '/index';}
            $main .= '/'. $path[$i];
        }

        if ($m == 4) {$main = $path[4] == 'show' ? null : null;}

        return $main;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public static function index()
    {
        $main = '<a href="'. route('dashboard') .'" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>';

        $path = explode('/', url()->current());//Str::of(url()->current())->explode('/');
        $countPath = count($path)-1;

        for ($i = 3; $i <= $countPath; $i++) {
            $text_path[$i] = $i == 5 ? Phrase::findOrFail($path[5])->phrase : $path[$i];

            $main .= $i == $countPath ? '<span class="breadcrumb-item active">'. $text_path[$i] .'</span>' : '<a href="'. static::generateUrl($path, $i) .'" class="breadcrumb-item">'. $text_path[$i] .'</a>';
        }

        return $main;
    }
}
