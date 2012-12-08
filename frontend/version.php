<?php
  include_once("includes/start.php");
  $title = Software::getFullName();
  $tpl->set('title', 'Software Information');
  $tpl->set('shortTitle', 'ÜberSite Info');

  $host = $MYSQL_HOST;
  if (is_numeric($host[0])) {
    $hostAlt = gethostbyaddr($host);
  } else {
    $hostAlt = gethostbyname($host);
  }

  if ($hostAlt != $host) {
    $host .= " (resolves to $hostAlt)";
  }

  $tpl->set('dbHost', $host);
  $tpl->set('dbUser', $MYSQL_USER);
  $tpl->set('dbPass', $MYSQL_PASSWORD);
  $tpl->set('dbName', $MYSQL_DATABASE);

  fetch();
?>
