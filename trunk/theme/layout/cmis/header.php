<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?= pageTitle ?> | <?= $this->title ?></title>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<meta http-equiv="content-style-type" content="text/css" />
		<meta name="description" content="<?= pageDescription ?>" />
		<meta name="keywords" content="<?= pageKeywords ?>" />
		<link rel="stylesheet" href="<?= layoutUrl ?>/css/cmis.css.php" type="text/css" />
		<link rel="stylesheet" href="<?= layoutUrl ?>/css/format.css.php" type="text/css" />
		<link rel="stylesheet" href="<?= layoutUrl ?>/css/layout.css.php" type="text/css" />
		<link rel="shortcut icon" type="image/ico" href="<?= layoutUrl ?>/favicon.ico" />
		<link rel="search" title="Search blog" href="<?= scriptUrl ?>/search.php?id=1&amp;searchString=" />
		<? for ($i=0; $i<sizeof($this->rssFeeds); $i++) { ?>
		<link rel="alternate" title="<?= $this->rssFeeds[$i][0] ?>" href="<?= $this->rssFeeds[$i][1] ?>" type="application/rss+xml" />
		<? } ?>
		<script type="text/javascript" src="<?= scriptUrl ?>/javascript/default.js"></script>
	</head>

	<body>
		<div class="layoutContainer layoutSpacer">
			<div class="layoutBorder">
				<div class="layoutMenu">
					<? if (!file_exists(scriptPath."/".folderUploadedFiles."/themeHeader.jpg")) { ?>
					<a href="<?= scriptUrl ?>" class="menuHeader"><h1 class="menuHeader"><?= pageTitle ?></h1></a>
					<?= pageDescription ?>
					<? } ?>
				</div>

				<div class="layoutContentContainer">
					<div class="layoutContentWrapper">
						<div class="layoutContent">
							<?= $printHeader?$this->printSectionHeader($this->title):"" ?>