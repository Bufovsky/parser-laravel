<?PHP

$phrase = 'Lokówki';
$city = 'Warszawa';
		$ch = curl_init();

		$data = [
			"q" => ''.sprintf("%s %s", $phrase->phrase, $city),
			"num" => 100,
			"parse" => "true",
			"geo" => sprintf("%s, Poland", $city),
        ];
var_dump(json_encode($data));

//var_dump( file_get_contents("https://energiadlawarszawy.pl") );

		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_USERPWD, "SM293786" . ":" . "vjIwv0siqw");//"SM75294" . ":" . "sph7tqIER1");//
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_URL, 'https://rt.serpmaster.com/');//'http://parse.ourcrm.pl/pobierz.json');//
		curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
		$response = curl_exec($ch);
		curl_close($ch);
        	$json = json_decode( $response ,true );

var_dump($json );

		$str = '/[\w.%+-]+@(?:[a-z\d-]+\.)+[a-z]{2,4}/iu';
		preg_match_all($str, $html, $out);

var_dump($out);
		
		foreach( $out[0] as $email )
		{
			echo $email;
		}

?>