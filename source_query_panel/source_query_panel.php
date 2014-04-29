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
if (isset($_GET['server']) && preg_match("=^[0-9]+$=i", $_GET['server'])) {
    $result = dbquery("SELECT id                            
                            FROM " . DB_QSP_MAIN . "                                
                                WHERE active ='1'
                                AND id=" . $_GET['server'] . "                                    
                                    ORDER BY sort");
} else {
    $result = dbquery("SELECT id
                            FROM " . DB_QSP_MAIN . "                                
                                WHERE active ='1'                                    
                                    ORDER BY sort");
}

require INFUSIONS . "source_query_panel/SourceQuery/SourceQuery.class.php";
	
	// Edit this ->
	define( 'SQ_SERVER_ADDR', 'localhost' );
	define( 'SQ_SERVER_PORT', 27015 );
	define( 'SQ_TIMEOUT',     1 );
	define( 'SQ_ENGINE',      SourceQuery :: SOURCE );
	// Edit this <-
	
	$Timer = MicroTime( true );
	
	$Query = new SourceQuery( );
	
	$Info    = Array( );
	$Rules   = Array( );
	$Players = Array( );
	
	try
	{
		$Query->Connect( SQ_SERVER_ADDR, SQ_SERVER_PORT, SQ_TIMEOUT, SQ_ENGINE );
		
		$Info    = $Query->GetInfo( );
		$Players = $Query->GetPlayers( );
		$Rules   = $Query->GetRules( );
	}
	catch( Exception $e )
	{
		$Exception = $e;
	}
	
	$Query->Disconnect( );
	
	$Timer = Number_Format( MicroTime( true ) - $Timer, 4, '.', '' );


opentable("<img src='" . INFUSIONS . "hlstats_server_panel/images/hlstats-icon.png' style='vertical-align: middle;'/> " . $locale['hls_100'], TRUE, "on");


closetable();
?>