<?
/**
 * The class Group represents a group in the system and containers methods 
 * for deleting and saving groups.
 * @author	Kaspar Rosengreen Nielsen
 */
class Group extends ModuleContentType {
	var $description = "";
	var $name = "";
	
	/**
	 * Group constructor.
	 * @param	$id		Identifier of group.
	 */
	function __construct($id="") {
		parent::__construct("groupModuleId", "groupContentTypeId");
		
		// Initialize values
		$this->init($id);
	}

	/**
	 * Add a given user to a group.
	 * @param	$groupId	Identifier of group.
	 * @param	$userId		Identifier of user.
	 */
	function addToGroup($groupId, $userId) {
		if (!empty($groupId) && !empty($userId)) {
			global $dbi;
			$result = $dbi->query("INSERT ".userGroupRefTableName."(groupId,userId) VALUES(".$dbi->quote($groupId).",".$dbi->quote($userId).")");
		}		
	}

	/** Delete group from the database. */
	function deleteGroup() {
		if (!empty($this->id)) {
			if ($this->hasDeletePermission()) {
				global $dbi,$log;
				
				// Check if data is submitted from the form			
				checkSubmitter();
				
				// Delete group
				$dbi->query("DELETE FROM `".groupTableName."` WHERE id=".$dbi->quote($this->id));
				
				// Delete references between users and this group
				$dbi->query("DELETE FROM ".userGroupRefTableName." WHERE groupid=".$dbi->quote($this->id));
				
				// Delete transaction from log
				$log->deleteTransaction(groupContentTypeId,$this->id);
			}
		}
	}

	/**
	 * Delete a given user from all groups.
	 * @param	$userId	Identifier of user.
	 */	
	function deleteGroupRefs($userId) {
		if (!empty($userId)) {
			if ($this->hasDeletePermission()) {
				global $dbi;
				$dbi->query("DELETE FROM ".userGroupRefTableName." WHERE userId=".$dbi->quote($userId));
			}
		}	
	}

	function getGroupLink() {
		return scriptUrl."/".folderGroups;
	}

	/**
	 * Get link to a given group.
	 * @param	$id	Identifier of group.
	 * @return	Link to group.
	 */	
	function getLink($id="") {
		if (!empty($id)) {
			$group = new Group($id);
			return $group->getGroupLink();
		}
		return scriptUrl."/".folderGroup;
	}

	/**
	 * Get name of a given group.
	 * @param	$id	Identifier of group to get name for.
	 * @return	Name of group if identifier set, otherwise return general group header.
	 */		
	function getName($id="") {
		if (!empty($id)) {
			$group = new Group($id);
			return $group->name;
		}
		else {
			global $lGroup;
			return $lGroup["Header"];
		}
	}

	/**
	 * Get number of search results for a given search string.
	 * @param	$searchString	Search string.
	 * @return	Number of search results.
	 */	
	function getNumberOfSearchResults($searchString) {
		return 0;
	}	
	
	/**
	 * Get number of users in this group.
	 * @return number of users in this group.
	 */
	function getNumberOfUsers() {
		if (!empty($this->id)) {
			global $dbi;
			$result = $dbi->query("SELECT COUNT(*) FROM `".userGroupRefTableName."` WHERE groupId=".$dbi->quote($this->id));
			if ($result->rows()) {
				list($count) = $result->fetchrow_array();
				return $count;
			}
		}
		return 0;
	}
	
	/**
	 * Does a given group already exist?
	 * @return true if group exists, false otherwise.
	 */
	function groupExists($name) {
		global $dbi;
		$result = $dbi->query("SELECT id FROM `".groupTableName."` WHERE name=".$dbi->quote($name));
		if ($result->rows()) {
			return true;
		}
		return false;
	}

	/** Does the current user have administer permission? */	
	function hasAdministerPermission() {
		global $login;
		return $login->isWebmaster();
	}
	
	/** Does the current user have delete permission? */	
	function hasDeletePermission() {
		global $login;
		return $login->isWebmaster();
	}
	
	/** Does the current user have edit permission? */	
	function hasEditPermission() {
		global $login;
		return $login->isWebmaster();
	}	
	
	/**
	 * Initialize object.
	 * @param	$id		Group identifier.
	 */
	function init($id=0) {
		if (!empty($id)) {
			global $dbi;
			$result = $dbi->query("SELECT id,name,description FROM `".groupTableName."` WHERE id=".$dbi->quote($id));
			if ($result->rows()) {
				list($this->id,$this->name,$this->description) = $result->fetchrow_array();
			}
		}		
	}	

	/**
	 * Is a given user member of a given group?
	 * @param	$groupId	Identifier of group.
	 * @param	$userId		Identifier of user.
	 * @return	true if user is a member, false otherwise.
	 */	
	function isMember($groupId, $userId) {
		if (!empty($groupId) && !empty($userId)) {
			global $dbi;
			$result = $dbi->query("SELECT id FROM ".userGroupRefTableName." WHERE userId=".$dbi->quote($userId)." AND groupId=".$dbi->quote($groupId));
			if ($result->rows()) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Print search results.
	 * @param	$searchString
	 * @param	$limit
	 * @param 	$page
	 * @param	$viewAll
	 */	
	function printSearchResults($searchString, $limit=0, $page=0, $viewAll=0) {}	
	
	/** Save group in database. */
	function saveGroup() {
		global $errors;

		if ($this->hasEditPermission()) {
			global $dbi,$log,$login;
	
			// Check if data is submitted from the form			
			checkSubmitter();			
	
			// Include language
			include scriptPath."/include/language/".pageLanguage."/admin.php";
		
			// Get values
			$this->name = getValue("groupName");
			$this->description = getValue("groupDescription");
			
			// Validate user data
			if (empty($this->name)) {
				$errors->addError("name",$lEditGroup["MissingName"]);
			}
			if (empty($this->id)) {
				if ($this->groupExists($this->name)) {
					$errors->addError("name",$lEditGroup["GroupExists"]);
				}
			}
	
			// If no errors insert/update database */		
			if (!$errors->hasErrors()) {
				if (!empty($this->id)) {
					$dbi->query("UPDATE `".groupTableName."` SET name=".$dbi->quote($this->name).",description=".$dbi->quote($this->description)." WHERE id=".$this->id);
				}
				else {
					$dbi->query("INSERT INTO `".groupTableName."`(name,description) VALUES(".$dbi->quote($this->name).",".$dbi->quote($this->description).")");
					
					// Get new id
					$this->id = $dbi->getInsertId();			
				}
	
				// Set permissions for group
				if ($login->isWebmaster()) {	
					// Remove permissions if any
					$dbi->query("DELETE FROM ".permissionTableName." WHERE type='Group' AND typeId=".$dbi->quote($this->id));
		
					// If module administrator set permissions
					$permissions = getValue("permissions");
					$result = $dbi->query("SELECT Id FROM ".moduleTableName);
					if ($result->rows()) {
						for ($i=0; list($moduleId) = $result->fetchrow_array(); $i++) {
							if (!empty($permissions[$moduleId])) {
								// Initialize values
								$administrator = 0;
								$comment = 0;
								$create = 0;
								$delete = 0;
								$edit = 0;
								$grant = 0;
								$publish = 0;
								$read = 0;
								
								// Get permission type
								switch($permissions[$moduleId]) {
									case 1:
										$read = 1;
										break;	
									case 2:
										$read = 1;
										$comment = 1;
										break;	
									case 3:
										$read = 1;
										$comment = 1;
										$create = 1;
										$edit = 1;
										break;	
									case 4:
										$read = 1;
										$comment = 1;
										$create = 1;
										$edit = 1;
										$publish = 1;
										break;	
									case 5:
										$read = 1;
										$comment = 1;
										$create = 1;
										$edit = 1;
										$publish = 1;	
										$delete = 1;					
										break;	
									case 6:
										$read = 1;
										$comment = 1;
										$create = 1;
										$edit = 2;
										$publish = 1;	
										$delete = 2;					
										break;	
									case 7:
										$administrator = 1;
										break;	
								}
							
								// Check if any permissions have been set
								if ($administrator || $comment!=0 || $create!=0 || $delete!=0 || $edit!=0 || $grant!=0 || $publish!=0 || $read!=0) {
									// Set permissions for module content
									$login->setModulePermissions($moduleId, 
																 "Group", 
																 $this->id, 
																 $administrator,
																 $comment, 
																 $create, 
																 $delete, 
																 $edit, 
																 $grant, 
																 $publish, 
																 $read
																 );											
								}
							}
						}
					}
				}
	
				// Log transaction
				$log->logTransaction(groupContentTypeId,$this->id);
			}
		}

		// Return errors if any
		return $errors;		
	}
}
?>