CREATE TABLE `achievement_list` (
  `ID` varchar(20) NOT NULL,
  `Name` varchar(40) NOT NULL,
  `Description` text NOT NULL,
  `Type` varchar(15) NOT NULL,
  `KeepProgress` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `Requirements` text,
  `Disabled` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC