<?php
// system/application/models/user.php
class RcMatchCandidate extends Doctrine_Record {

	public function setTableDefinition() {
		//$this->hasColumn('id', 'integer', 11);
		$this->hasColumn('cid', 'integer', 11);
		$this->hasColumn('cid_matched', 'integer', 11);
		
		
		
	}
public function setUp() {
		$this->setTableName('rc_match_candidate');
	
	}
}