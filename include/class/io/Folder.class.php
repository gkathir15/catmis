<?
/**
 * Class folder represents a folder on the webserver. The class contains methods
 * for renaming, moving and deleting folders.
 */
class Folder {
	var $id = 0;
	var $name = "";
	var $parent = null;
	
	/**
	 * Folder constructor.
	 * @param 	$id	 Folder identifier.
	 */
	function Folder($id=0) {
		if (!empty($id)) {
			global $dbi;
			
			// Fetch folder values from database
			$result = $dbi->query("SELECT id,name,parentId FROM ".folderTableName." WHERE id=".$dbi->quote($id));
			if($result->rows()) {
				list($this->id,$this->name,$parentId) = $result->fetchrow_array();
				
				// Create parent folder object
				$this->parent = new Folder($parentId);
			}
		}
	}
	
    function copyPath($path, $dest) {
		$count = 0;
	    $DS = DIRECTORY_SEPARATOR;
        if(is_dir($path)) {
            @mkdir($dest);
            $objects = scandir($path);
            if(sizeof($objects) > 0) {
                foreach($objects as $file) {
                    if( $file == "." || $file == ".." )
                        continue;
                    if(is_dir($path.$DS.$file)) {
                        return $this->copyPath($path.$DS.$file, $dest.$DS.$file );
                    }
                    else {
                        return copy($path.$DS.$file, $dest.$DS.$file);
                    }
                }
            }
        }
        else if(is_file($path)) {
			if (!is_file($dest)) {
				if (is_dir($dest)) {
					$dest = $dest ."/".basename($path);
				}
			}
            return copy($path, $dest);
        }
        return false;
    }
	
	/** 
	 * Create new folder.
	 * @param 	$name 		Name of new folder.
	 * @param 	$parentId 	Parent folder of new folder.
	 */
	function createFolder($name, $parentId=0) {
		global $dbi, $errors;
		global $lFileCreateFolder;

		if (!empty($name)) {
			// Check if data is submitted from the form
			checkSubmitter();
			
			// Validate data
			if (empty($name)) $errors->addError("name", $lFileCreateFolder["NameMissing"]);
			
			// Check if parent folder exists
			if (!empty($parentId)) {
				$folder = new Folder($parentId);
				if (empty($folder->id)) $errors->addError("folderId", $lFileCreateFolder["MissingParentFolder"]);
			}
			
			// Check if folder name already exists in parent folder
			if ($this->folderExists($name,$parentId)) $errors->addError("name", $lFileCreateFolder["FolderExists"]); 
			
			// Check if folder already exists
			if (!$errors->hasErrors()) {
				$dbi->query("INSERT INTO ".folderTableName."(name,parentId) VALUES(".$dbi->quote($name).",".$dbi->quote($parentId).")");	
			}
		}
		return $errors;
	}
	
	/**
	 * Delete this folder, all subsections and files
	 * @return boolean to determine if delete was succesfull
	 */
	function deleteFolder() {
		if(!empty($this->id)) {
			global $dbi;
			
			/* Delete files in folder */
			$result = $dbi->query("SELECT id FROM ".fileTableName." WHERE folderId=".$this->id);
			for($i=0;(list($id)=$result->fetchrow_array());$i++) {
				$file = new File($id);
				$file->deleteFile();	
			}
			
			/* Delete subfolders */
			$result = $dbi->query("SELECT id FROM ".folderTableName." WHERE parentId=".$this->id);
			for($i=0;(list($id)=$result->fetchrow_array());$i++) {
				$folder = new Folder($id);
				$folder->deleteFolder();	
			}

			/* Delete logged information in database */
			$dbi->query("DELETE FROM ".logTableName." WHERE type='folder' AND typeId=".$this->id);

			/* Delete this folder */
			$dbi->query("DELETE FROM ".folderTableName." WHERE id=".$this->id);

			/* Folder was deleted */			
			return true;
		}
		
		/* Folder was not deleted */
		return false;
	}
	
