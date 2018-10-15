HTTP/1.1 200 OK

<?
include "/htdocs/phplib/inf.php";
/*The variables are used in js and body both, so define them here. */
$ROUTING_MAX_COUNT = query("/route/static/max");
if ($ROUTING_MAX_COUNT == "") $ROUTING_MAX_COUNT = 10; 

/*necessary and basic definition */
$TEMP_MYNAME    = "adv_routingv6";
$TEMP_MYGROUP   = "advanced";
$TEMP_STYLE		= "complex";
include "/htdocs/webinc/templates.php";
?>
