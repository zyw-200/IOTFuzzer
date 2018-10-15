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

if (query("/runtime/device/layout")=="bridge")
{
	/* Start all LAN interfaces. */
	ifinetsetupall("BRIDGE");
}
else
{
	SHELL_info($START, "BRIDGE: The device is not in the bridge mode.");
	SHELL_info($STOP,  "BRIDGE: The device is not in the bridge mode.");
}

/* Done */
fwrite("a",$START, "exit 0\n");
fwrite("a", $STOP, "exit 0\n");
?>
