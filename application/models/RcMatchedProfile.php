<?php
// system/application/models/RcMatchedProfile.php
class RcMatchedProfile extends Doctrine_Record {

	public function setTableDefinition() {
		$this->hasColumn('cid', 'integer', 11);
		$this->hasColumn('cid_matched', 'integer', 11);
		$this->hasColumn('is_blocked', 'integer', 4);
		$this->hasColumn('is_abused', 'integer', 4);
		
	}
	public function setUp() {
		$this->setTableName('rc_matched_profile');
	}
}