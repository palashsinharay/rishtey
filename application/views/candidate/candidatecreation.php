<link href="<?php echo base_url();?>css/style.css" rel="stylesheet" type="text/css" />
<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,400,600,700' rel='stylesheet' type='text/css' />
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
<script src="<?php echo base_url();?>js/jquery.min.js" type="text/javascript"></script>

<!-- Plugin For jQuery Select Box Start Here -->
<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>css/jquery.selectBox.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.selectBox.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/slideControl.css" />
<!-- Plugin For jQuery Select Box End Here -->

<script src="<?php echo base_url();?>js/common.js" type="text/javascript"></script>
<script src="http://connect.facebook.net/en_US/all.js"></script>
<script src="<?php  echo base_url();?>js/jquery.form.js"></script>
<script src="<?php  echo base_url();?>js/aj_file_upload.js"></script>
<script src="<?php  echo base_url();?>js/validation.js" type="text/javascript"></script>	

<!---->
<script type="text/javascript">
    function setCookie(c_name,value,exdays){
        var exdate=new Date();
        exdate.setDate(exdate.getDate() + exdays);
        var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
        document.cookie=c_name + "=" + c_value;
        }
        
        function getCookie(c_name){
        var i,x,y,ARRcookies=document.cookie.split(";");
        for (i=0;i<ARRcookies.length;i++)
        {
          x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
          y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
          x=x.replace(/^\s+|\s+$/g,"");
          if (x==c_name)
            {
            return unescape(y);
            }
          }
        }
    var activeTab = '';
    jQuery(document).ready(function(){	
	if ($.browser.msie) {
        $(".ieLable").show();
        }
        
        //code for tabs menu selection starts
        $("li.ui-state-disabled").addClass('inactive-lst2');
        $("li#four").removeClass('inactive-lst2').addClass('inactive-last');
        $("li#one").addClass('inactive-before');
		$("li#two").addClass('inactive-lst2');
		$("li#three").addClass('inactive-lst2');
        
        
        $('#one').click(function(){	
            $('#one').removeClass('activeBefore');
            $('#two').removeClass('activeBefore');
            $('#three').removeClass('activeBefore');
            $('#four').removeClass('active-last ');
            
            if($('#four').hasClass('ui-state-disabled')){
				
				if(typeof(getCookie("tab_no")) == 'undefined' || getCookie("tab_no") == ''){
					$("li#two").removeClass('inactive-lst').addClass('inactive-lst2');
                }else{
					$("li#two").removeClass('inactive-lst2').addClass('inactive-lst');	
				}
			
				$("li#three").removeClass('inactive-before');
                //$("li#three").addClass('inactive-before-wht');
				
            }else{
                $("li#four").removeClass('inactive-lst2').removeClass('inactive-last');
            }
            
        });
        
        $('#two').click(function(){
            
            if($(this).is('.ui-state-active'))	
            {
                $('#one').addClass('activeBefore');
                $('#two').removeClass('activeBefore');
                $('#three').removeClass('activeBefore');
                $('#one').removeClass('active-frst');
                $('#four').removeClass('active-last ');
                
                if($('#four').hasClass('ui-state-disabled')){
                    $("li#three").removeClass('inactive-before');
                    $("li#three").addClass('inactive-before-wht');
                }else{
                    $("li#four").removeClass('inactive-lst2').removeClass('inactive-last');
                }
                
                $("#delBiodataDiv").html('');
                
            }
        });
        $('#three').click(function(){
            if($(this).is('.ui-state-active'))	
            {	
                $('#two').addClass('activeBefore');
                $('#one').removeClass('activeBefore');
                $('#three').removeClass('activeBefore');
                $('#one').removeClass('active-frst');
                $('#four').removeClass('active-last ');
                
                if($('#four').hasClass('ui-state-disabled')){
                    $("li#three").addClass('inactive-before');
                    $("li#one").removeClass('inactive-lst2').addClass('inactive-lst');
                }else{			
                    $("li#four").removeClass('inactive-lst2').removeClass('inactive-last');
                }
                
            }
            
        });
        $('#four').click(function(){
            if($(this).is('.ui-state-active'))	
            {	
                $('#three').addClass('activeBefore');
                $('#four').addClass('active-last ');
                $('#one').removeClass('activeBefore');
                $('#two').removeClass('activeBefore');
                $('#one').removeClass('active-frst');
                
                $("li#two").removeClass('inactive-lst2');
                $("li#three").removeClass('inactive-before-wht').removeClass('inactive-before');
                
            }
        });
        //code for tabs menu selection ends
	
        
        //for tabing
        $( "#tabs" ).tabs();
        //for initlialy tabs should be disabled 
        $( "#tabs" ).tabs( "disable", 1);
        $( "#tabs" ).tabs( "disable", 2);
        $( "#tabs" ).tabs( "disable", 3);
        
        var tabNo = getCookie("tab_no");
        
        switch (tabNo){
        
        case 'tab-0':
          $( "#tabs" ).tabs( "enable", 0);
          $( "#tabs" ).tabs( "enable", 1);
          $( "#tabs" ).tabs( "enable", 2);
          $( "#tabs" ).tabs({ active: 0 });
          $('#one').click();
          break;
        case 'tab-1':
          $( "#tabs" ).tabs( "enable", 0);
          $( "#tabs" ).tabs( "enable", 1);
          $( "#tabs" ).tabs( "enable", 2);
          $( "#tabs" ).tabs({ active: 1 });
          $("li#one").removeClass('inactive-before').addClass('inactive-lst2');
          $("li#three").removeClass('inactive-lst2').addClass('inactive-before-wht');
          $("#two").click();
          break;
        case 'tab-2':
          $( "#tabs" ).tabs( "enable", 0);
          $( "#tabs" ).tabs( "enable", 1);
          $( "#tabs" ).tabs( "enable", 2);
          $( "#tabs" ).tabs({ active: 2 });
          $("li#one").removeClass('inactive-before').addClass('inactive-lst2');
          $("li#three").removeClass('inactive-lst2').addClass('inactive-before-wht');
          $('#three').click();
		  	
          break;
        case 'tab-3':
          $( "#tabs" ).tabs( "enable", 0);
          $( "#tabs" ).tabs( "enable", 1);
          $( "#tabs" ).tabs( "enable", 2);
          $( "#tabs" ).tabs( "enable", 3);
          $( "#tabs" ).tabs({ active: 3 });
          $("li#one").removeClass('inactive-before');
          $("li#three").removeClass('inactive-lst2').addClass('inactive-before-wht');
          $("#four").click();
          break;
			
        default:
        }
        
		
        //flag for tabs
        /*$("#profile_tab").click(function(){
            $( "#tabs" ).tabs({ active: 0 });
            activeTab = $( "#tabs" ).tabs( "option", "active" );
            setCookie("tab_no",'tab-'+activeTab);
        });
        $("#biodata_tab").click(function(){
            $( "#tabs" ).tabs({ active: 1 });
            activeTab = $( "#tabs" ).tabs( "option", "active" );
            setCookie("tab_no",'tab-'+activeTab);
        });
        $("#match_tab").click(function(){
           $( "#tabs" ).tabs({ active: 2 });
            activeTab = $( "#tabs" ).tabs( "option", "active" );
            setCookie("tab_no",'tab-'+activeTab);
        });
        $("#invite_tab").click(function(){
            $( "#tabs" ).tabs({ active: 3 });
            activeTab = $( "#tabs" ).tabs( "option", "active" );
            setCookie("tab_no",'tab-'+activeTab);
        });*/        
		
		
    });	
