<?php
//database connect

define ('DB_USER', 'yourusername');
define ('DB_PASSWORD', 'yourpassword');
define ('DB_HOST', 'yourhostname(usually "localhost")');
define ('DB_NAME', 'yourdatabasename');

$dbc = mysql_connect (DB_HOST, DB_USER, DB_PASSWORD) OR die('Could not connect to MySQL: ' . mysql_error());
mysql_select_db (DB_NAME) OR die('Could not select the database: ' . mysql_error());
?>
