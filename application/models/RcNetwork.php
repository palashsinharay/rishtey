<?php
// system/application/models/user.php
class RcNetwork extends Doctrine_Record {

	public function setTableDefinition() {
		//$this->hasColumn('id', 'integer', 11);
		$this->hasColumn('rc_user_master_id', 'integer', 11);
		$this->hasColumn('ref_rc_id', 'integer', 11);
		
		
	}
public function setUp() {
		$this->setTableName('rc_network');
	
	}
}