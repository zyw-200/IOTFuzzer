<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_dhcpl";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_dhcpl";
set("/runtime/web/help_page",$MY_NAME);
/* --------------------------------------------------------------------------- */
if($ACTION_POST != "")
{
	require("/www/model/__admin_check.php");
	require("/www/__action.php");
	$ACTION_POST = "";
	exit;
}

/* --------------------------------------------------------------------------- */
require("/www/model/__html_head.php");
set("/runtime/web/next_page",$first_frame);
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.

/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>

function print_daytime(str_second)
{
	var time = second_to_daytime(str_second);
	var str =	(time[0]>0 ? time[0]+" <?=$m_days?> " : "") +
				(time[1]>0 ? time[1]+" <?=$m_hrs?> " : "") +
				(time[2]>0 ? time[2]+" <?=$m_mins?> " : "") +
				(time[3]>0 ? time[3]+" <?=$m_secs?> " : "");
	if(str == "")
	{
		str="<?=$m_timeout?>";
	}
	document.write(str);
}

/* page init functoin */
function init()
{
	var f=get_obj("frm");
	var str="adv_dhcpl.xgi?";
<?
	$refresh_count = query("/runtime/stats/web/adv_dhcpl/refresh_count");
	$refresh_flag = query("/runtime/stats/web/adv_dhcpl/refresh_flag");
	if($refresh_count=="")
		{$refresh_count ="0";}
	if($refresh_flag=="")
		{$refresh_flag ="2";}
	if($AUTH_GROUP=="0")
	{
		echo "str+=exe_str(\"submit DHCP_COMPARE\");\n";
		$refresh_count = $refresh_count+1;

		set("/runtime/stats/web/adv_dhcpl/refresh_count",$refresh_count);

		if($refresh_flag > $refresh_count)
		{
			echo "self.location.href=str";
		}
		else	
		{
			$refresh_flag = $refresh_count+2;
			set("/runtime/stats/web/adv_dhcpl/refresh_flag",$refresh_flag);
		}
	}	
?>	
	AdjustHeight();
}

/* parameter checking */
function check()
{
	var f = get_obj("frm");

	return true;
}

function submit()
{
	if(check()) get_obj("frm").submit();
}

</script>

<body <?=$G_BODY_ATTR?> onload="init();">

<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return check();">

<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
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
				<td>
					<table border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> style="padding: 0px 10px 0px 0px;">
						<tr>
							<td colspan="2">
								<table width="100%" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
									<tr>
										<td bgcolor="#cccccc" colspan="4" height="16" width="100%">
											<b>&nbsp;&nbsp;<?=$m_dynamic_pools?></b>
										</td>
									</tr>								
									<tr class="list_head" align="left">
										<td width="100">
											&nbsp;&nbsp;<?=$m_host_name?>
										</td>		
										<td width="200">
											<?=$m_mac?>
										</td>
										<td width="200">
											<?=$m_ip?>
										</td>
										<td width="100">
											<?=$m_time?>
										</td>																																																													
									</tr>									
								</table>
							<div class="div_client_tab">
									<table id="client_tab" width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>						
<?
$tmp = "";
$flag=0;
for("/runtime/dhcpserver/lease")
{	
	if(query("up")==1 && query("expire")!=0)
	{
		$tmp = $flag%2;
		if($tmp == 1)
		{
			echo "<tr align=\"left\" style=\"background:#cccccc;\">\n";
		}
		else
		{
			echo "<tr align=\"left\" style=\"background:#b3b3b3;\">\n";
		}
	echo "<td width=\"100\">".$G_TAG_SCRIPT_START."genTableSSID(\"".get(j,"hostname")."\",\"0\");".$G_TAG_SCRIPT_END."</td>\n";
		echo "<td width=\"200\">".query("mac")."</td>\n";
		echo "<td width=\"200\">".query("ip")."</td>\n";
		echo "<td width=\"130\"><script>print_daytime(\"".query("expire")."\");</script></td>\n";
		echo "</tr>\n";		
		$flag++;
	}		
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
				<td height="30">
				&nbsp;
				</td>
			</tr>				
			<tr>
				<td>
					<table border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> style="padding: 0px 10px 0px 0px;">
						<tr>
							<td colspan="2">
								<table width="100%" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
									<tr>
										<td bgcolor="#cccccc" colspan="4" height="16" width="100%">
											<b>&nbsp;&nbsp;<?=$m_static_pools?></b>
										</td>
									</tr>									
									<tr class="list_head" align="left">
										<td width="100">
											&nbsp;&nbsp;<?=$m_host_name?>
										</td>		
										<td width="200">
											<?=$m_mac?>
										</td>
										<td width="200">
											<?=$m_ip?>
										</td>																																																												
										<td width="100">
										</td>																																																												
									</tr>									
								</table>
							<div class="div_client_tab">
									<table id="client_tab" width="100%" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>						
<?
$tmp = "";
$flag=0;
for("/lan/dhcp/server/pool:1/staticdhcp/entry")
{	
	if(query("up")==1)
	{
		$tmp = $flag%2;
		if($tmp == 1)
		{
			echo "<tr align=\"left\" style=\"background:#cccccc;\">\n";
		}
		else
		{
			echo "<tr align=\"left\" style=\"background:#b3b3b3;\">\n";
		}
		echo "<td width=\"100\">".$G_TAG_SCRIPT_START."genTableSSID(\"".get(j,"hostname")."\",\"0\");".$G_TAG_SCRIPT_END."</td>\n";
		echo "<td width=\"200\">".query("mac")."</td>\n";
		echo "<td width=\"200\">".query("ip")."</td>\n";
		echo "<td width=\"100\"></td>\n";
		echo "</tr>\n";	
		$flag++;	
	}		
}
?>
									</table>
								</div>		
			
							</td>	
						</tr>				
					</table>
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
