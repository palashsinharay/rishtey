<?php 
class ShowFriends extends Controller
{

 function ShowFriends()
 {
   parent::Controller();
   
 }

 function index()
{
$q = Doctrine_Query::create()
	->select('*')
	->from('FbUserMaster');
//die();
$result = $q->execute();
$data_arr = $result->toArray();

$data['records'] = $data_arr;

$this->load->view('singlefbfriend', $data);

}
}


