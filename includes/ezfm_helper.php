<?php if ( ! defined('CONFIG_FILE')) exit('No direct script access allowed');?>
<?php
function check_login(){
    if (PASSWORD_PROTECTED && !isset($_COOKIE[COOKIE_NAME])) {
        header('Location: login.php');
    exit;
    }
}
/********
* $Id: SERT UPLOAD FOLDER 25-04-2015 Naz $
* FOLDER_ARRAY comma seperated folder names in config
* 
*/
function FolderSet(){
    $Folderarray=explode(",",FOLDER_ARRAY);
    if (isset($_GET['folder'])){
        $nav_array=explode("/",$_GET['folder']);
        $rootFolder=$nav_array[0];
        if(isset($rootFolder) && in_array($rootFolder, $Folderarray)){
            define('UPLOAD_FOLDER',''.$rootFolder.'/'.USER_FOLDER);//upload directory   
        }else{
            define('UPLOAD_FOLDER',''.$Folderarray[0].'/'.USER_FOLDER);//upload directory   
        }
    }else{
        define('UPLOAD_FOLDER',''.$Folderarray[0].'/'.USER_FOLDER);//upload directory
        }
}


/********
* $Id: folder_links 25-04-2015 Naz $
* Make directory navigation drop down
* FOLDER_ARRAY comma seperated folder names in config
*/
function folder_links(){
    $Folderarray=explode(",",FOLDER_ARRAY);
    $nav_array=explode("/",$_GET['folder']);
    $dropdown='';
    if (is_array($Folderarray) && count($Folderarray)>1){
        $nav_array=explode("/",$_GET['folder']);
        $dropdown .='<div class="pull-right">
            <div class="btn-group">
                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">'.rtrim($nav_array[0]).'
                    <span class="caret"></span>
                </button>
            <ul class="dropdown-menu" style="left: -100%;">';
            foreach ($Folderarray as $value) {
                if ($value != $nav_array[0])
                    $dropdown .='<li><a href="?folder='.$value.'">'.rtrim($value, "/").'</a></li>';
                }
                $dropdown .='</ul>
                    </div>
                </div>';
    }
    return $dropdown;
}


/****************************
* $Id: logi routine 25-04-2015 Naz $
* Basic and ugly login routine
* 
*********************/
function doLoginRoutine(){
    if(isset($_COOKIE[COOKIE_NAME])) {
     echo "<script>";
        echo "window.location = \"index.php?folder=".UPLOAD_FOLDER."\"";        
        echo "</script>";
    }
    if(!isset($_POST['user'], $_POST['password'])){
        $message = '';
        setcookie(COOKIE_NAME, "", 1, "/");
    }else{
        if ($_POST['user']==USER &&  $_POST['password']==PASSWORD){
            $expire ="0";
            if (COOKIE_DURATION){
                $expire= time() + COOKIE_DURATION;
            }
            setcookie(COOKIE_NAME, "TRUE", $expire, "/");
            echo "<script>";
            echo "window.location = \"index.php?folder=".UPLOAD_FOLDER."\"";        
            echo "</script>";
        }else{
            $message = '<div class="alert alert-danger">Please enter a valid username and password</div>';
        }
    }
}