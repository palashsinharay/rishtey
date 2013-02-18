<?php
class inviteFriends extends Controller {
	
    function inviteFriends() {
        parent::Controller();
    }
	
	//function to get all FB friends data from textfile
    public function getFriendFromFile() {
			if($this->session->userdata['user']) {
                $fbid = $this->session->userdata['user'];
                $fbp = Doctrine::getTable('FbProcess')->findOneByFb_user_id($fbid);
                $fileName = $fbp->filename;
				
                //read the file, unserialize it and store it in an array variable
                $fl = APPPATH . "/files/friendlist/" . $fileName;
                $string = read_file($fl);
                $friends = unserialize($string);
                foreach ($friends['data'] as $key => $value) {
					
                    $q = Doctrine_Query::create()
                            ->select('fum.username')
                            ->from('FbUserMaster fum,RcUserMaster rum')
                            ->where('fum.id = rum.fk_loc_fb_id')
                            ->andWhere('fum.fb_user_id = ?', $value['id']);
                    //echo $q->getSqlQuery();
					
                    $rows = $q->execute();
                    $result = $rows->toArray();
					
                    $rcId = $result[0]['id'];
					
					
                    //condition to check if user is existing rc user
                    if ($rcId) {
                        //is a rc user
                    } else {
                        $data['allFbFriends'][] = $value;
                    }
                }
				
				
                $fbAppData = $this->config->item('fbAppData');
                $data['fbAppId'] = $fbAppData['appId'];
                
                //load the header view
                $this->load->view('layouts/header');
                $this->load->view('inviteFriend', $data);
                //load the footer view
                $this->load->view('layouts/footer');
            } else{
                redirect('/', 'refresh');
            }
    }
	
}

?>
