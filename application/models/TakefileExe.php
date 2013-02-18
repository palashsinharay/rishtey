<?php
// system/application/models/FbProcess.php
// schema file for fb_process table

class TakefileExe extends Doctrine_Record {

	public function setTableDefinition() {
		$this->hasColumn('fb_user_id', 'integer', 11);				// unique id provided by facebook										                                                                            1 = confirmation mail sent
		
	}

	public function setUp() {
		$this->setTableName('takefile_exe');							// base table name													// creates the "created_at" and "updated_at fields"
	}
}
