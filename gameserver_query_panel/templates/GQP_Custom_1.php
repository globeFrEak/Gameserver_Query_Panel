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

function sortByChannel($a, $b) {
    return $a['channel'] - $b['channel'];
}

function outputOS($os, $osversion, $locale) {    
    switch ($os) {
        case "Win":
            echo "<span class='gqpfa-windows' title='" . $locale['gqp_temp_005'] . $osversion . "'></span>";
            break;
        case "WinX64":
            echo "<span class='gqpfa-windows' title='" . $osversion . "'></span>";
            break;
        case "Osx":
            echo "<span class='gqpfa-apple' title='" . $locale['gqp_temp_006'] . $osversion . "'></span>";
            break;
        case "X11":
            echo "<span class='gqpfa-linux' title='" . $locale['gqp_temp_007'] . $players[$pvalue]['osversion'] . "'></span>";
            break;
        case "Android":
            echo "<span class='gqpfa-android' title='" . $locale['gqp_temp_008'] . $osversion . "'></span>";
            break;
        default:
            return false;
            break;
    }
}

//panel
function PanelOut($data, $id, $locale) {
    if ($data['gq_type'] != 'mumble') {
        $join = ($data['gq_joinlink'] ? " <a href='" . $data['gq_joinlink'] . "' alt='" . $locale['gqp_temp_001'] . $data['gq_hostname'] . "' title='" . $locale['gqp_temp_001'] . $data['gq_hostname'] . "'><span class='gqpfa-sign-in'></span></a>" : "");
        $password = ($data['gq_password'] == 1 ? "<span class='gqpfa-lock'></span> " : "");
        echo "<div class='gqp-server'>";
        echo "<h5>$password<a href='" . GQPBASE . "gameserver_query_detail.php?id=$id'>" . $data['gq_hostname'] . "</a>$join</h5>";
        echo "<img src='" . GQPIMG . "games/" . $data['gq_type'] . ".jpg' alt='" . GameQ_GetInfo($data['gq_type'], 'N') . "' title='" . GameQ_GetInfo($data['gq_type'], 'N') . "' height='16' width='16'/> ";
        echo "<span><span class='gqpfa-globe'></span> " . $data['gq_mapname'] . "</span>";
        echo "<span style='float:right'>" . $data['gq_numplayers'] . "/" . $data['gq_maxplayers'] . " <span class='gqpfa-group'></span></span>";
        /*
         *
          if (iSUPERADMIN) {
          echo "<pre>";
          echo print_r($data);
          echo "</pre>";
          }
         * 
         */
        echo "</div>";
        echo "<div class='gqp-clear'></div>";
    } else {
        $join = ($data['gq_joinlink'] ? " <a href='" . $data['gq_joinlink'] . "' alt='" . $locale['gqp_temp_001'] . $data['gq_hostname'] . "' title='" . $locale['gqp_temp_001'] . $data['gq_hostname'] . "'><span class='gqpfa-sign-in'></span></a>" : "");
        $password = ($data['gq_password'] == 1 ? "<span class='gqpfa-lock'></span> " : "");
        $numplayers = (empty($data['gq_numplayers']) ? 0 : $data['gq_numplayers']);
        echo "<div class='gqp-server'>";
        echo "<h5><img src='" . GQPIMG . "games/" . $data['gq_type'] . ".jpg' alt='" . GameQ_GetInfo($data['gq_type'], 'N') . "' title='" . GameQ_GetInfo($data['gq_type'], 'N') . "' height='16' width='16'/> $password<a href='" . GQPBASE . "gameserver_query_detail.php?id=$id'>" . $data['gq_hostname'] . "</a>$join</h5>";
        echo "<span style='float:right'>" . $numplayers . "/" . $data['gq_maxplayers'] . " <span class='gqpfa-group'></span></span>";

        if ($numplayers > 0) {
            $players = $data['players'];
            $channels = $data['teams'];

            usort($players, 'sortByChannel');

            // combine arrays (players with channels)
            $player_channel = array();
            foreach ($channels AS $ckey => $cvalue) {
                foreach ($players AS $pkey => $pvalue) {
                    if ($cvalue['id'] == $pvalue['channel']) {
                        $player_channel[$ckey][] = $pkey;
                    }
                }
            }
            // output channel and players
            foreach ($player_channel AS $key => $value) {
                echo "<h6><img src='" . GQPIMG . "mumble/list_channel.png' alt='channel'/>" . $channels[$key]['gq_name'] . " [" . count($value) . "]</h6>";
                echo "<ul class='gqpmumblelist'>";
                foreach ($value AS $pkey => $pvalue) {
                    echo "<li>";
                    echo $players[$pvalue]['gq_name'];
                    echo ($players[$pvalue]['suppress'] == 1 ? "<img src='" . GQPIMG . "mumble/player_suppressed.png' alt='suppressed'/>" : "");
                    echo ($players[$pvalue]['selfMute'] == 1 ? "<img src='" . GQPIMG . "mumble/player_selfmute.png' alt='selfmute'/>" : "");
                    echo ($players[$pvalue]['selfDeaf'] == 1 ? "<img src='" . GQPIMG . "mumble/player_selfdeaf.png' alt='selfdeaf'/>" : "");
                    echo ($players[$pvalue]['userid'] != -1 ? "<img src='" . GQPIMG . "mumble/player_auth.png' alt='suppressed'/>" : "");
                    if (iMEMBER) {
                        echo "<span class='gqpfa-clock-o' title='" . $locale['gqp_temp_002'] . date("H:i:s", $players[$pvalue]['onlinesecs'] + strtotime("1970/1/1")) . $locale['gqp_temp_003'] . date("H:i:s", $players[$pvalue]['idlesecs'] + strtotime("1970/1/1")) . "'></span>";
                        echo "<img src='" . GQPIMG . "games/" . $data['gq_type'] . ".jpg' title='" . $locale['gqp_temp_004'] . $players[$pvalue]['release'] . "' height='16' width='16'/>";
                        outputOS ($players[$pvalue]['os'], $players[$pvalue]['osversion'], $locale);
                    }
                    echo "</li>";
                }
                echo "</ul>";
            }
        }
        echo "</div>";
        echo "<div class='gqp-clear'></div>";
    }
}

