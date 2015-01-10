<?php

/* -------------------------------------------------------+
  | PHP-Fusion Content Management System
  || Copyright (C) 2002 - 2013 Nick Jones
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
require_once THEMES . "templates/admin_header.php";
if (!checkrights("GQPG") || !defined("iAUTH") || !isset($_GET['aid']) || $_GET['aid'] != iAUTH) {
    redirect("../../index.php");
}

include_once INFUSIONS . "gameserver_query_panel/infusion_db.php";
include_once INFUSIONS . "gameserver_query_panel/functions.php";

add_to_head("<link rel='stylesheet' href='" . GQPBASE . "css/gqp.css' type='text/css'/>");

if (file_exists(GQPBASE . "locale/" . $settings['locale'] . ".php")) {
    include GQPBASE . "locale/" . $settings['locale'] . ".php";
} else {
    include GQPBASE . "locale/English.php";
}
add_to_head("<script type='text/javascript' src='" . INCLUDES . "jquery/jquery-ui.js'></script>");
add_to_head("<script>
$(document).ready(function() {
    //sortable server list
    $('.pdisabled').fadeTo(0, .5);
    $('.panels-list').sortable({
	handle : '.handle',
	placeholder: 'state-highlight',
	connectWith: '.connected',
	scroll: true,
	axis: 'y',
	update: function () {
            var ul = $(this),
            order = ul.sortable('serialize'),
            i = 0;
            $('#info').load('gqp_order_updater.php" . $aidlink . "&amp;'+order);			
            ul.find('li').removeClass('tbl2').removeClass('tbl1');
            ul.find('li:odd').addClass('tbl2');
            ul.find('li:even').addClass('tbl1');
            window.setTimeout('closeDiv();',2500);
	},
	receive: function () {
            var ul = $(this),
            order = ul.sortable('serialize')
            $('#info').load('gqp_order_updater.php" . $aidlink . "&amp;'+order);
	}
    });
    $('#gqp_gl_btn').click(function() {        
        if ( $(this).hasClass('gqp-chevron-down') ) {
            $(this).removeClass('gqp-chevron-down').addClass('gqp-chevron-up');            
        } else {
            $(this).removeClass('gqp-chevron-up').addClass('gqp-chevron-down');            
        }                
        $('#gqp_gamelist').slideToggle('slow', function() {        
        });
    });
});

</script>");
$error = "";
opentable($locale['gqp_admin_001']);
echo "<div id='info'></div>\n";
/* * Server aus DB auslesen* */
$result = dbquery("SELECT * FROM " . DB_GQP_MAIN . " ORDER BY server_order");
if (dbrows($result) != 0) {
    /// SORTABLE LIST
    $k = 0;
    echo "<ul style='list-style: none;' class='gqp_list panels-list connected'>\n";
    while ($data = dbarray($result)) {
        $row_color = ($k % 2 == 0 ? "tbl1" : "tbl2");
        echo "<li id='listItem_" . $data['id'] . "' class='" . $row_color . ($data['active'] == 0 ? " pdisabled" : "") . "'>\n";
        echo "<div><span class='gqp-arrows-alt handle'></span></div>\n";
        echo "<div>" . $data['name'] . "</div>\n";
        echo "<div><img src='" . GQPIMG . "games/" . $data['game'] . ".jpg' alt='" . GameQ_GetInfo($data['game'], 'N') . "' title='" . GameQ_GetInfo($data['game'], 'N') . "' height='32' width='32'/></div>\n";
        echo "<div>" . $data['address'] . ":" . $data['port'] . "</div>\n";
        echo "<div>";
        echo "<form name='addserver' method='post' action='" . GQPBASE . "gqp_handle_server.php" . $aidlink . "&server=state'>";
        echo "<input type='hidden' name='id' value='" . $data['id'] . "'>";
        echo "<input type='hidden' name='active' value='" . $data['active'] . "'>";
        echo "<button type='submit'><span class='" . ($data['active'] ? "gqp-eye-slash" : "gqp-eye") . "' title='" . ($data['active'] ? $locale['gqp_admin_deact'] : $locale['gqp_admin_act']) . "'></span></button>";
        echo "</form>";
        echo "</div>";
        echo "<div>";
        echo "<form name='addserver' method='post' action='" . GQPBASE . "gqp_handle_server.php" . $aidlink . "&server=edit'>";
        echo "<input type='hidden' name='id' value='" . $data['id'] . "'>";
        echo "<input type='hidden' name='name' value='" . $data['name'] . "'>";
        echo "<input type='hidden' name='game' value='" . $data['game'] . "'>";
        echo "<input type='hidden' name='address' value='" . $data['address'] . "'>";
        echo "<input type='hidden' name='port' value='" . $data['port'] . "'>";
        echo "<input type='hidden' name='server_order' value='" . $data['server_order'] . "'>";
        echo "<input type='hidden' name='active' value='" . $data['active'] . "'>";
        echo "<button type='submit'><span class='gqp-gear' title='" . $locale['gqp_admin_edit'] . "'></span></button>";
        echo "</form>";
        echo "</div>";
        echo "<div>";
        echo "<form name='addserver' method='post' action='" . GQPBASE . "gqp_handle_srvfields.php" . $aidlink . "&id=" . $data['id'] . "'>";
        echo "<button type='submit'><span class='gqp-gamepad' title='" . $locale['gqp_admin_edit'] . "'></span></button>";
        echo "</form>";
        echo "</div>";
        echo "<div>";
        echo "<form name='addserver' method='post' action='" . GQPBASE . "gqp_handle_server.php" . $aidlink . "&server=del'>";
        echo "<input type='hidden' name='id' value='" . $data['id'] . "'>";
        echo "<button type='submit'><span class='gqp-trash-o' title='" . $locale['gqp_admin_del'] . "'></span></button>";
        echo "</form>";
        echo "</div>";
        echo "<div style='clear:both;'></div>\n";
        echo "</li>\n";
        $k++;
    }
    echo "</ul>\n";
}
echo "<a class='gqp_a' href='" . GQPBASE . "gqp_handle_server.php" . $aidlink . "'><span class='gqp-sign-in'></span> " . $locale['gqp_admin_002'] . "</a>";
closetable();

