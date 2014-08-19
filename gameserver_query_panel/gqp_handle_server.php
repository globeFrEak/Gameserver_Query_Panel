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
add_to_head("<link rel='stylesheet' href='" . INFUSIONS . "gameserver_query_panel/gqp.css' type='text/css'/>");
add_to_head("<script>
$(document).ready(function() {     
    //set default game port on placeholder tag    
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
    //form reset
    $('#gqpreset','#gqpserver').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked');
    
    //form validation    
    $('#gqpsubmit').click(function() {
        $('.gqp_success').remove();
        $('.gqp_error').remove();
        var regexHOST = /^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$|^(([a-zA-Z]|[a-zA-Z][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z]|[A-Za-z][A-Za-z0-9\-]*[A-Za-z0-9])$|^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?\s*$/;
        var regexPORT = /^([0-9]{4}|[0-9]{5})$/;
        if (regexHOST.test($('#gqp_address').val())) {
            var gqp_address = 1;
            $('#gqp_address').before('<span class=\"gqp-check gqp_success\"></span>');
        } else {
            $('#gqp_address').before('<span class=\"gqp-times gqp_error\"></span>');
        }
        if (regexPORT.test($('#gqp_port').val())) {
            var gqp_port = 1;
            $('#gqp_port').before('<span class=\"gqp-check gqp_success\"></span>');
        } else {
            $('#gqp_port').before('<span class=\"gqp-times gqp_error\"></span>');
        }
        if (gqp_address && gqp_port) {
            $('#gqpserver').submit();
        }
        
    });
    $('#gqp_gl_btn').click(function() {        
        if ( $(this).closest('label').find('span').hasClass('gqp-chevron-down') ) {
            $(this).closest('label').find('span').removeClass('gqp-chevron-down').addClass('gqp-chevron-up');            
        } else {
            $(this).closest('label').find('span').removeClass('gqp-chevron-up').addClass('gqp-chevron-down');            
        }                
        $('#gqp_gamelist').slideToggle('slow', function() {        
        });
    });
});

</script>");

$id = (isset($_POST['id']) && is_numeric($_POST['id']) ? mysql_real_escape_string($_POST['id']) : "");
$address = (isset($_POST['address']) ? mysql_real_escape_string($_POST['address']) : "");
$port = (isset($_POST['port']) && is_numeric($_POST['port']) ? mysql_real_escape_string($_POST['port']) : "");
$game = (isset($_POST['game']) ? mysql_real_escape_string($_POST['game']) : "");
$name = (isset($_POST['name']) && strlen($_POST['name']) > 0 ? mysql_real_escape_string($_POST['name']) : $address . "(" . $game . ")");
$server_order = (isset($_POST['server_order']) && is_numeric($_POST['server_order']) ? mysql_real_escape_string($_POST['server_order']) : "");
$active = (isset($_POST['active']) && is_numeric($_POST['active']) ? mysql_real_escape_string($_POST['active']) : "");
$panel_name = (isset($_POST['panel_name']) ? mysql_real_escape_string($_POST['panel_name']) : $locale['gqp_title']);
$panel_template = (isset($_POST['panel_template']) ? mysql_real_escape_string($_POST['panel_template']) : "GQP_Custom_1.php");
        
$server_fields_panel = (isset($_POST['gqp_fields_panel']) && !empty($_POST['gqp_fields_panel']) ? filter_var_array($_POST['gqp_fields_panel'], FILTER_SANITIZE_STRING) : 0);
$server_fields_detail = (isset($_POST['gqp_fields_detail']) && !empty($_POST['gqp_fields_detail']) ? filter_var_array($_POST['gqp_fields_detail'], FILTER_SANITIZE_STRING) : 0);

if (isset($_GET['server']) && $_GET['server'] == "add") {
    if (isset($_POST['id'])) {
        $result = dbquery("SELECT * FROM " . DB_GQP_MAIN . " WHERE id = '$id'");
        if (dbrows($result) != 0) {
            $result = dbquery("UPDATE " . DB_GQP_MAIN . " SET name='$name', address='$address', port='$port', game='$game', server_order='$server_order', active='$active' WHERE id='$id' ");
            //// clear server settings
            $result = dbquery("DELETE FROM " . DB_GQP_SERVER_OPT . " WHERE server_id='$id'");
            for ($i = 0; $i < count($server_fields_panel) && is_array($server_fields_panel); $i++) {
                $result = dbquery("INSERT INTO " . DB_GQP_SERVER_OPT . " (id, server_id, panel, field) VALUES ('','$id',0,'$server_fields_panel[$i]')");
            }
            for ($i = 0; $i < count($server_fields_detail) && is_array($server_fields_detail); $i++) {
                $result = dbquery("INSERT INTO " . DB_GQP_SERVER_OPT . " (id, server_id, panel, field) VALUES ('','$id',1,'$server_fields_detail[$i]')");
            }
            redirect("gameserver_query_admin.php" . $aidlink);
        } else {
            $error = $locale['gqp_admin_004'];
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
        redirect("gameserver_query_admin.php" . $aidlink);
    }
}
if (isset($_GET['server']) && $_GET['server'] == "del") {
    $data = dbarray(dbquery("SELECT server_order FROM " . DB_GQP_MAIN . " WHERE id='" . $id . "'"));
    $result = dbquery("DELETE FROM " . DB_GQP_MAIN . " WHERE id='$id'");
    $result = dbquery("UPDATE " . DB_GQP_MAIN . " SET server_order=server_order-1 WHERE server_order>='" . $data['server_order'] . "'");
    $result = dbquery("DELETE FROM " . DB_GQP_SERVER_OPT . " WHERE server_id='$id'");
    redirect("gameserver_query_admin.php" . $aidlink);
}
if (isset($_GET['server']) && $_GET['server'] == "state") {
    $result = dbquery("UPDATE " . DB_GQP_MAIN . " SET active=" . ($active ? 0 : 1) . " WHERE id='$id'");
    redirect("gameserver_query_admin.php" . $aidlink);
}

if (isset($_GET['settings']) && $_GET['settings'] == "edit") {
    $result = dbquery("UPDATE " . DB_GQP_SETTINGS . " SET panel_name='$panel_name', panel_template='$panel_template' WHERE id='$id' ");
    redirect("gameserver_query_admin.php" . $aidlink);
}

/* * Server hinzufuegen/editieren * */
$exit = "<a href='" . FUSION_SELF . $aidlink . "'><button><span class='gqp-times' title='" . $locale['gqp_admin_005'] . "'></span></button></a>";
opentable((isset($_GET['server']) && $_GET['server'] == "edit" ? $locale['gqp_admin_006'] . $exit : $locale['gqp_admin_002']));
echo "<div id='gqp_server_form'>";
echo "<form id='gqpserver' name='addserver' method='post' action='" . FUSION_SELF . $aidlink . "&server=add'>";
if (isset($_GET['server']) && $_GET['server'] == "edit") {
    echo "<input type='hidden' name='id' value='$id' />";

    echo "<label>" . $locale['gqp_admin_007'] . "</label>";
    echo "<input name='name' type='text' size='20' maxlength='50' value='$name' />\n";

    echo "<label>" . $locale['gqp_admin_008'] . "</label>\n";
    echo "<select name='game' class='textbox' maxlength='30'>"
    . GameQ_Games($game, 'dropdown')
    . "</select>";

    echo "<label>" . $locale['gqp_admin_009'] . "</label>\n";
    echo "<input id='gqp_address' name='address' type='text' size='20' maxlength='50' value='$address' />\n";

    echo "<label>" . $locale['gqp_admin_010'] . "</label>\n";
    echo "<input id='gqp_port' name='port' type='text' size='2' maxlength='6' value='$port' placeholder='27015' />\n";

    echo "<label>" . $locale['gqp_admin_011'] . "</label>\n";
    if (isset($active) && $active == "1") {
        echo "<input class='gqp_checkbox' type='checkbox' name='active' value='1' checked />\n";
    } else {
        echo "<input class='gqp_checkbox' type='checkbox' name='active' value='1' />\n";
    }
    echo "</br>";
    $servers = GameQ_Create(GameQ_Servers($id));
    if ($servers != FALSE) {
        $result = dbquery("SELECT field, panel FROM " . DB_GQP_SERVER_OPT . " WHERE server_id ='$id'");
        $saved_fields_panel = array();
        $saved_fields_detail = array();
        if (dbrows($result) != 0) {
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
                echo "<label id='gqp_gl_btn'>" . $locale['gqp_admin_003'] . "<span class='gqp-chevron-down'></span></label>";
                echo "<div id='gqp_gamelist' style='display:none;'>";
                echo "<label>Panel + Detail Anzeige</label><br>\n";
                foreach ($data as $key => $value) {
                    echo "<input class='gqp_checkbox' type='checkbox' name='gqp_fields_panel[]' value='$key' " . (in_array($key, $saved_fields_panel) ? "checked" : "") . " />"
                    . "<input class='gqp_checkbox' type='checkbox' name='gqp_fields_detail[]' value='$key' " . (in_array($key, $saved_fields_detail) ? "checked" : "") . " /><b>$key</b> - ($value)<br>";
                }
                echo "</div><br>";
            }
        }
    }
} else {
    echo "<label>" . $locale['gqp_admin_007'] . "</label>\n";
    echo "<input name='name' type='text' size='20' maxlength='50' placeholder='" . $locale['gqp_admin_007a'] . "' /><br>\n";
    echo "<label>" . $locale['gqp_admin_008'] . "</label>\n";
    echo "<select name='game' class='textbox' maxlength='30'>"
    . GameQ_Games($game, 'dropdown')
    . "</select><br>\n";
    echo "<label>" . $locale['gqp_admin_009'] . "</label>\n";
    echo "<input id='gqp_address' name='address' type='text' size='20' maxlength='50' placeholder='" . $locale['gqp_admin_009a'] . "' /><br>\n";
    echo "<label>" . $locale['gqp_admin_010'] . "</label>\n";
    echo "<input id='gqp_port' name='port' type='text' size='2' maxlength='6' placeholder='' /><br>\n";
    echo "<label>" . $locale['gqp_admin_011'] . "</label>\n";
    echo "<input class='gqp_checkbox' type='checkbox' name='active' value='1' checked /><br>\n";
}
echo "<button type='button' id='gqpsubmit'><span class='gqp-check' title='" . $locale['gqp_admin_edit'] . "'></span></button>";
echo "<button type='reset' id='gqpreset'><span class='gqp-rotate-left' title='" . $locale['gqp_admin_005'] . "'></span></button>";
echo "</form>\n";
echo "</div>";
echo "<a class='gqp_a' href='" . INFUSIONS . "gameserver_query_panel/gameserver_query_admin.php" . $aidlink . "'>zurück!</a>";
closetable();
require_once(THEMES . "templates/footer.php");
?>