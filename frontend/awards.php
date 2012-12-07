<?php
  include_once("includes/start.php");
  $title = 'Awards';
  $tpl->set('title', $title);

  $query = "SELECT * FROM `award_categories` WHERE `Enabled` = 1";
  $result = do_query($query);
  $categories = array();
  $listHTML = "";
  while ($row = fetch_row($result)) {
    $categories[$row['ID']] = array(
        "ID" => $row['ID'], "name" => $row['Category'],
        "description" => $row['Description'], "nominee" => false,
        "userpage" => false, "invisible" => true);
  }

  $day = date("l");

  if (isset($_POST['category']) && isset($categories[$_POST['category']])) {
    if (isset($_POST['denominate'])) {
      $query = "UPDATE `award_nominations` SET `Status` = 0 WHERE ";
      $query .= "`Category` = '{$_POST['category']}' AND `Submitter` = '$username'";
      $query .= " AND `Day` = '$day'";
      do_query($query);

      storeMessage('success', "You have successfully removed your nomination for " .
                  $people[$_POST['nominee']] . ".", $_POST['category']);
      action("denominate", $_POST['category'], $_POST['nominee']);
      refresh();
    } else if (isset($people[$_POST['nominee']])) {
      $nominee = userInput($_POST['nominee']);
      $notes = userInput($_POST['notes']);
      if (empty($notes)) {
        $messages->addMessage(new Message("error", "You must enter a nomination reason!"));
      } else {
        $query = "INSERT IGNORE INTO `award_nominations` (`Nominee`, `Category`, `Submitter`,  `Notes`, `Date`, `Day`)";
        $query .= " VALUES ('$nominee', '{$_POST['category']}', '$username', '$notes', NOW(), '$day')";
        do_query($query);

        $categories[$_POST['category']]['invisible'] = false;
        storeMessage('success', "You have successfully nominated {$people[$_POST['nominee']]} for \"".
                    $categories[$_POST['category']]['name'] . "\"!", $_POST['category']);
        action("nominate", $_POST['category'], $_POST['nominee']);
        refresh();
      }
    }
  }

  if (isset($storedValue)) {
    $categories[$storedValue]['invisible'] = false;
  }

  foreach ($categories as $ID => $data) {
    $listHTML .= "<option value='{$data['ID']}' ";
    if (!$data['invisible']) {
      $listHTML .= "selected";
    }
    $listHTML .= ">{$data['name']}</option>\n";
  }

  $query = "SELECT * FROM `award_nominations` WHERE `Submitter` = '$username' AND `Status` = 1 AND `Day` = '$day'";
  $result = do_query($query);
  while ($row = fetch_row($result)) {
    $categories[$row['Category']]["nominee"] = $row['Nominee'];
    $categories[$row['Category']]["userpage"] = userpage($row['Nominee']);
  }

  $sortedPeople = array_flip($people);
  ksort($sortedPeople);

  $dropdown = "<option value='none'>---</option>\n";
  foreach ($sortedPeople as $name => $ID) {
    $dropdown .= "<option value='$ID'";
    if ($ID == $username) {
      $dropdown .= " disabled";
    }
    $dropdown .= ">$name</option>\n";
  }

  if ($leader) {
    $tpl->set('day', $day);
    $query = "SELECT * FROM `award_nominations` WHERE `Day` = '$day' AND `Status` = 1 ";
    $query .= " ORDER BY `Category`";
    $result = do_query($query);
    $nomCats = array();

    foreach ($categories as $id => $info) {
      $nomCats[$id]['name'] = $info['name'];
      $nomCats[$id]['nominees'] = array();
    }

    while ($row = fetch_row($result)) {
      $nomCats[$row['Category']]['nominees'][] = userpage($row['Nominee']) . ": {$row['Notes']} <em>(nominated by ".userpage($row['Submitter'], true).")</em>";
    }

    $tpl->set('nomCats', $nomCats);
  }

  $tpl->set('userDropdown', $dropdown);
  $tpl->set('categoryDropdown', $listHTML);
  $tpl->set('categories', $categories);

  fetch();
?>
