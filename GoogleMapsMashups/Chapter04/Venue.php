<?php

class Venue {
	// venue city
	public $location;
	// venue name
	public $name;
	// venue latitude
	public $latitude;
	// venue longitude
	public $longitude;

	public function __construct($location, $name, $lat, $lng) {
		$this->location = $location;
		$this->name = $name;
		$this->latitude = $lat;
		$this->longitude = $lng;
	}
}

?>
