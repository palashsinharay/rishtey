<?php
class recommendation extends Controller {

function index($arr,$arr2){
					
					$fbdetails = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($arr);
			        $data['username'] = $fbdetails->username;
					$data['fname'] = $fbdetails->fname;
					$data['lname'] = $fbdetails->lname;
					$data['gender'] = $fbdetails->gender;	
					$data['candidateFbId'] = $arr;
					$data['recommenderFbId'] = $arr2;
					$this->load->view('layouts/header');
					$this->load->view('recommendation',$data);	
					$this->load->view('layouts/footer');
	
}
	
}