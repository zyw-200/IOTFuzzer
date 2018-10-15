<?
include "/etc/services/WIFI/wifi.php";

fwrite("w",$START, "#!/bin/sh\n");
fwrite("w", $STOP, "#!/bin/sh\n");

//workaround for but 5G disabled, 2.4G enabled 
//enable3092clock("WLAN-2");	//wlan-2 is ra0, A band

function get_sched_command($uid)
{
	/* Get schedule setting */
	$ifpath = XNODE_getpathbytarget("", "phyinf", "uid", $uid, 0);
	$sch = XNODE_getschedule($ifpath);
	if ($sch=="") $cmd = "start";
	else
	{
		$days = XNODE_getscheduledays($sch);
		$start = query($sch."/start");
		$end = query($sch."/end");
		if (query($sch."/exclude")=="1") $cmd = 'schedule!';
		else $cmd = 'schedule';
		$cmd = $cmd.' "'.$days.'" "'.$start.'" "'.$end.'"';
	}
	return $cmd;
}	

$scmd = get_sched_command("WLAN-1");
$scmd2 = get_sched_command("WLAN-2");

fwrite("a", $_GLOBALS["START"], 'service PHYINF.WLAN-1 '.$scmd.'\n');
fwrite("a", $_GLOBALS["START"], 'service PHYINF.WLAN-2 '.$scmd2.'\n');

//fwrite("a", $_GLOBALS["START"], "service PHYINF.WLAN-1 start\n");
//fwrite("a", $_GLOBALS["START"], "service PHYINF.WLAN-2 start\n");

fwrite("a", $_GLOBALS["STOP"], "service PHYINF.WLAN-1 stop\n");
fwrite("a", $_GLOBALS["STOP"], "service PHYINF.WLAN-2 stop\n");

fwrite("a",$_GLOBALS["START"], "exit 0\n");
fwrite("a",$_GLOBALS["STOP"],  "exit 0\n");
?>
