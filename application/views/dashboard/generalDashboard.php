<script>
function goBack()
  {
  window.location.href = "<?php echo base_url();?>dashboard";
  }
</script>
<div class="page">
	<div class="content-widget">
		<div class="wrapper-widget">
			
			<div class="lftPan">
				<div class="lft-block">
				  <?php echo $leftPanelCount; ?>
				</div>  
				<!--<div id="help-frnd-lft-block" class="lft-block">  -->
				  <?php echo $helpFriendData; ?>
				<!--</div>-->
			</div>
			
			<div class="rghtPan">
				<h4>Welcome to Rishtey Connect.(<a class="lightGreen" href="<?php echo base_url();?>dashboard">choose a candidate</a>)</h4>
				
				
				<p class="line">&nbsp;</p>
				
				<div class="container-inr">
					<div class="network-wrapper width93">
						<ul style="list-style:none">
                                                    <li><span>Debmalya</span> has shown interest on <span>Shinjana</span> for Debmalya</li><li><span>Debmalya</span> has shown interest on <span>Shinjana</span> for Debmalya</li><li><span>Debmalya</span> has shown interest on <span>Shinjana</span> for Debmalya</li>
                                                    <li><span>Debmalya</span> has blocked <span>Sanchita</span> for <span>Debmalya</span></li><li><span>Debmalya</span> has blocked <span>Sanchita</span> for <span>Debmalya</span></li><li><span>Debmalya</span> has blocked <span>Sanchita</span> for <span>Debmalya</span></li>
                                                    <li>New match <span>N Das</span></li><li>New match <span>P Singh</span></li><li>New match <span>S Das</span></li>
                                                </ul>
					</div>
					<button title="Back" type="submit" class="btn-submit mrgn-l15" id="back" onClick="goBack()"><span><span>Back</span></span></button>
					
						
				</div>
				<p class="clearSM">&nbsp;</p>
			</div>
			
		</div>
		<div class="bg-leaf"><img src="<?php echo base_url();?>images/leaf-bg-no-repeat.png" alt="" /></div>
	</div>
</div>
