HTTP/1.1 200 OK

<?
/* The variables are used in js and body both, so define them here. */
$MAC_FILTER_MAX_COUNT = query("/acl/macctrl/max");
if ($MAC_FILTER_MAX_COUNT == "") $MAC_FILTER_MAX_COUNT = 25;

/* necessary and basic definition */
$TEMP_MYNAME    = "adv_mac_filter";
$TEMP_MYGROUP   = "advanced";
$TEMP_STYLE		= "complex";
include "/htdocs/webinc/templates.php";
?>
