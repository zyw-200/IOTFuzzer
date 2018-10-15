<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | Basic | Wireless</title>
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
<script type="text/javascript" src="js/jquery-ext.js"></script>
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
	var ch2_lst		= dev_info.ch2_lst;
	var ch5_lst		= dev_info.ch5_lst;
	var ch5_DFS_lst	= dev_info.ch5_DFS_lst;
	var logo_fw		= dev_info.Logo_FW;

	var submit_c	= "";
	var is_wps;//wps enable or not
	var is_wps_5;//wps_5G enable or not
	/**
	**    Date:		2013-08-13
	**    Author:	Silvia Chang
	**    Reason:	DFS Manually switch for CE logo test
	**    Note:		0 --> disable DFS channel option, can not be selected
					1 --> enable  DFC channel option, can be selected
	**	 2013-10-30 if DFS_cert enable set select_sort_channel
	**/
	var DFS_Cert	= 1;

	var main = new ccpObject();
	main.set_param_url('get_set.ccp');
	main.set_ccp_act('get');
	main.add_param_arg('IGD_',1000);
	main.add_param_arg('IGD_ScheduleRule_i_',1000);

	for (i=1;i<=8;i++){
		main.add_param_arg('IGD_WLANConfiguration_i_','1'+i+'00');
		main.add_param_arg('IGD_WLANConfiguration_i_WEP_','1'+i+'10');
		main.add_param_arg('IGD_WLANConfiguration_i_WEP_WEPKey_i_','1'+i+'10');
		main.add_param_arg('IGD_WLANConfiguration_i_WPS_','1'+i+'10');
		main.add_param_arg('IGD_WLANConfiguration_i_WPA_','1'+i+'10');
		main.add_param_arg('IGD_WLANConfiguration_i_WPA_PSK_','1'+i+'11');
		main.add_param_arg('IGD_WLANConfiguration_i_WPA_EAP_i_','1'+i+'10');
	}

	main.get_config_obj();

	var lanCfg = {
		'enable':			main.config_str_multi("wlanCfg_Enable_"),
		'schedule':			main.config_str_multi("wlanCfg_ScheduleIndex_"),
		'rate':				main.config_str_multi("wlanCfg_TransmitRate_"),
		'bssid':			main.config_str_multi("wlanCfg_BSSID_"),
		'ssid':				main.config_str_multi("wlanCfg_SSID_"),
		'autochan':			main.config_str_multi("wlanCfg_AutoChannel_"),
		'channel':			main.config_str_multi("wlanCfg_Channel_"),
		'coexi':			main.config_str_multi("wlanCfg_BSSCoexistenceEnable_"),
		'beaconEnab':		main.config_str_multi("wlanCfg_BeaconAdvertisementEnabled_"),//VisibilityStatus
		'chanwidth':		main.config_str_multi("wlanCfg_ChannelWidth_"),
		'standard':			main.config_str_multi("wlanCfg_Standard_"),
		'standard5G':		main.config_str_multi("wlanCfg_Standard5G_"),
		'sMode':			main.config_str_multi("wlanCfg_SecurityMode_"),	//wep_type_value
		'wdsenable':		main.config_str_multi("wlanCfg_WDSEnable_"),
		'exchannel':		main.config_str_multi("wlanCfg_ExChannel_"),
		'shortgi':			main.config_str_multi("wlanCfg_ShortGIEnable_")
	}

	var htCfg = {
		'operating':		main.config_str_multi("wlanCfg_OperatingMode_"),
		'rdg':				main.config_str_multi("wlanCfg_ReverseDirectionGrant_"),
		'amsdu':			main.config_str_multi("wlanCfg_AMSDU_"),
		'autoba':			main.config_str_multi("wlanCfg_AutoBlockACK_"),
		'declineba':		main.config_str_multi("wlanCfg_DeclineBA_")
	}

	var otherCfg = {
		'rxstream':			main.config_str_multi("wlanCfg_RxStream_"),
		'txstream':			main.config_str_multi("wlanCfg_TxStream_")
	}

	var wpsCfg = {
		'enable':			main.config_str_multi("wpsCfg_Enable_"),
		'status':			main.config_str_multi("wpsCfg_Status_")
	}

	var wepCfg = {
		'infokey':			main.config_str_multi("wepInfo_KeyIndex_"),
		'infoAuthMode':		main.config_str_multi("wepInfo_AuthenticationMode_"),
		'infoKeyL':			main.config_str_multi("wepInfo_KeyLength_"),
		'key64':			main.config_str_multi("wepKey_KeyHEX64_"),
		'key128':			main.config_str_multi("wepKey_KeyHEX128_"),
		'keyType':			main.config_str_multi("wepKey_KeyType_"),
		'keyLength':		main.config_str_multi("wepKey_KeyLength_")
	};
	var wpaCfg = {
		'infoWPAMode':		main.config_str_multi("wpaInfo_WPAMode_"),
		'infoAuthMode':		main.config_str_multi("wpaInfo_AuthenticationMode_"),
		'infoKeyup':		main.config_str_multi("wpaInfo_KeyUpdateInterval_"),
		'infoTimeout':		main.config_str_multi("wpaInfo_AuthenticationTimeout_"),
		'infoMode':			main.config_str_multi("wpaInfo_WPAMode_"),
		'encrMode':			main.config_str_multi("wpaInfo_EncryptionMode_"),
		'pskKey':			main.config_str_multi("wpaPSK_KeyPassphrase_")
	};
	var EapCfg ={
		'ip':				main.config_str_multi("wpaEap_RadiusServerIP_"),
		'port':				main.config_str_multi("wpaEap_RadiusServerPort_"),
		'psk':				main.config_str_multi("wpaEap_RadiusServerPSK_"),
		'macauth':			main.config_str_multi("wpaEap_MACAuthentication_"),
		'preauth':			main.config_str_multi("wpaEap_PreAuthentication_"),
		'cacheT':			main.config_str_multi("wpaEap_PMKCachePeriod_"),
		'SessionT':			main.config_str_multi("wpaEap_RadiusSessionTime_")
		
	};

	var schedule_cnt = 0;
	var array_sch_inst 	=	main.config_inst_multi("IGD_ScheduleRule_i_");
	var schCfg = {
		'name':				main.config_str_multi("schRule_RuleName_"),
		'allweek':			main.config_str_multi("schRule_AllWeekSelected_"),
		'allday':			main.config_str_multi("schRule_AllDayChecked_"),
		'weekday':			main.config_str_multi("schRule_SelectedDays_"),
		'start_h':			main.config_str_multi("schRule_StartHour_"),
		'start_m':			main.config_str_multi("schRule_StartMinute_"),
		'end_h':			main.config_str_multi("schRule_EndHour_"),
		'end_m':			main.config_str_multi("schRule_EndMinute_")
	};
	
	if(schCfg.name != null)
		schedule_cnt = schCfg.name.length;
	var wifi_txRate = (lanCfg.rate[0]? lanCfg.rate[0]:"0");
	var wifi_txRate1 = (lanCfg.rate[4]? lanCfg.rate[4]:"0");

	var isWDS = main.config_str_multi("wlanCfg_WDSEnable_");
	var SSID = main.config_str_multi("wlanCfg_SSID_");
	var standard = main.config_str_multi("wlanCfg_Standard_");
	var standard5g = main.config_str_multi("wlanCfg_Standard5G_");
	var securMode = main.config_str_multi("wlanCfg_SecurityMode_"); //need to add check b/g/n
	
	function check_value()
	{
		is_wps = wpsCfg.enable[0];
		is_wps_5 = wpsCfg.enable[4];
		var ssid_vs = $('input[name=wlan0_ssid_broadcast]:checked').val();
		var ssid_vs1 = $('input[name=wlan1_ssid_broadcast]:checked').val();
		var alert_st = 0;	// value != 0 not alert msg yet
		
		if (wpsCfg.enable[0] == 1)
		{
			if (ssid_vs == 0 && alert_st == 0)
			{
				alert_st = -1;
				if (confirm(get_words("msg_wps_sec_03")) == false)
					return false;
				else
					is_wps = 0;
			}
		}
		if (wpsCfg.enable[4] == 1)
		{
			if (ssid_vs1 == 0 && alert_st == 0)
			{
				alert_st = -1;
				if (confirm(get_words("msg_wps_sec_03")) == false)
					return false;
				else
					is_wps_5 = 0;
			}
		}
		/*
		** Date:	2013-05-03
		** Author:	Moa Chung
		** Reason:	Wireless 2.4G/5G → basic：When security mode is WEP/WPA-TKIP, you can not set 802.11n only.
		** Note:	TEW-810DR pretest no. 112,118,131
		**/
		if($('#dot11_mode').val()=='n')
		{
			for(var i=0;i<4;i++)
			{
				if(lanCfg.sMode[i]=='1' ||//WEP
					lanCfg.sMode[i]=='2' && wpaCfg.encrMode[i]=='0' //WPA-TKIP
				)
				{
					alert(get_words('_wlan_11n_not_support_wep_wpa_tkip'));
					return false;
				}
			}
		}
		if($('#dot11_mode1').val()=='n')
		{
			for(var i=4;i<8;i++)
			{
				if(lanCfg.sMode[i]=='1' ||//WEP
					lanCfg.sMode[i]=='2' && wpaCfg.encrMode[i]=='0' //WPA-TKIP
				)
				{
					alert(get_words('_wlan_11n_not_support_wep_wpa_tkip'));
					return false;
				}
			}
		}
		if(!(check_ssid_0("show_ssid_0")))
			return false;
		if(!(check_ssid_0("show_ssid1_0")))
			return false;
		return true;
	}

	function check_apply()
	{
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('basic_wireless.asp');
		
		if(check_value())
		{
			var arrIns = [1,2,3,4];
			for(var v in arrIns)
			{
				var i = arrIns[v];
				//Wireless Network
				switch($('#dot11_mode').val())
				{
					case 'bg':
						obj.add_param_arg('wlanCfg_Standard_','1.'+i+'.0.0','3');
						obj.add_param_arg('wlanCfg_TransmitRate_','1.'+i+'.0.0',$('#wlan0_11bg_txrate').val());
						break;
					case "n":
						obj.add_param_arg('wlanCfg_Standard_','1.'+i+'.0.0','2');
						obj.add_param_arg('wlanCfg_TransmitRate_','1.'+i+'.0.0',0);
						break;
					case "bgn":
						obj.add_param_arg('wlanCfg_Standard_','1.'+i+'.0.0','5');
						obj.add_param_arg('wlanCfg_TransmitRate_','1.'+i+'.0.0',0);
						break;
				}
				switch($('#dot11_mode1').val())
				{
					case 'a':
						obj.add_param_arg('wlanCfg_Standard5G_','1.'+(i+4)+'.0.0','1');
						obj.add_param_arg('wlanCfg_TransmitRate_','1.'+(i+4)+'.0.0',$('#wlan1_11a_txrate').val());
						break;
					case "n":
						obj.add_param_arg('wlanCfg_Standard5G_','1.'+(i+4)+'.0.0','0');
						obj.add_param_arg('wlanCfg_TransmitRate_','1.'+(i+4)+'.0.0',0);
						break;
					case "na":
						obj.add_param_arg('wlanCfg_Standard5G_','1.'+(i+4)+'.0.0','2');
						obj.add_param_arg('wlanCfg_TransmitRate_','1.'+(i+4)+'.0.0',0);
						break;
					case "acna":
						obj.add_param_arg('wlanCfg_Standard5G_','1.'+(i+4)+'.0.0','4');
						obj.add_param_arg('wlanCfg_TransmitRate_','1.'+(i+4)+'.0.0',0);
						break;
				}

				var beacon = $('input[name=wlan0_ssid_broadcast]:checked').val();
				obj.add_param_arg('wlanCfg_BeaconAdvertisementEnabled_','1.'+i+'.0.0',beacon);
				
				var autochannel = (($('#sel_wlan0_channel').val()==0)?"1":"0");
				obj.add_param_arg('wlanCfg_AutoChannel_','1.'+i+'.0.0',autochannel);
				
				var channel = $('#sel_wlan0_channel').val();
				obj.add_param_arg('wlanCfg_Channel_','1.'+i+'.0.0',channel);
				
				/*
				** Date:	2013-04-29
				** Author:	Moa Chung
				** Reason:	Wireless 5G → basic：SSID broadcast can not disable.
				** Note:	TEW-810DR pre-test no.72
				**/
				var beacon = $('input[name=wlan1_ssid_broadcast]:checked').val();
				obj.add_param_arg('wlanCfg_BeaconAdvertisementEnabled_','1.'+(i+4)+'.0.0',beacon);
				
				var autochannel = (($('#sel_wlan1_channel').val()==0)?"1":"0");
				obj.add_param_arg('wlanCfg_AutoChannel_','1.'+(i+4)+'.0.0',autochannel);
				
				var channel = $('#sel_wlan1_channel').val();
				obj.add_param_arg('wlanCfg_Channel_','1.'+(i+4)+'.0.0',channel);
			
				//HT Physical Mode
				obj.add_param_arg('wlanCfg_OperatingMode_','1.'+i+'.0.0',$('input[name=n_opmode]:checked').val());
				obj.add_param_arg('wlanCfg_ChannelWidth_','1.'+i+'.0.0',$('input[name=n_bandwidth]:checked').val());
				obj.add_param_arg('wlanCfg_ExChannel_','1.'+i+'.0.0',$('#n_extcha').val());
				
				obj.add_param_arg('wlanCfg_OperatingMode_','1.'+(i+4)+'.0.0',$('input[name=n_opmode1]:checked').val());
				obj.add_param_arg('wlanCfg_ChannelWidth_','1.'+(i+4)+'.0.0',$('input[name=n_bandwidth1]:checked').val());
				obj.add_param_arg('wlanCfg_ExChannel_','1.'+(i+4)+'.0.0',$('#n_extcha1').val());
				
				//Other
				obj.add_param_arg('wlanCfg_TxStream_','1.'+i+'.0.0',$('#tx_stream').val());
				obj.add_param_arg('wlanCfg_RxStream_','1.'+i+'.0.0',$('#rx_stream').val());
				obj.add_param_arg('wlanCfg_TxStream_','1.'+(i+4)+'.0.0',$('#tx_stream1').val());
				obj.add_param_arg('wlanCfg_RxStream_','1.'+(i+4)+'.0.0',$('#rx_stream1').val());
				
			}
			
			obj.add_param_arg('wlanCfg_ScheduleIndex_','1.1.0.0',$('#ssid_schedule').val());
			obj.add_param_arg('wlanCfg_ScheduleIndex_','1.5.0.0',$('#ssid_schedule1').val());
			obj.add_param_arg('wlanCfg_SSID_','1.1.0.0',urlencode($('#show_ssid_0').val()));
			obj.add_param_arg('wlanCfg_SSID_','1.5.0.0',urlencode($('#show_ssid1_0').val()));
			
		/* security 2.4G */
		//check data
		if (checkData() == false)
			return false;
		
		var idx = 1;
		var security_mode = $('#security_mode').val();
		
		if(security_mode == "Disable")
			obj.add_param_arg('wlanCfg_SecurityMode_','1.'+idx+'.0.0','0');
		else if(security_mode == "OPEN" || security_mode == "SHARED" ||security_mode == "WEPAUTO")//WEP_MODE
		{
			obj.add_param_arg('wlanCfg_SecurityMode_','1.'+idx+'.0.0','1');
			
			if(security_mode == "OPEN")
				obj.add_param_arg('wepInfo_AuthenticationMode_','1.'+idx+'.1.0','0');
			else if(security_mode == "SHARED")
				obj.add_param_arg('wepInfo_AuthenticationMode_','1.'+idx+'.1.0','1');
			else if(security_mode == "WEPAUTO")
				obj.add_param_arg('wepInfo_AuthenticationMode_','1.'+idx+'.1.0','2');
			
			obj.add_param_arg('wepInfo_KeyIndex_','1.'+idx+'.1.0',$('#wep_default_key').val());
			/* 
			** Date:	2012-03-29
			** Author:	Pascal Pai
			** Reason:	Saving each WEP key's key length
			**/	
			for(var i=1; i<=4; i++)
			{
				obj.add_param_arg('wepKey_KeyType_','1.'+idx+'.1.'+i,$('#WEP'+i+'Select').val());
				
				if($('#WEP'+i).val().length == 5 || $('#WEP'+i).val().length == 10)
				{
					obj.add_param_arg('wepKey_KeyLength_','1.'+idx+'.1.'+i,0);
					obj.add_param_arg('wepKey_KeyHEX64_','1.'+idx+'.1.'+i,urlencode($('#WEP'+i).val()));
				}
				else
				{
					obj.add_param_arg('wepKey_KeyLength_','1.'+idx+'.1.'+i,1);
					obj.add_param_arg('wepKey_KeyHEX128_','1.'+idx+'.1.'+i,urlencode($('#WEP'+i).val()));
				}
			}
		}
		else
		{
			if(security_mode == "WPAPSK" || security_mode == "WPA2PSK" || security_mode == "WPAPSKWPA2PSK") //WPA_P
			{
				obj.add_param_arg('wlanCfg_SecurityMode_','1.'+idx+'.0.0','2');
				
				if(security_mode == "WPAPSKWPA2PSK")
					obj.add_param_arg('wpaInfo_WPAMode_','1.'+idx+'.1.0',0);
				else if(security_mode == "WPA2PSK")
					obj.add_param_arg('wpaInfo_WPAMode_','1.'+idx+'.1.0',1);
				else if(security_mode == "WPAPSK")
					obj.add_param_arg('wpaInfo_WPAMode_','1.'+idx+'.1.0',2);
				
				obj.add_param_arg('wpaInfo_AuthenticationMode_','1.'+idx+'.1.0',0);
				obj.add_param_arg('wpaPSK_KeyPassphrase_','1.'+idx+'.1.1',$("#passphrase").val());
			}
			else if(security_mode == "WPA" || security_mode == "WPA2" || security_mode == "WPA1WPA2") //WPA_E
			{
				obj.add_param_arg('wlanCfg_SecurityMode_','1.'+idx+'.0.0','3');
				
				if(security_mode == "WPA1WPA2")
					obj.add_param_arg('wpaInfo_WPAMode_','1.'+idx+'.1.0',0);
				else if(security_mode == "WPA2")
					obj.add_param_arg('wpaInfo_WPAMode_','1.'+idx+'.1.0',1);
				else if(security_mode == "WPA")
					obj.add_param_arg('wpaInfo_WPAMode_','1.'+idx+'.1.0',2);
				
				obj.add_param_arg('wpaInfo_AuthenticationMode_','1.'+idx+'.1.0',1);
				
				obj.add_param_arg('wpaEap_RadiusServerIP_','1.'+idx+'.1.1',$("#RadiusServerIP").val());
				obj.add_param_arg('wpaEap_RadiusServerPort_','1.'+idx+'.1.1',$("#RadiusServerPort").val());
				obj.add_param_arg('wpaEap_RadiusServerPSK_','1.'+idx+'.1.1',$("#RadiusServerSecret").val());
				obj.add_param_arg('wpaEap_PreAuthentication_','1.'+idx+'.1.1',get_radio_value(get_by_name("PreAuthentication")));
				obj.add_param_arg('wpaEap_PMKCachePeriod_','1.'+idx+'.1.1',$('#PMKCachePeriod').val());
				obj.add_param_arg('wpaEap_RadiusSessionTime_','1.'+idx+'.1.1',$('#RadiusServerSessionTimeout').val());
			}
			
			obj.add_param_arg('wpaInfo_EncryptionMode_','1.'+idx+'.1.0',get_radio_value(get_by_name("cipher")));
			obj.add_param_arg('wpaInfo_KeyUpdateInterval_','1.'+idx+'.1.0',$("#keyRenewalInterval").val());
		}
		/* security 5G */
		//check data
		if (checkData1() == false)
			return false;
		
		var idx = 5;
		var security_mode = $('#security_mode1').val();
		
		if(security_mode == "Disable")
			obj.add_param_arg('wlanCfg_SecurityMode_','1.'+idx+'.0.0','0');
		else if(security_mode == "OPEN" || security_mode == "SHARED" ||security_mode == "WEPAUTO")//WEP_MODE
		{
			obj.add_param_arg('wlanCfg_SecurityMode_','1.'+idx+'.0.0','1');
			
			if(security_mode == "OPEN")
				obj.add_param_arg('wepInfo_AuthenticationMode_','1.'+idx+'.1.0','0');
			else if(security_mode == "SHARED")
				obj.add_param_arg('wepInfo_AuthenticationMode_','1.'+idx+'.1.0','1');
			else if(security_mode == "WEPAUTO")
				obj.add_param_arg('wepInfo_AuthenticationMode_','1.'+idx+'.1.0','2');
			
			obj.add_param_arg('wepInfo_KeyIndex_','1.'+idx+'.1.0',$('#wep_default_key1').val());
			/* 
			** Date:	2012-03-29
			** Author:	Pascal Pai
			** Reason:	Saving each WEP key's key length
			**/	
			for(var i=1; i<=4; i++)
			{
				obj.add_param_arg('wepKey_KeyType_','1.'+idx+'.1.'+i,$('#WEP'+i+'Select1').val());
				
				if($('#WEP'+i+'1').val().length == 5 || $('#WEP'+i+'1').val().length == 10)
				{
					obj.add_param_arg('wepKey_KeyLength_','1.'+idx+'.1.'+i,0);
					obj.add_param_arg('wepKey_KeyHEX64_','1.'+idx+'.1.'+i,urlencode($('#WEP'+i+'1').val()));
				}
				else
				{
					obj.add_param_arg('wepKey_KeyLength_','1.'+idx+'.1.'+i,1);
					obj.add_param_arg('wepKey_KeyHEX128_','1.'+idx+'.1.'+i,urlencode($('#WEP'+i+'1').val()));
				}
			}
		}
		else
		{
			if(security_mode == "WPAPSK" || security_mode == "WPA2PSK" || security_mode == "WPAPSKWPA2PSK") //WPA_P
			{
				obj.add_param_arg('wlanCfg_SecurityMode_','1.'+idx+'.0.0','2');
				
				if(security_mode == "WPAPSKWPA2PSK")
					obj.add_param_arg('wpaInfo_WPAMode_','1.'+idx+'.1.0',0);
				else if(security_mode == "WPA2PSK")
					obj.add_param_arg('wpaInfo_WPAMode_','1.'+idx+'.1.0',1);
				else if(security_mode == "WPAPSK")
					obj.add_param_arg('wpaInfo_WPAMode_','1.'+idx+'.1.0',2);
				
				obj.add_param_arg('wpaInfo_AuthenticationMode_','1.'+idx+'.1.0',0);
				obj.add_param_arg('wpaPSK_KeyPassphrase_','1.'+idx+'.1.1',$("#passphrase1").val());
			}
			else if(security_mode == "WPA" || security_mode == "WPA2" || security_mode == "WPA1WPA2") //WPA_E
			{
				obj.add_param_arg('wlanCfg_SecurityMode_','1.'+idx+'.0.0','3');
				
				if(security_mode == "WPA1WPA2")
					obj.add_param_arg('wpaInfo_WPAMode_','1.'+idx+'.1.0',0);
				else if(security_mode == "WPA2")
					obj.add_param_arg('wpaInfo_WPAMode_','1.'+idx+'.1.0',1);
				else if(security_mode == "WPA")
					obj.add_param_arg('wpaInfo_WPAMode_','1.'+idx+'.1.0',2);
				
				obj.add_param_arg('wpaInfo_AuthenticationMode_','1.'+idx+'.1.0',1);
				
				obj.add_param_arg('wpaEap_RadiusServerIP_','1.'+idx+'.1.1',$("#RadiusServerIP1").val());
				obj.add_param_arg('wpaEap_RadiusServerPort_','1.'+idx+'.1.1',$("#RadiusServerPort1").val());
				obj.add_param_arg('wpaEap_RadiusServerPSK_','1.'+idx+'.1.1',$("#RadiusServerSecret1").val());
				obj.add_param_arg('wpaEap_PreAuthentication_','1.'+idx+'.1.1',get_radio_value(get_by_name("PreAuthentication1")));
				obj.add_param_arg('wpaEap_PMKCachePeriod_','1.'+idx+'.1.1',$('#PMKCachePeriod1').val());
				obj.add_param_arg('wpaEap_RadiusSessionTime_','1.'+idx+'.1.1',$('#RadiusServerSessionTimeout1').val());
			}
			obj.add_param_arg('wpaInfo_EncryptionMode_','1.'+idx+'.1.0',get_radio_value(get_by_name("cipher1")));
			obj.add_param_arg('wpaInfo_KeyUpdateInterval_','1.'+idx+'.1.0',$("#keyRenewalInterval1").val());
		}
		/* other setting(not in page) */
		obj.add_param_arg('wpsCfg_Enable_','1.1.1.0',is_wps);
		obj.add_param_arg('wpsCfg_Enable_','1.2.1.0',is_wps);
		obj.add_param_arg('wpsCfg_Enable_','1.3.1.0',is_wps);
		obj.add_param_arg('wpsCfg_Enable_','1.4.1.0',is_wps);
		obj.add_param_arg('wpsCfg_Enable_','1.5.1.0',is_wps_5);
		obj.add_param_arg('wpsCfg_Enable_','1.6.1.0',is_wps_5);
		obj.add_param_arg('wpsCfg_Enable_','1.7.1.0',is_wps_5);
		obj.add_param_arg('wpsCfg_Enable_','1.8.1.0',is_wps_5);
		
		var paramForm = obj.get_param();
		
		totalWaitTime = (logo_fw == 1) ? 90 : 20; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(paramForm.url, paramForm.arg, logo_fw);
	}
}

