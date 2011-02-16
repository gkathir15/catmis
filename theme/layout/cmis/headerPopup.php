<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?= pageTitle ?> | <?= $title ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="<?= pageDescription ?>" />
<meta name="keywords" content="<?= pageKeywords ?>" />
<link rel="stylesheet" href="<?= layoutUrl ?>/css/cmis.css.php" type="text/css" />
<link type="text/css" href="<?= layoutUrl ?>/css/popup.css.php" rel="stylesheet" />
<link type="text/css" href="<?= layoutUrl ?>/css/format.css.php" rel="stylesheet" />
<link rel="shortcut icon" type="image/ico" href="<?= layoutUrl ?>/favicon.ico" />
<link rel="search" title="Search blog" href="<?= scriptUrl ?>/search.php?id=1&amp;searchString=" />
<script language="JavaScript" type="text/javascript" src="<?= scriptUrl ?>/javascript/default.js"></script>
</head>

<body>
<div id="content">
<?
if ($printHeader) {
	printSectionHeader($title);
}
?>
