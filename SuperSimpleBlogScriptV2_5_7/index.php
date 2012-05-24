 <?php
 
//****INCLUDE GLOBALS*******************************************************************

include("sqlStrings.php");
include("settings.php");

//****HANDLE FORM***********************************************************************

if (isset($_POST['submit']) || isset($_POST['update'])){ // Handle the form.

//****SET OPERATION*********************************************************************

if (isset($_POST['submit'])) { $_db_operation = 'insert';}
else if (isset($_POST['update'])){ $_db_operation = 'update';}

//****SORT OUT THE USERNAME AND PASSWORD*************************************************


//init the errors
$err = "the following errors occurred:\n";

	
if(!empty($_POST['username'])){
$_un = $_POST['username']; 
}else{
$err .= "Username was not set.\n";
}

if(!empty($_POST['password'])){
$_pw = $_POST['password'];
}else{
$err .= "Password was not set.\n";
}

//check for match
if(array_key_exists($_un, $users_login)){
	if($USERS[$_un] == $_pw){
		$userPassed = true;
	}else{
		$userPassed = false;
		$error= 'The password was not correct. Please try again.';
	}
}else{
		$userPassed = false;
		$error= 'The username and/or password was not correct. Please try again.';

}


//****CALCULATE THE OFFSET TIME************************************************************

	$OFFSETTIME = date("Y-m-d H:i:s", time() + ($TIMEOFFSET * 60 * 60));
	
//***************************************************************************************

if($userPassed){//check the password and username

// Function for escaping and trimming form data.
	function escape_data ($data) { 
		global $dbc;
		if (ini_get('magic_quotes_gpc')) {
			$data = stripslashes($data);
		}
		return mysql_real_escape_string (trim ($data), $dbc);
	} // End of escape_data() function.
	
	//check to see that the fields are filled in
	if(!empty($_POST['comment'])){
	$co = escape_data($_POST['comment']);
	} else {
	echo 'you forgot to set the comment.';
	
	die;}
	
	if(!empty($_POST['title'])){
	$ti = escape_data($_POST['title']);
	} else {
	echo 'you forgot to set the title.';
	
	die;}
	
	//fields for update only
	if(!empty($_POST['uid'])){
	$uid = escape_data($_POST['uid']);
	}
	if(!empty($_POST['col'])){
	$editCol = escape_data($_POST['col']);
	}
		

	
	//check to see if the entry time will be updated to current time, or remain the original time
	if($_POST['time']){
	$time = $OFFSETTIME;
	}else{
	$time = $_POST['origtime'];
	}
		
	$t = escape_data($_POST['title']);
	$c = escape_data($_POST['comment']);
	$un = escape_data($_POST['username']);
	
	
//****GET FILENAMES************************************************************************

	//concat the filenames into an array
	$filename = "";
	
	//prepend the files with the month and date to organize, and to ensure that 2 files dont have the same name
	$prependfile = date("my");
	
	for($i=0; $i<$imagesPerEntry; $i++){
		$curImageFile = "upload$i";
		$oldImageFile = "image$i";
		
		//make sure that it isnt an empty field, because that will destroy the array structure
		if(!empty($_FILES[$curImageFile]['name'])){
			if($i == 0){//no comma before the first entry
				$filename .= $prependfile.$_FILES[$curImageFile]['name'];
			}else{
				$filename .= ', '.$prependfile.$_FILES[$curImageFile]['name'];
			}
		}else{ //we should try to keep the old image
			if($i == 0){//no comma before the first entry
				$filename .= $_POST[$oldImageFile];
			}else{
				$filename .= ', '.$_POST[$oldImageFile];
			}
		}
	}
	

//****INSERT INTO DB***********************************************************************	

	if($_db_operation == 'insert'){
	//Add the record to the database.
	$query = "INSERT INTO $TABLENAME (comment, title, image, time, postedBy) VALUES ('$c', '$t', '$filename', '$OFFSETTIME', '$un' )";
	$result = @mysql_query($query);
	echo '<h1>Comment added</h1>';	
	}
	else if($_db_operation == 'update'){
	//Update the record to the database.
	$query = "UPDATE $TABLENAME SET comment = '$c' , title = '$t', image = '$filename', time = '$time', postedBy = '$un' WHERE $editCol = '$uid'";
	$result = @mysql_query ($query);
	echo '<h1>Comment added</h1>';
	}



//****UPLOAD IMAGES************************************************************************	
	
	//split the filenames into an array
	$filesArray = preg_split(", ", $filename);
	if ($result) {
		//Move file(s) over
	
		//loop through the filesArray and upload files
		for($i=0; $i<$imagesPerEntry; $i++){
			$curImageFile = "upload$i";
			if(!empty($filesArray[$i])){
				if (move_uploaded_file($_FILES[$curImageFile]['tmp_name'], "$baseURL/blogImages/$filesArray[$i]")) { //moving to the 'blogImages' directory
					chmod("$baseURL/blogImages/$filesArray[$i]", 0755); //added to ensure that the files are readable
					echo '<p>The file has been uploaded!</p>';
				} else {
					echo '<p><font color="red">The file could not be moved.</font></p>';
				}
			
			}else{/*no file in here*/}
		
		} //end of for loop
		
	} else { // If the query did not run OK.
		echo '<p><font color="red">Your submission could not be processed due to a system error. We apologize for any inconvenience.</font></p>'; 
		echo 'this is the error: '. mysql_error();
	}

//refresh the page to clear out the POSTback	

}//end of username and password check
else{
	echo '<p><font color="red">The Username/Password that you entered does not match.</font></p>';
}

//****GENERATE RSS FEED********************************************************************	

include('feed_maker.php');
$makerss = new GenerateFeed;
$genFile = $makerss->makeFeed($TABLENAME,$websiteRoot, $pageTitle, $rssDesc, $rssLink, $rssFileName);


//*****************************************************************************************	

} // End of the main Submit conditional.
?>	


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $pageTitle ?></title>
	<style type="text/css" media="all">@import "blogStyles.css";</style>
	<link rel="alternate" type="application/rss+xml" title="RSS news feed (summaries)" href="<?php echo $websiteRoot.'/'.$rssFileName?>" />
