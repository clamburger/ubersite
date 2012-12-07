CREATE TABLE `suggestions` (
  `ID` smallint(30) NOT NULL AUTO_INCREMENT,
  `Idea` text NOT NULL,
  `Submitter` varchar(30) NOT NULL,
  `Category` varchar(15) NOT NULL,
  `Status` smallint(6) NOT NULL DEFAULT '1',
  `Bug` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `Submitter` (`Submitter`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8