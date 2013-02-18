<!--load the FB js library-->
<script src="http://connect.facebook.net/en_US/all.js"></script>

<!--custom scripts for "Create Candidate" page-->
<script type="text/javascript">
	jQuery(document).ready(function($) {
		//event that removes the selected friend from "Help Friend" section of the logged in user
		$('#nc').live("click", function(){			
			
			$('#send_message').hide();
			$('#sfr').hide();
			
			$('#helpfriend textarea#candidate_rec_msg').val('A Short Recommendation');
			$('#helpfriend #candidate_rec_msg').hide();
			
			$('#helpfriend #candidate1').val('');
			$('#helpfriend #other_fb_friend_box').val('A Short Recommendation');
			$('#helpfriend #other_fb_friend_rec_msg').hide();
			
			$('#helpfriend #candidate_relation').hide();
			$('#helpfriend #other_fb_friend_relation').hide();			
			
			var selected = "";
			var selected = $("input[type='radio']:checked");
			if (selected.length > 0){
				selectedId = selected.attr('id');	//indicates which radio button is selected
				selectedValue = selected.val();		//friend's unique facebook id
			
				selectedName = $('#nc').attr('rel'); //get friend's fullname
			}
			
			if(selectedValue=='other_fb_friend') return false;
			
			if(confirm('Are you sure you want to remove '+selectedName+' from potential candidate list?')) {  
				var request = $.ajax({
					url: "<?php echo base_url();?>facebooker/manageFriends",
					type: "POST",
					data: {fbUserId : selectedValue, otherfbUserId : selectedValue, id: selectedId, loggedInUser : $('#userId').val()}            
				});
				
				request.done(function(msg) {
					//show the success message					
					if($('#fb-root')){    
                                        $('#fb-root').html(msg);
                                        }
				
					$('p.alert').removeClass('success').addClass('errors');
					$('p.alert.errors').html(selectedName+" is removed from potential candidate list");
					
					if(msg==''){
						$('div#help-frnd-lft-block').removeClass('lft-block');
						$('div#dashboard-help-frnd-lft-block').removeClass('lft-block');
					}
				
				}); 
				
			}else{
				$('#me').trigger("click");	
			}
				//redirect to add candidate page
				//window.location.href = "<?php echo base_url();?>candidate/addcandidate";
	});						
			
    //prepare the autosuggest arrays 
    var availableTags2 = new Array();
	var availableTags2WithName = new Array();
			
    <?php  
    $fr_user = $suggestedfriends; 		
		
        foreach ($fr_user['records'] as $key => $value){ ?>   	
            availableTags2[<?php echo $key ?>] = '<?php echo addslashes($value["fb_user_id"]."##".$value["username"]."##".$value["name"]) ?>'
			availableTags2WithName[<?php echo $key ?>] = '<?php echo addslashes($value["name"]) ?>'
    <?php  
        }		
		
    ?>
		
	//populate the autosuggest array in the main panel
    $("#candidateName").autocomplete({
        source: availableTags2WithName
    });
		
	//populate the autosuggest array in the "Help your friends" and Guardian panel
    $('div#guardian_selector #candidate1').autocomplete({
        source: availableTags2WithName
    }); 
		
    /***
    $('#candidate1').autocomplete({
    source: availableTags2WithName
    });
    ***/
		
    $('#helpfriend #candidate1').live('keydown',  function(){ $(this).autocomplete({
        source: availableTags2WithName
    });
	});
		
			 // show/hide the guardian selection div on the basis of a valid name being typed in the autosuggest box
			 $("#candidateName").blur(function()
			 {					
					if($("#candidateName").val() != ''){									
						
						var candidateNameList = new Array();
						<?php  $candidate = $suggestedfriends; foreach ($candidate['records'] as $key => $value): ?>   	
							candidateNameList[<?php echo $key; ?>] = '<?php echo addslashes($value["name"]); ?>';
						<?php  endforeach;?>
						
						//check if name typed in the autosuggest box is a valid name
						if($.inArray($("#candidateName").val(), candidateNameList)!= -1){
							
							//fetch the username of the friend and assign it to the "Me" radio button
							var str1 = $('#candidateName').val();			
							
							jQuery.each(availableTags2, function() {
								userdata = this.split('##');
								
								if(str1 == userdata[2]){
									$('div#guardian_selector #candidateMe').val(userdata[1]);
									$('div#guardian_selector #candidate').val(userdata[0]);
								}
								
							});
							
							$('div#guardian_selector span#selected_guardian').html($("#candidateName").val());
							
							$('#cn_err').html('');
							
							//$("div#guardian_selector #candidate").attr("disabled","disabled");
							//$("div#guardian_selector #other_guardian").attr("disabled","disabled");
							
							$("div#guardian_selector #candidateMe").trigger("click");
							$("div#guardian_selector").show();	
							$("button#Candidate_submit span span").text("Create Candidate");
							
						}else{
							$('#cn_err').addClass('errors');							
               				$('#cn_err').html('Please select a facebook friend');	
							$("div#guardian_selector").hide();
						}					
							
					}else{
						$('#cn_err').html('');
						$("div#guardian_selector").hide();
					}
			 });    
			
		//event that checks whether any invalid name is typed in the autosuggest text box in the main panel
		var candidateNameList = new Array();
        <?php  $candidate = $suggestedfriends; foreach ($candidate['records'] as $key => $value): ?>   	
            candidateNameList[<?php echo $key; ?>] = '<?php echo addslashes($value["name"]); ?>';
        <?php  endforeach;?>
			
		$('button#Candidate_submit').live('click', function()
        {	
			//show the loader image
			$('button#Candidate_submit').html('<img style="border:0px solid;" src="<?php echo base_url();?>images/ajax-loader.gif"/>');
			
			//Guardian Panel functionality starts
			//check which radio button in the Guardian Panel is checked and execute the corresponding actions
			if($('div#guardian_selector #candidate').is(':checked')){ 
				
				var fbUserId = $('#userId').val();
				var frFbUserId = $('div#guardian_selector #candidate').val();
				var otherfrFbUserId = $('div#guardian_selector #candidate').val();	
				var msg = $('#guardian_selector textarea#candidate_rec_msg').val();
				var	fbUserName = $("div#guardian_selector #candidateMe").val();				
					
					//insert the recommendation text in rc_recommendations table
					var request = $.ajax({
						url: "<?php echo base_url();?>facebooker/insertRecMsg",
						type: "POST",
						data: {fbUserId : fbUserId, frFbUserId : frFbUserId, otherfrFbUserId : otherfrFbUserId, msg: msg, type: 'G', gRelation: $('#guardian_selector #select_candidate_relation :selected').val()}            
					});
					
					request.done(function(msg) {
					
						//check if the user is a rishtey user and if so send mail
						var request = $.ajax({
							url: "<?php echo base_url();?>facebooker/chkRcUser",
							type: "POST",
							data: {fbUserId : fbUserId, fbUserName : fbUserName , otherfrFbUserId : otherfrFbUserId, inviteFriends : 2}            
						});
						
						request.done(function(msg) {	
							if(msg==0){
								//user is not a FB user so send him a FB message							
								//call FB Dialog API
								fb_dialogue(fbUserName, frFbUserId, otherfrFbUserId, 1);
							}else{
																			
										//create log message for guardian change
										var request = $.ajax({
											url: "<?php echo base_url();?>facebooker/addRecommendationLog",
											type: "POST",
											data:{candidateFbId : frFbUserId, othercandidateFbId : otherfrFbUserId, type: 'G(BYMAIL)', recommenderFbId : fbUserId}
										});									
										
										request.done(function(msg) {
											$('p.alert_guardian').addClass('successful');
											$('p.alert_guardian').html("Mail sent successfully");
										
											//hide the guardian panel
											//$('#addCandidate #candidateName').val('');
										
											$('#guardian_selector #candidateMe').trigger("click");
											$('#guardian_selector').hide();
										
											$('button#Candidate_submit').html('<span><span>Create Candidate</span></span>');	
										});
							}
						});
					});			
						
					return false;
						
			}else if($('div#guardian_selector #other_guardian').is(':checked')){
						
				var fbUserId = $('#userId').val();
				var frFbUserId = $('div#guardian_selector #candidate').val();
				var otherfrFbUserId = $('div#guardian_selector #candidate').val();	
				var msg = $('#guardian_selector #other_fb_friend_box').val();
				var	fbUserName = $("div#guardian_selector #candidate1").val();				
						
				//fetch the username for sending message through FB dialog box 
				var availableTags2ForGuardian = new Array();				
						
				<?php  
					$fr_user = $suggestedfriends; 		
					
					foreach ($fr_user['records'] as $key => $value){ ?>   	
						availableTags2ForGuardian[<?php echo $key ?>] = '<?php echo addslashes($value["fb_user_id"]."##".$value["username"]."##".$value["name"]) ?>'						
				<?php  
					}		
					
				?>
					
					jQuery.each(availableTags2ForGuardian, function() {
					userdata = this.split('##');
					
					if(fbUserName == userdata[2]){
						fbUserName = userdata[1];
						frFbUserId = userdata[0];
					}
					
				});		
					
						//insert the recommendation text in rc_recommendations table
						var request = $.ajax({
							url: "<?php echo base_url();?>facebooker/insertRecMsg",
							type: "POST",
							data: {fbUserId : fbUserId, frFbUserId : frFbUserId, otherfrFbUserId : otherfrFbUserId, msg: msg, type: 'G', gRelation: $('#guardian_selector #select_other_fb_friend_relation :selected').val()}            
						});
						
						request.done(function(msg) {
							
							//check if the user is a rishtey user and if so send mail
							var request = $.ajax({
								url: "<?php echo base_url();?>facebooker/chkRcUser",
								type: "POST",
								data: {fbUserId : fbUserId, fbUserName : fbUserName, otherfrFbUserId : otherfrFbUserId, inviteFriends : 2 }            
							});
								
							request.done(function(msg) {	
								if(msg==0){
									//user is not a FB user so send him a FB message							
									//call FB Dialog API
									fb_dialogue(fbUserName, frFbUserId, otherfrFbUserId, 1);
								}else{										
										
									//create log message for guardian change
									var request = $.ajax({
										url: "<?php echo base_url();?>facebooker/addRecommendationLog",
										type: "POST",
										data:{candidateFbId : frFbUserId, othercandidateFbId : otherfrFbUserId, type: 'G(BYMAIL)', recommenderFbId : fbUserId}
									});									
									
									request.done(function(msg) {
										$('p.alert_guardian').addClass('successful');
										$('p.alert_guardian').html("Mail sent successfully");
							
										//hide the guardian panel
										//$('#addCandidate #candidateName').val('');
							
										$('#guardian_selector #candidateMe').trigger("click");
										$('#guardian_selector').hide();
							
										$('button#Candidate_submit').html('<span><span>Create Candidate</span></span>');	
									});																
									
								}	
									
							});
							
						});
						
						return false;
						
			}			
						
			//check which radio button is checked if profile exists
			if($('span#cn_err #rec_candidate').is(':checked')){ 
					
				var parentId = $(this).parent().attr('id');
				var fbUserId = $('#userId').val();
				var frFbUserId = $('span#cn_err #rec_candidate').val();
				var otherfrFbUserId = $('span#cn_err #rec_candidate').val();	
				var msg = $('span#cn_err textarea#candidate_rec_msg').val();
					
					//insert the recommendation text in rc_recommendations table
					var request = $.ajax({
						url: "<?php echo base_url();?>facebooker/insertRecMsg",
						type: "POST",
						data: {fbUserId : fbUserId, frFbUserId : frFbUserId, otherfrFbUserId : otherfrFbUserId, msg: msg, type: 'R', gRelation: $('span#cn_err #select_candidate_relation :selected').val()}            
					});
						
					request.done(function(msg) {

						//create log message for guardian change request 
						var request = $.ajax({
							url: "<?php echo base_url();?>facebooker/addRecommendationLog",
							type: "POST",
							data:{candidateFbId : $("#cn_err span[name='fb_guardian']").attr('id'), othercandidateFbId : $("#cn_err #rec_candidate").val(), type: 'CG(BYMAIL)', recommenderFbId : $("#userId").val()}
						});
						
						request.done(function(msg) {
							if(parentId!='notGuardian'){
								$('#cn_err').html('');
							}

							$('button#Candidate_submit').html('<span><span>Create Candidate</span></span>');
							//$('#candidateName').val('');
							$('p.alert_guardian').addClass('successful');
							$('p.alert_guardian').html("Recommendation message saved successfully");						
						
						});
					
					});
						
					return false;
						
			}else if($('span#cn_err #mail_guardian').is(':checked')){ 
					
					var parentId = $(this).parent().attr('id');
					
					var request = $.ajax({
						url: "<?php echo base_url();?>facebooker/sendMailToGuardian",
						type: "POST",
						data: {fbUserId : $("#userId").val(), gfbUserId : $("#guardian_loc_fb_id").val()}            
					});	
						
					request.done(function(msg) {
					
						//create log message for guardian change request 
						var request = $.ajax({
							url: "<?php echo base_url();?>facebooker/addRecommendationLog",
							type: "POST",
							data:{candidateFbId : $("#cn_err span[name='fb_guardian']").attr('id'), othercandidateFbId : $("#cn_err #rec_candidate").val(), type: 'CG(BYMAIL)', recommenderFbId : $("#userId").val()}
						});
						
						request.done(function(msg) {
							
							if(parentId!='notGuardian'){
								$('#cn_err').html('');
							}

							$('button#Candidate_submit').html('<span><span>Create Candidate</span></span>');
							//$('#candidateName').val('');
							$('p.alert_guardian').addClass('successful');
							$('p.alert_guardian').html("Mail send successfully");						
						});
						
					});	
						
					return false;
						
			}			
					
			//Guardian Panel functionality ends			
			
            var isChecked1 = $('#candidateMe').attr('checked') ? true : false;			
			
			//if "Myself" checkbox is not checked, check if "Guardian panel Me" radio button is checked
			if(!isChecked1){
				var isChecked1 = $('div#guardian_selector #candidateMe').is(':checked');
			}            
			
			var str1 = $('#candidateName').val();			
			
		    jQuery.each(availableTags2, function() {
				userdata = this.split('##');
					
				if(str1 == userdata[1]){
					$("#frCandidateName").val(userdata[0]);
				}
					
			});            
					
            if (str1.length > 0){
            	if($.inArray(str1, candidateNameList)!= -1){
					
					$('button#Candidate_submit').html('<span><span>Create Candidate</span></span>');
					//show the loader image
					$('#cn_err').html('<img style="border:0px solid;" src="<?php echo base_url();?>images/ajax-loader.gif"/>');		
					
					//assign the guardian selector "Me" value 
					if($('div#guardian_selector #candidateMe').is(':checked')){ 
						$("#frCandidateName").val($('div#guardian_selector #candidateMe').val());
					}
						
					//check if profile exists
					var request = $.ajax({
						url: "<?php echo base_url();?>facebooker/chkProfileExists",
						type: "POST",
						data: {fbUserName : $("#frCandidateName").val(), loggedInUser : $('#userId').val()}            
					});
						
					request.done(function(msg) {
						
						msgArr = msg.split('##');
						if(msgArr[0]==1){
							//hide the guardian panel div
							$("div#guardian_selector").hide();
							$('button#Candidate_submit').html('<span><span>Create Candidate</span></span>');							
							$('#cn_err').html(msgArr[1]);
						}else{						
							$('#addCandidate').submit();
						}
							
					});					
            				
            	}
            	else{
					$('button#Candidate_submit').html('<span><span>Create Candidate</span></span>');
					$('#cn_err').addClass('errors');
               		$('#cn_err').html('Please select a facebook friend');
            	}  
				
			}else if((isChecked1)){			
             $('button#Candidate_submit').html('<span><span>Create Candidate</span></span>');
			 //show the loader image
             $('#cn_err').html('<img style="border:0px solid;" src="<?php echo base_url();?>images/ajax-loader.gif"/>');			 
				
					//check if profile exists
					var request = $.ajax({
						url: "<?php echo base_url();?>facebooker/chkProfileExists",
						type: "POST",
						data: {fbUserName : $("#candidateMe").val(), loggedInUser : $('#userId').val()}            
					});
					
					request.done(function(msg) {    
						msgArr = msg.split('##');
						if(msgArr[0]==1){
							//hide the guardian panel div
							$("div#guardian_selector").hide();
							$('button#Candidate_submit').html('<span><span>Create Candidate</span></span>');
							$('#cn_err').html(msgArr[1]);
						}else{	
							var isChecked = $('#candidateMe:checked').val();
							
							if( isChecked=='' && (str1.length == 0) ) {			
								$('button#Candidate_submit').html('<span><span>Create Candidate</span></span>');
								$('#cn_err').addClass('errors');
								$('#cn_err').html('Please fillup the form');
							}else{									
								$('#addCandidate').submit();							
							}
						}
							
					});
						
            }		
					
			//select either "Myself" checkbox or type a friend's name in the autosuggest text box
            /*if( !isChecked1 && (str1.length == 0) ) {			
				$('button#Candidate_submit').html('<span><span>Create Candidate</span></span>');
            	$('#cn_err').html('Please fillup the form');
            }*/
					
        });			
				
	//conditionally prepare the "Send Message" button text value
	$('#me').live("click", function(){
		$("textarea#candidate_rec_msg").val('A Short Recommendation');
		$("#candidate_rec_msg").hide();
		$("#other_fb_friend_box").val('');
			
		$("#candidate1").val('');
		$("#other_fb_friend_box").val('A Short Recommendation');
		$("#other_fb_friend_rec_msg").hide();
		$("#candidate_relation").hide();
		$("#other_fb_friend_relation").hide();
		$("#send_message").text("Go");
	});
			
	$('#helpfriend #candidate').live("click", function(){
		$("#other_fb_friend_box").val('A Short Recommendation');
		$("#other_fb_friend_rec_msg").hide();
		$("#candidate_rec_msg").show();
		$("#candidate_relation").show();
		$("#other_fb_friend_relation").hide();
		$("#send_message").text("Send Message");
	});
		
	$('#helpfriend #other_fb_friend').live("click", function(){		
		$("textarea#candidate_rec_msg").val('A Short Recommendation');	
		$("#candidate_rec_msg").hide();		
		$("#candidate_relation").hide();
		$("#send_message").text("Send Message");
	});
		
	$('#nc').live("click", function(){
		/*$("#send_message").text("Save");*/
	});	
		
	//conditionally show/hide the "Other Friend" section in "Help Friend" Panel	
	$('#helpfriend #candidate1').live("blur", function(){
		if($('#helpfriend #candidate1').val()!=''){
						var candidateNameList = new Array();
						<?php  $candidate = $suggestedfriends; foreach ($candidate['records'] as $key => $value): ?>   	
							candidateNameList[<?php echo $key; ?>] = '<?php echo addslashes($value["name"]); ?>';
						<?php  endforeach;?>
						
						//check if name typed in the autosuggest box is a valid name
						if($.inArray($("#helpfriend #candidate1").val(), candidateNameList)!= -1){
							$("#helpfriend #other_fb_friend_rec_msg").show();
							$("#helpfriend #other_fb_friend_relation").show();
						
							$("#send_message").show();
						
						}else{
							$("#helpfriend #other_fb_friend_rec_msg").hide();
							$("#helpfriend #other_fb_friend_relation").hide();
						}
		}else{
			$("#helpfriend #other_fb_friend_rec_msg").hide();
			$("#helpfriend #other_fb_friend_relation").hide();
		}
		
	});	
		
});		
</script>
		
