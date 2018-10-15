<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | Administrator | Status</title>
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
	var sysCfg = {
		'fwver'	:		dev_info.fw_ver+', '+dev_info.ver_date,
		'uptime':		''
	};
	
	var temp_wlan_enable = 1;

	var obj;
function getDeviceStatus() {
	var start_time = new Date();
	start_time= start_time.getTime();
	obj = new ccpObject();
	obj.set_param_url('get_set.ccp');
	obj.set_ccp_act('get');
	obj.add_param_arg('IGD_Status_General_',1100);
	obj.add_param_arg('IGD_WANDevice_i_WANStatus_',1100);
	obj.add_param_arg('IGD_LANDevice_i_LANHostConfigManagement_',1100);
	obj.add_param_arg('IGD_LANDevice_i_ConnectedAddress_i_',1110);
	obj.add_param_arg('IGD_WLANConfiguration_i_',1000);
	obj.add_param_arg('IGD_',1000);
	obj.add_param_arg('IGD_Time_',1100);
	for (i=1;i<=8;i++){
		//obj.add_param_arg('IGD_LANDevice_i_WLANConfiguration_i_','11'+i+'00');
		obj.add_param_arg('IGD_WLANConfiguration_i_WEP_','1'+i+'10');
		//obj.add_param_arg('IGD_WLANConfiguration_i_WPS_','1'+i+'10');
		obj.add_param_arg('IGD_WLANConfiguration_i_WPA_','1'+i+'10');
		obj.add_param_arg('IGD_WLANConfiguration_i_WLANStatus_','1'+i+'10');
	}

	obj.get_config_obj();

	sysCfg.uptime = (obj.config_val('sysTime_SystemUpTime_')==''?0:obj.config_val('sysTime_SystemUpTime_'));
	
	var isReg = (obj.config_val("igd_Register_st_")? obj.config_val("igd_Register_st_"):"");
	var devMode = (obj.config_val("igd_DeviceMode_")? obj.config_val("igd_DeviceMode_"):"0");
	if(devMode == '1')	//ap mode, hide wan st
	{
		$('#st_wan').hide();
		$('#st_igmp').hide();
		$('#st_router_lan').hide();
		$('#st_ap_lan').show();
	}
	else
	{
		$('#st_wan').show();	//show
		$('#st_igmp').show();
		$('#st_router_lan').show();	//show
		$('#st_ap_lan').hide();
	}
	get_time();
	
	// WAN
	var wanProto = '';
	switch(obj.config_val('igdWanStatus_WanProto_'))
	{
		case "0":
			wanProto =  get_words('_sdi_staticip');
			break;
		case "1":
			wanProto =  get_words('bwn_Mode_DHCP');
			break;
		case "2":
			wanProto =  get_words('_PPPoE');
			break;
		case "3":
			wanProto =  get_words('_PPTP');
			break;
		case "4":
			wanProto =  get_words('_L2TP');
			break;
		case "6":
			wanProto =  get_words('rus_wan_pppoe');
			break;
		case "7":
			wanProto =  get_words('rus_wan_pptp');
			break;
		case "8":
			wanProto =  get_words('rus_wan_l2tp');
			break;
		case "9":
			wanProto =  get_words('bwn_Mode_DHCPPLUS');
			break;
		case "10":
			wanProto = 	get_words('IPV6_TEXT140');
			break;
	}
	
	$('#connection_type').html(wanProto);
	if(obj.config_val('igdWanStatus_CableStatus_') == 'Connected')
		$('#cable_status').html(get_words('CONNECTED'));
	else
		$('#cable_status').html(get_words('DISCONNECTED'));
		
	get_wan_time(obj.config_val('igdWanStatus_WanUpTime_'));
	$('#show_uptime').html();
	$('#show_mac').html(obj.config_val('igdWanStatus_MACAddress_'));
	
	var conn_st = obj.config_val("igdWanStatus_NetworkStatus_");
	
	if(conn_st == "Disconnected")
	{
		$('#wan_ip').html("0.0.0.0");
		$('#wan_netmacaddr').html("00:00:00:00:00:00");
		$('#wan_netmask').html("0.0.0.0");
		$('#wan_gateway').html("0.0.0.0");
		$('#wan_dns1').html("0.0.0.0");
		$('#wan_dns2').html("0.0.0.0");
//		$('#aftr').html("None");
//		$('#ds_dhcp').html(obj.config_val('igdWanStatus_DsLiteDHCP_'));
	}
	else
	{
		$('#wan_ip').html(obj.config_val('igdWanStatus_IPAddress_'));
		$('#wan_netmacaddr').html(obj.config_val('igdWanStatus_MACAddress_'));
		$('#wan_netmask').html(obj.config_val('igdWanStatus_SubnetMask_'));
		$('#wan_gateway').html(obj.config_val('igdWanStatus_DefaultGateway_'));
		$('#wan_dns1').html(obj.config_val('igdWanStatus_PrimaryDNSAddress_'));
		$('#wan_dns2').html(obj.config_val('igdWanStatus_SecondaryDNSAddress_'));
//		$('#aftr').html(filter_ipv6_addr(obj.config_val('igdWanStatus_DsLiteAFTR_')));
//		$('#ds_dhcp').html(obj.config_val('igdWanStatus_DsLiteDHCP_'));
	}
//	$('#opendns_enable').html(
//		(obj.config_val('igdWanStatus_AdvancedDNSEnable_') == '0'? get_words('_disabled'): get_words('_enabled'))
//	);
	
	// LAN
	$('#lan_mac').html(obj.config_val('lanHostCfg_MACAddress_'));
	$('#lan_ip').html(obj.config_val('lanHostCfg_IPAddress_'));
	$('#lan_netmask').html(obj.config_val('lanHostCfg_SubnetMask_'));
	$('#lan_dhcpd_enable').html(
		(obj.config_val('lanHostCfg_DHCPServerEnable_') == '0'? get_words('_disabled'): get_words('_enabled'))
	);
	
	$('#ap_lan_mac').html(obj.config_val('igdLanStatus_MACAddress_'));
	$('#ap_lan_ip').html(obj.config_val('lanHostCfg_APIPAddress_'));
	$('#ap_lan_netmask').html(obj.config_val('lanHostCfg_APSubnetMask_'));
	$('#ap_lan_gw').html(obj.config_val('lanHostCfg_APGateway_'));
	$('#ap_lan_dns1').html(obj.config_val('lanHostCfg_APPrimaryDNSAddress_'));
	$('#ap_lan_dns2').html(obj.config_val('lanHostCfg_APSecondaryDNSAddress_'));
	
	// WLAN
	var wifiCfg = 
	{
		'enable': 		obj.config_str_multi("igdWlanStatus_WlanEnable_"),
		'wlanMac': 		obj.config_str_multi("igdWlanStatus_WlanMAC_"),
		'ssid':			obj.config_str_multi("wlanCfg_SSID_"),
		'standard':		obj.config_str_multi("wlanCfg_Standard_"),
		'standard5G':	obj.config_str_multi("wlanCfg_Standard5G_"),
		'channelWidth':	obj.config_str_multi("wlanCfg_ChannelWidth_"),
		'channellan':	obj.config_str_multi("wlanCfg_Channel_"),
		'channel':		obj.config_str_multi("igdWlanStatus_Channel_"),
		'security':		obj.config_str_multi("wlanCfg_SecurityMode_")
	};
	
	var wepCfg = 
	{
		'keyLength':	obj.config_str_multi("wepInfo_KeyLength_"),
		'authType':		obj.config_str_multi("wepInfo_AuthenticationMode_")
	};

	var wpaCfg = 
	{
		'wpamode' :		obj.config_str_multi("wpaInfo_WPAMode_"),
		'wpacipher' :   obj.config_str_multi("wpaInfo_EncryptionMode_")
	};

//	var wps_enable = obj.config_str_multi('wpsCfg_Enable_');
//	var wps_config = obj.config_str_multi('wpsCfg_Status_');

	var wlanEnable = wifiCfg.enable[0];
	//var wlanEnable = 1;
	if ( wlanEnable != null && wlanEnable == '1')
	{
		$('#show_wlan0').html(get_words('_enable'));
		$('#wlan0_mac').html(wifiCfg.wlanMac[0]);
		//$('#wlan0_channel').html(wifiCfg.channellan[0]);
		$('#wlan0_channel').html(wifiCfg.channel[0]);
		for(var i=0;i<4;i++){
			var security = (wifiCfg.security[i]? wifiCfg.security[i]:"0");
			switch(security)
			{
				case "1":
				{
					var keyLenStr = "";
					var authTypeStr = "";
					if(wepCfg.keyLength[i] == "0")
						keyLenStr = "64";
					else
						keyLenStr = "128";
						
	/*						if(wepCfg.authType[i] == "0")
						authTypeStr = "Open";
					else if(wepCfg.authType[i] == "1")
						authTypeStr = "Share";
					else if(wepCfg.authType[i] == "2")
						authTypeStr = "Both";
	*/						//$('#wlan0_security'+i).html("wep_auto_"+keyLenStr);
					$('#wlan0_security'+i).html(get_words('_WEP'));//+ " " +authTypeStr+" "+keyLenStr);
				}
				break;
				case "2":
					//$('#wlan0_security'+i).html(get_words('_WPApersonal'));
					var wpamode = "";
					//var ciphermode = "";
					if(wpaCfg.wpamode[i] == "0")
						//wpamode = get_words('bws_WPAM_2') + " - PSK";
						wpamode = get_words('bws_WPAM_2');
					else if(wpaCfg.wpamode[i] == "1")
						//wpamode = 'wpa2_psk';
						wpamode = get_words('bws_WPAM_3');
					else
						//wpamode = 'wpa_psk';
						wpamode = get_words('bws_WPAM_1');
	/*						if(wpaCfg.wpacipher[i] == "0")
						ciphermode = get_words('bws_CT_1');
					else if(wpaCfg.wpacipher[i] == "1")
						ciphermode = get_words('bws_CT_2');
					else
						ciphermode = get_words('bws_CT_3');
	*/						//$('#wlan0_security'+i).html(wpamode);
					$('#wlan0_security'+i).html(wpamode+ " - PSK"); //+ " / " + ciphermode);
				break;
				case "3":
					//$('#wlan0_security'+i).html(get_words('_WPAenterprise'));
				var wpamode = "";
				//var ciphermode = "";
				if(wpaCfg.wpamode[i] == "0")
					wpamode = get_words('bws_WPAM_2'); //+ " - EAP";
				else if(wpaCfg.wpamode[i] == "1")
					//wpamode = 'wpa2_eap';
					wpamode = get_words('bws_WPAM_3');
				else
					//wpamode = 'wpa_eap';
					wpamode = get_words('bws_WPAM_1');
	/*					if(wpaCfg.wpacipher[i] == "0")
					ciphermode = get_words('bws_CT_1');
				else if(wpaCfg.wpacipher[i] == "1")
					ciphermode = get_words('bws_CT_2');
				else
					ciphermode = get_words('bws_CT_3');
	*/					$('#wlan0_security'+i).html(wpamode+ " - EAP"); //+ "/" + ciphermode);
				//$('#wlan0_security'+i).html(wpamode);
				break;
				case "0":
				default:
					$('#wlan0_security'+i).html(get_words('_disable_s'));
				break;
			}
		}
		
		$('#wlan0_ssid0').html(sp_words(wifiCfg.ssid[0])+' / '+$('#wlan0_security0').html());
		if(wifiCfg.enable[1]=='1')
			$('#wlan0_ssid1').html(sp_words(wifiCfg.ssid[1])+' / '+$('#wlan0_security1').html());
		else
			$('#wlan0_ssid1').html('');
		if(wifiCfg.enable[2]=='1')
			$('#wlan0_ssid2').html(sp_words(wifiCfg.ssid[2])+' / '+$('#wlan0_security2').html());
		else
			$('#wlan0_ssid2').html('');
		if(wifiCfg.enable[3]=='1')
			$('#wlan0_ssid3').html(sp_words(wifiCfg.ssid[3])+' / '+$('#wlan0_security3').html());
		else
			$('#wlan0_ssid3').html('');
	}
	
	var wlanEnable = wifiCfg.enable[4];
	//var wlanEnable = 1;
	if ( wlanEnable != null && wlanEnable == '1')
	{
//		$('#show_wlan1').html(get_words('_enable'));
		$('#wlan1_mac').html(wifiCfg.wlanMac[4]);
		//$('#wlan1_channel').html(wifiCfg.channellan[4]);
		$('#wlan1_channel').html(wifiCfg.channel[4]);
		for(var i=0;i<4;i++){
			var security = (wifiCfg.security[i+4]? wifiCfg.security[i+4]:"0");
			switch(security)
			{
				case "1":
				{
					var keyLenStr = "";
					var authTypeStr = "";
					if(wepCfg.keyLength[i+4] == "0")
						keyLenStr = "64";
					else
						keyLenStr = "128";
						
	/*						if(wepCfg.authType[i+4] == "0")
						authTypeStr = "Open";
					else if(wepCfg.authType[i+4] == "1")
						authTypeStr = "Share";
					else if(wepCfg.authType[i+4] == "2")
						authTypeStr = "Both";
	*/						//$('#wlan1_security'+i).html("wep_auto_"+keyLenStr);
					$('#wlan1_security'+i).html(get_words('_WEP'));//+ " " +authTypeStr+" "+keyLenStr);
				}
				break;
				case "2":
					//$('#wlan1_security'+i).html(get_words('_WPApersonal'));
					var wpamode = "";
					//var ciphermode = "";
					if(wpaCfg.wpamode[i+4] == "0")
						//wpamode = get_words('bws_WPAM_2') + " - PSK";
						wpamode = get_words('bws_WPAM_2');
					else if(wpaCfg.wpamode[i+4] == "1")
						//wpamode = 'wpa2_psk';
						wpamode = get_words('bws_WPAM_3');
					else
						//wpamode = 'wpa_psk';
						wpamode = get_words('bws_WPAM_1');
	/*						if(wpaCfg.wpacipher[i+4] == "0")
						ciphermode = get_words('bws_CT_1');
					else if(wpaCfg.wpacipher[i+4] == "1")
						ciphermode = get_words('bws_CT_2');
					else
						ciphermode = get_words('bws_CT_3');
	*/						//$('#wlan1_security'+i).html(wpamode);
					$('#wlan1_security'+i).html(wpamode+ " - PSK"); //+ " / " + ciphermode);
				break;
				case "3":
					//$('#wlan1_security'+i).html(get_words('_WPAenterprise'));
				var wpamode = "";
				//var ciphermode = "";
				if(wpaCfg.wpamode[i+4] == "0")
					wpamode = get_words('bws_WPAM_2'); //+ " - EAP";
				else if(wpaCfg.wpamode[i+4] == "1")
					//wpamode = 'wpa2_eap';
					wpamode = get_words('bws_WPAM_3');
				else
					//wpamode = 'wpa_eap';
					wpamode = get_words('bws_WPAM_1');
	/*					if(wpaCfg.wpacipher[i+4] == "0")
					ciphermode = get_words('bws_CT_1');
				else if(wpaCfg.wpacipher[i+4] == "1")
					ciphermode = get_words('bws_CT_2');
				else
					ciphermode = get_words('bws_CT_3');
	*/					$('#wlan1_security'+i).html(wpamode+ " - EAP"); //+ "/" + ciphermode);
				//$('#wlan1_security'+i).html(wpamode);
				break;
				case "0":
				default:
					$('#wlan1_security'+i).html(get_words('_disable_s'));
				break;
			}
		}
		
		$('#wlan1_ssid0').html(sp_words(wifiCfg.ssid[4])+' / '+$('#wlan1_security0').html());
		if(wifiCfg.enable[5]=='1')
			$('#wlan1_ssid1').html(sp_words(wifiCfg.ssid[5])+' / '+$('#wlan1_security1').html());
		else
			$('#wlan1_ssid1').html('');
		if(wifiCfg.enable[6]=='1')
			$('#wlan1_ssid2').html(sp_words(wifiCfg.ssid[6])+' / '+$('#wlan1_security2').html());
		else
			$('#wlan1_ssid2').html('');
		if(wifiCfg.enable[7]=='1')
			$('#wlan1_ssid3').html(sp_words(wifiCfg.ssid[7])+' / '+$('#wlan1_security3').html());
		else
			$('#wlan1_ssid3').html('');
	}
	
	set_control_button();
	var end_time = new Date();
	end_time= end_time.getTime();
	var cal_time = (end_time-start_time)/1000 ;
	if (cal_time<=3)
		setTimeout("getDeviceStatus()",3000);
	else
		setTimeout("getDeviceStatus()",cal_time*1000);
}

