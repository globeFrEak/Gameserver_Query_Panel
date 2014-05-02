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

include INFUSIONS . "gameserver_query_panel/infusion_db.php";

if (file_exists(INFUSIONS . "gameserver_query_panel/locale/" . $settings['locale'] . ".php")) {
    include INFUSIONS . "gameserver_query_panel/locale/" . $settings['locale'] . ".php";
} else {
    include INFUSIONS . "gameserver_query_panel/locale/English.php";
}

// Infusion general information
$inf_title = $locale['gqp_title'];
$inf_description = $locale['gqp_desc'];
$inf_version = "1.0";
$inf_developer = "globeFrEak";
$inf_email = "globefreak@web.de";
$inf_weburl = "http://www.cwclan.de";

$inf_folder = "gameserver_query_panel"; 

$inf_newtable[1] = DB_GQP_MAIN . "(
id INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
name VARCHAR(60) DEFAULT '' ,
address VARCHAR(60) DEFAULT '' ,
port MEDIUMINT(6) DEFAULT '27015' ,
game VARCHAR(10) DEFAULT '' ,
sort SMALLINT(3) DEFAULT '0' NOT NULL ,
active TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL ,
PRIMARY KEY (id)
) ENGINE=MyISAM;";

$inf_droptable[1] = DB_GQP_MAIN;

$inf_adminpanel[1] = array(
    "title" => $locale['gqp_admin'],
    "image" => "image.gif",
    "panel" => "gameserver_query_admin.php",
    "rights" => "GQP"
);

/**
  $inf_sitelink[1] = array(
  "title" => $locale['gameq_link1'],
  "url" => "gameq_panel.php",
  "visibility" => "0"
  );
 * */
?>