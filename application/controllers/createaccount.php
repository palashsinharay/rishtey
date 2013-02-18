<?php 
class createAccount extends Controller
{

	function __construct()
	{
		parent::Controller();
	
	}
	 
	function create($fbUserId){
		
		$data = array();
		$data['fbUserId'] = $fbUserId;
		
		//get the user's email from fb_user_master table
		$fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($fbUserId);			
		
		$data['fbId'] = $fbu->id;
		$data['email'] = $fbu->email;
		
		
		//check if user already activated
		$sqlChkActivation = "SELECT *
							FROM rc_user_master AS rum 				
							WHERE rum.fk_loc_fb_id = ".$fbu->id."
							AND status = 1";  
			
			
    	$pdo = Doctrine_Manager::connection()->getDbh();       
    	$resultChkActivation = $pdo->query($sqlChkActivation)->fetchAll();	
		
		if(count($resultChkActivation)==0){
			//load the header section
			$this->load->view('layouts/header');
			//load the main section
			$this->load->view('createaccount', $data);
			//load the footer section
			$this->load->view('layouts/footer');
		}else{
			redirect('facebooker/', 'refresh');
			
		}
				
	}
	
	public function save()
	{
		
		//if Cancel button is pressed, redirect to login page	
		if($_POST['account_save']=="c"){
			redirect('facebooker', 'refresh');
		}
		
		//load the required helper classes
		$this->load->helper(array('form', 'url'));
		
		//load the validation library
		$this->load->library('form_validation');		
		
		//set the form validation rules
		$this->form_validation->set_rules('email', 'Email', 'required|callback_email_check');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[8]');
		$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|callback_cpassword_match');		
		
		if ($this->form_validation->run() == FALSE)
		{
			//form validation failed, load the form again
			$data = array();
			$data['fbUserId'] = $_POST['fbUserId'];
			
			//get the user's email from fb_user_master table
			$fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($_POST['fbUserId']);			
			
			$data['fbId'] = $fbu->id;
			$data['email'] = $fbu->email;
			
			//load the header section
			$this->load->view('layouts/header');
			//load the main section
			$this->load->view('createaccount', $data);
			//load the footer section
			$this->load->view('layouts/footer');
			
		}
		else
		{			
			
			//form validation successful, so save the data 
			$rcu = Doctrine::getTable('RcUserMaster')->findOneByFk_loc_fb_id($_POST['fbId']);
			
			$rcu->email=$_POST['email'];
			$rcu->password=$_POST['password'];
			$rcu->status=1;						//1 = user activated  0 = user not activated			
			
			if($rcu->save()){			
				//data updated, redirect to login page
				//redirect('facebooker', 'refresh');
				
				//load the header section
				$this->load->view('layouts/header');
				//load the main section
				$this->load->view('formsuccess');
				//load the footer section
				$this->load->view('layouts/footer');
				
			}else{
				//data updation error, load the form again
				$data = array();
				$data['fbUserId'] = $_POST['fbUserId'];
				
				//get the user's email from fb_user_master table
				$fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($_POST['fbUserId']);			
				
				$data['fbId'] = $fbu->id;
				$data['email'] = $fbu->email;
				
				//load the header section
				$this->load->view('layouts/header');
				//load the main section
				$this->load->view('formsuccess');
				//load the footer section
				$this->load->view('layouts/footer');
			}
				
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
