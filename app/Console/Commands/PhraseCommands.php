<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Phrase;
use App\Urls;

class PhraseCommands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:phrase';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for run api to get urls from phrase.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * CURL FROM PARAMETR
     *
     * @return \Illuminate\Http\Response
     */
    public function curl($phrase, $city)
    {
        /*
        $client = new \GuzzleHttp\Client();
        $id = 5;
        $value = "ABC";

        $response = $client->request('GET', $url, ['query' => [
            'key1' => $id, 
            'key2' => $value,
        ]]);

        $statusCode = $response->getStatusCode();
        $content = $response->getBody();

        return json_decode($response->getBody(), true);
        */

		$data = [
			"q" => ''.sprintf("%s %s", $phrase->phrase, $city),
			"num" => 100,
			"parse" => "true",
			"geo" => sprintf("%s, Poland", $city),
        ];
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_USERPWD, "SM293786" . ":" . "vjIwv0siqw");	 //"SM75294" . ":" . "sph7tqIER1");//
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_URL, 'https://rt.serpmaster.com/');//'http://parse.ourcrm.pl/pobierz.json');//
		curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
		$response = curl_exec($ch);
		curl_close($ch);
        $json = json_decode( $response ,true );

		return $json;
    }

     /**
     * CONVERT DOMAIN => DOMAIN.ALIAS
     *
     * @return \Illuminate\Http\Response
     */
    public function clearUrl($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $urlParams = explode('/', $url);

            return sprintf("%s//%s", $urlParams[0], $urlParams[2]);
        } else {
            return false;
        }
    }

    /**
     * QUERY FREE ELEMENT
     *
     * @return \Illuminate\Http\Response
     */
    public function getFree($tableName)
    {
        $freeElement = DB::table($tableName)
                            ->where('checked', 0)
                            ->orderBy('id', 'ASC')
                            ->first();

        if (collect($freeElement)->isNotEmpty()) {
            DB::table($tableName)
                ->where('id', $freeElement->id)
                ->update(['checked' => 1, 'updated_at' => $this->getTime()]);
            return $freeElement;
        }

        return exit();
    }

    /**
     * GET TIME NOW
     *
     * @return \Illuminate\Http\Response
     */
    public function getTime()
    {
        return Carbon::now();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //if (config('app.token') != $token) {exit();}
        
        //CHECK FREE PHRASE -> GET URLS FROM PHRASE
        $freePhrase = $this->getFree('cities');

        if ($freePhrase) {
            $phrase = Phrase::findOrFail($freePhrase->phrase);
            $city = empty($freePhrase->city) ? 'Warszawa' : $freePhrase->city;
            $dane = $this->curl($phrase, $city);
            $limitString = 'Too many requests.';

//var_dump($dane);

            if (!isset($dane['error'])) {
                if (isset($dane['results'][0]['content']['results']['organic'])) {
                    foreach ($dane['results'][0]['content']['results']['organic'] as $url) {
                        $cleanUrl = $this->clearUrl($url['url']);
                        //check url is in db phrase
                        $urlIsInPhrase = DB::table('urls')
                                            ->where('phrase', $freePhrase->phrase)
                                            ->where('url', $cleanUrl)
                                            ->get();

                        //check url is excluded
                        $checkExclude = DB::select('SELECT * FROM `excludes` WHERE INSTR("'. $cleanUrl .'", `url`) <> 0;');

                        $checked = collect($checkExclude)->isEmpty() && collect($urlIsInPhrase)->isEmpty() ? 0 : 1;
                        $email = collect($checkExclude)->isEmpty() ? (collect($urlIsInPhrase)->isEmpty() ? '' : '[DUPLIKAT]') : '[WYKLUCZONY]';
                        
                        //if (collect($urlIsInPhrase)->isEmpty() && collect($checkExclude)->isEmpty()) { 

//echo '</br></br>';
//var_dump($url);

                            $time = $this->getTime();
                            DB::table('urls')->insert([
                                'phrase' => $freePhrase->phrase, 
                                'position' => $url['pos'],
                                'city' => $freePhrase->city,
                                'url' => $cleanUrl,
                                'type' => 'organic',
                                'created_at' => $time,
                                'updated_at' => $time,

                                'checked' => $checked,
                                'email' => $email,
                            ]);
                        //}
                    }
                }
            } else {
                if ($dane['error'] == $limitString) {
                    DB::table('configs')->where('parameter', 'serp')->update(['value' => '1']);
                    DB::table('cities')->where('id', $freePhrase->id)->update(['checked' => 0, 'updated_at' => $this->getTime()]);
                }
            }
        }
    }
}
