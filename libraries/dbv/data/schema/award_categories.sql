CREATE TABLE `award_categories` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Category` varchar(30) NOT NULL,
  `Description` text NOT NULL,
  `Enabled` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8