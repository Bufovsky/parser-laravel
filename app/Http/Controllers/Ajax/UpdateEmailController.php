<?php

namespace App\Http\Controllers\ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use App\Urls;

class UpdateEmailController extends Controller
{
    /**
     * Update Email in Database
     *
     * @param $id - Id of record in db
     * @param $email - New value of `email`
     * @return \Illuminate\Http\Response
     */
    public function index($id, $email)
    {
        $data = Validator::make(
            [
                '_id' => $id,
                'email' => $email
            ],
            [
                '_id' => ['bail', 'required','integer'],
                'email' => ['bail', 'required', 'email']
            ]
        );
        
        if ($data->fails()) {
            return response()->json(['error' => $data->errors()], 400);
        }
        
        Urls::findOrFail($id)->update(['email' => $email]);
    }
}
