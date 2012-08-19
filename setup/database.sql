--
-- Definition of table `access`
--

CREATE TABLE `access` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `UserID` varchar(20) collate utf8_general_ci NOT NULL,
  `Page` varchar(30) collate utf8_general_ci NOT NULL,
  `RequestString` text collate utf8_general_ci NOT NULL,
  `RequestMethod` char(4) collate utf8_general_ci NOT NULL,
  `IP` varchar(15) collate utf8_general_ci NOT NULL,
  `UserAgent` text collate utf8_general_ci NOT NULL,
  `Filename` text collate utf8_general_ci NOT NULL,
  `Refer` text collate utf8_general_ci,
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Definition of table `achievement_list`
--

CREATE TABLE `achievement_list` (
  `ID` varchar(20) character set utf8 NOT NULL,
  `Name` varchar(40) character set utf8 NOT NULL,
  `Description` text character set utf8 NOT NULL,
  `Type` varchar(15) character set utf8 NOT NULL,
  `KeepProgress` tinyint(1) unsigned NOT NULL default '0',
  `Requirements` text character set utf8,
  `Disabled` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Definition of table `achievement_people`
--

CREATE TABLE `achievement_people` (
  `ID` varchar(30) character set utf8 NOT NULL,
  `UserID` varchar(20) character set utf8 NOT NULL,
  `Unlocked` tinyint(1) unsigned NOT NULL default '0',
  `Progress` text character set utf8,
  PRIMARY KEY  (`ID`,`UserID`),
  KEY `UserID` (`UserID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Definition of table `actions`
--

CREATE TABLE `actions` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `UserID` varchar(20) character set utf8 NOT NULL,
  `Timestamp` datetime NOT NULL,
  `Page` varchar(20) character set utf8 NOT NULL,
  `Action` varchar(20) character set utf8 NOT NULL,
  `SpecificID1` varchar(20) character set utf8 default NULL,
  `SpecificID2` varchar(20) character set utf8 default NULL,
  `AdminAction` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Definition of table `announcements`
--

CREATE TABLE `announcements` (
  `DT` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `Title` varchar(50) collate utf8_general_ci NOT NULL,
  `Announcement` text collate utf8_general_ci NOT NULL,
  `Enabled` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`DT`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Definition of table `award_categories`
--

CREATE TABLE `award_categories` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Category` varchar(30) NOT NULL,
  `Description` text NOT NULL,
  `Enabled` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Definition of table `award_nominations`
--

CREATE TABLE `award_nominations` (
  `Nominee` varchar(20) NOT NULL,
  `Category` int(10) unsigned NOT NULL,
  `Submitter` varchar(20) NOT NULL,
  `Notes` text,
  `Date` datetime NOT NULL,
  `Status` int(10) unsigned NOT NULL default '1',
  `Day` varchar(10) NOT NULL,
  `ID` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Definition of table `contacts`
--

CREATE TABLE `contacts` (
  `UserID` varchar(20) collate utf8_general_ci NOT NULL,
  `Email` varchar(75) collate utf8_general_ci default NULL,
  `MSN` varchar(75) collate utf8_general_ci default NULL,
  `Google` varchar(75) collate utf8_general_ci default NULL,
  `Phone` varchar(15) collate utf8_general_ci default NULL,
  `Mobile` varchar(15) collate utf8_general_ci default NULL,
  `Facebook` varchar(75) collate utf8_general_ci default NULL,
  PRIMARY KEY  (`UserID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Definition of table `dutyteams`
--

CREATE TABLE `dutyteams` (
  `ID` int(11) NOT NULL,
  `Name` text NOT NULL,
  `Colour` char(6) character set utf8 NOT NULL default 'FFFFFF',
  `FontColour` char(6) NOT NULL default '000000',
  `WarCry` text character set utf8,
  `Group` int(10) unsigned NOT NULL default '1',
  PRIMARY KEY  (`ID`,`Group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dutyteams`
--

/*!40000 ALTER TABLE `dutyteams` DISABLE KEYS */;
INSERT INTO `dutyteams` (`ID`,`Name`) VALUES
  (0, 'Default');

--
-- Definition of table `errors`
--

CREATE TABLE `errors` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `UserID` varchar(20) NOT NULL,
  `RequestString` varchar(100) NOT NULL,
  `RequestMethod` char(4) NOT NULL,
  `Query` text NOT NULL,
  `Error` text NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Definition of table `pegosaurus`
--

CREATE TABLE `pegosaurus` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Leader` varchar(20) NOT NULL,
  `Pegs` int(10) unsigned NOT NULL,
  `Date` datetime NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Definition of table `people`
--

CREATE TABLE `people` (
  `UserID` varchar(20) character set utf8 collate utf8_general_ci NOT NULL,
  `Name` text character set utf8 collate utf8_general_ci NOT NULL,
  `Category` varchar(10) character set utf8 collate utf8_general_ci NOT NULL default 'camper',
  `Admin` tinyint(1) unsigned NOT NULL default '0',
  `Nickname` varchar(30) character set utf8 collate utf8_general_ci default NULL,
  `DutyTeam` int(11) NOT NULL default '0',
  `StudyGroup` varchar(10) character set utf8 collate utf8_general_ci default NULL,
  `About` text,
  `Facts` text character set utf8 collate utf8_general_ci,
  `InfoFilled` tinyint(1) NOT NULL default '0',
  `Password` text character set utf8 collate utf8_general_ci,
  `PasswordChanged` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Definition of table `people_groups`
--

CREATE TABLE `people_groups` (
  `UserID` varchar(20) NOT NULL,
  `Group` varchar(20) NOT NULL,
  PRIMARY KEY (`UserID`,`Group`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Definition of table `people_special`
--

CREATE TABLE `people_special` (
  `ID` varchar(20) NOT NULL,
  `Name` varchar(20) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `people_special`
--

/*!40000 ALTER TABLE `people_special` DISABLE KEYS */;
INSERT INTO `people_special` (`ID`,`Name`) VALUES
  ('trex', 'T-Rex'),
  ('fenix', 'Fenix'),
  ('moose', 'Tony');

--
-- Definition of table `photo_captions`
--

CREATE TABLE `photo_captions` (
  `ID` smallint(5) unsigned NOT NULL auto_increment,
  `Filename` varchar(32) collate utf8_general_ci NOT NULL,
  `Caption` varchar(200) collate utf8_general_ci NOT NULL default '',
  `Status` tinyint(4) NOT NULL default '0',
  `Author` varchar(20) collate utf8_general_ci NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Definition of table `photo_event_tags`
--


CREATE TABLE `photo_event_tags` (
  `Filename` varchar(32) NOT NULL,
  `Tag` varchar(255) NOT NULL,
  PRIMARY KEY  (`Filename`,`Tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Definition of table `photo_processing`
--


CREATE TABLE `photo_processing` (
  `Filename` varchar(32) NOT NULL,
  `Uploader` varchar(20) NOT NULL,
  `CampWebsite` tinyint(1) default NULL,
  `Quality` smallint(5) unsigned default NULL,
  `Nobody` tinyint(1) default NULL,
  `Reviewer` varchar(20) default NULL,
  `DateUploaded` datetime NOT NULL,
  `DateReviewed` datetime default NULL,
  PRIMARY KEY  (`Filename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Definition of table `photo_tags`
--


CREATE TABLE `photo_tags` (
  `Filename` varchar(32) NOT NULL,
  `Username` varchar(20) NOT NULL,
  PRIMARY KEY  (`Filename`,`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Definition of table `poll_options`
--


CREATE TABLE `poll_options` (
  `OptionID` smallint(6) NOT NULL auto_increment,
  `PollID` smallint(6) NOT NULL,
  `Response` varchar(50) collate utf8_general_ci NOT NULL,
  PRIMARY KEY  (`OptionID`),
  KEY `PollID` (`PollID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Definition of table `poll_questions`
--


CREATE TABLE `poll_questions` (
  `ID` smallint(6) NOT NULL auto_increment,
  `Question` varchar(50) character set utf8 collate utf8_general_ci NOT NULL,
  `Creator` varchar(20) NOT NULL,
  `Status` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Definition of table `poll_votes`
--


CREATE TABLE `poll_votes` (
  `PollID` smallint(6) NOT NULL,
  `OptionID` smallint(6) NOT NULL,
  `UserID` varchar(20) collate utf8_general_ci NOT NULL,
  PRIMARY KEY  (`PollID`,`UserID`),
  KEY `OptionID` (`OptionID`),
  KEY `UserID` (`UserID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Definition of table `questionnaire`
--


CREATE TABLE `questionnaire` (
  `UserID` varchar(20) character set utf8 collate utf8_general_ci NOT NULL,
  `QuestionStage` smallint(5) NOT NULL default '0',
  `timeOnCamp` tinyint(4) default NULL,
  `TimeOver` text character set utf8 collate utf8_general_ci,
  `Most` text character set utf8 collate utf8_general_ci,
  `Least` text character set utf8 collate utf8_general_ci,
  `God` tinyint(4) default NULL,
  `leaderQuality` text character set utf8 collate utf8_general_ci,
  `GodComment` text character set utf8 collate utf8_general_ci,
  `Hear` tinyint(4) default NULL,
  `HearComment` text character set utf8 collate utf8_general_ci,
  `Posters` tinyint(4) default NULL,
  `OtherComment` text character set utf8 collate utf8_general_ci,
  `NotDoComments` text character set utf8 collate utf8_general_ci,
  `ThemeComments` text character set utf8 collate utf8_general_ci,
  `PostersYes` tinyint(4) default NULL,
  `GeneralComments` text character set utf8 collate utf8_general_ci,
  `FavouriteLeader` text character set utf8 collate utf8_general_ci,
  `LeaderComment` text,
  `Bible1` tinyint(4) unsigned default NULL,
  `Bible2` tinyint(4) unsigned default NULL,
  `Bible3` tinyint(4) unsigned default NULL,
  `Bible4` tinyint(4) unsigned default NULL,
  `Bible5` tinyint(4) unsigned default NULL,
  `BibleComments` text character set utf8 collate utf8_general_ci,
  `Power1` tinyint(4) unsigned default NULL,
  `Power2` tinyint(4) unsigned default NULL,
  `Power3` tinyint(4) unsigned default NULL,
  `PowerComments` text character set utf8 collate utf8_general_ci,
  `ElectivesGeneral1` tinyint(4) unsigned default NULL,
  `ElectivesGeneral2` tinyint(4) unsigned default NULL,
  `ElectivesGeneral3` tinyint(4) unsigned default NULL,
  `Game1` tinyint(4) unsigned default NULL,
  `Game2` tinyint(4) unsigned default NULL,
  `Game3` tinyint(4) unsigned default NULL,
  `Game4` tinyint(4) unsigned default NULL,
  `GameComments` text character set utf8 collate utf8_general_ci,
  `Outdoor1` tinyint(4) unsigned default NULL,
  `Outdoor2` tinyint(4) unsigned default NULL,
  `Outdoor3` tinyint(4) unsigned default NULL,
  `OutdoorComments` text character set utf8 collate utf8_general_ci,
  `Website1` tinyint(4) unsigned default NULL,
  `Website2` tinyint(4) unsigned default NULL,
  `Website3` tinyint(4) unsigned default NULL,
  `WebsiteComments` text character set utf8 collate utf8_general_ci,
  `ShowNight1` tinyint(4) unsigned default NULL,
  `ShowNight2` tinyint(4) unsigned default NULL,
  `ShowNightComments` text,
  `Beards` text character set utf8 collate utf8_general_ci,
  PRIMARY KEY  (`UserID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Definition of table `questionnaire_electives`
--

CREATE TABLE `questionnaire_electives` (
  `ShortName` varchar(30) NOT NULL,
  `LongName` varchar(60) NOT NULL,
  `Type` varchar(10) NOT NULL,
  `Order` double unsigned NOT NULL,
  PRIMARY KEY  (`ShortName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Definition of table `quotes`
--

CREATE TABLE `quotes` (
  `ID` smallint(5) unsigned NOT NULL auto_increment,
  `Person` varchar(50) collate utf8_general_ci NOT NULL default '',
  `Context` varchar(100) collate utf8_general_ci NOT NULL default '',
  `Quote` text collate utf8_general_ci,
  `Status` tinyint(4) NOT NULL default '0',
  `Submitter` varchar(20) collate utf8_general_ci NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Definition of table `suggestions`
--

CREATE TABLE `suggestions` (
  `ID` smallint(30) NOT NULL auto_increment,
  `Idea` text collate utf8_general_ci NOT NULL,
  `Submitter` varchar(30) collate utf8_general_ci NOT NULL,
  `Category` varchar(15) collate utf8_general_ci NOT NULL,
  `Status` smallint(6) NOT NULL default '1',
  `Bug` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `Submitter` (`Submitter`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Definition of table `timetable`
--

CREATE TABLE `timetable` (
  `Day` varchar(12) collate utf8_general_ci NOT NULL,
  `Start` varchar(5) collate utf8_general_ci NOT NULL,
  `End` varchar(5) collate utf8_general_ci NOT NULL,
  `Activity` varchar(30) collate utf8_general_ci NOT NULL,
  `Visible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY  (`Day`,`Start`,`End`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;