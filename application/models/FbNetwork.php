<?php
// system/application/models/user.php
class FbNetwork extends Doctrine_Record {

	public function setTableDefinition() {
		//$this->hasColumn('id', 'integer', 11);
		$this->hasColumn('fb_user_master_id', 'integer', 11);
		$this->hasColumn('ref_fb_id', 'integer', 11);
		
	}
public function setUp() {
		$this->setTableName('fb_network');
	}
}
