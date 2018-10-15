<? /* vi: set sw=4 ts=4: */
/* The menu definitions */
if ($TEMP_MYGROUP=="basic")
{
	//$menu = i18n("EASY SETUP").			"|".
	//		i18n("INTERNET").				"|".
	$menu = i18n("INTERNET").				"|".
			i18n("WIRELESS SETTINGS").		"|".
			i18n("NETWORK SETTINGS");
	if ( isfile("/proc/net/if_inet6") == 1 )
	{
		$menu = $menu."|".i18n("IPV6");
	}
	//$link = "bsc_easy.php".				"|".
	//		"bsc_internet.php".				"|".
	$link = "bsc_internet.php".				"|".
			"bsc_wlan_main.php".			"|".
			"bsc_lan.php";
	if ( isfile("/proc/net/if_inet6") == 1 )
	{
		$link = $link."|"."bsc_internetv6.php";
	}
}
else if ($TEMP_MYGROUP=="advanced")
{
	$menu = i18n("VIRTUAL SERVER").			"|".
			i18n("PORT FORWARDING").		"|".
			i18n("APPLICATION RULES").		"|".
			i18n("QOS ENGINE").				"|".
			i18n("NETWORK FILTER").			"|".
			i18n("WEBSITE FILTER").			"|".
			i18n("FIREWALL SETTINGS").		"|".
			i18n("ROUTING").				"|".
			i18n("ADVANCED WIRELESS").		"|".
			i18n("WI-FI PROTECTED SETUP").	"|".
			i18n("ADVANCED NETWORK");
	if ( isfile("/proc/net/if_inet6") == 1 )
	{
		$menu = $menu."|".i18n("IPV6 FIREWALL")."|".i18n("IPV6 ROUTING");
	}

	$link =	"adv_vsvr.php".			"|".
			"adv_pfwd.php".			"|".
			"adv_app.php".			"|".
			"adv_qos.php".			"|".
			"adv_mac_filter.php".	"|".
			"adv_web_filter.php".	"|".
			"adv_firewall.php".		"|".
			"adv_routing.php".		"|".
			"adv_wlan.php".			"|".
			"adv_wps.php".			"|".
			"adv_network.php";

			if ( isfile("/proc/net/if_inet6") == 1 )
			{
				$link = $link."|"."adv_firewallv6.php"."|"."adv_routingv6.php";
			}
}
else if ($TEMP_MYGROUP=="tools")
{
	$menu = i18n("ADMIN").			"|".
			i18n("TIME").			"|".
			i18n("SYSLOG").			"|".
			i18n("EMAIL SETTINGS").	"|".
			i18n("SYSTEM").			"|".
			i18n("FIRMWARE").		"|".
			i18n("DYNAMIC DNS").	"|".
			i18n("SYSTEM CHECK").	"|".
			i18n("SCHEDULES");
	$link = "tools_admin.php".		"|".
			"tools_time.php".		"|".
			"tools_syslog.php".		"|".
			"tools_email.php".		"|".
			"tools_system.php".		"|".
			"tools_firmware.php".	"|".
			"tools_ddns.php".		"|".
			"tools_check.php".		"|".
			"tools_sch.php";
}
else if ($TEMP_MYGROUP=="status")
{
	$menu = i18n("DEVICE INFO").		"|".
			i18n("LOGS").				"|".
			i18n("STATISTICS").			"|".
			i18n("INTERNET SESSIONS").	"|".
			i18n("WIRELESS").			"|".
			//i18n("IPv6");
			i18n("IPv6").				"|".
			i18n("IPV6 ROUTING");
	$link = "st_device.php".	"|".
			"st_log.php".		"|".
			"st_stats.php".		"|".
			"st_session.php".	"|".
			"st_wlan.php".		"|".
			//"st_ipv6.php";
			"st_ipv6.php".		"|".
			"st_routingv6.php";
}
else if ($TEMP_MYGROUP=="support")
{
	$menu = i18n("MENU").		"|".
			i18n("SETUP").		"|".
			i18n("ADVANCED").	"|".
			i18n("TOOLS").		"|".
			i18n("STATUS");
	$link = "spt_menu.php".		"|".
			"spt_setup.php".	"|".
			"spt_adv.php".		"|".
			"spt_tools.php".	"|".
			"spt_status.php";
}
?>
