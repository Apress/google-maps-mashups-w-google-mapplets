<?php

# Simple File Cache
# Requires php5

class SimpleCache {

	private $cache_dir = "cache/";
	private $cache_ext = ".cache";

	public function __construct() {
	}

	private function getCacheFileName($key) {
		$cache_file = $this->cache_dir . md5($key) . $this->cache_ext;
		return $cache_file;
	}

	public function flush($expiration = 0) {

		// loop through all of the files in the cache directory
		if ($handle = opendir($this->cache_dir)) {
			while (false !== ($file = readdir($handle))) {
				// make sure we skip "." and ".." - the directories
				if ($file != '.' and $file != '..') {
					// only delete files that match our cache extension
					$file_ext = strrchr($file, '.');
					if ($file_ext == $this->cache_ext) {
						// find out when the cache file was last modified
						$mtime = filemtime($this->cache_dir . '/' . $file);
						if ((time() - $mtime) > $expiration) {
							// delete the files since it has "expired"
							unlink($this->cache_dir . '/' . $file);
						}
					} 
				}
			}
			closedir($handle);
		}
	}

	public function delete($key) {
		$cache_file = $this->getCacheFileName($key);
		return unlink($cache_file);
	}

	function get($key) {
		$cache_file = $this->getCacheFileName($key);

		if (file_exists($cache_file)) {
			// let's read the object from the file
			$cache_data = file_get_contents($cache_file);
			$cache_object = unserialize($cache_data);

			// if the file_get_contents() or unserialize() failed, 
			// $cache_object will have a value of false

			return $cache_object;
		} else {
			// we didn't find the cache file
			return false;
		}
	}

	function set($key, $var) {

		// get the cache file name
		$cache_file = $this->getCacheFileName($key);

		// serialize the venue object 
		// and store it in our cache file
		$s = serialize($var);
		$fp = fopen($cache_file, "w");
		if (!$fp) {
			print "Error: Failed to open cache file. Make sure dir/file have write permissions: $cache_file\n";
			return false;
		}
	
		// write the serialized object to the file
		if (fwrite($fp, $s) === FALSE) {
			print "Error: Failed to write the data to the cache file $cache_file\n";
			return false;	
		}
	
		// close up the file
		fclose($fp);
	
		return true;
	}
}

?>
