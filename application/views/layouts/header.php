<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Rishtey Connect :: Home</title>
<!--load the required css files-->
<link href="<?php echo base_url();?>css/style.css" rel="stylesheet" type="text/css" />
<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,200,600,700,900,200italic,300italic,400italic,600italic,700italic,900italic' rel='stylesheet' type='text/css' />
<!--load the favicon-->
<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url();?>favicon.ico" />
<!--<script type="text/javascript" src="<?php //echo base_url();?>js/jquery/1.8.2/jquery.min.js"></script>-->
<script src="<?php echo base_url();?>js/jquery-1.8.2.js" type="text/javascript"></script>

<!-- Plugin For jQuery Select Box Start Here -->
<script type="text/javascript" src="<?php echo base_url();?>js/jquery/jquery.selectBox.js"></script>
<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>css/jquery.selectBox.css" />
<!-- Plugin For jQuery Select Box End Here -->

<!-- Plugin For jQuery Calender Start Here -->
<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>js/jquery/ui/1.9.1/jquery-ui.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/jquery/ui/1.9.1/jquery-ui.js"></script>
<!-- Plugin For jQuery Calender End Here -->

<!-- Plugin For jQuery Slider Control Start Here -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/slideControl.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/jquery/jquery.slideControl.js"></script>
<!-- Plugin For jQuery Slider Control End Here -->

<script src="<?php echo base_url();?>js/common.js" type="text/javascript"></script>

<!--load the required js libraries-->

<script src="<?php echo base_url();?>js/jquery-ui.js" type="text/javascript"></script>
<!--custom scripts-->
<script type="text/javascript">
    //event that displays different login landing pages based on type	
	jQuery(document).ready(function($) {    
		 $('.btn-private').live("click", function(){	
			//show the loader div containing loader image
			//$('#loader_div').show();
			var request = $.ajax({
				url: "<?php echo base_url();?>facebooker/showlanding",
				type: "POST",
				data: {type : 'p', loginUrl : $('#loginUrl').val()}            
			});
		
			request.done(function(msg) {
				//hide the loader div containing loader image
				//$('#loader_div').hide();
				$('.page').html(msg); 				
			});
		
		 });
		
		  $('.btn-effective').live("click", function(){	
            //show the loader div containing loader image 
			//$('#loader_div').show();
			var request = $.ajax({
				url: "<?php echo base_url();?>facebooker/showlanding",
				type: "POST",
				data: {type : 'e', loginUrl : $('#loginUrl').val()}            
			});
		
			request.done(function(msg) {
				//hide the loader div containing loader image
				//$('#loader_div').hide();
				$('.page').html(msg); 				
			});
	
		 });
		
		 $('.btn-simple').live("click", function(){	
			//show the loader div containing loader image
			//$('#loader_div').show();
			var request = $.ajax({
				url: "<?php echo base_url();?>facebooker/showlanding",
				type: "POST",
				data: {type : 's', loginUrl : $('#loginUrl').val()}            
			});
		
			request.done(function(msg) {
				//hide the loader div containing loader image
				//$('#loader_div').hide();
				$('.page').html(msg); 				
			});
		
		 });
		
	});	
</script>
</head>
<body>

	<?php
		//get access_token from logout url
		//$accessToken = explode('access_token=',$this->session->userdata['logoutUrl']);
	
	?>

	<div class="header">
	<!--display the login buttons based on whether user logged in or not-->
	<?php if($this->session->userdata['loggedIn']==0): ?> 
	  <div class="widget-index">
		<h1 class="logo"><a href="<?php echo base_url(); ?>" title="Rishtey Connect ~ HOME">Rishtey Connect</a></h1>
		<div class="user-login">
			
			<!--<span id="loader_div" style="display:none; "><img src="<?php //echo base_url();?>images/ajax-loader-home.gif"/></span>-->
			
			<a class="btn-signin" href="<?php echo ($loginUrl=='') ? '/' : $loginUrl ;?>" title="Sign IN"><span>Sign IN</span><img src="<?php echo base_url();?>images/fb-ico.gif" alt="" /></a> <a class="btn-signup" href="<?php echo ($loginUrl=='') ? '/' : $loginUrl ;?>" title="Sign UP"><span>Sign UP</span><img src="<?php echo base_url();?>images/fb-ico.gif" alt="" /></a>	
			<small>We wont post anything automatically on Facebook</small> </div>
	  </div>
	<?php else: ?>
			
			<?php
				//get access_token from logout url
				$accessToken = explode('access_token=',$this->session->userdata['logoutUrl']);
				//echo $accessToken[1];
				
				$this->session->userdata['logoutUrl'] = "https://www.facebook.com/logout.php?next=http://development.rishteyconnect.com/facebooker/logout&access_token=".$accessToken[1];
				
			?>
			
			<div class="wrapper-widget">
				<h1 class="logo"><a href="<?php echo base_url(); ?>" title="Rishtey Connect ~ HOME">Rishtey Connect</a></h1>
				<div class="user-info">
					<img src="<?php echo $this->session->userdata['userimage']; ?>" title="<?php echo $this->session->userdata['fullname']; ?>" />
					<div class="msg">
						<div class="clear">
							<span class="welcome-msg">Welcome <?php echo $this->session->userdata['fullname']; ?>!</span>
						</div>
						<input type="hidden" id="userId" value="<?php echo $this->session->userdata['user'];?>">
						<a href="<?php echo $this->session->userdata['logoutUrl']; ?>" title="Logout">Logout</a>
					</div>
					<p class="alert-header invite"></p>
				</div>
			</div>
	
	<?php endif ?>
	</div>

	<div class="wrapper">