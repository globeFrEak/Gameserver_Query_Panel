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
include_once INFUSIONS . "gameserver_query_panel/includes/functions.php";

add_to_head("<link rel='stylesheet' href='" . GQPBASE . "css/gqp.css' type='text/css'/>");

add_to_head("<script type=\"text/javascript\">
    function gqp_ajax_panel() {
        $.ajax({
            url:'" . GQPBASE . "includes/ajax_panel.php',
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
            url:'" . GQPBASE . "includes/ajax_panel.php',            
            success:function(data){
                $('#gqp_ajax_panel').html(data);
            },
            dataType: 'html'
        });
    }
    jQuery(document).ready(function() {
        $('#gqp_ajaxrel').click(function(){
            gqp_ajax_panel();
        });
        gqp_ajax_panel_reload();        
    });
</script>");

$result = dbquery("SELECT panel_name FROM " . DB_GQP_SETTINGS . "");
while ($data = dbarray($result)) {
    $title = $data['panel_name'];
}

openside("<span class='gqpfa-gamepad'></span> " . $title, true, "on");
echo "<div id='gqp_ajax_panel'></div>";
echo "<hr />";
echo "<div class='gqp_pfooter'>";
echo "<span id='gqp_ajaxrel' class='gqpfa-refresh' alt='" . $locale['gqp_reload'] . "' title='" . $locale['gqp_reload'] . "'></span>";
if (checkrights("GQPG")) {
    echo "<a href='" . GQPBASE . "gameserver_query_admin.php" . $aidlink . "'><span class='gqpfa-gear'></span>".$locale['gqp_admin_link']."</a>";
}
echo "</div>";
closeside();
?>