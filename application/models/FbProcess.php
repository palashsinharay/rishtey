<?php
// system/application/models/FbProcess.php
// schema file for fb_process table

class FbProcess extends Doctrine_Record {

	public function setTableDefinition() {
		$this->hasColumn('fb_user_id', 'integer', 11);				// unique id provided by facebook
		
		$this->hasColumn('fk_loc_fb_id', 'integer', 10);			// unique auto-increment id which is the primary key in fb_user_master table
		
		$this->hasColumn('status', 'tinyint', 4);					// 0 =	unprocesed files															                                                                            1 =	direct friends data has been imported											                                                                        2 =	indirect friends data has been imported					                                                                                                     if the value is 2, then only we can say that the network is ready and the user is allowed	                                                                    to go to the first login landing page 
		
		$this->hasColumn('filename', 'string', 255);				// name of the file that stores friends data
		
		$this->hasColumn('send_confirmation_mail', 'tinyint', 4);	// 0 = confirmation mail not sent													                                                                            1 = confirmation mail sent
		
	}

	public function setUp() {
		$this->setTableName('fb_process');							// base table name					
		$this->actAs('Timestampable');								// creates the "created_at" and "updated_at fields"
	}
}
