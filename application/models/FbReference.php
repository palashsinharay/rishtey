<?php
// system/application/models/user.php
class FbReference extends Doctrine_Record {

	public function setTableDefinition() {
		//$this->hasColumn('id', 'integer', 11);
		$this->hasColumn('fb_id', 'string', 255);
		$this->hasColumn('ref_friend_id', 'integer', 11);

		
	}
public function setUp() {
		$this->setTableName('fb_reference');
		
	}
}