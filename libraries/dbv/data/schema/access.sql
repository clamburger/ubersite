CREATE TABLE `access` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `UserID` varchar(20) NOT NULL,
  `Page` varchar(30) NOT NULL,
  `RequestString` text NOT NULL,
  `RequestMethod` char(4) NOT NULL,
  `IP` varchar(15) NOT NULL,
  `UserAgent` text NOT NULL,
  `Filename` text NOT NULL,
  `Refer` text,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8