<?
include "/etc/services/INET/interface.php";
fwrite("w",$START, "#!/bin/sh\n");
fwrite("w", $STOP, "#!/bin/sh\n");
ifchildsetup("LAN-3");
?>
