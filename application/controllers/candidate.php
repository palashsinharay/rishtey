<?php

ini_set('date.timezone', 'Asia/Kolkata');
require_once(APPPATH . 'controllers/facebooker.php');

class Candidate extends facebooker {
    public static $bucket;

function __construct() {
        parent::Controller();
        $this->load->helper('url');
        
        $this->checkLogin();
        $this->suggetionlistStatus($this->session->userdata['user']);
        $config['accessKey'] = $this->config->item('accessKey');
        $config['secretKey'] = $this->config->item('secretKey');
        self::$bucket = $this->config->item('bucket');
        $this->load->library('S3',$config);
    }
    
	//1st step of the add candidate process
    function addCandidate() {
		
		//unset sessions used during candidate creation from   	
		//$this->session->unset_userdata('CandidateUsername');
		//$this->session->unset_userdata('CandidateProfileId');
		//unset sessions used during candidate creation from
        if (isset($this->session->userdata['CandidateUsername']))
            $this->session->unset_userdata('CandidateUsername');
		
        if (isset($this->session->userdata['CandidateProfileId']))
            $this->session->unset_userdata('CandidateProfileId');
		
          /*
          if(isset($this->session->userdata['CandidateGender']))
          $this->session->unset_userdata('CandidateGender');
		
          if(isset($this->session->userdata['CandidateAge']))
          $this->session->unset_userdata('CandidateAge');
		
          if(isset($this->session->userdata['CandidateHeight']))
          $this->session->unset_userdata('CandidateHeight'); */
		
		  	
        $fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($this->session->userdata['user']);
        $this->loggedInID = $fbu->id;
		
        //function to load the left panel data counts
        $leftPanelData = $this->getLeftPanelCounts($this->loggedInID);
		
        $data['fbUserId'] = $fbu['fb_user_id'];
		
        //$dataHelpFriend['random_suggested_friend'] = parent::getHelpFriend($this->session->userdata['user']);
		
        $fbAppData = $this->config->item('fbAppData');
        $dataHelpFriend['fbAppId'] = $fbAppData['appId'];
		
        $fr_user = $this->getSinglefriends($this->loggedInID);
		
        $dataHelpFriend['suggestedfriends'] = parent::getSuggestedfriends($this->loggedInID); //for accessing getSuggestedfriends() function for the autosuggest box on the addcandidate view page 
        //load the left panel data count view
        $data['leftPanelCount'] = $this->load->view('layouts/leftpanelcount', $leftPanelData, true);
		
		//prepare relation dropdown for "Helpyour friends" section
		$sqlGetRelations = "SELECT * FROM rc_recommrelation_master WHERE 1";
		$pdo = Doctrine_Manager::connection()->getDbh();
                $resultGetRelations = $pdo->query($sqlGetRelations)->fetchAll();		
		
		
		$dataHelpFriend['recommendationRelations'] = $resultGetRelations;
		
        //load the help friend section view
        $data['helpFriendData'] = $this->load->view('layouts/helpfrienddata', $dataHelpFriend, true);
		
		//unset 'tab_no' cookie
		echo '<script type="text/javascript">
				document.cookie = "tab_no=; expires=-1 UTC; path=/candidate"	
			  </script>';
		
        //load the header view
        $this->load->view('layouts/header');
        $this->load->view('candidate/addcandidate', $data);
        
		//load the footer view
        $this->load->view('layouts/footer');
    }

	//function to add recomendation same function is called to update also 
    /*function addRecommendation() {
	
        if ($_POST['recommendation'] == '') {
            echo 'no recommendation added';
        } else {
            
            $rcr = new RcRecommendations;
            
            $rcr->fb_user_id = $_POST['recommenderFbId'];
            
            $rcr->fr_fb_user_id = $_POST['candidateFbId'];
            $rcr->other_fr_fb_user_id = $_POST['candidateFbId'];
		
            $rcr->relationship = $_POST['relationship'];
            $rcr->recommendation = $_POST['recommendation'];
		
            $rcr->type = $_POST['type'];
		
            $rcr->save();
			
            //below is the code to save log information on Recommendation
            $action = 'Recommendation';
            $referer = $_SERVER["HTTP_REFERER"];
            $fbUserId = isset($this->session->userdata['user'])?$this->session->userdata['user']:$_POST['recommenderFbId'];
            $ownCandidateId = $_POST['candidateFbId'];
            $otherCandidateId = $_POST['candidateFbId'];
            $page = $_SERVER['REQUEST_URI'];
            $fbu = Doctrine::getTable('RcProfiles')->findOneByFb_user_id($_POST['recommenderFbId']);
            $rcp = Doctrine::getTable('RcProfiles')->findOneByFb_user_id($_POST['candidateFbId']);
            
            $msg = $fbu->fname . ' has recommended ' . $rcp->fname;            
			
            $state = (isset($_REQUEST['state'])) ? $_REQUEST['state'] : '';
            $code = (isset($_REQUEST['code'])) ? $_REQUEST['code'] : '';
			
            //$date = new DateTime();
            //$timestamp = $date->getTimestamp();
			
            $timestamp = time();
			
            //creatng the access-denied log.   
            $this->rc_log_save($action, $referer, $fbUserId, $page, $msg, $state, $code, $timestamp, $ownCandidateId, $otherCandidateId);
			
            //unset all objects after db insertaion 
            unset($rcr);
            unset($fbu);
            unset($rcp);
        }
    }*/
    
