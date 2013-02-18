<?php
// system/application/models/RcEducationMaster.php
// schema file for rc_education_master table

class RcEducationMaster extends Doctrine_Record {

	public function setTableDefinition() {

		$this->hasColumn('education', 'string', 255);	// unique education name

		
	}

	public function setUp() {
		$this->setTableName('rc_education_master');		// base table name
		
	}
	
}