<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_radiusserver";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_radiusserver";
set("/runtime/web/help_page",$MY_NAME);
/* --------------------------------------------------------------------------- */
if($ACTION_POST=="adv_radiusserver")
{
	require("/www/model/__admin_check.php");
	require("/www/__action_adv.php");
	$ACTION_POST = "";
	exit;  	
}


/* --------------------------------------------------------------------------- */
require("/www/model/__html_head.php");
require("/www/comm/__js_ip.php");

/* --------------------------------------------------------------------------- */
$list_row=0;
$cfg_ipv6 = query("/inet/entry:1/ipv6/valid");
$cfg_nap = query("/sys/vlan_mode");
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>
var r_list=[['index','name','passwd','status']
<?
for("/wlan/inf:1/wpa/embradius/eap_user/index")
{
	echo ",\n ['".$@."','".get("j","name")."','".queryEnc("passwd")."','".query("enable")."']";
	$list_row++;
}
?>
];
//var n_list=['name'
<?
/*for("/wlan/inf:1/wpa/embradius/eap_user/index")
{
	echo ",'".get("j","name")."'";
}*/
?>
//];
var str_edit="<?=$m_edit?>";
function init()
{
	var f=get_obj("frm");
	init_radio();
/*	if("<?=$cfg_nap?>" == "1")
    {
        f.r_name.disabled=f.r_password.disabled=f.account_status.disabled=true;
        for(var s=1;s<r_list.length;s++)
        {
            get_obj("account_status_disabled"+s).disabled = true;
            get_obj("account_status_enabled"+s).disabled = true;
        }
    }*/
	AdjustHeight();
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
	f.which_delete.value=id;
	f.action.value="delete";
	f.submit();
	return true;
}

function check()
{
	return true;
}

function check_values()
{
	var f=get_obj("frm");
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
	if(strchk_radiusname(f.r_name.value) == false)
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
			}
		}
	if(is_blank_in_first_end(f.r_password.value))
	{
		alert("<?=$a_first_end_blank?>");
		f.r_password.value="";
		f.r_password.select();
		return false;
	}
						
	if(f.r_password.value.length <8 || f.r_password.value.length > 64)
	{
		alert("<?=$a_invalid_password_len?>");
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

	return true;
}

function submit_do_add()
{
	var f=get_obj("frm");
	if(check_values()==true)
	{
		if(f.which_edit.value=="")
		{
			f.action.value="add";
		}
		else
		{
			f.action.value="edit";
		}
		for(var s=1;s<r_list.length;s++)
		{
			get_obj("account_status_disabled"+s).disabled = true;
			get_obj("account_status_enabled"+s).disabled = true;
		}
		AdjustHeight();
		f.submit();
		return true;
	}
}
function submit()
{
	var f=get_obj("frm");
	if(f.r_name.value == "" && f.r_password.value == "") 
	{
		if("<?=$list_row?>" == 0)
		{
			alert("<?=$a_empty_user_name?>");
			f.r_name.select();
		}
		else
		{
			f.action.value="save";
			f.r_name.disabled=true;
			f.r_password.disabled=true;
			f.which_delete.disabled=true;
			f.which_edit.disabled=true;

			f.submit();
			return true;
		}
	}
	else 
	submit_do_add();
}
function init_radio()
{
	var f=get_obj("frm");
	var i;
	select_index(f.account_status, "1");
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
