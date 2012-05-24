<?php
#run this setup only 1 time. YOU MUST SET THE 'sqlStrings.php' FILE FIRST.

//****INCLUDE THE DATABASE CONNECTION AND GLOBAL SETTINGS FILES****************************************	

	require_once("sqlStrings.php");


//****BUILD STRINGS FOR WRITING TO THE SETTINGS.PHP FILE***********************************************


require_once('config_modify.php');


//****HANDLE THE FORM********************************************************************************

	if(isset($_POST['submit'])){
				
		//init an error message
		$_ERRORS = '';
		
		if(!empty($_POST['BASEURL'])){
			$SET0 = $_POST['BASEURL'];
		}else{
			$_ERRORS .= "\nThe BASE URL could not be set";
		}
		
		//**************************************
		
		if(!empty($_POST['PAGETITLE'])){
			$SET1 = $_POST['PAGETITLE'];
		}else{
			$_ERRORS .= "\nYou must set the PAGE TITLE";
		}
		
		//**************************************
		
		if(!empty($_POST['PERPAGE']) && is_numeric($_POST['PERPAGE'])){
			$SET2 = $_POST['PERPAGE'];
		}else{
			$_ERRORS .= "\nYou must set the ENTRIES PER PAGE to an integer";
		}
		
		//**************************************
		
		if(!empty($_POST['BLOGPAGENAME'])){
			$TEMPSTR = $_POST['BLOGPAGENAME'];
			if(substr($TEMPSTR, -4, 4) == '.php'){
				$SET3 = $_POST['BLOGPAGENAME'];
			}else{
				$SET3 = $_POST['BLOGPAGENAME'].'.php';
			}
		}else{
			$_ERRORS .= "\nYou must set the BLOG PAGE NAME";
		}
		
		//**************************************
		
		if(!empty($_POST['USER0'])){
			$SET4 = $_POST['USER0'];
		}else{
			$_ERRORS .= "\nYou must set the USER NAME";
		}
		
		//**************************************
		
		if(!empty($_POST['PASSWORD0'])){
			$SET5 = $_POST['PASSWORD0'];
		}else{
			$_ERRORS .= "\nYou must set the PASSWORD";
		}
		
		//**************************************
		
		if(!empty($_POST['IMAGESPERENTRY']) && is_numeric($_POST['IMAGESPERENTRY'])){
			$SET6 = $_POST['IMAGESPERENTRY'];
		}else{
			$_ERRORS .= "\nYou must set the IMAGES PER ENTRY to an integer";
		}
		
		//**************************************
		
		if(!empty($_POST['MULTIUSERS'])){
			$SET7 = $_POST['MULTIUSERS'];
		}else{
			$_ERRORS .= "\nYou must set the MULTIPLE USERS";
		}
		
		//**************************************
		
		if(!empty($_POST['COMMENT'])){
			$SET8 = $_POST['COMMENT'];
		}else{
			$_ERRORS .= "\nYou must set the USER COMMENTS";
		}
		
		//**************************************
		
		if(!empty($_POST['TABLENAME'])){
			$SET9 = str_replace(' ', '_', $_POST['TABLENAME']);
		}else{
			$_ERRORS .= "\nYou must set the DATABASE TABLE NAME";
		}
		
		//**************************************
		
		if(!empty($_POST['TIMEOFFSET']) && is_numeric($_POST['TIMEOFFSET']) || $_POST['TIMEOFFSET'] == '0'){
			$SET10 = $_POST['TIMEOFFSET'];
		}else{
			$_ERRORS .= "\nYou must set the TIME OFFSET to an integer";
		}
		
		//**************************************
		
			$SET11 = $_POST['url'];
				
		//**************************************
		
		if(!empty($_POST['RSSDESC'])){
			$SET12 = htmlspecialchars($_POST['RSSDESC']);
		}else{
			$_ERRORS .= "\nYou must set the RSS DESCRIPTION";
		}	
		
		//**************************************
		
		$_getThisYear = getdate();
		$THISYEAR = $_getThisYear['year'];
		
		//**************************************
		
		//CHECK FOR ERRORS
		if(!empty($_ERRORS)){
			echo "<span style=\"color:#f21818;\">The following errors have occurred <br />". nl2br($_ERRORS). "<br /><br /></span><p class=\"hr\"></p><br /><br />";	
		}else{ 
				

//****MAKE THE BLOGIMAGES FOLDER***********************************************************************	
	
		 //check to see if it already exists first
		 if(!file_exists("$SET0/blogImages")){
		 mkdir("$SET0/blogImages", 0777) or die('The blogImages folder could not be created. Please create it manually.');
 		 }
 		//now be a little more forceful in setting the permmissions of the new directory 
 		system("chmod -R 777 \"$SET0/blogImages\" ");
 		
//*****************************************************************************************************	


//****MAKE THE COMMENTS FOLDER*************************************************************************	
		
		if(!file_exists("$SET0/comments")){
		 mkdir("$SET0/comments", 0777) or die('The comments folder could not be created. Please create it manually.');
		}
		//now be a little more forceful in setting the permmissions of the new directory 
 		system("chmod -R 777 \"$SET0/comments\" ");

//*****************************************************************************************************	


//****CHANGE THE NAME OF THE BLOG FILE*****************************************************************	
	
		 rename("$SET0/index.php", "$SET0/$SET3") or die("The blog page could not be renamed to $SET3.<br />You are so close, dont give up.<br />Please make sure that the permissions of the file and its folder are '777' and then re-run the setup.");

//*****************************************************************************************************


//****MAKE THE SETTINGS FILE***************************************************************************	
		
//build settings file
$_settingsStr = <<<EOT
<?php
$baseURL = '$SET0'; // set the file where the blog will be installed - no trailing '/'. DOCUMENT_ROOT is a path to your server's root. (eg. /var/www/html/)

$pageTitle = "$SET1"; //set the page title here

$perPage = "$SET2"; //This is where you set the number of posts per page.

$blogPageName = "$SET3"; //this is the file that the blog is displayed in

$USERS = array('$SET4'=>'$SET5'); //array of valid users. Format for arrays is ('user'=>'password',...)

$multiUsers = $SET7; //this will show the username of who wrote each post (true or false)

$imagesPerEntry = "$SET6"; //this is the number of files that you can upload with each entry

$COMMENTS = $SET8; //display/allow comments (true or false)

$TABLENAME = '$SET9'; //name of the database table that holds this blog's data

$TIMEOFFSET = "$SET10"; //the difference between your time, and your server's time

//RSS Settings

$websiteRoot = '$SET11'; //root of your blog (eg. http://www.yoursite.com/blog)

$rssFileName = 'feed.rss'; //this is the name of the RSS file. You must create a blank file and put it into the same directory. Also, set it's permissions to 777.

$rssLink = '$SET11/$SET3';//set the link to your site

$rssDesc = '$SET12';//set a description for what this feed is all about

//CMS Settings
$users_login = array('$SET4'=>'$SET5');

$show_global_nav = true; //true or false will either show or hide the global nav in the CMS pages

$global_nav_items = array('add_blog.php' => 'Add Blog Entry','chooseTable.php' => 'Choose Table'); //assoc array using file names as keys and titles as values



EOT;

$_settingsStr .= '?';
$_settingsStr .= '>';

		
		//****WORK WITH THE FLAT FILE*************************************************************	
			
			if(file_exists("$SET0/settings.php")){
				//make sure that file is writable 
 				system("chmod -R 777 \"$SET0/settings.php\" ");
			}
			
			//open or create a file
			$_settingsFile = fopen("$SET0/settings.php", 'w') or die('Cannot open settings file');
			fwrite($_settingsFile, $_settingsStr) or die('Cannot write to file');
			fclose($_settingsFile);
			
		//****************************************************************************************

//*****************************************************************************************************


//****CREATE THE DATABASE TABLE************************************************************************	
		
		$query = "CREATE TABLE `$SET9` (`comment` mediumtext NOT NULL, `time` timestamp NOT NULL default CURRENT_TIMESTAMP, `image` TEXT NOT NULL default '', `title` varchar(40) NOT NULL default '', `uid` int(4) NOT NULL auto_increment, `comments` int(11) NOT NULL default '0',  `postedBy` varchar(40) NOT NULL default '', PRIMARY KEY  (`uid`))";
		$result = @mysql_query ($query);
	
//*****************************************************************************************************	


//****CHECK THE DATABASE*******************************************************************************	
	
	if ($result) {
	//echo "<p>Setup was successful. Please check the permissions on the blogImages and comments directories. They must be set to '777'.</p>";
	 //redirect to a script that will destroy this file
	header("Location: setup-complete.php?site=$SET11&page=$SET3&path=$SET0");	
	exit;
		
	} else { // If the query did not run OK.
		
		echo '<p><font color="red">Your submission could not be processed due to a system error. We apologize for the inconvenience.</font></p>'; 
		echo '<p><font color="red">This is the error: '. mysql_error().'</p>';
		
		//remove the directory that we created, destroy the table, and clear the settings
		rmdir("$SET0/blogImages");
		rmdir("$SET0/comments");
		
		$_clearFile = fopen("$SET0/settings.php", 'w');
		fclose($_clearFile);
		
		$query = "DROP TABLE '$SET9'";
		$result = @mysql_query($query);
		
		die();
	}

//*****************************************************************************************************	

			
			
		
		} //end error else
	}//end if submit


