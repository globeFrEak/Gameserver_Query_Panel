<?php

/* -------------------------------------------------------+
  | PHP-Fusion Content Management System
  | Copyright � 2002 - 2008 Nick Jones
  | http://www.php-fusion.co.uk/
  +--------------------------------------------------------+
  | Infusion: Source Query Panel
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

include INFUSIONS . "source_query_panel/infusion_db.php";

if (!checkrights("SQP") || !defined("iAUTH") || $_GET['aid'] != iAUTH) {
    redirect("../index.php");
}

if (file_exists(INFUSIONS . "source_query_panel/locale/" . $settings['locale'] . ".php")) {
    include INFUSIONS . "source_query_panel/locale/" . $settings['locale'] . ".php";
} else {
    include INFUSIONS . "source_query_panel/locale/English.php";
}
$error = "";

$id = $_POST['id'];
$server_id = $_POST['server_id'];
$name = $_POST['name'];
$sort = $_POST['sort'];
$active = $_POST['active'];

if (isset($_GET['server']) && $_GET['server'] == "add") {
    if (isset($_POST['id'])) {
        $result = dbquery("SELECT * FROM " . DB_HLS_TABLE . " WHERE server_id = '$server_id'");
        if (dbrows($result) == 0) {
            $result = dbquery("UPDATE " . DB_HLS_TABLE . " SET server_id='$server_id', name='$name', sort='$sort', active='$active' WHERE id='$id' ");
        } else {
            $error = "Der Server ist schon eingetragen!";
        }
    } else {
        $result = dbquery("SELECT * FROM " . DB_HLS_TABLE . " WHERE server_id = '$server_id'");
        if (dbrows($result) == 0) {
            $result = dbquery("INSERT INTO " . DB_HLS_TABLE . " (id, server_id, name, sort, active) VALUES ('', '$server_id', '$name', '$sort', '$active')");
        } else {
            $error = "Der Server ist schon eingetragen!";
        }
    }
}
if (isset($_GET['server']) && $_GET['server'] == "del") {
    $result = dbquery("DELETE FROM " . DB_HLS_TABLE . " WHERE id='" . $_POST['id'] . "'");
}

opentable($locale['hls_admin']);
/* * Server aus DB auslesen* */
echo "<h4>Eingetragene Server:</h4>";
$result = dbquery("SELECT * FROM " . DB_HLS_TABLE . " ORDER BY sort ASC");
if (dbrows($result) != 0) {
    echo "<table class='tbl-border forum_idx_table' cellpadding='0' cellspacing='1'>";
    echo "<tr>";
    echo "<td class='tbl1'><strong>ID</strong></td>";
    echo "<td class='tbl2'><strong>Server ID</strong></td>";
    echo "<td class='tbl1'><strong>Name</strong></td>";
    echo "<td class='tbl2'><strong>Sortierung</strong></td>";
    echo "<td class='tbl1'><strong>Active</strong></td>";
    echo "<td class='tbl2' colspan='2'><strong>Optionen</strong></td>";
    echo "</tr>";
    while ($data = dbarray($result)) {
        echo "<tr>";
        echo "<td class='tbl1'>" . $data['id'] . "</td>";
        echo "<td class='tbl2'>" . $data['server_id'] . "</td>";
        echo "<td class='tbl1'>" . $data['name'] . "</td>";
        echo "<td class='tbl2'>" . $data['sort'] . "</td>";
        if ($data['active'] == 1) {
            echo "<td class='tbl1'>Ja</td>";
        } else {
            echo "<td class='tbl1'>Nein</td>";
        }
        echo "<td class='tbl2'>";
        echo "<form name='addserver' method='post' action='" . FUSION_SELF . $aidlink . "&server=del'>";
        echo "<input type='hidden' name='id' value='" . $data['id'] . "'>";
        echo "<input type='submit' value='l&ouml;schen'>";
        echo "</form>";
        echo "</td>";
        echo "<td class='tbl1'>";
        echo "<form name='addserver' method='post' action='" . FUSION_SELF . $aidlink . "&server=edit'>";
        echo "<input type='hidden' name='id' value='" . $data['id'] . "'>";
        echo "<input type='hidden' name='server_id' value='" . $data['server_id'] . "'>";
        echo "<input type='hidden' name='name' value='" . $data['name'] . "'>";
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
    echo "<td class='tbl1'><strong>Server ID</strong></td>\n";
    echo "<td class='tbl2'><strong>Name</strong></td>\n";
    echo "<td class='tbl1'><strong>Sortierung</strong></td>\n";
    echo "<td class='tbl2'><strong>Active</strong></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class='tbl1'><form name='addserver' method='post' action='" . FUSION_SELF . $aidlink . "&server=add'>\n";
    echo "<input type='hidden' name='id' value='$id' readonly='readonly'>\n";
    echo "<input name='server_id' type='text' size='4' maxlength='4' value='$server_id' readonly='readonly'></td>\n";
    echo "<td class='tbl1'><input name='name' type='text' size='50' maxlength='50' value='$name' readonly='readonly'></td>\n";
    echo "<td class='tbl2'><input name='sort' type='text' size='3' maxlength='3' value='$sort'></td>\n";
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
}
closetable();

require_once(THEMES . "templates/footer.php");
?>