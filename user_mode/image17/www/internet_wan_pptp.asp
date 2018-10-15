<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | Network | WAN Setting</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<link href="/css/css_router.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="uk.js"></script>
<script type="text/javascript" src="public.js"></script>
<script type="text/javascript" src="public_msg.js"></script>
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
	var cli_mac 	= dev_info.cli_mac;
	var wan_mac		= dev_info.wan_mac;
	

	var main = new ccpObject();
	main.set_param_url('get_set.ccp');
	main.set_ccp_act('get');
	main.add_param_arg('IGD_WANDevice_i_',1100);
	main.add_param_arg('IGD_WANDevice_i_PPTP_',1110);
	main.add_param_arg('IGD_WANDevice_i_PPTP_ConnectionCfg_',1111);
	main.add_param_arg('IGD_',1000);
	main.add_param_arg('IGD_WANDevice_i_PPPoEv6_i_',1110);
	main.get_config_obj();
	
	var macCfg = {
		'override':			main.config_val("wanDev_MACAddressOverride_"),
		'macaddr':			main.config_val("wanDev_MACAddressClone_")
	}
	var pptpCfg = {
		'serverIP':			main.config_val("pptpConn_ServerIP_"),
		'username':			main.config_val("pptpConn_Username_"),
		'password':			main.config_val("pptpConn_Password_"),
		'mppe':				main.config_val("pptpConn_MPPEEnable_"),
		'addrtype':			main.config_val("pptpCfg_IPAddressType_"),
		'ip':				main.config_val("pptpCfg_ExternalIPAddress_"),
		'mask':				main.config_val("pptpCfg_SubnetMask_"),
		'gateway':			main.config_val("pptpCfg_DefaultGateway_"),
		'opmode':			main.config_val("pptpConn_ConnectionTrigger_"),
		'idle':				main.config_val("pptpConn_IdleDisconnectTime_"),
		'redialPeriod':		main.config_val("pptpConn_RedialPeriod_"),
		'mtu':				main.config_val("pptpConn_MaxMTUSize_"),
		'dnsenable':		main.config_val("pptpCfg_DNSEnabled_"),
		'dns':				main.config_val("pptpCfg_DNSServers_")
	};
	var ipv6_wan_proto = main.config_val("wanDev_CurrentConnObjType6_");
	var ipv6_pppoe_share = main.config_val("ipv6PPPoEConn_SessionType_"); 
	var ipDNSServ = main.config_val("pptpCfg_DNSServers_").split(",");
	var adv_dns_en = main.config_val('pptpCfg_AdvancedDNSEnable_');
	var isReg = (main.config_val("igd_Register_st_")? main.config_val("igd_Register_st_"):"");
	var connect_mode ='';
    var submit_button_flag = 0;

    function opendns_enable_selector(value)
    {
        if (value == true) {
            get_by_id("wan_specify_dns").value = "1";
            get_by_id("wan_primary_dns").value = "204.194.232.200";
            get_by_id("wan_secondary_dns").value= "204.194.234.200";
            get_by_id("wan_primary_dns").disabled = true;
            get_by_id("wan_secondary_dns").disabled = true;
        }
        else {
            get_by_id("wan_specify_dns").value = "0";
            get_by_id("wan_primary_dns").disabled = false;
            get_by_id("wan_secondary_dns").disabled = false;
            get_by_id("wan_primary_dns").value = "0.0.0.0";
            get_by_id("wan_secondary_dns").value =  "0.0.0.0";
        }
    }

    function onPageLoad()
    {
		setValuePPTPAddressMode();
		setEventPPTPAddressMode();
		
		setValueOperationModeSel();
		setEventOperationModeSel();
		setValueRedialPeriod();
		//WAN MTU Setting
		setValueUseDefaultMTUSetting();
		setEventUseDefaultMTUSetting();
		//MAC Address Clone
		setValueMACClone();
		setEventMACClone();
//		$('#wan_pptp_dynamic').val(main.config_val("pptpCfg_IPAddressType_"));
		set_checked(adv_dns_en, get_by_id('opendns_enable_sel'));
		if (adv_dns_en == '1') {
			opendns_enable_selector(true);
		}

		/**
		**    Date:		2013-10-11
		**    Author:	Silvia Chang
		**    Reason:	Customization for TEW-813DRU
		**    Note:		Vic request to add MS-CHAPv2(MPPE option) to normal and RU PPTP/L2TP
		**/
		set_checked(pptpCfg.mppe,$('#pptp_mppe')[0]);
		get_by_id("wan_pptp_ipaddr").value = main.config_val("pptpCfg_ExternalIPAddress_");
		get_by_id("wan_pptp_netmask").value = main.config_val("pptpCfg_SubnetMask_");
		get_by_id("wan_pptp_gateway").value = main.config_val("pptpCfg_DefaultGateway_");
		get_by_id("wan_primary_dns").value= (ipDNSServ[0]==""?"0.0.0.0":ipDNSServ[0]);
		
		if(ipDNSServ[1])
			get_by_id("wan_secondary_dns").value = ipDNSServ[1];
		else
			get_by_id("wan_secondary_dns").value = "0.0.0.0";

		get_by_id("wan_pptp_server_ip").value = main.config_val("pptpConn_ServerIP_");
		get_by_id("wan_pptp_username").value = main.config_val("pptpConn_Username_");
		//get_by_id("wan_pptp_password").value = main.config_val("");
		get_by_id("wan_pptp_max_idle_time").value = main.config_val("pptpConn_IdleDisconnectTime_");
		get_by_id("wan_pptp_mtu").value = main.config_val("pptpConn_MaxMTUSize_");

	if(macCfg.override == "1")
		get_by_id("wan_mac").value = main.config_val("wanDev_MACAddressClone_");
		get_by_id("wanDev_MACAddressOverride_1.1.0.0").value = main.config_val("wanDev_MACAddressOverride_");

		//set_mac(get_by_id("wan_mac").value, ":");
		//disable_static_address(get_by_name("wan_pptp_dynamic"), get_by_id("wan_pptp_ipaddr"), get_by_id("wan_pptp_netmask"), get_by_id("wan_pptp_gateway"), get_by_id("wan_primary_dns"));

//        check_connectmode();
//        disable_pptp_ip();

        if (login_Info != "w") {
           DisableEnableForm(form1,true);
        }

        set_form_default_values("form1");
    }

    function clone_mac_action()
    {
		get_by_id("wan_mac").value = cli_mac;
		get_by_id("wanDev_MACAddressOverride_1.1.0.0").value = "1";
    }

    function check_connectmode()
    {
        var conn_mode = get_by_name("wan_pptp_connect_mode");
        var idle_time = get_by_id("wan_pptp_max_idle_time");
        idle_time.disabled = !conn_mode[1].checked;
    }

    function disable_pptp_ip()
    {
        var fixIP = get_by_name("wan_pptp_dynamic");
        var is_disabled = false;

        if (fixIP[0].checked)
            is_disabled = true;

        get_by_id("wan_pptp_ipaddr").disabled = is_disabled;
        get_by_id("wan_pptp_netmask").disabled = is_disabled;
        get_by_id("wan_pptp_gateway").disabled = is_disabled;
    }

	function send_request(){
	
	//form1 always modified? why?
    	get_by_id("asp_temp_52").value = get_by_id("wan_proto").value;
    	var is_modify = is_form_modified("form1");
    	if (!is_modify && !confirm(get_words('_ask_nochange'))) {
			return false;
		}
		
		if (ipv6_wan_proto == "3" && ipv6_pppoe_share == "0"){
			alert(get_words('IPV6_TEXT161a'));
			return false;
		}
		
		var user_name = get_by_id("wan_pptp_username").value;
		var wan_type = $('#wan_pptp_dynamic').val();
		var ip = get_by_id("wan_pptp_ipaddr").value;		
		var mask = get_by_id("wan_pptp_netmask").value;
		var gateway = get_by_id("wan_pptp_gateway").value;	
		var dns = get_by_id("wan_primary_dns").value;	
		var idle_time = get_by_id("wan_pptp_max_idle_time").value;	    	
		var mtu = get_by_id("wan_pptp_mtu").value;
		var server_ip = $('#wan_pptp_server_ip').val();
		
		var ip_addr_msg = replace_msg(all_ip_addr_msg,get_words('_ipaddr'));
		var gateway_msg = replace_msg(all_ip_addr_msg,get_words('wwa_gw'));
		var dns_server_msg = replace_msg(all_ip_addr_msg,get_words('wwa_pdns'));
		var max_idle_msg = replace_msg(check_num_msg, get_words('usb3g_max_idle_time'), 0, 999);
		var mtu_msg = replace_msg(check_num_msg, get_words('bwn_MTU'), 1300, 1400);
		var server_ip_addr_msg = replace_msg(all_ip_addr_msg,get_words('_server_ip'));
		
		var temp_ip_obj = new addr_obj(ip.split("."), ip_addr_msg, false, false);
		var temp_mask_obj = new addr_obj(mask.split("."), subnet_mask_msg, false, false);
		var temp_gateway_obj = new addr_obj(gateway.split("."), gateway_msg, false, false);
		var temp_dns_obj = new addr_obj(dns.split("."), dns_server_msg, false, false);
		var temp_idle = new varible_obj(idle_time, max_idle_msg, 0, 9999, false);
		var temp_mtu = new varible_obj(mtu, mtu_msg, 1300, 1400, false);
		var temp_server_ip_obj = new addr_obj(server_ip.split("."), server_ip_addr_msg, false, false);

		/*
		** Date:	2013-04-23
		** Author:	Moa Chung
		** Reason:	allow pptp server ip enter domain name and ip
		**/
		if(ip_pattern(server_ip))// ip format
		{
			if (!check_address(temp_server_ip_obj)){
				return false;
			}
		}
		else//domain format
		{
			if(server_ip == ""){
				alert(get_words('YM108'));
				return false;
			}
		}
		
		if(user_name==""){
			alert(get_words('GW_WAN_PPTP_USERNAME_INVALID'));
    		return false;
	     }
		 
		connect_mode = $("#wan_pptp_connect_mode").val();

		if (wan_type=='1'){
			if (!check_lan_setting(temp_ip_obj, temp_mask_obj, temp_gateway_obj, get_words('WAN'))){
				return false;
			}
		}
		
		if (dns != "" && dns != "0.0.0.0"){
			if (!check_address(temp_dns_obj)){
				return false;
			}
		}

/* there are no pwd verify
		if (!check_pwd("pptppwd1", "pptppwd2")){
			return false;
		}
*/
		
/*		if (wan_type=='0') //Set dynamic IP
			get_by_id("wan_specify_dns").value = 0;
		else{						//Set static IP
			get_by_id("wan_specify_dns").value = (dns == "" || dns == "0.0.0.0") ? 0 : 1;			
		}
*/
			
		if(connect_mode == "2")
		{
			if (!check_varible(temp_idle)){
				return false;
			}
		}
    	
    	if (!check_varible(temp_mtu)){
    		return false;
    	}

		/*
		 * Validate MAC and activate cloning if necessary
		 */			
		if($('#macCloneEnbl').val()=='1'){
			var clonemac = get_by_id("wan_mac").value;
			if (!check_mac_00(clonemac)){
				alert(get_words('KR3'));
				return false;
			} 
			
			var mac = trim_string(get_by_id("wan_mac").value);
			if(!is_mac_valid(mac, true)) {
				alert (get_words('KR3')+":" + mac + ".");
				return false;
			}else{
				get_by_id("wan_mac").value = mac;
			}
		}
		
		if($('#macCloneEnbl').val()=='0'){
			$("#wan_mac").val(wan_mac);
			get_by_id("wanDev_MACAddressOverride_1.1.0.0").value = "0";
		}
		else if($("#wan_mac").val() == "00:00:00:00:00:00")
		{
			$("#wan_mac").val(wan_mac);
			get_by_id("wanDev_MACAddressOverride_1.1.0.0").value = "0";
		}
		else
			$("#wan_mac").val(mac);
			
		copyDataToDataModelFormat();
//		send_submit("form2");
		var paramSubmit = {
			url: "get_set.ccp",
			arg: translateFormObjToAJAXArg("form2")
		};
		
		totalWaitTime = 18; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(paramSubmit.url, paramSubmit.arg);
	}

	function copyDataToDataModelFormat()
	{
		get_by_id("pptpCfg_IPAddressType_1.1.1.0").value = $("#wan_pptp_dynamic").val();
		get_by_id("pptpCfg_ExternalIPAddress_1.1.1.0").value = get_by_id("wan_pptp_ipaddr").value;
		get_by_id("pptpCfg_SubnetMask_1.1.1.0").value = get_by_id("wan_pptp_netmask").value;
		get_by_id("pptpCfg_DefaultGateway_1.1.1.0").value = get_by_id("wan_pptp_gateway").value;
		//get_by_id("pptpCfg_DNSEnabled_1.1.1.0").value = get_by_id("");
		get_by_id("pptpCfg_DNSServers_1.1.1.0").value = get_by_id("wan_primary_dns").value;
		if(get_by_id("wan_secondary_dns").value != "")
			get_by_id("pptpCfg_DNSServers_1.1.1.0").value += ","+get_by_id("wan_secondary_dns").value;
		get_by_id("pptpConn_ServerIP_1.1.1.1").value = get_by_id("wan_pptp_server_ip").value;
		get_by_id("pptpConn_Username_1.1.1.1").value = get_by_id("wan_pptp_username").value;
		//if(get_by_id("wan_pptp_password").value == "WDB8WvbXdHtZyM8Ms2RENgHlacJghQyG")
			//get_by_id("pptpConn_Password_1.1.1.1").value = main.config_val("pptpConn_Password_");
		//else
			get_by_id("pptpConn_Password_1.1.1.1").value = get_by_id("pptppwd1").value;
		get_by_id("pptpConn_ConnectionTrigger_1.1.1.1").value = connect_mode;
		get_by_id("pptpConn_IdleDisconnectTime_1.1.1.1").value = get_by_id("wan_pptp_max_idle_time").value;
		get_by_id("pptpConn_RedialPeriod_1.1.1.1").value = get_by_id("pptpRedialPeriod").value;
		get_by_id("pptpConn_MaxMTUSize_1.1.1.1").value = get_by_id("wan_pptp_mtu").value;

//		get_by_id("pptpCfg_AdvancedDNSEnable_1.1.1.0").value = get_checked_value(get_by_id("opendns_enable_sel"));
		get_by_id("pptpConn_MPPEEnable_1.1.1.1").value = get_checked_value($('#pptp_mppe')[0]);

		/*
		 * 20121226 moa rewrite contitions if MAC override or not
		 */
		if(get_by_id("wan_mac").value == wan_mac)
		{
			get_by_id("wanDev_MACAddressOverride_1.1.0.0").value = "0";
		}
		else
		{
			get_by_id("wanDev_MACAddressOverride_1.1.0.0").value = "1";
		}
		get_by_id("wanDev_MACAddressClone_1.1.0.0").value = get_by_id("wan_mac").value;

	}

