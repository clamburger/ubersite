<?php

require_once("includes/jsonLoader.php");
require_once("includes/database.php");
require_once("base-uploader.php");


// list of valid extensions, ex. array("jpeg", "xml", "bmp")
$allowedExtensions = array();
// max file size in bytes
$sizeLimit = 10 * 1024 * 1024;
// directory
$uploadDirectory = "camp-data/uploads/codechallenge/admin/";
$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
$result = $uploader->handleUpload($uploadDirectory);

session_start();

if (isset($result['success'])) {

  $safeFilename = userInput($result['filename']);
  
  if (strpos($safeFilename, "content") !== false) {
    $uploadType = "content";
  } else if (strpos($safeFilename, "test") !== false) {
    $uploadType = "tests";
  } else if (strpos($safeFilename, "result") !== false) {
    $uploadType = "results";
  } else {
  	$uploadType = "error";
  }
  
  
  $csvFileLength = filesize("$uploadDirectory$safeFilename");
  $handle = fopen("$uploadDirectory$safeFilename", "r");
  
  while (($csvData = fgetcsv($handle, 0, "\t")) !== false) {
    $query = "";
    
    switch($uploadType) {
      case "results":
        $query = "REPLACE INTO codechallenge_results (UserID, Score, TimeMSAverage, TimeMS1, TimeMS2, TimeMS3)";
	    $query .= " VALUES ( '$csvData[0]', $csvData[1], $csvData[2], $csvData[3], $csvData[4], $csvData[5] );";
	    break;
	  case "content":
	    $query = "REPLACE INTO codechallenge_content (Title, Content)";
	    $query .= " VALUES ( '$csvData[0]', '$csvData[1]' );";
	    break;
	  case "tests":
	    $query = "REPLACE INTO codechallenge_tests (ID, Params, Result, Visible)";
	    $query .= " VALUES ( $csvData[0], '$csvData[1]', '$csvData[2]', $csvData[3] )";
	    break;
	  default:
	    break;
    }
    
    if ($uploadType !== "error") {
	  $query_result = do_query($query, true);
	  if (!$query_result) {
		$result = array("error" => "MYSQL error");
		break;
	  }
	} else {
		$result = array("error" => "File Format not recognised");
		break;
	}
  }
}

// to pass data through iframe you will need to encode all html tags
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
?>
