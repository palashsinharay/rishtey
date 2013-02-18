<?php
	
class Functions extends Controller {
    public static $bucket;
	//base controller
    function __construct() {
        parent::Controller();
        $config['accessKey'] = $this->config->item('accessKey');
        $config['secretKey'] = $this->config->item('secretKey');
        self::$bucket = $this->config->item('bucket');
        $this->load->library('S3',$config);
    }
	
	//base function
    function index() {
        //do nothing        
    }
	
/***to debug the array***/
    function pr($arr = null) {
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }
	
/* function imageupload($a_id, $m_id){
			
	  $data['fb_user_id'] = $this->session->userdata['user'];
	  $data['a_id'] = $a_id;
	  $data['m_id'] = $m_id;
	  $data['CandidateProfileId'] = $this->session->userdata['CandidateProfileId'];
	  $this->load->view('candidate/imageupload', $data);
  } */
    
    /**
     * funtion to check user log-in or not
     */
public function checkLogin(){
if($this->session->userdata['loggedIn']==0){
    redirect('/');
}
} 

/**
* funtion to check user suggetion status
*/
public function suggetionlistStatus($fbid) {
  $fum = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($fbid);
  if(!$fum->set_suggestion_list){
   redirect('/');   
  }

}

//profileID to fbID
public  function getPidToFbid($pid){
   $rcp = Doctrine::getTable('RcProfiles')->findOneById($pid);
   $fbid = $rcp->fb_user_id;

       //unset and return
       unset($rcp);
   return $fbid;
}

//fbID to profileID
public function getFbidToPid($fbid){

$rcp = Doctrine::getTable('RcProfiles')->findOneByFb_user_id($fbid);
$pid = $rcp->id;

        //unset and return
        unset($rcp);
return $pid;       

}

// profileID to Name ajax call

 public function getFbidToNameAjax($fbid){

        

        $rcp = Doctrine::getTable('RcProfiles')->findOneByFb_user_id($fbid);

        $name = $rcp->fname.' '.$rcp->lname;

        unset($rcp);

        echo $name;

 }
 // profileID to Name

 public function getFbidToName($fbid){

        

        $rcp = Doctrine::getTable('RcProfiles')->findOneByFb_user_id($fbid);
        if($rcp->fname == NULL){
            unset($rcp);
            $rcp = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($fbid);
        }
        
        
        $name = $rcp->fname.' '.$rcp->lname;

        unset($rcp);

        return $name;

 }
 
 public function getPidToName($pid){

        $rcp = Doctrine::getTable('RcProfiles')->findOneById($pid);

        $name = $rcp->fname.' '.$rcp->lname;

        unset($rcp);

        return $name;

 }
 
 /**
  * funtion to get gaurdian fbid
  * @param INT $fbid candidate fbid
  * @return string gaurdian name
  */
 public function getGaurdianFbid($fbid) {
     
     $dql = Doctrine_Query::create()
             ->select('fb_guardian')
             ->from('RcProfileRelation')
             ->where('fb_user_id = ?',$fbid)
             ->andWhere('status = 1')
             ->andWhere("type = 'G'");
     $resultset = $dql->execute();
     $resultset->toArray();
     return $resultset[0]['fb_guardian'];
     
 }

 
	//get profile pictures of candidate other than his facebook picture
	//$rcProfileId is the unique facebook id of the candidate
    function getProfilePictures($rcProfileId) {

        $sqlGetPicture = "SELECT picture FROM rc_profile_picture
                            WHERE fb_user_id = " . $rcProfileId . " AND img_tag_id != 1";
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultGetPicture = $pdo->query($sqlGetPicture)->fetchAll();
        foreach ($resultGetPicture as $key => $option) {
            $canPicture[$key] = $option['picture'];
        }
	
        return $canPicture;
    }
	

	//get facebook picture of the candidate
	//$fbUserId is the unique facebook id of the candidate
    function getFbPicture($fbUserId) {

        $chkFbImageExista = "SELECT * FROM rc_profile_picture WHERE fb_user_id = " . $fbUserId . " AND img_tag_id = 1";
		
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultFbImageExista = $pdo->query($chkFbImageExista)->fetchAll();
		
        if (count($resultFbImageExista) > 0) {
            return $resultFbImageExista[0][picture];
        } else {
            return null;
        }
    }
	
	//this will pickup one friend randomly from the suggestionList
	//$fbUserId contains the logged in user's unique facebook id
    function getHelpFriend($fbUserId = NULL) {
        //query to fetch a random friend from the logged in user's suggested list of friends  
        $sqlGetRandomFriend = "SELECT fum.id, fum.fname, fum.lname, fum.picture, fum.username, fum.fb_user_id , fum.birthday, fum.relationship_status
								FROM fb_user_master AS fum 				
								INNER JOIN fb_suggestion_list AS fsl ON fum.fb_user_id = fsl.ref_fb_user_id								
								AND fsl.fb_user_id=" . $fbUserId . "
								AND fsl.ref_fb_user_id NOT IN(SELECT fb_user_id FROM rc_profiles WHERE 1)
								AND fsl.rem_candidature_flag=0
								AND fsl.send_message=0 
								ORDER BY RAND() LIMIT 0,1";
		
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultGetRandomFriend = $pdo->query($sqlGetRandomFriend)->fetchAll();
		
        return $resultGetRandomFriend;
    }
	
