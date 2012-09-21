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



echo "<pre>";
//print_r($facebook);
echo "</pre>";
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
    $user_profile = $facebook->api('/me');

	$friends = $facebook->api('/me/friends');
		

  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
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

echo "<pre>";
//print_r($user_profile);
echo "</pre>";
echo "<pre>";
//print_r($friends['data']);
echo "</pre>";
$count == 0;
				foreach ($friends['data'] as $key => $person) {
					/*echo "<pre>";		
					print_r($person);
					echo "</pre>";*/
			
					$friendsInfo = $facebook->api($person['id']);
				 
					echo "<pre>";		
					//print_r($friendsInfo);
					echo "</pre>";
			
					if($count == 6){
					break;
					//$this->load->view('fbfriend', $friendsInfo);
					//die();	
					}
						
						$count++;
												
												$u = new Friend;
												$u->fb_id = $friendsInfo['id'];
												$u->fname = $friendsInfo['first_name'];
												$u->lname = $friendsInfo['last_name'];
												$u->username = $friendsInfo['username'];
												$u->gender = $friendsInfo['gender'];
												$u->save();
												$data = $friendsInfo;
												unset($u);
						
					}

//echo "records added";

$q = Doctrine_Query::create()
	->select('*')
	->from('friend');

$result = $q->execute();
$data_arr = $result->toArray();
/*foreach ($data_arr  as $value) {
	$data['id'] = $value['id'];
	$data['fb_id'] = $value['fb_id'];
	$data['first_name'] = $value['first_name'];
	$data['last_name'] = $value['last_name'];
	$data['username'] = $value['username'];
	$data['gender'] = $value['gender'];
}*/
$data['records'] = $data_arr;
/*echo $data[0]->id;		
echo $data[0]->fb_id;
echo $data[0]->first_name;
echo $data[1]->fb_id;
echo $data[1]->fb_id;
echo $data[1]->first_name;*/
	
 $this->load->view('fbfriend', $data);
 }
}
?>