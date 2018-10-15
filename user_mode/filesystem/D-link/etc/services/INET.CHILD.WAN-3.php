<?
include "/etc/services/INET/interface.php";
fwrite("w",$START, "#!/bin/sh\n");
fwrite("w", $STOP, "#!/bin/sh\n");
ifchildsetup("WAN-3");
fwrite("a",$START, "#sleep 2\n");
?>
