<?php
// system/application/controllers/hello.php

class Hello extends Controller {

	function world() {
		echo "Hello CodeIgniter!";
	}

	function user_test() {

		$u = new User;
		$u->username = 'johndoe';
		$u->password = 'secret';
		$u->first_name = 'John';
		$u->last_name = 'Doe';
		$u->save();

		$u2 = new User;
		$u2->username = 'phprocks';
		$u2->password = 'mypass';
		$u2->first_name = 'Codeigniter';
		$u2->last_name = 'Doctrine';
		$u2->save();

		echo "added 2 users";
	}
	
	function fbuser($user)
	{
	$user_data = $this->facebook->fb->api_client->fql_query("select name from user where uid = $user");	
	print_r($user_data);
	}

}
