CREATE TABLE `achievement_people` (
  `ID` varchar(30) NOT NULL,
  `UserID` varchar(20) NOT NULL,
  `Unlocked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `Progress` text,
  PRIMARY KEY (`ID`,`UserID`),
  KEY `UserID` (`UserID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8