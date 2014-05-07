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
<?php echo "SESSION[".AUTHENTICATION_SESSION_NAME."]= ". $_SESSION[AUTHENTICATION_SESSION_NAME] ?>
<br />

<?php
if (isset($_SESSION[AUTHENTICATION_SESSION_NAME]))
echo "SESSION[".AUTHENTICATION_SESSION_NAME."]= AUTHENTICATION_SESSION_NAME is set"; ?>
<br />
