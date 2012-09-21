<?php
// system/application/models/user.php
class Friend extends Doctrine_Record {

	public function setTableDefinition() {
		$this->hasColumn('fb_id', 'integer', 10);
		$this->hasColumn('fname', 'string', 255);
		$this->hasColumn('lname', 'string', 255);
		$this->hasColumn('username', 'string', 255);	
		$this->hasColumn('gender', 'string', 255);	
		$this->hasColumn('email', 'string', 255);	
		$this->hasColumn('password', 'string', 255);	
		$this->hasColumn('is_rishtey_user', 'string', 255);	
		$this->hasColumn('date_created', 'string', 255);
		$this->hasColumn('date_modified', 'string', 255);
	}

}
