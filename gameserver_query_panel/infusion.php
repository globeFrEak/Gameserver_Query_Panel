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
if (!defined("IN_FUSION")) {
    die("Access Denied");
}

include_once INFUSIONS . "gameserver_query_panel/infusion_db.php";

if (file_exists(INFUSIONS . "gameserver_query_panel/locale/" . $settings['locale'] . ".php")) {
    include INFUSIONS . "gameserver_query_panel/locale/" . $settings['locale'] . ".php";
} else {
    include INFUSIONS . "gameserver_query_panel/locale/English.php";
}

// Infusion general information
$inf_title = $locale['gqp_title'];
$inf_description = $locale['gqp_desc'];
$inf_version = $locale['gqp_version'];
$inf_developer = "globeFrEak";
$inf_email = "globefreak@web.de";
$inf_weburl = "http://www.cwclan.de";

$inf_folder = "gameserver_query_panel";

$inf_newtable[1] = DB_GQP_MAIN . "(
id INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
name VARCHAR(60) DEFAULT '' ,
address VARCHAR(60) DEFAULT '' ,
port MEDIUMINT(6) DEFAULT '27015' ,
game VARCHAR(30) DEFAULT '' ,
server_order SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL ,
active TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL ,
PRIMARY KEY (id)
) ENGINE=MyISAM;";

$inf_newtable[2] = DB_GQP_SERVER_OPT . "(
id INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
server_id INT(5) NULL ,
panel TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL ,
side TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL ,
icon VARCHAR(60) NULL ,
field VARCHAR(60) NULL ,
var_order SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL ,
PRIMARY KEY (id)
) ENGINE=MyISAM;";


$inf_newtable[3] = DB_GQP_SETTINGS . "(
id INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
panel_name VARCHAR(120) NULL ,
panel_template VARCHAR(60) NULL ,
PRIMARY KEY (id)
) ENGINE=MyISAM;";


$inf_insertdbrow[1] = DB_GQP_SETTINGS." (id, panel_name, panel_template) VALUES('', '".$locale['gqp_title']."', 'GQP_Custom_1.php')";

$inf_droptable[1] = DB_GQP_MAIN;
$inf_droptable[2] = DB_GQP_SERVER_OPT;
$inf_droptable[3] = DB_GQP_SETTINGS;

$inf_adminpanel[1] = array(
    "title" => $locale['gqp_admin'],
    "image" => "../infusions/gameserver_query_panel/icon.png",
    "panel" => "gameserver_query_admin.php",
    "rights" => "GQPG"
);
?>