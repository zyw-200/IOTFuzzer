<?
/* vi: set sw=4 ts=4: */
$MY_NAME			="cfg_valid";
$MY_MSG_FILE		=$MY_NAME.".php";

$NO_NEED_AUTH		="1";
$NO_SESSION_TIMEOUT	="1";
$NO_COMMJS			="1";
$NO_BUTTON			="1";
require("/www/model/__html_head.php");
require("/www/model/__burn_time.php");
$SUBMIT_STR	=query("/runtime/web/sub_str");
$XGISET_STR	=query("/runtime/web/sub_xgi_str");
$cfg_mode = query("/wlan/inf:1/ap_mode");
$cfg_mssid = query("/wlan/inf:1/multi/state");
$cfg_count= query("/runtime/web/count");
$change_ip=query("/runtime/web/check/change_ip");
if($flag=="1")
{
	set("/runtime/web/count",0);
}
?>
<script>
function generate_random_str()
{
	var d = new Date();
	var str=d.getFullYear()+"."+(d.getMonth()+1)+"."+d.getDate()+"."+d.getHours()+"."+d.getMinutes()+"."+d.getSeconds();
	return str;
}
var countdown =<?=$cfg_count?>;
var str="cfg_valid.xgi?random="+generate_random_str();
function exe_str(str_shellPath)
{
	var temp_str="";
	myShell = str_shellPath.split(";");
	for(i=0; i<myShell.length; i++)
	{
		temp_str+="&"+"exeshell="+myShell[i];
	}
	return temp_str;
}
<? 
	if ($SUBMIT_STR!="")
	{
		echo "str+=exe_str(\"submit COMMIT;".$SUBMIT_STR."\");\n";
	}
	else
	{
		echo "str+=exe_str(\"submit COMMIT\");\n";
	}
	
?>

function init()
{
	
	if("<?=$flag?>" != "1")
	{
		str+="&flag=1";
	 	self.location.href=str;
	}
	<?
		if ($flag=="1" || $change_ip=="1")
		{
			echo "nev();";
		}
		else
		{
			echo "document.frm.WaitInfo.value=countdown;";
		}
	?>
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
$REQUIRE_FILE="__active_msg.php";
require("/www/model/__show_info.php");
set("/runtime/web/check/change_ip",0);
?>