function add_option(id)
{
	var obj = null;
	var arr = null;
	var nam = null;
	
	if (id == 'Schedule') {
		obj = schedule_cnt;
		arr = array_sch_inst;
		nam = schCfg.name;
	} else if (id == 'Inbound') {
		obj = inbound_cnt;
		arr = array_ib_inst;
		nam = array_ib_name;
	}
	
	if (obj == null)
		return;

	for (var i = 0; i < obj; i++){		
			var inst = inst_array_to_string(arr[i]);
			document.write("<option value=" + inst.charAt(1) + ">" + nam[i] + "</option>");
	}	
}
//	var txrate_11b = new Array('11Mbps', '5.5Mbps', '2Mbps', '1Mbps');
//	var txrate_11g = new Array('54Mbps', '48Mbps', '36Mbps', '24Mbps', '18Mbps', '12Mbps', '9Mbps', '6Mbps');
//	var txrate_11n = new Array('MCS15: 130M(270M)', 'MCS14: 117M(243M)', 'MCS13: 104M(216M)', 'MCS12: 78M(162M)', 'MCS11: 52M(108M)', 'MCS10: 39M(81M)', 'MCS9: 26M(54M)', 'MCS8: 13M(27M)', 'MCS7: 65M(135M)', 'MCS6: 58.5M(121.5M)', 'MCS5: 52M(108M)', 'MCS4: 39M(81M)', 'MCS3: 26M(54M)', 'MCS2: 19.5M(40.5M)', 'MCS1: 13M(27M)', 'MCS0: 6.5M(13.5M)');
//	var txrate_11b_value = new Array(25,26,27,28);
//	var txrate_11g_value = new Array(17,18,19,20,21,22,23,24);
//	var txrate_11n_value = new Array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16);
	var txrate_11a = new Array('6Mbps', '9Mbps', '12Mbps', '18Mbps', '24Mbps', '36Mbps', '48Mbps', '54Mbps');
	var txrate_11b = new Array('1Mbps', '2Mbps', '5.5Mbps', '11Mbps');
	var txrate_11g = new Array('6Mbps', '9Mbps', '12Mbps', '18Mbps', '24Mbps', '36Mbps', '48Mbps', '54Mbps');
	//2013.01.25 brian say not support MCS>15
//	var txrate_11n = new Array('MCS0: 6.5M(13.5M)', 'MCS1: 13M(27M)', 'MCS2: 19.5M(40.5M)', 'MCS3: 26M(54M)', 'MCS4: 39M(81M)', 'MCS5: 52M(108M)', 'MCS6: 58.5M(121.5M)', 'MCS7: 65M(135M)', 'MCS8: 13M(27M)', 'MCS9: 26M(54M)', 'MCS10: 39M(81M)', 'MCS11: 52M(108M)', 'MCS12: 78M(162M)', 'MCS13: 104M(216M)', 'MCS14: 117M(243M)', 'MCS15: 130M(270M)');//, 'MCS16: 19.5M(40.5M)', 'MCS17: 39M(81M)', 'MCS18: 58.5M(121.5M)', 'MCS19: 78M(162M)', 'MCS20: 117M(243M)', 'MCS21: 156M(324M)', 'MCS22: 175.5M(364.5M)', 'MCS23: 195M(405M)','MCS32: 6M'
	var txrate_11n = new Array('MCS0: 6.5M(13.5M)', 'MCS1: 13M(27M)', 'MCS2: 19.5M(40.5M)', 'MCS3: 26M(54M)', 'MCS4: 39M(81M)', 'MCS5: 52M(108M)', 'MCS6: 58.5M(121.5M)', 'MCS7: 65M(135M)');//, 'MCS8: 13M(27M)', 'MCS9: 26M(54M)', 'MCS10: 39M(81M)', 'MCS11: 52M(108M)', 'MCS12: 78M(162M)', 'MCS13: 104M(216M)', 'MCS14: 117M(243M)', 'MCS15: 130M(270M)', 'MCS16: 19.5M(40.5M)', 'MCS17: 39M(81M)', 'MCS18: 58.5M(121.5M)', 'MCS19: 78M(162M)', 'MCS20: 117M(243M)', 'MCS21: 156M(324M)', 'MCS22: 175.5M(364.5M)', 'MCS23: 195M(405M)','MCS32: 6M'
