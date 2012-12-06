CREATE TABLE `uber` (
  `UserID` varchar(20) NOT NULL,
  `Url` char(32) NOT NULL,
  `Ubered` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`UserID`,`Url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8