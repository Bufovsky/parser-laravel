<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\EmailValidate;
use App\Http\Controllers\EmailExtractor;
use GuzzleHttp\Client;

use App\Phrase;
use App\Exclude;

class UrlController extends Controller
{
    protected $pages = [];
    protected $email = '';
	protected $url = '';
	protected $contactPageName = ['kontakt', 'contact'];

    /**
     * QUERY FREE ELEMENT
     *
     * @param tableName Name of table to find
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

            $phraseName = Phrase::findOrFail($freeElement->phrase);
            DB::table('configs')->where('parameter', 'phrase')->update(['value' => $phraseName->phrase .' '. $freeElement->city]);
            DB::table('configs')->where('parameter', 'position')->update(['value' => $freeElement->position]); 

            return $freeElement;
        }

        return exit();
    }

    /**
     * GET PAGE CONTENT
     *
     * @return \Illuminate\Http\Response
     */
    public function pageGetContent($page)
    {
        //check is url exist
        //$headers = get_headers($page); 

        //if ($headers && strpos($headers[0], '200')) {
        if (!empty($page)) {
            try {
                //curl
                $handle = curl_init();
                curl_setopt($handle, CURLOPT_URL, $page);
                curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);

                curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($handle, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36");
                $output = curl_exec($handle);
                curl_close($handle);

                if ($output) {
                    return $output;
                }

                //Guzzle HTTP
                $client = new Client([
                    'base_uri' => trim($page),
                    'http_errors' => false
                ]);
                //$client->setDefaultOption('verify', false);
                //$client->setDefaultOption('verify', '/home/admin/domains/parse.vpsmaster.pl/public_html/bin/curl-ca-bundle.crt');
                
                return $client != false ? $client->request('GET', '', ['verify', '/home/admin/domains/parse.vpsmaster.pl/public_html/bin/curl-ca-bundle.crt'])->getBody()->getContents() : false;
                
                //file get content
                /*
                $content = file_get_contents($page, 0, stream_context_create(["ssl"=>["verify_peer"=>false,"verify_peer_name"=>false],"http"=>["timeout"=>30]]));
                return collect($page)->isNotEmpty() ? $content : null;
                */
            } catch(exception $e) {
                echo '</br>Nie można pobrać zasobów ze strony:'. $page;
            }

        } else {
            return false;
        }
    }

    /**
     * VALIDATE DOMAIN DOMAIN.ALIAS
     *
     * @return \Illuminate\Http\Response
     */
    public function validateUrl($url, $page)
    {
        $urlElements = parse_url($page);

		if (isset($urlElements['host'])) {
			if (is_int(strpos($urlElements['host'], explode('/', $url)[2]))) {
				return false;
			}
		}

        $array = ['/' => 'path', '?' => 'query', '#' => 'fragment'];

        foreach ($array as $key => $value) {
            if (isset($urlElements[$value])) {
                if ($value == 'path') {
                    $urlElements[$value] = substr( $urlElements[$value] , 0 , 1 ) == '/' ? substr($urlElements[$value], 1, strlen($urlElements[$value])) : $urlElements[$value];
                }
                $url .= $key . $urlElements[$value];
//echo $url .' >> '. $value .' >> '. $urlElements[$value] .'</br>';
            }
        }

        return $url;
    }


     /**
     * ADD MAIL TO DB
     *
     * @return \Illuminate\Http\Response
     */
	public function updateEmail($id, $email)
	{
        DB::table('urls')
        ->where('id', $id)
        ->update([
            'email' => $email,
            'updated_at' => $this->getTime()
        ]);

echo '</br></br>EMAIL: '. $email;

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
     * HIPER MEGA ULTRA ALGORITM OF TRUE EMAILS
     *
     * @return \Illuminate\Http\Response
     */
	public function checkEmails($array, $emails)
	{
		$domains = [ 
						'gmail.com' ,
						'wp.pl' , 
						'interia.pl' , 'poczta.fm' , 'interia.eu' , 'interia.com' , 'intmail.pl' , 'interiowy.pl' , 'adresik.net' , 'pisz.to' , 'vip.interia.pl' , 'pacz.to' , 'ogarnij.se' ,
						'o2.pl' , 'go2.pl' , 'tlen.pl' , 
						'onet.pl' , 'vp.pl' , 'op.pl' , 'opoczta.pl' , 'spoko.pl' , 'poczta.onet.pl' , 'onet.eu' , 'amorki.pl' , 'buziaczek.pl' , 'poczta.onet.pl' , 'poczta.onet.eu' ,
						'adresik.com' , 'os.pl' , 'va.pl' , 'iv.pl' , 'boy.pl' , 'gog.pl' , 'fuks.pl' , 'bajery.pl' , 'e-mail.net.pl' , 'skejt.biz' , 'ziomek.biz' , 'twoj.info' , 'twoja.info' , 'najx.com' ,
						'lycos.com' ,
						'gazeta.pl' ,
						'mail.com' ,
						'live.com' , 
						'yahoo.com'
					];
        $excluded = [ 'iod' , 'iodo' , 'inspektor' , 'ochronadanych', 'rodo' ];

        foreach ($emails as $email) {
            $emailParm = explode('@', $email);
            
            if ((is_int(strpos($emailParm[1], $array->url)) || is_int(strpos($array->url, $emailParm[1])) || in_array($emailParm[1], $domains)) && !in_array(strtolower($emailParm[0]), $excluded)) {
				$mailUrl = "http://api.ourcrm.pl/pages/checkEmail.php?token=scbCAVFty9QufbVxPCEMULKZfvu8V5M8&email=". $email;
                $emailClass = $this->pageGetContent( $mailUrl );
                    
                //$emailChecker = new EmailValidate;
                //$emailClass = isset($emailChecker->check($email)["result"]["success"]) ? $emailChecker->check($email)["result"]["success"] : 0;
                $this->email = $emailClass == 1 ? $this->updateEmail($array->id, $email) : '[NIEPRAWIDŁOWY]';
            } else {
                $this->email = '[NIEZGODNY]';
            }
        }
    }


    /**
     * Collect emails from pages
     *
	 * @param Url to parse
     * @return \Illuminate\Http\Response
     */
	public function getEmails($url)
	{
		//GET CONTENT AND FIND EMAIL OF EVERYPAGE
echo '<br/><br/>';
var_dump($url);
        $html = $this->pageGetContent($url);
		$str = '/([\w+\.]*\w+@[\w+\.]*\w+[\w+\-\w+]*\.\w+)/is';
        preg_match_all($str, $html, $out);

        //if no email get content with javascript
        if (count($out[0]) == 0) {
            $request = \PhantomJs::get($url);
            $response = \PhantomJs::send($request);
            
            if($response->getStatus() === 200) {
               $html = $response->getContent();
               preg_match_all($str, $html, $out);
            }
        }
echo '<br/>';		
var_dump($out[0]);
		
		return $out[0];
	}
	
    /**
     * Collect emails from pages
     *
	 * @param Url to parse
     * @return \Illuminate\Http\Response
     */
	public function getLinksFromUrl($freeUrl)
	{
		//GET DOM CONTENT
		$html = $this->pageGetContent($freeUrl->url);
		
//var_dump('<xmp>'. $html .'</xmp>');

		if (collect($html)->isNotEmpty()) {
			$dom = new \DOMDocument();
			@$dom->loadHTML($html);

			//FIND LINKS IN CONTENT
			if ($dom) {
				$links = $dom->getElementsByTagName('a');
                $isInArrayUrl = [];

                if (collect($this->pages)->isEmpty()) {
                    foreach ($links as $link) {
                        echo $link->getAttribute('href') .' == kontakt</br>';
                        foreach ($this->contactPageName as $pageName) {
                            if (is_int(strpos($link->getAttribute('href'), $pageName))) {
                                $page = $this->validateUrl($this->url, $link->getAttribute('href'));
                                $emails = collect($page)->isNotEmpty() ? $this->getEmails($page) : '';
                                $this->checkEmails($freeUrl, $emails);
                                break 2;
                            }
                        }
                    }
                }
				//$countLinks = count($links);
				//$linksCount = $countLinks > 100 ? 100 : $countLinks;
				//for ($i = 0; $i < $linksCount; $i++) {
				foreach ($links as $link) {
					$page = $this->validateUrl($this->url, $link->getAttribute('href'));

					if (!in_array($page, $this->pages) && !empty($page)) {
						$this->pages[] = $page;
						$emails = $this->getEmails($page);
						$this->checkEmails($freeUrl, $emails);
						$freeUrl->url = $page;
						$this->getLinksFromUrl($freeUrl);
					}
				}
			}
		}
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($token)
    {
        if (config('app.token') != $token) {exit();}
        set_time_limit(0);

        $freeUrl = $this->getFree('urls');
        $this->url = $freeUrl->url;



        /*
        $excludes = Exclude::all();
        $checkExclude = false;
        foreach ($excludes as $exclude) {
            var_dump($this->url);
            echo '</br>';
            var_dump($exclude->url);
            echo '</br>';
            var_dump(strpos($this->url, $exclude->url));
            echo '</br></br>';
            
            if (strpos($exclude->url, $cleanUrl) !== false) {
                $checkExclude = true;
                break;
            }
            
        }
        echo 'excludes:';
        var_dump($checkExclude);
        */




        if ($freeUrl) {
			//GET DOM CONTENT -> FIND LINKS -> GET EMAILS FROM SUBPAGES
			$html = $this->getLinksFromUrl($freeUrl);
				
			//SNOV IO
			if (collect($this->email)->isEmpty()) {
				//$findEmails = new EmailExtractor($freeUrl->url);
				//$snovIo = isset($findEmails['emails']) && count($findEmails['emails']) > 0 ? $this->checkEmails($freeUrl->url, $findEmails['emails']) : null;
            }
			
			//UPDATE EMAIL
			if (collect($this->email)->isNotEmpty()) {
				$this->updateEmail($freeUrl->id, $this->email);
			}
        }
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
        //
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
        //
    }
}

