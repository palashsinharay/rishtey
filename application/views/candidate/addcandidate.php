<!-- Plugin For jQuery Select Box Start Here -->
<!--<script type="text/javascript" src="<?php //echo base_url();?>js/jquery.selectBox.js"></script>
<link type="text/css" rel="stylesheet" href="<?php //echo base_url();?>css/jquery.selectBox.css" />-->
<!-- Plugin For jQuery Select Box End Here -->

<!--<script src="<?php //echo base_url();?>js/common.js" type="text/javascript"></script>-->

<script type="text/javascript">
	jQuery(document).ready(function($) {
		//use either "Myself" checkbox or the autosuggest textbox to create candidate profile on the Add candidate page, not both
		$('#addCandidate #candidateMe').click(function(){                 
			
			var isChecked = $('#addCandidate #candidateMe').attr('checked') ? true : false;	   
			
			if(isChecked){ 
				$('#candidateName').attr('disabled',true);
				
				//if the "Me" selector in the guardian panel is checked, reset it
				$('#guardian_selector #candidateMe').val($('#candidateMe').val());
				
				//make mySelf input hidden field value 1 for next page relation dropdown field
				$('#mySelf').val('1');
				
			}else{
				$('#candidateName').attr('disabled',false);
				$('#mySelf').val('0'); 
			}            
		});	
			
		$('#candidateName').keyup(function(){             
			var str = $('#candidateName').val();
			if(str.length>0) $('#candidateMe').attr('disabled',true);             
			else $('#candidateMe').attr('disabled',false);              
		});
		
		// conditionally show/hide the recommendation area and relation box and "Other Guardian" autosuggest box in the Guardian Panel	
		$('#guardian_selector #other_guardian').click(function(){
			$('#sfr_g').show(); 	
				
			$("#guardian_selector #candidate_rec_msg").hide();		
			$("#guardian_selector #candidate_relation").hide();
			$("#guardian_selector textarea#candidate_rec_msg").val('A Short Recommendation');
			$("button#Candidate_submit span span").text("Send Message");
			
		});	
		
		$('#guardian_selector #candidate').click(function(){          
			$('#sfr_g').hide();		
			
			$("#guardian_selector #candidate1").val('');
			$('#guardian_selector #other_fb_friend_box').val('A Short Recommendation');
			$("#guardian_selector #other_fb_friend_rec_msg").hide();
			$("#guardian_selector #candidate_rec_msg").show();
			$("#guardian_selector #candidate_relation").show();
			$("#guardian_selector #other_fb_friend_relation").hide();
			$("button#Candidate_submit span span").text("Send Message");	
			
		});	
				
		$('#guardian_selector #candidateMe').click(function(){     
			$('#sfr_g').hide();
			
			$('#guardian_selector textarea#candidate_rec_msg').val('A Short Recommendation');
			$('#guardian_selector #candidate_rec_msg').hide();
			
			$('#guardian_selector #candidate1').val('');
			$('#guardian_selector #other_fb_friend_box').val('A Short Recommendation');
			$('#guardian_selector #other_fb_friend_rec_msg').hide();
				
			$('#guardian_selector #candidate_relation').hide();
			$('#guardian_selector #other_fb_friend_relation').hide();
			$("button#Candidate_submit span span").text("Create Candidate");
			
		});	
			
		//conditionally show/hide the recommendation area and relation box if profile exists
		$('span#cn_err #rec_candidate').live("click",function(){
			$('#sfr_g').show(); 	
				
			$("span#cn_err #candidate_rec_msg").show();		
			$("span#cn_err #candidate_relation").show();
			
			$("button#Candidate_submit span span").text("Recommend");
			
		});	
		
		$('span#cn_err #mail_guardian').live("click",function(){
			$('#sfr_g').show(); 	
				
			$("span#cn_err #candidate_rec_msg").hide();		
			$("span#cn_err #candidate_relation").hide();
			
			$("button#Candidate_submit span span").text("Send Mail");
			
		});	
		
	});	
		
    //conditionally show/hide the "Other Guardian" section in the Guardian Panel
	$('#guardian_selector #candidate1').live("blur", function(){
		if($('#guardian_selector #candidate1').val()!=''){
						var candidateNameList = new Array();
						<?php  $candidate = $suggestedfriends; foreach ($candidate['records'] as $key => $value): ?>   	
							candidateNameList[<?php echo $key; ?>] = '<?php echo addslashes($value["name"]); ?>';
						<?php  endforeach;?>
						
						//check if name typed in the autosuggest box is a valid name
						if($.inArray($("#guardian_selector #candidate1").val(), candidateNameList)!= -1){
							$("#guardian_selector #other_fb_friend_rec_msg").show();
							$("#guardian_selector #other_fb_friend_relation").show();							
						
						}else{
							$("#guardian_selector #other_fb_friend_rec_msg").hide();
							$("#guardian_selector #other_fb_friend_relation").hide();
						}
		}else{
			$("#guardian_selector #other_fb_friend_rec_msg").hide();
			$("#guardian_selector #other_fb_friend_relation").hide();
		}
		
	});
		
	//prevent users from navigating to previous page
	history.go(1);
		
