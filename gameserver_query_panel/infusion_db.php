<?php
/*-------------------------------------------------------+
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
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

if (!defined("DB_GQP_MAIN")) {
	define("DB_GQP_MAIN", DB_PREFIX."gqp_main");
}
if (!defined("DB_GQP_SERVER_OPT")) {
	define("DB_GQP_SERVER_OPT", DB_PREFIX."gqp_server_options");
}
?>