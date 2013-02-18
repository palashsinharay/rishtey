<!--custom scripts for friend add/delete-->
<script type="text/javascript">
jQuery(document).ready(function($) {    
	//event that removes the selected friend from the potential candidate list
    $('.delete').live("click", function(){	
		
		//show the ajax loader image
		$('.alert').html('<img src="<?php echo base_url();?>images/ajax-loader.gif"/>');
        
        var el = $(this).attr('id');			//friend's unique facebook id
		var el_tmp = $(this).attr('id');		//friend's unique facebook id
        var elUserName = $(this).attr('name');  //friend's fullname
		
		//get friend's first name
		var arrelUserFirstName = elUserName.split(' ');
		var elUserFirstName = arrelUserFirstName[0];
		
		var loggedInUser = $('#userId').val();	//logged in user's unique facebook id		
        
		if(confirm('Are you sure you want to remove '+elUserFirstName+' from potential candidate list?')) {  
			var request = $.ajax({
				url: "<?php echo base_url();?>/facebooker/manageSuggestionList",
				type: "POST",
				data: {fbId : el, fbUserName : elUserName, user : loggedInUser}            
			});
				
			request.done(function(msg) {
					
				  //delete friend from potential candidate list and adjust the list				 
				  $('#th-'+el).remove();
				  $('#fb-'+el_tmp).remove();
					
				  $('#slist li').each(function(index){      
					   $('#'+$(this).attr('id')).removeClass('last');
				  });
				  
				  $('#slist li').each(function(index){ 
										  
				  if(index>0 && ((index+1)%5)==0){
						 $('#'+$(this).attr('id')).addClass('last');
				  }   
				  }); 
				  
				  
				  if($('#slist li').length == 0){
					 $('ul#slist').append('<div><strong>No potential candidate exists</strong></div><br />');
					 $('button#done').hide();
				  }
                                
				
				//show the success message
				$('.alert').removeClass('success').addClass('errors');
				$('.alert.errors').html(elUserFirstName+' has been removed from potential candidate list');
			}); 
          
        }else{
			//nullify the ajax loader image
			$('.alert').html('');
			return false;	
		}
    });
	
    //function that inserts the selected list of friends into fb_suggestion_list table    
    $('#done').click(function(){
		//show the ajax loader image
		$('#done').html('<img src="<?php echo base_url();?>images/ajax-loader.gif"/>');
		
        var arr = new Array();
		var loggedInUser = $('#userId').val();	//logged in user's unique facebook id
        
        $('.fb_id').each(function(index){            
          arr[index] = $(this).val();			//friend's unique facebook id   
            
        });
				
		//now updating the DB through Ajax.
		var request = $.ajax({
            url: "<?php echo base_url();?>/facebooker/insertSuggestionList",
            type: "POST",
            data: {suList : arr, user : loggedInUser}            
        });
		
        request.done(function(msg) {           
            //$('#done').hide();
            //$('.alert').html("<h4>Message</h4>Your suggested list of friends has been added to Rishtey connect network.");
			
			//potential candidate list is ready, redirect to add candidate page
   			window.location.href='<?php echo base_url();?>/candidate/addcandidate';
        });				
			
    });
	
	//prepare the autosuggest array
    var availableTags = new Array();
	var availableTagsWithName = new Array();
    
	<?php 
	foreach ($availableTags as $key => $value){ ?>   	
		availableTags[<?php echo $key ?>] = "<?php echo $value; ?>";
	<?php  
	}
	?>
		
	<?php 
	foreach ($availableTagsWithName as $key2 => $value2){ ?>   	
		availableTagsWithName[<?php echo $key2 ?>] = "<?php echo $value2; ?>";
	<?php  
	}
	?>
		
		//populate the autosuggest textbox on the first login landing page
		$("#candidate").autocomplete({
			source: availableTagsWithName
		});
		
	//event that adds a virtual user to the list of facebook friends of the logged in user
	$('#add_user').live("click", function(){			
		   //show the ajax loader image
		   $('.alert').html('<img src="<?php echo base_url();?>images/ajax-loader.gif"/>');
			
		   var fbUserFullName = $('#candidate').val();	//friend's fullname
			
		   //get friend's first name	
		   var arrFbUserFirstName = fbUserFullName.split(' ');
		   var FbUserFirstName = arrFbUserFirstName[0]; 	
			
		   var fbUserName = '';
		   var userdata = '';		   
			
		    jQuery.each(availableTags, function() {
				userdata = this.split('??');
				
				if(fbUserFullName == userdata[1]){
					fbUserName=userdata[0];				//friend's username
				}
			
			});				
			
		    var loggedInUser = $('#userId').val();		//logged in user's unique facebook id
			
			//check if any invalid name is typed in the autosuggest textbox on the first login landing page	
			if(fbUserFullName=='' || ($.inArray(fbUserFullName, availableTagsWithName)== -1)){
				//show the failure message
				$('.alert').removeClass('success').addClass('errors');
				$('.alert.errors').html('ERROR : Please select a facebook friend');
				
				return false;
			}			
			
			if(confirm('Are you sure you want to add '+FbUserFirstName+' as a potential candidate?')) {
				
				//disable Add Candidate button
				$('button#add_user').attr('disabled', ''); // disable Add Candidate button
				$('button#add_user').removeClass('btn-submit').addClass('btnDisable');				
				
				var request = $.ajax({
					url: "<?php echo base_url();?>/facebooker/addFriendToSuggestionList",
					type: "POST",
					data: {frFbUserName : fbUserName, fbUserId : loggedInUser}            
				});
				
				request.done(function(msg) {
					
					msg = msg.split('##');
									
					availableTagsWithName = msg[1].split(',');
					
					//empty and repopulate the autosuggest text box
					$("#candidate").val('');
					$("#candidate").autocomplete({
						source: availableTagsWithName
					});				
					
					
					if($('#slist li').length > 0){
						//add the record at the last
						$('#slist li:last').after(msg[0]);
					}else{
						$('ul#slist').html('');
						$('ul#slist').append(msg[0]);
						
						$(".network-wrapper").find("br").remove();
						
						$('button#done').show();
					}
					
					//enable Add Candidate button and show the success message
					$('button#add_user').removeClass('btnDisable').addClass('btn-submit');
					$('button#add_user').removeAttr('disabled'); //enable Add Candidate button
					
					$('.alert').removeClass('errors').addClass('success');
					$('.alert.success').html(FbUserFirstName+' has been added as a potential candidate');					
						
				}); 
			  		
			}else{
				//nullify the ajax loader image and empty the autosuggest text box
				$('#candidate').val('');
				$('.alert').html('');
				return false;	
			}
	});
	
	//event that checks whether potential candidate list is ready and user is allowed to go to the Create Candidate page 
	$('#create_candidate').click(function(event) {		
		//show the ajax loader image
		$('.alert').html('<img src="<?php echo base_url();?>images/ajax-loader.gif"/>');
		
		var fbUserId = $(userId).val();		
		
        event.preventDefault();
		
        $.ajax({
            type: "GET",
            url: "<?php echo base_url();?>/facebooker/checkSuggestionList/"+fbUserId,            
            success: function(data) {
				if(data=="false"){
					//show the failure message
					$('.alert').removeClass('success').addClass('errors');
					$('.alert.errors').html('ERROR : Potential candidate list is not ready');					
				}
				else
					window.location.href='<?php echo base_url();?>/candidate/addcandidate';	
			}
        });
    });
	
	
});
</script>

