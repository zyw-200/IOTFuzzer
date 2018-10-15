HTTP/1.1 200 OK

<?
$TEMP_MYNAME	= "spt_tools";
$TEMP_MYGROUP	= "support";
$TEMP_STYLE		= "support";
$USR_ACCOUNTS	= query("/device/account/count");
include "/htdocs/webinc/templates.php";
?>
