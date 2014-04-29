<?php

/* -------------------------------------------------------+
  | PHP-Fusion Content Management System
  | Copyright 2002 - 2008 Nick Jones
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

require INFUSIONS . "source_query_panel/SourceQuery/SourceQuery.class.php";
define('SQ_TIMEOUT', 1);
define('SQ_ENGINE', SourceQuery :: SOURCE);

$result = dbquery("SELECT address, port FROM " . DB_SQP_MAIN . "                             
                WHERE active ='1' ORDER BY sort");
$rows = dbrows($result);
$i = 0;

if ($rows != 0) {
    openside("<span class='icon-pacman'></span> ".$locale['sqp_title']);
    while ($data = dbarray($result)) {
        $i++;
        $Timer = MicroTime(true);
        $Query = new SourceQuery( );
        $Info = Array();
        $Rules = Array();
        $Players = Array();

        try {
            $Query->Connect($data['address'], $data['port'], SQ_TIMEOUT, SQ_ENGINE);

            $Info = $Query->GetInfo();
            $Players = $Query->GetPlayers();
            $Rules = $Query->GetRules();
        } catch (Exception $e) {
            $Exception = $e;
        }

        $Query->Disconnect();

        $Timer = Number_Format(MicroTime(true) - $Timer, 4, '.', '');

        if (isset($Exception)) {
            echo Get_Class($Exception);
            echo $Exception->getLine();
            echo htmlspecialchars($Exception->getMessage());
            echo nl2br($e->getTraceAsString(), false);
        } else {
            if (Is_Array($Info)) {
                //DEBUG
                //echo print_r($Info);
                echo "<div>";
                echo "<h5>" . $Info['HostName'] . "</h5>";
                //echo "<a href='http://store.steampowered.com/app/" . $Info['AppID'] . "/'>Link</a>";
                echo "<span><span class='icon-earth'></span> " . $Info['Map'] . "</span>";
                echo "<span style='float:right'><span class='icon-users'></span> " . $Info['Players'] . "/" . $Info['MaxPlayers'] . "</span>";
                echo "</div>";
                echo ($i === $rows ? "" : "<hr />");
            }
        }        
    }
    closeside();
}
?>