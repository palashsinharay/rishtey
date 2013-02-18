<?php
// system/application/models/RcMtongueMaster.php
// schema file for rc_mtongue_master table

class RcMtongueMaster extends Doctrine_Record {

	public function setTableDefinition() {

		$this->hasColumn('language_name', 'string', 255);	// unique language name

		
	}

	public function setUp() {
		$this->setTableName('rc_mtongue_master');		// base table name
		
	}
	
}