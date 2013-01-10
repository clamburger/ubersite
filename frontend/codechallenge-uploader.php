<?php

require_once("includes/jsonLoader.php");
require_once("includes/database.php");
require_once("base-uploader.php");


// list of valid extensions, ex. array("jpeg", "xml", "bmp")
$allowedExtensions = array("py");
// max file size in bytes
$sizeLimit = 100 * 1024;

$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
// we want to only allow one submission per participant, replace previous attempts
$result = $uploader->handleUpload("camp-data/uploads/codechallenge/", TRUE);

session_start();

// to pass data through iframe you will need to encode all html tags
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
?>