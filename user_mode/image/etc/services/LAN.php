<?
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/xnode.php";
include "/etc/services/INET/interface.php";

fwrite("w",$START, "#!/bin/sh\n");
fwrite("a", $STOP, "#!/bin/sh\n");
/* start IPTABLES first */
fwrite("a",$START, "service IPTABLES start\n");
/* start IP6TABLES first */
if (isfile("/proc/net/if_inet6")==1)
	fwrite("a",$START, "service IP6TABLES start\n");

if (query("/runtime/device/layout")=="router")
{
	/* setup ipaddress for  all LAN interfaces. */
	ifinetsetupall("LAN");

	fwrite("a",$START, "service IPTMACCTRL start\n");
	fwrite("a",$START, "service IPTURLCTRL start\n");
	fwrite("a",$START, "service IPTPORTT start\n");
	
	/* start IPT.ifname services for all LAN interfaces. */
	srviptsetupall("LAN");

	fwrite("a",$STOP, "service IPTPORTT stop\n");
	fwrite("a",$STOP, "service IPTURLCTRL stop\n");
	fwrite("a",$STOP, "service IPTMACCTRL stop\n");
}
else
{
	SHELL_info($START, "LAN: The device is not in the router mode.");
	SHELL_info($STOP,  "LAN: The device is not in the router mode.");
}

/* Done */
fwrite("a",$START, "exit 0\n");
fwrite("a", $STOP, "exit 0\n");
?>
