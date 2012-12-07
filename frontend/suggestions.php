<?php
  include_once("includes/start.php");
  $title = 'Suggestion Box';
  $tpl->set('title', $title);

  # If an idea has been submitted...
  if (count($_POST)) {
    $idea = userInput($_POST['idea']);
    if (trim($idea) == '') {
      # Reject empty suggestions
      $messages->addMessage(new Message("error", "You cannot submit a blank feature request."));
    } else {
      # Insert the suggestion into the database
      if (num_rows(do_query("SELECT `Idea` FROM `suggestions` WHERE `Idea` = '$idea'"))) {
        $messages->addMessage(new Message("error",
          "That suggestion has already been suggested (did you accidentally refresh the page?)."));
      } else {
        if (isset($_POST['bug'])) {
          $bug = "1";
        } else {
          $bug = "0";
        }
        do_query("INSERT INTO `suggestions` VALUES (0, '$idea', '$username', '{$_POST['category']}', 1, $bug)");
        action('submit', $_POST['category'], mysql_insert_id());
        storeMessage('success', "Your suggestion has been successfully submitted.");
        refresh();
      }
    }
  }

  function validSuggestion($id, $status) {
    if (!is_numeric($id)) {
      return false;
    }
    $query = "SELECT * FROM `suggestions` WHERE `ID` = '$id' AND `Status` = $status";
    if (!num_rows(do_query($query))) {
      return false;
    }
    return true;
  }

  # Process the requested deletion of a suggestion
  if ($SEGMENTS[1] == "delete") {
    $toDelete = $SEGMENTS[2];
    # Check if the suggestion actually exists
    if (!validSuggestion($toDelete, 1)) {
      $messages->addMessage(new Message("error", "That is not a valid suggestion."));
    } else {
      # Check if the user is allowed to delete it
      $row = fetch_row($result);
      if ($row['Submitter'] != $username and !$leader) {
        $messages->addMessage(new Message("error",
          "You are not allowed to delete that suggestion!"));
      } else {
        # Everything's okay, delete it.
        do_query("UPDATE `suggestions` SET `Status` = -1 WHERE `ID` = '$toDelete'");
        if ($row['Submitter'] == $username) {
          action("self-delete", $toDelete);
        } else {
          action("force-delete", $toDelete, $row['Submitter']);
        }
        $messages->addMessage(new Message("success",
          "You have successfully deleted a suggestion."));
      }
    }
  }

  # Restore a deleted suggestion
  if ($SEGMENTS[1] == "restore" && $leader) {
    $toRestore = $SEGMENTS[2];
    if (!validSuggestion($toRestore, -1)) {
      $messages->addMessage(new Message("error", "That is not a valid suggestion."));
    } else {
      do_query("UPDATE `suggestions` SET `Status` = 1 WHERE `ID` = '$toRestore'");
      action("restore", $toRestore);
      $messages->addMessage(new Message("success", "You have successfully restored a suggestion."));
    }
  }

  $query = "SELECT * FROM `suggestions_categories` ORDER BY `Order` ASC";
  $result = do_query($query);

  $categories = array();
  while ($row = fetch_row($result)) {
    $categories[$row['ID']] = array("name" => $row["Name"], "description" => $row["Description"]);
  }

  $tabs = array();
  $categoriesFormatted = array();

  $first = true;

  # Get a list of ideas
  foreach ($categories as $id => $info) {
    if ($first) {
      $class = "active";
      $tabFirst = 'style="margin-left: 20px;" class="active"';
      $first = false;
    } else {
      $class = "";
      $tabFirst = "";
    }

    $tabs[] = array("id" => $id, "name" => $info['name'], "first" => $tabFirst);

    $delete = false;
    if ($leader && isset($_GET['debug'])) {
      $extra = "";
    } else {
      $extra = "AND `Status` = 1";
    }
    $result = do_query("SELECT * FROM `suggestions` WHERE `Category` = '$id' $extra ORDER BY `ID` DESC");
    $ideas = array();

    while ($row = fetch_row($result)) {
      $idea = $row['Idea'];
      $style = false;

      # Check if the submitter is a user (it could indicate an error if they aren't)
      if (!isset($people[$row['Submitter']])) {
        $submitter = str_replace("-","&nbsp;",$row['Submitter']);
        $submitter = "<em>Unknown&nbsp;Account&nbsp;($submitter)</em>";
      } else {
        $submitter = userpage($row['Submitter'], true);
      }

      # Show the delete link if allowed to delete it
      if ($row['Submitter'] == $username or $leader) {
        $delete = true;
        $deleteLink = "<td><a href='/suggestions/delete/{$row['ID']}' style='color: maroon;'>Delete</a></td>";
      } else {
        $deleteLink = "<td>&nbsp;</td>";
      }

      if ($row['Status'] == -1) {
        $style = 'background-color: #FFB7B7;';
        $deleteLink = "<td><a href='/suggestions/restore/{$row['ID']}'>Restore</a></td>";
      } else if ($row['Bug'] == 1) {
        if ($leader) {
          $style = 'background-color: #BBBBBB;';
        } else {
          $style = 'display: none;';
        }
      }

      $ideas[] = array("idea" => $idea, "submitter" => $submitter, "delete" => $deleteLink, "style" => $style);
    }

    # If there are no eligible suggestions to delete, go back and remove the column
    if (!$delete) {
      foreach ($ideas as &$data) {
        $data['delete'] = "";
      }
    }

    $bugBox = false;
    if ($leader && $id == "trosnoth") {
      $bugBox = true;
    }

    $categoriesFormatted[] = array(
        "id" => $id,
        "name" => $info['name'],
        "description" => $info['description'],
        "delete" => $delete,
        "deleteH" => $delete,
        "ideas" => $ideas,
        "bugBox" => $bugBox,
        "class" => $class
    );
  }

  $debug = false;
  if (isset($_GET['debug'])) {
    $debug = true;
  }

  $tpl->set('tabs', $tabs);
  $tpl->set('debug', $debug, true);
  $tpl->set('categories', $categoriesFormatted, true);

  fetch();
?>
