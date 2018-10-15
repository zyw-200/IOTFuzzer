<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<script>
	var funcWinOpen = window.open;
</script>
<title>TRENDNET | modelName | Setup | IPv6</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<link href="/css/css_router.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="uk.js"></script>
<script type="text/javascript" src="public.js"></script>
<script type="text/javascript" src="public_msg.js"></script>
<script type="text/javascript" src="public_ipv6.js"></script>
<script type="text/javascript" src="pandoraBox.js"></script>
<script type="text/javascript" src="menu_all.js"></script>
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/xml.js"></script>
<script type="text/javascript" src="js/object.js"></script>
<script type="text/javascript" src="js/ddaccordion.js"></script>
<script type="text/javascript" src="js/ccpObject.js"></script>
<script type="text/javascript">
	var def_title = document.title;
	var misc = new ccpObject();
	var dev_info = misc.get_router_info();
	document.title = def_title.replace("modelName", dev_info.model);
    var submit_button_flag = 0;

	var menu = new menuObject();
	menu.setSupportUSB(dev_info.KCode_USB);

	var main = new ccpObject();
	main.set_param_url('get_set.ccp');
	main.set_ccp_act('get');
	main.add_param_arg('IGD_WANDevice_i_StaticIPv6_',1110);
	main.add_param_arg('IGD_WANDevice_i_6to4Tunnel_i_',1110);
	main.add_param_arg('IGD_WANDevice_i_PPPoEv6_i_',1110);
	main.add_param_arg('IGD_WANDevice_i_AutoConfiguration_',1110);
	main.add_param_arg('IGD_WANDevice_i_',1100);
	main.add_param_arg('IGD_LANDevice_i_IPv6ConfigManagement_',1110);
	main.add_param_arg('IGD_WANDevice_i_IPv6Status_',1110);
	main.add_param_arg('IGD_WANDevice_i_WANStatus_',1110);
	main.add_param_arg('IGD_LANDevice_i_ULA_',1110);

	main.get_config_obj();

	var wan_addr4 = main.config_val("igdWanStatus_IPAddress_");	//IPv4 address
	var ipv6_type = main.config_val("wanDev_CurrentConnObjType6_");	//IPv6 connection type
	var wan_type = main.config_val("wanDev_CurrentConnObjType_");
	var dhcppd_enable = main.config_val("lanIPv6Cfg_DHCPPDEnable_");		//poe and autoconf
	var enable_autoconfig = main.config_val("lanIPv6Cfg_AutoV6AddressAssignEnable_");
	
	/*** define static ***/
	var use_linklocal_addr = filter_ipv6_addr(main.config_val("ipv6StaticConn_UseLinkLocalAddress_"));
	var static_wan_ip = filter_ipv6_addr(main.config_val("ipv6StaticConn_ExternalIPAddress_"));
	var static_prefix_length = main.config_val("ipv6StaticConn_SubnetPrefixLength_");
	var wan_ipv6_linklocal_addr = filter_ipv6_addr(main.config_val("igdIPv6Status_IPv6WanLinkLocalAddress_"));
	var wan_ipv6_linklocal_prefix_length = main.config_val("wanDev_SubnetPrefixLength_");

	/*** define pppoe ***/
	var default_MTU = "1492";
	var session_type = main.config_val("ipv6PPPoEConn_SessionType_");
	var address_typr = main.config_val("ipv6PPPoEConn_IPAddressType_");
	var reconnect_mode = main.config_val("ipv6PPPoEConn_ConnectionTrigger_");
	var auto_dns_enable_pppoe = main.config_val("ipv6PPPoEConn_AutomaticDNSServer_");
	var pppoe_prefixLen = main.config_val("ipv6PPPoEConn_IPv6PrefixLength_");

	/*** define autoconf ***/
	var auto_dns_enable_conf = main.config_val("ipv6AutoConfConn_AutomaticDNSServer_");

	/*** define 6to4 ***/
	var ipv6_6to4_wan_addr;
	var ipv6_6to4_lan_prefix;
	var ipv6_6to4_lan_subnet;
	var ipv6_6to4_tunnel_addr = filter_ipv6_addr(main.config_val("ipv66to4Conn_Tunne6to4Address_"));
	var ipv6_6to4_tunnel_addr_type = main.config_val("ipv66to4Conn_TunnelRelayType_");
	var lan_ipv6_static_addr = filter_ipv6_addr(main.config_val("lanIPv6Cfg_Tunnel6to4LanAddress_"));

	/*** define linkloacal ***/
	var enable_ula = main.config_val("igdULASetup_ULAEnable_");
	var lan_ipv6_ula_addr = filter_ipv6_addr(main.config_val("igdIPv6Status_IPv6LanULAAddress_"));
	var lan_ipv6_linklocal_addr = filter_ipv6_addr(main.config_val("igdIPv6Status_IPv6LanLinkLocalAddress_"));

	/*** Address autoconfiguration settings ***/
	var auto_dhcppd_enable = main.config_val("lanIPv6Cfg_AutoDHCPPDEnable_");
	var ipv6_dhcpd_start_range = filter_ipv6_addr(main.config_val("lanIPv6Cfg_DHCPv6AddressRangeStart_"));
	var ipv6_dhcpd_end_range = filter_ipv6_addr(main.config_val("lanIPv6Cfg_DHCPv6AddressRangeEnd_"));
	
	function change_ipv6_type()
	{
		$('#static_setting').hide();
		$('#pppoe_setting').hide();
		$('#ipv6to4_setting').hide();
		$('#dns_setting').hide();
		$('#DNSSelect_tr').hide();
		$('#DnsPri_tr').hide();
		$('#DnsSec_tr').hide();
		$('#LanIpEnDhcpPD_tr').hide();
		$('#autoconf_setting').hide();
		$('#LanIpULAIp_tr').hide();
		$('#LanIpAddr_tr').hide();
		$('#LanIpPrefixLen_tr').hide();
		$('#LanIpPrefixLen_6to4_tr').hide();
		$('#LanIpLinkLocalIp_tr').hide();
	
		switch(parseInt($("#ipv6_w_proto option:selected").val(),10)){
			case 1: //Static
				$('#static_setting').show();
				$('#dns_setting').show();
				$('#DnsPri_tr').show();
				$('#DnsSec_tr').show();
				$('#autoconf_setting').show();
				$('#LanIpAddr_tr').show();
				$('#LanIpPrefixLen_tr').show();
				$('#LanIpLinkLocalIp_tr').show();
				GetValue_static();
				break;
			case 2: //Autoconfiguration
				$('#dns_setting').show();
				$('#DNSSelect_tr').show();
				$('#LanIpEnDhcpPD_tr').show();
				$('#LanIpAddr_tr').show();
				$('#LanIpPrefixLen_tr').show();
				$('#LanIpLinkLocalIp_tr').show();
				$('#autoconf_setting').show();
				GetValue_autoconf();
				break;
			case 3: //PPPoE
				$('#pppoe_setting').show();
				$('#dns_setting').show();
				$('#DNSSelect_tr').show();
				$('#LanIpEnDhcpPD_tr').show();
				$('#LanIpAddr_tr').show();
				$('#LanIpPrefixLen_tr').show();
				$('#LanIpLinkLocalIp_tr').show();
				$('#autoconf_setting').show();
				GetValue_pppoe();
				break;
			case 5: //6to4
				$('#ipv6to4_setting').show();
				$('#dns_setting').show();
				$('#DnsPri_tr').show();
				$('#DnsSec_tr').show();
				$('#LanIpPrefixLen_tr').show();
				$('#autoconf_setting').show();
				$('#LanIpLinkLocalIp_tr').show();
				$('#LanIpPrefixLen_6to4_tr').show();
				GetValue_6to4();
				break;
			case 7: //Link-Local
				$('#LanIpLinkLocalIp_tr').show();
				GetValue_LinkLocal();
				break;
			default:
				break;
		}
	}

    function set_ipv6_autoconf_range()
	{
		var ipv6_lan_ip = $('#ipv6_lan_ip').val();
		var prefix_length = 64;
		var correct_ipv6="";
		if (ipv6_lan_ip != "") {
			correct_ipv6 = get_stateful_ipv6(ipv6_lan_ip);
			$('#ipv6_addr_range_start_prefix').val(get_stateful_prefix(correct_ipv6,prefix_length));
			$('#ipv6_addr_range_end_prefix').val(get_stateful_prefix(correct_ipv6,prefix_length));
		}
    }

	function set_ipv6_autoconf_range_6to4()
	{
		var ipv6_6to4_lan_ip_subnet = $('#ipv6_6to4_lan_ip_subnet').val();
		if (ipv6_6to4_lan_ip_subnet != "") {
			$('#ipv6_addr_range_start_prefix').val(ipv6_6to4_lan_prefix.toUpperCase()+ ipv6_6to4_lan_ip_subnet);
			$('#ipv6_addr_range_end_prefix').val(ipv6_6to4_lan_prefix.toUpperCase()+ ipv6_6to4_lan_ip_subnet);
		}
	}

	function use_wan_link_local_selector(value)
	{
		if(value == true){
			static_wan_ip = $('#ipv6_static_wan_ip').val();
			static_prefix_length = $('#ipv6_static_prefix_length').val();
			if(wan_ipv6_linklocal_addr.length > 1)
			{
				$('#ipv6_static_wan_ip').val(wan_ipv6_linklocal_addr.toUpperCase());
				$('#ipv6_static_prefix_length').val(64);
			}
			else{
				$('#ipv6_static_wan_ip').val("");
				$('#ipv6_static_prefix_length').val("");
			}
			$('#ipv6_static_wan_ip').attr('disabled',true);
			$('#ipv6_static_prefix_length').attr('disabled',true);
		}
		else{
			$('#ipv6_static_wan_ip').val(static_wan_ip);
			$('#ipv6_static_prefix_length').val(static_prefix_length);
			$('#ipv6_static_wan_ip').attr('disabled','');
			$('#ipv6_static_prefix_length').attr('disabled','');
		}
	}

   function GetValue_static()
    {
		//WAN IPv6 address settings
		set_checked(use_linklocal_addr, $('#ipv6_use_link_local_sel')[0]);
		$('#ipv6_static_wan_ip').val(static_wan_ip);
		$('#ipv6_static_prefix_length').val(static_prefix_length);
		$('#ipv6_static_default_gw').val(filter_ipv6_addr(main.config_val("ipv6StaticConn_DefaultGateway_")));
		$('#ipv6_primary_dns').val(filter_ipv6_addr(main.config_val("ipv6StaticConn_PrimaryDNSAddress_")));
		$('#ipv6_secondary_dns').val(filter_ipv6_addr(main.config_val("ipv6StaticConn_SecondaryDNSAddress_")));
		use_wan_link_local_selector(use_linklocal_addr);

		//LAN IPv6 address settings
		$('#ipv6_lan_ip').val(filter_ipv6_addr(main.config_val("lanIPv6Cfg_StatictLanAddress_")));
		$('#lan_link_local_ip').html(lan_ipv6_linklocal_addr.toUpperCase()+"/64");
		$('#lan_ipv6_ip_prefix').html("64");	//Silvia note: maybe need modify.

		//Address autoconfiguration settings
		$('#ipv6_adver_lifetime').val(main.config_val("lanIPv6Cfg_AdvertisementLifetime_"));
        $('#ipv6_autoconfig_type')[0].selectedIndex = main.config_val("lanIPv6Cfg_AutoConfigurationType_");
		$('#ipv6_dhcpd_lifetime').val(main.config_val("lanIPv6Cfg_IPv6AddressLifeTime_"));
		
		set_checked(0, $('#EnDhcpPD')[0]);
		$('#ipv6_lan_ip').attr('disabled', '');

        set_ipv6_autoconfiguration_type();
        set_ipv6_stateful_range();
		//disable_autoconfig();		//addbyS
    }

	function useDefMTU(useDefault)
	{
		$('#ipv6_pppoe_mtu').attr('disabled', (useDefault==1?true:''));
		if(useDefault==1)
			$('#ipv6_pppoe_mtu').val(default_MTU);
	}

	function clone_ipv4_pppoe()
	{
		var disable = get_by_name("ipv6_pppoe_share")[1].checked;
		$('#ipv6_pppoe_username').attr('disabled',!disable);
		$('#ipv6_pppoe_password_s').attr('disabled',!disable);
		$('#ipv6_pppoe_password_v').attr('disabled',!disable);
		$('#ipv6_pppoe_ipaddr').attr('disabled',!disable);
		$('#ipv6_pppoe_prefixlen').attr('disabled',!disable);
		$('#useDefMTU_Select').attr('disabled',!disable);
		
		if ($('#ipv6_pppoe_mtu').val() == default_MTU )
		{
			set_selectIndex(1, $('#useDefMTU_Select')[0]);
			useDefMTU(1);
		}else{
			set_selectIndex(0, $('#useDefMTU_Select')[0]);
			useDefMTU(0);
		}
	}

	function Manually_poeip()
	{
		var Auto = $("#ipv6_pppoeSelcet option:selected").val();
		if (Auto == 0)
		{
			$('#Ipv6pppoeStaticIp').hide();
		//	$('#Ipv6pppoePrefixLen').hide();
			$('#ipv6_pppoe_ipaddr').val();
			$('#ipv6_pppoe_prefixlen').val();
		}else{
			$('#Ipv6pppoeStaticIp').show();
		//	$('#Ipv6pppoePrefixLen').show();
		}
		set_selectIndex(Auto, $('#ipv6_pppoeSelcet')[0]);
	}

	function GetValue_pppoe()
	{
		//PPPoE
		set_checked(session_type, get_by_name("ipv6_pppoe_share"));
		set_selectIndex(address_typr, $('#ipv6_pppoeSelcet')[0]);
		$('#ipv6_pppoe_ipaddr').val(filter_ipv6_addr(main.config_val("ipv6PPPoEConn_ExternalIPAddress_")));
		$('#ipv6_pppoe_username').val(main.config_val("ipv6PPPoEConn_Username_"));
		$('#ipv6_pppoe_password_s').val(main.config_val("ipv6PPPoEConn_Password_"));
		$('#ipv6_pppoe_password_v').val(main.config_val("ipv6PPPoEConn_Password_"));
		$('#ipv6_pppoe_service').val(main.config_val("ipv6PPPoEConn_ServiceName_"));
		$('#ipv6_pppoe_mtu').val(main.config_val("ipv6PPPoEConn_MaxMTUSize_"));
		$('#ipv6_pppoe_prefixlen').val(pppoe_prefixLen);
		//clone_ipv4_pppoe();

		//IPv6 DNS settings
		$('#DNSSelect').val(auto_dns_enable_pppoe);
		$('#ipv6_primary_dns').val(filter_ipv6_addr(main.config_val("ipv6PPPoEConn_PrimaryDNSAddress_")));
		$('#ipv6_secondary_dns').val(filter_ipv6_addr(main.config_val("ipv6PPPoEConn_SecondaryDNSAddress_")));

		//LAN IPv6 address settings
		$('#ipv6_lan_ip').val(filter_ipv6_addr(main.config_val("lanIPv6Cfg_PPPoELanAddress_")));
		$('#lan_link_local_ip').html(lan_ipv6_linklocal_addr.toUpperCase()+"/64");
		$('#lan_ipv6_ip_prefix').html("64");	//Silvia note: maybe need modify.(ipv6_6to4_lan_prefix.toUpperCase() + "::1/64");
		set_checked(dhcppd_enable, $('#EnDhcpPD')[0]);

		//Address autoconfiguration settings
		$('#ipv6_autoconfig_type')[0].selectedIndex = main.config_val("lanIPv6Cfg_AutoConfigurationType_");
		$('#ipv6_adver_lifetime').val(main.config_val("lanIPv6Cfg_AdvertisementLifetime_"));
		$('#ipv6_dhcpd_lifetime').val(main.config_val("lanIPv6Cfg_IPv6AddressLifeTime_"));
		

		set_ipv6_autoconfiguration_type();
		set_ipv6_stateful_range();
		en_dhcp_pd();
		ManuallyDNS();
		Manually_poeip();
		clone_ipv4_pppoe();
	}

	function en_dhcp_pd()
	{
		var is_chk = get_checked_value($('#EnDhcpPD')[0]);
		$('#ipv6_lan_ip').attr('disabled', is_chk);
		$('#ipv6_adver_lifetime').attr('disabled', is_chk);
		$('#ipv6_dhcpd_lifetime').attr('disabled', is_chk);

		if($('#EnDhcpPD')[0].checked)
		{
			$('#ipv6_lan_ip').val(filter_ipv6_addr(main.config_val('igdIPv6Status_IPv6LanAddress_')));
			set_ipv6_autoconf_range();
			$('#ipv6_adver_lifetime').val(main.config_val("igdIPv6Status_IPv6LifeTime_"));
			$('#ipv6_dhcpd_lifetime').val(main.config_val("igdIPv6Status_IPv6LifeTime_"));
		}
		else
		{
			$('#ipv6_adver_lifetime').val(main.config_val("lanIPv6Cfg_AdvertisementLifetime_"));
			$('#ipv6_dhcpd_lifetime').val(main.config_val("lanIPv6Cfg_IPv6AddressLifeTime_"));
		}
	}

	function GetValue_autoconf()
	{
		//IPv6 DNS settings
		set_selectIndex(auto_dns_enable_conf, $('#DNSSelect')[0]);
		$('#ipv6_primary_dns').val(filter_ipv6_addr(main.config_val("ipv6AutoConfConn_PrimaryDNSAddress_")));
		$('#ipv6_secondary_dns').val(filter_ipv6_addr(main.config_val("ipv6AutoConfConn_SecondaryDNSAddress_")));

		//LAN IPv6 address settings
		$('#ipv6_lan_ip').val(filter_ipv6_addr(main.config_val("lanIPv6Cfg_AutoConfigurationLanAddress_")));
		$('#lan_link_local_ip').html(lan_ipv6_linklocal_addr.toUpperCase()+"/64");
		$('#lan_ipv6_ip_prefix').html("64");	//Silvia note: maybe need modify.(ipv6_6to4_lan_prefix.toUpperCase() + "::1/64");
		set_checked(dhcppd_enable, $('#EnDhcpPD')[0]);

		//Address autoconfiguration settings
        $('#ipv6_autoconfig_type')[0].selectedIndex = main.config_val("lanIPv6Cfg_AutoConfigurationType_");
		$('#ipv6_dhcpd_lifetime').val(main.config_val("lanIPv6Cfg_IPv6AddressLifeTime_"));
 
        set_ipv6_autoconfiguration_type();
        set_ipv6_stateful_range();
		en_dhcp_pd();
		ManuallyDNS();
	}

	function set_6to4_ipv6_wan_addr()
	{
		if(wan_addr4=="")
			wan_addr4 = "0.0.0.0";
		var array_addr4 = wan_addr4.split(".");

		ipv6_6to4_wan_addr	= "2002:"+dec2hex(array_addr4[0])+dec2hex(array_addr4[1])+":"
		+dec2hex(array_addr4[2])+dec2hex(array_addr4[3])+"::"
		+dec2hex(array_addr4[0])+dec2hex(array_addr4[1])+":"
		+dec2hex(array_addr4[2])+dec2hex(array_addr4[3]);
		$('#ipv6_6to4_addr').html(filter_ipv6_addr(ipv6_6to4_wan_addr).toUpperCase());
	}

	function set_6to4_ipv6_lan_addr()
	{
		if(wan_addr4=="")
			wan_addr4 = "0.0.0.0";
		var array_addr4 = wan_addr4.split(".");

		ipv6_6to4_lan_prefix = "2002:"+dec2hex(array_addr4[0])+dec2hex(array_addr4[1])+":"
		+dec2hex(array_addr4[2])+dec2hex(array_addr4[3])+":";
		if(lan_ipv6_static_addr==""){
			ipv6_6to4_lan_subnet = "";
		}else{
			var array_addr = lan_ipv6_static_addr.split(":");
			ipv6_6to4_lan_subnet = array_addr[3];
		}
		var array_start = ipv6_dhcpd_start_range.split(":");
		var array_end = ipv6_dhcpd_end_range.split(":");
		$('#ipv6_addr_range_start_suffix').val(array_start[array_start.length-1]);
		$('#ipv6_addr_range_end_suffix').val(array_end[array_end.length-1]);
	}

	function GetValue_6to4()
	{
		if(ipv6_6to4_tunnel_addr_type == "0")
			$('#ipv6_6to4_relay').val(main.config_val("ipv66to4Conn_Tunnel6to4RelayAddress_"));
		else
			$('#ipv6_6to4_relay').val(main.config_val("ipv66to4Conn_Tunnel6to4RelayDomain_"));
		$('#ipv6_primary_dns').val(filter_ipv6_addr(main.config_val("ipv66to4Conn_PrimaryDNSAddress_")));
		$('#ipv6_secondary_dns').val(filter_ipv6_addr(main.config_val("ipv66to4Conn_SecondaryDNSAddress_")));

		//LAN IPv6 address settings
		set_6to4_ipv6_wan_addr();
		set_6to4_ipv6_lan_addr();

		$('#lan_link_local_ip').html(lan_ipv6_linklocal_addr.toUpperCase()+"/64");
		$('#lan_ipv6_ip_prefix').html("64");	//Silvia note: maybe need modify.(ipv6_6to4_lan_prefix.toUpperCase() + "::1/64");
		$('#lan_ipv6_6to4_ip_prefix').html(ipv6_6to4_lan_prefix.toUpperCase());
		//$('#ipv6_lan_ip').val(lan_ipv6_static_addr);
		$('#ipv6_6to4_lan_ip_subnet').val(ipv6_6to4_lan_subnet);
		//Address autoconfiguration settings
		$('#ipv6_autoconfig_type')[0].selectedIndex = main.config_val("lanIPv6Cfg_AutoConfigurationType_"); 

		if(main.config_val('igdIPv6Status_IPv4LifeTime_')=="0")
		{
			$('#ipv6_adver_lifetime').val("10080");
			$('#ipv6_dhcpd_lifetime').val("10080");
		}else{
			$('#ipv6_adver_lifetime').val(main.config_val('igdIPv6Status_IPv4LifeTime_'));
			$('#ipv6_dhcpd_lifetime').val(main.config_val('igdIPv6Status_IPv4LifeTime_'));
		}

		set_ipv6_autoconf_range_6to4();
		set_ipv6_autoconfiguration_type();
		
		//$('#ipv6_lan_ip').attr('disabled',true);
		$('#ipv6_adver_lifetime').attr('disabled',true);
		$('#ipv6_dhcpd_lifetime').attr('disabled',true);
	}

	function GetValue_LinkLocal()
	{
        $('#lan_link_local_ip').html(lan_ipv6_linklocal_addr.toUpperCase()+"/64");
        if(enable_ula !=0)
		{
        	$('#lan_ula_ip').html(lan_ipv6_ula_addr.toUpperCase());
			$('#LanIpULAIp_tr').show();
		}else
			$('#LanIpULAIp_tr').hide();
	}

	function ManuallyDNS()
	{
		var chk_manually = $("#DNSSelect").val();
		
		if (chk_manually == 0)
		{
			$('#DnsPri_tr').hide();
			$('#DnsSec_tr').hide();
			$('#ipv6_primary_dns').val();
			$('#ipv6_secondary_dns').val();
		}else{
			$('#DnsPri_tr').show();
			$('#DnsSec_tr').show();
		}
		set_selectIndex(chk_manually, $('#DNSSelect')[0]);
	}

    function set_ipv6_stateful_range()
    {
		var prefix_length = 64;
		var ipv6_lan_ip = $('#ipv6_lan_ip').val();
		var correct_ipv6="";
		if(ipv6_lan_ip != "")
		{
			correct_ipv6 = get_stateful_ipv6(ipv6_lan_ip);
			$('#ipv6_addr_range_start_prefix').val(get_stateful_prefix(correct_ipv6,prefix_length));
			$('#ipv6_addr_range_end_prefix').val(get_stateful_prefix(correct_ipv6,prefix_length));
		}
		$('#ipv6_addr_range_start_suffix').val(get_stateful_suffix(ipv6_dhcpd_start_range));
		$('#ipv6_addr_range_end_suffix').val(get_stateful_suffix(ipv6_dhcpd_end_range));
    }

	function set_ipv6_autoconfiguration_type()
	{
		var mode = $('#ipv6_autoconfig_type')[0].selectedIndex;
		var w_proto = parseInt($("#ipv6_w_proto option:selected").val(),10);
		switch(mode){
		case 1: //Stateless
			$('#show_ipv6_addr_range_start').hide();
			$('#show_ipv6_addr_range_end').hide();
			$('#show_ipv6_addr_lifetime').hide();
			$('#show_router_advert_lifetime').show();
			break;
		case 2: //Stateful DHCPv6
			if (w_proto == 5)
				set_ipv6_autoconf_range_6to4();
			else
				set_ipv6_autoconf_range();
			$('#show_ipv6_addr_range_start').show();
			$('#show_ipv6_addr_range_end').show();
			$('#show_ipv6_addr_lifetime').show();
			$('#show_router_advert_lifetime').hide();
			break;
		default:
			$('#show_ipv6_addr_range_start').hide();
			$('#show_ipv6_addr_range_end').hide();
			$('#show_ipv6_addr_lifetime').hide();
			$('#show_router_advert_lifetime').show();
			break;
		}
	}

    function onPageLoad()
	{
		set_selectIndex(ipv6_type, $('#ipv6_w_proto')[0]);
		change_ipv6_type();
		set_form_default_values("form1");
    }

	function submit_all()
	{
		var w_proto = parseInt($("#ipv6_w_proto option:selected").val(),10);

		switch(w_proto)
		{
			case 1:
				submit_static();
				break;
			case 2:
				submit_autoconf();
				break;
			case 3:
				submit_pppoe();
				break;
			case 5:
				submit_6to4();
				break;
			case 7:
				submit_linklocal();
				break;
			default:
				break;
		}
	}

	function check_dns_addr()
	{
		var primary_dns = $('#ipv6_primary_dns').val();
		var v6_primary_dns_msg = replace_msg(all_ipv6_addr_msg,get_words('DNS_ADDRESS_DESC',LangMap.msg));
		var secondary_dns = $('#ipv6_secondary_dns').val();
		var v6_secondary_dns_msg = replace_msg(all_ipv6_addr_msg,get_words('SEC_DNS_ADDRESS_DESC',LangMap.msg));

		//check DNS Address
		if (primary_dns != ""){
			if(primary_dns != "0:0:0:0:0:0:0:0"){
				if(check_ipv6_symbol(primary_dns,"::")==2){ // find two '::' symbol
					return false;
				}else if(check_ipv6_symbol(primary_dns,"::")==1){    // find one '::' symbol
					temp_ipv6_primary_dns = new ipv6_addr_obj(primary_dns.split("::"), v6_primary_dns_msg, false, false);
					if (!check_ipv6_address(temp_ipv6_primary_dns ,"::"))
						return false;
				}else{	//not find '::' symbol
					temp_ipv6_primary_dns  = new ipv6_addr_obj(primary_dns.split(":"), v6_primary_dns_msg, false, false);
					if (!check_ipv6_address(temp_ipv6_primary_dns,":"))
						return false;
				}
			}
		}
		if (secondary_dns != ""){
			if(secondary_dns != "0:0:0:0:0:0:0:0"){
				if(check_ipv6_symbol(secondary_dns,"::")==2){ // find two '::' symbol
					return false;
				}else if(check_ipv6_symbol(secondary_dns,"::")==1){    // find one '::' symbol
					temp_ipv6_secondary_dns = new ipv6_addr_obj(secondary_dns.split("::"), v6_secondary_dns_msg, false, false);
					if (!check_ipv6_address(temp_ipv6_secondary_dns ,"::"))
						return false;
				}else{	//not find '::' symbol
					temp_ipv6_secondary_dns  = new ipv6_addr_obj(secondary_dns.split(":"), v6_secondary_dns_msg, false, false);
					if (!check_ipv6_address(temp_ipv6_secondary_dns,":"))
						return false;
				}
			}
		}
		return true;
	}

	function check_lan_ip_addr()
	{
		var ipv6_lan = $('#ipv6_lan_ip').val();
		var ipv6_lan_msg = replace_msg(all_ipv6_addr_msg,get_words('IPV6_TEXT46'));
		var temp_ipv6_lan = new ipv6_addr_obj(ipv6_lan.split(":"), ipv6_lan_msg, false, false);
	
		if(check_ipv6_symbol(ipv6_lan,"::")==2){ // find two '::' symbol
			return false;
		}else if(check_ipv6_symbol(ipv6_lan,"::")==1){    // find one '::' symbol
			temp_ipv6_lan = new ipv6_addr_obj(ipv6_lan.split("::"), ipv6_lan_msg, false, false);
			if (!check_ipv6_address(temp_ipv6_lan ,"::"))
				return false;
		}else{  //not find '::' symbol
			temp_ipv6_lan  = new ipv6_addr_obj(ipv6_lan.split(":"), ipv6_lan_msg, false, false);
			if (!check_ipv6_address(temp_ipv6_lan,":"))
				return false;
		}
		return true;
	}

    function send_request()
    {
		var w_proto = parseInt($("#ipv6_w_proto option:selected").val(),10);

		switch(w_proto)
		{
			case 1: 
				var ipv6_static = $('#ipv6_static_wan_ip').val();
				var ipv6_static_msg = replace_msg(all_ipv6_addr_msg,get_words('IPV6_TEXT43'));
				var temp_ipv6_static = new ipv6_addr_obj(ipv6_static.split(":"), ipv6_static_msg, false, false);
				var prefix_length_msg = replace_msg(check_num_msg, get_words('IPV6_TEXT74'), 1, 128);
				var prefix_length_obj = new varible_obj($('#ipv6_static_prefix_length').val(), prefix_length_msg, 1, 128, false);
				var ipv6_static_gw = $('#ipv6_static_default_gw').val();
				var ipv6_static_gw_msg = replace_msg(all_ipv6_addr_msg,get_words('_defgw'));
				var temp_ipv6_static_gw = new ipv6_addr_obj(ipv6_static_gw.split(":"), ipv6_static_gw_msg, false, false);
				
				if (get_checked_value($('#ipv6_use_link_local_sel')[0]) == 0)	//silvia add
				{
					// check the ipv6 address
					if(check_ipv6_symbol(ipv6_static,"::")==2){ // find two '::' symbol
						return false;
					}else if(check_ipv6_symbol(ipv6_static,"::")==1){    // find one '::' symbol
						temp_ipv6_static = new ipv6_addr_obj(ipv6_static.split("::"), ipv6_static_msg, false, false);
						if (!check_ipv6_address(temp_ipv6_static,"::"))
							return false;
					}else{  //not find '::' symbol
						temp_ipv6_static = new ipv6_addr_obj(ipv6_static.split(":"), ipv6_static_msg, false, false);
						if (!check_ipv6_address(temp_ipv6_static,":"))
							return false;
					}
				}

				//check the Subnet Prefix Length
				if (!check_varible(prefix_length_obj))
					return false;

				//check Default Gateway
				if(check_ipv6_symbol(ipv6_static_gw,"::")==2){ // find two '::' symbol
					return false;
				}else if(check_ipv6_symbol(ipv6_static_gw,"::")==1){    // find one '::' symbol
					temp_ipv6_static_gw = new ipv6_addr_obj(ipv6_static_gw.split("::"), ipv6_static_gw_msg, false, false);
					if (!check_ipv6_address(temp_ipv6_static_gw,"::"))
						return false;
				}else{  //not find '::' symbol
					temp_ipv6_static_gw = new ipv6_addr_obj(ipv6_static_gw.split(":"), ipv6_static_gw_msg, false, false);
					if (!check_ipv6_address(temp_ipv6_static_gw,":"))
						return false;
				}
				break;
			case 2:
				break;
			case 3:
				/**
				**    Date:		2013-02-06
				**    Author:	Silvia Chang
				**    Reason:	1. Fix pppoe connection can not be save issue
				**				2. add clone_ipv4_pppoe() for PPPoE Session
				**/
				set_ipv6_autoconf_range();
				var pppoe_share = get_by_name("ipv6_pppoe_share");
				var pppoe_dynamic = $("#ipv6_pppoeSelcet option:selected").val();
				var pppoe_ip = $('#ipv6_pppoe_ipaddr').val();
				var pppoe_ip_msg = replace_msg(all_ip_addr_msg,get_words('IPV6_TEXT43'));
				var temp_pppoe_ip = new ipv6_addr_obj(pppoe_ip.split(":"), pppoe_ip_msg, false, false);
				var mtu_msg = replace_msg(check_num_msg, "MTU", 1300, 1492);
				var mtu_obj = new varible_obj($('#ipv6_pppoe_mtu').val(), mtu_msg , 1300, 1492, false);

				if (pppoe_share[0].checked){
					if(wan_type != 2){
						alert(get_words('IPV6_TEXT161'));
						return false;
					}
				}
				
				$('#ipv6_dhcp_pd_enable').val(get_checked_value($('#EnDhcpPD')[0]));
				$('#ipv6_wan_proto').val($('#ipv6_w_proto').val());
				$('#ipv6_pppoe_dns_enable').val($("#DNSSelect").val());

				//check the PPPoE IP Address
				if (pppoe_dynamic == 1){
					if(check_ipv6_symbol(pppoe_ip,"::")==2){ // find two '::' symbol
						return false;
					}else if(check_ipv6_symbol(pppoe_ip,"::")==1){    // find one '::' symbol
						temp_pppoe_ip = new ipv6_addr_obj(pppoe_ip.split("::"), pppoe_ip_msg, false, false);
						if (!check_ipv6_address(temp_pppoe_ip ,"::"))
							return false;
					}else{	//not find '::' symbol
						temp_pppoe_ip  = new ipv6_addr_obj(pppoe_ip.split(":"), pppoe_ip_msg, false, false);
						if (!check_ipv6_address(temp_pppoe_ip,":")){
							return false;
						}
					}
				}
				if(!pppoe_share[0].checked){
					if ($('#ipv6_pppoe_username').val() == "") {
						alert(get_words('PPP_USERNAME_EMPTY', LangMap.msg));
						return false;
					}
					//check the password match
					if (!check_pwd("ipv6_pppoe_password_s", "ipv6_pppoe_password_v"))
						return false;

					//check the MTU value
					if (!check_varible(mtu_obj))
						return false;
				}
				break;
			case 5:
				set_ipv6_autoconf_range_6to4();
				var ipv6_lan_ip_subnet = $('#ipv6_6to4_lan_ip_subnet').val();
				var v6_6to4_relay = $('#ipv6_6to4_relay').val();
				var v6_6to4_relay_msg = replace_msg(all_ip_addr_msg,get_words('_6to4RELAY'));
				var v6_6to4_relay_obj = new addr_obj(v6_6to4_relay.split("."), v6_6to4_relay_msg, false, false);
				
				if(trim(v6_6to4_relay)=="")
				{
					alert(get_words('IPv6_6to4_relay'));
					return false;
				}
				/*
				** Date:	2013-03-19
				** Author:	Moa Chung
				** Reason:	Network → IPV6 setting → 6to4：Do not check the value of Relay Server IP. Ex : "192.168.2.1111"
				** Note:	TEW-810DR pre-test no.109
				**/
				if(!check_address(v6_6to4_relay_obj))
					return false;
				if(is_ipv4_valid(v6_6to4_relay))
					ipv6_6to4_tunnel_addr_type = 0;
				else
					ipv6_6to4_tunnel_addr_type = 1;

				//set the LAN IPv6
				$('#ipv6_lan_ip').val(ipv6_6to4_lan_prefix + $('#ipv6_6to4_lan_ip_subnet').val() + "::1");

				//check the subnet of LAN IPv6
				if (!check_lan_ipv6_subnet(ipv6_lan_ip_subnet,get_words('IPV6_TEXT46')))
					return false;

				break;
			case 7:
				if (submit_button_flag == 0) {
					submit_button_flag = 1;
					submit_all();
					return true;
				}
				break;
			default:
				break;
		}

		var check_mode = $('#ipv6_autoconfig_type')[0].selectedIndex;
		var addr_lifetime_msg = replace_msg(check_num_msg, get_words('IPV6_TEXT68'), 1, 999999);
		var addr_lifetime_obj = new varible_obj($('#ipv6_dhcpd_lifetime').val(), addr_lifetime_msg, 1, 999999, false);
		var adver_lifetime_msg = replace_msg(check_num_msg, get_words('IPV6_TEXT69'), 1, 999999);
		var adver_lifetime_obj = new varible_obj($('#ipv6_adver_lifetime').val(), adver_lifetime_msg , 1, 999999, false);
		var ipv6_addr_s_suffix = $('#ipv6_addr_range_start_suffix').val();
		var ipv6_addr_e_suffix = $('#ipv6_addr_range_end_suffix').val();

		//check DNS Address, check LAN IP Address
		if (w_proto == 2 || w_proto == 3)
		{
			if($("#DNSSelect").val() == 1)
			{
				if (!check_dns_addr())
					return false;
			}
			if(!$('#EnDhcpPD')[0].checked)
			{
				if (!check_lan_ip_addr())
					return false;
			}
		}else{
			if (!check_lan_ip_addr())
				return false;

			if (!check_dns_addr())
				return false;
		}

		if(check_mode == 2 && enable_autoconfig == 1){
			//check the suffix of Address Range(Start)
			if(!check_ipv6_address_suffix(ipv6_addr_s_suffix,get_words('IPv6_addrSr')))
				return false;
			//check the suffix of Address Range(End)
			if(!check_ipv6_address_suffix(ipv6_addr_e_suffix,get_words('IPv6_addrEr')))
				return false;
			//compare the suffix of start and the suffix of end
			if(!compare_suffix(ipv6_addr_s_suffix,ipv6_addr_e_suffix))
					return false;
			//check the IPv6 Address Lifetime
			
			if(!$('#EnDhcpPD').attr('checked'))
			{
				if (!check_varible(addr_lifetime_obj))
					return false;
			}
			//set autoconfiguration range value
			$('#ipv6_dhcpd_start').val($('#ipv6_addr_range_start_prefix').val() + "::" + $('#ipv6_addr_range_start_suffix').val());
			$('#ipv6_dhcpd_end').val($('#ipv6_addr_range_end_prefix').val() + "::" + $('#ipv6_addr_range_end_suffix').val());
		}
		else if(enable_autoconfig == 1 )
		{
			//check the Router Advertisement Lifetime

			if (w_proto == 2 || w_proto == 3)
			{
				if(!$('#EnDhcpPD')[0].checked)
				{
					if (!check_varible(adver_lifetime_obj))
						return false;
				}
			}else if (w_proto ==1){
				if (!check_varible(adver_lifetime_obj))
					return false;
			}
		}

        if (submit_button_flag == 0) {
            submit_button_flag = 1;
			submit_all();
            return true;
        }
        return false;
    }

	function submit_static()
	{
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('internet_ipv6.asp');
		
		obj.add_param_arg('wanDev_CurrentConnObjType6_','1.1.0.0','1');
		obj.add_param_arg('ipv6StaticConn_ExternalIPAddress_','1.1.1.0',$('#ipv6_static_wan_ip').val());
		obj.add_param_arg('ipv6StaticConn_SubnetPrefixLength_','1.1.1.0',$('#ipv6_static_prefix_length').val());
		obj.add_param_arg('ipv6StaticConn_UseLinkLocalAddress_','1.1.1.0',get_checked_value($('#ipv6_use_link_local_sel')[0]));
		obj.add_param_arg('ipv6StaticConn_DefaultGateway_','1.1.1.0',$('#ipv6_static_default_gw').val());
		obj.add_param_arg('ipv6StaticConn_PrimaryDNSAddress_','1.1.1.0',$('#ipv6_primary_dns').val());
		obj.add_param_arg('ipv6StaticConn_SecondaryDNSAddress_','1.1.1.0',$('#ipv6_secondary_dns').val());
		obj.add_param_arg('lanIPv6Cfg_StatictLanAddress_','1.1.1.0',$('#ipv6_lan_ip').val());
		obj.add_param_arg('lanIPv6Cfg_AutoConfigurationType_','1.1.1.0',$('#ipv6_autoconfig_type')[0].selectedIndex);
		obj.add_param_arg('lanIPv6Cfg_AdvertisementLifetime_','1.1.1.0',$('#ipv6_adver_lifetime').val());
		obj.add_param_arg('lanIPv6Cfg_DHCPv6AddressRangeStart_','1.1.1.0',$('#ipv6_addr_range_start_prefix').val() + "::" + $('#ipv6_addr_range_start_suffix').val());
		obj.add_param_arg('lanIPv6Cfg_DHCPv6AddressRangeEnd_','1.1.1.0',$('#ipv6_addr_range_end_prefix').val() + "::" + $('#ipv6_addr_range_end_suffix').val());
		obj.add_param_arg('lanIPv6Cfg_IPv6AddressLifeTime_','1.1.1.0',$('#ipv6_dhcpd_lifetime').val());
		//obj.add_param_arg('lanIPv6Cfg_AutoV6AddressAssignEnable_','1.1.1.0','1');
		//Silvia note: defconfig = 1, we do not change this value on TEW-810DR always = 1
		
		var paramStatic = obj.get_param();

		totalWaitTime = 18; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(paramStatic.url, paramStatic.arg);
	}

	function submit_pppoe()
	{
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('internet_ipv6.asp');
		
		obj.add_param_arg('wanDev_CurrentConnObjType6_','1.1.1.0','3');
		obj.add_param_arg('ipv6PPPoEConn_SessionType_','1.1.1.0',get_radio_value(get_by_name("ipv6_pppoe_share")));
		obj.add_param_arg('ipv6PPPoEConn_IPAddressType_','1.1.1.0',$("#ipv6_pppoeSelcet option:selected").val());
		obj.add_param_arg('ipv6PPPoEConn_Username_','1.1.1.0',$('#ipv6_pppoe_username').val());
		obj.add_param_arg('ipv6PPPoEConn_ExternalIPAddress_','1.1.1.0',$('#ipv6_pppoe_ipaddr').val());
		obj.add_param_arg('ipv6PPPoEConn_Password_','1.1.1.0',$('#ipv6_pppoe_password_s').val());
		obj.add_param_arg('ipv6PPPoEConn_MaxMTUSize_','1.1.1.0',$('#ipv6_pppoe_mtu').val());
		//obj.add_param_arg('ipv6PPPoEConn_IPv6PrefixLength_','1.1.1.0',$('#ipv6_pppoe_prefixlen').val());
		obj.add_param_arg('ipv6PPPoEConn_AutomaticDNSServer_','1.1.1.0',$("#DNSSelect").val());
		obj.add_param_arg('ipv6PPPoEConn_PrimaryDNSAddress_','1.1.1.0',$('#ipv6_primary_dns').val());
		obj.add_param_arg('ipv6PPPoEConn_SecondaryDNSAddress_','1.1.1.0',$('#ipv6_secondary_dns').val());

		obj.add_param_arg('lanIPv6Cfg_DHCPPDEnable_','1.1.1.0',get_checked_value($('#EnDhcpPD')[0]));
		obj.add_param_arg('lanIPv6Cfg_PPPoELanAddress_','1.1.1.0',$('#ipv6_lan_ip').val());
		obj.add_param_arg('lanIPv6Cfg_AdvertisementLifetime_','1.1.1.0',$('#ipv6_adver_lifetime').val());
		obj.add_param_arg('lanIPv6Cfg_AutoConfigurationType_','1.1.1.0',$('#ipv6_autoconfig_type')[0].selectedIndex);
		obj.add_param_arg('lanIPv6Cfg_DHCPv6AddressRangeStart_','1.1.1.0',$('#ipv6_addr_range_start_prefix').val() + "::" + $('#ipv6_addr_range_start_suffix').val());
		obj.add_param_arg('lanIPv6Cfg_DHCPv6AddressRangeEnd_','1.1.1.0',$('#ipv6_addr_range_end_prefix').val() + "::" + $('#ipv6_addr_range_end_suffix').val());
		obj.add_param_arg('lanIPv6Cfg_IPv6AddressLifeTime_','1.1.1.0',$('#ipv6_dhcpd_lifetime').val());
		//obj.add_param_arg('lanIPv6Cfg_AutoDHCPPDEnable_','1.1.1.0','1');
		//Silvia note: defconfig = 1, we do not change this value on TEW-810DR always = 1
		var paramPppoe = obj.get_param();
		
		totalWaitTime = 18; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(paramPppoe.url, paramPppoe.arg);
	}
	
	function submit_autoconf()
	{
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('internet_ipv6.asp');
		
		obj.add_param_arg('wanDev_CurrentConnObjType6_','1.1.0.0','2');
		obj.add_param_arg('ipv6AutoConfConn_AutomaticDNSServer_','1.1.1.0',$("#DNSSelect").val());
		obj.add_param_arg('ipv6AutoConfConn_PrimaryDNSAddress_','1.1.1.0',$('#ipv6_primary_dns').val());
		obj.add_param_arg('ipv6AutoConfConn_SecondaryDNSAddress_','1.1.1.0',$('#ipv6_secondary_dns').val());
		obj.add_param_arg('lanIPv6Cfg_DHCPPDEnable_','1.1.1.0',get_checked_value($('#EnDhcpPD')[0]));
		obj.add_param_arg('lanIPv6Cfg_AdvertisementLifetime_','1.1.1.0',$('#ipv6_adver_lifetime').val());
		obj.add_param_arg('lanIPv6Cfg_AutoConfigurationLanAddress_','1.1.1.0',$('#ipv6_lan_ip').val());
		obj.add_param_arg('lanIPv6Cfg_AutoConfigurationType_','1.1.1.0',$('#ipv6_autoconfig_type')[0].selectedIndex);
		obj.add_param_arg('lanIPv6Cfg_DHCPv6AddressRangeStart_','1.1.1.0',$('#ipv6_addr_range_start_prefix').val() + "::" + $('#ipv6_addr_range_start_suffix').val());
		obj.add_param_arg('lanIPv6Cfg_DHCPv6AddressRangeEnd_','1.1.1.0',$('#ipv6_addr_range_end_prefix').val() + "::" + $('#ipv6_addr_range_end_suffix').val());
		obj.add_param_arg('lanIPv6Cfg_IPv6AddressLifeTime_','1.1.1.0',$('#ipv6_dhcpd_lifetime').val());
		//obj.add_param_arg('lanIPv6Cfg_AutoV6AddressAssignEnable_','1.1.1.0','1');
		//obj.add_param_arg('lanIPv6Cfg_AutoDHCPPDEnable_','1.1.1.0','1');
		//Silvia note: defconfig = 1, we do not change this value on TEW-810DR always = 1
		var paramAutoconf = obj.get_param();
		
		totalWaitTime = 18; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(paramAutoconf.url, paramAutoconf.arg);
	}

	function submit_linklocal()
	{
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('internet_ipv6.asp');
		
		obj.add_param_arg('wanDev_CurrentConnObjType6_','1.1.0.0','7');
		var param1 = obj.get_param();
		
		totalWaitTime = 18; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(param1.url, param1.arg);
	}
	
	function submit_6to4()
	{
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('internet_ipv6.asp');
		
		obj.add_param_arg('wanDev_CurrentConnObjType6_','1.1.0.0','5');
		obj.add_param_arg('ipv66to4Conn_TunnelRelayType_','1.1.1.0',ipv6_6to4_tunnel_addr_type);
		if(ipv6_6to4_tunnel_addr_type == 0)
			obj.add_param_arg('ipv66to4Conn_Tunnel6to4RelayAddress_','1.1.1.0',$('#ipv6_6to4_relay').val());
		else
			obj.add_param_arg('ipv66to4Conn_Tunnel6to4RelayDomain_','1.1.1.0',$('#ipv6_6to4_relay').val());
		obj.add_param_arg('ipv66to4Conn_PrimaryDNSAddress_','1.1.1.0',$('#ipv6_primary_dns').val());
		obj.add_param_arg('ipv66to4Conn_SecondaryDNSAddress_','1.1.1.0',$('#ipv6_secondary_dns').val());
		obj.add_param_arg('lanIPv6Cfg_AdvertisementLifetime_','1.1.1.0',$('#ipv6_adver_lifetime').val());
		obj.add_param_arg('lanIPv6Cfg_AutoConfigurationLanAddress_','1.1.1.0',$('#ipv6_lan_ip').val());
		obj.add_param_arg('lanIPv6Cfg_Tunnel6to4LanAddress_','1.1.1.0',ipv6_6to4_lan_prefix + $('#ipv6_6to4_lan_ip_subnet').val() + "::1");
		obj.add_param_arg('lanIPv6Cfg_AutoConfigurationType_','1.1.1.0',$('#ipv6_autoconfig_type')[0].selectedIndex);
		obj.add_param_arg('lanIPv6Cfg_IPv6AddressLifeTime_','1.1.1.0',$('#ipv6_dhcpd_lifetime').val());
		obj.add_param_arg('lanIPv6Cfg_DHCPv6AddressRangeStart_','1.1.1.0',$('#ipv6_addr_range_start_prefix').val() + "::" + $('#ipv6_addr_range_start_suffix').val());
		obj.add_param_arg('lanIPv6Cfg_DHCPv6AddressRangeEnd_','1.1.1.0',$('#ipv6_addr_range_end_prefix').val() + "::" + $('#ipv6_addr_range_end_suffix').val());
		//obj.add_param_arg('lanIPv6Cfg_AutoV6AddressAssignEnable_','1.1.1.0',1);
		//obj.add_param_arg('lanIPv6Cfg_AutoDHCPPDEnable_','1.1.1.0',1);
		//Silvia note: defconfig = 1, we do not change this value on TEW-810DR always = 1
		var param6to4 = obj.get_param();
		
		totalWaitTime = 18; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(param6to4.url, param6to4.arg);
	}

	function trim(stringToTrim) {
		return stringToTrim.replace(/^\s+|\s+$/g,"");
	}

	function dec2hex(dec)
	{
		var hex_array0, hex_array1;
		hex_array0 = dec/16;
		hex_array1 = dec%16;
		hex_array0 = hex_array0 - hex_array1/16;
		return hexchar(hex_array0)+hexchar(hex_array1);
	}

	function hexchar(c)
	{
		return c.toString(16);
	}
