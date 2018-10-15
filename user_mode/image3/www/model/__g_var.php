<?
if ($__GLOBAL_VARIABLE_REQUIRED != "true")
{
	$__GLOBAL_VARIABLE_REQUIRED = "true";

	$G_WIZ_PREFIX_WAN	="/tmp/wiz/wan";
	$G_WIZ_PREFIX_WLAN	="/tmp/wiz/wlan";

	// about page url
	$G_HOME_PAGE             ="index";
	$G_SAVING_URL            ="/www/__saving.php";
	$G_NO_CHANGED_URL        ="/www/__no_changed.php";
	$G_SCAN_URL              ="/www/__scan.php";
	$G_PAGE_BSC_WLAN         = "bsc_wlan.php";
	$G_PAGE_BSC_LAN          = "bsc_lan.php";
	$G_PAGE_BSC_IPV6          = "bsc_ipv6.php";
	$G_PAGE_BSC_CAPWAP       = "bsc_capwap.php";
	$G_PAGE_ADV_PERF         = "adv_perf.php";
	$G_PAGE_ADV_PERF_S         = "adv_perf_s.php";
	$G_PAGE_ADV_RESOURCE         = "adv_resource.php";
	$G_PAGE_ADV_TR069V3         = "adv_tr069v3.php";
	$G_PAGE_ADV_MSSID        = "adv_mssid.php";
	$G_PAGE_ADV_MSSID_S        = "adv_mssid_s.php";
	$G_PAGE_ADV_8021Q 		 = "adv_8021q.php";
	$G_PAGE_ADV_8021Q_S        = "adv_8021q_s.php";
	$G_PAGE_ADV_ROGUE        = "adv_rogue.php";
	$G_PAGE_ADV_SCHEDULE     = "adv_schedule.php";
	$G_PAGE_ADV_SCHEDULE_S     = "adv_schedule_s.php";
	$G_PAGE_ADV_RADIUSSERVER       = "adv_radiusserver.php";
	$G_PAGE_ADV_QOS		 	 = "adv_qos.php";	
	$G_PAGE_ADV_QOS_LIMIT		= "adv_qos_limit.php";
	$G_PAGE_ADV_CTRL_SETTING		 	 = "adv_updnsetting.php";	
	$G_PAGE_ADV_CTRL_SETTING_S             = "adv_updnsetting_s.php";
	$G_PAGE_ADV_ARP_SPOOFING       = "adv_arpspoofing.php";
	$G_PAGE_ADV_CAPTIVAL_SETTING		="adv_captivals.php";
	$G_PAGE_ADV_CAPTIVAL_SETTING_V2		="adv_captivals_v2.php";
	$G_PAGE_ADV_CAPTIVAL_UPLOAD        ="adv_captivalu.php";
	$G_PAGE_ADV_CAPTIVAL_UPLOAD_V2        ="adv_captivalu_v2.php";
	$G_PAGE_ADV_URL_CAPTIVAL		= "adv_url_cap.php";
	$G_PAGE_ADV_CTRL_QOS		 	 = "adv_qos.php";	
	$G_PAGE_ADV_CTRL_QOS_USER		 = "adv_qos_user.php";
	$G_PAGE_ADV_CTRL_TRAFFICMANAGE   ="adv_trafficmanage.php";
	$G_PAGE_ADV_CTRL_TRAFFICMANAGE_DY ="adv_trafficmanage_dy.php";
	$G_PAGE_ADV_CTRL_FAIR	 = "adv_fair.php";
	$G_PAGE_ADV_WTP		 	 = "adv_wtp.php";		
	$G_PAGE_ADV_DHCP_DYNAMIC = "adv_dhcpd.php";
	$G_PAGE_ADV_DHCP_STATIC  = "adv_dhcps.php";
	$G_PAGE_ADV_DHCP_LIST    = "adv_dhcpl.php";
	$G_PAGE_ADV_ACL          = "adv_acl.php";
	$G_PAGE_ADV_ACL_S          = "adv_acl_s.php";
	$G_PAGE_ADV_ACL_PERSSID		= "adv_acl_perssid.php";
	$G_PAGE_ADV_MAC				= "adv_macbypass.php";
	$G_PAGE_ADV_IP				= "adv_ip_filter.php";
	$G_PAGE_ADV_PARTITION    = "adv_partition.php";
	$G_PAGE_ADV_RADIUSCLIENT = "adv_radiusclient.php";
	$G_PAGE_ADV_AP_ARRAY     = "adv_ap_array.php";
	$G_PAGE_ADV_ARRAY_SCAN		= "adv_array_scan.php";
	$G_PAGE_ADV_ARRAY_CONFIG	= "adv_array_config.php";
	$G_PAGE_ADV_ARRAY_AUTH		= "adv_array_auth.php";
	$G_PAGE_ADV_AUTO_RF			= "adv_auto_rf.php";
	$G_PAGE_ADV_LOAD_BALANCE	= "adv_load_balance.php";
	$G_PAGE_ADV_MCAST   		 = "adv_mcast.php";
	$G_PAGE_ADV_URL   		 = "adv_url.php";
	$G_PAGE_ADV_URL2          = "adv_url_addr.php";
	$G_PAGE_ST_DEVICE        = "st_device.php";
	$G_PAGE_ST_CLIENT        = "st_info.php";
	$G_PAGE_ST_AP		 = "st_ap.php";
	$G_PAGE_ST_AP_TEST = "st_ap_test.php";
	$G_PAGE_ST_WDS_CLIENT    = "st_wds_info.php";
	$G_PAGE_ST_ETHERNET      = "st_stats_lan.php";
	$G_PAGE_ST_WLAN          = "st_stats_wl.php";
	$G_PAGE_ST_LOG_VIEW      = "st_log.php";
	$G_PAGE_ST_LOG_SETTING   = "st_logs.php";
	// -----------------------------------------------------------------------

	// about table attribute.
	$G_BODY_ATTR="leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" bgcolor=\"#CCDCE2\" style=\"overflow-x: auto; overflow-y: auto;\"";
	$G_TAG_SCRIPT_START = "<script type=\"text/javascript\">";
	$G_TAG_SCRIPT_END   = "</script>";

	$G_LOG_MAIN_TABLE_ATTR="border=\"1\" cellpadding=\"2\" cellspacing=\"1\" width=\"775\"".
		" align=\"center\" bgcolor=\"#CCDCE2\" bordercolordark=\"#136393\"";

	$G_UPLOAD_FW_MAIN_TABLE_ATTR="border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"775\"".
		" align=\"center\" bgcolor=\"#CCDCE2\" style=\"border: 1px solid #136393; \"";


	$G_MAIN_TABLE_ATTR="width=\"775\" cellspacing=\"0\" cellpadding=\"0\"  bgcolor=\"#CCDCE2\"".
		" style=\"border: solid; border-width:1; border-right-color:#1E4C7D;.border-bottom-color:#1E4C7D; border-left-color:#1E4C7D; border-top-color:#1E4C7D;\"".

	$G_MENU_TABLE_ATTR="id=\"sidenav_container\" valign=\"top\" width=\"125\" align=\"right\"";
	$G_HELP_TABLE_ATTR="id='help_text' width='138'";

	$G_TABLE_ATTR_CELL_ZERO="cellspacing=\"0\" cellpadding=\"0\"";

	// -----------------------------------------------------------------------

	$G_APPLY_CANEL_BUTTON="<script>apply(''); echo(\"&nbsp;\"); cancel('');</script>";
	// Please DO NOT set the default password too long.
	// If the length of default password is longer than the max length of the password field,
	// it may cause the discrimination incorrect.
	$G_DEF_PASSWORD="XxXxXxXxXx";
	$multi_lang=fread("/www/locale/alt/langcode");
	/*victor modify 2009-1-7 start*/
	if($multi_lang!="")
	{
		$pic_path="/locale/alt/";
	}
	else
	{
		$pic_path="/pic/";	
	}
	
	$G_APPLY_BUTTON	="<table width=\"100%\">\n<tr>\n<td align=\"right\" height=\"50\">\n".
					 	"<a href=\"javascript:submit()\"><img src=\"".$pic_path."save1.gif\" border=\"0\" OnMouseOver=\"this.src='".$pic_path."save2.gif'\"  OnMouseOut=\"this.src='".$pic_path."save1.gif'\"></a>\n".
					 	"</td>\n</tr>\n</table>";				 	
	$G_ADD_BUTTON	="<table width=\"100%\">\n<tr>\n<td align=\"right\" height=\"50\">\n".
					 	"<a href=\"javascript:submit_do_add()\"><img src=\"".$pic_path."add1.gif\" border=\"0\" OnMouseOver=\"this.src='".$pic_path."add2.gif'\"  OnMouseOut=\"this.src='".$pic_path."add1.gif'\"></a>\n".
					 	"&nbsp;&nbsp;&nbsp;&nbsp;</td>\n</tr>\n</table>";					 	
	/*victor modify 2009-1-7 end*/ 	
}
?>
