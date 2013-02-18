<?php
error_reporting(1);
//require_once(APPPATH . '/controllers/candidate.php');

class test extends Controller {
    public static $bucket;
    
    public function __construct() {
        parent::__construct();
        //$this->config->load('s3');
        $config['accessKey'] = $this->config->item('accessKey');
        $config['secretKey'] = $this->config->item('secretKey');
        self::$bucket = $this->config->item('bucket');
        $this->load->library('S3',$config);
        
    }
    
    
    /**
     * @param GLOBAL $_POST['profileFbId'] candidate's facebook id
     * @return JSON encoded string
     */
    function unitT_getProfileDetails($fbid){
        $_POST['profileFbId'] = $fbid;
        $testResult = $this->getProfileDetails();
    }
    
    function index(){
        
        $bucketname = self::$bucket;
        //print_r($config);
        
        //$s3 = new S3($config);
        // Create a Bucket
       // var_dump($this->s3->putBucket($bucketname, $this->s3->ACL_PUBLIC_READ));

        // List Buckets
        //$buckets = $this->s3->listBuckets();
        //var_dump($buckets);
        
        // Get the contents of our bucket  
        $bucket_contents = $this->s3->getBucket($bucketname);
        
        foreach ($bucket_contents as $file){  
            $fname = $file['name'];  
            $furl = "http://$bucketname.s3.amazonaws.com/".$fname;  
            //output a link to the file  
            echo "<a href=\"$furl\"><img src=\"$furl\"/></a><br />";  
        } 
        
        //var_dump($this->s3->getBucketLocation($buckets[4]));
        //$this->s3->getAuth();
    }
    
    
    
}
?>