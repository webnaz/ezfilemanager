/* ezFilemanager - file manager platform for TinyMCE or stand-alone
 * Copyright (c) Nazaret Armenagian (Naz)
 * Project home: http://www.webnaz.net
  * Version: 3.0
 */
$(document).ready(function(){
    var confirmFile;
    var fileCopy;
    var fileDownload;
    var deleteFiles;
    var createFolder;
    var selectedFiles;
    var maxSize;
      
    var removeError;
    var pasteError;
    var pasteEmptyError;
    var createError;
    var emptycreateError;
    var nothingError;
    var toomanyError;
    var invalidError;
    
    var xOffset = 5;
    var yOffset = 5;
    var xPreOffset = 5;
    var yPreOffset = 120;
    var id= 0;
    var y= 0;
    var alt= '';
    var UploadStart= '0';
    var messageholder = ['errormsg','successmsg','imagePreview','writable']; // define the messages types
    var domain =window.location.hostname;
    var lastChar = domain.substr(-1); // Selects the last character
    if (lastChar != '/') {         // If the last character is not a slash
        var domain =window.location.hostname+"/";
    }
    $('#progress').hide();
    $( "#upload_files" ).hide();
    $("#version-pane").html(version);


/*****************************
 * Set home pane Autoscroll height
 * 
***************************/
$(function(){
    windowHeight=$(window).height()
    containerHeight= windowHeight-100;
    console.log(containerHeight);;
    $('.pane-home').slimScroll({
        height: containerHeight + 'px'
    });
});      
/*****************************
 * Hide notification areas on page laod
***************************/
function hideNotifications(){
    var messagesHeights = new Array(); // this array will store height for each
    for (i=0; i<messageholder.length; i++)
	{
	    $('.' + messageholder[i]).hide();
            messagesHeights[i] = $('.' + messageholder[i]).outerHeight();
	    $('.' + messageholder[i]).css('top', -messagesHeights[i]); //move outside viewport	  
	}
};
hideNotifications();
/*****************************
 * IF folder is not writable
 * disable actions that need write access
***************************/
    if (writable !=1) {
       $( ".writable" ).show();
       $('#ezfm-tab a:gt(0)').hide();
       $( ".glyphicon-cog" ).hide();
       $( "#deleteFiles" ).hide();
       $("#filelist td:first-child").remove();
       $("#filelist th:first-child").remove()
    }
/*****************************
 * Hide when message is clicked
***************************/
    $('.message').click(function(){
	$(this).animate({top: -$(this).outerHeight()}, 500);
    });

/*****************************
 * TRANSLATION Start
***************************/
    i18n.init({
        lng: lang,
        debug: true,
        fallbackLng: 'en',
        load:'unspecific',
        resGetPath: "lng/__ns__-__lng__.json",
        ns: {
        namespaces: ['translation'],
        defaultNs: 'translation'
        }
    }, function(t) {
        $("#all").i18n();
        confirmFile = t("form.confirmFile");
        fileCopy = t("form.fileCopy");
        fileDownload = t("form.fileDownload");
        deleteFiles = t("form.deleteFiles");
        createFolder = t("form.createFolder");
        selectedFiles = t("form.selectedFiles");
        maxSize = t("form.maxSize");
               
        removeError = t("error.removeError");
        pasteError = t("error.pasteError");
        pasteEmptyError = t("error.pasteEmptyError");
        createError = t("error.createError");
        emptycreateError = t("error.emptycreateError");
        nothingError = t("error.nothingError");
        toomanyError = t("error.toomanyError");
        invalidError = t("error.invalidError");
        tooLargeError = t("error.tooLargeError");
    });


/*****************************
 * Activate First tab on page load
***************************/
$(function () {
    $('#ezfm-tab a:first').tab('show')
});

/*****************************
 *PREVIEW Click
 *Preview images
 *hide when button losses focus
***************************/
$( document ).on( "click", ".preview", function(event) {
    var preview ="";
    var id_array = $(this).attr("id").split("-");
    id = id_array[1];
    var alt = $(this).attr("alt");
    var width =  $("#width"+id).attr("alt");
    var height = $("#height"+id).attr("alt");
    preview +="<div  class='thumbnail'>";
    preview +="<img src='/"+folder+alt+"' id='previewid' style='width:"+width+"px;height:"+height+"px' class='img-responsive'>";
    preview +="</div>";
    $('#preview').html(preview);
    $("#preview")
        .css("top",(event.pageY -  height) + "px")
        .css("left",(event.pageX + xPreOffset) + "px");
    $( "#preview" ).fadeIn( "fast" );
});

$('.preview').bind('mouseleave', function() {
        $( "#preview" ).hide()
        $('#preview').html('');
});
  
/*****************************
 * DROPDOWN Click, Show tools drop down menu
***************************/
$( document ).on( "click", "a.dropdown-actions", function(event) {
    if(!$('.tools').is(':visible'))
    {
        var preview ="";
        var id = $(this).attr("id");
        var alt = $(this).attr("alt");
        $('ul.toolsli:lt(2)').show();
        if (alt=='folder'){$('ul.tools li:lt(2)').hide();}
        $('.download').attr("id",id);
        $('.download').attr("alt",alt);
        $('.filedelete').attr("id",id);
        $('.copy').attr("id",id);
        $('.rename').attr("id",id);
        $( ".tools" ).show();
        menuHeight=$( ".tools" ).height();
        $("#actions-options")
            .css("top",(event.pageY - menuHeight-yOffset) + "px")
            .css("left",(event.pageX - xOffset) + "px");
    }else{
        $( ".tools" ).hide()
    }
    if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; }
});
 
/*****************************
 * Hide Drop Down menu
***************************/
$('.tools').bind('mouseleave', function() {
    $( ".tools" ).hide();
});
 
/*****************************
 * INSERT in TinyMCE on Click
***************************/
$( document ).on( "click", "a.insert", function(event) {
    var id_array = $(this).attr("id").split("_");
    id = id_array[1];
    var file_extension =  $("#ext_"+id).text();
    var file_name = $("#edit_"+id).text();
    if (window.name != null && window.name !='' ){
        // alert('Plugin mode');
         console.log('Plugin mode');
         fileName = decodeURI('/'+folder+file_name+file_extension);
    //alert( window.name);
     $(opener.document.getElementsByName(window.name)).val(fileName);
     $(opener.document.getElementsByName(window.name)).focus();
     self.close() 
    }else{
         try
    {
    //alert('TinyMCE mode');
         console.log('PTinyMCE mode');
        ezDialogue.fileSubmit(http+domain+folder+file_name+file_extension);
    }
     catch(err)
    {
    // alert('Standalone mode');
    console.log('Standalone mode');
    }
    }
     $( ".tools" ).hide();
    if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; }
});

/*****************************
 * INSERT in TinyMCE on Click
***************************/
$( document ).on( "click", "a.insert-plugin", function(event) {
    var id_array = $(this).attr("id").split("_");
    id = id_array[1];
    var file_extension =  $("#ext_"+id).text();
    var file_name = $("#edit_"+id).text();
    try
  {
    //alert(folder+file_name+file_extension);
  //ezDialogue.fileSubmit(http+domain+folder+file_name+file_extension);
      fileName = decodeURI('/'+folder+file_name+file_extension);
    //alert( window.name);
     $(opener.document.getElementsByName(window.name)).val(fileName);
     $(opener.document.getElementsByName(window.name)).focus();
     self.close()
  }
     catch(err)
  {
  console.log('Plugin mode');
  }
    
  
    $( ".tools" ).hide();
    if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; }
});
/*****************************
 * TinyMCE Plugin insert 
***************************/
var ezDialogue = {
    
     init : function () {
          // remove tinymce stylesheet.
        var allLinks = document.getElementsByTagName("link");
        allLinks[allLinks.length-1].parentNode.removeChild(allLinks[allLinks.length-1]);
    },
     fileSubmit : function (FileURL) {
        var URL = FileURL;
        var win = top.tinymce.activeEditor.windowManager.getParams().window;
        win.document.getElementById(top.tinymce.activeEditor.windowManager.getParams().input).value = URL;
        top.tinymce.activeEditor.windowManager.close();
    }
}  
/*****************************
 * DOWNLOAD Click
***************************/
$("a.download").click(function(event){
    id = $(this).attr("id");
    var file_extension =  $("#ext_"+id).text();
    var file_name = $("#edit_"+id).text();
    $( ".tools" ).hide();
    hideNotifications();
    $('.successmsg').html('<h4>'+fileDownload+' '+file_name+file_extension+'</h4>' );
    jQuery('.successmsg').show();
    $('.successmsg').animate({top:"0"}, 500);
    setTimeout(function(){hideNotifications()},3000);
    window.location.href =location.protocol + '//' + location.host + location.pathname+'?folder='+folder+'&action=download&file='+file_name+file_extension;               
    if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; }
});

