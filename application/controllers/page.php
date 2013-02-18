<?php 

require_once(APPPATH . 'controllers/facebooker.php');

class page extends facebooker
{
	
	function __construct()
	{
		parent::Controller();
	
	}
	 
	function about(){
		
		$data['text'] = 'About...';
		
		if($this->session->userdata['user']){
			$fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($this->session->userdata['user']);
			$this->loggedInID = $fbu->id;
		
			//function to load the left panel data counts
			$leftPanelData = $this->getLeftPanelCounts($this->loggedInID);
		
        
			//load the left panel data count view
			$data['leftPanelCount'] = $this->load->view('layouts/leftpanelcount', $leftPanelData, true);		
		}		
		
		
        //load the header view
        $this->load->view('layouts/header');
        $this->load->view('page', $data);
        
		//load the footer view
        $this->load->view('layouts/footer');
		
					
	}
	
	function jobs(){
		
		$data['text'] = 'Jobs...';
		
		if($this->session->userdata['user']){
			$fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($this->session->userdata['user']);
			$this->loggedInID = $fbu->id;
		
			//function to load the left panel data counts
			$leftPanelData = $this->getLeftPanelCounts($this->loggedInID);
		
        
			//load the left panel data count view
			$data['leftPanelCount'] = $this->load->view('layouts/leftpanelcount', $leftPanelData, true);		
		}
		
        //load the header view
        $this->load->view('layouts/header');
        $this->load->view('page', $data);
        
		//load the footer view
        $this->load->view('layouts/footer');
		
					
	}

	function faq(){
		
		$data['text'] = "Faq's...";
		
		if($this->session->userdata['user']){
			$fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($this->session->userdata['user']);
			$this->loggedInID = $fbu->id;
		
			//function to load the left panel data counts
			$leftPanelData = $this->getLeftPanelCounts($this->loggedInID);
		
        
			//load the left panel data count view
			$data['leftPanelCount'] = $this->load->view('layouts/leftpanelcount', $leftPanelData, true);		
		}		
		
		
        //load the header view
        $this->load->view('layouts/header');
        $this->load->view('page', $data);
        
		//load the footer view
        $this->load->view('layouts/footer');
		
					
	}

	function press(){
		
		$data['text'] = 'Press...';
		
		if($this->session->userdata['user']){
			$fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($this->session->userdata['user']);
			$this->loggedInID = $fbu->id;
		
			//function to load the left panel data counts
			$leftPanelData = $this->getLeftPanelCounts($this->loggedInID);
		
        
			//load the left panel data count view
			$data['leftPanelCount'] = $this->load->view('layouts/leftpanelcount', $leftPanelData, true);		
		}		
		
		
        //load the header view
        $this->load->view('layouts/header');
        $this->load->view('page', $data);
        
		//load the footer view
        $this->load->view('layouts/footer');
		
					
	}

	function help(){
		
		$data['text'] = 'Help...';
		
		if($this->session->userdata['user']){
			$fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($this->session->userdata['user']);
			$this->loggedInID = $fbu->id;
		
			//function to load the left panel data counts
			$leftPanelData = $this->getLeftPanelCounts($this->loggedInID);
		
        
			//load the left panel data count view
			$data['leftPanelCount'] = $this->load->view('layouts/leftpanelcount', $leftPanelData, true);		
		}		
		
		
        //load the header view
        $this->load->view('layouts/header');
        $this->load->view('page', $data);
        
		//load the footer view
        $this->load->view('layouts/footer');
		
					
	}

	function termofservice(){
		
		$data['text'] = 'Terms of Service...';
		
		if($this->session->userdata['user']){
			$fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($this->session->userdata['user']);
			$this->loggedInID = $fbu->id;
		
			//function to load the left panel data counts
			$leftPanelData = $this->getLeftPanelCounts($this->loggedInID);
		
        
			//load the left panel data count view
			$data['leftPanelCount'] = $this->load->view('layouts/leftpanelcount', $leftPanelData, true);		
		}		
		
		
        //load the header view
        $this->load->view('layouts/header');
        $this->load->view('page', $data);
        
		//load the footer view
        $this->load->view('layouts/footer');
		
					
	}

