<?php
  include_once("../includes/start.php");
  $title = 'Questionnaire';
  $tpl->set('title', $title);
  $tpl->set('usersname', $people[$username]);
  $tpl->set('directors', $DIRECTORS);

  if (isset($_GET['view']) && $leader) {
    $title = 'View Feedback';
    $tpl->set('title', $title);
    $tpl->set('view_feedback', true, true);
    $username = $_GET['view'];
    $tpl->set('usersname', $people[$username]);
  } else {
    $title = 'Questionairre';
    $tpl->set('title', $title);
    $tpl->set('view_feedback', false, true);
    $tpl->set('usersname', $people[$username]);
    $tpl->set('directors', $DIRECTORS);
  }

  $submitted = false;

  if (num_rows(do_query("SELECT * FROM questionnaire WHERE UserID = '$username'"))){
    $submitted = true;
  }

  if (isset($_POST['submitter'])){
    unset($_POST['submitter']);
    do_query("DELETE FROM questionnaire WHERE UserID = '$username'");
    $query = "INSERT INTO questionnaire(UserID";
    $values = ") VALUES ('$username'";
    $electiveAnswer = array();
    $cruAnswer = array();
    foreach ($_POST as $key => $value) {
      if(strpos($key, "elective") !== FALSE) {
        $electiveAnswer[$key] = $value;
        continue;
      }
      if (strpos($key, "CRU_") === 0) {
        $cruAnswer[$key] = $value;
        continue;
      }

      $query .= ',' . $key;

      if(is_numeric($value)){
        $values .= ',' . $value;
      } else {
        $values .= ',"' . str_replace("\"", "\"\"", $value) . '"';
      }
    }

    $query .= ", ElectiveQuestions, CruQuestions";
    $values .= ", \"" . str_replace("\"", "\"\"", serialize($electiveAnswer)) . "\"";
    $values .= ", \"" . str_replace("\"", "\"\"", serialize($cruAnswer)) . "\"";

    $query .= $values . ")";
    do_query($query);
    $submitted = true;
  }

  if ($submitted) {
    $res = do_query("SELECT * FROM questionnaire WHERE UserID = '$username'");
    $row = fetch_row($res);
    $submittedvalues = "{\n";
    foreach ($row as $key => $value) {
      if ($key == 'UserID') continue;
      if (is_numeric($key)) continue;
      if ($key == "ElectiveQuestions") continue;
      if ($key == "CruQuestions") continue;
      $submittedvalues .= "\"$key\": ";
      if (is_numeric($value)) {
        $submittedvalues .= $value . ",\n";
      } else {
        $submittedvalues .= "\"".str_replace("\r\n", '\n', str_replace("\"", "\\\"", $value))."\",\n";
      }
    }
    $row["ElectiveQuestions"] = unserialize($row["ElectiveQuestions"]);
    $row["CruQuestions"] = unserialize($row["CruQuestions"]);
    foreach ($row["ElectiveQuestions"] as $key => $value) {
      $submittedvalues .= "\"$key\":";
      if(is_numeric($value)) {
        $submittedvalues .= $value . ",\n";
      } else {
        $submittedvalues .= "\"".str_replace("\r\n", "\\n", str_replace("\"", "\\\"", $value))."\",\n";
      }
    }
    foreach ($row["CruQuestions"] as $key => $value) {
      $submittedvalues .= "\"$key\":";
      if(is_numeric($value)) {
        $submittedvalues .= $value . ",\n";
      } else {
        $submittedvalues .= "\"".htmlentities(str_replace("\r\n", '\n', str_replace("\"", "\\\"", $value)))."\",\n";
      }
    }
    $submittedvalues .= "\"t\":\"\"\n}";
    $tpl->set('submittedvalues', $submittedvalues, true);
  }

  $query = "SELECT * FROM elective_questions WHERE InUse = 1";
  $res = do_query($query);
  $electives = array();
  while ($row = fetch_row($res)) {
    $i = 0;
    $t = array();
    $t["name"] = $row["Name"];
    $t["ident"] = "elective".$row["Id"];
    $questions = array();
    foreach (unserialize($row["Questions"]) as $question) {
      $q = $question[0];
      $q .= "<select name=\"${t["ident"]}_$i\" style=\"margin-left:25px;display:inline;clear:left;\">";
      switch ($question[1]) {
        case 0:
          $q .= "<option value=\"0\">--</option>";
          $q .= "<option value=\"2\">No</option>";
          $q .= "<option value=\"1\">Yes</option>";
          break;
        case 1:
          $q .= "<option value=\"0\">--</option>";
          $q .= "<option value=\"5\">Too long</option>";
          $q .= "<option value=\"4\">Little long</option>";
          $q .= "<option value=\"3\">Just right</option>";
          $q .= "<option value=\"2\">Little short</option>";
          $q .= "<option value=\"1\">Too short</option>";
          break;
        case 2:
          $q .= "<option value=\"0\">--</option>";
          $q .= "<option value=\"10\">10</option>";
          $q .= "<option value=\"9\">9</option>";
          $q .= "<option value=\"8\">8</option>";
          $q .= "<option value=\"7\">7</option>";
          $q .= "<option value=\"6\">6</option>";
          $q .= "<option value=\"5\">5</option>";
          $q .= "<option value=\"4\">4</option>";
          $q .= "<option value=\"3\">3</option>";
          $q .= "<option value=\"2\">2</option>";
          $q .= "<option value=\"1\">1</option>";
          break;
        case 3:
          $q .= "<option value=\"0\">--</option>";
          $q .= "<option value=\"5\">5</option>";
          $q .= "<option value=\"4\">4</option>";
          $q .= "<option value=\"3\">3</option>";
          $q .= "<option value=\"2\">2</option>";
          $q .= "<option value=\"1\">1</option>";
          break;
      }
      $q .= "</select>";
      $questions[] = $q;
      $i++;
    }
    $t["questions"] = $questions;
    $electives[] = $t;
  }

  $tpl->set('electives', $electives);

  $tpl->set('js', "questionnaire.js", true);
  $tpl->set('submitted', $submitted, true);
  $a = $tpl->fetch('./templates/questionnaire.tpl');
  $a = preg_replace("/\<tag:([^\/])* \/>/", "", $a);
  $tpl->set('content', $a);

  echo $tpl->fetch('./templates/master.tpl');
?>
