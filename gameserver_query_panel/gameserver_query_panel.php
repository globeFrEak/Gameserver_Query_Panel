<?php

/* -------------------------------------------------------+
  | PHP-Fusion Content Management System
  | Copyright 2002 - 2008 Nick Jones
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
include INFUSIONS . "gameserver_query_panel/functions.php";

add_to_head("<link rel='stylesheet' href='" . INFUSIONS . "gameserver_query_panel/gqp.css' type='text/css'/>");

if (file_exists(INFUSIONS . "gameserver_query_panel/locale/" . $settings['locale'] . ".php")) {
    include INFUSIONS . "gameserver_query_panel/locale/" . $settings['locale'] . ".php";
} else {
    include INFUSIONS . "gameserver_query_panel/locale/English.php";
}

$Servers_GameQ = array();
$result = dbquery("SELECT id, address, port, game FROM " . DB_GQP_MAIN . "                             
                WHERE active ='1' ORDER BY sort");
if (dbrows($result) != 0) {
    for ($i = 0; $data = dbarray($result); $i++) {
        $Servers_GameQ[$i]['id'] = $data['id'];
        $Servers_GameQ[$i]['type'] = $data['game'];
        $Servers_GameQ[$i]['host'] = $data['address'] . ":" . $data['port'];
    }
}

require INFUSIONS . "gameserver_query_panel/GameQ/GameQ.php";

// Call the class, and add your servers.
$gq = new GameQ();
$gq->addServers($Servers_GameQ);

// You can optionally specify some settings
$gq->setOption('timeout', 1); // Seconds
// You can optionally specify some output filters,
// these will be applied to the results obtained.
$gq->setFilter('normalise');

// Send requests, and parse the data
$Results_GameQ = $gq->requestData();

function print_results($results) {
    foreach ($results as $id => $data) {
        print_table($data, $id);
    }
}

function print_table($data, $id) {
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
    echo "</div>";
}

openside("<span class='gqp-gamepad'></span> " . $locale['gqp_title']);
print_results($Results_GameQ);
closeside();
?>