<?php
error_reporting(0);
class FileToDb extends Controller {
	
	function FileToDb() 
	{
		parent::Controller();
	}
	
	public function takefile()
	{
		$fbp = Doctrine::getTable('FbProcess')->findByStatus('0');
		
		foreach ($fbp as $key => $value) {
			$fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($fbp[$key]->fb_user_id);
			//echo $fbu->id;
			//die();
			$this->index($fbp[$key]->filename,$fbu->id);
			
		}
		//print $s[0]->filename;
		
	}
	
	public function index($strfile,$localuserid)
	{
		$string = read_file(APPPATH.$strfile);
		$friends = unserialize($string);
				
				
			foreach ($friends['data'] as $key => $person) {
									
					
								/*$u = new Friend;
								$u->fb_id = $person['id'];
								$u->fname = $person['first_name'];
								$u->lname = $person['last_name'];
								$u->username = $person['username'];
								$u->gender = $person['gender'];
								$u->save();
								unset($u);*/
								if (strlen($person['birthday'])<10){
								continue;
								}
								$u = new FbUserMaster;
								$u->fb_user_id = $person['id'];
								$u->fname = $person['first_name'];
								$u->lname = $person['last_name'];
								$u->username = $person['username'];
								$u->picture = $person['picture']['data']['url'];
								//$u->picture = $person['picture'];
								
								$u->birthday = $person['birthday'];
							
								
								$u->gender = $person['gender'];
								$u->relationship_status = $person['relationship_status'];
								$u->save();
								unset($u);
								$temp = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($person['id']);
								
								$n = new FbNetwork;
								$n->fb_id = $localuserid;
								$n->ref_fb_id = $temp->id;
								$n->save();
								unset($n);
				}
	}
}
