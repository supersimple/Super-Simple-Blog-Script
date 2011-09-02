<?php
	require_once('class.xml-parser.php');
	
	if(isset($_POST))
	{
		$xmlobj = new XMLParser("settings.xml");
		$xmlobj->updateSettings($_POST,true);
	}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Super Simple Blog Script V3 Setup</title>
<link href="http://fonts.googleapis.com/css?family=Coustard" rel="stylesheet" type="text/css" />
<link href="http://fonts.googleapis.com/css?family=Arvo" rel="stylesheet" type="text/css" />
<link rel="stylesheet" media="all" href="screen.css" type="text/css" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8" src="js/validation.js"></script>
<script type="text/javascript" charset="utf-8" src="js/utils.js"></script>
</head>

<body>
<div id="container" style="text-align:center;">
	<div class="success_box">
	<h1>Congratulations! You are ready to go.</h1>
	<a href="" class="lrg_btn">Start adding blog posts now &raquo;</a>
	</div>
</div>
</body>

</html>