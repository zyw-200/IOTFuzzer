<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | WPS</title>
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
	/*
	** Date:	2013-04-01
	** Author:	Moa Chung
	** Reason:	integrate 2.4G/5G WPS page
	** Note:	wireless_wps.asp,wireless2_wps.asp
	**/
	var def_title = document.title;
	var misc = new ccpObject();
	var dev_info = misc.get_router_info();
	document.title = def_title.replace("modelName", dev_info.model);

	var hw_version 	= dev_info.hw_ver;
	var version 	= dev_info.fw_ver;
	var model		= dev_info.model;
	var login_Info 	= dev_info.login_info;
	var cli_mac 	= dev_info.cli_mac;
	var submit_c	= "";
	var is_wps;
	var PIN;
	
	var main = new ccpObject();
	main.set_param_url('get_set.ccp');
	main.set_ccp_act('get');
	
	main.add_param_arg('IGD_WLANConfiguration_i_WPS_',1110);
	main.add_param_arg('IGD_WLANConfiguration_i_',1100);
	main.add_param_arg('IGD_WLANConfiguration_i_WEP_',1110);
	main.add_param_arg('IGD_WLANConfiguration_i_WPA_',1110);
	main.add_param_arg('IGD_WLANConfiguration_i_WPA_PSK_',1111);
	main.add_param_arg('IGD_WLANConfiguration_i_WEP_WEPKey_i_',1110);
	main.add_param_arg('IGD_WLANConfiguration_i_WPS_',1510);
	main.add_param_arg('IGD_WLANConfiguration_i_',1500);
	main.add_param_arg('IGD_WLANConfiguration_i_WEP_',1510);
	main.add_param_arg('IGD_WLANConfiguration_i_WPA_',1510);
	main.add_param_arg('IGD_WLANConfiguration_i_WPA_PSK_',1511);
	main.add_param_arg('IGD_WLANConfiguration_i_WEP_WEPKey_i_',1510);
	
	main.get_config_obj();
	
	var current_pin = (main.config_val("wpsCfg_SelfPINNumber_")? main.config_val("wpsCfg_SelfPINNumber_"):"");
	var wlan_enable = (main.config_str_multi("wlanCfg_Enable_")? main.config_str_multi("wlanCfg_Enable_"): "0");
	var wlan_secMode = (main.config_str_multi("wlanCfg_SecurityMode_")? main.config_str_multi("wlanCfg_SecurityMode_"): "0");
	var wlan_wepKeyIdx = (main.config_val("wepInfo_KeyIndex_")? main.config_val("wepInfo_KeyIndex_"): "1");
	var wlan_wepAuth= (main.config_val("wepInfo_AuthenticationMode_")? main.config_val("wepInfo_AuthenticationMode_"): "0");
	var wlan_wpaAuth= (main.config_val("wpaInfo_AuthenticationMode_")? main.config_val("wpaInfo_AuthenticationMode_"): "0");
	var wlan_ssidBst= (main.config_str_multi("wlanCfg_BeaconAdvertisementEnabled_")? main.config_str_multi("wlanCfg_BeaconAdvertisementEnabled_"): "0");

	var wps_enable = (main.config_val("wpsCfg_Enable_")? main.config_val("wpsCfg_Enable_"): "0");
	var wps_state = (main.config_val("wpsCfg_Status_")? main.config_val("wpsCfg_Status_"): "0");	//only use status [0] for chk wps status

	var wps_locked = (main.config_str_multi("wpsCfg_SetupLock_")? main.config_str_multi("wpsCfg_SetupLock_"): "0");
	var encrMode = (main.config_str_multi("wpaInfo_EncryptionMode_")? main.config_str_multi("wpaInfo_EncryptionMode_"): "0");
	var wpaMode = (main.config_str_multi("wpaInfo_WPAMode_")?main.config_str_multi("wpaInfo_WPAMode_"):"0");

	var lanCfg = {
		'enable':			main.config_str_multi("wlanCfg_Enable_")
	};
	var wlanCfg = {
		'ssid':				main.config_str_multi("wlanCfg_SSID_"),
		'standard':			main.config_str_multi("wlanCfg_Standard_"),
		'security':			main.config_str_multi("wlanCfg_SecurityMode_"),
		'vs':				main.config_str_multi("wlanCfg_BeaconAdvertisementEnabled_")
	};
	var wepCfg = {
		'keyLength':		main.config_str_multi("wepInfo_KeyLength_"),
		'keyIdx':			main.config_str_multi("wepInfo_KeyIndex_"),
		'authType':			main.config_str_multi("wepInfo_AuthenticationMode_"),
		'keyType':			main.config_str_multi("wepInfo_KeyType_"),
		'key64':			main.config_str_multi("wepKey_KeyHEX64_"),
		'key128':			main.config_str_multi("wepKey_KeyHEX128_")
	};
	var wpaCfg = {
		'wpamode' :			main.config_str_multi("wpaInfo_WPAMode_"),
		'wpaAuth' :			main.config_str_multi("wpaInfo_AuthenticationMode_"),
		'wpacipher':		main.config_str_multi("wpaInfo_EncryptionMode_"),
		'wpakey':			main.config_str_multi("wpaPSK_KeyPassphrase_")
	};
	
	var wpsCfg = {
		'enable':			main.config_val("wpsCfg_Enable_"),
		'state':			main.config_val("wpsCfg_Status_"),
		'locked':			main.config_val("wpsCfg_SetupLock_"),
		'dn':				main.config_val("wpsCfg_DeviceName_"),
		'password':			main.config_val("wpsCfg_DevicePassword_"),
		'selfpin':			main.config_val("wpsCfg_SelfPINNumber_")
	};
	