var nNow,gTime;
function get_time(){
	if (gTime)
		return;
	var gTime = obj.config_val('sysTime_CurrentLocalTime_');
	var time = gTime.split(",");
	gTime = month_device[time[1]-1] + " " + time[2] + ", " + time[0] + " " + time[3] + ":" + time[4] + ":" + time[5];		
	nNow = new Date(gTime);
}

function STime(){
	nNow.setTime(nNow.getTime() + 1000);
	//var date = new Date();
	var fulldate = ' '+change_week(nNow.getDay()) + 
					' ' + change_mon(nNow.getMonth()) + 
					' ' + nNow.getDate() + 
					' ' + len_checked(nNow.getHours()) + 
					':' + len_checked(nNow.getMinutes()) + 
					':' + len_checked(nNow.getSeconds()) + 
					' ' + nNow.getFullYear();

	$("#sysTime").html(fulldate);
	//$("#show_time").html(nNow.toLocaleString());
	setTimeout('STime()', 1000);
}

	function padout(number) {
		return (number < 10) ? '0' + number : number;
	}

var wTimesec, wan_time;
var temp, days = 0, hours = 0, mins = 0, secs = 0;
function caculate_time(wTimesec){
	var tttt;
	var wTime = Math.floor(wTimesec);
	var days = Math.floor(wTime / 86400);
		wTime %= 86400;
		var hours = Math.floor(wTime / 3600);
		wTime %= 3600;
		var mins = Math.floor(wTime / 60);
		wTime %= 60;

		tttt = days + " " + 
			((days <= 1) 
				? get_words('tt_Day')
				: get_words('gw_days'))
			+ ", ";
		tttt += hours + ":" + padout(mins) + ":" + padout(wTime);
	return tttt;
}

