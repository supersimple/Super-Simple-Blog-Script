<?php
#this is the global nav for the CMS


if(isset($_GET['table'])){
	$table_name = $_GET['table'];
}

//check if the global nav visibility is turned on
if($show_global_nav){
	
	//open the nav element
	echo "<ul id=\"cmsNav\">";
	
	//display each of the nav items
	foreach($global_nav_items as $key => $value){
		echo "<li><a href=\"$key\">$value</a></li>";
	}
	
	//close the nav element
	echo "</ul>";

}
	
?>
