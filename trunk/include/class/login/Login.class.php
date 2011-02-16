<?
/** 
 * The Login class handles login and gets user data from the database. 
 * @author	Kaspar Rosengreen Nielsen
 */
class Login {
	var $id = 0;
	var $administrator = false;
	var $email = "";
	var $groupId = 0;
	var $name = "";
	var $username = "";
	var $webmaster = false;
	
	var $savedPermissions = array();

	/** Login constructor */
	function Login() {
		// Check session and logout if invalid
		if($this->isLoggedIn()) {
			$this->checkSession();
		}
		else if(isset($_COOKIE["Login"])) {
			$this->checkRemembered($_COOKIE["Login"]);
		}
	}
	
	/**
	 * Activate a given user.
	 * @param	$id				Identifier of user.
	 * @param	$activationKey	Activation key for user.
	 */
	function activateUser($id, $activationKey) {
		if (!empty($id) && !empty($activationKey)) {
			global $dbi;
			global $lActivate;

			$result = $dbi->query("SELECT username,activationKey FROM ".userTableName." WHERE id=".$dbi->quote($id));
			if ($result->rows()) {
				list($username,$activationKeyDB) = $result->fetchrow_array();
				if ($activationKey==$activationKeyDB) {
					$dbi->query("UPDATE ".userTableName." SET registered=registered,lastUpdated=lastUpdated,lastLogged=lastLogged,activated=1,activationKey='' WHERE id=".$dbi->quote($id));
					
					// Send confirmation email
					$result = $dbi->query("SELECT name,email FROM ".userDataTableName." WHERE id=".$dbi->quote($id));
					if ($result->rows()) {
                                      list($name,$email) = $result->fetchrow_array();

                                      // Send activation email
                                      $mail = new phpmailer();
                                      $mail->CharSet = "UTF-8";
                                      $mail->From     = pageAdminMail;
                                      $mail->FromName = pageTitle;
                                      $mail->Subject  = $lActivate["MailSubject"];
                                      $mail->Body     = sprintf($lActivate["MailMessage"], $name, $username);
                                      $mail->IsHTML(false);
                                      $mail->AddAddress($email);
                                      $mail->Send();
					}				
					echo '<p>'.$lActivate["HeaderText"].'</p>';		
				}
				else {
					echo '<p>'.$lActivate["HeaderTextError"].'</p>';		
				}
			}			
		}
	}
		
	/** 
	  * Check login data
	  * @param 	$username 	Username to log in with.
	  * @param 	$password 	Password to log in with.
	  * @param 	$remember 	Remember login.
	  */
	function checkLogin($username, $password, $remember) {
		global $dbi;
		
		// Include language
		include scriptPath."/include/language/".pageLanguage."/general.php";
		
		// Check for maximum allowed logins
		if(!isset($_SESSION["u_login"])) $_SESSION["u_login"] = 20;
		else if($_SESSION["u_login"]<=1) die($lLogin["Error"]);

		// Get user data from database
		$result = $dbi->query("SELECT u1.id,u1.username,UNIX_TIMESTAMP(u1.lastLogged),u1.cookie,u1.activated,u1.administrator,u1.webmaster,u2.name,u2.email ".
							  "FROM ".userTableName." as u1, ".userDataTableName." as u2 ".
							  "WHERE (u1.id=u2.id) AND (u1.username=".$dbi->quote($username).") AND (u1.password=".$dbi->quote(md5($password)).")");
		if($result->rows()) {
			$success = $this->setSession($result, $remember);
                        if ($success) {
                            $_SESSION["u_login"] = 20;
                            return true;
                        }
                        else {
                            return false;
                        }
		}
		else {
			$_SESSION['u_login']--;
			$this->logout();
			return false;
		}
	}

	/**
	  * Check if user wanted to be remembered.
	  * @param 	$cookie 	Cookie to check with.
	  */
	function checkRemembered($cookie) {
		global $dbi;

		$cookie = str_replace("\\","",$cookie);	
		list($username, $cookie) = @unserialize($cookie);
		
		if(!$username or !$cookie) return;

		// Get user data from database and start session
		$result = $dbi->query("SELECT u1.id,u1.username,UNIX_TIMESTAMP(u1.lastLogged),u1.cookie,u1.activated,u1.administrator,u1.webmaster,u2.name,u2.email ".
							  "FROM ".userTableName." as u1, ".userDataTableName." as u2 ".
							  "WHERE (u1.id=u2.id) AND (u1.username=".$dbi->quote($username).") AND (u1.cookie=".$dbi->quote($cookie).")");
		if($result->rows()) {
			$this->setSession($result, true);
		}
	}

	/** 
	 * Check current session. If cookie and session doesn't match data 
	 * stored in the database logout the current user. 
	 */
	function checkSession() {
		global $dbi;
		global $checkSession;
		
		$username = !empty($_SESSION['u_username'])?$_SESSION['u_username']:"";
		$cookie = !empty($_SESSION['u_cookie'])?$_SESSION['u_cookie']:"";
		$session = session_id();
		$ip = $_SERVER['REMOTE_ADDR'];

		$result = $dbi->query("SELECT u1.id,u1.username,UNIX_TIMESTAMP(u1.lastLogged),u1.cookie,u1.activated,u1.administrator,u1.webmaster,u2.name,u2.email ".
							  "FROM ".userTableName." as u1, ".userDataTableName." as u2 ".
							  "WHERE (u1.id=u2.id) AND (u1.username=".$dbi->quote($username).") AND (u1.cookie=".$dbi->quote($cookie).")".($checkSession ? " AND (u1.session=".$dbi->quote($session).") AND (u1.ip=".$dbi->quote($ip).")" : ""));
		if($result->rows()) $this->setSession($result, false, false);
		else $this->logout();
	}

	/**
	 * Clear all permissions for a given content type and identifier.
	 * @param	$moduleContentTypeId	Module content type identifier.
	 * @param	$moduleContentId		Module content identifier.
	 */
	function clearPermissions($moduleContentTypeId, $moduleContentId) {
		if (!empty($moduleContentTypeId) && !empty($moduleContentId)) {
			global $dbi;
			$dbi->query("DELETE FROM ".permissionTableName." WHERE moduleContentTypeId=".$dbi->quote($moduleContentTypeId)." AND moduleContentId=".$dbi->quote($moduleContentId));
		}
	}
	
	/** 
	  * Delete login cookie in the user's browser.
	  * @param 	$name	Name of the cookie. 
	  */
	function deleteCookie($name) {
		if(!headers_sent()) {
			setcookie($name,"bogus",time()-3600,"/");
			setcookie($name,"bogus",time()-3600,"/",".".getCurrentDomain());
		}
	}
		
	/** 
	 * Generate cookie.
	 * @return Generated cookie.
	 */
	function generateCookie() {
		$cookie = mt_rand(1, mt_getrandmax());
		$cookie = md5(uniqid($cookie));
		return $cookie;
	}
	
	/**
	 * Get permission level of a given user or group.
	 * @param	$moduleId
	 * @param	$type
	 * @param	$typeId
	 */
	function getModulePermissionLevel($moduleId, $type, $typeId="") {
		if (!empty($moduleId) && ($type=="User" || $type=="Group")) {
			global $dbi,$login;

			// If admin return maximum
			if ($type=="User") {
				if ($login->isAdmin($typeId)) {
					return 7;
				}
			}
						
			// Get permissions for module
			$modulePermissionLevel = 0;
			if ($type=="User") {
				// Check if user is webmaster, administrator og module administrator
				if ($this->isModuleAdmin($moduleId, $typeId)) return 7;
			}

			$result = $dbi->query("SELECT AdministerPermission,CommentPermission,CreatePermission,DeletePermission,EditPermission,PublishPermission,ReadPermission FROM ".permissionTableName." WHERE Type=".$dbi->quote($type).(!empty($typeId)?" AND TypeId=".$dbi->quote($typeId):"")." AND ModuleId=".$dbi->quote($moduleId)." AND ModuleContentTypeId=0 AND ModuleContentId=0");
			if ($result->rows()) {
				list($administrator,$comment,$create,$delete,$edit,$publish,$read) = $result->fetchrow_array();
				$modulePermissionLevel = $this->getPermissionLevel($administrator,$comment,$create,$delete,$edit,$publish,$read);
			}
			return $modulePermissionLevel;
		}
		return 0;		
	}
	
