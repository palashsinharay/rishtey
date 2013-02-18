var errorCounter = new Array;
$(document).ready(function() {

$('#fName').blur(function() {
	validateStr($(this).attr("id"),'First Name')
			});
$('#lName').blur(function() {
	validateStr($(this).attr("id"),'Last Name')
			});
$('#location').blur(function() {
	validateStr($(this).attr("id"),'Location')
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
	
	/*
	validateStr(id,'Religion');
		$.ajax({
						type: "POST",
						data: {religion:value},
						url: "http://107.20.134.141/rishtey-connect/index.php/candidate/ajax_religion_check",            
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
								$('#error_'+id).html('Please enter a valid Religion');
								$('#error_'+id).show('slow');
								errorCounter['error_'+id] = false;
								//$('#'+id).focus();
								return false;
									
								}
									
						}
				});*/
			
	
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
	
	/*
	validateStr(id,'Mother tongue');
		$.ajax({
						type: "POST",
						data: {mTongue:value},
						url: "http://107.20.134.141/rishtey-connect/index.php/candidate/ajax_lan_check",            
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
								$('#error_'+id).html('Please enter a valid Mother Tongue');
								$('#error_'+id).show('slow');
								errorCounter['error_'+id] = false;
								//$('#'+id).focus();
								return false;
									
								}
									
						}
				});*/
			
	
});
$('#caste').blur(function() {
	var id = $(this).attr("id");
	var value = $('#caste').val();
	validateStr(id,'Caste');
	$.ajax({
		            type: "POST",
		            data: {mcaste:value},
		            url: "http://107.20.134.141/rishtey-connect/index.php/candidate/ajax_cast_check",            
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
							$('#error_'+id).html('Please enter a valid Caste');
							$('#error_'+id).show('slow');
							errorCounter['error_'+id] = false;
							//$('#'+id).focus();
							return false;
								
							}
								
					}
		    });
	
	
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
	
	//validateStr(id,'Mother tongue');
	/*
	$.ajax({
						type: "POST",
						data: {hEducation:value},
						url: "http://107.20.134.141/rishtey-connect/index.php/candidate/ajax_edu_check",            
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
								$('#error_'+id).html('Please enter a valid Education');
								$('#error_'+id).show('slow');
								errorCounter['error_'+id] = false;
								//$('#'+id).focus();
								return false;
									
								}
									
						}
				});*/
			
	
});
$('#mcaste').change(function() {
	var id = $(this).attr("id");
	valiadteMulti (id,'Caste');
	/*
	$.ajax({
						type: "POST",
						data: {mcaste:value},
						url: "http://107.20.134.141/rishtey-connect/index.php/candidate/ajax_cast_check",            
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
								$('#error_'+id).html('Please enter valid Caste');
								$('#error_'+id).show('slow');
								errorCounter['error_'+id] = false;
								//$('#'+id).focus();
								return false;
									
								}
									
						}
				});		*/
	
	
});
$('#dob').blur(function() {
	var id = $(this).attr("id");
	var value = $('#dob').val();
	if(validateDDMMY(id))
	{
		$.ajax({
		            type: "POST",
		            data: {dob:value},
		            url: "http://107.20.134.141/rishtey-connect/index.php/candidate/ajax_dob_check",            
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

$("#salary").blur(function(){
	var id = 'salary';
	if($(this).val() == " ")
	$("#salary").val(presal);
	var salary = $('#salary').val();	
	var myRegxp = /\d{1,3}(?=(\d{3})+(?!\d))/g;
	
	if(myRegxp.test(salary) == false){
		if($('#'+'error_'+id)) $('#'+'error_'+id).remove(); 
		
		$('#'+id).parent().after("<span id='error_"+id+"' class='error'></span>");
		$('#error_'+id).html('Please enter a valid salary');
		$('#error_'+id).show('slow');
		errorCounter['error_'+id] = false;
		//$('#'+id).focus();
		return false;
	}else{
		errorCounter['error_'+id] = true;
		clearError('error_'+id);
		
			function addCommas(nStr)
					{
					nStr += '';
					x = nStr.split('.');
					x1 = x[0];
					x2 = x.length > 1 ? '.' + x[1] : '';
					var rgx = /(\d+)(\d\d+)(\d{3})/;
					while (rgx.test(x1)) {
					x1 = x1.replace(rgx, '$1' + ',' + '$2'+ ',' + '$3');
					}
					return x1 + x2;
					}
			$("#salary").val(addCommas($(this).val()));
			return true;
		}
	
	//alert("ddd");
				

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
	
/*
	validateStr(id,'Profession');
	$.ajax({
		            type: "POST",
		            data: {profession:value},
		            url: "http://107.20.134.141/rishtey-connect/index.php/candidate/ajax_pro_check",            
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
							$('#error_'+id).html('Please enter a valid Profession');
							$('#error_'+id).show('slow');
							errorCounter['error_'+id] = false;
							//$('#'+id).focus();
							return false;
								
							}
								
					}
		    });*/
		
			
			
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

});

function validateCandidate(){	
	$('#fName').blur();
	$('#lName').blur();
	$('#dob').blur();
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