//detail
function DetailOut($data, $locale) {
    if ($data['gq_type'] != 'mumble') {
        $join = ($data['gq_joinlink'] ? " <a href='" . $data['gq_joinlink'] . "' alt='" . $locale['gqp_temp_001'] . $data['gq_hostname'] . "' title='" . $locale['gqp_temp_001'] . $data['gq_hostname'] . "'><span class='gqpfa-sign-in'></span></a>" : "");
        $password = ($data['gq_password'] == 1 ? "<span class='gqpfa-lock'></span> " : "");
        echo "<div class='gqp-server'>";
        echo "<h5>$password" . $data['gq_hostname'] . "$join</h5>";
        echo "<div><img src='" . GQPIMG . "games/" . $data['gq_type'] . ".jpg' alt='" . GameQ_GetInfo($data['gq_type'], 'N') . "' title='" . GameQ_GetInfo($data['gq_type'], 'N') . "' height='16' width='16'/> ";
        echo "<span><span class='gqpfa-globe'></span> " . $data['gq_mapname'] . "</span>";
        echo "<span style='float:right'>" . $data['gq_numplayers'] . "/" . $data['gq_maxplayers'] . " <span class='gqpfa-group'></span></span></div>";
        echo "<div><h5>IP: " . $data['gq_address'] . ":" . $data['gq_port'] . "</h5>";
        if ($data['gq_numplayers'] > 0) {
            echo "<ul class='gqpgamelist'>";
            for ($count = 0; $count < $data['gq_numplayers']; $count++) {
                echo "<li>";
                echo "<span class='gqpfa-user'></span>";
                echo ($data['gq_protocol'] == 'source' ? "<a href='http://steamcommunity.com/search/?text=" . $data['players'][$count]['gq_name'] . "' title='" . $locale['gqp_temp_020'] . "'>" . $data['players'][$count]['gq_name'] . "</a>" : $data['players'][$count]['gq_name']);
                echo "<span class='gqpfa-clock-o' title='" . $locale['gqp_temp_002'] . date("H:i:s", $data['players'][$count]['time'] + strtotime("1970/1/1")) . "'></span>";
                echo "</li>";
            }
            echo "</ul>";
        }
        echo "</div>";

        if (iSUPERADMIN) {
            echo "<pre>";
            echo print_r($data);
            echo "</pre>";
        }

        echo "</div>";
        echo "<div class='gqp-clear'></div>";
    } else {
        $join = ($data['gq_joinlink'] ? " <a href='" . $data['gq_joinlink'] . "' alt='" . $locale['gqp_temp_001'] . $data['gq_hostname'] . "' title='" . $locale['gqp_temp_001'] . $data['gq_hostname'] . "'><span class='gqpfa-sign-in'></span></a>" : "");
        $password = ($data['gq_password'] == 1 ? "<span class='gqpfa-lock'></span> " : "");
        $numplayers = (empty($data['gq_numplayers']) ? 0 : $data['gq_numplayers']);
        echo "<div class='gqp-server'>";
        echo "<h5><img src='" . GQPIMG . "games/" . $data['gq_type'] . ".jpg' alt='" . GameQ_GetInfo($data['gq_type'], 'N') . "' title='" . GameQ_GetInfo($data['gq_type'], 'N') . "' height='16' width='16'/> $password" . $data['gq_hostname'] . "$join</h5>";
        echo "<div>";
        echo "<span style='float:right'>" . $numplayers . "/" . $data['gq_maxplayers'] . " <span class='gqpfa-group'></span></span></div>";
        echo "<div>";

        if ($numplayers > 0) {
            $players = $data['players'];
            $channels = $data['teams'];

            usort($players, 'sortByChannel');

            // combine arrays (players with channels)
            $player_channel = array();
            foreach ($channels AS $ckey => $cvalue) {
                foreach ($players AS $pkey => $pvalue) {
                    if ($cvalue['id'] == $pvalue['channel']) {
                        $player_channel[$ckey][] = $pkey;
                    }
                }
            }
            // output channel and players
            foreach ($player_channel AS $key => $value) {
                echo "<h5><img src='" . GQPIMG . "mumble/list_channel.png' alt='channel'/>" . $channels[$key]['gq_name'] . " [" . count($value) . "]</h5>";
                echo "<ul class='gqpmumblelist'>";
                foreach ($value AS $pkey => $pvalue) {
                    echo "<li>";
                    echo $players[$pvalue]['gq_name'];
                    echo ($players[$pvalue]['suppress'] == 1 ? "<img src='" . GQPIMG . "mumble/player_suppressed.png' alt='suppressed'/>" : "");
                    echo ($players[$pvalue]['selfMute'] == 1 ? "<img src='" . GQPIMG . "mumble/player_selfmute.png' alt='selfmute'/>" : "");
                    echo ($players[$pvalue]['selfDeaf'] == 1 ? "<img src='" . GQPIMG . "mumble/player_selfdeaf.png' alt='selfdeaf'/>" : "");
                    echo ($players[$pvalue]['userid'] != -1 ? "<img src='" . GQPIMG . "mumble/player_auth.png' alt='suppressed'/>" : "");
                    if (iMEMBER) {
                        echo "<span class='gqpfa-clock-o' title='" . $locale['gqp_temp_002'] . date("H:i:s", $players[$pvalue]['onlinesecs'] + strtotime("1970/1/1")) . $locale['gqp_temp_003'] . date("H:i:s", $players[$pvalue]['idlesecs'] + strtotime("1970/1/1")) . "'></span>";
                        echo "<img src='" . GQPIMG . "games/" . $data['gq_type'] . ".jpg' title='" . $locale['gqp_temp_004'] . $players[$pvalue]['release'] . "' height='16' width='16'/>";
                        outputOS ($players[$pvalue]['os'], $players[$pvalue]['osversion'], $locale);
                    }
                    echo "</li>";
                }
                echo "</ul>";
            }
        }

        echo "</div>";
        if (iSUPERADMIN) {
            echo "<pre>";
            //echo print_r($player_channel);
            //echo print_r($players);
            echo print_r($data);
            echo "</pre>";
        }
        echo "</div>";
        echo "<div class='gqp-clear'></div>";
    }
}

?>