//	var txrate_11ac = new Array('MCS0: 6.5M(13.5M)', 'MCS1: 13M(27M)', 'MCS2: 19.5M(40.5M)', 'MCS3: 26M(54M)', 'MCS4: 39M(81M)', 'MCS5: 52M(108M)', 'MCS6: 58.5M(121.5M)', 'MCS7: 65M(135M)', 'MCS8: 13M(27M)', 'MCS9: 26M(54M)');
	var txrate_11ac = new Array('MCS0: 6.5M(13.5M)', 'MCS1: 13M(27M)', 'MCS2: 19.5M(40.5M)', 'MCS3: 26M(54M)', 'MCS4: 39M(81M)', 'MCS5: 52M(108M)', 'MCS6: 58.5M(121.5M)', 'MCS7: 65M(135M)');//, 'MCS8: 13M(27M)', 'MCS9: 26M(54M)'
	var txrate_11b_value = new Array(37,36,35,34);
	var txrate_11g_value = new Array(33,32,31,30,29,28,27,26);
	var txrate_11n_value = new Array(25,24,23,22,21,20,19,18,17,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1);
	var txrate_11a_value = new Array(33,32,31,30,29,28,27,26);
	var txrate_11ac_value = new Array(25,24,23,22,21,20,19,18,17,16);
	function set_11a_txrate(obj){
		for(var i = 0; i < txrate_11a.length; i++){
			var ooption = document.createElement("option");
			
			obj.options[i+1] = null;
			ooption.text = txrate_11a[i];
			ooption.value = txrate_11a_value[i];
			obj.options[i+1] = ooption;	
		}
	}
	function set_11b_txrate(obj){
		for(var i = 0; i < txrate_11b.length; i++){
			var ooption = document.createElement("option");
			
			obj.options[i+1] = null;
			ooption.text = txrate_11b[i];
			ooption.value = txrate_11b_value[i];
			obj.options[i+1] = ooption;
		}
	}
	function set_11g_txrate(obj){
		for(var i = 0; i < txrate_11g.length; i++){
			var ooption = document.createElement("option");
			
			obj.options[i+1] = null;
			ooption.text = txrate_11g[i];
			ooption.value = txrate_11g_value[i];
			obj.options[i+1] = ooption;	
		}
	}
	function set_11n_txrate(obj){
		for(var i = 0; i < txrate_11n.length; i++){
			var ooption = document.createElement("option");
			
			obj.options[i+1] = null;
			ooption.text = txrate_11n[i];
			ooption.value = txrate_11n_value[i];//txrate_11n[i].split(':')[0];
			obj.options[i+1] = ooption;	
		}
	}
	function set_11bg_txrate(obj){
		var count = 0;
		var legth = txrate_11b.length + txrate_11g.length
		for(var bi=0,gi=0; bi+gi < legth;){
			var ooption = document.createElement("option");
			obj.options[bi+gi+1] = null;
			if(bi >= txrate_11b.length){
				ooption.text = txrate_11g[gi];
				ooption.value = txrate_11g_value[gi];
				obj.options[bi+gi+1] = ooption;
				gi++
			}else{
				ooption.text = txrate_11b[bi];
				ooption.value = txrate_11b_value[bi];
				obj.options[bi+gi+1] = ooption;
				bi++
			}
		}
	}
	function set_11gn_txrate(obj){
		var count = 0;
		var legth = txrate_11g.length + txrate_11n.length
		for(var i = 0; i < legth; i++){
			var ooption = document.createElement("option");
			obj.options[i+1] = null;
			if(i >= txrate_11n.length){
				ooption.text = txrate_11g[count];
				ooption.value = txrate_11g_value[count];
				count++;
			}else{
				ooption.text = txrate_11n[i];
				ooption.value = txrate_11n_value[i];
			}
			obj.options[i+1] = ooption;	
		}
	}
	function set_11bgn_txrate(obj){
		for(var i = 0; i < txrate_11n.length; i++){
			var ooption = document.createElement("option");
			
			obj.options[i+1] = null;
			ooption.text = txrate_11n[i];
			ooption.value = txrate_11n_value[i];//txrate_11n[i].split(':')[0];
			obj.options[i+1] = ooption;	
		}
	}
	function set_11ac_txrate(obj){
		for(var i = 0; i < txrate_11n.length; i++){
			var ooption = document.createElement("option");
			
			obj.options[i+1] = null;
			ooption.text = txrate_11ac[i];
			ooption.value = txrate_11ac_value[i];//txrate_11ac[i].split(':')[0];
			obj.options[i+1] = ooption;	
		}
	}
	function set_11na_txrate(obj){
		for(var i = 0; i < txrate_11n.length; i++){
			var ooption = document.createElement("option");
			
			obj.options[i+1] = null;
			ooption.text = txrate_11n[i];
			ooption.value = txrate_11n_value[i];//txrate_11n[i].split(':')[0];
			obj.options[i+1] = ooption;	
		}
	}
	function set_11acna_txrate(obj){
		for(var i = 0; i < txrate_11ac.length; i++){
			var ooption = document.createElement("option");
			
			obj.options[i+1] = null;
			ooption.text = txrate_11ac[i];
			ooption.value = txrate_11ac_value[i];//txrate_11n[i].split(':')[0];
			obj.options[i+1] = ooption;	
		}
	}
	
	function switchRadio(isOn)
	{
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('wireless_basic.asp');
		
		obj.add_param_arg('wlanCfg_Enable_','1.1.0.0',(isOn?"1":"0"));
		if(!isOn)
		{
			obj.add_param_arg('wlanCfg_Enable_','1.2.0.0','0');
			obj.add_param_arg('wlanCfg_Enable_','1.3.0.0','0');
			obj.add_param_arg('wlanCfg_Enable_','1.4.0.0','0');
		}
		else
		{
			if(lanCfg.ssid[1]!='')
				obj.add_param_arg('wlanCfg_Enable_','1.2.0.0','1');
			if(lanCfg.ssid[2]!='')
				obj.add_param_arg('wlanCfg_Enable_','1.3.0.0','1');
			if(lanCfg.ssid[3]!='')
				obj.add_param_arg('wlanCfg_Enable_','1.4.0.0','1');
		}
		
		var param = obj.get_param();
		
		totalWaitTime = 20; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(param.url, param.arg);
	}
	function switchRadio1(isOn){
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('wireless_basic.asp');
		
		obj.add_param_arg('wlanCfg_Enable_','1.5.0.0',(isOn?"1":"0"));
		if(!isOn)
		{
			obj.add_param_arg('wlanCfg_Enable_','1.6.0.0','0');
			obj.add_param_arg('wlanCfg_Enable_','1.7.0.0','0');
			obj.add_param_arg('wlanCfg_Enable_','1.8.0.0','0');
		}
		else
		{
			if(lanCfg.ssid[5]!='')
				obj.add_param_arg('wlanCfg_Enable_','1.6.0.0','1');
			if(lanCfg.ssid[6]!='')
				obj.add_param_arg('wlanCfg_Enable_','1.7.0.0','1');
			if(lanCfg.ssid[7]!='')
				obj.add_param_arg('wlanCfg_Enable_','1.8.0.0','1');
		}
		
		var param = obj.get_param();
		
		totalWaitTime = 20; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(param.url, param.arg);
	}
	
	
	function setValueWMode()
	{
		var dot11_mode_t = "bgn";
		switch(lanCfg.standard[0]){
		case '0':
			dot11_mode_t = "b";
			$('#wlan0_11b_txrate').val(wifi_txRate);
			break;
		case '1':
			dot11_mode_t = "g";
			$('#wlan0_11g_txrate').val(wifi_txRate);
			break;
		case '2':
			dot11_mode_t = "n";
			$('#wlan0_11n_txrate').val(wifi_txRate);
			break;
		case '3':
			dot11_mode_t = "bg";
			$('#wlan0_11bg_txrate').val(wifi_txRate);
			break;
		case '4':
			dot11_mode_t = "ng";
			$('#wlan0_11gn_txrate').val(wifi_txRate);
			break;
		case '5':
			dot11_mode_t = "bgn";
			$('#wlan0_11bgn_txrate').val(wifi_txRate);
			break;
		}
		set_selectIndex(dot11_mode_t, $('#dot11_mode')[0]);
	}
	function setValueWMode1(){
		var dot11_mode_t5 = "na";
		switch(lanCfg.standard5G[4]){
		case '0':
			dot11_mode_t5 = "n";
			$('#wlan1_11n_txrate').val(wifi_txRate1);
			break;
		case '1':
			dot11_mode_t5 = "a";
			$('#wlan1_11a_txrate').val(wifi_txRate1);
			break;
		case '2':
			dot11_mode_t5 = "na";
			$('#wlan1_11na_txrate').val(wifi_txRate1);
			break;
		case '4':
			dot11_mode_t5 = "acna";
			$('#wlan1_11acna_txrate').val(wifi_txRate1);
			break;
		case '5':
			dot11_mode_t5 = "acn";
			$('#wlan1_11acn_txrate').val(wifi_txRate1);
			break;
		case '3':
			dot11_mode_t5 = "ac";
			$('#wlan1_11ac_txrate').val(wifi_txRate1);
			break;
		}
		set_selectIndex(dot11_mode_t5, $('#dot11_mode1')[0]);
	}

	function setValueWirelessName()
	{
		$('#show_ssid_0').val(lanCfg.ssid[0]);
	}
	function setValueWirelessName1(){
		$('#show_ssid1_0').val(lanCfg.ssid[4]);
	}

	function setValueBoardcastSSID()
	{
		var chk_beacon = lanCfg.beaconEnab[0];
		$('input[name=wlan0_ssid_broadcast][value='+chk_beacon+']').attr('checked', true);
	}
	function setValueBoardcastSSID1(){
		var chk_beacon = lanCfg.beaconEnab[4];
		$('input[name=wlan1_ssid_broadcast][value='+chk_beacon+']').attr('checked', true);
	}

	function setValueFrequency()
	{
		if (ch2_lst == "UNKNOWN")
		{
			alert(get_words('HWerr'));
			ch2_lst = "1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11";
		}

		var current_channel =lanCfg.channel[0];
		var ch = ('0, '+ch2_lst).split(", ");
		for (var i = 0; i < ch.length; i++)
		{
			var opt = $('<option>');
			var g = parseInt(ch[i], 10)*5+2407+'';
			opt.text(ch[i]=='0'?get_words("_sel_autoselect"):''.concat(g, ' MHz (',get_words('_channel'),' ', ch[i],')'));
			opt.val(ch[i]);
			$('#sel_wlan0_channel').append(opt);
			if ((ch[i] == current_channel && lanCfg.autochan[0]!=1) || (i==0 && lanCfg.autochan[0]==1))
				opt.attr('selected', true);
		}
	}
	
	/* 2013.01.30 temporary, replace setValueFrequency later
	*/
	function setValueFrequency1(){
		if (ch5_lst == "UNKNOWN")
		{
			alert(get_words('HWerr'));//keep web show currect.
			ch5_lst = "36,40,44,48";
		}

		var current_channel =lanCfg.channel[4];
		var ch = ('0,'+ch5_lst).split(",");
		for (var i = 0; i < ch.length; i++)
		{
			if((i!=0)&&(ch[i]=='0'))//skip on EG
				continue;
			var opt = $('<option>');
			var g = parseInt(ch[i], 10)*5+5000+'';
			opt.text(ch[i]=='0'?get_words("_sel_autoselect"):''.concat(g, ' MHz (',get_words('_channel'),' ', ch[i],')'));
			opt.val(ch[i]);
			$('#sel_wlan1_channel').append(opt);
			if ((ch[i] == current_channel && lanCfg.autochan[4]!=1) || (i==0 && lanCfg.autochan[4]==1))
				opt.attr('selected', true);
		}
	}

	function setValueDFSChannelList()
	{
		//check DFSEnable
		if(logo_fw == 1)
		{
			var select_sort_channel = function() {
				var current_channel =lanCfg.channel[2];
				$("#sel_wlan1_channel option[value='" +current_channel+ "']").attr("selected", true);
			}

			var arrDFS = ch5_DFS_lst.split(", ");
			for(var i in arrDFS)
			{
				var g = parseInt(arrDFS[i], 10)*5+5000+'';	//calculate frequency by channel
				g = ''.concat(g, ' MHz (',get_words('_channel'),' ', arrDFS[i],')');
				if (DFS_Cert == 0)
					$('#sel_wlan1_channel').append($('<option>').val(arrDFS[i]).html(g).attr('disabled','disabled'));
				else if (DFS_Cert == 1)
					$('#sel_wlan1_channel').append($('<option>').val(arrDFS[i]).html(g));
			}
			$('#sel_wlan1_channel>option').sortElements(function(a, b){
				return parseInt($(a).val()) > parseInt($(b).val()) ? 1 : -1;
			});
			if (DFS_Cert == 1)
				select_sort_channel();
		}
	}

	function setEventWMode()
	{
		var func=function(){
			var sel_wmode = $('#dot11_mode option:selected').val();
			$('#show_11b_txrate').hide();
			$('#show_11g_txrate').hide();
			$('#show_11n_txrate').hide();
			$('#show_11bg_txrate').hide();
			$('#show_11gn_txrate').hide();
			$('#show_11bgn_txrate').hide();
			switch(sel_wmode){
			case 'b':
				$('#show_11b_txrate').show();
				$('#bandwidth,#guard_interval,#ext_channel').hide();
				break;
			case 'g':
				$('#show_11g_txrate').show();
				$('#bandwidth,#guard_interval,#ext_channel').hide();
				break;
			case 'n':
				$('#show_11n_txrate').show();
				$('#bandwidth,#guard_interval,#ext_channel').show();
				break;
			case 'bgn':
				$('#show_11bgn_txrate').show();
				$('#bandwidth,#guard_interval,#ext_channel').show();
				break;
			case 'bg':
				$('#show_11bg_txrate').show();
				$('#bandwidth,#guard_interval,#ext_channel').hide();
				break;
			case 'ng':
				$('#show_11gn_txrate').show();
				$('#bandwidth,#guard_interval,#ext_channel').show();
				break;
			default:
				alert('error: '+sel_wmode);
			}
		};
		func();
		$('#dot11_mode').change(func);
	}
	function setEventWMode1(){
		var func=function(){
			var sel_wmode = $('#dot11_mode1 option:selected').val();
			$('#show_11a_txrate1').hide();
			$('#show_11n_txrate1').hide();
			$('#show_11na_txrate1').hide();
			$('#show_11acna_txrate1').hide();
			switch(sel_wmode){
			case 'a':
				$('#show_11a_txrate1').show();
				$('#bandwidth1,#guard_interval1,#ext_channel1').hide();
				$('#bwl1_2040').show();
				$('#bwl1_204080').hide();
				break;
			case 'n':
				$('#show_11n_txrate1').show();
				$('#bandwidth1,#guard_interval1,#ext_channel1').show();
				$('#bwl1_2040').show();
				$('#bwl1_204080').hide();
				$('input[name=n_bandwidth1][value=1]').attr('checked', true);
				break;
			case 'na':
				$('#show_11na_txrate1').show();
				$('#bandwidth1,#guard_interval1,#ext_channel1').show();
				$('#bwl1_2040').show();
				$('#bwl1_204080').hide();
				$('input[name=n_bandwidth1][value=1]').attr('checked', true);
				break;
			case 'acna':
				$('#show_11acna_txrate1').show();
				$('#bandwidth1,#guard_interval1,#ext_channel1').show();
				$('#bwl1_2040').show();
				$('#bwl1_204080').show();
				$('input[name=n_bandwidth1][value=2]').attr('checked', true);
				break;
			default:
				alert('error: '+sel_wmode);
			}
		};
		func();
		$('#dot11_mode1').change(func);
	}

	function setEventFrequency()
	{
		var func = function(){
			var freqMap = new Array([0],[5],[6],[7],[8],[1,9],[2,10],[3,11],[4],[5],[6],[7],[8],[1]);
			var ch_text = new Array(get_words('_sel_autoselect'),"2412","2417","2422","2427","2432","2437","2442","2447","2452","2457","2462","2467","2472");
			var sel_freq = $('#sel_wlan0_channel').val();
			$('#n_extcha').children().remove();
			
			var channel_i = freqMap[sel_freq];
			for(var i=0;i<channel_i.length;i++)
			{
				var opt = $('<option/>');
				opt.text(channel_i[i]==0?ch_text[channel_i[i]]:ch_text[channel_i[i]] + "MHz (Channel " + channel_i[i]+")");
				opt.val(channel_i[i]);
				if(lanCfg.exchannel[0]==channel_i[i])
					opt.attr('selected', true);//這裡之後要改為多判斷datamodel的值
				$('#n_extcha').append(opt);
			}
			if(sel_freq=='0')
			{
				$('#n_extcha').attr('disabled','disabled');
			}
			else if($('input[name=n_bandwidth]:checked').val()==1)
			{
				$('#n_extcha').attr('disabled','');
			}
		};
		func();
		$('#sel_wlan0_channel').change(func);
	}
	function setEventFrequency1(){
		var func = function(){
			$('#dot11_mode1').trigger('change');
			var freqMap = {'0':'0','36':'40','40':'36','44':'48','48':'44','52':'56','56':'52','60':'64','64':'60','149':'153','153':'149','157':'161','161':'157','165':'0'};
			var ch_text = {'0':get_words('_sel_autoselect'),'36':"5180",'40':"5200",'44':"5220",'48':"5240",'52':"5260",'56':"5280",'60':"5300",'64':"5320",'149':"5745",'153':"5765",'157':"5785",'161':"5805",'165':"5825"};
			var sel_freq = $('#sel_wlan1_channel').val();
			$('#n_extcha1').children().remove();
			
			var channel_i = freqMap[sel_freq];
			
			var opt = $('<option/>');
			opt.text(channel_i==0?ch_text[channel_i]:ch_text[channel_i] + "MHz (Channel " + channel_i+")");
			opt.val(channel_i);
			if(lanCfg.exchannel[0]==channel_i)
				opt.attr('selected', true);//這裡之後要改為多判斷datamodel的值
			$('#n_extcha1').append(opt);
			
			if(sel_freq=='0')
			{
				$('#n_extcha1').attr('disabled','disabled');
			}
			/*
			** Date:	2013-05-03
			** Author:	Moa Chung
			** Reason:	Wireless 5G → basic：Channel 165 bandwodth should be only 20MHz.
			** Note:	TEW-810DR pretest no. 115, 133
			**/
			else if(sel_freq=='165')
			{
				$('#bwl1_2040').hide();
				$('#bwl1_204080').hide();
				$('input[name=n_bandwidth1][value=0]').attr('checked', true);
			}
			else if($('input[name=n_bandwidth1]:checked').val()==1)
			{
				$('#n_extcha1').attr('disabled','');
			}
		};
		func();
		$('#sel_wlan1_channel').change(func);
	}

	function setValueOperatingMode(){
		var chk_op = htCfg.operating[0];
		$('input[name=n_opmode][value='+chk_op+']').attr('checked', true);
	}		
	function setValueOperatingMode1(){
//		$('#opmode1').show();
		var chk_op = htCfg.operating[4];
		$('input[name=n_opmode1][value='+chk_op+']').attr('checked', true);
	}
	function setValueChannelBandWidth(){
		var chk_bw = lanCfg.chanwidth[0];
		$('input[name=n_bandwidth][value='+chk_bw+']').attr('checked', true);
	}
	function setValueChannelBandWidth1(){
		var chk_bw = lanCfg.chanwidth[4];
		$('input[name=n_bandwidth1][value='+chk_bw+']').attr('checked', true);
	}
	function setEventChannelBandWidth(){
		var func = function(){
			var wmode = $('#dot11_mode option:selected').val();
			var rad_bw = $('input[name=n_bandwidth]:checked').val();
			if(rad_bw==0)//no MCS32 in 20MHz
			{
				$('#n_extcha').attr('disabled','disabled');
				$('#wlan0_11'+wmode+'_txrate option[value=1]').remove();
			}
			//2013.01.25 brian say not support MCS>15
/*			else//add MCS32 in 20/40MHz
			{
				$('#n_extcha').attr('disabled','');
				if($('#wlan0_11'+wmode+'_txrate option[value=1]').length==0)
				{
					var option = document.createElement("option");
					option.text = "MCS32: 6M";
					option.value = "1";
					$('#wlan0_11'+wmode+'_txrate').append(option);
				}
				
			}*/
		};
		func();
		$('input[name=n_bandwidth]').change(func);
	}
	function setEventChannelBandWidth1(){
		var func = function(){
			var wmode = $('#dot11_mode1 option:selected').val();
			var rad_bw = $('input[name=n_bandwidth1]:checked').val();
			if(rad_bw==0)//no MCS32 in 20MHz
			{
				$('#n_extcha1').attr('disabled','disabled');
				$('#wlan1_11'+wmode+'_txrate option[value=1]').remove();
			}
			//2013.01.25 brian say not support MCS>15
/*			else//add MCS32 in 20/40MHz
			{
				$('#n_extcha').attr('disabled','');
				if($('#wlan1_11'+wmode+'_txrate option[value=1]').length==0)
				{
					var option = document.createElement("option");
					option.text = "MCS32: 6M";
					option.value = "1";
					$('#wlan1_11'+wmode+'_txrate').append(option);
				}
				
			}*/
		};
		func();
		$('input[name=n_bandwidth1]').change(func);
	}
	function setValueFortyIntolerant(){
		var chk_f40mhz = otherCfg.f40mhz[0];
		$('input[name=f_40mhz][value='+chk_f40mhz+']').attr('checked', true);
	}
	function setValueFortyIntolerant1(){
		var chk_f40mhz = otherCfg.f40mhz[4];
		$('input[name=f_40mhz1][value='+chk_f40mhz+']').attr('checked', true);
	}
	function setValueWiFiOptimum(){
		var chk_wifiopt = otherCfg.wifiopt[0];
		$('input[name=wifi_opt][value='+chk_wifiopt+']').attr('checked', true);
	}
	function setValueWiFiOptimum1(){
		var chk_wifiopt = otherCfg.wifiopt[1];
		$('input[name=wifi_opt1][value='+chk_wifiopt+']').attr('checked', true);
	}
	function setValueHTTxStream(){
		var sel_tx = otherCfg.txstream[0];
		$('#tx_stream').val(sel_tx);
	}
	function setValueHTTxStream1(){
		var sel_tx = otherCfg.txstream[4];
		$('#tx_stream1').val(sel_tx);
	}
	function setValueHTRxStream(){
		var sel_rx = otherCfg.rxstream[0];
		$('#rx_stream').val(sel_rx);
	}
	function setValueHTRxStream1(){
		var sel_rx = otherCfg.rxstream[4];
		$('#rx_stream1').val(sel_rx);
	}
	
