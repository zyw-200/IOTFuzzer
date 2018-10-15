<?
include "/etc/services/INET/interface.php";
fwrite("w",$START, "#!/bin/sh\n");
fwrite("w", $STOP, "#!/bin/sh\n");
fwrite("a",$START, "event PCI.APAUTODETECT\n");
ifsetup("BRIDGE-1");
?>
