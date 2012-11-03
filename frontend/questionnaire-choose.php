<?php
  include_once("../includes/start.php");
  $title = 'Choose Questionnaire';
  $tpl->set('title', $title);
  $src = null;
  if (isset($_GET['src'])) {
    $tpl->set('src', $_GET['src']);
  } else if (isset($_SERVER["HTTP_REFERER"])) {
    $tpl->set('src', $_SERVER["HTTP_REFERER"]);
  } else {
    $tpl->set('src', "/questionnaire.php");
  }
  // Get questionnaires.
  $query = "SELECT Id, Name FROM questionnaires";
  $res = do_query($query);
  $questionnaires = array();
  while ($row = fetch_row($res)) {
    $questionnaires[] = $row;
  }
  $tpl->set('questionnaires', $questionnaires);
  fetch();
?>