//security start

	function isWDS_CipherType()
	{
		if(isWDS[0]=="3" && $('#security_mode').val() == "WPA2PSK")
		{
			$("#cipher[value=2]").attr('disabled', true);
			$("#cipher[value=1]").attr('checked', true);
		}
	}
	function isWDS_CipherType1()
	{
		if(isWDS[4]=="3" && $('#security_mode1').val() == "WPA2PSK")
		{
			$("#cipher1[value=2]").attr('disabled', true);
			$("#cipher1[value=1]").attr('checked', true);			
		}
	}
	
	function onWPAAlgorithmsClick(type)
	{
		var security_mode = $('#security_mode').val();
		var PhyMode = '8';
		if( PhyMode >= 5 && (security_mode == "WPA" || security_mode == "WPAPSK" || security_mode == "WPAPSKWPA2PSK" || security_mode == "WPA1WPA2"
			|| (security_mode == "WPA2" && type == 2) || (security_mode == "WPA2PSK" && type == 2)	)) 
			$('#msgbox').show();
		else 
			$('#msgbox').hide();
	}
	function onWPAAlgorithmsClick1(type)
	{
		var security_mode = $('#security_mode1').val();
		var PhyMode = '8';
		if( PhyMode >= 5 && (security_mode == "WPA" || security_mode == "WPAPSK" || security_mode == "WPAPSKWPA2PSK" || security_mode == "WPA1WPA2"
			|| (security_mode == "WPA2" && type == 2) || (security_mode == "WPA2PSK" && type == 2)	)) 
			$('#msgbox1').show();
		else 
			$('#msgbox1').hide();
	}

	function setValueSecurModeList()
	{
		var lstContent ="";
		
		if(isWDS[0] == "3") //WDS is enable
		{
			lstContent += '<select name="security_mode" id="security_mode" size="1" onchange="securityMode();">';
			lstContent += '<option value="Disable">'+get_words('_disable')+'</option>';
			lstContent += '<option value="OPEN">'+get_words('_wifiser_mode0')+'</option>';
			lstContent += '<option value="SHARED">'+get_words('_wifiser_mode1')+'</option>';
			lstContent += '<option value="WPAPSK">'+get_words('_WPA')+ "-" + get_words('LW24')+'</option>';
			lstContent += '<option value="WPA2PSK">'+get_words('_wifiser_mode6') + "-" + get_words('LW24')+'</option>';
			lstContent += '</select>';
		}
		else
		{
			lstContent += '<select name="security_mode" id="security_mode" size="1" onchange="securityMode();">';
			lstContent += '<option value="Disable">'+get_words('_disable')+'</option>';
			lstContent += '<option value="OPEN">'+get_words('_wifiser_mode0')+'</option>';
			lstContent += '<option value="SHARED">'+get_words('_wifiser_mode1')+'</option>';
			lstContent += '<option value="WEPAUTO">'+get_words('_wifiser_mode2')+'</option>';
			lstContent += '<option value="WPA">'+get_words('_WPAenterprise')+'</option>';
			lstContent += '<option value="WPAPSK">'+get_words('_WPA')+ "-" + get_words('LW24')+'</option>';
			lstContent += '<option value="WPA2PSK">'+get_words('_wifiser_mode6') + "-" + get_words('LW24')+'</option>';
			lstContent += '<option value="WPAPSKWPA2PSK">'+get_words('_wifiser_mode5')+'</option>';
			lstContent += '<option value="WPA2">'+get_words('_wifiser_mode6')+ "-" + get_words('LW23')+'</option>';
			lstContent += '<option value="WPA1WPA2">'+get_words('_wifiser_mode7')+'</option>';
			lstContent += '</select>';
		}
		
		$('#SecurMode_list').html(lstContent)
	}
	function setValueSecurModeList1()
	{
		var lstContent ="";
		
		if(isWDS[4] == "3") //WDS is enable
		{
			lstContent += '<select name="security_mode1" id="security_mode1" size="1" onchange="securityMode1();">';
			lstContent += '<option value="Disable">'+get_words('_disable')+'</option>';
			lstContent += '<option value="OPEN">'+get_words('_wifiser_mode0')+'</option>';
			lstContent += '<option value="SHARED">'+get_words('_wifiser_mode1')+'</option>';
			lstContent += '<option value="WPAPSK">'+get_words('_WPA')+ "-" + get_words('LW24')+'</option>';
			lstContent += '<option value="WPA2PSK">'+get_words('_wifiser_mode6') + "-" + get_words('LW24')+'</option>';
			lstContent += '</select>';
		}
		else
		{
			lstContent += '<select name="security_mode1" id="security_mode1" size="1" onchange="securityMode1();">';
			lstContent += '<option value="Disable">'+get_words('_disable')+'</option>';
			lstContent += '<option value="OPEN">'+get_words('_wifiser_mode0')+'</option>';
			lstContent += '<option value="SHARED">'+get_words('_wifiser_mode1')+'</option>';
			lstContent += '<option value="WEPAUTO">'+get_words('_wifiser_mode2')+'</option>';
			lstContent += '<option value="WPA">'+get_words('_WPAenterprise')+'</option>';
			lstContent += '<option value="WPAPSK">'+get_words('_WPA')+ "-" + get_words('LW24')+'</option>';
			lstContent += '<option value="WPA2PSK">'+get_words('_wifiser_mode6') + "-" + get_words('LW24')+'</option>';
			lstContent += '<option value="WPAPSKWPA2PSK">'+get_words('_wifiser_mode5')+'</option>';
			lstContent += '<option value="WPA2">'+get_words('_wifiser_mode6')+ "-" + get_words('LW23')+'</option>';
			lstContent += '<option value="WPA1WPA2">'+get_words('_wifiser_mode7')+'</option>';
			lstContent += '</select>';
		}
		
		$('#SecurMode_list1').html(lstContent)
	}
	
	function MBSSIDChange(index)
	{
		LoadFields(index);
		fill_WEPkeys(index);
		fill_WPA(index);

		//show warning msg
		onWPAAlgorithmsClick(get_radio_value(get_by_name("cipher")));
		
		//if WDS is enable, gray out TKIP/AES option
		isWDS_CipherType();
		
		return true;
	}
	function MBSSIDChange1(index)
	{
		LoadFields1(index);
		fill_WEPkeys1(index);
		fill_WPA1(index);

		//show warning msg
		onWPAAlgorithmsClick1(get_radio_value(get_by_name("cipher1")));
		
		//if WDS is enable, gray out TKIP/AES option
		isWDS_CipherType1();
		
		return true;
	}
	
	function fill_WEPkeys(index)
	{
		set_selectIndex(wepCfg.infokey[index], $('#wep_default_key')[0]);
		for(var i=0; i<4; i++)
		{
			$('#WEP'+(i+1)+'Select').val(wepCfg.keyType[index*4+i]);
			if(wepCfg.keyLength[index*4+i] == "0")
				$('#WEP'+(i+1)).val(wepCfg.key64[index*4+i]);
			else
				$('#WEP'+(i+1)).val(wepCfg.key128[index*4+i]);
		}
		if(wepCfg.infoAuthMode[index]==1) //shared
			$('#security_shared_mode').val(1);
		else
			$('#security_shared_mode').val(0);
	}
	function fill_WEPkeys1(index)
	{
		set_selectIndex(wepCfg.infokey[index], $('#wep_default_key1')[0]);
		for(var i=0; i<4; i++)
		{
			$('#WEP'+(i+1)+'Select1').val(wepCfg.keyType[index*4+i]);
			if(wepCfg.keyLength[index*4+i] == "0")
				$('#WEP'+(i+1)+'1').val(wepCfg.key64[index*4+i]);
			else
				$('#WEP'+(i+1)+'1').val(wepCfg.key128[index*4+i]);
		}
		if(wepCfg.infoAuthMode[index]==1) //shared
			$('#security_shared_mode1').val(1);
		else
			$('#security_shared_mode1').val(0);
	}
	
	function fill_WPA(index)
	{
		set_checked(wpaCfg.encrMode[index], get_by_name("cipher"));
		$('#passphrase').val(wpaCfg.pskKey[index]);
		$('#keyRenewalInterval').val(wpaCfg.infoKeyup[index]);
		$('#PMKCachePeriod').val(EapCfg.cacheT[index]);
		set_checked(EapCfg.preauth[index*2], get_by_name("PreAuthentication"));
		
		//802.1x wep
		/*
		if(IEEE8021X[MBSSID] == "1"){
			if(EncrypType[MBSSID] == "WEP")
				document.security_form.ieee8021x_wep[1].checked = true;
			else
				document.security_form.ieee8021x_wep[0].checked = true;
		}
		*/
		
		$('#RadiusServerIP').val(EapCfg.ip[index*2]);
		$('#RadiusServerPort').val(EapCfg.port[index*2]);
		$('#RadiusServerSecret').val(EapCfg.psk[index*2]);
		$('#RadiusServerSessionTimeout').val(EapCfg.SessionT[index*2]);
		
		
	}
	function fill_WPA1(index)
	{
		set_checked(wpaCfg.encrMode[index], get_by_name("cipher1"));
		$('#passphrase1').val(wpaCfg.pskKey[index]);
		$('#keyRenewalInterval1').val(wpaCfg.infoKeyup[index]);
		$('#PMKCachePeriod1').val(EapCfg.cacheT[index]);
		set_checked(EapCfg.preauth[index*2], get_by_name("PreAuthentication1"));
		
		//802.1x wep
		/*
		if(IEEE8021X[MBSSID] == "1"){
			if(EncrypType[MBSSID] == "WEP")
				document.security_form.ieee8021x_wep[1].checked = true;
			else
				document.security_form.ieee8021x_wep[0].checked = true;
		}
		*/
		
		$('#RadiusServerIP1').val(EapCfg.ip[index*2]);
		$('#RadiusServerPort1').val(EapCfg.port[index*2]);
		$('#RadiusServerSecret1').val(EapCfg.psk[index*2]);
		$('#RadiusServerSessionTimeout1').val(EapCfg.SessionT[index*2]);
		
		
	}
	
	function ShowAP(index)
	{
		for(var i=0; i<max_SSID; i++){
			set_selectIndex(MACAction[index], $('#apselect_'+i)[0]);
			if(index != i)
			{
				$('#AccessPolicy_'+i).hide();
				$('#AccessPolicy3_'+i).hide();
			}
			else
			{
				$('#AccessPolicy_'+i).show();
				$('#AccessPolicy3_'+i).show();
			}
		}
	}
	function ShowAP1(index)
	{
		for(var i=0; i<max_SSID; i++){
			set_selectIndex(MACAction[index], $('#apselect_'+i+'1')[0]);
			if(index != i)
			{
				$('#AccessPolicy_'+i+'1').hide();
				$('#AccessPolicy3_'+i+'1').hide();
			}
			else
			{
				$('#AccessPolicy_'+i+'1').show();
				$('#AccessPolicy3_'+i+'1').show();
			}
		}
	}

	function LoadFields(index)
	{
		//this SSID's security mode
		var sucurIndex;
		if(securMode[index]==0) //None
			sucurIndex="Disable";
		else if(securMode[index]==1) //WEP
		{
			switch(wepCfg.infoAuthMode[index]){
			case "0":
				sucurIndex="OPEN";
				break;
			case "1":
				sucurIndex="SHARED";
				break;
			case "2":
				sucurIndex="WEPAUTO";
				break;
			}
		
		}
		else if(securMode[index]==2) //WPA-P
		{
			switch(wpaCfg.infoWPAMode[index]){
			case "0":
				sucurIndex="WPAPSKWPA2PSK";
				break;
			case "1":
				sucurIndex="WPA2PSK";
				break;
			case "2":
				sucurIndex="WPAPSK";
				break;
			}
		}
		else if(securMode[index]==3) //WPA-E
		{
			switch(wpaCfg.infoWPAMode[index]){
			case "0":
				sucurIndex="WPA1WPA2";
				break;
			case "1":
				sucurIndex="WPA2";
				break;
			case "2":
				sucurIndex="WPA";
				break;
			}
		}
		$('#security_mode').val(sucurIndex);
		securityMode();
		
		
	}
	function LoadFields1(index)
	{
		//this SSID's security mode
		var sucurIndex;
		if(securMode[index]==0) //None
			sucurIndex="Disable";
		else if(securMode[index]==1) //WEP
		{
			switch(wepCfg.infoAuthMode[index]){
			case "0":
				sucurIndex="OPEN";
				break;
			case "1":
				sucurIndex="SHARED";
				break;
			case "2":
				sucurIndex="WEPAUTO";
				break;
			}
		
		}
		else if(securMode[index]==2) //WPA-P
		{
			switch(wpaCfg.infoWPAMode[index]){
			case "0":
				sucurIndex="WPAPSKWPA2PSK";
				break;
			case "1":
				sucurIndex="WPA2PSK";
				break;
			case "2":
				sucurIndex="WPAPSK";
				break;
			}
		}
		else if(securMode[index]==3) //WPA-E
		{
			switch(wpaCfg.infoWPAMode[index]){
			case "0":
				sucurIndex="WPA1WPA2";
				break;
			case "1":
				sucurIndex="WPA2";
				break;
			case "2":
				sucurIndex="WPA";
				break;
			}
		}
		$('#security_mode1').val(sucurIndex);
		securityMode1();
		
		
	}
	
	function securityMode()
	{
		var security_mode = $('#security_mode').val();
		$('#wep').hide();
		$('#security_shared_mode').hide();
		$('#wpa').hide();
		$('#wpa_algorithms').hide();
		$('#wpa_passphrase').hide();
		$('#wpa_key_renewal_interval').hide();
		$('#8021x_wep').hide();
		$('#radius_server').hide();
		$('#wpa_preAuthentication').hide();
		$('#wpa_PMK_Cache_Period').hide();
		set_checked(0, get_by_name("cipher"));
		
		if (security_mode == "OPEN" || security_mode == "SHARED" ||security_mode == "WEPAUTO")
			$('#wep').show();
		else if(security_mode == "WPAPSK" || security_mode == "WPA2PSK" || security_mode == "WPAPSKWPA2PSK") 
		{
			$('#wpa').show();
			$('#wpa_algorithms').show();
			$('#AES').hide();
			$('#TKIPAES').hide();
			$('#TKIP').show();
			set_checked(0, get_by_name("cipher"));
			
			//if(security_mode == "WPAPSK" && document.security_form.cipher[2].checked)
				//document.security_form.cipher[2].checked = false;
			
			if(security_mode == "WPA2PSK" || security_mode == "WPAPSKWPA2PSK") {
				if (security_mode == "WPAPSKWPA2PSK") {
					set_checked(2, get_by_name("cipher"));
					$('#TKIP').hide();
					$('#AES').hide();
					$('#TKIPAES').show();
				}
				else {
					set_checked(1, get_by_name("cipher"));
					$('#TKIP').hide();
					$('#AES').show();
					$('#TKIPAES').show();
				}
			}
			$('#wpa_passphrase').show();
			$('#wpa_key_renewal_interval').show();
		}
		else if (security_mode == "WPA" || security_mode == "WPA2" || security_mode == "WPA1WPA2") //wpa enterprise
		{
			$('#wpa').show();
			$('#wpa_algorithms').show();
			$('#wpa_key_renewal_interval').show();
			$('#radius_server').show();
			$('#AES').hide();
			$('#TKIPAES').hide();
			$('#TKIP').show();
			
			//if(security_mode == "WPA" && document.security_form.cipher[2].checked)
				//document.security_form.cipher[2].checked = false;
			
			if(security_mode == "WPA2"){
				set_checked(1, get_by_name("cipher"));
				$('#TKIP').hide();
				$('#AES').show();
				$('#TKIPAES').show();
				$('#wpa_preAuthentication').show();
				$('#wpa_PMK_Cache_Period').show();
				
			}
			if(security_mode == "WPA1WPA2"){
				set_checked(2, get_by_name("cipher"));
				$('#TKIP').hide();
				$('#AES').hide();
				$('#TKIPAES').show();
			}			
		}
		else if (security_mode == "IEEE8021X") // 802.1X-WEP
		{
			$('#8021x_wep').show();
			$('#radius_server').show();
		}
		
		var PhyMode = '8';
		if( PhyMode >= 5 && (security_mode == "WPA" || security_mode == "WPAPSK" || security_mode == "WPAPSKWPA2PSK" || security_mode == "WPA1WPA2"
			|| (security_mode == "WPA2" && get_radio_value(get_by_name("cipher")) == 2) || (security_mode == "WPA2PSK" && get_radio_value(get_by_name("cipher")) == 2))) 
			$('#msgbox').show();
		else 
			$('#msgbox').hide();
			
		isWDS_CipherType();
		
	}
	function securityMode1()
	{
		var security_mode = $('#security_mode1').val();
		$('#wep1').hide();
		$('#security_shared_mode1').hide();
		$('#wpa1').hide();
		$('#wpa_algorithms1').hide();
		$('#wpa_passphrase1').hide();
		$('#wpa_key_renewal_interval1').hide();
		$('#8021x_wep1').hide();
		$('#radius_server1').hide();
		$('#wpa_preAuthentication1').hide();
		$('#wpa_PMK_Cache_Period1').hide();
		set_checked(0, get_by_name("cipher1"));
		
		if (security_mode == "OPEN" || security_mode == "SHARED" ||security_mode == "WEPAUTO")
			$('#wep1').show();
		else if(security_mode == "WPAPSK" || security_mode == "WPA2PSK" || security_mode == "WPAPSKWPA2PSK") 
		{
			$('#wpa1').show();
			$('#wpa_algorithms1').show();
			$('#AES1').hide();
			$('#TKIPAES1').hide();
			$('#TKIP1').show();
			set_checked(0, get_by_name("cipher1"));
			
			//if(security_mode == "WPAPSK" && document.security_form.cipher[2].checked)
				//document.security_form.cipher[2].checked = false;
			
			if(security_mode == "WPA2PSK" || security_mode == "WPAPSKWPA2PSK") {
				if (security_mode == "WPAPSKWPA2PSK") {
					set_checked(2, get_by_name("cipher1"));
					$('#TKIP1').hide();
					$('#AES1').hide();
					$('#TKIPAES1').show();
				}
				else {
					set_checked(1, get_by_name("cipher1"));
					$('#TKIP1').hide();
					$('#AES1').show();
					$('#TKIPAES1').show();
				}
			}
			$('#wpa_passphrase1').show();
			$('#wpa_key_renewal_interval1').show();
		}
		else if (security_mode == "WPA" || security_mode == "WPA2" || security_mode == "WPA1WPA2") //wpa enterprise
		{
			$('#wpa1').show();
			$('#wpa_algorithms1').show();
			$('#wpa_key_renewal_interval1').show();
			$('#radius_server1').show();
			$('#AES1').hide();
			$('#TKIPAES1').hide();
			$('#TKIP1').show();
			
			//if(security_mode == "WPA" && document.security_form.cipher[2].checked)
				//document.security_form.cipher[2].checked = false;
			
			if(security_mode == "WPA2"){
				set_checked(1, get_by_name("cipher1"));
				$('#TKIP1').hide();
				$('#AES1').show();
				$('#TKIPAES1').show();
				$('#wpa_preAuthentication1').show();
				$('#wpa_PMK_Cache_Period1').show();
				
			}
			if(security_mode == "WPA1WPA2"){
				set_checked(2, get_by_name("cipher1"));
				$('#TKIP1').hide();
				$('#AES1').hide();
				$('#TKIPAES1').show();
			}			
		}
		else if (security_mode == "IEEE8021X") // 802.1X-WEP
		{
			$('#8021x_wep1').show();
			$('#radius_server1').show();
		}
		
		var PhyMode = '8';
		if( PhyMode >= 5 && (security_mode == "WPA" || security_mode == "WPAPSK" || security_mode == "WPAPSKWPA2PSK" || security_mode == "WPA1WPA2"
			|| (security_mode == "WPA2" && get_radio_value(get_by_name("cipher1")) == 2) || (security_mode == "WPA2PSK" && get_radio_value(get_by_name("cipher1")) == 2))) 
			$('#msgbox1').show();
		else 
			$('#msgbox1').hide();
			
		isWDS_CipherType1();
		
	}
	
	function checkData()
	{
		var dot11_mode_value = standard[0];
		var wpa_cipher = $('input[name=cipher]:checked').val();
		var securitymode = $('#security_mode').val();
		var checkValue, flag=0;
		if (securitymode == "OPEN" || securitymode == "SHARED" ||securitymode == "WEPAUTO")//WEP_MODE
		{
			if(!check_Wep(securitymode) )
				return false;
			/* 
			** Date:	2012-02-04
			** Author:	Moa Chung
			** Reason:	add foolproof from old model(WEP)
			**/
			if (dot11_mode_value == 2){
				alert(get_words('MSG044'));
				return false;
			}
			if (wpsCfg.enable[0] == 1)
			{
				if (confirm(get_words("msg_wps_sec_01")) == false)
					return false;
				else
					is_wps = 0;
			}
		}
		else if (securitymode == "WPAPSK" || securitymode == "WPA2PSK" || securitymode == "WPAPSKWPA2PSK")//WPA_P
		{
			var keyvalue = $('#passphrase').val();
			if (keyvalue.length == 0){
				alert(get_words('_wifiser_mode37'));
				return false;
			}
			/* 
			** Date:	2012-02-04
			** Author:	Moa Chung
			** Reason:	add foolproof from old model(PSK)
			**/
			if ((dot11_mode_value == 2) && (wpa_cipher == 0)){
					alert(get_words('MSG045'));
					return false;
			}
			if (wpsCfg.enable[0] == 1)
			{
				if (wpa_cipher == 0 && securitymode == "WPAPSK")
					if (confirm(get_words("msg_wps_sec_02")) == false)
						return false;
					else
						is_wps = 0;
				else if (securitymode == "WPAPSK")
					if (confirm(get_words("msg_wps_sec_04")) == false)
						return false;
					else
						is_wps = 0;
			}
			for (loopCount=0; loopCount<64; loopCount++)
			{
				checkValue = keyvalue.substr(loopCount, 1);
				if (!(parseInt(checkValue, 16) >= 0) && !(parseInt(checkValue, 16) <= 15)) {
					flag = 1;
					loopCount = 65;
				}
			}
			
			if (keyvalue.length == 64) //Hex
			{
				if (flag != 0){
					alert(get_words('_wifiser_mode36'));
					return false;
				}
			}
			else	//ASCII
			{
				if (keyvalue.length < 8){
					alert(get_words('_wifiser_mode35'));
					return false;
				}
				
				if (keyvalue.length >= 64){
					alert(get_words('_wifiser_mode34'));
					return false;
				}
			}
			if(check_wpa() == false)
				return false;	
		}
		else if (securitymode == "WPA" || securitymode == "WPA1WPA2") //WPA or WPA1WP2 mixed mode
		{
			if(check_wpa() == false)
				return false;
			if(chk_radius() == false)
				return false;
			/* 
			** Date:	2012-02-04
			** Author:	Moa Chung
			** Reason:	add foolproof from old model(EAP)
			**/
			if ((dot11_mode_value == 2) && (wpa_cipher == 0)){
				alert(get_words('MSG045'));
				return false;
			}
			if (wpsCfg.enable[0] == 1)
			{
				if (confirm(get_words("msg_wps_sec_05")) == false)
					return false;
				else
					is_wps = 0;
			}
		}
		else if (securitymode == "WPA2") //WPA2
		{
			if(check_wpa() == false)
				return false;
			if($('#PMKCachePeriod').val() == "")
			{
				alert(get_words('_wifiser_mode33'));
				return false;
			}
			/* 
			** Date:	2012-02-04
			** Author:	Moa Chung
			** Reason:	add foolproof from old model(EAP)
			**/
			if ((dot11_mode_value == 2) && (wpa_cipher == 0)){
				alert(get_words('MSG045'));
				return false;
			}
			if (wpsCfg.enable[0] == 1)
			{
				if (confirm(get_words("msg_wps_sec_05")) == false)
					return false;
				else
					is_wps = 0;
			}
			if(chk_radius() == false)
				return false;
		}
		
		return true;	
	}
	function checkData1()
	{
		var dot11_mode_value = standard5g[4];
		var wpa_cipher = $('input[name=cipher1]:checked').val();
		var securitymode = $('#security_mode1').val();
		var checkValue, flag=0;
		if (securitymode == "OPEN" || securitymode == "SHARED" ||securitymode == "WEPAUTO")//WEP_MODE
		{
			if(!check_Wep1(securitymode) )
				return false;
			/* 
			** Date:	2012-02-04
			** Author:	Moa Chung
			** Reason:	add foolproof from old model(WEP)
			**/
			if (dot11_mode_value == 2){
				alert(get_words('MSG044'));
				return false;
			}
			if (wpsCfg.enable[4] == 1)
			{
				if (confirm(get_words("msg_wps_sec_01")) == false)
					return false;
				else
					is_wps_5 = 0;
			}
		}
		else if (securitymode == "WPAPSK" || securitymode == "WPA2PSK" || securitymode == "WPAPSKWPA2PSK")//WPA_P
		{
			var keyvalue = $('#passphrase1').val();
			if (keyvalue.length == 0){
				alert(get_words('_wifiser_mode37'));
				return false;
			}
			/* 
			** Date:	2012-02-04
			** Author:	Moa Chung
			** Reason:	add foolproof from old model(PSK)
			**/
			if ((dot11_mode_value == 2) && (wpa_cipher == 0)){
					alert(get_words('MSG045'));
					return false;
			}
			if (wpsCfg.enable[4] == 1)
			{
				if (wpa_cipher == 0 && securitymode == "WPAPSK")
					if (confirm(get_words("msg_wps_sec_02")) == false)
						return false;
					else
						is_wps_5 = 0;
				else if (securitymode == "WPAPSK")
					if (confirm(get_words("msg_wps_sec_04")) == false)
						return false;
					else
						is_wps_5 = 0;
			}
			for (loopCount=0; loopCount<64; loopCount++)
			{
				checkValue = keyvalue.substr(loopCount, 1);
				if (!(parseInt(checkValue, 16) >= 0) && !(parseInt(checkValue, 16) <= 15)) {
					flag = 1;
					loopCount = 65;
				}
			}
			
			if (keyvalue.length == 64) //Hex
			{
				if (flag != 0){
					alert(get_words('_wifiser_mode36'));
					return false;
				}
			}
			else	//ASCII
			{
				if (keyvalue.length < 8){
					alert(get_words('_wifiser_mode35'));
					return false;
				}
				
				if (keyvalue.length >= 64){
					alert(get_words('_wifiser_mode34'));
					return false;
				}
			}
			if(check_wpa1() == false)
				return false;	
		}
		else if (securitymode == "WPA" || securitymode == "WPA1WPA2") //WPA or WPA1WP2 mixed mode
		{
			if(check_wpa1() == false)
				return false;
			if(chk_radius1() == false)
				return false;
			/* 
			** Date:	2012-02-04
			** Author:	Moa Chung
			** Reason:	add foolproof from old model(EAP)
			**/
			if ((dot11_mode_value == 2) && (wpa_cipher == 0)){
				alert(get_words('MSG045'));
				return false;
			}
			if (wpsCfg.enable[4] == 1)
			{
				if (confirm(get_words("msg_wps_sec_05")) == false)
					return false;
				else
					is_wps_5 = 0;
			}
		}
		else if (securitymode == "WPA2") //WPA2
		{
			if(check_wpa1() == false)
				return false;
			if($('#PMKCachePeriod').val() == "")
			{
				alert(get_words('_wifiser_mode33'));
				return false;
			}
			/* 
			** Date:	2012-02-04
			** Author:	Moa Chung
			** Reason:	add foolproof from old model(EAP)
			**/
			if ((dot11_mode_value == 2) && (wpa_cipher == 0)){
				alert(get_words('MSG045'));
				return false;
			}
			if (wpsCfg.enable[0] == 1)
			{
				if (confirm(get_words("msg_wps_sec_05")) == false)
					return false;
				else
					is_wps_5 = 0;
			}
			if(chk_radius1() == false)
				return false;
		}
		
		return true;
	}
	
	function check_Wep(securitymode)
	{
		var defaultid = $('#wep_default_key').val();
	
		if (defaultid == 1 )
			var keyvalue = $('#WEP1').val();
		else if (defaultid == 2)
			var keyvalue = $('#WEP2').val();
		else if (defaultid == 3)
			var keyvalue = $('#WEP3').val();
		else if (defaultid == 4)
			var keyvalue = $('#WEP4').val();

		if (keyvalue.length == 0 &&  (securitymode == "SHARED" || securitymode == "OPEN" || securitymode == "WEPAUTO")){ // shared wep  || md5
			alert(get_words('_wifiser_mode32')+" "+defaultid+' !');
			return false;
		}
		for(var i=1; i<=4; i++)
		{
			var keylength = $('#WEP'+i).val().length;
			var key = $('#WEP'+i).val();
			if(keylength != 0)
			{
				//ASCII
				if($('#WEP'+i+'Select').val() == 1)
				{
					if(keylength != 5 && keylength != 13) {
						alert(get_words('_wifiser_mode31')+" "+ i);
						return false;
					}
					for (var j = 0; j < keylength; j++){
						if (!is_ascii(key.substring(j, j+1))){
							alert(get_words('TEXT042_1')+" " + i + " "+get_words('TEXT041_2') + " " + get_words('_ascii'));
							return false;
						}
					}
				}
				
				//HEX
				if($('#WEP'+i+'Select').val() == 0)
				{
					if(keylength != 10 && keylength != 26) {
						alert(get_words('_wifiser_mode30')+" "+ i);
						return false;
					}
					for (var j = 0; j < keylength; j++){
						if (!check_hex(key.substring(j, j+1))){
							alert(get_words('TEXT042_1')+" " + i + " "+get_words('TEXT042_2'));	
							return false;
						}
					}
				}
			}
		}
		return true;
	}
	function check_Wep1(securitymode)
	{
		var defaultid = $('#wep_default_key1').val();
	
		if (defaultid == 1 )
			var keyvalue = $('#WEP11').val();
		else if (defaultid == 2)
			var keyvalue = $('#WEP21').val();
		else if (defaultid == 3)
			var keyvalue = $('#WEP31').val();
		else if (defaultid == 4)
			var keyvalue = $('#WEP41').val();

		if (keyvalue.length == 0 &&  (securitymode == "SHARED" || securitymode == "OPEN" || securitymode == "WEPAUTO")){ // shared wep  || md5
			alert(get_words('_wifiser_mode32')+" "+defaultid+' !');
			return false;
		}
		for(var i=1; i<=4; i++)
		{
			var keylength = $('#WEP'+i+'1').val().length;
			var key = $('#WEP'+i+'1').val();
			if(keylength != 0)
			{
				//ASCII
				if($('#WEP'+i+'Select1').val() == 1)
				{
					if(keylength != 5 && keylength != 13) {
						alert(get_words('_wifiser_mode31')+" "+ i);
						return false;
					}
					for (var j = 0; j < keylength; j++){
						if (!is_ascii(key.substring(j, j+1))){
							alert(get_words('TEXT042_1')+" " + i + " "+get_words('TEXT041_2') + " " + get_words('_ascii'));
							return false;
						}
					}
				}
				
				//HEX
				if($('#WEP'+i+'Select1').val() == 0)
				{
					if(keylength != 10 && keylength != 26) {
						alert(get_words('_wifiser_mode30')+" "+ i);
						return false;
					}
					for (var j = 0; j < keylength; j++){
						if (!check_hex(key.substring(j, j+1))){
							alert(get_words('TEXT042_1')+" " + i + " "+get_words('TEXT042_2'));	
							return false;
						}
					}
				}
			}
		}
		return true;
	}
	
	function check_wpa()
	{
		var keyvalue = $('#keyRenewalInterval').val();
		var PMKvalue = $('#PMKCachePeriod').val();
		if(isNaN(keyvalue))
		{
			alert(get_words('_wifiser_mode27'));
			return false;
		}
		if(keyvalue < 60)
		{
			alert(get_words('_wifiser_mode28'));
			return false;
		}
		if(isNaN(PMKvalue))
		{
			alert(get_words('_wifiser_mode29'));
			return false;
		}
		return true;
	}
	function check_wpa1()
	{
		var keyvalue = $('#keyRenewalInterval1').val();
		var PMKvalue = $('#PMKCachePeriod1').val();
		if(isNaN(keyvalue))
		{
			alert(get_words('_wifiser_mode27'));
			return false;
		}
		if(keyvalue < 60)
		{
			alert(get_words('_wifiser_mode28'));
			return false;
		}
		if(isNaN(PMKvalue))
		{
			alert(get_words('_wifiser_mode29'));
			return false;
		}
		return true;
	}
	
	function chk_radius()
	{
		
		var ip1 = $('#RadiusServerIP').val();		
		var radius1_msg = replace_msg(all_ip_addr_msg,get_words('bws_RIPA'));		
		var temp_ip1 = new addr_obj(ip1.split("."), radius1_msg, false, false);	
		var temp_radius1 = new raidus_obj(temp_ip1, $('#RadiusServerPort').val(), $('#RadiusServerSecret').val());

		if (!check_radius(temp_radius1)){
			return false;
		}
		
		/*
		if($('#RadiusServerIP').val()==""){
			alert('Please input the radius server IP address.');
			return false;		
		}
		if($('#RadiusServerPort').val()==""){
			alert('Please input the radius server port number.');
			return false;		
		}
		if($('#RadiusServerSecret').val()==""){
			alert('Please input the radius server shared secret.');
			return false;		
		}

		if(checkIpAddr(document.security_form.RadiusServerIP) == false){
			alert('Please input a valid radius server ip address.');
			return false;		
		}
		if( (checkRange(document.security_form.RadiusServerPort.value, 1, 65535)==false) ||
			(checkAllNum(document.security_form.RadiusServerPort.value)==false)){
			alert('Please input a valid radius server port number.');
			return false;		
		}
		if(checkInjection(document.security_form.RadiusServerSecret.value)==false){
			alert('The shared secret contains invalid characters.');
			return false;		
		}

		if(document.security_form.RadiusServerSessionTimeout.value.length){
			if(checkAllNum(document.security_form.RadiusServerSessionTimeout.value)==false){
				alert('Please input a valid session timeout number or u may left it empty.');
				return false;	
			}	
		}*/

		return true;
	}
	function chk_radius1()
	{
		
		var ip1 = $('#RadiusServerIP1').val();		
		var radius1_msg = replace_msg(all_ip_addr_msg,get_words('bws_RIPA'));		
		var temp_ip1 = new addr_obj(ip1.split("."), radius1_msg, false, false);	
		var temp_radius1 = new raidus_obj(temp_ip1, $('#RadiusServerPort1').val(), $('#RadiusServerSecret1').val());

		if (!check_radius(temp_radius1)){
			return false;
		}
		
		/*
		if($('#RadiusServerIP1').val()==""){
			alert('Please input the radius server IP address.');
			return false;		
		}
		if($('#RadiusServerPort1').val()==""){
			alert('Please input the radius server port number.');
			return false;		
		}
		if($('#RadiusServerSecret1').val()==""){
			alert('Please input the radius server shared secret.');
			return false;		
		}

		if(checkIpAddr(document.security_form.RadiusServerIP) == false){
			alert('Please input a valid radius server ip address.');
			return false;		
		}
		if( (checkRange(document.security_form.RadiusServerPort.value, 1, 65535)==false) ||
			(checkAllNum(document.security_form.RadiusServerPort.value)==false)){
			alert('Please input a valid radius server port number.');
			return false;		
		}
		if(checkInjection(document.security_form.RadiusServerSecret.value)==false){
			alert('The shared secret contains invalid characters.');
			return false;		
		}

		if(document.security_form.RadiusServerSessionTimeout.value.length){
			if(checkAllNum(document.security_form.RadiusServerSessionTimeout.value)==false){
				alert('Please input a valid session timeout number or u may left it empty.');
				return false;	
			}	
		}*/

		return true;
	}
	
