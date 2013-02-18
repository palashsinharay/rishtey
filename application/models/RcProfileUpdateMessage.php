<?php
// system/application/models/RcCasteMaster.php
// schema file for rc_caste_master table

class RcProfileUpdateMessage extends Doctrine_Record {

	public function setTableDefinition() {

		$this->hasColumn('fb_user_id', 'integer', 11);	// unique facebook id name
		$this->hasColumn('update_message', 'string', 255); //message
		$this->hasColumn('action', 'string', 255); //log action name  
	}

	public function setUp() {
		$this->setTableName('rc_profile_update_message');		// base table name
		$this->actAs('Timestampable');							// creates the "created_at" and "updated_at fields"
	}
	
}