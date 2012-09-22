<?php
// system/application/models/user.php
class RcCandidateMaster extends Doctrine_Record {

	public function setTableDefinition() {
		//$this->hasColumn('id', 'integer', 11);
		$this->hasColumn('cId', 'integer', 11);
		$this->hasColumn('creator_id', 'integer', 11);
		$this->hasColumn('fname', 'string', 225);
		$this->hasColumn('lname', 'string', 225);
		$this->hasColumn('gender', 'integer', 11);
		$this->hasColumn('dob', 'string', 255);
		$this->hasColumn('marital_status', 'string', 255);
		$this->hasColumn('mother_tongue', 'string', 255);
		$this->hasColumn('caste', 'string', 255);
		$this->hasColumn('height', 'string', 255);
		$this->hasColumn('location', 'string', 255);
		$this->hasColumn('highest_education', 'string', 255);
		$this->hasColumn('profession', 'string', 255);
		$this->hasColumn('salary', 'string', 255);
		$this->hasColumn('biodata', 'string', 255);
		$this->hasColumn('short_recommendation', 'string', 255);
		
		
	}
public function setUp() {
		$this->setTableName('rc_candidate_master');
		$this->actAs('Timestampable');
	}
}