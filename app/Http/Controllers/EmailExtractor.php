<?php
	
namespace App\Http\Controllers;
  
class EmailExtractor
{
	public static function getAccessToken()
	{
		$params = [
			'grant_type'    => 'client_credentials',
			'client_id'     => '79ea7f749cc99b0bcc0dc046f9e1992e',
			'client_secret' => 'b852565eedcd659a5c0ac8084db84838'
		];

		$options = [
			CURLOPT_URL            => 'https://api.snov.io/v1/oauth/access_token',
			CURLOPT_POST           => true,
			CURLOPT_POSTFIELDS     => $params,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true
		];

		$ch = curl_init();

		curl_setopt_array($ch, $options);

		$res = json_decode(curl_exec($ch), true);
		curl_close($ch);

		return $res['access_token'];
	}

	function __construct($page)
	{
		$token = $this::getAccessToken();
		
		$params = [
			'access_token' => $token,
			'domain'       => $page,
			'type'         => 'all',
			'limit'        => 10,
			'lastId'       => 0
		];

		$params = http_build_query($params);
		$options = [
			CURLOPT_URL            => 'https://api.snov.io/v2/domain-emails-with-info?'.$params,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true
		];

		$ch = curl_init();

		curl_setopt_array($ch, $options);

		$res = json_decode(curl_exec($ch), true);
		curl_close($ch);

		return $res;
	}
}
	
?>