/*****************************
 * COPY Click
***************************/
$("a.copy").click(function(event){
    id = $(this).attr("id");
    var file_extension =  $("#ext_"+id).text();
    var file_name = $("#edit_"+id).text();
     $( ".tools" ).hide();
    hideNotifications();
    $('.successmsg').html('<h4>'+file_name+file_extension+' '+fileCopy+'</h4>' );
    jQuery('.successmsg').show();
    $('.successmsg').animate({top:"0"}, 500);
    setTimeout(function(){hideNotifications()},3000);
    $.ajax({
        type:"POST",
        url: indexFile+"?folder="+folder+"&action=copy",
        data:{ file: file_name, extension: file_extension },
        success: function(data){
            if (data !='') {
                $("#response").html("<span class=\'alert alert-danger response\'>"+data+"</span>");
                setInterval(function(){$("#response").html("")},3000);
            }
        }
    });
    if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; }
});

/*****************************
 * PASTE Click
***************************/
function doPaste(){
    $.ajax({
        type:"POST",
        url: indexFile+"?folder="+folder+"&action=paste",
        data:{ file: 'paste' },
        success: function(data){
            if (data =='error') {
                hideNotifications();
                $('.errormsg').html('<h4>'+ pasteError+'</h4>' );
                jQuery('.errormsg').show();
                $('.errormsg').animate({top:"0"}, 500);
                setTimeout(function(){hideNotifications()},2000);
            }else if (data =='emptyPaste')
            {
                 hideNotifications();
                $('.errormsg').html('<h4>'+ pasteEmptyError+'</h4>' );
                jQuery('.errormsg').show();
                $('.errormsg').animate({top:"0"}, 500);
                setTimeout(function(){hideNotifications()},3000);
            }else{
                y++;
                var data_array = data.split(".");
                $('<tr class="success" id="tr_c'+y+'"><td style="width:10px"></td><td style="width:10px"><a href="#" id="insert_c'+y+'" alt="'+data+'" class="insert"><span class="glyphicon glyphicon-import"></span></a></td><td style="width:10px"></td><td><span id="edit_c'+y+'" alt="c'+y+'">'+data_array[0]+'</span><span id="ext_c'+y+'">.'+data_array[1]+'</span></td><td></td><td></td></tr>').prependTo('table > tbody');
            }
        }
    });
    return true;
};


/*****************************
 * Prevent link click when renaming Folders
***************************/
$(".editable").click(function(event){
    if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; }
});

/*****************************
 * RENAME click
***************************/
$( document ).on( "click", "a.rename", function(event) {
    id = $(this).attr("id");
    $('#href_'+id).click(function () {return false;});
    var file_extension =  $("#ext_"+id).text();
    var file_name = $("#edit_"+id).text();
    $.post(indexFile+"?folder=" + folder + "&action=oldfilename", {"oldfilename": file_name, "extension": file_extension});
    $("#filetype-"+id).hide();
    $("#edit_"+id).trigger("rename");
     $( ".tools" ).hide();
    if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; }
});


/*****************************
 * The custome event for in-line edit
 ***************************/
