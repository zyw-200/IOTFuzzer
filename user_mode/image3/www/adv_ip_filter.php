<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_ip_filter";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_ip_filter";
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
anchor("/captival");
$check_band = query("/wlan/inf:2/ap_mode");

if($band_reload == 0 || $band_reload == 1){$cfg_band = $band_reload;}
else{$cfg_band = query("/wlan/ch_mode");}

if($index_reload != ""){$cfg_index = $index_reload;}
else{$cfg_index = 0;}

if($cfg_band == 0){$ssid_index = $cfg_index + 1;}
else{$ssid_index = $cfg_index + 9;}

anchor("/captival/ssid:".$ssid_index);
$cfg_state = query("state");
$ip_index = 0;
echo "-->";
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>
var ip_list=[['index','ip','mask']<?
for("ipfilter/index")
{
	echo ",\n ['".$@."','".query("ip")."','".query("mask")."']";
	$ip_index++;
}
?>];

function on_change_index()
{
	var f = get_obj("frm");
	self.location.href = "adv_ip_filter.php?band_reload=" + f.band.value + "&index_reload=" + f.ssid_index.value;
}

function page_table(index)
{
	var f=get_obj("frm");
	var str="";
	if(ip_list[index][1] != "" && ip_list[index][2] != "" && "<?=$cfg_state?>" != 0 && "<?=$cfg_state?>" != "")
	{
		if(index%2 == 1)
		{
			str+= "<tr align='left' style='background:#cccccc;'>\n";
		}
		else
		{
			str+= "<tr align='left' style='background:#b3b3b3;'>\n";
		}
		str+= "<td width='10'>&nbsp;</td>\n";	
		str+= "<td width='70'>"+index+"</td>\n";	
		str+= "<td width='180'>"+ip_list[index][1]+"</td>\n";	
		str+= "<td width='180'>"+ip_list[index][2]+"</td>\n";
		str+= "<td><a href='javascript:do_del(\""+index+"\")'><img src='/pic/delete.jpg' border=0></a></td>";
		str+= "</tr>\n";
	}
	document.write(str);
}

function do_del(id)
{
	var f = get_obj("frm");
	if(f.band.value == 0)
		f.f_ssid.value = parseInt(f.ssid_index.value, [10]) + 1;
	else
		f.f_ssid.value = parseInt(f.ssid_index.value, [10]) + 9;
	if(confirm("<?=$a_del_confirm?>")==true)
	{
		f.f_action.value = "del";
		f.which_del.value = id;
		fields_disabled(f, false);
		f.submit();
	}
}

/* page init functoin */
function init()
{
	var f=get_obj("frm");
	if("<?=$check_band?>" == "")
		f.band.disabled = true;
	select_index(f.band, "<?=$cfg_band?>");
	select_index(f.ssid_index, "<?=$cfg_index?>");
	if("<?=$cfg_state?>" == 0 ||"<?=$cfg_state?>" == "")
		f.ip.disabled = f.mask.disabled = true;
	AdjustHeight();
}

/* parameter checking */

function check()
{
	return true;
}

function submit()
{
	if(check()) get_obj("frm").submit();
}

function submit_do_add()
{
	var f = get_obj("frm");
	if("<?=$cfg_state?>" != 0 && "<?=$cfg_state?>" != "")
	{
		if(f.band.value == 0)
			f.f_ssid.value = parseInt(f.ssid_index.value, [10]) + 1;
		else
			f.f_ssid.value = parseInt(f.ssid_index.value, [10]) + 9;
		if(do_add())  
		{
			fields_disabled(f, false);
			get_obj("frm").submit();
		}
	}
}

function do_add()
{
	var f = get_obj("frm");
	var cap_ip="";
	var ip_check,ip_st="";
	if(!is_valid_ip3(f.ip.value, 0))
	{
		alert("<?=$a_invalid_ip?>");
		f.ip.select();
		return false;
	}
	if(!is_valid_mask(f.mask.value))
	{
		alert("<?=$a_invalid_mask?>");
		f.mask.select();
		return false;
	}

	if(ip_list.length == 65 && !is_blank(f.ip.value) && !is_blank(f.mask.value))
	{
		alert("<?=$a_max_ip?>");
		return false;
	}
	for(i=1;i<ip_list.length;i++)
	{
		if(f.ip.value == ip_list[i][1] && f.mask.value == ip_list[i][2])
		{
			alert("<?=$a_same_rule?>");
			return false;
		}
	}
	f.f_action.value = "add";
	return true;
}

