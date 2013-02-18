<?php
// system/application/models/RcRecommrelationMaster.php
// schema file for rc_recommrelation_master table

class RcRecommrelationMaster extends Doctrine_Record {

	public function setTableDefinition() {

		$this->hasColumn('relation', 'string', 255);	// unique relation name

		
	}

	public function setUp() {
		$this->setTableName('rc_recommrelation_master');		// base table name
		
	}
	
}