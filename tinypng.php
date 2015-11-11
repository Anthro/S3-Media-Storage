<?php

/**
 * This class was taken from portions of code
 * written by Anne Rozema from HETWORKS, http://www.hetworks.nl
 */

class TinyPNG {

	private static $key = false;

	public function __construct($key) {
		$this->key = $key;
	}

	public function shrink( $file ) {
		if ($this->key) {
			$this->tinypngrequest($file);
		}
	  return $file;
	}

	public function tinypngrequest($file) {
		$request = curl_init();
		curl_setopt_array($request, array(
			CURLOPT_URL => "https://api.tinypng.com/shrink",
			CURLOPT_USERPWD => "api:" . $this->key,
			CURLOPT_POSTFIELDS => file_get_contents($file),
			CURLOPT_BINARYTRANSFER => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER => true,
			CURLOPT_CAINFO => dirname(__FILE__) . '/cacert.pem',
			CURLOPT_SSL_VERIFYPEER => true 
			));

		$response = curl_exec($request);
		$json = explode("\r\n\r\n", $response);
		$json = $json[2];
		$jsonarr = json_decode($json);

		if (curl_getinfo($request, CURLINFO_HTTP_CODE) === 201) {

			$headers = substr($response, 0, curl_getinfo($request, CURLINFO_HEADER_SIZE));
			foreach (explode("\r\n", $headers) as $header) {
				if (substr($header, 0, 10) === "Location: ") {
					$request = curl_init();
					curl_setopt_array($request, array(
						CURLOPT_URL => substr($header, 10),
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_CAINFO => dirname(__FILE__) . '/cacert.pem',
						CURLOPT_SSL_VERIFYPEER => true
						));
					file_put_contents($file, curl_exec($request));
					return $json;
				}
			}
		} else {
			if ($jsonarr->error == 'TooManyRequests') {
				return $json;
			}
			$jerror = Array();
			$jerror['error'][] = curl_error($request);
			$jerror['error'][] = $json;
			return $jerror;
		}
	} 
}
