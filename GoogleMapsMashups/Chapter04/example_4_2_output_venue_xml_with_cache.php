<?php

# Tourfilter + Eventful + XML Output
# Requires SimpleXML (php5) and curl

// set the script timeout to "no limit" as it might take a while
set_time_limit(0);

header("Content-type: text/plain");

require('Eventful.php');
require('SimpleCache.php');
require('Venue.php');

$url = "http://www.tourfilter.com/newyork/rss/by_concert_date";
$location = "new york";

// XML directory and filename prefix
$XML_DIR = "xml";
$xml_file_prefix = "newyork";

// set up the Eventful object
$app_key = 'your_app_key_goes_here';
$ev = new Eventful($app_key);

// set up the SimpleCache object
$cache = new SimpleCache();
// flush everything that's older than a day (in seconds)
$cache->flush(60*60*24);

// start off the xml
$xml = array('<?xml version="1.0" encoding="UTF-8"?>');
$xml[] = '<markers>';

// set up curl
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_HEADER, false); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_USERAGENT, 'My Curl API Client (http://mysite.com) ' . phpversion());
curl_setopt($ch, CURLOPT_URL, $url);

// make the http request and close up curl
$response = curl_exec($ch);
curl_close($ch);

// make sure we get a response
if (!$response) {
	die("Error: we didn't get any data from $url\n");
}

// parse the response
$sxml = simplexml_load_string($response); 
if (!$sxml) {
	die("Error: failed to parse XML from $url\n");
}

// loop through each <item> found in the RSS feed 
foreach ($sxml->channel->item as $item) { 

	// trim the whitespace from the title
	$title = trim($item->title);

	// clear these out
	$band = "";
	$venue = "";
	$date = "";

	// parse the band and venue/date fields from the title
	preg_match("/^(.*)[\s]*\((.*)\)$/", $title, $matches);
	if (count($matches) != 3) {
		// error - not the correct number of fields
		// you can log this so you can see where the parsing failed
		// this may signal a change to the format of the title field
		print "Error: title '$title' isn't of the format we're expecting\n";
		continue;
	}
      
	// grab the band and venue/date fields from our "matches" array 
	$band = trim($matches[1]);
	$venue_date = trim($matches[2]);
     
	// parse the venue and date from the venue/date string we just parsed 
	preg_match("/^(.*)\s([\d]{1,2}\/[\d]{1,2})$/", $venue_date, $venue_date_matches);
	if (count($venue_date_matches) == 3) {
		// parsing was successful
		$venue = $venue_date_matches[1];
		$date = $venue_date_matches[2];
	} else {
		// venue/date were not in the format we were expecting but we'll move on
		// this could mean the date was missing or the format has changed	
		// you can log this so you can see where the parsing failed
		print "Warn: we didn't find the venue and date in '$title'\n";
		$venue = $venue_date;
	}

	// look to see if we already have the venue in our cache
	// create the cache key
	$cache_key = $location . "_" . $venue;
	$cached_venue = $cache->get($cache_key);
	if ($cached_venue !== FALSE) {
		print "Found $venue in cache\n";
		// found the venue in the cache - create the XML for it
		$xml[] = createMarkerXML($band, $venue, $date, (string)$cached_venue->latitude, (string)$cached_venue->longitude);
		continue;
	} else {
		print "Didn't find $venue in cache\n";
	}

	// we haven't looked up this venue, so we'll do that now
	// build our parameter list, using the venue we just parsed 
	$params = array(
		'keywords' => $venue,
		'location' => $location,
		'within' => 20,
	);

	// search for our venue
	$sxml = $ev->searchVenue($params);
	if ($sxml) {
		
		// make sure we have at least one venue in the XML	
		if (count($sxml->venues->venue) > 0) {

			// grab the first venue in the XML and print out the lat/lng
			$v = $sxml->venues->venue[0];

			// add the location to our XML output array
			$xml[] = createMarkerXML($band, $venue, $date, (string)$v->latitude, (string)$v->longitude);
			
			// store the venue in the cache
			$new_venue = new Venue($location, $venue, (string)$v->latitude, (string)$v->longitude);
			$cache->set($cache_key, $new_venue);

		} else if ($sxml->description) {
			// no venues found in the XML
			// if the description is set in the XML, let's print it out to help debug
			print "Error: failed to retrieve location for $venue: " . $sxml["string"] . ". " . $sxml->description . "\n";
		} else {
			// no venues found in the XML
			print "Error: falied to retrieve location for venue $venue using parameters: " . join(",", array_values($params)) . "\n";
		}
	} else {
		// failed to get a valid XML response from Eventful
		print "Error: falied to get XML data from Eventful using parameters: " . join(",", array_values($params)) . "\n";
	}
}

// close up the markers in the XML 
$xml[] = '</markers>';

// put all of the XML output into a single string	
$xml_output = join("\n", $xml);

// write the xml data to a local file
$xml_path = $XML_DIR . "/" . $xml_file_prefix . ".xml";
$xml_file = fopen($xml_path, "w"); 
if ($xml_file) {
	// write the XML to the file and close it up
	fputs($xml_file, $xml_output);
	fclose($xml_file);
} else {
	die("Error: failed to open XML file $xml_path for writing XML data\n");
}


function createMarkerXML($band, $venue, $date, $lat, $lng) {

	// create the marker XML
	$marker = ' <marker ';
	$marker .= 'band="' . htmlentities($band) . '" ';
	$marker .= 'venue="' . htmlentities($venue) . '" ';
	$marker .= 'date="' . htmlentities($date) . '" ';
	$marker .= 'lat="' . $lat . '" ';
	$marker .= 'lng="' . $lng . '" ';
	$marker .= '/>';

	return $marker;
}

?>