//*****************************************************************************************************	


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Super Simple Setup</title>
	<style type="text/css" media="all">@import "blogStyles.css";</style>
	<script type="text/javascript">
	window.onload=function(){
	//this function will grab the exact url where the blog is installed
	var pageName = 	window.location.toString();
	var fileStart = pageName.indexOf('/setup.php');
	var rootUrl = pageName.substring(0,fileStart);
	document.install.url.value = rootUrl;
	}
	</script>
</head>
<body>
<form name="install" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
<?php 

//****GET THE BASE URL*********************************************************************************

	//get the path to the current file
	//$_basePath = $_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF'];
	$_basePath = __FILE__;
	
	$_setupFile = strpos($_basePath,'setup.php');
	
	$_baseURL = substr($_basePath, 0, $_setupFile -1);
	
	echo "The install path is: $_baseURL<br /><br />";
	
	echo "<input type=\"hidden\" name=\"BASEURL\" value=\"$_baseURL\" />";
	echo "<p class=\"hr\"></p>";

//*****************************************************************************************************	



//****SET THE PAGE TITLE*******************************************************************************
	
	echo "<br /><br />Enter the page title for your blog: ";
	echo "<input type=\"input\" name=\"PAGETITLE\" value=\"\" style=\"width:250px;\" />";
	echo "<p class=\"hr\"></p>";
	
