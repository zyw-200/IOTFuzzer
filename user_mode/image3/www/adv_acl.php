<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_acl";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_acl";
set("/runtime/web/help_page",$MY_NAME);
/* --------------------------------------------------------------------------- */
if($ACTION_POST != "")
{
	require("/www/model/__admin_check.php");
	require("/www/__action_adv.php");
	$ACTION_POST = "";
	exit;
}

/* --------------------------------------------------------------------------- */
require("/www/model/__html_head.php");
require("/www/comm/__js_ip.php");
set("/runtime/web/next_page",$first_frame);
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
echo "<!--debug\n";
set("/runtime/web/sub_acl", "");
if($band_reload == 0 || $band_reload == 1) // change band
{ 	
	$cfg_band = $band_reload;
}
else // first init
{	
		$cfg_band = query("/wlan/ch_mode");
}
if($cfg_band == 0) // 11g
{
	anchor("/wlan/inf:1");
	$anchor_node = "/wlan/inf:1";
	$path_node = "/runtime/stats/wlan/inf:1";
	set("/runtime/wireless/aclpath","/wlan/inf:1/acl");
}
else
{
	anchor("/wlan/inf:2");
	$anchor_node = "/wlan/inf:2";
	$path_node = "/runtime/stats/wlan/inf:2";
	set("/runtime/wireless/aclpath","/wlan/inf:2/acl");
}
$acl_type		= query("acl/mode");
$zone_defence = query("zonedefence");
$ap_mode	=query("ap_mode");
$acl_index = 0;
for("acl/mac")
{
	$acl_index++;
}
$client_index=0;
for($path_node."/client")
{	
	$client_index++;
}
for($anchor_node."/multi/index")
{
		$mssid_index = $@;
	if(query("state") !="0")
	{
		for($path_node."/mssid:".$mssid_index."/client")
		{
			$client_index++;
		}
	}
}
echo "-->";
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>
var acl_list=[['index','mac']<?
for("acl/mac")
{
	echo ",\n['".$@."',";
	anchor(.$anchor_node."/acl");
	echo "'".query("mac:".$@.)."']";
}
?>];
function on_change_band(s)
{
	var f = get_obj("frm");
	self.location.href = "adv_acl.php?band_reload=" + s.value;
}

function acl_del_confirm(id)
{
	var f = get_obj("frm");
	if(confirm("<?=$a_acl_del_confirm?>")==false) return;
	f.f_acl_del.value = id;
	fields_disabled(f, false);
	get_obj("frm").submit();
}

function on_click_client_info(id,mac)
{
	var f = get_obj("frm");

	for(i=1;i<acl_list.length;i++)
	{
		if(acl_list.length == 257)
		{
			alert("<?=$a_max_acl_mac?>");
			get_obj(id).checked = false;
			return false;
		}	
			
		if(mac == acl_list[i][1])
		{
			alert("<?=$a_same_acl_mac?>");
			get_obj(id).checked = false;
			return false;
		}			
	}
	f.f_client_info_mac_add.value = 1;
	f.hacl_mac.value = mac;
	get_obj("frm").submit();
}

function print_rule_del(id)
{
	var str="";

	str+="&nbsp;&nbsp;<a href='javascript:acl_del_confirm(\""+id+"\")'><img src='/pic/delete.jpg' border=0></a>";

	document.write(str);
}
/* page init functoin */
function init()
{
	var f=get_obj("frm");
	
	select_index(f.band, "<?=$cfg_band?>");
	
	if("<?=$ap_mode?>" =="1")
	{
		f.acl_type.value = "0";
		f.acl_type.disabled = true;
	}
	else
	{
		f.acl_type.value = "<?=$acl_type?>";
		f.acl_type.disabled = false;
	}	
	
	on_change_acl_type();
	AdjustHeight();
}

