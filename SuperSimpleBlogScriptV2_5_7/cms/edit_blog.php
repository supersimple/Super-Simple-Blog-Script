<?php
/***************************************************

edit_blog.php

This file displays 1 column that can be edited.
It will not allow you to edit the primary key.

***************************************************/

include("../settings.php");
include("../sqlStrings.php");

if(isset($_GET['colName'])){
$_this_col = $_GET['colName'];
$_this_uid = $_GET['colVal'];
$_this_table_name = $_GET['table'];

$query="SELECT * FROM $_this_table_name WHERE uid = '$_this_uid'";
$result = mysql_query ($query);

while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$_this_time = $row['time'];
	$_this_title = $row['title'];
	$_this_comment = $row['comment'];
	
	//run a check to detect if this is not a blog table
	if(empty($_this_time) && empty($_this_comment)){
		header("Location: edit.php?table=$_this_table_name&colName=$_this_col&colVal=$_this_uid");
	}
	
	$_this_image = split(", ", $row['image']); //build array out of images list
}

}else{
echo "You must select an entry to edit.";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?echo $pageTitle ?></title>
</head>
<!--
super simple PHP blog

written by Todd Resudek for TR1design.net.
todd@tr1design.net

source available at http://www.supersimple.org
copyright 2006

-->
<style type="text/css" media="all">@import "../blogStyles.css";</style>
<body>
<form enctype="multipart/form-data" action="<?='../'.$blogPageName; ?>" method="post" name="add">
<input type="hidden" name="MAX_FILE_SIZE" value="5000000"> <!--sets the max file size that this form will handle. php.ini file has more control than this.-->
<input type="hidden" name="uid" value="<?=$_this_uid ?>" />
<input type="hidden" name="col" value="<?=$_this_col ?>" />
<input type="hidden" name="table_name" value="<?=$_this_table_name ?>" />
<input type="hidden" name="origtime" value="<?=$_this_time ?>" />
<div><p><b>Time: </b><br /><input type="checkbox" name="time" /> Change to current time</p></div>
<div id="title">
<p><b>Title: </b><br /><input type="text" name="title" style="width:340px;" value="<?=$_this_title ?>" /><br /></p>
</div>
<div id="content">
<p><b>Comment: </b>
<? if($imagesPerEntry > 1){echo "<br /><em>Insert \"[#]\" in place of each image that you want to display (eg. lorem ipsum [1] sic dol amet...)</em>";} ?>
<br /><textarea name="comment" style="width:340px;height:250px"><?=$_this_comment ?></textarea><br />
</div>
<div id="image">
<?php #create the file uploads
for($i=0; $i < $imagesPerEntry; $i++){
$j = $i + 1;
echo "<p>Add Image: [$j] <span class=\"accent\">| current file: $_this_image[$i]</span><br /><input type=\"file\" name=\"upload$i\" value=\"$_this_image[$i]\"/></p>";
echo "<input type=\"hidden\" name=\"image$i\" value=\"$_this_image[$i]\" />\n";

}
?>
</div>
<div><p>Username: <input type="text" name="username" style="width:100px;"/> Password: <input type="password" name="password" style="width:100px;"/>
<p><input type="submit" name="update" value="Submit" /></p></div>
</form><!-- End of Form -->
</div>
</body>
</html>
