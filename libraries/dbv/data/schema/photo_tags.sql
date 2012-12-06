CREATE TABLE `photo_tags` (
  `Filename` varchar(32) NOT NULL,
  `Username` varchar(20) NOT NULL,
  PRIMARY KEY (`Filename`,`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC