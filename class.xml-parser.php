<?php

/***************************************************************************

	Class XMLParser
	
	Author: Todd Resudek <supersimple.org>
	Written: 08/26/2011
	Description:
	Class reads and parses XML data files. Requires XML formatted file in
	constructor.
	
	Known Issues: none
	Version 1.0

***************************************************************************/

Class XMLParser
{
	
	public $xmlfile;
	public $xmlstr;
	
	function __construct($xmlfile)
	{
		if($xmlfile)
		{
			$this->xmlfile = $xmlfile;
			$this->xmlstr = $this->parseXMLToString();
		}
		else
		{
			return false;
		}
	}
	
	
	//method to read XML file and store as a string
	public function parseXMLToString()
	{
		$fh = fopen($this->xmlfile, "r");
		$xmlstr = fread($fh,filesize($this->xmlfile));
		fclose($fh);
		return $xmlstr;
	}
	
}


$xmlobj = new XMLParser("settings.xml");

$xml = new SimpleXMLElement($xmlobj->xmlstr);
foreach($xml as $node){
	foreach($node[0]->attributes() as $n=>$a){
		echo "$n:$a";
	}
}

?>