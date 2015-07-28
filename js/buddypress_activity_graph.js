function buddypress_activity_graph_javascript(action,function_name) {

	for(x=0;x<document.getElementById("buddypress_activity_graph").childNodes.length;x++){
	
		document.getElementById("buddypress_activity_graph").childNodes[x].setAttribute("class", '');
	
	}
	
	document.getElementById(action).setAttribute("class", "current");

	document.getElementById("buddypress_activity_graph_ajax_response").innerHTML = "";

	jQuery(document).ready(function($) {

		var data = {
			action: action
		};
		
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		$.post(ajaxurl, data, function(response) {
			
			obj = eval(response);
									
			if(obj[0].error!=undefined){
			
				document.getElementById("buddypress_activity_graph_ajax_response").innerHTML = obj[0].error;
			
			}else{
			
				window[function_name](eval(response));
				
			}
		});
	});

}