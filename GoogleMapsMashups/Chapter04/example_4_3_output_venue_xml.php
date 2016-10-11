<?php

# Tourfilter + Eventful + XML Output
# Requires SimpleXML (php5) and curl

// set the script timeout to "no limit" as it might take a while
set_time_limit(0);

header("Content-type: text/plain");

require('Eventful.php');
require('SimpleCache.php');
require('Venue.php');

// XML directory and filename prefix
$XML_DIR = "xml";
				
$cities = array(
	array (
		"location" => "new york, ny",
		"xml_file_name" => "newyork",
		"url" => "http://www.tourfilter.com/newyork/rss/by_concert_date",
	),
	array (
		"location" => "albuquerque, nm",
		"xml_file_name" => "albuquerque",
		"url" => "http://www.tourfilter.com/albuquerque/rss/by_concert_date",
	),
	array (
		"location" => "asheville, nc",
		"xml_file_name" => "asheville",
		"url" => "http://www.tourfilter.com/asheville/rss/by_concert_date",
	),

	array (
		"location" => "atlanta, ga",
		"xml_file_name" => "atlanta",
		"url" => "http://www.tourfilter.com/atlanta/rss/by_concert_date",
	),
	array (
		"location" => "austin, tx",
		"xml_file_name" => "austin",
		"url" => "http://www.tourfilter.com/austin/rss/by_concert_date",
	),
	array (
		"location" => "baltimore, md",
		"xml_file_name" => "baltimore",
		"url" => "http://www.tourfilter.com/baltimore/rss/by_concert_date",
	),
	array (
		"location" => "boulder, co",
		"xml_file_name" => "boulder",
		"url" => "http://www.tourfilter.com/boulder/rss/by_concert_date",
	),
	array (
		"location" => "buffalo, ny",
		"xml_file_name" => "buffalo",
		"url" => "http://www.tourfilter.com/buffalo/rss/by_concert_date",
	),
	array (
		"location" => "boston, ma",
		"xml_file_name" => "boston",
		"url" => "http://www.tourfilter.com/boston/rss/by_concert_date",
	),
	array (
		"location" => "cincinnati, oh",
		"xml_file_name" => "cincinnati",
		"url" => "http://www.tourfilter.com/cincinnati/rss/by_concert_date",
	),
	array (
		"location" => "cleveland, oh",
		"xml_file_name" => "cleveland",
		"url" => "http://www.tourfilter.com/cleveland/rss/by_concert_date",
	),
	array (
		"location" => "columbus, oh",
		"xml_file_name" => "columbus",
		"url" => "http://www.tourfilter.com/columbus/rss/by_concert_date",
	),
	array (
		"location" => "chicago, il",
		"xml_file_name" => "chicago",
		"url" => "http://www.tourfilter.com/chicago/rss/by_concert_date",
	),
	array (
		"location" => "dallas, tx",
		"xml_file_name" => "dallas",
		"url" => "http://www.tourfilter.com/dallas/rss/by_concert_date",
	),
	array (
		"location" => "denver, co",
		"xml_file_name" => "denver",
		"url" => "http://www.tourfilter.com/denver/rss/by_concert_date",
	),
	array (
		"location" => "dublin, ireland",
		"xml_file_name" => "dublin",
		"url" => "http://www.tourfilter.com/dublin/rss/by_concert_date",
	),
	array (
		"location" => "washington dc",
		"xml_file_name" => "dc",
		"url" => "http://www.tourfilter.com/dc/rss/by_concert_date",
	),
	array (
		"location" => "detroit, mi",
		"xml_file_name" => "detroit",
		"url" => "http://www.tourfilter.com/detroit/rss/by_concert_date",
	),
	array (
		"location" => "el paso, tx",
		"xml_file_name" => "elpaso",
		"url" => "http://www.tourfilter.com/elpaso/rss/by_concert_date",
	),
	array (
		"location" => "fargo, nd",
		"xml_file_name" => "fargo",
		"url" => "http://www.tourfilter.com/fargo/rss/by_concert_date",
	),
	array (
		"location" => "greensboro, nc",
		"xml_file_name" => "greensboro",
		"url" => "http://www.tourfilter.com/greensboro/rss/by_concert_date",
	),
	array (
		"location" => "houston, tx",
		"xml_file_name" => "houston",
		"url" => "http://www.tourfilter.com/houston/rss/by_concert_date",
	),
	array (
		"location" => "honolulu, hi",
		"xml_file_name" => "honolulu",
		"url" => "http://www.tourfilter.com/honolulu/rss/by_concert_date",
	),
	array (
		"location" => "indianapolis, in",
		"xml_file_name" => "indianapolis",
		"url" => "http://www.tourfilter.com/indianapolis/rss/by_concert_date",
	),
	array (
		"location" => "jacksonville, fl",
		"xml_file_name" => "jacksonville",
		"url" => "http://www.tourfilter.com/jacksonville/rss/by_concert_date",
	),
	array (
		"location" => "kansas city, mo",
		"xml_file_name" => "kansas city",
		"url" => "http://www.tourfilter.com/kansascity/rss/by_concert_date",
	),
	array (
		"location" => "london, united kingdom",
		"xml_file_name" => "london",
		"url" => "http://www.tourfilter.com/london/rss/by_concert_date",
	),
	array (
		"location" => "los angeles, ca",
		"xml_file_name" => "losangeles",
		"url" => "http://www.tourfilter.com/losangeles/rss/by_concert_date",
	),
	array (
		"location" => "las vegas, nv",
		"xml_file_name" => "lasvegas",
		"url" => "http://www.tourfilter.com/lasvegas/rss/by_concert_date",
	),
	array (
		"location" => "louisville, ky",
		"xml_file_name" => "louisville",
		"url" => "http://www.tourfilter.com/louisville/rss/by_concert_date",
	),
	array (
		"location" => "melbourne, australia",
		"xml_file_name" => "melbourne",
		"url" => "http://www.tourfilter.com/melbourne/rss/by_concert_date",
	),
	array (
		"location" => "madison, wi",
		"xml_file_name" => "madison",
		"url" => "http://www.tourfilter.com/madison/rss/by_concert_date",
	),
	array (
		"location" => "memphis, tn",
		"xml_file_name" => "memphis",
		"url" => "http://www.tourfilter.com/memphis/rss/by_concert_date",
	),
	array (
		"location" => "miami, fl",
		"xml_file_name" => "miami",
		"url" => "http://www.tourfilter.com/miami/rss/by_concert_date",
	),
	array (
		"location" => "milwaukee, wi",
		"xml_file_name" => "milwaukee",
		"url" => "http://www.tourfilter.com/milwaukee/rss/by_concert_date",
	),
	array (
		"location" => "nashville, tn",
		"xml_file_name" => "nashville",
		"url" => "http://www.tourfilter.com/nashville/rss/by_concert_date",
	),
	array (
		"location" => "new orleans, la",
		"xml_file_name" => "neworleans",
		"url" => "http://www.tourfilter.com/neworleans/rss/by_concert_date",
	),
	array (
		"location" => "oklahoma city, ok",
		"xml_file_name" => "oklahomacity",
		"url" => "http://www.tourfilter.com/oklahomacity/rss/by_concert_date",
	),
	array (
		"location" => "omaha, nb",
		"xml_file_name" => "omaha",
		"url" => "http://www.tourfilter.com/omaha/rss/by_concert_date",
	),
	array (
		"location" => "orlando, fl",
		"xml_file_name" => "orlando",
		"url" => "http://www.tourfilter.com/orlando/rss/by_concert_date",
	),
	array (
		"location" => "phoenix, ar",
		"xml_file_name" => "phoenix",
		"url" => "http://www.tourfilter.com/phoenix/rss/by_concert_date",
	),
	array (
		"location" => "pittsburgh, pa",
		"xml_file_name" => "pittsburgh",
		"url" => "http://www.tourfilter.com/pittsburgh/rss/by_concert_date",
	),
	array (
		"location" => "philadelphia, pa",
		"xml_file_name" => "philadelphia",
		"url" => "http://www.tourfilter.com/philadelphia/rss/by_concert_date",
	),
	array (
		"location" => "portland, or",
		"xml_file_name" => "portland",
		"url" => "http://www.tourfilter.com/portland/rss/by_concert_date",
	),
	array (
		"location" => "providence, ri",
		"xml_file_name" => "providence",
		"url" => "http://www.tourfilter.com/providence/rss/by_concert_date",
	),
	array (
		"location" => "raleigh-durham, nc",
		"xml_file_name" => "raleighdurham",
		"url" => "http://www.tourfilter.com/raleighdurham/rss/by_concert_date",
	),
	array (
		"location" => "rochester, ny",
		"xml_file_name" => "rochester",
		"url" => "http://www.tourfilter.com/rochester/rss/by_concert_date",
	),
	array (
		"location" => "seattle, wa",
		"xml_file_name" => "seattle",
		"url" => "http://www.tourfilter.com/seattle/rss/by_concert_date",
	),
	array (
		"location" => "st louis, mo",
		"xml_file_name" => "stlouis",
		"url" => "http://www.tourfilter.com/stlouis/rss/by_concert_date",
	),
	array (
		"location" => "san antonio, tx",
		"xml_file_name" => "sanantonio",
		"url" => "http://www.tourfilter.com/sanantonio/rss/by_concert_date",
	),
	array (
		"location" => "san diego, ca",
		"xml_file_name" => "sandiego",
		"url" => "http://www.tourfilter.com/sandiego/rss/by_concert_date",
	),
	array (
		"location" => "san francisco, ca",
		"xml_file_name" => "sf",
		"url" => "http://www.tourfilter.com/sf/rss/by_concert_date",
	),
	array (
		"location" => "toronto, ontario, canada",
		"xml_file_name" => "toronto",
		"url" => "http://www.tourfilter.com/toronto/rss/by_concert_date",
	),
	array (
		"location" => "tucson, ar",
		"xml_file_name" => "tucson",
		"url" => "http://www.tourfilter.com/tucson/rss/by_concert_date",
	),	
	array (
		"location" => "tulsa, ok",
		"xml_file_name" => "tulsa",
		"url" => "http://www.tourfilter.com/tulsa/rss/by_concert_date",
	),
	array (
		"location" => "minneapolis, mn",
		"xml_file_name" => "twincities",
		"url" => "http://www.tourfilter.com/twincities/rss/by_concert_date",
	),
	array (
		"location" => "vancouver, british columbia, canada",
		"xml_file_name" => "vancouver",
		"url" => "http://www.tourfilter.com/vancouver/rss/by_concert_date",
	),
	array (
		"location" => "virginia beach, va",
		"xml_file_name" => "virginiabeach",
		"url" => "http://www.tourfilter.com/virginiabeach/rss/by_concert_date",
	),
	array (
		"location" => "wichita, ks",
		"xml_file_name" => "wichita",
		"url" => "http://www.tourfilter.com/wichita/rss/by_concert_date",
	),
);

