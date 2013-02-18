<?php
class FileToDb extends Controller {
	
	function FileToDb() 
	{
		parent::Controller();
	}
	//read data from text file and insert into fb_user_master and network tables and update "status" field of fb_process table once on a daily basis
	public function takefile()
	{     
              $timer = new Timer(1);
              $query_time = $timer->get();
		//fetch first two unprocessed files from fb_process table
		//0 indicates that the file is unprocessed
		$tfeDql = Doctrine_Query::create()
					->select('*')
					->from('TakefileExe');
		$row =$tfeDql->execute();
                $result = $row->toArray();
                //echo "<pre>";
                //print_r($result);
           
			
		//get value from fb_process table
                $ptbl = Doctrine::getTable('FbProcess')->findOneByFb_user_id($result[0]['fb_user_id']);
		
                //get value from fb_user_master table
                $fbu = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($result[0]['fb_user_id']);

                //insert direct friends data into network tables 
                $this->insertFrdData($ptbl->filename,$fbu->id);
                $ptbl->status = 1;									//1 indicates that logged in user's direct friends has been imported into network tables

                //update fb_process table
                $ptbl->save();

                //unset object
                unset($ptbl);			
		
		$tfeDql = Doctrine_Query::create()
					->delete('TakefileExe')
					->where('fb_user_id = ?',$result[0]['fb_user_id']);
                $tfeDql->execute();
		//unset objec5
		unset($tfeDql);			
		
               echo '\nDirect Friends data imported successfully for facebook id '.$result[0]['fb_user_id'];
               echo ' \nexcution time '.$processing_time = $timer->get().' Sec';
               unset($timer);
	}
	
	//fetch users with status 1 and call the function that fetches indirect friends and inserts into extended network table
	//1 indicates a record whose direct friends data has been imported
	public function takefile_indirect()
	{     
		$timer = new Timer(1);
                $query_time = $timer->get();

                //fetch first two rows with status 1
		$fbpDql = Doctrine_Query::create()
					->select('*')
					->from('FbProcess')
					->where('status = 1');
					
					
		$fbp = Doctrine::getTable('FbProcess')->findByDql($fbpDql);	
		
		
		foreach ($fbp as $key => $value) {
			$fbu = Doctrine::getTable('FbUserMaster')->findOneById($fbp[$key]->fk_loc_fb_id);						
			
			$this->insertIndirectFrdData($fbu->id, $fbu->direct_friends_str);
			$fbp[$key]->status = 2;								//2 indicates that logged in user's indirect friends has been imported into extended_network table                                                                    and his network is ready and he should be allowed to go to the first login landing page	
			//update fb_process table
			$fbp->save();
			
			//unset object
			unset($fbu);
			
		}
		
		//unset object
		unset($fbp);
		
		echo '\nIndirect Friends data imported successfully';
                echo ' \nexcution time '.$processing_time = $timer->get().' Sec ';
                unset($timer);
	}
	
	//process that sends activation mail to all new users once on a daily basis
	public function sendMailToUser()
	{
		$sqlGetUser = "SELECT fum.id, fum.fb_user_id, fum.fname, fum.lname, fum.email, fum.gender
						FROM fb_user_master AS fum 
						INNER JOIN fb_process AS p ON fum.fb_user_id = p.fb_user_id					
						AND p.status=2
						AND p.send_confirmation_mail=0";  
		
		
    $pdo = Doctrine_Manager::connection()->getDbh();       
    $resultGetUser = $pdo->query($sqlGetUser)->fetchAll();	
	
	if(count($resultGetUser)){
		
		//load CI helper file
		$this->load->library('email');
		
		//load mail config info from config file
		$config = $this->config->item('mailconfigData');	
		
		//initialize
        $this->email->initialize($config);
		
		//fetch and set sender info from config file
        $this->email->from($this->config->item('fromMailForAccountReady'), $this->config->item('fromNameForAccountReady'));
		$this->email->subject($this->config->item('subject'));	
		
		foreach($resultGetUser as $key => $val){
			$this->email->to($val['email']);
			
			//read mail text from file
			$message = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/'.APPPATH.'views/layouts/mailtmpl/accountready.php');				

			$message = str_replace('$baseUrl', base_url(), str_replace('$gender', ($val['gender']==1)?'Mr':'Ms', str_replace('$lname', $val['lname'], str_replace('$dateToday', date('D, F d, Y', time()), str_replace('$yearToday', date('Y', time()), $message)))));
				
			//$this->email->message(str_replace('$fname', $val['fname'], str_replace('$lname', $val['lname'], str_replace('$baseUrl', base_url(), $this->config->item('message')))));  
			
			$this->email->message($message);
			
			if($this->email->send()){				
				//update the "send_confirmation_mail" flag field in fb_process table
				$fbp = Doctrine::getTable('FbProcess')->findOneByFb_user_id($val['fb_user_id']);
				$fbp->send_confirmation_mail=1;		//0	=	activation mail has not been sent																											                                                      1	=	activation mail has been sent
				$fbp->save();
			}
		}              
		
        echo $this->email->print_debugger();	//CI inbuilt function, so cannot change it to Camel Case
			
	}else{
		echo "No new user in the system";
	}
	
	}
	
    //insert direct friends data into fb_user_master and network tables
    //$strfile is the text file containing friends data of the user
    //$localuserid is the auto-increment id of the user (from fb_user_master table)
    public function insertFrdData($strfile, $localuserid) {
        //read the file, unserialize it and store it in an array variable
        $fl = "http://".$this->config->item('bucket').".s3.amazonaws.com/files/friendlist/".$strfile;
        $string = file_get_contents($fl);
        $friends = unserialize($string);
		
        //initialize the direct friends array
        $drFriends = array();
		
        //check if file is empty or not (for unit testing purpose)
        if (count($friends['data']) == 0) {
            return 'NULL';
        }
		
        $fbstatus = array(1 => "Single", 2 => "In a relationship", 3 => "Engaged", 4 => "Married", 5 => "It's complicated", 6 => "In a open relationship", 7 => "Widowed", 8 => "Separated", 9 => "Divorced");
        $gender = array(1 => "male", 2 => "female");
		
		
        //first delete all records from network and extended_network tables
        
        $sqlDelNetwork = Doctrine_Query::create()
					->delete('*')
					->from('Network')
					->where('loc_fb_id = ?',$localuserid);
	$sqlDelNetwork->execute();
        //$sqlDelNetwork->delete();
        
	$sqlDelENetwork = Doctrine_Query::create()
					->delete('*')
					->from('ExtendedNetwork')
					->where('loc_fb_id = ?',$localuserid);
        $sqlDelENetwork->execute();
       // $sqlDelENetwork->delete();
        
        //die();
		
		
        foreach ($friends['data'] as $key => $person) {
		
            //filer out friends without birthday in proper format or username or relationship status
            /* if ( !isset($person['username']) || !isset($person['birthday']) || strlen($person['birthday'])<10 || !isset($person['relationship_status']) ){
              continue;
              } */
            $check = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($person['id']);	
            if($check == NULL){
            
            $u = new FbUserMaster;
            $u->fb_user_id = $person['id'];
            $u->fname = $person['first_name'];
            $u->lname = $person['last_name'];
            $u->username = (isset($person['username'])) ? $person['username'] : '';
            $u->picture = $person['picture']['data']['url'];
            $u->birthday = (isset($person['birthday'])) ? date("Y-m-d", strtotime($person['birthday'])) : 0;
            $u->gender = (isset($person['gender'])) ? array_search($person['gender'], $gender) : '';
            $u->relationship_status = (isset($person['relationship_status'])) ? array_search($person['relationship_status'], $fbstatus) : '';
            //save user data into fb_user_master table
            $u->save();

            //check whether user exists in fb_user_master table
           // $temp = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($person['id']);

            //insert data into network table									
            $n = new Network;
            $n->loc_fb_id = $localuserid;
            $n->loc_fr_fb_id = $u->id;

            //save user relationship data into network table
            $n->save();

            //insert data into extended network table							
            $en = new ExtendedNetwork;
            $en->loc_fb_id = $localuserid;
            $en->loc_fr_fb_id = $u->id;

            $drFriends[] = $u->id;

            //save user relationship data into extended network table
            $en->save();

            //unset user and network objects
            unset($u);
            unset($n);
            unset($en);
            } else{
            $temp = Doctrine::getTable('FbUserMaster')->findOneByFb_user_id($person['id']);
            
            //insert data into network table									
            $n = new Network;
            $n->loc_fb_id = $localuserid;
            $n->loc_fr_fb_id = $temp->id;

            //save user relationship data into network table
            $n->save();
            

            //insert data into extended network table							
            $en = new ExtendedNetwork;
            $en->loc_fb_id = $localuserid;
            $en->loc_fr_fb_id = $temp->id;

            //save user relationship data into extended network table
            $en->save();
            
            $drFriends[] = $temp->id;

            //unset user and network objects
            unset($temp);
            unset($n);
            unset($en);
                
            }
            unset($check);
        }
		
        //update fb_user_master table with a comma separated string of direct friends
        $fbu = Doctrine::getTable('FbUserMaster')->findOneById($localuserid);
		
        if (count($drFriends) > 0) {
            $drFriendsStr = implode(',', $drFriends);
            $fbu->direct_friends_str = $drFriendsStr;
        }
		
        $fbu->save();
        unset($fbu);
	unset($friends);
        return 1;
    }
	
