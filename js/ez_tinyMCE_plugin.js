/* Generic Plugin for text input area.
 * Copyright (c) Nazaret Armenagian (Naz)
 * Project home: http://www.webnaz.net
  * Version: 1.0
 */
$(document).ready(function(){
    $('#ezfm').dblclick(function() {
        var rel =$(this).attr('rel');
        var windowname =$(this).attr('name');
        var querystr= "";
        if (typeof rel != 'undefined' && rel != '')
	    {
		var querystr= "?folder="+rel;   
	    }
	var newWindow = window.open(ezfmURL+"index.php"+querystr,windowname,"scrollbars=yes,status=yes,menubar=no,toolbar=no,resizeable=no,height="+ezfmHeight+",width="+ezfmWidth);
	   
    });
});
