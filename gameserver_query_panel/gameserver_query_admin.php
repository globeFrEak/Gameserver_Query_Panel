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

include_once INFUSIONS . "gameserver_query_panel/infusion_db.php";

if (!checkrights("GQPG") || !defined("iAUTH") || !isset($_GET['aid']) || $_GET['aid'] != iAUTH) {
    redirect("../../index.php");
}
include_once INFUSIONS . "gameserver_query_panel/functions.php";

if (file_exists(INFUSIONS . "gameserver_query_panel/locale/" . $settings['locale'] . ".php")) {
    include INFUSIONS . "gameserver_query_panel/locale/" . $settings['locale'] . ".php";
} else {
    include INFUSIONS . "gameserver_query_panel/locale/English.php";
}

add_to_head("<script type='text/javascript' src='" . INCLUDES . "jquery/jquery-ui.js'></script>");
add_to_head("<link rel='stylesheet' href='" . INFUSIONS . "gameserver_query_panel/gqp.css' type='text/css'/>");
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
});

</script>");
$error = "";
opentable($locale['gqp_admin_001']);
echo "<div id='info'></div>\n";
echo "<a href='" . INFUSIONS . "gameserver_query_panel/gqp_handle_server.php" . $aidlink . "'>" . $locale['gqp_admin_002'] . "</a>";
/* * Server aus DB auslesen* */
$result = dbquery("SELECT * FROM " . DB_GQP_MAIN . " ORDER BY server_order");
if (dbrows($result) != 0) {
    /// SORTABLE LIST
    $k = 0;
    echo "<ul style='list-style: none;' class='panels-list connected'>\n";

    while ($data = dbarray($result)) {
        $row_color = ($k % 2 == 0 ? "tbl1" : "tbl2");
        echo "<li id='listItem_" . $data['id'] . "' class='" . $row_color . ($data['active'] == 0 ? " pdisabled" : "") . "'>\n";
        echo "<div style='float:left;'><img src='" . IMAGES . "arrow.png' alt='move' class='handle' /></div>\n";
        echo "<div style='float:left;'>" . $data['name'] . "</div>\n";
        echo "<div style='float:left;'><img src='" . INFUSIONS . "gameserver_query_panel/images/games/" . $data['game'] . ".jpg' alt='" . GameQ_GetInfo($data['game'], 'N') . "' title='" . GameQ_GetInfo($data['game'], 'N') . "' height='32' width='32'/></div>\n";
        echo "<div style='float:left;'>" . $data['address'] . ":" . $data['port'] . "</div>\n";
        echo "<div style='float:right;'>";
        echo "<form name='addserver' method='post' action='" . INFUSIONS . "gameserver_query_panel/gqp_handle_server.php" . $aidlink . "&server=del'>";
        echo "<input type='hidden' name='id' value='" . $data['id'] . "'>";
        echo "<button type='submit'><span class='gqp-trash-o' title='L&ouml;schen'></span></button>";
        echo "</form>";
        echo "</div>";
        echo "<div style='float:right;'>";
        echo "<form name='addserver' method='post' action='" . INFUSIONS . "gameserver_query_panel/gqp_handle_server.php" . $aidlink . "&server=edit'>";
        echo "<input type='hidden' name='id' value='" . $data['id'] . "'>";
        echo "<input type='hidden' name='name' value='" . $data['name'] . "'>";
        echo "<input type='hidden' name='game' value='" . $data['game'] . "'>";
        echo "<input type='hidden' name='address' value='" . $data['address'] . "'>";
        echo "<input type='hidden' name='port' value='" . $data['port'] . "'>";
        echo "<input type='hidden' name='server_order' value='" . $data['server_order'] . "'>";
        echo "<input type='hidden' name='active' value='" . $data['active'] . "'>";
        echo "<button type='submit'><span class='gqp-gear' title='Editieren'></span></button>";
        echo "</form>";
        echo "</div>";
        echo "<div style='float:right;'>";
        echo "<form name='addserver' method='post' action='" . INFUSIONS . "gameserver_query_panel/gqp_handle_server.php" . $aidlink . "&server=state'>";
        echo "<input type='hidden' name='id' value='" . $data['id'] . "'>";
        echo "<input type='hidden' name='active' value='" . $data['active'] . "'>";
        echo "<button type='submit'><span class='" . ($data['active'] ? "gqp-eye-slash" : "gqp-eye") . "' title='" . ($data['active'] ? "deaktivieren" : "aktivieren") . "'></span></button>";
        echo "</form>";
        echo "</div>";
        echo "<div style='clear:both;'></div>\n";
        echo "</li>\n";
        $k++;
    }

    echo "</ul>\n";
    /** ALT
      while ($data = dbarray($result)) {
      echo "<tr>";
      echo "<td class='tbl1'>" . $data['name'] . "</td>";
      echo "<td class='tbl1'>"
      . "<img src='" . INFUSIONS . "gameserver_query_panel/images/games/" . $data['game'] . ".jpg' alt='" . GameQ_GetInfo($data['game'], 'N') . "' title='" . GameQ_GetInfo($data['game'], 'N') . "' height='32' width='32'/></td>";
      echo "<td class='tbl1'>" . $data['address'] . "</td>";
      echo "<td class='tbl1'>" . $data['port'] . "</td>";
      echo "<td class='tbl1'>" . $data['order'] . "</td>";
      if ($data['active'] == 1) {
      echo "<td class='tbl1'>Ja</td>";
      } else {
      echo "<td class='tbl1'>Nein</td>";
      }
      echo "<td class='tbl1'>";
      echo "<form name='addserver' method='post' action='" . FUSION_SELF . $aidlink . "&server=edit'>";
      echo "<input type='hidden' name='id' value='" . $data['id'] . "'>";
      echo "<input type='hidden' name='name' value='" . $data['name'] . "'>";
      echo "<input type='hidden' name='game' value='" . $data['game'] . "'>";
      echo "<input type='hidden' name='address' value='" . $data['address'] . "'>";
      echo "<input type='hidden' name='port' value='" . $data['port'] . "'>";
      echo "<input type='hidden' name='order' value='" . $data['order'] . "'>";
      echo "<input type='hidden' name='active' value='" . $data['active'] . "'>";
      echo "<button type='submit'><span class='gqp-gear' title='Editieren'></span></button>";
      echo "</form>";
      echo "</td>";
      echo "<td class='tbl1'>";
      echo "<form name='addserver' method='post' action='" . FUSION_SELF . $aidlink . "&server=del'>";
      echo "<input type='hidden' name='id' value='" . $data['id'] . "'>";
      echo "<button type='submit'><span class='gqp-trash-o' title='L&ouml;schen'></span></button>";
      echo "</form>";
      echo "</td>";
      echo "</tr>";
      }
     * */
} else {
    echo "keine Server Eingetragen!";
}
closetable();

//List all supported games
opentable("Game list - " . GameQ_Games('', 'count') . " games supported");
echo GameQ_Games();
closetable();
require_once(THEMES . "templates/footer.php");
?>