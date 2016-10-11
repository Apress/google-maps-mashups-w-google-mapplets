<?php

# Eventful Venue Lookup
# Requires SimpleXML (php5) and curl

header("Content-type: text/plain");

require('Eventful.php');

$app_key = 'your app key goes here';
// create an instance of our Eventful library
$ev = new Eventful($app_key);

// create our search parameters
$params = array(
	'keywords' => "knitting factory",
	'location' => "new york",
	'within'   => 20,
);

// call the search API
$sxml = $ev->searchVenue($params);

// list the venues returned from the API
foreach ($sxml->venues->venue as $v) {
	print "venue name: " . $v->venue_name . "\n";
	print "latitude:   " . $v->latitude . "\n";
	print "longitude:  " . $v->longitude . "\n";
}

?>