	/**
	 * Get permission level of given user or group.
	 * @param	$moduleContentTypeId
	 * @param	$moduleContentId
	 * @param	$type
	 * @param	$typeId
	 */
	function getModuleContentTypePermissionLevel($moduleContentTypeId, $moduleContentId, $type, $typeId="") {
		if (!empty($moduleContentTypeId) && !empty($moduleContentId) && !empty($type)) {
			global $dbi,$login;

			// If admin return maximum
			if ($type=="User") {
				if ($login->isAdmin($typeId)) {
					return 7;
				}
			}
			
			// Get permission for module content
			$moduleContentTypePermissionLevel = 0;
			$result = $dbi->query("SELECT MAX(AdministerPermission),MAX(CommentPermission),MAX(CreatePermission),MAX(DeletePermission),MAX(EditPermission),MAX(PublishPermission),MAX(ReadPermission) FROM ".permissionTableName." WHERE (Type=".($type=="Users"?"'Visitors' OR Type='Users'":$dbi->quote($type)).")".(!empty($typeId)?" AND TypeId=".$dbi->quote($typeId):"")." AND ModuleContentTypeId=".$dbi->quote($moduleContentTypeId)." AND ModuleContentId=".$dbi->quote($moduleContentId));
			if ($result->rows()) {
				list($administrator,$comment,$create,$delete,$edit,$publish,$read) = $result->fetchrow_array();
				$moduleContentTypePermissionLevel = $this->getPermissionLevel($administrator,$comment,$create,$delete,$edit,$publish,$read);
			}
			return $moduleContentTypePermissionLevel;			
		}
		return 0;
	}	
	
	/**
	 * Get permission level in the system given permissions.
	 * @param	$administrator
	 * @param	$comment
	 * @param	$create
	 * @param	$delete
	 * @param	$edit
	 * @param	$publish
	 * @param	$read
	 */
	function getPermissionLevel($administrator,$comment,$create,$delete,$edit,$publish,$read) {
		// Determine permission type
		$permissionLevel = 0;
		if ($administrator==1) $permissionLevel = 7;
		else if ($edit==2 && $delete==2) $permissionLevel = 6;
		else if ($delete==1) $permissionLevel = 5;
		else if ($publish==1) $permissionLevel = 4;
		else if ($edit==1) $permissionLevel = 3;
		else if ($comment==1) $permissionLevel = 2;
		else if ($read==1) $permissionLevel = 1;		
		return $permissionLevel;
	}
	
	function getSavedPermission($moduleContentTypeId, $moduleContentId, $type, $typeId, $permissionType) {
		for ($i=0; $i<sizeof($this->savedPermissions); $i++) {
			if ($moduleContentTypeId==$this->savedPermissions[$i]["moduleContentTypeId"] &&
				$moduleContentId==$this->savedPermissions[$i]["moduleContentId"] &&
				$type==$this->savedPermissions[$i]["type"] &&
				$typeId==$this->savedPermissions[$i]["typeId"] &&
				$permissionType==$this->savedPermissions[$i]["permissionType"]) {
				return $this->savedPermissions[$i]["permission"];
			}
		}
		return -1;
	}	

	function hasAdministerPermission($moduleContentTypeId, $moduleContentId, $type="", $typeId="") {
		return $this->hasModulePermission($moduleContentTypeId, $moduleContentId, "Administer", $type, $typeId);
	}

	function hasCommentPermission($moduleContentTypeId, $moduleContentId, $type="", $typeId="") {
		return $this->hasModulePermission($moduleContentTypeId, $moduleContentId, "Comment", $type, $typeId);
	}

