<?php if ( ! defined('CONFIF_FILE')) exit('No direct script access allowed');?>
<?php echo "UPLOAD_FOLDER_PATH=".UPLOAD_FOLDER_PATH ?>
<br />
<?php echo "UPLOAD_FOLDER=".UPLOAD_FOLDER ?>
<br />
<?php echo "FOLDER_NAME=".FOLDER_NAME ?>
<br />
<?php echo "CURRENT_URL=".CURRENT_URL ?>
<br />
<?php echo "SESSION[\"type\"]= ".$_SESSION['type'] ?>
<br />
<?php echo "COOKIE[".COOKIE_NAME."]= ". $_COOKIE[COOKIE_NAME] ?>
<br />
