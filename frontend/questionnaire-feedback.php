<?php
  include_once("includes/start.php");

  class ResultSection {
    private $name;
    private $discreteAnswers;
    private $textAnswers;
    private $questions;

    function __construct($name) {
      $this->name = $name;
      $this->discreteAnswers = array();
      $this->textAnswers = array();
      $this->questions = array();
      $this->discreteQuestions = array();
    }

    function addTextResponse($user, $id, $answer) {
      if (!isset($this->textAnswers[$id])) {
        $this->textAnswers[$id] = array();
      }
      $this->textAnswers[$id][$user] = $answer;
    }

    function addAnswer($user, $id, $answer) {
      if (!trim($answer)) return;
      if (!isset($this->discreteAnswers[$user])) {
        $this->discreteAnswers[$user] = array();
      }
      if (isset($this->discreteAnswers[$user][$id])) {
        $this->discreteAnswers[$user][$id] .= ", $answer";
      } else {
        $this->discreteAnswers[$user][$id] = $answer;
      }
    }

    function addQuestion($id, $question, $discrete=false) {
      if ($discrete) {
        $this->discreteQuestions[$id] = $question;
      } else {
        $this->questions[$id] = $question;
      }
    }

    function render() {
      $result = sprintf("<b>%s:</b><br/>\n", $this->name);

      if (count($this->discreteAnswers)) {
        $result .= "<table>";
        $headerArray = array();
        ksort($this->discreteAnswers);
        foreach ($this->discreteQuestions as $id => $question) {
          $headerArray[] = $question;
        }
        $result .= sprintf("<tr><th>Camper</th><th>%s</th></tr>\n",
                           implode("</th><th>", $headerArray));

        foreach ($this->discreteAnswers as $user => $answers) {
          $row = array();
          foreach ($this->discreteQuestions as $id => $unused) {
            $row[] = $answers[$id];
          }
          $result .= sprintf("<tr><th>%s</th><td>%s</td></tr>\n",
                             userPage($user), implode("</td><td>", $row));
        }
        $result .= "</table>";
      }

      foreach ($this->textAnswers as $id => $answers) {
        $result .= sprintf("<h3>%s</h3>\n<ul>\n", $this->questions[$id]);
        foreach ($answers as $user=> $answer) {
          $result .= sprintf("<li>%s - <i>%s</i></li>\n", $answer,
                             userPage($user));
        }
        $result .= "</ul>\n";
      }

      return $result;
    }
  }

  function getValues($question) {
    switch ($question[1]) {
      case 0:
        return array("", "No", "Yes");
        break;
      case 1:
        return array("", "Too long", "Little Long", "Just right", "Little short",
                     "Too short");
      case 2:
        return array("", "10", "9", "8", "7", "6", "5", "4", "3", "2", "1");
      case 3:
        return array("", "Excellent", "Good", "Average", "Poor", "Terrible");
      case 6:
      case 7:
        return $question[2];
    }
  }

  error_reporting(E_ALL ^ E_NOTICE);
  // Which questionnaire.
  $id = isset($_GET["id"]) ? $_GET["id"] : false;
  $urlParts = getUrlParts(
      array("questionnaire-feedback", "questionnaire-feedback.php"),
      array("id"), 1);
  if (!$id && $urlParts === false) {
    header(
        "Location: /questionnaire-choose.php?src=/questionnaire-feedback.php");
    die;
  }
  extract($urlParts);
  if (!is_numeric($id)) {
    header(
        "Location: /questionnaire-choose.php?src=/questionnaire-feedback.php");
    die;
  }

  $query = "SELECT Id, Name, Pages FROM questionnaires WHERE Id = $id";
  $res = do_query($query);
  if ($row = fetch_row($res)) {
    $title = $row["Name"];
    $tpl->set('title', $title);
    $pages = unserialize($row["Pages"]);
  } else {
    header(
        "Location: /questionnaire-choose.php?src=/questionnaire-feedback.php");
    die;
  }

  // Populate the pages and questions cache.
  $query = "SELECT p.Id AS Id, p.Name AS Name, q.Name AS Question, q.Id AS qid\n" .
           "FROM questionnaire_pages AS p, questionnaire_questions AS q\n" .
           "WHERE p.Id = q.PageId AND\n" .
           "    p.Id IN (" . implode(", ", $pages) . ")\n" .
           "ORDER BY q.Position";
  $res = do_query($query);
  $resultSections = array();
  while ($row = fetch_row($res)) {
    $resultSections[intval($row["Id"])][$row["qid"]] =
        new ResultSection($row["Question"]);
  }

  // Also cache questions.
  $query = "SELECT Id, Name, Questions, PageId FROM questionnaire_questions\n" .
           "WHERE PageId IN (" . implode(",", $pages) . ") AND\n" .
           "    Position IS NOT NULL\n" .
           "ORDER BY Position ASC";
  $res = do_query($query);
  $questionCache = array();
  $i = 0;
  while ($row = fetch_row($res)) {
    $question = array(
        "name"=>$row["Name"], "questions"=>unserialize($row["Questions"]));
    $questionCache[$row["Id"]] = $question;
    $q = &$resultSections[intval($row["PageId"])][$row["Id"]];
    foreach($question["questions"] as $qid => $value) {
      $q->addQuestion($qid, $value[0], $value[1] !== 5);
    }
  }

  // Now get the responses.
  $query = "SELECT UserID, QuestionStage, Responses FROM questionnaire\n" .
           "WHERE QuizId = $id AND QuestionStage >= 0";
  $res = do_query($query);
  while ($row = fetch_row($res)) {
    $responses = unserialize($row["Responses"]);
    $section = &$resultSections[$pages[intval($row["QuestionStage"])]];
    foreach ($responses as $key => $response) {
      $parts = explode("_", $key);
      $qsid = $parts[1];
      $item = &$section[$qsid];
      $qid = intval($parts[2]);
      if (count($parts) > 3) {
        $sid = intval($parts[3]);
      }
      $question = &$questionCache[$qsid]["questions"][$qid];
      switch ($question[1]) {
        case 5:
          if (trim($response))
            $item->addTextResponse($row["UserID"], $qid, $response);
          break;
        case 0:
        case 1:
        case 2:
        case 3:
        case 6:
          // Get values, and then get the particular value.
          $values = getValues($question);
          $item->addAnswer($row["UserID"], $qid, $values[intval($response)]);
          break;
        case 4:
          $item->addAnswer($row["UserID"], $qid, $response);
          break;
        case 7:
          // Get values, and then get the particular value.
          $values = getValues($question);
          $item->addAnswer($row["UserID"], $qid, $values[$sid]);
      }
    }
  }

  $sections = array();
  foreach($resultSections as &$section) {
    foreach ($section as &$question) {
      $sections[] = $question->render();
    }
  }
  $tpl->set("sections", $sections);

  $numbers = array();
  $bible = array();
  $power = array();

  $items = array("Most", "Least", "TimeOver", "OtherComment", "NotDoComments",
                 "ThemeComments", "GeneralComments", "Beards",
                 "BibleComments", "PowerComments", "GameComments",
                 "OutdoorComments", "WebsiteComments", "ShowNightComments");

  $electives = array();
  $query = "SELECT * FROM `questionnaire_electives` ORDER BY `Type` = 'morning' DESC, `Type` = 'evening' DESC, `Order`";
  $result = do_query($query);

  while ($row = fetch_row($result)) {
    $electives[$row['ShortName']] = $row['LongName'];
  }

  $data = array();
  $electiveResponses = array();

  foreach ($electives as $ID => $longName) {
    $electiveResponses[$longName] = array("numbers" => array(),
                                          "comments" => array());
  }

  foreach ($items as $item) {
    $data[$item] = array();
  }

  # Response lookup arrays begin here
  $timeOnCamp = array(
      "--",
      "5 - <span style='color: green;'>&Uuml;ber!</span>",
      "4 - <span style='color: #999900;'>Awesome</span>",
      "3 - <span style='color: #CC9900;'>Great</span>",
      "2 - <span style='color: #FF6600;'>Average</span>",
      "1 - <span style='color: red;'>Lousy</span>",
      "0 - <span style='color: red;'>Terrible</span>");

  $leaderQuality = array(
      "--",
      "4 - <span style='color: green;'>Always</span>",
      "3 - <span style='color: #999900;'>Usually</span>",
      "2 - <span style='color: #CC9900;'>Sometimes</span>",
      "1 - <span style='color: #FF6600;'>Rarely</span>",
      "0 - <span style='color: red;'>Never</span>",
    );

    $hearingAboutCamp = array(
      "--", "Flyer / Poster", "SU Qld", "&Uuml;berTweak website",
      "School Chaplain", "Church / Youth Group", "Friend", "Been before",
      "Other");

  $posters = array("--", "Yes", "<span style='color: silver;'>No</span>");

  $sendInfo = array("<span style='color: silver;'>No</span>", "Yes");

  $christ = array(
      "--",
      "<strong>No; don't want to learn more</strong>",
      "<strong>No; want to learn more</strong>",
      "<strong>Have been slack, turned back at this camp</strong>",
      "<strong style='color: green;'>Became a Christian at this camp</strong>",
      "Following Christ already",
      "Other");

  $length = array("--", "Too short", "Little short", "Just right",
                  "Little long", "Too long");

  $variety = array("--", "No", "Kind of", "Yes");

  $num = array("--", "<span style='color: red;'>1</span>", "2", "3", "4",
               "<strong>5</strong>");

  $smallgroup = true;

  # Response lookup arrays end here
  if (isset($_GET['camper']) && isset($people[$_GET['camper']])) {
    $extra = " `Q`.`UserID` = '{$_GET['camper']}'";
  } else if (isset($_GET['leaders'])) {
    $extra = " `Category` != 'camper'";
  } else if (isset($_GET['smallgroup'])) {
    $extra = " `DutyTeam` = (SELECT `DutyTeam` FROM `people` WHERE `UserID` = '$username') AND `Category` = 'camper'";
    $smallgroup = true;
  } else if (isset($_GET['group'])) {
    $extra = " `DutyTeam` = '{$_GET['group']}' AND `Category` = 'camper'";
  } else {
    $extra = " `Category` = 'camper'";
    $smallgroup = false;
  }

  $tpl->set('smallgroup', $smallgroup);

  $query = "SELECT * FROM `questionnaire` AS `Q`, `people` AS `P` WHERE `P`.`UserID` = `Q`.`UserID` AND $extra ORDER BY `Name` ASC";
  $res = do_query($query);

  while ($row = fetch_row($res)) {
    # First main table
    if (isset($row['GodComment'])) {
      $christStr = "{$christ[$row['God']]}<br><small>{$row['GodComment']}</small>";
      //$christStr = "<span title='{$row['GodComment']}' style='color: blue;'>{$christ[$row['God']]}</span>";
    } else {
      $christStr = $christ[$row['God']];
    }

    if (isset($row['HearComment'])) {
      $hearStr = "{$hearingAboutCamp[$row['Hear']]}<br><small>{$row['HearComment']}</small>";
    } else {
      $hearStr = $hearingAboutCamp[$row['Hear']];
    }

    $temp = array(
        "name" => "<span style='white-space: nowrap;'>{$row['Name']}</span>",
        "timeOnCamp" => $timeOnCamp[$row['timeOnCamp']],
        "leaderQuality" => $leaderQuality[$row['leaderQuality']],
        "hearingAboutCamp" => $hearStr,
        "posters" => $posters[$row['Posters']],
        "christ" => $christStr,
        "sendInfo" => $sendInfo[$row['PostersYes']]);

    if (strlen($row['FavouriteLeader']) > 1) {
      $temp['favouriteLeader'] = $row['FavouriteLeader'];
    } else {
      $temp['favouriteLeader'] = "--";
    }

    $numbers[] = $temp;

    # Activity responses
    if ($row['Bible1'] + $row['Bible2'] + $row['Bible3'] + $row['Bible4'] > 0) {
      $temp = array(
          "name" => $row['Name'],
          "1" => $num[$row['Bible1']],
          "2" => $num[$row['Bible2']],
          "3" => $num[$row['Bible3']],
          "4" => $length[$row['Bible4']],
          "5" => $num[$row['Bible5']]);
      $bible[] = $temp;
    }

    if ($row['Power1'] + $row['Power2'] + $row['Power3'] > 0) {
      $temp = array(
          "name" => $row['Name'],
          "1" => $num[$row['Power1']],
          "2" => $num[$row['Power2']],
          "3" => $num[$row['Power3']]);
      $power[] = $temp;
    }

    if ($row['Game1'] + $row['Game2'] + $row['Game3'] + $row['Game4'] > 0) {
      $temp = array(
          "name" => $row['Name'],
          "1" => $num[$row['Game1']],
          "2" => $num[$row['Game2']],
          "3" => $length[$row['Game3']],
          "4" => $num[$row['Game4']]);
      $game[] = $temp;
    }

    if ($row['Outdoor1'] + $row['Outdoor2'] + $row['Outdoor3'] > 0) {
      $temp = array(
          "name" => $row['Name'],
          "1" => $num[$row['Outdoor1']],
          "2" => $num[$row['Outdoor2']],
          "3" => $length[$row['Outdoor3']]);
      $outdoor[] = $temp;
    }

    if ($row['Website1'] + $row['Website2'] + $row['Website3'] > 0) {
      $temp = array(
          "name" => $row['Name'],
          "1" => $num[$row['Website1']],
          "2" => $num[$row['Website2']],
          "3" => $num[$row['Website3']]);
      $website[] = $temp;
    }

    if ($row['ShowNight1'] + $row['ShowNight2'] > 0) {
      $temp = array(
          "name" => $row['Name'],
          "1" => $num[$row['ShowNight1']],
          "2" => $length[$row['ShowNight2']]);
      $showNight[] = $temp;
    }

    if ($row['ElectivesGeneral1'] + $row['ElectivesGeneral2'] + $row['ElectivesGeneral3'] > 0) {
      $temp = array(
          "name" => $row['Name'],
          "1" => $num[$row['ElectivesGeneral1']],
          "2" => $num[$row['ElectivesGeneral2']],
          "3" => $length[$row['ElectivesGeneral3']]);
      $electivesGeneral[] = $temp;
    }

    # Elective responses
    foreach ($electives as $ID => $longName) {
      if ($row[$ID."1"] + $row[$ID."2"] > 0) {
        $temp = array(
            "name" => $row['Name'],
            "1" => $num[$row[$ID."1"]],
            "2" => $num[$row[$ID."2"]]);
        $electiveResponses[$longName]['numbers'][] = $temp;
        $comment = trim($row[$ID."Comments"]);
        if (!empty($comment)) {
          $electiveResponses[$longName]['comments'][] = "$comment - <em>{$row['Name']}</em>";
        }

      }
    }

    # General responses
    foreach ($items as $item) {
      $row[$item] = trim($row[$item]);
      if (!empty($row[$item])) {
        $data[$item][] = "{$row[$item]} - <em>{$row['Name']}</em>";
      }
    }

  }

    $temp = $electiveResponses;
    $electiveResponses = array();

    foreach ($temp as $electiveName => $electiveData) {
      $electiveResponses[] = array(
          "name" => $electiveName,
          "data" => $electiveData['numbers'],
          "comments" => $electiveData['comments']);
  }

    foreach ($items as $item) {
      $tpl->set($item, $data[$item]);
    }

    $tpl->set('numbers', $numbers);
    $tpl->set('bible', $bible);
    $tpl->set('power', $power);
    $tpl->set('game', $game);
    $tpl->set('outdoor', $outdoor);
    $tpl->set('website', $website);
    $tpl->set('showNight', $showNight);
    $tpl->set('electivesGeneral', $electivesGeneral);
    $tpl->set('electives', $electiveResponses);

    fetch();
?>
