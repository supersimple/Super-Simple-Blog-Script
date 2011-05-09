<?php
/********************************************

feed_maker.php

This class is used for generating an RSS 
feed.

********************************************/

class GenerateFeed{

var $TABLENAME;
var $websiteRoot;
var $pageTitle;
var $rssDesc;
var $rssLink;
var $rssFileName;
var $blogPageName;

	function makeFeed($table, $webroot, $title, $desc, $link, $rssfile){
		
		$this->TABLENAME = $table;
		$this->websiteRoot = $webroot;
		$this->pageTitle = $title;
		$this->rssDesc = $desc;
		$this->rssLink = $link;
		$this->rssFileName = $rssfile;
		
		//empty array which will be pushed all of the 
		//possible images for the home page. 
		//These are displayed when the page loads.
		$spl = array();
		
		// Query the database.
		$query = "SELECT * FROM $this->TABLENAME ORDER BY uid DESC";
		$result = mysql_query ($query);
		
		//init a counter
		$i = 0;

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

		$spl[$i] = $row;
		$i++;

		$ima = split(", ", $row['image']); //build array out of images list
		$com = nl2br($row['comment']);

		//if there is more than 1 image per entry
		for($h=0; $h<count($ima); $h++){
			$j = $h+1;
				if($imagesPerEntry != 1){
					if(substr($ima[$h],-4) != '.pdf'){ //filter out PDFs
					$com = str_replace("[$j]", "<img src=\"$this->websiteRoot/blogImages/$ima[$h]\" />", $com);
					}
				}

				if($imagesPerEntry == 1){
					if(!empty($ima[$h])) { 
						if(substr($ima[$h],-4) != '.pdf'){ //filter out PDFs
							$com = str_replace("[$j]", "<img src=\"$this->websiteRoot/blogImages/$ima[$h]\" />", $com);
						}	
					}
				}
		}
		//output the database
		//each row will be 1 item
		$addBlock .= "\n\n<item>
		\n
		<title>{$row['title']}</title>
		\n
		<link>$this->rssLink?entry={$row['uid']}</link>
		\n
		<description><![CDATA[$com]]></description>
		\n
		</item>\n\n";
		}


// Write the three blocks to file
  //this is the file that we are writing	
  $fileT = $this->rssFileName;
  $fileT = fopen($fileT, "w");
	
	//start off every feed with this	
	$startBlock = <<<EOR
<?xml  version="1.0"?> 
	<rss version="2.0">
	<channel>
	<title>$this->pageTitle</title>
	<description>$this->rssDesc</description>
	<link>$this->rssLink</link>
EOR;

	//end every feed with this
	$endBlock = "</channel>\n\n</rss>";
	

  fwrite($fileT, $startBlock); 

  fwrite($fileT, $addBlock);

  fwrite($fileT, $endBlock);

  fclose($fileT);
  
  

//end of method	
}
//end of class
}


  
  



?>
