<?
/** 
 * Class Comment contains values of a comment and methods for adding, updating, 
 * deleting and printing comments.
 * @author	Kaspar Rosengreen Nielsen
 */
class Comment extends ModuleContentType {
	public $ip = 0;
	public $link = "";
	public $mail = "";
	public $message = "";
	public $moduleId = 0;
	public $moduleContentTypeId = 0;
	public $moduleContentId = 0;
	public $name = "";
	public $posted = 0;
	public $spam = 0;
	public $subject = "";
	public $userId = 0;
	
	public $disableComments = "";
	public $errors = null;
	public $language = "";	

	/** 
	 * Comment constructor.
	 * @param 	$id 	Identifier of comment.
	 */ 
	function __construct($id = 0) {
		parent::__construct("commentModuleId", "commentContentTypeId");
	
		// Initialize values		
		$this->init($id);
	}

	/** Delete this comment. */
	function deleteComment($permanent=false) {
		if (!empty($this->id)) {
			if ($this->hasDeletePermission()) {
				global $dbi;

				// Delete from database
				if ($permanent) $dbi->query("DELETE FROM ".commentTableName." WHERE id=".$dbi->quote($this->id));
				else $dbi->query("UPDATE ".commentTableName." SET posted=posted,trash=1 WHERE id=".$dbi->quote($this->id));
			}
		}
	}
	
	/** 
	 * Delete all comments associated with the given resource.
	 * @param	$moduleId				Identifier of module.
	 * @param	$moduleContentTypeId	Identifier of content type.
	 * @param	$moduleContentId		Identifier of content resource.
	 */
	function deleteComments($moduleId, $moduleContentTypeId, $moduleContentId) {
		if (!empty($moduleId) && !empty($moduleContentTypeId) && !empty($moduleContentId)) {
			if ($this->hasDeletePermission()) {
				global $dbi;
	
				// Delete from database
				$dbi->query("DELETE FROM ".commentTableName." WHERE moduleId=".$dbi->quote($moduleId)." AND moduleContentTypeId=".$dbi->quote($moduleContentTypeId)." AND moduleContentId=".$dbi->quote($moduleContentId));
			}
		}		
	}
	
	/**
	 * Delete all comments marked as spam from a given module.
	 * @param	$moduleId	Module identifier.
	 */
	function deleteSpamComments($moduleId="") {
		if ($this->hasDeletePermission()) {
			global $dbi;
			
			$result = $dbi->query("SELECT id FROM ".commentTableName." WHERE ".(!empty($moduleId)?"moduleId=".$dbi->quote($moduleId)." AND ":"")."spam='1'");
			if ($result->rows()) {
				for ($i=0; (list($id) = $result->fetchrow_array()); $i++) {
					$comment = new Comment($id);
					$comment->deleteComment(true);
				}
			}
		}
	}
	
	/**
	 * Delete all comments marked as trash from a given module.
	 * @param	$moduleId	Module identifier.
	 */
	function deleteTrashComments($moduleId="") {
		if ($this->hasDeletePermission()) {
			global $dbi;
			
			$result = $dbi->query("SELECT id FROM ".commentTableName." WHERE ".(!empty($moduleId)?"moduleId=".$dbi->quote($moduleId)." AND ":"")."trash='1'");
			if ($result->rows()) {
				for ($i=0; (list($id) = $result->fetchrow_array()); $i++) {
					$comment = new Comment($id);
					$comment->deleteComment(true);
				}
			}
		}
	}
	
	/** Get link to comment. */	
	function getCommentLink() {
		if (!empty($this->id) && !empty($this->moduleContentTypeId) && !empty($this->moduleContentId)) {
			global $module;
			
			$moduleContentTypeObject = $module->getModuleContentTypeObject($this->moduleContentTypeId);
			if (method_exists($moduleContentTypeObject, "getLink")) {
				$link = $moduleContentTypeObject->getLink($this->moduleContentId);
				$link .= "#".$this->id;
				return $link;
			}
		}
		return "";
	}
		
	/**
	 * Get link for comment.
	 * @return link for comment.
	 */
	function getLink($id="") {
		if (!empty($id)) {
			$comment = new Comment($id);
			return $comment->getCommentLink();
		}
		return scriptUrl."/".folderComment."/".fileCommentIndex;
	}

	/**
	 * Get name of a given comment.
	 * @param	$id	Identifier of comment.
	 * @return name of comment.
	 */		
	function getName($id="") {
		if (!empty($id)) {
			$comment = new Comment($id);
			return $category->subject;
		}
		else {
			global $lContentType;
			return $lContentType["Comment"];
		}
	}
		
