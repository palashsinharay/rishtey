<?php
// system/application/models/user.php
class RcCandidateRelation extends Doctrine_Record {

	public function setTableDefinition() {
		//$this->hasColumn('id', 'integer', 11);
		$this->hasColumn('cid', 'integer', 11);
		$this->hasColumn('fb_guardian', 'integer', 11);
		$this->hasColumn('fb_initiator', 'integer', 11);
		$this->hasColumn('fb_recommender', 'integer', 11);
		
		
	}
public function setUp() {
		$this->setTableName('rc_candidate_relation');
	
	}
}