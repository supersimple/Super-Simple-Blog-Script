<?php
	@session_start();
	require_once('class.xml-parser.php');
	if(isset($_POST['submit']))
	{
		$xmlobj = new XMLParser("settings.xml");
		$login_passed = $xmlobj->checkLogin($_POST['username'],$_POST['password']);
		$_SESSION['logged-in'] = $login_passed;
		if($login_passed){ header("Location: manage.php"); }
	}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Super Simple Blog Script V3</title>
<link href="http://fonts.googleapis.com/css?family=Coustard" rel="stylesheet" type="text/css" />
<link href="http://fonts.googleapis.com/css?family=Arvo" rel="stylesheet" type="text/css" />
<link rel="stylesheet" media="all" href="screen.css" type="text/css" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8" src="js/validation.js"></script>
<script type="text/javascript" charset="utf-8" src="js/utils.js"></script>
</head>

<body>
<div id="container" style="text-align:center;">
	<div id="login_container">
		<form action="" method="post" id="login_form" name="login_form" onsubmit="return validateLoginForm();">
			<h3>Login</h3>
			<label for="username">Email Address</label><input type="text" class="txt" name="username" id="username" value="" />
			<div class="hr-clr">&nbsp;</div>
			<label for="password">Password</label><input type="password" class="txt" name="password" id="password" value="" />
			<div class="hr-dot">&nbsp;</div>
			<input class="btn" type="submit" name="submit" value="Submit &raquo;" />
		</form>
	</div>
</div>
</body>

</html>