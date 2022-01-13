<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;

use App\Exclude;

class ExcludeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $excludes = Exclude::latest('created_at')->paginate(100);
		
		return view('pages.exclude.index', compact('excludes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.exclude.create');
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
			'exclude' => 'required|string'
        ]);

        if (collect($request->exclude)->isNotEmpty()) {
            $excludes = explode(PHP_EOL, $request->exclude);
            $time = Carbon::now();

            foreach ($excludes as $exclude) {
                //Check duplicate 
                $checkDuplicateExclude = Exclude::all()->where('url', $exclude);
                
                if (collect($checkDuplicateExclude)->isEmpty()) {
                    Exclude::create([
                        'url' => $exclude,
                        'created_at' => $time,
                        'updated_at' => $time
                    ]);
                }
            }
            
            $message = 'success|Wykluczone adresy zostały dodane!';
        } else {
            $message = 'error|Wprowadź adresy w pole tekstowe.';
        }

        session()->flash('message', $message);

        return redirect(route('exclude.create'));
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
        //
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
        $exclude = Exclude::findOrFail($id);

        $check = $exclude->delete();
        
        $message = collect($check)->isNotEmpty() ? 'success|Usunięto wykluczony adres!' : 'error|Nie usunięto wykluczonego adresu.';

        session()->flash('message', $message);

		return redirect(route('exclude.index'));
    }
}