	function hasCreatePermission($moduleContentTypeId, $moduleContentId, $type="", $typeId="") {
		return $this->hasModulePermission($moduleContentTypeId, $moduleContentId, "Create", $type, $typeId);
	}

	function hasDeletePermission($moduleContentTypeId, $moduleContentId, $type="", $typeId="") {
		return $this->hasModulePermission($moduleContentTypeId, $moduleContentId, "Delete", $type, $typeId);
	}
	
	function hasEditPermission($moduleContentTypeId, $moduleContentId, $type="", $typeId="") {
		return $this->hasModulePermission($moduleContentTypeId, $moduleContentId, "Edit", $type, $typeId);
	}
	
	function hasModulePermission($moduleContentTypeId, $moduleContentId, $permissionType, $type, $typeId) {
		if (!empty($moduleContentTypeId) && !empty($permissionType)) {
			global $module;
			
			// If type or type identifier has not been set use current user
			if (empty($type) || empty($typeId)) {
				global $login;
				$type = "User";
				$typeId = $login->id;
			}

			// Check if saved permission exists
			$permission = $this->getSavedPermission($moduleContentTypeId, $moduleContentId, $type, $typeId, $permissionType);
			if ($permission!=-1) {
				return $permission;
			}
			else {
				global $dbi, $module;

				// Get module id
				$moduleId = $module->getModuleIdFromContentType($moduleContentTypeId);		
				
				// Get list of groups the user is part of
				$groupIds = "";
				if ($type=="User" && !empty($typeId)) {
					// Get groups the user is part of
					$result = $dbi->query("SELECT GroupId FROM ".userGroupRefTableName." WHERE UserId=".$dbi->quote($typeId));
					if ($result->rows()) {
						for ($i=0; list($groupId) = $result->fetchrow_array(); $i++) {
							$groupIds .= ($i!=0?",":"").$groupId;
						}
					}
				}

				// Check if user is a module administrator or has permission
				if ($type=="User" && !empty($moduleId) && !empty($typeId)) {
					if ($this->isModuleAdmin($moduleId, $typeId)) {					
						if ($permissionType=="Edit" || $permissionType=="Delete") return 2;
						return 1;
					}
				}
				
				$finalPermission = 0;

				// Get permission for permission type in this module
				$result = $dbi->query("SELECT MAX(".$permissionType."Permission) ".
									  "FROM ".permissionTableName." ".
									  "WHERE ((moduleContentTypeId=".$dbi->quote($moduleContentTypeId)." AND ".
									  "moduleContentId=".$dbi->quote($moduleContentId).") OR ".
									  "(moduleId=".$dbi->quote($moduleId).")) AND (".
									  (!empty($typeId)?"(type=".$dbi->quote($type)." AND typeId=".$dbi->quote($typeId).") OR ":"").
									  "(type='Visitors')".
									  (!empty($login->id)?" OR (type='Users')":"").
									  (!empty($groupIds)?" OR "."(type='Group' AND typeId IN (".$dbi->quote($groupIds)."))":"").
									  ")");
	
				if ($result->rows()) {
					list($permission) = $result->fetchrow_array();
					if ($permission>$finalPermission) $finalPermission = $permission;
				}
				
				// Get permission for content type parent (if any)
				$object = $module->getModuleContentTypeObject($moduleContentTypeId);
				if (!empty($object)) {
					if (method_exists($object, "getParentContentTypeId") && method_exists($object, "getParentId")) {
						$parentContentTypeId = $object->getParentContentTypeId();
						$parentContentId = $object->getParentId($moduleContentId);
						$permission = $this->hasModulePermission($parentContentTypeId, $parentContentId, $permissionType, $type, $typeId);
						if ($permission>$finalPermission) $finalPermission = $permission;
					}
				}

				// Save permission
				$this->savePermission($moduleContentTypeId, $moduleContentId, $type, $typeId, $permissionType, $finalPermission);
	
				// Return permission
				return $finalPermission;
			}
		}
		return 0;
	}	

	function hasPublishPermission($moduleContentTypeId, $moduleContentId, $type="", $typeId="") {
		return $this->hasModulePermission($moduleContentTypeId, $moduleContentId, "Publish", $type, $typeId);
	}

