<?php

/*********************************************

add_user.php

this file will add a user to the global 
constants list.


*********************************************/

require_once('includes/header.php'); // include global header
require_once('../settings.php');

if(isset($submit)){
	

//**************************************
		
		//store the user count
		$_tuc = count($USERS);
		$_userAdd = $_POST['userAdd'];
		$_pwdAdd = $_POST['pwdAdd'];
		
			$USERS[$_userAdd] = $_pwdAdd;
			
			
			
			//build a string for writing to config file
			
			$user_str = 'array(';
			
			foreach($USERS as $key=>$value){
				$user_str .= '\''.$key.'\'=>\''.$value.'\',';		
			}
				//get rid of that last comma
				$user_str = substr($user_str, 0, strlen($user_str)-1);
		
//**************************************


	//****BUILD STRINGS FOR WRITING TO THE SETTINGS.PHP FILE***********************************************

$baseURL = "$baseURL";
$pageTitle = "'$pageTitle'";
$perPage = "'$perPage'";
$blogPageName = "'$blogPageName'";
$USERS = $user_str.')';
$multiUsers = "'$MULTIUSERS'";
$imagesPerEntry = "'$imagesPerEntry'";
$COMMENTS = "'$COMMENTS'";
$TABLENAME = "'$TABLENAME'";
$TIMEOFFSET = "'$TIMEOFFSET'";

//RSS Settings

$websiteRoot = "'$websiteRoot'";
$rssFileName = "'$rssFileName'";
$rssLink = "'$rssLink'";
$rssDesc = "'$rssDesc'";

//CMS Settings

$users_login = $user_str.')';
$show_global_nav = "'$show_global_nav'";
$global_nav_items = "'$global_nav_items'";



	//****BUILD STRINGS FOR WRITING TO THE SETTINGS.PHP FILE***********************************************

$baseURLw = '$baseURL';
$pageTitlew = '$pageTitle';
$perPagew = '$perPage';
$blogPageNamew = '$blogPageName';
$USERSw = '$USERS';
$multiUsersw = '$MULTIUSERS';
$imagesPerEntryw = '$imagesPerEntry';
$COMMENTSw = '$COMMENTS';
$TABLENAMEw = '$TABLENAME';
$TIMEOFFSETw = '$TIMEOFFSET';

//RSS Settings

$websiteRootw = '$websiteRoot';
$rssFileNamew = '$rssFileName';
$rssLinkw = '$rssLink';
$rssDescw = '$rssDesc';

//CMS Settings

$users_loginw = '$users_login';
$show_global_navw = '$show_global_nav';
$global_nav_itemsw = '$global_nav_items';


//build settings file
$_settingsStr = <<<EOT
<?php
$baseURLw = '$baseURL'; // set the file where the blog will be installed - no trailing '/'. DOCUMENT_ROOT is a path to your server's root. (eg. /var/www/html/)

$pageTitlew = $pageTitle; //set the page title here

$perPagew = $perPage; //This is where you set the number of posts per page.

$blogPageNamew = $blogPageName; //this is the file that the blog is displayed in

$USERSw = $USERS; //array of valid users. Format for arrays is ('user'=>'password',...)

$multiUsersw = $multiUsers; //this will show the username of who wrote each post (true or false)

$imagesPerEntryw = $imagesPerEntry; //this is the number of files that you can upload with each entry

$COMMENTSw = $COMMENTS; //display/allow comments (true or false)

$TABLENAMEw = $TABLENAME; //name of the database table that holds this blog's data

$TIMEOFFSETw = $TIMEOFFSET; //the difference between your time, and your server's time

//RSS Settings

$websiteRootw = $websiteRoot; //root of your blog (eg. http://www.yoursite.com/blog)

$rssFileNamew = $rssFileName; //this is the name of the RSS file. You must create a blank file and put it into the same directory. Also, set it's permissions to 777.

$rssLinkw = $rssLink;//set the link to your site

$rssDescw = $rssDesc;//set a description for what this feed is all about

//CMS Settings
$users_loginw = $USERS; //array of valid users. Format for arrays is ('user'=>'password',...)

$show_global_navw = $show_global_nav; //true or false will either show or hide the global nav in the CMS pages

$global_nav_itemsw = array('add_blog.php' => 'Add Blog Entry','chooseTable.php' => 'Choose Table'); //assoc array using file names as keys and titles as values



EOT;

$_settingsStr .= '?';
$_settingsStr .= '>';


		
		//****WORK WITH THE FLAT FILE*************************************************************	
			
			//open or create a file 
			$_settingsFile = fopen("$baseURL/settings.php", 'w') or die('Cannot open settings file');
			fwrite($_settingsFile, $_settingsStr) or die('Cannot write to file');
			fclose($_settingsFile);
			
		//****************************************************************************************

//****REDIRECT TO SUCCESS PAGE********************************************************************	

echo "Your settings were successfully modified.<br /><a href=\"$SET11/$SET3\">Go to home page</a></table></div></div></body></html>"; 
die();

//************************************************************************************************



}
?>
</table>
<form name="add_user" method="post" action="<?=$_SERVER['PHP_SELF']?>">
<fieldset style="width:300px; margin:0 auto; text-align:center; background-color:#e2e2e2; border:1px solid #666;">
<legend style="background-color:#e2e2e2; border:1px solid #666;">Add User</legend>
<br />
USERNAME: <input type="text" name="userAdd" /><br /><br />
PASSWORD: <input type="password" name="pwdAdd" />
<br /><br />
<input type="submit" name="submit" value="Add User"/>
</fieldset>
</form>

</div></div></body></html>
 