</script>
<script type="text/javascript">
    var hf1,hf2,ht1,ht2;
    
    jQuery(document).ready(function(){
        //ajax call for the autosuggest array of Caste on Candidate Profile	and Caste (multiselect) on Match preferences
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>candidate/casteGet",            
            success: function(data) {
                if(data)
                {
                    var casts = JSON.parse(data);
                    //$("#mcaste").selectBox('options',cc.casts);
                    //$("#mcaste").selectBox('value',0);
                    
                    //delete cc[0];
                    $("#caste").autocomplete({
                        source: casts
                    });
                    //casts['Any']="Any";
                    
                    
                    
                }		
                
            }
        });
        
        //ajax call for the Caste (multiselect) on Match preferences
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>candidate/casteGetForMulti",            
            success: function(data) {
                if(data)
                {
                    var casts = JSON.parse(data);
                    $("#mcaste").selectBox('options',casts);
                    $("#mcaste").selectBox('value',0);
               
                }		
                
            }
        });
        
        //ajax call for the dropdown array of RELATIONSHIP STATUS on Candidate Profile and MARITIAL STATUS(multiselect) on Match preferences
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>candidate/relationGet",            
            success: function(data) {
                if(data)
                {
                    var relations = JSON.parse(data);
                    $("#maritalStatus").selectBox('options',relations);
                    $("#maritalStatus").selectBox('value',0);
                    
                    delete relations[0];
                    relations[0]="Relationship status";
                    $("#relationship").selectBox('options',relations);
                    // var fbstatus = <?php // if ($cRelationship != '') echo $cRelationship; else echo '0'; ?>;
                    $("#relationship").selectBox('value',1);
                                       
                    
                }			
            }
        });
        
        
        //ajax call for the dropdown array for MOTHER TONGUE on Candidate Profile and MOTHER TONGUE(multiselect) on Match preferences
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>candidate/mTongueGet",            
            success: function(data) {
                if(data)
                {
                    var language = JSON.parse(data);
                    $("#mTongueMulti").selectBox('options',language);
                    $("#mTongueMulti").selectBox('value',0);
                    
                    delete language[0];
                    language[0]="Mother Tongue";
                    $("#mTongue").selectBox('options',language);
                    $("#mTongue").selectBox('value',0);
                                        		
                }		
                
            }
        });
        
        //ajax call for the dropdown array for RELIGION on Candidate Profile and RELIGION (multiselect) on Match preferences
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>candidate/religionGet",            
            success: function(data) {
                if(data)
                {
                    var religion = JSON.parse(data);
                    $("#mreligion").selectBox('options',religion);
                    $("#mreligion").selectBox('value',0);
                    
                    delete religion[0];
                    religion[0]="Religion";
                    $("#religion").selectBox('options',religion);
                    $("#religion").selectBox('value',0);
                                        		
                }		
                
            }
        });
        
        //ajax call for the dropdown array for EDUCATION on Candidate Profile and EDUCATION (multiselect) on Match preferences
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>candidate/educationGet",            
            success: function(data) {
                if(data)
                {
                    var education = JSON.parse(data);
                    $("#mEducation").selectBox('options',education);
                    $("#mEducation").selectBox('value',0);
                    delete education[0];
                    education[0]="Education";
                    $("#hEducation").selectBox('options',education);
                    $("#hEducation").selectBox('value',0);
                                      
                    		
                }		
                
            }
        });
        
        //ajax call for the dropdown array for PROFESSION on Candidate Profile and PROFESSION (multiselect) on Match preferences
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>candidate/professionGet",            
            success: function(data) {
                if(data)
                {
                    var profession = JSON.parse(data);
                    $("#mprofession").selectBox('options',profession);
                    $("#mprofession").selectBox('value',0);
                    delete profession[0];
                    profession[0]="Profession";
                    $("#profession").selectBox('options',profession);
                    $("#profession").selectBox('value',0);
                                      
                   		
                }		
                
            }
        });
        
        //ajax call for the dropdown array for recommendation relation on Candidate Profile
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>candidate/recommrelationGet",            
            success: function(data) {
                if(data)
                {   
                    if( <?php if (isset($_POST['mySelf'])) echo $_POST['mySelf']; else echo 0; ?> ){
                        var relation = "<option value='1'>Self</option>";
                        $("#relation").selectBox('options',relation);
                        $("#relation").selectBox('disable');
                    }else{
                        var relation = JSON.parse(data);
                        $("#relation").selectBox('options',relation);
                    }
                }		
                
            }
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
            if(gen ==1){
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
            var gen = $("#gender").val();
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
                $("#mheightTo").html($hFt+"'"+$hInch+"''");
                var sliderHfrom = $candidateHeight;
                var SliderHto = $maleHeight;
            }
            $( "#slider-range-heightRange" ).slider( "option", "values", [ sliderHfrom, SliderHto ] );
        }
        
        $('#dob').blur(function(){
            calcage();			
        });
        
        $('#candidate_save').click(function(event) {
            if(validateCandidate())
            {  
                var profileFbId = $("#profileFbId").val();
                var fName = $("#fName").val();
                var lName = $("#lName").val();
                var gender = $("#gender").val();
                var dob = $("#dob").val();
                var relationship = $('#relationship').val();
                var religion = $('#religion').val();
                var mTongue = $('#mTongue').val();
                var caste = $('#caste').val();
                var heightFt = $('#heightFt').val();
                var heightInch = $('#heightInch').val();
                var location = $('#location').val();
                var hEducation = $('#hEducation').val();
                var hEducationDes = $('#hEducationDes').val();
                var profession = $('#profession').val();
                var professionDes = $('#professionDes').val();
                var salary = $('#salary').val();
                var sRecommendation = $('#sRecommendation').val();
                
                $.ajax({
                    type: "POST",
                    data: {caste : caste},
                    url: "<?php echo base_url(); ?>candidate/casteInsert",            
                    success: function(data) {
                        
                        $('#CasteKey').val(data);
                        var casteKey = $('#CasteKey').val();  
                        
                        $.ajax({
                            type: "POST",
                            data: {profileFbId:profileFbId,fName : fName,
                                lName : lName,gender :gender,dob :dob,
                                relationship:relationship,religion:religion,
                                mTongue:mTongue,caste:caste,heightFt:heightFt,
                                heightInch:heightInch,location:location,hEducation:hEducation,hEducationDes:hEducationDes,profession:profession,professionDes:professionDes,salary:salary,sRecommendation:sRecommendation},
                            url: "<?php echo base_url(); ?>/candidate/addCandidateProfile",            
                            success: function(data) {
                                if(data=="true")
                                {   //alert(data);
                                    $( "#tabs" ).tabs( "enable", 1);
                                    $( "#tabs" ).tabs( "enable", 2);
                                    $( "#tabs" ).tabs({ active: 1 });
                                    activeTab = $( "#tabs" ).tabs( "option", "active" );
                                    //alert(activeTab);
                                    setCookie("tab_no",'tab-'+activeTab);
                                    $('#dob').trigger('blur');
                                    
                                    $("li#one").removeClass('inactive-before').addClass('inactive-lst2');
                                    $("li#three").removeClass('inactive-lst2');
                                    
                                    //ajax call for removing candidate from "Help Friend" panel
                                    /*$.ajax({
                                        type: "POST",
                                        
                                        data: {fbUserId : profileFbId, loggedInUser : $('#userId').val(),otherfbUserId : profileFbId },
                                        url: "<?php echo base_url(); ?>/facebooker/manageFriends",            
                                        success: function(data) {
                                            
                                            //$('.alert.success').html("Your friend's candidature is cancelled.");
                                            $('#fb-root').html(data);
                                            
                                            if(data==''){
                                                $('div#help-frnd-lft-block').removeClass('lft-block')
                                            }
                                            
                                        }
                                    });*/
                                    
                                    //ajax call for adding recommendation
                                    if($('#sRecommendation').val() !='' || /^\s/.test($('#sRecommendation').val()) == false)
                                    {                                        
                                        
                                        $.ajax({
                                            type: "POST",
                                            data: {recommenderFbId:$('#userId').val(),candidateFbId:$('#profileFbId').val(),relationship:$('#relation').val(),recommendation:$('#sRecommendation').val(),type:'G'},
                                            url:"<?php echo base_url(); ?>/candidate/addRecommendation",
                                            success: function(data){
                                                
                                            }
                                        });
                                    }
                                    
                                    $("#biodata_tab").click();
                                    
                                    
                                }else{
                                    $('#alert_c_profile').html(data);	
                                }
                            }
                        });
                        
                    }
                });
                
            }
        });
        
        //function called on Match Preferences tab click 											
        $('#match_tab').click(function(){
            
            var userid = $('#profileFbId').val();
            
            //ajax call for the autosuggest array of Caste on Candidate Profile	and Caste (multiselect) on Match preferences
            //to get the new values typed on Candidate Profile tab.
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>candidate/casteGet",            
                success: function(data) {
                    if(data)
                    {
                        var casts = JSON.parse(data);
                        $("#caste").autocomplete({
                            source: casts
                        });
                       
                       // $("#mcaste").selectBox('options',casts);
                      // $("#mcaste").selectBox('value',0);
                    }		
                }
            });
            
            //ajax call for the Caste (multiselect) on Match preferences
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>candidate/casteGetForMulti",            
                success: function(data) {
                    if(data)
                    {
                        var casts = JSON.parse(data);
                        $("#mcaste").selectBox('options',casts);
                        $("#mcaste").selectBox('value',0);

                    }		

                }
            });
            
            
            //ajax call for getting matchpre database value
            $.ajax({
                type: "POST",
                data: {userid:userid},
                url: "<?php echo base_url(); ?>dashboard/matchpre",            
                success: function(data) {
                    if(data)
                    {   var gen = $('#gender').val();
                        var matchdetails = JSON.parse(data);
                        if(matchdetails.fromAge == null){
                            calcage();
                        }else{
                            
                            $("#mAgeFrom").html(matchdetails.fromAge);
                            $("#mAgeTo").html(matchdetails.toAge);
                            $("#ageFrom").val(matchdetails.fromAge);
                            $("#ageTo").val(matchdetails.toAge);
                            $( "#slider-range-age" ).slider( "option", "values", [ matchdetails.fromAge, matchdetails.toAge ] );
                        }
                        var mc = matchdetails.caste;
                        if(matchdetails.caste == null){
                            //$('#mcaste').val("");
                        }else{
                            var mcarray = mc.split(",");
                            $("#mcaste").selectBox('value',mcarray);
                        }
                        
                        if(matchdetails.fromHeight == null){
                            calcheight();
                        }else{
                            var fromft = Math.floor(matchdetails.fromHeight/12);
                            var frominch = matchdetails.fromHeight%12;
                            
                            var toft = Math.floor(matchdetails.toHeight/12);
                            var toinch = matchdetails.toHeight%12;
                            //alert(fromft+"'"+frominch+"''"+toft+"''"+toinch+"''");
                            $("#heightFromFt").val(fromft);
                            $("#heightFromInch").val(frominch);
                            $("#mheightFrom").html(fromft+"'"+frominch+"''");
                            
                            $("#heightToFt").val(toft);
                            $("#heightToInch").val(toinch);
                            $("#mheightTo").html(toft+"'"+toinch+"''");
                            $( "#slider-range-heightRange" ).slider( "option", "values", [ matchdetails.fromHeight, matchdetails.toHeight ] );
                        }
                    }
                }
                
            });
            
        });
                
        
        //function to save match Match Preferences data to databae using ajax 
        $('#save_m_preferences').click(function(event){
            
            //vloadingAjaxDivSiteMatchPreferences() defined on validation.js
            if(validateMatchPreferences())
            {
                var matchpreProfileFbId = $("#matchpreProfileFbId").val();
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
                
				
                $.ajax({
                    type: "POST",
                    data: {matchpreProfileFbId:matchpreProfileFbId,ageFrom : ageFrom,
                        ageTo : ageTo,maritalStatus :maritalStatus,mreligion :mreligion,
                        mTongueMulti:mTongueMulti,
                        mcaste:mcaste,heightFromFt:heightFromFt,
                        heightFromInch:heightFromInch,heightToFt:heightToFt,heightToInch:heightToInch,mEducation:mEducation,mprofession:mprofession,msalary:msalary},
                    url: "<?php echo base_url(); ?>candidate/addmatchpreferences",            
                    success: function(data) {
                        if(data=="true")
                        {
							$( "#tabs" ).tabs( "enable", 3);
                            $( "#tabs" ).tabs({ active: 3 });
                                activeTab = $( "#tabs" ).tabs( "option", "active" );
                                setCookie("tab_no",'tab-'+activeTab);
                            $("#invite_tab").click();
						}		
                        else
                            $('#alert_m_pref').html(data);	
                    }
                });
                
                var candidateFbId = $("#candidateFbId").val();
                //update rc_profiles and rc_profile_relation table's status field to for profile completation status
                $.ajax({
                    type: "POST",
                    data: {candidateFbId : candidateFbId},
                    url: "<?php echo base_url(); ?>candidate/candidateStatus",            
                    success: function(data) {
						
                        if(data=="true")
                        {
                            $('#alert_candidate_creation').html("candidate creation complete");
                            
                        }
						
                        else
                            $('#alert_candidate_creation').html("candidate creation incomplete");	
						
                    }
                });
            }
            
        });
        
        
        $("#biodata_tab").click( function(){
            $("#delBiodataDiv").hide();
            //$('.file .button').text(" ");
        });
               
        
        var exts = ['doc','docx','pdf'];
        $('#biodata_save').click(function() {
            var imgVal = $('#bioData').val();			
            
            if(imgVal=='')
			{
	        alert("empty input file");
                
			}else{
                
                var ext = imgVal.split('.').pop();		
                
                if ( $.inArray ( ext, exts ) > -1 ){
                    //do nothing
                }else{
                    alert( 'Invalid file!' );
                    return false;
                }
                var bioData = $("#bioData").val();
                var biodataprofileFbId =$("#biodataprofileFbId").val();
	    		$.ajax({
                    type: "POST",
                    data: {bioData : bioData,biodataprofileFbId:biodataprofileFbId},
                    url: "<?php echo base_url(); ?>candidate/addbiodatapics",            
                    success: function(data) {
                        if(data=="true")
                        {
                            $( "#tabs" ).tabs( "enable", 2);
                            $( "#tabs" ).tabs({ active: 2 });
                            activeTab = $( "#tabs" ).tabs( "option", "active" );
                            setCookie("tab_no",'tab-'+activeTab);
                            $("#match_tab").click();
                            
                        }else{
                            $( "#tabs" ).tabs( "enable", 2);
                            $( "#tabs" ).tabs({ active: 2 });
                            activeTab = $( "#tabs" ).tabs( "option", "active" );
                            setCookie("tab_no",'tab-'+activeTab);
                            $("#match_tab").click();
                            //$('#alert_biodata').html("<h4>Message</h4> biodata upload failed.");
                        }
                        
                    }
                });
	    }
            
	});	
        
        //function called on click of done button of invite friends tab	
        $('#candidateDone').click(function(){
            var urls = "<?php echo base_url(); ?>dashboard";    
            $(location).attr('href',urls); 		    
        });		    
        
        
        $('#goToPref').click(function(){
            $( "#tabs" ).tabs({ active: 2 });
            activeTab = $( "#tabs" ).tabs( "option", "active" );
            setCookie("tab_no",'tab-'+activeTab);
            $("#match_tab").click();	
            
            $("li#three").removeClass('inactive-before-wht');
            $("li#one").removeClass('inactive-lst2').addClass('inactive-lst');
            
        });
        
        $('#goToCandi').click(function(){
            $("#profile_tab").click();	
        });
        
        $('#alert_c_profile > p').css("color","red");
                
        //code start to slider edit textbox
        $('#editheight-icon').click(function(){
            $('#editheight').toggle();
            $('#cHeight').toggle();
            $('#heightFt').blur();
            $('#heightInch').blur();
            $('#editheight-icon').hide();
            $('#editheight_msg').show();
            $('#editheight_msg').hide(5000);
        });
        
        $('#cHeight').click(function(){
            $('#editheight').toggle();
            $('#cHeight').toggle();
            $('#heightFt').blur();
            $('#heightInch').blur();
            $('#editheight-icon').show();
            $('#heightFt').focus();
            
        });
        
        $('#editsalary-icon').click(function(){
            $('#editsalary').toggle();
            $('#cSalary').toggle();
            $('#editsalary-icon').hide();
            $('#editsalary_msg').show();
            $('#editsalary_msg').hide(5000);
        });
        
        $('#cSalary').click(function(){
            $('#editsalary').toggle();
            $('#cSalary').toggle();
            $('#editsalary-icon').show();
            $('#salary').focus();
        });
        
        $('#editsalary-icon2').click(function(){
            $('#editsalary2').toggle();
            $('#smSalary').toggle();
            $('#editsalary-icon2').hide();
            $('#editsalary2_msg').show();
            $('#editsalary2_msg').hide(5000);
        });
        
        $('#smSalary').click(function(){
            $('#editsalary2').toggle();
            $('#smSalary').toggle();
            $('#editsalary-icon2').show();
            $('#msalary').focus();
        });
        
        $('#editagerange-icon').click(function(){
            $('#mAgeFrom').toggle();
            $('#editagerange1').toggle();
            $('#mAgeTo').toggle();
            $('#editagerange2').toggle();
            $('#editagerange-icon').hide();
            $('#editagerange_msg').show();
            $('#editagerange_msg').hide(5000);
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
        $('#editheightrange-icon').click(function(){
            $('#mheightFrom').toggle();
            $('#editheightrange1').toggle();
            $('#mheightTo').toggle();
            $('#editheightrange2').toggle();
            $('#editheightrange-icon').hide();
            $('#editheightrange_msg').show();
            $('#editheightrange_msg').hide(5000);
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
        //code start to slider edit textbox
           
        
        //for datepicker 
        $( "#dob" ).datepicker({
            dateFormat: "dd-mm-yy" ,
            changeYear: true,maxDate: -1, 
            yearRange: "-62:+0", 
            showOn: "both",
            buttonImage: "<?php echo base_url(); ?>images/ico-cal.png",
            buttonImageOnly: true,
            buttonText: "Date of birth",
            onClose: function(dateText, inst) {
                $('#dob').blur();
            }
        });
        
        
        //for slider height
        $( "#slider-range-height" ).slider({
            range: "min",
            value: 65,
            min: 48,
            max: 83,
            slide: function( event, ui ) {
            	var ft = Math.floor(ui.value/12);
            	var inch = ui.value%12;
                $( "#heightFt" ).val(ft);
                $( "#heightInch" ).val(inch);
                // $( "#sheightFt" ).val(ft.toPrecision(1) +" Feet");
                // $( "#sheightInch" ).val(inch +" Inch");
                $("#cHeight").html(ft+"'"+inch+"''");
                $( "#sheightFt" ).val(ft +" Feet");
                $( "#sheightInch" ).val(inch +" Inch");
                
            }
        });
        $('#heightFt').blur(function(){
            var ht = '';
            var ft = parseInt($('#heightFt').val());	
            if(ft>=4 && ft<=6){
        	var hval = parseInt($("#heightFt").val())*12+parseInt($("#heightInch").val());
        	$( "#slider-range-height" ).slider( "option", "value", hval );
        	
        	var heightFt = 0;
        	var heightInch = 0;        	
        	
        	if($('#heightFt').val()!='')
                    heightFt = $('#heightFt').val();
        	else
                    $('#heightFt').val(heightFt);
        	
        	if($('#heightInch').val()!='')
                    heightInch = $('#heightInch').val();
        	else
                    $('#heightInch').val(heightInch);
        	        	
        	
        	//$("#cHeight").html($('#heightFt').val()+"'"+$('#heightInch').val()+"''");
        	
        	
        	$("#cHeight").html(heightFt+"'"+heightInch+"''");	
            } else{
                $('#heightFt').val(0);
                $("#cHeight").html(heightFt+"'"+heightInch+"''");
            }
        });
        
        $('#heightInch').blur(function(){
            var inch = parseInt($('#heightInch').val());		
            if(inch>=0 && inch<=11){
        	var hval = parseInt($("#heightFt").val())*12+parseInt($("#heightInch").val());
        	$( "#slider-range-height" ).slider( "option", "value", hval );
        	
        	var heightFt = 0;
        	var heightInch = 0;        	
        	
        	if($('#heightFt').val()!='')
                    heightFt = $('#heightFt').val();
        	else
                    $('#heightFt').val(heightFt);
        	
        	if($('#heightInch').val()!='')
                    heightInch = $('#heightInch').val();
        	else
                    $('#heightInch').val(heightInch);
        	
        	
        	
        	$("#cHeight").html(heightFt+"'"+heightInch+"''");
            } else{
                $('#heightInch').val(0);
                $("#cHeight").html(heightFt+"'"+heightInch+"''");
                
            }
        }); 
        
        //for slider salary(candidatepro)
        $( "#slider-range-salary" ).slider({
            range: "min",
            value: 0,
            min: 0,
            max: 5000000,
            step: 25000,
            slide: function( event, ui ) {
            	var salary = ui.value;
            	
                $( "#salary" ).val(salary);
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
                var salarywithcommas = addCommas(salary);
               	$( "#ssalary" ).val("Rs."+salarywithcommas);
               	//$( "#ssalary" ).val(ui.value);
                $( "#cSalary" ).html("Rs."+salarywithcommas);
            }
        });
        $('#salary').change(function(){
            var sval = parseInt($('#salary').val());
            if($('#salary').val()=='' || /\s/g.test($('#salary').val()))
        	sval=0;
            if(sval<=5000000)
            {
        	$( "#slider-range-salary" ).slider( "option", "value", sval );
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
                $( "#cSalary" ).html("Rs."+salarywithcommas);
                $('#salary').val(sval);
            } else{
                $('#salary').val(0);
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
                
                $("#smsalary" ).val("Rs."+salarywithcommas);
                $("#smSalary").html("Rs."+salarywithcommas);
                
            }
        });  
        
        $( "#ssalary" ).val("Rs."+ "0");
        $( "#cSalary" ).html("Rs."+"0");
        $( "#salary").val(0);
        $( "#smsalary" ).val("Rs."+ "0");
        $( "#msalary" ).val(0);
        $( "#smSalary").html("Rs."+ "0");
        $( "#sheightFt" ).val( 5 +" Feet");
        $( "#sheightInch" ).val( 5 +" Inch");
        $( "#cHeight").html(5+"'"+5+"''" );
        $( "#heightFt" ).val(5);
        $( "#heightInch" ).val(5);
        
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
            if(htfrom <= htto && parseInt($("#heightFromInch").val()) <= 11 )
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
        //ajax call to get candidate profile tabs's data
        var value1 = $("#profileFbId").val();
		  $.ajax({
		    type: "POST",
			data: {profileFbId:value1},
			url: "<?php echo base_url();?>candidate/getProfileDetails",
			success: function(data) {
				
						var prodetails = JSON.parse(data);
				
						$("#fName").val(prodetails.uFname);
						$("#lName").val(prodetails.uLname);
						////$('#gender').val(prodetails.uGender);
						$('#gender').selectBox('value',prodetails.uGender);
						$('#dob').val(prodetails.uBirthday);
						$('#relationship').selectBox('value',prodetails.uRelationship);
						$('#religion').selectBox('value',prodetails.uReligion);
						$('#mTongue').selectBox('value',prodetails.uMtongue);
						$('#caste').val(prodetails.uCast);
						
						$('#location').val(prodetails.uLocation);
						$('#hEducation').selectBox('value',prodetails.uHeducation);
                                                $('#hEducationDes').selectBox('value',prodetails.uhEducationDes);
						$('#profession').selectBox('value',prodetails.uProfession);
                                                $('#professionDes').selectBox('value',prodetails.uprofessionDes);
						
						$('#sRecommendation').val(prodetails.uSrecommendation);
						$('#relation').selectBox('value',prodetails.relation);
                                                $.ajax({
                                                    type: "POST",
                                                    data: {profileFbId:value1},
                                                    url: "<?php echo base_url();?>candidate/getbioData",
                                                    success: function(data) {
                                                                    $("#biodata_msg").show();
                                                                    $("#biodata_msg").html(data);
                                                    }
                                                });
												
                                        }
                                    });
        //ajax call to get match preference tabs's data
        
        $.ajax({
		    type: "POST",
			data: {profileFbId:value1},
			url: "<?php echo base_url();?>candidate/getMatchPreferences",            
			success: function(data) {
				if(data)
					{
						var matchdetails = JSON.parse(data);
						//alert(matchdetails.motherTongue);
						
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
												
											}		
				else{alert("else");}
					
		}
        });
                                    
    }); //onready end
		
</script>
<style type='text/css'>
#slider-range-height .ui-widget-header {
        background: url("") ! important;
		background-color:#01A3FF ! important;
		/*border:none!important;*/
    }
		
#slider-range-salary .ui-widget-header {
  background: url("") ! important;
  background-color:#01A3FF ! important;
  /*border:none!important;*/
 }

#slider-range-age .ui-widget-header {
  background: url("") ! important;
  background-color:#01A3FF ! important;
  /*border:none!important;*/
 } 

#slider-range-heightRange .ui-widget-header {
  background: url("") ! important;
  background-color:#01A3FF ! important;
  /*border:none!important;*/
 }

#slider-range-msalary .ui-widget-header {
  background: url("") ! important;
  background-color:#01A3FF ! important;
  /*border:none!important;*/
 }
 
#ui-datepicker-div .ui-widget-header {
  background: url("") ! important;
  background-color:#01A3FF ! important;
  /*border:none!important;*/
 }
    
.ui-tabs-nav.ui-widget-header {
        border:none!important;
        box-shadow: none!important;
}	

#content_body .ui-widget-content{
		background:none!important;	
}
   
</style>
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
        
    $('.del_biodata').live("click", function(){
        
        var cv = $(this).attr('id');		
        //alert(cv);
		
		//show the loader image
		$('#delBiodataDiv').html('<img height="20" width="20" src="http://'+top.location.host+'/images/ajax-loader.gif" alt=""/>');
        
        $.ajax({
            type: "POST",
            data: {fbUserId : cv},
            url: "<?php echo base_url(); ?>facebooker/deleteBiodata",            
            success: function(data) {
                $('#biodataDiv').remove();
                $('#delBiodataDiv').remove();
                $('#biodata_msg').html("<div id='delBiodataDiv'>BioData deleted successfully</div>");
            }
            
        });
        
    });

</script>
<script>
    jQuery(document).ready(function() {
        
        $('.delete.invite_friends').live("click", function(){

			$('p.alert-header.invite').html('<img style="border:0px solid;" src="<?php echo base_url();?>images/ajax-loader.gif"/>');
            
            var fbUserName = $(this).attr('id');			
            var frFbUserId = $(this).attr('rel');				//unique facebook id of user whom recommendation is sent
            var otherfrFbUserId = $('#profileFbId').val();	    //unique fb user id of candidate for whom recommendation is sent
            
            //check if the user is a rishtey user and if so send mail
            var request = $.ajax({
                url: "<?php echo base_url(); ?>facebooker/chkRcUser",
                type: "POST",
                data: {fbUserId : frFbUserId, fbUserName : fbUserName,otherfrFbUserId : otherfrFbUserId ,inviteFriends : 1 }            
            });
            
            request.done(function(msg) {	
                if(msg!=0){
					
					//create profile data
					var request = $.ajax({
						url: "<?php echo base_url(); ?>facebooker/insertProfileData",
						type: "POST",
						data: {fbUserId : frFbUserId, otherfrFbUserId : otherfrFbUserId, type: 'R', loggedInUser : $('#userId').val()}            
					});					
					
					request.done(function(msg) {						
						
						//remove friend from "Invite Friends" page
						var request = $.ajax({
							url: "<?php echo base_url();?>facebooker/manageFbfriends",
							type: "POST",
							data: {fbUserId : frFbUserId, otherfbUserId : otherfrFbUserId, loggedInUser : $('#userId').val()}            
						});
						
						request.done(function(msg) {
							
							//show the success message							
							$('.invite-friendz.network-friendz').html(msg);
							$('p.alert-header.invite').removeClass('errors').addClass('successful');
							$('p.alert-header.invite.successful').html("Mail sent successfully");
							
							//alert($('.invite-friendz.network-friendz li').length);
							
							if($('.invite-friendz.network-friendz li').length == 0){
								$('ul.invite-friendz.network-friendz').append('<div><strong>No potential candidate exists</strong></div><br />');
								$('button#candidateDone').hide();
							}
							
							//create log message for initiation
							var request = $.ajax({
								url: "<?php echo base_url(); ?>facebooker/addRecommendationLog",
								type: "POST",
								data:{candidateFbId : frFbUserId, othercandidateFbId : otherfrFbUserId, type: 'R(BYMAIL)', recommenderFbId : $('#userId').val()}
							});								
							
							request.done(function(msg) {	
								//do nothing	
							});
						});						
						
						
					});
					
                }else{
                    
                    fb_dialogue_for_invite_friends(fbUserName, frFbUserId, otherfrFbUserId);
                    
                }
                
            });
        });     
        
        
        $('.delete.d').live('click', function(){								
            var a_id_val = $(this).attr('id');
            var a_id = a_id_val.split('__');
            
            //show the loader image
            $('a#'+a_id[1]+".delete.pimg").hide();	
            $('a#'+a_id_val).html('<img height="20" width="20" src="http://'+top.location.host+'/images/ajax-loader.gif" alt=""/>');
            
            //return false;
            
            $.ajax({
                type: "POST",
                data: {a_id : a_id_val},
                url: "<?php echo base_url(); ?>candidate/delCanImage",            
                success: function(data) {
                    
                    var arr = data.split('__');
                    
                    if(data!=0)	{
                        //alert(data);
                        //alert(arr[1]);
                        
                        $("li a#"+data).remove();
                        
                        $("li a#"+arr[1]).html('<img alt="no profile picture" title="no profile picture" src="http://<?php echo $bucket; ?>.s3.amazonaws.com/files/profile_images/thumbs/no_profile_picture.jpg">');
                        
                        $('a#'+a_id[1]+".delete.pimg").html('Add');
                        $('a#'+a_id[1]+".delete.pimg").show();	
                        
                    }else{
                        //print error
                    }
                    
                }
            });
        });
               
    });    
    
    function fb_dialogue_for_invite_friends(fbUserName, frFbUserId, otherfrFbUserId)
    {
        //alert(frFbUserId);
        //alert(otherfrFbUserId);
        
        $("#remove_invite_friend").val(frFbUserId);	
        $("#remove_other_invite_friend").val(otherfrFbUserId);
        
        var url = '<?php echo base_url(); ?>recommendation/index/'+otherfrFbUserId+'/'+frFbUserId;
        //alert($("#remove_invite_friend").val());
        //alert($("#remove_other_invite_friend").val());
        
        //return false;
        
        // assume we are already logged in
        FB.init({appId: '<?php echo $fbAppId; ?>', xfbml: true, cookie: true});
        
        FB.ui({
            to: fbUserName,
            method: 'send',
            name: 'Test',
            //link: 'http://ec2-50-19-66-142.compute-1.amazonaws.com/rishtey-connect'
            link: url
            //link: '<?php echo base_url(); ?>'
        },requestCallback_for_invite_friend );  
        
    }	 
    
    function requestCallback_for_invite_friend(response) {
        //Handle callback here 
        //alert('response='+response);
        
        //alert($("#remove_invite_friend").val());       
        //alert($("#remove_other_invite_friend").val());
        
        if(response == null){
			$('p.alert-header.invite').html('');
			//return false;
		}
        
        for(var key in response){				
            
            if(response[key] === true){	
                //alert(response[key]);					
                
                var request = $.ajax({

					url: "<?php echo base_url(); ?>facebooker/insertProfileData",
					type: "POST",
					data: {fbUserId : $('#remove_invite_friend').val(), otherfrFbUserId : $('#remove_other_invite_friend').val(), type: 'R', loggedInUser : $('#userId').val()}
                                
                });
                
                request.done(function(msg) {	//alert(msg); return false;                    							
                    
                    //create profile data
                    var request = $.ajax({
                       url: "<?php echo base_url(); ?>facebooker/manageFbfriends",
					   type: "POST",
					   data: {fbUserId : $('#remove_invite_friend').val(), otherfbUserId : $('#remove_other_invite_friend').val(), loggedInUser : $('#userId').val()}            
                    });
                    
                    request.done(function(msg) {
						
						//show the success message							
						$('.invite-friendz.network-friendz').html(msg);
						$('p.alert-header.invite').removeClass('errors').addClass('successful');
						$('p.alert-header.invite.successful').html("FB message sent successfully");
						
						//alert($('.invite-friendz.network-friendz li').length);
						
						if($('.invite-friendz.network-friendz li').length == 0){
							$('ul.invite-friendz.network-friendz').append('<div><strong>No potential candidate exists</strong></div><br />');
							$('button#candidateDone').hide();
						}
                        
                        //create log message for initiation
                        var request = $.ajax({
                            url: "<?php echo base_url(); ?>facebooker/addRecommendationLog",
                            type: "POST",
                            data:{candidateFbId : $('#remove_invite_friend').val(), othercandidateFbId : $('#remove_other_invite_friend').val(), type: 'R(BYFB)', recommenderFbId : $('#userId').val()}
                        });								
                        
                        request.done(function(msg) {	
                            //do nothing	
                        });
                    });
                    
                    //});					
                    
                });
                
            }
            
        }
    }

