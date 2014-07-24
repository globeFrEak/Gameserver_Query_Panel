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
    ///reset
    $('#gqpreset','#gqpserver').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked');
    ///setzt placeholder tag von port je nach spiel    
    $(\"select[name='game']\").change(function() {
        var gametype = $(this).val();
        $.ajax({
            url:'" . INFUSIONS . "gameserver_query_panel/ajax_admin.php',
            data: {game: gametype},            
            type: 'post',
            success: function(data) { 
                $(\"input[name='port']\").attr('placeholder', data);               
            }            
        });                
    });
});

</script>");

$id = (isset($_POST['id']) && is_numeric($_POST['id']) ? mysql_real_escape_string($_POST['id']) : "");
$name = (isset($_POST['name']) ? mysql_real_escape_string($_POST['name']) : "");
$address = (isset($_POST['address']) ? mysql_real_escape_string($_POST['address']) : "");
$port = (isset($_POST['port']) && is_numeric($_POST['port']) ? mysql_real_escape_string($_POST['port']) : "");
$game = (isset($_POST['game']) ? mysql_real_escape_string($_POST['game']) : "");
$server_order = (isset($_POST['server_order']) && is_numeric($_POST['server_order']) ? mysql_real_escape_string($_POST['server_order']) : "");
$active = (isset($_POST['active']) && is_numeric($_POST['active']) ? mysql_real_escape_string($_POST['active']) : "");

$server_fields_panel = (isset($_POST['gqp_fields_panel']) ? filter_var_array($_POST['gqp_fields_panel'], FILTER_SANITIZE_STRING) : "");
$server_fields_detail = (isset($_POST['gqp_fields_detail']) ? filter_var_array($_POST['gqp_fields_detail'], FILTER_SANITIZE_STRING) : "");

$error = "";

if (isset($_GET['server']) && $_GET['server'] == "add") {
    if (isset($_POST['id'])) {
        $result = dbquery("SELECT * FROM " . DB_GQP_MAIN . " WHERE id = '$id'");
        if (dbrows($result) != 0) {
            $result = dbquery("UPDATE " . DB_GQP_MAIN . " SET name='$name', address='$address', port='$port', game='$game', server_order='$server_order', active='$active' WHERE id='$id' ");
            //// clear server settings
            $result = dbquery("DELETE FROM " . DB_GQP_SERVER_OPT . " WHERE server_id='$id'");
            for ($i = 0; $i < count($server_fields_panel); $i++) {
                $result = dbquery("INSERT INTO " . DB_GQP_SERVER_OPT . " (id, server_id, panel, field) VALUES ('','$id',0,'$server_fields_panel[$i]')");
            }
            for ($i = 0; $i < count($server_fields_detail); $i++) {
                $result = dbquery("INSERT INTO " . DB_GQP_SERVER_OPT . " (id, server_id, panel, field) VALUES ('','$id',1,'$server_fields_detail[$i]')");
            }
        } else {
            $error = "<b>Der Server ist schon eingetragen!</b>";
        }
    } else {
        //Panel Order            
        $result_order = dbquery("SELECT server_order FROM " . DB_GQP_MAIN . " ORDER BY server_order DESC LIMIT 1");
        if (dbrows($result_order) != 0) {
            $data_order = dbarray($result_order);
            $neworder = $data_order['server_order'] + 1;
        } else {
            $neworder = 1;
        }

        $result = dbquery("INSERT INTO " . DB_GQP_MAIN . " (id, name, address, port, game, server_order, active) VALUES ('', '$name', '$address', '$port', '$game', '$neworder', '$active')");
    }
}
if (isset($_GET['server']) && $_GET['server'] == "del") {
    $data = dbarray(dbquery("SELECT server_order FROM " . DB_GQP_MAIN . " WHERE id='" . $id . "'"));
    $result = dbquery("DELETE FROM " . DB_GQP_MAIN . " WHERE id='$id'");
    $result = dbquery("UPDATE " . DB_GQP_MAIN . " SET server_order=server_order-1 WHERE server_order>='" . $data['server_order'] . "'");
    $result = dbquery("DELETE FROM " . DB_GQP_SERVER_OPT . " WHERE server_id='$id'");
}

opentable($locale['gqp_admin']);
echo "<div id='info'></div>\n";
/* * Server aus DB auslesen* */
echo "<h4>Eingetragene Server:</h4>";
$result = dbquery("SELECT * FROM " . DB_GQP_MAIN . " ORDER BY server_order");
if (dbrows($result) != 0) {
    echo "<table class='tbl-border forum_idx_table' cellpadding='0' cellspacing='1'>";
    echo "<tr>";
    echo "<th class='tbl2'><strong>Name</strong></th>";
    echo "<th class='tbl2'><strong>Spiel</strong></th>";
    echo "<th class='tbl2'><strong>Adresse:Port</strong></th>";
    echo "<th class='tbl2'><strong>Active</strong></th>";
    echo "<th class='tbl2'><strong>Optionen</strong></th>";
    echo "</tr>";

    /// SORTABLE LIST
    $k = 0;
    echo "<tr><td colspan='4'>";
    echo "<ul id='panel-side1' data-side='1' style='list-style: none;' class='panels-list connected'>\n";

    while ($data = dbarray($result)) {
        $row_color = ($k % 2 == 0 ? "tbl1" : "tbl2");
        echo "<li id='listItem_" . $data['id'] . "' class='" . $row_color . ($data['active'] == 0 ? " pdisabled" : "") . "'>\n";
        echo "<div style='float:left;'><img src='" . IMAGES . "arrow.png' alt='move' class='handle' /></div>\n";
        echo "<div style='float:left;'>" . $data['name'] . "</div>\n";
        echo "<div style='float:left;'><img src='" . INFUSIONS . "gameserver_query_panel/images/games/" . $data['game'] . ".jpg' alt='" . GameQ_GetInfo($data['game'], 'N') . "' title='" . GameQ_GetInfo($data['game'], 'N') . "' height='32' width='32'/></div>\n";
        echo "<div style='float:left;'>" . $data['address'] . ":" . $data['port'] . "</div>\n";
        echo "<div style='float:right;'>";
        echo "<form name='addserver' method='post' action='" . FUSION_SELF . $aidlink . "&server=edit'>";
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
        echo "<form name='addserver' method='post' action='" . FUSION_SELF . $aidlink . "&server=del'>";
        echo "<input type='hidden' name='id' value='" . $data['id'] . "'>";
        echo "<button type='submit'><span class='gqp-trash-o' title='L&ouml;schen'></span></button>";
        echo "</form>";
        echo "</div>";
        echo "<div style='clear:both;'></div>\n";
        echo "</li>\n";
        $k++;
    }

    echo "</ul>\n</td>\n</tr>\n";
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
    echo "</table>";
} else {
    echo "keine Server Eingetragen!";
}
echo $error;
echo "<hr class='side-hr'/>";

