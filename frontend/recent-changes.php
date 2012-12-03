<?php
  include_once("includes/start.php");
  $title = 'Recent Changes';
  $tpl->set('title', $title);

  $actions = array();

  $verbs = array(
      "change-password" =>  array(
          "reset" => "reset [[%1]]'s password",
          "change" => "changed their password"),
      "polls" => array(
          "voted" => "voted on the poll \"[[%1]]\"",
          "reset" => "reset their vote on the poll \"[[%1]]\"",
          "new" => "created a new poll: \"[[%1]]\"",
          "approve" => "approved a poll: \"[[%1]]\"",
          "decline" => "declined a poll %ID1",
          "delete" => ""), // The one in $failedLookup is used instead
      "questionnaire" =>   array(
          "submitted" => "completed the [[questionnaire]]"),
      "suggestions" => array(
          "submit" => "added a [[%1]] %ID2",
          "self-delete" => "deleted one of their own [[suggestions]] %ID1",
          "force-delete" => "deleted somebody else's [[suggestion]] %ID1",
          "restore" => "restored a [[suggestion]] %ID1"),
      "view-photo" => array(
          "submit" => "submitted a photo caption for [[%1]] %ID2",
          "approve" => "approved a photo caption for [[%1]] %ID2",
          "decline" => "declined a photo caption for [[%1]] %ID2",
          "tag" => "tagged %2 in [[%1]]", "untag" => "untagged %2 from [[%1]]"),
      "quotes" => array(
          "submit" => "submitted a [[quote]] %ID1",
          "approve" => "approved a [[quote]] %ID1",
          "decline" => "declined a [[quote]] %ID1",
          "delete" => "deleted a [[quote]] %ID1",
          "revert" => "reverted a [[quote]] %ID1"),
      "person" => array(
          "profile" => "updated their profile",
          "contact" => "updated their contact details"),
      "accounts" => array(
          "new" => "created a new account for [[%1]]",
          "edit" => "modified [[%1]]'s account",
          "delete" => "deleted an account %ID1",
          "import" => "imported %1 new accounts"),
      "awards" => array(
          "nominate" => "nominated somebody for [[%1]]",
          "denominate" => "removed a nomination for [[%1]]"),
      "pegosaurus" => array(
          "new" => "added a new [[Pegosaurus]] record: %1!"),
  );

  $failedLookup = array(
      "changepassword" => array(
          "reset" => "reset a user's password %ID1"),
      "polls" => array(
          "voted" => "voted on a poll",
          "reset" => "reset their vote on a poll",
          "new" => "created a new poll",
          "approve" => "approved a poll %ID1",
          "decline" => "declined a poll %ID1",
          "delete" => "deleted a poll %ID1"),
      "accounts" => array(
          "new" => "created a new account %ID1",
          "edit" => "modified an account %ID1"),
      "view-photo" => array(
          "tag" => "tagged somebody in [[%1]] %ID2",
          "untag" => "untagged somebody from [[%1]] %ID2"),
  );

  $leaderOnly = array(
      "changepassword+reset", "suggestions+force-delete", "quotes+delete",
      "quotes+revert", "accounts+edit", "accounts+delete",
      "suggestions+restore", "accounts+import", "view-photo+untag",
      "polls+delete", "awards+nominate", "awards+denominate");

  // Get the list of polls and store them
  $polls = array();
  $result = do_query("SELECT * FROM `poll_questions`");
  while ($row = fetch_row($result)) {
    $polls[$row['ID']] = $row['Question'];
  }

  // Get the list of award categories and store them
  $awards = array();
  $result = do_query("SELECT * FROM `award_categories`");
  while ($row = fetch_row($result)) {
    $awards[$row['ID']] = $row['Category'];
  }

  // Grab the last 50 actions
  if (isset($_GET['all'])) {
    $limit = "";
  } else {
    $limit = "LIMIT 100";
  }

  $result = do_query("SELECT * FROM `actions` ORDER BY `ID` DESC $limit");
  if (!num_rows($result)) {
    $actions[] = array("action" => "A tumbleweed rolls past...",
                       "fade" => false);
  } else {
    while ($row = fetch_row($result)) {
      $str = howlong($row['Timestamp']);
      $str .= ": <a href='person.php?id={$row['UserID']}'>{$people[$row['UserID']]}</a> ";
      $verb = $verbs[$row['Page']][$row['Action']];

      $fade = false;
      if (array_search($row['Page']."+".$row['Action'], $leaderOnly) !== false) {
        if (!$leader) {
          continue;
        } else {
          $fade = true;
        }
      }

      $url = $row['Page'] . ".php";
      $ID1 = $row['SpecificID1'] or $ID1 = "";
      $ID2 = $row['SpecificID2'] or $ID2 = "";

      # Page-specific formatting
      if ($row['Page'] == "changepassword" && $row['Action'] == "reset") {
        if (isset($people[$ID1])) {
          $url = "person.php?id=$ID1";
          $ID1 = $people[$ID1];
        } else {
          $verb = $failedLookup[$row['Page']][$row['Action']];
        }
      } else if ($row['Page'] == "view-photo") {
        $url .= "?image=$ID1";
        if ($row['Action'] == "tag" || $row['Action'] == "untag") {
          if ($ID2 == "trex") {
            $ID2 = "T-Rex";
          } else if ($ID2 == "fenix") {
            $ID2 = "Fenix";
          } else if ($ID2 == "moose") {
            $ID2 = "Moose";
          } else if (isset($people[$ID2])) {
            $ID2 = userpage($ID2);
          } else {
            $verb = $failedLookup[$row['Page']][$row['Action']];
          }
        }
      } else if ($row['Page'] == "suggestions" && $row['Action'] == "submit") {
        $suggestions = array("trosnoth" => "general Trosnoth suggestion",
                             "achievements" => "Trosnoth achievement idea",
                             "website" => "website suggestion");
        $ID1 = $suggestions[$ID1];
      } else if ($row['Page'] == "polls") {
        $url .= "?id=$ID1";
        # Poll not found - must have been deleted
        if (!isset($polls[$ID1])) {
          $verb = $failedLookup[$row['Page']][$row['Action']];
        } else {
          $ID1 = $polls[$ID1];
        }
      } else if ($row['Page'] == "accounts" && ($row['Action'] == "new" || $row['Action'] == "edit")) {
        if (isset($people[$ID1])) {
          $url = "person.php?id=$ID1";
          $ID1 = $people[$ID1];
        } else {
          $verb = $failedLookup[$row['Page']][$row['Action']];
        }
      } else if ($row['Page'] == "pegosaurus") {
        $ID1 = userpage($ID1);
        $ID2 = userpage($ID2);
      } else if ($row['Page'] == "awards") {
        $ID1 = $awards[$ID1];
      }

      # Parse the link
      $verb = str_replace("[[", "<a href='$url'>", $verb);
      $verb = str_replace("]]", "</a>", $verb);

      # Parse variables
      $verb = str_replace("%1", $ID1, $verb);
      $verb = str_replace("%2", $ID2, $verb);

      # Parse the ID
      $verb = str_replace("%ID1", "<small>(ID: $ID1)</small>", $verb);
      $verb = str_replace("%ID2", "<small>(ID: $ID2)</small>", $verb);
      $str .= $verb;
      $actions[] = array("action" => $str, "fade" => $fade);
    }
  }
  $tpl->set('actions', $actions, true);

  fetch();
?>