<div class="page">
<div class="content-widget">
	<div class="wrapper-widget">
		<div class="lftPan">
			<div class="lft-block">
				<?php echo $leftPanelCount; ?>
				<?php // echo $helpFriendData; ?>
			</div>
		</div>
		<div class="rghtPan">
			<span class="alert"></span>
			<h4>Rishtey Connect helps people find great matches for their loved ones within their own networks.</h4>
			<p>Based on their Facebook profiles, we think your following friends are single and eligible. Please remove the ones who are married or whom you dont want to refer to your network.</p>
			<p class="line">&nbsp;</p>
			<div class="network-wrapper">
			
				<?php if(count($records) > 0) {?>
				<ul class="network-friendz" id="slist">			
				
				<?php $i=1; ?>
				
				<?php foreach($records as $row): ?>
					<li id="th-<?php echo $row['fb_user_id']; ?>" class=<?php echo ($i%5==0) ? "last": "" ?>>
					
					<span class="close"><a class="delete" id="<?php echo $row['fb_user_id'];?>" name="<?php echo $row['name'];?>" href="JavaScript:void(0);" title="Close"><img src="<?php echo base_url(); ?>images/close-ico.png" alt="Close" /></a></span>
					
					<img width="107" height="107" src="https://graph.facebook.com/<?php echo $row['fb_user_id'];?>/picture?width=107&height=107" alt="<?php echo $row['name']; ?>" title="<?php echo $row['name']; ?>" />		   
					
					</li>
					
					<input id="fb-<?php echo $row['fb_user_id'] ?>" type="hidden" name="fb_id" value="<?php echo $row['fb_user_id'] ?>" class="fb_id"/>
					
				<?php $i++; ?>
					
				<?php endforeach; ?>				
				
				</ul>
				<?php }else{?>
					
					<ul class="network-friendz" id="slist" style="padding-top:4px!important;">
						<div><strong>No eligible friends are there</strong></div>
					</ul>
					<br />
					
				<?php }?>
				
				<h4>Did we miss someone? Please add any other Facebook who is single and eligible</h4>
					<form id="form1" name="form1" class="add-frnd" method="post" action="">
						<label>
							<input type="text" name="candidate" id="candidate" value="Start typing FB Friends name" onfocus="if(this.value=='Start typing FB Friends name') {this.value='';}" onblur="if(this.value=='') {this.value='Start typing FB Friends name'}" style="width:260px;" />
						</label>
						<button id="add_user" class="btn-submit" type="button" title="Add Candidate"><span><span>Add Candidate</span></span></button>
					</form>
			</div>
			
			<?php if(count($records) > 0) {?>
				<button class="btn-done" name="done" id="done" type="button" title="Done"><span><span>Done</span></span></button>
			<?php }else{?>
				<button style="display:none;" class="btn-done" name="done" id="done" type="button" title="Done"><span><span>Done</span></span></button>
			<?php }?>
			
			<p class="clearSM">&nbsp;</p>
			
		</div>
		
	</div>
	<div class="bg-leaf"><img src="<?php echo base_url();?>images/leaf-bg-no-repeat.png" alt="" /></div>
	</div>
</div>  