function on_change_acl_type()
{
	var f = get_obj("frm");
	
	if(f.acl_type.value ==0)
	{
		f.acl_mac1.disabled = f.acl_mac2.disabled = f.acl_mac3.disabled = f.acl_mac4.disabled = f.acl_mac5.disabled = f.acl_mac6.disabled = true;
		if(f.client_info1 != null)
		{	
			for(var i=1;i<= <?=$client_index?>;i++)
			get_obj("client_info"+i).disabled=true;
		}
	}
	else
	{
		f.acl_mac1.disabled = f.acl_mac2.disabled = f.acl_mac3.disabled = f.acl_mac4.disabled = f.acl_mac5.disabled = f.acl_mac6.disabled = false;	
		if(f.client_info1 != null)
		{
			for(var i=1;i<= <?=$client_index?>;i++)
			get_obj("client_info"+i).disabled=false;
		}
	}		
	if("<?=$zone_defence?>"==1)
    {
        if(f.acl_type.value !=2)
        {
            alert("<?=$a_zone_defence_disable?>");
        }
    }		
}
/* parameter checking */

function check()
{
	// do check here ....

	return true;
}

function submit()
{
	if(check()) get_obj("frm").submit();
}

function submit_do_add()
{
	var f = get_obj("frm");
	if(do_add())  
	{
		fields_disabled(f, false);
		get_obj("frm").submit();
	}
}

function do_add()
{
	var f = get_obj("frm");
	var acl_mac, ACL_MAC = "", ACL_MAC_LIST = "";
	var mac_check,mac_st="";
	if(f.acl_type.value !="0")
	{
	for(i=1;i<=6;i++)
	{
		mac_st+=eval("f.acl_mac"+i+".value");
	
	}
	if(mac_st == "")
	{
		alert("<?=$a_invalid_mac?>");
		return false;
	}
	else if(f.acl_type.value !=0 && ((mac_st !="" && "<?=$acl_index?>" !=0 )|| "<?=$acl_index?>" == 0) )
	{
		var num_mac1=parseInt("0x"+f.acl_mac1.value);
    	var str_mac1=num_mac1.toString(2);
	    if(str_mac1.charAt(str_mac1.length-1) == "1")
		{
			alert("<?=$a_invalid_mac?>");
			f.acl_mac1.select();
			return false;
		}
		for(i=1;i<=6;i++)
		{
			acl_mac=eval("f.acl_mac"+i+".value");
			mac_check=eval("f.acl_mac"+i+".value");

			if(is_blank(acl_mac) || !is_valid_mac(acl_mac))
			{
				alert("<?=$a_invalid_mac?>");
				eval("f.acl_mac"+i+".focus()");
				return false;			
			}
			if (acl_mac.length == 1) eval("f.acl_mac"+i+".value= '0'+acl_mac");
		}
	
		acl_mac = f.acl_mac1.value+":"+f.acl_mac2.value+":"+f.acl_mac3.value+":"+f.acl_mac4.value+":"+f.acl_mac5.value+":"+f.acl_mac6.value;
		mac_check =f.acl_mac1.value+f.acl_mac2.value+f.acl_mac3.value+f.acl_mac4.value+f.acl_mac5.value+f.acl_mac6.value;
		if(acl_mac == "00:00:00:00:00:00")
		{
			alert("<?=$a_invalid_mac?>");
			f.acl_mac1.select();
			return false;
		}
		for(i=1;i<acl_list.length;i++)
		{
			if((mac_check.length != 12)&&(mac_check.length != 0))
			{
				alert("<?=$a_invalid_mac?>");
				field_focus(f.acl_mac1, "**");
				return false;
			}

			if((acl_list.length == 257)&&(!is_blank(mac_check)))
			{
				alert("<?=$a_max_acl_mac?>");
				return false;
			}	
				
			ACL_MAC = acl_mac.toUpperCase();
			ACL_MAC_LIST = acl_list[i][1].toUpperCase();
			
			if(ACL_MAC == ACL_MAC_LIST)
			{
				alert("<?=$a_same_acl_mac?>");
				field_focus(f.acl_mac1, "**");
				return false;
			}		
			
			if(is_blank(mac_check))
			{
				acl_mac = 0;				
			}							
		}	
				
		f.hacl_mac.value=acl_mac.toUpperCase();
		
	}
	else if(f.acl_type.value ==0)
	{
		acl_mac = 0;
		f.hacl_mac.value=acl_mac;
	}

	f.f_add.value = 1;
	
	return true;
	}
	//get_obj("frm").submit();	
}

function formCheckAcl()
{
	var f=get_obj("frm_acl");	
	
	if(is_blank(f.macacl.value))
	{
		alert("<?=$a_blank_acl_file?>");
		f.macacl.focus();
		return false;
	}	

	if(f.macacl.value.slice(-4)!=".acl")
	{
		alert("<?=$a_format_error_file?>");
		f.macacl.focus();
		return false;
	}	
}