	/**
	 * Get number of comments for a given resource in a module.
	 * @param	$moduleId				Module identifier.
	 * @param	$moduleContentTypeId	Type identifier.
	 * @param	$moduleContentId		Module content identifier.
	 * @return	number of comments for resource.
	 */
	function getNumberOfComments($moduleId=0, $moduleContentTypeId=0, $moduleContentId=0, $searchQuery="") {
		global $dbi;
		$result = $dbi->query("SELECT COUNT(*) FROM ".commentTableName." WHERE ".(!empty($searchQuery)?$searchQuery." AND ":"").(!empty($moduleId)?"moduleId=".$dbi->quote($moduleId)." AND ":"").(!empty($moduleContentTypeId)?"moduleContentTypeId=".$dbi->quote($moduleContentTypeId)." AND ":"").(!empty($moduleContentId)?"moduleContentId=".$dbi->quote($moduleContentId)." AND ":"")."spam=0 AND trash=0");
		if ($result->rows()) {
			list($count) = $result->fetchrow_array();
			$result->finish();
			return $count;
		}		
		$result->finish();
		return 0;
	}

	/**
	 * Get number of comments marked as spam for a given resource in a module.
	 * @param	$moduleId			Module identifier.
	 * @param	$moduleContentId	Module content identifier.
	 * @return	number of comments marked as spam.
	 */
	function getNumberOfSpamComments($moduleId=0, $moduleContentTypeId=0, $moduleContentId=0, $searchQuery="") {
		global $dbi;
		$result = $dbi->query("SELECT COUNT(*) FROM ".commentTableName." WHERE ".(!empty($searchQuery)?$searchQuery." AND ":"").(!empty($moduleId)?"moduleId=".$dbi->quote($moduleId)." AND ":"").(!empty($moduleContentTypeId)?"moduleContentTypeId=".$dbi->quote($moduleContentTypeId)." AND ":"").(!empty($moduleContentId)?"moduleContentId=".$dbi->quote($moduleContentId)." AND ":"")."spam=1");
		if ($result->rows()) {
			list($count) = $result->fetchrow_array();
			$result->finish();
			return $count;
		}
		$result->finish();
		return 0;
	}
	
	/**
	 * Get number of comments marked as trash for a given resource in a module.
	 * @param	$moduleId			Module identifier.
	 * @param	$moduleContentId	Module content identifier.
	 * @return	number of comments marked as trash.
	 */
	function getNumberOfTrashComments($moduleId=0, $moduleContentTypeId=0, $moduleContentId=0, $searchQuery="") {
		global $dbi;
		$result = $dbi->query("SELECT COUNT(*) FROM ".commentTableName." WHERE ".(!empty($searchQuery)?$searchQuery." AND ":"").(!empty($moduleId)?"moduleId=".$dbi->quote($moduleId)." AND ":"").(!empty($moduleContentTypeId)?"moduleContentTypeId=".$dbi->quote($moduleContentTypeId)." AND ":"").(!empty($moduleContentId)?"moduleContentId=".$dbi->quote($moduleContentId)." AND ":"")."trash=1");
		if ($result->rows()) {
			list($count) = $result->fetchrow_array();
			$result->finish();
			return $count;
		}
		$result->finish();
		return 0;
	}	
	
	/** Does the current user have administer permission? */	
	function hasAdministerPermission() {
		global $login;
		return $login->hasAdministerPermission(commentContentTypeId, $this->id) || $login->hasAdministerPermission($this->moduleContentTypeId, $this->moduleContentId);
	}

	/** Does the current user have comment permission? */	
	function hasCommentPermission() {
		global $login;
		return $login->hasCommentPermission(commentContentTypeId, $this->id) || $login->hasCommentPermission($this->moduleContentTypeId, $this->moduleContentId);
	}

	/** Does the current user have delete permission? */	
	function hasDeletePermission() {
		global $login;
		return $login->hasDeletePermission(commentContentTypeId, $this->id) || $login->hasDeletePermission($this->moduleContentTypeId, $this->moduleContentId);
	}
	
	/** Does the current user have edit permission? */	
	function hasEditPermission() {
		global $login;
		return $login->hasEditPermission(commentContentTypeId, $this->id) || $login->hasEditPermission($this->moduleContentTypeId, $this->moduleContentId);
	}
	
