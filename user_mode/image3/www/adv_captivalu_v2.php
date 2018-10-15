<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_captivalu_v2";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_captivalu_v2";
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
$style_index = 0;
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
echo "<!--debug\n";
set("/runtime/web/sub_capu_v2","");
$check_band = query("/wlan/inf:2/ap_mode");
$switch = query("/runtime/web/display/switchable");
if($band_reload == 0 || $band_reload == 1){$cfg_bandddd = $band_reload;}
else{$cfg_band = query("/wlan/ch_mode");}

$cfg_left = query("/captival/mtd/tar/remains");
if($switch == 1)
{
	$cfg_pri_style = query("/captival/ssid:1/webdir");
	$cfg_s1_style = query("/captival/ssid:2/webdir");
	$cfg_s2_style = query("/captival/ssid:3/webdir");
	$cfg_s3_style = query("/captival/ssid:4/webdir");
	$cfg_s4_style = query("/captival/ssid:5/webdir");
	$cfg_s5_style = query("/captival/ssid:6/webdir");
	$cfg_s6_style = query("/captival/ssid:7/webdir");
	$cfg_s7_style = query("/captival/ssid:8/webdir");
}
else
{
	if($cfg_band == 0)
	{
		$cfg_pri_style = query("/captival/ssid:1/webdir");
		$cfg_s1_style = query("/captival/ssid:2/webdir");
		$cfg_s2_style = query("/captival/ssid:3/webdir");
		$cfg_s3_style = query("/captival/ssid:4/webdir");
		$cfg_s4_style = query("/captival/ssid:5/webdir");
		$cfg_s5_style = query("/captival/ssid:6/webdir");
		$cfg_s6_style = query("/captival/ssid:7/webdir");
		$cfg_s7_style = query("/captival/ssid:8/webdir");
	}
	else
	{
		$cfg_pri_style = query("/captival/ssid:9/webdir");
		$cfg_s1_style = query("/captival/ssid:10/webdir");
		$cfg_s2_style = query("/captival/ssid:11/webdir");
		$cfg_s3_style = query("/captival/ssid:12/webdir");
		$cfg_s4_style = query("/captival/ssid:13/webdir");
		$cfg_s5_style = query("/captival/ssid:14/webdir");
		$cfg_s6_style = query("/captival/ssid:15/webdir");
		$cfg_s7_style = query("/captival/ssid:16/webdir");
	}
}

if($switch == 1){$tmp_band = 1;}
else{$tmp_band = $cfg_band + 1;}
$cfg_m1 = query("/wlan/inf:".$tmp_band."/multi/index:1/state");
$cfg_m2 = query("/wlan/inf:".$tmp_band."/multi/index:2/state");
$cfg_m3 = query("/wlan/inf:".$tmp_band."/multi/index:3/state");
$cfg_m4 = query("/wlan/inf:".$tmp_band."/multi/index:4/state");
$cfg_m5 = query("/wlan/inf:".$tmp_band."/multi/index:5/state");
$cfg_m6 = query("/wlan/inf:".$tmp_band."/multi/index:6/state");
$cfg_m7 = query("/wlan/inf:".$tmp_band."/multi/index:7/state");

if($cfg_pri_style == ""){$cfg_pri_style = $m_pages_default_st;}
if($cfg_s1_style == ""){$cfg_s1_style = $m_pages_default_st;}
if($cfg_s2_style == ""){$cfg_s2_style = $m_pages_default_st;}
if($cfg_s3_style == ""){$cfg_s3_style = $m_pages_default_st;}
if($cfg_s4_style == ""){$cfg_s4_style = $m_pages_default_st;}
if($cfg_s5_style == ""){$cfg_s5_style = $m_pages_default_st;}
if($cfg_s6_style == ""){$cfg_s6_style = $m_pages_default_st;}
if($cfg_s7_style == ""){$cfg_s7_style = $m_pages_default_st;}


echo "-->";
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>

var style_list=[['index','name']
<?
for("/captival/mtd/tar/index")
{
    echo ",\n ['".$@."','".query("name")."']";
    $style_index++;
}
$list_num = $style_index+3;
$style_index_plus = $style_index + 1;
?>];

function init()
{
	var f=get_obj("frm");
	if("<?=$check_band?>" == "")
		f.band.disabled = true;
	f.band.value = "<?=$cfg_band?>";
	f.pri.value = "<?=$cfg_pri_style?>";
	f.s_1.value = "<?=$cfg_s1_style?>";
	f.s_2.value = "<?=$cfg_s2_style?>";
	f.s_3.value = "<?=$cfg_s3_style?>";
	f.s_4.value = "<?=$cfg_s4_style?>";
	f.s_5.value = "<?=$cfg_s5_style?>";
	f.s_6.value = "<?=$cfg_s6_style?>";
	f.s_7.value = "<?=$cfg_s7_style?>";

	if("<?=$cfg_m1?>" != 1){for(var i=0;i<"<?=$list_num?>";i++) {f.s_1[i].disabled = true;}}
	if("<?=$cfg_m2?>" != 1){for(var i=0;i<"<?=$list_num?>";i++) {f.s_2[i].disabled = true;}}
	if("<?=$cfg_m3?>" != 1){for(var i=0;i<"<?=$list_num?>";i++) {f.s_3[i].disabled = true;}}
	if("<?=$cfg_m4?>" != 1){for(var i=0;i<"<?=$list_num?>";i++) {f.s_4[i].disabled = true;}}
	if("<?=$cfg_m5?>" != 1){for(var i=0;i<"<?=$list_num?>";i++) {f.s_5[i].disabled = true;}}
	if("<?=$cfg_m6?>" != 1){for(var i=0;i<"<?=$list_num?>";i++) {f.s_6[i].disabled = true;}}
	if("<?=$cfg_m7?>" != 1){for(var i=0;i<"<?=$list_num?>";i++) {f.s_7[i].disabled = true;}}

	AdjustHeight();
}

function on_change_band()
{
	var f = get_obj("frm");
	self.location.href = "adv_captivalu_v2.php?band_reload=" + f.band.value;
}

function page_table(index)
{
	var f=get_obj("frm");
	var str="";
	var xml_index = index-3;
	if(index%2 == 1)
	{
		str+= "<tr align='left' style='background:#cccccc;'>\n";
	}
	else
	{
		str+= "<tr align='left' style='background:#b3b3b3;'>\n";
	}
	str+= "<td width='5%' align='center'>"+index+"</td>\n";

	if(index == 1)
	{
		str+= "<td width='30%'>"+"<?=$m_pages_default?>"+"</td>\n";
		var name="<?=$m_pages_default_st?>";
	}
	else if(index == 2)
	{
		str+= "<td width='30%'>"+"<?=$m_pages_headerpic?>"+"</td>\n";
		var name="<?=$m_pages_headerpic_st?>";
	}
	else if(index == 3)
	{
		str+= "<td width='30%'>"+"<?=$m_pages_license?>"+"</td>\n";
		var name="<?=$m_pages_license_st?>";
	}
	else
	{
		str+= "<td width='30%' style='word-wrap:break-word; word-break:break-all;'>"+style_list[xml_index][1]+"</td>\n";
		var name = style_list[xml_index][1].substring(0, style_list[xml_index][1].length - 4);
	}

	var checked1 = checked2 = checked3 = checked4 = checked5 = checked6 = checked7 = checked8 = "";
	if("<?=$cfg_pri_style?>" == name){checked1 = "checked";}
	if("<?=$cfg_s1_style?>" == name){checked2 = "checked";}
	if("<?=$cfg_s2_style?>" == name){checked3 = "checked";}
	if("<?=$cfg_s3_style?>" == name){checked4 = "checked";}
	if("<?=$cfg_s4_style?>" == name){checked5 = "checked";}
	if("<?=$cfg_s5_style?>" == name){checked6 = "checked";}
	if("<?=$cfg_s6_style?>" == name){checked7 = "checked";}
	if("<?=$cfg_s7_style?>" == name){checked8 = "checked";}
	
	str+= "<td width='6%' align='center'><input type='radio' id='pri' name='pri' value='"+name+"' "+checked1+"></td>\n";
	str+= "<td width='6%' align='center'><input type='radio' id='s_1' name='s_1' value='"+name+"' "+checked2+"></td>\n";	
	str+= "<td width='6%' align='center'><input type='radio' id='s_2' name='s_2' value='"+name+"' "+checked3+"></td>\n";
	str+= "<td width='6%' align='center'><input type='radio' id='s_3' name='s_3' value='"+name+"' "+checked4+"></td>\n";
	str+= "<td width='6%' align='center'><input type='radio' id='s_4' name='s_4' value='"+name+"' "+checked5+"></td>\n";
	str+= "<td width='6%' align='center'><input type='radio' id='s_5' name='s_5' value='"+name+"' "+checked6+"></td>\n";
	str+= "<td width='6%' align='center'><input type='radio' id='s_6' name='s_6' value='"+name+"' "+checked7+"></td>\n";
	str+= "<td width='6%' align='center'><input type='radio' id='s_7' name='s_7' value='"+name+"' "+checked8+"></td>\n";

	str+= "<td width='10%' align='center'><a href='javascript:do_download(\""+index+"\")'><img src='/pic/download.jpg' border=0></a></td>\n";
	if(index > 3)
		str+= "<td width='8%' align='center'><a href='javascript:do_del(\""+xml_index+"\")'><img src='/pic/delete.jpg' border=0></a></td>\n";
	else
		str+= "<td width='8%'></td>\n";
	str+= "</tr>\n";
	document.write(str);
}

function do_download(id)
{
	self.location.href="../"+id+"_captival_tar.bin";
}

