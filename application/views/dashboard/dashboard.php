<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,400,600,700' rel='stylesheet' type='text/css' />
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
<script src="<?php echo base_url();?>js/jquery.min.js" type="text/javascript"></script>

<!-- Plugin For jQuery Select Box Start Here -->
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.selectBox.js"></script>
<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>css/jquery.selectBox.css" />
<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>css/popup.css" />
<!-- Plugin For jQuery Select Box End Here -->

<script src="<?php echo base_url();?>js/common.js" type="text/javascript"></script>

<script src="http://connect.facebook.net/en_US/all.js"></script>
<!--<script src="http://code.jquery.com/ui/1.9.1/jquery-ui.js"></script>-->

<script src="<?php  echo base_url();?>js/jquery.form.js"></script>
<script src="<?php  echo base_url();?>js/aj_file_upload.js"></script>
<script src="<?php  echo base_url();?>js/validationdashboard.js" type="text/javascript"></script>

<style>
	.ui-tabs-nav.ui-widget-header {
        border:none!important;
        box-shadow: none!important;
	}
	.ui-tabs .ui-tabs-nav li{
		border: none;
	}
	.ui-tabs .ui-tabs-nav, .ui-tabs .ui-tabs-nav li a{
		padding:0;
	}
	.ui-datepicker .ui-datepicker-header{
		background: none repeat scroll 0 0 #93A833!important;
	}
</style>

<script type="text/javascript">
//history.go(1);
var chatMatch = 'ABC' ;
var hf1,hf2,ht1,ht2,presal;
var age_counter = 0,height_counter = 0; //counter for setting age and height default value based on candidates profile setting tabs's value' 
jQuery(document).ready(function() {
     
// for chat 
    $(".chatPopup").click(function(){
		$(".chatCont").slideToggle();
	});
	
//$( "#tabs" ).tabs();
$( "#tabs" ).tabs({ active: 0 });

$( "#dob" ).datepicker({ dateFormat: "dd-mm-yy" ,
            changeYear: true,maxDate: -1, 
            yearRange: "-62:+0", 
            showOn: "both",
            buttonImage: "<?php echo base_url();?>images/ico-cal.png",
            buttonImageOnly: true,
            buttonText: "Date of birth",
            onClose: function(dateText, inst) {
                   $('#dob').blur();
            } 
});
			
//on click empty the salary field and and store current text value in presal (presal is used on validationdashboard.js to restore onblur if text value empty)
$("#salary").click(function(){
        function removeCommas(str) {
        return(str.replace(/,/g,''));
        }
	presal = removeCommas($("#salary").val());
	$("#salary").val(" ");
});		 
		
		
//prepare the autosuggest array for Highest Education
var pro = new Array();
<?php  foreach($this->config->item('profession') as $key => $option):?>
	    	pro[<?php echo $key;?>] = '<?php echo $option;?>';
<?php endforeach;?>

$("#profession").autocomplete({
    source: pro
});

		//conditionally show/hide the recommendation area and relation box if profile exists
		$('span#cn_err #rec_candidate').live("click",function(){
			$('#sfr_g').show(); 	
				
			$("span#cn_err #candidate_rec_msg").show();		
			$("span#cn_err #candidate_relation").show();
			
			$("button#Candidate_submit span span").text("Recommend");
			$("button.btn-done").show();
			
		});	
		
		$('span#cn_err #mail_guardian').live("click",function(){
			$('#sfr_g').show(); 	
				
			$("span#cn_err #candidate_rec_msg").hide();		
			$("span#cn_err #candidate_relation").hide();
			
			$("button#Candidate_submit span span").text("Send Mail");
			$("button.btn-done").show();
			
		});

//get candidate data based on cuserId select box field // START
$('#getpsetting').live("click", function () {
		$('#candidate_msg').html("");
        $('#mpre_msg').html("");
		$('.error').html("");
		$('.error').hide();
		$('#currentCandidate').show();
		age_counter = 0;
		height_counter = 0;	   
		
		var userid = $('#cuserId').val();
		
                //ajax call to get candidates match data for match tab
                $.ajax({
                      type: "POST",
                      url:"<?php echo base_url();?>/dashboard/getMatchId/"+userid,
                      success: function(data){
                              $('#tabs-2').html(data);
                              //alert(data);
                      }
                });
                      
                //ajax call to to show selected candidate image and name on Network Status palen
                $.ajax({
                      type: "POST",
                      url:"<?php echo base_url();?>/dashboard/getUserImgName",
                      success: function(data){ //alert(data); return false;
                              $('#selectedCandidate').html(data);
                             
                      }
                });
                   
          //ajax call to get new match data for update tab
		  $.ajax({
			type: "POST",
			url:"<?php echo base_url();?>/dashboard/newMatchUpdateAjax/"+userid,
			success: function(data){
				$('#newMatchUpdate').html(data);
				//alert(data);
			}
			});
                
          //ajax call to get new interest data for update tab
		  $.ajax({
			type: "POST",
			url:"<?php echo base_url();?>/dashboard/showInterestUpdateAjax/"+userid,
			success: function(data){
				$('#interestUpdate').html(data);
				//alert(data);
			}
			});
                
          //ajax call to get new interest data for update tab
		  $.ajax({
			type: "POST",
			url:"<?php echo base_url();?>/dashboard/showInterestReceivedUpdateAjax/"+userid,
			success: function(data){
				$('#interestReceivedUpdate').html(data);
				//$('#interests').html(data);
                //alert(data);
			}
			});
			//        ajax call to get new interest data for interest tab
			//		  code commented on 24th jan 2013 
			//		  $.ajax({
			//			type: "POST",
			//			url:"<?php //echo base_url();?>/dashboard/getInterestMsg/"+userid,
			//			success: function(data){
			//				
			//				$('#interests').html(data);
			//			}
			//			});
                        
          //ajax call to get all interest interaction for interest tab
		  $.ajax({
			type: "POST",
			url:"<?php echo base_url();?>dashboard/getMessage/"+userid,
			success: function(data){
				//alert(chatMatch);
                                if(data != null)
                                {
                                 //alert(data);
                        var inter = JSON.parse(data);
				//$('#interaction').html(data);
                                //alert(inter);
                                $('#interaction').html('');
                                for (var key in inter) {
                                var obj = inter[key];
                                // chatMatch = '';
                                var chatMatch = $.ajax({
                                   type:"POST",
                                   url:"<?php echo base_url();?>dashboard/getFbidToNameAjax/"+key,
                                   async:false,
                                   success: function(data){
                                   return data;
                                 
                                }
                               }).responseText;
                               var firstImage = $.ajax({
                                   type:"POST",
                                   url:"<?php echo base_url();?>/dashboard/getImageTag1/"+key,
                                   async:false,
                                   success: function(data){
                                   return data;
                                 
                                }
                               }).responseText;
                               //alert(chatMatch);
                                //$('#interaction').append("<div class='spacer'></div><br><img src='https://graph.facebook.com/"+key+"/picture?width=107&amp;height=107' width='107' height='107' alt='"+key+"' class='left' /><div class='left' style='margin-left:10px; width:550px;'><div id='chatPopup-"+key+"' class='chatPopup chatPopupArrowDown' onclick=JavaScript:chatOpen("+key+")><span>"+chatMatch+"</span></div><div id='chatCont-"+key+"' class='chatCont'></div></div>");
                                $('#interaction').append("<div class='spacer'></div><br><img src='"+firstImage+"' width='107' height='107' alt='"+key+"' class='left' /><div class='left' style='margin-left:10px; width:550px;'><div id='chatPopup-"+key+"' class='chatPopup chatPopupArrowDown' onclick=JavaScript:chatOpen("+key+")><span>"+chatMatch+"</span></div><div id='chatCont-"+key+"' class='chatCont'><div id='chatPanl-"+key+"'></div></div></div>");
                                //alert(obj);
                                // var output = '';
                                var output2 = '';
                                // var list = '';
                                var list2 = '';
                                for (var prop in obj) {
                                    //alert(obj[prop].cid+" "+key);
                                //alert(obj.cid_matched + " to " + obj.cid + " : " + obj.interest_message);
                                   if(obj[prop].cid == key){
                                   //output2 = "<img src='"+obj[prop].image+"' width='35' height='35' alt='"+obj[prop].cid_name+obj[prop].reciver_name+"' class='left' /><span>"+obj[prop].cid_name+"'s Gaurdian "+obj[prop].reciver_gaurdian_name+"</span><span class='chat left'>"+obj[prop].interest_message+"</span><span class='time right'>"+obj[prop].created_at+"</span><div class='clear'></div>";  
                                   output2 = "<span>"+obj[prop].cid_name+"'s Gaurdian "+obj[prop].reciver_gaurdian_name+": </span><span class='chat left'>"+obj[prop].interest_message+"</span><span class='time right'>"+obj[prop].created_at+"</span><div class='clear'></div>";  
                                   }else{
                                   //output2 = "<img src='"+obj[prop].image+"' width='35' height='35' alt='"+obj[prop].cid_name+obj[prop].reciver_name+"' class='left' /><span>"+obj[prop].cid_name+"</span><span class='chat left'>"+obj[prop].interest_message+"</span><span class='time right'>"+obj[prop].created_at+"</span><div class='clear'></div>";   
                                   output2 = "<span class='left'>"+obj[prop].cid_name+": </span><span class='chat left'>"+obj[prop].interest_message+"</span><span class='time right'>"+obj[prop].created_at+"</span><div class='clear'></div>";
                                   }
                                   
                                   // list +="<li>"+output+"</li>";
                                   list2 +="<div class='chatIndivisual'>"+output2+"</div>"; 
                                   // $("#chat-"+key).html(list);
                                   $("#chatPanl-"+key).html(list2);
                                   var sender =  obj[prop].sender;
                                   var reciver =  obj[prop].reciver;
                                }
                                var frm = "<form id='chatFrm-"+key+"' action='' method='get'><textarea id='chatMsg-"+key+"' name='' style='width: 520px; height: 53px;'></textarea><div class='clear'></div><br><button id='chatSend-"+key+"' class='btn-submit' type='button' title='Send' onclick=JavaScript:sendChat("+reciver+","+sender+","+key+") ><span><span>Send Message</span></span></button><br><br><div id='chatloader-"+key+"'></div></form>";
                                $("#chatCont-"+key).append(frm);
                                //alert(output);
                             }
								

			}
                        }
			});
                        
          //ajax call to get new interest data for update tab
		  $.ajax({
			type: "POST",
			url:"<?php echo base_url();?>/dashboard/recommendationUpdateAjax/"+userid,
			success: function(data){
				$('#recommendationUpdate').html(data);
				//alert(data);
			}
			});
          //ajax call to get new interest data for update tab
		  $.ajax({
			type: "POST",
			url:"<?php echo base_url();?>/dashboard/blockedUpdateAjax/"+userid,
			success: function(data){
				$('#blockedUpdate').html(data);
				//alert(data);
			}
			});
                        
		  //ajax call to get candidate profile tabs's data
		  $.ajax({
		    type: "POST",
			data: {userid:userid},
			url: "<?php echo base_url();?>/dashboard/getProfileDetails",            
			success: function(data) {
				if(data)
				{
						$("#notGuardian").html("");
                                                // $( "#tabs" ).tabs( "enable", 0);
                                                $( "#tabs" ).tabs( "enable", 1);
                                                $( "#tabs" ).tabs( "enable", 2);
                                                $( "#tabs" ).tabs( "enable", 3);
                                                $( "#tabs" ).tabs( "enable", 4);
                                               
                                                var prodetails = JSON.parse(data);
						$('#panelname').html(prodetails.uFname+" "+prodetails.uLname);
						
						if(prodetails.uFname==null){
                                                    $( "#tabs" ).tabs( "disable", 1);
                                                    $( "#tabs" ).tabs( "disable", 2);
                                                    return false;
						}
                                                if(prodetails.uStatus==0){
                                                    $( "#tabs" ).tabs( "disable", 1);
                                                    $( "#tabs" ).tabs( "disable", 2);
                                                    $( "#tabs" ).tabs( "disable", 3);
                                                    $( "#tabs" ).tabs( "disable", 4);
                                                    $("#notGuardian").html("<p class='errors'><strong>A profile for "+prodetails.uFname+" "+prodetails.uLname+" has been created by "+prodetails.uGuardianName+". You can either:</strong></p><p><input type='radio' value='"+prodetails.other_fb_user_id+"' id='rec_candidate' name='guardian'>Recommend <strong>"+prodetails.uFname+"</strong> if <span name='fb_guardian' id='"+prodetails.guardian_id+"'>you</span> want to help the candidate find a match in your circle.</p>  <div id='candidate_rec_msg' style='display:none; '><textarea class='textarea inputWidth' id='candidate_rec_msg'>A Short Recommendation</textarea></div> <div id='candidate_relation' style='display:none; margin-top:2px;'><select class='selectBox inputWidthDropdown2' name='select_candidate_relation' id='select_candidate_relation'>"+prodetails.relOptStr+"</select><br /></div> <p><input type='radio' value='' id='mail_guardian' name='guardian'>You can also request <strong>"+prodetails.uGuardianName+"</strong> to make you the guardian instead</p> <input type='hidden' value='"+prodetails.guardian_fk_loc_fb_id+"' id='guardian_loc_fb_id' name='guardian_loc_fb_id'><button style='display:none; margin:6px 0 0 0;' title='Create Candidate' type='submit' class='btn-done' id='Candidate_submit' name='Candidate_submit'><span><span>Create Candidate</span></span></button>");
                                                    $("#recommendationUpdate").html("");
                                                    $("#interestUpdate").html("");
                                                    $("#blockedUpdate").html("");
                                                    $("#newMatchUpdate").html("");
                                                    $("#interestReceivedUpdate").html("");
                                                    $( "#tabs" ).tabs( "option", "active", 0 );
                                                    //$( "#tabs" ).tabs( "disable", 0);
                                                    
                                                    return false;
                                                }
						
						$("#fName").val(prodetails.uFname);
						$("#lName").val(prodetails.uLname);
						//$('#gender').val(prodetails.uGender);
						$('#gender').selectBox('value',prodetails.uGender);
						
						$('#dob').val(prodetails.uBirthday);
						
						$('#relationship').selectBox('value',prodetails.uRelationship);
						
						$('#religion').selectBox('value',prodetails.uReligion);
						$('#mTongue').selectBox('value',prodetails.uMtongue);
						$('#caste').val(prodetails.uCast);
						$('#heightFt').selectBox('value',prodetails.uHeightFt);
						$('#heightInch').selectBox('value',prodetails.uHeightInch);
						
						//$('#hEducation').selectBox('value',prodetails.uHeight);
						$('#location').val(prodetails.uLocation);
						$('#hEducation').selectBox('value',prodetails.uHeducation);
                        $('#hEducationDes').selectBox('value',prodetails.uhEducationDes);
						$('#profession').selectBox('value',prodetails.uProfession);
                        $('#professionDes').selectBox('value',prodetails.uprofessionDes);
						
						$('#biodata_msg').attr('style','color:green!important');
						
						if(prodetails.biodata!=''){
							$('#biodata_msg').html('<div id="biodataDiv"><a href="<?php echo base_url()?>/application/files/candidate_biodata/'+prodetails.biodata+'">'+prodetails.biodata+'</a>&nbsp;'+'<a id="'+userid+'" class="del_biodata" href="javascript:void(0);"><img alt="Delete" src="<?php echo base_url()?>/images/close-ico.png"></a></div>');
						}else{
							$('#biodata_msg').html('');
						}
						
						$('#biodata_msg').show();
						
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
						$('#salary').val(addCommas(prodetails.uAsalary));
						$('#sRecommendation').val(prodetails.uSrecommendation);
						$('#relation').selectBox('value',prodetails.relation);
						
						//if(prodetails.status==1)
						$('a#1 > img').attr('src',"http://<?php echo $bucket;?>.s3.amazonaws.com/files/profile_images/thumbs/"+prodetails.cPicture);
						//else
						//	$('a#1 > img').attr('src','https://graph.facebook.com/'+prodetails.fbId+'/picture?width=107&height=107');
						
						if(prodetails.canPictures2 == null){
							$('a#2 > img').attr('src',"http://<?php echo $bucket;?>.s3.amazonaws.com/files/profile_images/thumbs/no_profile_picture.jpg");
						} else {
						$('a#2 > img').attr('src',"http://<?php echo $bucket;?>.s3.amazonaws.com/files/profile_images/thumbs/"+prodetails.canPictures2);
						$('#'+prodetails.fbId+'__2').remove();
						$('li#img2 .delete.pimg').before('<a href="JavaScript:void(0);" title="Delete Picture" class="delete d" id="'+prodetails.fbId+'__2">Delete</a>');
						$('li#img2 .delete.pimg').text('Change');
						}
						
						if(prodetails.canPictures3 == null){
							$('a#3 > img').attr('src',"http://<?php echo $bucket;?>.s3.amazonaws.com/files/profile_images/thumbs/no_profile_picture.jpg");
						} else {
						$('a#3 > img').attr('src',"http://<?php echo $bucket;?>.s3.amazonaws.com/files/profile_images/thumbs/"+prodetails.canPictures3);
						$('#'+prodetails.fbId+'__3').remove();
						$('li#img3 .delete.pimg').before('<a href="JavaScript:void(0);" title="Delete Picture" class="delete d" id="'+prodetails.fbId+'__3">Delete</a>');
						$('li#img3 .delete.pimg').text('Change');
						}
						
						if(prodetails.canPictures4 == null){
							$('a#4 > img').attr('src',"http://<?php echo $bucket;?>.s3.amazonaws.com/files/profile_images/thumbs/no_profile_picture.jpg");
						} else {
						$('a#4 > img').attr('src',"http://<?php echo $bucket;?>.s3.amazonaws.com/files/profile_images/thumbs/"+prodetails.canPictures4);
						$('#'+prodetails.fbId+'__4').remove();
						$('li#img4 .delete.pimg').before('<a href="JavaScript:void(0);" title="Delete Picture" class="delete d" id="'+prodetails.fbId+'__4">Delete</a>');
						$('li#img4 .delete.pimg').text('Change');
						}
						if(prodetails.canPictures5 == null){
							$('a#5 > img').attr('src',"http://<?php echo $bucket;?>.s3.amazonaws.com/files/profile_images/thumbs/no_profile_picture.jpg");
						} else {
						$('a#5 > img').attr('src',"http://<?php echo $bucket;?>.s3.amazonaws.com/files/profile_images/thumbs/"+prodetails.canPictures5);
						$('#'+prodetails.fbId+'__5').remove();
						$('li#img5 .delete.pimg').before('<a href="JavaScript:void(0);" title="Delete Picture" class="delete d" id="'+prodetails.fbId+'__5">Delete</a>');
						$('li#img5 .delete.pimg').text('Change');
						}
						
						//header name
						$("#head").html("Panel for "+prodetails.uFname+" "+prodetails.uLname+" (change candidate)");
						
					    $("#fb_user_id").val(prodetails.fbId);
					    $("#file_fb_user_id").val(prodetails.fbId);
						}else{
						//alert("No Profile exits for Dashboard");
						
                                                $( "#tabs" ).tabs( "disable", 0);
						$( "#tabs" ).tabs( "disable", 1);
                                                $( "#tabs" ).tabs( "disable", 2);
                                                $( "#tabs" ).tabs( "disable", 3);
                                                $( "#tabs" ).tabs( "disable", 4);
						}
						
		}
        });
        
        //ajax call to get match preference tabs's data
        
        $.ajax({
		    type: "POST",
			data: {userid:userid},
			url: "<?php echo base_url();?>/dashboard/matchpre",            
			success: function(data) {
						if(data)
						{
						var matchdetails = JSON.parse(data);
						//alert(matchdetails.motherTongue);
						if(matchdetails.fromAge == null){
							//$("#ageFrom").empty();
							//$("#ageTo").empty();
							age_counter = 1;
								
						} else
						{
							$( "#slider-range-age" ).slider( "option", "values", [ matchdetails.fromAge, matchdetails.toAge ] );
							$("#mAgeFrom").html(matchdetails.fromAge);
							$("#mAgeTo").html(matchdetails.toAge);
							$("#ageFrom").val(matchdetails.fromAge);
							$("#ageTo").val(matchdetails.toAge);
						}
						
						if(matchdetails.fromHeight == null){
							//$("#heightFromFt").empty();
							//$("#heightFromInch").empty();
							//$("#heightToFt").empty();
							//$("#heightToInch").empty();
							height_counter = 1;	   
						}else{
							$( "#slider-range-heightRange" ).slider( "option", "values", [ matchdetails.fromHeight, matchdetails.toHeight ] );
							var fromft = Math.floor(matchdetails.fromHeight/12);
			            	var frominch = matchdetails.fromHeight%12;
			            	
			           		var toft = Math.floor(matchdetails.toHeight/12);
			            	var toinch = matchdetails.toHeight%12;
			                
			                $('#mheightFrom').html(fromft+"'"+frominch+"''");
			                $('#mheightTo').html(toft+"'"+toinch+"''");
			                
		                	$("#heightFromFt").val(fromft);
							$("#heightFromInch").val(frominch);
							$("#heightToFt").val(toft);
							$("#heightToInch").val(toinch);
						}
						
						
						var ms = matchdetails.maritalStatus;
						if(matchdetails.maritalStatus == null || matchdetails.maritalStatus == '1,2,3,4,5,6,7,8,9'){
							//$('#maritalStatus option[value=Any]').attr("selected",true);
							$("#maritalStatus").selectBox('value','1,2,3,4,5,6,7,8,9');
						} else {
							
							var msarray=ms.split(",");
							$('#maritalStatus').selectBox('value',msarray);
							
						}
						
						var mr = matchdetails.religion;
						if(matchdetails.religion == null || matchdetails.religion == '1,2,3,4,5,6,7,8,9,10,11'){
							$("#mreligion").selectBox('value','1,2,3,4,5,6,7,8,9,10,11');
						}else{
							var mrarray=mr.split(",");
							$("#mreligion").selectBox('value',mrarray);
						}
												
						var mt = matchdetails.motherTongue;
						if(matchdetails.motherTongue == null || matchdetails.motherTongue == '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17'){
							$("#mTongueMulti").selectBox('value','1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17');
						}else{
							var mtarray = mt.split(",");
							$("#mTongueMulti").selectBox('value',mtarray);
						}
						
						var mc = matchdetails.caste;
						if(matchdetails.caste == null){
							$('#mcaste').selectBox('value','');
						}else{
							var mcarray = mc.split(",");
							$("#mcaste").selectBox('value',mcarray);
						}
						
						var me = matchdetails.minEducation;
						if(matchdetails.minEducation == null || matchdetails.minEducation == '1,2,3,4,5,6,7'){
								$("#mEducation").selectBox('value','1,2,3,4,5,6,7');
						}else{
							var mearray = me.split(",");
							$("#mEducation").selectBox('value',mearray);
						}
						
						var mp = matchdetails.profession;
						if(matchdetails.profession == null || matchdetails.profession == '1,2,3,4,5'){
							$('#mprofession').selectBox('value','1,2,3,4,5');
						}else{
							var mparray = mp.split(",");
							$('#mprofession').selectBox('value',mparray);
						}
						
						if(matchdetails.minSalary == null){
							$('#msalary').val(50000);
							$("#smSalary").html("Rs."+50000);
        					$( "#slider-range-msalary" ).slider( "option", "value", 50000 );
							//load profile setting tab
							//$('#profilesetting_tab').click(); 
						}else{
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
                			var salarywithcommas = addCommas(matchdetails.minSalary);
							
							$('#msalary').val(matchdetails.minSalary);
        					$("#smSalary").html("Rs."+salarywithcommas);
        					$( "#slider-range-msalary" ).slider( "option", "value", matchdetails.minSalary );
						}
						
					}		
				else{alert("else");}
					
		}
        });
		
		//if logined in user == cuserId select box user then relation dropdown value should be self
		if ($('#userId').val() == $('#cuserId').val()) {
			
			var relation = "<option value='1'>Self</option>";
			$("#relation").selectBox('options',relation);
			$("#relation").selectBox('disable');
		} else{
			
			//ajax call for the dropdown array for recommendation relation
        	$.ajax({
            type: "POST",
            url: "<?php echo base_url();?>/candidate/recommrelationGet",            
            success: function(data) {
							if(data)
							{
							var relation = JSON.parse(data);
							$("#relation").selectBox('options',relation);
							$("#relation").selectBox('enable');
							
							}		
						    //else
						    //$('#alert_m_pref').html(data);	
					}
        	});
		}		 
		 
		//$('#pro_setting').click();		 
});
//get candidate data based on cuserId select box field // END

//function call on Profile settings tabs's save button 		
$('#candidate_update').click(function(event) {
		
		//validation check validateCandidate() defined on validationdashboard.js
		if(validateCandidate())
		{
            var profileFbId = $('#cuserId').val();
	       	var fName = $("#fName").val();
	        var lName = $("#lName").val();
	        var gender = $('#gender :selected').val();
	        var dob = $("#dob").val();
	        var relationship = $('#relationship').val();
	        var religion = $('#religion').val();
	        var mTongue = $('#mTongue').val();
                var caste = $('#caste').val();
                var heightFt = $('#heightFt :selected').val();
                var heightInch = $('#heightInch :selected').val();
                var location = $('#location').val();
                var hEducation = $('#hEducation').val();
                var hEducationDes = $('#hEducationDes').val();
                var profession = $('#profession').val();
                var professionDes = $('#professionDes').val();
                function removeCommas(str) {
                return(str.replace(/,/g,''));
		}
		
		var salary = removeCommas($('#salary').val());
		var sRecommendation = $('#sRecommendation').val();
		
		$('#candidate_msg').html('<img src="<?php echo base_url();?>images/ajax-loader.gif"/>');
        $.ajax({
            type: "POST",
            data: {profileFbId:profileFbId,fName : fName,
            	lName : lName,gender :gender,dob :dob,
            	relationship:relationship,religion:religion,
            	mTongue:mTongue,caste:caste,heightFt:heightFt,
            	heightInch:heightInch,location:location,hEducation:hEducation,hEducationDes:hEducationDes,profession:profession,professionDes:professionDes,salary:salary,sRecommendation:sRecommendation},
            url: "<?php echo base_url();?>/candidate/addCandidateProfile",            
            success: function(data) {
					if(data=="true")
					{
						$('#candidate_msg').html("profile settings updated");
						//$( "#tabs" ).tabs( "enable", 1);
						//$("#biodata_tab").click();
					}else
						{
							//$('#alert_c_profile').html(data);	
						}
			}
			});
        
		//ajax call for adding recommendation
		if($('#sRecommendation').val() !='' || /^\s/.test($('#sRecommendation').val()) == false )
		{			
			
			$.ajax({
			type: "POST",
			data: {recommenderFbId:$('#userId').val(),candidateFbId:$("#cuserId").val(),relationship:$('#relation').val(),recommendation:$('#sRecommendation').val(),type:'G'},
			url:"<?php echo base_url();?>/candidate/addRecommendation",
			success: function(data){
				
			}
			});
		}
        }
		});
    
//function call on Match Preferences tabs's "Save Profile Information" button 
$('#update_m_preferences').click(function(event){
	
		if(validateMatchPreferences())
		{
			var matchpreProfileFbId = $("#cuserId").val();
			var ageFrom = $("#ageFrom").val();
                        var ageTo = $("#ageTo").val();
                        var maritalStatus = $('select#maritalStatus').val();
                        var mreligion = $("select#mreligion").val();
                        var mTongueMulti = $('select#mTongueMulti').val();
                        var mcaste = $('select#mcaste').val();
                        var heightFromFt = $('#heightFromFt').val();
			var heightFromInch = $('#heightFromInch').val();
			var heightToFt = $('#heightToFt').val();
			var heightToInch = $('#heightToInch').val();
			var mEducation = $('select#mEducation').val();
			var mprofession = $('select#mprofession').val();
			var msalary = $('#msalary').val();
		
            $('#mpre_msg').html('<img src="<?php echo base_url();?>images/ajax-loader.gif"/>');
			$.ajax({
		            type: "POST",
		            data: {matchpreProfileFbId:matchpreProfileFbId,ageFrom : ageFrom,
		            	ageTo : ageTo,maritalStatus :maritalStatus,mreligion :mreligion,
		            	mTongueMulti:mTongueMulti,
		            	mcaste:mcaste,heightFromFt:heightFromFt,
		            	heightFromInch:heightFromInch,heightToFt:heightToFt,heightToInch:heightToInch,mEducation:mEducation,mprofession:mprofession,msalary:msalary},
		            url: "<?php echo base_url();?>/candidate/addmatchpreferences",            
		            success: function(data) {
							if(data=="true")
							{
							$('#mpre_msg').html("match references updated");
							//$( "#tabs" ).tabs( "enable", 3);
							//$("#dob").blur();
							//$("#invite_tab").click();
							//update rc_profiles table's status field to for profile completation status
                            $.ajax({
						            type: "POST",
						            data: {candidateFbId : matchpreProfileFbId},
						            url: "<?php echo base_url();?>/candidate/candidateStatus",            
						            success: function(data) {
										
											if(data=="true")
											{
												$('#mpre_msg').html("candidate creation complete");
												//var urls = "<?php //echo base_url();?>/dashboard";    
												//$(location).attr('href',urls);
											}
											
											//else
											//$('#mpre_msg').html("candidate creation incomplete");	
												
											}
								});	
							
							}		
						else{
							//$('#alert_m_pref').html(data);	
							}
					}
		        });
		        
		      	   
        }
	
});

$('#profilesetting_tab').click(function(){
$('#candidate_msg').html("");	
});
	
			$('#matchpre_tab').click(function(){
								if(age_counter == 1)
								{
									calcage();
								}
								if(height_counter == 1)
								{
									calcheight()
								}
								$('#mpre_msg').html("");
								//$('#getpsetting').click();							
			});


//calculate the age range for Match Preferences age-range slider
function calcage(){
					var gen = $("#gender").val();
					var match = $('#dob').val();
					var day = match[1];
					var month = match[3]+match[4];
					var  year = match[6]+match[7]+match[8]+match[9];
					var dob = new Date(year, month - 1, day);
					var today = new Date();
					var age = today.getFullYear() - dob.getFullYear();
					//alert(today.getFullYear() - dob.getFullYear()+" "+gen);
					if(gen == 1){
						$('#ageTo').val(age);
						$('#mAgeTo').html(age);
					 	$('#ageFrom').val(age-5);
						$('#mAgeFrom').html(age-5); 
					 	
					 	
					 }
					 if(gen ==2){
					 	$('#ageTo').val(age+5);
						$('#mAgeTo').html(age+5);
						$('#ageFrom').val(age);
						$('#mAgeFrom').html(age);
					 }
				
					$( "#slider-range-age" ).slider( "option", "values", [ $('#ageFrom').val(), $('#ageTo').val() ] );
}

//calculate the height range for Match Preferences height-range slider
function calcheight(){
			var gen = $('#gender').val();
			if(gen ==1){
					var	$candidateHeight = parseInt($("#heightFt").val())*12+parseInt($("#heightInch").val());
					var $femaleHeight = $candidateHeight - 5; //starting height range of female 
					var $hFt = Math.floor($femaleHeight / 12); 
					var $hInch = $femaleHeight % 12;
					$("#heightFromFt").val($hFt);
					$("#heightFromInch").val($hInch);
					$("#mheightFrom").html($hFt+"'"+$hInch+"''");
					
					$("#heightToFt").val($('#heightFt').val());
					$("#heightToInch").val($('#heightInch').val());
					$("#mheightTo").html($('#heightFt').val()+"'"+$('#heightInch').val()+"''");
					var sliderHfrom = $femaleHeight;
					var SliderHto = $candidateHeight;	 
					
			}
			if(gen ==2){
					var	$candidateHeight = parseInt($("#heightFt").val())*12+parseInt($("#heightInch").val());
					var $maleHeight = $candidateHeight + 5; //starting height range of male 
					var $hFt = Math.floor($maleHeight / 12); 
					var $hInch = $maleHeight % 12;
					$("#heightFromFt").val($('#heightFt').val());
					$("#heightFromInch").val($('#heightInch').val());
					$("#mheightFrom").html($('#heightFt').val()+"'"+$('#heightInch').val()+"''");
					
					$("#heightToFt").val($hFt);
					$("#heightToInch").val($hInch);
					$("#mheightTo").html($hFt+"''"+$hInch+"''");
					var sliderHfrom = $candidateHeight;
					var SliderHto = $maleHeight;
			}
			$( "#slider-range-heightRange" ).slider( "option", "values", [ sliderHfrom, SliderHto ] );
}
					
					
            $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>/candidate/casteGet",            
            success: function(data) {
						if(data)
						{
							var casts = JSON.parse(data);
                                                       //$("#mcaste").selectBox('options',cc.casts);
                                                       // $("#mcaste").selectBox('value',0);
                                                       // delete cc[0];
							$("#caste").autocomplete({
			        			source: casts
			    			});
						    						
			    			
			    			//$("#mcaste").selectBox('value','Any');
							/*
							$("#mcaste").autocomplete({
														source: casts
														});*/
														
						}		
						//else
						//$('#alert_m_pref').html(data);	
			}
        	});
                
        //ajax call for the Caste (multiselect) on Match preferences
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>/candidate/casteGetForMulti",            
            success: function(data) {
                if(data)
                {
                    var casts = JSON.parse(data);
                    $("#mcaste").selectBox('options',casts);
                    $("#mcaste").selectBox('value',0);
					
                }		
                
            }
        });
        	
            //ajax call for the dropdown array of relationship status
            $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>/candidate/relationGet",            
            success: function(data) {
						if(data)
						{
							var relations = JSON.parse(data);
							$("#maritalStatus").selectBox('options',relations);
							$("#maritalStatus").selectBox('value',0);
								                        delete relations[0];
                                                        $("#relationship").selectBox('options',relations);
														
						}		
						//else
						//$('#alert_m_pref').html(data);	
			}
        	});
        	
        	//ajax call for the dropdown array for Mother Tongue
        	$.ajax({
            type: "POST",
            url: "<?php echo base_url();?>/candidate/mTongueGet",            
            success: function(data) {
						if(data)
						{
							var language = JSON.parse(data);
							$("#mTongueMulti").selectBox('options',language);
							$("#mTongueMulti").selectBox('value',0);
							delete language[0];
                            $("#mTongue").selectBox('options',language);
							
						}		
						//else
						//$('#alert_m_pref').html(data);	
						}
        	});
        	
			//ajax call for the dropdown and multiselect array for religion
        	$.ajax({
            type: "POST",
            url: "<?php echo base_url();?>/candidate/religionGet",            
            success: function(data) {
						if(data)
						{
							var religion = JSON.parse(data);
                                                        $("#mreligion").selectBox('options',religion);
							$("#mreligion").selectBox('value',0);
                                                        delete religion[0];
							$("#religion").selectBox('options',religion);
							
									
						}		
						//else
						//$('#alert_m_pref').html(data);	
					}
        	});
        	
        	//ajax call for the dropdown array for education
        	$.ajax({
            type: "POST",
            url: "<?php echo base_url();?>/candidate/educationGet",            
            success: function(data) {
						if(data)
						{
							var education = JSON.parse(data);
                                                        $("#mEducation").selectBox('options',education);
							$("#mEducation").selectBox('value',0);
							delete education[0];
                                                        $("#hEducation").selectBox('options',education);
								
						}		
						//else
						//$('#alert_m_pref').html(data);	
					}
        	});
        	
			//ajax call for the dropdown array for profession
        	$.ajax({
            type: "POST",
            url: "<?php echo base_url();?>/candidate/professionGet",            
            success: function(data) {
						if(data)
						{
							var profession = JSON.parse(data);
                                                        $("#mprofession").selectBox('options',profession);
							$("#mprofession").selectBox('value',0);
                                                        delete profession[0];
							$("#profession").selectBox('options',profession);
																
						}		
						//else
						//$('#alert_m_pref').html(data);	
					}
        	});
        	
        	//ajax call for the dropdown array for recommendation relation
        	$.ajax({
            type: "POST",
            url: "<?php echo base_url();?>/candidate/recommrelationGet",            
            success: function(data) {
						if(data)
						{
							var relation = JSON.parse(data);
							$("#relation").selectBox('options',relation);
							
						}		
						//else
						//$('#alert_m_pref').html(data);	
					}
        	});					
					
		
