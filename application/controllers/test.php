<?php
class Test extends Controller{
	function index(){
		
		//$this->load->helper('file');	
				echo $base = APPPATH."test.txt";
				$data[0] = "sinharay";
				$data[1] = "dutta";
				$data[2] = "rama";
				$sdata = serialize($data);
				$string = write_file($base,$sdata,'a+');
				//echo $string;
		
		
/*
$filename = APPPATH."test.txt";
$handle = fopen($filename, "w");

while(!feof($handle))
  {
  echo fgets($handle). "<br />";
  }
fwrite($handle, '1');
fwrite($handle, '23');
fclose($handle);*/

//echo $contents;	
		/*
		$data = 'Some file data';
		
					if ( ! write_file($base.'file.php', $data,'r+'))
					{
						 echo 'Unable to write the file';
					}
					else
					{
						 echo 'File written!';
					}*/
		
	}
}
