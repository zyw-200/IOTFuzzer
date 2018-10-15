<?
/* vi: set sw=4 ts=4: */
require("/www/model/__auth_check.php");
$MY_NAME			="sys_stunnel_process";
$MY_MSG_FILE		=$MY_NAME.".php";

$NO_NEED_AUTH		="1";
$NO_SESSION_TIMEOUT	="1";
$NO_COMMJS			="1";
$NO_BUTTON			="1";
set("/runtime/web/acl/upload_flag",1);

if($reboot=="1")
{
        set("/runtime/reboot",1);
}
if($reboot=="2")
{
	set("/runtime/reboot",2);
}
require("/www/model/__html_head.php");
?>
<script type="text/javascript">
<?
echo "var countdown=5;\n";
?>
function init()
{
	nev();
}
function nev(){ countdown--; document.frm.WaitInfo.value=countdown;
if(countdown < 1 ) top.location.href='<?=$G_HOME_PAGE?>.php';
else setTimeout('nev()',1000);}
</script>
<?
$REQUIRE_FILE="__file_is_processing.php";
require("/www/model/__show_info.php");
?>
