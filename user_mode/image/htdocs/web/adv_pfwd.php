HTTP/1.1 200 OK

<?
/* The variables are used in js and body both, so define them here. */
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/inf.php";
$p = XNODE_getpathbytarget("/nat", "entry", "uid", "NAT-1", 0);
$PFWD_MAX_COUNT = query($p."/portforward/max");

/* necessary and basic definition */
$TEMP_MYNAME    = "adv_pfwd";
$TEMP_MYGROUP   = "advanced";
$TEMP_STYLE		= "complex";
include "/htdocs/webinc/templates.php";
?>
