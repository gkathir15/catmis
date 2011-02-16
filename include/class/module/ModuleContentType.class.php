<?
abstract class ModuleContentType {
	public $id = 0;
	protected $moduleId = 0;
	protected $moduleContentTypeId = 0;
	protected $parentModuleContentTypeId = 0;
	protected $parentModuleContentId = 0;
	protected $userId = 0;

	public abstract function getLink();
	public abstract function getName();

	/**
	  * ModuleContentType constructor.
	  * @param	$moduleId	
	  * @param	$moduleContentTypeId
	  * @param	$parentModuleContentTypeId
	  * @param	$parentModuleContentId
	  * @param	$userId
	  */
	public function __construct($moduleId, $moduleContentTypeId, $parentModuleContentTypeId=0, $parentModuleContentId=0, $userId=0) {
		$this->moduleId = $moduleId;
		$this->moduleContentTypeId = $moduleContentTypeId;
		$this->parentModuleContentTypeId = $parentModuleContentTypeId;
		$this->parentModuleContentId = $parentModuleContentId;
		$this->userId = $userId;
	}
	
	/**
	  * Get time of last update for this content type.
	  * @return time of last update. 
	  */
	function getLastUpdated() {
		global $log;
		return $log->getLastUpdated($this->getModuleContentTypeId(), $this->id);
	}
	
	/**
	 * Get module identifier.
	 * @return	Module identifier.
	 */
	function getModuleId() {
		if (defined($this->moduleId)) return constant($this->moduleId);
		return 0;	
	}
		
	/**
	 * Get module content type identifier.
	 * @return	Module content type identifier.
	 */
	function getModuleContentTypeId() {
		if (defined($this->moduleContentTypeId)) return constant($this->moduleContentTypeId);
		return 0;
	}

	/**
	 * Get parent module content type identifier.
	 * @return	Parent module content type identifier.
	 */
	function getParentModuleContentTypeId() {
		if (defined($this->parentModuleContentTypeId)) return constant($this->parentModuleContentTypeId);
		return 0;
	}
	
	private function getPermissionId() {
		if (!empty($this->parentModuleContentId)) return $this->parentModuleContentId;
		return $this->id;
	}
	
	private function getPermissionTypeId() {
		if (!empty($this->parentModuleContentTypeId)) return $this->getParentModuleContentTypeId();
		return $this->getModuleContentTypeId();
	}	
	
	/** Does the current user have administer permission? */	
	public function hasAdministerPermission() {
		global $login;
		if (empty($login)) return false;
		return $login->hasAdministerPermission($this->getPermissionTypeId(), $this->getPermissionId());
	}
	
	/** Does the current user have permission to create new posts? */
	function hasCommentPermission() {
		global $login;
		if (empty($login)) return false;
		return $login->hasCommentPermission($this->getPermissionTypeId(), $this->getPermissionId());
	}
		
	/** Does the current user have delete permission? */	
	public function hasDeletePermission() {
		global $login;
		if (empty($login)) return false;
		$permission = $login->hasDeletePermission($this->getPermissionTypeId(), $this->getPermissionId()); 
		if ($permission==1 && !empty($this->userId)) {
			if ($this->userId!=$login->id) $permission = 0;
		}
		return $permission;
	}
	
	/** Does the current user have edit permission? */	
	public function hasEditPermission() {
		global $login;
		if (empty($login)) return false;
		$permission = $login->hasEditPermission($this->getPermissionTypeId(), $this->getPermissionId());
		if ($permission==1 && !empty($this->userId)) {
			if ($this->userId!=$login->id) $permission = 0;
		}
		return $permission;
	}
	
	/** Does the current user have permission to publish posts? */
	function hasPublishPermission() {
		global $login;
		if (empty($login)) return false;
		return $login->hasPublishPermission($this->getPermissionTypeId(), $this->getPermissionId());
	}	

	/** Does the current user have read permission? */	
	public function hasReadPermission() {
		global $login;
		if (empty($login)) return false;
		return $login->hasReadPermission($this->getPermissionTypeId(), $this->getPermissionId());
	}	
	
