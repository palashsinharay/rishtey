<?php
//load the "Functions" class
require_once(APPPATH.'controllers/functions.php');

class Facebooker extends Functions
{
    
        public $loginUrl = NULL;	//stores facebook login URL 
        public $logoutUrl = NULL;  //stores facebook logout URL
        public $user = NULL;		//stores facebook unique user id
        public $username = NULL;	//stores facebook username
        public $fullname = NULL;	//stores facebook user's full name
        public $userimage = NULL;	//stores facebook user's image
        public $loggedInID = NULL; //stores unique auto-increment id from fb_user_master table
        public static $bucket; //store S3 bucket
 
        //base constructor
        function __construct()
        {

          parent::Controller();
          $fbConfig = $this->config->item('fbAppData');			//loads Facebook App credentials from config file
          $this->load->helper('url');								//loads CI helper file	
          $this->load->library('facebook/facebook',$fbConfig);		//loads relevant facebook libraries
          $config['accessKey'] = $this->config->item('accessKey');
          $config['secretKey'] = $this->config->item('secretKey');
          self::$bucket = $this->config->item('bucket');
          $this->load->library('S3',$config);

        }  

 
        //this will get data(user profile details and friends details) from facebook using facebook library object
        //$facebook is the facebook library object
        function getFbData($facebook)
        {
               $fbData['userProfile'] = array();
               $fbData['friendsDetails'] = array();	

                       try {
                   // Proceed knowing you have a logged in user who's authenticated and if not, return null.
                   $fbData['userProfile'] = $facebook->api('/me?fields=first_name,gender,id,birthday,last_name,username,relationship_status,picture,email');

                       $fbData['friendsDetails'] = $facebook->api('/me/friends?fields=first_name,gender,id,birthday,last_name,username,relationship_status,picture,email');

                       } catch (FacebookApiException $e) 
                                       {
                                       error_log($e);
                                       $this->user = null;

                                       return null;
                                       }

                       return $fbData;
        }
 
        //save friends data of logged in user in serialized format in a text file.
        function fileWrite($var)
        { 	
                $fFile = APPPATH."/files/friendlist/".$var['userProfile']['username']."-friendlist-".date("Y-m-d");
                $fFileS3 = "files/friendlist/".$var['userProfile']['username']."-friendlist-".date("Y-m-d");
                $fData = serialize($var['friendsDetails']);	
                //$string = write_file($fFile,$fData,'w+');  //this is CI inbuilt function name, so cannot change it to CamelCase
                file_put_contents($fFile, $fData);
                $this->s3->putObjectFile($fFile, self::$bucket, $fFileS3, S3::ACL_PUBLIC_READ);
                //get fb app credentials
                $fbCred = $this->config->item('fbAppData');

                //fetch filename from fb_process table
                //$var['userProfile']['id'] contains the unique facebook user id
                $fbpu = Doctrine::getTable('FbProcess')->findOneByFb_user_id($var['userProfile']['id']);	

                        //if file exists then update, else create a new file
                        if(isset($fbpu->filename)){
                                $fbpu->filename = $var['userProfile']['username']."-friendlist-".date("Y-m-d");

                                //echo '<pre>';
                                //print_r($_SESSION);
                                //exit;

                                if($_SESSION['fb_'.$fbCred['appId'].'_user_id']==''){			
                                        $fbpu->status = 0;
                                }

                                $fbpu->save();
                    unset($fbpu);

                        }else{				
                                $fbp = new FbProcess;
                                $fbp->fb_user_id = $var['userProfile']['id'];
                                //$fbp->status = 0;
                                $fbp->filename = $var['userProfile']['username']."-friendlist-".date("Y-m-d");
                                $fbp->save();

                        }	
        }
 
