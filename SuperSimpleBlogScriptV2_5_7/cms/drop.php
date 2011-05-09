<?
/***************************************************

drop.php

This file drops tables from the database.

***************************************************/

require_once('../settings.php'); //include global prefs
require_once('includes/header.php'); // include global header

//get the table name. If it is unset, display an error and die
if(isset($_GET['table'])){$table_name = $_GET['table'];}else{die('you must choose a table name. Please <a href="chooseTable.php">go back</a> and choose one.');}

require_once ('../sqlStrings.php'); // Connect to the database.

if(isset($_GET['dump'])){ //see if the dump is set. 'dump' is a boolean that is set when you agree to remove this row

//query the database
$query = "DROP TABLE {$_GET['table']} ";//the drop query
$result = mysql_query ($query) or die('The table could not be removed, perhaps due to a system error. Please try again.');
echo "The row was successfully deleted."; die("<a href=\"chooseTable.php\">Return to previous page</a>");
}//end IF 'dump' is set

echo "</table>";
echo "Table Name: {$_GET['table']}"; //print out the table name

//display safety message to make sure you dont delete a row by accident
if(!isset($_GET['dump'])){echo "<h1>Are you sure that you want to you want to drop this table?</h1><p>*The table and all of it's contents will be destroyed, which cannot be undone. If you would like to keep the table, but empty it&#39;s contents, choose <a href=\"emptyTable.php\">empty table</a> instead.</p>";}
?>

<a href="<?="drop.php?dump=true&table={$_GET['table']}"; ?>">Yes, delete.</a>
<a href="javascript:history.go(-1);">No, go back</a>
</div>
</div>

</body>
</html>