function connectionTypeSwitch(){
	var sel_wan = $('#wan_proto').val();
	switch(sel_wan)
	{
		case "0":
			html_file = "internet_wan_static.asp";
			break;
		case "1":
			html_file = "internet_wan_dhcp.asp";
			break;
		case "2":
			html_file = "internet_wan_poe.asp";
			break;
		case "3":
			html_file = "internet_wan_pptp.asp";
			break;
		case "4":
			html_file = "internet_wan_l2tp.asp";
			break;
		case "6":
			html_file = "internet_rus_wan_poe.asp";
			break;
		case "7":
			html_file = "internet_rus_wan_pptp.asp";
			break;
		case "8":
			html_file = "internet_rus_wan_l2tp.asp";
			break;
		default:
			html_file = "internet_wan_dhcp.asp";
			break;
	}
	location.href = html_file;
}
function setValuePPTPAddressMode(){
	var val_type = pptpCfg.addrtype;
	$('#wan_pptp_dynamic').val(val_type);
}
function setEventPPTPAddressMode(){
	var func = function(){
		var sel_type = $('#wan_pptp_dynamic').val();
		if(sel_type=='1'){
			$('#rpptpIp').show();
			$('#rpptpNetmask').show();
			$('#rpptpGateway').show();
			$('#DNSServer').show();
		}
		else{
			$('#rpptpIp').hide();
			$('#rpptpNetmask').hide();
			$('#rpptpGateway').hide();
			$('#DNSServer').hide();
		}
	};
	func();
	$('#wan_pptp_dynamic').change(func);
}
function setValueOperationModeSel(){
	var sel_mode = pptpCfg.opmode;
	$('#wan_pptp_connect_mode').val(sel_mode);
}
function setEventOperationModeSel(){
	var func = function(){
		var sel_mode = $('#wan_pptp_connect_mode').val();
		if(sel_mode=='0'){
			$('#pptpRedialPeriod').removeAttr('disabled');
			$('#wan_pptp_max_idle_time').attr('disabled','disabled');
		}
		else if(sel_mode=='1'){
			$('#pptpRedialPeriod').attr('disabled','disabled');
			$('#wan_pptp_max_idle_time').attr('disabled','disabled');
		}
		else if(sel_mode=='2'){
			$('#pptpRedialPeriod').attr('disabled','disabled');
			$('#wan_pptp_max_idle_time').removeAttr('disabled');
		}
	};
	func();
	$('#wan_pptp_connect_mode').change(func);
}
function setValueRedialPeriod(){
	var val_period = pptpCfg.redialPeriod;
	$('#pptpRedialPeriod').val(val_period);
}
function setValueUseDefaultMTUSetting(){
	var val_mtu = pptpCfg.mtu;
	if(val_mtu=='1400')
		$('#useDefaultMTU_Select').val('1');
	else
		$('#useDefaultMTU_Select').val('0');
}
function setEventUseDefaultMTUSetting(){
	var func = function(){
		var sel_def = $('#useDefaultMTU_Select').val();
		if(sel_def=='0'){
			$('#wan_pptp_mtu').removeAttr('disabled');
		}
		else{
			$('#wan_pptp_mtu').attr('disabled','disabled');
			$('#wan_pptp_mtu').val(1400);
		}
	}
	func();
	$('#useDefaultMTU_Select').change(func);
}
function setValueMACClone(){
	var sel_clone = macCfg.override;
	$('#macCloneEnbl').val(sel_clone);
}
function setEventMACClone(){
	var func = function(){
		var sel_clone = $('#macCloneEnbl').val();
		if(sel_clone=='1'){
			$('#macCloneMacRow').show();
		}
		else{
			$('#macCloneMacRow').hide();
		}
	};
	func();
	$('#macCloneEnbl').change(func);
}
$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(model);
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
				<script>document.write(menu.build_structure(1,1,1))</script>
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
								<script>show_words('_wan_setting_l');</script>
								</div>
								<div class="hr"></div>
								<div class="section_content_border">
								<div class="header_desc" id="wanIntroduction">
									<script>show_words('_DHCP_DESC');</script>
									<p></p>
								</div>

		<input type="hidden" id="old_wan_mac" name="old_wan_mac" value='' />

								<form id="form2" name="form2" method="post" action="get_set.ccp">
									<input type="hidden" name="ccp_act" value="set" />
									<input type="hidden" name="ccpSubEvent" value="CCP_SUB_WEBPAGE_APPLY" />
									<input type="hidden" name="nextPage" value="internet_wan.asp" />
									<input type="hidden" name="wanDev_CurrentConnObjType_1.1.0.0" id="wanDev_CurrentConnObjType_1.1.0.0" value="3" />
									<input type="hidden" name="wanDev_MACAddressClone_1.1.0.0" id="wanDev_MACAddressClone_1.1.0.0" value="" />
									<input type="hidden" name="wanDev_MACAddressOverride_1.1.0.0" id="wanDev_MACAddressOverride_1.1.0.0" value="" />
									<input type="hidden" name="pptpCfg_IPAddressType_1.1.1.0" id="pptpCfg_IPAddressType_1.1.1.0" value="" />
									<input type="hidden" name="pptpCfg_ExternalIPAddress_1.1.1.0" id="pptpCfg_ExternalIPAddress_1.1.1.0" value="" />
									<input type="hidden" name="pptpCfg_SubnetMask_1.1.1.0" id="pptpCfg_SubnetMask_1.1.1.0" value="" />
									<input type="hidden" name="pptpCfg_DefaultGateway_1.1.1.0" id="pptpCfg_DefaultGateway_1.1.1.0" value="" />
									<input type="hidden" name="pptpCfg_AdvancedDNSEnable_1.1.1.0" id="pptpCfg_AdvancedDNSEnable_1.1.1.0" value="0" />
									<input type="hidden" name="pptpCfg_DNSEnabled_1.1.1.0" id="pptpCfg_DNSEnabled_1.1.1.0" value="1" />
									<input type="hidden" name="pptpCfg_DNSServers_1.1.1.0" id="pptpCfg_DNSServers_1.1.1.0" value="" />
									<input type="hidden" name="pptpConn_ServerIP_1.1.1.1" id="pptpConn_ServerIP_1.1.1.1" value="" />
									<input type="hidden" name="pptpConn_Username_1.1.1.1" id="pptpConn_Username_1.1.1.1" value="" />
									<input type="hidden" name="pptpConn_Password_1.1.1.1" id="pptpConn_Password_1.1.1.1" value="" />
									<input type="hidden" name="pptpConn_ConnectionTrigger_1.1.1.1" id="pptpConn_ConnectionTrigger_1.1.1.1" value="" />
									<input type="hidden" name="pptpConn_IdleDisconnectTime_1.1.1.1" id="pptpConn_IdleDisconnectTime_1.1.1.1" value="" />
									<input type="hidden" name="pptpConn_RedialPeriod_1.1.1.1" id="pptpConn_RedialPeriod_1.1.1.1" value="" />
									<input type="hidden" name="pptpConn_MaxMTUSize_1.1.1.1" id="pptpConn_MaxMTUSize_1.1.1.1" value="" />
									<input type="hidden" name="pptpConn_MPPEEnable_1.1.1.1" id="pptpConn_MPPEEnable_1.1.1.1" value="" />
								</form>
		
								<form id="form1" name="form1" method="post" action="">
								<input type="hidden" id="html_response_page" name="html_response_page" value="reboot.asp" />
								<input type="hidden" id="html_response_message" name="html_response_message" value="" />
								<script>get_by_id("html_response_message").value = get_words('sc_intro_sv');</script>
								<input type="hidden" id="html_response_return_page" name="html_response_return_page" value="wan_pptp.asp" />
								<input type="hidden" id="mac_clone_addr" name="mac_clone_addr" value="" />
								<input type="hidden" id="wan_specify_dns" name="wan_specify_dns" value="" />
								<input type="hidden" id="wan_pptp_password" name="wan_pptp_password" value="" />
								<input type="hidden" id="asp_temp_51" name="asp_temp_51" value="" />
								<input type="hidden" id="asp_temp_52" name="asp_temp_52" value="" />
								<input type="hidden" id="reboot_type" name="reboot_type" value="shutdown" />
								