    function casteInsert() {
		
        $resultSet = Doctrine::getTable('RcCasteMaster')->findAll();
        foreach ($resultSet as $value) {
            $avilableCaste[$value->id] = $value->caste;
        }
        $key = array_search(ucwords($_POST['caste']), $avilableCaste);
			
        
        if (!in_array(ucwords($_POST['caste']), $avilableCaste)) {
            $rcm = new RcCasteMaster;
            $rcm->caste = ucwords($_POST['caste']);
            $rcm->save();
            if ($rcm->id) {
                $key = $rcm->id;
                unset($rcm);
                echo $key;
                exit;
            } else {
                //do nothing
                //unset($rcm);
                //return 0;
            }
        } else {
            echo $key;
        }
    }
	
    function casteGet() {
		
        $casts = Array();
		
        $sqlGetCaste = "SELECT * FROM rc_caste_master";
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultGetCaste = $pdo->query($sqlGetCaste)->fetchAll();
        
        //$casts[0] = 'Any';
        foreach ($resultGetCaste as $key => $option) {
            //$id = (int) $option['id'];
            //$castswithkey[$id] = $option['caste'];
            $casts[] = $option['caste'];
        }
        
        //$anykeys = implode(',',array_keys($castswithkey));
        //$castswithkey[$anykeys] = 'Any';
        
        echo json_encode($casts);
        //echo json_encode($castswithkey);
        
    }
    
    function casteGetForMulti() {
		
        $casts = Array();
		
        $sqlGetCaste = "SELECT * FROM rc_caste_master";
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultGetCaste = $pdo->query($sqlGetCaste)->fetchAll();
        
        $casts[0] = 'Any';
        foreach ($resultGetCaste as $key => $option) {
            $id = (int) $option['id'];
            $casts[$id] = $option['caste'];
            
        }
        echo json_encode($casts);
        //echo json_encode($castswithkey);
        
    }
	
    function relationGet() {
		
        $relations = Array();
		
        $sqlGetCaste = "SELECT * FROM rc_relation_master";
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultGetCaste = $pdo->query($sqlGetCaste)->fetchAll();
        $relations[0] = 'Any';
        foreach ($resultGetCaste as $option) {
            $relations[$option['id']] = $option['relation_name'];
        }
		
        echo json_encode($relations);
    }
	
    function mTongueGet() {
		
        $sqlGetCaste = "SELECT * FROM rc_mtongue_master";
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultGetCaste = $pdo->query($sqlGetCaste)->fetchAll();
        $languages[0] = 'Any';
        foreach ($resultGetCaste as $option) {
            $languages[$option['id']] = $option['language_name'];
        }
		
        echo json_encode($languages);
    }
	
    function religionGet() {
		
        $sqlGetCaste = "SELECT * FROM rc_religion_master";
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultGetCaste = $pdo->query($sqlGetCaste)->fetchAll();
        $religion[0] = 'Any';
        foreach ($resultGetCaste as $option) {
            $religion[$option['id']] = $option['religion_name'];
        }
		
        echo json_encode($religion);
    }
	
    function educationGet() {
		
        $sqlGetCaste = "SELECT * FROM rc_education_master";
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultGetCaste = $pdo->query($sqlGetCaste)->fetchAll();
        $education[0] = 'Any';
        foreach ($resultGetCaste as $option) {
            $education[$option['id']] = $option['education'];
        }
		
        echo json_encode($education);
    }	
	
    function professionGet() {
		
        $sqlGetCaste = "SELECT * FROM rc_profession_master";
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultGetCaste = $pdo->query($sqlGetCaste)->fetchAll();
        $profession[0] = 'Any';
        foreach ($resultGetCaste as $option) {
            $profession[$option['id']] = $option['profession'];
        }
		
        echo json_encode($profession);
    }
	
