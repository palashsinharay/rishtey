<?php
// system/application/models/RcProfilePicture.php
// schema file for rc_profile_picture table

class RcProfilePicture extends Doctrine_Record {


	public function setTableDefinition() {		
		$this->hasColumn('fb_user_id', 'integer', 11);				// unique id of the candidate provided by facebook
		$this->hasColumn('picture', 'string', 255);					// candidate profile image
		$this->hasColumn('img_tag_id', 'tinyint', 4);				// candidate image tag id

		
	}
	
	public function setUp() {
			$this->setTableName('rc_profile_picture');				// base table name
		
	}

}