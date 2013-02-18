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
	<div class="content-widget">
		<div class="wrapper-widget">
			
			<?php if($this->session->userdata['user']) {?>
			<div class="lftPan">
				<div class="lft-block">
				  <?php echo $leftPanelCount; ?>
				</div>  
				
			</div>
			<?php }?>
			
			<div class="rghtPan">
				
				<?php echo $text; ?>
				
			</div>
		</div>
		<div class="bg-leaf"><img src="<?php echo base_url();?>images/leaf-bg-no-repeat.png" alt=""></div>
	</div>
</div>
