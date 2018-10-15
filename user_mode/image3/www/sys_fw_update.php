<?
/* vi: set sw=4 ts=4: */
require("/www/model/__auth_check.php");
$MY_NAME			="sys_fw_update";
$MY_MSG_FILE		=$MY_NAME.".php";

$NO_NEED_AUTH		="1";
$NO_SESSION_TIMEOUT	="1";
//$NO_COMMJS			="1";
$NO_BUTTON			="1";


require("/www/model/__html_head.php");
require("/www/model/__burn_time.php");

$step1=query("/runtime/web/display/upgrade_fw/step1/pass");
$step2=query("/runtime/web/display/upgrade_fw/step2/pass");
$step3=query("/runtime/web/display/upgrade_fw/step3/pass");
$Step_num=query("/runtime/web/display/upgrade_fw/step_num");

require("/www/comm/__js_comm.php"); 
?>

<script>
var failStr = "<?=$a_upgrade_fw_fail_msg?>";
var Step_num = "<?=$Step_num?>";

function init()
{
<?
	if ($Step_num=="0")
	{
		$Step_num=1; 
		set("/runtime/web/display/upgrade_fw/step_num", $Step_num);
	}	
	else if ($Step_num==1)
	{
		$Step_num=2; 
		set("/runtime/web/display/upgrade_fw/step_num", $Step_num);
	}		
	else if ($Step_num==2)
	{
		$Step_num=3; 
		set("/runtime/web/display/upgrade_fw/step_num", $Step_num);
	}
?>

alert("Step_num="+Step_num+"step1="+"<?=$step1?>");

	if(Step_num=="0") //star
	{
		UpdateInit();		
		ProgressRun('upload_fw_part', 'upload_fw_percent');	
	}		
	else if(Step_num=="1") //step1 
	{
		if("<?=$step1?>"==0) //if step1 error
		{
			upload_fw_bar.style.display = "none";
			upload_fw_check.style.visibility = "hidden";
			upload_fw_msg.innerHTML = failStr;
			upload_fw_msg.style.color = "red";
			upload_fw_msg.style.fontWeight="bolder";	
		}	
		else if("<?=$step1?>"==1) //if step1 ok
		{
			upload_fw_check.style.visibility = "visible";	
			Bar100Percent('upload_fw_part', 'upload_fw_percent');
			ProgressRun('verify_fw_part', 'verify_fw_percent');	
		}		
	}		
	else if(Step_num=="2") //step1 
	{
		upload_fw_check.style.visibility = "visible";	
		Bar100Percent('upload_fw_part', 'upload_fw_percent');		
		
		if("<?=$step2?>"==0) //if step1 error
		{
			verify_fw_bar.style.display = "none";
			verify_fw_check.style.visibility = "hidden";
			verify_fw_msg.innerHTML = failStr;
			verify_fw_msg.style.color = "red";
			verify_fw_msg.style.fontWeight="bolder";	
		}	
		else if("<?=$step2?>"==1) //if step1 ok
		{
			verify_fw_check.style.visibility = "visible";	
			Bar100Percent('verify_fw_part', 'verify_fw_percent');
			ProgressRun('upgrade_device_part', 'upgrade_device_percent');	
		}
	}		
	else if(Step_num=="3")
	{
		
		upload_fw_check.style.visibility = "visible";	
		Bar100Percent('upload_fw_part', 'upload_fw_percent');
		
		verify_fw_check.style.visibility = "visible";	
		Bar100Percent('verify_fw_part', 'verify_fw_percent');	
					
		if("<?=$step3?>"==0) //if step1 error
		{
			upgrade_device_bar.style.display = "none";
			upgrade_device_check.style.visibility = "hidden";
			upgrade_device_msg.innerHTML = failStr;
			upgrade_device_msg.style.color = "red";
			upgrade_device_msg.style.fontWeight="bolder";	
		}	
		else if("<?=$step3?>"==1) //if step1 ok
		{
			upgrade_device_check.style.visibility = "visible";	
			Bar100Percent('upgrade_device_part', 'upgrade_device_percent');
			ProgressRun('reboot_device_part', 'reboot_device_percent');	
		}
	}				
}

