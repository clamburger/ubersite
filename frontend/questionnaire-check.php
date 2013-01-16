<?php
  include_once("includes/start.php");
  $title = 'Questionnaire Status';
  $shortTitle = 'Questionnaire';
  $DISABLE_UBER_BUTTON = true;
  $tpl->set('title', $title);
  $tpl->set('shortTitle', $shortTitle);

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
    header("Location: /questionnaire-choose?src=/questionnaire-check");
    exit;
  }

  $details = json_decode($row['Pages']);
  $pages = [];
  foreach ($details->PageOrder as $pageID) {
    if (isset($details->Pages->$pageID)) {
      $pages[] = $details->Pages->$pageID->Title;
    }
  }

  $tpl->set("pages", $pages);

  // Fill the raw status with an empty list of campers
  $rawStatus = [];
  $query = "SELECT `UserID` FROM `people` WHERE `Category` = 'camper' " .
           "ORDER BY `Name` ASC";
  $result = do_query($query);
  while ($row = mysql_fetch_assoc($result)) {
    $rawStatus[$row['UserID']] = 0;
  }

  $query = "SELECT `UserID`, `QuestionStage` FROM `questionnaire` WHERE `QuizId` = $id";
  $result = do_query($query);
  while ($row = fetch_row($result)) {
    $user = $row["UserID"];
    $stage = intval($row["QuestionStage"]);
    if (isset($rawStatus[$user])) {
      $rawStatus[$user] = $stage;
    }
  }

  $status = [];
  $totals = array_fill(1, count($pages), 0);
  foreach ($rawStatus as $userID => $userStatus) {
    $temp = array("name" => userpage($userID));
    for ($i = 1; $i <= count($pages); $i++) {
      if ($i > $userStatus) {
        $temp["stages"][] = "<td style='text-align: center;'>---</td>";
      } else if ($i === $userStatus) {
        $temp["stages"][] = "<td style='text-align: center; background-color: orange; color: white;'>In Progress</td>";
      } else {
        $temp["stages"][] = "<td style='text-align: center; background-color: green; color: white;'>Complete</td>";
        $totals[$i]++;
      }
    }
    $status[] = $temp;
  }

  foreach ($totals as $key => $total) {
    $totals[$key] = "$total / ".count($rawStatus);
  }

  $tpl->set('status', $status);
  $tpl->set('totals', $totals);
  $tpl->set('head', '<meta http-equiv="refresh" content="5;/questionnaire-check/'.$id.'?autorefresh" >');

  fetch();
?>
