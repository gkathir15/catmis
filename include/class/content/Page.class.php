<?
/** 
 * Class Page contains information about the pages on the webpage
 * and contains functions to add, delete, print and update pages in the
 * database.
 * @author	Kaspar Rosengreen Nielsen
 */
class Page extends ModuleContentType implements ModuleSearchType {
	var $disableComments = 0;
	var $fullLink = "";
	var $leftTemplate = 0;
	var $leftText = "";
	var $link = "";
	var $navbarTitle = "";
	var $parent = null;
	var $position = 0;
	var $rightTemplate = 0;
	var $rightText = "";
	var $separator = 0;
	var $showComments = 0;
	var $showInMenu = 1;
	var $showLastModified = 0;
	var $text = "";
	var $title = "";

	/** 
	 * Page constructor.
	 * @param 	$id 	Page identifier.
	 */
	function __construct($id=0, $title="") {
		parent::__construct("pageModuleId", "pageContentTypeId");
		
		// Initialize values
		$this->init($id, $title);
	}
	
	/** Delete cached files. */	
	function deleteCache() {
		if (!empty($this->id)) {
			if (file_exists(cachePath."/menu.txt")) {
				unlink(cachePath."/menu.txt");
			}
		}
	}
	
	/** Delete page from database */
	function deletePage() {
		global $errors;
		try {
			if(!empty($this->id)) {
				if ($this->hasDeletePermission()) {
					global $dbi, $log, $login;

					// Check submitter
					checkSubmitter();

					// Delete entries from the log
					$log->deleteTransaction(pageContentTypeId, $this->id);
					
					// Delete comments made to this page
					$comment = new Comment();
					$comment->deleteComments(pageModuleId, pageContentTypeId, $this->id);
	
					// Clear permissions for this page
					$login->clearPermissions(pageContentTypeId, $this->id);
	
					// Delete from database
					$dbi->query("DELETE FROM ".pageTableName." WHERE id=".$this->id);
					
					// Get subpages and delete them
					$result = $dbi->query("SELECT id FROM ".pageTableName." WHERE parentId=".$this->id);
					if($result->rows()) {
						for($i=0;(list($id) = $result->fetchrow_array());$i++) {
							$page = new Page($id);
							$page->deletePage();	
						}
					}

					// Free resultset
					$result->finish();	
					
					// Delete cache
					$this->deleteCache();
				}
			}
		}
		catch (Exception $e) {
			
		}
		return $errors;
	}

	/**
	 * Get link to a given page.
	 * @param	$id	Identifier of page.
	 * @return	Link to the given page.
	 */
	function getLink($id="") {
		if (!empty($id)) {
			$page = new Page($id);
			return $page->getPageLink();
		}
		else if (!empty($this->id)) {
			return $this->getPageLink();	
		}
		return scriptUrl."/".folderPage;
	}
	
	/**
	 * Get name of a given page.
	 * @param	$id	Identifier of page.
	 * @return	Name of the given page.
	 */
	function getName($id="") {
		if (!empty($id)) {
			$page = new Page($id);
			return $page->title;
		}
		else {
			global $lContentType;
			return $lContentType["Page"];
		}
	}
				
	/**
	  * Get navigation information for this page
	  * @return array with navigation information.
	  */
	function getNavigationList() {
		$navigation = $this->getNavigationPath();
		if ($this->id!=pageDefaultPage) {
			$index = sizeof($navigation);
			$navigation[$index][0] = $this->getPageLink();
			$navigation[$index][1] = $this->title;
		}
		return $navigation;
	}
	
	/** Get navigation path for this page */
	function getNavigationPath($navigation=array()) {
		if (!empty($this->parent)) {
			$navigation = $this->parent->getNavigationPath($navigation);
			if (!empty($this->parent->id)) {
				$index = sizeof($navigation);
				$navigation[$index][0] = $this->parent->getPageLink();
				$navigation[$index][1] = $this->parent->title;
			}
		}
		return $navigation;
	}

