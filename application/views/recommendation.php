<!-- Plugin For jQuery Select Box Start Here -->
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.selectBox.js"></script>
<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>css/jquery.selectBox.css" />
<!-- Plugin For jQuery Select Box End Here -->

<script src="<?php echo base_url();?>js/common.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function(){	
			
 			//ajax call for the dropdown array for recommendation relation on Candidate Profile
        	$.ajax({
            type: "POST",
            url: "<?php echo base_url();?>functions/recommrelationGet",            
            success: function(data) {
						if(data)
						{   
							var relation = JSON.parse(data);
							$("#relation").selectBox('options',relation);
							
						}		
						
						}
        	});
        	
        	$('#recommendation_save').click(function(){
        		
        		//ajax call for adding recommendation
				if($('#sRecommendation').val() !='')
				{
					$('.alert.recommendation').html('<img style="border:0px solid;" src="<?php echo base_url();?>images/ajax-loader.gif"/>')
					
					$.ajax({
					type: "POST",
					data: {recommenderFbId:$('#recommenderFbId').val(),candidateFbId:$('#candidateFbId').val(),relationship:$('#relation').val(),recommendation:$('#sRecommendation').val(),type:'R'},
					url:"<?php echo base_url();?>functions/addRecommendation",
					success: function(data){
												$('#msg').html('Recommendation message saved successfully');
                                                $('.alert.recommendation').html('<img style="border:0px solid;" src="<?php echo base_url();?>images/ajax-loader.gif"/>')
                                                setTimeout("location.href='<?php echo base_url();?>facebooker'", 4000);
					}
					});
				}else{
					alert('Please enter recommendation text'); 
					return false;
				}
        	});        	       	
  	
}); 	
</script>
  <div class="page">
	<div class="wrapper-widget min-height480">
				<div id="user_auth_form" class="network-wrapper">
					<h6 style="margin: 0 0 6px;">Recommend <?php echo $fname;?> </h6>
						<div><p>Rishtey Connect helps people find reliable life partners for themselves or their loved ones within people known to their friends and family.
If you know <?php echo $fname;?> well and think that <?php echo ($gender==1) ? 'he' : 'she'; ?> will make a good <?php echo ($gender==1) ? 'husband' : 'wife'; ?>, please recommend <?php echo ($gender==1) ? 'him' : 'her'; ?> to your network.
Your recommendation will give people a better understanding of who <?php echo $fname;?> is and help <?php echo ($gender==1) ? 'him' : 'her'; ?> find a match within your network.</p></div>
					<div>
						<div class="clear overflow">
							<div class="from-blk-email">					  
							  <h6 style="margin: 12px 0 6px;">RECOMMENDATION</h6>
							  <div class="spacer"><</div>
							  <input type="hidden" id="candidateFbId" name="profileFbId" value="<?php echo $candidateFbId; ?>">
							  <input type="hidden" id="recommenderFbId" name="profileFbId" value="<?php echo $recommenderFbId; ?>">
							  <div class="row">
								  <textarea placeholder="Add short recommendation" tabindex="10" id="sRecommendation" name="sRecommendation" cols="60" rows="5" style="width:535px;"></textarea>
							  </div>
							</div>
							<div class="spacer"></div>
							
							  <h6 style="margin: 12px 0 6px;">RELATION</h6>
							  <div class="spacer"></div>
							  <div class="row">
								<div class="row md-select">
				                      <div class="row">
				                      <select id="relation" name="relation" class="custom-class2">
				                      </select>
				                      </div>
				                 </div>
								
							  </div>
								
							<div class="spacer"></div>												
							
							<button class="btn-done" type="submit" id="recommendation_save" name="recommendation_save" title="Save" style="margin:4px 0 0 -4px;">
								<span><span>Save</span></span>
							</button>                                               
							
						</div>
						<div class="row">
							<div class="spacer"></div>
						</div>
                        <span id="msg" class="success"></span>&nbsp<span class ="alert recommendation"></span>
					</div>
				</div>
			</form>

</div>
</div>
  