$("#pro_setting").click( function(){
				$("#delBiodataDiv").hide();
				$('#candidate_msg').html("");
				//$('.file .button').text(" ");
});

	   //for age range
       $( "#slider-range-age" ).slider({
            range: true,
            min: 18,
            max: 60,
            values: [  18 , 60 ],
            slide: function( event, ui ) {
                var agemin = ui.values[ 0 ] ;
                var agemax = ui.values[ 1 ] ;
                $( "#ageFrom" ).val(agemin);
                $( "#ageTo" ).val(agemax);
                $("#mAgeFrom").html(agemin);
                $("#mAgeTo").html(agemax);
            }
        });
        
						   /*
						   $('#editagerange-icon').click(function(){
                               $('#mAgeFrom').toggle();
                               $('#editagerange1').toggle();
                               $('#mAgeTo').toggle();
                               $('#editagerange2').toggle();
                           });*/
       
					
					$('#editagerange-icon').click(function(){
						$('#mAgeFrom').toggle();
						$('#editagerange1').toggle();
						$('#mAgeTo').toggle();
						$('#editagerange2').toggle();
						$('#editagerange-icon').hide();
						$('#agerange_msg').show();
						$('#agerange_msg').hide(5000);
					});
					$('#mAgeTo').click(function(){
						$('#mAgeFrom').toggle();
						$('#editagerange1').toggle();
						$('#mAgeTo').toggle();
						$('#editagerange2').toggle();
						$('#editagerange-icon').show();
						$('#ageTo').focus();
					});
					$('#mAgeFrom').click(function(){
						$('#mAgeFrom').toggle();
						$('#editagerange1').toggle();
						$('#mAgeTo').toggle();
						$('#editagerange2').toggle();
						$('#editagerange-icon').show();
						$('#ageFrom').focus();
					});			
					
        $('#ageFrom').blur(function(){
        	var agef = parseInt($('#ageFrom').val());
        	var aget = parseInt($('#ageTo').val());
        	if(agef<=aget && agef>=18){
        		$("#slider-range-age").slider("option","values",[agef,aget]);
        		$("#mAgeFrom").html(agef);
        		}else{
        			$('#ageFrom').val(18);
        			$("#mAgeFrom").html(18);
        			
        			$('#ageTo').val(19);
                    $("#mAgeTo").html(19);
                    $("#slider-range-age").slider("option","values",[18,19]);
        		}
        });
        
        
        $('#ageTo').blur(function(){
                    var agef = parseInt($('#ageFrom').val());
                    var aget = parseInt($('#ageTo').val());
                    if(aget>=agef && aget<=60){
                        $("#slider-range-age").slider("option","values",[agef,aget]);
                        $("#mAgeTo").html(aget);
                        }else{
                            $('#ageTo').val(19);
                            $("#mAgeTo").html(19);
                            
                            $('#ageFrom').val(18);
        					$("#mAgeFrom").html(18);
        					$("#slider-range-age").slider("option","values",[18,19]);
                        }
         });	
        
       //for height range
       $( "#slider-range-heightRange" ).slider({
            range: true,
            min: 48,
            max: 83,
            // values: [ parseInt($("#heightFromFt").val())*12+parseInt($("#heightFromInch").val()), parseInt($("#heightToFt").val())*12+parseInt($("#heightToInch").val())  ],
            values: [ 48, 83 ],
            slide: function( event, ui ) {
                var ft = Math.floor(ui.values[ 0 ]/12);
            	var inch = ui.values[ 0 ]%12;
            	
            	
            	var toft = Math.floor(ui.values[ 1 ]/12);
            	var toinch = ui.values[ 1 ]%12;
                $( "#heightFromFt" ).val(ft);
                $( "#heightFromInch" ).val(inch);
                $('#mheightFrom').html(ft+"'"+inch+"''");
                
                $( "#heightToFt" ).val(toft);
                $( "#heightToInch" ).val(toinch);
                $('#mheightTo').html(toft+"'"+toinch+"''");
            }
        });
        
								/*
								$('#editheightrange-icon').click(function(){
                                $('#mheightFrom').toggle();
                                $('#editheightrange1').toggle();
                                $('#mheightTo').toggle();
                                $('#editheightrange2').toggle();
                                hf1 = $("#heightFromFt").val();
                                hf2 = $("#heightFromInch").val();
                                ht1 = $("#heightToFt").val();
                                ht2 = $("#heightToInch").val();
                                });*/
        
					$('#editheightrange-icon').click(function(){
						$('#mheightFrom').toggle();
						$('#editheightrange1').toggle();
						$('#mheightTo').toggle();
						$('#editheightrange2').toggle();
						$('#editheightrange-icon').hide();
						$('#heightrange_msg').show();
						$('#heightrange_msg').hide(5000);
						hf1 = $("#heightFromFt").val();
						hf2 = $("#heightFromInch").val();
						ht1 = $("#heightToFt").val();
						ht2 = $("#heightToInch").val();
			
					});
					$('#mheightFrom').click(function(){
						$('#mheightFrom').toggle();
						$('#editheightrange1').toggle();
						$('#mheightTo').toggle();
						$('#editheightrange2').toggle();
						$('#editheightrange-icon').show();
						$('#heightFromFt').focus();
						hf1 = $("#heightFromFt").val();
						hf2 = $("#heightFromInch").val();
						ht1 = $("#heightToFt").val();
						ht2 = $("#heightToInch").val();
			
					});
					$('#mheightTo').click(function(){
						$('#mheightFrom').toggle();
						$('#editheightrange1').toggle();
						$('#mheightTo').toggle();
						$('#editheightrange2').toggle();
						$('#editheightrange-icon').show();
						$('#heightToFt').focus();
						hf1 = $("#heightFromFt").val();
						hf2 = $("#heightFromInch").val();
						ht1 = $("#heightToFt").val();
						ht2 = $("#heightToInch").val();
			
					});
        
			$("#heightFromFt").blur(function(){
				var htfrom = parseInt($("#heightFromFt").val())*12+parseInt($("#heightFromInch").val());
				var htto = parseInt($("#heightToFt").val())*12+parseInt($("#heightToInch").val());
				if(htfrom <= htto && parseInt($("#heightFromInch").val()) <= 11 && parseInt($("#heightFromFt").val()) >= 4)
				{
					$("#slider-range-heightRange").slider("option","values",[htfrom,htto]);
					$('#mheightFrom').html($("#heightFromFt").val()+"'"+$("#heightFromInch").val()+"''"); 
				}else{
					$("#heightFromFt").val(hf1);
				}
			});
			
			$("#heightFromInch").blur(function(){
				var htfrom = parseInt($("#heightFromFt").val())*12+parseInt($("#heightFromInch").val());
				var htto = parseInt($("#heightToFt").val())*12+parseInt($("#heightToInch").val());
				if(htfrom <= htto && parseInt($("#heightFromInch").val()) <= 11)
				{
					$("#slider-range-heightRange").slider("option","values",[htfrom,htto]);
					$('#mheightFrom').html($("#heightFromFt").val()+"'"+$("#heightFromInch").val()+"''"); 
				}else {
					$("#heightFromInch").val(hf2);
				}
			});
			
			$("#heightToFt").blur(function(){
				var htfrom = parseInt($("#heightFromFt").val())*12+parseInt($("#heightFromInch").val());
				var htto = parseInt($("#heightToFt").val())*12+parseInt($("#heightToInch").val());
				if(htfrom <= htto && parseInt($("#heightToInch").val()) <= 11)
				{
					$("#slider-range-heightRange").slider("option","values",[htfrom,htto]);
					$('#mheightTo').html($("#heightToFt").val()+"'"+$("#heightToInch").val()+"''"); 
				}else{
					$("#heightToFt").val(ht1);
				}
			});
			
			$("#heightToInch").blur(function(){
				var htfrom = parseInt($("#heightFromFt").val())*12+parseInt($("#heightFromInch").val());
				var htto = parseInt($("#heightToFt").val())*12+parseInt($("#heightToInch").val());
				if(htfrom <= htto && parseInt($("#heightToInch").val()) <= 11)
				{
					$("#slider-range-heightRange").slider("option","values",[htfrom,htto]);
					$('#mheightTo').html($("#heightToFt").val()+"'"+$("#heightToInch").val()+"''"); 
				}else {
					$("#heightToInch").val(ht2);
				}
			});	
        
            //for slider salary(matchpre)
            $( "#slider-range-msalary" ).slider({
            range: "min",
            value: 0,
            min: 0,
            max: 5000000,
            step: 25000,
            slide: function( event, ui ) {
            	var msalary = ui.value;
            	
                $( "#msalary" ).val(msalary);
                
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
                var salarywithcommas = addCommas(msalary);
                
                
                $("#smSalary").html("Rs."+salarywithcommas);
                
            }
        }); 
        
							/*
							$('#editsalary-icon2').click(function(){
                                $('#editsalary2').toggle();
                                $('#smSalary').toggle();
                            });*/
        
		
					$('#editsalary-icon2').click(function(){
						$('#editsalary2').toggle();
						$('#smSalary').toggle();
						$('#editsalary-icon2').hide();
						$('#salary_msg').show();
						$('#salary_msg').hide(5000);
					});
					
		$('#smSalary').click(function(){
			$('#editsalary2').toggle();
			$('#smSalary').toggle();
			$('#editsalary-icon2').show();
			$('#msalary').focus();
		});			
					
		$('#msalary').change(function(){
        	var sval = parseInt($('#msalary').val());
	        	if($('#msalary').val()=='' || /\s/g.test($('#msalary').val()))
        		sval=0;
        	
        	if(sval<=5000000)
        	{
        	$( "#slider-range-msalary" ).slider( "option", "value", sval );
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
                var salarywithcommas = addCommas(sval);
                $( "#smSalary" ).html("Rs."+salarywithcommas);
                 $('#msalary').val(sval);
               } else{
               		$('#msalary').val(0);
               }
        });			
      
		$('#sRecommendation').click(function(){
						if($('#sRecommendation').val() == "A Short Recommendation"){
							$('#sRecommendation').val("");
						}

		});
		$('#sRecommendation').blur(function(){
			
			if($('#sRecommendation').val() == ""){
				$('#sRecommendation').val("A Short Recommendation");
				
			}
		});
        
		
						/*
						$('#sRecommendation').keyup(function(){
                             if($('#sRecommendation').val() == "" || /^\s/.test($('#sRecommendation').val()) == true)
                             {
                                 $('#relation-div').hide(1000)
                                 }else{$('#relation-div').show(1000);}
                        });*/
		
		$('#reset').click(function(){
			$('#getpsetting').click();
		});			  
								
					
		$('#getpsetting').click();				
});
</script>