	/**
	 * Get number of pages matching a given search string.
	 * @return	Number of pages matching the given search string.
	 */	
	function getNumberOfSearchResults($searchString) {			
		global $dbi;

		// Fetch page hits
		$count = 0;
		$result = $dbi->query("SELECT COUNT(*) FROM ".pageTableName." WHERE MATCH(title, text, leftText, rightText) AGAINST ('$searchString' IN BOOLEAN MODE)");
		if ($result->rows()) {
			list($count) = $result->fetchrow_array();
		}
		return $count;
	}	
	
	/** 
	 * Get number of subpages for this page
	 * @return the number of subpages for this page 
	 */
	function getNumberOfSubpages() {
		global $dbi;
		
		$result = $dbi->query("SELECT COUNT(*) FROM ".pageTableName." WHERE parentId=".$this->id);
		if($result->rows()) {
			list($count) = $result->fetchrow_array();
			return $count;
		}
		return 0;
	}

	/**
	 * Get link to page.
	 * @return	Link to this page.
	 */	
	function getPageLink() {
		global $dbi,$settings;
		
		if (!empty($this->fullLink)) {
			return parseString($this->fullLink);
		}

		$title = !empty($this->navbarTitle)?$this->navbarTitle:$this->title;
		if ($settings->linkType==1 || $settings->linkType==3) {			
			// Check if pages with same title exists
			$multiplePages = false;
			$result = $dbi->query("SELECT title FROM ".pageTableName." WHERE title=".$dbi->quote($title)." AND id!=".$dbi->quote($this->id));
			if ($result->rows()) {
				$multiplePages = true;
			}
			$result->finish();
			return generateURL(scriptUrl."/".filePage, array($title, $multiplePages || $settings->linkType==3?$this->id:0));
		}
		return scriptUrl."/".filePage."?pageId=".$this->id;
	}
	
	/**
	 * Get text of a given page.
	 * @param	$id	Identifier of page.
	 * @return	Field id of the given page.
	 */
	function getText($id="", $textfieldId=0) {
		if (!empty($id)) {
			$page = new Page($id);
			return $page->text;
		}
		return "";
	}
	
	/** 
	 * Initialize page.
	 * @param	$id		Page identifier.
	 * @param	$title	Page title.
	 */
	function init($id, $title="") {
		// Reset to default values
		$this->disableComments = 0;
		$this->fullLink = "";
		$this->leftTemplate = 0;
		$this->leftText = "";
		$this->link = "";
		$this->navbarTitle = "";
		$this->parent = null;
		$this->position = 0;
		$this->rightTemplate = 0;
		$this->rightText = "";
		$this->separator = 0;
		$this->showComments = 0;
		$this->showInMenu = 1;
		$this->showLastModified = 0;
		$this->text = "";
		$this->title = "";		
		
		// Attempt to read values from database
		if ((!empty($id) && is_numeric($id)) || !empty($title)) {
			global $dbi;
			
			// Fetch page data from database
			$result = $dbi->query("SELECT id,parentId,title,text,leftTemplate,leftText,rightTemplate,rightText,link,position,showComments,disableComments,showInMenu,showLastModified,navbarTitle,`separator` FROM ".pageTableName." WHERE ".(!empty($id)?"id=".$dbi->quote($id):(!empty($title)?"title=".$dbi->quote($title)." OR navbarTitle=".$dbi->quote($title):"")));
			if($result->rows()) {
				list($this->id,$parentId,$this->title,$this->text,$this->leftTemplate,$this->leftText,$this->rightTemplate,$this->rightText,$this->link,$this->position,$this->showComments,$this->disableComments,$this->showInMenu,$this->showLastModified,$this->navbarTitle,$this->separator) = $result->fetchrow_array();

				// Parse strings
				$this->title = parseString($this->title);
				$this->text = parseString($this->text);
				$this->leftText = parseString($this->leftText);
				$this->rightText = parseString($this->rightText);
				$this->parent = new Page($parentId);

				// If link does not contain http:// or https:// prepend scriptUrl
				if(!empty($this->link)) {
					$this->link = parseString($this->link);
					if(substr($this->link, 0, 7)!="http://" && substr($this->link, 0, 8)!="https://") $this->fullLink = scriptUrl."/".$this->link;
					else $this->fullLink = $this->link;
				}
			}
		}
	}
	
