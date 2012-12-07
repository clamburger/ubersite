CREATE TABLE `poll_questions` (
  `ID` smallint(6) NOT NULL AUTO_INCREMENT,
  `Question` varchar(50) NOT NULL,
  `Creator` varchar(20) NOT NULL,
  `Status` tinyint(3) NOT NULL DEFAULT '0',
  `Multiple` tinyint(4) NOT NULL DEFAULT '0',
  `Hidden` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8