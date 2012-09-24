<?php
  error_reporting(E_ALL);
  require_once("../camp-data/config/database.php");
  require_once("database.inc.php");

  $mysql = new mysqli($MYSQL_HOST, $MYSQL_USER, $MYSQL_PASSWORD,
                      $MYSQL_DATABASE);
  if (!isset($CAMP_DB_VERSION) || !$CAMP_DB_VERSION) {
    $CAMP_DB_VERSION = 0;
  }

  $i = 0;
  for ($i = $CAMP_DB_VERSION; $i < count($SCHEMAS); ++$i) {
    $mysql->multi_query($SCHEMAS[$i]);
    while ($mysql->next_result());
  }
  $new_version = $i;

  file_put_contents("../camp-data/config/database.php",
  '<?php
  $MYSQL_USER = "'.$MYSQL_USER.'";
  $MYSQL_PASSWORD = "'.$MYSQL_PASSWORD.'";
  $MYSQL_HOST = "'.$MYSQL_HOST.'";
  $MYSQL_DATABASE = "'.$MYSQL_DATABASE.'";
  $CAMP_DB_VERSION = "'.$new_version.'";
  ?>');

  echo "Updated from $CAMP_DB_VERSION to $new_version.";
?>