	//function to get recommendation relation
    /*function recommrelationGet() {
		
        $sqlGetCaste = "SELECT * FROM rc_recommrelation_master";
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultGetCaste = $pdo->query($sqlGetCaste)->fetchAll();
		
        foreach ($resultGetCaste as $option) {
            $relation[$option['id']] = $option['relation'];
        }
		
        echo json_encode($relation);
    }*/

	//function to get candidate data to candidate creation tab
    function candidateProfile($candiUsername = null) {
        $fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($this->session->userdata['user']);
        $this->loggedInID = $fbu->id;
        $data = $this->getSinglefriends($this->loggedInID);
        $data['fb_user_id'] = $fbu['fb_user_id'];
        $data['username'] = $fbu['username'];
        $data['fullname'] = $fbu['fname'] . ' ' . $fbu['lname'];
		
        $dataHelpFriend['random_suggested_friend'] = $this->getHelpFriend($this->session->userdata['user']);
		
        
        //check if candiadte data is comming from myself radio button element or candidatename input element 
        //$this->session->set_userdata('CandidateUsername',(isset($_POST['candidateMe'])) ? $_POST['candidateMe'] : $_POST['candidate']) ;
		
		
        if (!isset($this->session->userdata['CandidateUsername'])) {
            $this->session->set_userdata('CandidateUsername', (isset($_POST['candidateMe'])) ? $_POST['candidateMe'] : $_POST['frCandidateName']);
        }
        
		//to check data if candidate creation data comming form message link
        if (isset($this->session->userdata['CandiUsername'])) {
            $candidateUsername = $this->session->userdata['CandiUsername'];
            $this->session->unset_userdata('CandiUsername');
        } else {
            $candidateUsername = isset($_POST['candidateMe']) ? $_POST['candidateMe'] : $_POST['frCandidateName'];
        }
		
        $fbdetails = Doctrine::getTable('FbUserMaster')->findOneByUsername($candidateUsername);
		
        $data['cFname'] = $fbdetails->fname;
        $data['cLname'] = $fbdetails->lname;
        $data['cGender'] = $fbdetails->gender;
        $data['cBirthday'] = $fbdetails->birthday;
        $data['cRelationship'] = $fbdetails->relationship_status;
        //$data['cPicture'] = $fbdetails->picture;
		
        $data['can_fb_user_id'] = $fbdetails->fb_user_id;
		
        $data['cPicture'] = ($this->getFbPicture($data['can_fb_user_id']) != null) ? $this->getFbPicture($data['can_fb_user_id']) : '';
		
		if($data['cPicture']==''){
			//save large image from facebook
			$url = 'http://graph.facebook.com/'.$data['can_fb_user_id'].'/picture?width=275&height=275';
			$urlSmall = 'http://graph.facebook.com/'.$data['can_fb_user_id'].'/picture?width=107&height=107';
			
			$dataLargeImage = file_get_contents($url);
			$dataSmallImage = file_get_contents($urlSmall);
			
			$fileName = $_SERVER['DOCUMENT_ROOT'] . '/' . APPPATH . 'files/profile_images/'. $data['can_fb_user_id'].'__1__fb.jpg';
                            $fileNameS3 = 'files/profile_images/'. $data['can_fb_user_id'].'__1__fb.jpg';
			$fileNameSmall = $_SERVER['DOCUMENT_ROOT'] . '/' . APPPATH . 'files/profile_images/thumbs/'. $data['can_fb_user_id'].'__1__fb.jpg';
                            $fileNameSmallS3 = 'files/profile_images/thumbs/'. $data['can_fb_user_id'].'__1__fb.jpg';
                        
			//$file = fopen($fileName, 'w+') or die("can't open file");
			//$fileSmall = fopen($fileNameSmall, 'w+') or die("can't open thumb file");
                        
			
			file_put_contents($fileName, $dataLargeImage);
                        //fputs($fileName, $dataLargeImage);
			//fclose($file);
			
			file_put_contents($fileNameSmall, $dataSmallImage);
                        //fputs($fileNameSmall, $dataSmallImage);
			//fclose($fileSmall);
                       
                        $this->s3->putObjectFile($fileName, self::$bucket, $fileNameS3, S3::ACL_PUBLIC_READ);
                        $this->s3->putObjectFile($fileNameSmall, self::$bucket, $fileNameSmallS3, S3::ACL_PUBLIC_READ);
			
		}
				
		/*$ch = curl_init();
		$timeout = 0;
		curl_setopt ($ch, CURLOPT_URL, 'http://graph.facebook.com/shashankvaishnav/picture');
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);
		$fileName = 'fb_profilepic.jpg';
		$file = fopen($fileName, 'w+');
		fputs($file, $data);
		fclose($file);*/
				
        //$this->session->set_userdata('CandidateGender',$fbdetails->gender) ;
        $this->session->set_userdata('CandidateProfileId', $fbdetails->fb_user_id);
        $data['candidateFbId'] = $fbdetails->fb_user_id;
        //$this->session->set_userdata('CandidateAge',$this->getAge($fbdetails->birthday, date('Y-m-d H:i:s')));
        $data['canPictures'] = $this->getProfilePictures($fbdetails->fb_user_id);
		      
		
        $data['canPictures2'] = $data['canPictures'][0];
        $data['canPictures3'] = $data['canPictures'][1];
        $data['canPictures4'] = $data['canPictures'][2];
        $data['canPictures5'] = $data['canPictures'][3];
		
		
        $leftPanelData = $this->getLeftPanelCounts($this->loggedInID);
		
        $fbAppData = $this->config->item('fbAppData');
        $dataHelpFriend['fbAppId'] = $fbAppData['appId'];
		
        $dataHelpFriend['suggestedfriends'] = parent::getSuggestedfriends($this->loggedInID);
        //load the header part
        //$data['header'] = $this->load->view('layouts/header');
        //load the left panel data count view
        $data['leftPanelCount'] = $this->load->view('layouts/leftpanelcount', $leftPanelData, true);
		
		//prepare relation dropdown for "Help your friends" section
		$sqlGetRelations = "SELECT * FROM rc_recommrelation_master WHERE 1";
		$pdo = Doctrine_Manager::connection()->getDbh();
        $resultGetRelations = $pdo->query($sqlGetRelations)->fetchAll();
				
		
		$dataHelpFriend['recommendationRelations'] = $resultGetRelations;
		
        //load the help friend section view
        $data['helpFriendData'] = $this->load->view('layouts/helpfrienddata', $dataHelpFriend, true);
		
        //load all Friends
        $data['allFbFriends'] = parent::getAllFbfriends($this->loggedInID);        
	$data['bucket'] = self::$bucket;	

        //load the header view
        $this->load->view('layouts/header');
        $this->load->view('candidate/candidatecreation', $data);
        //load the footer view
        $this->load->view('layouts/footer');
    }
		