	//function that inserts indirect friend data into extended network table
	//$fbId is the auto-increment id of the user (from fb_user_master table)
	//$direct_friends_str is the comma separated string of the user's direct friend ids
	public function insertIndirectFrdData($fbId, $directFriendsStr){
		
		//check whether $fbId is null or user has any direct friend or not (for unit testing purpose) 
		if($fbId=='' || $directFriendsStr==''){
			return 'NULL';
		}
		
		$sql = "SELECT loc_fr_fb_id FROM network WHERE
				loc_fb_id IN (".$directFriendsStr.")"; 
		
		$pdo = Doctrine_Manager::connection()->getDbh();
		$arr = $pdo->query($sql)->fetchAll();		
		
		foreach($arr as $key => $val){
			$en = new ExtendedNetwork;
			$en->loc_fb_id = $fbId;
			$en->loc_fr_fb_id = $val['loc_fr_fb_id'];
			$en->is_indirect_friend = 1;				//0 = friend is a direct friend                                                                                                                           1 = friend is an indirect friend
			
			//check if combination data already exists in extended network table
			$sqlChkExists="SELECT * FROM extended_network 
							 WHERE loc_fb_id=".$fbId." AND 
							 loc_fr_fb_id=".$val['loc_fr_fb_id'];
			
			//$pdo = Doctrine_Manager::connection()->getDbh();
			$arrChkExists = $pdo->query($sqlChkExists)->fetchAll();
			
			//if not then save data and unset the extended network object
			if(count($arrChkExists)==0 && ($fbId != $val['loc_fr_fb_id'])){										
				$en->save();
				unset($en);
			}			
		}
		
	}
	
	//function to unset the "status" field in fb_process table once on a weekly basis
	function unsetFbProcessStatus(){
		
		//$fbp = Doctrine::getTable('FbProcess')->findOneByStatus('0');
		
		$fbp = Doctrine_Query::create()
					->select('*')
					->from('FbProcess')
					->where('status = 0')
					->orWhere('status = 1');
		$fbp->execute();
		
		if($fbp->count() == 0){
		
			$sqlUnsetStatus = "UPDATE fb_process SET status = 0";
			
			$pdo = Doctrine_Manager::connection()->getDbh();
			
			if($resultStatus = $pdo->query($sqlUnsetStatus)){
				echo 'Status field value succesfully updated.';
			}
			
			//empty the ORM resultset
			$fbp->delete();
			
		}else{
			echo 'Some files exists with status 0 or 1';
		}
				
	}
	
	//function to calculate direct friends of the logged in facebook user and insert into database once on a hourly basis
	function calcDirectFriends(){
			
			//first fetch all users from network table with their direct friend counts
			$sqlGetAllUsers = "SELECT loc_fb_id, count(*) as numberofdirectfriends 
									FROM network
									GROUP BY loc_fb_id";									
			
			$pdo = Doctrine_Manager::connection()->getDbh(); 
                                
			$resultGetAllUsers = $pdo->query($sqlGetAllUsers)->fetchAll();
			
			foreach($resultGetAllUsers AS $key => $val){
					
				//update the direct friend count data in fb_user_master table
				$sql="UPDATE fb_user_master SET
				dr_friend_cnt = ".$val['numberofdirectfriends']."
				WHERE id = ".$val['loc_fb_id'];
				
				if($result = $pdo->query($sql)){					
					//success
				}
				
			}
			
			echo 'Direct friend count calculated succesfully.';
	}		
			
