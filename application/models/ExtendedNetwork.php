<?php
// system/application/models/Network.php
// schema file for extended_network table

class ExtendedNetwork extends Doctrine_Record {

	public function setTableDefinition() {
		$this->hasColumn('loc_fb_id', 'integer', 10);			// unique auto-increment id of the user which is the primary key in fb_user_master table
		$this->hasColumn('loc_fr_fb_id', 'integer', 10);		// unique auto-increment id of the friend which is the primary key in fb_user_master table
		
		$this->hasColumn('is_indirect_friend', 'tinyint', 4);	// 0 = friend is a direct friend                                                                                                                                1 = friend is an indirect friend

		$this->hasColumn('fk_rc_user_id', 'integer', 10);		// unique auto-increment id of the friend which is the primary key in rc_user_master table
		
	}

	public function setUp() {
		$this->setTableName('extended_network');				// base table name
	}
}