	/** 
	 * Initialize object. 
	 * @param	$id		Comment identifier.
	 */
	function init($id=0) {
		global $errors;
		
		// Initialize errors object
		$this->errors = $errors;
		
		if (!empty($id)) {
			global $dbi;
	
			// Fetch comment
			$result = $dbi->query("SELECT id,moduleId,moduleContentId,moduleContentTypeId,userId,name,mail,link,subject,message,UNIX_TIMESTAMP(posted),ip,spam FROM ".commentTableName." WHERE id=".$dbi->quote($id));
			if ($result->rows()) {
				list ($this->id, $this->moduleId, $this->moduleContentId, $this->moduleContentTypeId, $this->userId, $this->name, $this->mail, $this->link, $this->subject, $this->message, $this->posted, $this->ip, $this->spam) = $result->fetchrow_array();
				
				// Parse strings
				$this->name = parseString($this->name);
				$this->subject = parseString($this->subject);
				$this->message = parseString($this->message);
			}
			else {
				$this->id = 0;
			}
	
			// Free result
			$result->finish();
		}
		
		// Set language to default
		$this->language = pageLanguage;		
	}
	
	/**
	 * Print comments.
	 * @param 	$moduleId				Module identifier.
	 * @param	$moduleContentTypeId	Type identifier.
	 * @param	$moduleContentId		Module content identifier.
	 * @param	$replySubject			Subject to put in form.
	 * @param	$formURL				URL of post form.
	 * @param	$navPath				Navigation path.
	 */
	function printComments($moduleId, $moduleContentTypeId, $moduleContentId, $replySubject, $formURL, $navPath) {
		global $dbi,$login,$module,$settings;
		
		// Include language
		include scriptPath."/include/language/".$this->language."/admin.php";
		include scriptPath."/include/language/".$this->language."/general.php";

		// Get page number
		$page = !empty($_GET["page"])?$_GET["page"]-1:0;

		// Include comments header template
		if (file_exists(layoutPath."/template/commentsHeader.template.php")) {
			include layoutPath."/template/commentsHeader.template.php";
		}
		else {
			include scriptPath."/include/template/commentsHeader.template.php";
		}
		
		// TODO: Add pages: LIMIT ".($page*15).",".($page*15+15)
		$result = $dbi->query("SELECT id FROM ".commentTableName." WHERE moduleId=".$dbi->quote($moduleId)." AND moduleContentTypeId=".$dbi->quote($moduleContentTypeId)." AND moduleContentId=".$dbi->quote($moduleContentId)." AND spam=0 AND trash=0 ORDER BY posted ASC");
		if ($result->rows()) {
			$comment = new Comment();
			for ($i=0; list($id) = $result->fetchrow_array(); $i++) {
				$comment->init($id);
				$commentAdmin = $comment->hasEditPermission();
				$user = null;
				if (!empty($comment->userId)) {
					$user = new User($comment->userId);
					$name = $user->username;
				}
				else {
					$name = $comment->name;
				}
				$lastComment = $i-1==$result->rows();

				// Include template
				if (file_exists(layoutPath."/template/comment.template.php")) {
					include layoutPath."/template/comment.template.php";
				}
				else {
					include scriptPath."/include/template/comment.template.php";
				}
			}
		}
		else {
			echo "<i>".$lComment["NoComments"]."</i><br />";
		}

		// Fetch user data
		if (!empty ($login->id)) {
			$user = new User($login->id);
			$name = $user->name;
			$mail = $user->email;
			$link = $user->linkurl;
			$remember = 0;
		}
		else if (!empty($_COOKIE["commentPoster"])) {
			$poster = unserialize(stripslashes(stripslashes($_COOKIE["commentPoster"])));
			$name = $poster["name"];
			$mail = $poster["mail"];
			$link = $poster["link"];	
			$remember = $poster["remember"];				
		}
		else {
			// Reset values
			$name = "";
			$mail = "";
			$link = "";
			$remember = 0;				
		}

		if (!empty($_POST["name"])) $name = $_POST["name"];
		if (!empty($_POST["mail"])) $mail = $_POST["mail"];
		if (!empty($_POST["subject"])) $subject = $_POST["subject"];
		if (!empty($_POST["message"])) $message = $_POST["message"];
		if (!empty($_POST["remember"])) $remember = $_POST["remember"];

		// Disable comments for this user
		$disableForm = $this->disableComments || !$login->hasCommentPermission($moduleContentTypeId, $moduleContentId);

		// Include comment form
		if (file_exists(layoutPath."/template/commentForm.php")) include layoutPath."/template/commentForm.php";
		else include scriptPath."/include/form/commentForm.php";
		
		// Create page indexing
		$pages = printPageIndex($navPath."&amp;showComments=1&amp;",$page,$this->getNumberOfComments($moduleId, $moduleContentId),15,"","comments");
	
		// Print page navigation
		if (!empty($pages)) echo "<p align=\"center\">$pages</p>";
		
		// Free result
		$result->finish();			
	}
		