//security end

function setValueRadioOnSchedule()
{
	var val_sche = lanCfg.schedule[0];
	$('#ssid_schedule').val(val_sche);
}
function setValueRadioOnSchedule1()
{
	var val_sche = lanCfg.schedule[4];
	$('#ssid_schedule1').val(val_sche);
}

$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(model);
	if(lanCfg.enable[0]==1)
		$('#radioOnField').show();
	else
		$('#radioOffField').show();
	//Wireless Network
	setValueRadioOnSchedule();
	setValueWMode();
	setValueWirelessName();
	setValueBoardcastSSID();
	setValueFrequency();
	setEventWMode();
	setEventFrequency();
	
	//HT Physical Mode
	setValueOperatingMode();
	setValueChannelBandWidth();
	setEventChannelBandWidth();
	
	//Other
//	$('#div_11n_plugfest').show();//close
//	setValueFortyIntolerant();//close
//	setValueWiFiOptimum();//close
	setValueHTTxStream();
	setValueHTRxStream();
	
	//Security
	setValueSecurModeList();
	MBSSIDChange(0);
	
	
	if(lanCfg.enable[4]==1)
		$('#radioOnField1').show();
	else
		$('#radioOffField1').show();
	//Wireless Network
	setValueRadioOnSchedule1();
	setValueWMode1();
	setValueWirelessName1();
	setValueBoardcastSSID1();
	setValueFrequency1();
	setEventWMode1();
	setEventFrequency1();
	setValueDFSChannelList();
	
	//HT Physical Mode
	setValueOperatingMode1();
	setValueChannelBandWidth1();
	setEventChannelBandWidth1();
	
	//Other
