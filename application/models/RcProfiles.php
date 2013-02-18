<?php
// system/application/models/RcProfiles.php
// schema file for rc_profiles table

class RcProfiles extends Doctrine_Record {

	public function setTableDefinition() {		
		$this->hasColumn('fb_user_id', 'integer', 11);			// unique id of the candidate provided by facebook
		$this->hasColumn('fk_loc_fb_id', 'integer', 10);		// unique auto-increment id which is the primary key in fb_user_master table
		$this->hasColumn('fname', 'string', 225);			// candidate first name	
		$this->hasColumn('lname', 'string', 225);			// candidate last name
		$this->hasColumn('gender', 'string', 225);			// candidate gender	
		$this->hasColumn('dob', 'date');				// candidate date of birth
		$this->hasColumn('marital_status', 'string', 255);		// candidate marital status	
		$this->hasColumn('religion', 'string', 255);			// candidate religion
		$this->hasColumn('mother_tongue', 'string', 255);		// candidate mother tongue
		$this->hasColumn('caste', 'string', 255);			// candidate caste
		$this->hasColumn('height', 'string', 255);			// candidate height (in inches)
		$this->hasColumn('location', 'string', 255);			// candidate location	
		$this->hasColumn('highest_education', 'string', 255);           // candidate highest education
                $this->hasColumn('education_des', 'string', 255);               // candidate education description
		$this->hasColumn('profession', 'string', 255);			// candidate profession
                $this->hasColumn('profession_des', 'string', 255);		// candidate profession description
		$this->hasColumn('salary', 'double');				// candidate salary
		$this->hasColumn('biodata', 'string', 255);			// candidate biodata
		$this->hasColumn('short_recommendation', 'string', 255);        // recommendation text

		$this->hasColumn('status', 'tinyint', 4);			// 0 = rishtey profile creation is incomplete                                                                                                                   1 = rishtey profile creation is complete		
		
		
	}

	public function setUp() {
			$this->setTableName('rc_profiles');			// base table name
			$this->actAs('Timestampable');				// creates the "created_at" and "updated_at fields"
	}
}