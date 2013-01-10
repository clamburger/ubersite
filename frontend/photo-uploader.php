<?php

require_once("includes/jsonLoader.php");
require_once("includes/database.php");
require_once("base-uploader.php");


// list of valid extensions, ex. array("jpeg", "xml", "bmp")
$allowedExtensions = array();
// max file size in bytes
$sizeLimit = 20 * 1024 * 1024;

$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
$result = $uploader->handleUpload("camp-data/uploads/");

session_start();

if (isset($result['success'])) {
  $safeFilename = userInput($result['filename']);
  $tags = exif_read_data("camp-data/uploads/$safeFilename");
  $takenDate = $tags['DateTimeOriginal'];
  $query = "INSERT INTO `photo_processing` (`Filename`, `Uploader`, `DateUploaded`, DateTaken) ";
  $query .= " VALUES ('$safeFilename', '{$_SESSION['username']}', NOW(), '$takenDate')";
  $res = do_query($query, true);
  $thumbnail = generate_thumbnail("camp-data/uploads/{$result['filename']}", 200, 133);
  if (!$res) {
      $result = array("error" => 'File was uploaded successfully but a MySQL error occurred. ' .
          'Contact a tech leader for assistance. ('.mysql_error().')');
  }
}

// to pass data through iframe you will need to encode all html tags
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
?>