function get_wan_time(_t){
	var wTimesec = parseInt(_t);
	if(wTimesec == 0)
		//wan_time = "N/A";
		wan_time = get_words('_na');
	else{
		wTimesec = wTimesec/100;
		wan_time = caculate_time(wTimesec);
	}
}
function WTime(){
	$("#WanUptime").html(wan_time);
	if(wTimesec != 0){
		wTimesec++;
		wan_time = caculate_time(wTimesec);
	}
	setTimeout('WTime()', 1000);
}

var upTimesec, up_time;
function get_up_time(_t){
	upTimesec = parseInt(_t);
	if(upTimesec == 0)
		//up_time = "N/A";
		up_time = get_words('_na');
	else{
//		upTimesec = upTimesec/100;
		up_time = caculate_time(upTimesec);
	}
}
function UpTime(){
	$("#sysUptime").html(up_time);
	if(upTimesec != 0){
		upTimesec++;
		up_time = caculate_time(upTimesec);
	}
	setTimeout('UpTime()', 1000);
}
function set_control_button(){
	var wan_type = obj.config_val("igdWanStatus_WanProto_");
	var button_position = $('#show_button')[0];
	if(wan_type != "0")
	{
		var commonAction1 = "do_connect()";
		var commonAction2 = "do_disconnect()";

		var button1_name = get_words("_connect");
		var button2_name = get_words("LS315");
		var button1_action = commonAction1;
		var button2_action = commonAction2;

		if(wan_type=="1"){
			button1_name = get_words("LS312");	
			button2_name = get_words("LS313");
		}

		
		
		var ctrl_buttons = 
		'<table id="box_statusButton" cellspacing="0" cellpadding="0" class="formarea">'+
		'<tr>'+
			'<td colspan="2" align="center" class="btn_field">'+
				'<input type="button" class="button_submit" id="connect" value="'+button1_name+'" onclick="'+button1_action+'" />'+
				'<input type="button" class="button_submit" id="disconnect" value="'+button2_name+'" onclick="'+button2_action+'" />'+
			'</td>'+
		'</tr>'+
		'</table>';
			
		$("#ctrl_buttons").html(ctrl_buttons);
		if(dev_info.login_info == "w")
		{
			{
				var conn_st = obj.config_val("igdWanStatus_NetworkStatus_");
				if(conn_st == "Disconnected")
				{
					$("#disconnect").attr("disabled",'disabled');
					$('#network_status').html(get_words('DISCONNECTED'));
				}
				else if($("#network_status").html() == get_words('ddns_disconnecting'))
					$("#disconnect").attr("disabled",'disabled');
				else
				{
					$('#network_status').html(get_words('CONNECTED'));
					$("#connect").attr("disabled",'disabled');
				}
			}
		}
		else
		{
			var conn_st = obj.config_val("igdWanStatus_NetworkStatus_");
			if(conn_st == "Disconnected")
				$('#network_status').html(get_words('DISCONNECTED'));
			else
				$('#network_status').html(get_words('CONNECTED'));
			$("#connect").attr("disabled",'disabled');
			$("#disconnect").attr("disabled",'disabled');
		}
	}
	else
	{
		var conn_st = obj.config_val("igdWanStatus_NetworkStatus_");
		if(conn_st == "Disconnected")
			$('#network_status').html(get_words('DISCONNECTED'));
		else
			$('#network_status').html(get_words('CONNECTED'));
	}
}

