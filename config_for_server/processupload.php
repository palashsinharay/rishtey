<?php
/*** Upload Image Class ***/
class processUpload extends Controller
{
    function __construct()
    {
        parent::Controller();
        $this->load->helper('url');
        
    }
 
    function uploadImage(){       
        
    
        //Some Settings
        $ThumbSquareSize        = 107; //Thumbnail will be 200x200
        $BigImageMaxSize        = 300; //Image Maximum height or width
        $ThumbDir               = "thumbs/"; //Normal thumb Prefix
        $DestinationDirectory	= APPPATH.'files/profile_images/'; //Upload Directory ends with / (slash)
        $Quality 		= 90;

           
        // check $_FILES['ImageFile'] array is not empty
        // "is_uploaded_file" Tells whether the file was uploaded via HTTP POST
        if(!isset($_FILES['ImageFile']) || !is_uploaded_file($_FILES['ImageFile']['tmp_name']))
        {
                        die('Upload Error!'); // output error when above checks fail.
        }

        // Elements (values) of $_FILES['ImageFile'] array
        //let's access these values by using their index position
        $ImageName 		= str_replace(' ','-',strtolower($_FILES['ImageFile']['name'])); 
        $ImageSize 		= $_FILES['ImageFile']['size']; // Obtain original image size
        $TempSrc	 	= $_FILES['ImageFile']['tmp_name']; // Tmp name of image file stored in PHP tmp folder
        $ImageType	 	= $_FILES['ImageFile']['type']; //Obtain file type, returns "image/png", image/jpeg, text/plain etc.

        //Let's use $ImageType variable to check wheather uploaded file is supported.
        //We use PHP SWITCH statement to check valid image format, PHP SWITCH is similar to IF/ELSE statements 
        //suitable if we want to compare the a variable with many different values
        switch(strtolower($ImageType))
        {
                case 'image/png':
                        $CreatedImage =  imagecreatefrompng($_FILES['ImageFile']['tmp_name']);
                        break;
                case 'image/gif':
                        $CreatedImage =  imagecreatefromgif($_FILES['ImageFile']['tmp_name']);
                        break;			
                case 'image/jpeg':
                case 'image/pjpeg':
                        $CreatedImage = imagecreatefromjpeg($_FILES['ImageFile']['tmp_name']);
                        break;
                default:
                        die('Unsupported File!'); //output error and exit
        }

        //PHP getimagesize() function returns height-width from image file stored in PHP tmp folder.
        //Let's get first two values from image, width and height. list assign values to $CurWidth,$CurHeight
        list($CurWidth,$CurHeight)=getimagesize($TempSrc);
        //Get file extension from Image name, this will be re-added after random name
        $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
        $ImageExt = str_replace('.','',$ImageExt);

        //remove extension from filename
        $ImageName 		= preg_replace("/\\.[^.\\s]{3,4}$/", "", $ImageName); 

        //Construct a new image name (with random number added) for our new image.
        $NewImageName = $_POST['fb_user_id'].'__'.$_POST['a_id'].'__'.$ImageName.'.'.$ImageExt;
        //set the Destination Image
        $thumb_DestRandImageName 	= $DestinationDirectory.$ThumbDir.$NewImageName; //Thumb name
        $DestRandImageName 			= $DestinationDirectory.$NewImageName; //Name for Big Image

        //Resize image to our Specified Size by calling resizeImage function.
        if($this->resizeImage($CurWidth,$CurHeight,$BigImageMaxSize,$DestRandImageName,$CreatedImage,$Quality,$ImageType))
        {
                //Create a square Thumbnail right after, this time we are using cropImage() function
                if(!$this->cropImage($CurWidth,$CurHeight,$ThumbSquareSize,$thumb_DestRandImageName,$CreatedImage,$Quality,$ImageType))
                        {
                                echo 'Error Creating thumbnail!';
                        }
                /*
                At this point we have succesfully resized and created thumbnail image
                We can render image to user's browser or store information in the database
                For demo, we are going to output results on browser.
                */                
                
               
				echo '<img width="107" height="107" src="'.base_url().APPPATH.'files/profile_images/thumbs/'.$ThumbPrefix.$NewImageName.'" title="Picture '.$_POST['a_id'].'">';
                

                /*Db queries should be here ****/
				//check whether image exists
				$sqlChkImageExists = "SELECT * FROM rc_profile_picture WHERE fb_user_id = ".$_POST['fb_user_id']." AND img_tag_id  = ".$_POST['a_id'];
				$pdo = Doctrine_Manager::connection()->getDbh(); 
				$resultChkImageExists = $pdo->query($sqlChkImageExists)->fetchAll();
					
								
				if(count($resultChkImageExists)==0){
						$cpp = new RcProfilePicture;
						$cpp->fb_user_id = $_POST['fb_user_id'];		//$_POST['fb_user_id'] contains the logged in user's unique facebook id
						$cpp->picture = $NewImageName;
						$cpp->img_tag_id = $_POST['a_id'];
						
						//save the records to fb_suggestion_list table
						$cpp->save(); 
				}else{
						$sqlUpdImage = "UPDATE  rc_profile_picture SET 
						picture = '".$NewImageName."'
						WHERE fb_user_id = ".$_POST['fb_user_id']."
						AND img_tag_id = ".$_POST['a_id'];

						$resultUpdImage = $pdo->query($sqlUpdImage);

						if(!$resultUpdImage){
							die('DB Error!'); //output error	
						}	
				}


        }else{
                die('Resize Error!'); //output error
        }
}



// This function will proportionally resize image 
function resizeImage($CurWidth,$CurHeight,$MaxSize,$DestFolder,$SrcImage,$Quality,$ImageType)
{
	//Check Image size is not 0
	if($CurWidth <= 0 || $CurHeight <= 0) 
	{
		return false;
	}
	
	//Construct a proportional size of new image
	$ImageScale      	= min($MaxSize/$CurWidth, $MaxSize/$CurHeight); 
	$NewWidth  			= ceil($ImageScale*$CurWidth);
	$NewHeight 			= ceil($ImageScale*$CurHeight);
	$NewCanves 			= imagecreatetruecolor($NewWidth, $NewHeight);
	
	// Resize Image
	if(imagecopyresampled($NewCanves, $SrcImage,0, 0, 0, 0, $NewWidth, $NewHeight, $CurWidth, $CurHeight))
	{
		switch(strtolower($ImageType))
		{
			case 'image/png':
				imagepng($NewCanves,$DestFolder);
				break;
			case 'image/gif':
				imagegif($NewCanves,$DestFolder);
				break;			
			case 'image/jpeg':
			case 'image/pjpeg':
				imagejpeg($NewCanves,$DestFolder,$Quality);
				break;
			default:
				return false;
		}
	//Destroy image, frees memory	
	if(is_resource($NewCanves)) {imagedestroy($NewCanves);} 
	return true;
	}

}

//This function corps image to create exact square images, no matter what its original size!
function cropImage($CurWidth,$CurHeight,$iSize,$DestFolder,$SrcImage,$Quality,$ImageType)
{	 
	//Check Image size is not 0
	if($CurWidth <= 0 || $CurHeight <= 0) 
	{
		return false;
	}
	
	
	if($CurWidth>$CurHeight)
	{
		$y_offset = 0;
		$x_offset = ($CurWidth - $CurHeight) / 2;
		$square_size 	= $CurWidth - ($x_offset * 2);
	}else{
		$x_offset = 0;
		$y_offset = ($CurHeight - $CurWidth) / 2;
		$square_size = $CurHeight - ($y_offset * 2);
	}
	
	$NewCanves 	= imagecreatetruecolor($iSize, $iSize);	
	if(imagecopyresampled($NewCanves, $SrcImage,0, 0, $x_offset, $y_offset, $iSize, $iSize, $square_size, $square_size))
	{
		switch(strtolower($ImageType))
		{
			case 'image/png':
				imagepng($NewCanves,$DestFolder);
				break;
			case 'image/gif':
				imagegif($NewCanves,$DestFolder);
				break;			
			case 'image/jpeg':
			case 'image/pjpeg':
				imagejpeg($NewCanves,$DestFolder,$Quality);
				break;
			default:
				return false;
		}
	//Destroy image, frees memory	
	if(is_resource($NewCanves)) {imagedestroy($NewCanves);} 
	return true;

	}
	  
}

function uploadBiodata(){

		//echo '<pre>';
		//print_r($_FILES);
		//exit;

		// check $_FILES['ImageFile'] array is not empty
        // "is_uploaded_file" Tells whether the file was uploaded via HTTP POST
        if(!isset($_FILES['bioData']) || !is_uploaded_file($_FILES['bioData']['tmp_name']))
        {
                        die('Upload Error!'); // output error when above checks fail.
        }

		 // Elements (values) of $_FILES['ImageFile'] array
        //let's access these values by using their index position
        $FileName 		= str_replace(' ','-',strtolower($_FILES['bioData']['name'])); 
        $FileSize 		= $_FILES['bioData']['size']; // Obtain original file size
        $TempSrc	 	= $_FILES['bioData']['tmp_name']; // Tmp name of the file stored in PHP tmp folder
        $FileType	 	= $_FILES['bioData']['type']; //Obtain file type, returns "image/png", image/jpeg, text/plain etc.
		

		//Let's use $ImageType variable to check wheather uploaded file is supported.
        //We use PHP SWITCH statement to check valid image format, PHP SWITCH is similar to IF/ELSE statements 
        //suitable if we want to compare the a variable with many different values
        switch(strtolower($FileType))
        {
                case 'application/octet-stream':                        
                        break;
				case 'application/pdf':                        
                        break;  
				case 'application/vnd.ms-word':                        
                        break;  
                case 'application/vnd.ms-excel':                        
                        break;                
                case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':                        
                        break;
				case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':                        
                        break;
                default:
                        die('Unsupported File!'); //output error and exit
        }

		
		//Get file extension from Image name, this will be re-added after random name
        $FileExt = substr($FileName, strrpos($FileName, '.'));
        $FileExt = str_replace('.','',$FileExt);

        //remove extension from filename
        $FileName 		= preg_replace("/\\.[^.\\s]{3,4}$/", "", $FileName); 

        //Construct a new image name (with random number added) for our new image.
        $NewFileName = $_POST['fb_user_id'].'__'.$FileName.'.'.$FileExt;	

		if(move_uploaded_file($_FILES["bioData"]["tmp_name"], $_SERVER['DOCUMENT_ROOT']."/rishtey-connect/application/files/candidate_biodata/".$NewFileName )){		
				/*Db queries should be here ****/
				//check whether file exists
				$sqlChkFileExists = "SELECT * FROM rc_profiles WHERE fb_user_id = ".$_POST['fb_user_id'];
				$pdo = Doctrine_Manager::connection()->getDbh(); 
				$resultChkFileExists = $pdo->query($sqlChkFileExists)->fetchAll();
					
				if(count($resultChkFileExists)>0){	
						$cpp = Doctrine::getTable('RcProfiles')->findOneByFb_user_id($_POST['fb_user_id']);
						$cpp->fb_user_id = $_POST['fb_user_id'];		//$_POST['fb_user_id'] contains the logged in user's unique facebook id
						$cpp->biodata = $NewFileName;						
						
						//save the records to fb_suggestion_list table
						$cpp->save(); 
						
						echo '<div id="biodataDiv"><a href="'.base_url()."application/files/candidate_biodata/".$NewFileName.'">'.$NewFileName.'</a>&nbsp;<a href="javascript:void(0);" class="del_biodata" id="'.$_POST['fb_user_id'].'"><img alt="Delete" src="'.base_url().'/images/close-ico.png"></a></div>';
						
				}else{
						//do nothing
				}
		
				die( "<div id='delBiodataDiv' style='color: green;'>BioData uploaded successfully</div>");
		
		}else{
				die("Upload Error!");
		}

}

}