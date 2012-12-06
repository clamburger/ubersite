CREATE TABLE `photo_processing` (
  `Filename` varchar(32) NOT NULL,
  `Uploader` varchar(20) NOT NULL,
  `CampWebsite` tinyint(1) DEFAULT NULL,
  `Quality` smallint(5) unsigned DEFAULT NULL,
  `Nobody` tinyint(1) DEFAULT NULL,
  `Reviewer` varchar(20) DEFAULT NULL,
  `DateTaken` datetime NOT NULL,
  `DateUploaded` datetime NOT NULL,
  `DateReviewed` datetime DEFAULT NULL,
  PRIMARY KEY (`Filename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC