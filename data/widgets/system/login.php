<?
// Create administrator box
$metaTitle = $lGeneral["Administrator"];
include layoutPath."/template/metaHeader.template.php";
$output = $metaHeader;

global $login;
if(!$login->isLoggedIn()) {
	// Prepare login link
	$metaTitle = $lGeneral["Login"];
	$metaLink = scriptUrl."/".fileLogin;
	$metaCount = -1;

	// Include template
	include layoutPath."/template/metaBody.template.php";
	$output .= $metaBody;
}
else {
	// Prepare control panel link
	$metaTitle = $lGeneral["ControlPanel"];
	$metaLink = scriptUrl."/".folderAdmin;
	$metaCount = -1;

	// Include template
	include layoutPath."/template/metaBody.template.php";
	$output .= $metaBody;

	// Prepare logout link
	$metaTitle = $lGeneral["Logout"];
	$metaLink = scriptUrl."/".fileLogout;
	$metaCount = -1;

	// Include template
	include layoutPath."/template/metaBody.template.php";
	$output .= $metaBody;			
}

include layoutPath."/template/metaFooter.template.php";
$output .= $metaFooter;

echo $output;
?>