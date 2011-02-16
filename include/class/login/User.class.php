<?
/** 
 * Class User contains information about a user and contains functions to add,
 * delete and update users in the database.
 * @author	Kaspar Rosengreen Nielsen
 */
class User {
	var $id = 0;
	var $activated = false;
	var $administrator = 0;
	var $categoryId = 0;
	var $department = "";
	var $email = "";
	var $facebook = "";
	var $groupId = 0;
	var $hideEmail = 0;
	var $hideInUserlist = 0;
	var $hideOnlineStatus = 0;
	var $hideTelephone = 0;
	var $lastLogged = "";
	var $lastUpdated = "";
	var $linkname = "";
	var $linkurl = "";
	var $location = "";
	var $mobile = "";
	var $name = "";
	var $notifyAboutChanges = false;
	var $password = "";
	var $phone = "";
	var $position = "";
	var $profileText = "";
	var $registered = "";
	var $signature = "";
	var $twitter = "";
	var $username = "";
	var $webmaster = 0;

	/**
	 * User constructor
	 * @param 	$id 		Identifier of user.
	 * @param	$username	Username of user.
	 * @param	$email		Email of user.
	 */
	function User($id=0,$username="") {
		$this->init($id,$username);
	}
	
	/** 
	 * Change password for user.
	 * @param 	$password 	Password to set.
	 */ 
	function changePassword($password) {
		if(!empty($this->id)) {
			global $dbi,$errors,$login;
			
			// Check if data is submitted from the form		
			checkSubmitter();
		
			// Include language
			include scriptPath."/include/language/".pageLanguage."/admin.php";
		
			// Get values
			$changePasswordAdmin = getValue("changePasswordAdmin");
			$forgotPassword = getValue("forgotPassword");
			$key = getValue("key");
			$oldPassword = getValue("oldPassword");
			$password = getValue("password");
			$repeatedPassword = getValue("repeatedPassword");

			// Check if old password is correct
			$result = $dbi->query("SELECT password,activationKey FROM ".userTableName." WHERE id=".$dbi->quote($this->id));
			if ($result->rows()) {
				list ($oldPassword1,$activationKey) = $result->fetchrow_array();

				if (!$changePasswordAdmin && $login->isWebmaster()) {
					if (!empty($forgotPassword)) {
						if ($key != $activationKey) {
							$errors->addError("oldPassword", $lForgotPassword["InvalidKey"]);					
						}
					}
					else if (empty($oldPassword)) {
						$errors->addError("oldPassword", $lEditUser["MissingOldPassword"]);
					}
					else {
						if (md5($oldPassword)!=$oldPassword1) $errors->addError("oldPassword", $lEditUser["WrongPassword"]);
					}
				}

				if (empty($password)) {
					$errors->addError("password", $lEditUser["MissingPassword"]);
				}
				else {
					if (empty($repeatedPassword)) {
						$errors->addError("repeatedPassword", $lEditUser["MissingRepeatedPassword"]);
					}
					else {
						if ($password != $repeatedPassword) {
							$errors->addError("password", $lEditUser["DifferentPasswords"]);
						}
					}
				}
								
				if (!$errors->hasErrors()) {
					$dbi->query("UPDATE ".userTableName." SET registered=registered,lastLogged=lastLogged,lastUpdated=NOW(),password=".$dbi->quote(md5(trim($password))).",activationKey='' WHERE id=".$dbi->quote($this->id));
				}	
			}
			return $errors;
		}
	}
	
	/** Delete user from database. */
	function deleteUser() {
		if(!empty($this->id)) {
			if ($this->hasDeletePermission()) {
				global $dbi, $log;

				// Check if data is submitted from the form			
				checkSubmitter();
				
				// Delete log data
				$log->deleteTransaction(userModuleId, $this->id);
				
				// Delete user data from database
				$dbi->query("DELETE FROM ".userTableName." WHERE id=".$dbi->quote($this->id));	
				$dbi->query("DELETE FROM ".userDataTableName." WHERE id=".$dbi->quote($this->id));

				// Delete references to groups
				$dbi->query("DELETE FROM ".userGroupRefTableName." WHERE userId=".$dbi->quote($this->id));

				// Delete permissions for user
				$dbi->query("DELETE FROM ".permissionTableName." WHERE Type='User' AND TypeId=".$dbi->quote($this->id));

				// Delete user picture	
				if(file_exists(scriptPath."/".folderUploadedFiles."/user_".$this->id.".jpg")) unlink(scriptPath."/".folderUploadedFiles."/user_".$this->id.".jpg");
			}
		}
	}
	
