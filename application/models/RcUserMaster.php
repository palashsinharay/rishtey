<?php
// system/application/models/RcUserMaster.php
// schema file for rc_user_master table

class RcUserMaster extends Doctrine_Record {

	public function setTableDefinition() {
		
		$this->hasColumn('fk_loc_fb_id', 'integer', 10);		// unique auto-increment id of the user which is the primary key in fb_user_master table
		$this->hasColumn('picture', 'string', 255);				// picture that is uploaded in facebook 
		$this->hasColumn('email', 'string', 255);				// rishtey user email id
		$this->hasColumn('phone', 'string', 255);				// rishtey user phone number 
		$this->hasColumn('password', 'string', 255);			// rishtey user password
		$this->hasColumn('status', 'tinyint', 4);				// 0 = user not activated  1 = user activated
	}

	public function setUp() {
		$this->setTableName('rc_user_master');					// base table name
		$this->actAs('Timestampable');							// creates the "created_at" and "updated_at fields"
		
	}
	
}