<?php
// system/application/models/RcLog.php
// schema file for rc_log table

class RcLog extends Doctrine_Record {

	public function setTableDefinition() {
		$this->hasColumn('action', 'string', 255);				
		$this->hasColumn('referer', 'text');						// referer url from where the user has come
		$this->hasColumn('fb_user_id', 'integer', 11);				// unique id provided by facebook
		$this->hasColumn('own_candidate_id', 'integer', 11);		
		$this->hasColumn('other_candidate_id', 'integer', 11);
		$this->hasColumn('page', 'string', 255);		
		$this->hasColumn('msg', 'text');							// message string
		$this->hasColumn('state', 'text');							// state value
		$this->hasColumn('code', 'text');							// code value
		$this->hasColumn('timestamp', 'integer', 11);				// stores current timestamp
	}

	public function setUp() {
		$this->setTableName('rc_log');								// base table name
		
	}
	
}