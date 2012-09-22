<?php
// system/application/models/user.php
class FbProcess extends Doctrine_Record {

	public function setTableDefinition() {
		//$this->hasColumn('id', 'integer', 11);
		$this->hasColumn('fb_user_master_id', 'integer', 11);
		$this->hasColumn('status', 'integer', 4);
		$this->hasColumn('file', 'string', 255);
		$this->hasColumn('send_confirmation_mail', 'integer', 4);	
		
	}
public function setUp() {
		$this->setTableName('fb_process');
		$this->actAs('Timestampable');
	}
}
