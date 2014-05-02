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

add_to_head("<link rel='stylesheet' href='" . INFUSIONS . "gameserver_query_panel/gqp.css' type='text/css'/>");

if (file_exists(INFUSIONS . "gameserver_query_panel/locale/" . $settings['locale'] . ".php")) {
    include_once INFUSIONS . "gameserver_query_panel/locale/" . $settings['locale'] . ".php";
} else {
    include_once INFUSIONS . "gameserver_query_panel/locale/English.php";
}

// Server abfragen!
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = mysql_real_escape_string($_GET['id']);
    $result = dbquery("SELECT id, address, port, game FROM " . DB_GQP_MAIN . "                             
                WHERE active ='1' AND id = '$id' ORDER BY sort");
} else {
    $result = dbquery("SELECT id, address, port, game FROM " . DB_GQP_MAIN . "                             
                WHERE active ='1' ORDER BY sort");
}
$rows = dbrows($result);
$Servers_GameQ = array();
if ($rows != 0) {
    for ($i = 0; $data = dbarray($result); $i++) {
        $Servers_GameQ[$i]['id'] = $data['id'];
        $Servers_GameQ[$i]['type'] = $data['game'];
        $Servers_GameQ[$i]['host'] = $data['address'] . ":" . $data['port'];
    }
}

include_once INFUSIONS . "gameserver_query_panel/functions.php";

function GameQ_Print_Detail($Servers_GameQ) {
    $result = GameQ_Create($Servers_GameQ);
    foreach ($result as $id => $data) {
        if (!$data['gq_online']) {
            print_r($data);
            printf("<p>The server did not respond</p>\n");
            return;
        }
        echo "<div>";
        echo "<h5><a href='" . INFUSIONS . "gameserver_query_panel/gameserver_query_detail.php?id=$id'>" . $data['gq_hostname'] . "</a></h5>";
        echo "<img src='" . INFUSIONS . "gameserver_query_panel/images/games/" . $data['gq_type'] . ".jpg' alt='" . GameQ_GetInfo($data['gq_type'], 'N') . "' title='" . GameQ_GetInfo($data['gq_type'], 'N') . "' height='16' width='16'/> ";
        echo "<span><span class='gqp-globe'></span> " . $data['gq_mapname'] . "</span>";
        echo "<span style='float:right'><span class='gqp-group'></span> " . $data['gq_numplayers'] . "/" . $data['gq_maxplayers'] . "</span>";
        echo print_r($data);
        echo "</div>";
    }
}

if ($rows != 0) {
    opentable("<span class='gqp-gamepad'></span> " . $locale['gqp_title']);
    GameQ_Print_Detail($Servers_GameQ);
    closetable();
} else {
    redirect(BASEDIR . "index.php");
}
require_once THEMES . "templates/footer.php";
?>