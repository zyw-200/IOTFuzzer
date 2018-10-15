<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | Basic | Guest Network</title>
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

	var main = new ccpObject();
	main.set_param_url('get_set.ccp');
	main.set_ccp_act('get');
	
	main.add_param_arg('IGD_','1000');
	main.add_param_arg('IGD_ScheduleRule_i_','1000');
	
	for (i=1;i<=8;i++){
		main.add_param_arg('IGD_WLANConfiguration_i_','1'+i+'00');
		main.add_param_arg('IGD_WLANConfiguration_i_WEP_','1'+i+'10');
		main.add_param_arg('IGD_WLANConfiguration_i_WEP_WEPKey_i_','1'+i+'10');
//		main.add_param_arg('IGD_WLANConfiguration_i_WPS_','1'+i+'10');
		main.add_param_arg('IGD_WLANConfiguration_i_WPA_','1'+i+'10');
		main.add_param_arg('IGD_WLANConfiguration_i_WPA_PSK_','1'+i+'11');
		main.add_param_arg('IGD_WLANConfiguration_i_WPA_EAP_i_','1'+i+'10');
	}
	
	main.get_config_obj();

	var lanCfg = {
		'enable':			main.config_str_multi("wlanCfg_Enable_"),	//gz_enable
		'schedule':			main.config_str_multi("wlanCfg_ScheduleIndex_"),	//gz_schedule
//		'domain':			main.config_str_multi("wlanCfg_RFDomain_"),	Silvia: we do not have
//		'domain_A':			main.config_str_multi("wlanCfg_RFDomain_A_"),	Silvia: we do not have
		'rate':				main.config_str_multi("wlanCfg_TransmitRate_"),
		'ssid':				main.config_str_multi("wlanCfg_SSID_"),
		'autochan':			main.config_str_multi("wlanCfg_AutoChannel_"),
		'channel':			main.config_str_multi("wlanCfg_Channel_"),
		'coexi':			main.config_str_multi("wlanCfg_BSSCoexistenceEnable_"),
		'beaconEnab':		main.config_str_multi("wlanCfg_BeaconAdvertisementEnabled_"),
		'chanwidth':		main.config_str_multi("wlanCfg_ChannelWidth_"),
		'standard':			main.config_str_multi("wlanCfg_Standard_"),
		'standard5G':		main.config_str_multi("wlanCfg_Standard5G_"),
		'sMode':			main.config_str_multi("wlanCfg_SecurityMode_"),	//wep_type_value
		'wdsenable':		main.config_str_multi("wlanCfg_WDSEnable_")
	}

	var advCfg = {
		'bgprotection':		main.config_str_multi("wlanCfg_BGProtection_"),
		'beaconperiod':		main.config_str_multi("wlanCfg_BeaconPeriod_"),
		'dtim':				main.config_str_multi("wlanCfg_DTIMInterval_"),
		'fragthres':		main.config_str_multi("wlanCfg_FragmentThreshold_"),
		'rtsthres':			main.config_str_multi("wlanCfg_RTSThreshold_"),
		'txpower':			main.config_str_multi("wlanCfg_TransmitPower_"),
		'spreamble':		main.config_str_multi("wlanCfg_ShortPreamble_"),
		'sslot':			main.config_str_multi("wlanCfg_ShortSlot_"),
		'txburst':			main.config_str_multi("wlanCfg_TxBurst_"),
		'pktaggregate':		main.config_str_multi("wlanCfg_PktAggregate_"),
		'ieee11h':			main.config_str_multi("wlanCfg_IEEE11H_"),
		'partition':		main.config_str_multi("wlanCfg_WlanPartitionEnable_"),
		'routingzone':		main.config_str_multi("wlanCfg_RouteBetweenZone_")
	}
