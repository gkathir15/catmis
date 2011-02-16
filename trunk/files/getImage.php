<?
// Include common functions and declarations
include "../include/common.php";

// Get parameters
$path = getValue("path");
$width = getValue("width");
$height = getValue("height");
$autoCorrect = getValue("autoCorrect");

if (!empty($path)) {
	// Output header
	header("Content-type: image/jpeg");

	// Get source file
	$sourcefile = scriptPath."/".folderUploadedFiles."/".$path;
	if (file_exists($sourcefile)) {	
		$dimensions = getImageDimensions($sourcefile);
		
		if ($autoCorrect) {
			if ($dimensions[0]<$dimensions[1]) {
				if (!empty($width)) {
					$height = $width;
					$width = 0;
				}
			}
		}

		if (empty($width)) { // Scale according to height
			$ratio = $height/$dimensions[1];
			$width = $dimensions[0]*$ratio;				
		}
		if (empty($height)) { // Scale according to width
			$ratio = $width/$dimensions[0];
			$height = $dimensions[1]*$ratio;
		}
		
		// Round values
		$width = round($width,0);
		$height = round($height,0);	
		
		// Get the dimensions of the source picture
		$source_x = $dimensions[0];
		$source_y  = $dimensions[1];
		
		// Create a new image object (not neccessarily true colour)
		$image = @imagecreatefromjpeg($sourcefile); 
		$newImage = @imagecreatetruecolor($width, $height);
	
		// Resize the original picture and copy it into the just created image
		// object. Because of the lack of space I had to wrap the parameters to
		// several lines. I recommend putting them in one line in order keep your
		// code clean and readable.
		@imagecopyresampled($newImage,$image,0,0,0,0,$width,$height,$source_x,$source_y);
	
		// Create a jpeg with the quality of "$jpegqual" out of the
		// image object "$target_pic". This will be saved as $targetfile.
		@imagejpeg ($newImage,"",100);
		
		// Free memory
		@imagedestroy($newImage);
		@imagedestroy($image);
	}
}
?>