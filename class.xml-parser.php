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
	
	//method to get one setting
	public function getSetting($name)
	{
			$settings = $this->xmlstr;
			$setting = (string)$settings->$name;

		return $setting;
	}
	
	
	//method to update settings
	public function updateSettings($settings,$first_run=false)
	{
		//this method takes an array,containing 1 or more of the settings nodes to be updated
		$xml = $this->xmlstr;

		if(isset($settings['basepath'])){ $xml->basepath = $settings['basepath']; }
		if(isset($settings['baseurl'])){ $xml->baseurl = $settings['baseurl']; }
		if(isset($settings['pagetitle'])){ $xml->pagetitle = $settings['pagetitle']; }
		if(isset($settings['postsperpage'])){ $xml->postsperpage = $settings['postsperpage']; }
		if(isset($settings['blogpagefilename'])){ $xml->blogpagefilename = $settings['blogpagefilename']; }
		if(isset($settings['allowcomments'])){ $xml->allowcomments = $settings['allowcomments']; }
		if(isset($settings['timeoffsetfromserver'])){ $xml->timeoffsetfromserver = $settings['timeoffsetfromserver']; }
		if(isset($settings['rssdescription'])){ $xml->rssdescription = $settings['rssdescription']; }
		
		if($first_run)
		{
			$this->deleteAllUsers();
		}
		
		if(isset($settings['username']))
		{ 
			$user = array();
			$user['name']=$settings['username'];
			$user['id']=md5($settings['username']);
			$user['password']=sha1($settings['password']);
			
			$this->addNewUser($user);
		}
		
		$xml->asXML($this->xmlfile);
	}
	
	private function getAllUsers()
	{
		
		//get an array of users
		$users = array();
		
		$xml = $this->xmlstr;
					foreach($xml->user as $user)
					{
						$users[] = array(
							'id'=>(int)$user['id'],
							'password'=>(string)$user['password'],
							'username'=>(string)$user
						);
					}

		return $users;
		
	}
	
	public function getUser($uid)
	{
		$xml = $this->xmlstr;
					foreach($xml->user as $user)
					{
						if((int)$user['id'] == $uid)
						{
							return array(
								'id'=>(int)$user['id'],
								'password'=>(string)$user['password'],
								'username'=>(string)$user
							);
						}
					}
	}

	public function deleteAllUsers()
	{
		$xml = $this->xmlstr;
		unset($xml->user);
		
		//write the file
		$xml->asXML($this->xmlfile);
	}
		
	public function addNewUser($user)
	{
		$xml = $this->xmlstr;
		
		$newuser = $xml->addChild('user',$user['name']);
		$newuser->addAttribute('id',$user['id']);
		$newuser->addAttribute('password',$user['password']);
		
		//write the file
		$xml->asXML($this->xmlfile);
	}
	
	public function deleteUser($uid)
	{
		//make sure this isnt the only user - we need to always have 1 user
		$xml = $this->xmlstr;
		
		if(count($xml->user) > 1)
		{
			
			for($u=0;$u<count($xml->user);$u++)
			{
				if($xml->user[$u]['id'] == $uid)
				{
					unset($xml->user[$u]);
					return true;
				}
			}
				
		}else{
			return false;
		}
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
		$xml = $this->xmlstr;
		
		for($p=0;$p<count($xml->post);$p++)
		{
			if((string)$xml->post[$p]->guid == $guid)
			{
				unset($xml->post[$p]);
			}
		}
		
		//write the file
		$xml->asXML($this->xmlfile);
		
	}
	
	public function editPost($guid,$postinfo)
	{
		$xml = $this->xmlstr;
		
		foreach($xml->post as $post)
		{
			if((string)$post->guid == $guid)
			{
				if(isset($postinfo['posttitle'])){ $post->posttitle = '<![CDATA['.$postinfo['posttitle'].']]>'; }
				if(isset($postinfo['posttext'])){ $post->posttext = '<![CDATA['.$postinfo['posttext'].']]>'; }
				if(isset($postinfo['active'])){ $post->active = $postinfo['active']; }
				
				//see if any of the post images were removed
				for($i=0;$i<count($post->postimage);$i++)
				{
					$postimage = $post->postimage[$i];
					$this_image_id = $postimage['imageid'];
					if(!array_key_exists($this_image_id,$postinfo['postimages']))
					{
						//delete this postimage
						unset($postimage);
					}
				}
				
				foreach($postinfo['postimages'] as $imageid=>$postimage)
				{
					$newpostimage = $post->addChild('postimage', $postimage);
					$newpostimage->addAttribute('imageid', $imageid);
				}
			}
		}
				
		//write the file
		$xml->asXML($this->xmlfile);
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
		


	//method to delete a comment from the posts XML
	public function deleteComment($guid,$timestamp)
	{
		$xml = $this->xmlstr;
		
		//look for the post with this guid
		foreach($xml->post as $post)
		{
			if($post->guid == $guid)
			{
				//find the comment in here
				for($c=0;$c<count($post->comment);$c++)
				{
					if($post->comment[$c]['timestamp'] == $timestamp){
						unset($post->comment[$c]);
						continue 2;
					}
				}
			}
		}
		
		//write the file
		$xml->asXML($this->xmlfile);
	}
	
	
	/* LOGIN METHODS */
	public function checkLogin($user,$pwd)
	{
		
		$xml = $this->xmlstr;
			
		for($u=0;$u<count($xml->user);$u++)
		{
			if($xml->user[$u] == $user && $xml->user[$u]['password'] == sha1($pwd))
			{
				return true;
			}
		}
				
		return false;
	}

} /* END OF CLASS */

//Just for testing////////////////////////////////////////////////////
$xmlobj = new XMLParser("settings.xml");

#$xmlobj->addNewPost(array('posttitle'=>htmlentities('Hey, man!'), 'posttext'=>htmlentities('This is so cool & stuff.\r\nAre you serious? "Yes, I am."'), 'postedby'=>1, 'active'=>1, 'postimage' => array('/file/path/images/imagename.jpg')));
#$xmlobj->addNewComment('XYZ7890',array('text'=>htmlentities('I think you need to check out http://cnn.com'),'url'=>htmlentities('http://twitter.com&user=heavy_t'),'name'=>htmlentities('Todd Resudek')));

#$xmlobj->updateSettings(array('basepath'=>'/users/var/www/'));
#print_r($xmlobj->getUser(987));

#$xmlobj->deletePost('835ec42cb6db64ded93835d545007e04');

#$posts = $xmlobj->getAllPosts();
#print_r($posts);

?>