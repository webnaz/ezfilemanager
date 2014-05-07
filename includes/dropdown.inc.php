<?php if ( ! defined('CONFIF_FILE')) exit('No direct script access allowed');?>
<!-- Hidden drop down menu-->
<div class="dropdown" id="actions-options">
    <ul class="dropdown-menu" role="menu">
        <li>
            <a class="download" id="" role="menuitem" tabindex="-1" href="">
                <span class="glyphicon glyphicon-download-alt"></span>
                <span data-i18n='dropdown.download'></span>
            </a>
        </li>
    <?php
    if (ENABLE_COPY){
    ?>
        <li>
            <a class="copy" id="" role="menuitem" tabindex="-1" href="">
                <span class="glyphicon glyphicon-file"></span>
                <span data-i18n='dropdown.copy'></span>
            </a>
        </li>
    <?php
    }
    ?>
    <?php
    if (ENABLE_DELETE){
    ?>
        <li>
            <a class="filedelete" id="" role="menuitem" tabindex="-1" href="">
                <span class="glyphicon glyphicon-trash"></span>
                <span data-i18n='dropdown.delete'></span>
            </a>
        </li>
    <?php
    }
    ?>
    
    <?php
    if (ENABLE_RENAME){
    ?>
        <li>
            <a class="rename" id="" role="menuitem" tabindex="-1" href="">
                <span class="glyphicon glyphicon-pencil"></span>
                <span data-i18n='dropdown.rename'></span>
            </a>
        </li>
    <?php
    }
    ?>
    </ul>
</div>