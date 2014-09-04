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
    $('#gqp_field_sel').on('change', function() {
        $( '#gqp_field' ).submit();
    });
});
</script>");

$id = (isset($_GET['id']) && is_numeric($_GET['id']) ? mysql_real_escape_string($_GET['id']) : "");
$sid = (isset($_GET['sid']) && is_numeric($_GET['sid']) ? mysql_real_escape_string($_GET['sid']) : "");

$server_field_panel = (isset($_POST['gqp_field_panel']) ? mysql_real_escape_string($_POST['gqp_field_panel']) : "");

// Suche Server uns lese Felder aus
$result = dbquery("SELECT * FROM " . DB_GQP_MAIN . " WHERE id = '$id'");
if (dbrows($result) != 0) {    
    $data = dbarray($result);
    // form actions
    if (isset($_GET['server']) && $_GET['server'] == "add") {
        $result = dbquery("INSERT INTO " . DB_GQP_SERVER_OPT . " (id, server_id, panel, field) VALUES ('', '$id', '0', '$server_field_panel')");
        //redirect(FUSION_SELF . $aidlink);
    }    
    if (isset($_GET['server']) && $_GET['server'] == "del") {        
        $result = dbquery("DELETE FROM " . DB_GQP_SERVER_OPT . " WHERE id='$sid'");
        redirect(FUSION_SELF . $aidlink . "&id=$id");
    }    

    opentable($locale['gqp_admin_006']);
    echo "<div id='gqp_server_form'>";
    echo "<h5>" . $data['name'] . "</h5>";
    $servers = GameQ_Create(GameQ_Servers($data['id']));
    if ($servers != FALSE) {
        $result = dbquery("SELECT id, field, panel FROM " . DB_GQP_SERVER_OPT . " WHERE server_id ='" . $data['id'] . "'");
        $saved_fields_panel = array();        
        if (dbrows($result) != 0) {
            for ($i = 0; $data_f = dbarray($result); $i++) {
                $saved_fields_panel[$i] = $data_f['field'];
                echo "<a href='" . FUSION_SELF . $aidlink . "&server=del&sid=" . $data_f['id'] . "&id=" . $data['id'] . "'>Lösche - " . $data_f['field'] . "</a>";
            }
        }
        // Option         
        foreach ($servers as $id => $data_f) {
            if ($data_f['gq_online']) {
                echo "<form id='gqp_field' name='addfield' method='post' action='" . FUSION_SELF . $aidlink . "&server=add&id=" . $data['id'] . "'>";                
                echo "<label>Was soll ausgegeben werden?</label><br>\n";
                echo "<select id='gqp_field_sel' size='0' style='width:50%;' name='gqp_field_panel'>";
                foreach ($data_f as $key => $value) {
                    if (!in_array($key, $saved_fields_panel)) {
                        echo "<option value='$key'/>$key [$value]</option>";
                    }
                }
                echo "</select>";                
                echo "</form>\n";
            }
        }
    }
    echo "</div>";
    echo "<a class='gqp_a' href='" . INFUSIONS . "gameserver_query_panel/gameserver_query_admin.php" . $aidlink . "'>zurück!</a>";
    closetable();
} else {
    redirect("gameserver_query_admin.php" . $aidlink);
}
require_once(THEMES . "templates/footer.php");
?>