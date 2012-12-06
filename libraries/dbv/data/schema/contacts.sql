CREATE TABLE `contacts` (
  `UserID` varchar(20) NOT NULL,
  `Email` varchar(75) DEFAULT NULL,
  `MSN` varchar(75) DEFAULT NULL,
  `Google` varchar(75) DEFAULT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `Mobile` varchar(15) DEFAULT NULL,
  `Facebook` varchar(75) DEFAULT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8