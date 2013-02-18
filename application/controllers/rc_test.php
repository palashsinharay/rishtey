<?php
error_reporting(1);
require_once(APPPATH . '/controllers/FileToDb.php');

class Rc_test extends FileToDb {

	function Rc_test()
	{
		parent::Controller();	

		$this->load->library('unit_test');
	}
	
	function index()
	{
		//do nothing

	}

	function rcTest_insertFrdData($filename, $uid)
	{
		$data = array();
		
		$numbers = array(
		//'' => 'exception',
		'NULL' => 'exception',
		//'x' => 'exception',
		//'9999' => 'OK',
		//'-1' => 'exception',
		//'1' => 'OK'
		);


		foreach($numbers AS $testkey => $testvalue)
		{
			$dbvalue = $this->insertFrdData($filename, $uid);
			$result .= $this->unit->run(preg_match("/$testkey/",$dbvalue), 1, 'File contains no data');
		}
		
		//print_r($result);
		//exit;

		$data['records'] = $this->unit->result();

		
		$this->load->view('rc_test', $data);

	}


	function rcTest_insertIndirectFrdData($fbId, $direct_friends_str)
	{
		$data = array();
		
		$numbers = array(
		//'' => 'exception',
		'NULL' => 'exception',
		//'x' => 'exception',
		//'9999' => 'OK',
		//'-1' => 'exception',
		//'1' => 'OK'
		);


		foreach($numbers AS $testkey => $testvalue)
		{
			$dbvalue = $this->insertIndirectFrdData($fbId, $direct_friends_str);
			$result .= $this->unit->run(preg_match("/$testkey/",$dbvalue), 1, 'User has no direct friends');
		}
		
		//print_r($result);
		//exit;

		$data['records'] = $this->unit->result();

		
		$this->load->view('rc_test', $data);

	}	


}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */