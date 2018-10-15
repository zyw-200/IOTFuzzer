<?
/* setcfg is used to move the validated session data to the configuration database.
 * The variable, 'SETCFG_prefix',  will indicate the path of the session data. */
set("/device/multicast/igmpproxy",		query($SETCFG_prefix."/device/multicast/igmpproxy"));
set("/device/multicast/wifienhance",	query($SETCFG_prefix."/device/multicast/wifienhance"));
?>