<div class="box_tn">
<div class="CT"><script>show_words('_wan_conn_type');</script></div>
<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL"><script>show_words('_contype');</script></td>
		<td class="CR">
			<select name="wan_proto" id="wan_proto" onChange="connectionTypeSwitch()">
				<option value="0">STATIC</option>
				<option value="1">DHCP</option>
				<option value="2">PPPoE</option>
				<option value="4">L2TP</option>
				<option value="3" selected>PPTP</option>
				<option value="6">Russia PPPoE</option>
				<option value="7">Russia PPTP</option>
				<option value="8">Russia L2TP</option>
				<!-- <option value="10"><script>show_words('IPV6_TEXT140')</script></option> -->
				<!--option value="bigpond">BigPond (Australia)</option-->
			</select>
		</td>
	</tr>
</table>
</div>

<div id="pptp" class="box_tn" style="visibility: visible; display: block;">
<div id="wL2tpMode" class="CT"><script>show_words('_pptp_setting');</script></div>
<table id="pptp" cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL"><script>show_words('bwn_PPTPSIPA')</script></td>
		<td class="CR">
			<input type="text" id="wan_pptp_server_ip" name="wan_pptp_server_ip" size="32" maxlength="64" value="" />
		</td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_username')</script></td>
		<td class="CR">
			<input type="text" id="wan_pptp_username" name="wan_pptp_username" size="32" maxlength="64" value="" />
		</td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_password')</script></td>
		<td class="CR">
			<input type="password" id="pptppwd1" name="pptppwd1" size="32" maxlength="64" onfocus="select();" value="WDB8WvbXdHtZyM8Ms2RENgHlacJghQyGWDB8WvbXdHtZyM8Ms2RENgHlacJghQyG" />
		</td>
	</tr>
