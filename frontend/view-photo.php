<?php
include_once("includes/start.php");
$title = 'Camp Photos';
$tpl->set('title', $title);

$image = $SEGMENTS[1];

# Check if the image name is valid
if (!$image || !file_exists("camp-data/photos/$image")) {
  header("HTTP/1.1 404 Not Found");
  die("Oh no, you broke something! 404 Image Not Found");
}

# Generate the thumbnail for the image
if (!$small) {
  $filename = generate_thumbnail("camp-data/photos/$image", 750, 600);
} else {
  $filename = generate_thumbnail("camp-data/photos/$image", 500, 600);
}
$tpl->set('imageURL', "/camp-data/photos/cache/$filename");

# Approve the selected caption (if it exists)
if ($leader && $SEGMENTS[2] == "approve") {
  $toApprove = $SEGMENTS[3];
  if (!is_numeric($toApprove) || !num_rows(do_query("SELECT `ID` FROM `photo_captions` WHERE `ID` = '$toApprove' AND `Status` = 0"))) {
    $messages->addMessage(new Message("error", "That is not a valid caption ID."));
  } else {
    do_query("UPDATE `photo_captions` SET `Status` = 1 WHERE `ID` = '$toApprove'");
    action("approve", $image, $toApprove);
    $msg = "You have successfully approved a caption.";
  }
}

# Decline the selected caption (if it exists)
if ($leader && $SEGMENTS[2] == "decline") {
  $toDecline = $SEGMENTS[3];
  if (!is_numeric($toDecline) || !num_rows(do_query("SELECT `ID` FROM `photo_captions` WHERE `ID` = '$toDecline' AND `Status` = 0"))) {
    $messages->addMessage(new Message("error", "That is not a valid caption ID."));
  } else {
    do_query("UPDATE `photo_captions` SET `Status` = -1 WHERE `ID` = '$toDecline'");
    action("decline", $image, $toDecline);
    $msg = "You have successfully declined a caption.";
  }
}

if (isset($msg)) {
  $count = checkCaptions();
  if ($count > 0) {
    $msg .= " (<a href='/photos/admin'>$count left to check</a>)";
  }
  $messages->addMessage(new Message("success", $msg));
}

# Submit a caption.
if (isset($_POST['caption'])) {
  $caption = userInput($_POST['caption']);
  if ($caption == "" || $caption == "Add a caption to this photo.") {
    $messages->addMessage(new Message("error", "You must enter a caption before submitting."));
  } else {
    # Check if the caption already exists.
    $caption = userInput($_POST['caption']);
    $result = do_query("SELECT `Status` FROM `photo_captions`\n" .
      "WHERE `Filename` = '$image' AND\n" .
      "  `Caption` = '$caption'");
    if (num_rows($result)) {
      $row = fetch_row($result);
      if ($row['Status'] == 0) {
        $messages->addMessage(new Message("error",
          "That caption has already been submitted (it's currently waiting to be approved)."));
      } else if ($row['Status'] == -1) {
        $messages->addMessage(new Message("error",
          "That caption was previously submitted and declined."));
      } else {
        $messages->addMessage(new Message("error",
          "That caption is the same as an existing caption."));
      }
    } else {

      # Leader captions are automatically approved
      if ($leader) {
        $initialStatus = 1;
      } else {
        $initialStatus = 0;
      }

      $query = "INSERT INTO `photo_captions` (`Filename`, `Caption`, `Author`, `Status`) ";
      $query .= "VALUES ('$image', '$caption', '$username', $initialStatus)";
      do_query($query);
      action("submit", $image, mysql_insert_id());
      if ($leader) {
        storeMessage('success', "Caption successfully submitted. Since you are a leader " .
          "it has been automatically approved and will be visible to campers immediately.");
      } else {
        storeMessage('success', "Caption successfully submitted. It will appear on the website once it has been approved.");
      }
      refresh();
    }
  }
}

//$special = array("fenix" => "Fenix", "trex" => "T-Rex", "moose" => "Moose");
$special = array("fenix" => "Fenix", "moose" => "Tony Moose");
$completeList = array_merge($people, $special);
asort($completeList);

# Somebody was tagged in the photo
if (isset($_POST['newTag'])) {

  if (isset($completeList[$_POST['newTag']]) || ($leader && $_POST['newTag'] == "nobody")) {

    $query = "SELECT * FROM `photo_tags` WHERE `Filename` = '$image' AND `Username` = '{$_POST['newTag']}'";
    $result = do_query($query);
    if (num_rows($result)) {
      if ($_POST['newTag'] != "nobody") {
        $messages->addMessage(new Message("warning",
          "{$completeList[$_POST['newTag']]} has already been tagged in this photo."));
      }
    } else {
      $query = "INSERT INTO `photo_tags` (`Filename`, `Username`) VALUES ('$image', '{$_POST['newTag']}')";
      do_query($query);
      if ($_POST['newTag'] != "nobody") {
        action("tag", $image, $_POST['newTag']);
        $messages->addMessage(new Message("success",
          "You have successfully tagged {$completeList[$_POST['newTag']]} in this photo."));
      } else {
        $messages->addMessage(new Message("success",
          "You have marked this photo as not having anybody in it."));
      }
    }
  }
}