	//function to add candidate data to rc_profiles and rc_profile_relation  
    function addCandidateProfile() {
        
        $this->form_validation->set_rules('dob', 'Date of birth', 'required|callback_dob_check');
      
        if ($this->form_validation->run() == FALSE) {
            //validation error reload candidateprofile form	
            echo form_error('dob');
            
            //die();
        } else {
			
            // logic to insert data into rc_profile table	
            $fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($_POST['profileFbId']);
            //check if record already exists in rc_profiles table
            $rcp = Doctrine::getTable('RcProfiles')->findOneByFb_user_id($_POST['profileFbId']);
			
            if (!$rcp->id) {
                $rcp = new RcProfiles;
            }
            
			$rcp->fb_user_id = $_POST['profileFbId'];
            $rcp->fk_loc_fb_id = $fbu->id;
            $rcp->fname = $_POST['fName'];
            $rcp->lname = $_POST['lName'];
            $rcp->gender = $_POST['gender'];
            $rcp->dob = date("Y-m-d", strtotime($_POST['dob']));
            $rcp->marital_status = $_POST['relationship'];
            $rcp->religion = $_POST['religion'];
            $rcp->mother_tongue = $_POST['mTongue'];
            $resultSet = Doctrine::getTable('RcCasteMaster')->findOneByCaste($_POST['caste']);
            $rcp->caste = $resultSet->id;
            $rcp->height = $_POST['heightFt'] * 12 + $_POST['heightInch'];
            $rcp->location = $_POST['location'];
            $rcp->highest_education = $_POST['hEducation'];
            $rcp->education_des = $_POST['hEducationDes'];
            $rcp->profession = $_POST['profession'];
            $rcp->profession_des = $_POST['professionDes'];
            $rcp->salary = $_POST['salary'];
            $rcp->short_recommendation = $_POST['sRecommendation'];
			
            //save to database
            $rcp->save();
			
            //$rprChk = Doctrine::getTable('RcProfileRelation')->findOneByFb_user_id($_POST['profileFbId']);
            //check if profile exists for guardian
            $sqlChkRecordExists = "SELECT * FROM  rc_profile_relation WHERE fb_user_id = " . $_POST['profileFbId'] . " AND fb_guardian = " . $this->session->userdata['user'] . " AND type='G'";
            $pdo = Doctrine_Manager::connection()->getDbh();
            $resultChkRecordExists = $pdo->query($sqlChkRecordExists)->fetchAll();
			
            //if(!$rprChk->id)
            if (count($resultChkRecordExists) == 0) {
                //get guardian details
                $fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($this->session->userdata['user']);
			
                //get candidate details
                $fbc = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($_POST['profileFbId']);
					
                // insert entry to rc_profile_relation table to maintain guardian refference
                $rpr = new RcProfileRelation;
				
                $rpr->fb_user_id = $_POST['profileFbId'];
                $rpr->fk_loc_fb_id = $fbc->id;
                $rpr->other_fb_user_id = $_POST['profileFbId'];
                $rpr->fb_guardian = $this->session->userdata['user'];
                $rpr->guardian_fk_loc_fb_id = $fbu->id;
                $rpr->type = 'G';
                //save to database
                $rpr->save();
				
                unset($rcp);
                unset($rpr);
                unset($fbu);
            }
			
             /*
             * this code will be used later
              if(!$rcp->id){
              //take the candidate fb profile pic and save it to rc_profile_picture
              $rpp = new RcProfilePicture;
              $rpp->fb_user_id = $rcp->fb_user_id;
              $cfbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($this->session->userdata['CandidateProfileId']); //to get candidate picture
              $rpp->picture = $cfbu->picture;
              $rpp->save();
              unset($rpp);
             } */
				
            // $this->session->set_userdata('CandidateAge',$this->getAge($rcp->dob, date('Y-m-d H:i:s')));
            // $this->session->set_userdata('CandidateHeight',$rcp->height);
			
            echo "true";
        }
    }
 /**
  * function to get user details from rc_profiles table ajax call
  * @param GLOBAL $_POST['profileFbId'] candidate's facebook id
  * @return JSON encoded string   
  * 
  */
    function getProfileDetails(){
        
           $rcp = Doctrine::getTable('RcProfiles')->findOneByFb_user_id($_POST['profileFbId']);
				
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
				
                //print_r($data);
                //die();
                
				$rcr = Doctrine::getTable('RcRecommendations')->findOneByOther_fr_fb_user_id($_POST['profileFbId']);
                $data['uSrecommendation'] = $rcr->recommendation;
                $data['relation'] = $rcr->relationship;
                unset($rcp);
                //return $data;
                echo json_encode($data);
                
				
    }			
    
