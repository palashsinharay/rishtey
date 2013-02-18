<?php
error_reporting(1);
require_once(APPPATH . '/controllers/functions.php');

class Rc_test_functions extends Functions {

	function Rc_test_functions()
	{
		parent::Controller();	

		$this->load->library('unit_test');
	}
	
	function index()
	{
		//do nothing

	}

	function rcTest_getSinglefriends($id)
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
			$dbvalue = $this->getSinglefriends($id);
			$result .= $this->unit->run(preg_match("/$testkey/",$dbvalue), 1, 'User is not logged in');
		}
		
		//print_r($result);
		//exit;

		$data['records'] = $this->unit->result();

		
		$this->load->view('rc_test', $data);

	}


	function rcTest_getLeftPanelCounts($id)
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
			$dbvalue = $this->getLeftPanelCounts($id);
			$result .= $this->unit->run(preg_match("/$testkey/",$dbvalue), 1, 'User is not logged in');
		}
		
		//print_r($result);
		//exit;

		$data['records'] = $this->unit->result();

		
		$this->load->view('rc_test', $data);

	}

	function rcTest_getDirectFriends($id)
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
			$dbvalue = $this->getDirectFriends($id);
			$result .= $this->unit->run(preg_match("/$testkey/",$dbvalue), 1, 'User is not logged in');
		}
		
		//print_r($result);
		//exit;

		$data['records'] = $this->unit->result();

		
		$this->load->view('rc_test', $data);

	}


	function rcTest_getIndirectFriends($id)
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
			$dbvalue = $this->getIndirectFriends($id);
			$result .= $this->unit->run(preg_match("/$testkey/",$dbvalue), 1, 'User is not logged in');
		}
		
		//print_r($result);
		//exit;

		$data['records'] = $this->unit->result();

		
		$this->load->view('rc_test', $data);

	}

	function rcTest_getAllfriends($id, $frData, $frFbUserName)
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
			$dbvalue = $this->getAllfriends($id, $frData, $frFbUserName);
			$result .= $this->unit->run(preg_match("/$testkey/",$dbvalue), 1, 'Unauthorised access');
		}
		
		//print_r($result);
		//exit;

		$data['records'] = $this->unit->result();

		
		$this->load->view('rc_test', $data);

	}


	function rcTest_getAllFbfriends($id)
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
			$dbvalue = $this->getAllFbfriends($id);
			$result .= $this->unit->run(preg_match("/$testkey/",$dbvalue), 1, 'User is not logged in');
		}
		
		//print_r($result);
		//exit;

		$data['records'] = $this->unit->result();

		
		$this->load->view('rc_test', $data);

	}

	function rcTest_getSuggestedfriends($id)
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
			$dbvalue = $this->getSuggestedfriends($id);
			$result .= $this->unit->run(preg_match("/$testkey/",$dbvalue), 1, 'User is not logged in');
		}
		
		//print_r($result);
		//exit;

		$data['records'] = $this->unit->result();

		
		$this->load->view('rc_test', $data);

	}


}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */