<?php
//require 'facebook/facebook.php';
error_reporting(0);
class Facebooker extends Controller
{

 function Facebooker()
 {
   parent::Controller();
   $this->load->helper('url');
   $this->load->library('facebook/facebook');
   //$this->load->library('database');
 }

 function index()
 {   //testpala
 	//api_key = 118878424929011
	//secret_key = da77afeb6272bd1fe1fdab3b8cacab15
   
   //$data['api_key'] = "366585186712793";palash
   //$data['secret_key'] = "6144b6eef34ef72d1b3e5420a27ce94c";
  
   
   $facebook = new Facebook(array('appId' => '118878424929011', 'secret' => 'da77afeb6272bd1fe1fdab3b8cacab15'));





// Get User ID
$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me?fields=first_name,gender,id,birthday,last_name,username,relationship_status,picture');

	$friends = $facebook->api('/me/friends?fields=first_name,gender,id,birthday,last_name,username,relationship_status,picture');
		

  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl= $facebook->getLogoutUrl();
} else {
  $loginUrl = $facebook->getLoginUrl();
}

if ($user):
echo "<a href='$logoutUrl'>Logout</a>";
else:
echo "<a href='$loginUrl'>Login with Facebook</a>";
endif;




/*
echo "<pre>";
print_r($friends);
echo "</pre>";

die();*/






if ($user){
$ufile = APPPATH.$user_profile[username].date("Y-m-d");
$ffile = APPPATH.$user_profile[username]."-friendlist".date("Y-m-d");
$udata = serialize($user_profile);
$fdata = serialize($friends);
$string = write_file($ufile,$udata,'w+');
$string = write_file($ffile,$fdata,'w+');

		$fbpu = Doctrine::getTable('FbProcess')->findOneByFb_user_id($user_profile['id']);	
		
		if($fbpu->filename!=''){
			$fbpu->filename = $user_profile[username]."-friendlist".date("Y-m-d");
			$fbpu->save();
			
		//echo "if excu";
		//die();
		} else{
			
		$fbp = new FbProcess;
		$fbp->fb_user_id = $user_profile['id'];
		$fbp->status = 0;
		$fbp->filename = $user_profile[username]."-friendlist".date("Y-m-d");
		$fbp->save();
		//echo "else excu";
		//die();
		}
		
		//echo "out side";
		//die();
		
				
		try {
			
		$fbu = new FbUserMaster;
		$fbu->fb_user_id = $user_profile['id'];
		$fbu->fname = $user_profile['first_name'];
		$fbu->lname = $user_profile['last_name'];
		$fbu->picture = $user_profile['picture']['data']['url'];
		//$fbu->picture = $user_profile['picture'];
		$fbu->username = $user_profile['username'];
		$fbu->gender = $user_profile['gender'];
		$fbu->birthday = $user_profile['birthday'];
		$fbu->relationship_status = $user_profile['relationship_status'];
		//save to database
		$fbu->save();
		$temp = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($user_profile['id']);
		
		$rcu = new RcUserMaster;
		$rcu->ref_fb_id = $temp->id;
		$rcu->fname = $user_profile['first_name'];
		$rcu->lname = $user_profile['last_name'];
		$rcu->picture = $user_profile['picture']['data']['url'];
		//$rcu->picture = $user_profile['picture'];
		$rcu->username = $user_profile['username'];
		$rcu->gender = $user_profile['gender'];
            //save to database
        $rcu->save();    
			
			
			$q = Doctrine_Query::create()
				->select('*')
				->from('FbUserMaster');

			$result = $q->execute();
			$data_arr = $result->toArray();

			$data['records'] = $data_arr;

			$this->load->view('fbfriend', $data);
        }
        catch(Exception $err){
            	
				
				
				
            
            $q = Doctrine_Query::create()
				->select('*')
				->from('FbUserMaster')
				->where('relationship_status = ?','Single');
				 

			$result = $q->execute();
			$data_arr = $result->toArray();

			$data['records'] = $data_arr;
			$data['frnd_count'] = count($friends['data']);

			$this->load->view('fbfriend', $data);
        }
		unset($fbu);
		unset($rcu);
}


//echo "records added";


 }
 



}
?>