function check_pin()
{
	var accum = 0;
	accum += 3 * Math.floor((PIN / 10000000) % 10);
	accum += 1 * Math.floor((PIN / 1000000) % 10);
	accum += 3 * Math.floor((PIN / 100000) % 10);
	accum += 1 * Math.floor((PIN / 10000) % 10);
	accum += 3 * Math.floor((PIN / 1000) % 10);
	accum += 1 * Math.floor((PIN / 100) % 10);
	accum += 3 * Math.floor((PIN / 10) % 10);
	accum += 1 * Math.floor((PIN / 1) % 10);
	return (0 == (accum % 10));
}

function chk_format() {
	//20120119 silvia add chk pin format - 0130 modify 8 num
	PIN = $('#PIN').val();
	var pins1 = PIN.split(' ');
	var pins2 = PIN.split('-');
	var pins3 = PIN.split('');

	if ((pins3[4] == '-') || (pins3[4] == ' ')) {
		if (pins1.length==2)
			PIN = pins1[0] +pins1[1];
		else if (pins2.length==2)
			PIN = pins2[0] +pins2[1];
		if(!_isNumeric(PIN) || pins3.length != 9)
		{
			alert(get_words('KR22_ww'));
			return false;
		}
	} else if ((pins3.length == 8)&&(_isNumeric(PIN))) {
		return;
	} else {
		alert(get_words('pin_f'));
		return false;
	}
}

function check_value()
{
	if(!(ischeck_wps("auto"))){
		return false;
	}
	
	//if(security[1] == "eap" ||security1[1] == "eap" || vap1_security[1] == "eap"  || vap1_security1[1] == "eap" ){				//EAP
	if(((wlan_secMode[0] == "2") || (wlan_secMode[0] == "3")) && (wlan_wpaAuth == "1")){
		alert(get_words('TEXT026'));
		return false;
	}
	
	//if(security[1] == "share" ||security1[1] == "share" || vap1_security[1] == "share"  || vap1_security1[1] == "share" ){				//EAP
	if((wlan_secMode[0] == "1") && (wlan_wepAuth == "1")){
		alert(get_words('_wps_albert_1'));
		return false;
	}

	if((wlan_wepKeyIdx != "1") && (wlan_secMode[0] == "1")){
		alert(get_words('TEXT024'));//Can't choose WEP key 2, 3, 4 when WPS is enable
		return false;
	}
	return true;
}
function ischeck_wps(obj)
{
	var is_true = false;
	if($('#wpsEnable').val()=='1'){
		if(wlan_enable == "0"){
			alert(get_words('TEXT028'));
			is_true = true;
		}
	}
	if(is_true){
		if(obj == "wps"){
			$('#wpsEnable').val(0);
		}
		return false;
	}
	return true;
}

	function wps_apply()
	{
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('wps.asp');
		
		//WPS Config
		obj.add_param_arg('wpsCfg_Enable_','1.1.1.0',$('#WPSEnable').val());
		obj.add_param_arg('wpsCfg_SetupLock_','1.1.1.0',$('#WPSEG').val());
		obj.add_param_arg('wpsCfg_Enable_','1.5.1.0',$('#WPSEnable').val());
		obj.add_param_arg('wpsCfg_SetupLock_','1.5.1.0',$('#WPSEG').val());
		
		var paramForm = obj.get_param();
		
		totalWaitTime = 10; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(paramForm.url, paramForm.arg);
	}

