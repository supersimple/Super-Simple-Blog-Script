<?php
ini_set('display_errors','on');
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
	public $xmlarray;
	
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
		$xmlstr = simplexml_load_file($this->xmlfile);
		return $xmlstr;
	}

	//method to get all posts
	public function getAllPosts()
	{
		$posts = array();
		
		foreach($this->xmlstr->post as $post){
			$this_guid = (string)$post->guid;
			$posts[$this_guid] = array();
			
			$posts[$this_guid]['guid'] = (string)$post->guid;
			$posts[$this_guid]['timestamp'] = (int)$post->timestamp;
			$posts[$this_guid]['posttitle'] = (string)$post->posttitle;
			$posts[$this_guid]['posttext'] = (string)$post->posttext;
			$posts[$this_guid]['postedby'] = (int)$post->postedby;
			$posts[$this_guid]['active'] = (int)$post->active;
			
			//merge the comments and images for each post
			$posts[$this_guid]['images'] = $this->getAllImagesByGuid($this_guid);
			$posts[$this_guid]['comments'] = $this->getAllCommentsByGuid($this_guid);
			
		}
		
		return $posts;
	}		
		
	public function getAllImagesByGuid($guid){
		//get an array of blog post images
		$images = array();
		
		foreach($this->xmlstr->post as $post){
			$this_guid = (string)$post->guid;
			if($this_guid === $guid){
					foreach($post->postimage as $postimage){
						$images[] = array(
							'imageid'=>(int)$postimage['imageid'],
							'url'=>(string)$postimage
						);
					}
			}
		}
		
		return $images;
	}
	
	
	public function getAllCommentsByGuid($guid){
		//get an array of blog post comments
		$comments = array();
		
		foreach($this->xmlstr->post as $post){
			$this_guid = (string)$post->guid;
			if($this_guid === $guid){
					foreach($post->comment as $comment){
						$comments[] = array(
							'timestamp'=>(string)$comment['timestamp'],
							'url'=>(string)$comment['url'],
							'name'=>(string)$comment['name'],
							'text'=>(string)$comment
						);
					}
			}
		}
		
		return $comments;
	}
	
	
}



//Just for testing////////////////////////////////////////////////////
$xmlobj = new XMLParser("posts.xml");
#print_r($xmlobj->xmlarray);

$posts = $xmlobj->getAllPosts();
print_r($posts);

#$comments = $xmlobj->getAllComments();
#print_r($comments);
/*
$xml = new SimpleXMLElement($xmlobj->xmlstr);
print_r($xml);
foreach($xml as $node){
	foreach($node[0]->attributes() as $n=>$a){
		echo "$n:$a";
	}
}

foreach($xml as $nodek=>$nodev){
		echo $nodek.':'.$nodev."\n";
	}
*/
?>