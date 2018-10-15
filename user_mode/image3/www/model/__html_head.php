<?
/* vi: set sw=4 ts=4: */
require("/www/model/__auth_check.php");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="/model/router.css" type="text/css" />
<meta http-equiv="Content-Type" content="no-cache" />
<meta http-equiv="Content-Type" content="text/html; charset=<?=$CHARSET?>" />
<?=$OTHER_META?>
<title><?=$DISNAME?></title>
<?
if ($NO_SESSION_TIMEOUT!="1" && $NOT_FRAME!="1")	{ require("/www/auth/__session_timeout.php"); }
if ($NO_COMMJS!="1")			{ require("/www/comm/__js_comm.php"); }
if ($NO_BUTTON!="1")			{ require("/www/model/__button.php"); }
?>
</head>

