CREATE TABLE `pegosaurus` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Leader` varchar(20) NOT NULL,
  `Pegs` int(10) unsigned NOT NULL,
  `Date` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC