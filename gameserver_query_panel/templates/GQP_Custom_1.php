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

//panel
function PanelOut($data, $id) {
    if ($data['gq_type'] != 'mumble') {
        $join = ($data['gq_joinlink'] ? " <a href='" . $data['gq_joinlink'] . "' alt='Verbinden mit " . $data['gq_hostname'] . "' title='Verbinden mit " . $data['gq_hostname'] . "'><span class='gqp-sign-in'></span></a>" : "");
        $password = ($data['gq_password'] == 1 ? "<span class='gqp-lock'></span> " : "");
        echo "<div class='gqpp-server'>";
        echo "<h5>$password<a href='" . GQPBASE . "gameserver_query_detail.php?id=$id'>" . $data['gq_hostname'] . "</a>$join</h5>";
        echo "<img src='" . GQPIMG . "games/" . $data['gq_type'] . ".jpg' alt='" . GameQ_GetInfo($data['gq_type'], 'N') . "' title='" . GameQ_GetInfo($data['gq_type'], 'N') . "' height='16' width='16'/> ";
        echo "<span><span class='gqp-globe'></span> " . $data['gq_mapname'] . "</span>";
        echo "<span style='float:right'>" . $data['gq_numplayers'] . "/" . $data['gq_maxplayers'] . " <span class='gqp-group'></span></span>";
        echo "</div>";
        echo "<div class='gqpp-clear'></div>";
    } else {
        $join = ($data['gq_joinlink'] ? " <a href='" . $data['gq_joinlink'] . "' alt='Verbinden mit " . $data['gq_hostname'] . "' title='Verbinden mit " . $data['gq_hostname'] . "'><span class='gqp-sign-in'></span></a>" : "");
        $password = ($data['gq_password'] == 1 ? "<span class='gqp-lock'></span> " : "");
        $numplayers = (empty($data['gq_numplayers'])? 0 : $data['gq_numplayers']);
        echo "<div class='gqpp-server'>";
        echo "<h5><img src='" . GQPIMG . "games/" . $data['gq_type'] . ".jpg' alt='" . GameQ_GetInfo($data['gq_type'], 'N') . "' title='" . GameQ_GetInfo($data['gq_type'], 'N') . "' height='16' width='16'/> $password<a href='" . GQPBASE . "gameserver_query_detail.php?id=$id'>" . $data['gq_hostname'] . "</a>$join</h5>";
        echo "<span style='float:right'>" . $numplayers . "/" . $data['gq_maxplayers'] . " <span class='gqp-group'></span></span>";

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
                    echo "</li>";
                }
                echo "</ul>";
            }
        }
        echo "</div>";
        echo "<div class='gqpp-clear'></div>";
    }
}

//detail
function DetailOut($data) {
    if ($data['gq_type'] != 'mumble') {
        $join = ($data['gq_joinlink'] ? " <a href='" . $data['gq_joinlink'] . "' alt='Verbinden mit " . $data['gq_hostname'] . "' title='Verbinden mit " . $data['gq_hostname'] . "'><span class='gqp-sign-in'></span></a>" : "");
        $password = ($data['gq_password'] == 1 ? "<span class='gqp-lock'></span> " : "");
        echo "<div class='gqpp-server'>";
        echo "<h5>$password" . $data['gq_hostname'] . "$join</h5>";
        echo "<div><img src='" . GQPIMG . "games/" . $data['gq_type'] . ".jpg' alt='" . GameQ_GetInfo($data['gq_type'], 'N') . "' title='" . GameQ_GetInfo($data['gq_type'], 'N') . "' height='16' width='16'/> ";
        echo "<span><span class='gqp-globe'></span> " . $data['gq_mapname'] . "</span>";
        echo "<span style='float:right'>" . $data['gq_numplayers'] . "/" . $data['gq_maxplayers'] . " <span class='gqp-group'></span></span></div>";
        echo "<div><h5>IP: " . $data['gq_address'] . ":" . $data['gq_port'] . "</h5>";
        if ($data['gq_numplayers'] > 0) {
            echo "<ul class='gqp-ul'>";
            for ($count = 0; $count < $data['gq_numplayers']; $count++) {
                $playtime = date("H:i:s", $data['players'][$count]['time'] + strtotime("1970/1/1"));
                echo "<li><span class='gqp-user'></span>" . $data['players'][$count]['gq_name'] . " [Spielzeit: $playtime]</li>";
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
        echo "<div class='gqpp-clear'></div>";
    } else {
        $join = ($data['gq_joinlink'] ? " <a href='" . $data['gq_joinlink'] . "' alt='Verbinden mit " . $data['gq_hostname'] . "' title='Verbinden mit " . $data['gq_hostname'] . "'><span class='gqp-sign-in'></span></a>" : "");
        $password = ($data['gq_password'] == 1 ? "<span class='gqp-lock'></span> " : "");
        $numplayers = (empty($data['gq_numplayers'])? 0 : $data['gq_numplayers']);
        echo "<div class='gqpp-server'>";
        echo "<h5><img src='" . GQPIMG . "games/" . $data['gq_type'] . ".jpg' alt='" . GameQ_GetInfo($data['gq_type'], 'N') . "' title='" . GameQ_GetInfo($data['gq_type'], 'N') . "' height='16' width='16'/> $password" . $data['gq_hostname'] . "$join</h5>";
        echo "<div>";
        echo "<span style='float:right'>" . $numplayers . "/" . $data['gq_maxplayers'] . " <span class='gqp-group'></span></span></div>";
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
        echo "<div class='gqpp-clear'></div>";
    }
}

?>