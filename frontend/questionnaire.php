<?php
  include_once("../includes/start.php");
  $title = 'Questionnaire';
  $tpl->set('title', $title);
  $tpl->set('usersname', $people[$username]);
  $tpl->set('directors', $DIRECTORS);

  class QuestionPage {
    private $name;
    private $questions;

    function __construct($name, $questions) {
      $this->name = $name;
      $this->questions = $questions;
    }

    function name() {
      return $this->name;
    }

    function toHtml() {
      $result = array();
      $first = true;
      foreach ($this->questions as $question) {
        if ($first) $first = false;
        else if (!$question->hideName()) {
          $result[] = "<div style=\"height:20px;\">&nbsp;</div>";
        }
        $result[] = implode("\n", $question->toHtml());
      }
      return implode("\n", $result);
    }
  }

  class QuestionSection {
    private $id;
    private $name;
    private $hideName;
    private $questions;
    private $expandable;

    function __construct($id, $name, $hideName, $questions, $expandable) {
      $this->id = intval($id);
      $this->name = $name;
      $this->hideName = intval($hideName);
      $this->questions = unserialize($questions);
      $this->expandle = $expandable;
    }

    function hideName() {
      return $this->hideName;
    }

    function selection($text, $name, $values) {
      $result = array();
      $result[] = "<table class=\"questionTable\">";
      $result[] = "<tr>";
      $result[] = "<td>";
      $result[] = $text;
      $result[] = "</td>";
      $result[] = "<td>";
      $result[] = "<select name=\"$name\" " .
                  "style=\"margin-left:25px;display:inline;clear:left;\">";
      $result[] = "<option value=\"0\">--</option>";
      foreach ($values as $key => $value) {
        ++$key;
        $result[] = "<option value=\"$key\">$value</option>";
      }
      $result[] = "</select>";
      $result[] = "</td>";
      $result[] = "</tr>";
      $result[] = "</table>";
      return $result;
    }

    function getValues($question) {
      switch ($question[1]) {
        case 0:
          return array("No", "Yes");
          break;
        case 1:
          return array("Too long", "Little Long", "Just right", "Little short",
                       "Too short");
        case 2:
          return array("10", "9", "8", "7", "6", "5", "4", "3", "2", "1");
        case 3:
          return array("5", "4", "3", "2", "1");
          $q .= "<option value=\"0\">--</option>";
          $q .= "<option value=\"5\">5</option>";
          $q .= "<option value=\"4\">4</option>";
          $q .= "<option value=\"3\">3</option>";
          $q .= "<option value=\"2\">2</option>";
          $q .= "<option value=\"1\">1</option>";
          break;
        case 6:
          return $question[2];
      }
    }

    function textArea($text, $name) {
      $result = array();
      $result[] = "<div style=\"margin-top:10px;\">$text</div>";
      $result[] = "<textarea name=\"$name\" /></textarea>";
      return $result;
    }

    function input($text, $name) {
      $result = array();
      $result[] = $text;
      $result[] = "<ul class=\"question\">";
      $result[] = "<li><input type=\"text\" name=\"$name\" value=\"\" /></li>";
      $result[] = "</ul>";
      return $result;
    }

    function radioList($text, $name, $values) {
      $result = array();
      $result[] = $text;
      $result[] = "<ul class=\"question\">";
      foreach ($values as $id => $value) {
        $result[] = "<li><input type=\"radio\" name=\"$name\" " .
                    "value=\"$id\" />$value</li>";
      }
      $result[] = "</ul>";
      return $result;
    }

    function toHtml() {
      $result = array();
      if (!$this->hideName) {
        $result[] = "<h3>" . $this->name . ":</h3>";
      }
      if ($this->expandable) {
        $result[] = "<div class=\"optquest\" id=\"QID" . $this->id . "\">";
        $result[] = "<a href=\"javascript:{}\" " .
                    "onclick=\"questionnaire_toggle(this)\">" .
                    "Did this elective, click to expand:</a>";
      }

      foreach ($this->questions as $key => $question) {
        $name = "question." . $this->id . ".$key";
        switch ($question[1]) {
          case 0:
          case 1:
          case 2:
          case 3:
            $result[] = implode(
                "\n", $this->selection($question[0], $name, $this->getValues(
                    $question)));
            break;
          case 6:
            $result[] = implode("\n", $this->radioList($question[0], $name,
                                                       $question[2]));
            break;
          case 4:
            $result[] = implode("\n", $this->input($question[0], $name));
            break;
          case 5:
            $result[] = implode("\n", $this->textArea($question[0], $name));
            break;
        }
      }

      if ($this->expandable) {
        $result[] = "</div>";
      }
      return $result;
    }
  }

  function fetch_page($pageId, $getQuestions = false) {

    $query = "SELECT Name FROM questionnaire_pages WHERE Id = $pageId";
    if ($row = fetch_row(do_query($query))) {
      $name = $row["Name"];
    }
    // Get questions.
    $questions = array();
    if ($getQuestions) {
      $query = "SELECT Id, Name, HideName, Questions, Expandable\n" .
               "FROM questionnaire_questions\n" .
               "WHERE PageId = $pageId AND Position IS NOT NULL\n" .
               "ORDER BY Position";
      $res = do_query($query);
      while ($row = fetch_row($res)) {
        $questions[] = new QuestionSection($row["Id"],
                                           $row["Name"],
                                           $row["HideName"],
                                           $row["Questions"],
                                           $row["Expandable"]);
      }
    }
    return new QuestionPage($name, $questions);
  }


  // These will almost certainly be overidden.
  $submitted = false;
  $stage = -1;
  $totalStages = -1;
  $pages = array();

  // Which questionnaire.
  $id = isset($_GET["id"]) ? $_GET["id"] : false;
  $urlParts = getUrlParts("questionnaire", array("id"), 1);
  if (!$id && $urlParts === false) {
    header("HTTP/1.1 408 Bad Request");
    die;
  }
  extract($urlParts);
  if (!is_numeric($id)) {
    header("HTTP/1.1 408 Bad Request");
    die;
  }

  // Get the current page for the user.
  $query = "SELECT COUNT(QuestionStage) FROM questionnaire\n" .
           "WHERE UserId = '$username' AND QuizId = $id";
  $res = do_query($query);
  if ($row = fetch_row($res)) {
    $stage = intval($row[0]) - 1;
  }

  // Add a skeleton entry to the database
  if (isset($_GET['begin']) && $stage === -1) {
    do_query("INSERT INTO questionnaire (UserID, QuestionStage, QuizId)\n" .
             "VALUES ('$username', $stage, $id)");
    $stage = 0;
  }

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST["stage"]) || $stage !== intval($_POST["stage"])) {
      die("Stop fiddling with form elements.");
    }
    unset($_POST["stage"]);
    do_query("INSERT INTO questionnaire VALUES('$username', $stage, $id, '" .
             mysql_escape_string(serialize($_POST)) . "')");
    ++$stage;
  }

  // Get the questionnaire from db. This loads the pages and the questions
  // for the current page.
  $query = "SELECT Name, Pages, Intro, Outro FROM questionnaires\n" .
           "WHERE Id = $id";
  $res = do_query($query);
  if ($row = fetch_row($res)) {
    $title = $row["Name"];
    $pageIds = unserialize($row["Pages"]);
    $i = 0;
    foreach ($pageIds as $page) {
      $pages[] = fetch_page($page, $i++ === $stage);
    }
    $totalStages = count($pages);
    $tpl->set("intro", $row["Intro"]);
    $tpl->set("outro", $row["Outro"]);
  } else {
    header("HTTP/1.1 404 Bad Request");
    die;
  }

  // Update the progress table on the right
  $incomplete = "<td style='color: red;'>Incomplete</td>";
  $inProgress = "<td style='color: orange;'>In Progress</td>";
  $complete = "<td style='color: green;'>Completed</td>";
  $progress = array();
  for ($i = 0; $i < $totalStages; ++$i) {
    $line = "<td>" . $pages[$i]->name() . "</td>";
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
  $tpl->set("header", $title);
  $tpl->set("start", false, true);
  $tpl->set("end", false, true);
  $tpl->set("questions", false, true);
  $tpl->set("stage", $stage);
  $tpl->set("progress", $progress);
  if ($stage < 0) {
    $tpl->set("start", true);
  } else if ($stage >= $totalStages) {
    $tpl->set("end", true);
  } else {
    $tpl->set("header", $pages[$stage]->name());
    $tpl->set("questions", $pages[$stage]->toHtml());
  }

  // Display "delete current progress" for admins
  $tpl->set("deleteButton", $admin, true);

  fetch("questionnaire");
?>
