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
 }

 function index()
 {
   //$data['api_key'] = "345381405552220";
   //$data['secret_key'] = "2058d2d39f8b71897de2d1ec6f513a30";

   
   
   $facebook = new Facebook(array(
  'appId' => '366585186712793',
  'secret' => '6144b6eef34ef72d1b3e5420a27ce94c',
));





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
// This call will always work since we are fetching public data.
$naitik = $facebook->api('/naitik');



//$count == 0;
				/*foreach ($friends['data'] as $key => $person) {
			
			
					
					
					if($count == 6){
												break;
						//$this->load->view('fbfriend', $friendsInfo);
					    //die();	
									}
										
																
					//$count++;
																						
								$u = new Friend;
								$u->fb_id = $person['id'];
								$u->fname = $person['first_name'];
								$u->lname = $person['last_name'];
								$u->username = $person['username'];
								$u->gender = $person['gender'];
								$u->save();
								$data = $person;
								unset($u);
					
					
						
}*/

$ufile = APPPATH.$user_profile[username].date("Y-m-d");
$ffile = APPPATH.$user_profile[username]."-friendlist".date("Y-m-d");
$udata = serialize($user_profile);
$fdata = serialize($friends);
$string = write_file($ufile,$udata,'a+');
$string = write_file($ffile,$fdata,'a+');

//echo "records added";

$q = Doctrine_Query::create()
	->select('*')
	->from('FbUserMaster');

$result = $q->execute();
$data_arr = $result->toArray();

$data['records'] = $data_arr;

$this->load->view('fbfriend', $data);
 }
}
?>