//	$('#div_11n_plugfest1').show();//close
//	setValueFortyIntolerant1();//close
//	setValueWiFiOptimum1();//close
	setValueHTTxStream1();
	setValueHTRxStream1();
	
	//Security
	setValueSecurModeList1();
	MBSSIDChange1(4);
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
				<script>document.write(menu.build_structure(0,1,-1))</script>
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
									<div class="headerbg" id="basicTitle">
									<script>show_words('_basic_wireless_settings')</script>
									</div>
									<div class="hr"></div>
									<div class="section_content_border">
									<div class="header_desc" id="basicIntroduction">
										<script>show_words('_desc_basic')</script>
										<p></p>
									</div>

<div id="radioOnField" style="display:none;">
<form method="post" name="wireless_basic" action="/goform/wirelessBasic" onsubmit="return CheckValue();">
<div class="box_tn">
	<div class="CT"><script>show_words('_wireless_2')</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL"><script>show_words('_lb_radio_onoff')</script></td>
		<td class="CR">
			<input type="button" id="radioButtonOff" name="radioButtonOff" class="button_inbox" value="" onclick="switchRadio(false)" /> &nbsp; 
			<script>$('#radioButtonOff').val(get_words('_btn_radio_off'));</script>
			<span id="scheduleField">
				<select id="ssid_schedule" name="ssid_schedule">
					<option value="255"><script>show_words('_always');</script></option>
					<option value="254"><script>show_words('_never');</script></option>
					<script>add_option('Schedule');</script>
				</select>
			</span>
			<input type="button" id="sche_btn" onclick="location.assign('/adv_schedule.asp');" />
			<script>$('#sche_btn').val(get_words('tc_new_sch'));</script>
		</td>
	</tr>
	<tr> 
		<td class="CL"><script>show_words('WLANMODE')</script></td>
		<td class="CR">
			<select name="dot11_mode" id="dot11_mode" size="1">
	<!--			<option value="b"><script>show_words('m_bwl_Mode_1')</script></option>
				<option value="g"><script>show_words('m_bwl_Mode_2')</script></option> -->
				<option value="bg"><script>show_words('m_bwl_Mode_3')</script></option>
				<option value="n"><script>show_words('m_bwl_Mode_8')</script></option>
	<!--			<option value="ng"><script>show_words('m_bwl_Mode_10')</script></option> -->
				<option value="bgn"><script>show_words('m_bwl_Mode_11')</script></option>
			</select>
			<input type="hidden" id="wlan0_dot11_mode" name="wlan0_dot11_mode" value='' />
		</td>
	</tr>
	<!-- 11b txrate -->
	<tr id="show_11b_txrate" style="display:none">
		<td class="CL"><script>show_words('bwl_TxR')</script></td>
		<td class="CR">
			<select id="wlan0_11b_txrate" name="wlan0_11b_txrate">
				<option value="0" selected><script>show_words('KR50')</script></option>
				<script>set_11b_txrate(get_by_id("wlan0_11b_txrate"));</script>
			</select>
		</td>
	</tr>
	<!-- 11g txrate -->
	<tr id="show_11g_txrate" style="display:none">
		<td class="CL"><script>show_words('bwl_TxR')</script></td>
		<td class="CR">
			<select id="wlan0_11g_txrate" name="wlan0_11g_txrate">
				<option value="0" selected><script>show_words('KR50')</script></option>
				<script>set_11g_txrate(get_by_id("wlan0_11g_txrate"));</script>
			</select>
		</td>
	</tr>
	<!-- 11b/g txrate -->
	<tr id="show_11bg_txrate" style="display:none">
		<td class="CL"><script>show_words('bwl_TxR')</script></td>
		<td class="CR">
			<select id="wlan0_11bg_txrate" name="wlan0_11bg_txrate">
				<option value="0" selected><script>show_words('KR50')</script></option>
				<script>set_11bg_txrate(get_by_id("wlan0_11bg_txrate"));</script>
			</select>
		</td>
	</tr>
	<tr> 
		<td class="CL"><script>show_words('_wmode_ssid')</script></td>
		<td class="CR">
			<input name="submit_SSID0" type="text" id="show_ssid_0" size="32" maxlength="32" value="" />
		</td>
	</tr>
	
	<tr> 
		<td class="CL"><script>show_words('_lb_broadcast_ssid')</script></td>
		<td class="CR">
			<input type="radio" id="wlan0_ssid_broadcast" name="wlan0_ssid_broadcast" value="1" /><script>show_words('_enable');</script>&nbsp;
			<input type="radio" id="wlan0_ssid_broadcast" name="wlan0_ssid_broadcast" value="0" /><script>show_words('_disable');</script>
		</td>
	</tr>
	
	<tr style="display:none"> 
		<td class="CL"><script>show_words('_bssid');</script></td>
		<td class="CR">&nbsp;&nbsp;<script>document.write(lanCfg.bssid[0])</script></td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_help_txt136')</script></td>
		<td class="CR">
			<select id="sel_wlan0_channel" name="sel_wlan0_channel">
			</select>
			<input type="hidden" id="wlan0_channel" name="wlan0_channel" value='' />
		</td>
	</tr>
	
	<tr id="opmode" style="display:none">
		<td class="CL"><script>show_words('_help_txt146');</script></td>
		<td class="CR">
			<input type="radio" name="n_opmode" value="0" /><script>show_words('_sel_mixed');</script>&nbsp;
			<input type="radio" name="n_opmode" value="1" /><script>show_words('_sel_greenfield');</script>
		</td>
	</tr>
	<tr id="bandwidth">
		<td class="CL"><script>show_words('_help_txt151')</script></td>
		<td class="CR" id="n_bandwidth">
			<input type="radio" name="n_bandwidth" value="0" /><script>show_words('bwl_ht20')</script>&nbsp;
			<input type="radio" name="n_bandwidth" value="1" /><script>show_words('bwl_ht2040')</script>
		</td>
	</tr>
	<tr style="display:none;">
		<td class="CL"><script>show_words('_lb_exten_channel');</script></td>
		<td class="CR">
			<select id="n_extcha" name="n_extcha" size="1" disabled="">
				<option value="0" selected=""><script>show_words('_sel_autoselect');</script></option>
			</select>
		</td>
	</tr>
	</table>