	/**
	 * Determine if a given folder already exists within a folder.
	 * @param 	$foldername 	Foldername to search for.
	 * @param 	$folderId 		Folder to look in.
	 * @return true if folder exists
	 */
	function folderExists($foldername, $folderId) {
		global $dbi;
		
		$result = $dbi->query("SELECT id FROM ".folderTableName." WHERE name=".$dbi->quote($foldername)." AND parentId=".$dbi->quote($folderId));
		if($result->rows()) return true;
		return false;
	}
	
	/** 
	 * Get time when this folder, subfolders or files was last modified.
	 * @return time the folder and its content was last modified.
	 */
	function getLastModified() {
		if(!empty($this->id)) {
			global $dbi;
			
			// Get last modified for this folder
			$lastModified = "";
			$result = $dbi->query("SELECT UNIX_TIMESTAMP(lastUpdated) FROM ".logTableName." WHERE type='folder' AND typeId='".$this->id."'");
			if($result->rows()) {
				list($lastModified) = $result->fetchrow_array();
			}

			// Check if folders inside the folder have been modified
			$result = $dbi->query("SELECT id FROM ".folderTableName." WHERE parentId=".$dbi->quote($this->id));
			for($i=0;(list($id)=$result->fetchrow_array());$i++) {
				$folder = new Folder($id);					
				if($folder->getLastModified()>$lastModified) $lastModified = $folder->getLastModified();	
			}

			// Check if files inside the folder have been modified
			$result = $dbi->query("SELECT id FROM ".fileTableName." WHERE folderId=".$dbi->quote($this->id));
			for($i=0;(list($id)=$result->fetchrow_array());$i++) {
				$file = new File($id);
				if($file->getLastModified()>$lastModified) $lastModified = $file->getLastModified();	
			}
			
			// Return time the folder was last modified
			if (!empty($lastModified)) return $lastModified;
		}
		return mktime();
	}

	/** Print folder header containing path */
	function getLinkPath($class="") {
		global $dbi;

		$parent = null;
		if (empty($this->parent)) $parent = new Folder();
		else $parent = $this->parent;
		if($this->id!=0) return $parent->getLinkPath($class)." / <a href=\"".scriptUrl."/".folderFilesAdmin."/".fileFilesIndex."?folderId=".$this->id."\"".(!empty($class)?" class=\"$class\"":"").">".$this->name."</a>";
		else return "";
	}
		
	/** 
	 * Get number of files in this folder and all its subdirectories
	 * @return number of files in this folder
	 */
	function getNumberOfFiles() {
		if(!empty($this->id)) {
			global $dbi;

			/* Initialize number of files */
			$numberOfFiles = 0;
			
			/* Get number of files in this folder */
			$result = $dbi->query("SELECT COUNT(*) FROM ".fileTableName." WHERE folderId=".$this->id);
			if($result->rows()) {
				list($numberOfFiles) = $result->fetchrow_array();
			}
			
			/* Get number of files for subdirectories */
			$result = $dbi->query("SELECT id FROM ".folderTableName." WHERE parentId=".$this->id);
			if($result->rows()) {
				for($i=0;(list($id)=$result->fetchrow_array());$i++) {
					$folder = new Folder($id);
					$numberOfFiles += $folder->getNumberOfFiles();
				}
			}
			
			/* Return number of files */
			return $numberOfFiles;
		}		
		return 0;
	}
	
	/** 
	 * Get number of files in this folder 
	 * @return number of files in this folder
	 */
	function getNumberOfFolders() {
		if(!empty($this->id)) {
			global $dbi;
			
			/* Initialize number of folders */
			$numberOfFolders = 0;
			
			/* Get number of folders in this folder */
			$result = $dbi->query("SELECT COUNT(*) FROM ".fileTableName." WHERE folderId=".$this->id);
			if($result->rows()) {
				list($numberOfFolders) = $result->fetchrow_array();
			}
			
			/* Get number of folders in subdirectories */
			$result = $dbi->query("SELECT id FROM ".folderTableName." WHERE parentId=".$this->id);
			if($result->rows()) {
				for($i=0;(list($id)=$result->fetchrow_array());$i++) {
					$folder = new Folder($id);
					$numberOfFolders += $folder->getNumberOfFolders();
				}
			}
			
			/* Return number of folders */
			return $numberOfFolders;
		}
		return 0;
	}
	
