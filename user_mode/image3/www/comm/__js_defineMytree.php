<?
$sysname	= query("/sys/hostname");
$func_wtp	= query("/sys/func/wtp");
$wtp_enable	= query("/sys/wtp/enable");
$ap_array_enable= query("/runtime/web/display/ap_array");
$local_radius_enable = query("/runtime/web/display/local_radius_server");
$array_v2_enable = query("/runtime/web/display/array_v2");
$url_redir_enable= query("/runtime/web/display/url_redir");
$url_tc_dy=query("/runtime/web/display/trafficdy");
$arp_spoofing_enable= query("/runtime/web/display/arp_spf");
$ap_mode = query("/wlan/inf:1/ap_mode");
$ap_mode_5g = query("/wlan/inf:2/ap_mode");
$runtime_ipv6 = query("/runtime/web/display/ipv6");
$cfg_sw_enable = query("/sys/swcontroller/enable");
$cfg_bsw_enable = query("/sys/b_swcontroller/enable");
$aclperssid_enable = query("/runtime/web/display/acl_perssid");
$cap_v1 = query("/runtime/web/display/cap_v1");
$cap_v2 = query("/runtime/web/display/cap_v2");
$cap_wr = query("/runtime/web/display/cap_wr");
$check_band = query("/wlan/inf:2/ap_mode");
?>
<script>
// You can find instructions for this file here:
// http://www.geocities.com/marcelino_martins/ftv2instructions.html

// Decide if the names are links or just the icons
var USETEXTLINKS = 1;  //replace 0 with 1 for hyperlinks

// Decide if the tree is to start all open or just showing the root folders
var STARTALLOPEN = 0; //replace 0 with 1 to show the whole tree

var ICONPATH = '/pic/';

var foldersTree = gHeader("<?=$sysname?>", "<?=$first_frame?>.php");
aux1 = insFld(foldersTree, gFld("<?=$basic_setting?>", ""));
if("<?=$cfg_sw_enable?>" != 1 && "<?=$cfg_bsw_enable?>" != 1)
{
	if("<?=$wtp_enable?>" != 1)
	{
		insDoc(aux1, gLnk("R","&nbsp;<?=$basic_wireless?>", "<?=$G_PAGE_BSC_WLAN?>"));
	}
}
	insDoc(aux1, gLnk("R","&nbsp;<?=$basic_lan?>", "<?=$G_PAGE_BSC_LAN?>"));