# Somebody was untagged from the photo
if ($SEGMENTS[2] == "untag" && $leader) {

  $toUntag = $SEGMENTS[3];

  if (isset($completeList[$toUntag]) || $toUntag == "nobody") {

    $query = "SELECT * FROM `photo_tags` WHERE `Filename` = '$image' AND `Username` = '$toUntag'";
    $result = do_query($query);
    if (!num_rows($result)) {
      if ($toUntag != "nobody") {
        $messages->addMessage(new Message("warning",
          "{$completeList[$toUntag]} was not tagged in this photo."));
      }
    } else {
      $query = "DELETE FROM `photo_tags` WHERE `Filename` = '$image' AND `Username` = '$toUntag'";
      do_query($query);
      if ($toUntag == "nobody") {
        $messages->addMessage(new Message("success",
          "You have marked this image as having one or more people in it."));
      } else {
        action("untag", $image, $toUntag);
        $messages->addMessage(new Message("success",
          "You have successfully untagged {$completeList[$toUntag]} in this photo."));
      }
    }
  }
}

$allPhotos = array();
if ($dh = opendir("camp-data/photos")) {
  while (($file = readdir($dh)) !== false) {
    if (stristr($file, "png") || stristr($file, "gif") || stristr($file, "jpg")) {
      $allPhotos[] = $file;
    }
  }
}

$curKey = array_search($image, $allPhotos);
if ($curKey == 0) {
  $prevKey = count($allPhotos) - 1;
} else {
  $prevKey = $curKey - 1;
}

if ($curKey == count($allPhotos) - 1) {
  $nextKey = 0;
} else {
  $nextKey = $curKey + 1;
}

$tpl->set('prevImage', $allPhotos[$prevKey]);
$tpl->set('nextImage', $allPhotos[$nextKey]);

$file = userInput($image);
$caption = "";

# Find out who is tagged in this photo
$query = "SELECT `Username` FROM `photo_tags` WHERE `Filename` = '$file' ORDER BY `Username` ASC";
$result = do_query($query);

$tags = $tagList = $untags = array();
$nobody = false;
while ($row = fetch_row($result)) {
  if ($row['Username'] == "nobody") {
    $nobody = true;
    break;
  } else {
    if (isset($special[$row['Username']])) {
      $tags[] = "<strong>{$special[$row['Username']]}</strong>";
    } else {
      $tags[] = userpage($row['Username']);
    }
    $untags[] = "<a href='/view-photo/$image/untag/{$row['Username']}' style='color: red;'>" .
      str_replace(" ", "&nbsp;", $completeList[$row['Username']]) . "</a>";
    $tagList[] = $row['Username'];
  }
}

$tags = implode(", ", $tags);
$untags = implode(", ", $untags);

if (empty($tags)) {
  $tags = false;
}

$tpl->set('tags', $tags, true);
$tpl->set('untags', $untags, true);
$tpl->set('nobody', $nobody, true);

# Generate the dropdown list for new tag possibilities
$dropdown = "<option>---</option>\n";
foreach ($completeList as $id => $name) {
  if (array_search($id, $tagList) === false) {
    $dropdown .= "<option value='$id'>$name</option>\n";
  } else {
    $dropdown .= "<option value='$id' disabled>$name</option>\n";
  }
}

if ($leader) {
  $dropdown .= "<option value='' disabled>---</option>\n";
  $dropdown .= "<option value='nobody'>Nobody is in this photo</option>\n";
}

$tpl->set('dropdown', $dropdown);

# Get a list of captions for the file
$query = "SELECT * FROM `photo_captions` WHERE `Filename` = '$file'";
if (!$leader) {
  $query .= " AND `Status` = 1";
} else {
  $query .= " AND `Status` != -1";
}
$query .= " ORDER BY `ID`";

$res = do_query($query);
$caption = "";

# Format the captions for the template.
while ($row = fetch_row($res)) {
  $caption .= $row["Caption"] . " - " . userpage($row['Author'], true);
  if ($row['Status'] == 0) {
    $caption .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    $caption .= "<a href='/view-photo/$file/approve/${row["ID"]}'>Approve</a> | ";
    $caption .= "<a href='/view-photo/$file/decline/${row["ID"]}'>Decline</a>";
  }
  $caption .= "<br />";
}

if (empty($caption)) {
  if ($wget) {
    $caption = "<em>There are no captions for this photo.</em><br />";
  } else {
    $caption = "<em>There are currently no captions for this photo! Why not add one?";
    $caption .= "<br />(Note: unapproved captions will not show up until approved)</em><br />";
  }
}

if (isset($_GET['tag']) && !$nobody) {
  $tpl->set('tagTextStyle', 'style="display: none;"');
  $tpl->set('tagInputStyle', '');
} else {
  $tpl->set('tagInputStyle', 'style="display: none;"');
  $tpl->set('tagTextStyle', '');
}

$tpl->set('filename', $image);
$tpl->set('caption', $caption);
fetch();
?>