<!--		<tr>
		<td class="CL"><script>show_words('_verifypw')</script></td>
		<td class="CR">
			<input type="password" id="pptppwd2" name="pptppwd2" size="20" maxlength="63" onfocus="select();" value="WDB8WvbXdHtZyM8Ms2RENgHlacJghQyGWDB8WvbXdHtZyM8Ms2RENgHlacJghQyG" />
		</td>
	</tr>
-->
	<tr>
		<td class="CL" id="wL2tpAddrMode"><script>show_words('bwn_AM');</script></td>
		<td class="CR">
			<select id="wan_pptp_dynamic" name="wan_pptp_dynamic" size="1">
				<option value="1"><script>show_words('_static');</script></option>
				<option value="0"><script>show_words('_dynamic');</script></option>
			</select>
		</td>
	</tr>
	<tr id="rpptpIp">
		<td class="CL"><script>show_words('_ipaddr')</script></td>
		<td class="CR">
			<input type="text" id="wan_pptp_ipaddr" name="wan_pptp_ipaddr" size="15" maxlength="15" value="" />
		</td>
	</tr>
	<tr id="rpptpNetmask">
		<td class="CL"><script>show_words('_subnet')</script></td>
		<td class="CR">
			<input type="text" id="wan_pptp_netmask" name="wan_pptp_netmask" size="15" maxlength="15" value="" />
		</td>
	</tr>
	<tr id="rpptpGateway">
		<td class="CL"><script>show_words('_defgw')</script></td>
		<td class="CR">
			<input name="wan_pptp_gateway" type=text id="wan_pptp_gateway" size="15" maxlength="15" value="" />
		</td>
	</tr>
	<tr>
		<td class="CL" rowspan="2" id="wL2tpOPMode"><script>show_words('_opeartion_mode');</script></td>
		<td class="CR">
			<select id="wan_pptp_connect_mode" name="wan_pptp_connect_mode" size="1">
				<option value="0"><script>show_words('_keep_alive');</script></option>
				<option value="2"><script>show_words('bwn_RM_1');</script></option>
				<option value="1"><script>show_words('bwn_RM_2');</script></option>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="CELL">
			<script>show_words('_keep_alive_mode_redial');</script>
			<input type="text" id="pptpRedialPeriod" name="pptpRedialPeriod" maxlength="5" size="3" value="10" />
			<script>show_words('_seconds');</script>
			<br>
			<script>show_words('_on_demand_mode_idle_time');</script>
			<input type="text" id="wan_pptp_max_idle_time" name="wan_pptp_max_idle_time" maxlength="3" size="2" value="5" disabled="" />
			<script>show_words('_mintues_lower');</script>
		</td>
	</tr>
