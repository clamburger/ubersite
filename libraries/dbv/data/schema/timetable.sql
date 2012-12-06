CREATE TABLE `timetable` (
  `Day` varchar(12) NOT NULL,
  `Start` varchar(5) NOT NULL,
  `End` varchar(5) NOT NULL,
  `Activity` varchar(30) NOT NULL,
  `Visible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`Day`,`Start`,`End`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC