// Jquery Tab Plugin

// Wait until the DOM has loaded before querying the document
$(document).ready(function(){
	$('ul.tabs').each(function(){
		// For each set of tabs, we want to keep track of
		// which tab is active and it's associated content
		var $active, $content, $links = $(this).find('a');

		// If the location.hash matches one of the links, use that as the active tab.
		// If no match is found, use the first link as the initial active tab.
		$active = $($links.filter('[href="'+location.hash+'"]')[0] || $links[0]);
		$active.addClass('active');
		$content = $($active.attr('href'));

		// Hide the remaining content
		$links.not($active).each(function () {
			$($(this).attr('href')).hide();
		});

		// Bind the click event handler
		$(this).on('click', 'a', function(e){
			// Make the old tab inactive.
			$active.removeClass('active');
			$content.hide();

			// Update the variables with the new link and content
			$active = $(this);
			$content = $($(this).attr('href'));

			// Make the tab active.
			$active.addClass('active');
			$content.show();

			// Prevent the anchor's default click action
			e.preventDefault();
		});
	});
});


$(document).ready( function() {

//
// Enable selectBox control and bind events
//

$("#create").click( function() {
	$("SELECT").selectBox();
});

$("#destroy").click( function() {
	$("SELECT").selectBox('destroy');
});

$("#enable").click( function() {
	$("SELECT").selectBox('enable');
});

$("#disable").click( function() {
	$("SELECT").selectBox('disable');
});

$("#serialize").click( function() {
	$("#console").append('<br />-- Serialized data --<br />' + $("FORM").serialize().replace(/&/g, '<br />') + '<br /><br />');
	$("#console")[0].scrollTop = $("#console")[0].scrollHeight;
});

$("#value-1").click( function() {
	$("SELECT").selectBox('value', 1);
});

$("#value-2").click( function() {
	$("SELECT").selectBox('value', 2);
});

$("#value-2-4").click( function() {
	$("SELECT").selectBox('value', [2, 4]);
});

$("#options").click( function() {
	$("SELECT").selectBox('options', {

		'Opt Group 1': {
			'1': 'Value 1',
			'2': 'Value 2',
			'3': 'Value 3',
			'4': 'Value 4',
			'5': 'Value 5'
		},
		'Opt Group 2': {
			'6': 'Value 6',
			'7': 'Value 7',
			'8': 'Value 8',
			'9': 'Value 9',
			'10': 'Value 10'
		},
		'Opt Group 3': {
			'11': 'Value 11',
			'12': 'Value 12',
			'13': 'Value 13',
			'14': 'Value 14',
			'15': 'Value 15'
		}

	});
});

$("#default").click( function() {
	$("SELECT").selectBox('settings', {
		'menuTransition': 'default',
		'menuSpeed' : 0
	});
});

$("#fade").click( function() {
	$("SELECT").selectBox('settings', {
		'menuTransition': 'fade',
		'menuSpeed' : 'fast'
	});
});

$("#slide").click( function() {
	$("SELECT").selectBox('settings', {
		'menuTransition': 'slide',
		'menuSpeed' : 'fast'
	});
});


$("SELECT")
	.selectBox()
	.focus( function() {
		$("#console").append('Focus on ' + $(this).attr('name') + '<br />');
		$("#console")[0].scrollTop = $("#console")[0].scrollHeight;
	})
	.blur( function() {
		$("#console").append('Blur on ' + $(this).attr('name') + '<br />');
		$("#console")[0].scrollTop = $("#console")[0].scrollHeight;
	})
	.change( function() {
		$("#console").append('Change on ' + $(this).attr('name') + ': ' + $(this).val() + '<br />');
		$("#console")[0].scrollTop = $("#console")[0].scrollHeight;
	});

});

/* function to display file name when selected */
$.fn.fileName = function() {
	var $this = $(this),
	$val = $this.val(),
	valArray = $val.split('\\'),
	newVal = valArray[valArray.length-1],
	$button = $this.siblings('.button');
	if(newVal !== '') {
		$button.text(newVal);
  	}
};

$().ready(function() {
	/* on change, focus or click call function fileName */
	$('input[type=file]').bind('change focus click', function() {$(this).fileName()});
});
/* onClick, div Show/Hide for dashboard pase start here */
$(window).load(function(){
	$('.linkImg').click(function()
	{
		//alert("asa");
		
		var pid = $(this).attr('id');
		$('#c'+pid).hide();
		$('#popup'+pid).fadeIn(function(){
			
			$('.close').fadeIn();
		});
		
	});
	
	$('.popupBox .close').click(function()
	{
		//alert("asa");
		//var pid = $(this).parent().attr('id');
		//$(this).fadeOut(function(){
			//$('#'+pid).fadeOut();
		//});
		$('.popupBox').hide();
	});
});



function showPop(id)
{
	 $('.popupBox').each(function(){
                    $(this).hide();                    
        });  
         
         if ((id % 2) == 0){
            $("#match-popup-"+id).addClass("popupBoxHeight");
            $("#popupone-"+id).show();  
         }else{
             i = id-1;
             $("#match-popup-"+i).addClass("popupBoxHeight");
             $("#popupone-"+id).show();
         }
        
}

function closePop(id)
{
	//$("#showmsg-"+id).html(" ");
        //$("#showmsg-"+id).hide();
        openPanel1(id);
        $("#popupone-"+id).hide();
        
        if ((id % 2) == 0){
        $("#match-popup-"+id).removeClass("popupBoxHeight");
        } else{
         i = id-1;
         $("#match-popup-"+i).removeClass("popupBoxHeight");   
        }
        
}

function openPanel3(id){
    
    $("#panel-1-"+id).hide();
    $("#panel-2-"+id).hide();
    $("#panel-3-"+id).show();
    
}

function openPanel2(id){
   
   $("#panel-1-"+id).hide();
   $("#panel-3-"+id).hide();
   $("#panel-2-"+id).show();
   
}

function openPanel1(id){
   
   $("#panel-2-"+id).hide();
   $("#panel-3-"+id).hide();
   $("#panel-1-"+id).show();
   
}

function changeImage(imageName,id){
    var url = "http://"+location.host+"/rishtey-connect/application/files/profile_images/"+imageName;
    //alert(url1);
     
    $("#largeImage-"+id).attr('src',url);
}

function changeFBImage(imageUrl,id){
    var url = imageUrl;
    //alert(url1);
     
    $("#largeImage-"+id).attr('src',url);
}

function chatOpen(id){
        $("#chatPopup-"+id).click(function(){
		$("#chatCont-"+id).toggle();
	});
    }

/* onClick, div Show/Hide for dashboard pase end here */