</script>

<div id="console" style="display:none"></div>

<div class="page">
	<div class="content-widget">
    <div class="wrapper-widget">

<div class="lftPan">
         	<div class="lft-block">
		      <?php echo $leftPanelCount; ?>
		    </div>  
		    <!--<div id="help-frnd-lft-block" class="lft-block">  -->
		      <?php //echo $helpFriendData; ?>
			<!--</div>-->
</div>
      <!--<div class="rghtPan" style="width: 753px;" id="content_body">-->
      <div class="rghtPan" id="content_body">
        <div id="tabs" class="candidate-prof">
          <ul class="info-pagin">
          <!--frst active-frst-->
          <li class="frst" id="one"><a id="profile_tab" href="#tabs-1"><span><big>1</big> <span class="title"><strong>Candidate Profile</strong><small>&nbsp;</small></span></span></a></li>
          <li class=" " id="two"><a id="biodata_tab" href="#tabs-2"><span><big>2</big> <span class="title"><strong>Biodata &amp; Pics</strong><small>&nbsp;</small></span></span></a></li>
          <li class=" " id="three"><a id="match_tab" href="#tabs-3"><span><big>3</big> <span class="title"><strong>Match Preferences</strong><small>&nbsp;</small></span></span></a></li>
          <li class="last " id="four"><a id="invite_tab" href="#tabs-4"><span><big>4</big> <span class="title"><strong>Invite Others</strong><small>&nbsp;</small></span></span></a></li>
		  </ul>
          
          <div id="tabs-1" class="candidate-frmSec">
          	<br class="spacer">
          	<p>Some information about the candidate is needed to be able to find good match Please verify or add the following information
            about <span><strong><?php echo $cFname." ".$cLname; ?></strong></span></p>
          <p class="line">&nbsp;</p>
          <div class="candidate-frmSec3">
            <form id="candidateprofile" name="candidateprofile" method="post" action="">
            	<div id="alert_c_profile"></div>
              <div class="network-wrapper">
                <h6>PERSONAL</h6>
                <div class="clear overflow">
                <div class="from-blk">
                  <div class="row">
                      <div class="pullLeft headingText ieLable">First Name</div>
                    <label>
                      <input placeholder="First Name" type="text" id="fName" name="fName" value="<?php echo $cFname; ?>" style="width:260px;" />
                    </label>
                    <!--<div class="error" id="error_fName"></div>-->
                    <input type="hidden" id="picture" name="picture" value="<?php echo $cPicture; ?>">
					<input type="hidden" id="profileFbId" name="profileFbId" value="<?php echo $candidateFbId; ?>">
                  </div>
                  <div class="row">
                      <div class="pullLeft headingText ieLable">Date of Birth</div>
                    <label>
                      <input type="text" id="dob" name="dob" value="<?php $dob = date("d-m-Y", strtotime($cBirthday)); echo $dob; ?>" style="width:228px;" />
                    </label>
                    </div>
                  <div class="row">
                      <div class="pullLeft headingText ieLable">Location(City, Country)</div>
                    <label>
                      <input placeholder="Location(City, Country)" type="text" id="location" name="location" value="<?php if(isset($_POST['location'])) echo $_POST['location'];?>" style="width:260px;" />
                    </label>
                  </div>
                    <div class="pullLeft headingText ieLable">Religion</div>
                  <div class="row">
                    <!--
                    <label>
                         <input placeholder="Religion" type="text" id="religion" name="religion" value="<?php //if(isset($_POST['religion'])) echo $_POST['religion'];?>" style="width:260px;" />
                    </label>
                    -->
                    
                    <select id="religion" name="religion" class="custom-class2">
                        <!--
                        <option value="empty" class="test-class-1" >Religion</option>
                                                <?php  //foreach($this->config->item('religion') as $key => $option):?>
                                                <option value="<?php //echo $option;?>" <?php //if($option == $_POST['religion']) echo "selected='selected'" ; ?> ><?php //echo $option;?></option>
                                                <?php //endforeach;?>-->
                        
                      </select>
                  </div>
                </div>
                <div class="from-blk lastBL">
                  <div class="row">
                  <div class="pullLeft headingText ieLable">Last Name</div> 
                    <label>
                      <input placeholder="Last Name" type="text" id="lName" name="lName" value="<?php echo $cLname; ?>" style="width:260px;" />
                    </label>
                  </div>
                    <div class="pullLeft headingText ieLable">Gender</div><div class="pullRight headingText ieLable">Relationship</div>
                  <div class="row sm-select"> 
                    <div class="select-candidate">
                      <select id="gender" name="gender" class="custom-class2">
                        <option value="empty" class="test-class-1" <?php if ($cGender == "0") {echo "selected='selected'";} ?>>Select Sex</option>
                        <option value="1" class="test-class-2" <?php if ($cGender == "1") {echo "selected='selected'";} ?>>Male</option>
                        <option value="2" class="test-class-3" <?php if ($cGender == "2") {echo "selected='selected'";}?>>Female</option>
                      </select>
                    </div>
                    <div class="select-candidate last">
                      <select id="relationship" name="relationship" class="custom-class2">
												<!--
												<option value="empty" class="test-class-1" >Relationship Status</option>
                                                <?php  //foreach($this->config->item('fbRelationship') as $key => $option):?>
                                                <option value="<?php //echo $option;?>" <?php //if($option == $cRelationship) echo "selected='selected'" ; ?> ><?php //echo $option;?></option>
                                                <?php //endforeach;?>-->
												
                      </select>
                    </div>
                  </div>
                    <div class="pullLeft headingText ieLable">Language</div>
                  <div class="row md-select2">
                    <!--
                    <label>
                           <input placeholder="Mother Tongue" type="text" id="mTongue" name="mTongue" value="<?php // if(isset($_POST['mTongue'])) echo $_POST['mTongue'];?>" style="width:260px;" />
                    </label>-->
                    <select id="mTongue" name="mTongue" class="custom-class2">
                        <!--
                        <option value="empty" class="test-class-1" >Mother Tongue</option>
                        <?php  //foreach($this->config->item('mTongue') as $key => $option):?>
                        <option value="<?php //echo $option;?>" <?php //if($option == $_POST['mTongue']) echo "selected='selected'" ; ?> ><?php //echo $option;?></option>
                        <?php //endforeach;?>
                        -->
                        
                      </select>
                   
                  </div>
                    <div class="pullLeft headingText ieLable">Caste</div>
                  <div class="row">
                    <label>
                      <input placeholder="Caste" type="text" id="caste" name="caste" value="<?php if(isset($_POST['caste'])) echo $_POST['caste'];?>" style="width:260px;" />
                      <input type="hidden" id="CasteKey" name="CasteKey">
                    </label>
                  </div>
                </div>
                </div>
                <!--<div class="slideControl1 margin-bt5"><img src="<?php //echo base_url();?>images/slider.png" width="551" height="30" alt="" /></div>-->
                
                <div class="row">
                <p>
                	<div class="pullLeft headingText">Height </div>
                	<div id="slider-range-height" class="pullLeft" style="width: 66%; margin: 9px 6px 0 4px;"></div>
                	<div id="cHeight" class="sliderMeter"></div>
                	<div id="editheight" class="hidden">
		                <label style="margin-right:2px;">
	                      <input type="text" id="heightFt" name="heightFt" style="width:16px;">
	                    </label>
	                    <div class="pullLeft headingText">ft </div>
	                    <label style="margin-right:2px;">
	                      <input type="text" id="heightInch" name="heightInch" style="width:16px;">
	                    </label>
	                    <div class="pullLeft headingText">in </div>
	                </div>
                	<div id="editheight-icon" class="hidden pullLeft headingText edit-button" style="margin: 7px 0px 0px 10px;">&nbsp;&nbsp;</div>
                	<div class="spacer"></div>
	                <div id="editheight_msg" class="hidden success" style="float:left; margin:4px 0 0 5px; font-size:11px;">Saved</div>
	                
                </p>
                
                <div class="spacer"></div>
                	                
	                <input type="hidden" id="sheightFt" name="sheightFt">
               		<input type="hidden" id="sheightInch" name="sheightInch">
					
                </div>
                
              </div>
              <div class="network-wrapper">
                <h6>EDUCATIONAL</h6>
                <div class="row margin-bt5">
									<!--
									<label>
                                      <input placeholder="Education" type="text" id="hEducation" name="hEducation" value="<?php //if(isset($_POST['hEducation'])) echo $_POST['hEducation'];?>" style="width:260px;" />
                                    </label>-->
                  <select id="hEducation" name="hEducation" class="custom-class2">
											   <!--
											   <option value="empty" class="test-class-1" >Education</option>
                                               <?php  //foreach($this->config->item('hEducation') as $key => $option):?>
                                               <option value="<?php //echo $option;?>" <?php //if($option == $_POST['hEducation']) echo "selected='selected'" ; ?> ><?php //echo $option;?></option>
                                               <?php //endforeach;?>-->
                       
                 </select>
                </div>
                <br>
                <div class="pullLeft headingText ieLable">Education details</div>
              	<div class="row">
	              <div class="row margin-bt5">
	                  <textarea placeholder="Enter details about your education" id="hEducationDes" name="hEducationDes" cols="60" rows="5" style="width:535px;"></textarea>
	              </div>
                </div>
              </div>
              <div class="network-wrapper">
              <h6>PROFESSIONAL</h6>
                <div class="row md-select">
                  <div class="row">
										<!--
										<select id="standard-dropdown2" name="standard-dropdown2" class="custom-class3">
                                          <option value="1" class="test-class-1" selected="selected">Profession</option>
                                          <option value="2" class="test-class-2">Salaried Person</option>
                                          <option value="3" class="test-class-3">Business Man</option>
                                          <option value="3" class="test-class-3">Social Work</option>
                                        </select>-->
                                        
										<!--
										<label>
                                        <input placeholder="Profession" type="text" id="profession" name="profession" class="input-xlarge span3" value="<?php //if(isset($_POST['profession'])) echo $_POST['profession'];?>" style="width:260px;" /> 
                                        </label>-->
                 <select id="profession" name="profession" class="custom-class2">
												<!--
												<option value="empty" class="test-class-1" >profession</option>
                                                <?php  //foreach($this->config->item('profession') as $key => $option):?>
                                                <option value="<?php //echo $option;?>" <?php //if($option == $_POST['profession']) echo "selected='selected'" ; ?> ><?php //echo $option;?></option>
                                                <?php //endforeach;?>-->
                        
                 </select>
                    
                  </div>
                    
                </div>
              <div class="pullLeft headingText ieLable">Education details</div>
              	<div class="row">
	              <div class="row margin-bt5">
	                  <textarea placeholder="Enter details about your profession" id="professionDes" name="professionDes" cols="60" rows="5" style="width:535px;"></textarea>
	              </div>
                </div>
                <!--<div class="slideControl"><img src="<?php //echo base_url();?>images/slider-salary.png" width="557" height="30" alt="" /></div>-->
                
                <div class="row">
                	<p>
                		<div class="pullLeft headingText">Annual Salary </div>
                		<div id="slider-range-salary" class="pullLeft" style="width: 53%; margin: 9px 6px 0 4px;"></div>
                		<div id="cSalary" class="sliderMeterLarge"></div>
                		<div id="editsalary" class="hidden">
			                <label style="margin-right:2px;">
		                      <input type="text" id="salary" name="salary" value="<?php if(isset($_POST['salary'])) echo $_POST['salary']; else echo "0";?>" style="width:100px;">
		                    </label>
		                </div>
                		<div id="editsalary-icon" class="hidden pullLeft headingText edit-button" style="margin: 7px 0px 0px 10px;">&nbsp;&nbsp;</div>
	                	<div class="spacer"></div>
		                <div id="editsalary_msg" class="hidden success" style="float:left; margin:4px 0 0 5px; font-size:11px;">Saved</div>
                	</p>              
                
                  <input type="hidden" type="text" id="ssalary" name="ssalary">
                
                  </div>               
                
                
              </div>
              <div class="network-wrapper">
              	 <h6>RECOMMENDATION</h6>
              	<div class="row">
	              <div class="row margin-bt5">
	                  <textarea placeholder="A Short Recommendation" id="sRecommendation" name="sRecommendation" cols="60" rows="5" style="width:535px;"></textarea>
	              </div>
                </div>
                <div id="relation-div">
                <h6>RELATION</h6>
                <div class="row md-select">
                      <div class="row">
                      <select id="relation" name="relation" class="custom-class2">
                        <!--
                        <?php  //foreach($this->config->item('gRelation') as $key => $option):?>
                        <option value="<?php //echo $key;?>"><?php //echo $option;?></option>
                        <?php //endforeach;?>
                  		-->
                        
                      </select>
                      </div>
                 </div>
                </div>
              </div>
              <button class="btn-done" type="button" id="candidate_save" title="Save Profile Information">
              	<span><span>Save Profile Information</span></span>
              	</button>
            </form>
            <p class="clearSM">&nbsp;</p>
            </div>
          </div><!--candidate profile tab end-->
          <div id="tabs-2" class="upload-photo noPadding">
		  <p>Please upload candidate’s photographs and biodata (optional)</p>
		  <p class="line">&nbsp;</p>
          <div class="network-wrapper">
          	<input type="hidden" id="biodataprofileFbId" name="biodataprofileFbId" value="<?php echo $candidateFbId; ?>">
          	<!--<p><strong>Please upload candidate’s photographs and biodata (optional)</strong></p>-->
            <!--<p class="line">&nbsp;</p>-->
            <ul class="network-friendz">
			  		
			  	
			  <?php if($cPicture=='') {?>	
			  	
              <li><a class="delete2 pimg" id="1" href="#"><img width="107" height="107" src="https://graph.facebook.com/<?php echo $can_fb_user_id; ?>/picture?width=107&height=107" title="picture 1"></a><a id="1" href="JavaScript:void(0);" title="Change Picture" class="delete pimg">Change</a></li>
			  	
			  <?php }else{?>
			  <li><a class="delete2 pimg" id="1" href="#"><img width="107" height="107" src="<?php echo $cPicture; ?>" alt="picture 1" title="picture 1"></a><a id="1" href="JavaScript:void(0);" title="Change Picture" class="delete pimg">Change</a></li>
			  <?php }?>
			  
              <li>
              	<a class="delete2 pimg" id="2" href="#"><img width="107" height="107" src="http://<?php echo $bucket; ?>.s3.amazonaws.com/files/profile_images/thumbs/<?php  echo ($canPictures2!='') ? $canPictures2 : 'no_profile_picture.jpg'; ?>" alt="picture 2" title="picture 2">	
              	</a>
			  <?php echo ($canPictures2!='') ? '<a href="JavaScript:void(0);" title="Delete Picture" class="delete d" id="'.$candidateFbId.'__2">Delete  </a>' : ''; ?>
			  <a id="2" href="JavaScript:void(0);" title="Change Picture" class="delete pimg"><?php echo ($canPictures2!='') ? 'Change' : 'Add' ?></a>
			  </li>
			  	
              <li><a class="delete2 pimg" id="3" href="#"><img width="107" height="107" src="http://<?php echo $bucket; ?>.s3.amazonaws.com/files/profile_images/thumbs/<?php echo ($canPictures3!='') ? $canPictures3 : 'no_profile_picture.jpg'; ?>" alt="picture 3" title="picture 3"></a>
			  
			  <?php echo ($canPictures3!='') ? '<a href="JavaScript:void(0);" title="Delete Picture" class="delete d" id="'.$candidateFbId.'__3">Delete  </a>' : ''; ?>
			  	
			  <a id="3" href="JavaScript:void(0);" title="Change Picture" class="delete pimg"><?php echo ($canPictures3!='') ? 'Change' : 'Add' ?></a>
			  	
			  </li>
			  	
              <li><a class="delete2 pimg" id="4" href="#"><img width="107" height="107" src="http://<?php echo $bucket; ?>.s3.amazonaws.com/files/profile_images/thumbs/<?php echo ($canPictures4!='') ? $canPictures4 : 'no_profile_picture.jpg'; ?>" alt="picture 4" title="picture 4"></a>
			  <?php echo ($canPictures4!='') ? '<a href="JavaScript:void(0);" title="Delete Picture" class="delete d" id="'.$candidateFbId.'__4">Delete  </a>' : ''; ?>
			  
			  <a id="4" href="JavaScript:void(0);" title="Change Picture" class="delete pimg"><?php echo ($canPictures4!='') ? 'Change' : 'Add' ?></a>
			  
			  </li>
			  	
              <li class="last"><a class="delete2 pimg" id="5" href="#"><img width="107" height="107" src="http://<?php echo $bucket; ?>.s3.amazonaws.com/files/profile_images/thumbs/<?php echo ($canPictures5!='') ? $canPictures5 : 'no_profile_picture.jpg'; ?>" alt="picture 5" title="picture 5"></a>
			  <?php echo ($canPictures5!='') ? '<a href="JavaScript:void(0);" title="Delete Picture" class="delete d" id="'.$candidateFbId.'__5">Delete  </a>' : ''; ?>
			  
			  <a id="5" href="JavaScript:void(0);" title="Change Picture" class="delete pimg"><?php echo ($canPictures5!='') ? 'Change' : 'Add' ?></a>
			  
			  </li>
            </ul>
                     
			
			<div align="center" id="upld_box">
                        <form action="<?php echo base_url();?>/processupload/uploadImage" method="post" enctype="multipart/form-data" id="UploadForm">
                            <input name="ImageFile" type="file" />
                            <input type="hidden" name="a_id" value= ""/>
                            <input type="hidden" name="fb_user_id" value="<?php echo $candidateFbId;?>"/>                               
                            <button type="submit" id="SubmitButton" class="btn-done" style="margin:4px 0 0 0;" ><span><span>Upload</span></span></button>
							<button type="button" id="CancelButton" class="btn-done" style="margin:4px 0 0 0;" ><span><span>Cancel</span></span></button>
                        </form>
						<!--<div id="output"></div>-->
     		</div>
            
          </div>
          <p class="line">&nbsp;</p>
          <form id="UploadBioDataForm" name="UploadBioDataForm" enctype="multipart/form-data" class="upload-frnd" method="post" action="<?php echo base_url();?>/processupload/uploadBiodata">
            <div class="label">Upload Biodata:</div>
            <div class="file">
            <input type="file" id="bioData" name="bioData" />
            <span class="button" style="width:250px;">Choose File to Upload</span>
            </div>
            <button type="submit" id="SubmitBioDataButton" class="btn-done" style="margin:4px 0 0 0;"><span><span>Upload</span></span></button>
            <!--<input id="SubmitBioDataButton" type="submit" value="Upload"/>-->
            
            <input type="hidden" name="fb_user_id" value="<?php echo $candidateFbId;?>"/>
            <p class="clearSM">&nbsp;</p> 
            <div id="biodata_msg" style="color: red; display: none;"></div> 
          </form>
          <button id="goToCandi" class="btn-done" style="margin:4px 0 0 0;"><span><span>Back</span></span></button>
          <button id="goToPref" class="btn-done" style="margin:4px 0 0 0;"><span><span>Next</span></span></button>
          <p class="clearSM">&nbsp;</p>
          </div><!--biodata tab ends-->

        <div id="tabs-3" class="candidate-frmSec">
        	<br class="spacer">
        	<p>We will be able to find matches if we know what kind of person you are looking for.<br />
            Please verify or add the following information about <span><strong><?php echo $cFname." ".$cLname; ?></strong></span></p>
            <p class="line">&nbsp;</p>
            <form id="matchpreference" name="matchpreference" method="post" action="">
              <div class="network-wrapper">
                <h6>PERSONAL</h6><span class="instructional">Press Ctrl and Click for multiple selection</span>
                <div class="clear overflow">
                <div id="alert_m_pref"></div>
                <input type="hidden" id="matchpreProfileFbId" name="matchpreProfileFbId" value="<?php echo $candidateFbId; ?>">
                <div class="from-blk">
                  <div class="row">
                  <p>Mother Tongue</p>
                    <select id="mTongueMulti" name="mTongueMulti[]" multiple="yes" style="width:272px;">
                    <?php  foreach($this->config->item('mTongue') as $key => $option):?>
		                <option value="<?php echo $option;?>" <?php if($option == 'Any') echo 'selected' ; ?> ><?php echo $option;?></option>
		              <?php endforeach;?>
                    </select>
                  </div>
                  <div class="row">
                  <p>Religion</p>
                    <select id="mreligion" name="mreligion[]" multiple="yes" style="width:272px;">
                      <!--
                      	<?php  //foreach($this->config->item('religion') as $key => $option):?>
		                <option value="<?php //echo $option;?>" <?php //if($option == 'Any') echo 'selected' ; ?>  ><?php //echo $option;?></option>
		              <?php //endforeach;?>
		              -->
                    </select>
                  </div>
                </div>
                <div class="from-blk lastBL">
                  <div class="row">
                  <p>Marital Status</p>
                    <select  id="maritalStatus" name="maritalStatus[]" multiple="yes" style="width:272px;">
                     <!--
                      <?php  //foreach($this->config->item('fbRelationship') as $key => $option):?>
                                             <option value="<?php // echo $option;?>" <?php //if($option == 'Single') echo 'selected' ; ?> ><?php //echo $option;?></option>
                                           <?php //endforeach;?>-->
                     
                    </select>
                  </div>
                  <div class="row">
                  <p>Caste</p>
											<!--
											<label>
                                            <input type="text" id="mcaste" name="mcaste" value="<?php //if(isset($_POST['caste'])) echo $_POST['caste'];?>" style="width:260px;">
											</label>-->
                    <select id="mcaste" name="mcaste[]" multiple="yes" style="width:272px;">
						<!--<option value="0">Any</option>-->
                        <?php //$casts = Array();
						//$sqlGetCaste2 = "SELECT * FROM rc_caste_master";  
						$pdo = Doctrine_Manager::connection()->getDbh();       
						//$resultGetCaste = $pdo->query($sqlGetCaste)->fetchAll(); 
						?>
						
						
                        <?php //foreach($resultGetCaste as $key => $option): ?>
						<!--<option value="<?php //echo $option['id'];?>"><?php //echo $option['caste'];?></option>-->
						<?php //endforeach; ?>
                    	
                    </select>
                  </div>
                </div>
                </div>
                <div class="row">
                <div class="slider-block">	
                <div class="sliderLabel">Age :</div>	
                <p>
                	<div class="from" style="line-height:30px; padding:0 2px 0 0;">From</div> 
                	<div id="mAgeFrom" class="sliderMeterReverse"></div>
                	<div id="editagerange1" class="hidden">
                		<label style="margin-right:2px;">
                		<input type="text" id="ageFrom" name="ageFrom" style="width: 62px;"/>
                		</label>
                	</div>
                	<div id="slider-range-age" style="width:54%; margin:9px 6px 0" class="pullLeft"></div>
                	<div id="mAgeTo" class="sliderMeter"></div>
                	<div id="editagerange2" class="hidden">
                		<label style="margin-right:2px;">
                		<input type="text" id="ageTo" name="ageTo" style="width: 62px;"/>
                		</label>
                	</div> 
                	<div class="to" style="line-height:30px; padding:0 0 0 2px;">To</div>
                	<div id="editagerange-icon" class="hidden pullLeft headingText edit-button" style="margin: 5px 0px 0px 10px;">&nbsp;&nbsp;</div>
                	<div class="spacer"></div>
                	<div id="editagerange_msg" class="hidden" style="color:green;">saved succesfully</div>	
                </p>
                <div class="spacer"></div>
                                
                </div>
                
                <div class="slider-block">	
                <div class="sliderLabel">Height :</div>
                <p>
                	<div class="from" style="line-height:30px; padding:0 2px 0 0;">Min</div>
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
                	<div id="slider-range-heightRange" style="width:54%; margin:9px 6px 0" class="pullLeft"></div>
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
                	<div class="to" style="line-height:30px; padding:0 0 0 2px;">Max</div>
                	<div id="editheightrange-icon" class="hidden pullLeft headingText edit-button" style="margin: 5px 0px 0px 10px;">&nbsp;&nbsp;</div>
	                	
	                	<div class="spacer"></div>
	                	<div id="editheightrange_msg" class="hidden" style="color:green;">saved succesfully</div>
	                	                
		                
                </p>
                <div class="spacer"></div>
                </div>
                </div>
								<!--
								<div class="slideControl1"><img src="<?php echo base_url();?>images/slider-age.png" width="551" height="36" alt="" /></div>
                                <div class="slideControl1 margin-bt5"><img src="<?php echo base_url();?>images/slider-height.png" width="551" height="36" alt="" /></div>-->
								
                <!--<p>Height From Feet&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Height From Inch</p>-->
                
                
                <!--<p>Height To Feet &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Height To Inch</p>-->
                
                
              </div>
              <div class="network-wrapper">
                <h6>EDUCATIONAL</h6><span class="instructional">Press Ctrl and Click for multiple selection</span>
                <div class="row">
                  <p>Education</p>
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
					<select id="mprofession" name="mprofession[]" multiple="yes" style="width:272px;">
                      <!--
                      	<?php  //foreach($this->config->item('profession') as $key => $option):?>
		                <option value="<?php //echo $option;?>" <?php //if($option == 'Salaried Person') echo 'selected' ; ?>  ><?php //echo $option;?></option>
		              <?php //endforeach;?>
		              -->
                    </select>                  
                  </div>
                 <div class="row">
                 	<div class="slider-block2">	
                	<div class="sliderlabel2">Annual Salary</div>
                	<p>
                		<div id="slider-range-msalary" style="width:53%; margin:9px 0 0 6px" class="pullLeft"></div>
                		<div id="smSalary" class="sliderMeterLarge"></div>
                		<div id="editsalary2" class="hidden">	
		                	 <label style="margin-left:4px;">
		                	 <input type="text" name="msalary" id="msalary" style="width: 84px" >
		                	 </label>
		                </div>
                		<div id="editsalary-icon2" class="hidden pullLeft headingText edit-button" style="margin: 5px 0px 0px 10px;">&nbsp;&nbsp;</div>
	                	<div class="spacer"></div>
	                	<div id="editsalary2_msg" class="hidden" style="color:green;">saved succesfully</div>
                	</p>
                	         
                
                <input type="hidden" name="smsalary" id="smsalary">
               	  </div>	
                  </div> 
              </div>
              <button id="save_m_preferences" class="btn-done" type="button" title="Save Match Preferences"><span><span>Save Match Preferences</span></span></button>
            </form>
            <p class="clearSM">&nbsp;</p>
          </div><!--tab end for match pre-->
          <div id="tabs-4" class="upload-photo noPadding">
          <br class="spacer">
          <p>Why Not Invite others to help you find a match for <span><strong><?php echo $cFname." ".$cLname; ?></strong></span> within their networks too.</p>
          <p class="line">&nbsp;</p>
		  
		  <!--<p class="alert invite"></p>-->
		  
          <div class="network-wrapper">
            <ul class="invite-friendz network-friendz">
              <?php $i=1; ?>
              <?php //print_r($this->session->userdata); ?>
              <?php foreach($allFbFriends['records'] as $row): ?>
				
				<?php 
				
				//check if message already sent
				$sqlchkRecExists = "SELECT * FROM rc_profile_relation WHERE fb_user_id = ".$row['fb_user_id']."
				AND guardian_fk_loc_fb_id = ".$this->session->userdata['loggedIn']." AND recm_msg_sent=1 AND type='R'";
				
				$resultchkRecExists = $pdo->query($sqlchkRecExists)->fetchAll();
			
				if(count($resultchkRecExists) == 0){
				
				?>			
				
              	<li class=" <?php if ($i%5==0) echo ' last'; ?>">
              		<img style="cursor:pointer; " rel="<?php echo $row['fb_user_id'] ?>" height="107" width="107" src="https://graph.facebook.com/<?php echo $row['fb_user_id'] ?>/picture?width=107&height=107" alt="<?php echo $row['name'] ?>"  title="<?php echo $row['name'] ?>" id="<?php echo $row['username'] ?>" class="rcimg delete invite_friends" />
              		<a rel="<?php echo $row['fb_user_id'] ?>" id="<?php echo $row['username'] ?>" href="JavaScript:void(0);" title="Invite Friends" class="delete invite_friends">Invite <?php echo $row['fname'] ?></a>
					<input type="hidden" id="candidate" name="candidate" value=<?php echo $row['fb_user_id']; ?> />
              	</li>
              <?php $i++; ?>
			  	
			  <?php } ?>	
			  	
              <?php endforeach; ?>
              <!--
              <li><img src="images/img1.gif" alt="" /><a href="JavaScript:void(0);" title="Invite Friends" class="delete">Invite</a></li>
              -->
              
            </ul>
          </div>
          <form id="invitefriend" action="#" method="post">
          <input type="hidden" id="candidateFbId" name="candidateFbId" value="<?php echo $candidateFbId; ?>">
		  <input type="hidden" id="remove_invite_friend" name="remove_invite_friend" value="" />
		  <input type="hidden" id="remove_other_invite_friend" name="remove_other_invite_friend" value="" />
          <button id="candidateDone" class="btn-done" type="button" title="Done"><span><span>Done</span></span></button>
          <p class="clearSM">&nbsp;</p>
        </div>
        </div> <!--main tab div end-->
      </div>
    </div>
    <div class="bg-leaf"><img src="<?php echo base_url();?>images/leaf-bg-no-repeat.png" alt=""></div>
    </div>
    </div>
