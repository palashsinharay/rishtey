var errorCounter = new Array;
$(document).ready(function() {

$('#fName').blur(function() {
	validateStr($(this).attr("id"),'First Name')
			});
$('#lName').blur(function() {
	validateStr($(this).attr("id"),'Last Name')
			});
$('#gender').change(function() {
			var id = $(this).attr("id");
			var gender = $('#'+id).val();
			//alert(gender);
			if(gender=="empty"){
							if($('#'+'error_'+id)) $('#'+'error_'+id).remove(); 
							
							$('#'+id).parent().append("<span id='error_"+id+"' class='error'></span>");
							$('#error_'+id).html('Select a valid gender');
							$('#error_'+id).show('slow');
							errorCounter['error_'+id] = false;
							//$('#'+id).focus();
							return false;
							
						}else{
							
							
							errorCounter['error_'+id] = true;
							clearError('error_'+id);
							return true;
						}
			
			});
$('#relationship').change(function() {
			var id = $(this).attr("id");
			var value = $('#'+id).val();
			//alert(gender);
			if(value==0){
							if($('#'+'error_'+id)) $('#'+'error_'+id).remove(); 
							
							$('#'+id).parent().append("<span id='error_"+id+"' class='error'></span>");
							$('#error_'+id).html('Select a valid relationship');
							$('#error_'+id).show('slow');
							errorCounter['error_'+id] = false;
							//$('#'+id).focus();
							return false;
							
						}else{
							
							
							errorCounter['error_'+id] = true;
							clearError('error_'+id);
							return true;
						}
			
			});						
$('#location').blur(function() {
	location($(this).attr("id"),'Location')
			});			
$('#religion').change(function() {
	var id = $(this).attr("id");
	var value = $('#religion').val();
	if(value==0){
							if($('#'+'error_'+id)) $('#'+'error_'+id).remove(); 
							
							$('#'+id).parent().append("<span id='error_"+id+"' class='error'></span>");
							$('#error_'+id).html('Select a valid Religion');
							$('#error_'+id).show('slow');
							errorCounter['error_'+id] = false;
							//$('#'+id).focus();
							return false;
							
						}else{
							
							
							errorCounter['error_'+id] = true;
							clearError('error_'+id);
							return true;
						}

});

$('#mTongue').change(function() {
	var id = $(this).attr("id");
	var value = $('#mTongue').val();
	if(value==0){
							if($('#'+'error_'+id)) $('#'+'error_'+id).remove(); 
							
							$('#'+id).parent().append("<span id='error_"+id+"' class='error'></span>");
							$('#error_'+id).html('Select a valid Mother Tongue');
							$('#error_'+id).show('slow');
							errorCounter['error_'+id] = false;
							//$('#'+id).focus();
							return false;
							
						}else{
							
							
							errorCounter['error_'+id] = true;
							clearError('error_'+id);
							return true;
						}
	
});
$('#caste').blur(function() {
	validateStr($(this).attr("id"),'Caste');
	
			});
$('#hEducation').change(function() {
	var id = $(this).attr("id");
	var value = $('#hEducation').val();
	if(value==0){
							if($('#'+'error_'+id)) $('#'+'error_'+id).remove(); 
							
							$('#'+id).parent().append("<span id='error_"+id+"' class='error'></span>");
							$('#error_'+id).html('Select a valid Education');
							$('#error_'+id).show('slow');
							errorCounter['error_'+id] = false;
							//$('#'+id).focus();
							return false;
							
						}else{
							
							
							errorCounter['error_'+id] = true;
							clearError('error_'+id);
							return true;
						}

});
$('#mcaste').change(function() {
	var id = $(this).attr("id");
	valiadteMulti (id,'Caste');
	
});
$('#dob').blur(function() {
	var id = $(this).attr("id");
	var value = $('#dob').val();
	if(validateDDMMY(id))
	{
		$.ajax({
		            type: "POST",
		            data: {dob:value},
		            url: "http://"+top.location.host+"candidate/ajax_dob_check",            
		            success: function(data) {
						if(data=="true"){
							//alert("match");
							errorCounter['error_'+id] = true;
							clearError('error_'+id);
							return true;
							}else{
								//alert("not match");
								if($('#'+'error_'+id)) $('#'+'error_'+id).remove(); 
							
							$('#'+id).parent().after("<span id='error_"+id+"' class='error'></span>");
							$('#error_'+id).html('Age should be more than 18 years');
							$('#error_'+id).show('slow');
							errorCounter['error_'+id] = false;
							//$('#'+id).focus();
							return false;
								
							}
								
					}
		    });
		 }		
	
});
$('#profession').change(function() {
	var id = $(this).attr("id");
	var value = $('#profession').val();
	if(value==0){
							if($('#'+'error_'+id)) $('#'+'error_'+id).remove(); 
							
							$('#'+id).parent().append("<span id='error_"+id+"' class='error'></span>");
							$('#error_'+id).html('Select a valid Profession');
							$('#error_'+id).show('slow');
							errorCounter['error_'+id] = false;
							//$('#'+id).focus();
							return false;
							
						}else{
							
							
							errorCounter['error_'+id] = true;
							clearError('error_'+id);
							return true;
						}
	
});	
$("#mTongueMulti").change(function(){
	//alert($('select#mTongueMulti').val());
	var id = $(this).attr("id");
	valiadteMulti (id,'Mother tongue');
});
$("#maritalStatus").change(function(){
	
	var id = $(this).attr("id");
	valiadteMulti (id,'Marital Status');
});
$("#mreligion").change(function(){
	//alert($('select#mTongueMulti').val());
	var id = $(this).attr("id");
	valiadteMulti (id,'Religion');
});
$("#mEducation").change(function(){
	//alert($('select#mTongueMulti').val());
	var id = $(this).attr("id");
	valiadteMulti (id,'Education');
});
$("#mprofession").change(function(){
	//alert($('select#mTongueMulti').val());
	var id = $(this).attr("id");
	valiadteMulti (id,'Profession');
});				
function valiadteMulti (id,fieldname) {
  						var options = $('select#'+id).val();
  						
  						if(options == null){
							if($('#'+'error_'+id)) $('#'+'error_'+id).remove(); 
							
							$('#'+id).parent().after("<span id='error_"+id+"' class='error'></span>");
							$('#error_'+id).html('Please select '+fieldname);
							$('#error_'+id).show('slow');
							errorCounter['error_'+id] = false;
							//$('#'+id).focus();
							return false;
						}else{
							errorCounter['error_'+id] = true;
							clearError('error_'+id);
							return true;
						}
}

function validateStr(id,fieldname){
				
				
						var name = $('#'+id).val();	
						var myRegxp = /^(([A-za-z]+[\s]{1}[A-za-z]+)|([A-Za-z]+))$/gim;
						
						if(myRegxp.test(name) == false){
							if($('#'+'error_'+id)) $('#'+'error_'+id).remove(); 
							
							$('#'+id).parent().after("<span id='error_"+id+"' class='error'></span>");
							$('#error_'+id).html('Please enter a valid '+fieldname);
							$('#error_'+id).show('slow');
							errorCounter['error_'+id] = false;
							//$('#'+id).focus();
							return false;
						}else{
							errorCounter['error_'+id] = true;
							clearError('error_'+id);
							return true;
						}
				
			
}

function location(id,fieldname){
				
				
						var name = $('#'+id).val();	
						var myRegxp = /[a-z0-9\s,]+/i;
						
						if(myRegxp.test(name) == false){
							if($('#'+'error_'+id)) $('#'+'error_'+id).remove(); 
							
							$('#'+id).parent().after("<span id='error_"+id+"' class='error'></span>");
							$('#error_'+id).html('Please enter a valid '+fieldname);
							$('#error_'+id).show('slow');
							errorCounter['error_'+id] = false;
							//$('#'+id).focus();
							return false;
						}else{
							errorCounter['error_'+id] = true;
							clearError('error_'+id);
							return true;
						}
				
			
}

function validateDDMMY (id) {
  					
  						var date = $('#'+id).val();	
						var myRegxp = /([3][0,1]|[0-2]\d)-([1][0-2]|[0]\d)-(\d\d\d\d)/g;
						
						if(myRegxp.test(date) == false){
							if($('#'+'error_'+id)) $('#'+'error_'+id).remove(); 
				
							$('#'+id).parent().after("<span id='error_"+id+"' class='error'></span>");
							$('#error_'+id).html('Please enter a valid date (dd-mm-yyyy)');
							$('#error_'+id).show('slow');
							errorCounter['error_'+id] = false;
							//$('#'+id).focus();
							return false;
						}else{
							errorCounter['error_'+id] = true;
							clearError('error_'+id);
							return true;
						}
}
				
function clearError(id){  
   $('#'+id).hide('slow');
   $('#'+id).html('');
   return true;
}

});

function validateCandidate(){	
	$('#fName').blur();
	$('#lName').blur();
	$('#dob').blur();
	$('#gender').change();
	$('#relationship').change();
	$('#location').blur();
	$('#mTongue').change();
	$('#religion').change();
	$('#caste').blur();
	$('#hEducation').change();
	$('#profession').change();
	
	for(var index in errorCounter) {
		 //alert(index+" "+errorCounter[index]);
		 
		 if(errorCounter[index] == false){
					  return false;
				  } 
		}
	errorCounter.length = 0;	
	return true;	
}

function validateMatchPreferences(){	
	$("#mTongueMulti").change();
	$("#maritalStatus").change();
	$("#mreligion").change();
	$("#mEducation").change();
	$("#mprofession").change();
	$('#mcaste').change();
	
	
	for(var index in errorCounter) {
		 //alert(index+" "+errorCounter[index]);
		 
		 if(errorCounter[index] == false){
					  return false;
				  } 
		}
	errorCounter.length = 0;	
	return true;	
}