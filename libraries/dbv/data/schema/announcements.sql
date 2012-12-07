CREATE TABLE `announcements` (
  `DT` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Title` varchar(50) NOT NULL,
  `Announcement` text NOT NULL,
  `Enabled` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`DT`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8