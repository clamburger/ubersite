<?php
  include_once("includes/start.php");
  include_once("includes/codechallenge.php");
  $title = 'Code Challenge Results';
  $tpl->set('title', $title);
  
  // mark results with score equal to the max number of tests as valid
  // and invalidate those results where they haven't achieved unit test coverage
  $query = "(SELECT people.Name, Score, TimeMSAverage, TimeMS1, TimeMS2, TimeMS3, 1 AS Valid " .
  		   "FROM people, codechallenge_results " .
  		   "WHERE people.UserID = codechallenge_results.UserID AND Score = $cc_tests_number " .
 		   "ORDER BY Score DESC, TimeMSAverage ASC) " .
 		   "UNION " .
 		   "(SELECT people.Name, Score, TimeMSAverage, TimeMS1, TimeMS2, TimeMS3, 0 AS Valid " .
  		   "FROM people, codechallenge_results " .
  		   "WHERE people.UserID = codechallenge_results.UserID AND Score <> $cc_tests_number " .
 		   "ORDER BY Score DESC, TimeMSAverage ASC);";
  $res = do_query($query);
  $output = array();

  while($row = fetch_row($res)) {
    $output[] = $row;
  }

  $tpl->set('output', $output);
  fetch();
?>
