<?php
  include_once("includes/start.php");
  $title = 'Code Challenge Submission System';
  $tpl->set('title', $title);
  $tpl->set('contenttitle',
            $title . "<sup style='color: green;'>Beta</sup>");
  $tpl->set('js', 'uploader.js');

  $tpl->set('previous', false, true);

  fetch();
?>