	/**
	 * Get module content type identifier for users.
	 * @return module content type identifier for users.
	 */
	function getModuleContentTypeId() {
		return userContentTypeId;
	}
	
	/**
	 * Get link to user.
	 * @param	$id	Identifier of user.
	 * @return link to user.
	 */
	function getLink($id="") {
		if (!empty($id)) {
			$user = new User($id);
			return $user->getProfileLink();
		}
		return scriptUrl."/".folderUsers;
	}
		
	/**
	 * Get name of user.
	 * @param	$id	Identifier of user.
	 * @return name of user.
	 */
	function getName($id="") {
		if (!empty($id)) {
			$user = new User($id);
			return $user->name;
		}
		else {
			global $lContentType;
			return $lContentType["User"];
		}
	}
		
	/**
	  * Get number of users.
	  * @return	number of users. 
	  */
	function getNumberOfUsers() {
		global $dbi;
		
		$result = $dbi->query("SELECT COUNT(*) FROM ".userTableName);
		if ($result->rows()) {
			list($count) = $result->fetchrow_array();
			return $count;
		}
		return 0;
	}

	/**
	 * Get link to profile.
	 * @return link to profile.
	 */
	function getProfileLink() {
		return scriptUrl."/".fileUserProfile."?profileId=".$this->id;
	}
	
	/**
	 * Get link to user image.
	 * @param	$width	Width to resize image to.
	 * @param	$height	Height to resize image to.
	 * @param	$crop	Should image be cropped.
	 */
	function getUserImage($width=0, $height=0, $crop=false) {
		global $cache;
		if (file_exists(scriptPath.'/'.folderUploadedFiles.'/user_'.$this->id.'.jpg')) {
			$imageUrl = $cache->generateThumbnail('', folderUploadedFiles."/user_".$this->id.".jpg", "user_".$this->id, $width, $height, true, false, $crop);
			if (!empty($imageUrl)) return $imageUrl;
		}
		/*if (file_exists(scriptPath."/".folderUploadedFiles."/user_".$this->id.".jpg")) {
			if (!empty($width) || !empty($height)) {
				if (!empty($width)) {
					$dimensions = getImageDimensions(scriptPath."/".folderUploadedFiles."/user_".$this->id.".jpg");
					$ratio = $width/$dimensions[0];
					$height = $dimensions[1]*$ratio;
				}
				else if (!empty($height)) {
					$dimensions = getImageDimensions(scriptPath."/".folderUploadedFiles."/user_".$this->id.".jpg");
					$ratio = $height/$dimensions[1];
					$width = $dimensions[0]*$ratio;
				}
				resizeToFile(scriptPath."/".folderUploadedFiles."/user_".$this->id.".jpg", $width, $height, cachePath."/user_".$this->id."_".$width."_".$height.($crop?"_crop":"").".jpg", 100, false, $crop);			
				return cacheUrl."/user_".$this->id."_".$width."_".$height.".jpg";
			}
			return scriptUrl."/".folderUploadedFiles."/user_".$this->id.".jpg";
		}*/
 		return iconUrl."/profile.jpg";
	}
	
