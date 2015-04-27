<?php if ( ! defined('CONFIG_FILE')) exit('No direct script access allowed');?>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <form action="" method="post" enctype="multipart/form-data" name="uploadForm" id="uploadForm">
            <h4 class="bottom-0" data-i18n="form.uploadFiles"></h4>
            <div class="input-group">
                <span class="input-group-btn">
                    <span class="btn btn-primary btn-file">
                        <span  data-i18n='form.browseFiles'></span> <input type="file" id="filetoupload" name="filetoupload[]" class='filetoupload' multiple>
                        </span>
                </span>
                <input type="text" class="form-control" value='' readonly>
            </div>
            <span class="help-block"><span  data-i18n="form.uploadFilesTip"></span> <span><strong><?php echo MAX_FILES_UPLOAD ?></strong></span> <span  data-i18n="type.Files"></span><br /><span data-i18n="form.maxSizeHelp1"></span> <strong><?php echo $ezf->helper_bytestostring(MAX_UPLOAD_SIZE); ?></strong><br /></span>

            <button type="submit" class="btn btn-default btn-sm">
                <span class="glyphicon glyphicon-folder-close"></span>
                <span data-i18n='form.uploadFiles'></span>
            </button>
            <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_UPLOAD_SIZE ?>" />
        </form>
        <div id="upload_files" class='alert alert-info'></div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <h4 class="bottom-0" data-i18n="form.allowedFiles"></h4>
        <div class="alert alert-success allowed-files"><strong><span data-i18n="type.imageFiles"></span></strong> 
        <?php echo preg_replace('/(?<!\w),|,(?!\d{3})/', ', ', IMAGE_FILES); ?></div>
        
        <div class="alert alert-warning allowed-files"><strong><span data-i18n="type.mediaFiles"></span></strong> 
        <?php echo preg_replace('/(?<!\w),|,(?!\d{3})/', ', ', MEDIA_FILES); ?></div>
        
        <div class="alert alert-info allowed-files"><strong><span data-i18n="type.otherFiles"></span></strong> 
        <?php echo preg_replace('/(?<!\w),|,(?!\d{3})/', ', ', OTHER_FILES); ?></div>
         <h4 class="bottom-0"><span data-i18n="form.maxSize"></span></h4>
        <div class="alert alert-danger allowed-files"><span data-i18n="form.maxSizeHelp1"></span> <strong><?php echo $ezf->helper_bytestostring(MAX_UPLOAD_SIZE) ?></strong><br />
        <span data-i18n="form.maxSizeHelp2"></span> <strong><?php echo MAX_FILES_UPLOAD ?></strong><br />
        <span data-i18n="form.maxSizeHelp3"></span></div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <progress id="progress" value="0" max="100"></progress>
    </div>
</div>

