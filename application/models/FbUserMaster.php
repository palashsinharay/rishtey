<?php
// system/application/models/user.php
class FbUserMaster extends Doctrine_Record {

	public function setTableDefinition() {
		//$this->hasColumn('id', 'integer', 11);
		$this->hasColumn('fb_user_id', 'integer', 11);
		$this->hasColumn('fname', 'string', 255);
		$this->hasColumn('lname', 'string', 255);
		$this->hasColumn('username', 'string', 255);	
		$this->hasColumn('gender', 'string', 255);
		$this->hasColumn('relationship_status', 'string', 255);
		$this->hasColumn('picture', 'string', 255);
		$this->hasColumn('birthday', 'string', 255);
		$this->hasColumn('email', 'string', 255);
		$this->hasColumn('is_rc_profile', 'integer', 11);	
		
	}
public function setUp() {
		$this->setTableName('fb_user_master');
		$this->actAs('Timestampable');
	}
}
