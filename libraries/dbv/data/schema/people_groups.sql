CREATE TABLE `people_groups` (
  `UserID` varchar(20) NOT NULL,
  `Group` varchar(20) NOT NULL,
  PRIMARY KEY (`UserID`,`Group`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8