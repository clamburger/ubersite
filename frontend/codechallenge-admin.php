<?php
  include_once("includes/start.php");
  $title = 'Code Challenge Admin';
  $tpl->set('title', $title);
  $tpl->set('contenttitle',
            $title . "<sup style='color: green;'>Beta</sup>");
  $tpl->set('js', 'uploader.js');
  
  if (isset($_POST['makeVisible'])) {
    $makeVisibleQuery = "UPDATE codechallenge_tests SET Visible = 1";
    do_query($makeVisibleQuery);
    $messages->addMessage(new Message("success", "All test cases have been made visible."));

  }

  $tpl->set('previous', false, true);

  fetch();
?>
