CREATE TABLE `quotes` (
  `ID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `Person` varchar(50) NOT NULL DEFAULT '',
  `Context` varchar(100) NOT NULL DEFAULT '',
  `Quote` text,
  `Status` tinyint(4) NOT NULL DEFAULT '0',
  `Submitter` varchar(20) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8