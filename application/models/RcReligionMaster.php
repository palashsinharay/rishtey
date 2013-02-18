<?php
// system/application/models/RcReligionMaster.php
// schema file for rc_religion_master table

class RcReligionMaster extends Doctrine_Record {

	public function setTableDefinition() {

		$this->hasColumn('religion_name', 'string', 255);	// unique religion name

		
	}

	public function setUp() {
		$this->setTableName('rc_religion_master');		// base table name
		
	}
	
}