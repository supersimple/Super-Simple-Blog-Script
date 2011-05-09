<?php
$site = mysql_real_escape_string($_GET['site']);
$page = mysql_real_escape_string($_GET['page']);
$path = mysql_real_escape_string($_GET['path']);

//destroy the setup file
if(!unlink("$path/setup.php")){ /* try windows */ unlink("$path\setup.php"); }

header("Location: http://www.supersimple.org/success.php?link=$site/$page");
?>