        //insert logged in user data in fb_user_master and rc_user_master table 
        function insertUserData($var, $fbUser)
        {  
               $fbstatus = array(1 =>"Single", 2=>"Divorced", 3=>"Widowed", 4=>"Separated"); 	
               //set the gender dropdown values
               $gender = array(1=>"male",2=>"female");

               try{

                       //check if $fbUser contains data	
                       if(!is_object($fbUser))
                       {

                               //echo '<pre>';
                               //print_r($var['userProfile']['relationship_status']);
                               //exit;

                               $fbu = new FbUserMaster;
                               $fbu->fb_user_id = $var['userProfile']['id'];					
                               $fbu->fname = $var['userProfile']['first_name'];
                               $fbu->lname = $var['userProfile']['last_name'];
                               $fbu->picture = $var['userProfile']['picture']['data']['url'];		
                               $fbu->username = $var['userProfile']['username'];
                               $fbu->gender = (isset($var['userProfile']['gender'])) ? array_search($var['userProfile']['gender'],$gender) : 0;	//$var['userProfile']['gender'];
                               $fbu->birthday = date("Y-m-d",strtotime($var['userProfile']['birthday']));

                               if($var['userProfile']['relationship_status'] == 'Single')
                                       $fbu->relationship_status = 1;
                               else				
                                       $fbu->relationship_status = (isset($var['userProfile']['relationship_status'])) ? array_search($var['userProfile']['relationship_status'],$fbstatus) : '';

                               $fbu->email = $var['userProfile']['email'];

                               //save to fb_user_master table
                               $fbu->save();

                               $rcu = new RcUserMaster;			
                               $rcu->fk_loc_fb_id = $fbu->id;											
                               $rcu->picture = $var['userProfile']['picture']['data']['url'];
                               $rcu->email = $var['userProfile']['email'];

                               //save to rc_user_master table
                               $rcu->save();

                               //update fk_loc_fb_id (primary key in fb_user_master table) field value in fb_process table
                               $fbp = Doctrine::getTable('FbProcess')->findOneByFb_user_id($var['userProfile']['id']);	
                               $fbp->fk_loc_fb_id = $fbu->id;
                               $fbp->save();

                       }else{

                               //insert into fb_process table
                               //first check whether record exists
                               $fp = Doctrine::getTable('FbProcess')->findOneByFb_user_id($var['userProfile']['id']);

                                       if($fp->id){								
                                               $fp->fk_loc_fb_id=$fbUser->id;

                                               //update fb_process table
                                               $fp->save();

                                       }

                               //check whether logged in user exists in rc_user_master table
                               $rcuMaster = Doctrine::getTable('RcUserMaster')->findOneByFk_loc_fb_id($fbUser->id);  
                               if(!$rcuMaster->id){
                                       $rcu = new RcUserMaster;			
                                       $rcu->fk_loc_fb_id = $fbUser->id;
                                       $rcu->picture = $var['userProfile']['picture']['data']['url'];
                                       $rcu->email = $var['userProfile']['email'];

                                       //save to rc_user_master table
                                       $rcu->save();
                               }
                       }		

                               //unset the user objects
                               unset($fbu);
                               unset($rcu);    

                               return 1;	

                       }

               catch(Exception $err){            		
                 return "An error occured";  	
               }

        }
 
