<?
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/trace.php";

$p = XNODE_getpathbytarget("/runtime", "phyinf", "uid", "WLAN-1");
$method = query($p."/media/wps/enrollee/method");

fwrite("w",$START, "#!/bin/sh\n");
if ($method == "pbc")	fwrite("a",$START, "/etc/scripts/wps.sh pbc &\n");
else					fwrite("a",$START, "/etc/scripts/wps.sh pin &\n");
fwrite("a",$START, "exit 0\n");

fwrite("w", $STOP, "#!/bin/sh\n");
fwrite("a", $STOP, "exit 0\n");
?>
