<?php
  include_once("includes/start.php");
  $title = 'Questionnaire';
  $DISABLE_UBER_BUTTON = true;
  $tpl->set('title', $title);
  $tpl->set('usersname', $people[$username]);
  $tpl->set('directors', $DIRECTORS);

  // These will almost certainly be overidden.
  $submitted = false;
  $stage = 0;
  $totalStages = 0;
  $currentData = [];

  // Which questionnaire.
  $id = $SEGMENTS[1];

  if (!$id) {
    header("Location: /questionnaire-choose?src=/questionnaire");
    exit;
  }

  $id = intval($id);

  // Check that the questionnaire exists, and if it does, load up information about it
  $query = "SELECT * FROM `questionnaires` WHERE `Id` = $id";
  $result = do_query($query);
  if (!$row = mysql_fetch_assoc($result)) {
    header("Location: /questionnaire-choose?src=/questionnaire");
    exit;
  }

  $title = $row['Name'];
  $tpl->set("intro", $row["Intro"]);
  $tpl->set("outro", $row["Outro"]);

  $details = json_decode($row['Pages']);
  $questions = [];
  $groups = [];
  $pages = [];

  use Questionnaire\Question;
  use Questionnaire\Group;
  use Questionnaire\Page;

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

  $totalStages = count($pageOrder);

  $tpl->set("ID", $id);

  // Get the current page for the user.
  $query = "SELECT * FROM questionnaire " .
           "WHERE `UserID` = '$username' AND `QuizId` = $id";
  $result = do_query($query);
  if ($row = mysql_fetch_assoc($result)) {
    $stage = intval($row['QuestionStage']);
    $currentData = json_decode($row['Responses'], true);
  }

  // Add a skeleton entry to the database
  if ($SEGMENTS[2] == "begin" && $row === false) {
    do_query("INSERT INTO `questionnaire` (`UserID`, `QuizId`, `QuestionStage`, `Responses`) " .
             "VALUES ('$username', $id, 1, '{}')");
    $stage = 1;
  }

  // Delete current progress
  if ($SEGMENTS[2] == "delete" && $user->isAdmin()) {
    do_query("DELETE FROM `questionnaire` " .
             "WHERE `UserID` = '$username' AND `QuizId` = $id");
    $stage = 0;
    $messages->addMessage(new Message("success", "Hopes deleted."));
  }

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["stage"]) && $stage === intval($_POST["stage"])) {
      unset($_POST["stage"]);
      $currentData = array_merge($currentData, $_POST);
      $encoded = mysql_real_escape_string(json_encode($currentData));
      $stage++;
      $query = "UPDATE `questionnaire` SET `Responses` = \"$encoded\", `QuestionStage` = $stage ";
      $query .= "WHERE `QuizId` = $id AND `UserID` = \"$username\"";
      do_query($query);
      storeMessage("success", $pageOrder[$stage-2]->title." successfully submitted.");
      refresh();
    }
  }

  // Update the progress table on the right
  $incomplete = "<td style='color: red;'>Incomplete</td>";
  $inProgress = "<td style='color: orange;'>In Progress</td>";
  $complete = "<td style='color: green;'>Completed</td>";
  $progress = array();
  for ($i = 1; $i <= $totalStages; ++$i) {
    $line = "<td>$i. {$pageOrder[$i-1]->title}</td>";
    if ($i > $stage) {
      $line .= $incomplete;
    } else if ($i == $stage) {
      $line .= $inProgress;
    } else {
      $line .= $complete;
    }
    $progress[] = $line;
  }

  $tpl->set("title", $title);
  $tpl->set("start", false, true);
  $tpl->set("end", false, true);
  $tpl->set("questions", false, true);
  $tpl->set("stage", $stage);
  $tpl->set("progress", $progress);
  if ($stage === 0) {
    $tpl->set("start", true);
  } else if ($stage > $totalStages) {
    $messages->addMessage(new Message("alert", "Congratulations. The test is now over. ".
      "All Aperture technologies remain safely operational up to 4000 degrees Kelvin. ".
      "Rest assured that there is absolutely no chance of a dangerous equipment malfunction ".
      "prior to your victory candescence. Thank you for participating in this Aperture Science ".
      "computer-aided enrichment activity. Goodbye."));
    $tpl->set("end", true);
  } else {
    $tpl->set("title", $pageOrder[$stage-1]->title);
    $tpl->set("questions", $pageOrder[$stage-1]->renderHTML());
  }

  // Display "delete current progress" for admins
  $tpl->set("deleteButton", $user->isAdmin(), true);

  fetch("questionnaire");
?>
