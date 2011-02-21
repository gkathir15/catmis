<?
// Get database version
$currentDatabaseVersion = $module->getModuleDatabaseVersion();
$databaseVersion = $currentDatabaseVersion;

// Determine modifications to make
$dbTableDefs = array();
switch ($currentDatabaseVersion) {
	case 0:
		$dbTableDefs = array(
			"meta" => "CREATE TABLE IF NOT EXISTS %smeta (
			  id int(10) NOT NULL auto_increment,
			  moduleId int(10) NOT NULL default '0',
			  cmisVersion varchar(10) NOT NULL default '',
			  databaseVersion int(10) NOT NULL default '0',
			  lastUpdated TIMESTAMP NOT NULL,
			  PRIMARY KEY  (id)
			) TYPE=MyISAM
			  CHARACTER SET utf8;"
		);
		$dbTableDefs[] = 
			"CREATE TABLE IF NOT EXISTS %slog_read (
		  	`id` int(10) NOT NULL AUTO_INCREMENT,
		  	`moduleContentTypeId` int(10) NOT NULL,
		  	`moduleContentId` int(10) NOT NULL,
		  	`totalReads` int(10) NOT NULL,
		  	PRIMARY KEY (`Id`)
			) TYPE=MyISAM
		  	  CHARACTER SET utf8;";
		$dbTableDefs[] = 
			"CREATE TABLE IF NOT EXISTS %sreadsIP (
		  	id int(10) NOT NULL auto_increment,
		  	moduleContentTypeId int(10) NOT NULL,
		  	moduleContentId int(10) NOT NULL,
		  	ip varchar(100) NOT NULL default '',
		  	timestamp timestamp(14) NOT NULL,
		  	PRIMARY KEY  (id)
			) TYPE=MyISAM
		  	  CHARACTER SET utf8;";
		$dbTableDefs[] = 
			"ALTER TABLE %suserData ADD hideTelephone INT(1) AFTER hideOnlineStatus;";
		$dbTableDefs[] = 
			"ALTER TABLE %suserData ADD notifyAboutChanges INT(1) AFTER hideTelephone;";
		$dbTableDefs[] = 
			"ALTER TABLE %suserData ADD facebook varchar(255) AFTER linkname;";
		$dbTableDefs[] = 
			"ALTER TABLE %suserData ADD twitter varchar(255) AFTER facebook;";
		$dbTableDefs[] = 
			"CREATE TABLE IF NOT EXISTS `%srevision` (
			 id int(10) NOT NULL auto_increment,
			 moduleId int(10) NOT NULL default '0',
			 moduleContentId int(10) NOT NULL default '0',
			 moduleContentTypeId varchar(100) NOT NULL default '',
			 textfieldIndex INT(10) NOT NULL default '0',
			 diff TEXT NOT NULL,
			 revision INT(10) NOT NULL default '0',
			 userId INT(10) NOT NULL,
			 timestamp TIMESTAMP NOT NULL,
			 PRIMARY KEY  (id)
			 ) TYPE=MyISAM
			   CHARACTER SET utf8;";
		$dbTableDefs[] = 
			"CREATE TABLE IF NOT EXISTS `%scontributor` (
			 `id` int(10) NOT NULL AUTO_INCREMENT,
			 `userId` int(10) NOT NULL,
			 `name` varchar(255) NOT NULL,
			 PRIMARY KEY (`id`)
			 ) TYPE=MyISAM
			   CHARACTER SET utf8;";
		$dbTableDefs[] = 
			"CREATE TABLE IF NOT EXISTS `%scontributorRef` (
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
			   CHARACTER SET utf8;";

		// Update database version
		$databaseVersion = 1;
	case 1:
		$dbTableDefs[] = "RENAME TABLE  %slog_read TO  ".dbPrefix."logRead";
		$dbTableDefs[] = "RENAME TABLE  %sreadsIP TO ".dbPrefix."logReadsIP";
		$databaseVersion = 2;
	case 2:
		$dbTableDefs[] = 
			"CREATE TABLE IF NOT EXISTS %snotification (
		  	  id int(10) NOT NULL auto_increment,
		  	  moduleId int(10) NOT NULL default '0',
		  	  moduleContentTypeId int(10) NOT NULL default '0',
		  	  moduleContentId int(10) NOT NULL default '0',
		 	  email varchar(100) NOT NULL,
			  userId int(10) NOT NULL default '0',
		  	  PRIMARY KEY  (id)
			) TYPE=MyISAM
		  	  CHARACTER SET utf8;";
		$databaseVersion = 3;
	case 3:
		$dbi->addColumnDefinition('%ssettings','enableRevisioning','int(1)','enableCaching');
		$databaseVersion = 4;
	case 4:
		$dbi->addColumnDefinition('%scomment','trash','int(1) NOT NULL default \'0\'','spam');
		$databaseVersion = 5;
		break;
}

// Upgrade database
if ($databaseVersion != $currentDatabaseVersion) {
	$dbi->createTables($dbTableDefs);

	// Update meta database version number
	$module->updateModuleDatabaseVersion(0, $databaseVersion);
}
?>