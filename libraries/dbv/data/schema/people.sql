CREATE TABLE `people` (
  `UserID` varchar(20) NOT NULL,
  `Name` text NOT NULL,
  `Category` varchar(10) NOT NULL DEFAULT 'camper',
  `Admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `Nickname` varchar(30) DEFAULT NULL,
  `DutyTeam` int(11) NOT NULL DEFAULT '0',
  `StudyGroup` varchar(10) DEFAULT NULL,
  `About` text,
  `Facts` text,
  `InfoFilled` tinyint(1) NOT NULL DEFAULT '0',
  `Password` text,
  `PasswordChanged` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT