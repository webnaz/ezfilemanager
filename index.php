<?php
define('INDEX', pathinfo(__FILE__, PATHINFO_BASENAME)); // The name of THIS file
include("includes/config.inc.php");
include("includes/ezfm_helper.php");
check_login();
FolderSet();
include("includes/ezfm.class.php");
$ezf = new ezFilemanager();
$ezf->ezfm_init();
?>
<!DOCTYPE html>
<html lang="<?php echo LANG ?>">
<head>
<title><?php echo EZ_VERSION ?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="robots" content="NOINDEX,NOFOLLOW,NONE,NOARCHIVE" />
<script>
var lang='<?php echo LANG ?>';
var version='<?php echo EZ_VERSION ?>';
var http='<?php echo HTTP ?>';
var removeSpace='<?php echo REMOVE_SPACE ?>';
var folder ='<?php echo $_GET['folder']; ?>';
var indexFile ='<?php echo INDEX; ?>';
var writable ='<?php echo WRITABLE; ?>';
var maxFileSize ='<?php echo MAX_UPLOAD_SIZE; ?>';
var maxFileUpload ='<?php echo MAX_FILES_UPLOAD; ?>';
var imageArr =  '<?php echo json_encode(explode(",",IMAGE_FILES)); ?>';
var DEBUG_MODE = '<?php echo DEBUG_MODE; ?>';
var valid_extensions = <?php  echo "/(\.".str_replace(",", "|\.", ALLOWED_FILES).")$/i;";  ?>
</script>
<!-- jquery jquery-2.xxx not support for ie8-->
<script type="text/javascript" src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<!-- Boostrap CDN-->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<!-- Jeditable-->
<script type='text/javascript' src='js/jquery.jeditable.min.js?v=1.7.3'></script>
<!-- ezFM-->
<link  href="css/ezfm.css" rel="stylesheet" type="text/css" />
<script type='text/javascript' src='js/ezfm.js'></script>
<!-- Translation-->
<script type='text/javascript' src='js/i18next-1.7.3.min.js'></script>
<!-- Scroll-->
<script type='text/javascript' src='js/jquery.slimscroll.min.js?v=1.3.1'></script>
<!--[if lt IE 9]>
<script src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body id="all">
    <div class="container">
    <?php include('includes/notification.inc.php');?>
    <?php include('includes/dropdown.inc.php');?>
<div class="row">
<!-- Navigation -->
    <div class="col-xs-12">
        <ol class="breadcrumb"><?php echo $ez_navigation=$ezf->navigation_links();?></ol>
    </div>

</div>
<div class="row">
<!-- Left Column -->
    <div class="col-xs-8">
<!-- Nav tabs -->
<ul class="nav nav-tabs" id="ezfm-tab">
    <li><a href="#home-pane" data-toggle="tab" id="version-pane"></a></li>
    <?php
    if (MAX_FILES_UPLOAD>0){
        echo "<li><a href='#upload-pane' data-toggle='tab'><span class='glyphicon glyphicon-upload'></span> <span data-i18n='nav.upload'></span></a></li>";
    }
    if (ENABLE_NEW_DIR>0){
        echo "<li><a href='#folder-pane' data-toggle='tab'><span class='glyphicon glyphicon-folder-close'></span> <span data-i18n='nav.new-folder'></span></a></li>";
    }
    ?>
</ul>
    </div><!-- /left column -->
<!-- Right Column -->
<!-- Multi folder drop down -->
    <div class="col-xs-2"><?php echo folder_links();?></div> 
<!-- File type drop down -->
<div class="col-xs-2">   
    <select class="form-control"  id="type-control"  data-style="btn-inverse">
        <option value='all' data-i18n='type.allFiles'></option>
        <option value='image' data-i18n='type.imageFiles'<?php echo $_SESSION['type'] == 'image' ? ' selected="selected"' : '';?>></option>
        <option value='media' data-i18n='type.mediaFiles'<?php echo $_SESSION['type'] == 'media' ? ' selected="selected"' : '';?>></option>
        <option value='file' data-i18n='type.otherFiles'<?php echo $_SESSION['type'] == 'file' ? ' selected="selected"' : '';?>></option>
    </select>     
</div>
    
    </div><!-- /row-->
<div class="row">
<div class="col-xs-12">
    <!-- Tab panes -->
        <div class="tab-content"  id="tab-content">
            <div class="tab-pane active" id="home-pane">
                <div class="pane-home">
                <?php include("includes/tabs/home.inc.php")?>
                </div>
            </div>
            <?php
            if (MAX_FILES_UPLOAD>0){
                echo "<div class='tab-pane' id='upload-pane'>";
                    include('includes/tabs/file_upload.inc.php');
                echo "</div>";
            }
            ?>
            <?php
            if (ENABLE_NEW_DIR>0){
                echo "<div class='tab-pane' id='folder-pane'>";
                    include('includes/tabs/new_directory.inc.php');
                echo "</div>";
             }
             ?>
        </div> <!-- /tab panes-->
</div><!-- /row -->
</div><!-- /row -->
</div><!-- /container -->
</body>
</html>