	function privacypolicy(){
		
		$data['text'] = 'Privacy Policy...';
		
		if($this->session->userdata['user']){
			$fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($this->session->userdata['user']);
			$this->loggedInID = $fbu->id;
		
			//function to load the left panel data counts
			$leftPanelData = $this->getLeftPanelCounts($this->loggedInID);
		
        
			//load the left panel data count view
			$data['leftPanelCount'] = $this->load->view('layouts/leftpanelcount', $leftPanelData, true);		
		}		
		
		
        //load the header view
        $this->load->view('layouts/header');
        $this->load->view('page', $data);
        
		//load the footer view
        $this->load->view('layouts/footer');
		
					
	}

	function blog(){
		
		$data['text'] = 'Blog...';
		
		if($this->session->userdata['user']){
			$fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($this->session->userdata['user']);
			$this->loggedInID = $fbu->id;
		
			//function to load the left panel data counts
			$leftPanelData = $this->getLeftPanelCounts($this->loggedInID);
		
        
			//load the left panel data count view
			$data['leftPanelCount'] = $this->load->view('layouts/leftpanelcount', $leftPanelData, true);		
		}		
		
		
        //load the header view
        $this->load->view('layouts/header');
        $this->load->view('page', $data);
        
		//load the footer view
        $this->load->view('layouts/footer');
		
					
	}

	function twitter(){
		
		$data['text'] = 'Twitter...';
		
		if($this->session->userdata['user']){
			$fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($this->session->userdata['user']);
			$this->loggedInID = $fbu->id;
		
			//function to load the left panel data counts
			$leftPanelData = $this->getLeftPanelCounts($this->loggedInID);
		
        
			//load the left panel data count view
			$data['leftPanelCount'] = $this->load->view('layouts/leftpanelcount', $leftPanelData, true);		
		}		
		
		
        //load the header view
        $this->load->view('layouts/header');
        $this->load->view('page', $data);
        
		//load the footer view
        $this->load->view('layouts/footer');
		
					
	}

	function facebook(){
		
		$data['text'] = 'Facebook...';
		
		if($this->session->userdata['user']){
			$fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($this->session->userdata['user']);
			$this->loggedInID = $fbu->id;
		
			//function to load the left panel data counts
			$leftPanelData = $this->getLeftPanelCounts($this->loggedInID);
		
        
			//load the left panel data count view
			$data['leftPanelCount'] = $this->load->view('layouts/leftpanelcount', $leftPanelData, true);		
		}		
		
		
        //load the header view
        $this->load->view('layouts/header');
        $this->load->view('page', $data);
        
		//load the footer view
        $this->load->view('layouts/footer');
		
					
	}

	function pinterest(){
		
		$data['text'] = 'PInterest...';
		
		if($this->session->userdata['user']){
			$fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($this->session->userdata['user']);
			$this->loggedInID = $fbu->id;
		
			//function to load the left panel data counts
			$leftPanelData = $this->getLeftPanelCounts($this->loggedInID);
		
        
			//load the left panel data count view
			$data['leftPanelCount'] = $this->load->view('layouts/leftpanelcount', $leftPanelData, true);		
		}		
		
		
        //load the header view
        $this->load->view('layouts/header');
        $this->load->view('page', $data);
        
		//load the footer view
        $this->load->view('layouts/footer');
		
					
	}

	function contactus(){
		
		$data['text'] = 'Contactus...';
		
		if($this->session->userdata['user']){
			$fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($this->session->userdata['user']);
			$this->loggedInID = $fbu->id;
		
			//function to load the left panel data counts
			$leftPanelData = $this->getLeftPanelCounts($this->loggedInID);
		
        
			//load the left panel data count view
			$data['leftPanelCount'] = $this->load->view('layouts/leftpanelcount', $leftPanelData, true);		
		}		
		
		
        //load the header view
        $this->load->view('layouts/header');
        $this->load->view('page', $data);
        
		//load the footer view
        $this->load->view('layouts/footer');
		
					
	}

	function mailtmpl(){
		
		$data['text'] = 'Contactus...';
		
		if($this->session->userdata['user']){
			$fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($this->session->userdata['user']);
			$this->loggedInID = $fbu->id;
		
			//function to load the left panel data counts
			$leftPanelData = $this->getLeftPanelCounts($this->loggedInID);
		
        
			//load the left panel data count view
			$data['leftPanelCount'] = $this->load->view('layouts/leftpanelcount', $leftPanelData, true);		
		}		
		
		
        //load the header view
        //$this->load->view('layouts/header');
        $this->load->view('layouts/mailtmpl/welcome', $data);
        
		//load the footer view
        //$this->load->view('layouts/footer');
		
					
	}
	
 
}