$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(dev_info.model);
});
</script>
</head>
<body>
<div class="wrapper">
<table border="0" width="950" cellpadding="0" cellspacing="0" align="center">
<!-- banner and model description-->
<tr>
	<td class="header_1">
		<table border="0" cellpadding="0" cellspacing="0" style="position:relative;width:920px;top:8px;" class="maintable">
		<tr>
			<td valign="top"><img src="/image/logo.png" /></td>
			<td id="product_desc" align="right" valign="middle" class="description" style="width:600px;line-height:1.5em;"></td>
		</tr>
		</table>
	</td>
</tr>
<!-- End of banner and model description-->

<tr>
	<td>
		<table border="0" cellpadding="0" cellspacing="0" style="position:relative;width:950px;top:10px;margin-left:5px;" class="maintable">
		<!-- upper frame -->
		<tr>
			<td><img src="/image/bg_topl.gif" width="270" height="7" /></td>
			<td><img src="/image/bg_topr_01.gif" width="680" height="7" /></td>
		</tr>
		<!-- End of upper frame -->

		<tr>
			<!-- left menu -->
			<td style="background-image:url('/image/bg_l.gif');background-repeat:repeat-y;vertical-align:top;" width="270">
				<div style="padding-left:6px;">
				<script>document.write(menu.build_structure(1,1,3))</script>
				<p>&nbsp;</p>
				</div>
				<img src="/image/bg_l.gif" width="270" height="5" />
			</td>
			<!-- End of left menu -->

			<td style="background-image:url('/image/bg_r.gif');background-repeat:repeat-y;vertical-align:top;" width="680">
				<img src="/image/bg_topr_02.gif" width="680" height="5" />
				<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td valign="top" style="width:650px;padding-left:10px;">
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td valign="top">
						<iframe class="rebootRedirect" name="rebootRedirect" id="rebootRedirect" frameborder="0" width="1" height="1" scrolling="no" src="" style="visibility: hidden;">redirect</iframe>
						<div id="waitform"></div>
						<div id="waitPad" style="display: none;"></div>
						<div id="mainform">
								<!-- main content -->
								<div class="headerbg" id="setmanTitle">
								<script>show_words('_net_ipv6_01');</script>
								</div>
								<div class="hr"></div>
								<div class="section_content_border">
								<div class="header_desc" id="wanIntroduction">
									<script>show_words('_net_ipv6_02');</script>
									<p></p>
								</div>

							<form id="form1" name="form1" method="post" action="">
								<input type="hidden" id="ipv6_autoconfig" name="ipv6_autoconfig" value="" />
								<input type="hidden" id="ipv6_dhcpd_start" name="ipv6_dhcpd_start" value="" />
								<input type="hidden" id="ipv6_dhcpd_end" name="ipv6_dhcpd_end" value="" />
								<input type="hidden" id="ipv6_wan_proto" name="ipv6_wan_proto" value="" />
								<input type="hidden" id="ipv6_addr_range_start_prefix" name="ipv6_addr_range_start_prefix" value="" />
								<input type="hidden" id="ipv6_addr_range_end_prefix" name="ipv6_addr_range_end_prefix" value="" />
								<input type="hidden" maxLength=80 size=80 name="link_local_ip_w" id="link_local_ip_w" value="" />
								<input type="hidden" id="page_type" name="page_type" value="IPv6" />
								<p>
								
								
								<div class="box_tn">
									<div class="CT"><script>show_words('IPV6_TEXT29a');</script></div>
									<table cellspacing="0" cellpadding="0" class="formarea">
									<tr>
										<td class="CL"><script>show_words('IPV6_TEXT29a')</script></td>
										<td class="CR">
											<select name="ipv6_w_proto" id="ipv6_w_proto" onChange='change_ipv6_type()'>
												<option value="1"><script>show_words('_net_ipv6_12')</script></option>	<!--Static-->
												<option value="2"><script>show_words('IPV6_TEXT107')</script></option>	<!--Autoconfiguration-->
						<!--					<option value="1"><script>show_words('_help_txt82')</script></option>		Stateless DHCPv6-->
						<!--					<option value="2"><script>show_words('_help_txt84')</script></option>		DHCPv6(Stateful)-->
												<option value="7"><script>show_words('_help_txt110')</script></option>	<!--Link-Local-->
												<option value="3"><script>show_words('_PPPoE')</script></option>			<!--PPPoE-->
												<option value="5"><script>show_words('IPV6_TEXT36')</script></option>		<!--6to4-->
											</select>
										</td>
									</tr>
									</table>
								</div>
								<br>

						<!-------------- static Settings -------------->
								<div class="box_tn" id="static_setting">
									<div class="CT"><script>show_words('_net_ipv6_03');</script></div>
									<table cellspacing="0" cellpadding="0" class="formarea">
									<tr>
										<td class="CL"><script>show_words('IPV6_TEXT104')</script></td>
										<td class="CR">
											<input name="ipv6_use_link_local_sel" type="checkbox" id="ipv6_use_link_local_sel" value="1" onClick="use_wan_link_local_selector(this.checked);" />
										</td>
									</tr>
									<tr>
										<td class="CL"><script>show_words('TEXT071')</script></td>
										<td class="CR">
											<input type="text" id="ipv6_static_wan_ip" name="ipv6_static_wan_ip" size="45" maxlength="63" value="" />
										</td>
									</tr>
									<tr>
										<td class="CL"><script>show_words('IPV6_TEXT74')</script></td>
										<td class="CR">
											<input type="text" id="ipv6_static_prefix_length" name="ipv6_static_prefix_length" size="5" maxlength="63" value="" />
										</td>
									</tr>
									<tr>
										<td class="CL"><script>show_words('_defgw')</script></td>
										<td class="CR">
											<input type="text" id="ipv6_static_default_gw" name="ipv6_static_default_gw" size="45" maxlength="39" value="" />
										</td>
									</tr>
									</table>
								</div>
						<!-------------- End of static Settings -------------->

						<!-------------- pppoe Settings -------------->
								<div class="box_tn" id="pppoe_setting">
									<div class="CT"><script>show_words('_net_ipv6_08');</script></div>
									<table cellspacing="0" cellpadding="0" class="formarea">
									<tr>
										<td class="CL"><script>show_words('TEXT077')</script></td>
										<td class="CR">
											<input type="radio" name="ipv6_pppoe_share" value="0" onClick="clone_ipv4_pppoe()" checked />
											<script>show_words('IPV6_TEXT129')</script>
											<input type="radio" name="ipv6_pppoe_share" value="1" onClick="clone_ipv4_pppoe()" />
											<script>show_words('TEXT078')</script>
										</td>
									</tr>
									<tr>
										<td class="CL"><script>show_words('_username')</script></td>
										<td class="CR">
											<input type="text" id="ipv6_pppoe_username" name="ipv6_pppoe_username" size="20" maxlength="63" value='' />
										</td>
									</tr>
									<tr>
										<td class="CL"><script>show_words('_password')</script></td>
										<td class="CR">
											<input type="password" id="ipv6_pppoe_password_s" name="ipv6_pppoe_password_00_s" size="20" maxlength="63" onfocus="select();" />
										</td>
									</tr>
									<tr>
										<td class="CL"><script>show_words('_verifypw')</script></td>
										<td class="CR">
											<input type="password" id="ipv6_pppoe_password_v" name="ipv6_pppoe_password_00_v" size="20" maxlength="63" onfocus="select();" />
										</td>
									</tr>
									<tr>
										<td class="CL"><script>show_words('bwn_AM');</script></td>
										<td class="CR">
											<select id="ipv6_pppoeSelcet" name="ipv6_pppoeSelcet" onchange="Manually_poeip()">
												<option value="1"><script>show_words('_static');</script></option>
												<option value="0"><script>show_words('KR50');</script></option>
											</select>
										</td>
									</tr>
									
									<tr id="Ipv6pppoeStaticIp">
										<td class="CL"><script>show_words('TEXT071')</script></td>
										<td class="CR">
											<input type="text" id="ipv6_pppoe_ipaddr" name="ipv6_pppoe_ipaddr" size="45" maxlength="45" value='' />
										</td>
									</tr>
									<tr id="Ipv6pppoePrefixLen" style="display:none">
										<td class="CL"><script>show_words('IPV6_TEXT74')</script></td>
										<td class="CR">
											<input type="text" id="ipv6_pppoe_prefixlen" name="ipv6_pppoe_prefixlen" size="3" maxlength="3" value='' />
										</td>
									</tr>
									
									<tr>
										<td class="CL"><script>show_words('_net_ipv6_09');</script></td>
										<td class="CR">
											<select id="useDefMTU_Select" name="useDefMTU_Select" onchange="useDefMTU(this.value);">
												<option value="0"><script>show_words('_disable');</script></option>
												<option value="1"><script>show_words('_enable');</script></option>
											</select>
										</td>
									</tr>
									<tr>
										<td class="CL"><script>show_words('_net_ipv6_10')</script></td>
										<td class="CR">
											<input type="text" id="ipv6_pppoe_mtu" name="ipv6_pppoe_mtu" size="10" maxlength="5" value='' />
											<script>show_words('bwn_bytes')</script>
											<script>show_words('_308')</script> 1492
										</td>
									</tr>			
									</table>
								</div>
						<!-------------- End of pppoe Settings -------------->

						<!-------------- 6to4 Settings -------------->
								<div class="box_tn" id="ipv6to4_setting">	<!--Ipv6ToIpv4-->
									<div class="CT"><script>show_words('_net_ipv6_06');</script></div>
									<table cellspacing="0" cellpadding="0" class="formarea">
									<tr>
										<td class="CL"><script>show_words('_net_ipv6_07')</script></td>
										<td class="CR">
											<b><span id="ipv6_6to4_addr"></span></b>
										</td>
									</tr>
									<tr>
										<td class="CL"><script>show_words('_6to4RELAY')</script></td>
										<td class="CR">
											<input type="text" id="ipv6_6to4_relay" name="ipv6_6to4_relay" size="30" maxlength="39" value='' />
										</td>
									</tr>
									</table>
								</div>
						<!-------------- End of 6rd Settings -------------->
								<div class="box_tn" id="dns_setting">	<!--Ipv6DNSServer-->
									<div class="CT"><script>show_words('_help_txt94');</script></div>
									<table cellspacing="0" cellpadding="0" class="formarea">
									<tr id="DNSSelect_tr">
										<td class="CL"><script>show_words('_net_ipv6_11')</script></td>
										<td class="CR">
											<select name="DNSSelect" id="DNSSelect" size="1" onChange="ManuallyDNS()">
												<option id="DNSDisabled" value="0"><script>show_words('_disable');</script></option>
												<option id="DNSEnabled" value="1"><script>show_words('_enable');</script></option>
											</select></td>
									</tr>
									<tr id="DnsPri_tr">
										<td class="CL"><script>show_words('_dns1')</script></td>
										<td class="CR">
											<input type="text" id="ipv6_primary_dns" name="ipv6_static_primary_dns" size="45" maxlength="39" value="" />
										</td>
									</tr>
									<tr id="DnsSec_tr">
										<td class="CL"><script>show_words('_dns2')</script></td>
										<td class="CR">
											<input type="text" id="ipv6_secondary_dns" name="ipv6_static_secondary_dns" size="45" maxlength="39" value="" />
										</td>
									</tr>
									</table>
								</div>

								<div class="box_tn" id="lan_ip_setting">		<!--LanIpv6-->
									<div class="CT"><script>show_words('_help_txt96');</script></div>
									<table cellspacing="0" cellpadding="0" class="formarea">
									<tr id="LanIpEnDhcpPD_tr">
										<td class="CL"><script>show_words('DHCP_PD_ENABLE')</script></td>
										<td class="CR">
											<input type="checkbox" id="EnDhcpPD" name="EnDhcpPD" value="1" onClick="en_dhcp_pd();" />
										</td>
									</tr>
									<tr id="LanIpAddr_tr">
										<td class="CL"><script>show_words('IPV6_TEXT46')</script></td>
										<td class="CR">
											<input type="text" id="ipv6_lan_ip" name="ipv6_lan_ip" size="45" maxlength="63" />
										</td>
									</tr>
									<tr id="LanIpPrefixLen_6to4_tr">
										<td class="CL"><script>show_words('IPV6_TEXT46')</script></td>
										<td class="CR">
											<b><span id="lan_ipv6_6to4_ip_prefix">&nbsp;</span></b>
											<input type=text id="ipv6_6to4_lan_ip_subnet" name="ipv6_6to4_lan_ip_subnet" size="8" maxlength="4" value='' onChange="set_6to4_ipv6_lan_addr();set_ipv6_autoconf_range()">
											<b>::1</b>
										</td>
									</tr>
									<tr id="LanIpPrefixLen_tr">
										<td class="CL"><script>show_words('_net_ipv6_05')</script></td>
										<td class="CR">
											<span id="lan_ipv6_ip_prefix">&nbsp;</span>
										</td>
									</tr>

									<tr id="LanIpLinkLocalIp_tr">
										<td class="CL"><script>show_words('IPV6_TEXT47')</script></td>
										<td class="CR">
											<span id="lan_link_local_ip"></span>
										</td>
									</tr>
									<tr id="LanIpULAIp_tr" style="display:none">
										<td class="CL"><script>show_words('IPV6_ULA_TEXT08')</script></td>
										<td class="CR">
											<b><span id="lan_ula_ip"></span></b>
										</td>
									</tr>
									</table>
								</div>

								<div class="box_tn" id="autoconf_setting">	<!--LanIPv6Auto-->
									<div class="CT"><script>show_words('_help_txt98');</script></div>
									<table cellspacing="0" cellpadding="0" class="formarea">
									<tr>
										<td class="CL"><script>show_words('IPV6_TEXT51')</script></td>
										<td class="CR">
											<select id="ipv6_autoconfig_type" name="ipv6_autoconfig_type" onChange="set_ipv6_autoconfiguration_type()">
												<option value="rdnss"><script>show_words('_net_ipv6_04')</script></option>
												<option value="stateless"><script>show_words('_help_txt82')</script></option>
												<option value="stateful"><script>show_words('_help_txt84')</script></option>
											</select>
										</td>
									</tr>
									<tr id="show_ipv6_addr_range_start" style="display:none">	<!--LanIpv6DhcpStart_tr-->
										<td class="CL"><script>show_words('IPV6_TEXT54')</script></td>
										<td class="CR">
											::<input type="text" id="ipv6_addr_range_start_suffix" name="ipv6_addr_range_start_suffix" size="5" maxlength="4" />
										</td>
									</tr>
									<tr id="show_ipv6_addr_range_end" style="display:none">		<!--LanIpv6DhcpEnd_tr-->
										<td class="CL"><script>show_words('IPV6_TEXT55')</script></td>
										<td class="CR">
											::<input type="text" id="ipv6_addr_range_end_suffix" name="ipv6_addr_range_end_suffix" size="5" maxlength="4" />
										</td>
									</tr>
									<tr id="show_ipv6_addr_lifetime" style="display:none">		<!--LanIpv6LifeTime_tr-->
										<td class="CL"><script>show_words('IPV6_TEXT56')</script></td>
										<td class="CR">
											<input type="text" id="ipv6_dhcpd_lifetime" name="ipv6_dhcpd_lifetime" size="20" maxlength="6" value="" />
											<script>show_words('_minutes')</script>
										</td>
									</tr>
									<tr id="show_router_advert_lifetime">
										<td class="CL"><script>show_words('IPV6_TEXT57')</script></td>
										<td class="CR">
											<input type="text" id="ipv6_adver_lifetime" name="ipv6_adver_lifetime" size="20" maxlength="15" value="" />
											<script>show_words('_minutes')</script>
										</td>
									</tr>	
									</table>
								</div>

								<div class="box_tn">
								<table cellspacing="0" cellpadding="0" class="formarea">
									<tr align="center">
										<td colspan="2" class="btn_field">
											<input name="button" type="button" class="ButtonSmall" id="button" onClick="return send_request()" />
											<script>$('#button').val(get_words('_adv_txt_17'));</script>
											<input name="button2" type="button" class="ButtonSmall" id="button2" onClick="page_cancel('form1', 'internet_ipv6.asp');" />
											<script>$('#button2').val(get_words('ES_cancel'));</script>
										</td>
									</tr>
								</table>
							</div>

							</form>
							<br/>
							</div>
								<!-- End of main content -->

						</div>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		</td>
	</tr>
		<!-- lower frame -->
		<tr>
			<td><img src="/image/bg_butl.gif" width="270" height="12" /></td>
			<td><img src="/image/bg_butr.gif" width="680" height="12" /></td>
		</tr>
		<!-- End of lower frame -->

		</table>
		<!-- footer -->
		<div class="footer">
			<table border="0" cellpadding="0" cellspacing="0" style="width:920px;" class="maintable">
			<tr>
				<td align="left" valign="top" class="txt_footer">
				<br><script>show_words("_copyright");</script></td>
				<td align="right" valign="top" class="txt_footer">
				<br><a href="http://www.trendnet.com/register" target="_blank"><img src="/image/icons_warranty_1.png" style="border:0px;vertical-align:middle;padding-right:10px;" border="0" /><script>show_words("_warranty");</script></a></td>
			</tr>
			</table>
		</div>
		<!-- end of footer -->

	</td>
</tr>
</table><br/>
</div>
</body>
<script>
	onPageLoad();
</script>
</html>