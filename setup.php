<?php
/***************************************************************************

	setup.php
	
	This file will walk you thru the initial installation of the sofware
	and generate the settings.xml file

***************************************************************************/

//Grab some info to set some values by default
		
		//make sure the server has php5 installed
		$phpversion = phpversion();
		if(intval(substr($phpversion,0,1)) < 5){ $version_failed = true; }else{ $version_failed = false; }

		//try to figure out the base path
		$_basePathToSetupFile = __FILE__;
		$_setupFile = strpos($_basePathToSetupFile,'setup.php');
		$_basePath = substr($_basePathToSetupFile, 0, $_setupFile -1);
		
		//try to figure out the base url
		$_hostString = $_SERVER['HTTP_HOST'];
		$_scriptString = $_SERVER['SCRIPT_NAME'];
		$_setupFile = strpos($_scriptString,'setup.php');
		$_baseUrl = $_hostString.substr($_scriptString, 0, $_setupFile);
		
		//get the time on the server to compare for the offset (move to approximate milliseconds
		$_serverTime = time()*1000;
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Super Simple Blog Script V3 Setup</title>
<link href="http://fonts.googleapis.com/css?family=Coustard" rel="stylesheet" type="text/css" />
<link rel="stylesheet" media="all" href="screen.css" type="text/css" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8" src="js/validation.js"></script>
<script type="text/javascript" charset="utf-8" src="js/utils.js"></script>
<script type="text/javascript" charset="utf-8">
$(document).ready(function(){

	var servertime = <?php echo $_serverTime; ?>;
	var d = new Date();
	//this will get the difference in milliseconds
	var time_difference = Math.round((d.getTime() - servertime) / (1000*60*60));
	$('#timeoffsetfromserver').val(time_difference);

});
</script>
</head>

<body>
<div id="container">
	<div id="header">
		<h1>Super Simple Blog Script V3 Setup</h1>
	</div>
	<div class="hr-line">&nbsp;</div>
<?php
	if($version_failed)
	{
		echo '<div class="error_box"><h2>This software requires PHP5 to operate correctly.<br />You currently have version: '.phpversion().'</h2></div>';
	}
?>

<form action="<?php echo $_SERVER['PHP_SELF']?>" method="get" id="setup_form" name="setup_form" onsubmit="return validateSetupForm();">
	<label for="basepath">Base File Path</label><input type="text" class="txt" name="basepath" id="basepath" value="<?php echo $_basePath ?>" />
	<div class="hr-clr">&nbsp;</div>
	<label for="baseurl">Installed URL</label><input type="text" class="txt" name="baseurl" id="baseurl" value="<?php echo $_baseUrl ?>" />
	<div class="hr-clr">&nbsp;</div>
	<label for="pagetitle">Blog Page Title</label><input type="text" class="txt" name="pagetitle" id="pagetitle" value="" />
	<div class="hr-clr">&nbsp;</div>
	<label for="rssdescription">Blog Description</label><textarea name="rssdescription" id="rssdescription" class="txa"></textarea>
	<div class="hr-clr">&nbsp;</div>
	<label for="postsperpage">Number of Blog Posts to Show On Each Page</label><input type="text" class="txt" name="postsperpage" id="postsperpage" value="5" />
	<div class="hr-clr">&nbsp;</div>
	<label for="blogpagefilename">Name of Blog Page</label><input type="text" class="txt" name="blogpagefilename" id="blogpagefilename" value="index.php" />
	<div class="hr-clr">&nbsp;</div>
	<label for="allowcomments">Allow Comments on Blog Posts?</label><select class="sel" name="allowcomments" id="allowcomments"><option value="1">Yes</option><option value="0">No</option></select>
	<div class="hr-clr">&nbsp;</div>
	<label for="timeoffsetfromserver">Time Offset from Server</label><input type="text" class="txt" name="timeoffsetfromserver" id="timeoffsetfromserver" value="" />
	<div class="hr-line">&nbsp;</div>
	<input class="btn" type="image" src="im/btn_submit.png" alt="Submit" style="margin-right:142px;" />
</form>
</div>
</body>

</html>