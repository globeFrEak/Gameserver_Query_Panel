<?php
error_reporting(E_ALL);
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright � 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: gameq_detail.php
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
require_once "../../maincore.php";
require_once THEMES."templates/header.php";

include INFUSIONS."hlstats_server_panel/infusion_db.php";

if (file_exists(INFUSIONS."hlstats_server_panel/locale/".$settings['locale'].".php")) {
	include INFUSIONS."hlstats_server_panel/locale/".$settings['locale'].".php";
} else {	
	include INFUSIONS."hlstats_server_panel/locale/English.php";
}

// Server abfragen!
if(isset($_GET['server']) && preg_match("=^[0-9]+$=i",$_GET['server'])){
$result = dbquery("SELECT a.id, a.server_id,
                            b.game, b.port, b.address, b.name, b.act_players, b.act_map, 
                            b.max_players, b.kills, b.players, b.suicides, b.headshots
                            FROM ".DB_HLS_TABLE." AS a,
                                cwclan_hlstats.hlstats_Servers AS b
                                WHERE a.active ='1'
                                AND id=".$_GET['server']."
                                    AND a.server_id = b.serverId
                                    ORDER BY sort");
} else {
$result = dbquery("SELECT a.id, a.server_id,
                            b.game, b.port, b.address, b.name, b.act_players, b.act_map,
                            b.max_players, b.kills, b.players, b.suicides, b.headshots
                            FROM ".DB_HLS_TABLE." AS a,
                                cwclan_hlstats.hlstats_Servers AS b
                                WHERE a.active ='1'
                                    AND a.server_id = b.serverId
                                    ORDER BY sort");
}

opentable($locale['hls_title']);
	echo "<table align='center' cellpadding='0' cellspacing='1'>\n";
	echo "<tr><td class='tbl1'><b><a href='".INFUSIONS."hlstats_server_panel/hlstats_server_detail.php'>Server &Uuml;bersicht</a></b></td>\n";
	echo "<td class='tbl1'><b><a href='".INFUSIONS."hlstats_server_panel/reserveslots.php'>Reserveslots</a></b></td></tr>\n";
	echo "</table>\n";
	echo "<br />\n";
	if (dbrows($result) != 0) {
            while ($data = dbarray($result)) {
                $ip = $data['address'].":".$data['port'];
                $player = $data['act_players']."/".$data['max_players'];
                $map = $data['act_map'];
                $game = $data['game'];
                $name = $data['name'];
                $id = $data['id'];
                $kills = $data['kills'];
                $players = $data['players'];
                $suicides = $data['suicides'];
                $headshots = $data['headshots'];

                if(@fopen("http://hlstats.cwclan.de/hlstatsimg/games/".$game."/maps/".$map.".jpg",r)) {
                    $bild = "<img width='140' style='padding:1px;' src='http://hlstats.cwclan.de/hlstatsimg/games/".$game."/maps/".$map.".jpg' alt='Mapimage_".$map."' />";
                } elseif(@fopen("http://hlstats.cwclan.de/hlstatsimg/games/".$game."/maps/".$map.".png",r)) {
                    $bild = "<img width='140' style='padding:1px;' src='http://hlstats.cwclan.de/hlstatsimg/games/".$game."/maps/".$map.".png' alt='Mapimage_".$map."' />";
                } else {
                    $bild = "<img width='140' style='padding:1px;' src='".BASEDIR."infusions/hlstats_server_panel/images/noimage.png' alt='No Map Image' />";
                }
                echo "<table width='100%' cellspacing='0' cellpadding='0'>\n";
                echo "<tr><td class='tbl2' colspan='2'><img height='16' width='16' src='http://hlstats.cwclan.de/hlstatsimg/games/".$game."/game.png' alt='".$game."' title='".$game."' /> <strong>".$name."</strong></td><td class='tbl2' rowspan='9' valign='top' width='142'>".$bild;
                // Abfrage Maprating Ende
                echo "<br /><b>Server Statistik</b><br />
                        Kills:<b>".$kills."</b><br /> Players:<b>".$players."</b><br /> Selbstmorde:<b>".$suicides."</b><br /> Kopfschüsse:<b>".$headshots."</b>";
                echo "</td></tr>\n";
                echo "<tr><td class='tbl1'>Adresse:</td><td class='tbl1' >".$ip." (<a href='steam://connect/".$ip."'>Join</a>)</td></tr>\n";
                echo "<tr><td class='tbl2'>Map:</td><td class='tbl2' >".$map."</td></tr>\n";
                echo "<tr><td class='tbl1' valign='top'>Player:</td><td class='tbl1' >".$player;
	// Abfrage ob mehr als 1 Player Online
    if ($data['act_players'] >=1){
    echo "<br />";
    echo "<table align='center'>\n";
    echo "<tr><td class='tbl2'><strong>Name</strong></td>\n
            <td class='tbl2'><strong>K:D</strong></td>\n
                    <td class='tbl2'><b>Ping</b></td>\n
                        <td class='tbl2'><b>Skill</b></td></tr>\n";
	// HLSTATS CE Player Abfrage Blue!
    	$resultplayer = dbquery('SELECT player_id, cli_flag, name, team, kills, deaths, connected, skill_change, ping
                                FROM cwclan_hlstats.hlstats_Livestats
                                    WHERE server_id = "'.$data['server_id'].'" AND team = "Blue" ORDER BY skill_change DESC ');
    	if (dbrows($resultplayer) != 0) {
            while ($data_player = dbarray($resultplayer)) {
		$id = $data_player['player_id'];
                // Team Farbe auslesen
                if ($data_player['team'] == 'Blue'){
                    $style = "style='background: #0e374e;'";
                    $css = "blu";
                } elseif($data_player['team'] == 'Red'){
                    $style = "style='background: #441704;'";
                    $css = "red";
                }

                // Flags auslesen
                if ($data_player['cli_flag']){
                    $flag = "http://hlstats.cwclan.de/hlstatsimg/flags/".strtolower($data_player['cli_flag']).".gif";
                } else {
                    $flag = "http://hlstats.cwclan.de/hlstatsimg/flags/0.gif";
                }
		$linkplayer = "<a href='http://hlstats.cwclan.de/hlstats.php?mode=playerinfo&player=".$id."' target='_blank'><img src='".$flag."' alt='".$data_player['cli_flag']."' title='".$data_player['cli_flag']."' />&nbsp;".htmlspecialchars(utf8_encode($data_player['name']))."</a>\n";
                
                // Skill auslesen
                if ($data_player['skill_change'] > 0){
			$skill = "<img src='http://hlstats.cwclan.de/hlstatsimg/t0.gif' title='".$data_player['skill_change']." Punkte' alt='".$data_player['skill_change']." Punkte'/>&nbsp;".$data_player['skill_change'];
		} elseif ($data_player['skill_change'] = 0){
			$skill = "<img src='http://hlstats.cwclan.de/hlstatsimg/t2.gif' title='".$data_player['skill_change']." Punkte' alt='".$data_player['skill_change']." Punkte'/>&nbsp;".$data_player['skill_change'];
		} else {
			$skill = "<img src='http://hlstats.cwclan.de/hlstatsimg/t1.gif' title='".$data_player['skill_change']." Punkte' alt='".$data_player['skill_change']." Punkte'/>&nbsp;".$data_player['skill_change'];
		}

                echo "<tr><td class='tbl2 ".$css."'>".$linkplayer."</td>\n
                        <td class='tbl2 ".$css."'><center>".$data_player['kills'].":".$data_player['deaths']."</center></td>\n
                                <td class='tbl2 ".$css."'><center>".$data_player['ping']."</center></td>\n
                                    <td class='tbl2 ".$css."'><center>".$skill."</center></td></tr>\n";
            }
        }

        // HLSTATS CE Player Abfrage Red!
    	$resultplayer = dbquery('SELECT player_id, cli_flag, name, team, kills, deaths, connected, skill_change, ping
                                FROM cwclan_hlstats.hlstats_Livestats
                                    WHERE server_id = "'.$data['server_id'].'" AND team = "Red" ORDER BY skill_change DESC ');
    	if (dbrows($resultplayer) != 0) {
            while ($data_player = dbarray($resultplayer)) {
		$id = $data_player['player_id'];
                // Team Farbe auslesen
                if ($data_player['team'] == 'Blue'){
                    $style = "style='background: #0e374e;'";
                    $css = "blu";
                } elseif($data_player['team'] == 'Red'){
                    $style = "style='background: #441704;'";
                    $css = "red"; }

                // Flags auslesen
                if ($data_player['cli_flag']){
                    $flag = "http://hlstats.cwclan.de/hlstatsimg/flags/".strtolower($data_player['cli_flag']).".gif";
                } else {
                    $flag = "http://hlstats.cwclan.de/hlstatsimg/flags/0.gif";
                }
		$linkplayer = "<a href='http://hlstats.cwclan.de/hlstats.php?mode=playerinfo&player=".$id."' target='_blank'><img src='".$flag."' alt='".$data_player['cli_flag']."' title='".$data_player['cli_flag']."' />&nbsp;".htmlspecialchars(utf8_encode($data_player['name']))."</a>\n";

                // Skill auslesen
                if ($data_player['skill_change'] > 0){
			$skill = "<img src='http://hlstats.cwclan.de/hlstatsimg/t0.gif' title='".$data_player['skill_change']." Punkte' alt='".$data_player['skill_change']." Punkte'/>&nbsp;".$data_player['skill_change'];
		} elseif ($data_player['skill_change'] = 0){
			$skill = "<img src='http://hlstats.cwclan.de/hlstatsimg/t2.gif' title='".$data_player['skill_change']." Punkte' alt='".$data_player['skill_change']." Punkte'/>&nbsp;".$data_player['skill_change'];
		} else {
			$skill = "<img src='http://hlstats.cwclan.de/hlstatsimg/t1.gif' title='".$data_player['skill_change']." Punkte' alt='".$data_player['skill_change']." Punkte'/>&nbsp;".$data_player['skill_change'];
		}

                echo "<tr><td class='tbl2 ".$css."'>".$linkplayer."</td>\n
                        <td class='tbl2 ".$css."'><center>".$data_player['kills'].":".$data_player['deaths']."</center></td>\n
                                <td class='tbl2 ".$css."'><center>".$data_player['ping']."</center></td>\n
                                    <td class='tbl2 ".$css."'><center>".$skill."</center></td></tr>\n";
            }
        }

        // HLSTATS CE Player Abfrage Spectator!
    	$resultplayer = dbquery('SELECT player_id, cli_flag, name, team, kills, deaths, connected, skill_change, ping
                                FROM cwclan_hlstats.hlstats_Livestats
                                    WHERE server_id = "'.$data['server_id'].'" AND team = "" ORDER BY skill_change DESC ');
    	if (dbrows($resultplayer) != 0) {
            while ($data_player = dbarray($resultplayer)) {
		$id = $data_player['player_id'];

                // Flags auslesen
                if ($data_player['cli_flag']){
                    $flag = "http://hlstats.cwclan.de/hlstatsimg/flags/".strtolower($data_player['cli_flag']).".gif";
                } else {
                    $flag = "http://hlstats.cwclan.de/hlstatsimg/flags/0.gif";
                }
		$linkplayer = "<a href='http://hlstats.cwclan.de/hlstats.php?mode=playerinfo&player=".$id."' target='_blank'><img src='".$flag."' alt='".$data_player['cli_flag']."' title='".$data_player['cli_flag']."' />&nbsp;".htmlspecialchars(utf8_encode($data_player['name']))."</a>\n";

                // Skill auslesen
                if ($data_player['skill_change'] > 0){
			$skill = "<img src='http://hlstats.cwclan.de/hlstatsimg/t0.gif' title='".$data_player['skill_change']." Punkte' alt='".$data_player['skill_change']." Punkte'/>&nbsp;".$data_player['skill_change'];
		} elseif ($data_player['skill_change'] = 0){
			$skill = "<img src='http://hlstats.cwclan.de/hlstatsimg/t2.gif' title='".$data_player['skill_change']." Punkte' alt='".$data_player['skill_change']." Punkte'/>&nbsp;".$data_player['skill_change'];
		} else {
			$skill = "<img src='http://hlstats.cwclan.de/hlstatsimg/t1.gif' title='".$data_player['skill_change']." Punkte' alt='".$data_player['skill_change']." Punkte'/>&nbsp;".$data_player['skill_change'];
		}

                echo "<tr><td class='tbl2'>".$linkplayer."</td>\n
                        <td class='tbl2'><center>".$data_player['kills'].":".$data_player['deaths']."</center></td>\n
                                <td class='tbl2'><center>".$data_player['ping']."</center></td>\n
                                    <td class='tbl2'><center>".$skill."</center></td></tr>\n";
            }
        }
    	echo "</table>\n";
	}
	// Abfrage Player Ende
	echo "</td></tr>\n";  
    // HLSTATS Server Verlauf
    echo "<tr><td class='tbl2'>Verlauf:</td>\n
            <td class='tbl2' >
                <center><img src='http://hlstats.cwclan.de/show_graph.php?type=0&width=360&height=180&game=tf&server_id=".$data['server_id']."&bgcolor=3b3b3b&color=ffffff&range=1' title='Server Verlauf' alt='Server Verlauf'/></center>\n
            </td></tr>\n";
    echo "</table>\n";
    echo "<br />";
    }
}
closetable();

require_once THEMES."templates/footer.php";
?>