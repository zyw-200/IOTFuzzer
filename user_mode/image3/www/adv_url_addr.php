<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_url_addr";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_url_addr";
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
set("/runtime/web/next_page",$first_frame);
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
echo "<!--debug\n";
$cfg_band = query("/wlan/ch_mode");
if($cfg_band == 0) // 11g
{
	echo "anchor 11g";
	anchor("/wlan/inf:1");
}
else
{
	echo "anchor 11a";
	//anchor("/wlan/inf:2");
	anchor("/wlan/inf:1");
}
$cfg_auth = query("/wlan/inf:1/webredirect/auth/enable");
$cfg_url_enable = query("/wlan/inf:1/webredirect/url/enable");
$cfg_url_addr = query("/wlan/inf:1/webredirect/url");
$cfg_apmode = query("/wlan/inf:1/ap_mode");
$cfg_apmode_5g = query("/wlan/inf:2/ap_mode");
$cfg_url		= query("url_addr");
$list_row=0;
echo "-->";
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>
var r_list=[['index','name','passwd','status']
<?
for("/wlan/inf:1/webredirect/index")
{
	echo ",\n ['".$@."','".get("j","name")."','".queryEnc("passwd")."','".query("enable")."']";
	$list_row++;
}
?>
];
/* page init functoin */
var str_edit="<?=$m_edit?>";
var any_enabled=0;
function init()
{
	var f=get_obj("frm");
	if("<?=$cfg_url_enable?>" == 1)
	{
		f.url_enable.checked = true;
	}
	else
	{
		f.url_enable.checked = false;
	}
	if("<?=$cfg_auth?>" == 1)
	{
		f.auth_enable.checked = true;	
	}	
	else
	{
		f.auth_enable.checked = false;
	}
	if("<?=$cfg_apmode?>" == 1 || "<?=$cfg_apmode?>" == 4 || "<?=$cfg_apmode_5g?>" == 1 || "<?=$cfg_apmode_5g?>" == 4)
	{
		fields_disabled(f,true);	
	}
	else
	{
//		on_change_url();
		init_radio();
		on_change_auth();
	}
	AdjustHeight();
}

function on_change_auth()
{
	var f=get_obj("frm");
	if(f.auth_enable.checked)
	{
		fields_disabled(f,false);
		f.r_name.disabled = f.r_password.disabled = f.account_status.disabled = false;
		f.f_auth.value = 1;
	}
	else
	{
		fields_disabled(f,true);
		f.auth_enable.disabled = f.url_enable.disabled = false;
		f.r_name.disabled = f.r_password.disabled = f.account_status.disabled = true;
		f.f_auth.value = 0;
	}
	on_change_url();
}

function do_edit(id)
{
	var f=get_obj("frm");
	f.which_edit.value=id;
	f.r_name.value=r_list[id][1];
	f.r_password.value=r_list[id][2];
	f.account_status.value = r_list[id][3];
}

function do_del(id)
{
	var f=get_obj("frm");
	for(var s=1;s<r_list.length;s++)
	{
		if(r_list[s][3]==1 && id != s)
		{
			any_enabled++;
		}
	}
	if(any_enabled > 0)
	{	
	f.which_delete.value=id;
	f.action.value="delete";
	f.submit();
	}
	else
	{
		if("<?=$cfg_auth?>"==1)
		{
		alert("<?=$a_at_least_1_acc_enable?>");	
	}
		else
		{
			f.which_delete.value=id;
			f.action.value="delete";
			f.submit();	
		}
	}
}

function on_change_url()
{
	var f=get_obj("frm");
	if(f.url_enable.checked)
	{
		f.url_addr.disabled = false;
		f.f_url.value = 1;
	}
	else
	{
		f.url_addr.disabled = true;
		f.f_url.value = 0;
	}
}

function check()
{
	return true;
}

function check_auth()
{
	var f=get_obj("frm");
	if(f.auth_enable.checked)
	{
	if("<?=$list_row?>" >=256 && f.which_edit.value=="")
	{
		alert("<?=$a_max_account_number?>");
		return false;
	}
	if(f.r_name.value == "")
    {
	    alert("<?=$a_empty_user_name?>")
	    f.r_name.focus();
	    return false;
	}	
	if(is_blank_in_first_end(f.r_name.value))
    {
        alert("<?=$a_first_end_blank?>");
        f.r_name.select();
        return false;
    }
	if(strchk_unicode(f.r_name.value))
    {
        alert("<?=$a_invalid_name?>");
        f.r_name.select();
        return false;
    }
	if(f.which_edit.value=="")
	{
		//for(var s=1;s<n_list.length;s++)
		for(var s=1;s<r_list.length;s++)
		{
			//if(f.r_name.value==n_list[s])
			if(f.r_name.value==r_list[s][1])
			{
				alert("<?=$a_can_not_same_name?>");
				f.r_name.select();
				return false;
			}
			if(r_list[s][3]==1)
			{
				any_enabled++;
			}
		}
		if(f.account_status.value==1)
		{
			any_enabled++;
		}
	}
	else
		{
			//for(var s=1;s<n_list.length;s++)
			for(var s=1;s<r_list.length;s++)
			{
				//if(f.r_name.value==n_list[s] && f.which_edit.value!=s)
				if(f.r_name.value==r_list[s][1] && f.which_edit.value!=s)
				{
					alert("<?=$a_can_not_same_name?>");
					f.r_name.select();
					return false;
				}
			if(r_list[s][3]==1 && f.which_edit.value!=s)
			{
				any_enabled++;
			}
		}
		if(f.account_status.value==1)
		{
			any_enabled++;
			}
		}
	if(is_blank_in_first_end(f.r_password.value))
	{
		alert("<?=$a_first_end_blank?>");
		f.r_password.value="";
		f.r_password.select();
		return false;
	}
	if(strchk_unicode(f.r_password.value))
	{
		alert("<?=$a_invalid_password?>");
		f.r_password.select();
		return false;
	}	
	}
	return true;
}

