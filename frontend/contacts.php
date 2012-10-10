<?php
  include_once("../includes/start.php");
  $title = 'Contact List';
  $tpl->set('title', $title);

  $query = "SELECT `people`.`Name` AS `Name`, `Email`, `MSN`, `Google`,\n" .
           "  `Phone`, `Mobile`, `Facebook`\n" .
           "FROM `contacts`, `people`\n" .
           "WHERE `contacts`.`UserID` = `people`.`UserID`\n" .
           "ORDER BY `people`.`Name`";
  $res = do_query($query);
  $output = array();

  while($row = fetch_row($res)) {
    $output[] = $row;
  }

  $tpl->set('output', $output);
  fetch();
?>
