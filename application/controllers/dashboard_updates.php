<?php
/*************************************************************
This process is to generate the RC Updates for each candidate. This process will
get executed at the 00 hrs everyday & will insert the update data to the 
"rc_profile_update_message".
The used actions are:-
New match
New recomedation
Sent Interest
Blocked Candidate (Latest) 
Received interest (Latest)   
*** 
******************************/
class dashboard_updates extends Controller {

//function to get all records with Complete profile (i.e with status = 1 on rc_profile )  
    function getCompleteProfile() {
    $sql = "SELECT * FROM rc_profiles WHERE status=1";
    $pdo = Doctrine_Manager::connection()->getDbh();       
    $resultSet = $pdo->query($sql)->fetchAll();
    echo "<pre>";
    foreach($resultSet as $value){
            $completeProfileId[] = $value['fb_user_id'];
            //echo $value['fb_user_id']."<br>"; 
    }
    return $completeProfileId;
}

//function to get all records from rc_log table for recommendation  
    function getRecommenderFromLog($fb_id) {
		$sql = "SELECT * FROM rc_log WHERE DATE(from_unixtime(timestamp)) = CURDATE() AND action='recommendation' AND own_candidate_id=$fb_id";
		//$sql = "SELECT * FROM rc_log WHERE action='recommendation' AND own_candidate_id=$fb_id";
            $pdo = Doctrine_Manager::connection()->getDbh();       
	    $resultSet = $pdo->query($sql)->fetchAll();
		return $RecommenderLogMessage = $resultSet[0]['msg'];		
}

//function to get all message from rc_log table for recommendation
    function getRecommenderUpdates() {
        //get all complete profile id
        $profileId = $this->getCompleteProfile();

        //get all recommendation message
        foreach ($profileId as $value) {

            if ($this->getRecommenderFromLog($value) != '') {
                $listOfRecommendationMsg[$value] = $this->getRecommenderFromLog($value);
            }
        }
        //print_r($listOfRecommendationMsg);
        return $listOfRecommendationMsg;
    }

// this function needs to run once a day to insert Reccommendation message to RcProfileUpdateMessage form rc_log table
    function insertRecommenderUpdateMsg() {
        $data = $this->getRecommenderUpdates();
        foreach ($data as $key => $value) {
            $rpum = new RcProfileUpdateMessage;
            $rpum->fb_user_id = $key;
            $rpum->update_message = $value;
            $rpum->action = 'recommendation';
            $rpum->save();
            unset($rpum);
        }

		echo 'User Recommendations fetched succesfully from log';  	
} 

//this function needs to run once a day to insert show interest message to RcProfileUpdateMessage form rc_log table
    function insertShowInterestUpdateMsg() {
            
            $q = Doctrine_Query::create()
            ->select('*')
            ->from('RcLog')        
            ->where('action = ?','Interest request')
            ->andWhere('DATE(from_unixtime(timestamp)) = CURDATE()');

            echo $q->getSqlQuery();
            $rows = $q->execute();
            $data = $rows->toArray();
            //echo "<pre>";
            //print_r($rows->toArray());
            //echo "</pre>";
        
        foreach ($data as $key => $value) {
            //insert Interest request sent to the guardian to RcProfileUpdateMessage  
            $fbm = Doctrine::getTable("FbUserMaster")->findOneByFb_user_id($value['other_candidate_id']);
            //get guardian details 
            $fbm2 = Doctrine::getTable("FbUserMaster")->findOneByFb_user_id($value['fb_user_id']);
            $rpum = new RcProfileUpdateMessage;
            $rpum->fb_user_id = $value['own_candidate_id'];
            $rpum->update_message = $value['msg'];
            $rpum->action = 'Interest request to guardian';
            $rpum->save();
            unset($rpum);
            unset($fbm);
            unset($fbm2);
            
            //insert received Interest request to RcProfileUpdateMessage
//            $fbm = Doctrine::getTable("FbUserMaster")->findOneByFb_user_id($value['fb_user_id']);
//            $rpum = new RcProfileUpdateMessage;
//            $rpum->fb_user_id = $value['own_candidate_id'];
//            $rpum->update_message = "Received <span>Interest</span> from ".'<span>'. substr($fbm->fname,0,1).' '.$fbm->lname.'</span>';
//            $rpum->action = 'Received Interest request';
//            $rpum->save();
//            unset($rpum);
//            unset($fbm);
            
            
        }

		echo 'Candidate Interests fetched succesfully from log'; 

    }

//this function needs to run once a day to insert blocked message to RcProfileUpdateMessage form rc_log table
    function insertBlockedUpdateMsg() {
            
            $q = Doctrine_Query::create()
            ->select('*')
            ->from('RcLog')        
            ->where('action = ?','blocked')
            ->andWhere('DATE(from_unixtime(timestamp)) = CURDATE()');

            echo $q->getSqlQuery();
            $rows = $q->execute();
            $data = $rows->toArray();
            
			//echo "<pre>";
            //print_r($rows->toArray());
            //echo "</pre>";
        
        foreach ($data as $key => $value) {
            $rpum = new RcProfileUpdateMessage;
            $rpum->fb_user_id = $value['own_candidate_id'];
            $rpum->update_message = $value['msg'];
            $rpum->action = $value['action'];
            $rpum->save();
            unset($rpum);   
        }

		echo 'Blocked candidate information fetched succesfully from log';

    }

//this function needs to run once a day to insert new match for today to RcProfileUpdateMessage from rc_matched_profile_temp table
    public function insertNewMatchUpdateMsg() {
//finally enter the 2-way matched data
    $sqlSelFinalMatchedData = "SELECT a.cid, a.cid_matched 
                                                            FROM rc_matched_profile_temp AS a, rc_matched_profile_temp AS b 
                                                            WHERE a.cid=b.cid_matched
                                                            AND b.cid=a.cid_matched
                                                            AND a.status=0
                                                            AND b.status=0 AND DATE(a.created_at) = CURDATE()";
//        $sqlSelFinalMatchedData = "SELECT a.cid, a.cid_matched 
//                                                            FROM rc_matched_profile_temp AS a, rc_matched_profile_temp AS b 
//                                                            WHERE a.cid=b.cid_matched
//                                                            AND b.cid=a.cid_matched
//                                                            AND a.status=0
//                                                            AND b.status=0";

    $pdo = Doctrine_Manager::connection()->getDbh();
    $resultSelFinalMatchedData = $pdo->query($sqlSelFinalMatchedData)->fetchAll(PDO::FETCH_ASSOC);
    
	//echo '<pre>';
    //print_r($resultSelFinalMatchedData);

    foreach ($resultSelFinalMatchedData as $key => $value) {
            $rcp1 = Doctrine::getTable('RcProfiles')->findOneById($value['cid']);
            $rcp2 = Doctrine::getTable('RcProfiles')->findOneById($value['cid_matched']);
            $matchName = substr($rcp2->fname,0,1).' '.$rcp2->lname;
            $msg = 'New match <span>'.$matchName.'</span>'; 
            
            $rpum = new RcProfileUpdateMessage;
            $rpum->fb_user_id = $rcp1->fb_user_id;
            $rpum->update_message = $msg;
            $rpum->action = 'match';
            $rpum->save();
            unset($rpum);
            unset($rcp1);
            unset($rcp2);
    }

	echo 'New matched candidate information fetched succesfully from log';
    
 }

}
