-- --------------------------------------------------------

-- 
-- Struktur-dump for tabellen `blog`
-- 

CREATE TABLE blog (
  id int(10) NOT NULL auto_increment,
  title varchar(100) NOT NULL default '',
  category varchar(100) NOT NULL default '',
  description text NOT NULL,
  subscribers text NOT NULL,
  `language` char(2) NOT NULL default 'en',
  userlevel int(10) NOT NULL default '0',
  userlevelAdmin int(10) NOT NULL default '0',
  postLimit int(10) NOT NULL default '10',
  commentLimit int(10) NOT NULL default '10',
  allowComments int(1) NOT NULL default '1',
  showRSSLink int(1) NOT NULL default '0',
  showRSSCommentsLink int(1) NOT NULL default '0',
  position int(10) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Struktur-dump for tabellen `blogcategory`
-- 

CREATE TABLE blogcategory (
  id int(10) NOT NULL auto_increment,
  title varchar(100) NOT NULL default '',
  description varchar(255) NOT NULL default '',
  position int(10) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Struktur-dump for tabellen `blogpost`
-- 

CREATE TABLE blogpost (
  id int(10) NOT NULL auto_increment,
  blogId int(10) NOT NULL default '0',
  categoryId int(10) NOT NULL default '0',
  categoryId2 int(10) NOT NULL default '0',
  userId int(10) NOT NULL default '0',
  `subject` varchar(100) NOT NULL default '',
  summary text NOT NULL,
  `text` text NOT NULL,
  pictures int(10) NOT NULL default '4',
  posted timestamp NOT NULL,
  lastUpdated timestamp NOT NULL default '0000-00-00 00:00:00',
  showComments int(1) NOT NULL default '1',
  disableComments int(1) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Struktur-dump for tabellen `blogpostcategory`
-- 

CREATE TABLE blogpostcategory (
  id int(10) NOT NULL auto_increment,
  blogId int(10) NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  description text NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Struktur-dump for tabellen `blogpostcomment`
-- 

CREATE TABLE blogpostcomment (
  id int(10) NOT NULL auto_increment,
  blogId int(10) NOT NULL default '0',
  postId int(10) NOT NULL default '0',
  userId int(10) NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  mail varchar(100) NOT NULL default '',
  link varchar(100) NOT NULL default '',
  `subject` varchar(100) NOT NULL default '',
  `text` text NOT NULL,
  ip varchar(100) NOT NULL default '',
  posted timestamp NOT NULL,
  spam int(1) NOT NULL default '0',
  approved int(1) NOT NULL default '1',
  PRIMARY KEY  (id)
) TYPE=MyISAM;