//Settings Form
$result = dbquery("SELECT * FROM " . DB_GQP_SETTINGS . "");
if (dbrows($result) != 0) {
    while ($data = dbarray($result)) {
        opentable("Settings");
        echo "<div id='gqp_server_form'>";
        echo "<form name='settings' method='post' action='" . GQPBASE . "gqp_handle_server.php" . $aidlink . "&settings=edit' > ";
        echo "<input type='hidden' name='id' value='" . $data['id'] . "' > ";
        echo "<label>Panel Name:</label>";
        echo "<input name='panel_name' value='" . $data['panel_name'] . "' > ";
        /**
          echo "<label>Panel Template</label>";
          echo "<select name='panel_template'>";
          foreach (GameQ_ScanDir() as $key => $value) {
          echo "<option value=$value " . ($value == $data['panel_template'] ? 'selected' : '') . ">$value</option>";
          }
          echo "</select>";
         * */
        echo "<button type='submit'><span class='gqp-check' title='" . $locale['gqp_admin_edit'] . "' > </span></button>";
        echo "</form>";
        echo "</div>";
        closetable();
    }
}

//List all supported games
opentable("Game list - " . GameQ_Games('', 'count') . " games supported <span id = 'gqp_gl_btn' class = 'gqp-chevron-down'></span>");
echo "<div id = 'gqp_gamelist' style = 'display:none;'>";
echo GameQ_Games();
echo "</div>";
closetable();
include_once GQPBASE . "copyright.php";
require_once(THEMES . "templates/footer.php");
?>