/* * Server hinzufuegen/editieren * */
$exit = "<a href='" . FUSION_SELF . $aidlink . "'><button><span class='gqp-times' title='Formular zurücksetzen'></span></button></a>";
echo "<h4>" . (isset($_GET['server']) && $_GET['server'] == "edit" ? "Server editieren " . $exit : "Server hinzufügen") . "</h4>\n";
echo "<form id='gqpserver' name='addserver' method='post' action='" . FUSION_SELF . $aidlink . "&server=add'>";
echo "<table class='tbl-border forum_idx_table' cellpadding='0' cellspacing='1'>\n";
echo "<tr>\n";
echo "<th class='tbl2'><strong>Name</strong></th>\n";
echo "<th class='tbl2'><strong>Spiel</strong></th>\n";
echo "<th class='tbl2'><strong>Adresse</strong></th>\n";
echo "<th class='tbl2'><strong>Port</strong></th>\n";
echo "<th class='tbl2'><strong>Active</strong></th>\n";
echo "</tr>\n";
if (isset($_GET['server']) && $_GET['server'] == "edit") {
    echo "<tr>\n";
    echo "<td class='tbl1'>\n";
    echo "<input type='hidden' name='id' value='$id'>";
    echo "<input name='name' type='text' size='20' maxlength='50' value='$name' ></td>\n";
    echo "<td class='tbl1'>"
    . "<select name='game' class='textbox' maxlength='10'>"
    . GameQ_Games($game, 'dropdown')
    . "</select>"
    . "</td>\n";
    echo "<td class='tbl1'><input name='address' type='text' size='20' maxlength='50' value='$address' ></td>\n";
    echo "<td class='tbl1'><input name='port' type='text' size='2' maxlength='6' value='$port' placeholder='27015' ></td>\n";
    if (isset($active) && $active == "1") {
        echo "<td class='tbl1'><input type='checkbox' name='active' value='1' checked></td>\n";
    } else {
        echo "<td class='tbl1'><input type='checkbox' name='active' value='1'></td>\n";
    }
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class='tbl1' colspan='6'>";
    $servers = GameQ_Create(GameQ_Servers($id));
    if ($servers != FALSE) {
        $result = dbquery("SELECT field, panel FROM " . DB_GQP_SERVER_OPT . " WHERE server_id ='$id'");
        if (dbrows($result) != 0) {
            $saved_fields_panel = array();
            $saved_fields_detail = array();
            for ($i = 0; $data = dbarray($result); $i++) {
                if ($data['panel'] == 0) {
                    $saved_fields_panel[$i] = $data['field'];
                } else {
                    $saved_fields_detail[$i] = $data['field'];
                }
            }
        }
        foreach ($servers as $id => $data) {
            if ($data['gq_online']) {
                foreach ($data as $key => $value) {
                    echo "<input type='checkbox' name='gqp_fields_panel[]' value='$key' " . (in_array($key, $saved_fields_panel) ? "checked" : "") . ">"
                    . "<input type='checkbox' name='gqp_fields_detail[]' value='$key' " . (in_array($key, $saved_fields_detail) ? "checked" : "") . ">$key</b> ---> $value(" . is_array($value) . ")<br>";
                }
            }
        }
    }
    echo "</td>\n</tr>\n";
} else {
    echo "<tr>\n";
    echo "<td class='tbl1'>\n";
    echo "<input name='name' type='text' size='20' maxlength='50' placeholder='Server Name' ></td>\n";
    echo "<td class='tbl1'>"
    . "<select name='game' class='textbox' maxlength='10'>"
    . GameQ_Games($game, 'dropdown')
    . "</select>"
    . "</td>\n";
    echo "</td>\n";
    echo "<td class='tbl1'><input name='address' type='text' size='20' maxlength='50' placeholder='Server Adresse (IP or Hostname)'></td>\n";
    echo "<td class='tbl1'><input name='port' type='text' size='2' maxlength='6' placeholder='27015' ></td>\n";
    echo "<td class='tbl1'><input type='checkbox' name='active' value='1' checked></td>\n";
    echo "</tr>\n";
}
echo "<tr><td colspan='6'>";
echo "<button type='submit'><span class='gqp-check' title='Editieren'></span></button>";
echo "<button type='reset' id='gqpreset'><span class='gqp-rotate-left' title='Zurücksetzen'></span></button>";
echo "</td></tr>\n";
echo "</table>\n";
echo "</form>\n";
closetable();

//List all supported games
opentable("Game list - " . GameQ_Games('', 'count') . " games supported");
echo GameQ_Games();
closetable();
require_once(THEMES . "templates/footer.php");
?>