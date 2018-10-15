HTTP/1.1 200 OK

<?
/* The variables are used in js and body both, so define them here. */
$URL_MAX_COUNT = query("/acl/urlctrl/max");
if ($URL_MAX_COUNT == "") $URL_MAX_COUNT = 32;

/* necessary and basic definition */
$TEMP_MYNAME    = "adv_web_filter";
$TEMP_MYGROUP   = "advanced";
$TEMP_STYLE		= "complex";
include "/htdocs/webinc/templates.php";
?>
