CREATE TABLE `photo_event_tags` (
  `Filename` varchar(32) NOT NULL,
  `Tag` varchar(255) NOT NULL,
  PRIMARY KEY (`Filename`,`Tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8