</head>
<!--
super simple PHP blog

written by Todd Resudek for TR1design.net.
todd@tr1design.net

source available at http://www.supersimple.org
copyright 2006

-->
<body>
<div id="container">
<div id="header"></div>
<div class="cols">
<form action="search.php" method="post" name="search" id="searchBar" >
<input type="text" name="search" /><input type="submit" name="submit" value="search" />
</form>
<?php

//****GET PAGE NUMBER********************************************************************	

if(isset($_GET['pg']) && $_GET['pg']>0){$page = $_GET['pg'];}else{$page=1;} //finds the page number from the URL
$ePage = $page * $perPage; //tells the last posting on the page. (eg. 7 per page, page 2 the last posting is number 14)
$bPage = $ePage - $perPage; //counts back to the first listing on each page

//store the prev and next page numbers for use in the pagination nav later
$pPage = $page -1; // previous page
$nPage = $page +1; // next page

//****************************************************************************************	


//****GET COUNT OF ENTRIES****************************************************************	


// Query the database. 
$query = "SELECT uid FROM $TABLENAME";
$result = mysql_query ($query);
$totally = 0;
while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
$totally += 1; //loop runs to count the total number of posts. This sets the page forward and back links. It knows the last page and first page.
}

//****************************************************************************************	

//****GET ENTRIES FROM DATABASE***********************************************************	

//function for securing GET data
function cleanEntry($_entry_str){
	$_bad_chars = array(' ', 'select', 'insert', 'delete', 'update', ';', ',');
	$_remove_chars = str_replace($_bad_chars, '', strtolower($_entry_str));
	$_remove_html = strip_tags($_remove_chars);
	if(is_numeric($_remove_html)){
	return $_remove_html; 
	}else{
	die('You must refer to entries using integers only');
	}
}

//if the GET var 'entry' is set, this is a permalink and we will only ask for 1 entry 
if(isset($_GET['entry'])){
//set a var to check if we are in teh entry page view later
$_entryview = true;
//clean the entry number to remove possible security issues
$_perma_entry = cleanEntry($_GET['entry']);

$query = "SELECT comment, image, title, DATE_FORMAT(time, '%M %d, %Y %h:%i%p') AS time, uid, comments, postedBy, time AS ts FROM $TABLENAME WHERE uid = '$_perma_entry' LIMIT 1";

}else{

$query = "SELECT comment, image, title, DATE_FORMAT(time, '%M %d, %Y %h:%i%p') AS time, uid, comments, postedBy, time AS ts FROM $TABLENAME ORDER BY ts DESC LIMIT $bPage,$perPage ";

}


$result = mysql_query ($query);
while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
$dateTime = strtolower($row[3]); //makes the AM PM lowercase. CSS is set to Capitalize the date later

echo "<a id=\"{$row[4]}\"></a><p class=\"time\">$dateTime</p>"; //anchor tag set for easier linking
echo "<h1 class=\"title\">{$row[2]}</h1>\n";

