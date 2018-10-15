<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | Network | PPPoE</title>
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
	main.add_param_arg('IGD_',1000);
	main.add_param_arg('IGD_WANDevice_i_',1100);
	main.add_param_arg('IGD_WANDevice_i_PPPoE_i_',1110);
	main.add_param_arg('IGD_LANDevice_i_LANHostConfigManagement_',1110);
	main.add_param_arg('IGD_WANDevice_i_TrafficControl_',1110);
	main.add_param_arg('IGD_Firewall_',1100);
	main.get_config_obj();
	
	var hw_nat_enable = main.config_val("wanDev_HardwareNatEnable_");
	var spi_enable = main.config_val("firewallSetting_SPIEnable_");
	var trafficshap_enable = main.config_val('wanTrafficShp_EnableTrafficShaping_');
	var adv_dns_en = main.config_val('pppoeCfg_AdvancedDNSEnable_');
	var isReg = (main.config_val("igd_Register_st_")? main.config_val("igd_Register_st_"):"");
	var connect_mode ='';
	
	var lanCfg = {
		'lanIp':			main.config_val('lanHostCfg_IPAddress_')
	};		
	
	var macCfg = {
		'override':			main.config_val("wanDev_MACAddressOverride_"),
		'macaddr':			main.config_val("wanDev_MACAddressClone_")
	}
	var pppoeCfg = {
		'username':			main.config_val("pppoeCfg_Username_"),
		'password':			main.config_val("pppoeCfg_Password_"),
		'opmode':			main.config_val("pppoeCfg_ConnectionTrigger_"),
		'idle':				main.config_val("pppoeCfg_IdleDisconnectTime_"),
		'redialPeriod':		main.config_val("pppoeCfg_RedialPeriod_"),
		'mtu':				main.config_val("pppoeCfg_MaxMTUSize_"),
		'dnsenable':		main.config_val("pppoeCfg_DNSEnabled_"),
		'dns':				main.config_val("pppoeCfg_DNSServers_")
	};
	
	var ipDNSServ = main.config_val("pppoeCfg_DNSServers_").split(",");
	var devMode = (main.config_val("igd_DeviceMode_")? main.config_val("igd_DeviceMode_"):"0");
	var devLanIP = "";

    var submit_button_flag = 0;

    function opendns_enable_selector(value)
    {
        if (value == true) {
            get_by_id("wan_specify_dns").value = "1";
            get_by_id("wan_primary_dns").value = "204.194.232.200";
            get_by_id("wan_secondary_dns").value = "204.194.234.200";
            get_by_id("wan_primary_dns").disabled = true;
            get_by_id("wan_secondary_dns").disabled = true;
        }
        else {
            get_by_id("wan_specify_dns").value ="0";
            get_by_id("wan_primary_dns").disabled = false;
            get_by_id("wan_secondary_dns").disabled = false;
            get_by_id("wan_primary_dns").value = "0.0.0.0";
            get_by_id("wan_secondary_dns").value =  "0.0.0.0";
        }
    }