/*
	var wpsCfg = {
		'enable':		main.config_str_multi("wpsCfg_Enable_"),
		'status':		main.config_str_multi("wpsCfg_Status_")
	}
*/
	var wepCfg = {
		'infokey':		main.config_str_multi("wepInfo_KeyIndex_"),
		'infoAuthMode':	main.config_str_multi("wepInfo_AuthenticationMode_"),
		'infoKeyL':		main.config_str_multi("wepInfo_KeyLength_"),
		'key64':		main.config_str_multi("wepKey_KeyHEX64_"),
		'key128':		main.config_str_multi("wepKey_KeyHEX128_"),
		'keyType':		main.config_str_multi("wepKey_KeyType_"),
		'keyLength':	main.config_str_multi("wepKey_KeyLength_")
	};
	var wpaCfg = {
		'infoWPAMode':	main.config_str_multi("wpaInfo_WPAMode_"),
		'infoAuthMode':	main.config_str_multi("wpaInfo_AuthenticationMode_"),
		'infoKeyup':	main.config_str_multi("wpaInfo_KeyUpdateInterval_"),
		'infoTimeout':	main.config_str_multi("wpaInfo_AuthenticationTimeout_"),
		'infoMode':		main.config_str_multi("wpaInfo_WPAMode_"),
		'encrMode':		main.config_str_multi("wpaInfo_EncryptionMode_"),	//c_type
		'pskKey':		main.config_str_multi("wpaPSK_KeyPassphrase_")
	};
	var EapCfg ={
		'ip':			main.config_str_multi("wpaEap_RadiusServerIP_"),
		'port':			main.config_str_multi("wpaEap_RadiusServerPort_"),
		'psk':			main.config_str_multi("wpaEap_RadiusServerPSK_"),
		'macauth':		main.config_str_multi("wpaEap_MACAuthentication_"),
		'preauth':		main.config_str_multi("wpaEap_PreAuthentication_"),
		'cacheT':		main.config_str_multi("wpaEap_PMKCachePeriod_"),
		'SessionT':		main.config_str_multi("wpaEap_RadiusSessionTime_")
		
	};
	
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

	var schedule_cnt = 0;
	var array_sch_inst 	= main.config_inst_multi("IGD_ScheduleRule_i_");
	if(schCfg.name != null)
		schedule_cnt = schCfg.name.length;
		
/*	remove wps condition
	var is_wps = (wpsCfg.enable[0]?wpsCfg.enable[0]:"0");//wps enable or not
	var is_wps_5 = (wpsCfg.enable[4]?wpsCfg.enable[4]:"0");//wps_5G enable or not
*/
	
	var isWDS =				main.config_str_multi("wlanCfg_WDSEnable_");
	var SSID =				main.config_str_multi("wlanCfg_SSID_");
	var standard =			main.config_str_multi("wlanCfg_Standard_");
	var standard5g =		main.config_str_multi("wlanCfg_Standard5G_");
	var securMode =			main.config_str_multi("wlanCfg_SecurityMode_"); //need to add check b/g/n
	
function check_value(){
	if($('#gz_enable').attr('checked') && !(check_ssid_0("gz_ssid")))
		return false;
	if($('#gz_enable1').attr('checked') && !(check_ssid_0("gz_ssid1")))
		return false;
	return true;
}
function check_apply()
{
	var obj = new ccpObject();
	obj.set_param_url('get_set.ccp');
	obj.set_ccp_act('set');
	obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
	obj.set_param_next_page('basic_guest.asp');
	
	if(check_value())
	{
		obj.add_param_arg('wlanCfg_Enable_','1.4.0.0',($('#gz_enable').attr('checked')?1:0));
		obj.add_param_arg('wlanCfg_SSID_','1.4.0.0',urlencode($('#gz_ssid').val()));
		obj.add_param_arg('wlanCfg_ScheduleIndex_','1.4.0.0',$('#gz_schedule').val());
		obj.add_param_arg('wlanCfg_WlanPartitionEnable_','1.4.0.0',$('#gz_wlan_partition').attr('checked')?1:0);
		obj.add_param_arg('wlanCfg_RouteBetweenZone_','1.4.0.0',$('#gz_inet_access_only').attr('checked')?0:1);
		
		obj.add_param_arg('wlanCfg_Enable_','1.8.0.0',($('#gz_enable1').attr('checked')?1:0));
		obj.add_param_arg('wlanCfg_SSID_','1.8.0.0',urlencode($('#gz_ssid1').val()));
		obj.add_param_arg('wlanCfg_ScheduleIndex_','1.8.0.0',$('#gz_schedule1').val());
		obj.add_param_arg('wlanCfg_WlanPartitionEnable_','1.8.0.0',$('#gz_wlan_partition1').attr('checked')?1:0);
		obj.add_param_arg('wlanCfg_RouteBetweenZone_','1.8.0.0',$('#gz_inet_access_only1').attr('checked')?0:1);
		
		/* security 2.4G */
		//check data
		if (checkData() == false)
			return false;
		
		var idx = 4;
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
//			obj.add_param_arg('wpsCfg_Enable_','1.'+idx+'.1.0',is_wps);
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
				
//				obj.add_param_arg('wpsCfg_Enable_','1.'+idx+'.1.0',is_wps);
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
				
//				obj.add_param_arg('wpsCfg_Enable_','1.'+idx+'.1.0',is_wps);
			}
			
			obj.add_param_arg('wpaInfo_EncryptionMode_','1.'+idx+'.1.0',get_radio_value(get_by_name("cipher")));
			obj.add_param_arg('wpaInfo_KeyUpdateInterval_','1.'+idx+'.1.0',$("#keyRenewalInterval").val());
		}
		/* security 5G */
		//check data
		if (checkData1() == false)
			return false;
		
		var idx = 8;
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
//			obj.add_param_arg('wpsCfg_Enable_','1.'+idx+'.1.0',is_wps_5);
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
				
