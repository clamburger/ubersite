CREATE TABLE `poll_options` (
  `OptionID` smallint(6) NOT NULL AUTO_INCREMENT,
  `PollID` smallint(6) NOT NULL,
  `Response` varchar(50) NOT NULL,
  PRIMARY KEY (`OptionID`),
  KEY `PollID` (`PollID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8