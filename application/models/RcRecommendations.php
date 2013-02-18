<?php
// system/application/models/RcRecommendations.php
// schema file for rc_recommendations table

class RcRecommendations extends Doctrine_Record {

	public function setTableDefinition() {
		$this->hasColumn('fb_user_id', 'integer', 11);			//unique facebook user id of the recommender
		//$this->hasColumn('recommender_id', 'integer', 10);    //unique facebook user id of the recommender
		$this->hasColumn('fr_fb_user_id', 'integer', 11);		//unique facebook id of friend to whom recommendation is sent
		$this->hasColumn('other_fr_fb_user_id', 'integer', 11);	//unique facebook id of the candidate for whom recommendation is sent

		//$this->hasColumn('profile_id', 'integer', 10);        //candidate's auto increment id from rc_profile table 
		$this->hasColumn('relationship', 'string');				//relationship code of the recommender/initiator with the candidate	
		$this->hasColumn('recommendation', 'string');		    //the recommendation message
		$this->hasColumn('type', 'string');		    			//G=guardian, R=recommender, I=initiator

	}

	public function setUp() {
		$this->setTableName('rc_recommendations');				//base table name
	}

}
