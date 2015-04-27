<?php if ( ! defined('CONFIG_FILE')) exit('No direct script access allowed');?>
<?php
/* PHP Class/Helper - file manager platform for TinyMCE or stand-alone
 * Copyright (c) Nazaret Armenagian (Naz)
 * Project home: http://www.webnaz.net
 * Version: 3.0 RC
*/

class ezHelpers {
    function __construct() {
        if(isset($_GET['logout'])){
           setcookie(COOKIE_NAME, "", 1, "/");
           $this->helper_js_redirect("login.php");
        }
   }
/**************************
    * $Id: change_case 11-11-2013 Naz $
    * Preserve file/dir case or force to lowercase
    * 
    */
function helper_preserve_case($str){
    if (PRESERVE_CASE){
        return $this->helper_sanitize_name($str);
    }else{
        return strtolower($this->helper_sanitize_name($str));
    }
}

/**************************
* $Id: helper_sanitize_name 11-11-2013 Naz $
* Although we clean the file/dir name with javascript
* Clean the file or directory name for extra security
* If no spaces are allowed replace them with "-"
*/
function helper_sanitize_name($str){
    $this->bad = array(
            "<!--",
            "-->",
            "'",
            "<",
            ">",
            '"',
            '&',
            '$',
            '=',
            ';',
            '?',
            '/',
            '%',
            '[',
            ']',
            '|',
            "%20",
            "%22",
            "%3c",	// <
            "%253c", 	// <
            "%3e", 	// >
            "%0e", 	// >
            "%28", 	// (
            "%29", 	// )
            "%2528", 	// (
            "%26", 	// &
            "%24", 	// $
            "%3f", 	// ?
            "%3b", 	// ;
            "%3d"	// =
            );
    $str = str_replace($this->bad, '', $str);
    $str = str_replace("[^A-Za-z0-9 _-]", "", $str);
    if (REMOVE_SPACE){
        $str = preg_replace('/\s+/', '-',$str);
    }else{
         $str = preg_replace('/\s+/', ' ',$str);
    }
    return stripslashes($str);
}
/********
* $Id: helper_protected_filearray 11-11-2013 Naz $
* read HIDE_FILES from config
* and prepare an array
* 
*/
function helper_protected_filearray(){
    $this->protected_array = explode(",",HIDDEN_FILES);
    return  $this->protected_array;
}
/********
* $Id: helper_get_extension  12-10-2009 Naz $
* Get file extension and
* return the extension
*/
function helper_get_extension($file){
    return strtolower(substr($file, strrpos($file, '.') + 1));
}


/********
* $Id: ezf_file_type_show  17-11-2013 Naz $
* Returns what filetype to show of the
* ezfilemanager filetype multidimensional array
*/    
function ezf_file_type_show(){
    $this->file_type= $this->helper_file_extensions();
    (isset($_SESSION['type'])) ? $this->filetype=$this->file_type['filetype'][$_SESSION['type']] : $this->filetype=$this->file_type['filetype']['all'];
    return $this->filetype;
}
/********
* $Id: helper_file_information  12-10-2009 Naz $
* Returns ezf_file_types multidimensional array
* For file icons
*/ 
function helper_file_information($file) {
    $this->height=false;
    $this->width = PREVIEW_WIDTH;
    $this->extension="";
    $this->file_size = $this->helper_bytestostring(filesize(UPLOAD_FOLDER_PATH.$file));
    $this->file_date = date(DATE_FORMAT, filemtime(UPLOAD_FOLDER_PATH.$file));
    $this->file_type= $this->helper_file_extensions();
    $this->extension= ".".$this->helper_get_extension($file);
     if(in_array($this->helper_get_extension($file), $this->file_type['filetype']['image'])){
      
    $this->filename = UPLOAD_FOLDER_PATH.$file;
    $this->fileinfo =getimagesize($this->filename);
    list($width_orig, $height_orig) = $this->fileinfo;
    $this->new_size=array();
    $this->new_size=$this->helper_resize_proportional($width_orig,$height_orig);
    $this->width = $this->new_size[0];
    $this->height = $this->new_size[1];
    }
    // Match file extension with icon, type , preview and height (if image)
    foreach ($this->helper_file_info() as $this->type) {
            foreach ($this->type[0] as $this->the_extension) {
                    if ($this->helper_get_extension($file) == $this->the_extension)
                            return array("icon" => $this->type[1], "type" => $this->type[2], "class" => $this->type[3],"dimension"=>$this->width."_".$this->height, "filesize"=>$this->file_size, "file_date"=>$this->file_date, "extension"=>$this->extension);
            }
    }
    // If it is not in our $ezfilemanager_mime_types array
    return array("icon" => "unknown.gif", "type" => "Unknown", "class" => false,"dimension"=>false, "filesize"=>$this->file_size, "file_date"=>$this->file_date);
}
/********
* $Id: helper_bytestostring 011 18-08-2008 Naz $
* Crude and dirty bites conversion to KB or MB
*/
function helper_bytestostring($size, $precision = 2) {
    if ($size <= 0){
        return "0 ".KB; //just play it safe
    }elseif ($size < 1048576){//if smaller than 1MiB
        return number_format($size/1024, $precision)." ".KB;
    }else{
        return number_format($size/1048576, $precision)." ".MB;
    }
}
/********
* $Id: checkAllowedType 013 19-09-2008 Naz $
* Check if within allowed upload file types
*
*/
function helper_checkAllowedType($ext,$i){
    $this->ezfilemanager= $this->helper_file_extensions();
    if(in_array($ext, $this->ezfilemanager['filetype']['all'])){
	return false;
    }else{
	
        return TRUE;
    }
}
/********
* $Id: helper_file_extensions  12-10-2009 Naz $
* Returns ezfilemanager filetype multidimensional array
*/ 
function helper_file_extensions(){
    $this->ezfilemanager = array();
    $this->ezfilemanager['filetype']['media'] =  explode(',',MEDIA_FILES);
    $this->ezfilemanager['filetype']['file']  =  explode(',',OTHER_FILES);
    $this->ezfilemanager['filetype']['image'] =  explode(',',IMAGE_FILES);
    $this->ezfilemanager['filetype']['all'] =  array_merge($this->ezfilemanager['filetype']['media'],$this->ezfilemanager['filetype']['file'],$this->ezfilemanager['filetype']['image']);
    return $this->ezfilemanager;
}
/********
* $Id: helper_resize_proportional  21-11-2013 Naz $
* calculates the proportional resized images
* width and height based on PREVIEW_WIDTH
* in config
*/
function helper_resize_proportional($width,$height){
    $this->new_size = array();
    $this->ratio = $width/$height;
    if ($width  <= PREVIEW_WIDTH && $height<=PREVIEW_WIDTH){
        $this->new_size[0]=$width;
        $this->new_size[1]=$height;
        return $this->new_size;
    }else{
        if ($this->ratio  > 1) {
            $this->nheight = floor(($height*PREVIEW_WIDTH)/$width);
            $this->nwidth = PREVIEW_WIDTH;
        }else{
        $this->nheight = PREVIEW_WIDTH;
        $this->nwidth = floor(($width*PREVIEW_WIDTH)/$height);
        }
    }
    $this->helper_resize_proportional($this->nwidth,$this->nheight);
    $this->new_size[0]=$this->nwidth;
    $this->new_size[1]=$this->nheight;
    return $this->new_size;
}
/********
* $Id: helper_file_info  12-10-2009 Naz $
* Returns ezf_file_types multidimensional array
* used in helper_file_information
*/ 
function helper_file_info(){
    $this->ezf_file_types = array(
    array(array("exe", "com"), "glyphicon-file", "exe", "popup"),
    array(array("zip", "gzip", "rar", "gz", "tar"), "glyphicon-compressed", "archive", "popup"),
    array(array("htm", "html", "php", "jsp", "asp"), "glyphicon-file", "html", "popup"),
    array(array("mov", "mpg", "avi", "asf", "mpeg", "wmv","mp4","qt"), "glyphicon-film", "movie", "popup"),
    array(array("aif", "aiff", "wav", "mp3"), "glyphicon-file", "sound", "popup"),
    array(array("swf","flv"), "glyphicon-file", "Flash file", "popup"),
    array(array("ppt", "pps"), "glyphicon-file", "powerpoint", "popup"),
    array(array("rtf"), "glyphicon-book", "document", "popup"),
    array(array("css"), "glyphicon-file", "css", "popup"),
    array(array("js", "json"), "glyphicon-file", "script", "popup"),
    array(array("doc","docx"), "glyphicon-book", "word", "popup",),
    array(array("pdf"), "glyphicon-book", "pdf", "popup"),
    array(array("xls"), "glyphicon-file", "excel", "popup"),
    array(array("txt"), "glyphicon-file", "txt", "popup"),
    array(array("xml", "xsl", "dtd"), "glyphicon-list-alt", "xml", "popup"),
    array(array("gif", "jpg", "jpeg", "png", "bmp", "tif"), "glyphicon-picture", "image", "preview")
        );
     return $this->ezf_file_types;
}
/********
* $Id: helper_sort_case  12-10-2009 Naz $
* Sort file list taking into consideration character case
*/
static function helper_sort_case($a, $b){
    if (strtolower($a) == strtolower($b)) {
        return 0;
    }
    return (strtolower($a) < strtolower($b)) ? -1 : 1;
}
/********
* $Id: helper_strip_extension  18-11-2013 Naz $
* Strip the file extension and
* return the filename without the extension
*/
function helper_strip_extension($file){
    if (substr($file, 0, strrpos($file, '.')) !=''){
        return substr($file, 0, strrpos($file, '.'));
    }else{
        return $file;
        
    }
}
/*************************
 * $Id: helper_redirect_onhack  1-11-2013 Naz $
 * Redirect if dissalowed chars in path
 * Redirect if browsing outside of UPLOAD folder
 ************************/
function helper_redirect_onhack(){
    
    //if UPLOAD_FOLDER is not set and ROOT_ACCESS=false
    if (UPLOAD_FOLDER =='/' &&  USER_FOLDER =='' &&  !ROOT_ACCESS){
         exit('folder-error: No direct script access allowed');
    }
    //dissalowed chars in path
    if (preg_match(PATH_BLOCK_CHARS, $_GET['folder']) > 0) {
        $redirect =INDEX."?folder=".UPLOAD_FOLDER."&type=all";
        $this->helper_js_redirect($redirect);
        exit();
    }
    
    //browsing outside of UPLOAD folder
    if (substr($_GET['folder'], 0, strlen(UPLOAD_FOLDER))!= UPLOAD_FOLDER){
        $redirect =INDEX."?folder=".UPLOAD_FOLDER."&type=all";
       $this->helper_js_redirect($redirect);
        exit();
    }
}



/********
* $Id: delete_directory 20-2=11-2013 Naz $
* Recursively delete directory and content
*/

function helper_delete_directory($dir){
     if (!is_dir($dir) || is_link($dir)) return unlink($dir); 
        foreach (scandir($dir) as $file) { 
            if ($file == '.' || $file == '..') continue; 
            if (!$this->helper_delete_directory($dir . DIRECTORY_SEPARATOR . $file)) { 
                chmod($dir . DIRECTORY_SEPARATOR . $file, 0777); 
                if (!$this->helper_delete_directory($dir . DIRECTORY_SEPARATOR . $file)) return false; 
            }; 
        } 
        return rmdir($dir); 
}

/********
* $Id: get_mimetype 04-01-2010 Naz $
* Try to get the file mime headers by extension
* If not in list use force-download 
* 
*/
function get_mimetype($value='') {
        $this->ct['htm'] = 'text/html';
        $this->ct['html'] = 'text/html';
        $this->ct['txt'] = 'text/plain';
        $this->ct['asc'] = 'text/plain';
        $this->ct['bmp'] = 'image/bmp';
        $this->ct['gif'] = 'image/gif';
        $this->ct['jpeg'] = 'image/jpeg';
        $this->ct['jpg'] = 'image/jpeg';
        $this->ct['jpe'] = 'image/jpeg';
        $this->ct['png'] = 'image/png';
        $this->ct['ico'] = 'image/vnd.microsoft.icon';
        $this->ct['mpeg'] = 'video/mpeg';
        $this->ct['mpg'] = 'video/mpeg';
        $this->ct['mpe'] = 'video/mpeg';
        $this->ct['qt'] = 'video/quicktime';
        $this->ct['mov'] = 'video/quicktime';
        $this->ct['avi']  = 'video/x-msvideo';
        $this->ct['wmv'] = 'video/x-ms-wmv';
        $this->ct['mp2'] = 'audio/mpeg';
        $this->ct['mp3'] = 'audio/mpeg';
        $this->ct['rm'] = 'audio/x-pn-realaudio';
        $this->ct['ram'] = 'audio/x-pn-realaudio';
        $this->ct['rpm'] = 'audio/x-pn-realaudio-plugin';
        $this->ct['ra'] = 'audio/x-realaudio';
        $this->ct['wav'] = 'audio/x-wav';
        $this->ct['css'] = 'text/css';
        $this->ct['zip'] = 'application/zip';
        $this->ct['pdf'] = 'application/pdf';
        $this->ct['doc'] = 'application/msword';
        $this->ct['bin'] = 'application/octet-stream';
        $this->ct['exe'] = 'application/octet-stream';
        $this->ct['class']= 'application/octet-stream';
        $this->ct['dll'] = 'application/octet-stream';
        $this->ct['xls'] = 'application/vnd.ms-excel';
        $this->ct['ppt'] = 'application/vnd.ms-powerpoint';
        $this->ct['wbxml']= 'application/vnd.wap.wbxml';
        $this->ct['wmlc'] = 'application/vnd.wap.wmlc';
        $this->ct['wmlsc']= 'application/vnd.wap.wmlscriptc';
        $this->ct['dvi'] = 'application/x-dvi';
        $this->ct['spl'] = 'application/x-futuresplash';
        $this->ct['gtar'] = 'application/x-gtar';
        $this->ct['gzip'] = 'application/x-gzip';
        $this->ct['js'] = 'application/x-javascript';
        $this->ct['swf'] = 'application/x-shockwave-flash';
        $this->ct['tar'] = 'application/x-tar';
        $this->ct['xhtml']= 'application/xhtml+xml';
        $this->ct['au'] = 'audio/basic';
        $this->ct['snd'] = 'audio/basic';
        $this->ct['midi'] = 'audio/midi';
        $this->ct['mid'] = 'audio/midi';
        $this->ct['m3u'] = 'audio/x-mpegurl';
        $this->ct['tiff'] = 'image/tiff';
        $this->ct['tif'] = 'image/tiff';
        $this->ct['rtf'] = 'text/rtf';
        $this->ct['wml'] = 'text/vnd.wap.wml';
        $this->ct['wmls'] = 'text/vnd.wap.wmlscript';
        $this->ct['xsl'] = 'text/xsl';
        $this->ct['xml'] = 'text/xml';
        $this->extension = $this->helper_get_extension($value);
        if (!$this->type = $this->ct[$this->extension]) {
            $this->type = 'application/force-download';
        }
    return $this->type;
}

/********
* $Id: helper_rename 11-11-2013 Naz $
* Rename file or directory
*/
function helper_rename(){
    $newfile_extension=$this->helper_preserve_case($_SESSION['extension']);
    $filename=$this->helper_preserve_case($_POST['value']);
    $newfilename = $this->helper_set_newname($filename,$newfile_extension);
   
    if ($newfilename!=""){
        if (rename(UPLOAD_FOLDER_PATH.$_SESSION['oldfilename'].$_SESSION['extension'],UPLOAD_FOLDER_PATH.$newfilename))
            {
                 
                echo $this->helper_strip_extension($newfilename);
            }else{
                echo $_SESSION['oldfilename'];
            }
    }else{
        echo $_SESSION['oldfilename'];
    }
    unset($_SESSION['oldfilename']);
    unset($_SESSION['extension']);
}
/********
* Set the new file name  11-11-2013 Naz $
* If exits, it will append a number to the end of the file name
* This will avoid overwriting a pre-existing file.
*/
function helper_set_newname($filename,$newfile_extension=""){
    $new_filename="";
    if ( ! file_exists(UPLOAD_FOLDER_PATH.$filename.$newfile_extension))
	{
	    return $filename.$newfile_extension;
	}else{
            for ($i = 1; $i < 100; $i++){
                if ( ! file_exists(UPLOAD_FOLDER_PATH.$filename."-".$i.$newfile_extension))
                    {
                        $new_filename = $filename."-".$i.$newfile_extension;
                        break;
                    }
            }
            
        }
    if ($new_filename !=""){
            return $new_filename;
    }else{
        
        return $filename."-".date("YmdGi").$newfile_extension;
    }
 
}



/********
* $Id: Create new folder 20-11-2013 Naz $
* 
*/
function helper_create_folder(){
    $filename=$this->helper_preserve_case($_POST['directory']);
    $newfilename = $this->helper_set_newname($filename);
    $_SESSION['extension']='';
    if ($newfilename!=""){
        if (mkdir(UPLOAD_FOLDER_PATH.$newfilename, 0755)) {//remove ,0755 if creates problem
            if (INDEX_FOLDERS){
                $this->handle = fopen(UPLOAD_FOLDER_PATH.$newfilename."/".INDEX_FOLDERS, "w");
                fclose($this->handle);
            }
            echo $newfilename;
        };
    }else{echo "error";
    }
}


/********
* $Id: check_if_writable 012 26-08-2008 Naz $
* check if the working dir is writable
* If not writable retun error msg
 */
function helper_check_if_writable($dir){
    if (CHECK_IF_WRITABLE){
	if ($this->helper_do_write_check($dir))
	{
            define('WRITABLE',true);
            return true;
	}else{
            define('WRITABLE',false);       
            return false;   
            }
	}
}
/********
* $Id: do_write_check 012 26-08-2008 Naz $
* Return true if the dir is writable
*/
function helper_do_write_check($dir_to_check) {
    // if windows OS
    // is_writeable does not make a real UID/GID check on Windows.
    $this->folder_to_check = substr($dir_to_check, 0, -1);// remove trailing slash
	if (DIRECTORY_SEPARATOR == "\\")
	    {
		 $this->folder_to_check = str_replace("/", DIRECTORY_SEPARATOR,  $this->folder_to_check);
                if (is_dir($this->folder_to_check))
		    {
			$this->tmp_file = time().md5(uniqid('abcd'));
			if (@touch($this->folder_to_check . '\\' . $this->tmp_file)) {
			    @unlink($this->folder_to_check . '\\' . $this->tmp_file);
			    return true;
			}

		    }
	    return false;
	    }
    // Not windows OS
    return is_writeable($this->folder_to_check);
}


/********
* $Id: set_max_upload_size 04-01-2010 Naz $
* set the max upload file size
* either use custom size in config (CUSTOM_MAX_UPLOAD_SIZE) or server default
*/
function helper_maximum_upload_size(){
    if (CUSTOM_MAX_UPLOAD_SIZE){
    define('MAX_UPLOAD_SIZE',$this->helper_return_bytes(CUSTOM_MAX_UPLOAD_SIZE));
    }else{
        if (!ini_get('upload_max_filesize'))
        {
        die('Cannot autoset upload_max_filesize, please enter CUSTOM_MAX_UPLOAD_SIZE value in config.inc.php');
        }else{
        define('MAX_UPLOAD_SIZE',$this->helper_return_bytes(ini_get('upload_max_filesize')));
        }
    }
}
/********
* $Id: return_bytes 04-01-2010 Naz $
* Read php.ini settings e.g. ini_get('upload_max_filesize')
* and convert to bytes
*/
function helper_return_bytes($value) {
   if ( is_numeric( $value ) ) {
        return $value;
    } else {
        $value_length = strlen( $value );
        $qty = substr( $value, 0, $value_length - 1 );
        $unit = strtolower( substr( $value, $value_length - 1 ) );

        switch ($unit) {
            case 'k':
                 $qty = ($qty*1024);
                break;
            case 'm':
                 $qty = ($qty *1048576);
               
                break;
            case 'g':
                $qty = ($qty*1073741824);
                break;
        }
       return $qty;
    }
}
/********
* $Id: Use JS Redirect Naz $
*/
function helper_js_redirect($value) {
    if (USE_JS_REDIRECT){
echo "<script>";
        echo "window.location = \"$value\"";        
        echo "</script>";
    }else{
header('Refresh: 0;url='.$value.'');
    }
}



}// end Helper class


