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
require_once "../../maincore.php";
require_once THEMES . "templates/header.php";

include_once INFUSIONS . "gameserver_query_panel/infusion_db.php";
include_once INFUSIONS . "gameserver_query_panel/functions.php";

add_to_head("<link rel='stylesheet' href='" . GQPBASE . "css/gqp.css' type='text/css'/>");

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = mysql_real_escape_string($_GET['id']);
} else {
    $id = 0;
}

add_to_head("<script type=\"text/javascript\">    
    function gqp_ajax_detail() {
        $.ajax({
            url:'" . GQPBASE . "ajax_detail.php?id=" . $id . "',            
            beforeSend:function(){
                $('#gqp_ajax_detail').fadeOut('slow');
            },
            success:function(data){
                $('#gqp_ajax_detail').html(data).fadeIn('slow');
            },
            dataType: 'html'
        });
    }
    function gqp_ajax_detail_reload() {
        $.ajax({
            url:'" . GQPBASE . "ajax_detail.php?id=" . $id . "',            
            success:function(data){
                $('#gqp_ajax_detail').html(data);
            },
            dataType: 'html'
        });
    }
    jQuery(document).ready(function() {        
        $('#gqp_ajrel_detail').click(function(){
            gqp_ajax_detail();
        });
        gqp_ajax_detail_reload();        
    });
</script>");

$result = dbquery("SELECT panel_name FROM " . DB_GQP_SETTINGS . "");
while ($data = dbarray($result)) {
    $title = $data['panel_name'];
}

opentable("<span class='gqp-gamepad'></span> " . $title);
//echo "<button id='gqp_ajrel_detail'>click</button>";
echo "<div id='gqp_ajax_detail'></div>";
closetable();

require_once THEMES . "templates/footer.php";
?>