function onPageLoad(){
	setValueOperationModeSel();
	setEventOperationModeSel();
	setValueManuallyconfigureDNS();
	setEventManuallyconfigureDNS();
	//WAN MTU Setting
	setValueUseDefaultMTUSetting();
	setEventUseDefaultMTUSetting();
	//MAC Address Clone
	setValueMACClone();
	setEventMACClone();
	//set_checked(main.config_val("igd_DeviceMode_"), get_by_name("dev_mode"))

	get_by_id("asp_temp_52").value = main.config_val("wanDev_CurrentConnObjType_"); 

	set_checked(main.config_val("pppoeCfg_IPAddressType_"), get_by_name("wan_pppoe_dynamic_00"));
	set_checked(hw_nat_enable, get_by_id('HW_NAT_Enable'));
	set_checked(adv_dns_en, get_by_id('opendns_enable_sel'));
	if (adv_dns_en == '1') {
		opendns_enable_selector(true);
	}

//	get_by_id("wan_pppoe_ipaddr_00").value = main.config_val("pppoeCfg_ExternalIPAddress_");
	get_by_id("wan_pppoe_username_00").value = main.config_val("pppoeCfg_Username_");
//	get_by_id("wan_pppoe_service_00").value = main.config_val("pppoeCfg_ServiceName_");
	get_by_id("wan_pppoe_max_idle_time_00").value = main.config_val("pppoeCfg_IdleDisconnectTime_");
	get_by_id("pppoeRedialPeriod").value = main.config_val("pppoeCfg_RedialPeriod_");
	get_by_id("wan_primary_dns").value = (ipDNSServ[0]==""?"0.0.0.0":ipDNSServ[0]);
	
	if(ipDNSServ[1])
		get_by_id("wan_secondary_dns").value = ipDNSServ[1];
	else
		get_by_id("wan_secondary_dns").value = "0.0.0.0";

	get_by_id("wan_pppoe_mtu").value = main.config_val("pppoeCfg_MaxMTUSize_");

	//set_checked(main.config_val("pppoeCfg_NetSniperSupport_"), get_by_id("netSniper"));
	//get_by_id("dialModeSel").selectedIndex = main.config_val("pppoeCfg_SpecialDialMode_");
	
	if(macCfg.override == "1")
		get_by_id("wan_mac").value = main.config_val("wanDev_MACAddressClone_");
	get_by_id("wanDev_MACAddressOverride_1.1.0.0").value = main.config_val("wanDev_MACAddressOverride_");
	
	//set_checked(main.config_val("pppoeCfg_PPPoEPlusDialEnable_"), get_by_id("pppoe_plus_dial"));
	set_form_default_values("form1");	
	var login_who= login_Info;
	if(login_who!= "w"){
		DisableEnableForm(form1,true);	
	}else{
//		check_connectmode();
//		disable_poe_ip();
		//change_dialmode();
		//changeDeviceMode();
	} 	
}

function clone_mac_action(){
	get_by_id("wan_mac").value = cli_mac;
	get_by_id("wanDev_MACAddressOverride_1.1.0.0").value = "1";
}