function do_connect(){
	$("#network_status").html(get_words('ddns_connecting'));
	$("#connect").attr("disabled",'disabled');
	var event = new ccpObject();
	event.set_param_url('get_set.ccp');
	event.set_ccp_act('doEvent');
	event.add_param_event('CCP_SUB_DOWANCONNECT');
	event.get_config_obj();
}

function do_disconnect(){
	$("#network_status").html(get_words('ddns_disconnecting'));
	$("#disconnect").attr("disabled",'disabled');
	var event = new ccpObject();
	event.set_param_url('get_set.ccp');
	event.set_ccp_act('doEvent');
	event.add_param_event('CCP_SUB_DOWANDISCONNECT');
	event.get_config_obj();
}

function setValueFirmwareVersion(){
	var val_fw = sysCfg.fwver;
	$('#fwVer').html(val_fw);
}
function setValueSystemTime(){
	STime();
}
function setValueSystemUpTime(){
	var val_uptime = sysCfg.uptime;
	get_up_time(val_uptime);
	UpTime();
}
$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(model);
	getDeviceStatus();
	//System Info
	setValueFirmwareVersion();
	setValueSystemTime();
	setValueSystemUpTime();
	
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
				<script>document.write(menu.build_structure(1,0,0))</script>
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
								<script>show_words('_status');</script>
								</div>
								<div class="hr"></div>
								<div class="section_content_border">
								<div class="header_desc" id="manStatusIntroduction">
									<script>show_words('_help_txt225');</script>
									<p></p>
								</div>

