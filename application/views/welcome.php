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
<div class="welcomePadd">
    <div class="container">
        <p>
            <b>Thank you for joining Rishtey Connect.</b>      
        </p>
        <p>
            We have just launched the website and are gradually accepting users so that we can serve them best.
        </p>
        <p>
            We will send you an email as soon as we are ready for you.
        </p>
        <p style="padding-top:10px; ">
            Want to get faster access?
        </p>
            <p>
            <div id="fb-root"></div>
            <div class="fb-like" data-send="false" data-width="450" data-show-faces="true"></div>
            </p>
            <p>
                or, even better:
            </p>
            <p>
                <a href="<?php echo base_url() ?>inviteFriends/getFriendFromFile">Invite your Facebook freinds to join Rishtey Connect.</a>       
            </p>   
    </div>
</div>

<div class="rightwelcomePadd" style="padding-top:20px;">
    <!--Sidebar content-->
    <p><strong>About Rishtey Connect</strong></p>
    <p>Rishtey Connect is the easiest way to help your loved ones find great life partners within your own social network. Wouldn't you like to see if your friends or friends of friends know someone who will be a perfect match for yourself or someone close to you? We search through your networks and bring out the best matches.</p>               
   
</div>

</div>
</div>