/* WPS common*/
var count=120;
function do_count_down(){
	if (count == 0) {
		//back();
		$('#WPSCurrentStatus').html(get_words('psIdle'));
		count=120;
		return false;
	}

	if (count > 0) {
		$('#WPSCurrentStatus').html(get_words('_processing'));
		count--;
		setTimeout('do_count_down()',1000);
	}
}
/* PIN */
function query_wps_state_pin()
{
	var obj = new ccpObject();
	obj.set_param_url('get_set.ccp');
	obj.set_ccp_act('queryWPSPin');
	
	obj.get_config_obj();
	
	var WPSPinRet = obj.config_val("WPSPinRet");
	
	if((WPSPinRet == "success") && (count <=116))
	{
		count=0;
		obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('doEvent');
		obj.add_param_event('CCP_SUB_WPSSUCCESS');
		obj.get_config_obj();
		var path = "wps.asp";
		
		window.location.href = path;
		return;
	}
	else
	{
		obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('doEvent');
		obj.add_param_event('CCP_SUB_WPSFAILURE');
		obj.get_config_obj();
	}
	
	if(count == 0)
	{
		obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('doEvent');
		obj.add_param_event('CCP_SUB_WPSTIMEOUT');
		obj.get_config_obj();
		var path = "wps.asp";
		
		window.location.href = path;
		return;
	}
	
	//count--;		
	//do_count_down();
	setTimeout('query_wps_state_pin()',1000);
}
function sendWPSPIN()
{
	var time=new Date().getTime();
	var ajax_param = {
		type: 	"POST",
		async:	false,
		url: 	'get_set.ccp',
		data: 	'ccp_act=set&ccpSubEvent=CCP_SUB_WPSPIN'+
				"&wpsCfg_ClientPINNumber_1.1.1.0="+$('#PIN').val()+
				"&wpsCfg_ClientPINNumber_1.5.1.0="+$('#PIN').val()+
				"&"+time+"="+time,
				
		success: function(data) {
		}
	};
	$.ajax(ajax_param);
}
function ConfigByPIN()
{
	if(checkPIN())
	{
		sendWPSPIN();
		do_count_down();
		query_wps_state_pin();
	}
}
function checkPIN()
{
	var pinnum = $('#PIN').val();
	if (pinnum.length == 4)
	{
		if (!_isNumeric(pinnum))
		{
			alert(get_words('pin_f'));
			return false;
		}
		PIN = pinnum;
	}else{
		if (chk_format() == false)
			return false;
		if (!check_pin() || pinnum =='')
		{
			alert(get_words('KR22_ww'));
			return false;
		}
	}
	return true;
}
/* PBC */
function query_wps_state_pbc()
{
	var obj = new ccpObject();
	obj.set_param_url('get_set.ccp');
	obj.set_ccp_act('queryWPSPBC');
	
	var WPSPBCRet = obj.config_val("WPSPBCRet");
	
	if((WPSPBCRet == "success") && (count <=116))
	{
		obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('doEvent');
		obj.add_param_event('CCP_SUB_WPSSUCCESS');
		obj.get_config_obj();
		var path = "wps.asp";
		
		window.location.href = path;
		return;
	}
	else
	{
		obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('doEvent');
		obj.add_param_event('CCP_SUB_WPSFAILURE');
		obj.get_config_obj();
	}
	
	if(count == 0)
	{
		obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('doEvent');
		obj.add_param_event('CCP_SUB_WPSTIMEOUT');
		obj.get_config_obj();
		var path = "wps.asp";
		
		window.location.href = path;
		return;
	}
		
	//count--;
	//do_count_down();
	setTimeout('query_wps_state_pbc()',1000);
}
function sendWPSPBC()
{
	var time=new Date().getTime();
	var ajax_param = {
		type: 	"POST",
		async:	false,
		url: 	'get_set.ccp',
		data: 	'ccp_act=set&ccpSubEvent=CCP_SUB_WPSPBC'+
				"&"+time+"="+time,
				
		success: function(data) {
		}
	};
	$.ajax(ajax_param);
}
function ConfigByPBC(){
	sendWPSPBC();
	do_count_down();
	query_wps_state_pbc();
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
			else//add MCS32 in 20/40MHz
			{
				$('#n_extcha').attr('disabled','');
				if($('#wlan0_11'+wmode+'_txrate option[value=1]').length==0)
				{
					var option = document.createElement("option");
					option.text = "MCS32: 6M";
					option.value = "1";
					$('#wlan0_11'+wmode+'_txrate').append(option);
				}
				
			}
		};
		func();
		$('input[name=n_bandwidth]').change(func);
	}
	function setValueWiFiOptimum(){
		var chk_wifiopt = otherCfg.wifiopt[0];
		$('input[name=wifi_opt][value='+chk_wifiopt+']').attr('checked', true);
	}
	function setValueHTTxStream(){
		var sel_tx = otherCfg.txstream[0];
		$('#tx_stream').val(sel_tx);
	}
	function isWPS2_0(secMode, wpaMode, wpaEncrMode){
		if((secMode=='1') || //WEP
			((secMode=='2') && (wpaMode=='2')) || //WPA_P && WPA
			((secMode=='2') && (wpaEncrMode=='0')) ||// WPA_P && TKIP
			(secMode=='3')//WPA_E
		)
		{
			return true;
		}
		return false;
	}
	function setValueWPS(){
		var sel_enable = wpsCfg.enable;
		$('#WPSEnable').val(sel_enable);
		if(isWPS2_0(wlanCfg.security[0], wpaCfg.wpamode[0], wpaCfg.wpacipher[0]) ||
			isWPS2_0(wlanCfg.security[1], wpaCfg.wpamode[1], wpaCfg.wpacipher[1]))
			$('#WPSEnable').attr('disabled', true);
		if(wlanCfg.vs[0]=='0' || wlanCfg.vs[1]=='0')
			$('#WPSEnable').attr('disabled', true);
		
		if(sel_enable=='1')
		{
			$('#APLock').show();
			$('#div_wps_status').show();
			$('#div_wps').show();
		}
	}
	function setValueWPSExternalRgistrarLock(){
		var sel_locked = wpsCfg.locked;
		$('#WPSEG').val(sel_locked);
	}
	function setValueWPSCurrentStatus(){
	}
	function setValueWPSConfigured(){
		var val_conf = wpsCfg.state;
		$('#WPSConfigured').html((val_conf=='1'?get_words('_yes'):get_words('_unknown')));
	}
	function setValueWPSSSID(){
		var val_ssid = wlanCfg.ssid[0];
		$('#WPSSSID').html(val_ssid);
	}
	function setValueWPS2SSID(){
		var val_ssid = wlanCfg.ssid[1];
		$('#WPS2SSID').html(val_ssid);
	}
	function setValueWPSSecurityMode(){
		var val_mode = wlanCfg.security[0];
		var w_mode;
		switch(val_mode)
		{
			/*
			** Date:	2013-03-20
			** Author:	Moa Chung
			** Reason:	Wireless 2.4G → WPS：WPS key should not be "00000".
			** Note:	TEW-810DR pre-test no.69
			**/
			case "1":
				w_mode=get_words('_WEP');
				break;
			case "2":
				var wpamode = "";
				//var ciphermode = "";
				if(wpaCfg.wpamode[0] == "0")
					wpamode = get_words('bws_WPAM_2');
				else if(wpaCfg.wpamode[0] == "1")
					wpamode = get_words('bws_WPAM_3');
				else
					wpamode = get_words('bws_WPAM_1');
				w_mode = wpamode+ " - PSK"; //+ " / " + ciphermode);
				break;
			case "3":
				var wpamode = "";
				//var ciphermode = "";
				if(wpaCfg.wpamode[0] == "0")
					wpamode = get_words('bws_WPAM_2'); //+ " - EAP";
				else if(wpaCfg.wpamode[0] == "1")
					wpamode = get_words('bws_WPAM_3');
				else
					wpamode = get_words('bws_WPAM_1');
				w_mode = (wpamode+ " - EAP");
				break;
			case "0":
				w_mode = get_words('_disable');
			default:
				w_mode = get_words('_disable');
			break;
		}
		$('#WPSAuthMode').html(w_mode);
	}
	function setValueWPS2SecurityMode(){
		var val_mode = wlanCfg.security[1];
		var w_mode;
		switch(val_mode)
		{
			/*
			** Date:	2013-03-20
			** Author:	Moa Chung
			** Reason:	Wireless 5G → WPS：WPS key should not be "00000".
			** Note:	TEW-810DR pre-test no.69
			**/
			case "1":
				w_mode=get_words('_WEP');
				break;
			case "2":
				var wpamode = "";
				//var ciphermode = "";
				if(wpaCfg.wpamode[1] == "0")
					wpamode = get_words('bws_WPAM_2');
				else if(wpaCfg.wpamode[1] == "1")
					wpamode = get_words('bws_WPAM_3');
				else
					wpamode = get_words('bws_WPAM_1');
				w_mode = wpamode+ " - PSK"; //+ " / " + ciphermode);
				break;
			case "3":
				var wpamode = "";
				//var ciphermode = "";
				if(wpaCfg.wpamode[1] == "0")
					wpamode = get_words('bws_WPAM_2'); //+ " - EAP";
				else if(wpaCfg.wpamode[1] == "1")
					wpamode = get_words('bws_WPAM_3');
				else
					wpamode = get_words('bws_WPAM_1');
				w_mode = (wpamode+ " - EAP");
				break;
			case "0":
				w_mode = get_words('_disable');
			default:
				w_mode = get_words('_disable');
			break;
		}
		$('#WPS2AuthMode').html(w_mode);
	}
	function setValueWPSEncryptType(){
		var val_mode = wlanCfg.security[0];
		var w_type='';
		switch(val_mode)
		{
			case "0":
				$('#wpsEncTypeField').hide();
			case "1":
				if(wepCfg.authType[0] =='0')
					w_type = get_words('_wps_open');
				else if(wepCfg.authType[0] =='1')
					w_type = get_words('_wps_shared');
				else if(wepCfg.authType[0] =='2')
					w_type = get_words('KR50');
				break;
			case "2":
			case "3":
				if(wpaCfg.wpacipher[0]=='0')
					w_type=get_words('bws_CT_1');
				else if(wpaCfg.wpacipher[0]=='1')
					w_type=get_words('bws_CT_2');
				else if(wpaCfg.wpacipher[0]=='2')
					w_type=get_words('bws_CT_3');
				break;
		}
		$('#WPSEncryptype').html(w_type);
	}
	function setValueWPS2EncryptType(){
		var val_mode = wlanCfg.security[1];
		var w_type='';
		switch(val_mode)
		{
			case "0":
				$('#wps2EncTypeField').hide();
			case "1":
				if(wepCfg.authType[1] =='0')
					w_type = get_words('_wps_open');
				else if(wepCfg.authType[1] =='1')
					w_type = get_words('_wps_shared');
				else if(wepCfg.authType[1] =='2')
					w_type = get_words('KR50');
				break;
			case "2":
			case "3":
				if(wpaCfg.wpacipher[1]=='0')
					w_type=get_words('bws_CT_1');
				else if(wpaCfg.wpacipher[1]=='1')
					w_type=get_words('bws_CT_2');
				else if(wpaCfg.wpacipher[1]=='2')
					w_type=get_words('bws_CT_3');
				break;
		}
		$('#WPS2Encryptype').html(w_type);
	}
	function setValueWPSDefaultKeyIndex(){
		var val_mode = wlanCfg.security[0];
		var w_type='';
		switch(val_mode)
		{
			case '1':
				$('#keyIndexField').show();
				var val_idx = wepCfg.keyIdx[0];
				$('#WPSDefaultKeyIndex').html(val_idx);
			break;
		}
	}
	function setValueWPS2DefaultKeyIndex(){
		var val_mode = wlanCfg.security[1];
		var w_type='';
		switch(val_mode)
		{
			case '1':
				$('#keyIndexField2').show();
				var val_idx = wepCfg.keyIdx[1];
				$('#WPS2DefaultKeyIndex').html(val_idx);
			break;
		}
	}
	function setValueWPSKeyType(){
		var w_keytype='';
		var val_mode = wlanCfg.security[0];
		switch(val_mode)
		{
			case '0':
				$('#wpsKeyField').hide();
			case '1':
				var val_keytype = wepCfg.keyType[0];
				switch(val_keytype){
				case '0':
					w_keytype = get_words('_wps_key')+'('+get_words('_hex')+')';
					break;
				case '1':
					w_keytype = get_words('_wps_key')+'('+get_words('_ascii')+')';
					break;
				default:
					w_keytype = get_words('_wps_key')+'('+get_words('_ascii')+')';
					break;
				}
				break;
			case '2':
//			case '3':
			case '4':
				w_keytype = get_words('_wps_key');
				break;
		}
		$('#WPSKeyType').html(w_keytype);
	}
	function setValueWPS2KeyType(){
		var w_keytype='';
		var val_mode = wlanCfg.security[1];
		switch(val_mode)
		{
			case '0':
				$('#wps2KeyField').hide();
			case '1':
				var val_keytype = wepCfg.keyType[1];
				switch(val_keytype){
				case '0':
					w_keytype = get_words('_wps_key')+'('+get_words('_hex')+')';
					break;
				case '1':
					w_keytype = get_words('_wps_key')+'('+get_words('_ascii')+')';
					break;
				default:
					w_keytype = get_words('_wps_key')+'('+get_words('_ascii')+')';
					break;
				}
				break;
			case '2':
//			case '3':
			case '4':
				w_keytype = get_words('_wps_key');
				break;
		}
		$('#WPS2KeyType').html(w_keytype);
	}
	function setValueWPSKey(){
		var val_key='';
		var val_mode = wlanCfg.security[0];
		switch(val_mode)
		{
			case '1':
				var val_keytype = wepCfg.keyType[0];
				switch(val_keytype){
				case '0':
					val_key = wepCfg.key64[0];
					break;
				case '1':
					val_key = wepCfg.key128[0];
					break;
				default:
					val_key = wepCfg.key128[0];
					break;
				}
				break;
			case '2':
//			case '3':
			case '4':
				val_key = wpaCfg.wpakey[0]
				break;
		}
		$('#WPSKey').html(val_key);
	}
	function setValueWPS2Key(){
		var val_key='';
		var val_mode = wlanCfg.security[1];
		switch(val_mode)
		{
			case '1':
				var val_keytype = wepCfg.keyType[1];
				switch(val_keytype){
				case '0':
					val_key = wepCfg.key64[4];
					break;
				case '1':
					val_key = wepCfg.key128[4];
					break;
				default:
					val_key = wepCfg.key128[4];
					break;
				}
				break;
			case '2':
//			case '3':
			case '4':
				val_key = wpaCfg.wpakey[1]
				break;
		}
		$('#WPS2Key').html(val_key);
	}
	function setValueAPPIN(){
		var val_ping = wpsCfg.selfpin;
		$('#APPIN').html(val_ping);
	}
	function setValueWPSSummary(){
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('get');
		
		obj.add_param_arg('IGD_WLANConfiguration_i_WPS_',1110);
		obj.add_param_arg('IGD_WLANConfiguration_i_',1100);
		obj.add_param_arg('IGD_WLANConfiguration_i_WPS_',1510);
		obj.add_param_arg('IGD_WLANConfiguration_i_',1500);
		
		obj.get_config_obj();

		wpsCfg = {
			'enable':			obj.config_val("wpsCfg_Enable_"),
			'state':			obj.config_val("wpsCfg_Status_"),
			'locked':			obj.config_val("wpsCfg_SetupLock_"),
			'dn':				obj.config_val("wpsCfg_DeviceName_"),
			'password':			obj.config_val("wpsCfg_DevicePassword_"),
			'selfpin':			obj.config_val("wpsCfg_SelfPINNumber_"),
			'config':			obj.config_val("wpsCfg_Configured_"),
			'ssid':				obj.config_val("wpsCfg_SSID_"),
			'secmode':			obj.config_val("wpsCfg_SecurityMode_"),
			'enctype':			obj.config_val("wpsCfg_EncryptType_"),
			'defkeyidx':		obj.config_val("wpsCfg_DefaultKeyIndex_"),
			'keytype':			obj.config_val("wpsCfg_KeyType_"),
			'key':				obj.config_val("wpsCfg_Key_")
		};
		setValueWPSCurrentStatus();
		setValueWPSConfigured();
		
		//2.4GHz
		setValueWPSSSID();
		setValueWPSSecurityMode();
		setValueWPSEncryptType();
		setValueWPSDefaultKeyIndex();
		setValueWPSKeyType();
		setValueWPSKey();
		//5GHz
		setValueWPS2SSID();
		setValueWPS2SecurityMode();
		setValueWPS2EncryptType();
		setValueWPS2DefaultKeyIndex();
		setValueWPS2KeyType();
		setValueWPS2Key();
		
		setValueAPPIN();
		setTimeout("setValueWPSSummary();", 5000);
	}