<script>

/*$('.pimg').live( 'click',function(){
    var a_id_val = $(this).attr('id');
	$('input[name="a_id"]').val(a_id_val);
    $('#upld_box').show('slow');
});*/

$('.delete.pimg').live( 'click',function(){
    var a_id_val = $(this).attr('id');
	$('input[name="a_id"]').val(a_id_val);
    $('#upld_box').show('slow');
});

$('.delete2.pimg').live( 'click',function(){
    var a_id_val = $(this).attr('id');
	$('input[name="a_id"]').val(a_id_val);
    $('#upld_box').show('slow');
});

$('#CancelButton').live( 'click',function(){    
    $('#upld_box').hide('slow');
});


</script>

<script>
		jQuery(document).ready(function() {
        
        $('a img.rcimg').click(function(){
            
          var fbUserName = $(this).attr('id');
          
          fb_dialogue(fbUserName);
            
        });


							$('.delete').live('click', function(){								
								var a_id_val = $(this).attr('id');
								var a_id = a_id_val.split('__');
								
								//show the loader image
								$('a#'+a_id[1]+".delete.pimg").hide();	
								$('a#'+a_id_val).html('<img height="20" width="20" src="http://'+top.location.host+'/images/ajax-loader.gif" alt=""/>');
								
								$.ajax({
									type: "POST",
									data: {a_id : a_id_val},
									url: "<?php echo base_url();?>/candidate/delCanImage",            
									success: function(data) {
								
										var arr = data.split('__');
										
										if(data!=0)	{
											//alert(data);
											//alert(arr[1]);
								
											$("li a#"+data).remove();
								
											$("li a#"+arr[1]).html('<img width="107" height="107" title="picture 3" src="http://<?php echo $bucket;?>.s3.amazonaws.com/files/profile_images/thumbs/no_profile_picture.jpg">');
											
											$('a#'+a_id[1]+".delete.pimg").html('Add');
											$('a#'+a_id[1]+".delete.pimg").show();
									
										}else{
											//print error
										}
										
									}
								});
							})


	//remove class from "Help your friends" panel
	$('div#help-frnd-lft-block').removeClass('lft-block');
	
	
	$('.del_biodata').live("click", function(){
  	
   	var cv = $(this).attr('id');   //candidate's unique facebook id
   	//alert(cv);
	
	//show the loader image
	$('#delBiodataDiv').html('<img height="20" width="20" src="http://'+top.location.host+'/images/ajax-loader.gif" alt=""/>');
	//return false;
	
    $.ajax({
     type: "POST",
     data: {fbUserId : cv},
     url: "<?php echo base_url();?>/functions/deleteBiodata",            
     success: function(data) {
      $('#biodataDiv').remove();
      $('#delBiodataDiv').html("<div class='errors'>BioData deleted successfully</div>");
     }
	
    });
	
  	});
	
        
    }); 
    
    function fb_dialogue(fbUserName)
    {
	
        // assume we are already logged in
        FB.init({appId: '<?php echo $fbAppId; ?>', xfbml: true, cookie: true});
			
        FB.ui({
            to: fbUserName,
            method: 'send',
            name: 'Test',
            //link: 'http://ec2-50-19-66-142.compute-1.amazonaws.com/rishtey-connect'
			link: 'http://development.rishteyconnect.com'
        });  
		
    }

