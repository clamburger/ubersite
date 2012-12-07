<?php
  include_once("includes/start.php");
  $title = 'Photo Uploader';
  $tpl->set('title', $title);
  $tpl->set('contenttitle',
            $title . "<sup style='color: green;'>Beta</sup>");
  $tpl->set('js', 'uploader.js');

  $tpl->set('previous', false, true);

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
    $tpl->set('warning', "This is a friendly reminder: upload <strong>all</strong> of the photos you have (including terrible photos), or Sam will hunt you down and take them from you.<br />Alternatively, just give Sam your memory card and he will copy them over manually.");
  }

  fetch();
?>
