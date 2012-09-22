<?php

class FileToDb extends Controller {
	
	function FileToDb() 
	{
		parent::Controller();
	}
	
	public function index()
	{
		$string = read_file(APPPATH."palash.s.ray-friendlist2012-09-22");
		$friends = unserialize($string);
		
		/*echo "<pre>";
				print_r($friends['data']);
				echo "</pre>";*/
		
				
			foreach ($friends['data'] as $key => $person) {
					
								
								/*$u = new Friend;
								$u->fb_id = $person['id'];
								$u->fname = $person['first_name'];
								$u->lname = $person['last_name'];
								$u->username = $person['username'];
								$u->gender = $person['gender'];
								$u->save();
								unset($u);*/
								$u = new FbUserMaster;
								$u->fb_user_id = $person['id'];
								$u->fname = $person['first_name'];
								$u->lname = $person['last_name'];
								$u->username = $person['username'];
								$u->picture = $person['picture'];
								$u->birthday = $person['birthday'];
								$u->gender = $person['gender'];
								$u->save();
								unset($u);
				
				}
	}
}
