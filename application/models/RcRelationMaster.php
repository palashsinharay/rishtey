<?php
// system/application/models/RcRelationMaster.php
// schema file for rc_relation_master table

class RcRelationMaster extends Doctrine_Record {

	public function setTableDefinition() {

		$this->hasColumn('relation_name', 'string', 255);	// unique relationstatus name

		
	}

	public function setUp() {
		$this->setTableName('rc_relation_master');		// base table name
		
	}
	
}