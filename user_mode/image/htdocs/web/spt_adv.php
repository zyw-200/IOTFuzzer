HTTP/1.1 200 OK

<?
/* The variables are used in js and body both, so define them here. */
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/inf.php";

$p = XNODE_getpathbytarget("/nat", "entry", "uid", "NAT-1", 0);
$VSVR_MAX_COUNT = query($p."/virtualserver/max");
if ($VSVR_MAX_COUNT == "") $VSVR_MAX_COUNT = 5;

$p = XNODE_getpathbytarget("/nat", "entry", "uid", "NAT-1", 0);
$PFWD_MAX_COUNT = query($p."/portforward/max");
if ($PFWD_MAX_COUNT == "") $PFWD_MAX_COUNT = 5;

$p = XNODE_getpathbytarget("/nat", "entry", "uid", "NAT-1", 0);
$APP_MAX_COUNT = query($p."/porttrigger/max");
if ($APP_MAX_COUNT == "") $APP_MAX_COUNT = 24; 

$MAC_FILTER_MAX_COUNT = query("/acl/macctrl/max");
if ($MAC_FILTER_MAX_COUNT == "") $MAC_FILTER_MAX_COUNT = 25;

$URL_MAX_COUNT = query("/acl/urlctrl/max");
if ($URL_MAX_COUNT == "") $URL_MAX_COUNT = 32;

$TEMP_MYNAME    = "spt_adv";
$TEMP_MYGROUP   = "support";
$TEMP_STYLE	= "support";
include "/htdocs/webinc/templates.php";
?>
