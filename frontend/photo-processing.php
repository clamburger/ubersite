<?php
  include_once("../includes/start.php");
  $title = 'Photo Processing Lab';
  $shortTitle = "Photo Lab";
  $tpl->set('title', $title);
  $tpl->set('shortTitle', $shortTitle);

  if (isset($_POST['filename']) && file_exists("camp-data/uploads/{$_POST['filename']}")) {
    # Processing an image
    $filename = userinput($_POST['filename']);
    $website = 0;
    $nobody = 0;
    if (isset($_POST['website'])) {
      $website = 1;
    }
    if (isset($_POST['nobody'])) {
      $nobody = 1;
    }
    $quality = userinput($_POST['quality']);

    # First query: update the photo_processing table
    $query = "UPDATE `photo_processing` SET `CampWebsite` = $website, `Quality` = '$quality', `Nobody` = $nobody,";
    $query .= "`Reviewer` = '$username', `DateReviewed` = NOW() WHERE `Filename` = '$filename'";
    do_query($query);

    # Second query: add the "nobody" tag, if applicable
    if (isset($_POST['nobody'])) {
      $query = "INSERT IGNORE INTO `photo_tags` (`Filename`, `Username`) VALUES ('$filename', 'nobody')";
      do_query($query);
    }

    # Third query: add any event tags
    $tags = userinput($_POST['tags']);
    if (!empty($tags)) {
      $tags = explode(",", $tags);
      $query = "INSERT IGNORE INTO `photo_event_tags` (`Filename`, `Tag`) VALUES ";
      foreach ($tags as $tag) {
        $tag = trim($tag);
        $query .= "('$filename', '$tag'), ";
      }
      $query = substr($query, 0, -2);
      do_query($query);
    }

    # Copy the photo to the photos directory, if applicable
    if ($website) {
      copy("camp-data/uploads/$filename", "camp-data/photos/$filename");
    }

  } else if (isset($_GET['filename'])) {
    $filename = $_GET['filename'];
  } else {
    $filename = false;
  }

  processCurrentFile:

  if ($filename) {
    if (!file_exists("camp-data/uploads/$filename")) {
      $filename = false;
    } else {
      $thumbnail = generate_thumbnail("camp-data/uploads/$filename", 500, 500);
      $tpl->set('imageURL', "photos/cache/$thumbnail");
    }
  }

  $tpl->set('filename', $filename, true);

  $dh = opendir("camp-data/uploads");
  $navigation = array();
  $allPhotos = array();

  $query = "SELECT * FROM `photo_processing` ";
  $query .= "ORDER BY `Reviewer` IS NOT NULL, `DateUploaded` ASC";
  $result = do_query($query);

  $photoInfo = array();

    while ($row = fetch_row($result)) {
    $allPhotos[] = $row['Filename'];
    $thumbnail = generate_thumbnail("camp-data/uploads/{$row['Filename']}", 200, 133);

    if ($filename == $row['Filename']) {
      $colour = "lime";
      $photoInfo = $row;
    } else if (empty($row['Reviewer'])) {
      $colour = "red";
    } else {
      $colour = "blue";
    }

    $navigation[] = array("thumbnail" => "photos/cache/$thumbnail", "filename" => $row['Filename'],
                 "colour" => $colour);

  }

  $tpl->set('navigation', $navigation, true);

  if ($filename) {

    $curKey = array_search($filename, $allPhotos);
    if ($curKey == 0) {
      $prevKey = count($allPhotos)-1;
    } else {
      $prevKey = $curKey - 1;
    }

    if ($curKey == count($allPhotos) - 1) {
      $nextKey = 0;
    } else {
      $nextKey = $curKey + 1;
    }

    if (isset($_POST['filename'])) {
      if ($filename == $allPhotos[$nextKey]) {
        $filename = false;
      } else {
        $filename = $allPhotos[$nextKey];
      }
      unset($_POST['filename']);
      goto processCurrentFile;
    }

    $tpl->set('prevFile', $allPhotos[$prevKey]);
    $tpl->set('nextFile', $allPhotos[$nextKey]);

    $resolution = getimagesize("camp-data/uploads/$filename");
    $resolution = "{$resolution[0]}x{$resolution[1]}";
    $tpl->set("resolution", $resolution);

    $uploader = "{$people[$photoInfo['Uploader']]}<br />".howLong($photoInfo['DateUploaded']);
    $tpl->set("uploader", $uploader);

    $form = array("tags" => "", "nobody" => "", "website" => "", "qualityDupe" => "",
            "qualityLow" => "", "qualityMed" => "", "qualityHigh" => "",
            "submit" => "Save this image and move onto the next one");

    if ($filename == $allPhotos[$nextKey]) {
      $form['submit'] = "Save this image";
    }

    if (empty($photoInfo['Reviewer'])) {

      $status = "<span style='color: maroon;'><strong>NOT PROCESSED</strong></span>";
      $form['qualityLow'] = "checked";

    } else {

      # The photo has already been processed, load all information
      $status = "<span style='color: green;'><strong>PROCESSED</strong></span> by {$people[$photoInfo['Reviewer']]}";
      $status .= " (".howLong($photoInfo['DateReviewed']).")";

      $query = "SELECT `Tag` FROM `photo_event_tags` WHERE `Filename` = '$filename'";
      $result = do_query($query);
      $tags = array();
      while ($row = fetch_row($result)) {
        $tags[] = $row['Tag'];
      }
      $form['tags'] = implode(", ", $tags);

      if ($photoInfo['Nobody'] == 1) {
        $form['nobody'] = "checked";
      }
      if ($photoInfo['CampWebsite'] == 1) {
        $form['website'] = "checked";
      }
      if ($photoInfo['Quality'] == 0) {
        $form['qualityDupe'] = "checked";
      } else if ($photoInfo['Quality'] == 1) {
        $form['qualityLow'] = "checked";
      } else if ($photoInfo['Quality'] == 2) {
        $form['qualityMed'] = "checked";
      } else if ($photoInfo['Quality'] == 3) {
        $form['qualityHigh'] = "checked";
      }

    }

    $tpl->set('form', $form);
    $tpl->set('status', $status);
  }
  fetch();
?>