function checkacl()
{
	var f=get_obj("frm_acl");
<?
set("/runtime/web/next_page",$MY_NAME);
?>
}

function save_acl()
{
	self.location.href="../acl.bin";
}
</script>

<body <?=$G_BODY_ATTR?> onload="init();">

<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return check();">

<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<input type="hidden" name="f_wlmode"	value="">
<input type="hidden" name="f_acl_del"	value="">
<input type="hidden" name="hacl_mac" 	value="">
<input type="hidden" name="f_client_info_mac_add" 	value="">
<input type="hidden" name="f_add"			value="">

<table id="table_frame" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
	<tr>
		<td valign="top">
			<table id="table_header" <?=$G_TABLE_ATTR_CELL_ZERO?>>
			<tr>
				<td id="td_header" valign="middle"><?=$m_context_title?></td>
			</tr>
			</table>
<!-- ________________________________ Main Content Start ______________________________ -->
			<table id="table_set_main"  border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
				<tr>
					<td width="30%" id="td_left">
						<?=$m_wband?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("band", [0,1], ["<?=$m_band_2.4G?>","<?=$m_band_5G?>"], "on_change_band(this)");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_acl_type?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("acl_type", [0,1,2], ["<?=$m_disable?>","<?=$m_accept?>","<?=$m_reject?>"], "on_change_acl_type(this)");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_wmac?>
					</td>
					<td id="td_right">
						<script>print_mac("acl_mac");</script>&nbsp;&nbsp;
						<a href="javascript:submit_do_add()">
						<img src="<?=$pic_path?>add1.gif" align="middle" border="0" OnMouseOver="this.src='<?=$pic_path?>add2.gif'"  OnMouseOut="this.src='<?=$pic_path?>add1.gif'">
						</a>
					</td>
				</tr>			

				<tr>
					<td colspan="2">
						<table width="100%" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
							<tr class="list_head" align="left">
								<td width="10">
									&nbsp;
								</td>
								<td width="70">
									<?=$m_id?>
								</td>
								<td width="200">
									<?=$m_mac?>
								</td>
								<td>
									<?=$m_del?>
								</td>																																																										
							</tr>	
						</table>	
						<div class="div_tab">
							<table id="acl_tab" width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>						
<?
$tmp = "";
for("acl/mac")
{
	$tmp = $@%2;
	anchor(.$anchor_node."/acl");
	
	if($tmp == 1)
	{
		echo "<tr align=\"left\" style=\"background:#cccccc;\">\n";
	}
	else
	{
		echo "<tr align=\"left\" style=\"background:#b3b3b3;\">\n";
	}
	echo "<td width=\"10\">&nbsp;</td>\n";	
	echo "<td width=\"70\">".$@."</td>\n";	
	echo "<td width=\"200\">".query("mac:".$@.)."</td>\n";	
	echo "<td>".$G_TAG_SCRIPT_START."print_rule_del(".$@.");".$G_TAG_SCRIPT_END."</td></tr></td>\n";			
	echo "</tr>\n";	
}
?>
							</table>	
						</div>										
					</td>
				</tr>
				<tr height="20">
					<td>
						<?=$m_clirnt_info?>
					</td>
				</tr>	
				<tr>
					<td colspan="2">
						<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
							<tr class="list_head" align="left">
								<td width="10">
									&nbsp;
								</td>
								<td width="120">
									<?=$m_mac?>
								</td>															
								<td width="90">
									<?=$m_ssid?>
								</td>
								<td width="60">
									<?=$m_band?>
								</td>
								<td width="130">
									<?=$m_auth?>
								</td>
								<td width="60">
									<?=$m_signal?>
								</td>
								<td>
									<?=$m_b_add?>
								</td>																																																																				
							</tr>									
						</table>

						<div class="div_tab">
							<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>

