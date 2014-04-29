<?php

/* -------------------------------------------------------+
  | PHP-Fusion Content Management System
  | Copyright � 2002 - 2008 Nick Jones
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
require_once THEMES . "templates/header.php";

include INFUSIONS . "gameserver_query_panel/infusion_db.php";

if (file_exists(INFUSIONS . "gameserver_query_panel/locale/" . $settings['locale'] . ".php")) {
    include INFUSIONS . "gameserver_query_panel/locale/" . $settings['locale'] . ".php";
} else {
    include INFUSIONS . "gameserver_query_panel/locale/English.php";
}

// Server abfragen!
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = mysql_real_escape_string($_GET['id']);
    $result = dbquery("SELECT id, address, port FROM " . DB_GQP_MAIN . "                             
                WHERE active ='1' AND id = '$id' ORDER BY sort");
} else {
    $result = dbquery("SELECT id, address, port FROM " . DB_GQP_MAIN . "                             
                WHERE active ='1' ORDER BY sort");
}
if ($rows === 1) {
    opentable($locale['sqp_title']);
    $data = dbarray($result);
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
            echo "<h5><a href='" . INFUSIONS . "source_query_panel/source_query_detail.php?id=" . $data['id'] . "'>" . $Info['HostName'] . "</a></h5>";
            //echo "<a href='http://store.steampowered.com/app/" . $Info['AppID'] . "/'>Link</a>";
            echo "<span><span class='icon-earth'></span> " . $Info['Map'] . "</span>";
            echo "<span style='float:right'><span class='icon-users'></span> " . $Info['Players'] . "/" . $Info['MaxPlayers'] . "</span>";
            echo "</div>";            
        }
    }
    closetable();
} elseif ($rows > 1) {
    opentable($locale['sqp_title']);
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
                echo "<h5><a href='" . INFUSIONS . "source_query_panel/source_query_detail.php?id=" . $data['id'] . "'>" . $Info['HostName'] . "</a></h5>";
                //echo "<a href='http://store.steampowered.com/app/" . $Info['AppID'] . "/'>Link</a>";
                echo "<span><span class='icon-earth'></span> " . $Info['Map'] . "</span>";
                echo "<span style='float:right'><span class='icon-users'></span> " . $Info['Players'] . "/" . $Info['MaxPlayers'] . "</span>";
                echo "</div>";
                echo ($i === $rows ? "" : "<hr />");
            }
        }
    }
    closetable();
} else {
    redirect(BASEDIR . "index.php");
}
require_once THEMES . "templates/footer.php";
?>