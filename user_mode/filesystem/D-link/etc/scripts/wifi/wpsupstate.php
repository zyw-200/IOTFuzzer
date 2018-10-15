<?/* vi: set sw=4 ts=4: */
/* update WPS status */
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/trace.php";

/* we take wps state as one. Let WLAN-1 and WLAN-2 be the same */

/*
$p = XNODE_getpathbytarget("/runtime", "phyinf", "uid", $PHY_UID);
set($p."/media/wps/enrollee/state", $STATE);
*/
$p = XNODE_getpathbytarget("/runtime", "phyinf", "uid", "WLAN-1");
set($p."/media/wps/enrollee/state", $STATE);
$p = XNODE_getpathbytarget("/runtime", "phyinf", "uid", "WLAN-2");
set($p."/media/wps/enrollee/state", $STATE);

?>
