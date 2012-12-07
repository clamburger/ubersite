CREATE TABLE `poll_votes` (
  `PollID` smallint(6) NOT NULL,
  `OptionID` smallint(6) NOT NULL,
  `UserID` varchar(20) NOT NULL,
  PRIMARY KEY (`PollID`,`UserID`),
  KEY `OptionID` (`OptionID`),
  KEY `UserID` (`UserID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8