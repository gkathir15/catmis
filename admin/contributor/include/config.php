<?
// Include news declarations
require_once "declarations.php";

// Include Contributor classes
require_once "class/Contributor.php";

// Determine if contributor module is installed
if (!$module->isModuleInstalled("Contributor") && empty($skipInstalledCheck)) redirect(scriptUrl."/".folderContributor."/install/index.php");

// Register modules and content types
if (!defined("contributorModuleId") || !defined("contributorContentId")) {
	$module->registerModule("Contributor","contributorModuleId",folderContributor);
	$module->registerModuleContentType("Contributor", "Contributor", "", "contributorContentId", new Contributor());

	// Initialize module
	if (defined("modulesInitialized")) $module->initialize();
}

// Include language
if (file_exists(scriptPath."/".folderContributorSystem."/include/language/".pageLanguage."/general.php")) {
	include_once scriptPath."/".folderContributorSystem."/include/language/".pageLanguage."/general.php";
}

// Check if caching folder for blog exists
if ($settings->enableCaching && method_exists($cache, "createCacheDirectory")) {
	$cache->createCacheDirectory("contributor");
}
?>