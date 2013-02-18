<?php
//load the "Functions" class
require_once(APPPATH.'controllers/functions.php');

class Admin extends Functions
{
    
 public $loginUrl = NULL;	//stores facebook login URL 
 public $logoutUrl = NULL;  //stores facebook logout URL
 public $user = NULL;		//stores facebook unique user id
 public $username = NULL;	//stores facebook username
 public $fullname = NULL;	//stores facebook user's full name
 public $userimage = NULL;	//stores facebook user's image
 public $loggedInID = NULL; //stores unique auto-increment id from fb_user_master table
  
 
 //base constructor
 function __construct()
 {
   
   parent::Controller();
   $fbConfig = $this->config->item('fbAppData');			//loads Facebook App credentials from config file
   $this->load->helper('url');								//loads CI helper file	
   $this->load->library('facebook/facebook',$fbConfig);		//loads relevant facebook libraries	
		
 } 
 
//base function
function index()
{
	//load the required helper classes
	$this->load->helper(array('form', 'url'));
	
	//load the validation library
	$this->load->library('form_validation');	
	
	//set the form validation rules
	$this->form_validation->set_rules('userId', 'User ID', 'required|callback_userrc_check');
	$this->form_validation->set_rules('password', 'Password', 'required');
	$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|callback_cpassword_match');	
	
	if ($this->session->userdata('user')=='' && $this->form_validation->run() == FALSE )
	{ 
		
		//form validation failed, load the form again
		$data = array();
		
		$data['UserId'] = $_POST['userId'];
		//load the header section
		$this->load->view('layouts/header');
		//load the main section
		$this->load->view('adminloginaccount', $data);
		//load the footer section
		$this->load->view('layouts/footer');       
	
	}
	else
	{ 		
		
	    $userId = $_POST['userId'];
		$data = array();
		
		$g = "SELECT * FROM `fb_user_master` AS fum, `rc_user_master` AS rum WHERE fum.id = rum.fk_loc_fb_id AND fum.`username` = '".$userId."' AND `status` = 1";
        $pdo = Doctrine_Manager::connection()->getDbh();       
        $rcuser = $pdo->query($g)->fetchAll();
		
		//check if array empty
		if(is_array($rcuser) && !empty($rcuser))
		{	
			$rc_fk_loc_fb_id = $rcuser[0]['fk_loc_fb_id'];			
			
			$fbu = Doctrine::getTable('FbUserMaster')->findOneById($rc_fk_loc_fb_id);
			$this->user = (is_object($fbu)) ? $fbu->fb_user_id: '';
			$this->username = (is_object($fbu)) ? $fbu->username : '';
			$this->fullname = (is_object($fbu)) ? $fbu->fname.' '.$fbu->lname : '';
			$this->userimage = (is_object($fbu)) ? $fbu->picture : '';
			$this->loggedInID = (is_object($fbu)) ? $fbu->id : '';  
			
			
			//prepare the login and logout urls for admin login
			if ($this->user) {
			  $this->logoutUrl = base_url().'admin/logout';
			} else {
			  $this->loginUrl =  base_url().'admin';
			}			
			
			//show only single FB friends of the logged in user within age group 22 to 35 on the first login landing page
			$data = $this->getSinglefriends($this->loggedInID);
			
			//function to load the left panel data counts
			$leftPanelData = $this->getLeftPanelCounts($this->loggedInID);
			
			//keep the logged in user's credentials in an array variable and store it in a session object
			$userData = array();
			
			//store userdata in a session object
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
			
			//load the header section
		    $this->load->view('layouts/header');
			
			//redirect to add candidate page				
			//redirect('/candidate/addcandidate');
			
			//redirect to dashboard				
			redirect('dashboard', 'refresh');
			
			//load the footer section
			$this->load->view('layouts/footer'); 
	    }
		else{ 
			if($this->session->userdata('user')=='')
			{
				$data = array();
				
				//load the header section
				$this->load->view('layouts/header');
				
				//load the main section
				$this->load->view('adminloginaccount', $data);
				
				//load the footer section
				$this->load->view('layouts/footer'); 
				
			}else{
				redirect('dashboard', 'refresh');
			}
		}
	}
	     
        	
}	
	
	//destroys all session and cookie data of the logged in user and logs him out of the system
	function logout()
	{
		
		//destroying the session
		$this->session->sess_destroy(); 
		//redirect the user to the login page
		redirect('/admin/', 'refresh');		
			
	}
		
	
	//custom RC userId validation function
	public function userrc_check()
	{
		$fbu = Doctrine::getTable('FbUserMaster')->findOneByUsername($_POST['userId']);
		$fbUserId = (is_object($fbu)) ? $fbu->id: '';
		if($fbUserId){
			return true;
		}	
		else{
			$this->form_validation->set_message('userrc_check', 'The User ID field is invalid.');
			return false;
		}			
	}	
	
	
	//custom passwords match validation  
	public function cpassword_match()
	{
			$adminPass = $this->config->item('adminPass'); 
			if($_POST['password'] == $adminPass)
			{
				if($_POST['password'] == $_POST['cpassword']){
					return true;
				}
				else{
					$this->form_validation->set_message('cpassword_match', 'The Password and Confirm Password fields do not match.');
					return false;	
				}
			}
			else{
				$this->form_validation->set_message('cpassword_match', 'The Password is wrong.');
				return false;	
			}			
	}
		
}

?>
