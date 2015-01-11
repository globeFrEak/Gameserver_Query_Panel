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
if (!defined("IN_FUSION")) { die("Access Denied"); }
include_once INFUSIONS . "gameserver_query_panel/includes/functions.php";

$game = (isset($_POST['game']) ? mysql_real_escape_string($_POST['game']) : "");
echo GameQ_Games($game, 'portinfo');
?>