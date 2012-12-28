CREATE TABLE `codechallenge_results` (
  `UserID` varchar(20) NOT NULL,
  `Score` int(11) NOT NULL DEFAULT '0',
  `TimeMS1` decimal(5,5) DEFAULT NULL,
  `TimeMS2` decimal(5,5) DEFAULT NULL,
  `TimeMS3` decimal(5,5) DEFAULT NULL,
  `TimeMSAverage` decimal(5,5) DEFAULT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8