<div class="box_tn">
	<div class="CT"><script>show_words('_system_info');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<!-- ----------------- System Info ----------------- -->
	<tr>
		<td class="CL"><script>show_words('sd_FWV');</script></td>
		<td class="CR"><span id="fwVer"></span></td>
		<!--<td>1.0.0.2, 17-Jan-2008</td>-->
	</tr>
	<tr>
		<td class="CL"><script>show_words('_system_time');</script></td>
		<td class="CR"><span id="sysTime"></span></td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_system_up_time');</script></td>
		<td class="CR"><span id="sysUptime"></span></td>
	</tr>
	</table>
</div>

<div class="box_tn">
	<div class="CT"><script>show_words('_internet_configs');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL" id="statusConnectedType"><script>show_words('_connected_type');</script></td>
		<td class="CR"><span id="connection_type"></span></td>
	</tr>
<script>/*
		<tr>
			<td class="CL">WAN Network Status</td>
			<td><span id="wanNetworkStatus">&nbsp;</span></td>
		</tr>
*/</script>
	<tr>
		<td class="CL" id="statusWANIPAddr"><script>show_words('_wan_ip_addr');</script></td>
		<td class="CR"><span id="wan_ip"></span></td>
	</tr>
	<tr>
		<td class="CL" id="statusMACAddr"><script>show_words('WAN');</script> <script>show_words('_macaddr');</script></td>
		<td class="CR"><span id="wan_netmacaddr"></span></td>
	</tr>
	<tr>
		<td class="CL" id="statusSubnetMask"><script>show_words('_subnet');</script></td>
		<td class="CR"><span id="wan_netmask"></span></td>
	</tr>
	<tr>
		<td class="CL" id="statusDefaultGW"><script>show_words('_defgw');</script></td>
		<td class="CR"><span id="wan_gateway"></span></td>
	</tr>
	<tr>
		<td class="CL" id="statusPrimaryDNS"><script>show_words('_pri_dns');</script></td>
		<td class="CR"><span id="wan_dns1"></span></td>
	</tr>
	<tr>
		<td class="CL" id="statusSecondaryDNS"><script>show_words('_sec_dns');</script></td>
		<td class="CR"><span id="wan_dns2"></span></td>
	</tr>
	<tr style="display:none">
		<td class="CL" id="statusWANMAC"><script>show_words('_macaddr');</script></td>
		<td class="CR"><span id="show_mac"></span></td>
	</tr>
	</table>
	<!--
	/*
	** Date:	2013-02-08
	** Author:	Moa Chung
	** Reason:	fixed renew/release btn cannot enable/disable.
	**/
	-->
	<span id="network_status" style="display:none;"></span>
	<span id="ctrl_buttons">
	</span>
</div>

<div class="box_tn">
	<div class="CT"><script>show_words('_LAN');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL"><script>show_words('_macaddr');</script></td>
		<td class="CR"><span id="lan_mac"></span></td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_ipaddr');</script></td>
		<td class="CR"><span id="lan_ip"></span></td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_subnet');</script></td>
		<td class="CR"><span id="lan_netmask"></span></td>
	</tr>
	</table>
</div>
<!-- 2.4GHz -->
<div class="box_tn">
	<div class="CT"><script>show_words('_24Ghz_wireless');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL"><script>show_words('_macaddr');</script></td>
		<td class="CR"><span id="wlan0_mac"></span></td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_channel');</script></td>
		<td class="CR"><span id="wlan0_channel"></span></td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('sd_NNSSID');</script> / <script>show_words('sd_SecTyp');</script></td>
		<td class="CR"><span id="wlan0_ssid0"></span></td>
	</tr>
	<tr style="display:none;">
		<td colspan="2" class="CELL"><span id="wlan0_security0"></span></td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_lb_multi_ssid_1');</script> / <script>show_words('sd_SecTyp');</script></td>
		<td class="CR"><span id="wlan0_ssid1"></span></td>
	</tr>
	<tr style="display:none;">
		<td colspan="2" class="CELL"><span id="wlan0_security1"></span></td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_lb_multi_ssid_2');</script> / <script>show_words('sd_SecTyp');</script></td>
		<td class="CR"><span id="wlan0_ssid2"></span></td>
	</tr>
	<tr style="display:none;">
		<td colspan="2" class="CELL"><span id="wlan0_security2"></span></td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_guest_network');</script> / <script>show_words('sd_SecTyp');</script></td>
		<td class="CR"><span id="wlan0_ssid3"></span></td>
	</tr>
	<tr style="display:none;">
		<td colspan="2" class="CELL"><span id="wlan0_security3"></span></td>
	</tr>
	</table>