function formCheckIp()
{
	var f=get_obj("frm_ip");	
	
	if(is_blank(f.uploadip.value))
	{
		alert("<?=$a_blank_ip_file?>");
		f.uploadip.focus();
		return false;
	}	

	if(f.uploadip.value.slice(-4)!=".txt")
	{
		alert("<?=$a_format_error_file?>");
		f.uploadip.focus();
		return false;
	}	
}

function checkip()
{
	var f=get_obj("frm_ip");
<?
set("/runtime/web/next_page",$MY_NAME);
?>
}

function save_ip()
{
	self.location.href="../captival_ipfilter.bin";
}
</script>

<body <?=$G_BODY_ATTR?> onload="init();">

<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return check();">

<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<input type="hidden" name="f_ssid"   value="">
<input type="hidden" name="f_action"	value="">
<input type="hidden" name="which_del"	value="">

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
						<?=$G_TAG_SCRIPT_START?>genSelect("band", [0,1], ["<?=$m_band_2_4G?>","<?=$m_band_5G?>"], "on_change_index()");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_ssid_index?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("ssid_index", [0,1,2,3,4,5,6,7], ["<?=$m_pri?>","<?=$m_ssid1?>","<?=$m_ssid2?>","<?=$m_ssid3?>","<?=$m_ssid4?>","<?=$m_ssid5?>","<?=$m_ssid6?>","<?=$m_ssid7?>"], "on_change_index()");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
				<tr>
					<td id="td_left"><?=$m_ip?></td>
					<td id="td_right">
						<input type="text" id="ip" name="ip" size="20" maxlength="15" value="">
					</td>
				</tr>
				<tr>
					<td id="td_left"><?=$m_mask?></td>
					<td id="td_right">
						<input type="text" id="mask" name="mask" size="20" maxlength="15" value="">
					</td>
				</tr>
				<tr>
					<td id="td_left"></td>
					<td id="td_right">
						<a href="javascript:submit_do_add()"><img src="<?=$pic_path?>add1.gif" align="middle" border="0" OnMouseOver="this.src='<?=$pic_path?>add2.gif'"  OnMouseOut="this.src='<?=$pic_path?>add1.gif'"></a>
					</td>
				</tr>			

				<tr>
					<td colspan="2">
						<table width="100%" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
							<tr class="list_head" align="left">
								<td width="10">&nbsp;</td>
								<td width="70"><?=$m_id?></td>
								<td width="180"><?=$m_ip?></td>
								<td width="180"><?=$m_mask?></td>
								<td><?=$m_del?></td>	
							</tr>	
						</table>	
						<div class="div_tab">
							<table id="acl_tab" width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>						
<?
$key=0;
while($key < $ip_index)
{
	$key++;
	echo $G_TAG_SCRIPT_START."page_table(".$key.");".$G_TAG_SCRIPT_END;
}
?>
							</table>	
						</div>										
					</td>
				</tr>
</form>
	<form name="frm_ip" id="frm_ip" method="POST" action="upload_captival_ipfilter._int" enctype="multipart/form-data" onsubmit="return checkip();">
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
				<td  bgcolor="#cccccc" colspan="2" height="16"><b><?=$m_upload_ip_titles?></b></td>
				</tr>
				<tr>
					<td width="160" height="30"><?=$m_upload_ip_file?> :</td>
					<td>
					<input type="file" name="uploadip" id="uploadip" class="text" size="30">
					<input type="submit" name="ip_load"  value=" <?=$m_upload?> " onClick="return formCheckIp()">
					</td>
				</tr>

				<tr>
				<td  bgcolor="#cccccc" colspan="2" height="16"><b><?=$m_download_ip_title?></b></td>
				</tr>					
				<tr>
					<td width="160" height="30"><?=$m_save_ip?> :</td>
					<td><input type="button" value="<?=$m_save?>" onclick="save_ip()"></td>
				</tr>
			</form>			
				<tr>
					<td colspan="2"><?=$G_APPLY_BUTTON?></td>
				</tr>
			</table>
<!-- ________________________________  Main Content End _______________________________ -->
		</td>
	</tr>
</table>
</body>
</html>			
