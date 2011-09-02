<?php
	require_once("inc.protect.php");
	require_once('class.xml-parser.php');
	$xmlobj = new XMLParser("settings.xml");
	
	$pagetitle = ($xmlobj->getSetting('pagetitle')) ? $xmlobj->getSetting('pagetitle') : 'Super Simple Blog V3 Admin';	
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php echo $pagetitle; ?></title>
<link href="http://fonts.googleapis.com/css?family=Coustard" rel="stylesheet" type="text/css" />
<link href="http://fonts.googleapis.com/css?family=Arvo" rel="stylesheet" type="text/css" />
<link rel="stylesheet" media="all" href="screen.css" type="text/css" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8" src="js/validation.js"></script>
<script type="text/javascript" charset="utf-8" src="js/utils.js"></script>


</head>

<body>
<div id="container">
	<div id="header">
		<h1><?php echo $pagetitle; ?></h1>
	</div>
	<div class="hr-line">&nbsp;</div>

<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" id="manage_form" name="manage_form" onsubmit="return validateManageForm();">
	<input type="hidden" name="guid" value="" />
	<label for="posttitle">Title</label><input type="text" class="txt" name="posttitle" id="posttitle" value="<?php if(isset($blogpost['posttitle'])){ echo $blogpost['posttitle']; } ?>" />
	<div class="hr-clr">&nbsp;</div>
	<label for="posttext">Body</label><textarea class="txa" name="posttext" id="posttext"><?php if(isset($blogpost['posttext'])){ echo $blogpost['posttext']; } ?></textarea>
	<div class="hr-clr">&nbsp;</div>
	<label for="active">Active ?</label><select class="sel" name="active" id="active">
		<?php
			if(!isset($blogpost['active']))
			{
				$blogpost['active'] = 1;
			}
			foreach(array('no','yes') as $key=>$value)
			{
				echo '<option value="'.$key.'" ';
					if($blogpost['active'] == $key){ echo 'selected'; }
				echo '>'.$value.'</option>';
			}
		?>
	</select>
	<div class="hr-clr">&nbsp;</div>
	
	<div class="hr-line">&nbsp;</div>
	<input class="btn" type="submit" name="submit" value="Submit &raquo;" style="margin-right:142px;" />
</form>
</div>
</body>

</html>