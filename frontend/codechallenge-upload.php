<?php
  include_once("includes/start.php");
  $title = 'Code Challenge Submission System';
  $tpl->set('title', $title);
  $tpl->set('contenttitle',
            $title . "<sup style='color: green;'>Beta</sup>");
  $tpl->set('js', 'uploader.js');

  $tpl->set('previous', false, true);

/*
  if ($SEGMENTS[1] == 'previous') {
    $previous = array();

    $query = "SELECT `Filename`, `DateUploaded` FROM `photo_processing` ";
    $query .= "WHERE `Uploader` = '$username' ORDER BY `DateUploaded` ASC";
    $result = do_query($query);

    while ($row = fetch_row($result)) {
      $previous[] = array("filename" => $row['Filename'], "date" => howLong($row['DateUploaded']));
    }

    $tpl->set('oldFiles', $previous, true);
    $tpl->set('previous', true, true);
  } else {
    $messages->addMessage(new Message("warning",
      "You should upload all the photos you have, even the ones that aren't any good."));
  }
*/

  fetch();
?>
