<?php
/*******************************************************************************/
//
// Protects pages from being viewed without being logged in
//
/*******************************************************************************/
@session_start();

if(!$_SESSION['logged-in']){
	$req_url = $_SERVER['PHP_SELF'];
	if(!empty($_SERVER['QUERY_STRING'])){ $req_url .= '?'.$_SERVER['QUERY_STRING'];}
	$_SESSION['req_url'] = $req_url;
	header("Location: logout.php");
 	die();
}
?>