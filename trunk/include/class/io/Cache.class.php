<?
/**
 * The class Cache handles caching of files in the system. It can also read cached content and 
 * generate thumbnail images.
 * @author	Kaspar Rosengreen Nielsen
 */
class Cache {
	/**
	 * Cache given content to a given filename in the cache.
	 * @param	$dir		Directory to cache to.
	 * @param	$filename	Filename to cache to.
	 * @param	$content	Content to write to file.
	 * @param	$extension	Extension to use - default txt
	 */
	function cacheFile($dir, $filename, $content, $extension="txt") {
		global $settings;
		
		// Validate directory
		if (!empty($dir)) {
			if (is_dir(cachePath."/".$dir)) {
				if (!is_writable(cachePath."/".$dir)) {
					$dir = "";
				}
			}
			else {
				$dir = "";
			}
		}

		// Write to file
		$fp = fopen(cachePath.(!empty($dir)?"/".$dir:"")."/".$filename.".".$extension, 'w');
		fwrite($fp, $content);
		fclose($fp);
	}
	
	/**
	 * Clean cache sorted by age. If the cache exceeds the size specified in the page 
	 * settings oldest files are deleted first.
	 * @param	$dir	Directory to start cleaning.
	 */
	function cleanCache($dir="") {	
		global $settings;
		
		// Clear cached files if necessary
		$cacheFiles = $this->getCacheFiles();

	    if (sizeof($cacheFiles)>0) {
		    // Sort by age
		    sort($cacheFiles);

			// Remove cache files
			$cacheSize = 0;
		    for ($i=0; $i<sizeof($cacheFiles); $i++) {
				$cacheSize += @filesize($cacheFiles[$i][1])/1024;
				if ($cacheSize>$settings->cacheSize || !$settings->enableCaching) {
					if (@is_writable($cacheFiles[$i][1])) {
			        	@unlink($cacheFiles[$i][1]);
					}
				}
		    }
	    }
	}
	
	/**
	 * Create a new directory in the cache.
	 * @param	$name	Name of new directory.
	 */
	function createCacheDirectory($name) {
		if (!is_dir(cachePath."/".$name)) {
			if (is_writable(cachePath)) {
				mkdir(cachePath."/".$name, 0755);
			}	
		}			
	}
	
	/**
	 * Delete a file in the cache.
	 * @param	$dir		Directory the file is located in.
	 * @param	$filename	Name of file to delete.
	 */
	function deleteCacheFile($dir, $filename) {
		// Validate directory
		if (!empty($dir)) {
			if (is_dir(cachePath."/".$dir)) {
				if (!is_writable(cachePath."/".$dir)) {
					$dir = "";
				}
			}
			else {
				$dir = "";
			}
		}

		if (file_exists(cachePath.(!empty($dir)?"/".$dir:"")."/".$filename.".txt")) {
			if (is_writable(cachePath.(!empty($dir)?"/".$dir:"")."/".$filename.".txt")) {
				unlink(cachePath.(!empty($dir)?"/".$dir:"")."/".$filename.".txt");
			}	
		}		
	}
	
