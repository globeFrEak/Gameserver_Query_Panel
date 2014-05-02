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

include_once INFUSIONS . "gameserver_query_panel/infusion_db.php";

// Server abfragen!
if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] != 0) {
    $id = mysql_real_escape_string($_GET['id']);
    $result_detail = dbquery("SELECT id, address, port, game FROM " . DB_GQP_MAIN . "                             
                WHERE active ='1' AND id = '$id' ORDER BY sort");
} else {
    $result_detail = dbquery("SELECT id, address, port, game FROM " . DB_GQP_MAIN . "                             
                WHERE active ='1' ORDER BY sort");
}
$rows = dbrows($result_detail);
$Servers_GameQ = array();
if ($rows != 0) {
    for ($i = 0; $data = dbarray($result_detail); $i++) {
        $Servers_GameQ[$i]['id'] = $data['id'];
        $Servers_GameQ[$i]['type'] = $data['game'];
        $Servers_GameQ[$i]['host'] = $data['address'] . ":" . $data['port'];
    }
}

include_once INFUSIONS . "gameserver_query_panel/functions.php";

$Servers = GameQ_Create($Servers_GameQ);
foreach ($Servers as $id => $data) {
    if (!$data['gq_online']) {
        echo "<p>The server did not respond</p>\n";        
    } else {
        $join = ($data['gq_joinlink'] ? " <a href='" . $data['gq_joinlink'] . "' alt='Verbinden mit " . $data['gq_hostname'] . "' title='Verbinden mit " . $data['gq_hostname'] . "'><span class='gqp-sign-in'></span></a>" : "");
        $password = ($data['gq_password'] == 1 ? "<span class='gqp-lock'></span> " : "");
        echo "<div>";
        echo "<h5>$password<a href='" . INFUSIONS . "gameserver_query_panel/gameserver_query_detail.php?id=$id'>" . $data['gq_hostname'] . "</a>$join</h5>";
        echo "<div><img src='" . INFUSIONS . "gameserver_query_panel/images/games/" . $data['gq_type'] . ".jpg' alt='" . GameQ_GetInfo($data['gq_type'], 'N') . "' title='" . GameQ_GetInfo($data['gq_type'], 'N') . "' height='16' width='16'/> ";
        echo "<span><span class='gqp-globe'></span> " . $data['gq_mapname'] . "</span>";
        echo "<span style='float:right'>" . $data['gq_numplayers'] . "/" . $data['gq_maxplayers'] . " <span class='gqp-group'></span></span></div>";
        echo "<div><h5>IP: " . $data['gq_address'] . ":" . $data['gq_port'] . "</h5>";
        echo "</div>";
        echo "</div>";
    }
}
?>