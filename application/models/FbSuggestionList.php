<?php
// system/application/models/FbSuggestionList.php
// schema file for fb_suggestion_list table

class FbSuggestionList extends Doctrine_Record {

	public function setTableDefinition() {
		$this->hasColumn('fb_user_id', 'integer', 11);				// unique id provided by facebook
		$this->hasColumn('ref_fb_user_id', 'integer', 11);			// unique auto-increment id which is the primary key in fb_user_master table
																	
		$this->hasColumn('rem_candidature_flag', 'tinyint', 4);		// 0 = friend is visible in "Help Your Friend section" 									                                                                        1 = friend's visibility is cancelled in "Help Your Friend section"

		$this->hasColumn('send_message', 'tinyint', 4);				// 0 = message not sent to friend                                                                                                                               1 = message sent to friend
	}

	public function setUp() {
		$this->setTableName('fb_suggestion_list');					// base table name
	}
}
