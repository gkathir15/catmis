<?
/**
 * The module class handles registration of module, content types and search types.
 * It also handles get and set of permissions.
 * @author	Kaspar Rosengreen Nielsen.
 */
class Module {
	var $modules = array();
	var $moduleContentTypes = array();
	var $moduleContentTypeObjects = array();
	var $modulePaths = array();
	var $searchTypes = array();

	/**
	 * Add new module to website.
	 * @param	$title	Module title.
	 * @param	$path	Path in file system.
	 * @return identifier of module in database.
	 */
	function addModule($title, $path="") {
		if (!empty($title)) {
			global $dbi;
			
			// Check if module already exists
			$result = $dbi->query("SELECT Id FROM ".moduleTableName." WHERE title=".$dbi->quote($title));
			if ($result->rows()) {
				list($id) = $result->fetchrow_array();
				return $id;
			}
			else {
				$dbi->query("INSERT INTO ".moduleTableName."(title,path) VALUES(".$dbi->quote($title).",".$dbi->quote($path).")");
				return $dbi->getInsertId();	
			}
		}
		return 0;
	}
	
	/** 
	  * Add new module content type to website.
	  * @param	$title		Module content type title.
	  * @param	$moduleId	Parent module identifier.
	  * @return identifier of module content type in database.
	  */
	function addModuleContentType($title, $moduleId) {
		if (!empty($title)) {
			global $dbi;
			
			// Check if module content type already exists
			$result = $dbi->query("SELECT id FROM ".moduleContentTypeTableName." WHERE title=".$dbi->quote($title));
			if ($result->rows()) {
				list($id) = $result->fetchrow_array();
				return $id;
			}
			else {
				$dbi->query("INSERT INTO ".moduleContentTypeTableName."(title,moduleId) VALUES(".$dbi->quote($title).",".$dbi->quote($moduleId).")");
				return $dbi->getInsertId();
			}
		}
		return 0;
	}
	
	function deleteModule($title) {
		/* TODO */	
	}

	/**
	 * Get content type identifier.
	 * @param	$title	Title to get identifier for.
	 * @return content type identifier.
	 */
	function getModuleContentTypeId($title) {
		global $dbi;
		$result = $dbi->query("SELECT Id FROM ".moduleContentTypeTableName." WHERE title=".$dbi->quote($title));
		if ($result->rows()) {
			list($id) = $result->fetchrow_array();
			return $id;
		}
		return 0;
	}
	
	/**
	 * Get link to module content.
	 * @param	$moduleContentTypeId	Content type identifier.
	 * @param	$moduleContentId		Content identifier.
	 * @return link to module content.
	 */
	function getModuleContentTypeLink($moduleContentTypeId, $moduleContentId) {
		if (!empty($moduleContentTypeId) && !empty($moduleContentId)) {
			global $dbi;
			$object = $this->getModuleContentTypeObject($moduleContentTypeId);
			return $object->getLink($moduleContentId);
		}	
	}
	
	/**
	 * Get module content type object.
	 * @param	$moduleContentTypeId	Content type identifier.
	 * @return module content type object.
	 */
	function getModuleContentTypeObject($moduleContentTypeId) {
		if (!empty($this->moduleContentTypeObjects[$moduleContentTypeId])) {
			return $this->moduleContentTypeObjects[$moduleContentTypeId];
		}
		return null;
	}
	
	function getModuleContentTypeParent($moduleContentTypeId) {
		global $dbi;
		$result = $dbi->query("SELECT parentId FROM ".moduleContentTypeTableName." WHERE id=".$dbi->quote($moduleContentTypeId));
		if ($result->rows()) {
			list($id) = $result->fetchrow_array();
			return $id;
		}
		return 0;
	}
	
	/**
	 * Get database version for a given module.
	 * @param	$moduleId	Module identifier.
	 * @return 	database version of module.
	 */
	function getModuleDatabaseVersion($moduleId = 0) {
		global $dbi;
		
		// Get database version
		$databaseVersion = 0;
		$result = $dbi->query("SELECT databaseVersion FROM ".metaTableName." WHERE moduleId=".$dbi->quote($moduleId));
		if ($result->rows()) {
			list($databaseVersion) = $result->fetchrow_array();
		}
		else {
			$dbi->query("INSERT INTO ".metaTableName."(moduleId,cmisVersion,lastUpdated) VALUES(".$dbi->quote($moduleId).",".$dbi->quote(version).",NOW())");
		}
		return $databaseVersion;	
	}
	
