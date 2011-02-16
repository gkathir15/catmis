<?
/**
 * Class File represents a file on the webserver. The class contains methods for
 * uploading, renaming, moving and deleting files.
 * @author	Kaspar Rosengreen Nielsen
 */
class File {
	var $id = 0;
	var $name = "";
	var $parent = null;
	var $size = 0;
	var $type = "";
	
	/** 
	 * File constructor
	 * @param 	$id 	File identifier.
	 */
	function File($id=0) {
		if (!empty($id)) {
			global $dbi;
			
			// Fetch file data from database
			$result = $dbi->query("SELECT id,name,type,size,folderId FROM ".fileTableName." WHERE id=".$dbi->quote($id));
			if($result->rows()) {
				list($this->id,$this->name,$this->type,$this->size,$folderId) = $result->fetchrow_array();
				
				// Create parent folder object
				$this->parent = new Folder($folderId);	
			}
		}
	}

	/** Delete this file. */
	function deleteFile() {
		if(!empty($this->id)) {
			global $dbi;

			// Delete file
			if(file_exists(scriptPath."/".folderUploadedFiles."/".$this->id.".".getFileExtension($this->name))) unlink(filePath."/".$this->id.".".getFileExtension($this->name));
			
			// Delete from database
			$dbi->query("DELETE FROM ".fileTableName." WHERE id=".$dbi->quote($this->id));
		}
	}
	
	/**
	 * Determine if a given filename already exists within a folder
	 * @param $filename Filename to search for
	 * @param $folderId Folder to look in
	 * @return true if file exists
	 */
	function fileExists($filename,$folderId) {
		global $dbi;
		
		$result = $dbi->query("SELECT id FROM ".fileTableName." WHERE name=".$dbi->quote($filename)." AND folderId=".$dbi->quote($folderId));
		if($result->rows()) return true;
		return false;
	}

	/**
	  * Get file extension for database resource.
	  * @param 	$fileId 	Id of file in database to get extension from.
	  */
	function getDatabaseFileExtension($fileId) {
		global $dbi;
	
		$result = $dbi->query("SELECT name FROM ".fileTableName." WHERE id=".$dbi->quote($fileId));
		if($result->rows()) {
			list($fileName) = $result->fetchrow_array();
			return convertToLowercase(getFileExtension($fileName));
		}
		return "";
	}

	/**
	 * Get file extension of filename
	 * @param $name Filename to get extension from
	 * @return File extension
	 */
	function getFileExtension($name="") {
		if(empty($name)) $name = $this->name;
		return substr(strrchr($name,"."), 1);	
	}

	/**
	 * Get filename without extension
	 * @param $name Filename to get filename from
	 * @return Filename
	 */
	function getFilename($name="") {
		if(empty($name)) $name = $this->name;
		return str_replace(".".$this->getFileExtension($name),"",$name);		
	}

	/**
	 * Get type icon for the current file.
	 * @return url to icon.
	 */
	function getIconUrl() {
		$extension = convertToLowercase($this->getFileExtension());
		if (!file_exists(scriptPath."/".folderUploadedFiles."/".$this->id.".".$this->getFileExtension($this->name))) {
			return iconUrl."/warning.gif";			
		}
		else if ($extension=="bmp" || $extension=="gif" || $extension=="jpg" || $extension=="png") {
			return iconUrl."/image.gif";
		}		
		else if ($extension=="mp3") {
			return iconUrl."/audio.gif";	
		}
		else if ($extension=="txt" || $extension=="rtf" || $extension=="doc" || $extension=="pdf" || $extension=="odt") {
			return iconUrl."/document.gif";
		}
		return iconUrl."/page.gif";	
	}

