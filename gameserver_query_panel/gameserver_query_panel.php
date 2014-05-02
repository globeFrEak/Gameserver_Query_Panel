<?php

/* -------------------------------------------------------+
  | PHP-Fusion Content Management System
  | Copyright (C) 2002 - 2013 Nick Jones
  | http://www.php-fusion.co.uk/
  +--------------------------------------------------------+
  | Infusion: GamerServer Query Panel
  | Author: globeFrEak [www.cwclan.de]
  +--------------------------------------------------------+
  | This program is released as free software under the
  | Affero GPL license. You can redistribute it and/or
  | modify it under the terms of this license which you
  | can read by viewing the included agpl.txt or online
  | at www.gnu.org/licenses/agpl.html. Removal of this
  | copyright header is strictly prohibited without
  | written permission from the original author(s).
  +-------------------------------------------------------- */
if (!defined("IN_FUSION")) {
    die("Access Denied");
}

include INFUSIONS . "gameserver_query_panel/infusion_db.php";

add_to_head("<link rel='stylesheet' href='" . INFUSIONS . "gameserver_query_panel/gqp.css' type='text/css'/>");

add_to_head("<script type=\"text/javascript\">
    function gqp_ajax() {
        $.ajax({
            url:'" . INFUSIONS . "gameserver_query_panel/ajax.php',
            beforeSend:function(){
                $('#gqpajax').fadeOut('slow');
            },
            success:function(data){
                $('#gqpajax').html(data).fadeIn('slow');
            },
            dataType: 'html'
        });
    }    
    jQuery(document).ready(function() {
        $('#GQP_AjaxRel').click(function(){
            gqp_ajax();
        });
        gqp_ajax();    
    });
</script>");

if (file_exists(INFUSIONS . "gameserver_query_panel/locale/" . $settings['locale'] . ".php")) {
    include INFUSIONS . "gameserver_query_panel/locale/" . $settings['locale'] . ".php";
} else {
    include INFUSIONS . "gameserver_query_panel/locale/English.php";
}

openside("<span class='gqp-gamepad'></span> " . $locale['gqp_title']);
echo "<button id='GQP_AjaxRel'>click</button>";
echo "<div id='gqpajax'></div>";
closeside();
?>