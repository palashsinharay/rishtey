<?php
ini_set('date.timezone', 'Asia/Kolkata');
require_once(APPPATH.'controllers/facebooker.php');
class Dashboard extends facebooker
{  
    public static $bucket;
    function __construct(){
       parent::Controller();
       $this->load->helper('url'); 
       $config['accessKey'] = $this->config->item('accessKey');
       $config['secretKey'] = $this->config->item('secretKey');
       self::$bucket = $this->config->item('bucket');
       $this->checkLogin();
    }

	//base function
    function index() {
            if($this->session->userdata['user']){
                $fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($this->session->userdata['user']);
                $this->loggedInID = $fbu->id;
                
				
                $fbAppData = $this->config->item('fbAppData');
                $dataHelpFriend['random_suggested_friend'] = parent::getHelpFriend($this->session->userdata['user']);
                $dataHelpFriend['fbAppId'] = $fbAppData['appId'];
                $dataHelpFriend['suggestedfriends'] = parent::getSuggestedfriends($this->loggedInID); //load the getSuggestedfriends() function from the parent controller
                
				//function to load the left panel data counts
                $leftPanelData = $this->getLeftPanelCounts($this->loggedInID);
                
				//load the left panel data count view
                $data['leftPanelCount'] = $this->load->view('layouts/leftpanelcount', $leftPanelData, true);
				
                //load the help friend section view
                $data['helpFriendData'] = $this->load->view('layouts/helpfrienddata', $dataHelpFriend, true);
				
				//prepare the candidate dropdown for the logged in user
                $sql = "SELECT rp.fb_user_id, concat(rp.fname,' ',rp.lname) as fullname FROM rc_profiles AS rp ,rc_profile_relation As pr WHERE rp.fb_user_id = pr.fb_user_id AND pr.type = 'G' AND pr.fb_guardian = " . $this->session->userdata['user']. " ORDER BY rp.created_at DESC";
                $pdo = Doctrine_Manager::connection()->getDbh();
                $resultSet = $pdo->query($sql)->fetchAll();
                foreach ($resultSet as $value) {
                    $candifbid = $value[fb_user_id];
                    $candidateLists[$candifbid] = $value[fullname];
                }			
                  
				
                $data['candidateListId'] = $candidateLists;
                //$data['recommendationUpdates'] = $this->recommendationUpdate();
                //$data['interestUpdate'] = $this->showInterestUpdate();
                //$data['blockedUpdate'] = $this->blockedUpdate();
                //print_r($data['interestUpdate']);die();
                $data['bucket'] = self::$bucket;
				
                if($candidateLists == null){
                    redirect('/candidate/addcandidate');
                }else{
				
                }
                
				//load the header view
                $this->load->view('layouts/header');
                $this->load->view('dashboard/dashboard', $data);
                //load the footer view
                $this->load->view('layouts/footer');
            }else{
                redirect('/facebooker', 'refresh');
            }
        }
		
    /**
     * function to check logined in user is guardian or not and return actual guardian
     * @param int $fbId facebook ID
     * @return array
     */
    function checkGuardian($fbId){
      $guardianFbId = $this->session->userdata['user'];
      $cadidateFbId = $fbId;
        
		/*$q = Doctrine_Query::create()
            ->select('r.status')
            ->from('RcProfileRelation r')
            ->where('r.fb_user_id = ?',$cadidateFbId)
            ->andWhere('r.fb_guardian = ?',$guardianFbId);
		
        $row1 = $q->execute();
        $row1->toArray();*/

        $g = "SELECT * FROM `rc_profile_relation` WHERE `fb_user_id` = $cadidateFbId AND `status` = 1";
        $pdo = Doctrine_Manager::connection()->getDbh();       
        $guardian = $pdo->query($g)->fetchAll();
        if($guardian[0]['fb_guardian'] == null){
           $sql = "SELECT * FROM `rc_profile_relation` WHERE `fb_user_id` = $cadidateFbId AND `fb_guardian` = $guardianFbId AND `type` = 'G' AND `status` = 0";
           $pdo = Doctrine_Manager::connection()->getDbh();       
           $guardian = $pdo->query($sql)->fetchAll();
           
        }
        
        $data['guardian_id'] = $guardian[0]['fb_guardian'];
		
		$data['other_fb_user_id'] = $guardian[0]['other_fb_user_id'];
		
		$data['guardian_fk_loc_fb_id'] = $guardian[0]['guardian_fk_loc_fb_id'];
        
        $data['logged_in_user'] = ($data['guardian_id'] == $this->session->userdata['user']) ? 1 : 0; 
        
		
        return $data; 
    }
    //function to get candidate profile details from rc_profile table (ajax call)
    function getProfileDetails() {
            //$fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($this->session->userdata['user']);
            //$this->loggedInID = $fbu->id;
			
			
            if ($_POST['userid'] == 'no-profile')
                echo ''; else {
                //data for form prefillup field
                $rcp = Doctrine::getTable('RcProfiles')->findOneByFb_user_id($_POST['userid']);
                $fbdetails = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($_POST['userid']);
					
                  /*
                  if(!$rcp){
                  redirect('dashboard', 'location');
                  }*/
				
                $guardian = $this->checkGuardian($_POST['userid']);
				
                if($guardian['guardian_id']){
                    $guardianDetails = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($guardian['guardian_id']);
                    $data['uGuardianName'] = $guardianDetails->fname.' '.$guardianDetails->lname;
                }				
				
				//prepare relation dropdown for "Help your friends" section
				$sqlGetRelations = "SELECT * FROM rc_recommrelation_master WHERE 1";
				$pdo = Doctrine_Manager::connection()->getDbh();
				$resultGetRelations = $pdo->query($sqlGetRelations)->fetchAll();			
				
				
				foreach($resultGetRelations as $key => $option){
						$relOptStr .= '<option value="'.$option["id"].'">'.$option["relation"].'</option>';
				}
								
                $data['uStatus'] = $guardian['logged_in_user']; 
				
                $data['uFname'] = $rcp->fname;
                $data['uLname'] = $rcp->lname;
                $data['uGender'] = $rcp->gender;
                $data['uBirthday'] = date("d-m-Y", strtotime($rcp->dob));
                $data['uRelationship'] = $rcp->marital_status;
                $data['uReligion'] = $rcp->religion;
                $data['uMtongue'] = $rcp->mother_tongue;
                $resultSet = Doctrine::getTable('RcCasteMaster')->findOneById($rcp->caste);
                $data['uCast'] = $resultSet->caste;
                $data['uHeightFt'] = (int) ($rcp->height / 12);
                $data['uHeightInch'] = $rcp->height % 12;
                $data['uLocation'] = $rcp->location;
                $data['uHeducation'] = $rcp->highest_education;
                $data['uhEducationDes'] = $rcp->education_des;
                $data['uProfession'] = $rcp->profession;
                $data['uprofessionDes'] = $rcp->profession_des;
                $data['uAsalary'] = $rcp->salary;
				$data['status'] = $rcp->status;
                //$data['uSrecommendation'] = $rcp->short_recommendation;
				
                $data['biodata'] = $rcp->biodata;
				
				$data['relOptStr'] = $relOptStr;
				
				$data['other_fb_user_id'] = $guardian['other_fb_user_id'];
				
				$data['guardian_id'] = $guardian['guardian_id'];
				
				$data['guardian_fk_loc_fb_id'] = $guardian['guardian_fk_loc_fb_id'];
				
                $data['cPicture'] = ($this->getFbPicture($_POST['userid']) != null) ? $this->getFbPicture($_POST['userid']) : $_POST['userid']."__1__fb.jpg";
                $data['canPictures'] = $this->getProfilePictures($_POST['userid']);				
                
				
                $data['canPictures2'] = $data['canPictures'][0];
                $data['canPictures3'] = $data['canPictures'][1];
                $data['canPictures4'] = $data['canPictures'][2];
                $data['canPictures5'] = $data['canPictures'][3];
					
                //$data['uPicture'] = $fbu->picture;
                //$data['fb_id'] = $this->session->userdata['user'];	 
                
				$data['fbId'] = $_POST['userid'];
                $rcr = Doctrine::getTable('RcRecommendations')->findOneByOther_fr_fb_user_id($_POST['userid']);
                $data['uSrecommendation'] = $rcr->recommendation;
                $data['relation'] = $rcr->relationship;
                unset($rcp,$fbdetails,$guardianDetails);
                echo json_encode($data);
            }
            die();
        }
	
    
    //function to get candidate Match Preference details from rc_profile_preference table (ajax call)
    function matchpre(){
	
    $rcpp = Doctrine::getTable('RcProfilePreference')->findOneByFb_user_id($_POST['userid']);
    $data['fromAge'] = $rcpp->from_age;
    $data['toAge'] = $rcpp->to_age;
    $data['maritalStatus'] = $rcpp->marital_status;
    $data['religion'] = $rcpp->religion;
    $data['motherTongue'] = $rcpp->mother_tongue;
    $data['caste'] = $rcpp->caste;
    $data['fromHeight'] = $rcpp->from_height;
    $data['toHeight'] = $rcpp->to_height;
    $data['minEducation'] = $rcpp->min_education;
    $data['profession'] = $rcpp->profession;
    $data['minSalary'] = $rcpp->min_salary;
	
    echo json_encode($data);
	
    }	
    

