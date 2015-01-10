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
include_once INFUSIONS . "gameserver_query_panel/functions.php";

add_to_head("<link rel='stylesheet' href='" . GQPBASE . "css/gqp.css' type='text/css'/>");

add_to_head("<script type=\"text/javascript\">
    function gqp_ajax_panel() {
        $.ajax({
            url:'" . GQPBASE . "ajax_panel.php',
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
            url:'" . GQPBASE . "ajax_panel.php',            
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

if (file_exists(GQPBASE . "locale/" . $settings['locale'] . ".php")) {
    include GQPBASE . "locale/" . $settings['locale'] . ".php";
} else {
    include GQPBASE . "locale/English.php";
}

$result = dbquery("SELECT panel_name FROM " . DB_GQP_SETTINGS . "");
while ($data = dbarray($result)) {
    $title = $data['panel_name'];
}

openside("<span class='gqp-gamepad'></span> " . $title, true, "on");
echo "<div id='gqp_ajax_panel'></div>";
echo "<hr />";
echo "<span id='gqp_ajaxrel' class='gqp-rotate-left' alt='" . $locale['gqp_reload'] . "' title='" . $locale['gqp_reload'] . "'></span>";
if (checkrights("GQPG")) {
    echo "<a href='" . GQPBASE . "gameserver_query_admin.php" . $aidlink . "'>Admin</a>";
}
?>
<!--
<div>
    <div class='gqpp-server'>
        <h5><a href="../../infusions/gameserver_query_panel/gameserver_query_detail.php?id=1">CW Fortress #1[CW][GER] by cwclan.de</a><a href="steam://connect/server.cwclan.de:27015/" alt="Verbinden mit CW Fortress #1[CW][GER] by cwclan.de" title="Verbinden mit CW Fortress #1[CW][GER] by cwclan.de"><span class="gqp-sign-in"></span></a></h5>
        <div class='gqpp-right'>
            <div>0/24 <span class="gqp-group"></span></div>
            <div>0/24 <span class="gqp-group"></span></div>
            <div>0/24 <span class="gqp-group"></span></div>
            <div>0/24 <span class="gqp-group"></span></div>
            <div>0/24 <span class="gqp-group"></span></div>
            <div>0/24 <span class="gqp-group"></span></div>
        </div>
        <div class='gqpp-left'>
            <div><span class="gqp-globe"></span> pl_badwater</div>
            <div><span class="gqp-globe"></span> pl_badwater</div>
            <div><span class="gqp-globe"></span> pl_badwater</div>
        </div>        
    </div>
    <div class='gqpp-clear'></div>
    <div class='gqpp-server'>
        <h5><img src="../../infusions/gameserver_query_panel/images/games/tf2.jpg" alt="Team Fortress 2" title="Team Fortress 2" height="16" width="16"><a href="../../infusions/gameserver_query_panel/gameserver_query_detail.php?id=1">CW Fortress #1[CW][GER] by cwclan.de</a><a href="steam://connect/server.cwclan.de:27015/" alt="Verbinden mit CW Fortress #1[CW][GER] by cwclan.de" title="Verbinden mit CW Fortress #1[CW][GER] by cwclan.de"><span class="gqp-sign-in"></span></a></h5>
        <div class='gqpp-right'>
            <div>0/24 <span class="gqp-group"></span></div>
            <div>0/24 <span class="gqp-group"></span></div>
            <div>0/24 <span class="gqp-group"></span></div>
            <div>0/24 <span class="gqp-group"></span></div>
            <div>0/24 <span class="gqp-group"></span></div>
            <div>0/24 <span class="gqp-group"></span></div>
        </div>
        <div class='gqpp-left'>
            <div><span class="gqp-globe"></span> pl_badwater</div>
            <div><span class="gqp-globe"></span> pl_badwater</div>
            <div><span class="gqp-globe"></span> pl_badwater</div>
        </div>        
    </div>
    <div class='gqpp-clear'></div>
</div>
--!>
<?php
closeside();
?>