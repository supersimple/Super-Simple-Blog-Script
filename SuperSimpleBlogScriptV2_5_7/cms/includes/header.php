<?php
session_start();
//build a session name
$_session_name = md5($_SERVER['PHP_SELF'].'supersimple'.microtime());
session_name($_session_name);



if(!session_is_registered('passed')){
 header("Location: login.php?{$_SERVER['PHP_SELF']}?{$_SERVER['QUERY_STRING']}");
 die();
 }
 


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
 	<title><?=$pageTitle?></title>
 	<style type="text/css" media="all">@import "../blogStyles.css";</style>
	<script type="text/javascript" src="js/inits.js"></script>
</head>
<body>
<div id="container">
<div id="content">
<?
require_once('includes/nav.php'); // include nav file
?>
<table border="0" cellpadding="0" cellspacing="0">

<!--////////////////////////////////////////////////

Super Simple CMS Script

Written by: Todd A Resudek (todd@tr1design.net)
for Super Simple - http://www.supersimple.org.

Open source license. Feel free to use this script
on any sites, please leave this message intact.

///////////////////////////////////////////////////-->

