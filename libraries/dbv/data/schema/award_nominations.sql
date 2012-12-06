CREATE TABLE `award_nominations` (
  `Nominee` varchar(20) NOT NULL,
  `Category` int(10) unsigned NOT NULL,
  `Submitter` varchar(20) NOT NULL,
  `Notes` text,
  `Date` datetime NOT NULL,
  `Status` int(10) unsigned NOT NULL DEFAULT '1',
  `Day` varchar(10) NOT NULL,
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8