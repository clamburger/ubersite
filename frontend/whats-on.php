<?php
  error_reporting(E_ALL);

  include_once("libraries/bTemplate.php");
  include_once("includes/jsonLoader.php");
  include_once("includes/database.php");
  include_once("includes/functions.php");
  $tpl = new bTemplate();

  date_default_timezone_set($TIMEZONE);

  $whats_on = whats_on();
  $tpl->set('whatson', $whats_on);
  if (isset($whats_on['Activity'])) {
      $tpl->set('enabled', true, true);
  } else {
      $tpl->set('enabled', false, true);
  }

  echo $tpl->fetch('templates/whats-on.tpl');
?>
