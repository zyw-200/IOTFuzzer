HTTP/1.1 200 OK

<?
/* The variables are used in js and body both, so define them here. */
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/inf.php";
$p = XNODE_getpathbytarget("/dhcps4", "entry", "uid", "DHCPS4-1", 0);
$DHCP_MAX_COUNT = query($p."/staticleases/max");

/* necessary and basic definition */
$TEMP_MYNAME    = "bsc_lan";
$TEMP_MYGROUP   = "basic";
$TEMP_STYLE		= "complex";
include "/htdocs/webinc/templates.php";
?>
