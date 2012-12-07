CREATE TABLE `actions` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserID` varchar(20) NOT NULL,
  `Timestamp` datetime NOT NULL,
  `Page` varchar(20) NOT NULL,
  `Action` varchar(20) NOT NULL,
  `SpecificID1` varchar(20) DEFAULT NULL,
  `SpecificID2` varchar(20) DEFAULT NULL,
  `AdminAction` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC