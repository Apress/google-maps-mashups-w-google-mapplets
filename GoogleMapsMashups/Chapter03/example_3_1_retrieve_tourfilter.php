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

// parse the response - no error checking for now
$sxml = simplexml_load_string($response); 

// loop through each <item> found in the RSS feed 
foreach ($sxml->channel->item as $item) { 
	print "title: " . $item->title . "\n";
	print "pub date: " . $item->pubDate . "\n";
	print "link: " . $item->link . "\n";
	print "guid: " . $item->guid . "\n\n";
}

?>
