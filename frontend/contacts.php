<?php
  include_once("./includes/start.php");
  $title = 'Contact List';
  $tpl->set('title', $title);

  $query = "SELECT `people`.`Name` AS `Name`, `Email`, `MSN`, `Google`, `Phone`, `Mobile`, `Facebook` FROM `contacts`, `people` WHERE `contacts`.`UserID` = `people`.`UserID` ORDER BY `people`.`Name`";
  $res = do_query($query);
  $output = array();

  while($row = fetch_row($res)) {
    $output[] = $row;
  }

  $tpl->set('output', $output);
  fetch();
?>
