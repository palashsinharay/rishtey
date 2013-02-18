<!--load the jquery library-->
<script src="<?php echo base_url();?>js/jquery-1.9.1.js"></script>

<!--custom scripts-->
<script type="text/javascript">
	jQuery(document).ready(function() {	
		
		//prepare the autosuggest array for Caste
		var casts = new Array();
		<?php 
				$sqlGetCaste = "SELECT caste FROM rc_caste_master";  
				$pdo = Doctrine_Manager::connection()->getDbh();       
				$resultGetCaste = $pdo->query($sqlGetCaste)->fetchAll();
		?>
		<?php  foreach($resultGetCaste as $key => $option):?>
				casts[<?php echo $key;?>] = '<?php echo $option['caste'];?>';
		<?php endforeach;?>
		
		$("#caste").autocomplete({
			source: casts
		});
		
		$("#mcaste").autocomplete({
			source: casts
		});
		
		//prepare the autosuggest array for Highest Education
		var edu = new Array();
		<?php  foreach($this->config->item('hEducation') as $key => $option):?>
				edu[<?php echo $key;?>] = '<?php echo $option;?>';
		<?php endforeach;?>
		
		$("#hEducation").autocomplete({
			source: edu
		});
		
		$("#mEducation").autocomplete({
			source: edu
		});
		
		//prepare the autosuggest array for Religion
		var rel = new Array();
		
		<?php  foreach($this->config->item('religion') as $key => $option):?>
				 rel[<?php echo $key;?>] = '<?php echo $option;?>';
		<?php endforeach;?>
		
		$("#religion").autocomplete({
			source: rel
		});
		
		//prepare the autosuggest array for Mother Tongue
		var lang = new Array();
		
		<?php  foreach($this->config->item('mTongue') as $key => $option):?>
							lang[<?php echo $key;?>] = '<?php echo $option;?>';
		<?php endforeach;?>
		
		$("#mTongue").autocomplete({
			source: lang
		});
			
			//initiate the progress bar on the left panel
			$("#dr_friends").progressbar({ value: <?php echo $drFrndCount; ?> })
							.children('.ui-progressbar-value')
							.html(<?php echo $drFrndCount; ?>)
							.css("display", "block");
			
			$("#indr_friends").progressbar({ value: <?php echo $inDrFrndCount; ?> })
							  .children('.ui-progressbar-value')
							  .html(<?php echo $inDrFrndCount; ?>)
							  .css("display", "block");;
                         /**
                          * function to make cuserId select box last option selected and the fire getpsetting(GO button) to load the selected candidate data
                          */                                 
                        $("#my_dashboard").click(function(){
                        var value = $("#cuserId option:last").val();
                        //alert(value);
                        $('#cuserId').selectBox('value',value);
                        $('#getpsetting').click();
                        
                        });
			
			
	});
	
</script>

<!--overwrite css for the progress bar on the left panel-->
<style type='text/css'>
    .clear-frnds .ui-widget-header {
        background: url("<?php echo base_url();?>images/status-bg.jpg") repeat-y scroll 0 0 transparent;
		border: 1px solid #579E9D !important;
		border-radius: 6px 6px 6px 6px !important;
		box-shadow: 1px 1px #276867 !important;
		clear: both !important;
		color: #F4F5F1 !important;
		display: block !important;
		float: left !important;
		font: bold 11px/15px Tahoma,Arial,Helvetica,sans-serif !important;
		margin: 0 !important;
		outline: medium none !important;
		padding: 0 !important;
		text-align: right !important; 
    }
</style>
	
		<h3>Network Status</h3>
		<p>Your network is very small as of now for direct &amp; indirect friends</p>
		<div class="friends-meter">
			<div class="clear-frnds">
				<div class="label">Direct Friends</div>
				<div id="dr_friends" class="meter-wrapper"></div>
			</div>
			<div class="clear-frnds">
				<div class="label">Indirect Friends</div>
				<div id="indr_friends" class="meter-wrapper"></div>
			</div>
			<a class="greenText" href="<?php echo base_url() ?>inviteFriends/getFriendFromFile" title="Invite Friends">Invite Friends</a>
		</div>
		<ul>
			<li>Potential Brides<span><?php echo $potentialBridesCnt; ?></span></li>
			<li>Potential Grooms<span><?php echo $potentialGroomsCnt; ?></span></li>
			<li>Candidates<span><?php echo $ownCandidatesCnt; ?></span></li>
		</ul>
                
        <div id="currentCandidate" style="display:none">
            <ul id="selectedCandidate" >
            </ul>
        </div>
		<a class="greenText" id="create_candidate" href="<?php echo base_url();?>candidate/addcandidate" title="Create Candidate">Create Candidate</a>
                
				<?php if(strstr($_SERVER['REQUEST_URI'], 'dashboard')) {?>
				<a class="greenText" id="my_dashboard" href="#" title="My Dashboard">My Dashboard</a>
				<?php }?>