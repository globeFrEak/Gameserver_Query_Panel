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
require_once "../../../maincore.php";
if (!defined("IN_FUSION")) {
    die("Access Denied");
}

include_once INFUSIONS . "gameserver_query_panel/infusion_db.php";
include_once INFUSIONS . "gameserver_query_panel/includes/functions.php";

$result = dbquery("SELECT panel_template FROM " . DB_GQP_SETTINGS . "");
while ($data = dbarray($result)) {
    $template = $data['panel_template'];
}
require_once GQPBASE . "templates/" . $template;

$servers = gameQ_Create(gameQ_Servers());
if ($servers != FALSE) {
    foreach ($servers as $id => $data) {
        if (!$data['gq_online']) {
            echo "<div>";
            echo "<h5><span class='gqpfa-frown-o'></span> " . $data['gq_address'] . ":" . $data['gq_port'] . $locale['gqp_server_noresponse'] . "</h5>";
            echo "</div>\n";
        } else {
            panelOut($data, $id, $locale);
        }
    }
}
?>