	//function to calculate indirect friends of the logged in facebook user once on a hourly basis
	function calcIndirectFriends(){
		
		//first fetch all users from extended_network table with their indirect friend counts
		$sqlGetAllUsers = "SELECT loc_fb_id, count(*) as numberofindirectfriends 
							FROM extended_network
							WHERE is_indirect_friend = 1
							GROUP BY loc_fb_id";
		
		$pdo = Doctrine_Manager::connection()->getDbh();
		
		$resultGetAllUsers = $pdo->query($sqlGetAllUsers)->fetchAll();
		
		foreach($resultGetAllUsers AS $keyUser => $valUser){							
			
				//update the indirect friend count data in fb_user_master table
				$sql="UPDATE fb_user_master SET
						indr_friend_cnt = ".$valUser['numberofindirectfriends']."
						WHERE id = ".$valUser['loc_fb_id'];
				
				if($result = $pdo->query($sql)){					
					//success
				}
		}
				
				echo 'Indirect friend count calculated succesfully.';
	}			
				
				
	//function to calculate potential brides of the logged in facebook user once on a hourly basis
	function calcPotentialBrides(){
	
	//fetch the starting and ending age limits from config file
	$stAgeLimit		=	$this->config->item('stAgeLimit');
	$endAgeLimit	=	$this->config->item('endAgeLimit');
	
	
    //first fetch all users from fb_user_master and extended_network tables with their potential bride counts
	$sqlGetAllUsers = "SELECT loc_fb_id, count(*) AS potentialbridecount FROM extended_network AS e
								INNER JOIN fb_user_master AS fum ON
								e.loc_fr_fb_id=fum.id 
								AND fum.relationship_status=1
								AND fum.gender= 2
								AND fum.username !=''
								AND (DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( fum.birthday ) ) , '%Y' ) +0) >=".$stAgeLimit." 
								AND (DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( fum.birthday ) ) , '%Y' ) +0) <=".$endAgeLimit."
								GROUP BY loc_fb_id";
	
	$pdo = Doctrine_Manager::connection()->getDbh();
	
	$resultGetAllUsers = $pdo->query($sqlGetAllUsers)->fetchAll();
	
	foreach($resultGetAllUsers AS $keyUser => $valUser){							
	
				//update the potential bride count data in fb_user_master table
				$sql="UPDATE fb_user_master SET
						bride_cnt = ".$valUser['potentialbridecount']."
						WHERE id = ".$valUser['loc_fb_id'];
				
				if($result = $pdo->query($sql)){					
					//success
				}
				
		}
				
				echo 'Potential bride count calculated succesfully.'; 
	}			
				
				
	//function to calculate potential grooms of the logged in facebook user once on a hourly basis
	function calcPotentialGrooms(){
	
	//fetch the starting and ending age limits from config file
	$stAgeLimit		=	$this->config->item('stAgeLimit');
	$endAgeLimit	=	$this->config->item('endAgeLimit');
		
     //first fetch all users from fb_user_master and extended_network tables with their potential groom counts
	 $sqlGetAllUsers = "SELECT loc_fb_id, count(*) AS potentialgroomcount FROM extended_network AS e
								INNER JOIN fb_user_master AS fum ON
								e.loc_fr_fb_id=fum.id 
								AND fum.relationship_status=1
								AND fum.gender=1
								AND fum.username !=''
								AND (DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( fum.birthday ) ) , '%Y' ) +0) >=".$stAgeLimit." 
								AND (DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( fum.birthday ) ) , '%Y' ) +0) <=".$endAgeLimit."
								GROUP BY loc_fb_id";
	
	$pdo = Doctrine_Manager::connection()->getDbh();
	
	$resultGetAllUsers = $pdo->query($sqlGetAllUsers)->fetchAll();
	
	foreach($resultGetAllUsers AS $keyUser => $valUser){																	
	
				//update the potential groom count data in fb_user_master table
				$sql="UPDATE fb_user_master SET
						groom_cnt = ".$valUser['potentialgroomcount']."						
						WHERE id = ".$valUser['loc_fb_id'];
				
				if($result = $pdo->query($sql)){					
					//success
				}
				
		}		
				echo 'Potential groom count calculated succesfully.'; 
				
	}			
				
	//function to calculate logged in facebook user's own candidates once on a hourly basis
	function getOwnCandidates(){
				
				//first fetch all users from fb_user_master and rc_profile_relation tables with their candidate counts
				$sqlGetAllUsers = "SELECT fum.id, count(*) AS candidatecount FROM fb_user_master AS fum
          							 INNER JOIN rc_profile_relation AS pr ON
           								fum.fb_user_id=pr.fb_guardian
										AND pr.type='G'
          								GROUP BY fum.fb_user_id";
				
				$pdo = Doctrine_Manager::connection()->getDbh();
				
				$resultGetAllUsers = $pdo->query($sqlGetAllUsers)->fetchAll();
				
				foreach($resultGetAllUsers AS $keyUser => $valUser){													
					
					//update the candidate count data in fb_user_master table
					$sql="UPDATE fb_user_master SET
							candidate_cnt = ".$valUser['candidatecount']."
							WHERE id = ".$valUser['id'];
					
					if($result = $pdo->query($sql)){					
						//success
					}
					
				}
					
				echo 'Candidates count calculated succesfully.';
	}			
	
	
	
	/*******************************************MATCH MAKING PROCESS 1 STARTS********************************************************************/
	function buildUserNetwork(){
		
		//fetch users from rc_user_master table
		$sqlGetAllUsers = "SELECT fum.id FROM fb_user_master AS fum
						   INNER JOIN rc_user_master AS rum
						   ON fum.id = rum.fk_loc_fb_id
						   AND rum.status=1";
		
		$pdo = Doctrine_Manager::connection()->getDbh();
		
		$resultGetAllUsers = $pdo->query($sqlGetAllUsers)->fetchAll(PDO::FETCH_ASSOC);		
		
		foreach($resultGetAllUsers AS $keyUser => $valUser){
			
			$friendsArr = array();		
			
			//build the user's friends array and store it into a table as a comma separated string
			//fetch both direct and indirect friends
			$sqlGetUsersNetwork = "SELECT loc_fr_fb_id FROM extended_network WHERE loc_fb_id = ".$valUser['id'];  
																											
			$resultGetUsersNetwork = $pdo->query($sqlGetUsersNetwork)->fetchAll(PDO::FETCH_ASSOC);
			
			//push the user himself into the array
			array_push($resultGetUsersNetwork, array('loc_fr_fb_id' => $valUser['id']));
			sort($resultGetUsersNetwork);	
			
			
			foreach($resultGetUsersNetwork AS $keyUserNetwork => $valUserNetwork){
				
				$friendsArr[] = $valUserNetwork['loc_fr_fb_id'];
				
				//check if this friend is fb friend of any other friend
				$sqlChkFriendofFriend = "SELECT * FROM extended_network WHERE loc_fr_fb_id = ".$valUserNetwork['loc_fr_fb_id']."
												AND loc_fb_id !=".$valUser['id'];
				$resultChkFriendofFriend = $pdo->query($sqlChkFriendofFriend)->fetchAll(PDO::FETCH_ASSOC);				
				
				if(count($resultChkFriendofFriend) > 0){					
					
					//push the user into the array
					$friendsArr[] = $resultChkFriendofFriend[0]['loc_fb_id'];
				
				}
				
			}
			
			$friendsStr = implode(',', $friendsArr);			
			
			//check whether record exists
			$sqlchkUserExists = "SELECT * FROM user_friends WHERE loc_fb_id = ".$valUser['id'];  
			$resultchkUserExists = $pdo->query($sqlchkUserExists)->fetchAll(PDO::FETCH_ASSOC);
				
			if(count($resultchkUserExists) == 0){
				$sqlinsertUserFriends = "INSERT INTO user_friends
												SET loc_fb_id = ".$valUser['id']."
												, network_friends_str = '".$friendsStr."'";				
			}else{
				$sqlinsertUserFriends = "UPDATE user_friends
												SET network_friends_str = '".$friendsStr."'
												WHERE loc_fb_id = ".$valUser['id'];								
			}
				
				$resultinsertUserFriends = $pdo->query($sqlinsertUserFriends);
				
		}

		echo 'User Network built succesfully';
		
	}
	/*******************************************MATCH MAKING PROCESS 1 ENDS********************************************************************/
	
	
	/*******************************************MATCH MAKING PROCESS 2 STARTS********************************************************************/
	function buildCandidateNetwork(){
		//fetch friends from user_friends table
		$sqlGetUsersNetwork = "SELECT * FROM user_friends ORDER BY loc_fb_id";
		
		$pdo = Doctrine_Manager::connection()->getDbh();
		
		$resultGetUsersNetwork = $pdo->query($sqlGetUsersNetwork)->fetchAll(); 
		
		$cand = array();							
		
			foreach($resultGetUsersNetwork AS $keyUserNetwork => $valUserNetwork){					
					
					$sqlGetCandidateNetwork = "SELECT pr.fk_loc_fb_id FROM rc_profiles AS rp
											   INNER JOIN rc_profile_relation AS pr
											   ON rp.fb_user_id = pr.other_fb_user_id
											   AND guardian_fk_loc_fb_id IN (".$valUserNetwork['network_friends_str'].")
											   AND type='G'
											   AND rp.status=1";	
					
					$resultGetCandidateNetwork = $pdo->query($sqlGetCandidateNetwork)->fetchAll(PDO::FETCH_ASSOC);
									
					if(count($resultGetCandidateNetwork) > 0){								
										
						$data = array();
						
						foreach($resultGetCandidateNetwork as $resultGetCandidateNetwork_key=>$resultGetCandidateNetwork_val ){							
						
							$data[$valUserNetwork['loc_fb_id']][] =  $resultGetCandidateNetwork_val['fk_loc_fb_id'];
						}
					
						$candStr[$valUserNetwork['loc_fb_id']] = implode(',',$data[$valUserNetwork['loc_fb_id']]);				
												
						
						//check if user exists
						$sqlChkNetworkData = "SELECT * FROM user_candidate WHERE loc_fb_id = ".$valUserNetwork['loc_fb_id'];
						$resultChkNetworkData = $pdo->query($sqlChkNetworkData)->fetchAll();											
						
						if(count($resultChkNetworkData)==0){
							
							$sqlInsertNetworkData = "INSERT INTO user_candidate SET loc_fb_id='".$valUserNetwork['loc_fb_id']."', network_candidate_str ='".$candStr[$valUserNetwork['loc_fb_id']]."'";
						
						}else{
						
							$sqlInsertNetworkData = "UPDATE user_candidate SET network_candidate_str ='".$candStr[$valUserNetwork['loc_fb_id']]."' WHERE loc_fb_id= '".$valUserNetwork['loc_fb_id']."'";
						
						}
						
						$resultInsertNetworkData = $pdo->query($sqlInsertNetworkData);							
										
					}													
						
		   }
		   
		   echo 'Candidate Network built succesfully';
					
	}
	
	/*******************************************MATCH MAKING PROCESS 2 ENDS********************************************************************/
	
	
	/*******************************************MATCH MAKING PROCESS 3 STARTS********************************************************************/
	
	function fetchMatchedCandidatesFromNetwork(){
		
		//fetch users from user_candidate table
		$sqlGetAllUsers = "SELECT * FROM user_candidate ORDER BY loc_fb_id";
		
		$pdo = Doctrine_Manager::connection()->getDbh();
		$resultGetAllUsers = $pdo->query($sqlGetAllUsers)->fetchAll(PDO::FETCH_ASSOC);
		
		foreach($resultGetAllUsers AS $keyUser => $valUser){	
		
			//fetch own candidates of the user
			$sqlGetOwnCandidates	= "SELECT rp.id, fum.fb_user_id, fum.gender FROM rc_profiles AS rp
										INNER JOIN rc_profile_relation AS pr
										INNER JOIN fb_user_master AS fum										
										ON rp.fb_user_id = pr.other_fb_user_id
										AND rp.fb_user_id = fum.fb_user_id
										AND guardian_fk_loc_fb_id = ".$valUser['loc_fb_id']."			
										AND type='G'
										AND rp.status=1";											
			
			$resultGetOwnCandidates = $pdo->query($sqlGetOwnCandidates)->fetchAll();
			
			foreach($resultGetOwnCandidates AS $keyOwnCandidate => $valOwnCandidate){
				//fetch preferences of the candidate
				$sqlGetPreferences = "SELECT * FROM rc_profile_preference WHERE fb_user_id = ".$valOwnCandidate['fb_user_id']; 
				$resultGetPreferences = $pdo->query($sqlGetPreferences)->fetchAll(PDO::FETCH_ASSOC);
				
				foreach($resultGetPreferences AS $keyUserPreference => $valUserPreference){
					
					//get minimum education id from match preference string
					$minEducationArr = explode(',',$valUserPreference['min_education']);				
					$minEducation = $minEducationArr[0];
					
					//switch case for ....
					switch ($valUserPreference['marital_status'])
					{
						case 0:
							$sqlmStatus = 'SELECT id FROM rc_relation_master';
							break;
						default:
							$sqlmStatus = $valUserPreference['marital_status'];
					}
					
					switch ($valUserPreference['religion'])
					{
						case 0:
							$sqlReligion = 'SELECT id FROM rc_religion_master';
							break;
						default:
							$sqlReligion = $valUserPreference['religion'];
					}
					
					switch ($valUserPreference['mother_tongue'])
					{
						case 0:
							$sqlmTongue = 'SELECT id FROM  rc_mtongue_master';
							break;
						default:
							$sqlmTongue = $valUserPreference['mother_tongue'];
					}
					
					switch ($valUserPreference['caste'])
					{
						case 0:
							$sqlCaste = 'SELECT id FROM rc_caste_master';
							break;
						default:
							$sqlCaste = $valUserPreference['caste'];
					} 
					
					switch ($valUserPreference['profession'])
					{
						case 0:
							$sqlProfession = 'SELECT id FROM rc_profession_master';
							break;
						default:
							$sqlProfession = $valUserPreference['profession'];
					}
					
					if($valOwnCandidate['gender'] == 1){						
						//now fetch candidates according to preference fetched above	
						$sqlgetMatchedCandidate = "SELECT * FROM rc_profiles 
															WHERE 
													DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( dob ) ) , '%Y' ) +0 >= ".
													$valUserPreference['from_age']." AND DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( dob ) ) , '%Y' ) +0 <= ". $valUserPreference['to_age']."
															AND
													gender = 2
															AND
													height >= ".$valUserPreference['from_height']." AND height <= ".$valUserPreference['to_height']."
															AND
													marital_status IN (".$sqlmStatus.")
															AND
													religion IN (".$sqlReligion.")
															AND
													mother_tongue IN (".$sqlmTongue.")
															AND
													caste IN (".$sqlCaste.")
															AND
													highest_education >=".$minEducation."	
															AND
													profession IN (".$sqlProfession.")
															AND
													salary >= ".$valUserPreference['min_salary']."
															AND
													fk_loc_fb_id IN(".$valUser['network_candidate_str'].")
															AND 
													status=1";
						
					}else{
							//same logic as above with gender = 'female'
							$sqlgetMatchedCandidate = "SELECT * FROM rc_profiles 
															WHERE 
													DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( dob ) ) , '%Y' ) +0 >= ".
													$valUserPreference['from_age']." AND DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( dob ) ) , '%Y' ) +0 <= ". $valUserPreference['to_age']."
															AND
													gender = 1
															AND
													height >= ".$valUserPreference['from_height']." AND height <= ".$valUserPreference['to_height']."
															AND
													marital_status IN (".$sqlmStatus.")
															AND
													religion IN (".$sqlReligion.")
															AND
													mother_tongue IN (".$sqlmTongue.")
															AND
													caste IN (".$sqlCaste.")
															AND
													highest_education >=".$minEducation."	
															AND
													profession IN (".$sqlProfession.")
															AND
													salary >= ".$valUserPreference['min_salary']."
															AND
													fk_loc_fb_id IN(".$valUser['network_candidate_str'].")
															AND 
													status=1";
							
					}	
					
					//echo '<br /><br />'.$sqlgetMatchedCandidate;				
					
					$pdo = Doctrine_Manager::connection()->getDbh();
						$resultGetMatchedCandidate = $pdo->query($sqlgetMatchedCandidate)->fetchAll(PDO::FETCH_ASSOC);
						
						
					//update status of all matched records for the current candidate
					$sqlUpdOneWayMatched = "UPDATE rc_matched_profile_temp SET status=1, updated_at='".date('Y-m-d H:i:s', time())."' WHERE cid = ".$valOwnCandidate['id'];
					$resultUpdOneWayMatched = $pdo->query($sqlUpdOneWayMatched);
					
					
					//now insert the new matches
					foreach($resultGetMatchedCandidate AS $keyMatchedCandidate => $valMatchedCandidate){						
						
						//check if record already exists
						$sqlchkRecExists = "SELECT * FROM rc_matched_profile_temp WHERE cid= ".$valOwnCandidate['id']." AND cid_matched= ".$valMatchedCandidate['id']; 
						$resultchkRecExists = $pdo->query($sqlchkRecExists)->fetchAll(PDO::FETCH_ASSOC);                                                
						
                        if(count($resultchkRecExists) > 0){
							
							$sqlInsOneWayMatched = "UPDATE rc_matched_profile_temp SET status=0, updated_at='".date('Y-m-d H:i:s', time())."' WHERE cid = ".$valOwnCandidate['id']." AND cid_matched= ".$valMatchedCandidate['id'];
							
						}else{
							
							//enter tne 2 way matched candidates into a temporary table
							$sqlInsOneWayMatched = "INSERT INTO  rc_matched_profile_temp SET
													cid= ".$valOwnCandidate['id'].",
													fb_user_id= ".$valOwnCandidate['fb_user_id'].",	
													cid_matched= ".$valMatchedCandidate['id'].",
													fb_user_id_matched= ".$valMatchedCandidate['fb_user_id'].",													
													created_at='".date('Y-m-d H:i:s', time())."',
													updated_at='".date('Y-m-d H:i:s', time())."'";
						}
						
						$resultInsOneWayMatched = $pdo->query($sqlInsOneWayMatched);
						
						}
						
				}
			}
			
		}

			echo 'Temporary match table built succesfully';
	}
			
	/*******************************************MATCH MAKING PROCESS 3 ENDS********************************************************************/
			
			
	/*******************************************MATCH MAKING PROCESS 4 STARTS********************************************************************/
			
			function insertFinalData(){		
				//finally enter the 2-way matched data
				$sqlSelFinalMatchedData = "SELECT a.cid, a.cid_matched 
											FROM rc_matched_profile_temp AS a, rc_matched_profile_temp AS b 
											WHERE a.cid=b.cid_matched
											AND b.cid=a.cid_matched
											AND a.status=0
											AND b.status=0";
					
				$pdo = Doctrine_Manager::connection()->getDbh();
				$resultSelFinalMatchedData = $pdo->query($sqlSelFinalMatchedData)->fetchAll(PDO::FETCH_ASSOC);
				
				
				//delete all records of the candidate from final match table
				$sqlDelFromFinalMatched = "DELETE FROM rc_matched_profile WHERE 1";
				$resultDelFromFinalMatched = $pdo->query($sqlDelFromFinalMatched);
				
					
				if (count($resultSelFinalMatchedData)>0){
					foreach($resultSelFinalMatchedData AS $keyFinalMatchedData => $valFinalMatchedData){
						$sqlInsFinalMatchedData = "INSERT INTO rc_matched_profile SET cid= ".$valFinalMatchedData['cid'].", cid_matched= ".$valFinalMatchedData['cid_matched'];
						$resultInsFinalMatchedData = $pdo->query($sqlInsFinalMatchedData);
					}
				}

				echo 'Final match table built succesfully';
				
			}	
			
	/*******************************************MATCH MAKING PROCESS 4 ENDS********************************************************************/	
			
			
			
}				
