<?php
include "header.php";

$home = "home";
if (!is_dir($home)) {
  mkdir("home");
}

// chdir(getcwd() . DIRECTORY_SEPARATOR . $home);

if (!isset($_POST["cwd"])) {
  $cwd = getcwd() . DIRECTORY_SEPARATOR . $home;
}
else {
  $cwd = $_POST["cwd"];
}

chdir($cwd);

$all_contents = scandir($cwd);
// print_r($all_contents);

$contents = [];
$contents_size = [];
$contents_date = [];
$contents_type = [];


foreach ($all_contents as $item) {
  if ($item !== "." && $item !== "..") {
    // echo $item ."<br>"; // ou : echo "$item<br>";
    $contents[$item] = $item;
    $contents_date[$item] = filemtime($cwd . DIRECTORY_SEPARATOR . $item);
    if (is_dir($cwd . DIRECTORY_SEPARATOR . $item)) {
      $contents_size[$item] = "";
      $contents_type[$item] = "Folder";
    }
    else {
      $contents_size[$item] = filesize($cwd . DIRECTORY_SEPARATOR . $item);
      if (strpos($item, ".")) {
        $type = explode(".", $item);
        $contents_type[$item] = end($type);
      }
      else {
        $contents_type[$item] = "undefined";
      }
    }
    }
  }

$breadcrumb = explode(DIRECTORY_SEPARATOR, $cwd);
$cwd_road = "";

$is_home = false; /* la variable indique si on est arrivé à "home" ou pas*/

echo "<form id='changecwd' method='POST'></form>";
echo "<form id='sort' method='POST'></form>";

echo "<div class='container row'>";
foreach ($breadcrumb as $name) {
  $cwd_road .= $name . DIRECTORY_SEPARATOR; // ou $cwd_road = $cwd_road.$name . DIRECTORY_SEPARATOR; car .= est un opérateur concaténant
  if ($name === "$home") {
    $is_home = true; /* Quand on arrive à "home" alors "true" ... */
  }
  if ($is_home) { /*...et si on est passé après "home" on affiche les boutons*/
    echo "<div class='d-flex'>";
        echo "<button type='submit' form='changecwd' name='cwd' value='" . substr($cwd_road, 0, -1) . "'>";
        echo $name;
        echo "</button>";
    echo "</div>";
  }
}
echo "</div>";

echo "<div class='container'>";
  echo "<div class='breadcrumb'>";
    echo "<div class='w-25'>";
      echo "<button type='submit' form='sort' name='sort' value=''>";
      echo "Name";
      echo "</button>";
    echo "</div>";
    echo "<div class='w-25'>";
      echo "<button type='submit' form='changecwd' name='sort' value=''>";
      echo "Date";
      echo "</button>";
    echo "</div>";
    echo "<div class='w-25'>";
      echo "<button type='submit' form='changecwd' name='sort' value=''>";
      echo "Size";
      echo "</button>";
    echo "</div>";
    echo "<div class='w-25'>";
      echo "<button type='submit' form='changecwd' name='sort' value=''>";
      echo "Type";
      echo "</button>";
    echo "</div>";
  echo "</div>";
foreach ($contents as $name) {
  echo "<div class='breadcrumb'>";
    echo "<div class='w-25'>";
        echo "<button type='submit' form='changecwd' name='cwd' value='" . $cwd . DIRECTORY_SEPARATOR . $name . "'>";
        echo $name;
        echo "</button>";
      echo "</div>";
    echo "<div class='w-25'>";
        echo date("d-m-Y à H:i:s", $contents_date[$name]);
      echo "</div>";
    echo "<div class='w-25'>";
        echo $contents_size[$name];
      echo "</div>";
    echo "<div class='w-25'>";
        echo $contents_type[$name];
      echo "</div>";
  echo "</div>";
}
echo "</div>";

include "footer.php";