	/**
	 * Get module identifier from title.
	 * @param	$title	Module title.
	 * @return module identifier.
	 */
	function getModuleId($title) {
		global $dbi;
		$result = $dbi->query("SELECT Id FROM ".moduleTableName." WHERE title=".$dbi->quote($title));
		if ($result->rows()) {
			list($id) = $result->fetchrow_array();
			return $id;
		}
		return 0;
	}
	
	/**
	 * Get module identifier from content type.
	 * @param	$moduleContentTypeId	Content type identifier.
	 * @return module identifier.
	 */
	function getModuleIdFromContentType($moduleContentTypeId) {
		if (!empty($moduleContentTypeId)) {
			global $dbi;
			$result = $dbi->query("SELECT ModuleId FROM ".moduleContentTypeTableName." WHERE Id=".$dbi->quote($moduleContentTypeId));
			if ($result->rows()) {
				list($moduleId) = $result->fetchrow_array();
				return $moduleId;	
			}	
		}
		return 0;
	}	
	
	/**
	 * Get module title from identifier.
	 * @param	$title	Module title.
	 * @return module identifier.
	 */
	function getModuleTitle($id) {
		global $dbi;
		$result = $dbi->query("SELECT title FROM ".moduleTableName." WHERE id=".$dbi->quote($id));
		if ($result->rows()) {
			list($title) = $result->fetchrow_array();
			return $title;
		}
		return "";
	}	
	
	/** Get identifiers and define modules, module content types and search types. */	
	function initialize() {
		global $dbi;
		
		$titles = "";
		$i = 0;
	    foreach ($this->modules as $field => $value) {
	    	$titles .= ($i!=0?",":"")."'".$field."'";
	    	$i++;
	    }

	    // Get module identifiers from database
	    if ($titles!="") {
		    $result = $dbi->query("SELECT id,title,path FROM ".moduleTableName." WHERE title IN(".$titles.")");
	    	$titles = "";
		    if ($result->rows()) {
		    	for($i=0; list($id,$title,$path) = $result->fetchrow_array(); $i++) {
		    		$titles .= ($i!=0?",":"").$title;
					define($this->modules[$title], $id);
					
					// Check if path has been set
					if (empty($path) && !empty($this->modulePaths[$title])) {
						$dbi->query("UPDATE ".moduleTableName." SET path=".$dbi->quote($this->modulePaths[$title])." WHERE id=".$dbi->quote($id));
					}
		    	}
		    }
		    
		    // If module does not exists - create it
		    foreach ($this->modules as $field => $value) {
		    	if (strpos($titles,$field)===false) {
					$dbi->query("INSERT INTO ".moduleTableName."(title,path) VALUES(".$dbi->quote($field).",".$dbi->quote(!empty($this->modulePaths[$field])?$this->modulePaths[$field]:"").")");
					$id = $dbi->getInsertId();
					if (!empty($id)) {
						define($this->modules[$field], $id);
					}
		    	}
		    }
		    
		    // Clear array
		    $this->modules = array();
		    $this->modulePaths = array();
	    }

		// Process content types
		$titles = "";
		$i = 0;
	    foreach ($this->moduleContentTypes as $field => $value) {
	    	$titles .= ($i!=0?",":"")."'".$field."'";
	    	$i++;
	    }

		if ($titles!="") {
		    // Get module content type identifiers from database
		    $result = $dbi->query("SELECT id,title FROM ".moduleContentTypeTableName." WHERE title IN(".$titles.")");
	    	$titles = "";
		    if ($result->rows()) {
		    	for($i=0; list($id,$title) = $result->fetchrow_array(); $i++) {
		    		if (!empty($this->moduleContentTypes[$title])) {
			    		$titles .= ($i!=0?",":"").$title;
						define($this->moduleContentTypes[$title]["id"], $id);
						$this->moduleContentTypeObjects[$id] = $this->moduleContentTypes[$title]["object"];
		    		}
		    	}
		    }
		    
		    // If module content type does not exists - create it
		    foreach ($this->moduleContentTypes as $field => $value) {
		    	if (strpos($titles,$field)===false) {
		    		$moduleId = $this->getModuleId($this->moduleContentTypes[$field]["moduleTitle"]);
		    		$parentContentTypeId = $this->getModuleContentTypeId($this->moduleContentTypes[$field]["parentContentTypeTitle"]);
					$dbi->query("INSERT INTO ".moduleContentTypeTableName."(moduleId,parentId,title) VALUES(".$dbi->quote($moduleId).",".$dbi->quote($parentContentTypeId).",".$dbi->quote($field).")");
					define($this->moduleContentTypes[$field]["id"], $dbi->getInsertId());
					$this->moduleContentTypes[$dbi->getInsertId()] = $this->moduleContentTypes[$field]["object"];
		    	}
		    }
	
			// Clear array
		    $this->moduleContentTypes = array();
		}

		// Process search types
		$dbSearchTypes = array();
		$result = $dbi->query("SELECT moduleContentTypeId FROM ".searchTypeTableName);
		if ($result->rows()) {
			for ($i=0; list($moduleContentTypeId)=$result->fetchrow_array(); $i++) {
				$dbSearchTypes[$i] = $moduleContentTypeId;
			}
			
			for ($i=0; $i<sizeof($this->searchTypes); $i++) {
				if (defined($this->searchTypes[$i])) {
					$matchFound = false;
					for ($j=0; $j<sizeof($dbSearchTypes); $j++) {
						if ($dbSearchTypes[$j]==constant($this->searchTypes[$i])) {
							$matchFound = true;
							break;
						}
					}
					
					// If not match was found insert into database
					if (!$matchFound) {
						$position = 1;
						$result = $dbi->query("SELECT MAX(position) FROM ".searchTypeTableName);
						if ($result->rows()) {
							list($position) = $result->fetchrow_array();
							$position++;
						}
						$dbi->query("INSERT INTO ".searchTypeTableName."(moduleContentTypeId,position,visible) VALUES(".$dbi->quote(constant($this->searchTypes[$i])).",".$dbi->quote($position).",1)");				
					}		
				}		
			}
		}
				
		// Clear array
		$this->searchTypes = array();
		
		// Free result
		$result->finish();
	}
	
