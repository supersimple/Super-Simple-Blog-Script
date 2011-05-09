<?
/***************************************************

edit.php

This file displays 1 column that can be edited.
It will not allow you to edit the primary key.

***************************************************/

require_once('../settings.php'); //include global prefs
require_once('includes/header.php'); // include global header
include('classes/cmsClasses.php'); //include the DB classes


//get the table name. If it is unset, display an error and die
if(isset($_GET['table'])){
	$table_name = $_GET['table'];
}else if(!isset($submit)){
	die('you must choose a table name. Please <a href="chooseTable.php">go back</a> and choose one.');
}

require_once ('../sqlStrings.php'); // Connect to the database.



if(isset($submit)){ //see if the update is set - this section will handle the form

$obj = new getColNames; //create a new DB info object
$names = $obj->listColNames($_POST[tableName]); //use method to get the necessary info

//begin building the database query
$query = "UPDATE {$_POST[tableName]} SET "; //this is the first part of the query


for($i=0; $i < count($names); $i++){ //$names is the array that our object returned, containing all of the columns. This loop will continue building the 'meat' of our query
	if($i != 0){
	$query .= ", $names[$i] = '{$_POST[$names[$i]]}'"; //as we build the query, we need a comma between each column that we update.
	}else{
	$query .= "$names[$i] = '{$_POST[$names[$i]]}'";	//this is only used for the first column, since we do not want a column before it.
	}
}

$query .= " WHERE {$_POST['colName']} = {$_POST['colVal']}"; //complete the query

//query the database
$result = mysql_query ($query) or die("<br /><br />The row could not be updated, perhaps due to a system error. Please try again.");
echo "The row was successfully updated.\n"; die("<a href=\"cms.php?table={$_POST['tableName']}\">Return to previous page</a>");
}//end IF submit
?>

<form name="edit" method="post" action="<?=$_SERVER['PHP_SELF']?>">
<?
//link to edit blog page
echo "<a href=\"edit_blog.php?table=$table_name&colName={$_GET['colName']}&colVal={$_GET['colVal']}\">Edit as blog</a>";


// Query the database.
$query = "SELECT * FROM $table_name where {$_GET['colName']} = {$_GET['colVal']} limit 1";//get all columns in this row
$result = mysql_query ($query);

//get the data types so that we can produce the correct input types
//init arrays for later
$dataTypes = array();
$dataNames = array();

for ($i = 0; $i < mysql_num_fields($result); $i++) { //loop through the query results

   // Find the name of the current field
   $field_name = mysql_field_name($result, $i);

   // Find the type of the current field
   $field_type = mysql_field_type($result, $i);
   
   //build matching arrays of name-type. Use this method instead of array_push to save memory
   $dataTypes[] = $field_type;
   $dataNames[] = $field_name;
}

//init 2 more arrays for later
$values = array();
$names = array();

// Display the editable rows
while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
$i=0; //init i, making this while loop act like a for loop

//break each row into a key-value pair
foreach($row as $key => $value){
	if($key != $_GET['colName']){ //we do not want to be able to edit the primary KEY
		
		//we will build the query without updating the KEY
		$names[] = $key;
		$values[] = $value;
		
		//blobs are long, so we need to display in a textarea
		if($dataTypes[$i] == 'blob'){ 
		echo "\n<tr>\n<td>$key:<br /><textarea name=\"$dataNames[$i]\" >$value</textarea></td>\n</tr>\n";
		
		//the other datatypes are generally shorter, so we can use an input
		} else if($dataTypes[$i] == 'string' || $dataTypes[$i] == 'int' || $dataTypes[$i] == 'real' || $dataTypes[$i] == 'timestamp' || $dataTypes[$i] == 'year' || $dataTypes[$i] == 'date' || $dataTypes[$i] == 'time' || $dataTypes[$i] == 'datetime'){
			echo "\n<tr>\n<td>$key:";
			//give some format hints for timestamp
			if($dataTypes[$i] == 'timestamp'){echo " YYYY MM DD HH MM SS <em>or</em> <input type=\"checkbox\" name=\"$dataNames[$i]NOW\" style=\"width:10px; height:10px;\" />Set to current time";}
			
			echo"<br /><input type=\"text\" value=\"$value\" name=\"$dataNames[$i]\" /></td>\n</tr>\n";
		}
		
	}else{ //this is our PRIMARY KEY
	echo "\n<input type=\"hidden\" value=\"$value\" name=\"$dataNames[$i]\" />\n";
	}
$i++;
}//end foreach
}//end while


//we use hidden fields for passing table information
echo "<input type=\"hidden\" name=\"tableName\" value=\"{$_GET['table']}\" />";
echo "<input type=\"hidden\" name=\"colName\" value=\"{$_GET['colName']}\" />";
echo "<input type=\"hidden\" name=\"colVal\" value=\"{$_GET['colVal']}\" />";

?>


</table>

<input type="submit" name="submit" />
<input type="button" value="No, Go Back." onclick="history.go(-1);" />
</form>
</div>
</div>

</body>
</html>
