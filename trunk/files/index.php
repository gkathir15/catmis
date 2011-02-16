<?
// Include common functions and declarations
require_once "../include/common.php";

if(!$login->isUser()) {
	$login->printLoginForm();
	exit();
}
$mimetypes = "";
$fileTypes = !empty($_GET["fileTypes"])?$_GET["fileTypes"]:"";
switch ($fileTypes) {
	case "images":
		$mimetypes = "'image/jpeg','image/pjpeg','image/gif','image/png'";
		break;
}
?>
<html>
<head>
<title><?= $lFileBrowseFiles["Header"] ?></title>
<link rel="stylesheet" href="<?= layoutUrl ?>/css/cmis.css.php" type="text/css" />
<link rel="stylesheet" href="<?= layoutUrl ?>/css/format.css.php" type="text/css" />
<style type="text/css">
html, body { 
	font-size: 10px;
	margin:0px;
	padding:0px;
}
td {
	font-size: 10px;
}
</style>
</head>
<body>
<?
// Create folder object
$folder = new Folder(!empty($_GET["folderId"])?$_GET["folderId"]:0);

// Get subfolders
$subfolders = $dbi->query("SELECT id FROM ".folderTableName." WHERE parentId=".$folder->id." ORDER BY name");

//Get files
$files = $dbi->query("SELECT id FROM ".fileTableName." WHERE folderId=".$folder->id.(!empty($mimetypes)?" AND type IN(".$mimetypes.")":"")." ORDER BY name");

// Include form
include scriptPath."/".folderFiles."/include/template/browseFilesForm.php";
?>
</body>
</html>