	function getSubscribers($id) {
		global $dbi;
		$subscribers = array();
		$result = $dbi->query("SELECT email,userId FROM ".notificationTableName." WHERE moduleContentTypeId=".$dbi->quote($this->getModuleContentTypeId())." AND moduleContentId=".$dbi->quote($id));
		for ($i=0; list($email,$userId) = $result->fetchrow_array(); $i++) {
			$subscribers[$i]["email"] = $email;
			$subscribers[$i]["userId"] = $userId;
		}
		$result->finish();
		return $subscribers;
	}
	
	function isSubscriber($id, $email, $userId = 0) {
		global $dbi;
		$result = $dbi->query("SELECT id FROM ".notificationTableName." WHERE moduleContentTypeId=".$dbi->quote($this->getModuleContentTypeId())." AND moduleContentId=".$dbi->quote($id)." AND email=".$dbi->quote($email).(!empty($userId) ? " AND userId=".$dbi->quote($userId) : ""));
		if ($result->rows()) return true;
		return false;
	}
	
	function notifyAboutChanges() {
		/*global $dbi;
		
		// Notify thread subscribers
		$result = $dbi->query("SELECT email,userId FROM ".forumPostNotifyTableName." WHERE threadId=".$dbi->quote($this->threadId));
		for ($i=0;list($email,$userId)=$result->fetchrow_array();$i++) {
			if ($userId == $this->userId) continue;

			$mailBody = $mail->Body;					
			$mail->Body .= "<p>Afmeld email ved nyt indlæg for denne tråd: <a href=\"".$link."&amp;email=".$email."&amp;unsubscribe=1\">".$link."&amp;email=".$email."&amp;unsubscribe=1</a>";
			
			// Send mail to subscribers
		    $mail->AddAddress($email);
		    $mail->Send();

		    // Clear all addresses for next loop
		    $mail->ClearAddresses();					
		
			// Restore original body
			$mail->Body = $mailBody;
		}		
		$result->finish();*/
	}
	
	/**
	 * Subscribe to changes made to this content object.
	 */
	function subscribeToChanges($id, $email = "", $userId = 0) {
		if (empty($id)) return;
		if (empty($this->moduleContentTypeId)) return;
		if (empty($email)) return;
		if (!checkEmail($email)) return;
		if (!empty($userId)) {
			$user = new User($userId);
			if (empty($user->id)) return;
			if ($user->notifyAboutChanges) return;
		}
		global $dbi;
		$result = $dbi->query("SELECT id FROM ".notificationTableName." WHERE moduleId=".$dbi->quote($this->getModuleId())." AND moduleContentTypeId=".$dbi->quote($this->getModuleContentTypeId())." AND moduleContentId=".$dbi->quote($id)." AND email=".$dbi->quote($email)." AND userId=".$dbi->quote($userId));
		if (!$result->rows()) {
			$dbi->query("INSERT INTO ".notificationTableName."(moduleId,moduleContentTypeId,moduleContentId,email,userId) VALUES(".$dbi->quote($this->getModuleId()).",".$dbi->quote($this->getModuleContentTypeId()).",".$dbi->quote($id).",".$dbi->quote($email).",".$userId.")");
		}
		$result->finish();
	}
		
	/**
	 * Unsubscribe from changes made to this content object.
	 */
	function unsubscribeFromChanges($id, $email = "", $userId = 0) {
		if (empty($id)) return;
		if (empty($this->moduleContentTypeId)) return;
		if (empty($email)) return;
		if (!checkEmail($email)) return;
		if (!empty($userId)) {
			$user = new User($userId);
			if (empty($user->id)) return;
			if ($user->notifyAboutChanges) return;
		}
		global $dbi;
		$dbi->query("DELETE FROM ".notificationTableName." WHERE moduleId=".$dbi->quote($this->getModuleId())." AND moduleContentTypeId=".$dbi->quote($this->getModuleContentTypeId())." AND moduleContentId=".$dbi->quote($id)." AND email=".$dbi->quote($email).(!empty($userId) ? " AND userId=".$dbi->quote($userId) : ""));							
	}	
}
?>