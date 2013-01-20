<?php
  include_once("includes/start.php");
  $DISABLE_UBER_BUTTON = true;

  use Questionnaire\Question;
  use Questionnaire\Group;
  use Questionnaire\Page;

  // Which questionnaire.
  $id = $SEGMENTS[1];

  if (!$id) {
    header("Location: /questionnaire-choose?src=/questionnaire-feedback");
    exit;
  }

  $id = intval($id);

  $query = "SELECT * FROM `questionnaires` WHERE `Id` = $id";
  $result = do_query($query);
  if (!$row = mysql_fetch_assoc($result)) {
    header("Location: /questionnaire-choose?src=/questionnaire-feedback");
    exit;
  }

  $title = $row["Name"];
  $tpl->set('title', $title);
  $details = json_decode($row["Pages"]);

  $questions = [];
  $groups = [];
  $pages = [];

  foreach ($details->Questions as $questionID => $question) {
    $question->QuestionID = $questionID;
    $questions[$questionID] = new Question($question);
  }
  foreach ($details->Groups as $groupID => $group) {
    $group->GroupID = $groupID;
    $groups[$groupID] = new Group($group, $questions);
  }
  foreach ($details->Pages as $pageID => $page) {
    $page->PageID = $pageID;
    $pages[$pageID] = new Page($page, $questions, $groups);
  }

  $pageOrder = [];
  foreach ($details->PageOrder as $pageID) {
    if (isset($pages[$pageID])) {
      $pageOrder[] = $pages[$pageID];
    }
  }

  // Conditional
  $where = "`Category` = 'camper'";
  if ($SEGMENTS[2] && isset($people[$SEGMENTS[2]])) {
    $where = "`UserID` = '{$SEGMENTS[2]}'";
  } else if ($SEGMENTS[2] == "smallgroup") {
    $where = "`DutyTeam` = (SELECT `DutyTeam` FROM `people` WHERE `UserID` = '$username') AND `Category` = 'camper'";
  }

  $smallgroup = $SEGMENTS[2] == "smallgroup";

  // Find the responses
  $query = "SELECT `Name`, `UserID`, `Responses` FROM `questionnaire` ".
           "INNER JOIN `people` USING(`UserID`) WHERE $where".
           "AND `QuizId` = $id ORDER BY `Name` ASC";
  $result = do_query($query);

  $allResponses = [];

  while ($row = mysql_fetch_assoc($result)) {
    $responses = json_decode($row['Responses']);
    foreach ($responses as $questionID => $answer) {
      if (!$answer) {
        continue;
      }
      if (!isset($allResponses[$questionID])) {
        $allResponses[$questionID] = [];
      }
      $allResponses[$questionID][$row['UserID']] = ["UserID" => $row['UserID'], "Answer" => $answer];
    }
  }


  $output = "";
  foreach ($pageOrder as $page) {
    $output .= "<h2>{$page->title}</h2>\n";
    foreach ($page->questions as $question) {
      $output .= $question->renderFeedback($allResponses, $people);
    }
  }

  $tpl->set("id", $id);
  $tpl->set("output", $output);
  $tpl->set("smallgroup", $smallgroup);

  fetch();
?>
