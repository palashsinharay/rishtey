<?php
// system/application/models/RcProfessionMaster.php
// schema file for rc_profession_master table

class RcProfessionMaster extends Doctrine_Record {

	public function setTableDefinition() {

		$this->hasColumn('profession', 'string', 255);	// unique profession name

		
	}

	public function setUp() {
		$this->setTableName('rc_profession_master');		// base table name
		
	}
	
}