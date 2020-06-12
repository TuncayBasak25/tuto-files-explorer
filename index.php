<?php

$cwd = getcwd();
echo $cwd;

$all_contents = scandir($cwd);
print_r($all_contents);
