<?php
  include_once("includes/start.php");
  $title = 'Camp Photos';
  $tpl->set('title', $title);

  $query = "SELECT * FROM `photo_captions` WHERE `Status` = 1 ORDER BY `Filename` ASC, `ID` ASC";
  $result = do_query($query);
  $captions = array();
  while($row = fetch_row($result)) {
    if (!isset($captionCount[$row['Filename']])) {
      $captionCount[$row['Filename']] = array();
    }
    $captions[$row['Filename']][] = array("Caption" => $row['Caption'], "Author" => $row['Author']);
  }

  $photos = array();
  foreach ($captions as $filename => $data) {
    $code = "<td>";
    $thumb = str_replace(".", "-(200x133).", $filename);
    $code .= "<a href='view-photo.php?image=$filename' style='border: none;'>";
    $code .= "<img src='camp-data/photos/cache/$thumb' style='border-style: solid; border-color: #2A2AA5; border-width: 1px;' />";
    $code .= "</a></td>";
    $code .= "<td><ul style='text-align: left;'>";
    $code .= "<li><a href='view-photo.php?image=$filename'>$filename</a></li>";
    foreach ($data as $indcaptions) {
      $code .= "<li>{$indcaptions['Caption']} (".userpage($indcaptions['Author'], true).")</li>";
    }
    $code .= "</ul></td>";
    $photos[] = array("row" => $code);
  }
  $tpl->set('captions', $photos);

  fetch();
?>