	/** 
	 * Get path to this folder
	 * @param $id Id of folder to generate path for
	 * @return string containing path
	 */
	function getPath($id=0) {
		global $dbi;
		if(empty($id)) $id = $this->id;
		
		$path = "";
		$folder = new Folder($id);
		$result = $dbi->query("SELECT parentId FROM ".folderTableName." WHERE id=".$id);
		if($result->rows()) {
			list($parentId) = $result->fetchrow_array();
			if($parentId!=0) $path = $this->getPath($parentId);
		}
		return $path."/".$folder->name;
	}

	/** 
	 * Get total size of subfolders and files of this folder
	 * @return size of this folder in bytes
	 */
	function getSize() {
		if(!empty($this->id)) {
			global $dbi;

			$totalSize = 0;

			/* Get size of subfolders */
			$result = $dbi->query("SELECT id FROM ".folderTableName." WHERE parentId=".$this->id);
			for($j=0;(list($id)=$result->fetchrow_array());$j++) {
				$folder = new Folder($id);
				$totalSize += $folder->getSize();
			}
			
			/* Get size of files */
			$result = $dbi->query("SELECT id FROM ".fileTableName." WHERE folderId=".$this->id);
			for($j=0;(list($id)=$result->fetchrow_array());$j++) {
				$file = new File($id);
				$totalSize += $file->getSize();
			}
			
			/* Return size of folder in bytes */
			return $totalSize;
		}
	}

	/**
	 * Move folder to another folder
	 * @param $folderId Id of folder to move to
	 */
	function moveFolder($folderId) {
		if(!empty($this->id)) {
			global $dbi;

			// Update parent folder for this folder
			$dbi->query("UPDATE ".folderTableName." SET parentId=".$dbi->quote($folderId)." WHERE id=".$dbi->quote($this->id));
		}	
	}

	/** Print folder options in select box */
	function printFolderOptions($id=0) {
		global $dbi;

		if($id==0) echo "<option value=\"0\">/</option>";

		// Get subsections
		$result = $dbi->query("SELECT id FROM ".folderTableName." WHERE parentId=".$dbi->quote($id)." ORDER BY name");
		if($result->rows()) {
			for($i=0;(list($id) = $result->fetchrow_array());$i++) {
				$folder = new Folder($id);
				echo "<option value=\"".$folder->id."\"".($this->id==$folder->id?" selected=\"selected\"":"").">";
				echo $folder->getPath()."/</option>";
				$this->printFolderOptions($folder->id);
			}
		}
	}	
	
	/**
	 * Rename this folder
	 * @param $name Name to rename folder to
	 */
	function renameFolder($name) {
		if(!empty($this->id)) {
			global $dbi;
			
			// Rename folder in database
			$dbi->query("UPDATE ".folderTableName." SET name=".$dbi->quote($name)." WHERE id=".$dbi->quote($this->id));
		}
	}
	
	/** Save folder. */
	function saveFolder() {
		if (!empty($this->id)) {
			global $errors;
			global $lFileEditFolder;
			
			// Check if data is submitted from the form
			checkSubmitter();
	
			// Get values
			$this->name = getPostValue("folderName");
			$this->parent = new Folder(getPostValue("folderId"));
			
			// Validate
			if (empty($this->name)) $errors->addError("folderName", $lFileEditFolder["MissingFoldername"]);
			
			if (!$errors->hasErrors()) {
				// Rename folder
				$this->renameFolder($this->name);			
			}
			return $errors;
		}
	}
}
?>