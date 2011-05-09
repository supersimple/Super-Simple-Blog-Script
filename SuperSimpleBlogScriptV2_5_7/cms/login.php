<?php	
//include the global settings
require_once('../settings.php');


//hold the target url
$_refURL = $_SERVER['QUERY_STRING'];
//if the query string is empty, use chooseTable
if(empty($_refURL)){$_refURL = 'chooseTable.php';}


if(isset($_POST['submit'])){

//reset the target url
if(isset($_POST['ref'])){$_refURL = $_POST['ref'];}

//init the errors
$err = "the following errors occurred:\n";

	
if(!empty($_POST['user'])){
$_un = $_POST['user']; 
}else{
$err .= "Username was not set.\n";
}

if(!empty($_POST['pwd'])){
$_pw = $_POST['pwd'];
}else{
$err .= "Password was not set.\n";
}

//check for match
if(array_key_exists($_un, $users_login)){
	if($users_login[$_un] == $_pw){
		session_register('passed');
		header("Location: $_refURL");
	}else{
		$error= 'The password was not correct. Please try again.';
	}
}else{
		$error= 'The username and/or password was not correct. Please try again.';

}


}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 	<title><?=$page_title?></title>
 	<style type="text/css" media="all">@import "../blogStyles.css";</style>
	<script type="text/javascript" src="js/inits.js"></script>
</head>
<body>
<div id="container">
<div id="content">

<!--////////////////////////////////////////////////
 
Super Simple CMS Script

Written by: Todd A Resudek (todd@tr1design.net)
for Super Simple - http://www.supersimple.org.

Open source license. Feel free to use this script
on any sites, please leave this message intact.

///////////////////////////////////////////////////-->
<form action="<?=$_SERVER['PHP_SELF']?>" method="POST" >
<fieldset style="width:300px; margin:0 auto; text-align:center; background-color:#e2e2e2; border:1px solid #666;">
<legend style="background-color:#e2e2e2; border:1px solid #666;">LOGIN</legend>
<input type="hidden" name="ref" value="<?=$_refURL ?>" />
USERNAME: <input type="text" name="user" /><br />
PASSWORD: <input type="password" name="pwd" />
<br />
<input type="submit" name="submit" value="Submit"/><br />
<?=$error ?>
</fieldset>
</form>

</div>
</div>
</body>
</html>