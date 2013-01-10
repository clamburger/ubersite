<?php
  include_once("includes/start.php");
  $title = 'Code Challenge';
  $tpl->set('title', $title);
  
  $sqlResult = do_query("SELECT Title, Content FROM codechallenge_content LIMIT 1");
  $row = fetch_row($sqlResult);
  
  $testCases = array();
  
  if (isset($row) && strlen($row['Title']) > 0) {
    $challengeTitle = $row['Title'];
    $challengeContent = $row['Content'];
    $testSqlResult = do_query("SELECT Params, Result FROM codechallenge_tests WHERE Visible = 1");
    while($testRow = fetch_row($testSqlResult)) {
      $testCases[] = $testRow;
    }

  } else {
    $challengeTitle = $challengeContent = "Not Yet Available";
  }
  
  $tpl->set('challengeTitle', $challengeTitle);
  $tpl->set('challengeContent', $challengeContent);
  $tpl->set('testCases', $testCases);
  
  fetch();
?>
