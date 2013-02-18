<?php
// system/application/models/RcMatchedProfile.php
class RcProfileShowInterest extends Doctrine_Record {

	public function setTableDefinition() {
		$this->hasColumn('cid', 'integer', 11);
		$this->hasColumn('cid_matched', 'integer', 11);
		$this->hasColumn('interest_message', 'string', 255);
		
	}
	public function setUp() {
		$this->setTableName('rc_profile_show_interest');
		$this->actAs('Timestampable');
	}
}