<?
$tmp_1 = "";
$tmp = "";
$client_num=1;
$primary_ssid=get("j", "ssid");
$tmp_auth=query("authentication");
$index_for=$cfg_band+1;
for("/runtime/stats/wlan/inf:".$index_for."/client")
{	
	$tmp_1 = $@%2;
	if($tmp_1 == 1)
	{
		echo "<tr align=\"left\" style=\"background:#cccccc;\">\n";
		$tmp = 1;
	}
	else
	{
		echo "<tr align=\"left\" style=\"background:#b3b3b3;\">\n";
		$tmp = 0;
	}
	echo "<td width=\"10\">&nbsp</td>\n";
	echo "<td width=\"120\">".query("mac")."</td>\n";
	echo "<td width=\"90\">".$G_TAG_SCRIPT_START."genTableSSID(\"".$primary_ssid."\",\"0\");".$G_TAG_SCRIPT_END."</td>\n";
	echo "<td width=\"60\">".query("band")."</td>\n";
	if($tmp_auth!=9)
	{
	echo "<td width=\"130\">".query("auth")."</td>\n";
	}
	else
	{
		echo "<td width=\"130\">802.1X/WEP</td>\n";
	}
	echo "<td width=\"60\">".query("rssi")."%</td>\n";
	echo "<td>".$G_TAG_SCRIPT_START."genCheckBox(\"client_info".$client_num."\",\"on_click_client_info('client_info".$client_num."','".query("mac")."')\")".$G_TAG_SCRIPT_END."</td>\n";		
	echo "</tr>\n";	
	$client_num++;			
}

$ssid = "";
$mssid_index = "";
for("/wlan/inf:".$index_for."/multi/index")
{
	$tmp_mauth=query("auth");
		$mssid_index = $@;
	if(query("state") !="0")
	{
		for("/runtime/stats/wlan/inf:".$index_for."/mssid:".$mssid_index."/client")
		{
			$ssid =get("j","/wlan/inf:".$index_for."/multi/index:".$mssid_index."/ssid");
			if($tmp != 1)
			{
				echo "<tr align=\"left\" style=\"background:#cccccc;\">\n";
				$tmp = 1;
			}
			else
			{
				echo "<tr align=\"left\" style=\"background:#b3b3b3;\">\n";
				$tmp = 0;
			}
			echo "<td width=\"10\">&nbsp</td>\n";			
			echo "<td width=\"120\">".query("mac")."</td>\n";
			echo "<td width=\"100\">".$G_TAG_SCRIPT_START."genTableSSID(\"".$ssid."\",\"0\");".$G_TAG_SCRIPT_END."</td>\n";			
			echo "<td width=\"60\">".query("band")."</td>\n";
			if($tmp_mauth!=9)
			{
				echo "<td width=\"130\">".query("auth")."</td>\n";
			}
			else
			{
				echo "<td width=\"130\">802.1X/WEP</td>\n";
			}
			echo "<td width=\"60\">".query("rssi")."%</td>\n";
			echo "<td>".$G_TAG_SCRIPT_START."genCheckBox(\"client_info".$client_num."\",\"on_click_client_info('client_info".$client_num."','".query("mac")."')\")".$G_TAG_SCRIPT_END."</td>\n";			
			echo "</tr>\n";		
			$client_num++;						
		}
	}
}
?>

							</table>			
						</div>				
					</td>	
				</tr>		
</form>
<? if(query("/runtime/web/display/aclupdnload") !="1") {echo "<!--";} ?>
	<form name="frm_acl" id="frm_acl" method="POST" action="upload_acl._int" enctype="multipart/form-data" onsubmit="return checkacl();">
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
				<td  bgcolor="#cccccc" colspan="2" height="16"><b><?=$m_upload_acl_titles?></b></td>
				</tr>
				<tr>
					<td width="160" height="30"><?=$m_upload_acl_file?> :</td>
					<td>
					<input type="file" name="macacl" id="macacl" class="text" size="30">
					<input type="submit" name="acl_load"  value=" <?=$m_acl_upload?> " onClick="return formCheckAcl()">
					</td>
				</tr>

				<tr>
				<td  bgcolor="#cccccc" colspan="2" height="16"><b><?=$m_download_acl_title?></b></td>
				</tr>					
				<tr>
					<td width="160" height="30"><?=$m_save_acl?> :</td>
					<td><input type="button" value="<?=$m_save?>" onclick="save_acl()"></td>
				</tr>
				</form>			
<? if(query("/runtime/web/display/aclupdnload") !="1") {echo "-->";} ?>
				<tr>
					<td colspan="2">
<?=$G_APPLY_BUTTON?>
					</td>
				</tr>				
			</table>
<!-- ________________________________  Main Content End _______________________________ -->
		</td>
	</tr>
</table>
</body>
</html>			
