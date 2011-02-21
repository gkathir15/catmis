<?
// Set Catmis version number
define("productName","Catmis");
define("productLink","http://code.google.com/p/catmis/");
define("version","0.5");
define("databaseVersion","3");

// Check whether to load extensions
if (!isset($noExtensions)) $noExtensions = false;

// Include utility functions
require_once "functions.php";

// Make sure session variables are set for main domain and all subdomains
session_set_cookie_params(3600, '/', ".".getCurrentDomain());

// Start session
session_start();

// Set default separator for PHP to avoid breaking XHTML standard
ini_set("arg_separator.output","&amp;");

// Include configuration
((int) @include_once("config.php")) or die("Configuration file can not be read. Check that the file 'include/config.php' exists.");

// Check if session is valid?
$checkSession = !isset($checkSession) ? true : $checkSession;

// Check if values in configuration file has been set
if (empty($scriptUrl) || empty($scriptPath) || empty($filePath) || empty($dbHost) || empty($dbName) || empty($dbUserId) || empty($dbPassword)) {
	// Display welcome message
	if (file_exists("install/welcome.php")) {
		header("Location: install/welcome.php");
		exit();
	}
	else {
		echo "Please go to the root directory of your ".productName." installation.";
		exit();
	}
}
else {	
	// Display as many errors and warnings as possible if debug is enabled
	error_reporting($debug?6143:0);

	// Determine if diagnostics is enabled
	$diagnose = getGetValue("diagnose");
	define("diagnose", !empty($diagnose) ? true : false);

	// Include custom declarations, functions etc.
	if (!$noExtensions) {
		if (file_exists($scriptPath."/data/init.php")) {
			include_once $scriptPath."/data/init.php";
		}
	}
	
	// Include system classes
	require_once "class/module/Module.class.php";
	require_once "class/module/ModuleContentType.class.php";
	require_once "class/module/ModuleRegistry.class.php";
	require_once "class/module/ModuleSearchType.class.php";
	
	require_once "class/content/Category.class.php";
	require_once "class/content/Comment.class.php";
	require_once "class/content/Contributor.class.php";
	require_once "class/content/Page.class.php";

	require_once "class/database/mysql/MySQLDatabase.class.php";	
	require_once "class/database/mysql/MySQLResult.class.php";	

	require_once "class/display/Site.class.php";
	require_once "class/display/Theme.class.php";

	require_once "class/io/Cache.class.php";
	require_once "class/io/File.class.php";
	require_once "class/io/Folder.class.php";

	require_once "class/log/ErrorLog.class.php";
	require_once "class/log/Log.class.php";

	require_once "class/login/Group.class.php";
	require_once "class/login/Login.class.php";	
	require_once "class/login/User.class.php";

	require_once "class/revision/Revision.class.php";

	require_once "class/rss/RSS.class.php";
	require_once "class/rss/RSSItem.class.php";

	require_once "class/settings/Settings.class.php";	
	
	require_once "class/spamfilter/SpamFilter.class.php";
	
	// Include util classes
	require_once "util/html2text.php";
	require_once "util/phpmailer/class.phpmailer.php";
	
	// Database parameters
	define("dbHost", $dbHost);
	define("dbName", $dbName);
	define("dbUserId", $dbUserId);
	define("dbPassword", $dbPassword);
	define("dbPrefix", $dbPrefix);

	// Display debug information
	define("debug", defined("installing")?true:$debug);

	// Location to store files
	define("filePath", $filePath);	
	
	// Root location of the script
	define("scriptPath", $scriptPath);
	define("scriptUrl", $scriptUrl);

	// Set various paths
	define("cachePath", scriptPath."/data/cache");
	define("cacheUrl", scriptUrl."/data/cache");
	define("logPath", scriptPath."/data/log");
	define("logUrl", scriptUrl."/data/log");
	define("userScriptPath", scriptPath."/data/scripts");
	define("userScriptUrl", scriptUrl."/data/scripts");
	define("widgetPath", scriptPath."/data/widgets");
	
	// Set page state
	define("showPopup",!empty($_GET["popup"])?$_GET["popup"]:false);
	define("showPrint",!empty($_GET["print"])?$_GET["print"]:false);	
	
	// Set core folders
	define("folderAdmin","admin");
	define("folderCategory","admin/categories");
	define("folderComment","admin/comments");
	define("folderContributor","admin/contributor");
	define("folderFiles","files");
	define("folderFilesAdmin","admin/files");
	define("folderGroups","admin/groups");
	define("folderLog","admin/log");
	define("folderPage","admin/pages");
	define("folderRevision","admin/revisions");
	define("folderRSS","rss");
	define("folderSettings","admin/settings");
	define("folderSpamFilter","admin/spam");
	define("folderUploadedFiles","data/uploads");
	define("folderUsers","admin/users");
	
	// Set core files
	define("fileCategory","category.php");
	define("fileCategoryEdit","editCategory.php");
	define("fileCategoryIndex","index.php");
	define("fileCommentEdit","editComment.php");
	define("fileCommentIndex","index.php");
	define("fileEditPermissions","editPermissions.php");
	define("fileFilesBrowse","index.php");
	define("fileFilesCreateFolder","createFolder.php");
	define("fileFilesEditFile","editFile.php");
	define("fileFilesEditFolder","editFolder.php");
	define("fileFilesGetFile","getFile.php");
	define("fileFilesGetImage","getImage.php");
	define("fileFilesIndex","index.php");
	define("fileFilesUploadFiles","uploadFiles.php");
	define("fileGroupEdit","editGroup.php");
	define("fileGroupIndex","index.php");
	define("fileLogIndex","index.php");
	define("fileLogin","login.php");
	define("fileLogout","logout.php");
	define("filePage","index.php");
	define("filePageEdit","editPage.php");
	define("filePageBarEdit","editPageBar.php");
	define("filePageIndex","index.php");
	define("fileProfileActivate","activate.php");
	define("fileProfileForgotPassword","forgotPassword.php");
	define("fileRegister","register.php");
	define("fileSearch","search.php");
	define("fileSendMail", "mail.php");
	define("fileSendToFriend","send.php");
	define("fileSettingsEdit","index.php");
	define("fileUserEdit","editUser.php");
	define("fileUserCategoryEdit","editUserCategory.php");
	define("fileUserChangePassword","editPassword.php");
	define("fileUserIndex","index.php");
	define("fileUserProfileEdit","editProfile.php");
	define("fileUserProfile","profile.php");
	
	// Database table names
	define("categoryTableName",dbPrefix."category");
	define("categoryContentRefTableName",dbPrefix."categoryContentRef");
	define("commentTableName",dbPrefix."comment");
	define("contributorTableName",dbPrefix."contributor");	
	define("contributorRefTableName",dbPrefix."contributorRef");	
	define("fileTableName",dbPrefix."file");
	define("folderTableName",dbPrefix."folder");
	define("groupTableName",dbPrefix."group");
	define("logTableName",dbPrefix."log");
	define("logReadsTableName",dbPrefix."logRead");
	define("logReadsIpTableName",dbPrefix."logReadsIP");
	define("metaTableName",dbPrefix."meta");
	define("moduleTableName",dbPrefix."module");
	define("moduleContentTypeTableName",dbPrefix."moduleContentType");
	define("notificationTableName",dbPrefix."notification");
	define("permissionTableName",dbPrefix."permission");
	define("revisionTableName",dbPrefix."revision");
	define("searchTypeTableName",dbPrefix."searchType");
	define("pageTableName",dbPrefix."page");
	define("settingsTableName",dbPrefix."settings");
	define("userTableName",dbPrefix."user");
	define("userCategoryTableName",dbPrefix."userCategory");
	define("userDataTableName",dbPrefix."userData");
	define("userGroupRefTableName",dbPrefix."userGroupRef");

	// Create database object
	$dbi = new MySQLDatabase(dbHost, dbName, dbUserId, dbPassword);

	// Create Log object
	$log = new Log();
	
	// Create module object
	$module = new Module();
	
	// Create login object
	$login = new Login();
	if ($login->isWebmaster()) {
		// $dbi->query("SET NAMES 'utf8'");
		// $dbi->query("SET CHARACTER SET 'utf8'");
	}

	// Attempt to upgrade database
	if (!defined("installing")) {
		include scriptPath."/include/upgrade.php";
	}

	// Create new Revision object
	$revision = new Revision();
	
	// Create new Site object
	$site = new Site();

	// Check if script is installing
	if (!defined("installing")) {
		// Create settings object and define important values
		$settings = new Settings();
		if ($settings->isInitialized()) {
			define("pageAdminMail",$settings->adminMail);
			define("pageDefaultPage",isset($defaultPage) ? $defaultPage : $settings->defaultPage);
			define("pageDescription",$settings->description);
			define("pageIconTheme",$settings->iconTheme);
			define("pageKeywords",$settings->keywords);
			define("pageLanguage",$settings->language);
			define("pageShowDirectLink",$settings->showDirectLink);
			define("pageShowPrinterLink",$settings->showPrinterLink);
			define("pageShowRecommendLink",$settings->showRecommendLink);
 			if (isset($_GET["theme"])) {
				$theme = getValue("theme");
				$_SESSION["theme"] = $theme;
			}
			else if (isset($_SESSION["theme"])) {
				$theme = $_SESSION["theme"];
			}
			define("pageTheme",!empty($theme) ? $theme : $settings->theme);
			define("pageTitle",$settings->title);
			define("pageUploadFolder",$settings->defaultUploadFolder);	

			// Set urls and paths
			define("iconUrl",scriptUrl."/theme/icon/".pageIconTheme);
			define("iconPath",scriptPath."/theme/icon/".pageIconTheme);
			define("imgUrl",scriptUrl."/theme/layout/".pageTheme."/img");
			define("imgPath",scriptPath."/theme/layout/".pageTheme."/img");
			define("layoutUrl",scriptUrl."/theme/layout/".pageTheme);
			define("layoutPath",scriptPath."/theme/layout/".pageTheme);
			define("layoutLanguagePath",layoutPath."/language");
			define("templatePath", layoutPath."/template");
		}

		// Create other objects
		$cache = new Cache();
		$errors = new ErrorLog(); // Kept for backwards compatibility - don't use
		$errorLog = new ErrorLog();
		$category = new Category();
		$group = new Group();
		$spamFilter = new SpamFilter();

		// Create cache object and clean cache
		$cache->cleanCache();


		// Create module object and register modules and content types
		$module->registerModule("Categories","categoryModuleId");
		$module->registerModuleContentType("Category", "Categories", "", "categoryContentTypeId", new Category());
		$module->registerModule("Comments","commentModuleId");
		$module->registerModuleContentType("Comment", "Comments", "", "commentContentTypeId", new Comment());
		$module->registerModule("Contributors","contributorModuleId");
		$module->registerModuleContentType("Contributor", "Contributor", "", "contributorContentTypeId", new Contributor());
		$module->registerModule("Groups","groupModuleId");
		$module->registerModuleContentType("Group", "Groups", "", "groupContentTypeId", new Group());
		$module->registerModule("Pages","pageModuleId");
		$module->registerModuleContentType("Page", "Pages", "", "pageContentTypeId", new Page());
		$module->registerModule("Users","userModuleId");
		$module->registerModuleContentType("User", "Users", "", "userContentTypeId", new User());

		// Register search types
		$module->registerSearchType("pageContentTypeId");

		// Include module config files for third-party addons
		$modules = array();
		if (!$noExtensions) {
			$result = $dbi->query("SELECT id,path FROM ".moduleTableName." WHERE title NOT IN('Categories','Comments','Groups','Pages','Users')");
			if ($result->rows()) {
				for ($i=0; list($id,$path)=$result->fetchrow_array(); $i++) {
					$modules[$i]["id"] = $id;
					$modules[$i]["path"] = $path;
					if (file_exists(scriptPath."/".$path."/include/config.php")) {
						include_once scriptPath."/".$path."/include/config.php";
					}
				}
			}
		}

		// Initialize module types
		$module->initialize();

		// Perform module upgrades
		if (!$noExtensions) {
			for ($i=0; $i<sizeof($modules); $i++) {
				if (empty($modules[$i])) continue;
				if (empty($modules[$i]["path"])) continue;
				if (!file_exists(scriptPath."/".$modules[$i]["path"])) continue;
				if (!file_exists(scriptPath."/".$modules[$i]["path"]."/include")) continue;
				if (file_exists(scriptPath."/".$modules[$i]["path"]."/include/upgrade.php")) {
					include_once scriptPath."/".$modules[$i]["path"]."/include/upgrade.php";
				}
				if (!file_exists(scriptPath."/".$modules[$i]["path"])) continue;
				if (!file_exists(scriptPath."/".$modules[$i]["path"]."/install")) continue;
				if (file_exists(scriptPath."/".$modules[$i]["path"]."/install/upgrade.php")) {
					include_once scriptPath."/".$modules[$i]["path"]."/install/upgrade.php";
				}
			}
		}	
		
		// Indicate modules has been initialized
		define("modulesInitialized", true);
		
		// Include language
		if (file_exists(scriptPath."/include/language/".pageLanguage."/general.php")) {
			require_once scriptPath."/include/language/".pageLanguage."/general.php";
		}

		// Include admin language
		if (file_exists(scriptPath."/include/language/".pageLanguage."/admin.php")) {
			require_once scriptPath."/include/language/".pageLanguage."/admin.php";
		}

		// Define default timeformats
		define("timeFormat", !empty($timeFormat)?$timeFormat:"M jS, Y, H:i");
		define("shortTimeFormat", !empty($shortTimeFormat)?$shortTimeFormat:"M jS, Y");

		// Define "safe" file extensions to upload
		$safeFileExtensions = array("gif",
									"jpg",
									"jpeg",
									"png",
									"doc",
									"pdf",
									"odt"	
		);
		
		// Define "safe" file mime types to upload
		$safeMimeTypes = array("image/gif",
							   "image/jpeg",
							   "image/png",
							   "application/msword",
							   "application/pdf",
							   "application/vnd.oasis.opendocument.text"
	    );
		
		// Include custom declarations, functions etc.
		if (!$noExtensions) {
			if (file_exists(scriptPath."/data/custom.php")) {
				include_once scriptPath."/data/custom.php";
			}
		}		
	}
}
?>