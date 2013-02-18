<?php
// system/application/models/RcProfileRelation.php
class RcProfileRelation extends Doctrine_Record {

	public function setTableDefinition() {
		//$this->hasColumn('id', 'integer', 11);
		$this->hasColumn('fb_user_id', 'integer', 11);					//unique facebook user id of the friend to whom recommendation is sent				
		$this->hasColumn('fk_loc_fb_id', 'integer', 10);				//unique auto-increment id of the friend to whom recommendation is sent and which is the primary key in fb_user_master table
		$this->hasColumn('other_fb_user_id', 'integer', 11);			//unique facebook user id of the candidate for whom recommendation is sent

		$this->hasColumn('guardian_fk_loc_fb_id', 'integer', 10 );
		$this->hasColumn('fb_guardian', 'integer', 10);					//unique facebook user id of the recommender

		$this->hasColumn('type', 'string');								//G=guardian, R=recommender, I=initiator

		$this->hasColumn('recm_msg_sent', 'tinyint', 4);				//0 = recommendation message not sent to user                                                                                                                  1 = recommendation message sent to user
		
		
	}
	public function setUp() {
		$this->setTableName('rc_profile_relation');						//base table
		
	}
}