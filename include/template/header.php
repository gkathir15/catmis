<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?= pageTitle ?> | <?= $title ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="<?= pageDescription ?>" />
<meta name="keywords" content="<?= pageKeywords ?>" />
<link type="text/css" href="<?= scriptUrl ?>/include/template/style.css.php" rel="stylesheet" />
<script language="JavaScript" type="text/javascript" src="<?= scriptUrl ?>/javascript/default.js"></script>
</head>

<body>
<?
global $login;
if ($login->isWebmaster()) {
	echo "<div class=\"error\" style=\"padding:5px;width:100%;border:1px #000000 solid\">No theme specified. Click <a href=\"".scriptUrl."/".folderSettings."\">here</a> to set theme.</div>";
}

// Print header
echo $printHeader?printSectionHeader($title):"";
?>