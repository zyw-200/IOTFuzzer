<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | Administrator | IPv6 Status</title>
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

	var menu = new menuObject();
	menu.setSupportUSB(dev_info.KCode_USB);

	var hw_version 	= dev_info.hw_ver;
	var version 	= dev_info.fw_ver;
	var model		= dev_info.model;
	var login_Info 	= dev_info.login_info;

	var obj;
	
	var wan_addr4;
	var use_linklocal_addr;
	var v6Status = {
		'connType':	0,
		'IPAddressType':	0,
		'netState':	'Disconnected',
		'connupTime': '0',
		'wanAddr':	'',
		'wanIANA':	'',
		'wanStaticIP':	'',
		'gw':		'',
		'priDNS':	'',
		'secDNS':	'',
		'wanLL':	'',
		'lanLL':	'',
		'lanAddr':	'',
		'dhcpPdEn':	0,
		'dhcpPdAddr':	'',
		'dhcpPdLen':	0,
		'WanPrefixLen': 0,
		'WanStaticPrefixLen': 0,
		'LanPrefixLen': 0
	};

	var lanHost = {
		'name':			'',
		'ip':			'',
		'prefix':		''
	};

	function reqStatus() {
		obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('get');
		obj.add_param_arg('IGD_WANDevice_i_IPv6Status_',1100);
		obj.add_param_arg('IGD_LANDevice_i_ConnectedAddressV6_i_',1100);
		obj.add_param_arg('IGD_LANDevice_i_IPv6ConfigManagement_',1100);
		obj.add_param_arg('IGD_Status_WANStatus_',1100);
		obj.add_param_arg('IGD_WANDevice_i_StaticIPv6_',1110);
		obj.add_param_arg('IGD_WANDevice_i_PPPoEv6_i_',1110); //added by Derek 20120504
		obj.get_config_obj();

		wan_addr4 = obj.config_val("igdWanStatus_IPAddress_");
		use_linklocal_addr = filter_ipv6_addr(obj.config_val("ipv6StaticConn_UseLinkLocalAddress_"));
		v6Status.connType = obj.config_val('igdIPv6Status_CurrentConnObjType_');
		v6Status.IPAddressType =obj.config_val('ipv6PPPoEConn_IPAddressType_');//added by Derek 20120504
		v6Status.netState = obj.config_val('igdIPv6Status_NetworkStatus_');
		v6Status.connupTime = obj.config_val('igdIPv6Status_WanUpTime_');
		v6Status.wanAddr = filter_ipv6_addr(obj.config_val('igdIPv6Status_IPAddress_'));
		v6Status.wanIANA = filter_ipv6_addr(obj.config_val('igdIPv6Status_IPAddressIANA_'));
		v6Status.gw = obj.config_val('igdIPv6Status_DefaultGateway_');
		v6Status.priDNS = filter_ipv6_addr(obj.config_val('igdIPv6Status_PrimaryDNSAddress_'));
		v6Status.secDNS = filter_ipv6_addr(obj.config_val('igdIPv6Status_SecondaryDNSAddress_'));
		v6Status.wanLL = filter_ipv6_addr(obj.config_val('igdIPv6Status_IPv6WanLinkLocalAddress_'));
		v6Status.lanLL = filter_ipv6_addr(obj.config_val('igdIPv6Status_IPv6LanLinkLocalAddress_'));
		v6Status.lanAddr = filter_ipv6_addr(obj.config_val('igdIPv6Status_IPv6LanAddress_'));
		v6Status.lanAddr2 = filter_ipv6_addr(obj.config_val('igdIPv6Status_IPv6LanAddress2_'));
		v6Status.dhcpPdEn = obj.config_val("lanIPv6Cfg_DHCPPDEnable_");
		v6Status.dhcpPdAddr = filter_ipv6_addr(obj.config_val('igdIPv6Status_IPv6AddressAssignedByDHCPPD_'));
		v6Status.dhcpPdLen = obj.config_val('igdIPv6Status_IPv6AddressLenAssignedByDHCPPD_');
		v6Status.tunnelAddr = filter_ipv6_addr(obj.config_val('igdIPv6Status_IPv6TunnelAddress_'));
		v6Status.WanPrefixLen = obj.config_val('igdIPv6Status_IPv6WanPrefixLen_');
		v6Status.WanIANAPrefixLen = obj.config_val('igdIPv6Status_IANAPrefix_');
		v6Status.LanPrefixLen = obj.config_val('igdIPv6Status_IPv6LanPrefixLen_');
		v6Status.LanPrefixLen2 = obj.config_val('igdIPv6Status_IPv6LanPrefixLen2_');
		v6Status.wanStaticIP=obj.config_val('igdIPv6Status_StaticIPAddress_');//added by Derek 20120504
		v6Status.WanStaticPrefixLen = obj.config_val('igdIPv6Status_IPv6WanStaticPrefixLen_');
		lanHost.name = obj.config_str_multi("igdLanHostV6Status_HostName_");
		lanHost.ip = obj.config_str_multi("igdLanHostV6Status_HostIPv6Address_");
		lanHost.prefix = obj.config_str_multi("igdLanHostV6Status_HostIPv6Prefix_");
	}

	function onPageLoad()
	{
		reqStatus();
		var connection_type = '';
		ipv6_link_local();
		tr_tunnel_ipv6_addr.style.display="none";
		//v6Status.connType = '5';
		switch(v6Status.connType)
		{
			case "0":   //autodetection
				connection_type = get_words('IPV6_TEXT138');
				show_dhcppd(1);
				get_v6_gw(0);
				if (v6Status.netState == "Disconnected")
					tr_ipv6_show_button.style.display="none";
				tr_ipv6_conn_time.style.display="none";
				break;
			case "1":    //static
				connection_type = get_words('IPV6_TEXT32');
				show_dhcppd(0);
				get_v6_gw(0);
				tr_ipv6_conn_time.style.display="none";
				tr_ipv6_show_button.style.display="none";
				tr_dhcp_pd.style.display="none";
				tr_ipv6_addr_by_dhcppd.style.display="none";
				break;
			case "2":   //autoconfiguration
				connection_type = get_words('IPV6_TEXT171');
				if (v6Status.netState != "Disconnected")
					tr_ipv6_show_button.style.display="";
				show_dhcppd(1);
				get_v6_gw(0);
				break;
			case "3":  //pppoe
				connection_type = get_words('IPV6_TEXT34');
				if (v6Status.netState != "Disconnected")
					tr_ipv6_show_button.style.display="";
				show_dhcppd(1);
				get_v6_gw(0);
				break;
			case "4": //6in4
				connection_type = get_words('IPV6_TEXT35');
				show_dhcppd(1);
				get_v6_gw(0);
				tr_ipv6_show_button.style.display="none";
				tr_ipv6_conn_time.style.display="none";
				tr_tunnel_ipv6_addr.style.display="none";
				//$('#tunnel_ipv6_addr')[0].html() += "/128";
				break;
			case "5":  //6to4
				connection_type = get_words('IPV6_TEXT36');
				change_6to4_format();
				show_dhcppd(0);	
				get_v6_gw(1);
				tr_ipv6_show_button.style.display="none";
				tr_ipv6_conn_time.style.display="none";
				tr_dhcp_pd.style.display="none";
				tr_ipv6_addr_by_dhcppd.style.display="none";
				break;
			case "6":   //6rd
				connection_type = get_words('IPV6_TEXT139');
				show_dhcppd(0);	
				get_v6_gw(1);
				tr_ipv6_show_button.style.display="none";
				tr_ipv6_conn_time.style.display="none";
				tr_tunnel_ipv6_addr.style.display="";
				tr_wan_ipv6_addr.style.display="none";
				tr_dhcp_pd.style.display="none";
				tr_ipv6_addr_by_dhcppd.style.display="none";
				//$('#tunnel_ipv6_addr')[0].html() += "/64";
				break;
			case "7":  //link local
				connection_type = get_words('IPV6_TEXT37');
				tr_ipv6_show_button.style.display="none";
				tr_ipv6_conn_time.style.display="none";
				tr_network_ipv6_status.style.display="none";
				tr_wan_ipv6_addr.style.display="none";
				tr_lan_ipv6_addr.style.display="none";
				tr_primary_ipv6_dns.style.display="none";
				tr_secondary_ipv6_dns.style.display="none";
				tr_wan_ipv6_gw.style.display="none";
				tr_dhcp_pd.style.display="none";
				tr_ipv6_addr_by_dhcppd.style.display="none";
				show_dhcppd(0);	
				break;
			case "8":  //dslite
				connection_type = get_words('IPV6_TEXT140');
				lan_ip = "None";
				break;
			case "9":   //6rdAutoConfig
				connection_type = get_words('IPV6_TEXT172');
				if (v6Status.netState != "Disconnected")
					tr_ipv6_show_button.style.display="";
				show_dhcppd(1);
				get_v6_gw(0);
				tr_tunnel_ipv6_addr.style.display="";
				break;
			default:
				break;
		}
		
		$('#connection_ipv6_type').html(connection_type);
		$('#network_ipv6_status').html(v6Status.netState == "Disconnected" ? get_words('DISCONNECTED'):get_words('CONNECTED'));
		get_wan_time(v6Status.connupTime);
		
		$('#wan_ipv6_addr').html((v6Status.wanAddr == "")?"":(v6Status.wanAddr+"/"+v6Status.WanPrefixLen));

		if(v6Status.IPAddressType=="0" && v6Status.connType!="5"){
				$('#wan_ipv6_addriana').html((v6Status.wanIANA == "")?"":(v6Status.wanIANA+"/"+v6Status.WanIANAPrefixLen));
			}
		else	{
				$('#wan_ipv6_StaticIP').html((v6Status.wanStaticIP == "")?"":(filter_ipv6_addr(v6Status.wanStaticIP)+"/"+v6Status.WanStaticPrefixLen));
		}//modified by Derek 20120504
		
		if(v6Status.connType=="1"){
			if(use_linklocal_addr == "1")
				$('#wan_ipv6_addr').html((v6Status.wanLL == "")?"":(v6Status.wanLL+"/"+v6Status.WanStaticPrefixLen));		//modify by Vic
		}

		$('#primary_ipv6_dns').html((v6Status.priDNS == "")?"":v6Status.priDNS);
		$('#secondary_ipv6_dns').html((v6Status.secDNS == "")?"":v6Status.secDNS);
		$('#lan_ipv6_addr').html((v6Status.lanAddr == "")?"":(v6Status.lanAddr+"/"+v6Status.LanPrefixLen));
		//20120822 pascal add for 6rd two ipv6 address
		if(v6Status.lanAddr2 != "")
		{
			$("#count_ip").attr('rowspan',2);
			$("#tr_lan_ipv6_addr2").show();
			$('#lan_ipv6_addr2').html((v6Status.lanAddr2 == "")?"":(v6Status.lanAddr2+"/"+v6Status.LanPrefixLen2));
		}
		else
		{
			$("#count_ip").attr('rowspan',1);
			$("#tr_lan_ipv6_addr2").hide();
			$('#lan_ipv6_addr2').html("");
		}	
		$('#lan_link_local_ip').html((v6Status.lanLL == "")?"":(v6Status.lanLL+"/64"));

		/*if(v6Status.netState == "Disconnected")
		{
			$('#wan_ipv6_addr').html("");
			$('#wan_ipv6_addriana').html("");
			$('#tunnel_ipv6_addr').html("");
			$('#wan_ipv6_gw').html("");
			$('#primary_ipv6_dns').html("");
			$('#secondary_ipv6_dns').html("");
			$('#lan_ipv6_addr').html("");
			$('#ipv6_addr_by_dhcppd').html("");
		}*/
		//$('#wan_ipv6_addr').html("1111:2222:3333:4444:5555:6666:7777:8888/128");
		//$('#wan_ipv6_addriana').html("2222:2222:2222:2222:2222:2222:2222:2222/128");
		
		print_hostv6();
		
		/*
		// request by Bernie
		if((v6Status.connType=="2")||(v6Status.connType=="9"))
		{
			if(v6Status.gw=="")
			{
				if((v6Status.wanAddr=="")&&(v6Status.wanIANA==""))
				{
					$('#network_ipv6_status').html(get_words('DISCONNECTED'));
					$('#primary_ipv6_dns').html("");
					$('#secondary_ipv6_dns').html("");
					get_by_id("ipv6_conn_time").innerHTML = get_words('_NA');
				}
			}
		}
		*/
		set_control_button();
		setTimeout("onPageLoad()",3000);
	}
	String.prototype.lpad = function(padString, length) {
	var str = this;
	while (str.length < length)
	str = padString + str;
	return str;
	}

	var wTimesec, wan_time;
	var temp, days = 0, hours = 0, mins = 0, secs = 0;
	function caculate_time(){
	
		var wTime = Math.floor(wTimesec);
		var days = Math.floor(wTime / 86400);
			wTime %= 86400;
			var hours = Math.floor(wTime / 3600);
			wTime %= 3600;
			var mins = Math.floor(wTime / 60);
			wTime %= 60;

			wan_time = days + " " + 
				((days <= 1) 
					? get_words('day')
					: get_words('days'))
				+ ", ";
			wan_time += hours + ":" + padout(mins) + ":" + padout(wTime);
		
	}
	function padout(number) {
		return (number < 10) ? '0' + number : number;
	}	
	function get_wan_time(_t){
		wTimesec = parseInt(_t);
		if(wTimesec == 0){
			//wan_time = "N/A";
			wan_time = get_words('_na');
		}else{
			wTimesec = wTimesec/100;
			caculate_time();
			chk_typeAutoConf();
		}
		//get_by_id("network_ipv6_status").innerHTML = "+get_wan_time+"+wTimesec;
	}
	
	function WTime(){
		get_by_id("ipv6_conn_time").innerHTML = wan_time;
		if(wTimesec != 0){
			wTimesec++;
			caculate_time();
			chk_typeAutoConf();
			//get_by_id("network_ipv6_status").innerHTML = "+WTime()+"+wTimesec;
		}
		setTimeout('WTime()', 1000);
	}
	
	
	function h2d(h) {return parseInt(h,16);}

	function h2d2(a,b) {return parseInt(a,16)*16+parseInt(b,16);}

	function ipv6_link_local()
	{
		var u32_pf;
		var ary_ip6rd_pf = [0,0];
		var ary_ip4 = wan_addr4.split(".");
		var u32_ip4 = (ary_ip4[0]*Math.pow(2,24)) + (ary_ip4[1]*Math.pow(2,16)) + (ary_ip4[2]*Math.pow(2,8)) + parseInt(ary_ip4[3]);
		u32_pf = parseInt(u32_ip4);
		str_tmp = u32_pf.toString(16).lpad("0",8);
		ary_ip6rd_pf[0] = str_tmp.substr(0,4);
		ary_ip6rd_pf[1] = str_tmp.substr(4,4);

		/*
		**    Date:		2013-02-26
		**    Author:	Silvia Chang, Vic Liu
		**    Reason:   update for 6rd link local addr when cable is unplug
		**    Note:		sync with DIR-820L
		**/
		if((v6Status.connType=="6")||(v6Status.connType=="9") )
		{
			if(v6Status.netState == "Connected")
				$('#tunnel_ipv6_addr').html("fe80::"+ary_ip6rd_pf[0]+":"+ary_ip6rd_pf[1]+"/64");
			else
				$('#tunnel_ipv6_addr').html("");
		}
	}

	//20120906 pascal add 6to4 IANA adress
	function change_6to4_format()
	{
		var address = obj.config_val('igdIPv6Status_IPAddressIANA_');
		var str="::";
		if(address != "")
			{
				var tmpv6ip =address.split(":");
				for(i=4;i<8;i++)
				{
					tmphex = "";
					tmphex = tmpv6ip[i].split("");
					if(tmphex.length == 1)
						str +=h2d(tmphex[0]);
					else
						str +=h2d2(tmphex[0], tmphex[1]);
					if(i<7)
						str +=".";
				}
				str +="/";
				str +=v6Status.WanIANAPrefixLen;
				$('#wan_ipv6_addriana').html(str);
			}
		else
			$('#wan_ipv6_addriana').html("");
	}
	
	function get_v6_gw(is_v4_gw)
	{

		if(is_v4_gw == 0)
		{
			$('#wan_ipv6_gw').html((v6Status.gw == "")?"":filter_ipv6_addr(v6Status.gw));
		}
		else
		{
			var i, tmphex, decvalue;
			$('#wan_ipv6_gw').html("::");
			//v6Status.gw = '';
			if(v6Status.gw != "")
			{
				var tmpv6gw = v6Status.gw.split(":");
				for(i=4;i<8;i++)
				{
					//alert(tmpv6gw[i]);
					tmphex = "";
					tmphex = tmpv6gw[i].split("");
					if(tmphex.length == 1)
						$('#wan_ipv6_gw')[0].innerHTML +=h2d(tmphex[0]);
					else
						$('#wan_ipv6_gw')[0].innerHTML +=h2d2(tmphex[0], tmphex[1]);
					if(i<7)
						$('#wan_ipv6_gw')[0].innerHTML +=".";
				}
			}
		}
	}

	function show_dhcppd(dhcppd_support)
	{	
		if(dhcppd_support){
			if(v6Status.dhcpPdEn == 1)
			{
				$('#dhcp_pd').html(get_words('_enabled'));
				$('#ipv6_addr_by_dhcppd').html((v6Status.dhcpPdAddr == "")?"":(v6Status.dhcpPdAddr+"/"+v6Status.dhcpPdLen));
			}	
			else
			{
				$('#dhcp_pd').html(get_words('_disabled'));
				$('#ipv6_addr_by_dhcppd').html("");
			}
		}
		else
		{
			$('#dhcp_pd').html(get_words('_disabled'));
			$('#ipv6_addr_by_dhcppd').html("");
		}
	}

	function set_control_button()
	{
		var wan_type = v6Status.connType;
		var commonAction1 = "do_connect()";
		var commonAction2 = "do_disconnect()";

		var button1_name = get_words('_connect');		//_connect;
		var button2_name = get_words('LS315');	//sd_Disconnect;
		var button1_action = commonAction1;
		var button2_action = commonAction2;
		//wan_type = '2';
		switch(wan_type)
		{
			case "0":	//is_autodetect
				break;

			case "1":	//is_static
			case "4":	//is_6in4
			case "5":	//is_6to4
			case "6":	//is_6rd
			case "7":	//is_linklocal
			case "8":	//is_dslite
				return;

			case "2":	//is_autoconf
			case "9":	//is_autoconf_6rd
				button1_name =  get_words('LS312');		//sd_Renew;
				button2_name = get_words('LS313');	//sd_Release;
				break;
				
			case "3":	//is_pppoe
				break;
		}

		$('#ctrl_buttons').html("<input type=\"button\" value=\""+button1_name+"\" name=\"connect\" class=\"button_submit\" id=\"connect\" onClick=\""+button1_action+"\" />&nbsp;<input type=\"button\" value=\""+button2_name+"\" name=\"disconnect\" class=\"button_submit\" id=\"disconnect\" onClick=\""+button2_action+"\" />");
		if(v6Status.netState=="Disconnected")
			$('#disconnect').attr('disabled',true);
		else
			$('#connect').attr('disabled',true);

		//20120215 silvia add if wantype is autoconf renew always enable.
		if ((wan_type == '2')||(wan_type=='9'))
		{
			$('#connect').attr('disabled','');
			//if ((v6Status.wanIANA != "") || ((v6Status.dhcpPdEn == 1) && (v6Status.dhcpPdAddr != "")))
			if (((v6Status.wanIANA == "") && (v6Status.dhcpPdEn == 0))||
			((v6Status.WanIANAPrefixLen == "64") && (v6Status.dhcpPdEn == 0))||
			((v6Status.wanIANA == "") && (v6Status.dhcpPdEn == 1) && (v6Status.dhcpPdAddr == ""))||
			((v6Status.WanIANAPrefixLen == "64") && (v6Status.dhcpPdEn == 1) && (v6Status.dhcpPdAddr == "")))
				$('#disconnect').attr('disabled',true);
			else
				$('#disconnect').attr('disabled','');
		}

	/*	if(wan_type == '3')
		{
			if(v6Status.netState=="Disconnected")
			{
				if((v6Status.wanIANA != "0:0:0:0:0:0:0:0")||(v6Status.dhcpPdAddr != "0:0:0:0:0:0:0:0"))
				{
					$('#connect').attr('disabled',true);
					$('#disconnect').attr('disabled','');
				}
			}			
		}*/
		if (login_Info != "w") {
			$('#connect').attr('disabled',true);
			$('#disconnect').attr('disabled',true);
		}
	}

	function do_connect()
	{
		$('#network_ipv6_status').html(get_words('ddns_connecting'));
		$('#connect').attr('disabled',true);
		var event = new ccpObject();
		event.set_param_url('get_set.ccp');
		event.set_ccp_act('doEvent');
		event.add_param_event('CCP_SUB_DOWANCONNECT_V6');
		event.get_config_obj();
	}

	function do_disconnect()
	{
		$('#network_ipv6_status').html(get_words('ddns_disconnecting'));
		$('#disconnect').attr('disabled',true);
		var event = new ccpObject();
		event.set_param_url('get_set.ccp');
		event.set_ccp_act('doEvent');
		event.add_param_event('CCP_SUB_DOWANDISCONNECT_V6');
		event.get_config_obj();
	}

	function print_hostv6()
	{
		var str = 	'<tr><td class="CTS">'+get_words('IPV6_TEXT0')+'</td>'+
					'<td class="CTS">'+get_words('YM187')+'</td></tr>';

		if (lanHost != null && lanHost.name != null) {
			for(var i=0; i<lanHost.name.length; i++)
			{
				var lanHostip = filter_ipv6_addr(lanHost.ip[i]);
				str += '<tr><td class="CELL">' + lanHostip+ '</td><td class="CELL">' + lanHost.name[i] + '</td></tr>';
			}
		}
		$('#host6_table').html(str);
	}

	function replace_null_to_none(item){
		if(item=="(null)")
			item="none";
		return item;
	}

	//20120822 add by Silvia
	function chk_typeAutoConf()
	{
		if(v6Status.connType == "2")
		{
			if ((((v6Status.wanIANA) != '') || ((v6Status.wanAddr) != '')) && (v6Status.gw) != '')
				$('#network_ipv6_status').html(get_words('CONNECTED'));
			else{
				$('#network_ipv6_status').html(get_words('DISCONNECTED'));
				wan_time = get_words('_na');
			}
		}
		if(v6Status.connType == "9")
		{
			if ((((v6Status.wanIANA) != '') || ((v6Status.wanAddr) != '')) && (v6Status.gw) != '')
				$('#network_ipv6_status').html(get_words('CONNECTED'));
			else{
				$('#network_ipv6_status').html(get_words('DISCONNECTED'));
				wan_time = get_words('_na');
			}
		}
	}
