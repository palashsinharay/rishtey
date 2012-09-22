<?php
// system/application/models/user.php
class RcCandidatePicture extends Doctrine_Record {

	public function setTableDefinition() {
		//$this->hasColumn('id', 'integer', 11);
		$this->hasColumn('cid', 'integer', 11);
		$this->hasColumn('picture', 'string', 255);
		
		
		
	}
public function setUp() {
		$this->setTableName('rc_candidate_picture');
	
	}
}