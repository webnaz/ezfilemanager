<?php if ( ! defined('CONFIG_FILE')) exit('No direct script access allowed');?>
<?php
$directory=$ezf->read_folder();
?>

<div class="table-responsive">
    
<table class="table table-bordered table-condensed table-hover table-home" id="filelist">
    <thead>
    <tr>
        <th style='width:10px'><input type="checkbox" name="selectAll" id="selectSelects" /></th>
        <th colspan="3">
            <div class="btn-group">
          
          <button id="pagerefresh" type="button" class="btn btn-default btn-xs orrange"><span class="glyphicon glyphicon-refresh"></span></button>
          <button id="deleteFiles" type="button" class="btn btn-default btn-xs multiaction"><span class="glyphicon glyphicon-trash"></span></button>
          
          
          <?php
          if (ENABLE_COPY){
          ?>
           <button id="pasteFiles" type="button" class="btn btn-default btn-xs multiaction">
            <span class="glyphicon glyphicon-tasks" data-i18n="btn.paste"> </span>
          <?php
          }
          ?>
          </button>
          
          <span id="response" style="padding:0px 10px"></span>
        </div>
            
         </th>
        <th colspan="2" style="text-align:right;width:5%">  <?php
          if (PASSWORD_PROTECTED){
          ?>
           <button id="logout" type="button" class="btn btn-default btn-xs red"><span class="glyphicon glyphicon-log-out" data-i18n='btn.logout'></span></button>
            <?php
          }
          ?></th>
    </tr>
    </thead>
<tbody>
<?php
$x=0;
foreach ($directory['folder'] as $folder) {
    $x++;
    ?>
<tr id="tr_<?php echo $x;?>">
<td style="width:10px"><input type='checkbox' id="record_<?php echo $x;?>" value='<?php echo $folder;?>' class='del' name='_del[<?php echo $x;?>]' alt="<?php echo $x;?>" /></td>
    <td style="width:10px"></td>
    <td style="width:10px">
        <a id="<?php echo $x;?>" class="dropdown-actions" role="button" alt="folder" href="#"><span class="glyphicon glyphicon-cog"></span></a>
    </td>
    <td colspan="2">
        <a href="<?php echo CURRENT_URL.$folder."/";?>" id="href_<?php echo $x;?>"><span id="filetype-<?php echo $x;?>" class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;<span class="edit_area" id="edit_<?php echo $x;?>" alt="1"><?php echo $folder;?></span></a><span id="ext_<?php echo $x;?>"></span>
    </td>
    <td></td>
</tr>    
<?php
}
foreach ($directory['file'] as $file) {
$x++;
$width=0;
$height=0;
$is_mage=false;
$image_alt=false;
$icon=$directory['file_info'][$file]['icon'];
if ($directory['file_info'][$file]['class']=='preview'){
$is_mage=' preview';
$image_alt=' alt=\''.$file.'\'';
if (isset($directory['file_info'][$file]['dimension'])){
$dimension = explode("_", $directory['file_info'][$file]['dimension']);
$width=$dimension[0];
$height=$dimension[1];
}
}
?>
<tr id="tr_<?php echo $x;?>">
<td style="width:10px"><input type='checkbox' id="record_<?php echo $x;?>" value='<?php echo $file;?>' class='del' name='_del[<?php echo $x;?>]' alt="<?php echo $x;?>" /></td>
    <td style='width:10px'>
        <a href="#" id="insert_<?php echo $x;?>" alt="<?php echo $file;?>" class="insert"><span class="glyphicon glyphicon-import"></span></a>  
    </td>
    <td style='width:10px'>
        <a id="<?php echo $x;?>" alt="file" class="dropdown-actions" role="button" href="#"><span class="glyphicon glyphicon-cog"></span></a>
    </td>
    <td>
        <span id="filetype-<?php echo $x;?>" <?php echo $image_alt;?> class="glyphicon <?php echo $icon;?><?php echo $is_mage;?>"></span>&nbsp;
        <span class="edit_area" id="edit_<?php echo $x;?>" alt="1"><?php echo $ezf->helper_strip_extension($file);?></span><span id="ext_<?php echo $x;?>"><?php echo $directory['file_info'][$file]['extension'];?></span>
        <span id="width<?php echo $x;?>" alt="<?php echo $width;?>"></span><span id="height<?php echo $x;?>" alt="<?php echo $height;?>"></span>
    </td>
    <td style="width: 20%;"><?php echo $directory['file_info'][$file]['file_date'];?></td>
    <td style="width: 12%;"><?php echo $directory['file_info'][$file]['filesize'];?></td>
</tr>
<?php
}
if ($x==0){
 echo "<tr id='emptyTable'><td colspan='6'><div class='alert alert-warning'><span data-i18n='type.".$_SESSION['type']."Files'></span>: <span data-i18n='error.emptyError'></span></div></td></tr>";   
}
?>
</tbody>
</table>

</div>

 <?php
    if (DEBUG_MODE){
        include('includes/debug.inc.php');
    }
?>