</script>

<script type="text/javascript">

/*** The scripts for the show interest section ***/
function abuse(id,mid,did)
{
		var id = id;
		//alert(id);
		$("#usermsg-"+id).html('<img src="<?php echo base_url();?>images/ajax-loader.gif"/>');
		$.ajax({
	     type: "POST",
	     url: "<?php echo base_url();?>/dashboard/setAbuse/"+id+"/"+mid,            
	     success: function(data) {
                 $("#usermsg-"+id).html("abuse report send");
                
                setTimeout(function(){closePop(did);$("#usermsg-"+id).html(" ");

                  },3000);
	      
	     }
		
	    });
		
}

function shareProfile(id,mid,did)
{
		var id = id;
		//alert(id);
		//alert(id);
		var strcontent =$("#tolargepdf_"+id).html();
		
		//alert(strcontent);
		//return false;
		
		var email = $("#email-"+id).val();

		var msg = $("#emailMsg-"+id).val();

		//alert(msg);
		var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if (!filter.test(email)) {		
			alert('Please provide a valid email address');
			return false;
		}
        
		$("#email-"+id).val(' ');
		$("#emailMsg-"+id).val(' ');
		
        $("#shareProfileMsg-"+id).html('<img src="<?php echo base_url();?>images/ajax-loader.gif"/>');
		$.ajax({
	     type: "POST",
         data: {email : email, strcontent : strcontent, msg : msg},
	     url: "<?php echo base_url();?>/dashboard/shareProfile/"+id+"/"+mid,            
	     success: function(data) {
                 $("#shareProfileMsg-"+id).html('<img src="<?php echo base_url();?>images/ajax-loader.gif"/>Profile Shared Successfully');
                
                	setTimeout(function(){closePop(did);$("#shareProfileMsg-"+id).html('');
		
                 },3000);
                 
	    }
		
	    });
		
}

