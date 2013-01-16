<?php
  include_once("includes/start.php");
  $DISABLE_UBER_BUTTON = true;
  $title = 'Choose Questionnaire';
  $tpl->set('title', $title);
  $src = null;
  if (isset($_GET['src'])) {
    $src = $_GET['src'];
  } else if (isset($_SERVER["HTTP_REFERER"])) {
    $src = $_SERVER["HTTP_REFERER"];
  } else {
    $src = "/questionnaire";
  }
  $tpl->set('src', $src);
  // Get questionnaires.
  $query = "SELECT Id, Name FROM questionnaires";
  $res = do_query($query);
  $questionnaires = array();
  while ($row = fetch_row($res)) {
    $questionnaires[] = $row;
  }
  if (count($questionnaires) === 1) {
    header("Location: $src/{$questionnaires[0]['Id']}");
  }
  $tpl->set('questionnaires', $questionnaires);
  fetch();
?>
