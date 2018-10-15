HTTP/1.1 200 OK

<?
$TEMP_MYNAME    = "tools_admin";
$TEMP_MYGROUP   = "tools";
$TEMP_STYLE		= "complex";
$USR_ACCOUNTS	= query("/device/account/count");
include "/htdocs/webinc/templates.php";
?>
