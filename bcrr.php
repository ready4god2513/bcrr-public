<?php

class Bcrr
{
	
	private $base_path = "http://bcrr.us/api/";
	private $key = ""; // This needs to be a key unique to your application.  Create one by logging  in to bcrr.us
	
	
	public function generate_link($link, $hash = "")
	{
		$response = remote::send_request($this->base_path . "shorts.json?apiKey=" . $this->key, array(
			"short[expanded]" => $link,
			"short[contracted]" => $hash
		));
		
		$response = json_decode($response);
		if($response->status_code == 200)
		{
			return $response->shorts->short->hashed; // Will return the hashed ID of the short.
		}
		else
		{
			throw new Exception($response->status_text, 500);
		}
	}
	
	
}

class remote
{
	
	public static function send_request($url, $post)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
		curl_setopt($ch, CURLOPT_HEADER, false);  // DO NOT RETURN HTTP HEADERS
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // RETURN THE CONTENTS OF THE CALL
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);
		$info =  curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		return $response;
	}
	
}