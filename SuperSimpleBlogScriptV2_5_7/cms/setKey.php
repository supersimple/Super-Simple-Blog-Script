<?
/***************************************************

setKey.php

This file is used to set a key where one does not 
exist. The edit file will not allow you to edit a
file that has no key, because it is not safe.
Having a table without a key is generally a bad
idea, so this file will be used on the rare 
occasion that a table exists without one.

A key should be a column that is unique, like 'id',
which should auto-increment.

***************************************************/

require_once('../settings.php'); //include global prefs
require_once('includes/header.php'); // include global header

//get the table name. If it is unset, display an error and die
if(isset($_GET['table'])){$table_name = $_GET['table'];}else{die('you must choose a table name. Please <a href="chooseTable.php">go back</a> and choose one.');}


require_once ('../sqlStrings.php'); // Connect to the database.


if(isset($_GET['set'])){ //see if the SET is set

//query the database
$query = "ALTER TABLE {$_GET['table']} ADD PRIMARY KEY({$_GET['col']})";//set the KEY
$result = mysql_query ($query) or die('The KEY could not be set. Either the column that you chose had non-unique entries, or their was a system failure. Please go back and try another Column.');
echo "The KEY was successfully set to Column: {$_GET['col']}";
}//end IF set


// Query the database.
$query = "SELECT * FROM $table_name limit 1";//get column names for header
$result = mysql_query ($query);
// Display all the URLs.
while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
echo "\n<tr>\n";
foreach($row as $key => $value){
echo "\n<td><a href=\"setKey.php?set=true&col=$key&table=$table_name\">$key</a></td>"; 
}
echo "\n</tr>\n";
}


// Query the database.
$query = "SELECT * FROM $table_name";//list all rows in this table
$result = mysql_query ($query);
// Display all the URLs.
while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
echo "\n<tr>\n";
foreach($row as $key => $value){
echo "\n<td>$value</td>";
}
echo "\n</tr>\n";
}


require_once('includes/footer.php');
?>