function UpdateInit()
{
	upload_fw_check.style.visibility = "hidden";
	verify_fw_check.style.visibility = "hidden";
	upgrade_device_check.style.visibility = "hidden";
	reboot_device_check.style.visibility = "hidden";
	upload_fw_msg.innerHTML = "";
	verify_fw_msg.innerHTML = "";
	upgrade_device_msg.innerHTML = "";
	reboot_device_msg.innerHTML = "";

	get_obj('upload_fw_percent').innerHTML = "0%";

	get_obj('verify_fw_percent').innerHTML = "0%";
	
	get_obj('upgrade_device_percent').innerHTML = "0%";

	get_obj('reboot_device_percent').innerHTML = "0%";
}

function Bar100Percent(PartName, PercentName)
{
	for(i=0; i<100; i++)
		get_obj(PartName+i).style.background = "#115582";
	get_obj(PercentName).innerHTML = "100%";
}

var CountTime = 20;
var ProgressLength = 100;
function ProgressRun(PartName, PercentName)
{
	var i = 100 - ProgressLength;

	var timeRange = 0;
	
	if(Step_num=="0")
		CountTime = 1;	
	else if(Step_num=="1")
		CountTime = 1;	
	else if(Step_num=="2")
		CountTime = 50;	
	else if(Step_num=="3")
		CountTime = 60;						
	
	timeRange = (CountTime*1000)/100;
	
	if (ProgressLength!=1)
	{
		ProgressLength-=1;
		get_obj(PartName+i).style.background = "#115582";
		get_obj(PercentName).innerHTML = (i+1)+"%";
	    setTimeout("ProgressRun('"+PartName+"', '"+PercentName+"')",timeRange);
	}
	else
	{
		ProgressLength = 100;

		if((Step_num=="0")||(Step_num=="1"))
		{
			self.location.href='<?=$MY_NAME?>.php';
		}
		else if(Step_num=="2")
		{
			var str="";
			str="/sys_fw_update.xgi?";
			str+=exe_str("submit REBOOT");
			self.location.href=str;			
		}		
		else if(Step_num=="3")
		{
			self.location.href='<?=$G_HOME_PAGE?>.php';
		}
	}
	
}
function GenProgressionBar(BarName, PartName, PercentName)
{
	var barStr="";
	barStr+="<table width=340 height=18 id="+ BarName +" cellpadding=0 cellspacing=0 border=0>\n";
	barStr+="<tr>\n";
	barStr+="<td width=300 align=center valign=middle>\n";
	barStr+="<table width=300 height=16 cellpadding=0 cellspacing=0 style='border: 1px solid #000000;'>\n";
	barStr+="<tr>\n";
	barStr+="<td>\n";
	barStr+="<table width='100%' height='100%' cellpadding=0 cellspacing=0 border=0>\n";
	barStr+="<tr>\n";
	for(i=0; i<100; i++)
		barStr+="<td width=3 id='"+ PartName + i +"'></td>\n";
	barStr+="</tr>\n";
	barStr+="</table>\n";
	barStr+="</td>\n";
	barStr+="</tr>\n";
	barStr+="</table>\n";
	barStr+="</td>\n";
	barStr+="<td width=40 align=right valign=middle><span id='"+ PercentName +"' style='font-family: Arial, Helvetica, sans-serif; font-size: 10pt;'>0%</span></td>\n";
	barStr+="</tr>\n";
	barStr+="</table>\n";
	
	document.write(barStr);
}

</script>
<body onload="init();" class="body_fw" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm" method="post" action="login.php">
<input type="hidden" name="ACTION_POST" value="LOGIN">
<center>
<?require("/www/model/__logo.php");?>
<table <?=$G_UPLOAD_FW_MAIN_TABLE_ATTR?>>
<tr>
	<td>