	//function to fetch all single friends of the logged in user within a given age range(22 to 35)
	//$id contains the logged in user's auto-incremented id from fb_user_master table 
    function getSinglefriends($id = NULL) {
        //check if the logged in user is accessing this function (for unit testing purpose)
        if ($id == '') {
            return 'NULL';
        }
		
        //fetch the starting and ending age limits from config file
        $stAgeLimit = $this->config->item('stAgeLimit');
        $endAgeLimit = $this->config->item('endAgeLimit');
		
        $sqlGetSingleFriends = "SELECT fum.id, fum.picture, fum.username, fum.fb_user_id , fum.birthday, fum.relationship_status, CONCAT(fum.fname, ' ', fum.lname) AS name							FROM fb_user_master AS fum 
								INNER JOIN network AS n ON fum.id = n.loc_fr_fb_id 
								AND n.loc_fb_id=" . $id . "  
								AND fum.relationship_status =1
								AND fum.del_flag = 0
								AND fum.username!=''
								AND fum.birthday!=''
								AND fum.relationship_status!=''
								AND (DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( fum.birthday ) ) , '%Y' ) +0) >=" . $stAgeLimit . " 
								AND (DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( fum.birthday ) ) , '%Y' ) +0) <=" . $endAgeLimit;
		
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultGetSingleFriends = $pdo->query($sqlGetSingleFriends)->fetchAll();
		
        $data['records'] = $resultGetSingleFriends;
		
        return $data;
    }
	
	//function to fetch left panel data counts
	//$id contains the logged in user's auto-incremented id from fb_user_master table 
    function getLeftPanelCounts($id = NULL) {
		
        //check if the logged in user is accessing this function (for unit testing purpose)
        if ($id == '') {
            return 'NULL';
        }
		
        $data = array();
		
        $data['drFrndCount'] = $this->getDirectFriends($id);   //function to get direct friend count of the logged in user
        $data['inDrFrndCount'] = $this->getIndirectFriends($id);  //function to get indirect friend count of the logged in user
		
        $data['potentialBridesCnt'] = $this->getPotentialBrides($id); //function to get potential bride count of the logged in user
        $data['potentialGroomsCnt'] = $this->getPotentialGrooms($id);   //function to get potential groom count of the logged in user 
        $data['ownCandidatesCnt'] = $this->getOwnCandidates($id);     //function to get candidate count of the logged in user
		
        return $data;
    }
	
	//callback function to remove the selected friend from "Help Friend" section of the logged in user
    function manageFriends() {
        $randomSuggestedFriend = array();
		
        $loggedInUser = $_POST['loggedInUser'];  //$_POST['loggedInUser'] contains the logged in user's unique facebook id
			
        $fbUserId = $_POST['fbUserId'];    //$_POST['fbUserId'] contains the friend's unique facebook id to whom recommendation is sent
		
        $otherfbUserId = $_POST['otherfbUserId']; //$_POST['otherfbUserId'] contains the candidate's unique facebook id for whom recommendation is sent        
		
        $sqlChkMsgSent = "SELECT * FROM fb_suggestion_list WHERE fb_user_id = " . $loggedInUser . " AND ref_fb_user_id = " . $otherfbUserId;
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultChkMsgSent = $pdo->query($sqlChkMsgSent)->fetchAll();
	if(count($resultChkMsgSent) > 0){
        
            $fbu = Doctrine::getTable('FbSuggestionList')->findOneByRef_fb_user_id($resultChkMsgSent[0]['ref_fb_user_id']);
            //check and update the rem_candidature_flag / send_message fields in the fb_suggestion_list table
            //0 = record should show up in the "Help Friend" section of the logged in user          1 = record should not show up in the "Help Friend" section of the logged in user

            if ($_POST['selectedId'] == 'sm') {
                $fbu->send_message = 1;
            } else {
                $fbu->rem_candidature_flag = 1;
            }

            $fbu->save();
        }
       
		
        //rebuilt the "Help your friends" section
        $randomSuggestedFriend = $this->getHelpFriend($loggedInUser);		
		
        //prepare the relation dropdown		
		$sqlGetRelations = "SELECT * FROM rc_recommrelation_master WHERE 1";
		$pdo = Doctrine_Manager::connection()->getDbh();
        $resultGetRelations = $pdo->query($sqlGetRelations)->fetchAll();
			
        foreach ($resultGetRelations as $key => $option) {
            $optString .= '<option value="' . $option['id'] . '">' . $option['relation'] . '</option>';
        }
		
        //check if any more friend exists in the suggestion list	
        if (count($randomSuggestedFriend) > 0) {
            echo '<h4>Help your friends</h4><!--<p class="alert success" style="margin-bottom:5px!important; text-transform:none !important"></p>--><div class="poll-block"><img width="107" height="107" src="https://graph.facebook.com/' . $randomSuggestedFriend[0][fb_user_id] . '/picture?width=107&height=107" alt="' . $randomSuggestedFriend[0][fname] . ' ' . $randomSuggestedFriend[0][lname] . '" title="' . $randomSuggestedFriend[0][fname] . ' ' . $randomSuggestedFriend[0][lname] . '" />
					  <div class="poll">
						<p>Who can give us more information about ' . $randomSuggestedFriend[0][fname] . ' ' . $randomSuggestedFriend[0][lname] . '</p>
						<form id="helpfriend" name="helpfriend" method="post" action="' . base_url() . 'candidate/candidateprofile">
						<label class="row">
						<input name="candidateMe" type="radio" id="me" value="' . $randomSuggestedFriend[0][username] . '" checked="checked"  />
						<em>Me</em> </label>
						<label class="row">
						<input type="radio" name="candidateMe" id="candidate" value="' . $randomSuggestedFriend[0][fb_user_id] . '" />
						<em>' . $randomSuggestedFriend[0][fname] . ' ' . $randomSuggestedFriend[0][lname] . '</em> </label>
						<div id="candidate_rec_msg" style="display:none; ">
						<textarea class="textarea inputWidth" id="candidate_rec_msg">A Short Recommendation</textarea>
						</div>
						
						<div id="candidate_relation" style="display:none; margin-top:2px;">
							<select class="selectBox inputWidth" name="select_candidate_relation" id="select_candidate_relation">
								' . $optString . '
							</select>
						</div>
						
						<label class="row">
						<input type="radio" name="candidateMe" id="other_fb_friend" value="other_fb_friend" />
						<em>Other FB Friends</em>
						</label>
						<p id="sfr" style="display: none;">
							<input style="width:150px;" class="input" id="candidate1" name="candidate1">
						</p>
						<div id="other_fb_friend_rec_msg" style="display:none; "><textarea class="textarea inputWidth" id="other_fb_friend_box">A Short Recommendation</textarea></div>					
						
						<div id="other_fb_friend_relation" style="display:none; margin-top:2px;">
							<select class="selectBox inputWidth" name="select_other_fb_friend_relation" id="select_other_fb_friend_relation">
								' . $optString . '
							</select>
						</div>
						
						<label class="row">
						<input rel="'.$randomSuggestedFriend[0][fname].'" type="radio" name="candidateMe" id="nc" value="'.$randomSuggestedFriend[0][fb_user_id].'" />
						<em>Not a candidate</em> </label>
						<a name="send_message" id="send_message" href="javascript:void(0);" class="greenText">Go</a>
						<input type="hidden" name="remove_friend" id="remove_friend" value="' . $fbUserId . '" />
						<input type="hidden" name="remove_other_friend" id="remove_other_friend" value="' . $otherfbUserId . '" />
						</form>
					  </div></div>';
        } else {		
            //do nothing
        }
		
        exit;
    }	
			
	//callback function to remove the selected friend from "Invite friends" section of the logged in user
    function manageFbFriends() {
		
        $getAllFacebookfriends = array();
		
        $fbUserId = $_POST['fbUserId'];    //$_POST['fbUserId'] contains the friend's unique facebook id to whom recommendation is sent
		
        $otherfbUserId = $_POST['otherfbUserId']; //$_POST['otherfbUserId'] contains the candidate's unique facebook id for whom recommendation is sent
		
		$loggedInUser = $_POST['loggedInUser']; //$_POST['loggedInUser'] contains the logged in user's unique facebook id
		
		//get fb user details of the friend
        $fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($fbUserId);
		
		//get fb user details of the logged in user
        $fbg = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($loggedInUser);
		
        //check and update the "recm_msg_sent" flag field in the fb_user_master table
        //0 = record should show up in the "Invite friends" section of the logged in user                                                                            1 = record should not show up in the "Invite friends" section of the logged in user
		
        //$fbu->recm_msg_sent = 1;
        //$fbu->save();
		
		
        //update "recm_msg_sent" flag field in rc_profile_relation table
        $sqlUpdStr = "UPDATE rc_profile_relation SET recm_msg_sent = 1 WHERE fb_user_id = ".$fbUserId."
						AND other_fb_user_id = ".$otherfbUserId."
						AND type='R'";
		$pdo = Doctrine_Manager::connection()->getDbh();
			
        $resultUpdStr = $pdo->query($sqlUpdStr);							
        
        //rebuilt the "Invite friends" section
        $getAllFacebookfriends = $this->getAllFbfriends($fbg->id);
		
        //check if any more friend exists in the suggestion list	
        if (count($getAllFacebookfriends) > 0) {
		
            $i = 1;
            foreach ($getAllFacebookfriends['records'] as $row):
				
                if ($i % 5 == 0) {
                    $cls = 'last';
                } else {
                    $cls = '';
                }
				
				//check if message already sent
				$sqlchkRecExists = "SELECT * FROM rc_profile_relation WHERE fb_user_id = ".$row['fb_user_id']."
				AND guardian_fk_loc_fb_id = ".$fbg->id."
				AND recm_msg_sent=1
				AND type='R'";
				
				$resultchkRecExists = $pdo->query($sqlchkRecExists)->fetchAll();
				
				if(count($resultchkRecExists) == 0){
                echo '<li class="' . $cls . '">
							<img style="cursor:pointer;" rel="'.$row['fb_user_id'].'" height="107" width="107" src="https://graph.facebook.com/' . $row['fb_user_id'] . '/picture?type=large" alt="' . $row['name'] . '"  title="' . $row['name'] . '" id="' . $row['username'] . '" class="rcimg delete invite_friends" />
							<a rel="'.$row['fb_user_id'].'" id="' . $row['username'] . '" href="JavaScript:void(0);" title="Invite Friends" class="delete invite_friends">Invite '.$row['fname'].'</a>
							<input type="hidden" id="candidate" name="candidate" value="' . $row['fb_user_id'] . '" />
				
							<input type="hidden" name="remove_invite_friend" id="remove_friend" value="' . $fbUserId . '" />
							<input type="hidden" name="remove_other_invite_friend" id="remove_other_friend" value="' . $otherfbUserId . '" />
				
							</li>';
				
					 $i++;
				}
					
            endforeach;
				
        } else {
            //do nothing
        }
		
        exit;
    }
	
	//callback function to remove the selected friend from logged in user's suggested list of friends
    function manageSuggestionList() {
        $fbId = $_POST['fbId'];  //$_POST['fbId'] contains the logged in user's unique facebook id	
		
        $fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($fbId);
		
        //update the "del_flag" field in fb_user_master table
        //0 = record exists in logged in user's suggested list of friends                                                                                            1 = record              does not exist in logged in user's suggested list of friends	
		
        $fbu->del_flag = 1;
		
        $fbu->save();
		
        //remove the selected friend from the session array
        if (isset($this->session->userdata['finalSet'])) {
            array_pop($this->session->userdata['finalSet']);
            $finalSet = $this->session->userdata['finalSet'];
            $this->session->set_userdata($finalSet);
        }
		
        exit;
    }
	
	//callback function to save the logged in user's suggested list of friends into fb_suggestion_list table  
    function insertSuggestionList() {
        $suList = $_POST['suList'];  //$_POST['suList'] contains the logged in user's suggested array of friends		
        $loggedInUser = $_POST['user']; //$_POST['user'] contains the logged in user's unique facebook id
		
        foreach ($suList as $value) {
            $s = new FbSuggestionList;
            $s->fb_user_id = $loggedInUser;
            $s->ref_fb_user_id = $value;
		
            //save the record to fb_suggestion_list table
            $s->save();
            unset($s);
        }
        $fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($loggedInUser);
		
        //update the "set_suggestion_list" flag field in fb_user_master table
        //0 = logged in user's suggestion list of friends is not ready                                                                                            1   1 = logged              in user's suggestion list of friends is ready and the user is now eligible for profile creation
		
        $fbu->set_suggestion_list = 1;
		
        $fbu->save();
		
        //suggestion list ready, move to Create Candidate page and log the message in rc_log table 
        $action = 'move-to-add-candidate-page';
        $referer = $_SERVER["HTTP_REFERER"];
        $fbUserId = $this->session->userdata['user'];
        $page = $_SERVER['REQUEST_URI'];
        $msg = 'suggestion list ready, move to add candidate page';
		
        $state = $_REQUEST['state'];
        $code = $_REQUEST['code'];
		
        //$date = new DateTime();
        //$timestamp = $date->getTimestamp();
		
        $timestamp = time();
		
        //creatng the access-denied log.   
        $this->rc_log_save($action, $referer, $fbUserId, $page, $msg, $state, $code, $timestamp);
		
        exit;
    }
	
	//function to get direct friends count of the logged in user
	//$fbId contains the logged in user's auto-incremented id from fb_user_master table
    function getDirectFriends($fbId = null) {
		
        //check if the logged in user is accessing this function (for unit testing purpose)
        if ($fbId == '') {
            return 'NULL';
        }
		
        $sqlGetDrFriendCount = "SELECT dr_friend_cnt 
										FROM fb_user_master AS fum WHERE
										fum.id = " . $fbId;
		
        $pdo = Doctrine_Manager::connection()->getDbh();
		
        $resultGetDrFriendCount = $pdo->query($sqlGetDrFriendCount)->fetchAll();
		
        return ($resultGetDrFriendCount[0]['dr_friend_cnt'] != '' ? $resultGetDrFriendCount[0]['dr_friend_cnt'] : 0);
    }
	
	//function to get indirect friends count of the logged in user
	//$fbId contains the logged in user's auto-incremented id from fb_user_master table
    function getIndirectFriends($fbId = null) {
		
        //check if the logged in user is accessing this function (for unit testing purpose)
        if ($fbId == '') {
            return 'NULL';
        }
		
        $sqlGetInDrFriendCount = "SELECT indr_friend_cnt 
										  FROM fb_user_master AS fum WHERE
										  fum.id  = " . $fbId;
		
        $pdo = Doctrine_Manager::connection()->getDbh();
		
        $resultGetInDrFriendCount = $pdo->query($sqlGetInDrFriendCount)->fetchAll();
		
        return ($resultGetInDrFriendCount[0]['indr_friend_cnt'] != '' ? $resultGetInDrFriendCount[0]['indr_friend_cnt'] : 0);
    }
	
	//function to get potential brides count of the logged in user
	//$fbId contains the logged in user's auto-incremented id from fb_user_master table
    function getPotentialBrides($fbId = null) {
	
        $sqlGetBrideCount = "SELECT bride_cnt 
									  FROM fb_user_master AS fum WHERE
									  fum.id  = " . $fbId;
		
        $pdo = Doctrine_Manager::connection()->getDbh();
		
        $resultGetBrideCount = $pdo->query($sqlGetBrideCount)->fetchAll();
		
        return ($resultGetBrideCount[0]['bride_cnt'] != '' ? $resultGetBrideCount[0]['bride_cnt'] : 0);
    }
	
	//function to get potential grooms count of the logged in user
	//$fbId contains the logged in user's auto-incremented id from fb_user_master table
    function getPotentialGrooms($fbId = null) {
		
        $sqlGetGroomCount = "SELECT groom_cnt 
									  FROM fb_user_master AS fum WHERE
									  fum.id  = " . $fbId;
		
        $pdo = Doctrine_Manager::connection()->getDbh();
		
        $resultGetGroomCount = $pdo->query($sqlGetGroomCount)->fetchAll();
		
        return ($resultGetGroomCount[0]['groom_cnt'] != '' ? $resultGetGroomCount[0]['groom_cnt'] : 0);
    }
	
	//function to get logged in facebook user's own candidates
	//$fbId contains the logged in user's auto-incremented id from fb_user_master table
    function getOwnCandidates($fbId = null) {
		
        $sql = "SELECT candidate_cnt 
						  FROM fb_user_master AS fum WHERE
						  fum.id  = " . $fbId;
		
        $pdo = Doctrine_Manager::connection()->getDbh();
		
        $result = $pdo->query($sql)->fetchAll();
		
        return (isset($result[0]['candidate_cnt']) ? $result[0]['candidate_cnt'] : 0);
    }
	
	//function to get all facebook friends of the logged in user except the already selected ones on the first login landing page
    function getAllfriends($id = NULL, $frData, $frFbUserName = NULL) {
		
        //check if the logged in user is accessing this function and $frFbUserName variable is non-empty (for unit testing purpose)
        //if($id=='' || $frFbUserName==''){
        //	return 'NULL';
        //}
		
        //check if user array is empty and if not prepare the $finalSet array variable
        if (count($frData) > 0) {
            foreach ($frData as $key => $val) {
                $finalSet[] = "'" . $val['username'] . "'";
            }
        } else {
            $finalSet = array();
        }
		
        //push the virtually added user into the final array set
        if ($frFbUserName != '') {
			
            if (isset($this->session->userdata['finalSet'])) {
                $finalSet = $this->session->userdata['finalSet'];
                array_push($finalSet, "'" . $frFbUserName . "'");
                $this->session->set_userdata('finalSet', $finalSet);
            } else {
                array_push($finalSet, "'" . $frFbUserName . "'");
                $this->session->set_userdata('finalSet', $finalSet);
            }
        }
		
        //check if session data is empty
        if (isset($this->session->userdata['finalSet'])) {
            $strfinalSet = implode(',', $this->session->userdata['finalSet']);
        } else {
            $strfinalSet = implode(',', $finalSet);
        }
		
        //check if friendlist is empty	
        if ($strfinalSet != '') {
            $sqlGetAllFriends = "SELECT CONCAT(fum.fname, ' ', fum.lname) as name, fum.username
								FROM fb_user_master AS fum 
								INNER JOIN network AS n ON fum.id = n.loc_fr_fb_id 
								AND n.loc_fb_id=" . $id . "								
								AND fum.username NOT IN(" . $strfinalSet . ")
								AND fum.del_flag = 0";
        } else {
            $sqlGetAllFriends = "SELECT CONCAT(fum.fname, ' ', fum.lname) as name, fum.username
								FROM fb_user_master AS fum 
								INNER JOIN network AS n ON fum.id = n.loc_fr_fb_id 
								AND n.loc_fb_id=" . $id . "																
								AND fum.del_flag = 0";
        }
		
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultGetAllFriends = $pdo->query($sqlGetAllFriends)->fetchAll();
		
        $data['records'] = $resultGetAllFriends;
		
        return $data;
    }
	
	//function to get all facebook friends of the logged in user (irrespective of age restriction)
	//$id contains the logged in user's auto-incremented id from fb_user_master table
    function getAllFbfriends($id = NULL) {
	
        //check if the logged in user is accessing this function (for unit testing purpose)
        if ($id == '') {
            return 'NULL';
        }
		
        $sqlGetAllFbFriends = "SELECT fum.fname, CONCAT(fum.fname, ' ', fum.lname) as name, fum.id, fum.picture, fum.username, fum.fb_user_id, fum.birthday, fum.relationship_status
							FROM fb_user_master AS fum 
							INNER JOIN network AS n 
							ON fum.id = n.loc_fr_fb_id
							AND fum.recm_msg_sent = 0							
							AND n.loc_fb_id=" . $id;
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultGetAllFbFriends = $pdo->query($sqlGetAllFbFriends)->fetchAll();
		
        $data['records'] = $resultGetAllFbFriends;
		
        return $data;
    }
	
	//function to get the suggested list of friends of the logged in user
	//$id contains the logged in user's auto-incremented id from fb_user_master table
    function getSuggestedfriends($id = NULL) {
	
        //check if the logged in user is accessing this function (for unit testing purpose)
        if ($id == '') {
            return 'NULL';
        }
		
        $sqlGetSuggestedFriends = "SELECT fum.id, fum.fb_user_id, CONCAT(fum.fname, ' ', fum.lname) as name, fum.username
								FROM fb_user_master AS fum 
								INNER JOIN network AS n ON fum.id = n.loc_fr_fb_id 
								AND n.loc_fb_id=" . $id . "								
								AND fum.del_flag = 0";
		
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultGetSuggestedFriends = $pdo->query($sqlGetSuggestedFriends)->fetchAll();
		
        $data['records'] = $resultGetSuggestedFriends;
		
        return $data;
    }
	
	//callback function to add a virtual user to the list of facebook friends of the logged in user
    function addFriendToSuggestionList() {
		
        //initialize the variables
        $str = '';
        $liCls = '';
		
        //initialize the array
        $otherFriend = array();
		
        $frFbUserName = $_POST['frFbUserName'];  //$_POST['frFbUserName'] contains the logged in user's facebook username
        
		//get friend's details from fb_user_master table and prepare the $otherFriend array
        $frFbu = Doctrine::getTable('FbUserMaster')->findOneByUsername($frFbUserName);
        $otherFriend['id'] = $frFbu['id'];
        $otherFriend['picture'] = $frFbu['picture'];
        $otherFriend['username'] = $frFbu['username'];
        $otherFriend['name'] = $frFbu['fname'] . ' ' . $frFbu['lname'];
        $otherFriend['fb_user_id'] = $frFbu['fb_user_id'];
        $otherFriend['birthday'] = $frFbu['birthday'];
        $otherFriend['relationship_status'] = $frFbu['relationship_status'];
		
        //get details of the logged in user	
        $fbUserId = $_POST['fbUserId'];       //$_POST['fbUserId'] contains the logged in user's unique facebook id
        $fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($fbUserId);
		
        //fetch all single friends of the logged in user (within age group 22 to 35)
        $data = $this->getSinglefriends($fbu->id);
				
        //rebuilt the $availableTags array  
        $availableTags = array();
		
        //fetch all facebook friends of the logged in user (irrespective of age)
        $frUser = $this->getAllfriends($fbu->id, $data['records'], $frFbUserName);
		
        //set the css class for the last li per row
        $userCount = (isset($this->session->userdata['finalSet'])) ? ((count($this->session->userdata['finalSet'])) % 5) : count($data['records']);
		
        if ($userCount == 0) {
            $liCls = "last";
        } else {
            $liCls = "";
        }
		
        foreach ($frUser['records'] as $key => $value) {
            $availableTags[$key] = $value['username'] . '??' . $value['name'];
            $availableTagsWithName[$key] = $value['name'];
        }
		
        $availableTagsArr = implode(',', $availableTagsWithName);
		
        $str.= "<li rel=" . $userCount . " class='" . $liCls . "' id='th-" . $otherFriend['fb_user_id'] . "'>
    	
				<span class='close'><a id='" . $otherFriend['fb_user_id'] . "' name='" . $otherFriend['name'] . "' class='delete' href='JavaScript:void(0);' title='Close'><img src='" . base_url() . "images/close-ico.png' alt='Close' /></a></span>
				
				<img width='107' height='107' src='https://graph.facebook.com/" . $otherFriend['fb_user_id'] . "/picture?width=107&height=107' alt='" . $otherFriend['name'] . "' title='" . $otherFriend['name'] . "'>   
				  
				</li>      
				
				<input id='fb-" . $otherFriend['fb_user_id'] . "' type='hidden' name='fb_id' value='" . $otherFriend['fb_user_id'] . "' class='fb_id' />";
		
        echo $str . '##' . $availableTagsArr;
		
        exit;
    }
	
	//callback function to save recommendation messages from Help Friend section
    function insertRecMsg() {
		
        $rm = new RcRecommendations;
        $rm->fb_user_id = $_POST['fbUserId'];           //$_POST['fbUserId'] contains the recommender's unique facebook id 			
        $rm->fr_fb_user_id = $_POST['frFbUserId'];      //$_POST['frFbUserId'] contains the friend's unique facebook id to whom recommendation is sent
        $rm->other_fr_fb_user_id = $_POST['otherfrFbUserId'];    //$_POST['frFbUserId'] contains candidate's unique facebook id who is recommended
		
        $rm->recommendation = $_POST['msg'];            //$_POST['msg'] contains the recommendation text
		
        $rm->relationship = $_POST['gRelation'];        //$_POST['gRelation'] contains the relation id
		
        $rm->type = $_POST['type'];						//$_POST['type'] contains the type code:	I = initiator	G = guardian                                                                                                    R = recommender
        //save the record to rc_recommendations table	
        if($_POST['msg']!='A Short Recommendation')
			$rm->save();
		else
			//do nothing
        exit;
    }	
	
	//load the dashboard view
    function dashboard() {
		
        $this->load->view('dashboard/dashboard');
    }
	
	//callback function to check whether the suggestion list is ready for the logged in user and whether the user should be allowed to go to the Create Candidate page
	//$fbUserId is the logged in user's unique facebook id
    function checkSuggestionList($fbUserId) {
        $fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($fbUserId);
        if ($fbu->set_suggestion_list == 1) {
            echo 'true';
        } else {
            echo 'false';
        }
    }
	
	//this function will save the log messages for different actions in the app                
    function rc_log_save($action = null, $referer = null, $fbUserId = null, $page = null, $msg = null, $state = null, $code = null, $timestamp = null, $ownCandidateId = null, $otherCandidateId = null) {
		
        $rclog = new RcLog;
        $rclog->action = $action;
        $rclog->referer = $referer;
        $rclog->fb_user_id = $fbUserId;
        $rclog->own_candidate_id = $ownCandidateId;
        $rclog->other_candidate_id = $otherCandidateId;
        $rclog->page = $page;
        $rclog->msg = $msg;
        $rclog->state = $state;
        $rclog->code = $code;
        $rclog->timestamp = $timestamp;
		
        //save the record to rc_log table
        $rclog->save();
    }
		
	//this function will check if candidate profile already exixts
    function chkProfileExists() {
		
        $sqlChkProfileExists = "SELECT rp.fname, rp.gender, pr.* FROM fb_user_master AS fum
									INNER JOIN rc_profiles rp
									INNER JOIN rc_profile_relation AS pr
									ON rp.fb_user_id = pr.fb_user_id
									AND fum.fb_user_id = rp.fb_user_id
									AND fum.username = '" . $_POST['fbUserName'] . "'
									AND rp.status = 1
									AND pr.type = 'G'";
		
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultChkProfileExists = $pdo->query($sqlChkProfileExists)->fetchAll();		
		
        if (count($resultChkProfileExists) > 0){
			
			$relOptStr='';
           	
			//get relation name from rc_recommrelation_master table
			$sqlGetRelationName = "SELECT pr.*, rr.*, rm.relation FROM rc_profile_relation AS pr
										 INNER JOIN rc_recommendations AS rr 
									     INNER JOIN rc_recommrelation_master AS rm
										 ON pr.other_fb_user_id = rr.other_fr_fb_user_id										 
									     AND rr.relationship = rm.id										 
									     AND rr.other_fr_fb_user_id=".$resultChkProfileExists[0]['other_fb_user_id']."
										 AND pr.type='G'
										 AND pr.status=1
										 LIMIT 0,1";
			$resultGetRelationName = $pdo->query($sqlGetRelationName)->fetchAll();
			
			//prepare relation name string
			if(count($resultGetRelationName) == 0){
				$resultGetRelationName[0]['relation']='guardian';
			}
										
			//get guardian name from fb_user_master table
			$sqlGetGuardianName = "SELECT pr.*, CONCAT(fum.fname,' ',fum.lname) AS fullname FROM rc_profile_relation AS pr 
									INNER JOIN fb_user_master AS fum
									ON fum.id = pr.guardian_fk_loc_fb_id
									AND pr.status=1
									AND pr.other_fb_user_id=".$resultChkProfileExists[0]['other_fb_user_id'];
			
			$resultGetGuardianName = $pdo->query($sqlGetGuardianName)->fetchAll();
			
			//prepare relation dropdown for "Help your friends" section
			$sqlGetRelations = "SELECT * FROM rc_recommrelation_master WHERE 1";			
			$resultGetRelations = $pdo->query($sqlGetRelations)->fetchAll();
			
			//print_r($resultGetRelations);
			
			foreach($resultGetRelations as $key => $option){
					$relOptStr .= '<option value="'.$option["id"].'">'.$option["relation"].'</option>';
			}
			
			//check gender of candidate
			$candidateGender = ($resultChkProfileExists[0]['gender'] == 1) ? 'his' : 'her';
			
			
			echo '1'.'##'.'<p class="errors mrgn-t20"><strong>A profile for '.$resultChkProfileExists[0]['fname'].' has already been created by '.$candidateGender.' '.$resultGetRelationName[0]['relation'].' '.$resultGetGuardianName[0]['fullname'].'. You can either: </strong></p>
			
			<p><input type="radio" value="'.$resultGetGuardianName[0]['other_fb_user_id'].'" id="rec_candidate" name="guardian">Recommend the candidate profile so that <strong><span name="fb_guardian" id="'.$resultGetGuardianName[0]['fb_guardian'].'">'.$resultGetGuardianName[0]['fullname'].'</span></strong> may also find matches for <strong>'.$resultChkProfileExists[0]['fname'].'</strong> within your network.</p>
			
			<div id="candidate_rec_msg" style="display:none; ">
				<textarea class="textarea inputWidth" id="candidate_rec_msg">A Short Recommendation</textarea>
			</div>
			
			<div id="candidate_relation" style="display:none; margin-top:2px;">
				<select class="selectBox inputWidthDropdown" name="select_candidate_relation" id="select_candidate_relation">'.
					$relOptStr .'
				</select>
				<br />
			</div>
			
			<p><input type="radio" value="" id="mail_guardian" name="guardian">Request <strong>'.$resultGetGuardianName[0]['fullname'].'</strong> to make you the guardian of this profile.</p>
			
			<input type="hidden" value="'.$resultGetGuardianName[0]['guardian_fk_loc_fb_id'].'" id="guardian_loc_fb_id" name="guardian_loc_fb_id">
			
			';
			
	
		}else{
			echo 0;
		}
		
	}

	//function to get recommendation relation
    function recommrelationGet() {
		
        $sqlGetCaste = "SELECT * FROM rc_recommrelation_master";
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultGetCaste = $pdo->query($sqlGetCaste)->fetchAll();
		
        foreach ($resultGetCaste as $option) {
            $relation[$option['id']] = $option['relation'];
        }
		
        echo json_encode($relation);
    }
	
	//this function will check whether the user is a rishtey user and if so, send mail
    function chkRcUser() {
         
        $sqlchkRcUser = "SELECT fum.fname, fum.lname, fum.fb_user_id, rum.* FROM fb_user_master AS fum
									INNER JOIN rc_user_master AS rum
									ON fum.id = rum.fk_loc_fb_id
									AND fum.username = '" . $_POST['fbUserName'] . "'";
		
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultchkRcUser = $pdo->query($sqlchkRcUser)->fetchAll();
		
        //get sender's name and email id
        $fbu = Doctrine::getTable('FBUserMaster')->findOneByFb_user_id($_POST['fbUserId']);
		
        if (count($resultchkRcUser) > 0) {
            //send mail to the rishtey user
            $this->load->library('email');
		
            //load mail config info from config file
            $config = $this->config->item('mailconfigData');
		
            //initialize
            $this->email->initialize($config);
		
            $this->email->from($fbu->email, $fbu->fname . ' ' . $fbu->lname);
		
            if ($_POST['inviteFriends'] == '') {
                $this->email->subject($this->config->item('initiatorMailSubject'));
            }elseif ($_POST['inviteFriends'] == 1) {
                $this->email->subject($this->config->item('recommendationMailSubject'));
            }elseif ($_POST['inviteFriends'] == 2) {
                $this->email->subject($this->config->item('profilecreationMailSubject'));
            }else{
				//do nothing
			}
		
            $this->email->to($resultchkRcUser[0]['email']);
            
			if($_POST['inviteFriends']==''){
				$this->email->message(str_replace('$baseUrl', base_url(), str_replace('$otherfrFbUserId', $_POST['otherfrFbUserId'], $this->config->item('initiatorMailMessage'))));
			}elseif($_POST['inviteFriends']==1){
				$this->email->message(str_replace('$baseUrl', base_url(), str_replace('$otherfrFbUserId', $_POST['otherfrFbUserId'], str_replace('$fbUserId', $_POST['fbUserId'], $this->config->item('recommendationMailMessage')))));
			}elseif($_POST['inviteFriends']==2){
				$this->email->message(str_replace('$baseUrl', base_url(), str_replace('$otherfrFbUserId', $_POST['otherfrFbUserId'], $this->config->item('profilecreationMailMessage'))));
			}else{
				//do nothing
			}
	
            if ($this->email->send()) {
                echo 1;
                exit;
            } else {
                echo 0;
                exit;
            }
        } else {
            //do nothing 
        }
    }
	
	
