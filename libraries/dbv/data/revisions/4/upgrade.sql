ALTER TABLE `questionnaire`
	DROP PRIMARY KEY,
	ADD PRIMARY KEY (`UserID`, `QuizId`);