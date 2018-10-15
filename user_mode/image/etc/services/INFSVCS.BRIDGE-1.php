<? /* vi: set sw=4 ts=4: */
include "/etc/services/INFSVCS/infservices.php";
fwrite("w",$START, "#!/bin/sh\n");
fwrite("w", $STOP, "#!/bin/sh\n");
fwrite("a", $STOP, "event INFSVCS.BRIDGE-1.DOWN\n");

infsvcs_setup("BRIDGE-1");


fwrite("a",$START, "event UPDATERESOLV\n");
fwrite("a", $STOP, "event UPDATERESOLV\n");
fwrite("a",$START, "event INET.CONNECTED\n");
fwrite("a", $STOP, "event INET.DISCONNECTED\n");
fwrite("a",$START, "service DEVICE.TIME start\n");

/* restart WIFI enhancement */
fwrite("a",$START, "service MULTICAST restart\n");
fwrite("a",$STOP,  "service MULTICAST restart\n");

/* refresh the default chain when the interface is comming up/down. */
fwrite("a",$STOP,  "service IPTDEFCHAIN restart\n");
fwrite("a",$START, "service IPTDEFCHAIN restart\n");
/* restart LLD2 */
fwrite("a",$START, "service LLD2 restart\n");
fwrite("a",$START, "event INFSVCS.BRIDGE-1.UP\n");
?>