</table>
</div>

<div id="DNSServer" class="box_tn"><!--  style="display:none;" -->
<div id="wStaticMode" class="CT"><script>show_words('_dns_server_setting');</script></div>
<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL"><script>show_words('_dns1')</script></td>
		<td class="CR">
			<input type="text" id="wan_primary_dns" name="wan_primary_dns" size="20" maxlength="15" value="" />
		</td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_dns2')</script></td>
		<td class="CR">
			<input type="text" id="wan_secondary_dns" name="wan_secondary_dns" size="20" maxlength="15" value="" />
		</td>
	</tr>
</table>
</div>
<!-- MTU -->
<div class="box_tn" id="mtu_field" style="display: block;">
<div class="CT"><script>show_words('_help_txt37');</script></div>
<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL"><script>show_words('_net_ipv6_09');</script></td>
		<td class="CR">
			<select id="useDefaultMTU_Select" name="useDefaultMTU" size="1">
				<option value="0"><script>show_words('_disable');</script></option>
				<option value="1"><script>show_words('_enable');</script></option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_net_ipv6_10');</script></td>
		<td class="CR">
			<input type="text" id="wan_pptp_mtu" name="wan_pptp_mtu" maxlength="4" value="" />&nbsp;<script>document.write(get_words('_mtu_default_byte').replace('%s', 1400));</script>
		</td>
	</tr>
	
	<tr style="display:none">
		<td class="CL"><script>show_words('_en_AdvDns')</script></td>
		<td class="CR">
			<input type="checkbox" id="opendns_enable_sel" name="opendns_enable_sel" value="1" onclick="opendns_enable_selector(this.checked);" />
		</td>
	</tr>		
	
	<tr style="display:none">
		<td class="CL"><script>show_words('HW_NAT_enable')</script></td>
		<td class="CR">
			<input type="checkbox" id="HW_NAT_Enable" name="HW_NAT_Enable" value="1" />
		</td>
	</tr>