	//*************validation functions***********//START
    public function religion_check($str){
                            if(in_array($_POST['religion'],$this->config->item('religion')))
                                    return true;
                            else
                                    $this->form_validation->set_message('religion_check', 'Please select a valid Religion');
                                    return false;	
            }
    public function lan_check($str){
                            if(in_array($_POST['mTongue'],$this->config->item('mTongue')))
                                    return true;
                            else
                                    $this->form_validation->set_message('lan_check', 'Please select a valid Mother Tongue');
                                    return false;	
            }
    public function cast_check($str){
                            $sqlGetCaste = "SELECT caste FROM rc_caste_master";  
                $pdo = Doctrine_Manager::connection()->getDbh();       
                    $resultGetCaste = $pdo->query($sqlGetCaste)->fetchAll();
                            foreach($resultGetCaste as $key => $option){
                                    $casts[$key] = $option['caste'];
                            }
                            if(in_array($_POST['caste'],$casts))
                                    return true;
                            else
                                    $this->form_validation->set_message('cast_check', 'Please select a valid Cast');
                                    return false;	
            }
    public function edu_check($str){
                            if(in_array($_POST['hEducation'],$this->config->item('hEducation')))
                                    return true;
                            else
                                    $this->form_validation->set_message('edu_check', 'Please select a valid Highest Education');
                                    return false;	
            }
    //custom location validation function
    public function location_check($str){
                    if(filter_var(filter_var($_POST['location'], FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^[a-zA-Z\s0-9]+$/")))))
                            return true;
                    else
                            $this->form_validation->set_message('location_check', 'Please enter a valid %s');
                            return false;	
    }
    //custom profession validation function
    public function profession_check($str){
                    if(filter_var(filter_var($_POST['profession'], FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))) || $_POST['profession'] == "" )
                            return true;
                    else
                            $this->form_validation->set_message('profession_check', 'Please enter a valid %s');
                            return false;	
    }
    //custom salary validation function
    public function salary_check($str){
                 $sal = $_POST['salary'];
                    if(filter_var(filter_var($_POST['salary'], FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^-?([0-9])+([\.|,]([0-9])*)?$/")))) || $_POST['salary'] == "" )
                            return true;
                    else
                            $this->form_validation->set_message('salary_check', 'Please enter a valid %s');
                            return false;	
    }
	
	//*************validation functions***********//END
	//age calculator 
    public function getAge($birth, $now = NULL) {
        $now = new DateTime($now);
        $birth = new DateTime($birth);
        return $birth->diff($now)->format('%r%y');
    }
	
	//get Recommendation update message for today form rc_profile_update_message table
    public function recommendationUpdate($fbid) {
        $sql = "SELECT * FROM rc_profile_update_message WHERE fb_user_id=$fbid AND action='recommendation' ORDER BY DATE(created_at) DESC LIMIT 3";
        //$sql = "SELECT * FROM rc_profile_update_message WHERE fb_user_id=$fbid AND action='recommendation'";
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultSet = $pdo->query($sql)->fetchAll();
        foreach ($resultSet as $value) {
            $RecommenderLogMessage[] = $value['update_message'];
        }
		
        return $RecommenderLogMessage;
		
    }
    
	//get InterestUpdate message ajax
    public function recommendationUpdateAjax($fbid){
        $recommendationMsg = $this->recommendationUpdate($fbid);
        if($recommendationMsg != NULL) {
        foreach ($recommendationMsg as $key => $value) {
            echo '<li>'.$value.'</li>';
        }
        
        }
    }
	
	//get Interest message for today form rc_profile_update_message table
    public function blockedUpdate($fbid) {
        $sql = "SELECT * FROM rc_profile_update_message WHERE fb_user_id=$fbid AND action='blocked' ORDER BY DATE(created_at) DESC LIMIT 3";
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultSet = $pdo->query($sql)->fetchAll();
        foreach ($resultSet as $value) {
            $blockedLogMessage[] = $value['update_message'];
        }

        return $blockedLogMessage;
		
    }
    
	//get InterestUpdate message ajax
    public function blockedUpdateAjax($fbid){
        $blockedMsg = $this->blockedUpdate($fbid);
        if($blockedMsg !=NULL){
        foreach ($blockedMsg as $key => $value) {
            echo '<li>'.$value.'</li>';
        }
        }
    }
	
	//get Interest message for today form rc_profile_update_message table
    public function showInterestUpdate($fbid) {
        $sql = "SELECT * FROM rc_profile_update_message WHERE fb_user_id=$fbid AND action='Interest request to guardian' ORDER BY DATE(created_at) DESC LIMIT 3";
        //$sql = "SELECT * FROM rc_profile_update_message WHERE fb_user_id=$fbid AND action='Interest request'";
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultSet = $pdo->query($sql)->fetchAll();
        foreach ($resultSet as $value) {
            $InterestLogMessage[] = $value['update_message'];
        }

        return $InterestLogMessage;
		
    }
	
	//get InterestUpdate message ajax
    public function showInterestUpdateAjax($fbid){
        $showInterestMsg = $this->showInterestUpdate($fbid);
        if($showInterestMsg !=NULL){
            foreach ($showInterestMsg as $key => $value) {
            echo '<li>'.$value.'</li>';
        }
        
        }
    }
    
	//get Interest message for today form rc_profile_update_message table
    public function showInterestReceivedUpdate($fbid) {
        $sql = "SELECT * FROM rc_profile_update_message WHERE fb_user_id=$fbid AND action='Received Interest request' ORDER BY DATE(created_at) DESC LIMIT 3";
        //$sql = "SELECT * FROM rc_profile_update_message WHERE fb_user_id=$fbid AND action='Interest request'";
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultSet = $pdo->query($sql)->fetchAll();
        foreach ($resultSet as $value) {
            $InterestReceivedLogMessage[] = $value['update_message'];
        }
		
        return $InterestReceivedLogMessage;
		
    }
	
	//get InterestUpdate message ajax
    public function showInterestReceivedUpdateAjax($fbid){
        $showReceivedInterestMsg = $this->showInterestReceivedUpdate($fbid);
        
		if(count($showReceivedInterestMsg) > 0){
			foreach ($showReceivedInterestMsg as $key => $value) {
				echo '<li>'.$value.'</li>';
			}
			
		}
		
    }    
    
	
	//get new match message for today form rc_profile_update_message table
    public function newMatchUpdate($fbid) {
        $sql = "SELECT * FROM rc_profile_update_message WHERE fb_user_id=$fbid AND action='match' ORDER BY DATE(created_at) DESC LIMIT 3";
        //$sql = "SELECT * FROM rc_profile_update_message WHERE fb_user_id=$fbid AND action='match'";
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultSet = $pdo->query($sql)->fetchAll();
        foreach ($resultSet as $value) {
            $newMatchMessage[] = $value['update_message'];
        }
		
        return $newMatchMessage;

    }
	//get new match message ajax
    public function newMatchUpdateAjax($fbid){
        $matchMsg = $this->newMatchUpdate($fbid);
        if($matchMsg !=NULL){
            foreach ($matchMsg as $key => $value) {
            echo '<li>'.$value.'</li>';
        }
        }
    }
    public function getMatchId($fbid){
    //Get rc_profiles table primary key id from facebookid $cid
    $rcp = Doctrine::getTable('RcProfiles')->findOneByFb_user_id($fbid);
    //$sql = "SELECT * FROM rc_matched_profile WHERE cid=$rcp->id AND is_blocked = 0";
    $sql = "SELECT a.cid_matched FROM `rc_matched_profile` as a , `rc_matched_profile` as b WHERE a.`cid` = b.`cid_matched` AND a.`cid_matched` = b.`cid` AND a.`is_blocked` = 0 AND b.`is_blocked` = 0 AND a.`cid` = $rcp->id";
    $pdo = Doctrine_Manager::connection()->getDbh();       
    $resultSet = $pdo->query($sql)->fetchAll();
    foreach($resultSet as $value){
            $AllMatchID[]= $value['cid_matched'];
			}
    
	
    echo $this->profileDetails($AllMatchID,$rcp->id,$fbid);
    unset($rcp);
	}

    public function getMaritalStatus($id){
	$rrm = Doctrine::getTable('RcRelationMaster')->findOneById($id);
	$mstatus = $rrm->relation_name;
	unset($rrm);
	return $mstatus;
	
	}
    
	public function getProfession($id){
	$rpm = Doctrine::getTable('RcProfessionMaster')->findOneById($id);
	$prof = $rpm->profession;
	unset($rpm);
	return $prof;
	}

    public function getEducation($id){
	$rpm = Doctrine::getTable('RcEducationMaster')->findOneById($id);
	$edu = $rpm->education;
	unset($rpm);
	return $edu;
	}

    public function getReligion($id){
    $rrm = Doctrine::getTable('RcReligionMaster')->findOneById($id);
	$rel = $rrm->religion_name;
	unset($rrm);
	return $rel;
        
    }
    
    public function getMotherTongue($id){
        
    $rmm = Doctrine::getTable('RcMtongueMaster')->findOneById($id);
	$lang = $rmm->language_name;
	unset($rmm);
	return $lang;
        
    }
		
    public function convertHeightMetric($height){
		$ft = floor($height/12); 
		$inch = $height%12;
	return $ft.' Feet '.$inch.' Inches';
	}

    public function getImage($id){
	$rcp = Doctrine::getTable('RcProfiles')->findOneById($id);
        $rcum = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($rcp->fb_user_id);
	$image = $rcum->picture;
	unset($rcum);
	return $image;	

	}
    
	public function setAbuse($id,$mid){
			
    $q = Doctrine_Query::create()
    ->update('RcMatchedProfile')
    ->set('is_abused', 1)
    ->where('cid_matched = ?',$id)
    ->andWhere('cid = ?', $mid);
    $rows = $q->execute();
	
    echo $rows;
    $abuser = Doctrine::getTable('RcProfiles')->findOneById($id);
    $abused = Doctrine::getTable('RcProfiles')->findOneById($mid);
    $this->AbuseMailToAdmin($abused->fb_user_id, $abuser->fname.' '.$abuser->lname);
    unset($abuser);
    unset($abused);
	}
    
	//function to share profile  
    public function shareProfile($id,$mid){
       
    $toMail = $_POST['email'];
    
	//prepare the pdf file to be sent as attachment
	$pdf=new HTML2FPDF();
	
	$pdf->AddPage();
	
	$strContent = $_POST['strcontent'];
	$msg = $_POST['msg'];		
	
	$pdf->WriteHTML($strContent);
	
	//absolute path where the file is to be uploaded
	$folderPath = APPPATH.'files/pdf/';
	
	$pdf->Output($folderPath."sample_".$id.".pdf");
	
	//echo "PDF file is generated successfully!";
	//die();
	
    $sender = Doctrine::getTable('RcProfiles')->findOneById($id);
    $shareProfile = Doctrine::getTable('RcProfiles')->findOneById($mid);
	
    $this->sendMail($sender->fb_user_id,$toMail,$shareProfile->fb_user_id, $msg);
    
	unset($sender);
    unset($shareProfile);
        
    }

    public function setBlocked($id,$mid){
			
    $q = Doctrine_Query::create()
    ->update('RcMatchedProfile')
    ->set('is_blocked', 1)
    ->where('cid_matched = ?',$mid)
    ->andWhere('cid = ?', $id);
    $rows = $q->execute();
    $blocker = Doctrine::getTable("RcProfiles")->findOneById($mid);
    $blocked = Doctrine::getTable("RcProfiles")->findOneById($id);
    
    $this->blockedLog($blocker->fb_user_id, $blocked->fb_user_id);
    unset($blocker);
    unset($blocked);
	
    echo $rows;
	
	}
	
	//block message log
    public function blockedLog($blockerFbId, $blockedFbId) {
        //below is the code to save log information on blocked 
        
        $fbu1 = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($blockerFbId);
        $fbu2 = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($blockedFbId);
        //get details of loged in user
        $fbu3 = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($this->session->userdata['user']);
        
        $action = 'blocked';
        $referer = $_SERVER["HTTP_REFERER"];
        $fbUserId = $this->session->userdata['user']; //new to pud the fb id on loged in user
        $ownCandidateId = $blockerFbId;
        $otherCandidateId = $blockedFbId;
        $page = $_SERVER['REQUEST_URI'];
        $msg = '<span>'.$fbu3->fname.'</span> has blocked <span>'.$fbu2->fname.'</span>'.' for '.'<span>'.$fbu1->fname.'</span>';
        $state = (isset($_REQUEST['state'])) ? $_REQUEST['state'] : '';
        $code = (isset($_REQUEST['code'])) ? $_REQUEST['code'] : '';
        $timestamp = time();

        //creatng the blocked log.   
        $this->rc_log_save($action, $referer, $fbUserId, $page, $msg, $state, $code, $timestamp, $ownCandidateId, $otherCandidateId);
        unset($fbu1);
        unset($fbu2);
        unset($fbu3);
    }

	//facebook friend count
    public function countFbFriend($id){
    $rcp = Doctrine::getTable("RcProfiles")->findOneById($id);
    $fum = Doctrine::getTable("FbUserMaster")->findOneByFb_user_id($rcp->fb_user_id);
    $friendCount = $this->getDirectFriends($fum->id);
    unset($rcp);
    unset($fum);
    return $friendCount;
    //return $rcp->fb_user_id;
	}

	//function to get interest shown from rc_profile_show_interest table
    public function getInterestMsg($fbId){
        $rcp = Doctrine::getTable("RcProfiles")->findOneByFb_user_id($fbId);
        $candidateName = $rcp->fname; 
        $q = Doctrine_Query::create()
            ->select('DISTINCT (cid) as rcpid')
            ->from('RcProfileShowInterest')
            ->where('cid_matched = ?',$rcp->id);
            
        $rows = $q->execute();
        
        $data =  $rows->toArray();
        
        foreach ($data as $key => $value) {
		
            $rcp2 = Doctrine::getTable("RcProfiles")->findOneById($value['rcpid']);
            echo $rcp2->fname.' has shown interest on '.$rcp->fname;
            echo '<br>';
            unset($rcp2);
        }
		
        unset($rcp);
		
    }

	//function to get Interest message form rc_profile_show_interest
    public function getMessage($fbid){
       
        $pid = $this->getFbidToPid($fbid);
        
        $q = Doctrine_Query::create()
            ->select('DISTINCT (cid_matched) as person_pid')
            ->from('RcProfileShowInterest')
            ->where('cid = ?',$pid);
		
        $rows1 = $q->execute();
        if($q->count() > 0){
        $resultSet1 =  $rows1->toArray();
            foreach ($resultSet1 as $key => $value) {
                $data1[] = $value[person_pid];
            }
            $data = $data1;
        }
        $q->delete();
		       
        $q = Doctrine_Query::create()
            ->select('DISTINCT (cid) as person_pid')
            ->from('RcProfileShowInterest')
            ->where('cid_matched = ?',$pid);
           
            
        $rows2 = $q->execute();
        
        if($q->count() > 0){
            $resultSet2 =  $rows2->toArray();
         foreach ($resultSet2 as $key => $value) {
             $data2[] = $value[person_pid];
        }
        if(isset($data1)){
            $data = array_unique(array_merge($data1,$data2));
        }
            $data = $data2;
        }
        $q->delete();      
        
        
        foreach ($data as $key => $value) {
            $reciver_fbid = $this->getPidToFbid($value); 
              
            $q2 = Doctrine_Query::create()
            ->select('*')
            ->from('RcProfileShowInterest')
            ->where('cid = ?',$pid)
            ->andWhere('cid_matched = ?',$value)
            ->orWhere('cid = ?',$value)
            ->andWhere('cid_matched = ?',$pid)
            ->orderBy('created_at ASC');
        
            $rows2 = $q2->execute();
            $q2->delete();
            $data2 =  $rows2->toArray();
            
            
            foreach ($data2 as $key => $value2) {
                $data2[$key]['cid'] = $this->getPidToFbid($value2['cid']);
                $data2[$key]['cid_name'] = $this->getPidToName($value2['cid']);
                $data2[$key]['cid_matched'] = $this->getPidToFbid($value2['cid_matched']);
                $data2[$key]['cid_matched_name'] = $this->getPidToName($value2['cid_matched']);
                $data2[$key]['image'] = "https://graph.facebook.com/".$this->getPidToFbid($value2['cid'])."/picture?width=107&height=107";
                
                $data2[$key]['sender'] = $pid;
                $data2[$key]['sender_name'] = $this->getPidToName($pid);
                $data2[$key]['sender_fbid'] = $this->getPidToFbid($pid);
                $sg_fbid = $this->getGaurdianFbid($this->getPidToFbid($pid));
                $data2[$key]['sender_gaurdian_name'] = $this->getFbidToName($sg_fbid);
                
                $data2[$key]['reciver'] = $value['person_pid'];
                $data2[$key]['reciver_name'] = $this->getPidToName($value['person_pid']);
                $data2[$key]['reciver_fbid'] = $this->getPidToFbid($value['person_pid']);
                $rg_fbid = $this->getGaurdianFbid($this->getPidToFbid($value['person_pid']));
                $data2[$key]['reciver_gaurdian_name'] = $this->getFbidToName($rg_fbid);
            }
            
            //$chatUserid = $value['person_pid'].'.'.$pid;
            $message[$reciver_fbid] = $data2;           
			
        }
        
        echo json_encode($message);
		       
    }
    //TODO 11th feb 2013
    public function getSingleMsg($s_fbid,$r_fbid){
        
        $s_pid = $this->getFbidToPid($s_fbid);
        $r_pid = $this->getFbidToPid($r_fbid);
            
            $q2 = Doctrine_Query::create()
            ->select('*')
            ->from('RcProfileShowInterest')
            ->where('cid = ?',$s_pid)
            ->andWhere('cid_matched = ?',$r_pid)
            ->orWhere('cid = ?',$r_pid)
            ->andWhere('cid_matched = ?',$s_pid)
            ->orderBy('created_at ASC');
            $rows2 = $q2->execute();
            $q2->delete();
            $data2 =  $rows2->toArray();
            //$this->pr($data2);
             
            foreach ($data2 as $key => $value2) {
                $data2[$key]['cid'] = $this->getPidToFbid($value2['cid']);
                $data2[$key]['cid_name'] = $this->getPidToName($value2['cid']);
                $data2[$key]['cid_matched'] = $this->getPidToFbid($value2['cid_matched']);
                $data2[$key]['cid_matched_name'] = $this->getPidToName($value2['cid_matched']);
                $data2[$key]['image'] = "https://graph.facebook.com/".$this->getPidToFbid($value2['cid'])."/picture?width=107&height=107";
                
                $data2[$key]['sender'] = $s_pid;
                $data2[$key]['sender_name'] = $this->getPidToName($s_pid);
                $data2[$key]['sender_fbid'] = $this->getPidToFbid($s_pid);
                $sg_fbid = $this->getGaurdianFbid($this->getPidToFbid($s_pid));
                $data2[$key]['sender_gaurdian_name'] = $this->getFbidToName($sg_fbid);
                
                $data2[$key]['reciver'] = $r_pid;
                $data2[$key]['reciver_name'] = $this->getPidToName($r_pid);
                $data2[$key]['reciver_fbid'] = $r_fbid;
                $rg_fbid = $this->getGaurdianFbid($r_fbid);
                $data2[$key]['reciver_gaurdian_name'] = $this->getFbidToName($rg_fbid);
            }
            
            //$chatUserid = $value['person_pid'].'.'.$pid;
            $message = $data2;
            //$this->pr($message);
            echo json_encode($message);
    }
    
        //funtion to get candidate fb pic from database]
   public function getImageTag1($fbid) {
       $dql = Doctrine_Query::create()
               ->select("picture")
               ->from("RcProfilePicture")
               ->where("fb_user_id = ?",$fbid)
               ->andWhere("img_tag_id = 1");
       $resultset = $dql->execute();
       $image = $resultset->toArray();
       if($image[0]['picture'] == NULL){
         echo "http://".self::$bucket.".s3.amazonaws.com/files/profile_images/thumbs/".$fbid."__1__fb.jpg";  
       }else{
        echo "http://".self::$bucket.".s3.amazonaws.com/files/profile_images/thumbs/".$image[0]['picture'];   
       }
       
        
       
        } 


        //show Interest message
    public function showInterest($id, $mid) {
		        
        $rpi = new RcProfileShowInterest;
        $rpi->cid = $mid;
        $rpi->cid_matched = $id;
        $rpi->interest_message = $_POST['msg'];
        $rpi->save();
        unset($rpi);
        $sender = Doctrine::getTable("RcProfiles")->findOneById($mid);
        $reciver = Doctrine::getTable("RcProfiles")->findOneById($id);
		
        $this->showInterestLog($sender->fb_user_id, $reciver->fb_user_id);
        unset($sender);
        unset($reciver);
    }
	//show Interest message log
    public function showInterestLog($senderFbId, $reciverFbId) {
        
		//below is the code to save log information on showInterest         
        $fbu1 = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($senderFbId);
        $fbu2 = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($reciverFbId);
        
		//get details of loged in user
        $fbu3 = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($this->session->userdata['user']);
        
        $action = 'Interest request';
        $referer = $_SERVER["HTTP_REFERER"];
        $fbUserId = $this->session->userdata['user']; //new to pud the fb id on loged in user
        $ownCandidateId = $senderFbId;
        $otherCandidateId = $reciverFbId;
        $page = $_SERVER['REQUEST_URI'];
        $msg = '<span>'.$fbu3->fname.'</span>'.' has shown interest on '.'<span>'.$fbu2->fname.'</span> for '.$fbu1->fname;
        $state = (isset($_REQUEST['state'])) ? $_REQUEST['state'] : '';
        $code = (isset($_REQUEST['code'])) ? $_REQUEST['code'] : '';
        $timestamp = time();
		
        //creatng the access-denied log.   
        $this->rc_log_save($action, $referer, $fbUserId, $page, $msg, $state, $code, $timestamp, $ownCandidateId, $otherCandidateId);
        unset($fbu1);
        unset($fbu2);
    }
	
	// create the popup for match candidates
    public function profileDetails($matchIdArray, $cid ,$Mfbid){
	//$matchIdArray has the list of match candidate's rc_profile table's primary key.
	//$cid has the rc_profile table's primary key for whome the match list is found.
	//$Mfbid has the facebook id for whome the match list is found.
        
    $j = 0;
    if($matchIdArray !=NULL){
        
    $popup_cnt = count($matchIdArray);   
    
    foreach ($matchIdArray as $key => $value) {
		
        //$class = 'match-colft';
        if (($j % 2) == 0) {
            $class = 'match-colft';
            $str .= '<div class="match-col" id="match-popup-'.$j.'">';
        } else {
            $class = 'match-colrght';
        }
		
        $mCanid = $value;
        $sql = "SELECT rp.*, fum.picture FROM rc_profiles AS rp, fb_user_master AS fum WHERE rp.fk_loc_fb_id=fum.id AND rp.id=$mCanid";
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultSet = $pdo->query($sql)->fetchAll(Doctrine_Core::FETCH_ASSOC);
        
        $i = $resultSet[0][id];
        $name = $resultSet[0][fname];
        $lname = $resultSet[0][lname];
        $fbid = $resultSet[0][fb_user_id];
        //$str .= $name; 
        $dob = $resultSet[0][dob];
        $location = $resultSet[0][location];
        $religion = $this->getReligion($resultSet[0][religion]);
        $language = $this->getMotherTongue($resultSet[0][mother_tongue]);
        $education = $this->getEducation($resultSet[0][highest_education]);
        $edu_desc = $resultSet[0][education_des];
        $profession = $this->getProfession($resultSet[0][profession]);
        $pro_desc = $resultSet[0][profession_des];
        $caste = $resultSet[0][caste];
        $height = $this->convertHeightMetric($resultSet[0][height]);
        $marital_status = $this->getMaritalStatus($resultSet[0][marital_status]);
        $hierarchyStr = $this->hierarchyBuilder($Mfbid, $fbid);
        //$image = $this->getImage($mCanid);
			

		 $imageThumb = $resultSet[0][picture];
         $imageSmall = 'http://'.self::$bucket.'.s3.amazonaws.com/files/profile_images/thumbs/'.$fbid.'__1__fb.jpg';
         $imageLarge = 'http://'.self::$bucket.'.s3.amazonaws.com/files/profile_images/'.$fbid.'__1__fb.jpg';

         
         $sql2 = "SELECT * FROM rc_profile_picture WHERE fb_user_id=$fbid";
         $pdo2 = Doctrine_Manager::connection()->getDbh();
         $resultSet2 = $pdo2->query($sql2)->fetchAll(Doctrine_Core::FETCH_ASSOC);
         $noImage = "no_profile_picture.jpg";
         $noImageUrl = "http://".self::$bucket.".s3.amazonaws.com/files/profile_images/thumbs/".$noImage;
                  
         //$recommenderList = "";
         $recommenderList = $this->recommendationGet($fbid);
         $imgHTML = "";
         $imgFB = '<a href="JavaScript:void(0);" onclick=JavaScript:changeFBImage("'.$imageLarge.'",'.$i.') ><img src="'.$imageSmall.'" width="50" height="50" alt="" /></a>';
		 	
         $imgFBForPdf = '<img src="'.$imageLarge.'" width="275" height="275" alt="" />';
	
               
                 
         foreach ($resultSet2 as $key => $value) {
             $imgThumb[$key] = 'http://'.self::$bucket.'.s3.amazonaws.com/files/profile_images/thumbs/'.$value[picture];
             
            
             $s3url = 'http://'.self::$bucket.'.s3.amazonaws.com/files/profile_images/thumbs/'.$value[picture];
             $datafromurl = file_get_contents($s3url);
             $localfileName = $_SERVER['DOCUMENT_ROOT'] . '/' . APPPATH . 'files/profile_images/'.$value[picture];
             file_put_contents($localfileName, $datafromurl);
             $imgThumbForPdf[$key] = $localfileName;
             //$imgLarge[$key] = base_url().APPPATH."files/profile_images/".$value[picture];
             $imgLarge[$key] = $value[picture];
             $imgHTML .= '<a href="JavaScript:void(0);" onclick=JavaScript:changeImage("'.$imgLarge[$key].'",'.$i.') ><img src="'.$imgThumb[$key].'" width="50" height="50" alt="" /></a>';
			 
			$imgPdfHTML .= '<tr><td align="left" valign="top"><img src="'.$imgThumbForPdf[$key].'" width="275" height="275" alt="" /></td></tr>
							<tr><td align="left" valign="top">&nbsp;</td></tr>';				
			
          }
          
        if ($resultSet[0][gender] == 1)
            $gender = 'Male';
        if ($resultSet[0][gender] == 2)
            $gender = 'Female';
			
        
        $str_pop .='<div class="popupBox" id="popupone-' . $i . '" style="display:none;">
                <div class="popupCont"><span class="close-ico"><a href="JavaScript:void(0);" class="close" onclick="JavaScript:closePop(' . $i . ')"><img src="' . base_url() . 'images/cross-
icon.gif" width="12" height="12" alt="" /></a></span>
                  <div class="clear overflow">
                    <div class="match-img"><img id="largeImage-'.$i.'" src="' . $imageLarge . '" width="275" height="275"  alt="" />
                      
                          
                      <div class="profileThumb">'.$imgFB.$imgHTML.
                       
                        '<div class="clear"></div>

                        <div class="linkRow left"><a id="' . $j . '" class="abuse" href="JavaScript:abuse(' . $i . ',' . $cid . ','.$j.');">Report abuse</a></div>
                        <div class="linkRow left leftPadd"><a id="' . $j . '" class="blockPeo" href="JavaScript:block(' . $i . ',' . $cid . ','.$j.');">Block people</a></div>
                        <div class="clear"></div>
                      </div>
                      <span id="usermsg-'.$i.'" style="font-size: 11px;" class="successful"></span>     
                    </div>
                    <div class="match-info">
                      <h5><span>' . substr($name,0,1) . ' ' . $lname . '</span></h5>
                      
                      <!-- Default open this section #1st start here -->
                      <div id="panel-1-'.$j.'" class="clear overflow">
                        <div class="left">
                        
                        <div class="info-row">
                          <div class="match-label width75">Age:</div>
                          <div class="match-value">' . $this->getAge($dob) . '</div>
                        </div>
                        <div class="info-row">
                          <div class="match-label width75">Lives In:</div>
                          <div class="match-value">' . $location . '</div>
                        </div>
                        <div class="info-row">
                          <div class="match-label width75">Education:</div>
                          <div class="match-value">' . $education . '</div>
                        </div>
                        <div class="info-row">
                          <div class="match-label width75">Education Text:</div>
                          <div class="match-value">' . $edu_desc . '</div>
                        </div>
                        <div class="info-row">
                          <div class="match-label width75">Caste:</div>
                          <div class="match-value">'.$this->getCasteName($caste).'</div>
                        </div>
                        <div class="info-row">
                          <div class="match-label width75">Marital Status:</div>
                          <div class="match-value">' . $marital_status . '</div>
                        </div>
                        
                        </div>
                        <div class="right">
                        
                        <div class="info-row">
                          <div class="match-label width55">Religion:</div>
                          <div class="match-value">' . $religion . '</div>
                        </div>
						<div class="info-row">
                          <div class="match-label width55">Works In:</div>
                          <div class="match-value">' . $profession . '</div>
                        </div>
                        <div class="info-row">
                          <div class="match-label width55">Work Text:</div>
                          <div class="match-value">' . $pro_desc . '</div>
                        </div>
						<div class="info-row">
                          <div class="match-label width55">Height:</div>
                          <div class="match-value">' . $height . '</div>
                        </div>
                        <div class="info-row">
                          <div class="match-label width55">Language:</div>
                          <div class="match-value">' . $language . '</div>
                        </div>
                        
                        </div>
                        <div class="clear"></div>
                        
                        <div class="spacer"></div>
                        </br>
                        <div class="info-row">
                            <div class="recomendationBreadcumb">'.$hierarchyStr.'</div>
                        </div>
                        <div class="spacer"></div>
                       </br>
                      <div id="recomendation"><table>'.$recommenderList.'</table></div>
                          <div class="spacer"></div>
                          </br>
                          <button id="shareprofile-1-'.$j.'" class="btn-submit" type="submit" title="Share Profile" onclick="JavaScript:openPanel3('.$j.')"><span><span>Share 
Profile</span></span></button>
                        <button id="sendmsg-1-'.$j.'" class="btn-submit" type="submit" title="Send Message" onclick="JavaScript:openPanel2('.$j.')"><span><span>Send Message</span></span></button>
                      </div>
                      
                      <!-- /Default open this section #1st end here -->

                      <!-- Open this section #2nd after clicking the send message button from the prev div start here -->
                      <div id="panel-2-'.$j.'" class="clear overflow" style="display:none;margin: 5px 15px 15px 0;">
                        <div class="info-row">
                          <!--<div class="match-label">Message:</div>-->
                          <div class="clear"></div>
                          <div class="match-value">
                            <textarea name="textarea" id="msg-'.$i.'" cols="59" rows="5"></textarea>
                          </div>
                        </div>
                        <button class="btn-submit" type="submit" title="Send" onclick="JavaScript:sendMessage(' . $i . ',' . $cid . ','.$j.')"><span><span>Send</span></span></button>
                        <button class="btn-submit" type="reset" title="Cancel" onclick="JavaScript:openPanel1('.$j.')"><span><span>Cancel</span></span></button>
                        <div class="spacer"></div>
                        <span id="showmsg-'.$i.'" style="font-size: 11px;" class="successful"></span>
                      </div>
                      <!-- /Open this section #2nd after clicking the send message button from the prev div start here -->
                      <!--Open this div section on share profile button click Start here-->
                      <div id="panel-3-'.$j.'" class="clear overflow" style="display:none;margin: 5px 15px 15px 0;">
                        <p>We will be mailing the details of this match to the email addresss in a PDF file</p>
                        <div class="info-row">
                          <!--<div class="match-label">Email:</div>-->
                          <div class="clear"></div>
                          <label><input style="width:304px; " placeholder="Type the email address" type="text" id="email-'.$i.'" name="email-'.$i.'"/></label>
                          <div class="clear"></div>    
                          <!--<div class="match-label">Message:</div>-->
                          <div class="clear"></div>
                          <div class="match-value">
                            <textarea name="textarea" id="emailMsg-'.$i.'" cols="59" rows="5"></textarea>
                          </div>
                        </div>
                        <button class="btn-submit" type="submit" title="Share profile" onclick="JavaScript:shareProfile(' . $i . ',' . $cid . ','.$j.')"><span><span>Share profile</span></span></button>
                        <button class="btn-submit" type="reset" title="Cancel" onclick="JavaScript:openPanel1('.$j.')"><span><span>Cancel</span></span></button>
                         <div class="spacer"></div>
                        <span id="shareProfileMsg-'.$i.'" style="font-size: 11px;" class="successful"></span>
                      </div>

                      <!--Open this div section on share profile button click End here-->
                    </div>
                  </div>
                </div>
              </div>';

			
			
			if($edu_desc!=''){
				$strEduDesc = '('.$edu_desc.')';
			}
				
			if($pro_desc!=''){
				$strProDesc = '('.$pro_desc.')';
			}
			
			
			$str .= '<div style="display:none;" id="tolargepdf_'.$i.'">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td align="left" valign="top"><h5><span>' . substr($name,0,1) . ' ' . $lname . '</span></h5></td>
								</tr>
								<tr>
									<td align="left" valign="top">&nbsp;</td>
								</tr>
							</table>
			
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="400" align="left" valign="top"><span class="match-label">Age :</span> <span class="match-value">' . $this->getAge($dob) . '</span></td>
									<td align="left" valign="top">&nbsp;</td>
								</tr>
								<tr>
									<td align="left" valign="top"><span class="match-label">Lives In :</span> <span class="match-value">' . $location . '</span></td>
									<td align="left" valign="top"><span class="match-label">Religion :</span> <span class="match-value">' . $religion . '</span></td>
								</tr>
								<tr>
									<td align="left" valign="top"><span class="match-label">Education :</span> <span class="match-value">' . $education . '</span></td>
									<td align="left" valign="top"><span class="match-label">Profession :</span> <span class="match-value">' . $profession . '</span></td>
								</tr>
								<tr>
									<td align="left" valign="top">' . $strEduDesc . '</td>
									<td align="left" valign="top">' . $strProDesc . '</td>
								</tr>
								<tr>
									<td align="left" valign="top"><span class="match-label">Caste :</span> <span class="match-value">'.$this->getCasteName($caste).'</span></td>
									<td align="left" valign="top"><span class="match-label">Height :</span> <span class="match-value">' . $height . '</span></td>
								</tr>
								<tr>
									<td align="left" valign="top"><span class="match-label">Marital Status :</span> <span class="match-value">' . $marital_status . '</span></td>
									<td align="left" valign="top"><span class="match-label">Mother Tongue :</span> <span class="match-value">' . $language . '</span></td>
								</tr>
								<tr>
									<td align="left" valign="top">&nbsp;</td>
									<td align="left" valign="top">&nbsp;</td>
								</tr>
							</table>
			
							<table width="100%" border="0" cellspacing="0" cellpadding="0">'.$recommenderList.'</table>
							
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td align="left" valign="top">'.$imgFBForPdf.'</td>
								</tr>
								<tr>
									<td align="left" valign="top">&nbsp;</td>
								</tr>'.
								$imgPdfHTML								
							.'</table>
				
					 </div>';	
			
			
		
        $str .='<div class="' . $class . '"><div id="topdf_'.$i.'"><div id="cont-' . $j . '" class="network-wrapper">
						
						<div class="match-img width38">
							<span class="ico-attach">&nbsp;</span><a href="JavaScript:void(0);"><img width="107" height="107" alt="" src="' . $imageSmall . '" onclick="javascript:showPop(' . $i . ');"></a>
						</div>
                        <div class="match-info width48">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
						  	<tr class="info-row">
								<td align="left" valign="top"><h5>' . substr($name,0,1) . ' ' . $lname . '</h5></td>
							</tr>
						  </table>
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
							  <tr class="info-row">
								<td width="40%" align="left" valign="top" class="match-label">Age:</td>
								<td align="left" valign="top" class="match-value">' . $this->getAge($dob) . '</td>
							  </tr>
							  <tr class="info-row">
								<td width="40%" align="left" valign="top" class="match-label">Lives In:</td>
								<td align="left" valign="top" class="match-value">' . $location . '</td>
							  </tr>
							  <tr class="info-row">
								<td width="40%" align="left" valign="top" class="match-label">Profession:</td>
								<td align="left" valign="top" class="match-value">' . $profession . '</td>
							  </tr>
							  <tr class="info-row">
								<td width="40%" align="left" valign="top" class="match-label">Caste:</td>
								<td align="left" valign="top" class="match-value">' . $this->getCasteName($caste) . '</td>
							  </tr>
							  <tr class="info-row">
								<td width="40%" align="left" valign="top" class="match-label">Height:</td>
								<td align="left" valign="top" class="match-value">' . $height . '</td>
							  </tr>
							  <tr class="info-row">
								<td width="40%" align="left" valign="top" class="match-label">M Status:</td>
								<td align="left" valign="top" class="match-value">' . $marital_status . '</td>
							  </tr>
							</table>
						  
						  </div>
						
               </div></div></div>';
		
        if(($j%2)==0) {         
            if($j==($popup_cnt-1)){
                $str .= $str_pop; $str_pop ='';
            }
        
        } else{  $str .= $str_pop;  $str .= '</div>'; $str_pop ='';  }
        
        
        //$str .= '</div>'; 
        $j++;
    }
    }
    //$str .= '</div>';
    return $str;
    }

	//function to build hierarchy string ongoing..
    public function hierarchyBuilder($masterFbid,$childFbid) {
        //get candidates name
        $Mfum = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($masterFbid);
        $Cfum = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($childFbid);
        $Mfum->fname;
        $Cfum->fname;
        
		//get guardian fbid
        $Mrpr = Doctrine::getTable('RcProfileRelation')->findOneByfb_user_id($masterFbid);
        $Crpr = Doctrine::getTable('RcProfileRelation')->findOneByfb_user_id($childFbid);
        
		//get guardian name
        $MfumG = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($Mrpr->fb_guardian);
        $CfumG = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($Crpr->fb_guardian);
        $MfumG->fname;
        $CfumG->fname;
        
        //build the relation string
        if($MfumG->id == $CfumG->id){        
        	$str = $Mfum->fname.' >> '.$MfumG->fname.' >> <span>'.$Cfum->fname.'</span>';
		}elseif($MfumG->id != $CfumG->id){
				
			//check if direct friend
			$sqlChkDirectFriend = "SELECT * FROM extended_network AS en WHERE en.loc_fb_id = ".$MfumG->id." AND en.loc_fr_fb_id = ".$CfumG->id." AND en.is_indirect_friend = 0";
		    $pdo = Doctrine_Manager::connection()->getDbh();
            $resultChkDirectFriend = $pdo->query($sqlChkDirectFriend)->fetchAll();
			
			if(count($resultChkDirectFriend) > 0){  
				$str = $Mfum->fname.' >> '.$MfumG->fname.' >> '.$CfumG->fname.' >> <span>'.$Cfum->fname.'</span>';
			}else{
			
				//check if indirect friend and if so, find the common friend
				$sqlChkIndirectFriend = "SELECT en.*, fum.fname FROM `extended_network` AS en, fb_user_master AS fum
												 WHERE en.`loc_fr_fb_id` = fum.id
												 AND en.`loc_fb_id` = ".$MfumG->id."
												 AND en.`is_indirect_friend` = 0
												 AND en.`loc_fr_fb_id` IN(SELECT en.`loc_fb_id` FROM `extended_network` AS en WHERE en.`loc_fr_fb_id` = ".$CfumG->id.")";
				$resultChkIndirectFriend = $pdo->query($sqlChkIndirectFriend)->fetchAll(Doctrine_Core::FETCH_ASSOC);						
							
				$str = $Mfum->fname.' >> '.$MfumG->fname.' >> '.$resultChkIndirectFriend[0]['fname'].' >> '.$CfumG->fname.' >> <span>'.$Cfum->fname.'</span>';
			}
			
		}else{
			$str = '';
		}
        
        return $str;
        
    }
	
	//function to show selected candidate image and name on Network Status panel
    public function getUserImgName(){
			/*$q = Doctrine_Query::create()
            ->select('fname,lname,picture')
            ->from('FbUserMaster')
            ->where('fb_user_id = ?',$fbid);
            
        $rows = $q->execute();
        $q->delete();
        $data =  $rows->toArray();*/

		$strCandidates = '';
		
		$sqlGetAllCandidates = "SELECT rp.fb_user_id, rp.fname, rp.lname, fum.picture
										FROM rc_profiles AS rp, rc_profile_relation AS pr, fb_user_master AS fum
										WHERE rp.fb_user_id = pr.fb_user_id
										AND pr.fb_user_id = fum.fb_user_id
										AND pr.type = 'G'
										AND pr.fb_guardian = " . $this->session->userdata['user'];
		
		$pdo = Doctrine_Manager::connection()->getDbh();
		$resultGetAllCandidates = $pdo->query($sqlGetAllCandidates)->fetchAll();
			
		
		$strCandidates .= "<li>Your Candidates</li>";
		foreach ($resultGetAllCandidates as $data) {
			
			$strCandidates .= "<li><img src='http://".$this->config->item('bucket').".s3.amazonaws.com/files/profile_images/thumbs/".$data['fb_user_id']."__1__fb.jpg"."' width='32' height='32' style='float:left;'/><span style='padding: 5px 0px 0px 5px; float:left;'>".$data['fname']." ".$data['lname']."</span></li>";
		   
		}
		
			echo $strCandidates;
		
    }
	
}
 