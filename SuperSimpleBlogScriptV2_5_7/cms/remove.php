<?
/***************************************************

remove.php

This file deletes rows from the database.

***************************************************/

require_once('../settings.php'); //include global prefs
require_once('includes/header.php'); // include global header

//get the table name. If it is unset, display an error and die
if(isset($_GET['table'])){$table_name = $_GET['table'];}else{die('you must choose a table name. Please <a href="chooseTable.php">go back</a> and choose one.');}

require_once ('../sqlStrings.php'); // Connect to the database.

if(isset($_GET['dump'])){ //see if the dump is set. 'dump' is a boolean that is set when you agree to remove this row

//query the database
$query = "DELETE FROM {$_GET['table']} WHERE {$_GET['colName']} = {$_GET['colVal']} LIMIT 1";//the delete query
$result = mysql_query ($query) or die('The row could not be removed, perhaps due to a system error. Please try again.');
echo "The row was successfully deleted."; die("<a href=\"cms.php?table={$_GET['table']}\">Return to previous page</a>");
}//end IF 'dump' is set


// Query the database.
$query = "SELECT * FROM $table_name limit 1";//builds list of project for nav
$result = mysql_query ($query);

// Display all column names for this row to create a table header.
while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
echo "\n<tr>\n";
foreach($row as $key => $value){
echo "\n<td>$key</td>"; 
}
echo "\n</tr>\n"; 
}


// Query the database. 
$query = "SELECT * FROM $table_name where {$_GET['colName']} = {$_GET['colVal']} limit 1";//gets all columns from this row
$result = mysql_query ($query);

// Display the row
while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
echo "\n<tr>\n";
foreach($row as $key => $value){
echo "\n<td>$value</td>";
}
echo "\n</tr>\n";
}

echo "</table>";

//display safety message to make sure you dont delete a row by accident
if(!isset($_GET['dump'])){echo "<h1>Are you sure that you want to you want to delete this row?</h1>";}
?>

<a href="<?="remove.php?dump=true&table={$_GET['table']}&colName={$_GET['colName']}&colVal={$_GET['colVal']}"; ?>">Yes, delete.</a>
<a href="javascript:history.go(-1);">No, go back</a>
</div>
</div>

</body>
</html>