if("<?=$cfg_sw_enable?>" != 1 && "<?=$cfg_bsw_enable?>" != 1)
{
	if("<?=$runtime_ipv6?>" == "1" )
	{
		insDoc(aux1, gLnk("R","&nbsp;<?=$basic_ipv6?>", "<?=$G_PAGE_BSC_IPV6?>"));
	}
	aux1 = insFld(foldersTree, gFld("<?=$adv_setting?>", ""));
	if("<?=$wtp_enable?>" != 1)
	{
		insDoc(aux1, gLnk("R","&nbsp;<?=$adv_perf?>", "<?=$G_PAGE_ADV_PERF?>"));
		insDoc(aux1, gLnk("R","&nbsp;<?=$adv_resource?>", "<?=$G_PAGE_ADV_RESOURCE?>"));
		insDoc(aux1, gLnk("R","&nbsp;<?=$adv_mssid?>", "<?=$G_PAGE_ADV_MSSID?>"));
		if("<?=$check_band?>" == "")
			insDoc(aux1, gLnk("R","&nbsp;<?=$adv_8021q?>", "<?=$G_PAGE_ADV_8021Q_S?>"));
		else
			insDoc(aux1, gLnk("R","&nbsp;<?=$adv_8021q?>", "<?=$G_PAGE_ADV_8021Q?>"));
		insDoc(aux1, gLnk("R","&nbsp;<?=$adv_rogue_ap?>", "<?=$G_PAGE_ADV_ROGUE?>"));
		if("<?=$check_band?>" == "")
			insDoc(aux1, gLnk("R","&nbsp;<?=$adv_scheduling?>", "<?=$G_PAGE_ADV_SCHEDULE_S?>"));
		else
			insDoc(aux1, gLnk("R","&nbsp;<?=$adv_scheduling?>", "<?=$G_PAGE_ADV_SCHEDULE?>"));
		if("<?=$url_redir_enable?>"== 1 && "<?=$cap_v1?>" != 1 && "<?=$cap_v2?>" != 1)
		{
			insDoc(aux1, gLnk("R","&nbsp;<?=$adv_url_redir2?>", "<?=$G_PAGE_ADV_URL2?>"));
		}
		if("<?=$local_radius_enable?>"==1 )
		{
			insDoc(aux1, gLnk("R","&nbsp;<?=$adv_radiusserver?>", "<?=$G_PAGE_ADV_RADIUSSERVER?>"));
		}
		if("<?=$arp_spoofing_enable?>" == 1)
		{
			insDoc(aux1, gLnk("R","&nbsp;<?=$adv_arp_spoofing?>", "<?=$G_PAGE_ADV_ARP_SPOOFING?>"));
		}
		insDoc(aux1, gLnk("R","&nbsp;<?=$adv_ctrl_fair?>", "<?=$G_PAGE_ADV_CTRL_FAIR?>"));
		if("<?=$ap_array_enable?>"== 1 )
		{
			if("<?=$array_v2_enable?>" == 1)
			{
				aux2 = insFld(aux1, gFld("&nbsp;<?=$adv_ap_array?>", ""));
				insDoc(aux2, gLnk("R","&nbsp;<?=$adv_array_scan?>", "<?=$G_PAGE_ADV_ARRAY_SCAN?>"));
				insDoc(aux2, gLnk("R","&nbsp;<?=$adv_array_config?>", "<?=$G_PAGE_ADV_ARRAY_CONFIG?>"));
				insDoc(aux2, gLnk("R","&nbsp;<?=$adv_auto_rf?>", "<?=$G_PAGE_ADV_AUTO_RF?>"));
				insDoc(aux2, gLnk("R","&nbsp;<?=$adv_load_balance?>", "<?=$G_PAGE_ADV_LOAD_BALANCE?>"));
			}
			else
			{
				insDoc(aux1, gLnk("R","&nbsp;<?=$adv_ap_array?>", "<?=$G_PAGE_ADV_AP_ARRAY?>"));
			}
		}
		if("<?=$cap_v1?>" == 1 || "<?=$cap_v2?>" == 1)
		{
			aux2 = insFld(aux1, gFld("&nbsp;<?=$adv_captival?>", ""));
			if("<?=$cap_v2?>" == 1)
			{
				insDoc(aux2, gLnk("R","&nbsp;<?=$adv_captivals?>", "<?=$G_PAGE_ADV_CAPTIVAL_SETTING_V2?>"));
				insDoc(aux2, gLnk("R","&nbsp;<?=$adv_captivalu?>", "<?=$G_PAGE_ADV_CAPTIVAL_UPLOAD_V2?>"));
				insDoc(aux2, gLnk("R","&nbsp;<?=$adv_ip_filter?>", "<?=$G_PAGE_ADV_IP?>"));
			}
			else if("<?=$cap_v1?>" == 1)
			{
				insDoc(aux2, gLnk("R","&nbsp;<?=$adv_captivals?>", "<?=$G_PAGE_ADV_CAPTIVAL_SETTING?>"));
				insDoc(aux2, gLnk("R","&nbsp;<?=$adv_captivalu?>", "<?=$G_PAGE_ADV_CAPTIVAL_UPLOAD?>"));
				insDoc(aux2, gLnk("R","&nbsp;<?=$adv_url_cap?>", "<?=$G_PAGE_ADV_URL_CAPTIVAL?>"));
			}
			insDoc(aux2, gLnk("R","&nbsp;<?=$adv_filter_mac?>", "<?=$G_PAGE_ADV_MAC?>"));
		}
	}

	if("<?=$func_wtp?>" == 1)
	{
		insDoc(aux1, gLnk("R","&nbsp;<?=$adv_wtp?>", "<?=$G_PAGE_ADV_WTP?>"));
	}
	if("<?=$wtp_enable?>" != 1)
	{
		aux2 = insFld(aux1, gFld("&nbsp;<?=$adv_dhcp?>", ""));
		insDoc(aux2, gLnk("R","&nbsp;<?=$adv_dhcp_dynamic?>", "<?=$G_PAGE_ADV_DHCP_DYNAMIC?>"));
		insDoc(aux2, gLnk("R","&nbsp;<?=$adv_dhcp_static?>", "<?=$G_PAGE_ADV_DHCP_STATIC?>"));
		insDoc(aux2, gLnk("R","&nbsp;<?=$adv_dhcp_list?>", "<?=$G_PAGE_ADV_DHCP_LIST?>"));
		aux3 = insFld(aux1, gFld("&nbsp;<?=$adv_filter?>", ""));
		if("<?=$aclperssid_enable?>" == 1)
		{
			insDoc(aux3, gLnk("R","&nbsp;<?=$adv_filter_acl?>", "<?=$G_PAGE_ADV_ACL_PERSSID?>"));
		}
		else
		{
			if("<?=$check_band?>" == "")
				insDoc(aux3, gLnk("R","&nbsp;<?=$adv_filter_acl?>", "<?=$G_PAGE_ADV_ACL_S?>"));
			else
				insDoc(aux3, gLnk("R","&nbsp;<?=$adv_filter_acl?>", "<?=$G_PAGE_ADV_ACL?>"));
		}
		insDoc(aux3, gLnk("R","&nbsp;<?=$adv_filter_partition?>", "<?=$G_PAGE_ADV_PARTITION?>"));
		aux4 = insFld(aux1, gFld("&nbsp;<?=$adv_ctrl?>", ""));
		if("<?=$check_band?>" == "")
			insDoc(aux4, gLnk("R","&nbsp;<?=$adv_ctrl_setting?>", "<?=$G_PAGE_ADV_CTRL_SETTING_S?>"));
		else
			insDoc(aux4, gLnk("R","&nbsp;<?=$adv_ctrl_setting?>", "<?=$G_PAGE_ADV_CTRL_SETTING?>"));
		insDoc(aux4, gLnk("R","&nbsp;<?=$adv_ctrl_qos_user?>", "<?=$G_PAGE_ADV_CTRL_QOS_USER?>"));
		insDoc(aux4, gLnk("R","&nbsp;<?=$adv_ctrl_trafficmanage?>", "<?=$G_PAGE_ADV_CTRL_TRAFFICMANAGE?>"));
	}
}
aux1 = insFld(foldersTree, gFld("<?=$st_setting?>", ""));
insDoc(aux1, gLnk("R","&nbsp;<?=$st_device?>", "<?=$G_PAGE_ST_DEVICE?>"));
insDoc(aux1, gLnk("R","&nbsp;<?=$st_client?>", "<?=$G_PAGE_ST_CLIENT?>"));
insDoc(aux1, gLnk("R","&nbsp;<?=$st_wds_client?>", "<?=$G_PAGE_ST_WDS_CLIENT?>"));
insDoc(aux1,gLnk("R","&nbsp;<?=$st_ap?>","<?=$G_PAGE_ST_AP?>"));
//insDoc(aux1,gLnk("R","&nbsp;<?=$st_ap_test?>","<?=$G_PAGE_ST_AP_TEST?>"));
aux2 = insFld(aux1, gFld("&nbsp;<?=$st_stats?>", ""));
insDoc(aux2, gLnk("R","&nbsp;<?=$st_stats_ethernet?>", "<?=$G_PAGE_ST_ETHERNET?>"));
insDoc(aux2, gLnk("R","&nbsp;<?=$st_stats_wlan?>", "<?=$G_PAGE_ST_WLAN?>"));
aux3 = insFld(aux1, gFld("&nbsp;<?=$st_log?>", ""));
insDoc(aux3, gLnk("R","&nbsp;<?=$st_log_view?>", "<?=$G_PAGE_ST_LOG_VIEW?>"));
if("<?=$cfg_sw_enable?>" != 1 && "<?=$cfg_bsw_enable?>" != 1)
{
	insDoc(aux3, gLnk("R","&nbsp;<?=$st_log_setting?>", "<?=$G_PAGE_ST_LOG_SETTING?>"));
}
</script>
