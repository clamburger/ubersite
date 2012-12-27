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
  
  function downloadAll($path, $zipname) {
	$zip = new ZipArchive;
	if ( ($dir = opendir($path)) !== false ) {
		$zip->open($zipname, ZipArchive::CREATE);

         while ( ($file = readdir($dir)) !== false ) {
             if ($file != '.' && $file != '..') {
             	$zip->addFile($file);
             }
         }
     } else {
         return false;
     }
	 $zip->close();
	 return true;
  }
  
  if (isset($_POST['downloadZip'])) {
    $zipname = "submissions.zip";
    if (downloadAll("camp-data/uploads/codechallenge", $zipname)) {
      header('Content-Type: application/zip');
      header('Content-disposition: attachment; filename=' . $zipname);
      header('Content-Length: ' . filesize($zipname));
      readfile($zipname);
    }
  }

  $tpl->set('previous', false, true);

  fetch();
?>
