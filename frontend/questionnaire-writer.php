<?php
  include_once("../includes/start.php");
  if (!$leader) {
    header("Location: ./index.php");
    die;
  }

  function questionnaireWriterDisplay() {
    // Get page names.
    $query = "SELECT * FROM questionnaire_pages ORDER BY Name";
    $res = do_query($query);
    $pages = array();
    while ($row = fetch_row($res)) {
      $pages[intval($row["Id"])] = $row["Name"];
    }

    // Format the question rows.
    $query = "(SELECT 1 AS Rank, Id, PageId, Position, Name\n" .
             " FROM questionnaire_questions\n" .
             " WHERE Position IS NOT NULL)\n" .
             "UNION\n" .
             "(SELECT 2 AS Rank, Id, PageId, Position, Name\n" .
             " FROM questionnaire_questions\n" .
             " WHERE Position IS NULL)\n" .
             "ORDER BY Rank, PageId, Position\n";
    $res = do_query($query);
    $lines = "";
    while ($row = fetch_row($res)) {
      if (!intval($row["Position"])) $lines .= "\n";
      $pageName = intval($row["PageId"]) ?
          "<b>(${pages[intval($row["PageId"])]}) </b>" : "";
      $lines .= "<li>$pageName<a href=\"javascript:v();\" " .
                 "onclick=\"selectSection(" . $row["Id"] . ")\">" .
                 $row["Name"] . "</a> ";
      if (intval($row["Position"])) {
        $lines .= "[<a href=\"javascript:v();\" " .
                  "onclick='change_use(" . $row["Id"] . ")'>Remove</a>] ";
      } else if ($pageName) {
        $lines .= "[<a href=\"javascript:v();\" " .
                  "onclick='change_use(" . $row["Id"] . ")'>Add</a>] ";
      }
      $lines .= "[<a href=\"javascript:v();\" " .
                "onclick='copy_q(" . $row["Id"] . ")' />Copy</a>]";
      $lines .= "</li>\n";
    }
    return $lines;
  }

  function questionnairesDisplay() {
    $query = "SELECT Id, Name FROM questionnaires";
    $res = do_query($query);
    $lines = "";
    while ($row = fetch_row($res)) {
      $lines .= "<li><a href=\"javascript:v();\" " .
                 "onclick=\"selectQuiz(" . $row["Id"] . ")\">" .
                 $row["Name"] . "</a></li>";
    }
    return $lines;
  }

  function write($id) {
    // Create question obj
    $i = 0;
    $questions = array();
    while(isset($_POST["question".$i])) {
      $questions[] = array($_POST["question$i"],
                           intval($_POST["questionType$i"]));
      if ($questions[$i][1] >= 6 && isset($_POST["questionOptions$i"])) {
        // Custom options.
        $questions[$i][] = explode("\n", $_POST["questionOptions$i"]);
        foreach ($questions[$i][2] as &$option) {
          $option = trim($option);
        }
      }
      $i++;
    }

    $page = "NULL";
    if (is_numeric($_POST["page"])) {
      $page = intval($_POST["page"]);
      if ($page < 1) {
        $page = "NULL";
      }
    }
    $hidden = intval($_POST["hidden"]);
    $expandable = intval($_POST["expandable"]);

    if($id === NULL) {
      // New item
      $query = "INSERT INTO questionnaire_questions(\n" .
               "  Name, Questions, PageId, HideName, Expandable)\n" .
               "VALUES (\n" .
               "\"" . str_replace("\"", "\"\"", $_POST["name"]) . "\",\n" .
               "\"" . str_replace("\"", "\"\"",
                                  serialize($questions)) . "\",\n" .
               "$page, $hidden, $expandable)";
    } else {
      $query = "UPDATE questionnaire_questions SET Name = ".
               "\"" . str_replace("\"", "\"\"", $_POST["name"]) . "\",\n".
               "Questions = " .
               "\"" . str_replace("\"", "\"\"",
                                  serialize($questions)) . "\",\n".
               "PageId = $page,\n" .
               "HideName = $hidden,\n" .
               "Expandable = $expandable\n" .
               "WHERE Id = $id";
    }

    do_query($query);
    print questionnaireWriterDisplay();
    die;
  }

  function writeQuestionnaire($id) {
    // Populate pages array.
    $i = 0;
    $pages = array();
    while (isset($_POST["page".$i])) {
      $pages[] = intval($_POST["page$i"]);
      $i++;
    }

    if ($id === NULL) {
      // New item
      $query = "INSERT INTO questionnaires(Name, Pages, Intro, Outro)\n" .
               "VALUES (\n" .
               "\"" . str_replace("\"", "\"\"", $_POST["name"]) . "\",\n" .
               "\"" . str_replace("\"", "\"\"", serialize($pages)) . "\",\n" .
               "\"" . mysql_escape_string($_POST["intro"]) . "\",\n" .
               "\"" . mysql_escape_string($_POST["outro"]) . "\")";

    } else {
      $query = "UPDATE questionnaires SET Name = " .
               "\"" . str_replace("\"", "\"\"", $_POST["name"]) . "\",\n" .
               "Pages = " .
               "\"" . str_replace("\"", "\"\"", serialize($pages)) . "\",\n" .
               "Intro = " .
               "\"" . mysql_escape_string($_POST["intro"]) . "\",\n" .
               "Outro = " .
               "\"" . mysql_escape_string($_POST["outro"]) . "\"\n" .
               "WHERE Id = $id";
    }

    do_query($query);
    print questionnairesDisplay();
    die;
  }

  function copy_section($id) {
    $query = "INSERT INTO questionnaire_questions(Name, Questions)\n" .
             "SELECT Name, Questions FROM questionnaire_questions\n" .
             "  WHERE Id = $id";
    do_query($query);

    print questionnaireWriterDisplay();
    die;
  }

  function details($id) {
    $query = "SELECT * FROM questionnaire_questions WHERE ID = $id";
    $res = do_query($query);
    if ($row = fetch_row($res)) {
      print "{\n";
      print "\"name\": \"" . str_replace("\"", "\\\"", $row["Name"]) . "\",\n";
      print "\"hideName\": " . $row["HideName"] . ",\n";
      print "\"expandable\": " . $row["Expandable"] . ",\n";
      print "\"page\": \"" . str_replace("\"", "\\\"",
                                         $row["PageId"]) . "\",\n";
      print "\"questions\": [";
      $first = true;
      foreach (unserialize($row["Questions"]) as $question) {
        if ($first) $first = false;
        else print ",\n";
        print "[\"";
        print str_replace("\"", "\\\"", $question[0])."\", ";
        print $question[1];
        if ($question[1] >= 6 && count($question) == 3) {
          foreach ($question[2] as $option) {
            print ", \"" . str_replace("\"", "\\\"", $option) . "\"";
          }
        }
        print "]";
      }
      print "]\n}";
    }
    die;
  }

  function detailsQuestionnaire($id) {
    $query = "SELECT * FROM questionnaires WHERE ID = $id";
    $res = do_query($query);
    if ($row = fetch_row($res)) {
      print "{\n";
      print "\"name\": \"" . str_replace("\"", "\\\"", $row["Name"]) . "\",\n";
      print "\"intro\": \"" . str_replace(
          "\n", "\\n", str_replace("\"", "\\\"", $row["Intro"])) . "\",\n";
      print "\"outro\": \"" . str_replace(
          "\n", "\\n", str_replace("\"", "\\\"", $row["Outro"])) . "\",\n";
      print "\"pages\": [";
      $first = true;
      foreach (unserialize($row["Pages"]) as $page) {
        if ($first) $first = false;
        else print ",\n";
        // It's an array of ints, so just print $page.
        print $page;
      }
      print "]\n}";
    }
    die;
  }

  function toggle($id) {
    // Add at the last position.
    $query = "SELECT Id, Position, PageId FROM questionnaire_questions\n" .
             "WHERE PageId IS NOT NULL AND\n" .
             "  PageId = (SELECT PageId FROM questionnaire_questions\n" .
             "            WHERE Id = $id)";
    $res = do_query($query);
    $position = 1;
    $pageId = null;
    $oldPosition = null;
    while ($row = fetch_row($res)) {
      if (!$pageId) {
        $pageId = intval($row["PageId"]);
      }
      if (is_numeric($row["Position"])) {
        if (intval($row["Id"]) === $id) {
          $oldPosition = intval($row["Position"]);
          $position = "NULL";
          break;
        }
        $position++;
      }
    }

    $query = "UPDATE questionnaire_questions SET Position = $position\n" .
             "WHERE Id = $id";
    do_query($query);
    // Shift other questions up.
    if ($pageId && $oldPosition) {
      $query = "UPDATE questionnaire_questions SET Position = Position - 1\n".
               "WHERE PageId = $pageId AND Position > $oldPosition";
      do_query($query);
    }
    print questionnaireWriterDisplay();
    die;
  }

  $urlParts = getUrlParts(
      array("questionnaire-writer", "questionnaire-writer.php"),
      array("method", "id"), 1);
  if ($urlParts !== false) {
    $id = null;
    extract($urlParts);
    if ($id !== null) {
      if (!is_numeric($id)) {
        header("HTTP/1.1 408 Bad Request"); // Bad request
        die("Must specify integer section id.");
      }
      $id = intval($id);
    }
    switch ($method) {
      case "write":
        write($id);
        break;
      case "write_quiz":
        writeQuestionnaire($id);
        break;
      case "copy":
        if ($id === null) {
          header("HTTP/1.1 408 Bad Request"); // Bad request
          die("Must specify section id.");
        }
        copy_section($id);
        break;
      case "details":
        if ($id === null) {
          header("HTTP/1.1 408 Bad Request"); // Bad request
          die("Must specify section id.");
        }
        details($id);
      case "details_quiz":
        if ($id === null) {
          header("HTTP/1.1 408 Bad Request"); // Bad request
          die("Must specify section id.");
        }
        detailsQuestionnaire($id);
      case "toggle":
        if ($id === null) {
          header("HTTP/1.1 408 Bad Request"); // Bad request
          die("Must specify section id.");
        }
        toggle($id);
        break;
    }
  }

  header("HTTP/1.1 200 Ok");
  $title = "Questionnaire Writer";
  $tpl->set('title', $title);

  $query = "SELECT id, name FROM questionnaire_pages ORDER BY name";
  $res = do_query($query);
  $pages = array();
  while ($row = fetch_row($res)) {
    $pages[] = $row;
  }
  $tpl->set("pages", $pages);

  $tpl->set("items", str_replace(
      "\"", "\\\"", str_replace(
          "\n", "\\n", questionnaireWriterDisplay())));
  $tpl->set("questionnaires", str_replace(
      "\"", "\\\"", str_replace(
          "\n", "\\n", questionnairesDisplay())));
  $tpl->set("js", "questionEditor.js");
  fetch();
?>