	/**
	 * Is a given module installed?
	 * @param	$title	Module title.
	 * @return true if module is installed, false otherwise.
	 */
	function isModuleInstalled($title) {
		$moduleId = $this->getModuleId($title); 
		if (!empty($moduleId)) {
			return true;
		}
		return false;
	}

	/**
	 * Register module.
	 * @param	$title	Module title.
	 * @param	$id		Identifier to use for define constant.
	 */	
	function registerModule($title, $id, $path="") {
		if (!empty($title) && !empty($id)) {
			$this->modules[$title] = $id;
			if (!empty($path)) $this->modulePaths[$title] = $path;
		}
	}
	
	/**
	 * Register module content type.
	 * @param	$title			Module content type title.
	 * @param	$moduleTitle	Title of parent module.
	 * @param	$id				Identifier to use for define constant.
	 * @param	$object			Module content type object.
	 */	
	function registerModuleContentType($title, $moduleTitle, $parentContentTypeTitle, $id, $object) {
		if (!empty($title) && !empty($moduleTitle) && !empty($id) && !empty($object)) {
			$this->moduleContentTypes[$title] = array("id" => $id, "moduleTitle" => $moduleTitle, "parentContentTypeTitle" => $parentContentTypeTitle, "object" => $object);
		}
	}
	
	/**
	 * Register search type.
	 * @param	$moduleContentTypeId	Content type identifier.
	 */
	function registerSearchType($moduleContentTypeId) {
		if (!empty($moduleContentTypeId)) {
			$this->searchTypes[] = $moduleContentTypeId;
		}
	}
	
	function updateModule($title, $path="") {
		/* TODO */
	}
	
	/**
	 * Update module database version.
 	 * @param	$moduleId	Module identifier.
     * @param	$databaseVersion	Database version.
 	 */
	function updateModuleDatabaseVersion($moduleId, $databaseVersion) {
		global $dbi;
		$dbi->query("UPDATE ".metaTableName." SET databaseVersion=".$dbi->quote($databaseVersion).",cmisVersion=".$dbi->quote(version)." WHERE moduleId=".$dbi->quote($moduleId));		
	}
}
?>