<?php
$dbTableDefs = array(
	"contributor" => "CREATE TABLE IF NOT EXISTS `%scontributor` (
	  `id` int(10) NOT NULL AUTO_INCREMENT,
	  `userId` int(10) NOT NULL,
	  `name` varchar(255) NOT NULL,
	  PRIMARY KEY (`id`)
	) TYPE=MyISAM
	  DEFAULT CHARSET=utf8;"
	,
	"contributorRef" => "CREATE TABLE IF NOT EXISTS `%scontributorRef` (
	  `id` int(10) NOT NULL AUTO_INCREMENT,
	  `contributorId` int(10) NOT NULL,
	  `moduleContentTypeId` int(10) NOT NULL,
	  `moduleContentId` int(10) NOT NULL,
	  `photographer` int(1) NOT NULL,
	  `translator` int(1) NOT NULL,
	  `writer` int(1) NOT NULL,
	  `position` int(1) NOT NULL,
	  PRIMARY KEY (`id`)
	) TYPE=MyISAM
	  DEFAULT CHARSET=utf8;"
);
?>