	function hasReadPermission($moduleContentTypeId, $moduleContentId, $type="", $typeId="") {
		return $this->hasModulePermission($moduleContentTypeId, $moduleContentId, "Read", $type, $typeId);
	}	
	
	/**
	  * Determine if the current user is an administrator.
	  * @param	$userId		Identifier of user (not required).
	  * @return true if the user is an administrator, false otherwise.
	  */
	function isAdmin($userId="") {
		if (empty($userId) || $this->id==$userId) {
			if ($this->isLoggedIn()) {
				return $this->administrator || $this->webmaster;
			}
		}
		else {
			global $dbi;
			$result = $dbi->query("SELECT administrator,webmaster FROM ".userTableName." WHERE id=".$dbi->quote($userId));
			if ($result->rows()) {
				list($administrator,$webmaster) = $result->fetchrow_array();
				return $administrator || $webmaster;
			}
		}
		return false;
	}
	
	/**
	 * Determine if the current user is logged in.
	 * @return true if logged in, false otherwise. 
	 */	
	function isLoggedIn() {
		return !empty($_SESSION["u_logged"])?$_SESSION["u_logged"]:false;
	}

	/**
	  * Is the current user an administrator for the current module?
	  * @param	$moduleId	Identifier of module.
	  * @param	$userId		Identifier of user (not required).
	  * @return	true if the user is an module administrator, false otherwise. 
	  */
	function isModuleAdmin($moduleId=0, $userId="") {
		global $dbi,$login;
		if (empty($userId)) {
			if (!$login->isLoggedIn()) return false;
			$userId = $login->id;
		}
		if ($login->isAdmin($userId)) {
			return true;
		}
		
		if (!empty($moduleId)) {				
			// Check if user is module administrator
			$result = $dbi->query("SELECT AdministerPermission FROM ".permissionTableName." WHERE Type='User' AND TypeId=".$dbi->quote($userId)." AND ModuleId=".$dbi->quote($moduleId)." AND ModuleContentTypeId=0 AND ModuleContentId=0");
			if ($result->rows()) {
				list($admin) = $result->fetchrow_array();
				if ($admin) {
					return true;
				}
			}
		}
		else {
			// Check if user has any admin permissions
			$result = $dbi->query("SELECT AdministerPermission FROM ".permissionTableName." WHERE Type='User' AND TypeId=".$dbi->quote($userId)." AND ModuleContentTypeId=0 AND ModuleContentId=0");
			if ($result->rows()) {
				return true;
			}
		}
		return false;
	}		
	
	/** 
	  * Determine if the current user is a registered user
	  * @return true if the user is a registered user, false otherwise.
	  */
	function isUser() {
		// Check if user is logged in
		if($this->isLoggedIn()) {
			return true;
		}
		return false;
	}
	
	/**
	  * Determine if the current user is a webmaster.
	  * @param	$userId		Identifier of user (not required).
	  * @return true if the user is a webmaster, false otherwise.
	  */
	function isWebmaster($userId="") {
		if (empty($userId)) {
			if ($this->isLoggedIn()) {
				return $this->webmaster;
			}
		}
		else {
			global $dbi;
			$result = $dbi->query("SELECT webmaster FROM ".userTableName." WHERE id=".$dbi->quote($userId));
			if ($result->rows()) {
				list($webmaster) = $result->fetchrow_array();
				return $webmaster;
			}
		}
		return false;
	}
		
	/** Logout function */
	function logout() {
		$_SESSION["u_id"] = "";
		$this->deleteCookie("Login");
		$this->resetSession();
	}
	
	/** 
	 * Print login form
	 * @param $gotoReferer Goto referer after succesfull login
	 */	
	function printLoginForm($gotoReferer=false, $errors=Array()) {
		global $site;
		
		// Include language
		include scriptPath."/include/language/".pageLanguage."/general.php";

		// Generate navigation
		$site->addNavigationLink(scriptUrl."/".fileLogin, $lLogin["Header"]);

		// Print common header
		$site->printHeader();
		
		// Include form
		include scriptPath."/include/form/loginForm.php";
		
		// Print common footer
		$site->printFooter();
	}
	
