HTTP/1.1 200 OK

<?
/* The variables are used in js and body both, so define them here. */
include "/htdocs/phplib/inf.php";
$FW_MAX_COUNT = query("/acl/firewall/max");
if ($FW_MAX_COUNT == "") $FW_MAX_COUNT = 32;

/*necessary and basic definition */
$TEMP_MYNAME    = "adv_firewall";
$TEMP_MYGROUP   = "advanced";
$TEMP_STYLE		= "complex";
include "/htdocs/webinc/templates.php";
?>
