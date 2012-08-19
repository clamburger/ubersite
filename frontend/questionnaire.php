<?php
  include_once("../includes/start.php");
  $title = 'Questionnaire';
  $tpl->set('title', $title);
  $tpl->set('usersname', $people[$username]);
  $tpl->set('directors', $DIRECTORS);

  $submitted = false;
  $stage = 0;
  $totalStages = 4;

  # Get the current stage from the database
  $result = do_query("SELECT `QuestionStage` FROM `questionnaire` WHERE `UserID` = '$username'");
  if ($row = fetch_row($result)) {
    $stage = (int)$row['QuestionStage'];
  }

  # Add a skeleton entry to the database
  if (isset($_GET['begin']) && $stage === 0) {
    $stage = 1;
    do_query("INSERT INTO `questionnaire` (`UserID`, `QuestionStage`) VALUES ('$username', $stage)");
  }

  # Delete current progress
  if (isset($_GET['delete']) && $admin && $stage > 0) {
    do_query("DELETE FROM `questionnaire` WHERE `UserID` = '$username'");
    $tpl->set("success", "Questionnaire progress deleted.");
    $stage = 0;
  }

  # Display "delete current progress" for admins
  if ($admin && $stage > 0) {
    $tpl->set("deleteButton", true, true);
  } else {
    $tpl->set("deleteButton", false, true);
  }

  # The user is moving to the next stage
  if (isset($_POST['stage']) && $stage == $_POST['stage']) {
    $stage++;
    $prevStage = $_POST['stage'];
    unset($_POST['stage']);

    $query = "UPDATE `questionnaire` SET `QuestionStage` = $stage";
    $values = ") VALUES ('$username'";

    foreach ($_POST as $key => $value) {
      $query .= ", `$key` = ";
      $value = userInput($value);
      if (is_numeric($value)) {
        $query .= "$value";
      } else if (empty($value)) {
        $query .= "NULL";
      } else {
        $query .= "'$value'";
      }
    }

    $query .= " WHERE UserID = '$username'";
    do_query($query);

    if ($prevStage < $totalStages) {
      $tpl->set("success", "Section $prevStage of the questionnaire successfully submitted.");
    } else {
      $tpl->set("success", "Congratulations. The test is now over. All Aperture technologies remain safely operational up to 4000 degrees Kelvin. Rest assured that there is absolutely no chance of a dangerous equipment malfunction prior to your victory candescence. Thank you for participating in this Aperture Science computer-aided enrichment activity. Goodbye.");
      action("submitted");
    }

  }

  # Update the header
  $headers = array("Camp Questionnaire:", "General Feedback:",
                   "Activity Feedback:", "Elective Feedback:",
                   "Final Comments:", "Camp Questionnaire:");
  $tpl->set("header", $headers[$stage]);

  # Update the progress table on the right
  $incomplete = "<td style='color: red;'>Incomplete</td>";
  $inProgress = "<td style='color: orange;'>In Progress</td>";
  $complete = "<td style='color: green;'>Completed</td>";

  for ($i = 1; $i <= $totalStages; $i++) {
    if ($i > $stage) {
      $tpl->set("stage{$i}Progress", $incomplete);
    } else if ($i == $stage) {
      $tpl->set("stage{$i}Progress", $inProgress);
    } else {
      $tpl->set("stage{$i}Progress", $complete);
    }
  }

  # Ensure the correct set of questions is showing
  for ($i = 0; $i <= $totalStages + 1; $i++) {
    if ($i === $stage) {
      $tpl->set("stage$i", true, true);
    } else {
      $tpl->set("stage$i", false, true);
    }
  }

  # Commonly used responses
  $five = '<option value="0">--</option>' .
          '<option value="5" style="background-color: #63BE7B;">5</option>' .
          '<option value="4" style="background-color: #B1D580;">4</option>' .
          '<option value="3" style="background-color: #FFEB84;">3</option>' .
          '<option value="2" style="background-color: #FBAA77;">2</option>' .
          '<option value="1" style="background-color: #F8696B;">1</option>';

  $length = '<option value="0">--</option>' .
            '<option value="5" style="background-color: #F8696B;">Far too long</option>' .
            '<option value="4" style="background-color: #FFEB84;">A little too long</option>' .
            '<option value="3" style="background-color: #63BE7B;">Just right</option>' .
            '<option value="2" style="background-color: #FFEB84;">A little too short</option>' .
            '<option value="1" style="background-color: #F8696B;">Far too short</option>';

  # Questions for stage 2
  $stage2 = array(
      array("id" => "Bible",
            "name" => "Download",
            "questions" => array(
                array("id" => 1, "name" => "Did you find the studies interesting?", "answers" => $five),
                array("id" => 2, "name" => "Were the studies relevant to you?", "answers" => $five),
                array("id" => 3, "name" => "Did you find the sessions challenging?", "answers" => $five),
                array("id" => 4, "name" => "Did you think the sessions were of appropriate length?", "answers" => $length),
                array("id" => 5, "name" => "Did you find the skits interesting?", "answers" => $five))),
      array("id" => "Power",
            "name" => "Power Down",
            "questions" => array(
                array("id" => 1, "name" => "Did you enjoy hearing about the leaders' lives?", "answers" => $five),
                array("id" => 2, "name" => "Did you find the leaders' stories relevant to your own life?", "answers" => $five))),
      array("id" => "Game",
            "name" => "Game Strategy",
            "questions" => array(
                array("id" => 1, "name" => "How much did you enjoy the Game Strategy sessions?", "answers" => $five),
                array("id" => 2, "name" => "Did you like the choice of games?", "answers" => $five),
                array("id" => 3, "name" => "Did you think the Game Strategy sessions were of appropriate length?", "answers" => $length),
                array("id" => 4, "name" => "Did you find the Game Strategy sessions helpful?", "answers" => $five))),
      array("id" => "Outdoor",
            "name" => "Outdoor Games",
            "questions" => array(
                array("id" => 1, "name" => "How much did you enjoy the Outdoor Games sessions?", "answers" => $five),
                array("id" => 2, "name" => "How clear were the rules and instructions?", "answers" => $five),
                array("id" => 3, "name" => "Did you think the Outdoor Games sessions were of appropriate length?", "answers" => $length))),
    /*array("id" => "Website",
        "name" => "Camp Website",
        "questions" => array(array("id" => 1, "name" => "How much did you like the website?", "answers" => $five),
                   array("id" => 2, "name" => "How easy was it to use?", "answers" => $five),
                   array("id" => 3, "name" => "How much did you use the website?", "answers" => $five))),*/
      array("id" => "ShowNight",
            "name" => "Show Night",
            "questions" => array(
                array("id" => 1, "name" => "How much did you enjoy Show Night?", "answers" => $five),
                array("id" => 2, "name" => "Did you think that Show Night went for the right amount of time?", "answers" => $length)))
  );

  # Electives for stage 3
  $stage3 = array();

  $query = "SELECT * FROM `questionnaire_electives` ORDER BY `Type` = 'morning' DESC, `Type` = 'evening' DESC, `Order`";
  $result = do_query($query);
  while ($row = fetch_row($result)) {
    $stage3[] = array("id" => $row['ShortName'], "name" => $row['LongName'], "type" => $row['Type']);
  }

  $tpl->set("stage2Questions", $stage2);
  $tpl->set("stage3Questions", $stage3);

  # Template stuff
  $tpl->set('friday', $questionnaire || $admin, true);
  $tpl->set('five', $five);
  $tpl->set('length', $length);

  $tpl->set('submitted', $submitted, true);
  $a = $tpl->fetch('./templates/questionnaire.tpl');
  $a = preg_replace("/\<tag:([^\/])* \/>/", "", $a);
  //$tpl->set('content', $a);

  //echo $tpl->fetch('./templates/master.tpl');

  fetch("questionnaire", $a);
?>