        //base function
        function index()
        { 	
                // get Facebook App credentials from config file
                $fbConfig = $this->config->item('fbAppData'); 

                //initialize the logged in variable
                if(!isset($this->session->userdata['loggedIn']))	$this->session->userdata['loggedIn'] = 0;

                // create Facebook library object      
                $facebook = new Facebook($fbConfig);

                // Get Facebook unique user id
                $this->user = $facebook->getUser();	

                // We may or may not have this data based on whether the user is logged in.
                //
                // If we have a $user id here, it means we know the user is logged into
                // Facebook, but we don't know if the access token is valid. An access
                // token is invalid if the user logged out of Facebook.

                //echo '<pre>';
                //print_r($_SESSION);
                //exit;
                $myurl = base_url()."index.php/facebooker";
                // Login or logout url will be needed depending on current user state.
                if ($this->user) {
                  $this->logoutUrl = $facebook->getLogoutUrl();
                } else {
                  $this->loginUrl = $facebook->getLoginUrl(array('redirect_uri' =>  $myurl ,'scope' => 'email,user_birthday,user_relationships,friends_birthday,friends_relationships, user_likes'));
                }

                //echo '<pre>';
                //print_r($_SESSION);
                //exit;

                //check user logged in or not 
                //$this->user contains the logged in user's unique facebook id
                if ($this->user <> '0' && $this->user <> '') {

                                //save logged in user's image from facebook			
                                $url = 'http://graph.facebook.com/'.$this->user.'/picture?width=50&height=50';
                                $dataLargeImage = file_get_contents($url);
                                $fileName = $_SERVER['DOCUMENT_ROOT'] . '/' . APPPATH . 'files/profile_images/loggedinuser/'.$this->user.'_small.jpg';
                                $fileNameS3 = 'files/profile_images/loggedinuser/'.$this->user.'_small.jpg';
                                //$file = fopen($fileName, 'w+') or die("can't open file");
                                //fputs($file, $dataLargeImage);
                                //fclose($file);
                                file_put_contents($fileName, $dataLargeImage);
                                $this->s3->putObjectFile($fileName, self::$bucket, $fileNameS3, S3::ACL_PUBLIC_READ);

                    //Get user details from fb_user_master table based on logged in user's unique facebook id
                    $userRec = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($this->user);	

                                $this->username = (is_object($userRec)) ? $userRec->username : '';
                                $this->fullname = (is_object($userRec)) ? $userRec->fname.' '.$userRec->lname : '';
                                $this->userimage = (is_object($userRec)) ? $userRec->picture : '';

                    //check whether the network is ready for the logged in user:            
                    $fbProcess = Doctrine::getTable('FbProcess')->findOneByFb_user_id($this->user);			

                                // "status = 2" denotes that the network is ready and the user should be allowed to go to the First Login Landing Page
                    if((is_object($fbProcess)) && $fbProcess->status == 2){

                                        //unset the virtual friends array (it is created when friends are added into the suggestion list from First Login Landing Page)
                                        $this->session->unset_userdata('finalSet');

                                    //pull FB user data from FB using FB user object
                                    $fbData = $this->getFbData($facebook);

                                        //if null, then return to login page
                                        if($fbData==''){
                                                redirect('facebooker/logout');
                                        }

                                        //write friends data of logged in FB user in serialized format in a text file.
                                    $this->fileWrite($fbData);

                                        //fetch user details from fb_user_master table based on logged in user's unique facebook id
                                        //$fbData['userProfile']['id'] contains logged in user's unique facebook id
                                        $fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($fbData['userProfile']['id']);

                                        //fetch rc user details from rc_user_master table based on logged in user's unique auto-increment id
                                        $rcu = Doctrine::getTable('RcUserMaster')->findOneByFk_loc_fb_id($fbu->id); 				

                                        //search "loc_fr_fb_id" column in network table for logged in user's unique auto-increment id and update its corresponding "fk_rc_user_id" value with unique auto-increment id from rc_user_master table
                                        $sqlUpdateNetwork = "UPDATE network SET fk_rc_user_id = $rcu->id WHERE loc_fr_fb_id= $fbu->id";

                                        //search "loc_fr_fb_id" column in extended_network table for logged in user's unique auto-increment id and update its corresponding "fk_rc_user_id" value with unique auto-increment id from rc_user_master table
                                        $sqlUpdateExtNetwork = "UPDATE extended_network SET fk_rc_user_id = $rcu->id WHERE loc_fr_fb_id= $fbu->id";

                                        $pdo = Doctrine_Manager::connection()->getDbh(); 
                                        $resultUpdateNetwork = $pdo->query($sqlUpdateNetwork);
                                        $resultUpdateExtNetwork = $pdo->query($sqlUpdateExtNetwork);								

                                        //assign logged in user's unique auto-increment id from fb_user_master table to "loggedInID" variable
                        $this->loggedInID = $fbu->id;               

                                        //show only single FB friends of the logged in user within age group 22 to 35 on the first login landing page
                        $data = $this->getSinglefriends($this->loggedInID);

                                        //function to load the left panel data counts
                                        $leftPanelData = $this->getLeftPanelCounts($this->loggedInID);				

                                        //keep the logged in user's credentials in an array variable and store it in a session object
                                        $userData = array();

                                        $userData = array(
                                        'user'=>$this->user,
                                        'username'  => $this->username,
                                        'fullname'  => $this->fullname,
                                        'userimage' => $this->userimage,
                                        'logoutUrl' => $this->logoutUrl,
                                        'loggedIn' => TRUE
                                        );

                                        $this->session->set_userdata($userData);		//CI inbuilt function, so cannot change it to CamelCase

                                        //prepare the autosuggest array of fb friends of the logged in user on the first login landing page
                                        $availableTags = array();
                                        $availableTagsWithName = array();

                                        //get all FB friends of the logged in user
                                        $frUser = $this->getAllfriends($this->loggedInID, $data['records']);    

                                        foreach ($frUser['records'] as $key => $value){    	
                                                $availableTags[$key] = $value['username'].'??'.$value['name']; 
                                                $availableTagsWithName[$key] = $value['name']; 
                                        }	

                                        $data['availableTags'] = $availableTags;
                                        $data['availableTagsWithName'] = $availableTagsWithName;

                                        //load the left panel data count view
                                        $data['leftPanelCount'] = $this->load->view('layouts/leftpanelcount', $leftPanelData, true);

                                        //check whether logged in user's suggestion list is ready or not and if not, load the dashboard instead of the First Login Landing Page
                                        if($userRec->set_suggestion_list == 1){				

                                                if(isset($this->session->userdata['CandiUsername'])){

                                                        //suggestion list ready, go to dashboard and log the message in rc_log table 
                                                        $action  = 'show-candidate-profile page-from-initiation-request';
                                                        $referer = $_SERVER["HTTP_REFERER"];
                                                        $fbUserId = $this->user;
                                                        $page    = $_SERVER['REQUEST_URI'];
                                                        $msg     = 'show-candidate-profile page-from-initiation-request';	

                                                        //get fb user id of the candidate
                                                        $fbu = Doctrine::getTable('FbUserMaster')->findOneByUsername($this->session->userdata['CandiUsername']);

                                                        $own_candidate_id = $fbu->fb_user_id;
                                                        $other_candidate_id = $fbu->fb_user_id;

                                                        $state = $_REQUEST['state'];
                                                        $code = $_REQUEST['code'];

                                                        //$date = new DateTime();
                                                        //$timestamp = $date->getTimestamp();

                                                        $timestamp = time();

                                                        //creatng the access-denied log.   
                                                        $this->rc_log_save($action, $referer, $fbUserId, $page, $msg, $state, $code, $timestamp, $own_candidate_id, $other_candidate_id);

                                                        //redirect to candidate creation 1st page						
                                                        redirect('candidate/candidateprofile','refresh');

                                                }else{						

                                                        //check whether logged in user has any candidate profile created by him and accordingly log the message and redirect him
                                                        $sql = "SELECT * FROM rc_profile_relation AS pr WHERE pr.type = 'G' AND pr.fb_guardian = " . $this->session->userdata['user'];
                                                        $pdo = Doctrine_Manager::connection()->getDbh();
                                                        $resultSet = $pdo->query($sql)->fetchAll();

                                                        if(count($resultSet) == 0){

                                                                //suggestion list ready, go to add candidate page and log the message in rc_log table 
                                                                $action  = 'show-add-candidate-page';
                                                                $referer = $_SERVER["HTTP_REFERER"];
                                                                $fbUserId = $this->session->userdata['user'];
                                                                $page    = $_SERVER['REQUEST_URI'];
                                                                $msg     = 'has no candidate profile, move to add candidate page';				

                                                                $state = $_REQUEST['state'];
                                                                $code = $_REQUEST['code'];

                                                                //$date = new DateTime();
                                                                //$timestamp = $date->getTimestamp();

                                                                $timestamp = time();

                                                                //creatng the access-denied log.   
                                                                $this->rc_log_save($action, $referer, $fbUserId, $page, $msg, $state, $code, $timestamp);

                                                                //redirect to add candidate page				
                                                                redirect('candidate/addcandidate');

                                                        }else{

                                                                //suggestion list ready, go to dashboard and log the message in rc_log table 
                                                                $action  = 'show-dashboard';
                                                                $referer = $_SERVER["HTTP_REFERER"];
                                                                $fbUserId = $this->session->userdata['user'];
                                                                $page    = $_SERVER['REQUEST_URI'];
                                                                $msg     = 'has a candidate profile, move to dashboard';				

                                                                $state = $_REQUEST['state'];
                                                                $code = $_REQUEST['code'];

                                                                //$date = new DateTime();
                                                                //$timestamp = $date->getTimestamp();

                                                                $timestamp = time();

                                                                //creatng the access-denied log.   
                                                                $this->rc_log_save($action, $referer, $fbUserId, $page, $msg, $state, $code, $timestamp);

                                                                //redirect to dashboard				
                                                                redirect('dashboard', 'location');
                                                        }

                                                }

                                        }else{ 					

                                                //network ready but suggestion list is not so, show the first login landing page and log the message in rc_log table 
                                                $action  = 'show-first-login-landing-page';
                                                $referer = (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' );
                                                $fbUserId = $this->user;
                                                $page    = $_SERVER['REQUEST_URI'];
                                                $msg     = 'network ready, move to first login landing page';				

                                                $state = (isset($_REQUEST['state']) ? $_REQUEST['state'] : '' );
                                                $code = (isset($_REQUEST['code']) ? $_REQUEST['code'] : '' );

                                                //$date = new DateTime();
                                                //$timestamp = $date->getTimestamp();

                                                $timestamp = time();

                                                //creatng the access-denied log.   
                                                $this->rc_log_save($action, $referer, $fbUserId, $page, $msg, $state, $code, $timestamp);					

                                                //suggestion list is not ready so, load the First Login Landing Page
                                                //load the header part
                                                $this->load->view('layouts/header');
                                                $this->load->view('singlefbfriend', $data);	
                                                $this->load->view('layouts/footer');

                                        }

                    }else{

                                        //keep the logged in user's credentials in an array variable and store it in a session object
                                        $userData = array();

                                        $userData = array(
                                        'user'=>$this->user,
                                        'username'  => $this->username,
                                        'fullname'  => $this->fullname,
                                        'userimage' => $this->userimage,
                                        'logoutUrl' => $this->logoutUrl,
                                        'loggedIn' => TRUE
                                        );

                                        $this->session->set_userdata($userData);		//CI inbuilt function, so cannot change it to CamelCase		





                                        /////////////////////check if user is already activated///////////////////////////////////
                                        //////////////////////////////////////////////////////////////////////////////////////////
                                        /////////////////////////////////////////////////////////////////////////////////////////

                                        //Get user details from fb_user_master table based on logged in user's unique facebook id
                        $userRec = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($this->user);	
                                        if($userRec->set_suggestion_list == 1){	
                                                //check whether logged in user has any candidate profile created by him and accordingly log the message and redirect him
                                                $sql = "SELECT * FROM rc_profile_relation AS pr WHERE pr.type = 'G' AND pr.fb_guardian = " . $this->session->userdata['user'];
                                                $pdo = Doctrine_Manager::connection()->getDbh();
                                                $resultSet = $pdo->query($sql)->fetchAll();

                                                if(count($resultSet) == 0){
                                                        //suggestion list ready, go to add candidate page and log the message in rc_log table 
                                                        $action  = 'show-add-candidate-page';
                                                        $referer = $_SERVER["HTTP_REFERER"];
                                                        $fbUserId = $this->session->userdata['user'];
                                                        $page    = $_SERVER['REQUEST_URI'];
                                                        $msg     = 'has no candidate profile, move to add candidate page';				

                                                        $state = $_REQUEST['state'];
                                                        $code = $_REQUEST['code'];

                                                        //$date = new DateTime();
                                                        //$timestamp = $date->getTimestamp();

                                                        $timestamp = time();

                                                        //creatng the access-denied log.   
                                                        $this->rc_log_save($action, $referer, $fbUserId, $page, $msg, $state, $code, $timestamp);

                                                        //redirect to add candidate page				
                                                        redirect('candidate/addcandidate');

                                                }else{
                                                        //suggestion list ready, go to dashboard and log the message in rc_log table 
                                                        $action  = 'show-dashboard';
                                                        $referer = $_SERVER["HTTP_REFERER"];
                                                        $fbUserId = $this->session->userdata['user'];
                                                        $page    = $_SERVER['REQUEST_URI'];
                                                        $msg     = 'has a candidate profile, move to dashboard';				

                                                        $state = $_REQUEST['state'];
                                                        $code = $_REQUEST['code'];

                                                        //$date = new DateTime();
                                                        //$timestamp = $date->getTimestamp();

                                                        $timestamp = time();

                                                        //creatng the access-denied log.   
                                                        $this->rc_log_save($action, $referer, $fbUserId, $page, $msg, $state, $code, $timestamp);

                                                        //redirect to dashboard				
                                                        redirect('dashboard', 'location');

                                                }

                                        }
                                        //////////////////////////////////////////////////////////////////////////////
                                        /////////////////////////////////////////////////////////////////////////////
                                        ////////////////////////////////////////////////////////////////////////////





                        //if the status is 0 insert logged in user's own data, write the file containing his friends data and show him the welcome message.                
                                        //pull FB user data from FB using FB library object
                                    $fbData = $this->getFbData($facebook);

                                        //echo '<pre>';
                                        //print_r($fbData);
                                        //exit;

                                        //if null, then return to login page
                                        if($fbData==''){
                                                redirect('facebooker/logout');
                                        }

                                        // write friends data of logged in FB user in serialized format in a text file.
                                    $this->fileWrite($fbData);

                                        //get FB user data from fb_user_master table and store the user credentials in local variables
                                        //$fbData['userProfile']['id'] contains logged in user's unique facebook id
                                        $fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($fbData['userProfile']['id']);				

                                        //insert user data in fb_user_master and rc_user_master table
                                        $this->insertUserData($fbData, $fbu);				

                                        //Get user details from fb_user_master table based on logged in user's unique facebook id
                                        $userRec = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($this->user);	
                                        $this->username = $userRec->username;
                                        $this->fullname = $userRec->fname.' '.$userRec->lname;
                                        $this->userimage = $userRec->picture;

                        $data['username'] = $this->username;
                        $data['logoutUrl']  = $this->logoutUrl;

                                        //successful login from facebook, log the message in rc_log table        
                                        $action  = 'fb-successful-login';
                                        $referer = (isset($_SERVER["HTTP_REFERER"])) ? $_SERVER["HTTP_REFERER"] : '';
                                        $fbUserId = $this->user;
                                        $page    = $_SERVER['REQUEST_URI'];
                                        $msg     = 'successfully logged in through facebook';				

                                        $state = (isset($_REQUEST['state'])) ? $_REQUEST['state'] : '';
                                        $code = (isset($_REQUEST['code'])) ? $_REQUEST['code'] : '';

                                        //$date = new DateTime();
                                        //$timestamp = $date->getTimestamp();

                                        $timestamp = time();

                                        //creatng the access-denied log.   
                                        $this->rc_log_save($action, $referer, $fbUserId, $page, $msg, $state, $code, $timestamp);

                                        //keep the logged in user's credentials in an array variable and store it in a session object
                                        $userData = array();

                                        $userData = array(
                                        'user'=>$this->user,
                                        'username'  => $this->username,
                                        'fullname'  => $this->fullname,
                                        'userimage' => $this->userimage,
                                        'logoutUrl' => $this->logoutUrl,
                                        'loggedIn' => TRUE
                                        );

                                        $this->session->set_userdata($userData);		//CI inbuilt function, so cannot change it to CamelCase

                                        //load the header section				
                        $this->load->view('layouts/header');


                                        ////////////////////check if activation mail sent to user starts//////////////////////////////////////////////
                                        $dataForFirstTimeUser = array();
                                        $dataForFirstTimeUser['fbUserId'] = $this->session->userdata['user'];

                                        //fetch user's auto incremented id from fb_user_master table
                                        $fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($this->session->userdata['user']);				
                                        $dataForFirstTimeUser['fbId'] = $fbu->id;

                                        //fetch user's email from rc_user_master table
                                        $rcu = Doctrine::getTable('RcUserMaster')->findOneByFk_loc_fb_id($fbu->id);
                                        $dataForFirstTimeUser['email'] = $rcu->email;				

                                        //check if user already activated
                                        $sqlChkActivation = "SELECT *
                                                                                FROM rc_user_master AS rum 				
                                                                                WHERE rum.fk_loc_fb_id = ".$fbu->id."
                                                                                AND status = 1";  					

                                        $pdo = Doctrine_Manager::connection()->getDbh();       
                                        $resultChkActivation = $pdo->query($sqlChkActivation)->fetchAll();	

                                        if(count($resultChkActivation)==0){					
                                                //load the main section
                                                $this->load->view('createaccount', $dataForFirstTimeUser);					
                                        }else{					

                                                //load the main section
                                                $this->load->view('welcome', $data);					
                                        }			

                                        //load the footer section
                                        $this->load->view('layouts/footer');
                                        ////////////////////check if activation mail sent to user ends///////////////////////////////////////////	

                    }

                }else{	

                                if($_REQUEST['error']=='access_denied' && $_REQUEST['error_reason']=='user_denied'){				
                                        //permission denied from facebook, log the message in rc_log table        
                                        $action  = 'fb-permission-denied';
                                        $referer = $_SERVER["HTTP_REFERER"];
                                        $fbUserId = 0;
                                        $page    = $_SERVER['REQUEST_URI'];
                                        $msg     = $_REQUEST['error_description'];   

                                        $state = $_REQUEST['state'];
                                        $code = $_REQUEST['code'];

                                        //$date = new DateTime();
                                        //$timestamp = $date->getTimestamp();

                                        $timestamp = time();

                                        //creatng the access-denied log.   
                                        $this->rc_log_save($action, $referer, $fb_user_id, $page, $msg, $state, $code, $timestamp);                        
                    }else{

                                        //forcefully logout the user when facebook->getUser() returns 0 ocassionally
                                        unset($this->session->userdata);
                                }	

                                        //show the login page to the user
                                        $data['loginUrl'] = $this->loginUrl;            
                                        $this->load->view('layouts/header', $data);
                                        $this->load->view('login');
                                        $this->load->view('layouts/footer');

                }                     

        }	
	
	//destroys all session and cookie data of the logged in user and logs him out of the system
	function logout()
	{	
		
		$fbConfig = $this->config->item('fbAppData');			//FETCH Facebook App credentials from config file
			
		//logged out from facebook, log the message in rc_log table        
		$action  = 'fb-logout';
		$referer = $_SERVER["HTTP_REFERER"];
		$fbUserId = $this->session->userdata['user'];
		$page    = $_SERVER['REQUEST_URI'];
		$msg     = 'logged out from facebook'; 
		
		$state = (isset($_REQUEST['state'])) ? $_REQUEST['state'] : '' ;
		$code = (isset($_REQUEST['code'])) ? $_REQUEST['code'] : '' ;
		
		//$date = new DateTime();
		//$timestamp = $date->getTimestamp();
		
		$timestamp = time();		
		
		//creatng the access-denied log.   
		$this->rc_log_save($action, $referer, $fbUserId, $page, $msg, $state, $code, $timestamp);		
		
		//destroying the session
		$this->session->sess_destroy(); 		    
		
		/***destroying the cookies ***/
		if (isset($_SERVER['HTTP_COOKIE'])) {
			$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
			foreach($cookies as $cookie) {
				$parts = explode('=', $cookie);
				$name = trim($parts[0]);
				setcookie($name, '', time()-1000);				
				setcookie($name, '', time()-1000, '/');
			}
		}		 
		
		//unset 'tab_no' cookie
		echo '<script type="text/javascript">
				document.cookie = "tab_no=; expires=-1 UTC; path=/candidate/"	
			  </script>';
			
		//unset all session variables created by FB login
		unset($_SESSION['fb_'.$fbConfig['appId'].'_state']);
		unset($_SESSION['fb_'.$fbConfig['appId'].'_code']);
		unset($_SESSION['fb_'.$fbConfig['appId'].'_access_token']);
		unset($_SESSION['fb_'.$fbConfig['appId'].'_user_id']);	
		
		//redirect the user to the login page
		//redirect('/', 'refresh');
		header('location: '.base_url());
			
	}
	
	public function save()
	{		
			
			//if Cancel button is pressed, redirect to login page	
			if($_POST['account_save']=="c"){
				redirect('/', 'refresh');
			}
			
			//load the required helper classes
			$this->load->helper(array('form', 'url'));
			
			//load the validation library
			$this->load->library('form_validation');		
			
			//set the form validation rules
			$this->form_validation->set_rules('email', 'Email', 'required|callback_email_check');
			$this->form_validation->set_rules('phone', 'Phone', 'required|callback_phone_check');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
			$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|callback_cpassword_match');		
			
			if ($this->form_validation->run() == FALSE)
			{
				//form validation failed, load the form again
				$data = array();
				$data['fbUserId'] = $_POST['fbUserId'];
			
				//fetch user's email from fb_user_master table
				$fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($_POST['fbUserId']);			
			
				$data['fbId'] = $fbu->id;
				$data['email'] = $_POST['email'];
				$data['phone'] = $_POST['phone'];
			
				//load the header section
				$this->load->view('layouts/header');
				//load the main section
				$this->load->view('createaccount', $data);
				//load the footer section
				$this->load->view('layouts/footer');
			
			}
			else
			{			
				//form validation successful, so update the data in rc_user_master table 
				$rcu = Doctrine::getTable('RcUserMaster')->findOneByFk_loc_fb_id($_POST['fbId']);				
				
				$rcu->email=$_POST['email'];
				$rcu->phone=$_POST['phone'];
				$rcu->password=$_POST['password'];
				$rcu->status=1;						//1 = user activated  0 = user not activated			
				
				$rcu->save();

				//get fb details of the user
				$fbu = Doctrine::getTable('FbUserMaster')->findOneById($_POST['fbId']);				
				
				//send welcome mail to user 
				$this->load->library('email');			
				$config = $this->config->item('mailconfigData'); 
			
				$this->email->initialize($config);
			
				//fetch and set sender info from config file
				$this->email->from($this->config->item('fromMail'), $this->config->item('fromName'));
			
				$this->email->subject($this->config->item('welcomeMailSubject'));
			
				$this->email->to($rcu->email);

				
				//read mail text from file
				$message = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/'.APPPATH.'views/layouts/mailtmpl/welcome.php');				

				$message = str_replace('$baseUrl', base_url(), str_replace('$gender', ($fbu->gender==1)?'Mr':'Ms', str_replace('$lname', $fbu->lname, str_replace('$dateToday', date('D, F d, Y', time()), str_replace('$yearToday', date('Y', time()), $message)))));

				//$this->email->message($this->config->item('welcomeMailMessage'));  
				
				$this->email->message($message);
			
				if($this->email->send()){
					
					//successful login from facebook, log the message in rc_log table        
					$action  = 'save rc credentials and send welcome mail';
					$referer = (isset($_SERVER["HTTP_REFERER"])) ? $_SERVER["HTTP_REFERER"] : '';
					$fbUserId = $this->session->userdata['user'];
					$page    = $_SERVER['REQUEST_URI'];
					$msg     = 'rc credentials saved and welcome mail sent successfully';				
					
					$state = (isset($_REQUEST['state'])) ? $_REQUEST['state'] : '';
					$code = (isset($_REQUEST['code'])) ? $_REQUEST['code'] : '';
					
					//$date = new DateTime();
					//$timestamp = $date->getTimestamp();
					
					$timestamp = time();
					
					//creatng the access-denied log.   
					$this->rc_log_save($action, $referer, $fbUserId, $page, $msg, $state, $code, $timestamp);
					
				}else{
					
					//successful login from facebook, log the message in rc_log table        
					$action  = 'save rc credentials';
					$referer = (isset($_SERVER["HTTP_REFERER"])) ? $_SERVER["HTTP_REFERER"] : '';
					$fbUserId = $this->session->userdata['user'];
					$page    = $_SERVER['REQUEST_URI'];
					$msg     = 'rc credentials saved successfully';				
					
					$state = (isset($_REQUEST['state'])) ? $_REQUEST['state'] : '';
					$code = (isset($_REQUEST['code'])) ? $_REQUEST['code'] : '';
					
					//$date = new DateTime();
					//$timestamp = $date->getTimestamp();
					
					$timestamp = time();
					
					//creatng the access-denied log.   
					$this->rc_log_save($action, $referer, $fbUserId, $page, $msg, $state, $code, $timestamp);
					
				}
			
				//redirect to welcome page
				redirect('/', 'refresh');			
			
			}
			
	}
	
	//custom email validation function
	public function email_check()
	{
			if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
				return true;
			else
				$this->form_validation->set_message('email_check', 'The %s field is invalid.');
				return false;	
	}
	
	//custom phone validation function
	public function phone_check()
	{
			//check if landline number
			if(preg_match("/^[0-9]{3}-[0-9]{4}-[0-9]{4}$/", $_POST['phone'])) {
			  	return true;
			}
			else{
			
				//check if mobile number
				if(preg_match('/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{10})$/', $_POST['phone'])){					
					return true;
				}else{			
					$this->form_validation->set_message('phone_check', 'The %s field is invalid.');
					return false;	
				}
					
			}
	}
	
	//custom passwords match validation  
	public function cpassword_match()
	{
			if($_POST['password'] == $_POST['cpassword']){
				return true;
			}
			else{
				$this->form_validation->set_message('cpassword_match', 'The Password and Confirm Password fields do not match.');
				return false;	
			}
			
	}
		
}

?>
