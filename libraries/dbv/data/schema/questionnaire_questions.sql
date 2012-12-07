CREATE TABLE `questionnaire_questions` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` text,
  `HideName` tinyint(4) DEFAULT '0',
  `Questions` text,
  `PageId` int(11) DEFAULT NULL,
  `Position` int(11) DEFAULT NULL,
  `Expandable` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8