     /**
     * function to get biodata doc from rc_profiles ajax call 
     * @param GLOBAL $_POST['profileFbId'] candidate's facebook id
     * @return HTML
     */

	 function getbioData(){
        
        $rcp = Doctrine::getTable('RcProfiles')->findOneByFb_user_id($_POST['profileFbId']);
        $NewFileName = $rcp->biodata;
        unset($rcp);
        if($NewFileName != NULL){
        echo '<div id="biodataDiv"><a href="'.base_url().APPPATH."files/candidate_biodata/".$NewFileName.'">'.$NewFileName.'</a>&nbsp;<a href="javascript:void(0);" class="del_biodata" id="'.$_POST['profileFbId'].'"><img alt="Delete" src="'.base_url().'images/close-ico.png"></a></div>';
        }
     }
    
    function addbiodatapics() {
        $field_name = "bioData";
        $config['upload_path'] = APPPATH . 'files/candidate_biodata';
        $config['allowed_types'] = 'pdf|doc|docx';
        $config['file_name'] = $_POST['biodataprofileFbId'] . "_" . $_FILES["bioData"]["name"];
        $config['overwrite'] = TRUE;
        //$config['max_size']	= '100';
        //$config['max_width']  = '1024';
        //$config['max_height']  = '768';
		
        $this->load->library('upload', $config);
		
        if (!$this->upload->do_upload($field_name)) {
            $error = array('error' => $this->upload->display_errors());
            print_r($error);
            // uploading failed. $error will holds the errors.
            echo "false";
        } else {
            $data = array('upload_data' => $this->upload->data());
            $fileName = $data['upload_data']['file_name'];
			
            //update biodata field value on rc_profiles table
            $rcp = Doctrine::getTable('RcProfiles')->findOneByFb_user_id($this->session->userdata['CandidateProfileId']);
            $rcp->biodata = $fileName;
            $rcp->save();
            // uploading successfull, now do your further actions
            echo "true";
        }
    }
	
