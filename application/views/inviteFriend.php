<!--load all js and css libraries-->
<link href="<?php echo base_url();?>css/style.css" rel="stylesheet" type="text/css" />
<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,400,600,700' rel='stylesheet' type='text/css' />
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
<script src="<?php echo base_url();?>js/jquery.min.js" type="text/javascript"></script>

<!-- Plugin For jQuery Select Box Start Here -->
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.selectBox.js"></script>
<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>css/jquery.selectBox.css" />
<!-- Plugin For jQuery Select Box End Here -->

<script src="<?php //echo base_url();?>js/common.js" type="text/javascript"></script>
<script src="http://connect.facebook.net/en_US/all.js"></script>
<script src="<?php  echo base_url();?>js/jquery.form.js"></script>
<script src="<?php  echo base_url();?>js/aj_file_upload.js"></script>
	
<!--custom scripts for sending message through fb dialog-->

<script>
    jQuery(document).ready(function() {
		        
        $('.invite_friends').live("click", function(){

			//show the loader image
			$('#sendFbMsgAlert').html('<img style="border:0px solid;" src="<?php echo base_url();?>images/ajax-loader.gif"/>');

            var fbUserName = $(this).attr('id');			
            var frFbUserId = $(this).attr('rel');
            fb_dialogue_for_invite_friends(fbUserName, frFbUserId);
        });        
    });    
    
    function fb_dialogue_for_invite_friends(fbUserName, frFbUserId)
    {
		     
        //var url = '<?php echo base_url(); ?>facebooker';
        // assume we are already logged in
        FB.init({appId: '<?php echo $fbAppId; ?>', xfbml: true, cookie: true});
        
        FB.ui({
            to: fbUserName,
            method: 'send',
            name: 'Test',
            //link: 'http://ec2-50-19-66-142.compute-1.amazonaws.com/rishtey-connect'
            //link: url
            link: '<?php echo base_url(); ?>'
        },requestCallback_for_invite_friend);  
        
    }
    function requestCallback_for_invite_friend(response) {
        //Handle callback here 
        //alert('response='+response);       
        
        if(response != null){
			$('#sendFbMsgAlert').html('Message sent successfully');
			//return false;
		}
    }    
	
</script>

<div id="console" style="display:none"></div>

<div class="page">
	<div class="content-widget">
    <div class="wrapper-widget">
	
      <!--<div class="rghtPan" style="width: 753px;" id="content_body">-->
      <div class="rghtPan" id="content_body">
					         
          <p id="sendFbMsgAlert" class="successful"></p>
          <p>Why Not Invite others to help you find a match within their networks too.</p>
          <p class="line">&nbsp;</p>
		  <p class="alert invite"></p>
          <div class="network-wrapper">
            <ul class="invite-friendz network-friendz">
              <?php $i=1; ?>
              <?php //print_r($this->session->userdata); ?>
              <?php foreach($allFbFriends as $row): ?>
                               
                <li class=" <?php if ($i%5==0) echo ' last'; ?>">
              		<img style="cursor:pointer; " rel="<?php echo $row['id'] ?>" height="107" width="107" src="https://graph.facebook.com/<?php echo $row['id'] ?>/picture?type=large" alt="<?php echo $row['first_name'] ?>"  title="<?php echo $row['first_name'] ?>" id="<?php echo $row['username'] ?>" class="rcimg delete invite_friends" />
              		<a rel="<?php echo $row['id'] ?>" id="<?php echo $row['username'] ?>" href="JavaScript:void(0);" title="Invite Friends" class="invite_friends">Invite <?php echo $row['first_name'] ?></a>
                    <input type="hidden" id="candidate" name="candidate" value=<?php echo $row['id']; ?> />
              	</li>
            <?php $i++ ; ?>
                
            <?php endforeach; ?>
                            
            </ul>
          </div>
           
		  <button onclick="history.back();" title="Done" type="button" class="btn-submit"><span><span>Back</span></span></button>	
	  		  	
          <p class="clearSM">&nbsp;</p>        
	  		  		
      </div>
    </div>
    <div class="bg-leaf"><img src="<?php echo base_url();?>images/leaf-bg-no-repeat.png" alt=""></div>
    </div>
    </div>
	