/**************************
 *
 *
 *ezFM main Class
 *
 *
 ************************/
class ezFilemanager extends ezHelpers{
    
/********
* $Id: navigation_links 25-01-2010 Naz $
* Make directory navigation
*/
function navigation_links(){
    $nav_array=explode("/",$_GET['folder']);
    $folder_name ='';
    $breadcrumbs ='';
    $ez_navigation ='';
    $ez_navigation .= "<li><a href='".INDEX."?folder=".UPLOAD_FOLDER."' data-i18n='nav.home'></a></li>";
    for($x=0; $x<(sizeof($nav_array)-1); $x++)
    {
        $breadcrumbs .=$nav_array[$x]."/";
        if ($x!=0 && $nav_array[$x] !=''){
            $ez_navigation .= "<li><a href='".INDEX."?folder=".$breadcrumbs."'>".$nav_array[$x]."</a></li>";
            $folder_name=$nav_array[$x];
        }
    }
    define('FOLDER_NAME',$folder_name);
    return $ez_navigation;
}

/**************************
* $Id: initialize ezFM 11-11-2013 Naz $
* Set timezone, Find and define ABSOLUT_PATH 
* Set upload folder
* Initialize actions
*/    
function ezfm_init(){
    //SET URL SSL
    if (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS'])){
     define("HTTP","https://");   
    }else{
         define("HTTP","http://");  
    }
    define("SITE_URL",HTTP.$_SERVER['HTTP_HOST']);
    //SET TIMEZONE
    if(function_exists("date_default_timezone_set") && function_exists("date_default_timezone_get")){
        @date_default_timezone_set(@date_default_timezone_get());
    }
    //If the below does not work for you because of server settings, Set ABSOLUT_PATH manually 
    if ($_SERVER['DOCUMENT_ROOT']){//Try to find ABSOLUT_PATH,
        $_SERVER['DOCUMENT_ROOT'] .= (substr($_SERVER['DOCUMENT_ROOT'],-1)=='/')?'':'/'; 
        define('ABSOLUT_PATH',$_SERVER['DOCUMENT_ROOT']);//The domain root absolute path
    }else{
        define('ABSOLUT_PATH', str_replace($_SERVER['SCRIPT_NAME'], "/", $_SERVER['SCRIPT_FILENAME']));  
    }
    //SET DOAIN TRAILING SLASH
    if ($_SERVER['HTTP_HOST']){
        $_SERVER['HTTP_HOST'] .= (substr($_SERVER['HTTP_HOST'],-1)=='/')?'':'/';
    }
    

    //SET Upload Folder
    if (isset($_GET['folder']) && $_GET['folder'] !=""){
        $this->helper_redirect_onhack();
        $_GET['folder'] .= (substr($_GET['folder'],-1)=='/')?'':'/';
        define('UPLOAD_FOLDER_PATH', ABSOLUT_PATH.$_GET['folder']);
        define("CURRENT_URL","".INDEX."?folder=".$_GET['folder']);
    }else{
        $type="all";
        if (isset($_GET['type']) && $_GET['type'] !="")
        {$type=$_GET['type'];
        }
        $redirect =INDEX."?folder=".UPLOAD_FOLDER."&type=".$type;
         $this->helper_js_redirect($redirect);
        exit();
    }

    //SET FILE VISIBLE TYPE
    $filetype_array = explode(",",FILE_TYPES);
    if (isset($_GET['type'])) {
         $_SESSION['type'] = $_GET['type'];
        }else{
            
            if (!isset($_SESSION['type'])){
                $_SESSION['type'] = 'all';
            }
        }
    //Initialize
    define('ALLOWED_FILES', str_replace(" ", "", IMAGE_FILES.",".OTHER_FILES.",".MEDIA_FILES));
    
    $this->helper_check_if_writable(UPLOAD_FOLDER_PATH);
    $this->helper_maximum_upload_size();
    $this->ezf_actions();
}

/*************************
* $Id: read_folder 25-01-2010 Naz $
* * Read the folder and put everything
* in a multidimensional array
* 
*/
function read_folder(){
    $this->folder=array();
    $this->files=array();
    $this->current_path=array();
    $this->fileinformation=array();
    $this->folder_date=array();
    $this->size=0;
    $dir_handle = @opendir(UPLOAD_FOLDER_PATH) or die("<div class='alert alert-danger'><span  data-i18n='error.openFolder'></span> ".$_GET['folder']."</div>");
    $d=$f=0;
    while($file = readdir($dir_handle))
    {
      if ($file != '.' && $file != '..' && !in_array($file,$this->helper_protected_filearray()) && $file[0] !='.')
        {
          if (is_dir(UPLOAD_FOLDER_PATH.$file)){
            $this->folder[$d]=$file;
            $this->folder_date[$file]= date(DATE_FORMAT, filemtime(UPLOAD_FOLDER_PATH.$file));
            $d++;
          }else{
            if(in_array($this->helper_get_extension($file),$this->ezf_file_type_show())){
                $this->fileinformation[$file] =$this->helper_file_information($file);
            $this->files[$f]=$file;
            $this->size += filesize(UPLOAD_FOLDER_PATH.$file);
            $f++;
          }
        }
      }
    }
    usort($this->files, array(&$this,"helper_sort_case"));
    usort($this->folder, array(&$this,"helper_sort_case"));
    $this->current_path['folder']=$this->folder;
    $this->current_path['file']=$this->files;
    $this->current_path['file_info']=$this->fileinformation;
    $this->current_path['folder_size']=$this->helper_bytestostring($this->size);//$this->size_array;
    $this->current_path['folder_date']=$this->folder_date;
    return $this->current_path;
}

/**************************
* $Id: ezFM actions 11-11-2013 Naz $
* rename,delete,upload 
* some are done in the backround
* 
*/  
function ezf_actions(){
    if (isset($_GET['action']) && $_GET['action'] !=""){
        $this->action=$_GET['action'];
        switch($this->action) {
            
            case 'oldfilename': //SET THE OLD FILE/FOLDER NAME BEFORE RENAMING
                if (isset($_POST['oldfilename'])) 
                $_SESSION['oldfilename'] = $_POST['oldfilename'];
                $_SESSION['extension'] = $_POST['extension'];
                 exit();
                break;
        //RENAME File or Folder   
            case 'rename':
                if (isset($_SESSION['oldfilename']) && ENABLE_RENAME && isset($_POST['value']))
                if ($_SESSION['oldfilename']==$_POST['value'])
                {
                    echo $_SESSION['oldfilename'];
                }else{
                    $this->helper_rename();
                }
                exit();
                break;
        //COPY file  
            case 'copy':
                $_SESSION['pathefromCopy']=UPLOAD_FOLDER_PATH;
                $_SESSION['filetoCopy']=$_POST['file'];
                $_SESSION['extensionCopy']=$_POST['extension'];
                exit();
                break;
        //PASTE file  
            case 'paste':
                if (isset($_SESSION['filetoCopy']) )
                {
                    
                    
                    $newfileName= $this->helper_set_newname($_SESSION['filetoCopy'],$_SESSION['extensionCopy']);
                    if (copy($_SESSION['pathefromCopy'].$_SESSION['filetoCopy'].$_SESSION['extensionCopy'], UPLOAD_FOLDER_PATH.$newfileName)){
                        echo $newfileName;
                    
                    }else{
                     echo "error";   
                    }
                    unset($_SESSION['pathefromCopy']);
                    unset($_SESSION['filetoCopy']);
                    unset($_SESSION['extensionCopy']);
                }else{
                    echo "emptyPaste";
                }
                exit();
                break;
         //DELETE File or Folder    
            case 'delete':
                if (isset($_POST['file'])){
                    if ($_POST['extension']==""){
                            $this->helper_delete_directory(UPLOAD_FOLDER_PATH.$_POST['file']);
                    }else{
                        if (is_file(UPLOAD_FOLDER_PATH.$_POST['file'].$_POST['extension'])){
                            unlink(UPLOAD_FOLDER_PATH.$_POST['file'].$_POST['extension']);
                        }else{
                            echo $_POST['file'];  
                        }
                    }
                }
                exit();
                break;
        //CREATE New Folder   
            case 'createfolder':
                $_POST['directory']=trim($_POST['directory']);
                if (isset($_POST['directory'])){
                    if( $_POST['directory'] !=''){
                    $this->helper_create_folder();
                }else{
                    echo "empty";
                    }
                exit();
                }
                break;
            
        //DELETE multiple   
            case 'multidelete':
                $_POST['record']=trim($_POST['record']);
                if (isset($_POST['record']) && $_POST['extension'] !=''){
                    if (is_file(UPLOAD_FOLDER_PATH.$_POST['record'])){
                           unlink(UPLOAD_FOLDER_PATH.$_POST['record']);
                        }else{
                            echo "error";
                        }
                    
                }else
                {
                    if (isset($_POST['record']) && $_POST['file'] !=''){
                       
                            $this->helper_delete_directory(UPLOAD_FOLDER_PATH.$_POST['record']);
                        }else{
                            echo "error";
                        }
                    
                }
                exit();
                break;
            
            
        //DOWNLOAD File   
            case 'download':
            $file_info = UPLOAD_FOLDER_PATH.$_GET['file'];
                $file_type= $this->get_mimetype(basename($_GET['file']));
                header("Pragma: public"); // required
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Cache-Control: private",false); // required for certain browsers
                header("Content-Type: $file_type");
                header("Content-Disposition: attachment; filename=".rawurlencode(basename($file_info)).";" );
                header("Content-Transfer-Encoding: binary");
                header("Content-Length: ".filesize($file_info));
                readfile("$file_info");
                exit();
                break;
            //
        //UPLOAD File   
            case 'upload':    
                $succeedNum = 0;
                $successMsg='';
                $errorNum = 0;
                $canUpload = 0;
                $errorMsg = '';
        foreach($_FILES["filetoupload"]["error"] as $key => $value) {
            $name = $_FILES["filetoupload"]["name"][$key];
             $newfilename = $this->helper_preserve_case($name);
                $extension = $this->helper_get_extension($newfilename);
                $strippedName = $this->helper_strip_extension($newfilename);
                $newfilename = $this->helper_set_newname($strippedName, '.'.$extension);
                
                $file = $this->helper_strip_extension($newfilename);
                $this->ezfilemanager= $this->helper_file_extensions();
                if(!in_array($extension, $this->ezfilemanager['filetype']['all'])){
                $value = 'UPLOAD_ERR_MIME_TYPE';     
                    }
            
             $file_size = @filesize($_FILES['filetoupload']['tmp_name'][$key]);
                    if ($file_size > MAX_UPLOAD_SIZE) {
                $value = UPLOAD_ERR_FORM_SIZE;      
                    }
                    
            switch ($value) {
        case UPLOAD_ERR_OK:
            $succeedNum++;
             if (copy($_FILES['filetoupload']['tmp_name'][$key], UPLOAD_FOLDER_PATH.$newfilename)){
                $successMsg .= '<tr class="success" id="tr_c'.$succeedNum.'"><td style="width:10px"><input type="checkbox" id="record_c'.$succeedNum.'" value="'.$newfilename.'" class="del" name="_del[c'.$succeedNum.']" alt="c'.$succeedNum.'"></td><td style="width:10px"><a href="#" id="insert_c'.$succeedNum.'" alt="'.$newfilename.'" class="insert"><span class="glyphicon glyphicon-import"></span></a></td><td style="width:10px"><a id="c'.$succeedNum.'" class="ajaxdelete"  alt="folder" href="#"><span class="glyphicon glyphicon-trash"></span></a></td><td><span id="edit_c'.$succeedNum.'" alt="c'.$succeedNum.'">'.$file.'</span><span id="ext_c'.$succeedNum.'">.'.$extension.'</span></td><td></td><td></td></tr>';
                }else{
                   $errorNum++;
                   $errorMsg .= '<tr class="danger"><td style="width:10px"></td><td style="width:10px"></td><td style="width:10px"><span class="glyphicon glyphicon-warning-sign"></span></td><td>'.$name.'</td><td></td><td></td></tr>';
                } //if copy
            break;
        case 'UPLOAD_ERR_MIME_TYPE':
           $errorMsg .= '<tr class="danger"><td style="width:10px"></td><td style="width:10px"></td><td style="width:10px"><span class="glyphicon glyphicon-warning-sign"></span></td><td>'.$name.'</td><td nowrap><span class="glyphicon glyphicon-exclamation-sign"></span>&nbsp;'.$extension.'</td><td></td></tr>';
             break;
        
        case UPLOAD_ERR_FORM_SIZE:
            $errorMsg .= '<tr class="danger"><td style="width:10px"></td><td style="width:10px"></td><td style="width:10px"><span class="glyphicon glyphicon-warning-sign"></span></td><td>'.$name.'</td><td nowrap><span class="glyphicon glyphicon-exclamation-sign"></span>&nbsp; > '.$this->helper_bytestostring(MAX_UPLOAD_SIZE).'</td><td></td></tr>';
             break;
            
        case UPLOAD_ERR_NO_FILE:
             $errorMsg .= '<tr class="danger"><td style="width:10px"></td><td style="width:10px"></td><td style="width:10px"><span class="glyphicon glyphicon-warning-sign"></span></td><td>No file</td><td></td><td></td></tr>';
         break;
        default:
            $errorMsg .= '<tr class="danger"><td style="width:10px"></td><td style="width:10px"></td><td style="width:10px"><span class="glyphicon glyphicon-warning-sign"></span></td><td>Unknown error</td><td></td><td></td></tr>';
    }

    }

        
        echo $successMsg.$errorMsg;
          exit();
                break;   
            //
            
        }//End switch
    }//End if set GET Actions
} //End Actions

}// end ezfm class