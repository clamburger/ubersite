<?php
  include_once("../includes/start.php");
  $title = $SOFTWARE_NAME . " " . $SOFTWARE_VERSION;
  $tpl->set('title', 'Software Information');
  $tpl->set('shortTitle', 'ÜberSite Info');

  $tpl->set('software', $SOFTWARE_NAME);

  if (!isset($SOFTWARE_CODENAME)) {
    $CODENAME_DESCRIPTION = "";
  }
  $tpl->set('codename-desc', $CODENAME_DESCRIPTION);

  $html = "";
  if (!isset($CHANGELOG) || count($CHANGELOG) === 0) {
    $html .= "No changes have been recorded. Check back later in the week, since there's sure to be improvements by then!";
  } else {
    foreach ($CHANGELOG as $SOFTWARE_VERSION => $changes) {
      $html .= "<h3>$SOFTWARE_VERSION:</h3>\n";
      $html .= "<ul>\n";

      foreach ($changes as $change) {
        $html .= "<li>$change</li>\n";
      }
      $html .= "</ul>\n";
    }
  }

  $tpl->set('changes', $html, true);
  $host = $MYSQL_HOST;
  if (is_numeric($host[0])) {
    $hostAlt = gethostbyaddr($host);
  } else {
    $hostAlt = gethostbyname($host);
  }

  if ($hostAlt != $host) {
    $host .= " (resolves to $hostAlt)";
  }

  $tpl->set('db-host', $host);
  $tpl->set('db-user', $MYSQL_USER);
  $tpl->set('db-pass', $MYSQL_PASSWORD);
  $tpl->set('db-name', $MYSQL_DATABASE);

  fetch();
?>