	/** 
	 * Get the time when this file was last modified 
	 * @return time of last modified
	 */
	function getLastModified($id="") {
		if (empty($id)) $id = $this->id; 
		if (!empty($id)) {
			// Get last modified on disk
			if (file_exists(scriptPath."/".folderUploadedFiles."/".$this->id.".".$this->getFileExtension($this->name))) {
				return filemtime(scriptPath."/".folderUploadedFiles."/".$this->id.".".$this->getFileExtension($this->name));			
			}
		}
		return 0;
	}
	
	/** 
	 * Get path to this file.
	 * @return string containing path to string.
	 */
	function getPath() {
		global $dbi;
		$folderPath = $this->parent->getPath();
		return $folderPath.($folderPath!="/"?"/":"").$this->name;
	}

	/** 
	 * Get size of this file in bytes 
	 * @param	$id		File identifier.
	 * @return size of this file in bytes
	 */
	function getSize($id="") {
		if (empty($id)) $id = $this->id;
		if (!empty($id)) {
			if (file_exists(scriptPath."/".folderUploadedFiles."/".$this->id.".".$this->getFileExtension($this->name))) {
				return filesize(scriptPath."/".folderUploadedFiles."/".$this->id.".".$this->getFileExtension($this->name));
			}
		}
		return 0;
	}