    function delCanImage() {
        $pArr = explode('__', $_POST['a_id']);
        $fbUserId = $pArr[0];
        $aId = $pArr[1];
			
        $sqlChkImgExists = "SELECT picture FROM rc_profile_picture WHERE fb_user_id = " . $fbUserId . " AND img_tag_id = " . $aId;
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultChkImgExists = $pdo->query($sqlChkImgExists)->fetchAll();
		
        if ($resultChkImgExists) {		
			//delete picture from db
			$sqldelImgExists = "DELETE FROM rc_profile_picture WHERE fb_user_id = " . $fbUserId . " AND img_tag_id = " . $aId;        	
        	$resultdelImgExists = $pdo->query($sqldelImgExists);
				
			if($resultdelImgExists){
				
				//delete file physically from server				
				//@unlink($_SERVER['DOCUMENT_ROOT'] . '/' . APPPATH . "files/profile_images/".$resultChkImgExists[0]['picture']);
                                $this->s3->deleteObject(self::$bucket,'files/profile_images/'.$resultChkImgExists[0]['picture']);
				//@unlink($_SERVER['DOCUMENT_ROOT'] . '/' . APPPATH . "files/profile_images/thumbs/".$resultChkImgExists[0]['picture']);
                                $this->s3->deleteObject(self::$bucket,'files/profile_images/thumbs/'.$resultChkImgExists[0]['picture']);
            	echo $_POST['a_id'];
				
			}else {
            	echo 0;
        	}
			
        } else {
            //do nothing
        }
    }
	
    function addmatchpreferences() {
       
        $fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($_POST['matchpreProfileFbId']);
        $rcpp = Doctrine::getTable('RcProfilePreference')->findOneByFb_user_id($_POST['matchpreProfileFbId']);
        if (!$rcpp->id) {
            $rcpp = new RcProfilePreference;
        }
        $rcpp->fb_user_id = $_POST['matchpreProfileFbId'];
        $rcpp->fk_loc_fb_id = $fbu->id;
        $rcpp->from_age = $_POST['ageFrom'];
        $rcpp->to_age = $_POST['ageTo'];
        $rcpp->marital_status = implode(",", $_POST['maritalStatus']);
        $rcpp->religion = implode(",", $_POST['mreligion']);
        $rcpp->mother_tongue = implode(",", $_POST['mTongueMulti']);
        $rcpp->caste = implode(",", $_POST['mcaste']);
        $rcpp->from_height = $_POST['heightFromFt'] * 12 + $_POST['heightFromInch'];
        $rcpp->to_height = $_POST['heightToFt'] * 12 + $_POST['heightToInch'];
        $rcpp->min_education = implode(",", $_POST['mEducation']);
        $rcpp->profession = implode(",", $_POST['mprofession']);
        $rcpp->min_salary = $_POST['msalary'];
        //save to database
        $rcpp->save();
        unset($rcpp);
		
        echo "true";
        //unset sessions used during candidate creation from
        $this->session->unset_userdata('CandidateUsername');
        $this->session->unset_userdata('CandidateProfileId');
        //$this->session->unset_userdata('CandidateGender');
        //$this->session->unset_userdata('CandidateAge');
        //$this->session->unset_userdata('CandidateHeight');
        //$this->inviteFriend($msg); 
        
    }
		
