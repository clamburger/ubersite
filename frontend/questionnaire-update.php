<?php
  include_once("includes/start.php");
  $title = 'Synchronise Questionnaire Tables';
  $shortTitle = 'Questionnaire';
  $tpl->set('title', $title);
  $tpl->set('shortTitle', $shortTitle);


  # Ignore columns starting with these values (because they aren't electives)
  $ignore = array("Bible", "Power", "Game", "Outdoor", "Website", "ShowNight", "ElectivesGeneral");

  $columns = array();
  # This query will only get columns ending in 1, which excludes a bunch of
  # "static" questions
  $query = "SHOW COLUMNS FROM `questionnaire` LIKE '%1'";
  $result = do_query($query);

  # Chop the last digit off
  while ($row = fetch_row($result)) {
    $ID = substr($row['Field'], 0, -1);
    if (array_search($ID, $ignore) !== false) {
      continue;
    }
    $columns[$ID] = array(true, false, false);
  }

  # Now cross-reference with the electives table.
  $query = "SELECT `ShortName`, `LongName` FROM `questionnaire_electives`";
  $result = do_query($query);

  while ($row = fetch_row($result)) {
    if (isset($columns[$row['ShortName']])) {
      $columns[$row['ShortName']][1] = true;
      $columns[$row['ShortName']][2] = $row['LongName'];
    } else {
      $columns[$row['ShortName']] = array(false, true, $row['LongName']);
    }
  }

  $columnHTML = array();
  $add = array();
  $remove = array();

  ksort($columns);

  # Check if any questionnaires have been submitted
  $query = "SELECT COUNT(*) as `count` FROM `questionnaire`";
  $result = fetch_row(do_query($query));
  $count = $result['count'];

  # Generate the HTML for the table
  foreach ($columns as $ID => $data) {
    $HTML = "<td>$ID</td>\n";
    if ($data[2]) {
      $HTML .= "<td>{$data[2]}</td>\n";
    } else {
      $HTML .= "<td>---</td>\n";
    }
    if (!$data[0]) {
      $add[] = $ID;
      $HTML .= "<td style='color: white; background-color: red; text-align: center;'>New elective: needs to be added</td>";
    } else if (!$data[1]) {
      $remove[] = $ID;
      $HTML .= "<td style='color: white; background-color: orange; text-align: center;'>Old elective: can be removed</td>";
    } else {
      $HTML .= "<td style='color: white; background-color: green; text-align: center;'>Present in both tables</td>";
    }

    $columnHTML[] = $HTML;
  }

  # Make changes to the `questionnaire` table.
  if (isset($_POST['submit'])) {

    # Adding new electives
    if ($_POST['submit'] == "Add New Electives" && count($add) > 0) {

      $query = "ALTER TABLE `questionnaire`";
      foreach ($add as $ID) {
        $query .= " ADD COLUMN `{$ID}1` TINYINT(4) UNSIGNED,";
        $query .= " ADD COLUMN `{$ID}2` TINYINT(4) UNSIGNED,";
        $query .= " ADD COLUMN `{$ID}Comments` TEXT,";
      }
      $query = substr($query, 0, -1);

      $result = do_query($query);
      if ($result) {
        $messages->addMessage(new Message("success",
          "The new electives were succesfully added to the <tt>`questionnaire`</tt> table."));
      } else {
        $messages->addMessage(new Message("error",
          "An error occurred! The new electives could not be added!"));
      }

    # Removing old electives
    } else if ($_POST['submit'] == "Remove Old Electives" && count($remove) > 0) {
      if ($count) {
        $messages->addMessage(new Message("error",
          "You can't remove old electives with rows still in the <tt>`questionnaire`</tt> table!"));
      } else {

        $query = "ALTER TABLE `questionnaire`";
        foreach ($remove as $ID) {
          $query .= " DROP COLUMN `{$ID}1`,";
          $query .= " DROP COLUMN `{$ID}2`,";
          $query .= " DROP COLUMN `{$ID}Comments`,";
        }
        $query = substr($query, 0, -1);

        $result = do_query($query);
        if ($result) {
          $messages->addMessage(new Message("success",
            "The old electives were succesfully removed from the <tt>`questionnaire`</tt> table."));
        } else {
          $messages->addMessage(new Message("error",
            "An error occurred! The old electives could not be removed!"));
        }
      }
    }
    unset($_POST);
  }

  $tpl->set('columns', $columnHTML, true);

  $HTML = "";

  # Generate the HTML for changes that need to be made
  if (count($add) === 0 && count($remove) === 0) {
    $HTML .= "Both tables are currently in sync: no changes need to be made.";
  } else {
    if (count($add)) {
      $HTML .= "The following electives are not present in the <tt>`questionnaire`</tt> table.\n";
      $HTML .= "<ul style='margin: 0px;'>\n";
      foreach ($add as $ID) {
        $HTML .= "\t<li>$ID</li>\n";
      }
      $HTML .= "</ul>\n";
      $HTML .= "<input type=\"submit\" name=\"submit\" value=\"Add New Electives\" style=\"font-size: 150%;\" /><br /><br />";
    }
    if (count($remove)) {
      $HTML .= "The following electives no longer need to be in the <tt>`questionnaire`</tt> table.";
      if ($count) {
        $HTML .= " They cannot be removed until the <tt>`questionnaire`</tt> table is empty.";
      }
      $HTML .= "\n<ul style='margin: 0px;'>\n";
      foreach ($remove as $ID) {
        $HTML .= "\t<li>$ID</li>\n";
      }
      $HTML .= "</ul>\n";
      $HTML .= "<input type=\"submit\" name=\"submit\" value=\"Remove Old Electives\" style=\"font-size: 150%;\" ";
      if ($count) {
        $HTML .= "disabled=\"disabled\"";
      }
      $HTML .= "/><br /><br />";
    }
  }

  $tpl->set('actions', $HTML);

  fetch();
?>
