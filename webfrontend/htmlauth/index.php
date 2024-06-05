#!/usr/bin/php
<?php
require_once "loxberry_system.php";
require_once "loxberry_log.php";
require_once "Config/Lite.php";
require_once "$lbphtmldir/neoom_api.class.php";
require_once "$lbphtmldir/functions.inc.php";

$miniserverIP = "192.168.178.201";
$log = Null;
$neoomCfg = Null;
$Neoom = Null;
$msArray = Null;
$msID = 0;

// Creates a log object, automatically assigned to your plugin, with the group name "NeoomLog"
$log = LBLog::newLog( [ "name" => "NeoomLog", "package" => $lbpplugindir, "logdir" => $lbplogdir, "loglevel" => 6] );
// After log object is created, logging is started with LOGSTART
// A start timestamp and other information is added automatically
LOGSTART("Neoom rcvstatus(Cronjob) started");


$neoomCfg = new Config_Lite("$lbpconfigdir/neoom.cfg",LOCK_EX,INI_SCANNER_RAW);

if ($neoomCfg == Null){
	LOGCRIT("Unable to read config file, terminating");
	LOGEND("Processing terminated");
	exit;
}
else {
	LOGOK("Reading config file successfull");
}

if ($neoomCfg->get("NEOOM","ENABLED")){
	LOGOK("Plugin is enabled");
} else{
	LOGOK("Plugin is disabled");
	LOGEND("Processing terminated");
	exit;
}

//$msArray = LBSystem::get_miniservers();
//$msID = $neoomCfg->get("NEOOM","MINISERVER");
//$miniserverIP = $msArray[$msID]['IPAddress'];

//Neues Neoom Objekt anlegen. Username und Passwort werden aus cfg Datei gelesen.
$session_neoom = new neoom_api();
#$session_neoom->login($neoomCfg->get("NEOOM","USERNAME"), $neoomCfg->get("NEOOM","PASSWORD"));
$session_neoom->login($neoomCfg->get("NEOOM","APIKEY"), $neoomCfg->get("NEOOM","BEAAMIP"));
$result = $session_neoom->get_api();

$dataToSend = json_encode($result);
#$session_neoom->logout();
LOGOK("Data API:".$dataToSend);
//Tansfer Data
sendUDP($dataToSend, $miniserverIP, $neoomCfg->get("NEOOM","UDPPORT"));

LOGOK("From the loxberry webview: Data sent to Miniserver:".$neoomCfg->get("NEOOM","UDPPORT"));

LOGEND("Processing terminated");
?>