	//function to send mail to the newly created candidate 
	function sendmailtocandidate($matchpreProfileFbId)
	{
		print_r($_POST);
		exit;
		
		//fetch sender details
        $fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($fromMailFbId);
        $fromName = $fbu->fname.' '.$fbu->lname;
        $rcm = Doctrine::getTable('RcUserMaster')->findOneByFk_loc_fb_id($fbu->id);
        $fromMail = $rcm->email;  
		
		//fetch candidate details
		$cdtls = Doctrine::getTable('RcProfiles')->findOneByFb_user_id($fromMailFbId);     
		
		//load mail config info from config file
        $config = $this->config->item('mailconfigData');
        $mailSubject = $this->session->userdata['fullname'].' has shared profile of '.$fromName;
		$mailMsg = $msg;
		//initialize
        $this->email->initialize($config);
		
		//fetch and set sender info from config file
        $this->email->from($fromMail, $fromName);
        $this->email->subject($mailSubject);
        $this->email->to($toMail);
        $this->email->message($mailMsg);	
		
		unset($fbu);
        unset($rcm);
		unset($cdtls);
		
        $this->email->send();
        echo $this->email->print_debugger();
		
	}
	
	
/**
 * function to get Candidate's Match Preferences from rc_profile_preference table ajax call
 * @param GLOBAL $_POST['profileFbId'] candidate's facebook id
 * @return JSON encoded string  
 */
    function getMatchPreferences(){
        $rcpp = Doctrine::getTable('RcProfilePreference')->findOneByFb_user_id($_POST['profileFbId']);
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
/** 
 * function to set flag for candidate profile complitation
 *  rc_profiles 'status' field = 1 and rc_profile_relation 'is_rc_profile' field = 1
 *  Ajax function
 *  @global int $_POST['candidateFbId']
 */
    function candidateStatus() {
        $guardianFbId = $this->session->userdata['user']; //get value session
        //echo $this->getFbidToName($guardianFbId);
        //$_POST['candidateFbId'] = $guardianFbId;
        
        $q = Doctrine_Query::create()
        ->update('RcProfileRelation')
        ->set('status','?',1)
        ->where('fb_guardian = ?',$guardianFbId)
        ->andWhere('fb_user_id = ?',$_POST['candidateFbId']);
        $q->execute();
		
		
        //set rcprofile candidate status 
        $rcp = Doctrine::getTable('RcProfiles')->findOneByFb_user_id($_POST['candidateFbId']);
        $fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($_POST['candidateFbId']);
		
        if ($rcp->id && $fbu->id && ($rcp->status == 0) && ($fbu->is_rc_profile == 0)) {
            $rcp->status = 1;
            $rcp->save();
            $fbu->is_rc_profile = 1;
            $fbu->save();
            
            //code to send new candidate creation mail
            //load mail config info from config file
        $config = $this->config->item('mailconfigData');
        
        $msg = $this->getFbidToName($guardianFbId).' has created a profile for '.$this->getFbidToName($_POST['candidateFbId']);
		//initialize
        $this->email->initialize($config);
		
		//fetch and set sender info from config file
        $this->email->from('system@rc.com', 'RC');
        $this->email->subject($this->config->item('NewCandidateSubject'));
        $this->email->to($this->config->item('adminMail'));
        $this->email->message($msg);
        $this->email->send();
        echo $this->email->print_debugger();
          echo "true";  
            
        }
        else
            echo "false";
    }
	
    function addCandidateImage() {

        $fieldName = 'canImage';
        $deb = 0;
        $config['upload_path'] = APPPATH . 'files/profile_images';
        $config['allowed_types'] = 'gif|jpg|png|jpeg|tif|bmp';
        $config['file_name'] = $_POST['a_id'] . "_" . $_FILES["canImage"]["name"];
		
        //check if record exists
        $sqlChkImgExists = "SELECT * FROM rc_profile_picture WHERE fk_rc_profile_id=" . $this->session->userdata['CandidateProfileId'] . " AND
						picture LIKE '" . $_POST['a_id'] . "%'";
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultChkImgExists = $pdo->query($sqlChkImgExists)->fetchAll();
		
		
        if (count($resultChkImgExists) > 0) {
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . APPPATH . 'files/profile_images/' . $resultChkImgExists[0]['picture'])) {
                @unlink($_SERVER['DOCUMENT_ROOT'] . '/' . APPPATH . 'files/profile_images/' . $resultChkImgExists[0]['picture']);
                @unlink($_SERVER['DOCUMENT_ROOT'] . '/' . APPPATH . 'files/profile_images/thumbs/' . $resultChkImgExists[0]['picture']);
		
                //if($_POST['m_id']=='C'){
                //update the corresponding record in rc_profile_pictures table
                $sqlUpdProImg = "UPDATE  rc_profile_picture SET picture= '" . $config['file_name'] . "'
							WHERE id = " . $resultChkImgExists[0]['id'];
                //}else{
                //delete the corresponding record in rc_profile_pictures table
                //$sqlUpdProImg="DELETE FROM rc_profile_picture 
                //WHERE id = ".$resultChkImgExists[0]['id'];
                //}
                //$pdo = Doctrine_Manager::connection()->getDbh();       
                $resultUpdProImg = $pdo->query($sqlUpdProImg);
            }
			
            $deb++;
			
            //update the corresponding record in rc_profile_pictures table
            $sqlUpdProImg = "UPDATE  rc_profile_picture SET picture= '" . $config['file_name'] . "'
						WHERE fk_rc_profile_id=" . $this->session->userdata['CandidateProfileId'] . "
						AND picture LIKE '" . $_POST['a_id'] . "%'";
            //$pdo = Doctrine_Manager::connection()->getDbh();       
            $resultUpdProImg = $pdo->query($sqlUpdProImg);
        }		
        
		
        $this->load->library('upload', $config);
		
        if (!$this->upload->do_upload($fieldName)) {
            $error = array('error' => $this->upload->display_errors());
        } else {
            $image_data = $this->upload->data();      //get image data
			
            $configthumb = array(
                'source_image' => $image_data['full_path'], //get original image
                'new_image' => APPPATH . 'files/profile_images/thumbs', //save as new image //need to create thumbs first
                'maintain_ratio' => true,
                'width' => 50,
                'height' => 50);
			
            $this->load->library('image_lib', $configthumb); //load library
            $this->image_lib->resize();
			
            if ($deb == 0) {
                //insert image into rc_profile_picture table
                $rcpp = new RcProfilePicture;
                $rcpp->fk_rc_profile_id = $_POST['CandidateProfileId'];
                $rcpp->picture = $config['file_name'];
                $rcpp->save();
			
                //die('hereee');
            }
        }
		