function submit_do_add()
{
	var f=get_obj("frm");
	if(check_auth()==true)
	{
		if(f.which_edit.value=="")
		{
			f.action.value="add";
		}
		else
		{
			f.action.value="edit";
		}
		AdjustHeight();
		if(any_enabled > 0)
		{
		for(var s=1;s<r_list.length;s++)
		{
			get_obj("account_status_disabled"+s).disabled = true;
			get_obj("account_status_enabled"+s).disabled = true;
		}
		f.submit();
		}
		else
		{
			if(f.auth_enable.checked==true)
			{
			alert("<?=$a_at_least_1_acc_enable?>");				
		}
			else
			{
				f.submit();
			}
		}
		//return true;
	}
}

function check_url()
{
	var f=get_obj("frm");
	if(f.url_enable.checked)
    {
		if(f.url_addr.value == "")
		{
			alert("<?=$a_empty_url_addr?>");
			f.url_addr.select();
			return false;
		}
        if(strchk_url(f.url_addr.value)==false)
        {
            alert("<?=$a_invalid_url_addr?>");
            f.url_addr.select();
            return false;
        }
        for(var k=0; k < f.url_addr.value.length; k++)
        {
            if(f.url_addr.value.charAt(k) == '.' && f.url_addr.value.charAt(k+1) == '.')
            {
                alert("<?=$a_invalid_url_addr?>");
                f.url_addr.select();
                return false;
            }
        }
		if(f.url_addr.value.substring(0,4) == "http")
		{
			alert("<?=$a_invalid_http?>");
			f.url_addr.select();
			return false;
		}
    }
	return true;
}

function submit()
{
	var f=get_obj("frm");
	if(check_url() == true)
	{
		if(f.auth_enable.checked)
		{
			if(f.r_name.value == "" && f.r_password.value == "") 
			{
				if(r_list.length==1 && f.auth_enable.checked==true )
				{
					alert("<?=$a_at_least_1_acc_enable?>");	
				}
				else
				{
					for(var s=1;s<r_list.length;s++)
					{
				
						if(get_obj("account_status_enabled"+s).checked == true)
						{
							any_enabled++;
						}
					}
				
					if(any_enabled > 0 || f.auth_enable.checked==false)
					{
						f.action.value="save";
						f.r_name.disabled=true;
						f.r_password.disabled=true;
						f.which_delete.disabled=true;
						f.which_edit.disabled=true;
						f.submit();
					}
					else
					{
						alert("<?=$a_at_least_1_acc_enable?>");				
					}
				}
			}
			else 
				submit_do_add();
		}
		else
		{
			fields_disabled(f,false);
			f.submit();
		}
	}
}
function init_radio()
{
	var f=get_obj("frm");
	var i;
	select_index(f.account_status, "1");
	if("<?=$cfg_auth?>"==1)
	{
		f.auth_enable.checked=true;
	}
/*	if("<?=$cfg_apmode?>"==1 || "<?=$cfg_apmode?>"==4 || "<?=$cfg_apmode_5g?>"==1 || "<?=$cfg_apmode_5g?>"==4)
	{
		f.auth_enable.disabled=true;
	}*/
	for(i=1;i<= "<?=$list_row?>";i++)
	{
		if (r_list[i][3]==1)
		{
			get_obj("account_status_enabled"+i).checked = true;
		}
		else
		{
			get_obj("account_status_disabled"+i).checked = true;
		}
	}
<?
/*	for("/wlan/inf:1/wpa/embradius/eap_user/index")
	{
		echo" if (". query(enable). "==1)\n f.account_status".$@ ."[0].checked = true; \n else f.account_status".$@ ."[1].checked = true;\n";	
	}*/
?>
}
function page_table(index)
{
	var str="";

//	for(index=1;index<="<?=$list_row?>";index++)
	//{
		if(index%2==1)
		{
			str+="<tr style='background:#CCCCCC;'>";
		}
		else
		{
			str+="<tr style='background:#B3B3B3;'>";
		}
			str+="<td width='250' align='left' class='edit'><a href='javascript:do_edit(\""+index+"\")'>"+genTablelength(r_list[index][1]+str_edit,35)+"</td>";
			str+="<td width='18%' align='center'><input type='radio' id='account_status_enabled"+index+"' name='a_s"+index+"' value='1'> </td>";
			str+= "<td width='18%' align='center'><input type='radio' id='account_status_disabled"+index+"' name='a_s"+index+"' value='0'> </td>";
			str+= "<td align='center'><a href='javascript:do_del(\""+index+"\")'><img src='/pic/delete.jpg' border=0></a></td>";
			str+= "</tr>\n";
			document.write(str);
		//	str="";
	//}
//	document.write(str);
}
</script>