function block(id,mid,did)
{
		var id = id;
		//alert(id);
		$("#usermsg-"+id).html('<img src="<?php echo base_url();?>images/ajax-loader.gif"/>');
		$.ajax({
	     type: "POST",
	     url: "<?php echo base_url();?>/dashboard/setBlocked/"+id+"/"+mid,            
	     success: function(data) {
                $("#usermsg-"+id).html("user blocked");
                
                setTimeout(function(){closePop(did);$("#usermsg-"+id).html(" ");
		
                  },3000);
                  //hit it to remove the blocked user from ui
                  $("#getpsetting").click();
	      
	     }
		
	    });
		
}

function sendMessage(id,mid,did)
{     
		var id = id;
		//alert(id);
		var msg = $("#msg-"+id).val();
		//alert(msg);
                $("#showmsg-"+id).html('<img src="<?php echo base_url();?>images/ajax-loader.gif"/>');
		$.ajax({
	     type: "POST",
	     data: {msg : msg},
	     url: "<?php echo base_url();?>/dashboard/showInterest/"+id+"/"+mid,            
	     success: function(data) {
	      $("#showmsg-"+id).html("Message Send Successfully");
              $("#msg-"+id).val(" ");
              setTimeout(function(){closePop(did);closePop(id);$("#showmsg-"+id).html(" ");
              
              },3000);
              //closePop(did);
	     }
		
	    });
		
}

//TODO 11th feb 2013 
function sendChat(id,mid,fbid){
    var id = id;
    //alert(id);
    var msg = $("#chatMsg-"+fbid).val();
    //alert(msg);
    $("#chatloader-"+fbid).html('<img src="<?php echo base_url();?>images/ajax-loader.gif"/>');
    $.ajax({
        type: "POST",
        data: {msg : msg},
        url: "<?php echo base_url();?>/dashboard/showInterest/"+id+"/"+mid,            
        success: function(data) {
                    
		// closePop(did);
                var ss = $("#cuserId").val();
                //alert(ss+" "+fbid);
                $.ajax({
                    type: "POST",
                    data: {ss : ss},
                    url: "<?php echo base_url();?>/dashboard/getSingleMsg/"+ss+"/"+fbid,
                    success: function(data){
                    // alert(data);
                     var obj = JSON.parse(data);
                                var output2 = '';
                                // var list = '';
                                var list2 = '';
                                for (var prop in obj) {
                                //alert(obj[prop].cid+" "+key);
                                //alert(obj.cid_matched + " to " + obj.cid + " : " + obj.interest_message);
                                   if(obj[prop].cid == fbid){
                                   //output2 = "<img src='"+obj[prop].image+"' width='35' height='35' alt='"+obj[prop].cid_name+obj[prop].reciver_name+"' class='left' /><span>"+obj[prop].cid_name+"'s Gaurdian "+obj[prop].reciver_gaurdian_name+"</span><span class='chat left'>"+obj[prop].interest_message+"</span><span class='time right'>"+obj[prop].created_at+"</span><div class='clear'></div>";  
                                   output2 = "<span>"+obj[prop].cid_name+"'s Gaurdian "+obj[prop].reciver_gaurdian_name+": </span><span class='chat left'>"+obj[prop].interest_message+"</span><span class='time right'>"+obj[prop].created_at+"</span><div class='clear'></div>";  
                                   }else{
                                   //output2 = "<img src='"+obj[prop].image+"' width='35' height='35' alt='"+obj[prop].cid_name+obj[prop].reciver_name+"' class='left' /><span>"+obj[prop].cid_name+"</span><span class='chat left'>"+obj[prop].interest_message+"</span><span class='time right'>"+obj[prop].created_at+"</span><div class='clear'></div>";   
                                   output2 = "<span class='left'>"+obj[prop].cid_name+": </span><span class='chat left'>"+obj[prop].interest_message+"</span><span class='time right'>"+obj[prop].created_at+"</span><div class='clear'></div>";
                                   }
                                   
                                   // list +="<li>"+output+"</li>";
                                   list2 +="<div class='chatIndivisual'>"+output2+"</div>"; 
                                   // $("#chat-"+key).html(list);
                                   $("#chatPanl-"+fbid).html(list2);
                                   $("#chatMsg-"+fbid).val("");
                                   $("#chatloader-"+fbid).html('');
                                   var sender =  obj[prop].sender;
                                   var reciver =  obj[prop].reciver;
                                }
                     
                    }
                });
               
	}

	});
        
        
}

</script>

<script type="text/javascript">
    //history.go(1);
</script>

<div id="console" style="display:none"></div>

<div class="page">
	<div class="content-widget">
    <div class="wrapper-widget">

<div class="lftPan">
         	<div class="lft-block">
		      <?php echo $leftPanelCount; ?>
		    </div> 
			
			<?php if(count($random_suggested_friend)>0) {?>
				<div id="dashboard-help-frnd-lft-block" class="lft-block">  
				  <?php echo $helpFriendData; ?>
				</div>
			<?php }?>
