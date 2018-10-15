HTTP/1.1 200 OK

<?
/*The variables are used in js and body both, so define them here. */
include "/htdocs/phplib/xnode.php";
$p = XNODE_getpathbytarget("/nat", "entry", "uid", "NAT-1", 0);
$APP_MAX_COUNT = query($p."/porttrigger/max");
if ($APP_MAX_COUNT == "") $APP_MAX_COUNT = 24; 

/*necessary and basic definition */
$TEMP_MYNAME    = "adv_app";
$TEMP_MYGROUP   = "advanced";
$TEMP_STYLE		= "complex";
include "/htdocs/webinc/templates.php";
?>