function do_del(id)
{
	var f=get_obj("frm");
	if(confirm("<?=$a_del_confirm?>")==true)
	{
		f.f_action.value = "del";
		f.which_del.value = id;
		f.submit();
	}
}

function check()
{
	var f=get_obj("frm");
	f.f_action.value = "save";
	fields_disabled(f, false);
	return true;
}

function submit()
{
	if(check()) get_obj("frm").submit();
}

function formCheckStyle()
{
	var f_style=get_obj("frm_upload");
	if(f_style.cap_style.value.slice(-4)!=".tar" && f_style.cap_style.value.slice(-4)!=".TAR" )
	{
		alert("<?=$a_invalid_style?>");
		return false;
	}

	var num=0;
	for(var m=f_style.cap_style.value.length-1;m>=0;m--)
	{
		if(f_style.cap_style.value.charCodeAt(m) == "92"){break;}
	}
	var up_name = f_style.cap_style.value.substring(m+1, f_style.cap_style.value.length);

	if(up_name == "pages_default.tar" || up_name == "pages_headerpic.tar" || up_name == "pages_lisence.tar")
	{
		alert("<?=$a_same_rule?>");
		return false;
	}
	for(var n=1;n<"<?=$style_index_plus?>";n++)
	{
		if(up_name == style_list[n][1])
		{
			alert("<?=$a_same_rule?>");
			return false;
		}
	}
}
function checkstyle()
{
<?
set("/runtime/web/next_page",$MY_NAME);
?>
}
</script>
<body <?=$G_BODY_ATTR?> onload="init();">

<table id="table_frame" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
	<tr>
		<td valign="top">
			<table id="table_header" <?=$G_TABLE_ATTR_CELL_ZERO?>>
				<tr>
					<td id="td_header" valign="middle"><?=$m_context_title?></td>
				</tr>
			</table>
<!-- ________________________________ Main Content Start ______________________________ -->
	<table id="table_set_main" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
	<form id="frm_upload" name="frm_upload" method="POST" action="upload_captival_tar._int" enctype="multipart/form-data" onsubmit="return checkstyle();">
	<input type="hidden" name="upload_name" value="">
		<tr><td height="10"></td></tr>
		<tr><td bgcolor="#cccccc" colspan="2" height="16"><b><?=$m_upload_style_titles?></b></td></tr>
		<tr>
			<td width="160" height="30"><?=$m_upload_style_file?> :</td>
			<td>
				<input type="file" name="cap_style" id="cap_style" class="text" size="30">
				<input type="submit" name="style_load"  value=" <?=$m_upload?> " onClick="return formCheckStyle()">
			</td>
		</tr>
		<tr>
			<td width="160" height="30"><?=$m_left?></td>
			<td><?=$cfg_left?><?=$m_byte?></td>
		</tr>
	</form>
	<form id="frm" name="frm" method="POST" action="<?=$MY_NAME?>.php" onsubmit="return false;">
	<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
	<input type="hidden" name="f_action" value="">
	<input type="hidden" name="which_del" value="">
	<input type="hidden" name="which_download" value="">
		<tr><td height="10"></td></tr>
		<tr><td bgcolor="#cccccc" colspan="2" height="16"><b><?=$m_style_list?></b></td></tr>
		<tr>
			<td width="30%" id="td_left"><?=$m_band?></td>
			<td><?=$G_TAG_SCRIPT_START?>genSelect("band", [0,1], ["<?=$m_2_4g?>","<?=$m_5g?>"], "on_change_band()");<?=$G_TAG_SCRIPT_END?></td>
		</tr>
		<tr>
			<td colspan="2">
				<table width="100%" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
					<tr class="list_head" align="left">
						<td width="5%" align='center'><?=$m_id?></td>
						<td width="30%"><?=$m_name?></td>
						<td width="6%" align='center'><?=$m_pri?></td>
						<td width="6%" align='center'><?=$m_s1?></td>
						<td width="6%" align='center'><?=$m_s2?></td>
						<td width="6%" align='center'><?=$m_s3?></td>
						<td width="6%" align='center'><?=$m_s4?></td>
						<td width="6%" align='center'><?=$m_s5?></td>
						<td width="6%" align='center'><?=$m_s6?></td>
						<td width="6%" align='center'><?=$m_s7?></td>
						<td width="10%" align='center'><?=$m_download?></td>
						<td width="8%" align='center'><?=$m_del?></td>
					</tr>
				</table>
				<div class="div_tab_scan">
				<table id="acl_tab" width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
<?
$key=0;
$total_index = $style_index + 3;
while($key < $total_index)
{
	$key++;
	echo $G_TAG_SCRIPT_START."page_table(".$key.");".$G_TAG_SCRIPT_END."\n";
}
?>
				</table>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"><?=$G_APPLY_BUTTON?></td>
		</tr>
	</form>
	</table>
<!-- ________________________________  Main Content End _______________________________ -->
		</td>
	</tr>
</table>
</body>
</html>