<!--custom scripts for "Help your friends" section-->
<script type="text/javascript">
   jQuery(document).ready(function($) {		
	//prepare the Send Message Dialog
    $('#helpfriend #other_fb_friend').live("click", function(){        
        $('#sfr').show();    
    });
	
    $('#nc').live("click", function(){
        /*do nothing*/
		
    });
    
    $('#helpfriend #candidate').live("click", function(){
        $('#send_message').show();
		$('#helpfriend #candidate1').val('');        
		$('#helpfriend #other_fb_friend_box').val('A Short Recommendation');
        $('#sfr').hide();        
    });
	
    $('#me').live("click", function(){        
        $('#send_message').show();
        $('#sfr').hide();         
    });
    	
    //execute the Send Message Dialog    
    $('#send_message').live("click", function(){                   
            
            if($('#me').is(':checked')){ 
				
				//unset 'tab_no' cookie		
				document.cookie = "tab_no=; expires=-1 UTC; path=/candidate/";			  

                $('#helpfriend').submit();
            }else if($('#helpfriend #candidate').is(':checked')){
			
				var fbUserId = $('#userId').val();
				var frFbUserId = $('#helpfriend #candidate').val();
				var otherfrFbUserId = $('#helpfriend #candidate').val();
				var msg = $('#helpfriend textarea#candidate_rec_msg').val();
				var	fbUserName = $("[name='candidateMe']").val();				
									
					//show the loader image
					$('.alert-header').html('<img style="border:0px solid;" src="<?php echo base_url();?>images/ajax-loader.gif"/>');
					
					//insert the recommendation text in rc_recommendations table
					var request = $.ajax({
						url: "<?php echo base_url();?>facebooker/insertRecMsg",
						type: "POST",
						data: {fbUserId : fbUserId, frFbUserId : frFbUserId, otherfrFbUserId : otherfrFbUserId, msg: msg, type: 'I', gRelation: $('#helpfriend #select_candidate_relation :selected').val()}            
					});
					
					request.done(function(msg) {	
						//check if the user is a rishtey user and if so send mail
						var request = $.ajax({
							url: "<?php echo base_url();?>facebooker/chkRcUser",
							type: "POST",
							data: {fbUserId : fbUserId, fbUserName : fbUserName, otherfrFbUserId : otherfrFbUserId}            
						});
							
						request.done(function(msg) {	
							if(msg==0){
								//user is not a RC user so send him a FB message
								$('.alert-header').html('');
								//call FB Dialog API
								fb_dialogue(fbUserName, frFbUserId, otherfrFbUserId, 0);
							}else{							
								
								//remove friend from "Help Friend" panel
 								var request = $.ajax({
									url: "<?php echo base_url();?>facebooker/manageFriends",
									type: "POST",
									data: {fbUserId : frFbUserId, otherfbUserId : otherfrFbUserId, selectedId: 'sm', loggedInUser : $('#userId').val()}            
								});
								
								request.done(function(msg) {
										//show the success message
										if($('#fb-root')){    
                                                                                $('#fb-root').html(msg);
                                                                                }
										$('p.alert-header').removeClass('errors').addClass('success');
										$('p.alert-header.success').html("Mail sent successfully");
										//alert('hiii');
										//return false;
										
										//if no friend left then remove the "Help Friend" Panel
										if(msg==''){
											$('div#help-frnd-lft-block').removeClass('lft-block');
											$('div#dashboard-help-frnd-lft-block').removeClass('lft-block');
										}
										
										//create profile data
										var request = $.ajax({
											url: "<?php echo base_url();?>facebooker/insertProfileData",
											type: "POST",
											data: {fbUserId : frFbUserId, otherfrFbUserId : otherfrFbUserId, type: 'I', loggedInUser : $('#userId').val()}            
										});
										
										request.done(function(msg) {	
											
												//create log message for initiation
												var request = $.ajax({
													url: "<?php echo base_url();?>facebooker/addRecommendationLog",
													type: "POST",
													data:{candidateFbId : frFbUserId, othercandidateFbId : otherfrFbUserId, type: 'I(BYMAIL)', recommenderFbId : fbUserId}
												});												
												
												request.done(function(msg) {	
													//do nothing	
												});
												
										});								
								});								
							}					
												
						});						
												
					});							
                     							
            }else if($('#helpfriend #candidate1').attr('val')!=''){
				
				var fbUserId = $('#userId').val();
				var frFbUserId = $('#helpfriend #candidate').val();
				var otherfrFbUserId = $('#helpfriend #candidate').val();
				var msg = $('#helpfriend #other_fb_friend_box').val();
				var	fbUserName = $('#helpfriend #candidate1').val();				
				
				//fetch the username for sending message through FB dialog box 
				var availableTags2 = new Array();				
				
				<?php  
					$fr_user = $suggestedfriends; 		
					
					foreach ($fr_user['records'] as $key => $value){ ?>   	
						availableTags2[<?php echo $key ?>] = '<?php echo addslashes($value["fb_user_id"]."##".$value["username"]."##".$value["name"]) ?>'						
				<?php  
					
					}		
					
				?>
					
					jQuery.each(availableTags2, function() {
					userdata = this.split('##');
					
					if(fbUserName == userdata[2]){
						fbUserName = userdata[1];
						frFbUserId = userdata[0];
					}
					
				});
					
					//show the loader image
					$('.alert-header').html('<img style="border:0px solid;" src="<?php echo base_url();?>images/ajax-loader.gif"/>');
						
					//insert the recommendation text in rc_recommendations table
					var request = $.ajax({
						url: "<?php echo base_url();?>facebooker/insertRecMsg",
						type: "POST",
						data: {fbUserId : fbUserId, frFbUserId : frFbUserId, otherfrFbUserId : otherfrFbUserId, frFbUserName : fbUserName, msg: msg, type: 'I', gRelation: $('#helpfriend #select_other_fb_friend_relation :selected').val()}            
					});
					
					request.done(function(msg) {
						//check if the user is a rishtey user and if so send mail
						var request = $.ajax({
							url: "<?php echo base_url();?>facebooker/chkRcUser",
							type: "POST",
							data: {fbUserId : fbUserId, fbUserName : fbUserName, otherfrFbUserId : otherfrFbUserId}            
						});
						
						request.done(function(msg) {	
							if(msg==0){
								//user is not a FB user so send him a FB message
								$('.alert-header').html('');
								//call FB Dialog API
								fb_dialogue(fbUserName, frFbUserId, otherfrFbUserId, 0);
							}else{
								
								//remove friend from "Help Friend" panel
 								var request = $.ajax({
									url: "<?php echo base_url();?>facebooker/manageFriends",
									type: "POST",
									data: {fbUserId : frFbUserId, otherfbUserId : otherfrFbUserId, selectedId: 'sm', loggedInUser : $('#userId').val()}            
								});
								
								request.done(function(msg) {	
										
										if($('#fb-root')){    
                                                                                $('#fb-root').html(msg);
                                                                            }
										$('p.alert-header').removeClass('errors').addClass('success');
										$('p.alert-header.success').html("Mail sent successfully");
										
										if(msg==''){
											$('div#help-frnd-lft-block').removeClass('lft-block');
											$('div#dashboard-help-frnd-lft-block').removeClass('lft-block');
										}
										
										//create profile data
										var request = $.ajax({
											url: "<?php echo base_url();?>facebooker/insertProfileData",
											type: "POST",
											data: {fbUserId : frFbUserId, otherfrFbUserId : otherfrFbUserId, type: 'I', loggedInUser : $('#userId').val()}            
										});
										
										request.done(function(msg) {	
											
												//create log message for initiation
												var request = $.ajax({
													url: "<?php echo base_url();?>facebooker/addRecommendationLog",
													type: "POST",
													data:{candidateFbId : frFbUserId, othercandidateFbId : otherfrFbUserId, type: 'I(BYMAIL)', recommenderFbId : fbUserId}
												});
												
												
												request.done(function(msg) {	
													//do nothing	
												});
												
										});								
								});				
												
							}					
						});						
						
					});											
                   
            } 
    });
					//toggle the default text in the recommendation text boxes in "Help your friends" panel
					$('#helpfriend textarea#candidate_rec_msg').live("click", function(){
						if($('#helpfriend textarea#candidate_rec_msg').val() == "A Short Recommendation"){
							$('#helpfriend textarea#candidate_rec_msg').val("");
						}
						
					});
					$('#helpfriend textarea#candidate_rec_msg').live("blur", function(){
						
						if($('#helpfriend textarea#candidate_rec_msg').val() == ""){
							$('#helpfriend textarea#candidate_rec_msg').val("A Short Recommendation");
							
						}else{
							$("#helpfriend #candidate_relation").show();
						}
						
					});
					
					$('#helpfriend textarea#other_fb_friend_box').live("click", function(){
						if($('#helpfriend textarea#other_fb_friend_box').val() == "A Short Recommendation"){
							$('#helpfriend textarea#other_fb_friend_box').val("");
						}
					
					});
					$('#helpfriend textarea#other_fb_friend_box').live("blur", function(){
						
						if($('#helpfriend textarea#other_fb_friend_box').val() == ""){
							$('#helpfriend textarea#other_fb_friend_box').val("A Short Recommendation");
							
						}else{
							$("#helpfriend #other_fb_friend_relation").show();
						}
						
					});					
					
					//toggle the default text in the recommendation text boxes in Guardian panel
					$('#guardian_selector textarea#candidate_rec_msg').live("click", function(){
						if($('#guardian_selector textarea#candidate_rec_msg').val() == "A Short Recommendation"){
							$('#guardian_selector textarea#candidate_rec_msg').val("");
						}
						
					});
					$('#guardian_selector textarea#candidate_rec_msg').live("blur", function(){
						
						if($('#guardian_selector textarea#candidate_rec_msg').val() == ""){
							$('#guardian_selector textarea#candidate_rec_msg').val("A Short Recommendation");
							
						}else{
							$("#guardian_selector #candidate_relation").show();
						}
						
					});
					
					$('#guardian_selector textarea#other_fb_friend_box').live("click", function(){
						if($('#guardian_selector textarea#other_fb_friend_box').val() == "A Short Recommendation"){
							$('#guardian_selector textarea#other_fb_friend_box').val("");
						}
						
					});
					$('#guardian_selector textarea#other_fb_friend_box').live("blur", function(){
						
						if($('#guardian_selector textarea#other_fb_friend_box').val() == ""){
							$('#guardian_selector textarea#other_fb_friend_box').val("A Short Recommendation");
							
						}else{
							$("#guardian_selector #other_fb_friend_relation").show();
						}
							
					});
										
					
					//toggle the default text in the recommendation text boxes when profile exists
					$('#cn_err textarea#candidate_rec_msg').live("click", function(){
						if($('#cn_err textarea#candidate_rec_msg').val() == "A Short Recommendation"){
							$('#cn_err textarea#candidate_rec_msg').val("");
						}
						
					});
					
					
					$('#cn_err textarea#candidate_rec_msg').live("blur", function(){
						
						if($('#cn_err textarea#candidate_rec_msg').val() == ""){
							$('#cn_err textarea#candidate_rec_msg').val("A Short Recommendation");
							
						}else{
							$("#cn_err #candidate_relation").show();
						}
						
					});
					
					
					//remove select box styles from the "Help your friends" section on Create Candidate pages
					$('div#candidate_relation a.selectBox-dropdown').remove();
					$('#select_candidate_relation').removeAttr("style");
						
					$('div#other_fb_friend_relation a.selectBox-dropdown').remove();
					$('#select_other_fb_friend_relation').removeAttr("style");					
					
  });				
  
  //function that sends message through FB Dialog API  
  function fb_dialogue(fbUserName, frFbUserId, otherfrFbUserId, type)
  {
	//check if request came from Guardian/Help Friend panel
	if(type==1){
		
		//store the fb user id of the friend whom the message is sent  
		$('#remove_guardian').val(frFbUserId);
		//store the fb user id of the other friend for whom the message is sent  
		if(otherfrFbUserId!=''){
			$('#remove_other_guardian').val(otherfrFbUserId);
		}else{
			$('#remove_other_guardian').val(frFbUserId);
		}
		
	}else{	
			//store the fb user id of the friend whom message is sent  
			$('#remove_friend').val(frFbUserId);
			//store the fb user id of the other friend for whom the message is sent  
			if(otherfrFbUserId!=''){
				$('#remove_other_friend').val(otherfrFbUserId);
			}else{
				$('#remove_other_friend').val(frFbUserId);
			}	
	}		
		
		//initialize FB Dialog	
		FB.init({appId: '<?php echo $fbAppId; ?>', xfbml: true, cookie: true});	
		
        var url = '<?php echo base_url(); ?>sendmessage/index/'+otherfrFbUserId;
		
	//check if request came from Guardian/Help Friend panel
	if(type==0){
		FB.ui({
			to: fbUserName,
			method: 'send',
			name: 'Test',
			//link: 'http://ec2-50-19-66-142.compute-1.amazonaws.com/rishtey-connect'
			link: url
			//link: '<?php echo base_url(); ?>'
		},requestCallback );
	}else{
		FB.ui({
			to: fbUserName,
			method: 'send',
			name: 'Test',
			//link: 'http://ec2-50-19-66-142.compute-1.amazonaws.com/rishtey-connect'
			link: url
			//link: '<?php echo base_url(); ?>'
		},requestCallbackForGuardian );
	}	
      	
  }
	
  function requestCallback(response) {
		//Handle callback here 
		//alert('response='+response);
                
		for(var key in response){				
			
				if(response[key] === true){				
				
						var request = $.ajax({
							url: "<?php echo base_url();?>facebooker/manageFriends",
							type: "POST",
							data: {fbUserId : $('#remove_friend').val(), otherfbUserId : $('#remove_other_friend').val(), selectedId: 'sm', loggedInUser : $('#userId').val()}            			
						});
						
						request.done(function(msg) {		
							//show the success message
                                                        if($('#fb-root')){    
							$('#fb-root').html(msg);}
							$('p.alert-header').removeClass('errors').addClass('success');
							$('p.alert-header.success').html("FB message sent successfully");
							
							if(msg==''){
								$('div#help-frnd-lft-block').removeClass('lft-block');
								$('div#dashboard-help-frnd-lft-block').removeClass('lft-block');
							}							
							
							//create profile data
							var request = $.ajax({
								url: "<?php echo base_url();?>facebooker/insertProfileData",
								type: "POST",
								data: {fbUserId : $('#remove_friend').val(), otherfrFbUserId : $('#remove_other_friend').val(), type: 'I', loggedInUser : $('#userId').val()}            			
							});
							
							request.done(function(msg) {
								
								//create log message for initiation
								var request = $.ajax({
									url: "<?php echo base_url();?>facebooker/addRecommendationLog",
									type: "POST",
									data:{candidateFbId : $('#remove_friend').val(), othercandidateFbId : $('#remove_other_friend').val(), type: 'I(BYFB)', recommenderFbId : $('#userId').val()}
								});
								
								
								request.done(function(msg) {	
									//do nothing	
								});								
								
							});							
						});		
				}		
		}
  }		
		
  function requestCallbackForGuardian(response){					
		
		if(response!=null){			
				
				//create log message for initiation from Guardian Panel
				var request = $.ajax({
					url: "<?php echo base_url();?>facebooker/addRecommendationLog",
					type: "POST",
					data:{candidateFbId : $('#remove_guardian').val(), othercandidateFbId : $('#remove_other_guardian').val(), type: 'G(BYFB)', recommenderFbId : $('#userId').val()}
				});				
				
				request.done(function(msg) {	
					$('p.alert_guardian').addClass('successful');
					$('p.alert_guardian').html("FB message sent successfully");

					//hide the guardian panel
					//$('#addCandidate #candidateName').val('');
				
					$('#guardian_selector #candidateMe').trigger("click");
					$('#guardian_selector').hide();
				
					$('button#Candidate_submit').html('<span><span>Create Candidate</span></span>');
				});			
				
		}else{
				//hide the guardian panel
				//$('#addCandidate #candidateName').val('');
				
				$('#guardian_selector #candidateMe').trigger("click");
				$('#guardian_selector').hide();
					
				$('button#Candidate_submit').html('<span><span>Create Candidate</span></span>');
		}			
		
  }					
				
  function rc_mail(){
    //write the function body here		  
  }
	
</script>		
			
			<?php if(count($random_suggested_friend) > 0){ ?>
						<div id="help-frnd-lft-block" class="lft-block">
							<div id="fb-root">
								<h4>Help your friends</h4>
								<!--<p class="alert success" style="margin-bottom:5px!important; text-transform:none !important"></p>-->
								
								<div class="poll-block">
									  <img height="107" width="107" src="https://graph.facebook.com/<?php echo $random_suggested_friend[0]['fb_user_id'] ?>/picture?width=107&height=107" alt="<?php echo $random_suggested_friend[0]['fname'].' '.$random_suggested_friend[0]['lname'] ?>" title="<?php echo $random_suggested_friend[0]['fname'].' '.$random_suggested_friend[0]['lname'] ?>" />
									  <div class="poll">
										<p>Who can give us more information about <?php echo $random_suggested_friend[0]['fname'].' '.$random_suggested_friend[0]['lname'] ?>?</p>
										<form id="helpfriend" name="helpfriend" method="post" action="<?php echo base_url();?>candidate/candidateprofile">
											
											<label class="row">
												<input name="candidateMe" type="radio" id="me" value="<?php echo $random_suggested_friend[0]['username'];?>" checked="checked"  />
												<em>Me</em>
											</label>
										
											<label class="row">
												<input type="radio" name="candidateMe" id="candidate" value="<?php echo $random_suggested_friend[0]['fb_user_id'] ?>" />
												<em><?php echo $random_suggested_friend[0]['fname'].' '.$random_suggested_friend[0]['lname'] ?></em> 
											</label>
	
											<div id="candidate_rec_msg" style="display:none; ">
												<textarea class="textarea inputWidth" id="candidate_rec_msg">A Short Recommendation</textarea>
											</div>
	
											<div id="candidate_relation" style="display:none; margin-top:2px;">
												<select class="selectBox inputWidth" name="select_candidate_relation" id="select_candidate_relation">
													<?php  foreach($recommendationRelations as $key => $option):?>
																<option value="<?php echo $option['id'];?>"><?php echo $option['relation'];?></option>
													<?php endforeach;?>
												</select>
											</div>
										
											<label class="row">
												<input type="radio" name="candidateMe" id="other_fb_friend" value="other_fb_friend" />
												<em>Other FB Friends</em> 
											</label>
												
											<p id="sfr" style="display: none;">
												<input style="width:150px;" class="input" id="candidate1" name="candidate1" />
											</p>
	
											<div id="other_fb_friend_rec_msg" style="display:none; ">
												<textarea class="textarea inputWidth" id="other_fb_friend_box">A Short Recommendation</textarea>
											</div>
	
											<div id="other_fb_friend_relation" style="display:none; margin-top:2px;">
												<select class="selectBox inputWidth" name="select_other_fb_friend_relation" id="select_other_fb_friend_relation">
													<?php  foreach($recommendationRelations as $key => $option):?>
																<option value="<?php echo $option['id'];?>"><?php echo $option['relation'];?></option>
													<?php endforeach;?>
												</select>
											</div>
																
											<label class="row">
												<input rel="<?php echo $random_suggested_friend[0]['fname'] ?>" type="radio" name="candidateMe" id="nc" value="<?php echo $random_suggested_friend[0]['fb_user_id'] ?>" />
												<em>Not a candidate</em>
											</label>
										
											<div class="dvdr"></div>
											<a name="send_message" id="send_message" href="javascript:void(0);" class="greenText">Go</a>
	
											<input type="hidden" name="remove_friend" id="remove_friend" value="" />
											<input type="hidden" name="remove_other_friend" id="remove_other_friend" value="" />
	
										</form>
						
									  </div>
								</div>
							</div>
						</div>
	
			<?php }else{ ?>
				   
				   <!--do nothing-->
	
			<?php }?>
	
	