	/** Reset session variables. */
	function resetSession() {
		$_SESSION['u_id'] = 'NULL';
		$_SESSION['u_username'] = "";
		$_SESSION['u_cookie'] = 0;
		$_SESSION['u_logged'] = false;
		$_SESSION['u_remember'] = false;
		$_SESSION['u_lastLogged'] = mktime();
	}
	
	function savePermission($moduleContentTypeId, $moduleContentId, $type, $typeId, $permissionType, $permission) {
		$this->savedPermissions[] = 
			 array(
			  "moduleContentTypeId" => $moduleContentTypeId,
			  "moduleContentId" => $moduleContentId,
			  "type" => $type,
			  "typeId" => $typeId,
			  "permissionType" => $permissionType,
			  "permission" => $permission
			 );
	}	
	
	/** 
	 * Set a cookie that expires in one year.
	 * @param 	$name 	Name of cookie
	 * @param 	$value 	Value of cookie
	 */
	function sendCookie($name,$value) {
		if(!headers_sent()) setcookie($name,$value,time()+31104000,"/",".".getCurrentDomain());
	}
	
	
	/**
	 * Set module content permissions.
	 * @param	$moduleContentTypeId	Content type identifier.
	 * @param	$moduleContentId		Content identifier.
	 * @param	$type					Type to set permission for (User, Group, Visitors, Users).
	 * @param	$typeId					Identifier of type.
	 * @param	$administer				Admininster permission.
	 * @param	$comment				Comment permission.
	 * @param	$create					Create content permission.
	 * @param	$delete					Delete content permission (1=own content, 2=all user's content).
	 * @param	$edit					Edit content permission (1=own content, 2=all user's content).
	 * @param	$publish				Publish content permission.
	 * @param	$read					Read content permission.
	 */	
	function setModuleContentPermissions($moduleContentTypeId, $moduleContentId, $type, $typeId, $administrator, $comment, $create, $delete, $edit, $publish, $read) {
		if (!empty($moduleContentId) && !empty($moduleContentTypeId) && !empty($type)) {
			global $dbi;
			$result = $dbi->query("DELETE FROM ".permissionTableName." WHERE ModuleContentTypeId=".$dbi->quote($moduleContentTypeId)." AND ModuleContentId=".$dbi->quote($moduleContentId)." AND Type=".$dbi->quote($type).(!empty($typeId)?" AND TypeId=".$dbi->quote($typeId):""));
			if (!empty($administrator) || !empty($comment) || !empty($create) || !empty($delete) || !empty($edit) || !empty($publish) || !empty($read)) {
				if ($administrator) {
					$comment = 1;
					$create = 1;
					$delete = 2;
					$edit = 2;
					$publish = 1;
					$read = 1;
				}
				$dbi->query("INSERT INTO ".permissionTableName."(ModuleContentTypeId,ModuleContentId,Type,TypeId,AdministerPermission,CommentPermission,CreatePermission,DeletePermission,EditPermission,PublishPermission,ReadPermission) VALUES(".$dbi->quote($moduleContentTypeId).",".$dbi->quote($moduleContentId).",".$dbi->quote($type).",".$dbi->quote($typeId).",".$dbi->quote($administrator).",".$dbi->quote($comment).",".$dbi->quote($create).",".$dbi->quote($delete).",".$dbi->quote($edit).",".$dbi->quote($publish).",".$dbi->quote($read).")");		
			}
		}		
	}
		