	/** 
	 * Save comment in database
	 * @param 	$moduleId 				Module id to add comment to.
	 * @param 	$moduleContentTypeId 	Identifier of content type.
	 * @param	$moduleContentId		Identifier of content.
	 * @return 	List of errors if any.
	 */ 
	function saveComment($moduleId, $moduleContentTypeId, $moduleContentId) {
		global $dbi,$errors,$login,$referer,$settings,$spamFilter;	
		global $lComment, $lEditComment;

		// Check if data is submitted from the form
		checkSubmitter();

		// Get user ip
		$ip = getenv("REMOTE_ADDR");		

		// Get values
		$this->moduleId = $moduleId;
		$this->moduleContentTypeId = $moduleContentTypeId;
		$this->moduleContentId = $moduleContentId;
		$this->name = parseString(stripHtml(getValue("name")));
		$this->mail = parseString(stripHtml(getValue("mail")));
		$this->link = parseString(stripHtml(getValue("link")));
		$this->subject = parseString(stripHtml(getValue("subject")));
		$this->message = parseString(stripHtml(getValue("message")));
		$this->spam = getValue("spam");
		$this->userId = getValue("userId");
		
		// Get default name
		$defaultName = parseString(getPostValue("defaultName"));

		// Validate comment data
		if (empty($this->id)) {
			if (!$this->hasCommentPermission()) {
				$errors->addError("permissions", $lEditComment["InsufficientPermissions"]);
			}
			if (!$login->isLoggedIn()) {
				if ($settings->commentsRequireValidation) {
					if (!audit()) {
						$errors->addError("validation", $lComment["WrongValidation"]);	
					}
				}
				if (empty($this->name) || $this->name==$defaultName) {
					$errors->addError("name", $lEditComment["MissingName"]);
				}
			}
		}
		else {
			if (!$this->hasEditPermission()) {
				$errors->addError("permissions", $lEditComment["InsufficientPermissions"]);
			}
			else {
				if (empty($this->name) && empty($this->userId)) {
					$errors->addError("name", $lEditComment["MissingName"]);
				}
			}
		}
		if (empty($this->subject)) {
			$errors->addError("subject", $lEditComment["MissingSubject"]);
		}
		if (empty($this->message)) {
			$errors->addError("message", $lEditComment["MissingText"]);
		}

		// Check if message could be classified as spam
		$spam = $spamFilter->isSpam($this->name,$this->mail,$this->subject,$this->message);
		
		// Check if this ip has been spam before
		if (!$spam) {
			$result = $dbi->query("SELECT COUNT(*) FROM ".commentTableName." WHERE spam=1 AND ip=".$dbi->quote($ip));
			if ($result->rows()) {
				list($count) = $result->fetchrow_array();			
				if ($count!=0) {
					$spam = true;
				}
			}
		}
		
		// If there were no errors insert or update comment
		if (!$errors->hasErrors()) {
			if (empty($this->id)) {
				// Insert into comment database
				$dbi->query("INSERT INTO ".commentTableName."(moduleId,moduleContentTypeId,moduleContentId,userId,name,mail,link,subject,message,ip,posted,spam,trash) VALUES(".$dbi->quote($moduleId).",".$dbi->quote($moduleContentTypeId).",".$dbi->quote($moduleContentId).",".($login->isLoggedIn()?$login->id:0).",".$dbi->quote($this->name).",".$dbi->quote($this->mail).",".$dbi->quote($this->link).",".$dbi->quote($this->subject).",".$dbi->quote($this->message).",".$dbi->quote($ip).",NOW(),".$dbi->quote($spam).",0)");
		
				// Get new comment id
				$this->id = $dbi->getInsertId();
			}
			else {					
				// Update values in database
				$dbi->query("UPDATE ".commentTableName." SET name=".$dbi->quote($this->name).",mail=".$dbi->quote($this->mail).",link=".$dbi->quote($this->link).",subject=".$dbi->quote($this->subject).",message=".$dbi->quote($this->message).",posted=posted,spam=".$dbi->quote($spam)." WHERE (id=".$dbi->quote($this->id).")");
			}

			// Remember poster
			$remember = getValue("remember");
			if(!empty($remember)) {
				$poster["name"] = stripslashes($this->name);						
				$poster["mail"] = stripslashes($this->mail);						
				$poster["link"] = stripslashes($this->link);
				$poster["remember"] = stripslashes($remember);
				setcookie("commentPoster", addslashes(serialize($poster)), time()+31536000);
			}
		}
		
		// Return errors if any
		return $errors;
	}	
			
	/**
	 * Set spam status for this comment.
	 * @param	$spam	Spam true or false.
	 */
	function setSpamStatus($spam) {
		if (!empty ($this->id)) {
			global $dbi;

			// Update values in database
			$dbi->query("UPDATE ".commentTableName." SET spam=".$dbi->quote($spam).",posted=posted WHERE (id=".$dbi->quote($this->id).")");
		}
	}
}
?>