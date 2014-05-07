<?php if ( ! defined('CONFIF_FILE')) exit('No direct script access allowed');?>
<div class="row">
    <div class="col-lg-6 col-md-6  col-sm-6 col-xs-6">
        <h4  class="bottom-0" data-i18n='form.createFolder'></h4>
        <form role="form" id="foldercreateForm">
            <textarea class="form-control" rows="6" name="foldercreate" id="foldercreate"></textarea>
            <span class="help-block" data-i18n="form.createFolderTip"></span>
            <button type="submit" class="btn btn-default btn-sm">
                <span class="glyphicon glyphicon-folder-close"></span>
                <span data-i18n='form.createFolder'></span>
            </button>
       </form>
    </div>

    <div class="col-lg-6 col-md-6  col-sm-6 col-xs-6">
        <h4 class="bottom-0" data-i18n="form.allowedChars"></h4>
        <div class="alert alert-danger allowed-files"><span data-i18n="form.allowedCharsHelp"></span>
        <?php if (REMOVE_SPACE==0){?>
            <span data-i18n="form.allowedSpace"></span>
        <?php } ?>
        </div>
    </div>
</div>