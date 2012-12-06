CREATE TABLE `errors` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `UserID` varchar(20) NOT NULL,
  `RequestString` varchar(100) NOT NULL,
  `RequestMethod` char(4) NOT NULL,
  `Query` text NOT NULL,
  `Error` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC