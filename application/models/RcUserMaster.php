<?php
// system/application/models/user.php
class RcUserMaster extends Doctrine_Record {

	public function setTableDefinition() {
		//$this->hasColumn('id', 'integer', 11);
		$this->hasColumn('fb_user_id', 'integer', 11);
		$this->hasColumn('fname', 'string', 255);
		$this->hasColumn('lname', 'string', 255);
		$this->hasColumn('username', 'string', 255);	
		$this->hasColumn('gender', 'integer', 4);	
		$this->hasColumn('email', 'string', 255);
		$this->hasColumn('email', 'string', 255);
		$this->hasColumn('password', 'string', 255);
	}
	public function setUp() {
		$this->setTableName('rc_user_master');
		$this->actAs('Timestampable');
		
	}
	
}