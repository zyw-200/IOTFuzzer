<?
/* vi: set sw=4 ts=4: */
require("/www/model/__auth_check.php");
$MY_NAME			="sys_cfg_valid";
$MY_MSG_FILE		=$MY_NAME.".php";

$NO_NEED_AUTH		="1";
$NO_SESSION_TIMEOUT	="1";
$NO_COMMJS			="1";
$NO_BUTTON			="1";
require("/www/model/__html_head.php");
require("/www/model/__burn_time.php");
$cfg_mode = query("/wlan/inf:1/ap_mode");
$cfg_mssid = query("/wlan/inf:1/multi/state");
?>
<script>
if("<?=$cfg_mode?>" == 3 || "<?=$cfg_mode?>" == 4 || "<?=$cfg_mssid?>" == 1)
{
	var countdown = 170;
}
else
{
	var countdown = parseInt((get_burn_time(64)+10), [10]);
}
function init()
{
	nev();
}
function nev()
{
	countdown--;
	document.frm.WaitInfo.value=countdown;
	if(countdown < 1 ) top.location.href='<?=$G_HOME_PAGE?>.php';
	else setTimeout('nev()',1000);
}
</script>
<?
$REQUIRE_FILE="__rebooting_msg.php";
require("/www/model/__show_info.php");
?>