	/**
	 * Generate a thumbnail for a given image file.
	 * @param	$cacheDir		Directory file is located in.
	 * @param	$path			Path to image file relative to scriptUrl.
	 * @param	$name			Name of file.
	 * @param	$width			Desired width of thumbnail.
	 * @param	$height			Desired height of thumbnail.
	 * @param	$returnDefault	Return default image if image could not be generated.
	 * @param	$addBackground	Add background to make the image fit desired dimensions.
	 * @param	$crop			Crop image to fit desired dimensions.
	 * @param	$blackAndWhite	Black and white image.
	 * @return URL to thumbnail.
	 */
	function generateThumbnail($cacheDir, $path, $name, $width, $height=0, $returnDefault=true, $addBackground=false, $crop=false, $blackAndWhite=false) {
		global $settings;
		
		// Cache in news directory in cache if present
		if (!is_dir(cachePath."/".$cacheDir)) $cacheDir = "";
		
		// Set source file name
		$sourceFile = file_exists(scriptPath."/".$path) ? scriptPath."/".$path : ($returnDefault ? iconPath."/default.jpg" : "");

		if (!empty($sourceFile)) {
			if (!$settings->enableCaching) {
				return scriptUrl."/".$path;
			}
			
			if (!empty($width) || !empty($height)) {
				$dimensions = getImageDimensions($sourceFile);
				if (!$addBackground && !$crop) {
					if (!empty($width)) {
						$ratio = $width/$dimensions[0];
						$height = $dimensions[1]*$ratio;
					}
					else if (!empty($height)) {
						$ratio = $height/$dimensions[1];
						$width = $dimensions[0]*$ratio;
					}
				}
		
				// Round values
				$width = floor($width);
				$height = floor($height);
				if (empty($height)) $height = $width;
				
				// Set target file name
				$targetFile = cachePath.(!empty($cacheDir)?"/".$cacheDir:"")."/".$name.".".$width."_".$height.($addBackground ? "_background" : ($crop ? "_crop" : "")).($blackAndWhite ? "_bw" : "").".jpg";
				
				// Check if file has changed
				$resize = false;
				if (!file_exists($targetFile)) {
					$resize = true;
				}
				else if (filemtime($targetFile)<filemtime($sourceFile)) {
					$resize = true;
				}

				// Resize file
				if ($resize) {
					resizeToFile($sourceFile, $width, $height, $targetFile, 100, $addBackground, $crop, $blackAndWhite);
				}			
				return cacheUrl.(!empty($cacheDir)?"/".$cacheDir:"")."/".$name.".".$width."_".$height.($addBackground ? "_background" : ($crop ? "_crop" : "")).($blackAndWhite ? "_bw" : "").".jpg";
			}
			return iconUrl."/picture5050.gif";
		}
		return "";
	}
	
	/**
	 * Get content from a given cache file.
	 * @param	$dir		Directory cache file is located in.
	 * @param	$filename	Name of cache file.
	 * @param	$timeout	If file is older than a given timeout don't return content.
	 * @param	$extension	Extension to use - default txt
	 * @return content of the cache file.
	 */
	function getCacheFileContent($dir, $filename, $timeout=3600, $extension="txt") {
		global $settings;

		// Validate directory
		if (!empty($dir)) {
			if (is_dir(cachePath."/".$dir)) {
				if (!is_writable(cachePath."/".$dir)) {
					$dir = "";
				}
			}
			else {
				$dir = "";
			}
		}
		
		$cache = cachePath.(!empty($dir)?"/".$dir:"")."/".$filename.".".$extension;
		if (file_exists($cache)) {
			// Get last modified time		
			$mtime = filemtime($cache);
			$age = time()-$mtime;
			
			// Read content
			if ($age<=$timeout || !$settings->enableCaching) {
				return file_get_contents($cache);
			}
		}
		return "";	
	}
	
	/**
	 * Get list of cache files in a given directory recursively.
	 * @param	$dir			Directory to get cache files from.
	 * @param	$cacheFiles		List of cache files to extend further.
	 * @return a list of cache files.
	 */
	function getCacheFiles($dir="", $cacheFiles=array()) {
		if (empty($dir)) $dir = cachePath;
		if ($handle = opendir($dir)) {
		    while (false !== ($file = readdir($handle))) {
		    	if ($file!="." && $file!="..") {
		    		if (is_dir($dir."/".$file)) {
		    			$cacheFiles = $this->getCacheFiles($dir."/".$file, $cacheFiles);
		    		}
		    		else {
				    	$cache = $dir."/".$file;
			    		$length = sizeof($cacheFiles);
						$mtime = @filemtime($cache);
						$age = time()-$mtime;
						$cacheFiles[$length][0] = $age;
						$cacheFiles[$length][1] = $cache;
		    		}
		    	}
		    }
		    closedir($handle);
		}
		return $cacheFiles;
	}
}
?>