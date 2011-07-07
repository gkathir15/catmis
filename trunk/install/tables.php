<?php
$dbTableDefs = array(
	"blog" => "CREATE TABLE IF NOT EXISTS %sblog (
	  id int(10) NOT NULL auto_increment,
	  title varchar(100) NOT NULL default '',
	  category varchar(100) NOT NULL default '',
	  description text NOT NULL,
	  subscribers text NOT NULL,
	  language char(2) NOT NULL default 'en',
	  userlevel int(10) NOT NULL default '0',
	  userlevelAdmin int(10) NOT NULL default '0',
	  postLimit int(10) NOT NULL default '10',
	  commentLimit int(10) NOT NULL default '10',
	  showRSSLink int(1) NOT NULL default '0',
	  showRSSCommentsLink int(1) NOT NULL default '0',
	  position int(10) NOT NULL default '0',
	  PRIMARY KEY  (id)
	) TYPE=MyISAM 
	  CHARACTER SET utf8;"
	,
	"blogPost" => "CREATE TABLE IF NOT EXISTS %sblogPost (
	  id int(10) NOT NULL auto_increment,
	  blogId int(10) NOT NULL default '0',
	  categoryId int(10) NOT NULL default '0',
	  categoryId2 int(10) NOT NULL default '0',
	  userId int(10) NOT NULL default '0',
	  subject varchar(100) NOT NULL default '',
	  summary text NOT NULL,
	  text text NOT NULL,
	  pictures int(10) NOT NULL default '4',
	  posted timestamp NOT NULL,
	  lastUpdated timestamp NOT NULL default '0000-00-00 00:00:00',
	  showComments int(1) NOT NULL default '1',
	  disableComments int(1) NOT NULL default '0',
	  draft int(1) NOT NULL default '0',
	  PRIMARY KEY  (id)
	) TYPE=MyISAM
	  CHARACTER SET utf8"
	,
	"category" => "CREATE TABLE IF NOT EXISTS %scategory (
	  id int(10) NOT NULL auto_increment,
	  title varchar(100) NOT NULL,
	  description text NOT NULL,
	  PRIMARY KEY  (id)
	) TYPE=MyISAM
	  CHARACTER SET utf8;"
	,
	"categoryContentRef" => "CREATE TABLE IF NOT EXISTS %scategoryContentRef (
	  id int(10) NOT NULL auto_increment,
	  moduleId int(10) NOT NULL,
	  moduleContentTypeId int(10) NOT NULL,
	  moduleContentId int(10) NOT NULL,
	  categoryId int(10) NOT NULL,
	  position int(10) NOT NULL,
	  PRIMARY KEY  (id)
	) TYPE=MyISAM
	  CHARACTER SET utf8;"
	,
	"comment" => "CREATE TABLE IF NOT EXISTS %scomment (
	  id int(10) NOT NULL auto_increment,
	  moduleId int(10) NOT NULL default '0',
	  moduleContentId int(10) NOT NULL default '0',
	  moduleContentTypeId varchar(100) NOT NULL default '',
	  userId int(10) NOT NULL default '0',
	  name varchar(100) NOT NULL default '',
	  mail varchar(100) NOT NULL default '',
	  subject varchar(100) NOT NULL default '',
	  message text NOT NULL,
	  link varchar(100) NOT NULL default '',
	  posted timestamp(14) NOT NULL,
	  ip varchar(100) NOT NULL default '',
	  spam int(1) NOT NULL default '0',
  	  trash int(1) NOT NULL default '0',
	  PRIMARY KEY  (id)
	) TYPE=MyISAM
	  CHARACTER SET utf8;"
	,
	 "contributor" => "CREATE TABLE IF NOT EXISTS `%scontributor` (
		 `id` int(10) NOT NULL AUTO_INCREMENT,
		 `userId` int(10) NOT NULL,
		 `name` varchar(255) NOT NULL,
		 PRIMARY KEY (`id`)
		 ) TYPE=MyISAM
		   CHARACTER SET utf8;"
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
	,
	"file" => "CREATE TABLE IF NOT EXISTS %sfile (
	  id int(10) NOT NULL auto_increment,
	  folderId int(10) NOT NULL default '0',
	  userId int(10) NOT NULL default '0',
	  name varchar(100) NOT NULL default '',
	  type varchar(100) NOT NULL default '',
	  size varchar(100) NOT NULL default '',
	  userlevel int(1) NOT NULL default '0',
	  userlevelAdmin int(1) NOT NULL default '1',
	  PRIMARY KEY  (id)
	) TYPE=MyISAM
	  CHARACTER SET utf8;"
	,
	"folder" => "CREATE TABLE IF NOT EXISTS %sfolder (
	  id int(10) NOT NULL auto_increment,
	  parentId int(10) NOT NULL default '0',
	  userId int(10) NOT NULL default '0',
	  name varchar(100) NOT NULL default '',
	  userlevel int(1) NOT NULL default '0',
	  userlevelAdmin int(1) NOT NULL default '1',
	  PRIMARY KEY  (id)
	) TYPE=MyISAM
	  CHARACTER SET utf8;"
	,
	"group" => "CREATE TABLE IF NOT EXISTS %sgroup (
	  id int(10) NOT NULL auto_increment,
	  userLevel int(10) NOT NULL default '1',
	  name varchar(100) NOT NULL default '',
	  description text NOT NULL,
	  PRIMARY KEY  (id)
	) TYPE=MyISAM
	  CHARACTER SET utf8;"
	,
	"log" => "CREATE TABLE IF NOT EXISTS %slog (
	  id int(10) NOT NULL auto_increment,
	  moduleContentId int(10) NOT NULL default '0',
	  moduleContentTypeId int(10) NOT NULL default '0',
	  type varchar(100) NOT NULL default '',
	  typeId int(10) NOT NULL default '0',
	  uploaded timestamp(14) NOT NULL,
	  uploadedBy int(10) NOT NULL default '0',
	  lastUpdated timestamp(14) NOT NULL,
	  lastUpdatedBy int(10) NOT NULL default '0',
	  PRIMARY KEY  (id)
	) TYPE=MyISAM
	  CHARACTER SET utf8;"
	,
	"logReads" => "CREATE TABLE IF NOT EXISTS %slogRead (
	  `id` int(10) NOT NULL AUTO_INCREMENT,
	  `moduleContentTypeId` int(10) NOT NULL,
	  `moduleContentId` int(10) NOT NULL,
	  `totalReads` int(10) NOT NULL,
	  PRIMARY KEY (`Id`)
	) TYPE=MyISAM
	  CHARACTER SET utf8;"
	,	
	"logReadsIP" => "CREATE TABLE IF NOT EXISTS %slogReadsIP (
	  id int(10) NOT NULL auto_increment,
	  moduleContentTypeId int(10) NOT NULL,
	  moduleContentId int(10) NOT NULL,
	  ip varchar(100) NOT NULL default '',
	  timestamp timestamp(14) NOT NULL,
	  PRIMARY KEY  (id)
	) TYPE=MyISAM
	  CHARACTER SET utf8;"
	,	
	"meta" => "CREATE TABLE IF NOT EXISTS %smeta (
	  id int(10) NOT NULL auto_increment,
	  moduleId int(10) NOT NULL default '0',
	  cmisVersion varchar(10) NOT NULL default '',
	  databaseVersion int(10) NOT NULL default '0',
	  lastUpdated TIMESTAMP NOT NULL,
	  PRIMARY KEY  (id)
	) TYPE=MyISAM
	  CHARACTER SET utf8;"
	,
	"module" => "CREATE TABLE IF NOT EXISTS %smodule (
	  id int(10) NOT NULL auto_increment,
	  title varchar(100) NOT NULL default '',
	  path varchar(100) NOT NULL default '',
	  visible int(1) NOT NULL default '1',
	  PRIMARY KEY  (id)
	) TYPE=MyISAM
	  CHARACTER SET utf8;"
	,
	"moduleContentType" => "CREATE TABLE IF NOT EXISTS %smoduleContentType (
	  id int(10) NOT NULL auto_increment,
	  moduleId int(10) NOT NULL default '0',
	  parentId int(10) NOT NULL default '0',
	  title varchar(100) NOT NULL default '',
	  path varchar(100) NOT NULL default '',
	  PRIMARY KEY  (id)
	) TYPE=MyISAM
	  CHARACTER SET utf8;"
	,
	"notification" => "CREATE TABLE IF NOT EXISTS %snotification (
  	  id int(10) NOT NULL auto_increment,
  	  moduleId int(10) NOT NULL default '0',
  	  moduleContentTypeId int(10) NOT NULL default '0',
  	  moduleContentId int(10) NOT NULL default '0',
 	  email varchar(100) NOT NULL,
	  userId int(10) NOT NULL default '0',
  	  PRIMARY KEY  (id)
	) TYPE=MyISAM
  	  CHARACTER SET utf8;"
	,	
	"permission" => "CREATE TABLE IF NOT EXISTS %spermission (
	  id int(10) NOT NULL auto_increment,
	  type varchar(50) NOT NULL default '',
	  typeId int(10) NOT NULL default '0',
	  moduleId int(10) NOT NULL default '0',
	  moduleContentTypeId int(10) NOT NULL default '0',
	  moduleContentId int(10) NOT NULL default '0',
	  administerPermission int(1) NOT NULL default '0',
	  commentPermission int(1) NOT NULL default '0',
	  createPermission int(1) NOT NULL default '0',
	  deletePermission int(1) NOT NULL default '0',
	  editPermission int(1) NOT NULL default '0',
	  publishPermission int(1) NOT NULL default '0',
	  readPermission int(1) NOT NULL default '0',
	  PRIMARY KEY  (Id)
	) TYPE=MyISAM
	  CHARACTER SET utf8;"
	,
	"searchType" => "CREATE TABLE IF NOT EXISTS %ssearchType (
	  id int(10) NOT NULL auto_increment,
	  moduleContentTypeId int(10) NOT NULL,
	  position int(10) NOT NULL,
	  visible int(1) NOT NULL default '1',
	  PRIMARY KEY  (id)
	) TYPE=MyISAM
	  CHARACTER SET utf8;"
	,
	"page" => "CREATE TABLE IF NOT EXISTS %spage (
	  id int(10) NOT NULL auto_increment,
	  parentId int(10) NOT NULL default '0',
	  disableComments int(1) NOT NULL default '0',
	  leftTemplate int(10) NOT NULL default '0',
	  leftText text NOT NULL,
	  link varchar(100) NOT NULL default '',
	  navbarTitle varchar(100) NOT NULL default '',
	  position int(10) NOT NULL default '0',
	  rightTemplate int(10) NOT NULL default '0',
	  rightText text NOT NULL,
	  `separator` int(1) NOT NULL default '0',
	  showComments int(1) NOT NULL default '0',
	  showInMenu int(1) NOT NULL default '1',
	  showLastModified int(1) NOT NULL default '0',
	  text text NOT NULL,
	  title varchar(100) NOT NULL default '',
	  PRIMARY KEY  (id)
	) TYPE=MyISAM
	  CHARACTER SET utf8;"
	,
	"revision" => "CREATE TABLE IF NOT EXISTS %srevision (
	  id int(10) NOT NULL auto_increment,
  	  moduleId int(10) NOT NULL default '0',
	  moduleContentId int(10) NOT NULL default '0',
	  moduleContentTypeId varchar(100) NOT NULL default '',
	  textfieldIndex INT(10) NOT NULL default '0',
	  diff TEXT NOT NULL,
	  revision INT(10) NOT NULL default '0',
	  userId INT(10) NOT NULL default '0',
	  timestamp TIMESTAMP NOT NULL,
	  PRIMARY KEY  (id)
	) TYPE=MyISAM
	  CHARACTER SET utf8;"
	,
	"settings" => "CREATE TABLE IF NOT EXISTS %ssettings (
	  id int(10) NOT NULL auto_increment,
	  activateWithEmail int(1) NOT NULL default '1',
	  adminMail varchar(100) NOT NULL default '',
	  allowUserRegistration int(1) NOT NULL default '0',
	  cacheSize int(10) NOT NULL default '0',
	  commentBlacklist text NOT NULL,
	  commentsRequireValidation int(1) default '1',
	  defaultPage int(10) NOT NULL default '0',
	  defaultUploadFolder int(10) NOT NULL default '0',
	  description varchar(255) NOT NULL default '',
	  enableCaching int(1) NOT NULL default '0',
  	  enableRevisioning int(1) NOT NULL default '1',
	  iconTheme varchar(100) NOT NULL default '',
	  keywords varchar(255) NOT NULL default '',
	  language varchar(100) NOT NULL default '',
	  links text NOT NULL,
	  linkType int(1) NOT NULL default '1',
	  maxNoOfLinksInComments int(10) NOT NULL default '2',
	  requireValidation int(1) NOT NULL default '1',
	  showDirectLink int(1) NOT NULL default '0',
	  showPrinterLink int(1) NOT NULL default '0',
	  showRecommendLink int(1) NOT NULL default '0',
	  subtheme varchar(100) NOT NULL default '',
	  theme varchar(100) NOT NULL default '',
	  themeHeaderUrl varchar(100) NOT NULL default '',
	  themeWidth int(10) NOT NULL default '0',
	  title varchar(100) NOT NULL default '',
	  PRIMARY KEY  (id)
	) TYPE=MyISAM
	  CHARACTER SET utf8;"
	,	
	"user" => "CREATE TABLE IF NOT EXISTS %suser (
	  id smallint(6) NOT NULL auto_increment,
	  groupId int(10) NOT NULL default '1',
	  username varchar(20) NOT NULL default '',
	  password varchar(32) binary NOT NULL default '',
	  registered timestamp NOT NULL,
	  lastLogged timestamp NOT NULL default '0000-00-00 00:00:00',
	  lastUpdated timestamp NOT NULL default '0000-00-00 00:00:00',
	  cookie varchar(32) binary NOT NULL default '',
	  session varchar(32) binary NOT NULL default '',
	  ip varchar(15) binary NOT NULL default '',
	  `reads` int(10) NOT NULL default '0',
	  administrator int(1) NOT NULL default '0',
	  webmaster int(1) NOT NULL default '0',
	  activated int(1) NOT NULL default '0',
	  activationKey varchar(32) NOT NULL default '',
	  PRIMARY KEY  (id),
	  UNIQUE KEY username (username)
	) TYPE=MyISAM
	  CHARACTER SET utf8;"
	,
	"userCategory" => "CREATE TABLE IF NOT EXISTS %suserCategory (
	  id int(10) NOT NULL auto_increment,
	  title varchar(100) NOT NULL default '',
	  description varchar(100) NOT NULL default '',
	  position int(10) NOT NULL default '0',
	  PRIMARY KEY  (id)
	) TYPE=MyISAM
	  CHARACTER SET utf8;"
	,
	"userData" => "CREATE TABLE IF NOT EXISTS %suserData (
	  id int(10) NOT NULL default '0',
	  categoryId int(10) NOT NULL,
	  name varchar(100) NOT NULL default '',
	  email varchar(100) NOT NULL default '',
	  phone varchar(100) NOT NULL default '',
	  mobile varchar(100) NOT NULL default '',
	  linkurl varchar(100) NOT NULL default '',
	  linkname varchar(100) NOT NULL default '',
  	  facebook varchar(100) NOT NULL default '',
      twitter varchar(100) NOT NULL default '',
	  location varchar(50) NOT NULL default '',
	  department varchar(100) NOT NULL default '',
	  position varchar(100) NOT NULL default '',
	  profileText text NOT NULL,
	  signature tinytext NOT NULL,
	  hideEmail int(1) NOT NULL default '0',
	  hideInUserlist int(1) NOT NULL default '0',
	  hideOnlineStatus int(1) NOT NULL default '0',
  	  hideTelephone int(1) NOT NULL default '0',
  	  notifyAboutChanges int(1) NOT NULL default '0',
	  PRIMARY KEY  (id)
	) TYPE=MyISAM
	  CHARACTER SET utf8;"
	,
	"userGroupRef" => "CREATE TABLE IF NOT EXISTS %suserGroupRef (
	  id int(10) NOT NULL auto_increment,
	  userId int(10) NOT NULL default '0',
	  groupId int(10) NOT NULL default '0',
	  PRIMARY KEY  (id)
	) TYPE=MyISAM
	  CHARACTER SET utf8;"
	,
	"insertMeta" => "INSERT INTO %smeta(cmisVersion,databaseVersion) VALUES(".$dbi->quote(version).",".$dbi->quote(databaseVersion).")"
);
?>