$.editable.addInputType('no-space', {
    element: $.editable.types.text.element,
    plugin: function (settings, original) {
        $('input', this).bind('keypress', function (event) {
            var str = $(this).val();
            var regex = new RegExp("^[a-zA-Z0-9-_ ]*$");
            str = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (regex.test(str)) {
                return true;
            }
            if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; };
            return false;
        });
        $("input", this).blur(function(){
            $("#href_"+id).unbind('click');
            var str = $.trim($(this).val());
            if (removeSpace==1) {
                str = str.replace(/\s{2,}/g, ' ');
                str = str.replace(/\s/g,'-');
            }
            $("#href_"+id).attr('href', indexFile+'?folder='+folder+str);
            $(this).val(str);
        });
    }
});

/*****************************
 * Prepare in line edit for custom event
***************************/
$('.edit_area').editable(indexFile+'?folder='+folder+'&action=rename', {
    type   : 'no-space',
    submitdata: { _method: "POST" },
    select : true,
    submit : 'OK',
    cancel : 'cancel',
    cssclass : "editable",
    onreset: jeditableReset,
    event     : "rename",
    callback : function(value, settings) {
        $("#href_"+id).unbind('click');
        $("#filetype-"+id).show();
        }
});
/*****************************
 *evoke on cancel rename
 ***************************/
function jeditableReset(settings, original) {
    $("#href_"+id).unbind('click');
    $("#filetype-"+id).show();
};      
       
/*****************************
 *DELETE File click
 ***************************/
$( document ).on( "click", "a.filedelete", function(event) {
    id = $(this).attr("id");
    file_extension =  $("#ext_"+id).text();
    var file_name = $("#edit_"+id).text();
    var delMsg = confirm(confirmFile + ' '+ file_name+file_extension + '?');
    if (delMsg == true)
    {
        $.ajax({
            type:"POST",
            url: indexFile+"?folder="+folder+"&action=delete",
            data:{ file: file_name, extension: file_extension },
            success: function(data){
                if (data !='') {
                    $("#response").html("<span class=\'alert alert-danger response\'>"+ removeError+" \""+data+"\"</span>");
                    setInterval(function(){$("#response").html("")},3000);
                }else{
                    $(this).closest ('tr').remove ();
                    $('#tr_' + id).remove();
                }
            }
        });
        if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; };
    }else{
        if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; };
    }
});

/*****************************
 *DELETE click after ajax folder create/upload
 ***************************/
 $( document ).on( "click", "a.ajaxdelete", function(event) {
    //$('a.filedelete').click(function(event) {
    id = $(this).attr("id");
    file_extension =  $("#ext_"+id).text();
    var file_name = $("#edit_"+id).text();
    var delMsg = confirm(confirmFile + ' '+ file_name+file_extension + '?');
    if (delMsg == true)
    {
        $.ajax({
            type:"POST",
            url: indexFile+"?folder="+folder+"&action=delete",
            data:{ file: file_name, extension: file_extension },
            success: function(data){
                if (data !='') {
                    $("#response").html("<span class=\'alert alert-danger response\'>"+ removeError+" \""+data+"\"</span>");
                    setInterval(function(){$("#response").html("")},3000);
                }else{
                    $('#tr_' + id).remove();
                }
            }
            });
        if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; };
    }else{
        if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; };
    }
});
/*****************************
 *Prevent Illigal characters in Folder create
 ***************************/
$('#foldercreate', this).bind('keypress', function (event) {
    var str = $(this).val();
    var regex = new RegExp("^[a-zA-Z0-9-_\s\r\n ]*$");
    str = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (regex.test(str)) {
        return true;
    }
    if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; };
    return false;
});

        
/*****************************
 *Folder create Submit
 ***************************/
$( "#foldercreateForm" ).submit(function( event ) {
    if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; };
    var lines = $('textarea[name=foldercreate]').val().split('\n');
    if(typeof y === 'undefined'){
        y=1;
    }
    $.each(lines, function(){
        var str = $.trim(this);
            if (removeSpace==1) {
                str = str.replace(/\s{2,}/g, ' ');
                str = str.replace(/\s/g,'-');
            }
        if (str !=''){    
        $.ajax({
            type:"POST",
            url: indexFile+"?folder="+folder+"&action=createfolder",
            data:{ directory: str },
            success: function(data){
            if (data =='error')
            {
                $("#response").append("<span class=\'alert alert-danger response\'>"+ createError+": "+str+"</span>");
            }
            else if (data =='empty')
            {
                $("#response").append("<span class=\'alert alert-danger response\'>"+emptycreateError+"</span>");
            }
            else
            {
                y++;
                $('<tr class="success" id="tr_a'+y+'"><td style="width:10px"><input type="checkbox" id="record_a'+y+'" value="'+data+'" class="del" name="_del[a'+y+']" alt="a'+y+'"></td><td style="width:10px"></td><td style="width:10px"><a id="a'+y+'" class="ajaxdelete"  alt="folder" href="#"><span class="glyphicon glyphicon-trash"></span></a></td><td colspan="3"><a href="'+indexFile+'?folder='+folder+data+'/" id="href_a'+y+'"><span class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;<span id="edit_a'+y+'" alt="a'+y+'">'+data+'</span><span id="ext_a'+y+'"></span></a></td></tr>').prependTo('table > tbody');
                $('#emptyTable').remove();
                
            }
        }
    });
       setInterval(function(){$("#response").html("")},3000);  
    $("textarea[name=foldercreate]").val('');
   $('#ezfm-tab a:first').tab('show');
    $(".pane-home").slimScroll({ scrollTo: '0px' });
    if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; };   
    }
    });
  
});

/*****************************
 *Change File Type view
 ***************************/
$( "#type-control" ).change(function() {
    window.location.href =location.protocol + '//' + location.host + location.pathname+'?folder='+folder+'&type='+this.value;
 });

/***************************
 *UPLOAD do Ajax Upload
 ***************************/
$( "#uploadForm" ).submit(function( event ) {
    if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; };
    var multi = $(".filetoupload").val();
    var files = $('#filetoupload')[0].files;
    
    if (files.length > maxFileUpload) {
         hideNotifications();
                $('.errormsg').html('<h4>'+files.length+' '+ selectedFiles+': '+maxFileUpload+' '+toomanyError+'</h4>' );
                jQuery('.errormsg').show();
                $('.errormsg').animate({top:"0"}, 500);
                setTimeout(function(){hideNotifications()},3000);
       return false;   
    }
    for (var i = 0; i < files.length; i++) {
         var filesMimeType = files[i].type.split("/");
         console.log(files[i].name, files[i].type, files[i].size, filesMimeType[1]);
         //check extension
        if(!valid_extensions.test(files[i].name))
        { 
            hideNotifications();
                $('.errormsg').html('<h4>'+files[i].name+' '+ invalidError+'</h4>' );
                jQuery('.errormsg').show();
                $('.errormsg').animate({top:"0"}, 500);
                setTimeout(function(){hideNotifications()},3000);
        return false;
        }
        
        //check size
        if(files[i].size > maxFileSize)
        { 
            hideNotifications();
                $('.errormsg').html('<h4>'+files[i].name+' '+ tooLargeError+'</h4>' );
                jQuery('.errormsg').show();
                $('.errormsg').animate({top:"0"}, 500);
                setTimeout(function(){hideNotifications()},3000);
        return false;
        }
    }
    if (multi) {
        var UploadStart=1;
        var form = new FormData($('#uploadForm')[0]);
        $.ajax({
                url: indexFile+"?folder="+folder+"&action=upload",
                type: 'POST',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    if(myXhr.upload){
                        myXhr.upload.addEventListener('progress',progress, false);
                    }
                    return myXhr;
                },
                data: form,
                cache: false,
                contentType: false,
                processData: false,
                success: function (res) {
                    $(res).prependTo('table > tbody');
                    $( "#progress" ).hide();
                    $('#upload_files').html('');
                    $( "#upload_files" ).hide();
                    $('#emptyTable').remove();
                    $('#ezfm-tab a:first').tab('show');
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                    $(".pane-home").slimScroll({ scrollTo: '0px' });
                 
                    
                }
            });
    $('#uploadForm')[0].reset();
     }
     return true;
  
 });

/***************************
 *Ajax Upload progress bar
 *************************/
function progress(e){
    $( "#progress" ).show();
    if (UploadStart==0) {
        if(e.lengthComputable){
            //progress bar, works only with modern browsers
            $('progress').attr({value:e.loaded,max:e.total});
        }
    }
};
    

/***************************
 *UPload Button
 *************************/    
$('.btn-file :file').on('fileselect', function(event, numFiles, label) {
    var input = $(this).parents('.input-group').find(':text'),
    log = numFiles > 1 ? numFiles + ' '+ selectedFiles : label;
    input.val(log);
});

$(document).on('change', '.btn-file :file', function() {
    $('#upload_files').html('');
    var files = $('#filetoupload')[0].files;
    if (files.length>1) {
        for (var i = 0; i < files.length; i++) {
            $("#upload_files").append("\""+files[i].name+"\" ");
        }
    $( "#upload_files" ).show();
    }
    var input = $(this),
    numFiles = input.get(0).files ? input.get(0).files.length : 1,
    label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    
    input.trigger('fileselect', [numFiles, label]);
});
/***************
 *
 *BUTOONS
 *
 ***********************/
//SELECT ALL CHECKBOXES   
    $('#selectSelects').click(function() {
        var checkedStatus = this.checked;
         $('#filelist tbody tr').find('td:first :checkbox').each(function () {
            $(this).prop('checked', checkedStatus);
        });
    });
     
//REFRESH PAGE   
    $('#pagerefresh').click(function() {
       document.location.reload();
    });

//REFRESH PAGE   
    $('#logout').click(function() {
      document.location.href='?logout=1';
    });
//Multiaction Click
$(function() {
    $(".multiaction").click(function() {
        id = $(this).attr("id");
        if (id == "pasteFiles" ) {
            doPaste();
            return true;
        }else{
            //VERIFICATION MESSAGE
            if (CheckifCheckBoxChecked())
            {
                var delMsg = confirm(eval($(this).attr("id")));
                if (delMsg == true)
		{
		   id = $(this).attr("id");
                   if (id == "deleteFiles" ) {
                        doMultiDelete();
                   }else{
                        return false;
                   }
                return false;
		}
		return false;
            }else{
                hideNotifications();
                $('.errormsg').html('<h4>'+ nothingError+'</h4>' );
                jQuery('.errormsg').show();
                $('.errormsg').animate({top:"0"}, 500);
                setTimeout(function(){hideNotifications()},3000);
                return false;
            }
        }
    });
});
    

//Remove multiple Files and Folders
function doMultiDelete(){
    $(".del").each(function(){
        var $this = $(this);
        if($this.is(":checked")){
            var record = $('#record_'+$this.attr("alt")).val();
            var file_extension =  $("#ext_"+$this.attr("alt")).text();
            var file =  $("#edit_"+$this.attr("alt")).text();
            $.ajax({
                type:"POST",
                url: indexFile+"?folder="+folder+"&action=multidelete",
                data:{ record: record, extension: file_extension, file: file },
                success: function(data){
                    if (data !='') {
                        $("#response").append("<span class=\'alert alert-danger response\'>"+ data+removeError+" \""+record+"\"</span>");
                    }else{
                        $('#tr_' + $this.attr("alt")).remove();
                    }
                }
            });
             
        };
    });
    setInterval(function(){$("#response").html("")},3000);      
};

//hack to redraw screen for webkit browsers
$.fn.redraw = function(){
  $(this).each(function(){
    var redraw = this.offsetHeight;
     console.log('Repainting');
         
  });
};
$('.container').redraw();
});//end Documnet ready


//CHECK IF CHECKBOX SELECTED
function CheckifCheckBoxChecked()
  {
    var is_checked=false;
   $("input[type='checkbox']:checked").each(function(){
          //Check if checkbox is checked
         if($(this).is(':checked'))
         {
            is_checked=true;
         }
      }
    );
    if (is_checked==1){
    return true;
    }else{
    return false;
    }
  };

//Turn consol log off/on 
if (!DEBUG_MODE) {
    console = console || {};
    console.log = function(){};
}
