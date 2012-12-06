CREATE TABLE `photo_captions` (
  `ID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `Filename` varchar(32) NOT NULL,
  `Caption` varchar(200) NOT NULL DEFAULT '',
  `Status` tinyint(4) NOT NULL DEFAULT '0',
  `Author` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8