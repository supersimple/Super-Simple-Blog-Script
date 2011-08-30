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
		
	private function getAllImagesByGuid($guid)
	{
		//get an array of blog post images
		$images = array();
		
		foreach($this->xmlstr->post as $post)
		{
			$this_guid = (string)$post->guid;
			if($this_guid === $guid)
			{
					foreach($post->postimage as $postimage)
					{
						$images[] = array(
							'imageid'=>(int)$postimage['imageid'],
							'url'=>(string)$postimage
						);
					}
			}
		}
		
		return $images;
	}
	
	
	private function getAllCommentsByGuid($guid)
	{
		//get an array of blog post comments
		$comments = array();
		
		foreach($this->xmlstr->post as $post)
		{
			$this_guid = (string)$post->guid;
			if($this_guid === $guid)
			{
					foreach($post->comment as $comment)
					{
						$comments[] = array(
							'timestamp'=>(int)$comment['timestamp'],
							'url'=>(string)$comment['url'],
							'name'=>(string)$comment['name'],
							'text'=>(string)$comment
						);
					}
			}
		}
		
		return $comments;
	}
	
	
	//method to get all settings
	public function getAllSettings()
	{
		$settings = array();
		
			$setting = $this->xmlstr;
			$settings['basepath'] = (string)$setting->basepath;
			$settings['baseurl'] = (string)$setting->baseurl;
			$settings['pagetitle'] = (string)$setting->pagetitle;
			$settings['postsperpage'] = (string)$setting->postsperpage;
			$settings['blogpagefilename'] = (string)$setting->blogpagefilename;
			$settings['allowcomments'] = (string)$setting->allowcomments;
			$settings['timeoffsetfromserver'] = (string)$setting->timeoffsetfromserver;
			$settings['rssdescription'] = (string)$setting->rssdescription;
			
			//merge the users
			$settings['users'] = $this->getAllUsers();
		
		return $settings;
	}
	
	
	//method to update settings
	public function updateSettings($settings)
	{
		/*
		<settings>
	<basepath>str</basepath>
	<baseurl>str</baseurl>
	<pagetitle>str</pagetitle>
	<postsperpage>int</postsperpage>
	<blogpagefilename>str</blogpagefilename>
	<user id="int" password="SHA1">str</user>
	<allowcomments>bool</allowcomments>
	<timeoffsetfromserver>int</timeoffsetfromserver>
	<rssdescription>str</rssdescription>
</settings>
*/
		//this method takes an array,containing 1 or more of the settings nodes to be updated
		$setting = $this->xmlstr;
		
		foreach($settings as $node=>$value)
		{
				
		}
		
	}
	
	private function getAllUsers()
	{
		
		//get an array of users
		$users = array();
		
		$setting = $this->xmlstr;
					foreach($setting->user as $user)
					{
						$users[] = array(
							'id'=>(int)$user['id'],
							'password'=>(string)$user['password'],
							'username'=>(string)$user
						);
					}

		return $users;
		
	}
	
	private function getUser($uid)
	{
	
	}
	
	private function addNewUser()
	{
	
	}
	
	private function deleteUser($uid)
	{
	
	}
	
	//method to add a new post to the posts XML
	public function addNewPost($post)
	{
		$xml = $this->xmlstr;

		$newpost = $xml->addChild('post');
		$newpost->addChild('guid', md5(serialize($post)));
		$newpost->addChild('timestamp', time());
		$newpost->addChild('posttitle', '<![CDATA['.$post['posttitle'].']]>');
		$newpost->addChild('posttext', '<![CDATA['.$post['posttext'].']]>');
		$newpost->addChild('postedby', $post['postedby']);
		$newpost->addChild('active', $post['active']);
		
		$i=1;
		foreach($post['postimage'] as $postimage){
			$newpostimage = $newpost->addChild('postimage', $postimage);
			$newpostimage->addAttribute('imageid', $i);
			$i++;
		}
		
		//write the file
		$xml->asXML($this->xmlfile);
	}
	
	public function deletePost($guid)
	{
	
	}
	
	public function editPost($guid,$post)
	{
	
	}
	
	//method to add a comment to the posts XML
	public function addNewComment($guid,$comment)
	{
		$xml = $this->xmlstr;
		
		//look for the post with this guid
		foreach($xml->post as $post)
		{
			if($post->guid == $guid)
			{
				$newcomment = $post->addChild('comment',$comment['text']);
				$newcomment->addAttribute('timestamp',time());
				$newcomment->addAttribute('url',$comment['url']);
				$newcomment->addAttribute('name',$comment['name']);
			}
		}
		
		//write the file
		$xml->asXML($this->xmlfile);
	}
		
}



//Just for testing////////////////////////////////////////////////////
$xmlobj = new XMLParser("posts.xml");

#$xmlobj->addNewPost(array('posttitle'=>htmlentities('Hey, man!'), 'posttext'=>htmlentities('This is so cool & stuff.\r\nAre you serious? "Yes, I am."'), 'postedby'=>1, 'active'=>1, 'postimage' => array('/file/path/images/imagename.jpg')));
$xmlobj->addNewComment('XYZ7890',array('text'=>htmlentities('I think you need to check out http://cnn.com'),'url'=>htmlentities('http://twitter.com&user=heavy_t'),'name'=>htmlentities('Todd Resudek')));

$posts = $xmlobj->getAllPosts();
print_r($posts);

?>