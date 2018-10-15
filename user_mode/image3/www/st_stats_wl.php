<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "st_stats_wl";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "st_stats_wl";
set("/runtime/web/help_page",$MY_NAME);
/* --------------------------------------------------------------------------- */

/* --------------------------------------------------------------------------- */
require("/www/model/__html_head.php");
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
$check_band = query("/wlan/inf:2/ap_mode");
if($check_clear == "")
{
	$check_clear = 0;	
}
set("/runtime/stats/wireless/statistic/clear",$check_clear);

$cfg_T_Packet_g=query("/runtime/stats/wireless/inf:1/showtx/packets");
$cfg_T_Bytes_g=query("/runtime/stats/wireless/inf:1/showtx/bytes");
$cfg_T_Dropped_Packet_g=query("/runtime/stats/wireless/inf:1/showtx/drop");
$cfg_T_Retry_g=query("/runtime/stats/wireless/inf:1/showtx/retry");
$cfg_R_Packet_g=query("/runtime/stats/wireless/inf:1/showrx/packets");
$cfg_R_Bytes_g=query("/runtime/stats/wireless/inf:1/showrx/bytes");
$cfg_R_Dropped_Packet_g=query("/runtime/stats/wireless/inf:1/showrx/drop");
$cfg_R_CRC_g=query("/runtime/stats/wireless/inf:1/showrx/crcerr");
$cfg_R_Decryption_Error_g=query("/runtime/stats/wireless/inf:1/showrx/crypterr");
$cfg_R_MIC_Error_g=query("/runtime/stats/wireless/inf:1/showrx/micerr");
$cfg_R_PHY_Error_g=query("/runtime/stats/wireless/inf:1/showrx/phyerr");

$cfg_T_Packet_a=query("/runtime/stats/wireless/inf:2/showtx/packets");
$cfg_T_Bytes_a=query("/runtime/stats/wireless/inf:2/showtx/bytes");
$cfg_T_Dropped_Packet_a=query("/runtime/stats/wireless/inf:2/showtx/drop");
$cfg_T_Retry_a=query("/runtime/stats/wireless/inf:2/showtx/retry");
$cfg_R_Packet_a=query("/runtime/stats/wireless/inf:2/showrx/packets");
$cfg_R_Bytes_a=query("/runtime/stats/wireless/inf:2/showrx/bytes");
$cfg_R_Dropped_Packet_a=query("/runtime/stats/wireless/inf:2/showrx/drop");
$cfg_R_CRC_a=query("/runtime/stats/wireless/inf:2/showrx/crcerr");
$cfg_R_Decryption_Error_a=query("/runtime/stats/wireless/inf:2/showrx/crypterr");
$cfg_R_MIC_Error_a=query("/runtime/stats/wireless/inf:2/showrx/micerr");
$cfg_R_PHY_Error_a=query("/runtime/stats/wireless/inf:2/showrx/phyerr");
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>
/* page init functoin */
function init()
{
    var f = get_obj("frm");
	get_obj("single_band").style.display = "none";
	get_obj("two_bands").style.display = "none";
	if("<?=$check_band?>" == "")
	{
		get_obj("single_band").style.display = "";
	}
	else
	{
		get_obj("two_bands").style.display = "";
	}
   	var str="";
   	str+="st_stats_wl.xgi?"+generate_random_str();
    <?
   	$st_flag=query("/runtime/stats/wireless/statisitc/flag");
	$refresh_count = query("/runtime/web/refresh_count");
	$refresh_flag = query("/runtime/web/refresh_flag");
	if($refresh_count=="")
		{$refresh_count ="0";}
	if($refresh_flag=="")
		{$refresh_flag ="2";}
	if($st_flag=="")
		{$st_flag="1";}
	if($st_flag=="1")
	{	
	if($AUTH_GROUP=="0")
	{
		
		echo "str+=exe_str(\"submit ST_ATH_STATS\");\n";
		echo "str+=exe_str(\"submit ST_PACK_REFRESH;submit SLEEP 1\");\n";
		if($check_clear == 1)
		{		
			echo "self.location.href=str;\n";
		}	
		
		$refresh_count = $refresh_count+1;
		set("/runtime/web/refresh_count",$refresh_count);

		if($refresh_flag > $refresh_count)
		{
				echo "f.refresh.disabled = true;\n";
				echo "f.clear.disabled = true;\n";
			echo "self.location.href=str;\n";
		}			
		else 	
		{
				echo "f.refresh.disabled = false;\n";
				echo "f.clear.disabled = false;\n";
			$refresh_flag = $refresh_count+2;
			set("/runtime/web/refresh_flag",$refresh_flag);			
		}

	}	
	}
	else
	{
		echo "do_refresh();";
	}
	?>
	AdjustHeight();	
}
/* parameter checking */
function do_refresh()
{
	var f = get_obj("frm");
	f.refresh.disabled = true;
	f.clear.disabled= true;
	self.location.href="<?=$MY_MSG_FILE?>";
}
/* cancel function */
function do_clear()
{
	var f = get_obj("frm");
	f.refresh.disabled = true;
	f.clear.disabled= true;
	self.location.href = "st_stats_wl.php?random="+generate_random_str()+"&check_clear=1";
}

