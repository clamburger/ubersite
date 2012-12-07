<?php
  include_once("includes/start.php");

  # Make sure that an ID is given
  if(!$SEGMENTS[1]) {
    header("Location: /profiles");
  }

  # Update your own details if you submitted them
  $editMode = false;
  $contactMode = false;
  $tpl->set('allowedToEdit', false, true);
  if ($SEGMENTS[1] == $username) {

    if (isset($_POST['nickname'])) {
      $nickname = userInput($_POST['nickname']);
      $facts = userInput(str_replace($LINEBREAKS, "\n", $_POST['facts']));
      if (strpos($facts, "Use this box to provide brief information") !== FALSE) {
        $facts = "";
      }
      $about = userInput($_POST['about']);
      $query = "UPDATE `people` SET `Nickname` = '$nickname', `About` = '$about', ";
      $query .= "`Facts` = '$facts', `InfoFilled` = 1 WHERE `UserID` = '$username'";
      do_query($query);
      action("profile");
      $tpl->set('success', "Your profile has been updated.", true);
    }
    if ($SEGMENTS[2] == 'edit') {
      $editMode = true;
    } else if ($SEGMENTS[2] == 'contact') {
      $contactMode = true;
    } else {
      $tpl->set('allowedToEdit', true, true);
    }

  }

  $tpl->set('editMode', $editMode, true);
  $tpl->set('contactMode', $contactMode, true);

  if ($editMode || $contactMode) {
    $tpl->set('cancelButton', true, true);
  } else {
    $tpl->set('cancelButton', false, true);
  }

  # Make sure the ID is valid
  $query = "SELECT * FROM `people` WHERE `UserId` = '" . mysql_real_escape_string($SEGMENTS[1]) ."';";
  $result = do_query($query);
  if (!($row = fetch_row($result))) {
    header("Location: /profiles");
  }

  $ID = $SEGMENTS[1];

  # Get the duty team
  $query = "SELECT * FROM `dutyteams` WHERE `ID` = " . $row['DutyTeam'] .";";
  $result = do_query($query);
  if (!($dt = fetch_row($result))) {
    $row['DutyTeam'] = "No team";
    $row['Colour'] = "000000";
    $row['FontColour'] = "FFFFFF";
  } else {
    $row['DutyTeam'] = $dt['Name'];
    $row['Colour'] = $dt['Colour'];
    $row['FontColour'] = $dt['FontColour'];
  }

  $title = $row['Name'];
  $tpl->set('title', $title);

  //$greek = "<span style='font-family: arial;'>{$SYMBOLS[$row["StudyGroup"]]}</span> ({$row['StudyGroup']})";
  $greek = "";

  # Properly format the fact and about me sections
  if (!$editMode) {
    if (!empty($row['Facts'])) {
      $facts = explode("\n", $row['Facts']);
      foreach ($facts as $key => $info) {
        $details = explode(":", $info, 2);
        $facts[$key] = "<strong>{$details[0]}:</strong> {$details[1]}";
      }
    } else {
      $facts = "";
    }

    $about = str_replace(array("<", ">", "\n"), array("&lt;", "&gt;", "<br />"), $row['About']);

  } else {
    $about = $row['About'];
    $facts = $row['Facts'];
  }

  # Get all of the information about the person and set the various tags
  $person = array(
      'id' => $row['UserID'],
      'name' => $row['Name'],
      'category' => $row['Category'],
      'nickname' => $row['Nickname'],
      'about' => $about,
      'team' => $row['DutyTeam'],
      'colour' => $row['Colour'],
      'font' => $row['FontColour'],
      'greek' => $greek);

  if (empty($person['nickname'])) {
    $tpl->set('name', false, true);
  } else {
    $tpl->set('name', true, true);
  }

  if (empty($person['about'])) {
    $tpl->set('about', false, true);
  } else {
    $tpl->set('about', true, true);
  }

  if (empty($facts)) {
    $tpl->set('facts', false, true);
  } else {
    $tpl->set('facts', $facts, true);
  }

  # Has the person actually filled in their info?
  if ($row['InfoFilled'] === "0" && !$editMode) {
    $tpl->set('noInfo', true, true);
  } else {
    $tpl->set('noInfo', false, true);
  }

  # Are they are a camper or a leader?
  $type = $row['Category'];

  # Display the correct picture
  if (!file_exists("camp-data/profiles/$ID.jpg")) {
    $src = "/resources/img/no-pic.jpg";
  } else {
    $src = "/camp-data/profiles/$ID.jpg";
  }

  $tpl->set('picture', $src);
  $tpl->set('person', $person);

  # Check if they appear in any photos
  $query = "SELECT `Filename` FROM `photo_tags` WHERE `Username` = '$ID'";
  $result = do_query($query);
  $photos = array();

  $photoCount = 0;
  $excessPhotos = 0;

  if ($screenWidth >= 1760) {
    $photoLimit = 14;
  } else if ($screenWidth >= 1560) {
    $photoLimit = 12;
  } else if ($screenWidth >= 1360) {
    $photoLimit = 10;
  } else if ($screenWidth >= 1160) {
    $photoLimit = 8;
  } else {
    $photoLimit = 6;
  }

  if ($wget) {
    $photoLimit = 12;
  }

  while ($photoRow = fetch_row($result)) {
    $photoCount++;
    if ($photoCount > $photoLimit) {
      $excessPhotos++;
    } else {
      $thumbnail = generate_thumbnail("camp-data/photos/{$photoRow['Filename']}", 200, 133);
      $thumbWidth = getimagesize("camp-data/photos/cache/$thumbnail");
      # If the normal thumbnail is the same size as the small thumbnail it's pointless
      # to generate a new one
      if ($thumbWidth[0] > 180) {
        $thumbnail = generate_thumbnail("photos/{$photoRow['Filename']}", 180, 133);
      }
      $photos[] = array("filename" => $photoRow['Filename'], "thumbnail" => $thumbnail);
    }
  }
  $tpl->set('photos', $photos, true);
  $tpl->set('excessPhotos', $excessPhotos, true);

  # Getting such a modular layout formatted correctly is terribly annoying
  $tpl->set('factBreak', '', true);
  $tpl->set('photoBreak', '', true);

  if (!empty($person['nickname'])) {
    $tpl->set('factBreak', "<br />", true);
  }

  if (empty($person['about']) && (!empty($facts) || !empty($person['nickname'])) ) {
    $tpl->set('photoBreak', "<br />", true);
  }

  # Update the contact details if submitted
  if (isset($_POST['contactSubmitted'])){
    $cols = "(`UserID`";
    $vals = "('$username'";
    foreach ($_POST as $key => $value){
      $safeValue = userInput($value);
      if ($key != "contactSubmitted") {
        $cols .= ", `$key`";
        $vals .= ", '$safeValue'";
      }
    }
    $cols .= ")";
    $vals .= ")";
    $query = "REPLACE INTO `contacts` $cols VALUES $vals";
    do_query($query);
    action("contact");
    $tpl->set('success', "Your contact details have been updated.", true);
  }

  if ($contactMode) {
    $a = array();
    $res = do_query("SELECT * FROM `contacts` WHERE UserID = '$username'");
    if(num_rows($res)){
      $a = fetch_row($res);

      # Send the contact details to the template.
      $contact = array("email" => $a['Email'],
                "msn" => $a['MSN'],
                "google" => $a['Google'],
                "phone" => $a['Phone'],
                "mobile" => $a['Mobile'],
                "facebook" => $a['Facebook']);

    } else {
      $contact = array("email" => "", "msn" => "", "google" => "",
               "phone" => "", "mobile" => "", "facebook" => "");
    }

    $tpl->set('contact', $contact);
  } else {
    $tpl->set('contact', '');
  }

  $tpl->set('contactDetails', $CONTACT_DETAILS);
  fetch();
?>