//				obj.add_param_arg('wpsCfg_Enable_','1.'+idx+'.1.0',is_wps_5);
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
				
//				obj.add_param_arg('wpsCfg_Enable_','1.'+idx+'.1.0',is_wps_5);
			}
			
			obj.add_param_arg('wpaInfo_EncryptionMode_','1.'+idx+'.1.0',get_radio_value(get_by_name("cipher1")));
			obj.add_param_arg('wpaInfo_KeyUpdateInterval_','1.'+idx+'.1.0',$("#keyRenewalInterval1").val());
		}
		
		
		var paramForm = obj.get_param();
		
		totalWaitTime = 20; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(paramForm.url, paramForm.arg);
	}
}
function setValueGuestZoneEnable()
{
	var chk_part = lanCfg.enable[3];
	$('#gz_enable').attr('checked', chk_part=='1');
}
function setEventGuestZoneEnable()
{
	var func = function(){
		var gz_enable = $('#gz_enable').attr('checked');
		if(gz_enable)
		{
			$('#gz_schedule, #sche_btn').removeAttr('disabled');
		}
		else
		{
			$('#gz_schedule, #sche_btn').attr('disabled','disabled');
		}
	};
	func();
	$('#gz_enable').change(func);
}
function setValueWirelessNameSSID()
{
	var val_ssid = lanCfg.ssid[3];
	$('#gz_ssid').val(val_ssid);
}
function setValueRadioOnSchedule()
{
	var val_sche = lanCfg.schedule[3];
	$('#gz_schedule').val(val_sche);
}
function setValueWlanPartition()
{
	var chk_part = advCfg.partition[3];
	$('#gz_wlan_partition').attr('checked', chk_part=='1');
}
function setValueNetworkBridge() {
}
function setValueInternetAccessOnly() {
	var chk_access = advCfg.routingzone[3];
	$('#gz_inet_access_only').attr('checked', chk_access!='1');
}
function setValueGuestZoneEnable5G()
{
	var chk_part = lanCfg.enable[7];
	$('#gz_enable1').attr('checked', chk_part=='1');
}
function setEventGuestZoneEnable5G()
{
	var func = function(){
		var gz_enable = $('#gz_enable1').attr('checked');
		if(gz_enable)
		{
			$('#gz_schedule1, #sche_btn1').removeAttr('disabled');
		}
		else
		{
			$('#gz_schedule1, #sche_btn1').attr('disabled','disabled');
		}
	};
	func();
	$('#gz_enable1').change(func);
}
function setValueWirelessNameSSID5G()
{
	var val_ssid = lanCfg.ssid[7];
	$('#gz_ssid1').val(val_ssid);
}
function setValueRadioOnSchedule5G()
{
	var val_sche = lanCfg.schedule[7];
	$('#gz_schedule1').val(val_sche);
}
function setValueWlanPartition5G()
{
	var chk_part = advCfg.partition[7];
	$('#gz_wlan_partition1').attr('checked', chk_part=='1');
}
function setValueNetworkBridge5G() {
}
function setValueInternetAccessOnly5G() {
	var chk_access = advCfg.routingzone[7];
	$('#gz_inet_access_only1').attr('checked', chk_access!='1');
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
/*		remove wps condition
		if (wpsCfg.enable[0] == 1)
		{
			if (confirm(get_words("msg_wps_sec_01")) == false)
				return false;
			else
				is_wps = 0;
		}
*/
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
/*		remove wps condition
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
*/
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
/*		remove wps condition
		if (wpsCfg.enable[0] == 1)
		{
			if (confirm(get_words("msg_wps_sec_05")) == false)
				return false;
			else
				is_wps = 0;
		}
*/
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
/*		remove wps condition
		if (wpsCfg.enable[0] == 1)
		{
			if (confirm(get_words("msg_wps_sec_05")) == false)
				return false;
			else
				is_wps = 0;
		}
*/
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
/*		remove wps condition
		if (wpsCfg.enable[4] == 1)
		{
			if (confirm(get_words("msg_wps_sec_01")) == false)
				return false;
			else
				is_wps_5 = 0;
		}
*/
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
/*		remove wps condition
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
*/
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
/*		remove wps condition
		if (wpsCfg.enable[4] == 1)
		{
			if (confirm(get_words("msg_wps_sec_05")) == false)
				return false;
			else
				is_wps_5 = 0;
		}
*/
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
/*		remove wps condition
		if (wpsCfg.enable[0] == 1)
		{
			if (confirm(get_words("msg_wps_sec_05")) == false)
				return false;
			else
				is_wps_5 = 0;
		}
*/
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
// security end

$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(model);
	if(lanCfg.enable[0]==1)
		$('#radioOnField').show();
	else
		$('#radioOffField').show();
	
	//Guest Network
	setValueGuestZoneEnable();
	setEventGuestZoneEnable();
	setValueWirelessNameSSID();
	setValueRadioOnSchedule();
	setValueWlanPartition();
//	setValueNetworkBridge();
	setValueInternetAccessOnly();
	
	//Security
	setValueSecurModeList();
	MBSSIDChange(3);
	
	if(lanCfg.enable[4]==1)
		$('#radioOnField1').show();
	else
		$('#radioOffField1').show();
	
	setValueGuestZoneEnable5G();
	setEventGuestZoneEnable5G();
	setValueWirelessNameSSID5G();
	setValueRadioOnSchedule5G();
	setValueWlanPartition5G();
//	setValueNetworkBridge5G();
	setValueInternetAccessOnly5G();
	
	//Security
	setValueSecurModeList1();
	MBSSIDChange1(7);
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
			<td style="background-image:url('/image/bg_l.gif');background-repeat:repeat-y;vertical-align:top;width:270px;" width="270">
				<div style="padding-left:6px;">
				<script>document.write(menu.build_structure(0,2,-1));</script>
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
								<script>show_words('_guest_network');</script>
								</div>
								<div class="hr"></div>
								<div class="section_content_border">
									<div class="header_desc" id="basicIntroduction">
										<script>show_words('_guest_text1')</script>
										<p></p>
									</div>
<div id="radioOnField" style="display: none;">
<div class="box_tn">
	<div class="CT"><script>show_words('_guest_network')</script> - <script>show_words('KR16');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
		<tr>
			<td class="CL"><script>show_words('_enabled')</script></td>
			<td class="CR">
				<input type="checkbox" id="gz_enable" /> &nbsp;
				<span id="scheduleField">
					<select id="gz_schedule" name="gz_schedule">
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
			<td class="CL"><script>show_words('_wmode_ssid');</script></td>
			<td class="CR">
				<input type="text" id="gz_ssid" />
			</td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('guest_isolation');</script></td>
			<td class="CR">
				<input type="checkbox" id="gz_wlan_partition" />
				(<script>show_words('guest_isolation_txt');</script>)
			</td>
		</tr>
		<tr id="gz_networkbridge" style="display:none;">
			<td class="CL"><script>show_words('_network_bridge');</script></td>
			<td class="CR">
				<select id="gz_network_bridge" size="1" disabled>
					<option value="0"><script>show_words('_LAN');</script></option>
					<option value="1"><script>show_words('_guest');</script></option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('_inet_access_only');</script></td>
			<td class="CR">
				<input type="checkbox" id="gz_inet_access_only" />
				(<script>show_words('_inet_access_only_txt');</script>)
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
</div>

<div id="radioOffField" class="box_tn" style="display: none;">
	<table cellspacing="0" cellpadding="0" class="formarea">
		<tr>
			<td colspan="2" align="center" class="CELL"><font color="red" id="Msg" name="Msg"><script>show_words('_MSG_woff');</script></font></td>
		</tr>
	</table>
</div>

<div id="radioOnField1" style="display:none;">
<div class="box_tn">
	<div class="CT"><script>show_words('_guest_network')</script> - <script>show_words('KR17');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
		<tr>
			<td class="CL"><script>show_words('_enabled')</script></td>
			<td class="CR">
				<input type="checkbox" id="gz_enable1" /> &nbsp;
				<span id="scheduleField1">
					<select id="gz_schedule1" name="gz_schedule1">
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
			<td class="CL"><script>show_words('_wmode_ssid');</script></td>
			<td class="CR">
				<input type="text" id="gz_ssid1" />
			</td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('guest_isolation');</script></td>
			<td class="CR">
				<input type="checkbox" id="gz_wlan_partition1" />
				(<script>show_words('guest_isolation_txt');</script>)
			</td>
		</tr>
		<tr id="gz_networkbridge1" style="display:none;">
			<td class="CL"><script>show_words('_network_bridge');</script></td>
			<td class="CR">
				<select id="gz_network_bridge1" size="1" disabled>
					<option value="0"><script>show_words('_LAN');</script></option>
					<option value="1"><script>show_words('_guest');</script></option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('_inet_access_only');</script></td>
			<td class="CR">
				<input type="checkbox" id="gz_inet_access_only1" />
				(<script>show_words('_inet_access_only_txt');</script>)
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
</div>

<div id="radioOffField1" class="box_tn" style="display: none;">
	<table cellspacing="0" cellpadding="0" class="formarea">
		<tr>
			<td colspan="2" align="center" class="CELL"><font color="red" id="Msg" name="Msg"><script>show_words('_MSG_woff');</script></font></td>
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