	/**
	  * Is the given filetype supported?
	  * @param	$file		List of file attributes.
	  * @param	$filetypes	List of allowed file types.
	  * @return true if file type is allowed, false otherwise. 
	  */
	function isFiletypeSupported($file, $filetypes=array()) {
		if (!empty($file)) {
			if (!empty($file["name"]) && !empty($file["type"])) {
				$extension = $this->getFileExtension($file["name"]);
				if (sizeof($filetypes)>0) {
					// Checks if file is an allowed filetype
					$filetypeAllowed = false;
					for ($i=0; $i<sizeof($filetypes); $i++) {
						if ($file["type"]==$filetypes[$i][1] && $extension==$filetypes[$i][0]) $filetypeAllowed = true;
					}
					return $filetypeAllowed;
				}
				else if (($file["type"]=="image/jpeg" && $extension=="jpg") || 
						 ($file["type"]=="image/pjpeg" && $extension=="jpg") || 
 						 ($file["type"]=="image/jpeg" && $extension=="jpeg") || 
						 ($file["type"]=="image/pjpeg" && $extension=="jpeg") ||
						 ($file["type"]=="image/gif" && $extension=="gif") || 
						 ($file["type"]=="image/png" && $extension=="png")) {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Move this file to another folder
	 * @param $folderId Id of folder to move to
	 */
	function moveFile($folderId) {
		if(!empty($this->id)) {
			global $dbi;

			/* Update parent folder in database */			
			$dbi->query("UPDATE ".fileTableName." SET folderId=".$dbi->quote($folderId)." WHERE id=".$this->id);

			/* Log transaction */
			logTransaction("file",$this->id);
		}
	}

	/** 
	 * Rename this file
	 * @param $name Name to rename to
	 */
	function renameFile($name) {
		if(!empty($this->id)) {
			global $dbi;

			/* Update filename in database */
			$dbi->query("UPDATE ".fileTableName." SET name=".$dbi->quote($name)." WHERE id=".$this->id);

			/* Log transaction */
			logTransaction("file",$this->id);	
		}
	}
	
	/** 
	  * resizeImage resizes an image and writes it to the harddisk
	  * @param	$sourcefile 	The filename of the picture that is going to be resized
	  * @param 	$dest_x 		X-Size of the target picture in pixels
	  * @param	$dest_y			Y-Size of the target picture in pixels
	  * @param	$targetfile 	The name under which the resized picture will be stored
	  * @param	$jpegqual   	The Compression-Rate that is to be used
	  * @param	$addBackground	Add background to image.
	  * @param	$crop			Crop image to fit desired dimensions.
	  * @param	$blackAndWhite	Black and white image.
	  * @return true if resize was successfull, false otherwise.
	  */
	function resizeImage($sourcefile, $width, $height, $targetfile, $jpegqual, $addBackground=false, $crop=false, $blackAndWhite = false) {
		if (empty($height)) $height = $width;

		// Get the dimensions of the source picture
		$dimensions = getImageDimensions($sourcefile);		
		$source_x = $dimensions[0];
		$source_y  = $dimensions[1];
		
		// Create a new image object by type (not neccessarily true colour)
		$source_id = null;
		$type = $dimensions["mime"];
		switch ($type) {
			case "image/jpeg":
				$source_id = @imageCreateFromJPEG("$sourcefile"); 
				break;
			case "image/png":
				$source_id = @imageCreateFromPNG("$sourcefile"); 
				break;
			case "image/gif":
				$source_id = @imageCreateFromGIF("$sourcefile"); 
				break;
		}
		
		// Create target image
		$target_id = null;
		if ($addBackground) {
			$target_ratio = $width / $height;
			$img_ratio = $source_x / $source_y;
			if ($target_ratio > $img_ratio) {
				$new_height = $height;
				$new_width = $img_ratio * $height;
			} 
			else {
				$new_height = $width / $img_ratio;
				$new_width = $width;
			}

			if ($new_height > $height) {
				$new_height = $height;
			}
			if ($new_width > $width) {
				$new_height = $width;
			}
			$target_id = @imagecreatetruecolor($width, $height);
			@imagefilledrectangle($target_id, 0, 0, $width-1, $height-1, 0); // Fill the image black
			@imagecopyresampled($target_id, $source_id, ($width-$new_width)/2, ($height-$new_height)/2, 0, 0, $new_width, $new_height, $source_x, $source_y);
		}
		else if ($crop) {
	        // We're always going to crop from center
			if ($source_x < $source_y) {
				$cropx = 0;
				$cropy = ($source_y - $source_x)/2;
				$source_y = $source_x;
			}
			else {
				$cropx = ($source_x - $source_y)/2;
				$cropy = 0;
				$source_x = $source_y;
			}

			// Create image
			$target_id = @imagecreatetruecolor($width, $height);
			@imagefilledrectangle($target_id, 0, 0, $width-1, $height-1, 0); // Fill the image black
			@imagecopyresampled($target_id, $source_id, 0, 0, $cropx, $cropy, $width, $height, $source_x, $source_y);
		}
		else {
			$imageWidth = $width;
			$imageHeight = $height;

			// Calculate image ratio
			if (!empty($imageWidth)) {
				$ratio = $imageWidth/$dimensions[0];
				$imageHeight = $dimensions[1]*$ratio;
			}
			else if (!empty($imageHeight)) {
				$ratio = $imageHeight/$dimensions[1];
				$imageWidth = $dimensions[0]*$ratio;
			}

			// Round values
			$imageWidth = floor($imageWidth);
			$imageHeight = floor($imageHeight);

			// Create true color image
			$target_id = @imagecreatetruecolor($imageWidth, $imageHeight);

			// Resize the original picture and copy it into the just created image object. 
			@imagecopyresampled($target_id,$source_id,0,0,0,0,$imageWidth,$imageHeight,$source_x,$source_y);
		}
		
		// Create black and white image
		if ($blackAndWhite) {
			if (function_exists("imagefilter")) {
				imagefilter($target_id, IMG_FILTER_GRAYSCALE);
			}
			else {
				// Creates the 256 color palette 
				for ($c=0;$c<256;$c++) { 
					$palette[$c] = imagecolorallocate($target_id,$c,$c,$c); 
				}
				
 				// Reads the original colors pixel by pixel 
				for ($y=0;$y<$height;$y++) { 
					for ($x=0;$x<$width;$x++) {
						$rgb = imagecolorat($target_id,$x,$y); 
						$r = ($rgb >> 16) & 0xFF; 
						$g = ($rgb >> 8) & 0xFF; 
						$b = $rgb & 0xFF; // This is where we actually use yiq to modify our rbg values, and then convert them to our grayscale palette 
						$gs = $this->yiq($r,$g,$b); 
						imagesetpixel($target_id,$x,$y,$palette[$gs]);
					} 
				}
			}
		}
	
		// Create a jpeg with the quality of "$jpegqual" out of the
		// image object "$target_pic". This will be saved as $targetfile.
		@imagejpeg ($target_id,$targetfile,$jpegqual);
		
		// If file exists return path to file
		if (file_exists($targetfile)) {
			return $targetfile;
		}
		return $targetfile;
	}

	/** Save file. */
	function saveFile() {
		global $errors;
		global $lFileEditFile;
		
		// Check if data is submitted from the form
		checkSubmitter();
	
		// Get values
		$this->name = getPostValue("filename");
		$this->parent = new Folder(getPostValue("folderId"));
	
		// Validate
		if (empty($this->name)) $errors->addError("filename", $lFileEditFile["MissingFilename"]);

		if (!$errors->hasErrors()) {	
			// Move file
			$this->moveFile($this->parent->id);
		
			// Rename file
			$this->renameFile($this->name);				
		}
		return $errors;
	}

	/**
	 * Upload a new file to the webserver.
	 * @param 	$file		List of file values.
	 * @param 	$folderId 	Folder to move uploaded file to.
	 * @param	$filetypes	Filetypes to allow for this upload (not required).
	 * @return errors object.
	 */
	function uploadFile($file, $folderId, $filetypes=array()) {
		global $dbi, $errors;
		global $lFileUploadFiles;

		// Get file attributes
		$name = $file["name"];
		$size = $file["size"];
		$tmp_name = $file["tmp_name"];
		$type = $file["type"];

		// Validate file data
		if (!empty($name)) {
			if (sizeof($filetypes)>0) {
				if (!$this->isFiletypeSupported($file, $filetypes)) {
					$errors->addError("file", sprinft($lFileUploadFiles["FileTypeNotAllowed"], $name));
				}
			}

			// Check if file already exists
			if($this->fileExists($name,$folderId)) {
				for($i=1;$i<10;$i++) {
					if(!$this->fileExists($this->getFilename($name)."-$i.".$this->getFileExtension($name),$folderId)) {
						$name = $this->getFilename($name)."-$i.".$this->getFileExtension($name);
						$unique = true;
						break;
					}
				}
			}
			else {
				$unique = true;	
			}
	
			if($unique) {
				// Insert metadata into database
				$dbi->query("INSERT INTO ".fileTableName."(name,folderId,type,size) VALUES(".$dbi->quote($name).",".$dbi->quote($folderId).",".$dbi->quote($type).",".$dbi->quote($size).")");

				// Get identifier
				$id = $dbi->getInsertId();	
	
				if (!empty($id)) {
					// Move file to correct location
					if(!move_uploaded_file($tmp_name, filePath."/".$id.".".$this->getFileExtension($name))) {
						// Delete file from database
						$dbi->query("DELETE FROM ".fileTableName." WHERE id=".$dbi->quote($id));
	
						// Save error message
						$errors->addError("file", "The file \"".$name."\" could not be uploaded to the folder.");
					}
				}
			}
			else {
				// Save error message
				$errors->addError("file", sprinft($lFileUploadFiles["FileAlreadyExists"], $name));			
			}
		}
		return $errors;
	}
	
	/** Upload files to the website. */
	function uploadFiles($folderId=0) {
		// Check if comment is submitted from the form
		checkSubmitter();

		// Get number of files
		$numberOfFiles = getPostValue("numberOfFiles");

		// Upload files
		for($i=1; $i<$numberOfFiles+1; $i++) {
			if(!empty($_FILES["file$i"]["tmp_name"])) {
				$this->uploadFile($_FILES["file$i"], $folderId);
			}
		}
	}
	
	function yiq($r,$g,$b) { 
		return (($r*0.299)+($g*0.587)+($b*0.114)); 
	}	
}
?>