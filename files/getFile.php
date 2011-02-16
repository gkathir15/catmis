<?
// Include common functions and declarations
include "../include/common.lite.php";

// Get file id
$id = !empty($_GET["fileId"])?$_GET["fileId"]:"";
if (!empty($id)) {
	$extension = getDatabaseFileExtension($id);
	if (!empty($extension)) {
		$file = filePath."/".$id.".".$extension;

		if (file_exists($file)) {
  			// Determine whether to generate a thumbnail
			$imageWidth = getGetValue("imageWidth");
			if (!empty($imageWidth)) {
				// Generate cached thumbnail
				$imageUrl = $cache->generateThumbnail("", str_replace(scriptPath."/","",$file), "file_".$id, $imageWidth);
				if (!empty($imageUrl)) redirect($imageUrl);
			}

			// If file is outside web root get contents
			if (false) {
				// Make file stay in cache for 30 days.
				header("Expires: " . date("D, j M Y H:i:s", time() + (86400 * 30)) . " UTC");
				header("Cache-Control: Public");
				header("Pragma: Public");

				// File not cached or cache outdated, we respond '200 OK' and output the file
				header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($file)).' GMT', true, 200);
				header('Content-Length: '.filesize($file));
				header('Content-Type: '.$fileObject->type);
				header('Content-Disposition: attachment; filename="'.basename($fileObject->name).'"');
				print file_get_contents($file);
			}
			else {
				redirect(scriptUrl."/".folderUploadedFiles."/".$id.".".$extension);
			}
		}
	}
	
	// Close session
	session_write_close();
}
?>