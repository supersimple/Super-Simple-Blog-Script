<?
/***CLASSES***/
/********************************************

cmsClasses.php

This class is used for getting information
about a table.

********************************************/
class getColNames{
	var $table_name;
	
	function listColNames($table){
		$this->table_name = $table;
		
		$fieldFromTable = array();
		
		$query = "SHOW COLUMNS FROM $this->table_name";//builds list of project for nav
		$result = mysql_query ($query);
		while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
		$fieldFromTable[]=$row['Field'];
	}
		
		return $fieldFromTable;
	}
}

/*********/
?>