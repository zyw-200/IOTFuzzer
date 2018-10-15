<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_macbypass";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_macbypass";
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
$mac_index = 0;
echo "-->";
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>
var mac_list=[['index','mac']<?
for("white_list/index")
{
	echo ",\n ['".$@."','".query("mac")."']";
	$mac_index++;
}
?>];

function on_change_index()
{
	var f = get_obj("frm");
	self.location.href = "adv_macbypass.php?band_reload=" + f.band.value + "&index_reload=" + f.ssid_index.value;
}

function page_table(index)
{
	var f=get_obj("frm");
	var str="";
	if("<?=$cfg_state?>" != 0 && "<?=$cfg_state?>" != "")
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
		str+= "<td width='200'>"+mac_list[index][1]+"</td>\n";	
		str+= "<td><a href='javascript:do_del(\""+index+"\")'><img src='/pic/delete.jpg' border=0></a></td>";
		str+= "</tr>\n";
		document.write(str);
	}
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
	if("<?=$cfg_state?>" == 0 || "<?=$cfg_state?>" == "")
		f.cap_mac1.disabled = f.cap_mac2.disabled = f.cap_mac3.disabled = f.cap_mac4.disabled = f.cap_mac5.disabled = f.cap_mac6.disabled = true;
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
	var cap_mac="";
	var mac_check,mac_st="";
	for(i=1;i<=6;i++)
	{
		mac_st+=eval("f.cap_mac"+i+".value");
	}
	if(mac_st == "")
	{
		alert("<?=$a_invalid_mac?>");
		return false;
	}
	else
	{
		var num_mac1=parseInt("0x"+f.cap_mac1.value);
    	var str_mac1=num_mac1.toString(2);
	    if(str_mac1.charAt(str_mac1.length-1) == "1")
		{
			alert("<?=$a_invalid_mac?>");
			f.cap_mac1.select();
			return false;
		}
		for(i=1;i<=6;i++)
		{
			cap_mac=eval("f.cap_mac"+i+".value");
			mac_check=eval("f.cap_mac"+i+".value");

			if(is_blank(cap_mac) || !is_valid_mac(cap_mac))
			{
				alert("<?=$a_invalid_mac?>");
				eval("f.cap_mac"+i+".select()");
				return false;			
			}
			if (cap_mac.length == 1) eval("f.cap_mac"+i+".value= '0'+cap_mac");
		}
	
		cap_mac= f.cap_mac1.value+":"+f.cap_mac2.value+":"+f.cap_mac3.value+":"+f.cap_mac4.value+":"+f.cap_mac5.value+":"+f.cap_mac6.value;
		mac_check =f.cap_mac1.value+f.cap_mac2.value+f.cap_mac3.value+f.cap_mac4.value+f.cap_mac5.value+f.cap_mac6.value;
		if(cap_mac == "00:00:00:00:00:00")
		{
			alert("<?=$a_invalid_mac?>");
			f.cap_mac1.select();
			return false;
		}
		if((mac_check.length != 12)&&(mac_check.length != 0))
		{
			alert("<?=$a_invalid_mac?>");
			field_focus(f.cap_mac1, "**");
			return false;
		}

		if((mac_list.length == 65)&&(!is_blank(mac_check)))
		{
			alert("<?=$a_max_mac?>");
			return false;
		}	

		for(i=1;i<mac_list.length;i++)
		{
			if(cap_mac.toUpperCase()== mac_list[i][1].toUpperCase())
			{
				alert("<?=$a_same_rule?>");
				return false;
			}	
		}

		f.f_mac.value=cap_mac.toUpperCase();
	}
	f.f_action.value = "add";
	return true;
}

function formCheckMac()
{
	var f=get_obj("frm_mac");	
	
	if(is_blank(f.uploadmac.value))
	{
		alert("<?=$a_blank_mac_file?>");
		f.uploadmac.focus();
		return false;
	}	

	if(f.uploadmac.value.slice(-4)!=".txt")
	{
		alert("<?=$a_format_error_file?>");
		f.uploadmac.focus();
		return false;
	}	
}

function checkmac()
{
	var f=get_obj("frm_mac");
<?
set("/runtime/web/next_page",$MY_NAME);
?>
}

function save_mac()
{
	self.location.href="../captival_acl.bin";
}
</script>

<body <?=$G_BODY_ATTR?> onload="init();">

<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return check();">

<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<input type="hidden" name="f_ssid"   value="">
<input type="hidden" name="f_mac" 	value="">
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
					<td id="td_left">
						<?=$m_mac?>
					</td>
					<td id="td_right">
						<script>print_mac("cap_mac");</script>&nbsp;&nbsp;
						<a href="javascript:submit_do_add()"><img src="<?=$pic_path?>add1.gif" align="middle" border="0" OnMouseOver="this.src='<?=$pic_path?>add2.gif'"  OnMouseOut="this.src='<?=$pic_path?>add1.gif'"></a>
					</td>
				</tr>			

				<tr>
					<td colspan="2">
						<table width="100%" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
							<tr class="list_head" align="left">
								<td width="10">&nbsp;</td>
								<td width="70"><?=$m_id?></td>
								<td width="200"><?=$m_mac?></td>
								<td><?=$m_del?></td>	
							</tr>	
						</table>	
						<div class="div_tab">
							<table id="acl_tab" width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>						
<?
$key=0;
while($key < $mac_index)
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
	<form name="frm_mac" id="frm_mac" method="POST" action="upload_captival_acl._int" enctype="multipart/form-data" onsubmit="return checkmac();">
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
				<td  bgcolor="#cccccc" colspan="2" height="16"><b><?=$m_upload_mac_titles?></b></td>
				</tr>
				<tr>
					<td width="160" height="30"><?=$m_upload_mac_file?> :</td>
					<td>
					<input type="file" name="uploadmac" id="uploadmac" class="text" size="30">
					<input type="submit" name="mac_load"  value=" <?=$m_upload?> " onClick="return formCheckMac()">
					</td>
				</tr>

				<tr>
				<td  bgcolor="#cccccc" colspan="2" height="16"><b><?=$m_download_mac_title?></b></td>
				</tr>					
				<tr>
					<td width="160" height="30"><?=$m_save_mac?> :</td>
					<td><input type="button" value="<?=$m_save?>" onclick="save_mac()"></td>
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