</script>

<div class="page">
	<div class="content-widget">
		<div class="wrapper-widget">
			
			<div class="lftPan">
				<div class="lft-block">
				  <?php echo $leftPanelCount; ?>
				</div>  
<!--		<div id="help-frnd-lft-block" class="lft-block">  -->
				  <?php echo $helpFriendData; ?>
<!--                        </div>		-->
                        </div>
			
			<div class="rghtPan">
				<h4>Most of the users are looking for a match for someone special.</h4>
				<h5>Who do you want to find a match for ?</h5>
				
				<p class="line">&nbsp;</p>
				<p class="alert_guardian"></p>
				<div class="create-candidate">
					<div class="network-wrapper width93">
						<form class="form-inline" id="addCandidate" action="<?php echo base_url();?>candidate/candidateprofile" method="post">						
							<div class="candidate-row">
								<input type="checkbox" id="candidateMe" name="candidateMe"  value="<?php echo $this->session->userdata['username'];?>">
								<input type="hidden" id="mySelf" name="mySelf" value="0">
								<div class="label-value">Myself</div>
							</div>
							
							<div class="candidate-row">
								<label>
									<input type="text" style="width:260px;" placeholder="Start typing FB friends name" id="candidateName" name="candidate">
								</label>
								<div class="clear"></div>
								<span id ="cn_err"> </span>
								<input type="hidden" name="frCandidateName" id="frCandidateName" value="" />
								<!--<input type="hidden" name="frCandidateId" id="frCandidateId" value="" />-->
							</div>
							
							<!--Guardian div starts-->
							<div id="guardian_selector" class="srch-content" style="display:none; ">
								<div class="srch-curvBT">
									<div class="srch-curvTP"><h5>Select Guardian</h5>
										<div class="row-guardian">
											<p>						
												<input type="radio" id="candidateMe" name="candidateMe" value="" checked> Me
											</p>
										</div>
										
										<div class="row-guardian">
											<p>
												<input type="radio" name="candidateMe"  id="candidate" value="<?php echo $random_suggested_friend[0]['username'] ?>" /> <span id="selected_guardian"><?php echo $random_suggested_friend[0]['fname'].' '.$random_suggested_friend[0]['lname'] ?></span> (selected)
											</p>
										</div>											
												
												<!--------------recommendation area and relation box----------------------------->
												<div id="candidate_rec_msg" style="display:none; ">
													<p></p>
													<textarea class="textarea inputWidth" id="candidate_rec_msg">A Short Recommendation</textarea>
												</div>
												
												<div id="candidate_relation" style="display:none; margin-top:2px;">
													<select class="selectBox inputWidthDropdown" name="select_candidate_relation" id="select_candidate_relation">
														<?php  foreach($recommendationRelations as $key => $option):?>
																	<option value="<?php echo $option['id'];?>"><?php echo $option['relation'];?></option>
														<?php endforeach;?>
													</select>
												</div>
												<!------------------------------------------------------------------------------>											
												
										<div class="row-guardian">
											<p>
												<input type="radio" name="candidateMe"  id="other_guardian"  value="other_guardian" /> Other Guardian
											</p>							
												
											<p id="sfr_g" style="display: none;">
												<input class="input inputWidth" id="candidate1" name="candidate1" style="border:1px solid #D8D8D8; padding:4px;">
											</p>											
												
												<!--------------recommendation area and relation box for other friend----------------------------->
												<div id="other_fb_friend_rec_msg" style="display:none; ">
													<textarea class="textarea inputWidth" id="other_fb_friend_box">A Short Recommendation</textarea>
												</div>
													
												<div id="other_fb_friend_relation" style="display:none; margin-top:2px;">
													<select class="selectBox inputWidthDropdown" name="select_other_fb_friend_relation" id="select_other_fb_friend_relation">
														<?php  foreach($recommendationRelations as $key => $option):?>
																	<option value="<?php echo $key;?>"><?php echo $option['relation'];?></option>
														<?php endforeach;?>
													</select>
												</div>
												<!------------------------------------------------------------------------------------------->											
												
										</div>
									</div>
								</div>
							</div>						
						</form>
					</div>
					
					<div id="createCandidateDiv">
						<button name="Candidate_submit" id="Candidate_submit" class="btn-done" type="submit" title="Create Candidate"><span><span>Create Candidate</span></span></button>				
						<input type="hidden" name="remove_guardian" id="remove_guardian" value="" />
						<input type="hidden" name="remove_other_guardian" id="remove_other_guardian" value="" />
						
					</div>
						
				</div>
				<p class="clearSM">&nbsp;</p>
			</div>
			
		</div>
		<div class="bg-leaf"><img src="<?php echo base_url();?>images/leaf-bg-no-repeat.png" alt="" /></div>
	</div>
</div>	
		