	/**
	 * Initialize User object.
	 * @param	$id			User identifier.
	 * @param	$username	Username of user.
	 * @param	$email		Email of user.
	 */
	function init($id=0,$username="",$email="") {
		global $dbi;

		// Fetch user data
		if(!empty($id) || !empty($username)) {
			$result = $dbi->query("SELECT id,username,groupId,UNIX_TIMESTAMP(registered),UNIX_TIMESTAMP(lastUpdated),UNIX_TIMESTAMP(lastLogged),administrator,webmaster,activated ".
								  "FROM ".userTableName." ".
								  "WHERE ".(!empty($id) ? "id=".$dbi->quote($id) : (!empty($username) ? "username=".$dbi->quote($username) : "")));
			if($result->rows()) {
				list($this->id,$this->username,$this->groupId,$this->registered,$this->lastUpdated,$this->lastLogged,$this->administrator,$this->webmaster,$this->activated) = $result->fetchrow_array();

				// Fetch user information
				$result = $dbi->query("SELECT categoryId,name,email,phone,mobile,linkurl,linkname,facebook,twitter,location,department,position,profileText,signature,hideEmail,hideTelephone,hideInUserlist,hideOnlineStatus,notifyAboutChanges ".
									  "FROM ".userDataTableName." ".
									  "WHERE id=".$dbi->quote($this->id));
				if($result->rows()) {
					list($this->categoryId,$this->name,$this->email,$this->phone,$this->mobile,$this->linkurl,$this->linkname,$this->facebook,$this->twitter,$this->location,$this->department,$this->position,$this->profileText,$this->signature,$this->hideEmail,$this->hideTelephone,$this->hideInUserlist,$this->hideOnlineStatus,$this->notifyAboutChanges) = $result->fetchrow_array();
					$this->name = parseString($this->name);
					$this->linkname = parseString($this->linkname);
					$this->linkurl = parseString($this->linkurl);
					$this->location = parseString($this->location);
					$this->position = parseString($this->position);
					$this->profileText = parseString($this->profileText);
					$this->signature = parseString($this->signature);
					$this->department = parseString($this->department);
				}
			}
		}		
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
	  * Save user in database.
	  * @param	$readPost	Read values from post.
	  * @param	$validate	Validate input values.
	  * @return ErrorList object if there were errors.	
	  */
	function saveUser($readPost=true, $validate=true) {
		global $dbi, $errors, $group, $log, $login, $module, $settings;
		
		// Include language
		include scriptPath."/include/language/".pageLanguage."/admin.php";
		include scriptPath."/include/language/".pageLanguage."/general.php";
		
		// Save values into this user object
		if ($readPost) {
			if (empty($this->id)) $this->username = getValue("u_username");
			if ($login->isWebmaster()) {
				$this->activated = getValue("u_activated");
				$this->activated = !$this->activated;
			}
			$this->groupId = getValue("u_groupId");
			$this->name = getValue("u_name");
			$this->email = getValue("u_email");
			$this->phone = getValue("u_phone");
			$this->mobile = getValue("u_mobile");
			$this->facebook = getValue("u_facebook");
			$this->twitter = getValue("u_twitter");
			$this->linkurl = getValue("u_linkurl");
			$this->linkname = getValue("u_linkname");
			$this->location = getValue("u_location");
			$this->department = getValue("u_department");
			$this->position = getValue("u_position");
			$this->profileText = parseHtml(getValue("u_profileText"),2);
			$this->signature = getValue("u_signature");
			$this->hideEmail = getValue("u_hideEmail");
			$this->hideTelephone = getValue("u_hideTelephone");
			$this->hideInUserlist = getValue("u_hideInUserlist");
			$this->hideOnlineStatus = getValue("u_hideOnlineStatus");
			$this->notifyAboutChanges = getValue("u_notifyAboutChanges");
			$this->categoryId = getValue("categoryId");
			if (empty($this->id)) {
				$this->password = getValue("u_passwd");
				$repeatedPassword = getValue("u_repeated_passwd");
			}
			$groups = getValue("u_groups");
			$profile = getValue("profile");
		}
		
		if ($validate) {
			// Check submitter
			checkSubmitter(scriptUrl);

			if ($this->hasAdministerPermission() && !$profile) {
				$userType = getValue("userType");
				$this->administrator = 0;
				$this->webmaster = 0;
				
				if (!empty($userType)) {
					switch ($userType) {
						case 1: // Webmaster
							$this->webmaster = 1;
							break;
						case 2: // Administrator
							$this->administrator = 1;
							break;
					}
				}
			}
		
			// Validate username
			$this->validateUsername($this->username);

			// Validate full name
			if (empty ($this->name)) $errors->addError("name", $lEditUser["MissingFullName"]);
			
			// Validate email
			if (!$login->isWebmaster()) {
				if (empty ($this->email)) $errors->addError("email",$lEditUser["MissingEmail"]);
			}
			
			// Validate email is valid and not already registered
			if (!empty ($this->email)) {
				if (!checkEmail($this->email)) $errors->addError("email", $lEditUser["InvalidEmail"]);
				else {
					$result = $dbi->query("SELECT id FROM ".userDataTableName." WHERE ".(!empty($this->id)?"id!=".$dbi->quote($this->id)." AND ":"")."email=".$dbi->quote($this->email));
					if ($result->rows()) $errors->addError("email", $lEditUser["EmailExists"]);
				}
			}	
	
			// Validate password
			if (empty($this->id)) $this->validatePassword($this->password,$repeatedPassword);
	
			// Validate code
			if (empty($this->id) && !$this->hasAdministerPermission() && $settings->requireValidation) {
				if (!audit()) {
					$errors->addError("validation", $lEditUser["WrongValidation"]);	
				}
			}
		}
			
		// If no errors save user data
		if (!$errors->hasErrors()) {
			// Check if user category exists
			if (!empty($this->categoryId)) {
				$result = $dbi->query("SELECT Id FROM ".userCategoryTableName." WHERE Id=".$dbi->quote($this->categoryId)." OR Title=".$dbi->quote($this->categoryId));
				if ($result->rows()) {
					list($this->categoryId) = $result->fetchrow_array();				
				}
				else {
					// Get max position
					$position = 0;
					$result = $dbi->query("SELECT MAX(Position) FROM ".userCategoryTableName);
					if ($result->rows()) {
						list($maxPosition) = $result->fetchrow_array();
						$position = $maxPosition + 1;
					}
					
					// Insert the new category
					$dbi->query("INSERT INTO ".userCategoryTableName."(Title,Position) VALUES(".$dbi->quote($this->categoryId).",".$dbi->quote($position).")");
					$this->categoryId = $dbi->getInsertId();
				}
			}
			
			if (!empty($this->id)) {
				// Update basic user information
				$dbi->query("UPDATE ".userTableName." SET ".(!empty($this->username)?"username=".$dbi->quote(trim($this->username)).",":"")."groupId=".$dbi->quote($this->groupId).",registered=registered,lastLogged=lastLogged,lastUpdated=NOW()".(!empty($this->password)?",password=".$dbi->quote(md5(trim($this->password))):"").",administrator=".$dbi->quote($this->administrator).",webmaster=".$dbi->quote($this->webmaster).",activated=".$dbi->quote($this->activated)." WHERE id=".$this->id);			
	
				// Update information about user
				$dbi->query("UPDATE ".userDataTableName." SET categoryId=".$dbi->quote($this->categoryId).",name=".$dbi->quote($this->name).",email=".$dbi->quote($this->email).",phone=".$dbi->quote($this->phone).",mobile=".$dbi->quote($this->mobile).",linkurl=".$dbi->quote($this->linkurl).",linkname=".$dbi->quote($this->linkname).",facebook=".$dbi->quote($this->facebook).",twitter=".$dbi->quote($this->twitter).",location=".$dbi->quote($this->location).",department=".$dbi->quote($this->department).",position=".$dbi->quote($this->position).",profileText=".$dbi->quote($this->profileText).",signature=".$dbi->quote($this->signature).",hideEmail=".$dbi->quote($this->hideEmail).",hideTelephone=".$dbi->quote($this->hideTelephone).",hideInUserlist=".$dbi->quote($this->hideInUserlist).",hideOnlineStatus=".$dbi->quote($this->hideOnlineStatus).",notifyAboutChanges=".$dbi->quote($this->notifyAboutChanges)." WHERE id=".$this->id);
			}
			else {
				// Generate cookie
				$cookie = $login->generateCookie();
				
				if (!$login->isLoggedIn()) {
					// Generate random string
					if ($settings->activateWithEmail) $activationKey = generateRandomString(32);

					// Insert data into database
					$dbi->query("INSERT INTO ".userTableName." (username,password,groupId,cookie,webmaster,administrator,activated,activationKey) VALUES(".$dbi->quote(trim($this->username)).",".$dbi->quote(md5(trim($this->password))).",".$dbi->quote($this->groupId).",".$dbi->quote($cookie).",0,0,".($settings->activateWithEmail && !$this->activated?0:1).",".($settings->activateWithEmail?$dbi->quote($activationKey):"''").")");
				}
				else {
					// Insert data into database
					$dbi->query("INSERT INTO ".userTableName." (username,password,groupId,cookie,webmaster,administrator,activated) VALUES(".$dbi->quote(trim($this->username)).",".$dbi->quote(md5(trim($this->password))).",".$dbi->quote($this->groupId).",".$dbi->quote($cookie).",".$dbi->quote($this->webmaster).",".$dbi->quote($this->administrator).",1)");
				}
				
				// Get new id of user
				$this->id = $dbi->getInsertId();
				
				// Insert user information
				$dbi->query("INSERT INTO ".userDataTableName."(id,categoryId,name,email,phone,mobile,linkurl,linkname,facebook,twitter,location,department,position,profileText,signature,hideEmail,hideTelephone,hideOnlineStatus,notifyAboutChanges) VALUES(".$this->id.",".$dbi->quote($this->categoryId).",".$dbi->quote($this->name).",".$dbi->quote($this->email).",".$dbi->quote($this->phone).",".$dbi->quote($this->mobile).",".$dbi->quote($this->linkurl).",".$dbi->quote($this->linkname).",".$dbi->quote($this->facebook).",".$dbi->quote($this->twitter).",".$dbi->quote($this->location).",".$dbi->quote($this->department).",".$dbi->quote($this->position).",".$dbi->quote($this->profileText).",".$dbi->quote($this->signature).",".$dbi->quote($this->hideEmail).",".$dbi->quote($this->hideTelephone).",".$dbi->quote($this->hideOnlineStatus).",".$dbi->quote($this->notifyAboutChanges).")");

				// Send mail to registered user
				if (!$login->isLoggedIn() && $settings->activateWithEmail) {
                                    // Send registration email
                                    $mail = new phpmailer();
                                    $mail->CharSet = "UTF-8";
                                    $mail->From     = pageAdminMail;
                                    $mail->FromName = pageTitle;
                                    $mail->Subject  = sprintf($lEditUser["WelcomeEmailSubject"],pageTitle);
                                    $mail->Body     = sprintf($lEditUser["WelcomeEmailText"], $this->name, scriptUrl."/".fileProfileActivate."?id=".$this->id."&activate=1&activationKey=".$activationKey);
                                    $mail->IsHTML(false);
                                    $mail->AddAddress($this->email);
                                    $mail->Send();
				}

                                // Notify listeners that user was inserted
                                if (function_exists("userInserted")) {
                                    userInserted($this->id);
                                }
			}
			
			// Set permissions for user
			if ($this->hasAdministerPermission() && !$profile) {	
				if (!empty($userType)) {
					// Remove permissions if any
					$dbi->query("DELETE FROM ".permissionTableName." WHERE moduleContentTypeId='' AND moduleContentId='' AND type='User' AND typeId=".$dbi->quote($this->id));

					// If module administrator set permissions
					if ($userType==3) {
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
																	 "User", 
																	 $this->id, 
																	 $administrator,
																	 $comment, 
																	 $create, 
																	 $delete, 
																	 $edit, 
																	 $publish, 
																	 $read
																	 );							
									}
								}
							}
						}
					}
				}
				
				// Add to groups
				$group->deleteGroupRefs($this->id);
				if (!empty($groups)) {
					for ($i=0; $i<sizeof($groups); $i++) {
						$group->addToGroup($groups[$i], $this->id);
					}
				}				
			}
			
			// Upload index picture
			if (!empty($_FILES["img_0"]["tmp_name"])) {
				$size = getImageDimensions($_FILES["img_0"]["tmp_name"]);
				$height = $size[1]*(150/$size[0]);
				resizeToFile($_FILES["img_0"]["tmp_name"], 150, $height, scriptPath."/".folderUploadedFiles."/user_".$this->id.".jpg", 100);
			}
			
			// Call any custom sections
			global $site;
			if (!empty($site->editUserSections)) {
				for ($i=0; $i<sizeof($site->editUserSections); $i++) {
					if (function_exists($site->editUserSections[$i]["saveFunction"])) {
						$site->editUserSections[$i]["saveFunction"]($this->id);
					}
				}
			}

			// Log transaction
			$log->logTransaction(userContentTypeId, $this->id);
		}
		else {
			if (!empty($this->password)) {
				$errors->addError("reenterPassword", $lEditUser["ReenterPasswords"]);
			}
			if (!empty($_FILES["img_0"]["tmp_name"])) {
				$errors->addError("upload", $lErrors["ReUploadImages"]);			
			}
		}
		
		// Return list of errors
		return $errors;
	}
	
	public function validatePassword($password, $repeatedPassword) {
		global $dbi, $errors;
		global $lEditUser;
		if (!empty($this->password) || empty($this->id)) {
			if (empty ($this->password) && empty ($this->id)) {
				$errors->addError("password", $lEditUser["MissingPassword"]);
			}
			if ($this->password != $repeatedPassword) {
				$errors->addError("repeatedPassword", $lEditUser["DifferentPasswords"]);
			}
		}
	}
	
	/**
	 * Validate username
	 * @param $username Username
	 */
	public function validateUsername($username="") {
		global $dbi, $errors;
		global $lEditUser;
		
		// Validate username
		if (empty ($this->username)) $errors->addError("username", $lEditUser["MissingUsername"]);

		// Validate username does not exist
		$result = $dbi->query("SELECT id FROM ".userTableName." WHERE ".(!empty($this->id)?"id!=".$dbi->quote($this->id)." AND ":"")."username=".$dbi->quote($this->username));
		if ($result->rows()) $errors->addError("username", $lEditUser["UsernameExists"]);		
	}
}
?>