</div>
<!-- 5GHz -->
<div class="box_tn">
	<div class="CT"><script>show_words('_5Ghz_wireless');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL"><script>show_words('_macaddr');</script></td>
		<td class="CR"><span id="wlan1_mac"></span></td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_channel');</script></td>
		<td class="CR"><span id="wlan1_channel"></span></td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('sd_NNSSID');</script> / <script>show_words('sd_SecTyp');</script></td>
		<td class="CR"><span id="wlan1_ssid0"></span></td>
	</tr>
	<tr style="display:none;">
		<td colspan="2" class="CELL"><span id="wlan1_security0"></span></td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_lb_multi_ssid_1');</script> / <script>show_words('sd_SecTyp');</script></td>
		<td class="CR"><span id="wlan1_ssid1"></span></td>
	</tr>
	<tr style="display:none;">
		<td colspan="2" class="CELL"><span id="wlan1_security1"></span></td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_lb_multi_ssid_2');</script> / <script>show_words('sd_SecTyp');</script></td>
		<td class="CR"><span id="wlan1_ssid2"></span></td>
	</tr>
	<tr style="display:none;">
		<td colspan="2" class="CELL"><span id="wlan1_security2"></span></td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_guest_network');</script> / <script>show_words('sd_SecTyp');</script></td>
		<td class="CR"><span id="wlan1_ssid3"></span></td>
	</tr>
	<tr style="display:none;">
		<td colspan="2" class="CELL"><span id="wlan1_security3"></span></td>
	</tr>
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