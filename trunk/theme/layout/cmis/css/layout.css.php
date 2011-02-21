<? 
require_once "../../../../include/common.php";
header("Content-type: text/css"); 

$menuBackground = "";
switch ($settings->subtheme) {
	case "Black":
		$menuBackground = "#333333";
		break;
	case "Blue":
		$menuBackground = "#00b7d7";
		break;
	case "Green":
		$menuBackground = "#336600";
		break;
	case "Purple":
		$menuBackground = "#996699";
		break;
	case "Red":
		$menuBackground = "#ff0033";
		break;
}
?>
html, body { 
	background-color: #e7e7e7;
	background-image: url(<?= imgUrl ?>/background.jpg);
	background-repeat: repeat-x;
	margin-top: 0px;
	margin-left: 0px;
	margin-right: 0px;
	margin-bottom:20px;
}

body {
	min-width: 750px;
}

.layoutContainer {
	width: <?= $settings->themeWidth != 0 ? $settings->themeWidth."px" : "80%" ?>;
	margin-top: 10px;
	margin-left: auto;
	margin-right: auto;
}

.layoutBorder {
	background-color: #ffffff;
	background-image: url(<?= imgUrl ?>/top_background.jpg);
	background-position: top left;
	background-repeat: repeat-x;
	border-bottom: 2px #cccccc solid;
	border-left: 2px #ebebeb solid;
	border-right: 2px #cccccc solid;
	border-top: 2px #ebebeb solid;
}

.layoutSpacer {
	margin-bottom: 12px;
}

.layoutBoxHeader {
	color: #333333;
	font-size: 90%;
	font-weight: bold;
	padding: 5px;
}

.layoutBoxContent {	
	font-size: 85%;
	padding-bottom: 3px;
	padding-left: 10px;
	padding-right: 10px;
	padding-top: 5px;
	text-align: left;
}

.layoutMenu {
	background-color: <?= $menuBackground ?>;
	<? 
	$dimensions = array();
	if (!file_exists(scriptPath."/".folderUploadedFiles."/themeHeader.jpg")) { ?>
	background-image: url(<?= imgUrl ?>/menu_background.<?= !empty($settings->subtheme)?$settings->subtheme:"Blue" ?>.jpg);
	<? 
	}
	else {
		$dimensions = getImageDimensions(scriptPath."/".folderUploadedFiles."/themeHeader.jpg");
	?>
	background-image: url(<?= scriptUrl."/".folderUploadedFiles ?>/themeHeader.jpg);	
	<? } ?>
	background-repeat: repeat-x;
	border: 8px #ffffff solid;
	color: #ffffff;
	<? if (sizeof($dimensions)>0) { ?>
	height: <?= $dimensions[1] ?>px;
	line-height: <?= $dimensions[1] ?>px;
	<? } else { ?>
	height: 150px;
	line-height: 150px;	
	<? } ?>
	text-align: center;
	vertical-align: middle;
}

.layoutContentContainer {
	background-color: #ffffff;
	padding-right:8px;
}

.layoutContentWrapper {
	float:left;
	width:100%;
}

.layoutContent {
	padding: 20px;
	margin-right: 175px;
	text-align: left;
}

.layoutWidgets {
	float:left;
	margin-left:-175px;
	margin-top: 4px;
	width:175px;
}

.layoutCreditContainer {
	margin-left:auto;
	margin-right:auto;	
	width:30%;
}

.layoutCreditLogo {
	float:left;
	width:38px;
}

.layoutCreditText {
	color:#999999;	
	font-size:80%;
	line-height:38px;
	margin-left:38px;
	padding-left:8px;
}

.layoutNavigation {
	background-color:#ffffff;
	clear: left;
	font-size:90%;
	padding-bottom:8px;	
	text-align:center;
	width:100%;
}