</table>
</div>
<!-- MAC Clone -->
<div class="box_tn">
<div id="wMacClone" class="CT"><script>show_words('_mac_addr_clone');</script></div>
<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL"><script>show_words('_mac_clone');</script></td>
		<td class="CR">
			<select id="macCloneEnbl" name="macCloneEnbl" size="1">
				<option value="0"><script>show_words('_disable');</script></option>
				<option value="1"><script>show_words('_enable');</script></option>
			</select>
		</td>
	</tr>
	<tr id="macCloneMacRow">
		<td class="CL"><script>show_words('_macaddr')</script></td>
		<td class="CR">
			<input type="text" id="wan_mac" name="wan_mac" size="20" maxlength="17" value="" />&nbsp;&nbsp;(Ex: 00:11:22:33:44:55)<br>
			&nbsp;<input name="clone" id="clone" type="button" class="button_submit_NoWidth" value="" onClick="clone_mac_action()" />
			<script>get_by_id("clone").value = get_words('_clone');</script>
		</td>
	</tr>
	</table>
</div>

<!-- MPPE -->
<div class="box_tn" id="mppe_field" style="display: block;">
<div class="CT"><script>show_words('help617');</script></div>
<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL"><script>show_words('_MPPE_enable')</script></td>
		<td class="CR">
			<input name="pptp_mppe" type=checkbox id="pptp_mppe" value="1">
			(<script>show_words('_OnlyMSCHAPv2')</script>)</td>
	</tr>
	
</table>
</div>

<div class="box_tn">
<table cellspacing="0" cellpadding="0" class="formarea">
	<tr align="center">
		<td colspan="2" class="btn_field">
			<input name="button" type="button" class="ButtonSmall" id="button" onClick="return send_request()" value="" />
			<script>$('#button').val(get_words('_apply'));</script>
			<input name="button2" type="button" class="ButtonSmall" id="button2" onclick="page_cancel('form1', 'internet_wan.asp');" value="" />
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
