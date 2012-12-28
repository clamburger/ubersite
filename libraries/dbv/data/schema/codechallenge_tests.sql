CREATE TABLE `codechallenge_tests` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Params` varchar(200) NOT NULL,
  `Result` varchar(50) NOT NULL,
  `Visible` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8