	/**
	 * Set module permissions.
	 * @param	$moduleId	Module identifier.
	 * @param	$type		Type to set permission for (User, Group, Visitors, Users).
	 * @param	$typeId		Identifier of type.
	 * @param	$administer	Admininster permission.
	 * @param	$comment	Comment permission.
	 * @param	$create		Create content permission.
	 * @param	$delete		Delete content permission (1=own content, 2=all user's content).
	 * @param	$edit		Edit content permission (1=own content, 2=all user's content).
	 * @param	$publish	Publish content permission.
	 * @param	$read		Read content permission.
	 */
	function setModulePermissions($moduleId, $type, $typeId, $administer, $comment, $create, $delete, $edit, $publish, $read) {
		if (!empty($moduleId) && !empty($type) && !empty($typeId)) {
			global $dbi;
			$dbi->query("DELETE FROM ".permissionTableName." WHERE moduleId=".$dbi->quote($moduleId)." AND type=".$dbi->quote($type)." AND typeId=".$dbi->quote($typeId));
			if (!empty($administer) || !empty($comment) || !empty($create) || !empty($delete) || !empty($edit) || !empty($publish) || !empty($read)) {
				if ($administer) {
					$comment = 1;
					$create = 1;
					$delete = 2;
					$edit = 2;
					$publish = 1;
					$read = 1;
				}
				$dbi->query("INSERT INTO ".permissionTableName."(ModuleId,Type,TypeId,AdministerPermission,CommentPermission,CreatePermission,DeletePermission,EditPermission,PublishPermission,ReadPermission) VALUES(".$dbi->quote($moduleId).",".$dbi->quote($type).",".$dbi->quote($typeId).",".	$dbi->quote($administer).",".$dbi->quote($comment).",".$dbi->quote($create).",".$dbi->quote($delete).",".$dbi->quote($edit).",".$dbi->quote($publish).",".$dbi->quote($read).")");
			}
		}
	}	
	
	/** 
	 * Set session variables.
	 * @param 	$result 	Database result containing user values
	 * @param 	$remember 	Remember login across sessions
	 * @param 	$init 		Initialize user session.
	 */
	function setSession($result, $remember, $init=true) {
		global $dbi;

		// Get values from result set
		list($this->id,$this->username,$this->lastLogged,$this->cookie,$this->activated,$this->administrator,$this->webmaster,$this->name,$this->email) = $result->fetchrow_array();

		// If user is activated start session
		if ($this->activated) {		
			// If cookie is empty generate new
			if (empty($this->cookie)) {
				$this->cookie = $this->generateCookie();
				$dbi->query("UPDATE ".userTableName." SET cookie=".$dbi->quote($this->cookie).",registered=registered,lastLogged=lastLogged WHERE id=".$dbi->quote($this->id));
			}

			// Set session values
			$_SESSION['u_id'] = $this->id;
			$_SESSION['u_username'] = htmlspecialchars($this->username);
			$_SESSION['u_cookie'] = $this->cookie;
			$_SESSION['u_logged'] = true;
			$_SESSION['u_lastLoggedIn'] = $this->lastLogged;

			// Remember login? 
			if($remember) {
				$_SESSION['u_remember'] = true;
				$this->updateCookie($this->cookie, true);
			}

			// Update user values about timestamps, ip and session id in database
			$dbi->query("UPDATE ".userTableName." SET registered=registered,lastUpdated=lastUpdated".($init?",lastLogged=NOW(),session=".$dbi->quote(session_id()).",ip=".$dbi->quote($_SERVER['REMOTE_ADDR']):",lastLogged=lastLogged")." WHERE id=".$dbi->quote($this->id));
            return true;
		}
		return false;
	}
	
	/**
	 * Update cookie.
	 * @param 	$cookie 	Cookie to set
	 * @param 	$force 		Force update of cookie
	 */
	function updateCookie($cookie,$force=false) {
		$_SESSION['u_cookie'] = $cookie;
		if($_SESSION["u_remember"] OR $force) {
			$cookie = serialize(array($_SESSION["u_username"],$cookie));
			$this->sendCookie("Login",$cookie);
		}
	}

	/** 
	 * Check if a given username already exists.
	 * @param 	$username 	Username to check.
	 * @return true if username exist, false otherwise
	 */
	function userExists($username) {
		global $dbi;

		$result = $dbi->query("SELECT id FROM ".userTableName." WHERE username=".$dbi->quote($username));
		if($result->rows()) return true;
		return false;
	}
}
?>
