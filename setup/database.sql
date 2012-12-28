--
-- Generated by UberSite 2.1.0.
--

--
-- Definition of table `access`
--

CREATE TABLE `access` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `UserID` varchar(20) NOT NULL,
  `Page` varchar(30) NOT NULL,
  `RequestString` text NOT NULL,
  `RequestMethod` char(4) NOT NULL,
  `IP` varchar(15) NOT NULL,
  `UserAgent` text NOT NULL,
  `Filename` text NOT NULL,
  `Refer` text,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

--
-- Definition of table `achievement_list`
--

CREATE TABLE `achievement_list` (
  `ID` varchar(20) NOT NULL,
  `Name` varchar(40) NOT NULL,
  `Description` text NOT NULL,
  `Type` varchar(15) NOT NULL,
  `KeepProgress` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `Requirements` text,
  `Disabled` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC

--
-- Definition of table `achievement_people`
--

CREATE TABLE `achievement_people` (
  `ID` varchar(30) NOT NULL,
  `UserID` varchar(20) NOT NULL,
  `Unlocked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `Progress` text,
  PRIMARY KEY (`ID`,`UserID`),
  KEY `UserID` (`UserID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

--
-- Definition of table `actions`
--

CREATE TABLE `actions` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserID` varchar(20) NOT NULL,
  `Timestamp` datetime NOT NULL,
  `Page` varchar(20) NOT NULL,
  `Action` varchar(20) NOT NULL,
  `SpecificID1` varchar(20) DEFAULT NULL,
  `SpecificID2` varchar(20) DEFAULT NULL,
  `AdminAction` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC

--
-- Definition of table `announcements`
--

CREATE TABLE `announcements` (
  `DT` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Title` varchar(50) NOT NULL,
  `Announcement` text NOT NULL,
  `Enabled` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`DT`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

--
-- Definition of table `award_categories`
--

CREATE TABLE `award_categories` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Category` varchar(30) NOT NULL,
  `Description` text NOT NULL,
  `Enabled` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

--
-- Definition of table `award_nominations`
--

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

--
-- Definition of table `codechallenge_content`
--

CREATE TABLE `codechallenge_content` (
  `Title` varchar(50) NOT NULL,
  `Content` text NOT NULL,
  PRIMARY KEY (`Title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

--
-- Definition of table `codechallenge_results`
--

CREATE TABLE `codechallenge_results` (
  `UserID` varchar(20) NOT NULL,
  `Score` int(11) NOT NULL DEFAULT '0',
  `TimeMS1` decimal(5,5) DEFAULT NULL,
  `TimeMS2` decimal(5,5) DEFAULT NULL,
  `TimeMS3` decimal(5,5) DEFAULT NULL,
  `TimeMSAverage` decimal(5,5) DEFAULT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

--
-- Definition of table `codechallenge_tests`
--

CREATE TABLE `codechallenge_tests` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Params` varchar(200) NOT NULL,
  `Result` varchar(50) NOT NULL,
  `Visible` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

--
-- Definition of table `contacts`
--

CREATE TABLE `contacts` (
  `UserID` varchar(20) NOT NULL,
  `Email` varchar(75) DEFAULT NULL,
  `MSN` varchar(75) DEFAULT NULL,
  `Google` varchar(75) DEFAULT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `Mobile` varchar(15) DEFAULT NULL,
  `Facebook` varchar(75) DEFAULT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

--
-- Definition of table `dutyteams`
--

CREATE TABLE `dutyteams` (
  `ID` int(11) NOT NULL,
  `Name` text NOT NULL,
  `Colour` varchar(20) NOT NULL DEFAULT 'FFFFFF',
  `FontColour` varchar(20) NOT NULL DEFAULT '000000',
  `WarCry` text,
  `Group` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`,`Group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

--
-- Definition of table `errors`
--

CREATE TABLE `errors` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `UserID` varchar(20) NOT NULL,
  `RequestString` varchar(100) NOT NULL,
  `RequestMethod` char(4) NOT NULL,
  `Query` text NOT NULL,
  `Error` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC

--
-- Definition of table `pegosaurus`
--

CREATE TABLE `pegosaurus` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Leader` varchar(20) NOT NULL,
  `Pegs` int(10) unsigned NOT NULL,
  `Date` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC

--
-- Definition of table `people`
--

CREATE TABLE `people` (
  `UserID` varchar(20) NOT NULL,
  `Name` text NOT NULL,
  `Category` varchar(10) NOT NULL DEFAULT 'camper',
  `Admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `Nickname` varchar(30) DEFAULT NULL,
  `DutyTeam` int(11) NOT NULL DEFAULT '0',
  `StudyGroup` varchar(10) DEFAULT NULL,
  `About` text,
  `Facts` text,
  `InfoFilled` tinyint(1) NOT NULL DEFAULT '0',
  `Password` varchar(255) DEFAULT NULL,
  `PasswordChanged` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT

--
-- Definition of table `people_groups`
--

CREATE TABLE `people_groups` (
  `UserID` varchar(20) NOT NULL,
  `Group` varchar(20) NOT NULL,
  PRIMARY KEY (`UserID`,`Group`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8

--
-- Definition of table `people_special`
--

CREATE TABLE `people_special` (
  `ID` varchar(20) NOT NULL,
  `Name` varchar(20) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

--
-- Definition of table `photo_captions`
--

CREATE TABLE `photo_captions` (
  `ID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `Filename` varchar(32) NOT NULL,
  `Caption` varchar(200) NOT NULL DEFAULT '',
  `Status` tinyint(4) NOT NULL DEFAULT '0',
  `Author` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

--
-- Definition of table `photo_event_tags`
--

CREATE TABLE `photo_event_tags` (
  `Filename` varchar(32) NOT NULL,
  `Tag` varchar(255) NOT NULL,
  PRIMARY KEY (`Filename`,`Tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

--
-- Definition of table `photo_processing`
--

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

--
-- Definition of table `photo_tags`
--

CREATE TABLE `photo_tags` (
  `Filename` varchar(32) NOT NULL,
  `Username` varchar(20) NOT NULL,
  PRIMARY KEY (`Filename`,`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC

--
-- Definition of table `poll_options`
--

CREATE TABLE `poll_options` (
  `OptionID` smallint(6) NOT NULL AUTO_INCREMENT,
  `PollID` smallint(6) NOT NULL,
  `Response` varchar(50) NOT NULL,
  PRIMARY KEY (`OptionID`),
  KEY `PollID` (`PollID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

--
-- Definition of table `poll_questions`
--

CREATE TABLE `poll_questions` (
  `ID` smallint(6) NOT NULL AUTO_INCREMENT,
  `Question` varchar(50) NOT NULL,
  `Creator` varchar(20) NOT NULL,
  `Status` tinyint(3) NOT NULL DEFAULT '0',
  `Multiple` tinyint(4) NOT NULL DEFAULT '0',
  `Hidden` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

--
-- Definition of table `poll_votes`
--

CREATE TABLE `poll_votes` (
  `PollID` smallint(6) NOT NULL,
  `OptionID` smallint(6) NOT NULL,
  `UserID` varchar(20) NOT NULL,
  PRIMARY KEY (`PollID`,`UserID`),
  KEY `OptionID` (`OptionID`),
  KEY `UserID` (`UserID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

--
-- Definition of table `questionnaire`
--

CREATE TABLE `questionnaire` (
  `UserID` varchar(20) NOT NULL,
  `QuestionStage` smallint(5) NOT NULL DEFAULT '0',
  `QuizId` int(11) NOT NULL,
  `Responses` text,
  PRIMARY KEY (`UserID`,`QuestionStage`,`QuizId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

--
-- Definition of table `questionnaire_electives`
--

CREATE TABLE `questionnaire_electives` (
  `ShortName` varchar(30) NOT NULL,
  `LongName` varchar(60) NOT NULL,
  `Type` varchar(10) NOT NULL,
  `Order` double unsigned NOT NULL,
  PRIMARY KEY (`ShortName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

--
-- Definition of table `questionnaire_pages`
--

CREATE TABLE `questionnaire_pages` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` text NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

--
-- Definition of table `questionnaire_questions`
--

CREATE TABLE `questionnaire_questions` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` text,
  `HideName` tinyint(4) DEFAULT '0',
  `Questions` text,
  `PageId` int(11) DEFAULT NULL,
  `Position` int(11) DEFAULT NULL,
  `Expandable` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

--
-- Definition of table `questionnaires`
--

CREATE TABLE `questionnaires` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` text,
  `Pages` text,
  `Intro` text,
  `Outro` text,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

--
-- Definition of table `quotes`
--

CREATE TABLE `quotes` (
  `ID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `Person` varchar(50) NOT NULL DEFAULT '',
  `Context` varchar(100) NOT NULL DEFAULT '',
  `Quote` text,
  `Status` tinyint(4) NOT NULL DEFAULT '0',
  `Submitter` varchar(20) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

--
-- Definition of table `suggestions`
--

CREATE TABLE `suggestions` (
  `ID` smallint(30) NOT NULL AUTO_INCREMENT,
  `Idea` text NOT NULL,
  `Submitter` varchar(30) NOT NULL,
  `Category` varchar(15) NOT NULL,
  `Status` smallint(6) NOT NULL DEFAULT '1',
  `Bug` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `Submitter` (`Submitter`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

--
-- Definition of table `suggestions_categories`
--

CREATE TABLE `suggestions_categories` (
  `ID` varchar(20) NOT NULL DEFAULT '',
  `Name` varchar(50) NOT NULL,
  `Description` text,
  `Order` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

--
-- Definition of table `timetable`
--

CREATE TABLE `timetable` (
  `Day` varchar(12) NOT NULL,
  `Start` varchar(5) NOT NULL,
  `End` varchar(5) NOT NULL,
  `Activity` varchar(30) NOT NULL,
  `Visible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`Day`,`Start`,`End`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC

--
-- Definition of table `uber`
--

CREATE TABLE `uber` (
  `UserID` varchar(20) NOT NULL,
  `Url` char(32) NOT NULL,
  `Ubered` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`UserID`,`Url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

