$(document).ready(function() { 
	//call the submit event handler of the "image upload" form
	$('#UploadForm').on('submit', function(e) {
		var a_id = $('input[name="a_id"]').val();

		var fb_user_id = $('input[name="fb_user_id"]').val();
		
		$("a#"+a_id).html('<img style="position:absolute; width:31px; height:31px; display:block; left:38px; top:40px;" src="http://'+location.host+'/images/ajax-loader.gif" alt=""/>');
		//return false;
		
		e.preventDefault();
		$('#SubmitButton').attr('disabled', ''); // disable upload button
		$('#CancelButton').attr('disabled', ''); // disable cancel button
		$('#SubmitBioDataButton').attr('disabled', ''); // disable upload biodata button
		
		$('#candidate_update').attr('disabled', ''); // disable save button on dashboard
		$('#reset').attr('disabled', ''); 			// disable reset button on dashboard
		
		$('#goToCandi').attr('disabled', ''); // disable back button
		$('#goToPref').attr('disabled', ''); // disable next button
		
		//show uploading message
		$("a.delete.d").hide();
		$("a.delete.pimg").hide();
		$("a#"+a_id+".delete.pimg").html('<img src="http://'+location.host+'/images/ajax-loader.gif" alt=""/>');		
		
		//return false;
		
		$(this).ajaxSubmit({			
			target: 'a#'+a_id,
			success:  afterSuccess //call function after success
		});
	});


	//call the submit event handler of the "biodata upload" form
	$('#UploadBioDataForm').on('submit', function(e) {	
		
		var fb_user_id = $('input[name="fb_user_id"]').val();		
		
		e.preventDefault();
		$('#SubmitBioDataButton').attr('disabled', ''); // disable upload button
		
		$('#goToCandi').attr('disabled', ''); // disable back button
		$('#goToPref').attr('disabled', ''); // disable next button
		
		$('#candidate_update').attr('disabled', ''); // disable save button on dashboard
		$('#reset').attr('disabled', ''); 			// disable reset button on dashboard
		
		//disable the change/delete links
		$("a.delete.d").hide();
		$("a.delete.pimg").hide();
		
		//show uploading message
		//$("#output").html('<div style="padding:10px"><img src="http://107.20.134.141/images/ajax-loader.gif" alt="Please Wait"/> <span>Uploading...</span></div>');
		$("#biodata_msg").show();
		$("#biodata_msg").html('<div style="padding:10px"><img src="http://'+location.host+'/images/ajax-loader.gif" alt=""/></div>');
		$(this).ajaxSubmit({
			target: '#biodata_msg',			
			success:  afterSuccessBioData //call function after success
		});
	});




}); 

function afterSuccess()  {	
	$('#UploadForm').resetForm();  						// reset form
	$('#SubmitButton').removeAttr('disabled'); 			//enable submit button
	$('#CancelButton').removeAttr('disabled'); 			//enable submit button
	$('#SubmitBioDataButton').removeAttr('disabled'); // enable upload biodata button
	
	$('#goToCandi').removeAttr('disabled'); // enable back button
	$('#goToPref').removeAttr('disabled'); // enable next button
	
	$('#candidate_update').removeAttr('disabled'); // enable save button on dashboard
	$('#reset').removeAttr('disabled'); 			// enable reset button on dashboard
	
		var a_id = $('input[name="a_id"]').val();
		var fb_user_id = $('input[name="fb_user_id"]').val();
		
		//image uploaded successfully
		if($('#'+a_id).find('img').attr('title')){
			$('#'+fb_user_id+'__'+a_id).remove();
			
			if(a_id!=1){	
				$('#'+a_id).after('<a href="JavaScript:void(0);" title="Delete Picture" class="delete d" id="'+fb_user_id+'__'+a_id+'">Delete</a>');
				$("a#"+a_id+".delete.pimg").html('Change');
				$("a.delete.d").show();
				$("a.delete.pimg").show();
				
			}else{
				$("a#"+a_id+".delete.pimg").html('Change');

				$("a.delete.d").show();
				$("a.delete.pimg").show();
			}

		
		}else{
			//image upload failed
			$('#'+fb_user_id+'__'+a_id).remove();
			
			$("a#"+a_id+".delete.pimg").html('Change');
			$("a.delete.d").show();
			$("a.delete.pimg").show();
		}

		//hide the "upload image" section
		$('#upld_box').hide();

} 


function afterSuccessBioData()  {	
	$('#UploadBioDataForm').resetForm();  // reset form
	$('#SubmitBioDataButton').removeAttr('disabled'); //enable submit button	
	
	$('#goToCandi').removeAttr('disabled'); // enable back button
	$('#goToPref').removeAttr('disabled'); // enable next button
	
	$('#candidate_update').removeAttr('disabled'); // enable save button on dashboard
	$('#reset').removeAttr('disabled'); 			// enable reset button on dashboard
	
	//show the change/delete links
	$("a.delete.d").show();
	$("a.delete.pimg").show();
	
	$('.file .button').text(" ");
		//var fb_user_id = $('input[name="fb_user_id"]').val();

		//trigger the "onblur" event for the dob field on "Candidate Profile" tab for the auto fillup of the "Age from" and "to" field on the "Match Preferences" tab
		$('#dob').trigger('blur');
		//enable the "Match Prteferences" tab
		$( "#tabs" ).tabs( "enable", 2);


		

} 