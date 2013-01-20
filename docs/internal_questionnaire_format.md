#Questionnaire Format

A questionnaire is a JSON object containing the following items.

* `Questions` **required**: an array of Question objects indexed by question ID.
* `Groups` **required**: an array of Group objects indexed by group ID.
* `Pages` **required**: an array of Page objects indexed by page ID.
* `PageOrder` **required**: a list of page IDs which determines the order in which pages are shown.
* `FeedbackTable`: a list of question IDs that will appear in a table at the top of the feedback page.

## Questions

* `Question` **required**: the actual question that will be displayed.
* `QuestionShort`: a short version of the question that will be used for table headers on the feedback page. If not provided, will default to the value of Question.
* `AnswerType` **required**: the type of response that is expected. Valid values are:
 * `Text`: single-line text input
 * `Textarea`: multi-line text input
 * `Radio`: radio buttons
 * `Dropdown`: dropdown menu
 * `1-5`: dropdown menu containing the numbers 5 to 1
 * `Length`: dropdown menu containing values related to length of time
* `AnswerOptions`: an array of answers that the user will be able to choose from. **Required** if using AnswerType "Radio" or "Dropdown", ignored otherwise.
* `AnswerOther`: if true, adds a single-line text input under the last radio button. Only valid for AnswerType "Radio". Note that this doesn't add a corresponding label, so you should include an "Other" option as the last AnswerOption if you use this. *Default: false*

## Groups

* `Title` **required**: the title of the group.
* `Questions` **required**: a list of question IDs that will be included in the group. Questions will appear in the order given.
* `Collapsible`: if true, group will appear with the questions hidden and a button to show them. *Default: false*
* `Comments`: if true, a multi-line text input will be added at the end of the group with a corresponding label. *Default: true*

## Pages

* `Title` **required**: the title of the page.
* `Questions` **required**: a list of group or question IDs that will be shown on the page. Questions and groups will appear in the order given.
* `Intro`: if provided, the text specified here will appear at the top of the page. Full HTML can be used.

# Things to note

* Questions **must not** be included in groups or pages more than once. Although they will display correctly, responses will be overridden.
* In a similar vein, groups **must not** be included in pages more than once and pages **must not** be declared in the page order more than once.
* Questions and groups **must not** end in `-comments` or `-other` as these are used internally.
* Groups are designed primarily for dropdowns. Other AnswerTypes **may** be used, but they might not be fully styled.
* Groups **should** keep all dropdown questions before all non-dropdown questions. No data loss will occur if you don't, but the feedback won't look as neat.