</div>			
			<!--<div class="rghtPan" style="width: 763px;">-->
			<div class="rghtPan">
          <div id="tabs" class="container-inr noBg">
            <div class="change-candidate">
              <p class="candidate-info">Panel for <span id="panelname">...</span></p>
              <div class="select-candidate pullRight">
                <form id="form3" name="form3" method="post" action="">
                  <select id="cuserId" name="cuserId" class="custom-class1">
                    	<?php if($candidateListId == null):?>
                    		
                    	<option value="no-profile">select profile</option>
                    	<?php endif;?>
                    	<?php  foreach($candidateListId as $key => $option ):?>
		                <option value="<?php echo $key;?>"><?php echo $option;?></option>
		                <?php endforeach;?>
                  </select>
                  <button title="go" class="btn-submit" type="button" id="getpsetting">
				<span>
				<span>Go</span>
				</span>
				</button>
                  <!--<input id="getpsetting" class="btn-submit" type="button" value="Go">-->
				  <!--
                  <button title="Go" class="btn-submit" type="button" id="getpsetting">
                                 <span>
                                 <span>Go</span>
                                 </span>
                                 </button>-->
                 
                </form>
              </div>
            </div>
            <ul class='tabs-row hack'>
              <li><a href='#tabs-1'><span>Updates</span></a></li>
              <li><a href='#tabs-2'><span>Matches</span></a></li>
              <li><a id='pro_setting' href='#tabs-3'><span>Profile Settings</span></a></li>
              <li><a id='matchpre_tab' href='#tabs-4'><span>Match Preferences</span></a></li>
              <li><a href='#tabs-5'><span>Interests</span></a></li>
            </ul>
            <div id='tabs-1' class="network-wrapper" style="padding-top:11px !important;">
			  <!--<ul>
                <li>New Match <span>H Gupta</span></li>
                <li>Your Friend Nishit Rawat recommended Jaya Rawat to his network. <span>Thank Nishit.</span></li>
                <li>Interest request sent to the guardian of N singh</li>
                <li>New Match <span>N Singh</span></li>
                <li>New Match <span>G Agarwal</span></li>
                <li>Blocked <span>N Hari</span> from viewing Jaya Rawat as matching candidate</li>
                <li>Received <span>Interest</span> from <span>A Sharma</span></li>
                <li>New Match <span>N Hari</span></li>
              </ul>-->
              <span id="cn_err"><p id="notGuardian"></p></span>
              <ul id="recommendationUpdate">
              	<?php //foreach($recommendationUpdates as $value):?>
              	<li><?php //echo $value; ?></li>
              	<?php //endforeach;?>
              </ul>
               <ul id="interestUpdate">
                <?php //foreach($interestUpdate as $value):?>
              	<li><?php// echo $value; ?></li>
              	<?php //endforeach;?>
               </ul>
               <ul id="blockedUpdate">
                <?php //foreach($blockedUpdate as $value):?>
              	<li><?php //echo $value; ?></li>
              	<?php //endforeach;?>    
               </ul>
               <ul id="newMatchUpdate">
                   
               </ul>
               <ul id="interestReceivedUpdate">
                   
               </ul>
              
            </div>
            <!-- Tab #1 contents end here -->
            <div id='tabs-2' class='heighttab2'>
             <!-- <div class="loading">&nbsp;<span>More</span></div>-->
            </div>
            <!-- Tab #2 contents end here -->
            <div id='tabs-3'>
              <div class="setting-col">
                <div class="setting-colft">
                  <div class="network-wrapper">
                    <div class="setting-info">
                      <div class="info-row">
                        <div class="setting-label">First Name:</div>
                        	<div class="setting-value">
                        	<label><input id="fName" name="fName" type="text" style="width: 154px;" ></label>
                        	</div>
                      </div>
                      <div class="info-row">
                        <div class="setting-label">Last Name:</div>
                        <div class="setting-value">
                        	<label><input id="lName" name="lName" type="text" style="width: 154px;" ></label>
                       </div>
                      </div>
                      <div class="info-row sel">
                        <div class="setting-label">Gender:</div>
                        <div class="setting-value"><!--<input id="gender" name="gender" >-->
                        	<select id="gender" name="gender">
                        		<option value="1">Male</option>
                        		<option value="2">Female</option>
                        	</select>
                        </div>
                      </div>
                      <div class="info-row">
                        <div class="setting-label">Date of Birth:</div>
                        <div class="setting-value"><label class="noMargin"><input class="dashinput" id="dob" name="dob" class="input" type="text" style="width: 124px;" ></label></div>
                      </div>
                      <div class="info-row">
                        <div class="setting-label">Height:</div>
                        <div id="height" name="height" class="setting-value ht-ft">
                        <select id="heightFt" name="heightFt">
	              		
		                <?php for ($i=4; $i<=6; $i++): ?>
		                <option value="<?php echo $i;?>"><?php echo $i;?></option>
		                <?php endfor; ?>
		              	</select> ft
		          		<select  id="heightInch" name="heightInch">
		          			
			                <?php for ($i=1; $i<=11; $i++): ?>
			                <option value="<?php echo $i;?>"><?php echo $i;?></option>
			                <?php endfor; ?>
		          		</select> in</div>
                      </div>
                      <div class="info-row sel">
                        <div class="setting-label">Marital Status:</div>
                        <div class="setting-value">
                        	<select data-validation-engine="validate[required]" id="relationship" name="relationship" class="custom-class2">
                       <!--
                       
                        <?php  //foreach($this->config->item('fbRelationship') as $key => $option):?>
		                <option value="<?php //echo $option;?>" <?php //if($option == $cRelationship) echo "selected=true" ; ?> ><?php //echo $option;?></option>
		                <?php //endforeach;?>
		                -->
                      </select>
                      </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="setting-colrght">
                  <div class="network-wrapper">
                    <div class="setting-info">
                      <div class="info-row">
                        <div class="setting-label">Religion:</div>
                        <div  class="setting-value"><!--<label><input id="religion" name="religion" type="text" style="width: 154px;"></label>-->
                        <select id="religion" name="religion" class="custom-class2">
												<!--
												<option value="empty" class="test-class-1" >Religion</option>
                                                <?php  foreach($this->config->item('religion') as $key => $option):?>
                                                <option value="<?php echo $option;?>" <?php if($option == $_POST['religion']) echo "selected='selected'" ; ?> ><?php echo $option;?></option>
                                                <?php endforeach;?>-->
                        
                      	</select>
                        </div>
                      </div>
                      <div class="info-row">
                        <div class="setting-label">Mother Tongue:</div>
                        <div class="setting-value"><!--<label><input id="mTongue" name="mTongue" type="text" style="width: 154px;"></label>-->
                        	<select id="mTongue" name="mTongue" class="custom-class2">
												<!--
												<option value="empty" class="test-class-1" >Mother Tongue</option>
                                                <?php // foreach($this->config->item('mTongue') as $key => $option):?>
                                                <option value="<?php //echo $option;?>" <?php //if($option == $_POST['mTongue']) echo "selected='selected'" ; ?> ><?php //echo $option;?></option>
                                                <?php //endforeach;?>-->
                        
                      </select>
                        </div>
                      </div>
                      <div class="info-row">
                        <div class="setting-label">Caste:</div>
                        <div class="setting-value"><label><input id="caste" name="caste" type="text" style="width: 154px;"></label></div>
                      </div>
                      <div class="info-row">
                        <div class="setting-label">Location:</div>
                        <div class="setting-value"><label><input id="location" name="location" type="text" style="width: 154px;"></label></div>
                      </div>
                      <div class="info-row">
                        <div class="setting-label">Highest Education:</div>
                        <div class="setting-value"><!--<label><input id="hEducation" name="hEducation" type="text" style="width: 154px;"></label>-->
                        	<select id="hEducation" name="hEducation" class="custom-class2">
	                            <!--
                                <option value="empty" class="test-class-1" >Education</option>
                                <?php  //foreach($this->config->item('hEducation') as $key => $option):?>
                                <option value="<?php //echo $option;?>" <?php //if($option == $_POST['hEducation']) echo "selected='selected'" ; ?> ><?php //echo $option;?></option>
                                <?php //endforeach;?>-->
							
                                </select>
                        </div>
                        
                      </div>
                      <div class="info-row">
                        <div class="setting-label">&nbsp;</div>
                        <div class="setting-value">&nbsp;</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="setting-row">
                  <div class="setting-col">
              <div class="network-wrapper padd-b10">
                <div class="setting-colft">
                      <br>
	              <div class="row margin-bt5">
	                  <textarea type="text" placeholder="Enter details about your profession" id="professionDes" name="professionDes" cols="35" rows="5"></textarea>
	              </div>
                          
                </div>
                <div class="setting-colrght">
                      <br>
	              <div class="row margin-bt5">
	                  <textarea type="text" placeholder="Enter details about your education" id="hEducationDes" name="hEducationDes" cols="35" rows="5"></textarea>
	              </div>
                      
                </div>
              </div>
                      </div>
              </div>
              <div class="setting-row">
                <div class="network-wrapper">
                  <div class="setting-col">
                    <div class="setting-colft">
                      <div class="setting-info">
                        <div class="info-row">
                          <div class="setting-label">Profession:</div>
                          <div class="setting-value"><!--<label><input id="profession" name="profession" type="text" style="width: 158px;"></label>-->
                          	<select id="profession" name="profession" class="custom-class2">
														<!--
														<option value="empty" class="test-class-1" >Profession</option>
													   <?php  //foreach($this->config->item('profession') as $key => $option):?>
													   <option value="<?php //echo $option;?>" <?php //if($option == $_POST['profession']) echo "selected='selected'" ; ?> ><?php //echo $option;?></option>
													   <?php //endforeach;?>-->
							</select>
						   
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="setting-colrght">
                      <div class="setting-info">
                        <div class="info-row">
                          <div class="setting-label">Annual salary:</div>
                          <div class="setting-value"><label><input id="salary" name="salary" type="text" style="width: 158px;"></label></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="setting-row">
                <div class="network-wrapper padd-b10">
                  <h6 class="greenHeading">RECOMMENDATION</h6>
                    <form id="form1" name="form1" class="add-frnd" method="post" action="">
                      
                      <div class="row">
	              <div class="row margin-bt5">
                        <textarea type="text" id="sRecommendation" name="sRecommendation" cols="60" rows="5" style="width:96%;"></textarea>
                      </div>
                </div>
                    </form>
                 <div id="relation-div">
                 <h6 class="greenHeading">RELATION</h6>
                    <div class="row md-select">
                      <div class="row">
                      <select id="relation" name="relation" class="custom-class2">
                        <?php  foreach($this->config->item('gRelation') as $key => $option):?>
		                <option value="<?php echo $key;?>"><?php echo $option;?></option>
		                <?php endforeach;?>
                      </select>
                      </div>
                 </div>
                  </div>
                </div>
                
              </div>
              <div class="setting-row">
                <div class="network-wrapper">
             		<ul class="network-friends">
                    	<li class="frst">Upload Image:</li>
			              <li>
			              	<a class="delete2 pimg" id="1" href="javascript:void(0);">
			              		<img width="107" height="107" src="http://<?php echo $bucket;?>.s3.amazonaws.com/files/profile_images/thumbs/<?php  echo ($canPictures2!='') ? $canPictures2 : 'no_profile_picture.jpg'; ?>" title="picture 1">
			              	</a>
			              	<a id="1" href="JavaScript:void(0);" title="Change Picture" class="delete pimg">Change</a>
			              </li>
			              
			              <li id="img2">
			              	<a class="delete2 pimg" id="2" href="javascript:void(0);">
			              		<img width="107" height="107" src="http://<?php echo $bucket;?>.s3.amazonaws.com/files/profile_images/thumbs/<?php  echo ($cPictures!='') ? $cPictures : 'no_profile_picture.jpg'; ?>" title="picture 2">
			              	</a>
						  <a id="2" href="JavaScript:void(0);" title="Change Picture" class="delete pimg">Add</a>
						 </li>
			
			              <li id="img3">
			              	<a class="delete2 pimg" id="3" href="javascript:void(0);">
			              		<img width="107" height="107" src="http://<?php echo $bucket;?>.s3.amazonaws.com/files/profile_images/thumbs/no_profile_picture.jpg" title="picture 3">
			              	</a>
			
						  <?php //echo ($canPictures3!='') ? '<a href="JavaScript:void(0);" title="Delete Picture" class="delete" id="'.$candidateFbId.'__3">Delete | </a>' : ''; ?>
						
						  <a id="3" href="JavaScript:void(0);" title="Change Picture" class="delete pimg">Add</a>
						</li>
			
			              <li id="img4">
			              	<a class="delete2 pimg" id="4" href="javascript:void(0);">
			              		<img width="107" height="107" src="http://<?php echo $bucket;?>.s3.amazonaws.com/files/profile_images/thumbs/<?php echo ($canPictures4!='') ? $canPictures4 : 'no_profile_picture.jpg'; ?>" title="picture 4">
			              	</a>
						  <a id="4" href="JavaScript:void(0);" title="Change Picture" class="delete pimg">Add</a>
						  </li>
			
			              <li id="img5" class="last">
			              	<a class="delete2 pimg" id="5" href="javascript:void(0);">
			              		<img width="107" height="107" src="http://<?php echo $bucket;?>.s3.amazonaws.com/files/profile_images/thumbs/<?php echo ($canPictures5!='') ? $canPictures5 : 'no_profile_picture.jpg'; ?>" title="picture 5">
			              	</a>
						  <a id="5" href="JavaScript:void(0);" title="Change Picture" class="delete pimg">Add</a>
						  </li>
           		</ul>
              <div align="center" style="top:50%;" id="upld_box">
                        <form action="<?php echo base_url();?>/processupload/uploadImage" method="post" enctype="multipart/form-data" id="UploadForm">
                            <input name="ImageFile" type="file" />
                            <input type="hidden" name="a_id" value= ""/>
                            <input id="fb_user_id" type="hidden" name="fb_user_id" value="<?php //echo $candidateFbId;?>"/>                               
                            <button type="submit" id="SubmitButton" class="btn-done" style="margin:4px 0 0 0;" ><span><span>Upload</span></span></button>
							<button type="button" id="CancelButton" class="btn-done" style="margin:4px 0 0 0;" ><span><span>Cancel</span></span></button>
                        </form>
						<!--<div id="output"></div>-->
     		</div> 
                  
                </div>
              </div>
              <div class="setting-row">
                <div class="network-wrapper padd-b10">
                  <div class="setting-col">
                      <form id="UploadBioDataForm" name="UploadBioDataForm" enctype="multipart/form-data" class="upload-frnd" method="post" action="<?php echo base_url();?>/processupload/uploadBiodata">
                      <div class="labels">Upload Biodata:</div>
                      	<div class="file">
			            <input type="file" id="bioData" name="bioData" />
			            <span class="button" style="width:250px;">Choose File to Upload</span>
			            </div>
			            <!-- <input id="SubmitBioDataButton" type="submit" value="Upload"/>-->
			            <button type="submit" title="Reset" class="btn-submit" id="SubmitBioDataButton">
						<span>
						<span>Upload</span>
						</span>
						</button>
                	
			            <input id="file_fb_user_id" type="hidden" name="fb_user_id" value="<?php // echo $candidateFbId;?>"/> 
			            <br><br>
			            <div id="biodata_msg" style="color: red; display: none;"></div>
               		</form> 
                  </div>
                
                </div>
                <button id="candidate_update" class="btn-submit mrgn-l15" type="submit" title="Update"><span><span>Save</span></span></button>
                <button id="reset" class="btn-submit mrgn-t10" type="submit" title="Reset"><span><span>Reset</span></span></button>
              
              <div class="spacer"></div>
              </div>
              	   	
              <div id="candidate_msg" style="color:green; margin-left:24px"></div>
              
            </div>
            <!-- Tab #3 contents end here -->
            <div id='tabs-4' class="candidate-frmSec noPadding">
              <form id="matchpreference" name="matchpreference" method="post" action="">
              <div class="network-wrapper">
                <h6>PERSONAL</h6><span class="instructional">Press Ctrl and Click for multiple selection</span>
                <div class="clear overflow">
                <div id="alert_m_pref"></div>
                <input type="hidden" id="matchpreProfileFbId" name="matchpreProfileFbId" value="<?php echo $candidateFbId; ?>">
                <div class="from-blk tabForm">
                  <div class="row">
                  <p>Mother Tongue</p>
                    <select data-validation-engine="validate[required]" id="mTongueMulti" name="mTongueMulti[]" multiple="yes" style="width:272px;">
                      <!-- <?php  //foreach($this->config->item('mTongue') as $key => $option):?>
		                <option value="<?php// echo $option;?>" <?php //if($option == 'Any') echo 'selected' ; ?> ><?php //echo $option;?></option>
		              <?php //endforeach;?>
		              -->
                    </select>
                  </div>
                  <div class="row">
                  <p>Religion</p>
                    <select data-validation-engine="validate[required]" id="mreligion" name="mreligion[]" multiple="yes" style="width:272px;">
                      <!--
                      <?php // foreach($this->config->item('religion') as $key => $option):?>
		                <option value="<?php //echo $option;?>" <?php //if($option == 'Any') echo 'selected' ; ?>  ><?php //echo $option;?></option>
		              <?php //endforeach;?>
		              -->
                    </select>
                  </div>
                </div>
                <div class="from-blk tabForm lastBL">
                  <div class="row">
                  <p>Marital Status</p>
                    <select data-validation-engine="validate[required]" id="maritalStatus" name="maritalStatus[]" multiple="yes" style="width:272px;">
											<!--
											<?php // foreach($this->config->item('fbRelationship') as $key => $option):?>
                                             <option value="<?php //echo $option;?>" <?php //if($option == 'Single') echo 'selected' ; ?> ><?php //echo $option;?></option>
                                           <?php //endforeach;?>-->
                     
                    </select>
                  </div>
                  <div class="row">
                  <p>Caste</p>
										<!--
										<label>
                                            <input data-validation-engine="validate[required,custom[onlyLetterSp]]" type="text" id="mcaste" name="mcaste" value="<?php //if(isset($_POST['caste'])) echo $_POST['caste'];?>" style="width:260px;">
                                        </label>-->
                    <select id="mcaste" name="mcaste[]" multiple="yes" style="width:272px;">
                        <option value="0">Any</option>
                    	<?php $casts = Array();
			 
						$sqlGetCaste = "SELECT * FROM rc_caste_master";  
						$pdo = Doctrine_Manager::connection()->getDbh();       
						$resultGetCaste = $pdo->query($sqlGetCaste)->fetchAll(); 
						?>
						
						
						<?php foreach($resultGetCaste as $key => $option): ?>
							    <option value="<?php echo $option['id'];?>"><?php echo $option['caste'];?></option>
						<?php endforeach; ?>
                    </select>
                  </div>
                </div>
                </div>
                <div class="row">
                	<div class="slider-block">
                <div class="sliderLabel">Age :</div>
                <div style="padding:5px 0 0 27px; float: left">From</div>
                <div style="padding:5px 113px 0 0; float: right;">To</div>	
                <p>
                	 
                	<div id="mAgeFrom" class="sliderMeterReverse"></div>
                	<div id="editagerange1" class="hidden">
                		<label style="margin-right:2px;">
                		<input type="text" id="ageFrom" name="ageFrom" style="width: 62px;"/>
                		</label>
                	</div>
                	<div id="slider-range-age" style="width:70%; margin:9px 6px 0" class="pullLeft"></div>
                	<div id="mAgeTo" class="sliderMeter"></div>
                	<div id="editagerange2" class="hidden">
                		<label style="margin-right:2px;">
                		<input type="text" id="ageTo" name="ageTo" style="width: 62px;"/>
                		</label>
                	</div> 
                	
                	<div id="editagerange-icon" class="hidden pullLeft headingText edit-button pointer" style="margin: 5px 0px 0px 10px;">&nbsp;&nbsp;</div>
                	
                	<div class="spacer"></div>
                	<div id="agerange_msg" class="hidden" style="color:green;">saved succesfully</div>
                </p>
                <div class="spacer"></div>
                 </div>
                 <div class="slider-block">
                <div class="sliderLabel">Height :</div>
                <div style="padding:5px 0 0 27px; float: left">Min</div>
                <div style="padding:5px 112px 0 0; float: right;">Max</div>	
                <p>
                	
                	<div id="mheightFrom" class="sliderMeterReverse"></div>
                	<div id="editheightrange1" class="hidden">
	                	<label style="margin-right:2px;">
	                	<input  type="text" id="heightFromFt" name="heightFromFt" style="width: 11px;"/>
	                	</label>
	                	<div class="pullLeft headingText">'</div>
	                	<label style="margin-right:2px;">
		                <input  type="text" id="heightFromInch" name="heightFromInch" style="width: 12px;"/>
		                </label>
		                <div class="pullLeft headingText">"</div>
		            </div>
                	<div id="slider-range-heightRange" style="width:70%; margin:9px 6px 0" class="pullLeft"></div>
                	<div id="mheightTo" class="sliderMeter"></div>
                	<div id="editheightrange2" class="hidden">
		                <label style="margin-right:2px;">
		                <input  type="text" id="heightToFt" name="heightToFt" style="width: 11px;"/>
		                </label>
		                <div class="pullLeft headingText">'</div>
		                <label style="margin-right:2px;">
		                <input  type="text" id="heightToInch" name="heightToInch" style="width: 12px;"/>
		                </label>
		            <div class="pullLeft headingText">"</div>
		            </div>
                	
                	<div id="editheightrange-icon" class="hidden pullLeft headingText edit-button pointer" style="margin: 5px 0px 0px 10px;">&nbsp;&nbsp;</div>
	                	
	                	<div class="spacer"></div>
	                	<div id="heightrange_msg" class="hidden" style="color:green;">saved succesfully</div>
                </p>
                <div class="spacer"></div>
                </din>
                </div>
               
								<!--
								<input  type="text" id="heightFromFt" name="heightFromFt"/>
                                <input  type="text" id="heightFromInch" name="heightFromInch"/>
                                
                                
                                <input  type="text" id="heightToFt" name="heightToFt"/>
                                <input  type="text" id="heightToInch" name="heightToInch"/>-->
                
                
              </div>
              <div class="network-wrapper">
                <h6>EDUCATIONAL</h6><span class="instructional">Press Ctrl and Click for multiple selection</span>
                <div class="row">
                  <p>Minimum Education</p>
                    <!--
                    <label>
                        <input data-validation-engine="validate[required]" type="text" placeholder="Education" id="mEducation" name="mEducation" style="width:260px;">
                    </label>-->
                    <select id="mEducation" name="mEducation[]" multiple="yes" style="width:272px;">
                      <!--
                      <?php  //foreach($this->config->item('hEducation') as $key => $option):?>
		                <option value="<?php //echo $option;?>" <?php //if($option == 'None') echo 'selected' ; ?>  ><?php //echo $option;?></option>
		              <?php //endforeach;?>
		              -->
                    </select>  
                  </div>
              </div>
              <div class="network-wrapper">
              <h6>PROFESSIONAL</h6><span class="instructional">Press Ctrl and Click for multiple selection</span>
                <div class="row">
                  <p>Profession</p>
										<!--
										<label>
                                        <input data-validation-engine="validate[required,custom[onlyLetterSp]]" type="text"  placeholder="Profession" id="mprofession" name="mprofession" value="<?php if(isset($_POST['profession'])) echo $_POST['profession'];?>" >
                                        </label>-->
                    <select id="mprofession" name="mprofession[]" multiple="yes" style="width:272px;">
                      <!--
                      <?php // foreach($this->config->item('profession') as $key => $option):?>
		                <option value="<?php //echo $option;?>" <?php //if($option == 'Salaried Person') echo 'selected' ; ?>  ><?php //echo $option;?></option>
		              <?php //endforeach;?>
		              -->
                    </select>                  
                  </div>
                 <div class="row">
                	<div class="slider-block2">	
                	<div class="sliderlabel2">Annual Salary</div>
                	<p>
                		<div class="pullLeft" id="slider-range-msalary" style="width:74%; margin:9px 0 0 6px"></div>
                		<div id="smSalary" class="sliderMeterLarge"></div>
                		<div id="editsalary2" class="hidden">
		                	
		                	 <label style="margin-left:4px;">
		                	 <input type="text" name="msalary" id="msalary" style="width: 84px" >
		                	</label>
		                </div>
                		<div id="editsalary-icon2" class="hidden pullLeft headingText edit-button" style="margin: 5px 0px 0px 10px;">&nbsp;&nbsp;</div>
	                	
	                	<div class="spacer"></div>
	                	<div id="salary_msg" class="hidden" style="color:green;">saved succesfully</div>
	                	
		                
                	</p>
                	<!--<input name="msalary" id="msalary">-->
                 </div>	
               
                </div> 
              </div>
              </div>
              <button id="update_m_preferences" class="btn-submit mrgn-l15" type="button" title="Done"><span><span>Done</span></span></button>
            	
            	<div class="spacer"></div>
            	<div id="mpre_msg" style="color:green;"></div>
              
              </form>
            </div>
            <!-- Tab #4 contents end here -->
            <div id='tabs-5'>
			<!--<p>Interests contents goes here..</p>-->
                <ul id="interests"></ul>
                <div id="xxx"></div>
                <div id="interaction"></div>
            </div>
            <!-- Tab #5 contents end here -->
            
            <div class="spacerSM">&nbsp;</div>
            
            
          </div>
        </div>
      </div>
	  
	  <div class="bg-leaf"><img src="<?php echo base_url();?>images/leaf-bg-no-repeat.png" alt=""></div>
      
	  </div>
</div>