$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(model);
	onPageLoad();
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
				<script>document.write(menu.build_structure(1,0,1))</script>
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
								<div class="headerbg" id="manStatusTitle">
								<script>show_words('_ipv6_status');</script>
								</div>
								<div class="hr"></div>
								<div class="section_content_border">
								<div class="header_desc" id="manStatusIntroduction">
									<script>show_words('_help_txt299');</script>
									<p></p>
								</div>

<div class="box_tn">
	<div class="CT"><script>show_words('STATUS_IPV6_DESC_1');</script></div>
	<input type="hidden" id="dhcp_list" name="dhcp_list" value="" />
	<input type="hidden" id="ipv6_wan_proto" name="ipv6_wan_proto" value="" />
	<input type="hidden" id="ipv6_pppoe_dynamic" name="ipv6_pppoe_dynamic" value="" />
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL"><script>show_words('IPV6_TEXT29a');</script></td>
		<td class="CR"><span id="connection_ipv6_type"></span></td>
	</tr>
	<tr id="tr_ipv6_conn_time" style="display: none;">
		<td class="CL"><script>show_words('_conuptime')</script></td>
		<td class="CR">
			<span id="ipv6_conn_time" nowrap="">NaN Days, NaN:NaN:NaN</span>&nbsp;<!--span id="ctrl_buttons"></span-->
		</td>
	</tr>
	<tr id="tr_network_ipv6_status">
		<td class="CL"><script>show_words('_netwrk_status_addr');</script></td>
		<td class="CR"><span id="network_ipv6_status">Connected</span></td>
	</tr>
	<tr id="tr_ipv6_show_button">
		<td colspan="2" class="btn_field">
			<span id="ctrl_buttons"></span>
		</td>
	</tr>
	<tr id="tr_wan_ipv6_addr">
		<td class="CL"><script>show_words('TEXT071');</script></td>
		<td class="CR">
			<div id="wan_ipv6_addriana" nowrap></div>
			<div id="wan_ipv6_StaticIP" nowrap></div>
			<div id="wan_ipv6_addr" nowrap></div>
		</td>
	</tr>
	<tr id="tr_tunnel_ipv6_addr">
		<td class="CL"><script>show_words('IPV6_TEXT145')</script></td>
		<td class="CR">
			<span id="tunnel_ipv6_addr" nowrap=""></span>
		</td>
	</tr>
	<tr id="tr_wan_ipv6_gw">
		<td class="CL"><script>show_words('IPV6_TEXT75');</script></td>
		<td class="CR"><span id="wan_ipv6_gw"></span></td>
	</tr>
	<tr id="tr_lan_ipv6_addr">
		<td class="CL"><script>show_words('IPV6_TEXT46');</script></td>
		<td class="CR"><span id="lan_ipv6_addr"></span></td>
	</tr>
	<tr id="tr_lan_ipv6_addr2" style="display:none">
		<td class="CL"></td>
		<td class="CR"><span id="lan_ipv6_addr2" nowrap></span></td>
	</tr>
	<tr id="tr_lan_link_local_ip">
		<td class="CL"><script>show_words('IPV6_TEXT47')</script></td>
		<td class="CR"><span id="lan_link_local_ip" nowrap></span></td>
	</tr>
	<tr id="tr_primary_ipv6_dns">
		<td class="CL"><script>show_words('_dns1');</script></td>
		<td class="CR"><span id="primary_ipv6_dns"></span></td>
	</tr>
	<tr id="tr_secondary_ipv6_dns">
		<td class="CL"><script>show_words('_dns2');</script></td>
		<td class="CR"><span id="secondary_ipv6_dns"></span></td>
	</tr>
	<tr id="tr_dhcp_pd" style="display: none;">
		<td class="CL">DHCP-PD</td>
		<td class="CR"><span id="dhcp_pd" nowrap></span></td>
	</tr>
	<tr id="tr_ipv6_addr_by_dhcppd" style="display: none;">
		<td class="CL"><script>show_words('IPV6_TEXT166')</script></td>
		<td class="CR"><span id="ipv6_addr_by_dhcppd" nowrap></span></td>
	</tr>
	</table>
	<form id="form1" name="form1" method="post" action=""></form>
	<input type="hidden" id="html_response_page" name="html_response_page" value="back.asp" />
	<input type="hidden" id="html_response_return_page" name="html_response_return_page" value="adm_ipv6status.asp" />
	<input type="hidden" id="html_response_message" name="html_response_message" value="WAN is connecting. " />
	<form id="form2" name="form2" method="post" action=""></form>
	<input type="hidden" id="html_response_page" name="html_response_page" value="adm_ipv6status.asp" />
	<input type="hidden" id="html_response_return_page" name="html_response_return_page" value="adm_ipv6status.asp" />
</div>
<!-- LAN IPv6 Computers -->
<div class="box_tn">
	<div class="CT"><script>show_words('TEXT072');</script></div>
	<table id="host6_table" cellspacing="0" cellpadding="0" class="formarea">
	</table>
</div>

								</div>
								<!-- End of main content -->
							<br/>
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
</html>