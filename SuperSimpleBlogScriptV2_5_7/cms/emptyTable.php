<?
/***************************************************

emptyTable.php

This file empties tables from the database.

***************************************************/

require_once('../settings.php'); //include global prefs
require_once('includes/header.php'); // include global header

//get the table name. If it is unset, display an error and die
if(isset($_GET['table'])){$table_name = $_GET['table'];}else{die('you must choose a table name. Please <a href="chooseTable.php">go back</a> and choose one.');}

require_once ('../sqlStrings.php'); // Connect to the database.

if(isset($_GET['empty'])){ //see if the dump is set. 'dump' is a boolean that is set when you agree to remove this row

//query the database
$query = "DELETE  FROM {$_GET['table']} ";//the empty query
$result = mysql_query ($query) or die('The table could not be emptied, perhaps due to a system error. Please try again.');
echo "The row was successfully emptied."; die("<a href=\"chooseTable.php\">Return to previous page</a>");
}//end IF 'empty' is set

echo "</table>";
echo "Table Name: {$_GET['table']}"; //print out the table name

//display safety message to make sure you dont delete a row by accident
if(!isset($_GET['empty'])){echo "<h1>Are you sure that you want to you want to empty this table?</h1><p>*The table&#39;s contents will be destroyed, which cannot be undone.</p>";}
?>

<a href="<?="emptyTable.php?empty=true&table={$_GET['table']}"; ?>">Yes, empty.</a>
<a href="javascript:history.go(-1);">No, go back</a>
</div>
</div>

</body>
</html>
