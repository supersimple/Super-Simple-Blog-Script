<?
/***************************************************

cms.php

This file returns the contents of a table that you 
choose from chooseTable.php. There are links for 
each row that allow you to edit/remove that row.

***************************************************/

require_once('../settings.php'); //include global prefs
require_once('includes/header.php'); // include global header

//get the table name. If it is unset, display an error and die
if(isset($_GET['table'])){
	$table_name = $_GET['table'];
}else{
	die('you must choose a table name. Please <a href="chooseTable.php">go back</a> and choose one.');
}

require_once ('../sqlStrings.php'); // Connect to the database.

// Query the database.
$query = "SHOW INDEX FROM $table_name";//get the primary key for the table
$result = mysql_query ($query);

// Display all the URLs.
while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
$keyNum = $row['Column_name']; //this is the table's key
}

//make sure that there is a key. If not, we need to find an alternative
if(!isset($keyNum)){$keySet=false; echo "<h1 class=\"error\">This table contains no KEY, and cannot be safely updated. Please <a href=\"setKey.php?table=$table_name\">set a KEY</a> before trying to continue.</h1>";}

mysql_free_result($result); //free up the result for the next query




// Query the database.
$query = "SELECT * FROM $table_name limit 1";//builds the header
$result = mysql_query ($query);
$r=0; //holds the number of cells for later
// Display a header row with all of the column names.
while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
echo "\n<tr>\n";
foreach($row as $key => $value){
$r++;
echo "\n<td>$key</td>"; 
}
$r+=2;
echo "\n<td>edit</td>\n<td>remove</td>\n</tr>\n";
}


//get the page number (for paginating the rows). If not set, set to page=1.
if(!isset($_GET['pg'])){$pg=1;}else{$pg = $_GET['pg'];}

// Query the database.
$query = "SELECT * FROM $table_name";//get all of the rows from that table
$result = mysql_query ($query);

// Display all of the rows.
$totalEntries = 0; //init the total entries number

while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
$totalEntries++;
}

//set up the variables for paginating
$ePage = $pg * $perPage; //tells the last posting on the page. (eg. 7 per page, page 2 the last posting is number 14)
$bPage = $ePage - $perPage; //counts back to the first listing on each page

$pPage = $pg -1; // previous page
$nPage = $pg +1; // next page


// Query the database.
$query = "SELECT * FROM $table_name order by $keyNum desc LIMIT $bPage,$perPage";//get this page's rows
$result = mysql_query ($query);

// Display all of the rows.
while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
echo "\n<tr>\n";
foreach($row as $key => $value){
echo "\n<td>$value</td>";
}
echo "\n<td><a href=\"edit_blog.php?colName=$keyNum&colVal=$row[$keyNum]&table=$table_name\">edit</a></td>\n<td><a href=\"remove.php?colName=$keyNum&colVal=$row[$keyNum]&table=$table_name\">remove</a></td>\n</tr>\n";
}

//display pagination 
$pages = ceil($totalEntries/$perPage); //divides the total posts by the posts per page then rounds up. This sets the total pages.
	echo "<tr><td colspan=\"$r\">";
	if($pg != 1){
	echo "<a href=\"cms.php?pg=$pPage&table=$table_name\"<<</a>&nbsp;&nbsp;&nbsp;";
	}

	echo $pages." total pages";
	if($page != $pages && $totalEntries != 0){
	echo "&nbsp;&nbsp;&nbsp;<a href=\"cms.php?pg=$nPage&table=$table_name\">>></a>";
	}
	echo "</td></tr>";

require_once('includes/footer.php');
?>

