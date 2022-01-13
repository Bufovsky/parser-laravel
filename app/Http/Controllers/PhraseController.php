<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Phrase;
use App\City;
use App\Urls;
use App\Exclude;

class PhraseController extends Controller
{
    protected $checked = [0 => '<i class="icon-cross2 text-danger-400"></i>', 1 => '<i class="icon-checkmark3 text-success"></i>'];

    /**
     * Estimate time to parse phrases
     *
     * @return \Illuminate\Http\Response
     */
    public function estimate()
    {
        $countCities = City::where('checked', 0)->count();
        $countUrls = Urls::where('checked', 0)->count();
		$count = gmdate("H:i:s", ( ( intval( $countUrls ) * intval( 10 ) ) + ( $countCities * 1060 ) ) );
        
        return $count;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $phrases = Phrase::latest('created_at')->paginate(100);//DB::table('phrases')->orderBy('id', 'DESC')->get();
        $estimate = $this->estimate();

		return view('pages.phrase.index')->with('phrases', $phrases)->with('estimate', $estimate);
    }


    /**
     * Export mails to csv
     *
     * @return \Illuminate\Http\Response
     */
    public function export($id)
    {
        
        $data = Validator::make(
            ['_id' => $id],
            ['_id' => ['bail', 'required','integer']]
        );
        if ($data->fails()) {
            return response()->json(['error' => $data->errors()], 400);
        }

        $phrase = Phrase::findOrFail($id);
        
        $cities = City::where('phrase', $id)->select('city')->orderBy('id', 'ASC')->get()->toArray(); 
        
        //collect($cities)->values()->all();
        $citiesArray = [];
        foreach ($cities as $city) {
            $citiesArray[] = $city['city'];
        }

        $urls = Urls::where([
            ['phrase', $id],  
            ['email', 'NOT LIKE', '[%'], 
            ['email', '<>', ''], 
        ])->whereIn('city', $citiesArray)
        ->orderBy('id', 'ASC')
        ->get();
        
        
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=".$phrase->phrase.".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        
        $columns = array('Email', 'Url', 'Fraza', 'Pozycja', 'Opis');

        $callback = function() use($urls, $phrase) {
            $file = fopen('php://output', 'w');

            foreach ($urls as $url) {
                $row['Email']  = $url->email;
                $row['Url']    = explode('/', $url->url)[2];
                $row['Fraza']    = $phrase->phrase;
                $row['Pozycja']  = $url->position;
                $row['Opis']  = $row['Pozycja'] < 10 ? 'poniżej pierwszych miejsc' : 'poniżej pierwszej strony';

                fputcsv($file, array($row['Email'], $row['Url'], $row['Fraza'], $row['Pozycja'], $row['Opis']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.phrase.create');
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
            'phrase' => 'required|string',
            'cities' => 'nullable|array',
            'industries' => 'nullable|string'
        ]);

		$phrase = Phrase::create([
			'phrase' => $request->phrase
		]);

        if (collect($request->phrase)->isNotEmpty()) {
            $data = [];
            $elements = [];
            $arrays = ['cities', 'industries'];
            $request->industries = explode(PHP_EOL, $request->industries);

            foreach ($arrays as $array) {
                if (!empty($request->$array[0])) {
                    $elements = array_merge($elements, $request->$array);
                }
            }

            if (empty($elements)) {
                $elements = [''];
            }

            print_r($elements);

            foreach ($elements as $city) {
                $data[] = [
                    'phrase' => $phrase->id, 
                    'city' => trim($city),//Str::of($city)->trim(), 
                    'created_at' => Carbon::now(),
                ];
            }

            City::insert($data);

            /*
                City::create([
                    'phrase' => $phrase->id,
                    'city' => $city
                ]);
            */

            session()->flash('message', 'success|Fraza została dodana!');
        } else {
            session()->flash('message', 'error|Wprowadź frazę.');
        }

        return redirect(route('phrase.create'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cities = DB::table('cities')->where('phrase', $id)->orderBy('id', 'ASC')->get();
        $phrase = Phrase::findOrFail($id);
		
		return view('pages.phrase.show')->with('cities', $cities)->with('phrase', $phrase)->with('checked', $this->checked);
    }


    /**
     * Display the specified city in phrase
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function city($phrase, $city)
    {
        $city = $city === $phrase ? '' : $city;
        $urls = DB::table('urls')->where('city', $city)->where('phrase', $phrase)->get();
        $phrase = Phrase::findOrFail($phrase);
		
		return view('pages.phrase.city')->with('urls', $urls)->with('city', $city)->with('checked', $this->checked)->with('phrase', $phrase);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //$post = Post::findOrFail($id);

        //return view('admin.post.edit', compact('post'));
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
        $phrase = Phrase::findOrFail($id)->delete();

        $cities = City::where('phrase', $id)->delete();

        $urls = Urls::where('phrase', $id)->delete();
        
        $message = collect($phrase)->isNotEmpty() ? 'success|Usunięto frazę!' : 'error|Nie usunięto frazy.';

        session()->flash('message', $message);

		return redirect(route('phrase.index'));
    }
}
