<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\User;

class AccountController extends Controller
{
    protected $accessName = [0 => 'User', 1 => 'Admin'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
		
        return view('auth.index')->with('users', $users)->with('accessName', $this->accessName);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer',
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'nullable|string|confirmed',
            'access' => 'required|boolean',
        ]);

        $items = ['name', 'email', 'password', 'access'];
        $user = User::findOrFail($request->id);

        foreach ($items as $item) {
            if (collect($request->$item)->isNotEmpty()) {
                $user->$item = $request->$item;
            }
        }

        if ($user->save()) {
            session()->flash('message', 'success|Zaktualizowano użytkownika!');
        } else {
            session()->flash('message', 'error|Wystąpił błąd.');
        }

        return redirect(route('account.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('auth.edit')->with('user', $user);/*, compact('user')*/
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
