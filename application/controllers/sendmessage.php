<?php
class sendmessage extends Controller
{
  
    
    function index($arr){
        $fbdetails = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($arr);
        $username = $fbdetails->username;
		$this->session->set_userdata('CandiUsername',$username);
		redirect('/facebooker', 'refresh');
    }
    
}
