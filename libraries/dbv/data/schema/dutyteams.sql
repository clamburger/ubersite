CREATE TABLE `dutyteams` (
  `ID` int(11) NOT NULL,
  `Name` text NOT NULL,
  `Colour` varchar(20) NOT NULL DEFAULT 'FFFFFF',
  `FontColour` varchar(20) NOT NULL DEFAULT '000000',
  `WarCry` text,
  `Group` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`,`Group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8