$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(model);
	if(lanCfg.enable[0]==1 || lanCfg.enable[1]==1)
		$('#radioOnField').show();
	else
		$('#radioOffField').show();
	
	if(lanCfg.enable[0]==1){
		$('#div_wps_24g').show();
	}
	if(lanCfg.enable[1]==1){
		$('#div_wps_5g').show();
	}
	//WPS Config
	setValueWPS();
	setValueWPSExternalRgistrarLock();
	
	//WPS Summary
	setValueWPSSummary();
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
				<script>document.write(build_structure(1,4,-1))</script>
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
									<script>show_words('LW65')</script>
									</div>
									<div class="hr"></div>
									<div class="section_content_border">
									<div class="header_desc" id="basicIntroduction">
										<script>show_words('_desc_wps')</script>
										<p></p>
									</div>

<div id="radioOnField" style="display:none;">
<div class="box_tn">
	<div class="CT"><script>show_words('_wps_config')</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
		<tr>
			<td class="CL"><script>show_words('_WPS');</script></td>
			<td class="CR">
				<select id="WPSEnable" name="WPSEnable" size="1">
					<option value="0"><script>show_words('_disable');</script></option>
					<option value="1"><script>show_words('_enable');</script></option>
				</select>
			</td>
		</tr>
		<tr id="APLock" style="display: none;">
			<td class="CL"><script>show_words('_lb_wps_ext_reg_lock');</script></td>
			<td class="CR">
				<select id="WPSEG" name="WPSEG" size="1"><!-- onchange="checkWPSLock()" -->
					<option value="0"><script>show_words('_disable');</script></option>
					<option value="1"><script>show_words('_enable');</script></option>
				</select>
			</td>
		</tr>
		<tr align="center">
			<td colspan="2" class="btn_field">
			<input type="button" class="button_submit" value="Apply" id="submitWPSEnable" name="submitWPSEnable" onclick="wps_apply();" /></td>
			<script>$('#submitWPSEnable').val(get_words('_apply'));</script>
		</tr>
	</table>
</div>

<div id="div_wps_status" class="box_tn" style="display: none;">
	<div class="CT"><script>show_words('_wps_summary');</script></div>
	<table name="div_wps_status" cellspacing="0" cellpadding="0" class="formarea">
	<!--------------------  WPS Summary  -------------------------- -->
		<tr>
			<td class="CL"><script>show_words('_wps_cur_state');</script></td>
			<td class="CR"><span id="WPSCurrentStatus"></span></td>
			<script>$('#WPSCurrentStatus').html(get_words('psIdle'))</script>
		</tr>
		<tr>
			<td class="CL"><script>show_words('_wps_configed');</script></td>
			<td class="CR"><span id="WPSConfigured"></span></td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('_ap_pin');</script></td>
			<td class="CR"><span id="APPIN"></span></td>
		</tr>
	</table>
<div id="div_wps_24g" style="display: none;">
	<div class="CT"><script>show_words('_wps_24g');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
		<tr>
			<td class="CL"><script>show_words('_wps_ssid');</script></td>
			<td class="CR"><span id="WPSSSID"></span></td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('_wps_sec_mode');</script></td>
			<td class="CR"><span id="WPSAuthMode"></span></td>
		</tr>
		<tr id="wpsEncTypeField">
			<td class="CL"><script>show_words('_wps_enc_type');</script></td>
			<td class="CR"><span id="WPSEncryptype"></span></td>
		</tr>
		<tr id="keyIndexField" style="display: none;">
			<td class="CL"><script>show_words('_wps_def_key_idx');</script></td>
			<td class="CR"><span id="WPSDefaultKeyIndex"></span></td>
		</tr>
		<tr id="wpsKeyField">
			<td class="CL"><span id="WPSKeyType"></span></td>
			<td class="CR"><span id="WPSKey"><br></span></td>
		</tr>
	</table>
