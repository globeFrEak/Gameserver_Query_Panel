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
if (!defined("IN_FUSION")) {
    die("Access Denied");
}

include INFUSIONS . "source_query_panel/infusion_db.php";

if (file_exists(INFUSIONS . "source_query_panel/locale/" . $settings['locale'] . ".php")) {
    include INFUSIONS . "source_query_panel/locale/" . $settings['locale'] . ".php";
} else {
    include INFUSIONS . "source_query_panel/locale/English.php";
}

// Infusion general information
$inf_title = $locale['sqp_title'];
$inf_description = $locale['sqp_desc'];
$inf_version = "1.0";
$inf_developer = "globeFrEak";
$inf_email = "globefreak@web.de";
$inf_weburl = "http://www.cwclan.de";

$inf_folder = "source_query_panel"; // The folder in which the infusion resides.
// Delete any items not required below.
$inf_newtable[1] = DB_SQP_MAIN . "(
id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
server_id smallint(4) DEFAULT '0' NOT NULL ,
name VARCHAR(60) DEFAULT '' ,
sort smallint(3) DEFAULT '0' NOT NULL ,
active smallint(1) UNSIGNED DEFAULT '1' NOT NULL ,
PRIMARY KEY (id)
) TYPE=MyISAM;";

// Tabellen Deinstallation
$inf_droptable[1] = DB_SQP_MAIN;

$inf_adminpanel[1] = array(
    "title" => $locale['sqp_admin'],
    "image" => "image.gif",
    "panel" => "source_query_admin.php",
    "rights" => "SQP"
);

/**
  $inf_sitelink[1] = array(
  "title" => $locale['gameq_link1'],
  "url" => "gameq_panel.php",
  "visibility" => "0"
  );
 * */
?>