</div>

	<div class="box_tn">
		<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="formarea">
		<tr><td colspan="2" class="CT"><script>show_words('ES_security')</script></td></tr>
		<tr style="display:">
			<td class="CL"><script>show_words('bws_SM')</script></td>
			<td class="CR">
				<span id="SecurMode_list"></span>
			</td>
		</tr>
		<tr style="display:none" id="security_shared_mode">
			<td class="CL"><script>show_words('_wifiser_mode8')</script></td>
			<td class="CR">
				<select name="shared_mode" id="shared_mode" size="1" onChange="securityMode(1)">
					<option value=WEP><script>show_words('LS321')</script></option>
					<option value=None><script>show_words('_none')</script></option>
				</select>
			</td>
		</tr>
		</table>
	</div>

	<div class="box_tn" id="msgbox" style="display:none">
		<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="formarea">
			<tr>
				<td colspan="2" class="CELL">
					<font color="white" id="NmodeMsg" name="NmodeMsg"><script>show_words('_wifiser_mode9')</script>
					</font>
				</td>
			</tr>
		</table>
	</div>

	<!-- WEP -->
	<div id="wep" class="box_tn" style="display:none;">
		<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="formarea">
		<tr><td colspan="3" class="CT"><script>show_words('_WEP')</script></td></tr>
		<tr>
			<td class="CL"><script>show_words('_wifiser_mode10')</script></td>
			<td class="CR" colspan="2">
				<select name="wep_default_key" id="wep_default_key" size="1">
					<option value="1"><script>show_words('_wifiser_mode11')</script> 1</option>
					<option value="2"><script>show_words('_wifiser_mode11')</script> 2</option>
					<option value="3"><script>show_words('_wifiser_mode11')</script> 3</option>
					<option value="4"><script>show_words('_wifiser_mode11')</script> 4</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('LW22')</script> 1 :</td>
			<td class="CELL"><input name="wep_key_1" id="WEP1" maxlength="26" value="" /></td>
			<td class="CELL">
				<select id="WEP1Select" name="WEP1Select"> 
					<option value="1"><script>show_words('help423')</script></option>
					<option value="0"><script>show_words('_wifiser_mode12')</script></option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('LW22')</script> 2 :</td>
			<td class="CELL"><input name="wep_key_2" id="WEP2" maxlength="26" value="" /></td>
			<td class="CELL">
				<select id="WEP2Select" name="WEP2Select">
					<option value="1"><script>show_words('help423')</script></option>
					<option value="0"><script>show_words('_wifiser_mode12')</script></option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('LW22')</script> 3 :</td>
			<td class="CELL"><input name="wep_key_3" id="WEP3" maxlength="26" value="" /></td>
			<td class="CELL">
				<select id="WEP3Select" name="WEP3Select">
					<option value="1"><script>show_words('help423')</script></option>
					<option value="0"><script>show_words('_wifiser_mode12')</script></option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('LW22')</script> 4 :</td>
			<td class="CELL"><input name="wep_key_4" id="WEP4" maxlength="26" value="" /></td>
			<td class="CELL">
				<select id="WEP4Select" name="WEP4Select">
					<option value="1"><script>show_words('help423')</script></option>
					<option value="0"><script>show_words('_wifiser_mode12')</script></option>
				</select>
			</td>
		</tr>
		</table>
	</div>

	<!-- WPA -->
	<div id="wpa" class="box_tn" style="display:none;">
		<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="formarea">
		<tr><td colspan="4" class="CT"><script>show_words('help750')</script></td></tr>
		<tr id="wpa_algorithms" name="wpa_algorithms" style="display:none"> 
			<td class="CL"><script>show_words('_wifiser_mode13')</script></td>
			<td id="TKIP" class="CELL">
				<input name="cipher" id="cipher" value="0" type="radio" onClick="onWPAAlgorithmsClick(0)" />
				<script>show_words('bws_CT_1')</script> &nbsp;
			</td>
			<td id="AES" class="CELL">
				<input name="cipher" id="cipher" value="1" type="radio" onClick="onWPAAlgorithmsClick(1)" />
				<script>show_words('bws_CT_2')</script>  &nbsp;
			</td>
			<td id="TKIPAES" class="CELL">
				<input name="cipher" id="cipher" value="2" type="radio" onClick="onWPAAlgorithmsClick(2)" />
				<script>show_words('bws_CT_1')</script>/<script>show_words('bws_CT_2')</script> &nbsp;
			</td>
		</tr>
		<tr id="wpa_passphrase" name="wpa_passphrase" style="display:none">
			<td class="CL"><script>show_words('help381')</script></td>
			<td colspan="3" class="CELL">
				<input name="passphrase" id="passphrase" size="28" maxlength="64" value="" />
			</td>
		</tr>
		<tr id="wpa_key_renewal_interval" name="wpa_key_renewal_interval" style="display:none">
			<td class="CL"><script>show_words('_wifiser_mode14')</script></td>
			<td colspan="3" class="CELL">
				<input name="keyRenewalInterval" id="keyRenewalInterval" size="4" maxlength="4" value="3600" />
				<script>show_words('_seconds')</script>
			</td>
		</tr>
		<tr id="wpa_PMK_Cache_Period" name="wpa_PMK_Cache_Period" style="display:none">
			<td class="CL"><script>show_words('_wifiser_mode15')</script></td>
			<td colspan="3" class="CELL">
				<input name="PMKCachePeriod" id="PMKCachePeriod" size="4" maxlength="4" value="" /> 
				<script>show_words('tt_Minute')</script>
			</td>
		</tr>
		<tr id="wpa_preAuthentication" name="wpa_preAuthentication" style="display:none">
			<td class="CL"><script>show_words('_wifiser_mode16')</script></td>
			<td colspan="3" class="CELL">
				<input name="PreAuthentication" id="PreAuthentication" value="0" type="radio" />
				<script>show_words('_disable')</script> &nbsp;
				<input name="PreAuthentication" id="PreAuthentication" value="1" type="radio" />
				<script>show_words('_enable')</script> &nbsp;
			</td>
		</tr>
		</table>
	</div>

	<!-- 802.1x -->
	<!-- WEP  -->
	<div id="8021x_wep" class="box_tn" style="display:none;">
		<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="formarea">
		<tr><td colspan="3" class="CT"><script>show_words('_wifiser_mode17')</script></td></tr>
		<tr id="wpa_algorithms" name="wpa_algorithms" style="display:none"> 
			<td class="CL"><script>show_words('_WEP')</script></td>
			<td colspan="2" class="CELL">
				<input name="ieee8021x_wep" id="ieee8021x_wep" value="0" type="radio" />
				<script>show_words('_disable')</script> &nbsp;
				<input name="ieee8021x_wep" id="ieee8021x_wep" value="1" type="radio" />
				<script>show_words('_enable')</script> &nbsp;
			</td>
		</tr>
		</table>
	</div>

	<div id="radius_server" class="box_tn" style="display:none;">
		<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="formarea">
		<tr><td colspan="3" class="CT"><script>show_words('_wifiser_mode18')</script></td></tr>
		<tr> 
			<td class="CL"><script>show_words('ES_IP_ADDR')</script></td>
			<td colspan="2" class="CELL">
				<input name="RadiusServerIP" id="RadiusServerIP" size="16" maxlength="32" value="" />
			</td>
		</tr>
		<tr> 
			<td class="CL"><script>show_words('sps_port')</script></td>
			<td colspan="2" class="CELL">
				<input name="RadiusServerPort" id="RadiusServerPort" size="5" maxlength="5" value="" />
			</td>
		</tr>
		<tr> 
			<td class="CL"><script>show_words('_wifiser_mode19')</script></td>
			<td colspan="2" class="CELL">
				<input name="RadiusServerSecret" id="RadiusServerSecret" size="16" maxlength="64" value="" />
			</td>
		</tr>
		<tr style="display:none"> 
			<td class="CL"><script>show_words('_wifiser_mode20')</script></td>
			<td colspan="2" class="CELL">
				<input name="RadiusServerSessionTimeout" id="RadiusServerSessionTimeout" size="3" maxlength="4" value="0" />
			</td>
		</tr>
		<tr style="display:none"> 
			<td class="CL"><script>show_words('_wifiser_mode21')</script></td>
			<td colspan="2" class="CELL">
				<input name="RadiusServerIdleTimeout" id="RadiusServerIdleTimeout" size="3" maxlength="4" value="" readonly>
			</td>
		</tr>
		</table>
	</div>

<div id="div_11n_plugfest" class="box_tn" style="display:none">
	<div class="CT">Other</div>
	<table name="div_11n_plugfest" cellspacing="0" cellpadding="0" class="formarea">
		<!--
		<tr>
			<td class="CL"><script>show_words('_lb_forty_into');</script></td>
			<td class="CR"><font color="#003366" face=arial><b>
				<input type=radio name=f_40mhz value="0" /><script>show_words('_disable');</script>&&nbsp;
				<input type=radio name=f_40mhz value="1" /><script>show_words('_enable');</script>
			</b></font></td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('_lb_wifi_opt');</script></td>
			<td class="CR">
				<input type=radio name=wifi_opt value="0" /><script>show_words('_disable');</script>&&nbsp;
				<input type=radio name=wifi_opt value="1" /><script>show_words('_enable');</script>
			</td>
		</tr>
		-->
		<tr>
			<td class="CL"><script>show_words('_lb_ht_txstream');</script></td>
			<td class="CR">
				<select id="tx_stream" name="tx_stream" size="1">
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('_lb_ht_rxstream');</script></td>
			<td class="CR">
				<select id="rx_stream" name="rx_stream" size="1">
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
				</select>
			</td>
		</tr>
	</table>
</div>

<div id="buttonField" class="box_tn">
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr align="center">
		<td colspan="2" class="btn_field">
			<input type="button" class="button_submit" id="btn_apply" value="Apply" onclick="check_apply();" />
			<script>$('#btn_apply').val(get_words('_apply'));</script>
			<input type="reset" class="button_submit" id="btn_cancel" value="Cancel" onclick="window.location.reload()" />
			<script>$('#btn_cancel').val(get_words('_cancel'));</script>
		</td>
	</tr>
	</table>
</div>

</form>
</div>
<div id="radioOffField" style="display:none;">
	<div class="box_tn">
		<div class="CT"><script>show_words('_wireless_2')</script></div>
		<table cellspacing="0" cellpadding="0" class="formarea">
		<tr>
			<td class="CL"><script>show_words('_lb_radio_onoff')</script></td>
			<td class="CR">
				<input type="button" id="radioButtonOn" name="radioButtonOn" style="{width:120px;}" value="" onclick="switchRadio(true);" /> &nbsp; &nbsp;
				<script>$('#radioButtonOn').val(get_words('_btn_radio_on'));</script>
				<input type="hidden" name="radiohiddenButton" value="2" />
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center" class="CELL"><font color="red" id="Msg" name="Msg"><script>show_words('_MSG_woff');</script></font></td>
		</tr>
		</table>
	</div>
</div>

