<?
/** 
 * Class Settings contains default settings for the website. 
 * @author	Kaspar Rosengreen Nielsen
 */
class Settings {
	var $activateWithEmail = false;
	var $adminMail = "";
	var $allowUserRegistration = false;
	var $cacheSize = 0;
	var $commentBlacklist = "";
	var $commentsRequireValidation = 1;
	var $defaultPage = 0;
	var $defaultUploadFolder = 0;
	var $description = "";
	var $enableCaching = false;
	var $enableRevisioning = false;
	var $iconTheme = "default";
	var $initialized = false;
	var $keywords = "";
	var $language = "en";
	var $linkType = 1;
	var $maxNoOfLinksInComments = 0;
	var $requireValidation = 1;
	var $showDirectLink = 0;
	var $showPrinterLink = 0;
	var $showRecommendLink = 0;
	var $subtheme = "default";
	var $theme = "default";
	var $themeHeaderUrl = "";
	var $themeWidth = 0;
	var $title = "";

	/** Settings constructor */
	function Settings() {
		global $dbi;

		// Fetch settings from database
		$result = $dbi->query("SELECT ".
							  "activateWithEmail,".
							  "adminMail,".
							  "allowUserRegistration,".
							  "cacheSize,".
							  "commentBlacklist,".
							  "commentsRequireValidation,".
							  "defaultPage,".
							  "defaultUploadFolder,".
							  "description,".
							  "enableCaching,".
							  "enableRevisioning,".
							  "iconTheme,".
							  "keywords,".
							  "language,".
							  "linkType,".
							  "maxNoOfLinksInComments,".
							  "requireValidation,".
							  "showDirectLink,".
							  "showPrinterLink,".
							  "showRecommendLink,".
							  "subtheme,".
							  "theme,".
							  "themeHeaderUrl,".
							  "themeWidth,".
							  "title".
							  " FROM ".settingsTableName." WHERE id=1");
							  
		if($result->rows()) {
			list($this->activateWithEmail,
			     $this->adminMail,
			     $this->allowUserRegistration,
			     $this->cacheSize,
			     $this->commentBlacklist,
			     $this->commentsRequireValidation,
			     $this->defaultPage,
			     $this->defaultUploadFolder,
			     $this->description,
			     $this->enableCaching,
			 	 $this->enableRevisioning,
			     $this->iconTheme,
			     $this->keywords,
			     $this->language,
			     $this->linkType,
			     $this->maxNoOfLinksInComments,
			     $this->requireValidation,
			     $this->showDirectLink,
			     $this->showPrinterLink,
			     $this->showRecommendLink,
			     $this->subtheme,
			     $this->theme,
				 $this->themeHeaderUrl,
				 $this->themeWidth,
			     $this->title) = $result->fetchrow_array();
			
			// Parse strings	
			$this->description = parseString($this->description);
			$this->keywords = parseString($this->keywords);
			$this->title = parseString($this->title);	
			
			// Set initialized to true
			$this->initialized = true;		
		}
	}
	
	/**
	 * Has the settings been initialized.
	 * @return true if initialized, false otherwise.
	 */
	function isInitialized() {
		return $this->initialized;	
	}

	/** 
	  * Save settings.
	  * @param	$readPost	Read values from post.
	  * @return ErrorLog object if there were errors.
	  */
	function saveSettings($readPost=true) {
		global $dbi, $login;
		global $lSettings;
		
		// Check if data is submitted from the form
		checkSubmitter();
		
		// Get values
		if ($readPost) {
			$this->activateWithEmail = getPostValue("activateWithEmail");
			$this->adminMail = getPostValue("adminMail");
			$this->allowUserRegistration = getPostValue("allowUserRegistration");
			$this->cacheSize = getPostValue("cacheSize");
			$this->commentBlacklist = getPostValue("commentBlacklist");
			$this->commentsRequireValidation = getPostValue("commentsRequireValidation");
			$this->defaultPage = getPostValue("defaultPage");
			$this->defaultUploadFolder = getPostValue("defaultUploadFolder");
			$this->description = getPostValue("description");
			$this->enableCaching = getPostValue("enableCaching");
			$this->enableRevisioning = getPostValue("enableRevisioning");
			$this->iconTheme = getPostValue("iconTheme");
			$this->keywords = getPostValue("keywords");
			$this->language = getPostValue("language");
			$this->linkType = getPostValue("linkType");
			$this->maxNoOfLinksInComments = getPostValue("maxNoOfLinksInComments");
			$this->requireValidation = getPostValue("requireValidation");
			$this->showDirectLink = getPostValue("showDirectLink");
			$this->showPrinterLink = getPostValue("showPrinterLink");
			$this->showRecommendLink = getPostValue("showRecommendLink");
			$this->subtheme = getPostValue("subtheme");
			$this->theme = getPostValue("theme");
			$this->themeHeaderUrl = getPostValue("themeHeaderUrl");
			$this->themeWidth = getPostValue("themeWidth");
			$this->title = getPostValue("title");
		}
	
		// Create ErrorLog object
		$errorLog = new ErrorLog();
	
		// Validate data
		if (empty($this->title)) $errorLog->addError("title", $lSettings["MissingTitle"]);
		if (empty($this->adminMail)) $errorLog->addError("adminMail", $lSettings["MissingAdminMail"]);
		else if (!checkEmail($this->adminMail)) $errorLog->addError("adminMail", $lSettings["InvalidAdminMail"]);

		// Update database
		if (!$errorLog->hasErrors()) {
			// Check that row exists
			$result = $dbi->query("SELECT id FROM ".settingsTableName);
			if (!$result->rows()) {
				$dbi->query("INSERT INTO ".settingsTableName."(title) VALUES(".$dbi->quote($this->title).")");
			}

			// Update settings
			$dbi->query("UPDATE ".settingsTableName." SET ".
						"activateWithEmail=".$dbi->quote($this->activateWithEmail).",".
						"adminMail=".$dbi->quote($this->adminMail).",".
						"allowUserRegistration=".$dbi->quote($this->allowUserRegistration).",".
						"cacheSize=".$dbi->quote($this->cacheSize).",".
						"commentBlacklist=".$dbi->quote($this->commentBlacklist).",".
						"commentsRequireValidation=".$dbi->quote($this->commentsRequireValidation).",".
						"defaultPage=".$dbi->quote($this->defaultPage).",".
						"description=".$dbi->quote($this->description).",".
						"enableCaching=".$dbi->quote($this->enableCaching).",".
						"enableRevisioning=".$dbi->quote($this->enableRevisioning).",".
						"iconTheme=".$dbi->quote($this->iconTheme).",".
						"keywords=".$dbi->quote($this->keywords).",".
						"language=".$dbi->quote($this->language).",".
						"linkType=".$dbi->quote($this->linkType).",".
						"maxNoOfLinksInComments=".$dbi->quote($this->maxNoOfLinksInComments).",".
						"requireValidation=".$dbi->quote($this->requireValidation).",".
						"showDirectLink=".$dbi->quote($this->showDirectLink).",".
						"showPrinterLink=".$dbi->quote($this->showPrinterLink).",".
						"showRecommendLink=".$dbi->quote($this->showRecommendLink).",".
						"subtheme=".$dbi->quote($this->subtheme).",".
						"theme=".$dbi->quote($this->theme).",".
						"themeWidth=".$dbi->quote($this->themeWidth).",".
						"themeHeaderUrl=".$dbi->quote($this->themeHeaderUrl).",".
						"title=".$dbi->quote($this->title)
					    );
		}	
		
		// Return errors if any
		return $errorLog;
	}
}
?>