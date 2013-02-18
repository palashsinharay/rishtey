<!--custom scripts required for facebook integration-->
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=118878424929011";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
</script>

<div class="page">
<div class="wrapper-widget min-height480">
<div id="user_auth_form" class="network-wrapper">
<div style="width:350px; margin:0 auto;">
	<h6 align="center">Your login credentials updated successfully</h6>
	
	<p style="padding:0 0 0 80px;"><?php echo anchor('facebooker', 'Go to login page'); ?></p>
	
</div>
</div>
</div>
</div>