<div id="radioOnField1" style="display:none;">
	<form method="post" name="wireless_basic" action="/goform/wirelessBasic" onsubmit="return CheckValue();">
	<div class="box_tn">
		<div class="CT"><script>show_words('_wireless_5')</script></div>
		<table cellspacing="0 "cellpadding="0" class="formarea">
		<tr>
			<td class="CL"><script>show_words('_lb_radio_onoff')</script></td>
			<td class="CR">
				<input type="button" id="radioButtonOff1" name="radioButtonOff1" class="button_inbox" value="" onclick="switchRadio1(false)" /> &nbsp; 
				<script>$('#radioButtonOff1').val(get_words('_btn_radio_off'));</script>
				<span id="scheduleField1">
				<select id="ssid_schedule1" name="ssid_schedule1">
					<option value="255"><script>show_words('_always');</script></option>
					<option value="254"><script>show_words('_never');</script></option>
					<script>add_option('Schedule');</script>
				</select>
			</span>
			<input type="button" id="sche_btn1" onclick="location.assign('/adv_schedule.asp');" />
			<script>$('#sche_btn1').val(get_words('tc_new_sch'));</script>
			</td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('WLANMODE')</script></td>
			<td class="CR">
				<select name="dot11_mode1" id="dot11_mode1" size="1">
					<option value="a"><script>show_words('m_bwl_Mode5_1')</script></option>
					<option value="n"><script>show_words('m_bwl_Mode5_2')</script></option>
					<option value="na"><script>show_words('m_bwl_Mode5_3')</script></option>
					<option value="acna"><script>show_words('m_bwl_Mode5_4')</script></option>
				</select>
				<input type="hidden" id="wlan1_dot11_mode" name="wlan0_dot11_mode" value='' />
			</td>
		</tr>
		<!-- 11a txrate -->
		<tr id="show_11a_txrate1" style="display:none">
			<td class="CL"><script>show_words('bwl_TxR')</script></td>
			<td class="CR">
				<select id="wlan1_11a_txrate" name="wlan1_11a_txrate">
					<option value="0" selected><script>show_words('KR50')</script></option>
					<script>set_11a_txrate(get_by_id("wlan1_11a_txrate"));</script>
				</select>
			</td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('_wmode_ssid')</script></td>
			<td class="CR">
				<input name="submit_SSID01" type="text" id="show_ssid1_0" size="32" maxlength="32" value="" />
			</td>
		</tr>
		<tr> 
			<td class="CL"><script>show_words('_lb_broadcast_ssid')</script></td>
			<td class="CR">
				<input type="radio" id="wlan1_ssid_broadcast" name="wlan1_ssid_broadcast" value="1" /><script>show_words('_enable');</script>&nbsp;
				<input type="radio" id="wlan1_ssid_broadcast" name="wlan1_ssid_broadcast" value="0" /><script>show_words('_disable');</script>
			</td>
		</tr>
		<tr style="display:none"> 
			<td class="CL"><script>show_words('_bssid');</script></td>
			<td class="CR">&nbsp;&nbsp;<script>document.write(lanCfg.bssid[4])</script></td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('_help_txt136')</script></td>
			<td class="CR">
				<select id="sel_wlan1_channel" name="sel_wlan1_channel">
				</select>
				<input type="hidden" id="wlan1_channel" name="wlan1_channel" value='' />
			</td>
		</tr>
		
		<tr id="opmode1" style="display:none">
			<td class="CL"><script>show_words('_help_txt146');</script></td>
			<td class="CR">
				<input type="radio" name="n_opmode1" value="0" /><script>show_words('_sel_mixed');</script>&nbsp;
				<input type="radio" name="n_opmode1" value="1" /><script>show_words('_sel_greenfield');</script>
			</td>
		</tr>
		<tr id="bandwidth1">
			<td class="CL"><script>show_words('_help_txt151')</script></td>
			<td class="CR" id="n_bandwidth1">
				<span id="bwl1_20"><input type="radio" name="n_bandwidth1" value="0" /><script>show_words('bwl_ht20')</script></span><br/>
				<span id="bwl1_2040"><input type="radio" name="n_bandwidth1" value="1" /><script>show_words('bwl_ht2040')</script></span><br/>
				<span id="bwl1_204080"><input type="radio" name="n_bandwidth1" value="3" /><script>show_words('bwl_ht204080')</script></span>
			</td>
		</tr>
		<tr style="display:none;">
			<td class="CL"><script>show_words('_lb_exten_channel');</script></td>
			<td class="CR">
				<select id="n_extcha1" name="n_extcha1" size="1" disabled="">
					<option value="0" selected=""><script>show_words('_sel_autoselect');</script></option>
				</select>
			</td>
		</tr>
		</table>
	</div>

	<div class="box_tn">
		<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="formarea">
		<tr><td colspan="2" class="CT"><script>show_words('ES_security')</script></td></tr>
		<tr>
			<td class="CL"><script>show_words('bws_SM')</script></td>
			<td class="CR">
				<span id="SecurMode_list1"></span>
			</td>
		</tr>
		<tr style="display:none" id="security_shared_mode1">
			<td class="CL"><script>show_words('_wifiser_mode8')</script></td>
			<td class="CR">
				<select name="shared_mode1" id="shared_mode1" size="1" onChange="securityMode1(1)">
					<option value=WEP><script>show_words('LS321')</script></option>
					<option value=None><script>show_words('_none')</script></option>
				</select>
			</td>
		</tr>
		</table>
	</div>

	<div class="box_tn" id="msgbox1" style="display:none">
		<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="formarea">
			<tr>
				<td colspan="2" class="CELL">
					<font color="white" id="NmodeMsg1" name="NmodeMsg1"><script>show_words('_wifiser_mode9')</script>
					</font>
				</td>
			</tr>
		</table>
	</div>

	<!-- WEP -->
	<div id="wep1" class="box_tn" style="display:none;">
		<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="formarea">
		<tr><td colspan="3" class="CT"><script>show_words('_WEP')</script></td></tr>
		<tr>
			<td class="CL"><script>show_words('_wifiser_mode10')</script></td>
			<td class="CR" colspan="2">
				<select name="wep_default_key1" id="wep_default_key1" size="1">
					<option value="1"><script>show_words('_wifiser_mode11')</script> 1</option>
					<option value="2"><script>show_words('_wifiser_mode11')</script> 2</option>
					<option value="3"><script>show_words('_wifiser_mode11')</script> 3</option>
					<option value="4"><script>show_words('_wifiser_mode11')</script> 4</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('LW22')</script> 1 :</td>
			<td class="CELL"><input name="wep_key_11" id="WEP11" maxlength="26" value="" /></td>
			<td class="CELL">
				<select id="WEP1Select1" name="WEP1Select1"> 
					<option value="1"><script>show_words('help423')</script></option>
					<option value="0"><script>show_words('_wifiser_mode12')</script></option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('LW22')</script> 2 :</td>
			<td class="CELL"><input name="wep_key_21" id="WEP21" maxlength="26" value="" /></td>
			<td class="CELL">
				<select id="WEP2Select1" name="WEP2Select1">
					<option value="1"><script>show_words('help423')</script></option>
					<option value="0"><script>show_words('_wifiser_mode12')</script></option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('LW22')</script> 3 :</td>
			<td class="CELL"><input name="wep_key_31" id="WEP31" maxlength="26" value="" /></td>
			<td class="CELL">
				<select id="WEP3Select1" name="WEP3Select1">
					<option value="1"><script>show_words('help423')</script></option>
					<option value="0"><script>show_words('_wifiser_mode12')</script></option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('LW22')</script> 4 :</td>
			<td class="CELL"><input name="wep_key_41" id="WEP41" maxlength="26" value="" /></td>
			<td class="CELL">
				<select id="WEP4Select1" name="WEP4Select1">
					<option value="1"><script>show_words('help423')</script></option>
					<option value="0"><script>show_words('_wifiser_mode12')</script></option>
				</select>
			</td>
		</tr>
		</table>
	</div>

	<!-- WPA -->
	<div id="wpa1" class="box_tn" style="display:none;">
		<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="formarea">
		<tr><td colspan="4" class="CT"><script>show_words('help750')</script></td></tr>
		<tr id="wpa_algorithms1" name="wpa_algorithms1" style="display:none"> 
			<td class="CL"><script>show_words('_wifiser_mode13')</script></td>
			<td id="TKIP1" class="CELL">
				<input name="cipher1" id="cipher1" value="0" type="radio" onClick="onWPAAlgorithmsClick1(0)" />
				<script>show_words('bws_CT_1')</script> &nbsp;
			</td>
			<td id="AES1" class="CELL">
				<input name="cipher1" id="cipher1" value="1" type="radio" onClick="onWPAAlgorithmsClick1(1)" />
				<script>show_words('bws_CT_2')</script>  &nbsp;
			</td>
			<td id="TKIPAES1" class="CELL">
				<input name="cipher1" id="cipher1" value="2" type="radio" onClick="onWPAAlgorithmsClick1(2)" />
				<script>show_words('bws_CT_1')</script>/<script>show_words('bws_CT_2')</script> &nbsp;
			</td>
		</tr>
		<tr id="wpa_passphrase1" name="wpa_passphrase1" style="display:none">
			<td class="CL"><script>show_words('help381')</script></td>
			<td colspan="3" class="CELL">
				<input name="passphrase1" id="passphrase1" size="28" maxlength="64" value="" />
			</td>
		</tr>
		<tr id="wpa_key_renewal_interval1" name="wpa_key_renewal_interval1" style="display:none">
			<td class="CL"><script>show_words('_wifiser_mode14')</script></td>
			<td colspan="3" class="CELL">
				<input name="keyRenewalInterval1" id="keyRenewalInterval1" size="4" maxlength="4" value="3600" />
				<script>show_words('_seconds')</script>
			</td>
		</tr>
		<tr id="wpa_PMK_Cache_Period1" name="wpa_PMK_Cache_Period1" style="display:none">
			<td class="CL"><script>show_words('_wifiser_mode15')</script></td>
			<td colspan="3" class="CELL">
				<input name="PMKCachePeriod1" id="PMKCachePeriod1" size="4" maxlength="4" value="" /> 
				<script>show_words('tt_Minute')</script>
			</td>
		</tr>
		<tr id="wpa_preAuthentication1" name="wpa_preAuthentication1" style="display:none">
			<td class="CL"><script>show_words('_wifiser_mode16')</script></td>
			<td colspan="3" class="CELL">
				<input name="PreAuthentication1" id="PreAuthentication1" value="0" type="radio" />
				<script>show_words('_disable')</script> &nbsp;
				<input name="PreAuthentication1" id="PreAuthentication1" value="1" type="radio" />
				<script>show_words('_enable')</script> &nbsp;
			</td>
		</tr>
		</table>
	</div>

	<!-- 802.1x -->
	<!-- WEP  -->
	<div id="8021x_wep1" class="box_tn" style="display:none;">
		<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="formarea">
		<tr><td colspan="3" class="CT"><script>show_words('_wifiser_mode17')</script></td></tr>
		<tr id="wpa_algorithms1" name="wpa_algorithms1" style="display:none"> 
			<td class="CL"><script>show_words('_WEP')</script></td>
			<td colspan="2" class="CELL">
				<input name="ieee8021x_wep1" id="ieee8021x_wep1" value="0" type="radio" />
				<script>show_words('_disable')</script> &nbsp;
				<input name="ieee8021x_wep1" id="ieee8021x_wep1" value="1" type="radio" />
				<script>show_words('_enable')</script> &nbsp;
			</td>
		</tr>
		</table>
	</div>

	<div id="radius_server1" class="box_tn" style="display:none;">
		<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="formarea">
		<tr><td colspan="3" class="CT"><script>show_words('_wifiser_mode18')</script></td></tr>
		<tr> 
			<td class="CL"><script>show_words('ES_IP_ADDR')</script></td>
			<td colspan="2" class="CELL">
				<input name="RadiusServerIP1" id="RadiusServerIP1" size="16" maxlength="32" value="" />
			</td>
		</tr>
		<tr> 
			<td class="CL"><script>show_words('sps_port')</script></td>
			<td colspan="2" class="CELL">
				<input name="RadiusServerPort1" id="RadiusServerPort1" size="5" maxlength="5" value="" />
			</td>
		</tr>
		<tr> 
			<td class="CL"><script>show_words('_wifiser_mode19')</script></td>
			<td colspan="2" class="CELL">
				<input name="RadiusServerSecret1" id="RadiusServerSecret1" size="16" maxlength="64" value="" />
			</td>
		</tr>
		<tr style="display:none"> 
			<td class="CL"><script>show_words('_wifiser_mode20')</script></td>
			<td colspan="2" class="CELL">
				<input name="RadiusServerSessionTimeout1" id="RadiusServerSessionTimeout1" size="3" maxlength="4" value="0" />
			</td>
		</tr>
		<tr style="display:none"> 
			<td class="CL"><script>show_words('_wifiser_mode21')</script></td>
			<td colspan="2" class="CELL">
				<input name="RadiusServerIdleTimeout1" id="RadiusServerIdleTimeout1" size="3" maxlength="4" value="" readonly>
			</td>
		</tr>
		</table>
	</div>

	<div id="div_11n_plugfest1" class="box_tn" style="display:none">
		<div class="CT">Other</div>
		<table name="div_11n_plugfest1" cellspacing="0 "cellpadding="0" class="formarea">
			<!--
			<tr>
				<td class="CL" nowrap><script>show_words('_lb_forty_into');</script></td>
				<td class="CR"><font color="#003366" face=arial><b>
					<input type=radio name=f_40mhz1 value="0" /><script>show_words('_disable');</script>&&nbsp;
					<input type=radio name=f_40mhz1 value="1" /><script>show_words('_enable');</script>
				</b></font></td>
			</tr>
			<tr>
				<td class="CL"><script>show_words('_lb_wifi_opt');</script></td>
				<td class="CR">
					<input type=radio name=wifi_opt1 value="0" /><script>show_words('_disable');</script>&&nbsp;
					<input type=radio name=wifi_opt1 value="1" /><script>show_words('_enable');</script>
				</td>
			</tr>
			-->
			<tr>
				<td class="CL"><script>show_words('_lb_ht_txstream');</script></td>
				<td class="CR">
					<select id="tx_stream1" name="tx_stream1" size="1">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="CL"><script>show_words('_lb_ht_rxstream');</script></td>
				<td class="CR">
					<select id="rx_stream1" name="rx_stream1" size="1">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
					</select>
				</td>
			</tr>
		</table>
	</div>

	<div id="buttonField1" class="box_tn">
		<table cellspacing="0 "cellpadding="0" class="formarea">
			<tr align="center">
			<td colspan="2" class="btn_field">
				<input type="button" class="button_submit" id="btn_apply1" value="Apply" onclick="check_apply();" />
				<script>$('#btn_apply1').val(get_words('_apply'));</script>
				<input type="reset" class="button_submit" id="btn_cancel1" value="Cancel" onclick="window.location.reload()" />
				<script>$('#btn_cancel1').val(get_words('_cancel'));</script>
			</td>
			</tr>
		</table>
	</div>
</form>
</div>

<div id="radioOffField1" style="display:none;">
	<form method="post" name="wireless_basic_for_radio_off" action="/goform/wirelessBasic">
	<div class="box_tn">
		<div class="CT"><script>show_words('_wireless_5')</script></div>
		<table cellspacing="0 "cellpadding="0" class="formarea">
			<tr>
				<td class="CL"><script>show_words('_lb_radio_onoff')</script></td>
				<td class="CR">
					<input type="button" id="radioButtonOn1" name="radioButtonOn1" style="{width:120px;}" value="" onclick="switchRadio1(true);" /> &nbsp; &nbsp;
					<script>$('#radioButtonOn1').val(get_words('_btn_radio_on'));</script>
					<input type="hidden" name="radiohiddenButton" value="2" />
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center" class="CELL"><font color="red" id="Msg" name="Msg"><script>show_words('_MSG_woff');</script></font></td>
			</tr>
		</table>
	</div>
	</form>
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