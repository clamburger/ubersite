CREATE TABLE `questionnaires` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` text,
  `Pages` text,
  `Intro` text,
  `Outro` text,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8