	/**
	 * Is this page a parent to the given page?
	 * @param $id Identifier of page to query for.
	 * @return true, if current page is a parent, false otherwise.
	 */
	function isParent($id) {
		global $dbi;

		if ($this->id==$id) {
			return true;
		}
		else {
			$result = $dbi->query("SELECT id FROM ".pageTableName." WHERE parentId=".$dbi->quote($this->id));
			if ($result->rows()) {
				for ($i=0; (list($pageId) = $result->fetchrow_array()); $i++) {
					$page = new Page($pageId);
					if ($page->id==$id) return true;
					if ($page->isParent($id)) return true;
				}
			}
		}
		return false;
	}
	
	/**
	 * Is this page visible in the navigationbar?
	 * @return true if visible, false otherwise.
	 */
	function isVisible() {
		return $this->showInMenu;	
	}
		
	/** Move page down */
	function movePageDown() {
		global $dbi;
	
		$result = $dbi->query("SELECT id,position FROM ".pageTableName." WHERE position>".$this->position." AND parentId=".$this->parent->id." ORDER BY position LIMIT 1");	
		if($result->rows()) {
			list($swapId,$swapPos)=$result->fetchrow_array();
			$dbi->query("UPDATE ".pageTableName." SET position='$swapPos' WHERE id=".$this->id);
			$dbi->query("UPDATE ".pageTableName." SET position='".$this->position."' WHERE id='$swapId'");
		}
		
		// Delete cache
		$this->deleteCache();		
	}
	
	/** Move page up */
	function movePageUp() {
		global $dbi;

		$result = $dbi->query("SELECT id,position FROM ".pageTableName." WHERE position<".$this->position." AND parentId=".$this->parent->id." ORDER BY position DESC LIMIT 1");	
		if($result->rows()) {
			list($swapId,$swapPos)=$result->fetchrow_array();
			$dbi->query("UPDATE ".pageTableName." SET position='$swapPos' WHERE id=".$this->id);
			$dbi->query("UPDATE ".pageTableName." SET position='".$this->position."' WHERE id='$swapId'");
		}
		
		// Delete cache
		$this->deleteCache();		
	}
	
	/** Print the time the page was last modified. */
	function printLastModified() {
		// Print time of last modification
		if ($this->showLastModified) {
			global $lPage,$site;
			printf("<p class=\"small1\"><br /><i>".$lPage["LastModified"]."</i></p>", $site->generateTimestamp($this->getLastUpdated()));
			if ($this->showComments) echo "<br />";
		}
	}

	/**
	 * Print navigation option.
	 * @param	$id		Identifier of page.
	 * @param	$dashes	Number of dashes to add in front of title.
	 */
	function printNavigationOption($id=0,$dashes=1) {
		global $dbi;

		$parentId = getValue("parentId");

		$result = $dbi->query("SELECT id,title FROM ".pageTableName." WHERE parentId=$id AND id!=0 AND id!=".$this->id." ORDER BY position");
		for($i=0;(list($id2,$title2)=$result->fetchrow_array());$i++) {
			echo "<option value=\"$id2\"".($id2==$this->parent->id || $id2 == $parentId?" selected=\"selected\"":"").">";

			for($j=0;$j<$dashes;$j++) {
				echo "- ";
			}
			echo $title2;
			echo "</option>";

			if(!empty($id2)) $this->printNavigationOption($id2,$dashes+1);
			$j = 0;	
		}
	}