function disable_poe_ip(){
		var fixIP = get_by_name("wan_pppoe_dynamic_00");
		var is_disabled;
				  
	  	if (fixIP[0].checked){
	    	is_disabled = true;
	  	}else{
	    	is_disabled = false;
	    }
	  	get_by_id("wan_pppoe_ipaddr_00").disabled = is_disabled;
	  	//get_by_id("wan_primary_dns").disabled = is_disabled;
	  	//get_by_id("wan_secondary_dns").disabled = is_disabled;
	}
		
    function set_poe_dynamic(type){
    	var fixIP = get_by_name("wan_pppoe_dynamic_00");
        if (type == 0){
            fixIP[0].checked = true;
            get_by_id("wan_pppoe_ipaddr_00").value = "0.0.0.0";	           
        }
    }
    
    
	function send_pppoe_request()
	{
    	get_by_id("asp_temp_52").value = get_by_id("wan_proto").value;
    	var is_modify = is_form_modified("form1");
    	if (!is_modify && !confirm(get_words('_ask_nochange'))) {
			return false;
		}

		connect_mode = $('#pppoeOPMode').val();

		//add by Vic for check hw nat enable
		if(!check_hw_nat_enable())
			return false;
			
//   	var fixIP = get_by_name("wan_pppoe_dynamic_00");
//    	var ip = get_by_id("wan_pppoe_ipaddr_00").value;
    	var dns1 = get_by_id("wan_primary_dns").value;
    	var dns2 = get_by_id("wan_secondary_dns").value;
    	var idle = get_by_id("wan_pppoe_max_idle_time_00").value;	    	
        var mtu = get_by_id("wan_pppoe_mtu").value;
        var user_name = get_by_id("wan_pppoe_username_00").value;
        
        var ip_addr_msg = replace_msg(all_ip_addr_msg,get_words('_ipaddr'));
		var primary_dns_msg = replace_msg(all_ip_addr_msg,get_words('lan_dns'));
		var second_dns_msg = replace_msg(all_ip_addr_msg,get_words('lan_dns2'));
        var max_idle_msg = replace_msg(check_num_msg, get_words('usb3g_max_idle_time'), 0, 9999);
        var mtu_msg = replace_msg(check_num_msg, get_words('bwn_MTU'), 1300, 1492);
                
//        var temp_ip = new addr_obj(ip.split("."), ip_addr_msg, false, false);
        var temp_dns1 = new addr_obj(dns1.split("."), primary_dns_msg, true, false);
	    var temp_dns2 = new addr_obj(dns2.split("."), second_dns_msg, true, false);
        var temp_idle = new varible_obj(idle, max_idle_msg, 0, 9999, false);
        var temp_mtu = new varible_obj(mtu, mtu_msg, 1300, 1492, false);
/*        if (fixIP[1].checked){
        	if (!check_address(temp_ip)){
        		return false;
        	}
        }
*/
        
    	if (dns2 != "" && dns2 != "0.0.0.0"){
    		if (!check_address(temp_dns2)){
    			return false;
    		}
    	}
    	
    	 if(user_name == ""){
			alert(get_words('GW_WAN_PPPOE_USERNAME_INVALID'));
    		return false;
	     }
    	
	     if (get_by_id("poe_pass_s").value == "" || get_by_id("poe_pass_v").value == ""){
		 	alert(get_words('GW_WAN_PPPOE_PASSWORD_INVALID'));//
			return false;
		}
		
    	if (!check_pwd("poe_pass_s", "poe_pass_v")){
       		return false;
       	}
       	
       	if (!check_varible(temp_idle)){
    		return false;
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
		
        if (dns1 != "" && dns1 != "0.0.0.0"){
    		if (!check_address(temp_dns1)){
    			return false;
    		}
    	}
    	
		if((get_by_id("wan_primary_dns").value =="" || get_by_id("wan_primary_dns").value =="0.0.0.0")&& ( get_by_id("wan_secondary_dns").value =="" || get_by_id("wan_secondary_dns").value =="0.0.0.0")){
			get_by_id("wan_specify_dns").value = 0;
		}else{
			get_by_id("wan_specify_dns").value = 1;
		}
		
		if(submit_button_flag == 0){
			if(get_by_id("poe_pass_s").value !="WDB8WvbXdH"){
				get_by_id("wan_pppoe_password_00").value = get_by_id("poe_pass_s").value;
			}
			submit_button_flag = 1;
			copyDataToDataModelFormat();
			if(devMode != get_checked_value(get_by_name("dev_mode")))
			{
				//alert(translateFormObjToAJAXArg("form2"));
				var paramSubmit = {
					url: "get_set.ccp",
					arg: translateFormObjToAJAXArg("form2")
				};
				
				//ajax_submit(paramSubmit);

				totalWaitTime = 18; //second
				redirectURL = location.pathname;
				wait_page();
				jq_ajax_post(paramSubmit.url, paramSubmit.arg);
//				window.location.href = "back.asp?event=devModeChange&new_ip="+devLanIP;
				return true;
			}
//			send_submit("form2");
			var paramSubmit = {
				url: "get_set.ccp",
				arg: translateFormObjToAJAXArg("form2")
			};
			

			totalWaitTime = 18; //second
			redirectURL = location.pathname;
			wait_page();
			jq_ajax_post(paramSubmit.url, paramSubmit.arg);
			return true;
		}else{
			return false;
		}
	}

	function copyDataToDataModelFormat()
	{
//		get_by_id("pppoeCfg_IPAddressType_1.1.1.1.0").value = get_checked_value(get_by_name("wan_pppoe_dynamic_00"));
//		get_by_id("pppoeCfg_ExternalIPAddress_1.1.1.1.0").value = get_by_id("wan_pppoe_ipaddr_00").value;
//		get_by_id("pppoeCfg_ServiceName_1.1.1.1.0").value = get_by_id("wan_pppoe_service_00").value;
		get_by_id("pppoeCfg_Username_1.1.1.0").value = get_by_id("wan_pppoe_username_00").value;
		if(get_by_id("poe_pass_s").value  == "WDB8WvbXdH")
			get_by_id("pppoeCfg_Password_1.1.1.0").value = main.config_val("pppoeCfg_Password_");
		else
			get_by_id("pppoeCfg_Password_1.1.1.0").value = get_by_id("poe_pass_s").value;
			
		get_by_id("pppoeCfg_DNSEnabled_1.1.1.0").value = get_by_id("dhcpDNSSelect").value;
		get_by_id("pppoeCfg_DNSServers_1.1.1.0").value = get_by_id("wan_primary_dns").value;
		if(get_by_id("wan_secondary_dns").value != "")
			get_by_id("pppoeCfg_DNSServers_1.1.1.0").value += ","+get_by_id("wan_secondary_dns").value;
			
		get_by_id("pppoeCfg_ConnectionTrigger_1.1.1.0").value = connect_mode;
		get_by_id("pppoeCfg_IdleDisconnectTime_1.1.1.0").value = get_by_id("wan_pppoe_max_idle_time_00").value;
		get_by_id("pppoeCfg_RedialPeriod_1.1.1.0").value = get_by_id("pppoeRedialPeriod").value;
		get_by_id("pppoeCfg_MaxMTUSize_1.1.1.0").value = get_by_id("wan_pppoe_mtu").value;

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
			
//		get_by_id("pppoeCfg_AdvancedDNSEnable_1.1.1.1.0").value = get_checked_value(get_by_id("opendns_enable_sel"));
/*		hw_nat_enable = get_checked_value(get_by_id('HW_NAT_Enable'));
		if(hw_nat_enable)
		{
			spi_enable = "0";
			trafficshap_enable = "0";
		}
		
		//get_by_id("wanDev_HardwareNatEnable_1.1.1.0.0").value = hw_nat_enable;
		get_by_id("firewallSetting_SPIEnable_1.1.0.0.0").value = spi_enable;
		get_by_id("wanTrafficShp_EnableTrafficShaping_1.1.1.0.0").value = trafficshap_enable;

		//get_by_id("pppoeCfg_NetSniperSupport_1.1.1.1.0").value = get_checked_value(get_by_id("netSniper"));
		//get_by_id("pppoeCfg_SpecialDialMode_1.1.1.1.0").value = get_by_id("dialModeSel").value;
		
		//get_by_id("pppoeCfg_PPPoEPlusDialEnable_1.1.1.1.0").value = get_checked_value(get_by_id("pppoe_plus_dial"));
		
		//get_by_id("igd_DeviceMode_1.0.0.0.0").value = get_checked_value(get_by_name("dev_mode"));
*/
	}

	function change_dialmode()
	{
		if(get_by_id("dialModeSel").value != 7)
			get_by_id("bt_learn").disabled = true;
		else
			get_by_id("bt_learn").disabled = false;
	}
	
	function reload_page()
	{
		if (is_form_modified("form1") && confirm (get_words('up_fm_dc_1'))) {
			onPageLoad();
		}
	}
	
	function changeDeviceMode()
	{
		switch(get_checked_value(get_by_name("dev_mode")))
		{
			case "0":
				get_by_id("wan_proto").disabled = false;
				get_by_id("dialModeSel").disabled = false;		
				disableDiv("show_poe", false);
				disable_poe_ip();
				check_connectmode();		
				change_dialmode();
				devLanIP = lanCfg.lanIp;
			break;
			
			case "1":
				get_by_id("wan_proto").disabled = true;
				get_by_id("dialModeSel").disabled = true;	
				disableDiv("show_poe", true);
				devLanIP = lanCfg.lanAPIp;
			break;
		}

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
function setValueOperationModeSel(){
	var sel_mode = pppoeCfg.opmode;
	$('#pppoeOPMode').val(sel_mode);
}
function setEventOperationModeSel(){
	var func = function(){
		var sel_mode = $('#pppoeOPMode').val();
		if(sel_mode=='0'){
			$('#pppoeRedialPeriod').removeAttr('disabled');
			$('#wan_pppoe_max_idle_time_00').attr('disabled','disabled');
		}
		else if(sel_mode=='1'){
			$('#pppoeRedialPeriod').attr('disabled','disabled');
			$('#wan_pppoe_max_idle_time_00').attr('disabled','disabled');
		}
		else if(sel_mode=='2'){
			$('#pppoeRedialPeriod').attr('disabled','disabled');
			$('#wan_pppoe_max_idle_time_00').removeAttr('disabled');
		}
	};
	func();
	$('#pppoeOPMode').change(func);
}
function setValueRedialPeriod(){
	var val_period = pppoeCfg.redialPeriod;
	$('#pppoeRedialPeriod').val(val_period);
}
function setValueManuallyconfigureDNS(){
	var sel_dnsenable = pppoeCfg.dnsenable;
	$('#dhcpDNSSelect').val(sel_dnsenable);
}
function setEventManuallyconfigureDNS(){
	var func = function(){
		var sel_ = $('#dhcpDNSSelect').val();
		if(sel_=='1'){
			$('#DNSServer').show();
		}
		else{
			$('#DNSServer').hide();
			/*
			** Date:	2013-03-20
			** Author:	Moa Chung
			** Reason:	Network → Wan setting → PPPoe：Enable and set manually configure DNS, disable, and it still work.
			** Note:	TEW-810DR pre-test no.41
			**/
			$('#wan_primary_dns').val('0.0.0.0');
			$('#wan_secondary_dns').val('0.0.0.0');
		}
	};
	func();
	$('#dhcpDNSSelect').change(func);
}
function setValueUseDefaultMTUSetting(){
	var val_mtu = pppoeCfg.mtu;
	if(val_mtu=='1492')
		$('#useDefaultMTU_Select').val('1');
	else
		$('#useDefaultMTU_Select').val('0');
}
function setEventUseDefaultMTUSetting(){
	var func = function(){
		var sel_def = $('#useDefaultMTU_Select').val();
		if(sel_def=='0'){
			$('#wan_pppoe_mtu').removeAttr('disabled');
		}
		else{
			$('#wan_pppoe_mtu').attr('disabled','disabled');
			$('#wan_pppoe_mtu').val(1492);
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

			<form id="form2" name="form2" method="post" action="get_set.ccp">
				<input type="hidden" name="ccp_act" value="set" />
				<input type="hidden" name="ccpSubEvent" value="CCP_SUB_WEBPAGE_APPLY" />
				<input type="hidden" name="nextPage" value="internet_wan.asp" />
				<input type="hidden" name="igd_DeviceMode_1.0.0.0" id="igd_DeviceMode_1.0.0.0" value="" />
				<input type="hidden" name="wanDev_CurrentConnObjType_1.1.0.0" id="wanDev_CurrentConnObjType_1.1.0.0" value="2" />
				<input type="hidden" name="wanDev_MACAddressClone_1.1.0.0" id="wanDev_MACAddressClone_1.1.0.0" value="" />
				<input type="hidden" name="wanDev_MACAddressOverride_1.1.0.0" id="wanDev_MACAddressOverride_1.1.0.0" value="" />
				<input type="hidden" name="pppoeCfg_IPAddressType_1.1.1.0" id="pppoeCfg_IPAddressType_1.1.1.0" value="" />
				<input type="hidden" name="pppoeCfg_ExternalIPAddress_1.1.1.0" id="pppoeCfg_ExternalIPAddress_1.1.1.0" value="" />
				<input type="hidden" name="pppoeCfg_ServiceName_1.1.1.0" id="pppoeCfg_ServiceName_1.1.1.0" value="" />
				<input type="hidden" name="pppoeCfg_Username_1.1.1.0" id="pppoeCfg_Username_1.1.1.0" value="" />
				<input type="hidden" name="pppoeCfg_Password_1.1.1.0" id="pppoeCfg_Password_1.1.1.0" value="" />
				<input type="hidden" name="pppoeCfg_AdvancedDNSEnable_1.1.1.0" id="pppoeCfg_AdvancedDNSEnable_1.1.1.0" value="0" />
				<input type="hidden" name="pppoeCfg_DNSEnabled_1.1.1.0" id="pppoeCfg_DNSEnabled_1.1.1.0" value="1" />
				<input type="hidden" name="pppoeCfg_DNSServers_1.1.1.0" id="pppoeCfg_DNSServers_1.1.1.0" value="" />
				<input type="hidden" name="pppoeCfg_ConnectionTrigger_1.1.1.0" id="pppoeCfg_ConnectionTrigger_1.1.1.0" value="" />
				<input type="hidden" name="pppoeCfg_IdleDisconnectTime_1.1.1.0" id="pppoeCfg_IdleDisconnectTime_1.1.1.0" value="" />
				<input type="hidden" name="pppoeCfg_RedialPeriod_1.1.1.0" id="pppoeCfg_RedialPeriod_1.1.1.0" value="" />
				<input type="hidden" name="pppoeCfg_MaxMTUSize_1.1.1.0" id="pppoeCfg_MaxMTUSize_1.1.1.0" value="" />
				<input type="hidden" name="pppoeCfg_NetSniperSupport_1.1.1.0" id="pppoeCfg_NetSniperSupport_1.1.1.0" value="" />
				<input type="hidden" name="pppoeCfg_SpecialDialMode_1.1.1.0" id="pppoeCfg_SpecialDialMode_1.1.1.0" value="" />
				<input type="hidden" name="pppoeCfg_PPPoEPlusDialEnable_1.1.1.0" id="pppoeCfg_PPPoEPlusDialEnable_1.1.1.0" value="" />
				
				<input type="hidden" id="wanDev_HardwareNatEnable_1.1.0.0" name="wanDev_HardwareNatEnable_1.1.0.0" value="" />
				<input type="hidden" id="firewallSetting_SPIEnable_1.1.0.0" name="firewallSetting_SPIEnable_1.1.0.0" value="" />
				<input type="hidden" id="wanTrafficShp_EnableTrafficShaping_1.1.1.0" name="wanTrafficShp_EnableTrafficShaping_1.1.1.0" value="" />
				
				<span id="forAPMode"></span>
			</form>

			<form id="form1" name="form1" method="post" action="">
			<input type="hidden" id="html_response_page" name="html_response_page" value="reboot.asp" />
			<input type="hidden" id="html_response_message" name="html_response_message" value="" />
			<script>get_by_id("html_response_message").value = get_words('sc_intro_sv');</script>
			<input type="hidden" id="html_response_return_page" name="html_response_return_page" value="wan_poe.asp" />
			<input type="hidden" id="mac_clone_addr" name="mac_clone_addr" value="" />
			<input type="hidden" id="wan_specify_dns" name="wan_specify_dns" value="" />
			<input type="hidden" id="wan_pppoe_password_00" name="wan_pppoe_password_00" value="WDB8WvbXdHtZyM8Ms2RENgHlacJghQyG" />
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
					<option value="2" selected>PPPoE</option>
					<option value="4">L2TP</option>
					<option value="3">PPTP</option>
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

<div id="pppoe" class="box_tn" style="display: block;">
	<div id="wPppoeMode" class="CT"><script>show_words('_net_ipv6_08');</script></div>
	<table cellpadding="0" cellspacing="0" class="formarea">
	
	<input type="hidden" id="opendns_enable" name="opendns_enable" value="" />
	<input type="hidden" id="dns_relay" name="dns_relay" value="" />
	
	<tr style="display:none">
		<td class="CL"><script>show_words('HW_NAT_enable')</script></td>
		<td class="CR">
			<input type="checkbox" id="HW_NAT_Enable" name="HW_NAT_Enable" value="1" />
		</td>
	</tr>
	<div id="show_poe">
<!--
	<tr>
		<td class="CL"><script>show_words('bwn_AM')</script></td>
		<td class="CR">
			<input type="radio" name="wan_pppoe_dynamic_00" value="0" onClick="disable_poe_ip()" checked />
			<script>show_words('carriertype_ct_0')</script>
			<input type="radio" name="wan_pppoe_dynamic_00" value="1" onClick="disable_poe_ip()" />
			<script>show_words('_sdi_staticip')</script>
		</td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_ipaddr')</script></td>
		<td class="CR">
			<input type=text id="wan_pppoe_ipaddr_00" name="wan_pppoe_ipaddr_00" size="20" maxlength="15" value="" />
		</td>
	</tr>
-->
	<tr>
		<td class="CL"><script>show_words('bwn_UN')</script></td>
		<td class="CR">
			<input type="text" id="wan_pppoe_username_00" name="wan_pppoe_username_00" size="20" maxlength="63" value="" />
		</td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_password')</script></td>
		<td class="CR">
			<input type="password" id="poe_pass_s" name="poe_pass_s" size="20" maxlength="63" value="WDB8WvbXdH" onfocus="select();" />
		</td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_verifypw')</script></td>
		<td class="CR">
			<input type="password" id="poe_pass_v" name="poe_pass_v" size="20" maxlength="63" value="WDB8WvbXdH" onfocus="select();" />
		</td>
	</tr>
<!--	<tr>
		<td class="CL"><script>show_words('_srvname')</script></td>
		<td class="CR">
			<input type="text" id="wan_pppoe_service_00" name="wan_pppoe_service_00" size="30" maxlength="39" value="" />
			<script>show_words('_optional')</script>
		</td>
	</tr>
-->
	<tr>
		<td class="CL" rowspan="2" id="wPppoeOPMode"><script>show_words('_opeartion_mode');</script></td>
		<td class="CR">
			<select id="pppoeOPMode" name="pppoeOPMode" size="1">
				<option value="0"><script>show_words('_keep_alive');</script></option>
				<option value="2"><script>show_words('bwn_RM_1');</script></option>
				<option value="1"><script>show_words('bwn_RM_2');</script></option>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="CELL">
			<script>show_words('_keep_alive_mode_redial');</script>
			<input type="text" id="pppoeRedialPeriod" name="pppoeRedialPeriod" maxlength="5" size="3" value="10" />
			<script>show_words('_seconds');</script>
			<br>
			<script>show_words('_on_demand_mode_idle_time');</script>
			<input type="text" id="wan_pppoe_max_idle_time_00" name="wan_pppoe_max_idle_time_00" maxlength="3" size="2" value="5" disabled="" />
			<script>show_words('_mintues_lower');</script>
		</td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_net_ipv6_11');</script></td>
		<td class="CR">
			<select name="dhcpDNSSelect" id="dhcpDNSSelect" size="1">
			<option id="dhcpDNSDisabled" value="0" selected="selected"><script>show_words('_disable');</script></option>
			<option id="dhcpDNSEnabled" value="1"><script>show_words('_enable');</script></option>
			</select>
		</td>
	</tr>
	</div>
	</table>
</div>

<div id="DNSServer" class="box_tn"><!--  style="display:none;" -->
	<div id="wStaticMode" class="CT"><script>show_words('_dns_server_setting');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
		<tr>
			<td class="CL" id="wStaticPriDns"><script>show_words('_dns1');</script></td>
			<td class="CR"><input type="text" id="wan_primary_dns" name="wan_primary_dns" size="20" maxlength="15" value="" /></td>
		</tr>
		<tr>
			<td class="CL" id="wStaticSecDns"><script>show_words('_dns2');</script></td>
			<td class="CR"><input type="text" id="wan_secondary_dns" name="wan_secondary_dns" size="20" maxlength="15" value="" /></td>
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
				<input type="text" id="wan_pppoe_mtu" name="wan_pppoe_mtu" maxlength="4" value="" /><script>document.write(get_words('_mtu_default_byte').replace('%s', 1492));</script>
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

<div class="box_tn">
	<table cellspacing="0" cellpadding="0" class="formarea">
		<tr align="center">
	  		<td colspan="2" class="btn_field">
				<input name="button" type="button" class="ButtonSmall" id="button" onClick="return send_pppoe_request()" value="" />
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