        echo '<script type="text/javascript">window.close(); window.opener.location.reload(); </script>';
    }
	
    function getFbPicture($fbUserId) {
        $chkFbImageExista = "SELECT * FROM  rc_profile_picture WHERE  fb_user_id = " . $fbUserId . " AND img_tag_id = 1";
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultFbImageExista = $pdo->query($chkFbImageExista)->fetchAll();
        if (count($resultFbImageExista) > 0) {
            return base_url() . APPPATH . 'files/profile_images/thumbs/' . $resultFbImageExista[0][picture];
        } else {
            return null;
        }
    }
	
	//age calculator 
    public function getAge($birth, $now = NULL) {
        $now = new DateTime($now);
        $birth = new DateTime($birth);
		
        return $birth->diff($now)->format('%r%y');
    }

	//age validator
    public function dob_check($str) {
        $age = $this->getAge(date("Y-m-d", strtotime($_POST['dob'])), date('Y-m-d H:i:s'));
		
        if ($age < 18) {
            $this->form_validation->set_message('dob_check', 'candidate age not valid');
            return false;
        }
        else
            return true;
    }
	
	//age validator ajax
    public function ajax_dob_check() {
        //echo "false";
		
        $age = $this->getAge(date("Y-m-d", strtotime($_POST['dob'])), date('Y-m-d H:i:s'));
        if ($age < 18)
            echo "false";
        else
            echo "true";
    }
	
	//religion validator
    public function religion_check($str) {
        if (in_array($_POST['religion'], $this->config->item('religion')))
            return true;
        else
            $this->form_validation->set_message('religion_check', 'Please select a valid Religion');
        return false;
    }

	//religion validator ajax
    public function ajax_religion_check() {
        if (in_array($_POST['religion'], $this->config->item('religion')))
            echo "true";
        else
            echo "false";
    }

	//mother tongue validator
    public function lan_check($str) {
        if (in_array($_POST['mTongue'], $this->config->item('mTongue')))
            return true;
        else
            $this->form_validation->set_message('lan_check', 'Please select a valid Mother Tongue');
        return false;
    }

	//mother tongue validator ajax
    public function ajax_lan_check() {
        if (in_array($_POST['mTongue'], $this->config->item('mTongue')))
            echo "true";
        else
            echo "false";
    }
	
	//cast validator
    public function cast_check($str) {
        $sqlGetCaste = "SELECT caste FROM rc_caste_master";
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultGetCaste = $pdo->query($sqlGetCaste)->fetchAll();
        foreach ($resultGetCaste as $key => $option) {
            $casts[$key] = $option['caste'];
        }
        if (in_array($_POST['caste'], $casts) || in_array($_POST['mcaste'], $casts))
            return true;
        else
            $this->form_validation->set_message('cast_check', 'Please select a valid Cast');
        return false;
    }
	
	//cast validator ajax
    public function ajax_cast_check() {
        $sqlGetCaste = "SELECT caste FROM rc_caste_master";
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultGetCaste = $pdo->query($sqlGetCaste)->fetchAll();
        foreach ($resultGetCaste as $key => $option) {
            $casts[$key] = $option['caste'];
        }
        if (in_array($_POST['mcaste'], $casts))
            echo "true";
        else
            echo "false";
    }
	
	//education validator
    public function edu_check($str) {
        if (in_array($_POST['hEducation'], $this->config->item('hEducation')))
            return true;
        else
            $this->form_validation->set_message('edu_check', 'Please select a valid Highest Education');
        return false;
    }

	//education validator ajax
    public function ajax_edu_check() {
        if (in_array($_POST['hEducation'], $this->config->item('hEducation')))
            echo "true";
        else
            echo "false";
    }
	
	//profession validator ajax
    public function ajax_pro_check() {
        if (in_array($_POST['profession'], $this->config->item('profession')))
            echo "true";
        else
            echo "false";
    }
	
	//custom location validation function
    public function location_check($str) {
        if(preg_match("/[a-z0-9\s,]+/i", $_POST['location'])) 
            return true;
        else
            $this->form_validation->set_message('location_check', 'Please enter a valid %s');
			return false;
    }
	
	//custom profession validation function
    public function profession_check($str) {
        if (filter_var(filter_var($_POST['profession'], FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) || $_POST['profession'] == "")
            return true;
        else
            $this->form_validation->set_message('profession_check', 'Please enter a valid %s');
        return false;
    }
	
	//custom salary validation function
    public function salary_check($str) {
        $sal = $_POST['salary'];
        if (filter_var(filter_var($_POST['salary'], FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^-?([0-9])+([\.|,]([0-9])*)?$/")))) || $_POST['salary'] == "")
            return true;
        else
            $this->form_validation->set_message('salary_check', 'Please enter a valid %s');
        return false;
    }

}

