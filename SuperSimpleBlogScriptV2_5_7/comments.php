<?php

include("sqlStrings.php");
include('settings.php');


//****************************************************************************************	

//*****STORE THE UID**********************************************************************	

//store GET var
$uid = mysql_real_escape_string($_GET['entry']);

//****************************************************************************************	

//****GET THE CURRENT COMMENT COUNT*******************************************************	

	//get the current count of comments
	$query = "SELECT comments, time from $TABLENAME where uid = $uid";
	$result = @mysql_query($query);
	while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
	$origCount = $row[0];
	$origTime = $row[1];
	}
	
//****************************************************************************************	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $pageTitle; ?></title>
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
<div id="container" class="commentWindow">

<div class="cols">

<?php

//****HANDLE FORM********************************************************************	

if (isset($_POST['submit']) ){ // Handle the form.

	//init an error message
	$errors = 'The following errors occured:';
	//and boolean
	$err = false;
	
	if(!empty($_POST['url'])){
		$ePre = strip_tags(stripslashes($_POST['url']));
		if(substr($ePre, 0, 4) == 'http'){$e = $ePre;}else{$e = 'http://'.$ePre;}
	}else{
		$e = '';
	}
	if(!empty($_POST['name'])){
		$n = strip_tags(stripslashes($_POST['name']));
	}else{
		$err=true;
		$errors .= '<br />name was not set';
	}
	if(!empty($_POST['comment'])){
		$c = strip_tags(stripslashes(nl2br($_POST['comment'])));
	}else{
		$err=true;
		$errors .= '<br />comment was not set';
	}
//****************************************************************************************	

//****CHECK FOR ERRORS********************************************************************	

	if($err){echo "<p>$errors</p>";
	}else{//there are no errors
	
	$d=date('M j, Y g:ia');

//****************************************************************************************	
	
//****BUILD A STRING TO WRITE TO FILE*****************************************************	
	
	//build the string to be written
	$comStr = <<<EOT
	<p class="time">$d</p>
	<p class="userComment">$c</p>
	<p class="userName"><a href="$e" target="_blank">$n</a></p>
	<p class="hr"></p>
EOT;
	
//****************************************************************************************	


//****WORK WITH THE FLAT FILE*************************************************************	

	//open or create a file 
	$commentFile = fopen("$baseURL/comments/$uid.com", 'a') or die('Cannot open comments file');
	fwrite($commentFile, $comStr) or die('Cannot write to file');
	fclose($commentFile);

//****************************************************************************************	

//****INCREMENT THE COMMENT COUNT IN THE DATABASE*****************************************	
		
	//update the count in the database
	$newCount = $origCount + 1;
	$query = "UPDATE $TABLENAME SET comments = '$newCount', time = '{$_POST['orig_time']}' WHERE uid = '$uid'";
	$result = @mysql_query ($query);
	
//****************************************************************************************	

//****REFRESH THE PARENT WINDOW TO SHOW NEW COUNT*****************************************
echo "<script type=\"text/javascript\">this.opener.location.reload();</script>";

	}

}
?>

<h2>Comments</h2>
<?php

//****IF COMMENTS EXIST, DISPLAY THEM*****************************************************	

if($origCount != 0 || $submit){
include('comments/'.$uid.'.com');
}else{
echo "No comments to display";
}

//****************************************************************************************	

?>
<fieldset>
<legend>Leave Comment</legend>
<form name="leaveComment" action="<?php echo $PHP_SELF.'?entry='.$_GET['entry']; ?>" method="post">
<input type="hidden" name="orig_time" value="<?php echo $origTime?>" />
<p>URL:<br /><input type="text" name="url" style="width:280px;" /></p>
<p>Name:<br /><input type="text" name="name" style="width:280px;" maxlength="24" /></p>
<p>Comment: (no html tags)<br /><textarea name="comment" style="width:280px;height:75px;"></textarea></p>

<p><input type="submit" name="submit" value="Submit Comment" /></p>
</form>
</fieldset>
</div>
</div>
</body>
</html>