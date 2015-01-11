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
require_once "../../../maincore.php";
include_once INFUSIONS . "gameserver_query_panel/infusion_db.php";

if (!checkrights("GQPG") || !defined("iAUTH") || !isset($_GET['aid']) || $_GET['aid'] != iAUTH) {
    redirect("../index.php");
}

if (file_exists(INFUSIONS . "gameserver_query_panel/locale/" . $settings['locale'] . ".php")) {
    include INFUSIONS . "gameserver_query_panel/locale/" . $settings['locale'] . ".php";
} else {
    include INFUSIONS . "gameserver_query_panel/locale/English.php";
}

if (isset($_GET['listItem']) && is_array($_GET['listItem'])) {    
    foreach ($_GET['listItem'] as $position => $item) {
        if (isnum($position) && isnum($item)) {
            dbquery("UPDATE " . DB_GQP_MAIN . " SET server_order='" . ($position + 1) . "' WHERE id='" . $item . "'");
        }
    }
    header("Content-Type: text/html; charset=" . $locale['charset'] . "\n");
    echo "<div id='close-message'><div class='admin-message'>" . $locale['gqp_order_update'] . "</div></div>";
}
?>

