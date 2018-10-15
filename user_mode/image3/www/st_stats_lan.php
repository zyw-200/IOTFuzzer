<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "st_stats_lan";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "st_stats_lan";
set("/runtime/web/help_page",$MY_NAME);
/* --------------------------------------------------------------------------- */
//set("/runtime/stats/wireless/statistic/clear",0);
$check_lan = query("/sys/2lan");
if($check_clear == "")
{
	$check_clear = 0;	
}
$cfg_T_Packet=query("/runtime/stats/ethernet/tx/packets");
$cfg_T_Bytes=query("/runtime/stats/ethernet/tx/bytes");
$cfg_T_Dropped_Packet=query("/runtime/stats/ethernet/tx/drop");
$cfg_R_Packet=query("/runtime/stats/ethernet/rx/packets");
$cfg_R_Bytes=query("/runtime/stats/ethernet/rx/bytes");
$cfg_R_Dropped_Packet=query("/runtime/stats/ethernet/rx/drop");
$cfg_R_Multicast_Packet=query("/runtime/stats/ethernet/count/multicast");
$cfg_R_Broadcast_Packet=query("/runtime/stats/ethernet/count/broadcast_packets");
$cfg_Len_64=query("/runtime/stats/ethernet/count/len_64_packets");
$cfg_Len_65_127=query("/runtime/stats/ethernet/count/len_65_127_packets");
$cfg_Len_128_255=query("/runtime/stats/ethernet/count/len_128_255_packets");
$cfg_Len_256_511=query("/runtime/stats/ethernet/count/len_256_511_packets");
$cfg_Len_512_1023=query("/runtime/stats/ethernet/count/len_512_1023_packets");
$cfg_Len_1024_1518=query("/runtime/stats/ethernet/count/len_1024_1518_packets");
$cfg_Len_1519_MAX=query("/runtime/stats/ethernet/count/len_1519_max_packets");


