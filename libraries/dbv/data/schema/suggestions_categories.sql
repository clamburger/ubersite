CREATE TABLE `suggestions_categories` (
  `ID` varchar(20) NOT NULL DEFAULT '',
  `Name` varchar(50) NOT NULL,
  `Description` text,
  `Order` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8