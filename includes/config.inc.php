<?php
session_start();
error_reporting(E_ALL);//Change to error_reporting(0) for live sites
define('CONFIF_FILE', pathinfo(__FILE__, PATHINFO_BASENAME)); // The name of THIS file
define('EZ_VERSION','ezFilemanager v3.0 RC');
define('LANG','en');//en,es,el lng folder
define('USER_FOLDER','');//for multiuser directories, implementation is up to you, trailing slash required
define('UPLOAD_FOLDER',''.'/'.USER_FOLDER);//upload directory relative to your document root directory, no trailing slash
define('ENABLE_NEW_DIR',true);//allow new dir creation  (true/false)
define('PRESERVE_CASE',false);//If false, new files/dirs will be converted to lowercase
define('REMOVE_SPACE',true);//Remove space from file/folder names
define('CUSTOM_MAX_UPLOAD_SIZE','0');//0 to use server default or custom size in bytes 1048576=1M
define('MAX_FILES_UPLOAD','6');  //Max number of files to upload simultaneously, 0 disables upload
define('ENABLE_DELETE',true);//allow file/dir deleting  (true/false)
define('ENABLE_RENAME',true);//allow file/dir renaming  (true/false)
define('ENABLE_COPY',true);//allow file copy  (true/false)
define('HIDDEN_FILES','index.html');// hide files from filebrowser, seperated with comma, e.g DIR_INDEXING file or home.html,default.php
define('DATE_FORMAT','M d Y H:i');//http://php.net/manual/en/function.date.php
define('IMAGE_FILES','jpg,jpeg,png,gif');//allowed image files extensions
define('MEDIA_FILES','swf,flv,mp3,mp4,mov,avi,mpg,qt');//allowed meadia files extensions
define('OTHER_FILES','htm,html,css,pdf,ppt,txt,doc,docx,rtf,xml,xsl,dtd,zip,rar.tar');//allowed other files extensions
define('PREVIEW_WIDTH',150);//Files with more than xxx width or height, will be resized proportionally
define('KB','KiB');//KiB or Kb http://en.wikipedia.org/wiki/Kibibyte
define('MB','MB');//MiB or MB http://en.wikipedia.org/wiki/Kibibyte
define('INDEX_FOLDERS','index.html');//empty/filename, if "filename", "filename" will be created in new directories
define('DEBUG_MODE',false);//basic debuging for some variables
define('CHECK_IF_WRITABLE',true);//allow file copy  (true/false)   
define('FILE_TYPES','all,image,media,file');//Do not change
define('PATH_BLOCK_CHARS','/[;\\\\\\.&,:$><]/i');//no need to modify unless you know what you are doing
define('PASSWORD_PROTECTED',true);//Enable authentication (true/false)
define('AUTHENTICATION_SESSION_NAME','user_id');//Session name to use
define('USER','root@localhost'); //Username
define('PASSWORD','demo');//Password
if (PASSWORD_PROTECTED &&  empty($_SESSION[AUTHENTICATION_SESSION_NAME]) ){
 include('login.php');
exit;
    
}
/* Config: ezFilemanager - file manager platform for TinyMCE or stand-alone
 * Copyright (c) Nazaret Armenagian (Naz)
 * Project home: http://www.webnaz.net
 * Version: 3.0 RC
*/
?>