$ima = preg_split(", ", $row[1]); //build array out of images list
for($i=0; $i<count($ima); $i++){
	if($imagesPerEntry == 1){
		if(!empty($ima[$i])) { 
			if(substr($ima[$i],-4) != '.pdf'){ //filter out PDFs
			echo "<img src=\"blogImages/$ima[$i]\" class=\"im\" alt=\"$ima[$i]\" />\n";
			}else{ //handle PDFs
			echo "<a href=\"blogImages/$ima[$i]\" target=\"_blank\"><img src=\"im/pdfIcon.gif\" border=\"0\" class=\"im\" alt=\"PDF\" /> $ima[$i]</a>";
			}	
		}
	}
}
//****************************************************************************************	


//****DISPLAY COMMENT*********************************************************************	


$com = nl2br($row[0]);
for($i=0; $i<count($ima); $i++){
$j = $i+1;
if($imagesPerEntry != 1){
	if(substr($ima[$i],-4) != '.pdf'){ //filter out PDFs
	$com = str_replace("[$j]", "<img src=\"blogImages/$ima[$i]\" class=\"im\" alt=\"$ima[$i]\" />\n", $com);
	}else{ //handle PDFs
	$com = str_replace("[$j]", "<a href=\"blogImages/$ima[$i]\" target=\"_blank\"><img src=\"im/pdfIcon.gif\" border=\"0\" class=\"im\" alt=\"PDF\" /> $ima[$i]</a>\n", $com);
	}

}
}
echo "<p class=\"comment\">$com</p>";

//****************************************************************************************	



//****DISPLAY POSTED BY******************************************************************

//if multiUsers is turned on in settings.php, display the username that wrote the entry
if($MULTIUSERS){
echo "<p class=\"postedBy\">Posted By: $row[6]</p>";
}

//***************************************************************************************



//****DISPLAY COMMENTS*******************************************************************

//you must have comments turned on (in settings.php) for this to display
if($COMMENTS){
	if($row[5] != 0){
		echo "<p class=\"userComment\"><a href=\"javascript:void(function(){var super = 'simple';});\" onclick=\"window.open('comments.php?entry=$row[4]', 'Comments', 'width=490,height=350,location=no,toolbars=no,status=no scrollbars=yes');\">$row[5] comments</a> | <a href=\"javascript:void(function(){var super = 'simple';});\" onclick=\"window.open('comments.php?entry=$row[4]', 'Comments', 'width=490,height=350,location=no,toolbars=no,status=no, scrollbars=yes');\">Comment</a><a class=\"permalink\" href=\"$blogPageName?entry=$row[4]\">permalink</a></p>";
	}else{
		echo "<p class=\"userComment\">$row[5] comments | <a href=\"javascript:void(function(){var super = 'simple';});\" onclick=\"window.open('comments.php?entry=$row[4]', 'Comments', 'width=490,height=350,location=no,toolbars=no,status=no, scrollbars=yes');\">Comment</a><a class=\"permalink\" href=\"$blogPageName?entry=$row[4]\">permalink</a></p>";
	}
}

//***************************************************************************************



echo "<p class=\"hr\"></p>"; //this is the break line. set it's appearance in the CSS file.
}

echo "<div id=\"footer\">";

if(!$_entryview){ //if not the entry view, show pagination
	$pages = ceil($totally/$perPage); //divides the total posts by the posts per page then rounds up. This sets the total pages.
	
	//if on page 1, dont give a page back link
	if($page != 1){
		echo '<p><a href="'.$blogPageName.'?pg='.$pPage.'">&laquo;</a>&nbsp;&nbsp;&nbsp;';
	}

	echo $pages." total pages";
	
	//if on the last page, dont link ahead
	if($page != $pages && $totally != 0){
		echo '&nbsp;&nbsp;&nbsp;<a href="'.$blogPageName.'?pg='.$nPage.'">&raquo;</a></p><p>&nbsp;</p>';
	}
}else{ //this is an entry page
	echo "<p><a href=\"$blogPageName\">View all entries&raquo;</a></p>";
}

echo "<br /><a href=\"http://www.supersimple.org\"><img src=\"im/ss.gif\" alt=\"super simple blog script\" border=\"0\" /></a>";
echo " <a href=\"$rssFileName\"><img src=\"im/rss.gif\" alt=\"super simple RSS script\" border=\"0\" /></a>";
echo "</div>";
?>
</div>
<?php mysql_close(); // Close the database connection. ?>
</div>
</body>
</html>
