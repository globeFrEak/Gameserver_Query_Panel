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

function GameQ_Games($select) {
    require_once INFUSIONS . "gameserver_query_panel/GameQ/GameQ.php";
    $protocols_path = GAMEQ_BASE . "gameq/protocols/";
    $dir = dir($protocols_path);
    $protocols = array();
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
            'port' => $class->port(),
        );
        unset($class);
    }
    unset($dir);
    ksort($protocols);
    foreach ($protocols AS $gameq => $info) {
        $return .= "<option value='$gameq' " . ($select == $gameq ? "selected='selected'" : "") . ">" . htmlentities($info['name']) . "</option>";
    }
    return $return;
}

function GameQ_GetInfo($game, $return = 'N') {
    require_once INFUSIONS . "gameserver_query_panel/GameQ/GameQ.php";
    $protocols = array();
    $class_name = 'GameQ_Protocols_' . ucfirst(pathinfo($game, PATHINFO_FILENAME));
    $reflection = new ReflectionClass($class_name);
    if (!$reflection->IsInstantiable()) {
        return $game;
    }
    $class = new $class_name;
    switch ($return) {
        case N:
            return htmlentities($class->name_long());
            break;

        case P:
            return $class->port();
            break;
    }
    unset($class);
}
?>
