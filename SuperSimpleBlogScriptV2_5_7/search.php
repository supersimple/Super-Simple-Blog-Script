 <?php
 
//****INCLUDE GLOBALS*******************************************************************

include("sqlStrings.php");
include("settings.php");

//****HANDLE FORM***********************************************************************

if (isset($_POST['submit']) ){ // Handle the form.

//**************************************************************************************


// Function for escaping and trimming form data.
	function escape_data ($data) { 
		global $dbc;
		if (ini_get('magic_quotes_gpc')) {
			$data = stripslashes($data);
		}
		return mysql_real_escape_string (trim ($data), $dbc);
	} // End of escape_data() function.
	
	//check to see that the fields are filled in
	if(!empty($_POST['search'])){
	$s = escape_data($_POST['search']);
	} else {
	echo 'the search string was empty.';
	
	die;}
		
	$s = escape_data($_POST['search']);
	
	

} // End of the main Submit conditional.
?>	


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $pageTitle ?></title>
	<style type="text/css" media="all">@import "blogStyles.css";</style>
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
<div class="cols">
<h2>Search Results</h2>
<p class="hr"></p>
<?php
//****GET ENTRIES FROM DATABASE***********************************************************	


//Add the record to the database.
$query = "SELECT comment, image, title, DATE_FORMAT(time, '%M %d, %Y %h:%i%p') AS time, uid, comments, postedBy FROM $TABLENAME WHERE comment LIKE '%$s%' || title LIKE '%$s%' ORDER BY 'uid' DESC";
$result = @mysql_query ($query);
//to find out if we got any results or not
$counter = @mysql_query ($query);
$rows = mysql_fetch_array($counter, MYSQL_NUM);
if(count($rows) != 1){
while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
$dateTime = strtolower($row[3]); //makes the AM PM lowercase. CSS is set to Capitalize the date later

echo "<a name=\"{$row[4]}\"></a><p class=\"time\">$dateTime</p>"; //anchor tag set for easier linking
echo "<h1 class=\"title\">{$row[2]}</h1>\n";

$ima = split(", ", $row[1]); //build array out of images list
for($i=0; $i<count($ima); $i++){
	if($imagesPerEntry == 1){
		if(!empty($ima[$i])) { 
			if(substr($ima[$i],-4) != '.pdf'){ //filter out PDFs
			echo "<img src=\"blogImages/$ima[$i]\" class=\"im\" />\n";
			}else{ //handle PDFs
			echo "<a href=\"blogImages/$ima[$i]\" target=\"_blank\"><img src=\"im/pdfIcon.gif\" border=\"0\" class=\"im\"/> $ima[$i]</a>";
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
	$com = str_replace("[$j]", "<img src=\"blogImages/$ima[$i]\" class=\"im\" />\n", $com);
	}else{ //handle PDFs
	$com = str_replace("[$j]", "<a href=\"blogImages/$ima[$i]\" target=\"_blank\"><img src=\"im/pdfIcon.gif\" border=\"0\" class=\"im\" /> $ima[$i]</a>\n", $com);
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
		echo "<p class=\"userComment\"><a href=\"javascript:void();\" onclick=\"window.open('comments.php?entry=$row[4]', 'Comments', 'width=490,height=350,location=no,toolbars=no,status=no scrollbars=yes');\">$row[5] comments</a> | <a href=\"javascript:void();]\" onclick=\"window.open('comments.php?entry=$row[4]', 'Comments', 'width=490,height=350,location=no,toolbars=no,status=no, scrollbars=yes');\">Comment</a><a class=\"permalink\" href=\"$blogPageName?entry=$row[4]\">permalink</a></p>";
	}else{
		echo "<p class=\"userComment\">$row[5] comments | <a href=\"javascript:void();\" onclick=\"window.open('comments.php?entry=$row[4]', 'Comments', 'width=490,height=350,location=no,toolbars=no,status=no, scrollbars=yes');\">Comment</a><a class=\"permalink\" href=\"$blogPageName?entry=$row[4]\">permalink</a></p>";
	}
}

//***************************************************************************************

echo "<p class=\"hr\"></p>"; //this is the break line. set it's appearance in the CSS file.

}

} else { echo 'The search yielded no results. <a href="javascript: history.go(-1);" >Go back </a>and try again.';}
echo "<div id=\"footer\">";
echo "<br /><a href=\"http://www.supersimple.org\"><img src=\"im/ss.gif\" alt=\"super simple blog script\" border=\"0\" /></a>";
echo " <a href=\"$rssFileName\"><img src=\"im/rss.gif\" alt=\"super simple RSS script\" border=\"0\" /></a>";
echo "</div>";
?>
</div>
</div>
</body>
</html>