	/**
	 * Print search results for a given search string.
	 * @param	$searchString
	 * @param	$limit
	 * @param	$page
	 * @param	$viewAll
	 */
	function printSearchResults($searchString, $limit=0, $page=0, $viewAll=0) {
		global $dbi, $login;
		
		$result = $dbi->query("SELECT id,MATCH(title,text,leftText,rightText) AGAINST ('$searchString' IN BOOLEAN MODE) AS score FROM ".pageTableName." WHERE MATCH(title, text, leftText, rightText) AGAINST ('$searchString' IN BOOLEAN MODE) ORDER BY score DESC".(!empty($limit) && $viewAll?" LIMIT ".($limit*$page).",".$limit:(!empty($limit)?" LIMIT ".$limit:"")));
		$highlight = str_replace("\"","",stripslashes($searchString));
		for($i=0;(list($id,$score)=$result->fetchrow_array());$i++) {
			$page = new Page($id);
			printSearchResultItem($searchString, $page->title, $page->text, $page->getPageLink(), $score);
		}
		$result->finish();
	}

	/** Print page on webpage */
	function printPage($redirect=1) {
		if (!empty($this->id)) {
			global $dbi, $login, $module, $site;
			if ($this->hasReadPermission()) {
				// Include language
				include scriptPath."/include/language/".pageLanguage."/general.php";
		
				// Add comment to database
				if (!empty($_GET["addComment"])) {
					// Save comment
					$comment = new Comment();
					$errors = $comment->saveComment(pageModuleId, pageContentTypeId, $this->id);
					
					// Redirect if there were no errors
					if (!$errors->hasErrors()) redirect($this->getPageLink());
				}
	
				// Redirect to link?
				if(!empty($this->link) && $redirect) {
					if (preg_match("/http:/i",$this->link)) {
						redirect($this->link);
					}
					else {
						redirect(scriptUrl."/".$this->link);
					}
				}

				// Set page title
				$site->setTitle($this->title);

				// Set meta links
				if ($this->hasEditPermission()) $site->addMetaLink(scriptUrl."/".folderPage."/".filePageEdit."?pageId=".$this->id, $lPage["EditPage"], "edit");
				if ($this->hasEditPermission()) $site->addMetaLink(scriptUrl."/".folderPage."/".filePageEdit."?parentId=".$this->id."&amp;return=1", $lPage["CreateSubsection"], "edit");
				if ($this->hasAdministerPermission()) $site->addMetaLink(scriptUrl."/".folderUsers."/editPermissions.php?moduleContentTypeId=".pageContentTypeId."&amp;moduleContentId=".$this->id, "", "permission");
				$site->addMetaLink($this->getPageLink(), "", "direct");
				$site->addMetaLink($this->getPageLink()."&amp;print=1", "", "print");
				$site->addMetaLink(scriptUrl."/".fileSendToFriend."?url=".$this->getPageLink()."&amp;title=".$this->title, "", "recommend");
				if ($this->hasEditPermission()) $site->addMetaLink(scriptUrl."/".folderRevision."/index.php?moduleContentTypeId=".pageContentTypeId."&amp;moduleContentId=".$this->id, "", "revision");
				
				// Set navigation links
				$navigation = $this->getNavigationList();
				for ($i=0; $i<sizeof($navigation); $i++) {
					$site->addNavigationLink($navigation[$i][0], $navigation[$i][1]);	
				}
	
				// Include user script if any
				if ($site->isIPad() && file_exists(userScriptPath."/page_".$this->id.".iPad.php")) {
					include userScriptPath."/page_".$this->id.".iPad.php";				
				}
				else if ($site->isIPhone() && file_exists(userScriptPath."/page_".$this->id.".iPhone.php")) {
					include userScriptPath."/page_".$this->id.".iPhone.php";			
				}
				else if (file_exists(userScriptPath."/page_".$this->id.".php")) {
					include userScriptPath."/page_".$this->id.".php";
				}
				else {
					// Print common header
					$site->printHeader(false);

					// Print page body
					$this->printPageBody();

					// Print common footer
					$site->printFooter();
				}
			}
			else {
				$login->printLoginForm();
				exit();	
			}
		}
		else {
			redirect(scriptUrl);
		}
	}
	