<body <?=$G_BODY_ATTR?> onload="init();">

<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return false;">
<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<input type="hidden" name="which_delete" value="">
<input type="hidden" name="which_edit" value="">
<input type="hidden" name="f_url" value="">
<input type="hidden" name="f_auth" value="">
<input type="hidden" name="action" value="">
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
			<tr>
				<td><?=$G_TAG_SCRIPT_START?>genCheckBox("url_enable","on_change_url()");<?=$G_TAG_SCRIPT_END?>
				<?=$m_enable_url?></td>
			</tr>
			<tr>
				<td><?=$m_url_addr?>
				<input type="text" name="url_addr" id="url_addr" value="<?=$cfg_url_addr?>"></td>
			</tr>
      		<tr>
      			<td><?=$G_TAG_SCRIPT_START?>genCheckBox("auth_enable","on_change_auth()");<?=$G_TAG_SCRIPT_END?>
				<?=$m_enable_auth?></td>
      		</tr>
      		<tr>
      			<td colspan="2">
      			<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
      				<tr>
      					<td class="table_tool_td" valign="middle" colspan="2"><b><?=$m_account_title ?></b></td>
      				</tr>
      				<tr>
      					<td><?=$m_name?></td>
      					<td><input type="text" width="20%" maxlength="64" size="30" id="r_name" name="r_name" class="text" value=""></td>
      				</tr>
      				<tr>
      					<td><?=$m_password?></td>
      					<td><input type="password" width="20%" maxlength="64" size="30" id="r_password" name="r_password" class="text" value=""></td>
      				</tr>
      				<tr>
      					<td><?=$m_status?></td>
      					<td><?=$G_TAG_SCRIPT_START?>genSelect("account_status", [0,1], ["<?=$m_disable?>","<?=$m_enable?>"],"");<?=$G_TAG_SCRIPT_END?>
						</td>
      				</tr>
      				<!--tr>					
      					<td colspan="2">
      						<?=$G_ADD_BUTTON?>
      					</td>
      				</tr-->
      			</table>
      		</td>
      	</tr>
      	
      	<tr>
      		<td colspan="2">
      			<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
      				<tr>
      					<td class="table_tool_td" valign="middle" colspan="2"><b><?=$m_list_title ?></b></td>
      				</tr>
      				<tr>
      					<td colspan="2">
      						<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;">
      							<tr class="list_head" align="left">
      								<td width="50%"><?=$m_name ?></td>
      								<!--td width="12%" align="center"><?=$m_edit ?></td-->
									<td width="18%" align="center"><?=$m_enable?></td>
      								<td width="18%" align="center"><?=$m_disable?></td>
									<td width="10%" align="center"><?=$m_delete?></td>
									<td width="4%">&nbsp;</td>
      							</tr>
      						</table>
   						
							<div class="div_radius_tab" >
      							<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;">
      							
<?
$key=0;
/*modify for data passing will too many web page can't display  2009-3-9 victor */
while($key< $list_row)
{
	$key++;
	echo $G_TAG_SCRIPT_START."page_table(".$key.");".$G_TAG_SCRIPT_END;
	//$list_name=query("name");
/*	if($key%2==1)
	{
		echo "<tr style='background:#CCCCCC;'>\n";
	}
	else
	{
		echo "<tr style='background:#B3B3B3;'>\n";
	}
	echo "<td width='250' align='left' class='edit'><a href='javascript:do_edit(\"".$@."\")'>".$G_TAG_SCRIPT_START."genTablelength(r_list[".$key."][1]+str_edit,35);".$G_TAG_SCRIPT_END."</td>\n";
	//echo "<td width=10%><a href='javascript:do_edit(\"".$@."\")'><img src='/pic/edit.jpg' border=0></a></td>\n";
	/*echo "<td width='18%' align='center'><input type='radio' id='account_status_enabled".$key."' name='account_status".$key."' value='1'> </td>\n";
	echo "<td width='18%' align='center'><input type='radio' id='account_status_disabled".$key."' name='account_status".$key."' value='0'> </td>\n";*/
/*	echo $G_TAG_SCRIPT_START."page_table();".$G_TAG_SCRIPT_END;
	echo "<td align='center'><a href='javascript:do_del(\"".$key."\")'><img src='/pic/delete.jpg' border=0></a></td>\n";
	echo "</tr>\n";*/
}
?>      							
      							</table>
      						</div>
						</td>
					</tr>
				</table>
      		</td>
      	</tr>
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
</form>
</body>
</html>	