<?=$G_TAG_SCRIPT_END?>
<body <?=$G_BODY_ATTR?> onload="init();">
<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php">
<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<!-- ________________________________ Main Content Start ______________________________ -->
<table id="table_frame" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
	<tr>
		<td valign="top" align="center">
			<table id="table_header" <?=$G_TABLE_ATTR_CELL_ZERO?>>
			<tr>
				<td id="td_header" valign="middle"><?=$msg_title?></td>
			</tr>
			</table>
			<div id="single_band">
			<table width="98%"  border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
				<tr>
					<td  colspan="2" height="16" align="right">
						<input type="button" name="clear" value="<?=$msg_clear?>" onClick="do_clear();">
						<input type="button" name="refresh" value="<?=$msg_refresh?>" onClick="do_refresh();">
					</td>
				</tr>
				<tr>
					<td bgcolor="#cccccc" colspan="2" height="16"><b><?=$msg_t_title?></b></td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_t_msg1?>
					</td>
					<td id="td_right"><?=$cfg_T_Packet_g?>
					</td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_t_msg2?>
					</td>
					<td id="td_right">
						<?=$cfg_T_Bytes_g?>
					</td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_t_msg3?>
					</td>
					<td id="td_right">
						<?=$cfg_T_Dropped_Packet_g?>
					</td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_t_msg4?>
					</td>
					<td id="td_right">
						<?=$cfg_T_Retry_g?>
					</td>
				</tr>
				<tr>
					<td bgcolor="#cccccc" colspan="2" height="16"><b><?=$msg_r_title?></b></td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_r_msg1?>
					</td>
					<td id="td_right">
						<?=$cfg_R_Packet_g?>
					</td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_r_msg2?>
					</td>
					<td id="td_right">
						<?=$cfg_R_Bytes_g?>
					</td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_r_msg3?>
					</td>
					<td id="td_right">
						<?=$cfg_R_Dropped_Packet_g?>
					</td>
				</tr>
<? if(query("/runtime/web/display/stat_wl") !="1")	{echo "<!--";} ?>				
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_r_msg4?>
					</td>
					<td id="td_right">
						<?map("/runtime/stats/wireless/showrx/crcerr","",0);?>
					</td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_r_msg5?>
					</td>
					<td id="td_right">
						<?=$cfg_R_Decryption_Error_g?>
					</td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_r_msg6?>
					</td>
					<td id="td_right">
						<?=$cfg_R_MIC_Error_g?>
					</td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_r_msg7?>
					</td>
					<td id="td_right">
						<?=$cfg_R_PHY_Error_g?>
					</td>
				</tr>
<? if(query("/runtime/web/display/stat_wl") !="1")	{echo "-->";} ?>					
			</table>				
			</div>

			<div id="two_bands">
			<table width="98%"  border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
				<tr>
					<td  colspan="3" height="16" align="right">
						<input type="button" name="clear" value="<?=$msg_clear?>" onClick="do_clear();">
						<input type="button" name="refresh" value="<?=$msg_refresh?>" onClick="do_refresh();">
					</td>
				</tr>
				<tr>
					<td width="45%">&nbsp;</td>
                    <td width="30%"><b><?=$m_band_2.4G?></b></td>
                    <td width="25%"><b><?=$m_band_5G?></b></td>
				</tr>
				<tr>
					<td bgcolor="#cccccc" colspan="3" height="16"><b><?=$msg_t_title?></b></td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_t_msg1?>
					</td>
					<td id="td_right"><?=$cfg_T_Packet_g?>
					</td>
                    <td id="td_rght"><?=$cfg_T_Packet_a?></td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_t_msg2?>
					</td>
					<td id="td_right">
						<?=$cfg_T_Bytes_g?>
					</td>
                    <td id="td_rght"><?=$cfg_T_Bytes_a?></td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_t_msg3?>
					</td>
					<td id="td_right">
						<?=$cfg_T_Dropped_Packet_g?>
					</td>
                    <td id="td_rght"><?=$cfg_T_Dropped_Packet_a?></td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_t_msg4?>
					</td>
					<td id="td_right">
						<?=$cfg_T_Retry_g?>
					</td>
                    <td id="td_rght"><?=$cfg_T_Retry_a?></td>
				</tr>
				<tr>
					<td bgcolor="#cccccc" colspan="3" height="16"><b><?=$msg_r_title?></b></td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_r_msg1?>
					</td>
					<td id="td_right">
						<?=$cfg_R_Packet_g?>
					</td>
                    <td id="td_rght"><?=$cfg_R_Packet_a?></td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_r_msg2?>
					</td>
					<td id="td_right">
						<?=$cfg_R_Bytes_g?>
					</td>
                    <td id="td_rght"><?=$cfg_R_Bytes_a?></td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_r_msg3?>
					</td>
					<td id="td_right">
						<?=$cfg_R_Dropped_Packet_g?>
					</td>
                    <td id="td_rght"><?=$cfg_R_Dropped_Packet_a?></td>
				</tr>
<? if(query("/runtime/web/display/stat_wl") =="0")	{echo "<!--";} ?>				
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_r_msg4?>
					</td>
					<td id="td_right">
						<?map("/runtime/stats/wireless/inf:1/showrx/crcerr","",0);?>
					</td>
                    <td id="td_right">
                        <?map("/runtime/stats/wireless/inf:2/showrx/crcerr","",0);?>
                    </td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_r_msg5?>
					</td>
					<td id="td_right">
						<?=$cfg_R_Decryption_Error_g?>
					</td>
                    <td id="td_rght"><?=$cfg_R_Decryption_Error_a?></td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_r_msg6?>
					</td>
					<td id="td_right">
						<?=$cfg_R_MIC_Error_g?>
					</td>
                    <td id="td_rght"><?=$cfg_R_MIC_Error_a?></td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_r_msg7?>
					</td>
					<td id="td_right">
						<?=$cfg_R_PHY_Error_g?>
					</td>
                    <td id="td_rght"><?=$cfg_R_PHY_Error_a?></td>
				</tr>
<? if(query("/runtime/web/display/stat_wl") =="0")	{echo "-->";} ?>					
			</table>				
			</div>
<!-- ________________________________  Main Content End _______________________________ -->
		</td>
	</tr>
</table>
</form>
</body>
<script>


</script>
</html>