	/** Print the body part of the page. */
	function printPageBody() {
		global $dbi, $login;
		global $lPage;

		// Print text
		if(empty($this->text)) {
			$result = $dbi->query("SELECT id,title FROM ".pageTableName." WHERE (parentId=".$this->id.") AND (showInMenu=1) ORDER BY position");
			if($result->rows()) {
				// printSectionHeader($this->title);
				echo "<p>".$lPage["UnderConstruction"]."</p>";

				echo "<h2>".$lPage["Subpages"]."</h2>";
				echo "<ul>";
				for($i=0;(list($id2,$title2)=$result->fetchrow_array());$i++) {
					echo "<li><a href=\"".$this->getLink($id2)."\">$title2</a></li>";
				}
				echo "</ul>";
			}
			else {
				//printSectionHeader($this->title);
				echo "<p>".$lPage["UnderConstruction"]."</p>";
			}
		}
		else {							
			// Print text
			echo parseBodyText($this->text);
		}
		
		// Print last modified
		$this->printLastModified();
		
		// Print comments
		$this->printPageComments();
	}
	
	function printPageComments() {
		// Print comments
		if ($this->showComments) {
			$comment = new Comment();
			$comment->disableComments = $this->disableComments;
			$comment->printComments(pageModuleId, pageContentTypeId, $this->id, $this->title, scriptUrl."/".filePage."?pageId=".$this->id, filePage."?pageId=");
		}				
	}
	
	/** Print index of pages on the website. */
	function printPageIndex($id,$level) {
		global $dbi,$login;
		global $lPageIndex;

		// Fetch pages
		$result = $dbi->query("SELECT id FROM ".pageTableName." WHERE parentId=".$id." ORDER BY parentId,position");
		if ($result->rows()) {
			// Print page index
			for ($i = 0;(list ($id) = $result->fetchrow_array()); $i++) {
				$page = new Page($id);
				include scriptPath."/".folderPage."/include/template/pageIndex.php";
				if (!empty($_GET["parentId"])) {
					if ($page->isParent($_GET["parentId"])) {
						$page->printPageIndex($id,$level+1);
					}
				}
			}
		}
		else {
			echo "<p><i>".$lPageIndex["NoPages"]."</i></p>";
		}
	}

