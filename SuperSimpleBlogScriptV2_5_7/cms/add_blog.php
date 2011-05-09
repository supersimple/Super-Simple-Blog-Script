<?php
include("../settings.php");
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
<div id="title">
<p><b>Title: </b><br /><input type="text" name="title" style="width:340px;" /><br />
</div>
<div id="content">
<p><b>Comment: </b>
<? if($imagesPerEntry > 1){echo "<br /><em>Insert \"[#]\" in place of each image that you want to display (eg. lorem ipsum [1] sic dol amet...)</em>";} ?>
<br /><textarea name="comment" style="width:340px;height:250px"></textarea><br />
</div>
<div id="image">
<?php #create the file uploads
for($i=0; $i < $imagesPerEntry; $i++){
$j = $i + 1;
echo "<p>Add Image: [$j]<br /><input type=\"file\" name=\"upload$i\" /></p>";
}
?>
</div>
<div><p>Username: <input type="text" name="username" style="width:100px;"/> Password: <input type="password" name="password" style="width:100px;"/>
<p><input type="submit" name="submit" value="Submit" /></p></div>
</form><!-- End of Form -->
</div>
</body>
</html>