//this function will send mail to existing guardian
    function sendMailToGuardian() {
		$sqlsendMailToGuardian = "SELECT fum.fname, fum.lname, fum.fb_user_id, rum.* FROM fb_user_master AS fum
									INNER JOIN rc_user_master AS rum
									ON fum.id = rum.fk_loc_fb_id
									AND fum.id = '" . $_POST['gfbUserId'] . "'";
		
        $pdo = Doctrine_Manager::connection()->getDbh();
        $resultsendMailToGuardian = $pdo->query($sqlsendMailToGuardian)->fetchAll();
		
        //get sender's name and email id
        $fbu = Doctrine::getTable('FBUserMaster')->findOneByFb_user_id($_POST['fbUserId']);
		
        if (count($resultsendMailToGuardian) > 0) {
            //send mail to existing guardian
            $this->load->library('email');
				
            //load mail config info from config file
            $config = $this->config->item('mailconfigData');
			
            //initialize
            $this->email->initialize($config);
			
            $this->email->from($fbu->email, $fbu->fname . ' ' . $fbu->lname);		
           	
            $this->email->subject($this->config->item('changeofguardianMailSubject'));            
			
            $this->email->to($resultsendMailToGuardian[0]['email']);            
			
		    $this->email->message($this->config->item('changeofguardianMailMessage'));			
			
            if ($this->email->send()) {
                echo 1;
                exit;
            } else {
                echo 0;
                exit;
            }
        } else {
            //do nothing 
        }
		
	}
	
	
	//function that loads the different login landing pages based on type
    function showlanding() {
		
        $landingpagetype = $_POST['type'];
		
        if ($landingpagetype == 'p') {
            echo '<div class="private">
      <div class="widget-private">
        <div class="widget-index">
          <div class="button-group"><a href="' . base_url() . '" class="btn-home" title="Home"><span>Home</span></a><a href="javascript:void(0);" class="btn-private active" title="Private"><span>Private</span></a><a href="javascript:void(0);" class="btn-effective" title="Effective"><span>Effective</span></a><a href="javascript:void(0);" class="btn-simple noMargin" title="Simple"><span>Simple</span></a></div>
          <div class="slide-container">
            <div class="home-slider">
              <h2>Concerned about Privacy?</h2>
              <h3 class="center">You are always in full control</h3>
              <div class="left-block">
                <div class="trans-block">
                  <p>Rishtey Connect is a <span>PRIVATE</span> match making network</p>
                </div>
                <div class="trans-block">
                  <p>Your friends or strangers <span>wont be</span> able to browse your profile</p>
                </div>
              </div>
              <div class="right-block">
                <div class="trans-block">
                  <p>Only people <span>within your matches</span> your preferences will be able to see you</p>
                </div>
                <div class="trans-block">
                  <p><span>No one</span> contacts the candidates directly. Everything goes through <span>YOU</span></p>
                </div>
              </div>
              <div class="clear overflow">
                
			  <input type="hidden" id="loginUrl" name="loginUrl" value="' . $_POST['loginUrl'] . '" />
              <div class="pattern-leaf"><img src="' . base_url() . 'images/leaf-bg-no-repeat-private.png" alt="" /></div>
            </div>
          </div>
        </div>
      </div>
    </div>';
        } elseif ($landingpagetype == 'e') {
            echo '<div class="effective">
      <div class="widget-private min-height200">
        <div class="widget-index">
          <div class="button-group"><a href="' . base_url() . '" class="btn-home" title="Home"><span>Home</span></a><a href="javascript:void(0);" class="btn-private" title="Private"><span>Private</span></a><a href="javascript:void(0);" class="btn-effective active" title="Effective"><span>Effective</span></a><a href="javascript:void(0);" class="btn-simple noMargin" title="Simple"><span>Simple</span></a></div>
          <div class="slide-container">
            <div class="home-slider">
              <h2>Relationships between known people</h2>
              <h3> through common friends makes sense:</h3>
              <div class="left-block">
                <div class="blockr reliable">
                  <h3>Reliable</h3>
                  <div class="block-curv">
                    <div class="block-curv-innr">
                      <p>People are more honest when dealing with people within their social circles because lying can be embarrassing for them</p>
                    </div>
                  </div>
                </div>
                <div class="blockr grtr-chance">
                  <h3>Greater Chance</h3>
                  <div class="block-curv">
                    <div class="block-curv-innr">
                      <p>We have observed that proposals through common friends have greater chances of converting into an actual relationship than biodata exchanged with strangers</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="mid-block">
                <div class="find-to-match">
                  <p>Find a match for someone close to you</p>
                </div>
                <div class="find-partner">
                  <p>Find your Life partner</p>
                </div>
                <div class="find-help">
                  <p>Help other great single people to find an awesome match through your network</p>
                </div>
              </div>
              <div class="right-block">
                <div class="blockr surprise">
                  <h3>Surprise Free</h3>
                  <div class="block-curv">
                    <div class="block-curv-innr">
                      <p>It is easier to find out details about the candidates and their about post-marriage surprises</p>
                    </div>
                  </div>
                </div>
                <div class="blockr similar">
                  <h3>Similar</h3>
                  <div class="block-curv">
                    <div class="block-curv-innr">
                      <p>Birds of a feather, flock together! We tend to mix with people from similar social and economic backgrounds. So in terms of lifestyle, values, religion and caste, it is easiest to find people similar to us within our own networks</p>
                    </div>
                  </div>
                </div>
              </div>
			  <input type="hidden" id="loginUrl" name="loginUrl" value="' . $_POST['loginUrl'] . '" />
              <div class="bg-leaf"><img src="' . base_url() . 'images/leaf-bg-no-repeat-effective.png" alt="" /></div>
            </div>
          </div>
        </div>
      </div>
    </div>';
        } elseif ($landingpagetype == 's') {
            echo '<div class="simple">
      <div class="widget-private min-height200">
        <div class="widget-index">
          <div class="button-group"><a href="' . base_url() . '" class="btn-home" title="Home"><span>Home</span></a><a href="javascript:void(0);" class="btn-private" title="Private"><span>Private</span></a><a href="javascript:void(0);" class="btn-effective" title="Effective"><span>Effective</span></a><a href="javascript:void(0);" class="btn-simple noMargin active" title="Simple"><span>Simple</span></a></div>
          <div class="slide-container">
            <div class="home-slider">
              <h2>An average Facebook user has 250 friends. Through these friends, we are connected to <span>625,000</span><br />
                people.</h2>
              <h3>Rishtey Connect helps you find best matches for your loved ones within all candidates known to these <span>625,000</span> people.</h3>
              <div class="simple-step">
                <h2>Rishtey Connect is super easy to use</h2>
                <div class="simple-step-info">
                  <div class="candidates"><img src="' . base_url() . 'images/man-single.png" alt="" />
                    <div class="step-info">
                      <p>Select<br />
                        your<br />
                        Candidates</p>
                    </div>
                  </div>
                  <div class="preferences"><img src="' . base_url() . 'images/couple-thinking.png" alt="" />
                    <div class="step-info">
                      <p>Tell us about<br />
                        them and their preferences</p>
                    </div>
                  </div>
                  <div class="invite-others"><img src="' . base_url() . 'images/man-double.png" alt="" />
                    <div class="step-info">
                      <p>Invite Others</p>
                    </div>
                  </div>
                  <div class="start-matches"><img src="' . base_url() . 'images/couple.png" alt="" />
                    <div class="step-info">
                      <p>Start discovering awesome matches</p>
                    </div>
                  </div>
                </div>
              </div>
			  <input type="hidden" id="loginUrl" name="loginUrl" value="' . $_POST['loginUrl'] . '" />
              <div class="bg-leaf-pattrn"><img src="' . base_url() . 'images/leaf-bg-no-repeat-simple.png" alt="" /></div>
            </div>
          </div>
        </div>
      </div>
    </div>';
        } else {
            //do nothing
        }
    }
		
	//callback function to delete candidate's biodata
    public function deleteBiodata() {
		
        $fbUserId = $_POST['fbUserId'];      //$_POST['fbUserId'] contains logged in user's unique facebook id	
	$DestinationDirectory	= 'files/candidate_biodata/';	
        $rp = Doctrine::getTable('RcProfiles')->findOneByFb_user_id($fbUserId);
        		
		//delete file physically from server				
		//@unlink("C:/wamp/www/rishtey-connect/application/files/candidate_biodata/".$rp->biodata);
                $this->s3->deleteObject(self::$bucket,$DestinationDirectory.$rp->biodata);
		
		$rp->biodata = '';
				
        //update rc_profiles table
        $rp->save();
		
        echo 'success';
    }	
	
    function insertProfileData() {
		
        $loggedInUser = $_POST['loggedInUser'];  //unique facebook id of the logged in user who recommends
	
        $fbUserId = $_POST['fbUserId'];    //unique facebook id of the friend to whom recommendation is sent
		
        $otherfbUserId = $_POST['otherfrFbUserId']; //unique facebook id of the candidate for whom recommendation is sent
		
        $type = $_POST['type'];      //G = guardian, I = initiator, R = recommender
        //check whether profile exists
        $rcp = Doctrine::getTable('RcProfiles')->findOneByFb_user_id($otherfbUserId);
		
        //fetch fb user details of the candidate
        $fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($otherfbUserId);
		
        //fetch fb user details of the guardian
        $fbg = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($loggedInUser);
		
        // logic to insert data into rc_profile_relation table	
        // check if record exixts for initiator
        /* $sqlChkRecExists = "SELECT * FROM rc_profiles WHERE 
          fb_user_id = ".$otherfbUserId;
          $pdo = Doctrine_Manager::connection()->getDbh();
          $resultChkRecExists = $pdo->query($sqlChkRecExists)->fetchAll(); */
		
        if ($rcp->id == '') {
			
            $rcp = new RcProfiles;
			
            $rcp->fb_user_id = $otherfbUserId;
            $rcp->fk_loc_fb_id = $fbu->id;
            $rcp->fname = $fbu->fname;
            $rcp->lname = $fbu->lname;
            $rcp->gender = $fbu->gender;
            $rcp->dob = date("Y-m-d", strtotime($fbu->birthday));
            $rcp->marital_status = $fbu->relationship_status;
			
            // save to database
            $rcp->save();
			
            // insert entry to rc_profile_relation table to maintain guardian refference
            $rpr = new RcProfileRelation;
            $rpr->fb_user_id = $fbUserId;
            $rpr->fk_loc_fb_id = $fbu->id;
            $rpr->other_fb_user_id = $otherfbUserId;
            $rpr->fb_guardian = $loggedInUser;
            $rpr->guardian_fk_loc_fb_id = $fbg->id;
			
            if ($type == 'I') {
                $rpr->type = 'I';
            } elseif ($type == 'R') {
                $rpr->type = 'R';
            } else {
                $rpr->type = 'G';
            }
			
            //save to database
            $rpr->save();
			
            unset($rcp);
            unset($rpr);
        } else {
            // update rc_profile_relation table to maintain guardian erence
            $rpr = new RcProfileRelation;
            $rpr->fb_user_id = $fbUserId;
            $rpr->fk_loc_fb_id = $fbu->id;
            $rpr->other_fb_user_id = $otherfbUserId;
            $rpr->fb_guardian = $loggedInUser;
            $rpr->guardian_fk_loc_fb_id = $fbg->id;
			
            if ($type == 'I') {
                $rpr->type = 'I';
            } elseif ($type == 'R') {
                $rpr->type = 'R';
            } else {
                $rpr->type = 'G';
            }
			
            //save to database
            $rpr->save();
			
            unset($rcp);
            unset($rpr);
        }
    }

	//function to add recomendation from external recommendation form, same function is called to update also 
    function addRecommendation() {
	
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
    }
	
    function addRecommendationLog() {
		
        $type = $_POST['type'];
		
        //$action = ($type == 'I') ? 'Initiation' : 'Request for Recommendation';
		
        if ($type == 'I(BYMAIL)') {
            $action = 'Initiation by Mail';
        } elseif ($type == 'I(BYFB)') {
            $action = 'Initiation by FB';
        } elseif ($type == 'R(BYMAIL)') {
            $action = 'Request for Recommendation by Mail';
        } elseif ($type == 'R(BYFB)') {
            $action = 'Request for Recommendation by FB';
        } elseif ($type == 'G(BYMAIL)') {
            $action = 'Request for Profile Creation on RC by Mail';
        } elseif ($type == 'G(BYFB)') {
            $action = 'Request for Profile Creation on RC by FB';
        } elseif ($type == 'CG(BYMAIL)') {
            $action = 'Request for Change of Guardian by Mail';
        } else {
            $action = 'no action';
        }
		
        $referer = $_SERVER["HTTP_REFERER"];
		
        $fbUserId = $_POST['recommenderFbId'];
		
        $ownCandidateId = $_POST['candidateFbId'];
		
        $otherCandidateId = $_POST['othercandidateFbId'];
		
        //fetch details of the above users
        $fbur = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($fbUserId);
        $fbuc = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($ownCandidateId);
        $fburoc = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($otherCandidateId);
		
        $page = $_SERVER['REQUEST_URI'];
		
        if ($type == 'I(BYMAIL)') {
            $msg = $fbur->fname . ' has initiated ' . $fburoc->fname . ' to ' . $fbuc->fname.' by mail';
        }elseif ($type == 'I(BYFB)') {
            $msg = $fbur->fname . ' has initiated ' . $fburoc->fname . ' to ' . $fbuc->fname.' by fb';
        } elseif ($type == 'R(BYMAIL)') {
            $msg = $fbur->fname . ' has requested ' . $fbuc->fname . ' for recommending ' . $fburoc->fname.' by mail';
        } elseif ($type == 'R(BYFB)') {
            $msg = $fbur->fname . ' has requested ' . $fbuc->fname . ' for recommending ' . $fburoc->fname.' by fb';
        } elseif ($type == 'G(BYMAIL)') {
           $msg = $fbur->fname . ' has requested ' . $fbuc->fname . ' to be the guardian of ' . $fburoc->fname.' by mail';
        } elseif ($type == 'G(BYFB)') {
           $msg = $fbur->fname . ' has requested ' . $fbuc->fname . ' to be the guardian of ' . $fburoc->fname.' by fb';
        } elseif ($type == 'CG(BYMAIL)') {
           $msg = $fbur->fname . ' has requested existing guardian ' . $fbuc->fname . ' to be the new guardian of ' . $fburoc->fname.' by mail';
        } else {
            $msg = 'no message';
        }
		
        $state = (isset($_REQUEST['state'])) ? $_REQUEST['state'] : '';
        $code = (isset($_REQUEST['code'])) ? $_REQUEST['code'] : '';
		
        //$date = new DateTime();
        //$timestamp = $date->getTimestamp();
		
        $timestamp = time();
		
        //creatng the access-denied log.   
        $this->rc_log_save($action, $referer, $fbUserId, $page, $msg, $state, $code, $timestamp, $ownCandidateId, $otherCandidateId);
    }
    
	//function that will return caste name 
    public function getCasteName($id){
        $rcm = Doctrine::getTable("RcCasteMaster")->findOneById($id);
        return $rcm->caste;
        unset($rcm);
    }
    
	//function to send abuse mail to admin emailID
    public function AbuseMailToAdmin($abused,$abuser) {
         
        $fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($abused);
        $fromName = $fbu->fname.' '.$fbu->lname;
        $rcm = Doctrine::getTable('RcUserMaster')->findOneByFk_loc_fb_id($fbu->id);
        $fromMail = $rcm->email;
        unset($fbu);
        unset($rcm);
		
		//load mail config info from config file
        $config = $this->config->item('mailconfigData');
        $msg = $fromName.' has send a abuse report against '.$abuser;
		//initialize
        $this->email->initialize($config);
		
		//fetch and set sender info from config file
        $this->email->from($fromMail, $fromName);
        $this->email->subject($this->config->item('abuseSubject'));
        $this->email->to($this->config->item('adminMail'));
        $this->email->message($msg);
        $this->email->send();
        echo $this->email->print_debugger();
    }
    
	//function that sends mail with details of the candidate
    public function sendMail($fromMailFbId,$toMail,$shareFbId, $msg) {
        
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
		
		$file =  APPPATH.'files/pdf/sample_'.$cdtls->id.'.pdf';	         
	    $this->email->attach($file);
		
		unset($fbu);
        unset($rcm);
		unset($cdtls);
		
        $this->email->send();
        echo $this->email->print_debugger();
        
    }
    
	//function to get recommendation from rc_recommendations ongoing
    public function recommendationGet($candidateFbid){
        
        $q = Doctrine_Query::create()
        ->select('*')
        ->from('RcRecommendations r')
        ->Where('r.fr_fb_user_id = ?',$candidateFbid);               
        
        $data = $q->execute();
        $data = $data->toArray();        
        
        foreach ($data as $key => $value) {            
            $rrm = Doctrine::getTable('RcRecommrelationMaster')->findOneById($value[relationship]);
            $relationship = $rrm->relation;
            $fbm =  Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($value[fb_user_id]);

			if($fbm->count() > 0){
				$imageSmall = "http://".$this->config->item('bucket').".s3.amazonaws.com/files/profile_images/loggedinuser/".$value[fb_user_id]."_small.jpg";
            }else{
				 $imageSmall = "https://graph.facebook.com/".$value[fb_user_id]."/picture?width=50&height=50";	
			}

			$str .= "<tr>
						<td width='60' align='left' valign='top'>
							<img src=".$imageSmall." width='50' height='50' alt='' />
						</td>
						<td align='left' valign='top'>
							<strong>".$fbm->fname."'s <span>".$relationship.'</span> :</strong> '.$value[recommendation]."
						</td>
					</tr>
					<tr>
						<td width='60' align='left' valign='top'>&nbsp;</td>
						<td align='left' valign='top'>&nbsp;</td>
					</tr>";
           
            unset($fbm,$rrm);
            
            //echo $str;
        }
	return($str);
    }
	
}	
	
?>