	/** 
	  * Save page to database.
	  * @param	readPost	Read values from post (default true).
	  * @return ErrorLog object if there were errors.
	  */
	function savePage($readPost=true) {
		// Create ErrorLog object
		$errorLog = new ErrorLog();
		
		// Check if user has edit permission
		if ($this->hasEditPermission()) {
			global $dbi, $log, $login, $revision;
			global $lEditPage;
		
		 	// Save old text for revision
			$oldText = "";
			
			// Get values
			if ($readPost) {
				// Check submitter
				checkSubmitter();

				// Get values
				$this->disableComments = getPostValue("disableComments");
				$this->link = getPostValue("link");
				$this->navbarTitle = getPostValue("navbarTitle");
				$this->parent = new Page(getPostValue("parentId"));
				$this->separator = getPostValue("separator");
				$this->showComments = getPostValue("showComments");
				$this->showInMenu = getPostValue("showInMenu");
				$this->showLastModified = getPostValue("showLastModified");
				$oldText = $this->text;
				$this->text = parseHtml(getPostValue("text"),4);
				$this->text = parseThumbnailImages($this->text);
				$this->title = getPostValue("title");
				$lastUpdated = getPostValue("lastUpdated");
			}
			else {
				$this->parent = new Page(0);
			}
			
			// Validate data
			if (empty($this->title)) $errorLog->addError("title", $lEditPage["TitleMissing"]);
			if (!empty($lastUpdated)) {
				if ($lastUpdated!=$this->getLastUpdated()) $errorLog->addError("pageModified", $lEditPage["PageModified"]);
			} 
			
			// If no errors save page
			if (!$errorLog->hasErrors()) {
				if (!empty($this->id)) {
					// Update page in database
					$dbi->query("UPDATE ".pageTableName." SET parentId=".$dbi->quote($this->parent->id).",title=".$dbi->quote($this->title).",text=".$dbi->quote($this->text).",link=".$dbi->quote($this->link).",navbarTitle=".$dbi->quote($this->navbarTitle).",showInMenu=".$dbi->quote($this->showInMenu).",showLastModified=".$dbi->quote($this->showLastModified).",showComments=".$dbi->quote($this->showComments).",disableComments=".$dbi->quote($this->disableComments).",`separator`=".$dbi->quote($this->separator)." WHERE id=".$dbi->quote($this->id));	
				}
				else {
					// Get position
					$result = $dbi->query("SELECT MAX(position) FROM ".pageTableName);
					if($result->rows()) {			
						list($position) = $result->fetchrow_array();
						$position++;
					}
					else {
						$position = 0;	
					}
			
					// Insert page into database
					$dbi->query("INSERT INTO ".pageTableName."(parentId,title,link,text,navbarTitle,showInMenu,showLastModified,showComments,disableComments,position,`separator`) VALUES(".$dbi->quote($this->parent->id).",".$dbi->quote($this->title).",".$dbi->quote($this->link).",".$dbi->quote($this->text).",".$dbi->quote($this->navbarTitle).",".$dbi->quote($this->showInMenu).",".$dbi->quote($this->showLastModified).",".$dbi->quote($this->showComments).",".$dbi->quote($this->disableComments).",".($position+1).",".$dbi->quote($this->separator).")");

					// Get new page id
					$this->id = $dbi->getInsertId();

					// Set permissions for reading the page
					$login->setModuleContentPermissions(pageContentTypeId, $this->id, "Visitors", 0, 0, 1, 0, 0, 0, 0, 1);
					$login->setModuleContentPermissions(pageContentTypeId, $this->id, "Users", 0, 0, 1, 0, 0, 0, 0, 1);				
					
					// Free result set
					$result->finish();
				}

				// Log transaction
				$log->logTransaction(pageContentTypeId, $this->id);
				
				// Save page revision
				$revision->saveTextRevision(pageModuleId, pageContentTypeId, $this->id, $oldText, $this->text);
				
				// Delete cache
				$this->deleteCache();
			}
			
			// Return errors if any
			return $errorLog;
		}
	}

	/** Save page bar to database. */	
	function savePageBar() {
		if (!empty($this->id)) {
			if ($this->hasEditPermission()) {
				global $dbi,$log;
				
				// Check if data is submitted from the form
				checkSubmitter();
				
				// Get values
				$this->leftTemplate = getPostValue("leftTemplate");
				$this->leftText = getPostValue("leftText");
				$this->rightTemplate = getPostValue("rightTemplate");
				$this->rightText = getPostValue("rightText");
				
				// Update page in database
				$dbi->query("UPDATE ".pageTableName." SET leftTemplate=".$dbi->quote($this->leftTemplate).",rightTemplate=".$dbi->quote($this->rightTemplate).",leftText=".$dbi->quote($this->leftText).",rightText=".$dbi->quote($this->rightText)." WHERE id=".$dbi->quote($this->id));	
		
				// Log transaction		
				$log->logTransaction(pageContentTypeId, $this->id);
			}
		}
	}
	
	function setText($id, $text, $textfieldIndex=0) {
		$page = new Page($id);
		if (empty($page->id)) return;
		global $dbi, $log, $revision;
		
		$dbi->query("UPDATE ".pageTableName." SET text=".$dbi->quote($text)." WHERE id=".$dbi->quote($page->id));

		// Log transaction
		$log->logTransaction(pageContentTypeId, $page->id);
		
		// Save page revision
		$revision->saveTextRevision(pageModuleId, pageContentTypeId, $page->id, $page->text, $text);
		
		// Delete cache
		$this->deleteCache();
	}
	
	/**
	 * Set visibility of page
	 * @param 	$pageId 	Identifier of page.
	 * @param 	$visible 	Visibility to set (true/false).
	 */
	function setVisible($pageId,$visible) {
		global $dbi;		
		$result = $dbi->query("UPDATE ".pageTableName." SET showInMenu=".$dbi->quote($visible?1:0)." WHERE id=".$dbi->quote($pageId));	

		// Delete cache
		$this->deleteCache();
	}
}
?>