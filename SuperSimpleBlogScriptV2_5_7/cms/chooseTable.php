<?
/***************************************************

chooseTable.php

This file returns all of the tables in the 
database that you connect to. It will also link 
to cms.php page, passing the table name as a GET
var.

***************************************************/

require_once('../settings.php'); //include global prefs
require_once('includes/header.php'); // include global header

require_once ('../sqlStrings.php'); // Connect to the database.

$result = mysql_list_tables(DB_NAME);
$num_rows = mysql_num_rows($result);


for($i=0; $i < $num_rows; $i++){
	$tableNameResult = mysql_tablename($result, $i);
	
	//Begin the output
	echo "<tr><td>Table Name</td><td valign=\"top\"><a href=\"cms.php?table=$tableNameResult\">$tableNameResult</a></td><td><a href=\"emptyTable.php?table=$tableNameResult\">EMPTY TABLE</a></td><td><a href=\"drop.php?table=$tableNameResult\">DROP TABLE</a></td></tr>";
		
}

require_once('includes/footer.php');
?>
