<?php
ini_set('date.timezone', 'Asia/Kolkata');
require_once(APPPATH.'controllers/facebooker.php');
class generalDashboard extends facebooker
{
   
 
    function __construct(){
       parent::Controller();
       $this->load->helper('url');
      // $this->load->library('facebook/facebook');
     }

    function index() {
           
            if($this->session->userdata['user']){
                $fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($this->session->userdata['user']);
                $this->loggedInID = $fbu->id;
                
                //load facebook app date
                $fbAppData = $this->config->item('fbAppData');
                $dataHelpFriend['random_suggested_friend'] = parent::getHelpFriend($this->session->userdata['user']);
                $dataHelpFriend['fbAppId'] = $fbAppData['appId'];
                
                //load the getSuggestedfriends() function from the parent controller
                $dataHelpFriend['suggestedfriends'] = parent::getSuggestedfriends($this->loggedInID); 
                //function to load the left panel data counts
                $leftPanelData = $this->getLeftPanelCounts($this->loggedInID);
                
                //load the left panel data count view
                $data['leftPanelCount'] = $this->load->view('layouts/leftpanelcount', $leftPanelData, true);
                //load the help friend section view
                $data['helpFriendData'] = $this->load->view('layouts/helpfrienddata', $dataHelpFriend, true);

                //load the header view
                $this->load->view('layouts/header');
                $this->load->view('dashboard/generalDashboard', $data);
                //load the footer view
                $this->load->view('layouts/footer');
            }else{
                redirect('facebooker', 'refresh');
            }
        }

}
 