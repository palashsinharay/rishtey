<!--custom scripts required for facebook integration-->
<!--<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=118878424929011";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
</script>-->

<div class="page">
<div class="wrapper-widget min-height480">
			
			<?php echo form_open('facebooker/save'); ?>
				<div id="user_auth_form" class="network-wrapper">
					<div style="width:350px; margin:0 auto;">
						<h6>PLEASE CREATE YOUR LOGIN ID AND PASSWORD</h6>
								
						<?php //echo validation_errors(); ?>
					
						<div class="clear overflow">
							<div class="from-blk-email">					  
							  <div class="row">
								<span style="float:left; line-height:30px; width:108px">
									Email:
								</span>
								<label>
								  <input tabindex="1" placeholder="Email*" type="text" id="email" name="email" value="<?php echo $email; ?>" style="width:200px;"   maxlength="100">						  
								</label>
								<?php echo form_error('email'); ?>
							  </div>
							</div>
							
							<div class="spacer"></div>
							<div class="from-blk-phone">					  
							  <div class="row">
								<span style="float:left; line-height:30px; width:108px">
									Phone:
								</span>
								<label>
								  <input tabindex="1" placeholder="Phone*" type="text" id="phone" name="phone" value="<?php echo $phone; ?>" style="width:200px;"   maxlength="100">						  
								</label>
								<?php echo form_error('phone'); ?>
							  </div>
							</div>
							
							
							<div class="spacer"></div>
							<div class="from-blk-password">
							  <div class="row">
								<span style="float:left; line-height:30px; width:108px">
									Password:
								</span>
								<label>
								  <input tabindex="2" placeholder="Password*" type="password" id="password" name="password" value="" style="width:200px" maxlength="100">
								</label>
								<?php echo form_error('password'); ?>
							  </div>
							</div>
							<div class="spacer"></div>
							<div class="from-blk-cpassword">
							  <div class="row">
								<span style="float:left; line-height:30px; width:108px">
									Confirm Password:
								</span>
								<label>
								  <input tabindex="2" placeholder="Confirm Password*" type="password" id="cpassword" name="cpassword" value="" style="width:200px"  maxlength="100">						  
								</label>
								<?php echo form_error('cpassword'); ?>
							  </div>
							</div>
							<div class="spacer"></div>
							<input type="hidden" value="<?php echo $fbId; ?>" name="fbId">
							<input type="hidden" value="<?php echo $fbUserId; ?>" name="fbUserId">
							<input type="hidden" value="<?php echo $this->uri->uri_string(); ?>" name="redirect">
					
							<button class="btn-done" type="submit" id="account_save" name="account_save" title="Save" value="s">
								<span><span>Save</span></span>
							</button>
					
							<button class="btn-done" type="submit" id="account_cancel" name="account_save" title="Cancel" value="c">
								<span><span>Cancel</span></span>
							</button>
					
						</div>
						<div class="row">
							<div class="spacer"></div>
						</div>
					</div>
				</div>
			</form>
			
</div>
</div>