//*****************************************************************************************************


//****SET THE NUMBER OF ENTRIES PER PAGE***************************************************************
	
	echo "<br /><br />Choose the number of entries to display per page: ";
	echo "<input type=\"input\" name=\"PERPAGE\" value=\"\" style=\"width:50px;\" />";
	echo "<p class=\"hr\"></p>";

//*****************************************************************************************************


//****SET THE NAME OF THE BLOG FILE********************************************************************
	
	echo "<br /><br />Choose the name of the blog file: (eg. index.php) ";
	echo "<input type=\"input\" name=\"BLOGPAGENAME\" value=\"\" style=\"width:100px;\" />.php";
	echo "<p class=\"hr\"></p>";

//*****************************************************************************************************


//****SET THE USERS AND PASSWORDS**********************************************************************
	
	echo "<br /><br />Set the Username and Password for adding blog entries:<br />";
	echo "Username: <input type=\"input\" name=\"USER0\" value=\"\" style=\"width:125px;\" /> Password: <input type=\"password\" name=\"PASSWORD0\" value=\"\" style=\"width:125px;\" />";
	echo "<p class=\"hr\"></p>";

//*****************************************************************************************************


//****SET THE NUMBER OF IMAGES PER ENTRY***************************************************************
	
	echo "<br /><br />Choose the number of images per entry: ";
	echo "<input type=\"input\" name=\"IMAGESPERENTRY\" value=\"\" style=\"width:50px;\" />";
	echo "<p class=\"hr\"></p>";

//*****************************************************************************************************


//****SET MULTIPLE USERS ON/OFF************************************************************************
	
	echo "<br /><br />Allow multiple users:<br />";
	echo "<input type=\"radio\" name=\"MULTIUSERS\" value=\"true\" /> Yes<br />";
	echo "<input type=\"radio\" name=\"MULTIUSERS\" value=\"false\" /> No";
	echo "<p class=\"hr\"></p>";

//*****************************************************************************************************


//****SET COMMENTS ON/OFF******************************************************************************
	
	echo "<br /><br />Allow user comments:<br />";
	echo "<input type=\"radio\" name=\"COMMENT\" value=\"true\" /> Yes<br />";
	echo "<input type=\"radio\" name=\"COMMENT\" value=\"false\" /> No";
	echo "<p class=\"hr\"></p>";

//*****************************************************************************************************


//****SET TABLENAME************************************************************************************
	
	echo "<br /><br />Choose a unique name for this blog&#39;s database table:<br />";
	echo "<input type=\"text\" name=\"TABLENAME\" value=\"entries\" style=\"width:125px;\" />";
	echo "<p class=\"hr\"></p>";

//*****************************************************************************************************


//****SET TIME OFFSET**********************************************************************************
	
	echo "<br /><br />Set the offset of the time between your server&#39;s time and your time (in hours):<br />";
	echo "<input type=\"text\" name=\"TIMEOFFSET\" value=\"0\" style=\"width:50px;\" />";
	echo "<p class=\"hr\"></p>";

//*****************************************************************************************************


//****SET RSS DESCRIPTION******************************************************************************
	
	echo "<br /><br />Enter a description of your RSS feed:<br />";
	echo "<textarea name=\"RSSDESC\" value=\"\" style=\"height:60px\"></textarea>";
	echo "<p class=\"hr\"></p>";

//*****************************************************************************************************


//*****************************************************************************************************
	
	//will hold the absolute url of the blog/rss
	echo "<input type=\"hidden\" name=\"url\" value=\"\" />";

	echo "<br /><input type=\"submit\" name=\"submit\" />";

//*****************************************************************************************************


?>
</form>
</body>
</html>