<?php
// system/application/models/FbUserMaster.php
// schema file for fb_user_master table

class FbUserMaster extends Doctrine_Record {

	public function setTableDefinition() {
		$this->hasColumn('fb_user_id', 'integer', 11);					// unique id provided by facebook
		$this->hasColumn('fname', 'string', 255);						// user first name as stored in facebook
		$this->hasColumn('lname', 'string', 255);						// user last name as stored in facebook
		$this->hasColumn('username', 'string', 255);					// unique name provided by facebook
		$this->hasColumn('birthday', 'date');							// user birthday as stored in facebook
		
		$this->hasColumn('is_rc_profile', 'tinyint', 4);				// 0 = user does not have a rishtey profile																													   1 = user does have a rishtey profile

		$this->hasColumn('picture', 'string', 255);						// picture that is uploaded in facebook
		$this->hasColumn('gender', 'string', 255);						// user gender as stored in facebook
		$this->hasColumn('relationship_status', 'string', 255);			// user relationship status as stored in facebook
		$this->hasColumn('email', 'string', 255);						// user email id as stored in facebook

		$this->hasColumn('del_flag', 'tinyint', 4);						// 0 = user is added into the suggestion list                                                                                                                   1 = user is deleted from the suggestion list

		/*$this->hasColumn('recm_msg_sent', 'tinyint', 4);*/			// 0 = recommendation message not sent to user                                                                                                                  1 = recommendation message sent to user

		
		$this->hasColumn('dr_friend_cnt', 'integer', 10);				// direct friend count of the user
		$this->hasColumn('indr_friend_cnt', 'integer', 10);				// indirect friend count of the user
		$this->hasColumn('bride_cnt', 'integer', 10);					// bride count of the user
		$this->hasColumn('groom_cnt', 'integer', 10);					// groom count of the user
		$this->hasColumn('candidate_cnt', 'integer', 10);				// number of candidates created by the user

		$this->hasColumn('set_suggestion_list', 'tinyint', 4);			// 0 = suggestion list is not ready                                                                                                                             1 = suggestion list is ready

		$this->hasColumn('direct_friends_str', 'text');                 // comma separated string of all the direct friend ids (auto-increment id from fb_user_master                                                                    table) of the user 
		
	}

	public function setUp() {
			$this->setTableName('fb_user_master');						// base table name
			$this->actAs('Timestampable');								// creates the "created_at" and "updated_at fields"
	}
}
