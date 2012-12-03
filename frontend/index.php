<?php
  include_once("includes/start.php");
  $title = 'Home';
  $tpl->set('title', $title);
  $tpl->set('contenttitle', 'Welcome ' . $people[$username]);

  # Get the list of announcements
  $query = "SELECT `DT`, `Title`, `Announcement` FROM `announcements` ORDER BY `DT` DESC";
  $res = do_query($query);
  $announcements = array();

  while ($line = fetch_row($res)){
    # Don't show more than 10 announcements
    if (count($announcements) >= 10) {
      break;
    }
    #$line['DateStr'] = date("l, F jS \a\\t H:i", $line['DT']);
    $line['DateStr'] = howlong($line['DT']);
    $announcements[] = $line;
  }

  # Push the announcements to the template
  $tpl->set('announcements', $announcements);
  $tpl->set('alert', $alert, true);

  fetch();
?>
