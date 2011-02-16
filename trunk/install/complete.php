<?
// Include common functions
require_once "../include/functions.php";

// Indicate script is installing
define("installing", 1);

// Define variables
$debug = true;
$dbHost = getPostValue("dbHost");
$dbName = getPostValue("dbName");
$dbUsername = getPostValue("dbUsername");
$dbPassword = getPostValue("dbPassword");
$dbPrefix = getPostValue("dbPrefix");
$filePath = getPostValue("filePath");
$scriptPath = getPostValue("scriptPath");
$scriptUrl = getPostValue("scriptUrl");

// TODO: Validate data

// Prepare configuration to be written
$config = "<?
// Enable debug messages
\$debug = ".$debug.";

// Root location of the script
\$scriptPath = \"".$scriptPath."\";
\$scriptUrl = \"".$scriptUrl."\";

// Location to store files
\$filePath = \"".$filePath."\";

// Database parameters
\$dbHost = \"".$dbHost."\";
\$dbName = \"".$dbName."\";
\$dbUserId = \"".$dbUsername."\";
\$dbPassword = \"".$dbPassword."\";
\$dbPrefix = \"".$dbPrefix."_\";
?>";

// Write configuration to file
$success = false;
$file = $scriptPath."/include/config.php";  
if (file_exists($file)) {
	if (is_writable($file)) {
		if ($file_handle = fopen($file,"w")) {
			if (fwrite($file_handle, $config)) { 
				$success = true; 
			}
			fclose($file_handle);   
		}
	}
}

// Check if common.php exists
if (!file_exists("../include/common.php")) {
	$success = false;
}

// If writing of config file was successful continue
if ($success) {
	// Include common functions and declarations
	require_once "../include/common.php";
	
	// Insert table dumps into database
	include "tables.php";

	// Create tables
    $dbi->createTables($dbTableDefs);

	// Get language
	$language = getPostValue("language");
	if (empty($language)) $language = "en";

	// Save page settings
	$settings = new Settings();
	$settings->title = $_POST["title"];
	$settings->adminMail = $_POST["email"];
	$settings->theme = "cmis";
	$settings->subtheme = "blue";
	$settings->iconTheme = "default";
	$settings->language = $language;
	$settings->saveSettings(false);

	// Define pageLanguage
	define("pageLanguage", $settings->language);
	define("pageTitle", $settings->title);
	define("pageAdminMail", $settings->adminMail);

	// Create new user
	$result = $dbi->query("SELECT id FROM ".userTableName." WHERE username=".$dbi->quote($_POST["username"]));
	if ($result->rows()) {
		list($userId) = $result->fetchrow_array();
		$dbi->query("UPDATE ".dbPrefix."user SET password=".$dbi->quote(md5(trim($_POST["password"]))).",administrator=1,webmaster=1,activated=1 WHERE id=".$dbi->quote($userId));
		$dbi->query("UPDATE ".dbPrefix."userData SET name=".$dbi->quote($_POST["name"]).",email=".$dbi->quote($_POST["email"])." WHERE id=".$dbi->quote($userId));
	}
	else {
		$dbi->query("INSERT INTO ".dbPrefix."user(username,password,administrator,webmaster,activated) VALUES(".$dbi->quote($_POST["username"]).",".$dbi->quote(md5(trim($_POST["password"]))).",'1','1','1')");
		$userId = $dbi->getInsertId();
		$dbi->query("INSERT INTO ".dbPrefix."userData (id,name,email) VALUES(".$dbi->quote($userId).",".$dbi->quote($_POST["name"]).",".$dbi->quote($_POST["email"]).")");
	}

	// Attempt to login user
	$login->checkLogin($_POST["username"], $_POST["password"], false);
	
	// Create module object
	$module = new Module();

	// Register Blog module
	$blogModuleId = $module->addModule("Blog", "blog");
	$blogContentId = $module->addModuleContentType("Blog", $blogModuleId);
	$blogPostContentId = $module->addModuleContentType("Blog Post", $blogModuleId);
	
	// Register Page module
	$pageModuleId = $module->addModule("Pages", "");
	$pageContentId = $module->addModuleContentType("Page", $pageModuleId);

	// Define id's
	define("blogModuleId", $blogModuleId);
	define("blogContentId", $blogContentId);
	define("blogPostContentId", $blogPostContentId);
	define("pageModuleId", $pageModuleId);
	define("pageContentTypeId", $pageContentId);

	// Create welcome page
	$page = new Page();
	$page->title = "Welcome";
	$page->text = 	"<h1>Congratulations</h1>".
			"<p>You have successfully setup the CMIS system.</p>".
			"<p>To begin administrating the system click the \"Login\" link in the right box or goto the <a href=\"".scriptUrl."/".folderAdmin."\">Control Panel</a>.</p>".
			"<p>For security reasons remember to remove the \"install\" folder on the server and chmod the config.php file back to 755.".
			"<h2>Links</h2>".
			"» <a href=\"http://www.krosweb.dk/index.php?CMIS\" target=\"_blank\">Project page</a><br />".
			"» <a href=\"http://www.krosweb.dk\" target=\"_blank\">Krosweb</a>";
	$page->showInMenu = true;
	$page->savePage(false);
	
	// Set default page in settings
	$settings->defaultPage = $page->id;
	$settings->theme = "cmis";
	$settings->subtheme = "Blue";
	$settings->saveSettings(false);
	
	// Redirect to index
	redirect(scriptUrl);
}
else {
	echo "<h1>Installation Failed</h1>";
	echo "Installation of CMIS failed. Check that the files 'include/config.php' and 'include/common.php' exists on the webserver.";
}
?>