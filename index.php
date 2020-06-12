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

foreach ($all_contents as $item) {
  if ($item !== "." && $item !== "..") {
    // echo $item ."<br>"; // ou : echo "$item<br>";
    $contents[$item] = $item;
  }
}

$breadcrumb = explode(DIRECTORY_SEPARATOR, $cwd);
$cwd_road = "";

foreach ($breadcrumb as $name) {
  echo "<form method='POST'>";
    echo "<a href='index.php'>";
      echo "<button type='submit'>";
      echo $name;
      echo "</button>";
    echo "</a>";
    echo "<input type='text' name='cwd' value='$cwd_road'>";
  echo "</form>";
  $cwd_road .= $name . DIRECTORY_SEPARATOR; // ou $cwd_road = $cwd_road.$name . DIRECTORY_SEPARATOR; car .= est un opérateur concaténant
  echo $cwd_road."<br>";
}







include "footer.php";