// set up the Eventful object
$app_key = 'your_app_key_goes_here';
$ev = new Eventful($app_key);

// set up the SimpleCache object
$cache = new SimpleCache();
// flush everything that's older than a week (in seconds)
$cache->flush(60*60*24);

foreach ($cities as $city) {

	$location = $city["location"];
	$url = $city["url"];
	$xml_file_name = $city["xml_file_name"];

	// start off the xml
	$xml = array('<?xml version="1.0" encoding="UTF-8"?>');
	$xml[] = '<markers>';

	// set up curl
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_HEADER, false); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch, CURLOPT_USERAGENT, 'My Curl API Client (http://mysite.com) ' . phpversion());
	curl_setopt($ch, CURLOPT_URL, $url);

	print "Retrieving data from $url...\n";
	
	// make the http request and close up curl
	$response = curl_exec($ch);
	curl_close($ch);

	// make sure we get a response
	if (!$response) {
		print "Error: we didn't get any data from $url\n";
		continue;
	}

	// parse the response
	$sxml = simplexml_load_string($response); 
	if (!$sxml) {
		print "Error: failed to parse XML from $url\n";
		continue;
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
			if ($cached_venue) {
				// found the venue in the cache - create the XML for it
				$xml[] = createMarkerXML($band, $venue, $date, (string)$cached_venue->latitude, (string)$cached_venue->longitude);
			} else {
				print "We don't have valid data in the cache for $venue\n";	
			}
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
				$cache->set($cache_key, null);
			} else {
				// no venues found in the XML
				print "Error: falied to retrieve location for venue $venue using parameters: " . join(",", array_values($params)) . "\n";
				$cache->set($cache_key, null);
			}
		} else {
			// failed to get a valid XML response from Eventful
			print "Error: falied to get XML data from Eventful using parameters: " . join(",", array_values($params)) . "\n";
			$cache->set($cache_key, null);
		}
	}
	
	// close up the markers in the XML 
	$xml[] = '</markers>';
	
	// put all of the XML output into a single string	
	$xml_output = join("\n", $xml);
	
	// write the xml data to a local file
	$xml_path = $XML_DIR . "/" . $xml_file_name . ".xml";
print "writting to $xml_path...\n";
	$xml_file = fopen($xml_path, "w"); 
	if ($xml_file) {
		// write the XML to the file and close it up
		fputs($xml_file, $xml_output);
		fclose($xml_file);
	} else {
		print "Error: failed to open XML file $xml_path for writing XML data\n";
		continue;
	}
	
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

