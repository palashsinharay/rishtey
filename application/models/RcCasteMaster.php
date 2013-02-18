<?php
// system/application/models/RcCasteMaster.php
// schema file for rc_caste_master table

class RcCasteMaster extends Doctrine_Record {

	public function setTableDefinition() {

		$this->hasColumn('caste', 'string', 255);	// unique caste name

		
	}

	public function setUp() {
		$this->setTableName('rc_caste_master');		// base table name
		
	}
	
}