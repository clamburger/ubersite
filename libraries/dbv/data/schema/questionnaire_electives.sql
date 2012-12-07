CREATE TABLE `questionnaire_electives` (
  `ShortName` varchar(30) NOT NULL,
  `LongName` varchar(60) NOT NULL,
  `Type` varchar(10) NOT NULL,
  `Order` double unsigned NOT NULL,
  PRIMARY KEY (`ShortName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8