$cfg_T_Packet_eth2=query("/runtime/stats/ethernet:2/tx/packets");
$cfg_T_Bytes_eth2=query("/runtime/stats/ethernet:2/tx/bytes");
$cfg_T_Dropped_Packet_eth2=query("/runtime/stats/ethernet:2/tx/drop");
$cfg_R_Packet_eth2=query("/runtime/stats/ethernet:2/rx/packets");
$cfg_R_Bytes_eth2=query("/runtime/stats/ethernet:2/rx/bytes");
$cfg_R_Dropped_Packet_eth2=query("/runtime/stats/ethernet:2/rx/drop");
$cfg_R_Multicast_Packet_eth2=query("/runtime/stats/ethernet:2/count/multicast");
$cfg_R_Broadcast_Packet_eth2=query("/runtime/stats/ethernet:2/count/broadcast_packets");
$cfg_Len_64_eth2=query("/runtime/stats/ethernet:2/count/len_64_packets");
$cfg_Len_65_127_eth2=query("/runtime/stats/ethernet:2/count/len_65_127_packets");
$cfg_Len_128_255_eth2=query("/runtime/stats/ethernet:2/count/len_128_255_packets");
$cfg_Len_256_511_eth2=query("/runtime/stats/ethernet:2/count/len_256_511_packets");
$cfg_Len_512_1023_eth2=query("/runtime/stats/ethernet:2/count/len_512_1023_packets");
$cfg_Len_1024_1518_eth2=query("/runtime/stats/ethernet:2/count/len_1024_1518_packets");
$cfg_Len_1519_MAX_eth2=query("/runtime/stats/ethernet:2/count/len_1519_max_packets");
/* --------------------------------------------------------------------------- */
require("/www/model/__html_head.php");
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.

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
	get_obj("single_lan").style.display = "none";
	get_obj("two_lans").style.display = "none";
	if("<?=$check_lan?>" == "")
	{
		get_obj("single_lan").style.display = "";
	}
	else
	{
		get_obj("two_lans").style.display = "";
	}
   	var str="";
   	str+="st_stats_lan.xgi?random="+generate_random_str();
    <?
   	
	$refresh_count = query("/runtime/web/refresh_count");
	$refresh_flag = query("/runtime/web/refresh_flag");
	if($refresh_count=="")
		{$refresh_count ="0";}
	if($refresh_flag=="")
		{$refresh_flag ="2";}
	if($AUTH_GROUP=="0")
	{		
		
		if($check_clear == 1)
		{		
			echo "str+=exe_str(\"submit ST_LAN_RESET;submit SLEEP 1\");\n";
			$refresh_count = $refresh_count-1;	
		}	
		else
		{
			echo "str+=exe_str(\"submit ST_LAN_REFRESH;submit SLEEP 1\");\n";	
		}	
		$refresh_count = $refresh_count+1;	
		set("/runtime/web/refresh_count",$refresh_count);

		if($refresh_flag > $refresh_count)
		{
			echo "self.location.href=str;\n";
		}			
		else 	
		{
			$refresh_flag = $refresh_count+2;
			set("/runtime/web/refresh_flag",$refresh_flag);			
		}

	}	
	?>
	AdjustHeight();	
}
/* parameter checking */
function do_refresh()
{
	self.location.href="<?=$MY_MSG_FILE?>";
}
/* cancel function */
function do_clear()
{
	self.location.href = "st_stats_lan.php?random="+generate_random_str()+"&check_clear=1";
	
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
			<div id="single_lan">
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
						<?=$msg_T_Packet?>
					</td>
					<td id="td_right"><?=$cfg_T_Packet?>
					</td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_T_Bytes?>
					</td>
					<td id="td_right">
						<?=$cfg_T_Bytes?>
					</td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_T_Dropped_Packet?>
					</td>
					<td id="td_right">
						<?=$cfg_T_Dropped_Packet?>
					</td>
				</tr>
				<tr>
					<td bgcolor="#cccccc" colspan="2" height="16"><b><?=$msg_r_title?></b></td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_R_Packet?>
					</td>
					<td id="td_right">
						<?=$cfg_R_Packet?>
					</td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_R_Bytes?>
					</td>
					<td id="td_right">
						<?=$cfg_R_Bytes?>
					</td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_R_Dropped_Packet?>
					</td>
					<td id="td_right">
						<?=$cfg_R_Dropped_Packet?>
					</td>
				</tr>
<? if(query("/runtime/web/display/statistics/received") !="1")	{echo "<!--";} ?>					
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_R_Multicast_Packet?>
					</td>
					<td id="td_right">
						<?=$cfg_R_Multicast_Packet?>
					</td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_R_Broadcast_Packet?>
					</td>
					<td id="td_right">
						<?=$cfg_R_Broadcast_Packet?>
					</td>
				</tr>
<? if(query("/runtime/web/display/statistics/received") !="1")	{echo "-->";} ?>					
<? if(query("/runtime/web/display/statistics/len") !="1")	{echo "<!--";} ?>								
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_Len_64?>
					</td>
					<td id="td_right">
						<?=$cfg_Len_64?>
					</td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_Len_65_127?>
					</td>
					<td id="td_right">
						<?=$cfg_Len_65_127?>
					</td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_Len_128_255?>
					</td>
					<td id="td_right">
						<?=$cfg_Len_128_255?>
					</td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_Len_256_511?>
					</td>
					<td id="td_right">
						<?=$cfg_Len_256_511?>
					</td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_Len_512_1023?>
					</td>
					<td id="td_right">
						<?=$cfg_Len_512_1023?>
					</td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_Len_1024_1518?>
					</td>
					<td id="td_right">
						<?=$cfg_Len_1024_1518?>
					</td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_Len_1519_MAX?>
					</td>
					<td id="td_right">
						<?=$cfg_Len_1519_MAX?>
					</td>
				</tr>
<? if(query("/runtime/web/display/statistics/len") !="1")	{echo "-->";} ?>					
			</table>
			</div>

			<div id="two_lans">
			<table width="98%"  border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
				<tr>
					<td  colspan="3" height="16" align="right">
						<input type="button" name="clear" value="<?=$msg_clear?>" onClick="do_clear();">
						<input type="button" name="refresh" value="<?=$msg_refresh?>" onClick="do_refresh();">
					</td>
				</tr>
				<tr>
					<td width="45%" id="td_left"></td>
					<td id="td_right"><?=$m_lan1?></td>
					<td id="td_right"><?=$m_lan2?></td>
				</tr>
				<tr>
					<td bgcolor="#cccccc" colspan="3" height="16"><b><?=$msg_t_title?></b></td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_T_Packet?>
					</td>
					<td id="td_right"><?=$cfg_T_Packet_eth2?>
					</td>
					<td id="td_right"><?=$cfg_T_Packet?>
                    </td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_T_Bytes?>
					</td>
					<td id="td_right">
						<?=$cfg_T_Bytes_eth2?>
					</td>
					<td id="td_right">
                        <?=$cfg_T_Bytes?>
                    </td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_T_Dropped_Packet?>
					</td>
					<td id="td_right">
						<?=$cfg_T_Dropped_Packet_eth2?>
					</td>
					<td id="td_right">
                        <?=$cfg_T_Dropped_Packet?>
                    </td>
				</tr>
				<tr>
					<td bgcolor="#cccccc" colspan="3" height="16"><b><?=$msg_r_title?></b></td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_R_Packet?>
					</td>
					<td id="td_right">
						<?=$cfg_R_Packet_eth2?>
					</td>
					<td id="td_right">
                        <?=$cfg_R_Packet?>
                    </td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_R_Bytes?>
					</td>
					<td id="td_right">
						<?=$cfg_R_Bytes_eth2?>
					</td>
					<td id="td_right">
                        <?=$cfg_R_Bytes?>
                    </td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_R_Dropped_Packet?>
					</td>
					<td id="td_right">
						<?=$cfg_R_Dropped_Packet_eth2?>
					</td>
					<td id="td_right">
                        <?=$cfg_R_Dropped_Packet?>
                    </td>
				</tr>
<? if(query("/runtime/web/display/statistics/received") !="1")	{echo "<!--";} ?>					
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_R_Multicast_Packet?>
					</td>
					<td id="td_right">
						<?=$cfg_R_Multicast_Packet_eth2?>
					</td>
					<td id="td_right">
                        <?=$cfg_R_Multicast_Packet?>
                    </td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_R_Broadcast_Packet?>
					</td>
					<td id="td_right">
						<?=$cfg_R_Broadcast_Packet_eth2?>
					</td>
					<td id="td_right">
                        <?=$cfg_R_Broadcast_Packet?>
                    </td>
				</tr>
<? if(query("/runtime/web/display/statistics/received") !="1")	{echo "-->";} ?>					
<? if(query("/runtime/web/display/statistics/len") !="1")	{echo "<!--";} ?>								
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_Len_64?>
					</td>
					<td id="td_right">
						<?=$cfg_Len_64_eth2?>
					</td>
					<td id="td_right">
                        <?=$cfg_Len_64?>
                    </td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_Len_65_127?>
					</td>
					<td id="td_right">
						<?=$cfg_Len_65_127_eth2?>
					</td>
					<td id="td_right">
                        <?=$cfg_Len_65_127?>
                    </td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_Len_128_255?>
					</td>
					<td id="td_right">
						<?=$cfg_Len_128_255_eth2?>
					</td>
					<td id="td_right">
                        <?=$cfg_Len_128_255?>
                    </td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_Len_256_511?>
					</td>
					<td id="td_right">
						<?=$cfg_Len_256_511_eth2?>
					</td>
					<td id="td_right">
                        <?=$cfg_Len_256_511?>
                    </td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_Len_512_1023?>
					</td>
					<td id="td_right">
						<?=$cfg_Len_512_1023_eth2?>
					</td>
					<td id="td_right">
                        <?=$cfg_Len_512_1023?>
                    </td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_Len_1024_1518?>
					</td>
					<td id="td_right">
						<?=$cfg_Len_1024_1518_eth2?>
					</td>
					<td id="td_right">
                        <?=$cfg_Len_1024_1518?>
                    </td>
				</tr>
				<tr>
					<td width="45%" id="td_left">
						<?=$msg_Len_1519_MAX?>
					</td>
					<td id="td_right">
						<?=$cfg_Len_1519_MAX_eth2?>
					</td>
					<td id="td_right">
                        <?=$cfg_Len_1519_MAX?>
                    </td>
				</tr>
<? if(query("/runtime/web/display/statistics/len") !="1")	{echo "-->";} ?>					
			</table>
			</div>
<!-- ________________________________  Main Content End _______________________________ -->
		</td>
	</tr>
</table>
</form>
</body>
</html>
