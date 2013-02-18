<?php
// system/application/models/RcProfilePreference.php
class RcProfilePreference extends Doctrine_Record {

	public function setTableDefinition() {
		
		$this->hasColumn('fb_user_id', 'integer', 11);
		$this->hasColumn('fk_loc_fb_id', 'integer', 10);				// unique auto-increment id which is the primary key in fb_user_master table
		$this->hasColumn('from_age', 'double', 11);
		$this->hasColumn('to_age', 'double', 11);
		$this->hasColumn('marital_status', 'string', 255);
		$this->hasColumn('religion', 'string', 255);
		$this->hasColumn('mother_tongue', 'string', 255);
		$this->hasColumn('caste', 'string', 255);
		$this->hasColumn('from_height', 'integer', 11);
		$this->hasColumn('to_height', 'integer', 11);
		$this->hasColumn('min_education', 'string', 255);
		$this->hasColumn('profession', 'string', 255);
		$this->hasColumn('min_salary', 'double', 11);
		
	}
	public function setUp() {
		$this->setTableName('rc_profile_preference');
	
	}
}