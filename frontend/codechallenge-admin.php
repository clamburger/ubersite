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
	$zip = new ZipArchive();
	if (file_exists($zipname)) {
	  $zip->open($zipname, ZipArchive::OVERWRITE) or die("Could not create ZIP");
	} else {
	  $zip->open($zipname, ZipArchive::CREATE) or die("Could not create ZIP");
	}
	
	foreach (glob($path . "/*.py") AS $file) {
	  $zip->addFile($file, "submissions/" . basename($file) . ".py") OR DIE("Could not add file " . $file);
	}
		
	if (!$zip->status == ZIPARCHIVE::ER_OK) {
	  /*echo "Failed to write files to zip<br>";
	  echo $zip->status;*/
	  return false;
	}
		
	$zip->close();
	
	return true;

  }
  
  if (isset($_POST['downloadZip'])) {
    $zipname = "submissions.zip";
    if (downloadAll("camp-data/uploads/codechallenge", $zipname)) {
      header('Content-Type: application/zip');
      header('Content-disposition: attachment; filename=submissions.zip');
      header('Content-Length: ' . filesize($zipname));
      readfile($zipname);
    }
  }

  $tpl->set('previous', false, true);

  fetch();
?>
