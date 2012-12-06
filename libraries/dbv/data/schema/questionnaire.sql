CREATE TABLE `questionnaire` (
  `UserID` varchar(20) NOT NULL,
  `QuestionStage` smallint(5) NOT NULL DEFAULT '0',
  `QuizId` int(11) NOT NULL,
  `Responses` text,
  PRIMARY KEY (`UserID`,`QuestionStage`,`QuizId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8