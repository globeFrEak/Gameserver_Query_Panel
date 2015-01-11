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
require_once INFUSIONS . "gameserver_query_panel/GameQ/GameQ.php";

define("GQPBASE", BASEDIR . "infusions/gameserver_query_panel/");
define("GQPIMG", GQPBASE . "images/");

if (file_exists(GQPBASE . "locale/" . $settings['locale'] . ".php")) {
    include_once GQPBASE . "locale/" . $settings['locale'] . ".php";
} else {
    include_once GQPBASE . "locale/English.php";
}

function GameQ_Create($servers) {
    if ($servers != FALSE) {
        $gq = new GameQ();
        $gq->addServers($servers);
        $gq->setOption('timeout', 10);
        $gq->setOption('write_wait', 500);
        $gq->setFilter('normalise');
        return $gq->requestData();
    } else {
        return FALSE;
    }
}

function GameQ_Servers($id = FALSE) {
    if (isset($id) && is_numeric($id) && $id != 0) {
        $id = mysql_real_escape_string($id);
        $result_detail = dbquery("SELECT id, address, port, game FROM " . DB_GQP_MAIN . "                             
                WHERE active ='1' AND id = '$id' ORDER BY server_order");
    } else {
        $result_detail = dbquery("SELECT id, address, port, game FROM " . DB_GQP_MAIN . "                             
                WHERE active ='1' ORDER BY server_order");
    }
    $rows = dbrows($result_detail);
    if ($rows != 0) {
        $Servers_GameQ = array();
        for ($i = 0; $data = dbarray($result_detail); $i++) {
            $Servers_GameQ[$i]['id'] = $data['id'];
            $Servers_GameQ[$i]['type'] = $data['game'];
            $Servers_GameQ[$i]['host'] = $data['address'] . ":" . $data['port'];
        }
        return $Servers_GameQ;
    } else {
        return FALSE;
    }
}

function GameQ_Games($select = FALSE, $option = 'list', $aidlink = "") {
    require_once GQPBASE . "GameQ/GameQ.php";
    $protocols_path = GAMEQ_BASE . "gameq/protocols/";
    $dir = dir($protocols_path);
    $protocols = array();
    $print = "";
    while (false !== ($entry = $dir->read())) {
        if (!is_file($protocols_path . $entry)) {
            continue;
        }
        $class_name = 'GameQ_Protocols_' . ucfirst(pathinfo($entry, PATHINFO_FILENAME));
        $reflection = new ReflectionClass($class_name);
        if (!$reflection->IsInstantiable()) {
            continue;
        }
        $class = new $class_name;
        $protocols[$class->name()] = array(
            'name' => $class->name_long(),
            'port' => $class->port()
        );
        unset($class);
    }
    unset($dir);
    ksort($protocols);
    switch ($option) {
        case 'dropdown':
            foreach ($protocols AS $gameq => $info) {
                $print .= "<option value='$gameq' " . ($select == $gameq ? "selected='selected'" : "") . ">" . htmlentities($info['name']) . "</option>";
            }
            break;

        case 'list':
            foreach ($protocols AS $gameq => $info) {
                $print .= "<div class='gqplist tbl2'>"
                        . "<img src='" . INFUSIONS . "gameserver_query_panel/images/games/" . $gameq . ".jpg' alt='" . htmlentities($info['name']) . "' title='" . htmlentities($info['name']) . "' height='32' width='32'/>"
                        . "<span>" . htmlentities($info['name']) . "</span>"
                        . "<a class='gqp_a' href='" . GQPBASE . "gqp_handle_server.php" . $aidlink . "&game=" . $gameq . "'><span class='gqpfa-plus'></span></a>"
                        . "</div>";
            }
            break;

        case 'portinfo':
            foreach ($protocols AS $gameq => $info) {
                if ($gameq == $select) {
                    $print .= $info['port'];
                }
            }
            break;

        case 'count':
            $print = count($protocols);
            break;
    }
    return $print;
}

function GameQ_GetInfo($game, $return = 'N') {
    require_once GQPBASE . "GameQ/GameQ.php";
    $protocols = array();
    $class_name = 'GameQ_Protocols_' . ucfirst(pathinfo($game, PATHINFO_FILENAME));
    $reflection = new ReflectionClass($class_name);
    if (!$reflection->IsInstantiable()) {
        return $game;
    }
    $class = new $class_name;
    switch ($return) {
        case 'N':
            return htmlentities($class->name_long());
            break;

        case 'P':
            return $class->port();
            break;
    }
    unset($class);
}

function GameQ_ScanDir() {
    $result = array();
    $dir = scandir(GQPBASE . "templates");
    foreach ($dir as $key => $value) {
        $ext = substr($value, strrpos($value, '.') + 1);
        if (in_array($ext, array("php"))) {
            $result[] = $value;
        }
    }
    return $result;
}

?>