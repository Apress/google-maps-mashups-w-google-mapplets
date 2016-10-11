<?php

# Parse Tourfilter's RSS Feed of New York Concerts
# Requires SimpleXML (php5) and curl

header("Content-type: text/plain");

$url = "http://www.tourfilter.com/newyork/rss/by_concert_date";

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
		// this may signal a change to the format of the title field!
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
		// this could mean the date was missing or the format has changed!
		print "Warn: we didn't find the venue and date in '$venue_date'\n";
		$venue = $venue_date;
	}

	// for now, just print out what we found
	print "title: $title\n";
	print "band:'$band'\n";
	print "venue: '$venue'\n";
	print "date: '$date'\n\n";

}

?>
