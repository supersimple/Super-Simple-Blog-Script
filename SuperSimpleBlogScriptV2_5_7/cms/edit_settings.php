<?php

/*********************************************

edit_settings.php

this file will edit selected global constants
for the blog.


*********************************************/

require_once('includes/header.php'); // include global header

if(!isset($submit)){require_once('../settings.php'); /*include global prefs*/}


//****HANDLE FORM*****************************************************************************

	if(isset($submit)){
	
	
//****BUILD STRINGS FOR WRITING TO THE SETTINGS.PHP FILE**************************************


require_once('../config_modify.php');


//********************************************************************************************

				
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
		
		$_origBlogPageName = $_POST['ORIGBLOGPAGENAME'];
		
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
		
		//store the user count
		$_tuc = $_POST['total_user_count'];
		
		$_user_str = 'array(';
		//iterate through all users
		for($i=0; $i<$_tuc;$i++){
			if(!empty($_POST['USER'.$i])){
				if(!empty($_POST['PASSWORD'.$i])){
						
					$_this_new_user = $_POST['USER'.$i];
					$_this_new_password = $_POST['PASSWORD'.$i];
					
					$_user_str .= '\''.$_this_new_user.'\'=>\''.$_this_new_password.'\'';
					
					//add comma after all but last
					if($i != ($_tuc-1)){$_user_str.=',';}else{$_user_str .= ')';}
				}else{
				$_ERRORS .= "\nYou must set the PASSWORD";
				}
				
			}else{
			$_ERRORS .= "\nYou must set the USER NAME";
			}
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
		
		if(!empty($_POST['COMMENTS'])){
			$SET8 = $_POST['COMMENTS'];
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

//****CHANGE THE NAME OF THE BLOG FILE*****************************************************************	
	
		 rename("$SET0/$_origBlogPageName", "$SET0/$SET3") or die("The blog page could not be renamed to $SET3. Please rename it manually");

//*****************************************************************************************************


//****MAKE THE SETTINGS FILE***************************************************************************	
		
//build settings file
$_settingsStr = <<<EOT
<?php
$baseURL = '$SET0'; // set the file where the blog will be installed - no trailing '/'. DOCUMENT_ROOT is a path to your server's root. (eg. /var/www/html/)

$pageTitle = "$SET1"; //set the page title here

$perPage = "$SET2"; //This is where you set the number of posts per page.

$blogPageName = "$SET3"; //this is the file that the blog is displayed in

$USERS = $_user_str; //array of valid users. Format for arrays is ('user'=>'password',...)

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
$users_login = $_user_str; //array of valid users. Format for arrays is ('user'=>'password',...)

$show_global_nav = true; //true or false will either show or hide the global nav in the CMS pages

$global_nav_items = array('add_blog.php' => 'Add Blog Entry','chooseTable.php' => 'Choose Table'); //assoc array using file names as keys and titles as values



EOT;

$_settingsStr .= '?';
$_settingsStr .= '>';

		
		//****WORK WITH THE FLAT FILE*************************************************************	
			
			//open or create a file 
			$_settingsFile = fopen("$SET0/settings.php", 'w') or die('Cannot open settings file');
			fwrite($_settingsFile, $_settingsStr) or die('Cannot write to file');
			fclose($_settingsFile);
			
		//****************************************************************************************

//****REDIRECT TO SUCCESS PAGE********************************************************************	

echo "Your settings were successfully modified.<br /><a href=\"$SET11/$SET3\">Go to home page</a></table></div></div></body></html>"; 
die();

//************************************************************************************************


}//end else error
}//end if submit
//*****************************************************************************************************

//********************************************************************************************

?>
</table>
<script type="text/javascript">
	window.onload=function(){
	//this function will grab the exact url where the blog is installed
	var pageName = 	window.location.toString();
	var fileStart = pageName.indexOf('/cms');
	var rootUrl = pageName.substring(0,fileStart);
	document.edit_config.url.value = rootUrl;
	}
</script>
<form name="edit_config" method="post" action="<?=$_SERVER['PHP_SELF']?>">

<?php 

//****GET THE BASE URL*********************************************************************************

	//get the path to the current file
	$_baseURL = $_SERVER['DOCUMENT_ROOT'];
	
	
	echo "The install path is: $_baseURL<br /><br />";
	echo "<input type=\"hidden\" name=\"BASEURL\" value=\"$_baseURL\" />";
	echo "<p class=\"hr\"></p>";

//*****************************************************************************************************	



//****SET THE PAGE TITLE*******************************************************************************
	
	echo "<br /><br />Enter the page title for your blog: ";
	echo "<input type=\"input\" name=\"PAGETITLE\" value=\"$pageTitle\" style=\"width:250px;\" />";
	echo "<p class=\"hr\"></p>";
	
//*****************************************************************************************************


//****SET THE NUMBER OF ENTRIES PER PAGE***************************************************************
	
	echo "<br /><br />Choose the number of entries to display per page: ";
	echo "<input type=\"input\" name=\"PERPAGE\" value=\"$perPage\" style=\"width:50px;\" />";
	echo "<p class=\"hr\"></p>";

//*****************************************************************************************************


//****SET THE NAME OF THE BLOG FILE********************************************************************
	
	echo "<br /><br />Choose the name of the blog file: (eg. index.php) ";
	echo "<input type=\"hidden\" name=\"ORIGBLOGPAGENAME\" value=\"$blogPageName\" />";
	echo "<input type=\"input\" name=\"BLOGPAGENAME\" value=\"$blogPageName\" style=\"width:100px;\" />.php";
	echo "<p class=\"hr\"></p>";

//*****************************************************************************************************


//****SET THE USERS AND PASSWORDS**********************************************************************
	
	echo "<br /><br />Set the Username and Password for adding blog entries:<br />";
	
	//iterate through users
	$it = 0;
	
	foreach($USERS as $key=>$value){
	echo "Username: <input type=\"input\" name=\"USER$it\" value=\"$key\" style=\"width:125px;\" /> Password: <input type=\"password\" name=\"PASSWORD$it\" value=\"$value\" style=\"width:125px;\" /><br />";
	$it++;
	}
	
	echo "\n<input type=\"hidden\" name=\"total_user_count\" value=\"$it\" />";
	
	echo "<a href=\"add_user.php\" target=\"_self\">Add User</a>";
	
	echo "<p class=\"hr\"></p>";

//*****************************************************************************************************


//****SET THE NUMBER OF IMAGES PER ENTRY***************************************************************
	
	echo "<br /><br />Choose the number of images per entry: ";
	echo "<input type=\"input\" name=\"IMAGESPERENTRY\" value=\"$imagesPerEntry\" style=\"width:50px;\" />";
	echo "<p class=\"hr\"></p>";

//*****************************************************************************************************


//****SET MULTIPLE USERS ON/OFF************************************************************************
	
	echo "<br /><br />Allow multiple users:<br />";
	echo "<input type=\"radio\" name=\"MULTIUSERS\" value=\"true\""; 
		if($MULTIUSERS){echo ' checked ';} 
	echo "/> Yes<br />";
	
	echo "<input type=\"radio\" name=\"MULTIUSERS\" value=\"false\""; 
		if(!$MULTIUSERS){echo ' checked ';} 
	echo "/> No";
	echo "<p class=\"hr\"></p>";

//*****************************************************************************************************


//****SET COMMENTS ON/OFF******************************************************************************
	
	echo "<br /><br />Allow user comments:<br />";
	echo "<input type=\"radio\" name=\"COMMENTS\" value=\"true\""; 
		if($COMMENTS){echo ' checked ';} 
	echo "/> Yes<br />";
	
	echo "<input type=\"radio\" name=\"COMMENTS\" value=\"false\""; 
		if(!$COMMENTS){echo ' checked ';} 
	echo "/> No";
	echo "<p class=\"hr\"></p>";

//*****************************************************************************************************


//****SET TABLENAME************************************************************************************
	
	echo "<br /><br />Choose a unique name for this blog&#39;s database table:<br />";
	echo "<input type=\"text\" name=\"TABLENAME\" value=\"$TABLENAME\" style=\"width:125px;\" />";
	echo "<p class=\"hr\"></p>";

//*****************************************************************************************************


//****SET TIME OFFSET**********************************************************************************
	
	echo "<br /><br />Set the offset of the time between your server&#39;s time and your time (in hours):<br />";
	echo "<input type=\"text\" name=\"TIMEOFFSET\" value=\"$TIMEOFFSET\" style=\"width:50px;\" />";
	echo "<p class=\"hr\"></p>";

//*****************************************************************************************************


//****SET RSS DESCRIPTION******************************************************************************
	
	echo "<br /><br />Enter a description of your RSS feed:<br />";
	echo "<textarea name=\"RSSDESC\" style=\"height:60px\">$rssDesc</textarea>";
	echo "<p class=\"hr\"></p>";

//*****************************************************************************************************


//*****************************************************************************************************
	
	//will hold the absolute url of the blog/rss
	echo "<input type=\"hidden\" name=\"url\" value=\"\" />";

	echo "<br /><input type=\"submit\" name=\"submit\" />";

//*****************************************************************************************************


?>
</form>

</div></div></body></html>