<!-- ________________________________ Main Content Start ______________________________ -->
	<table border="0" width=100%  cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%"  height="20" border="0" cellspacing="0" cellpadding="0" background="/pic/tool_bar.jpg">
			<tr>
				<td>&nbsp;</td>
			</tr>
			</table>			
			<table width="575" height="380" border="0" cellspacing="0" cellpadding="0" style="margin:70px 100px 10px 100px;">
			<tr>
				<td height="60">
				<?=$m_menu_upgrade_fw?>
				</td>
			</tr>	
			<tr>
				<td height="80">
				<!-- ________ Uploading Firmware Table ________ -->
				<table width="85%" height="50" id="upload_fw" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td>
						<font face="Arial" size="2"><img src="/pic/check.gif" id="upload_fw_check" style="visibility: hidden;">
						1. <?=$m_upload_fw?>
						</font>	
					</td>
				</tr>
				<tr>
					<td align="center">
						<script>GenProgressionBar("upload_fw_bar","upload_fw_part","upload_fw_percent");</script>
						<span id="upload_fw_msg" style="font-size: 12px; font-family: Arial;"></span>
					</td>
				</tr>					
				</table>		
				</td>
			</tr>
			<tr>
				<td height="80">
				<!-- ________ Verifying Firmware Table ________ -->
				<table width="85%" height="50" id="verify_fw" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td>
						<font face="Arial" size="2"><img src="/pic/check.gif" id="verify_fw_check" style="visibility: hidden;">
						2. <?=$m_verify_fw?>
						</font>	
					</td>
				</tr>
				<tr>
					<td align="center">
						<script>GenProgressionBar("verify_fw_bar","verify_fw_part","verify_fw_percent");</script>
						<span id="verify_fw_msg" style="font-size: 12px; font-family: Arial;"></span>
					</td>
				</tr>					
				</table>
				</td>
			</tr>
			<tr>
				<td height="80">
				<!-- ________ Upgrading Device Table ________ -->
				<table width="85%" height="50" id="upgrade_device" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td>
						<font face="Arial" size="2"><img src="/pic/check.gif" id="upgrade_device_check" style="visibility: hidden;">
						3. <?=$m_upgrad_device?>
						</font>	
					</td>
				</tr>
				<tr>
					<td align="center">
						<script>GenProgressionBar("upgrade_device_bar","upgrade_device_part","upgrade_device_percent");</script>
						<span id="upgrade_device_msg" style="font-size: 12px; font-family: Arial;"></span>
					</td>
				</tr>					
				</table>
				</td>
			</tr>
			<tr>
				<td height="80">
				<!-- ________ Rebooting Device Table ________ -->
				<table width="85%" height="50" id="reboot_device" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td>
						<font face="Arial" size="2"><img src="/pic/check.gif" id="reboot_device_check" style="visibility: hidden;">
						4. <?=$m_reboot_device?>
						</font>	
					</td>
				</tr>
				<tr>
					<td align="center">
						<script>GenProgressionBar("reboot_device_bar","reboot_device_part","reboot_device_percent");</script>
						<span id="reboot_device_msg" style="font-size: 12px; font-family: Arial;"></span>
					</td>
				</tr>					
				</table>
				</td>
			</tr>																	
			</table>	
		</td>
	</tr>
	</table>
<!-- ________________________________  Main Content End _______________________________ -->
	<br>
	</td>
</tr>
</table>
</center>
</form>
</body>
<script language="Javascript">
var upload_fw        	 = get_obj("upload_fw");
var verify_fw         	 = get_obj("verify_fw");
var upgrade_device       = get_obj("upgrade_device");
var reboot_device        = get_obj("reboot_device");
var upload_fw_check   	 = get_obj("upload_fw_check");
var verify_fw_check   	 = get_obj("verify_fw_check");
var upgrade_device_check = get_obj("upgrade_device_check");
var reboot_device_check  = get_obj("reboot_device_check");
var upload_fw_msg   	 = get_obj("upload_fw_msg");
var verify_fw_msg   	 = get_obj("verify_fw_msg");
var upgrade_device_msg 	 = get_obj("upgrade_device_msg");
var reboot_device_msg    = get_obj("reboot_device_msg");
var upload_fw_bar   	 = get_obj("upload_fw_bar");
var upload_fw_part    	 = get_obj("upload_fw_part");
var upload_fw_percent    = get_obj("upload_fw_percent");
var verify_fw_bar    	 = get_obj("verify_fw_bar");
var verify_fw_part       = get_obj("verify_fw_part");
var verify_fw_percent  	 = get_obj("verify_fw_percent");
var upgrade_device_bar   = get_obj("upgrade_device_bar");
var upgrade_device_part  = get_obj("upgrade_device_part");
var upgrade_device_percent  = get_obj("upgrade_device_percent");
var reboot_device_bar    	= get_obj("reboot_device_bar");
var reboot_device_part    	= get_obj("reboot_device_part");
var reboot_device_percent   = get_obj("reboot_device_percent");
</script>
</html>