</div>

<div id="div_wps_5g" style="display: none;">
	<div class="CT"><script>show_words('_wps_5g');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
		<tr>
			<td class="CL"><script>show_words('_wps_ssid');</script></td>
			<td class="CR"><span id="WPS2SSID"></span></td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('_wps_sec_mode');</script></td>
			<td class="CR"><span id="WPS2AuthMode"></span></td>
		</tr>
		<tr id="wps2EncTypeField">
			<td class="CL"><script>show_words('_wps_enc_type');</script></td>
			<td class="CR"><span id="WPS2Encryptype"></span></td>
		</tr>
		<tr id="keyIndexField2" style="display: none;">
			<td class="CL"><script>show_words('_wps_def_key_idx');</script></td>
			<td class="CR"><span id="WPS2DefaultKeyIndex"></span></td>
		</tr>
		<tr id="wps2KeyField">
			<td class="CL"><span id="WPS2KeyType"></span></td>
			<td class="CR"><span id="WPS2Key"><br></span></td>
		</tr>
	</table>
</div>

<div id="div_wps" class="box_tn" style="display: none;">
	<div class="CT"><script>show_words('_wps_action');</script></div>
	<table name="div_wps" cellspacing="0" cellpadding="0" class="formarea">
	<tr><td colspan="2" class="CELL"><script>show_words('_desc_wps_action');</script></td></tr>
		<tr>
			<td class="CL">PIN</td>
			<td class="CR">
				<input value="" name="PIN" id="PIN" size="8" maxlength="8" type="text" />
				<input type="button" class="button_submit_NoWidth" value="Configure via PIN" id="submitWPS_PIN" name="submitWPS" onclick="ConfigByPIN();" />
				<script>$('#submitWPS_PIN').val(get_words('_config_via_pin'));</script>
			</td>
		</tr>
		<tr>
			<td class="CL">PBC</td>
			<td class="CR">
				<input type="button" class="button_submit_NoWidth" value="Configure via PBC" id="submitWPS_PBC" name="submitWPS" onclick="ConfigByPBC();" />
				<script>$('#submitWPS_PBC').val(get_words('_config_via_pbc'));</script>
			</td>
		</tr>
	</table>
</div>

</div>

<div id="div_11n_plugfest" class="box_tn" style="display:none">
	<div class="CT"><script>show_words('at_Prot_1');</script></div>
	<table name="div_11n_plugfest" cellspacing="0" cellpadding="0" class="formarea">
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