<?php

/* -------------------------------------------------------+
  | PHP-Fusion Content Management System
  | Copyright � 2002 - 2008 Nick Jones
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

include INFUSIONS . "gameserver_query_panel/infusion_db.php";

if (!checkrights("GQP") || !defined("iAUTH") || $_GET['aid'] != iAUTH) {
    redirect(BASEDIR . "index.php");
}

if (file_exists(INFUSIONS . "gameserver_query_panel/locale/" . $settings['locale'] . ".php")) {
    include INFUSIONS . "gameserver_query_panel/locale/" . $settings['locale'] . ".php";
} else {
    include INFUSIONS . "gameserver_query_panel/locale/English.php";
}

$id = (isset($_POST['id']) && is_numeric($_POST['id']) ? mysql_real_escape_string($_POST['id']) : "");
$name = (isset($_POST['name']) ? mysql_real_escape_string($_POST['name']) : "");
$address = (isset($_POST['address']) ? mysql_real_escape_string($_POST['address']) : "");
$port = (isset($_POST['port']) && is_numeric($_POST['port']) ? mysql_real_escape_string($_POST['port']) : "");
$game = (isset($_POST['game']) ? mysql_real_escape_string($_POST['game']) : "");
$sort = (isset($_POST['sort']) && is_numeric($_POST['sort']) ? mysql_real_escape_string($_POST['sort']) : "");
$active = (isset($_POST['active']) && is_numeric($_POST['active']) ? mysql_real_escape_string($_POST['active']) : "");

if (isset($_GET['server']) && $_GET['server'] == "add") {
    if (isset($_POST['id'])) {
        $result = dbquery("SELECT * FROM " . DB_GQP_MAIN . " WHERE id = '$id'");
        if (dbrows($result) != 0) {
            $result = dbquery("UPDATE " . DB_GQP_MAIN . " SET name='$name', address='$address', port='$port', game='$game', sort='$sort', active='$active' WHERE id='$id' ");
        } else {
            $error = "Der Server ist schon eingetragen!";
        }
    } else {
        $result = dbquery("SELECT * FROM " . DB_GQP_MAIN . " WHERE id = '$id'");
        if (dbrows($result) == 0) {
            $result = dbquery("INSERT INTO " . DB_GQP_MAIN . " (id, name, address, port, game, sort, active) VALUES ('', '$name', '$address', '$port', '$game', '$sort', '$active')");
        } else {
            $error = "Der Server ist schon eingetragen!";
        }
    }
}
if (isset($_GET['server']) && $_GET['server'] == "del") {
    $result = dbquery("DELETE FROM " . DB_GQP_MAIN . " WHERE id='$id'");
}

opentable($locale['gqp_admin']);
/* * Server aus DB auslesen* */
echo "<h4>Eingetragene Server:</h4>";
$result = dbquery("SELECT * FROM " . DB_GQP_MAIN . " ORDER BY sort ASC");
if (dbrows($result) != 0) {
    echo "<table class='tbl-border forum_idx_table' cellpadding='0' cellspacing='1'>";
    echo "<tr>";
    echo "<th class='tbl2'><strong>ID</strong></th>";
    echo "<th class='tbl2'><strong>Name</strong></th>";
    echo "<th class='tbl2'><strong>Adresse</strong></th>";
    echo "<th class='tbl2'><strong>Port</strong></th>";
    echo "<th class='tbl2'><strong>Spiel</strong></th>";
    echo "<th class='tbl2'><strong>Sortierung</strong></th>";
    echo "<th class='tbl2'><strong>Active</strong></th>";
    echo "<th class='tbl2' colspan='2'><strong>Optionen</strong></th>";
    echo "</tr>";
    while ($data = dbarray($result)) {
        echo "<tr>";
        echo "<td class='tbl1'>" . $data['id'] . "</td>";
        echo "<td class='tbl1'>" . $data['name'] . "</td>";
        echo "<td class='tbl1'>" . $data['address'] . "</td>";
        echo "<td class='tbl1'>" . $data['port'] . "</td>";
        echo "<td class='tbl1'>" . $data['game'] . "</td>";
        echo "<td class='tbl1'>" . $data['sort'] . "</td>";
        if ($data['active'] == 1) {
            echo "<td class='tbl1'>Ja</td>";
        } else {
            echo "<td class='tbl1'>Nein</td>";
        }
        echo "<td class='tbl1'>";
        echo "<form name='addserver' method='post' action='" . FUSION_SELF . $aidlink . "&server=del'>";
        echo "<input type='hidden' name='id' value='" . $data['id'] . "'>";
        echo "<input type='submit' value='l&ouml;schen'>";
        echo "</form>";
        echo "</td>";
        echo "<td class='tbl1'>";
        echo "<form name='addserver' method='post' action='" . FUSION_SELF . $aidlink . "&server=edit'>";
        echo "<input type='hidden' name='id' value='" . $data['id'] . "'>";
        echo "<input type='hidden' name='name' value='" . $data['name'] . "'>";
        echo "<input type='hidden' name='address' value='" . $data['address'] . "'>";
        echo "<input type='hidden' name='port' value='" . $data['port'] . "'>";
        echo "<input type='hidden' name='game' value='" . $data['game'] . "'>";
        echo "<input type='hidden' name='sort' value='" . $data['sort'] . "'>";
        echo "<input type='hidden' name='active' value='" . $data['active'] . "'>";
        echo "<input type='submit' value='editieren'>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "keine Server Eingetragen!";
}
echo "<b>" . $error . "<b>";
echo "<hr class='side-hr'/>";
/* * Server hinzufuegen/editieren * */
if (isset($_GET['server']) && $_GET['server'] == "edit") {
    echo "<h4>Server editieren:</h4>\n";
    echo "<table class='tbl-border forum_idx_table' cellpadding='0' cellspacing='1'>\n";
    echo "<tr>\n";
    echo "<th class='tbl2'><strong>Name</strong></th>\n";
    echo "<th class='tbl2'><strong>Adresse</strong></th>\n";
    echo "<th class='tbl2'><strong>Port</strong></th>\n";
    echo "<th class='tbl2'><strong>Spiel</strong></th>\n";
    echo "<th class='tbl2'><strong>Sortierung</strong></th>\n";
    echo "<th class='tbl2'><strong>Active</strong></th>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class='tbl1'><form name='addserver' method='post' action='" . FUSION_SELF . $aidlink . "&server=add'>\n";
    echo "<input type='hidden' name='id' value='$id'>";
    echo "<input name='name' type='text' size='20' maxlength='50' value='$name' ></td>\n";
    echo "<td class='tbl1'><input name='address' type='text' size='50' maxlength='50' value='$address' ></td>\n";
    echo "<td class='tbl1'><input name='port' type='text' size='6' maxlength='6' value='$port' ></td>\n";
    echo "<td class='tbl1'><input name='game' type='text' size='10' maxlength='10' value='$game' ></td>\n";
    echo "<td class='tbl1'><input name='sort' type='text' size='3' maxlength='3' value='$sort'></td>\n";
    if (isset($active) && $active == "1") {
        echo "<td class='tbl1'><input type='checkbox' name='active' value='1' checked></td>\n";
    } else {
        echo "<td class='tbl1'><input type='checkbox' name='active' value='1'></td>\n";
    }
    echo "</tr>\n";
    echo "</table>\n";
    echo "<input type='submit' value='Absenden'>\n";
    echo "<input type='reset' value='Abbrechen'>\n";
    echo "</form>\n";
} else {
    echo "<h4>Server hinzufügen:</h4>\n";
    echo "<table class='tbl-border forum_idx_table' cellpadding='0' cellspacing='1'>\n";
    echo "<tr>\n";
    echo "<th class='tbl2'><strong>Name</strong></th>\n";
    echo "<th class='tbl2'><strong>Adresse</strong></th>\n";
    echo "<th class='tbl2'><strong>Port</strong></th>\n";
    echo "<th class='tbl2'><strong>Spiel</strong></th>\n";
    echo "<th class='tbl2'><strong>Sortierung</strong></th>\n";
    echo "<th class='tbl2'><strong>Active</strong></th>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class='tbl1'><form name='addserver' method='post' action='" . FUSION_SELF . $aidlink . "&server=add'>\n";
    echo "<input name='name' type='text' size='20' maxlength='50' value='Server Name' ></td>\n";
    echo "<td class='tbl1'><input name='address' type='text' size='50' maxlength='50' value='Server Adresse (IP or Hostname)' ></td>\n";
    echo "<td class='tbl1'><input name='port' type='text' size='6' maxlength='6' value='27015' ></td>\n";
    echo "<td class='tbl1'><input name='game' type='text' size='10' maxlength='10' value='' ></td>\n";
    echo "<td class='tbl1'><input name='sort' type='text' size='3' maxlength='3' value=''></td>\n";
    echo "<td class='tbl1'><input type='checkbox' name='active' value='1' checked></td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    echo "<input type='submit' value='Absenden'>\n";
    echo "<input type='reset' value='Abbrechen'>\n";
    echo "</form>\n";
}
closetable();

require_once(THEMES . "templates/footer.php");
?>