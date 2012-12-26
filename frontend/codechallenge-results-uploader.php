<?php

require_once("includes/jsonLoader.php");
require_once("includes/database.php");
require_once("base-uploader.php");


// list of valid extensions, ex. array("jpeg", "xml", "bmp")
$allowedExtensions = array();
// max file size in bytes
$sizeLimit = 100 * 1024;
// directory
$upload_directory = "camp-data/uploads/codechallenge/results/";

$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
$result = $uploader->handleUpload($upload_directory);

session_start();

if (isset($result['success'])) {
  $safeFilename = userInput($result['filename']);
  $csvFileLength = filesize("$upload_directory$safeFilename");
  $handle = fopen("$upload_directory$safeFilename", "r");
  while (($csvData = fgetcsv($handle)) !== false) {
	$query = "REPLACE INTO codechallenge_results (UserID, Score, TimeMSAverage, TimeMS1, TimeMS2, TimeMS3)";
	$query .= " VALUES ( '$csvData[0]', $csvData[1], $csvData[2], $csvData[3], $csvData[4], $csvData[5] );";
	echo $query;
	$query_result = do_query($query, true);
	if (!$query_result) {
		$result = array("error" => "MYSQL error");
		break;
	}
  }
}

// to pass data through iframe you will need to encode all html tags
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
?>
