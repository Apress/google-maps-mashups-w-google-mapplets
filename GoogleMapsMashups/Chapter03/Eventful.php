<?php

# Simple Eventful API Library
# Requires SimpleXML (php5) and curl

class Eventful {

	// Eventful API URL
	private $api_root = 'http://api.evdb.com/rest/';

	// max results that we want returned from the api
	public $max_results = 5;
	// app key assigned by Eventful
	public $app_key = null;

	public function __construct($app_key) {
		$this->app_key = $app_key;
	}

	// Venue Search API
	public function searchVenue($params) {
		return $this->get("venues/search", $params);
	}

	// Converts XML from API call to SimpleXMLElement object
	private function get($method, $params) {

		// construct the api URL
		$api_url = $this->api_root . $method;

		// set up a few of the default params
		$params['app_key'] = $this->app_key;
		$params['page_size'] = $this->max_results;

		// encode each URL parameter
		$api_params = array();
		foreach ($params as $key => $value) {
			$api_params[] = $key . "=" . urlencode($value);
		}

		// add the parameters to the URL
		$api_url .= "?" . implode('&', $api_params);

		// set up curl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'My Curl API Client (http://mysite.com) ' . phpversion());
		curl_setopt($ch, CURLOPT_URL, $api_url);

		// make the http request and close up curl
		$response = curl_exec($ch);
		curl_close($ch);

		// convert the response SimpleXMLElement object and return it
		if ($response) {
			return simplexml_load_string($response);
		} else {
			// failed to get data from Eventful - return false here
			return false;
		}
	}
}

?>
