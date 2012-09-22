<?php
// system/application/models/user.php
class RcMatchPreference extends Doctrine_Record {

	public function setTableDefinition() {
		//$this->hasColumn('id', 'integer', 11);
		$this->hasColumn('id', 'integer', 11);
		$this->hasColumn('cid', 'integer', 11);
		$this->hasColumn('from_age', 'double', 11);
		$this->hasColumn('to_age', 'double', 11);
		$this->hasColumn('marital_status', 'integer', 11);
		$this->hasColumn('religion', 'integer', 11);
		$this->hasColumn('mother_tongue', 'integer', 11);
		$this->hasColumn('cast', 'integer', 11);
		$this->hasColumn('from_height', 'integer', 11);
		$this->hasColumn('to_height', 'integer', 11);
		$this->hasColumn('min_education', 'string', 255);
		$this->hasColumn('profession', 'string', 255);
		$this->hasColumn('min_salary', 'double', 11);
		
	}
public function setUp() {
		$this->setTableName('rc_match_preference');
	
	}
}