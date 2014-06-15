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
    function gqp_ajax_panel() {
        $.ajax({
            url:'" . INFUSIONS . "gameserver_query_panel/ajax_panel.php',
            beforeSend:function(){
                $('#gqp_ajax_panel').fadeOut('slow');
            },
            success:function(data){
                $('#gqp_ajax_panel').html(data).fadeIn('slow');
            },
            dataType: 'html'
        });
    }
    function gqp_ajax_panel_reload() {
        $.ajax({
            url:'" . INFUSIONS . "gameserver_query_panel/ajax_panel.php',            
            success:function(data){
                $('#gqp_ajax_panel').html(data);
            },
            dataType: 'html'
        });
    }
    jQuery(document).ready(function() {
        $('#GQP_AjaxRel').click(function(){
            gqp_ajax_panel();
        });
        gqp_ajax_panel_reload();        
    });
</script>");

if (file_exists(INFUSIONS . "gameserver_query_panel/locale/" . $settings['locale'] . ".php")) {
    include INFUSIONS . "gameserver_query_panel/locale/" . $settings['locale'] . ".php";
} else {
    include INFUSIONS . "gameserver_query_panel/locale/English.php";
}

openside("<span class='gqp-gamepad'></span> " . $locale['gqp_title'], true, "on");
echo "<div id='gqp_ajax_panel'></div>";
echo "<div class='GQP_AjaxRel'><span id='GQP_AjaxRel' class='gqp-rotate-left' alt='" . $locale['gqp_reload'] . "' title='" . $locale['gqp_reload'] . "'></span></div>";
closeside();
?>