<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<script>
var mywinopen = window.open;
</script>
<title>TRENDNET | modelName | Wireless 5GHz | Advanced</title>
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
	var RF_Domain	= dev_info.domain;
	var submit_c	= "";
	var is_wps;
	
	var main = new ccpObject();
	main.set_param_url('get_set.ccp');
	main.set_ccp_act('get');
	
	main.add_param_arg('IGD_',1000);
	
	for (var i =5; i<= 8; i++)
	{
		main.add_param_arg('IGD_WLANConfiguration_i_','1'+i+'00');
		main.add_param_arg('IGD_WLANConfiguration_i_WEP_','1'+i+'10');
		main.add_param_arg('IGD_WLANConfiguration_i_WEP_WEPKey_i_','1'+i+'10');
		main.add_param_arg('IGD_WLANConfiguration_i_WPS_','1'+i+'10');
		main.add_param_arg('IGD_WLANConfiguration_i_WPA_EAP_i_','1'+i+'10');
		main.add_param_arg('IGD_WLANConfiguration_i_WPA_','1'+i+'10');
		main.add_param_arg('IGD_WLANConfiguration_i_WPA_PSK_','1'+i+'11');
	}
	
	main.get_config_obj();

	var submit_button_flag = 0;
	var radius_button_flag = 0;
	var radius_button_flag_1 = 0;

	var lanCfg = {
		'enable':			main.config_str_multi("wlanCfg_Enable_"),	//gz_enable
		'schedule':			main.config_str_multi("wlanCfg_ScheduleIndex_"),	//gz_schedule
//		'domain':			main.config_str_multi("wlanCfg_RFDomain_"),	Silvia: we do not have
//		'domain_A':			main.config_str_multi("wlanCfg_RFDomain_A_"),	Silvia: we do not have
		'rate':				main.config_str_multi("wlanCfg_TransmitRate_"),
		'ssid':				main.config_str_multi("wlanCfg_SSID_"),
		'autochan':			main.config_str_multi("wlanCfg_AutoChannel_"),
		'channel':			main.config_str_multi("wlanCfg_Channel_"),
		'exchannel':		main.config_str_multi("wlanCfg_ExChannel_"),
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
	
	var qosCfg = {
		'wmmenable':		main.config_str_multi("wlanCfg_WMMEnable_"),
		'apsd':				main.config_str_multi("wlanCfg_APSD_"),
		'dls':				main.config_str_multi("wlanCfg_DLS_")
	}
	var mediaCfg = {
		'turbine':			main.config_str_multi("wlanCfg_VideoTurbine_")
	}
	var m2uCfg = {
		'm2u':				main.config_str_multi("wlanCfg_M2U_")
	}
	var EapCfg ={
		'ip':				main.config_str_multi("wpaEap_RadiusServerIP_"),
		'port':				main.config_str_multi("wpaEap_RadiusServerPort_"),
		'psk':				main.config_str_multi("wpaEap_RadiusServerPSK_"),
		'macauth':			main.config_str_multi("wpaEap_MACAuthentication_")
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
		'key128':			main.config_str_multi("wepKey_KeyHEX128_")
	}

	var htCfg = {
		'operating':		main.config_str_multi("wlanCfg_OperatingMode_"),
		'shortgi':			main.config_str_multi("wlanCfg_ShortGIEnable_"),
		'rdg':				main.config_str_multi("wlanCfg_ReverseDirectionGrant_"),
		'amsdu':			main.config_str_multi("wlanCfg_AMSDU_"),
		'autoba':			main.config_str_multi("wlanCfg_AutoBlockACK_"),
		'declineba':		main.config_str_multi("wlanCfg_DeclineBA_"),
		'ampdu':			main.config_str_multi("wlanCfg_EnableAMPDU_")
	}

	var wpaCfg = {
		'infoAuthMode':		main.config_str_multi("wpaInfo_AuthenticationMode_"),
		'infoKeyup':		main.config_str_multi("wpaInfo_KeyUpdateInterval_"),
		'infoTimeout':		main.config_str_multi("wpaInfo_AuthenticationTimeout_"),
		'infoMode':			main.config_str_multi("wpaInfo_WPAMode_"),
		'encrMode':			main.config_str_multi("wpaInfo_EncryptionMode_"),	//c_type
		'pskKey':			main.config_str_multi("wpaPSK_KeyPassphrase_")
	}

	var wpsEnableSt = (wpsCfg.enable[0]? wpsCfg.enable[0]:"0");
	var wifi_txRate = (lanCfg.rate[0]? lanCfg.rate[0]:"0");

	function open_wmm_window()
	{
		mywinopen("wireless_wmm.asp","WMM_Parameters_List","toolbar=no, location=yes, scrollbars=yes, resizable=no, width=640, height=480");
	}

	function check_value()
	{
		/*
		** Date:	2013-03-18
		** Author:	Moa Chung
		** Reason:	Wireless 5G → advanced：Do not check the value of Beacon interval, DTIM, Fragment threshold and RTS threshold.
		** Note:	TEW-810DR pre-test no.73
		**/
		if(!check_integer($('#beacon').val(), 100, 1000)){
			alert(get_words('YM27'));
			return;
		}
		if(!check_integer($('#dtim').val(), 1, 255)){
			alert(get_words('GW_WLAN_DTIM_INVALID'));
			return;
		}
		if(!check_integer($('#fragment').val(), 256, 2346)){
			alert(get_words('GW_WLAN_FRAGMENT_THRESHOLD_INVALID'));
			return;
		}
		if(!check_integer($('#rts').val(), 1, 2347)){
			alert(get_words('GW_WLAN_RTS_THRESHOLD_INVALID'));
			return;
		}
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('wireless2_advanced.asp');
		
		var arrIns = [5,6,7,8];
		for(var v in arrIns)
		{
			var i = arrIns[v];
			//Advanced Wireless
			obj.add_param_arg('wlanCfg_BGProtection_','1.'+i+'.0.0',$('#bg_protection').val());
			obj.add_param_arg('wlanCfg_BeaconPeriod_','1.'+i+'.0.0',$('#beacon').val());
			obj.add_param_arg('wlanCfg_DTIMInterval_','1.'+i+'.0.0',$('#dtim').val());
			obj.add_param_arg('wlanCfg_FragmentThreshold_','1.'+i+'.0.0',$('#fragment').val());
			obj.add_param_arg('wlanCfg_RTSThreshold_','1.'+i+'.0.0',$('#rts').val());
			obj.add_param_arg('wlanCfg_TransmitPower_','1.'+i+'.0.0',$('#tx_power').val());
			obj.add_param_arg('wlanCfg_ShortPreamble_','1.'+i+'.0.0',$('input[name=short_preamble]:checked').val());
			obj.add_param_arg('wlanCfg_ShortSlot_','1.'+i+'.0.0',$('input[name=short_slot]:checked').val());
			obj.add_param_arg('wlanCfg_TxBurst_','1.'+i+'.0.0',$('input[name=n_tx_burst]:checked').val());
			obj.add_param_arg('wlanCfg_PktAggregate_','1.'+i+'.0.0',$('input[name=pkt_aggregate]:checked').val());
			obj.add_param_arg('wlanCfg_IEEE11H_','1.'+i+'.0.0',$('input[name=ieee_80211h]:checked').val());
			
			//Qos Setting
			obj.add_param_arg('wlanCfg_WMMEnable_','1.'+i+'.0.0',$('input[name=wmm_capable]:checked').val());
			obj.add_param_arg('wlanCfg_APSD_','1.'+i+'.0.0',$('input[name=apsd_capable]:checked').val());
			obj.add_param_arg('wlanCfg_DLS_','1.'+i+'.0.0',$('input[name=dls_capable]:checked').val());
			
			//WiFi Multimedia
			obj.add_param_arg('wlanCfg_VideoTurbine_','1.'+i+'.0.0',$('input[name=wifi_video_turbine]:checked').val());
			
			//Multicast-to-Unicast
			obj.add_param_arg('wlanCfg_M2U_','1.'+i+'.0.0',$('input[name=m2u_enable]:checked').val());
			
			switch(lanCfg.standard5G[0])
			{
				case "0"://n
					obj.add_param_arg('wlanCfg_TransmitRate_','1.'+i+'.0.0',$('#wlan1_11n_txrate').val());
					break;
				case "2"://na
					obj.add_param_arg('wlanCfg_TransmitRate_','1.'+i+'.0.0',$('#wlan1_11na_txrate').val());
					break;
				case "4"://naac
					obj.add_param_arg('wlanCfg_TransmitRate_','1.'+i+'.0.0',$('#wlan1_11acna_txrate').val());
					break;
			}
			
			//HT Physical Mode
			obj.add_param_arg('wlanCfg_BSSCoexistenceEnable_','1.'+i+'.0.0',$('input[name=n_coexistence1]:checked').val());
			obj.add_param_arg('wlanCfg_ShortGIEnable_','1.'+i+'.0.0',$('input[name=n_gi1]:checked').val());
			obj.add_param_arg('wlanCfg_ReverseDirectionGrant_','1.'+i+'.0.0',$('input[name=n_rdg1]:checked').val());
			obj.add_param_arg('wlanCfg_ExChannel_','1.'+i+'.0.0',$('#n_extcha1').val());
			obj.add_param_arg('wlanCfg_AMSDU_','1.'+i+'.0.0',$('input[name=n_amsdu1]:checked').val());
			obj.add_param_arg('wlanCfg_AutoBlockACK_','1.'+i+'.0.0',$('input[name=n_autoba1]:checked').val());
			obj.add_param_arg('wlanCfg_DeclineBA_','1.'+i+'.0.0',$('input[name=n_declineba1]:checked').val());
			obj.add_param_arg('wlanCfg_EnableAMPDU_','1.'+i+'.0.0',$('input[name=n_ampdu1]:checked').val());
			
		}
		
		var paramForm = obj.get_param();
		
		totalWaitTime = 20; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(paramForm.url, paramForm.arg);
	}
	var txrate_11a = new Array('6Mbps', '9Mbps', '12Mbps', '18Mbps', '24Mbps', '36Mbps', '48Mbps', '54Mbps');
	//2013.07.15 kevin say 2T2R should to MCS15
	var txrate_11n = new Array('MCS0: 6.5M(13.5M)', 'MCS1: 13M(27M)', 'MCS2: 19.5M(40.5M)', 'MCS3: 26M(54M)', 'MCS4: 39M(81M)', 'MCS5: 52M(108M)', 'MCS6: 58.5M(121.5M)', 'MCS7: 65M(135M)', 'MCS8: 13M(27M)', 'MCS9: 26M(54M)', 'MCS10: 39M(81M)', 'MCS11: 52M(108M)', 'MCS12: 78M(162M)', 'MCS13: 104M(216M)', 'MCS14: 117M(243M)', 'MCS15: 130M(270M)');//, 'MCS16: 19.5M(40.5M)', 'MCS17: 39M(81M)', 'MCS18: 58.5M(121.5M)', 'MCS19: 78M(162M)', 'MCS20: 117M(243M)', 'MCS21: 156M(324M)', 'MCS22: 175.5M(364.5M)', 'MCS23: 195M(405M)','MCS32: 6M'
	var txrate_11ac = new Array('MCS0: 6.5M(13.5M)', 'MCS1: 13M(27M)', 'MCS2: 19.5M(40.5M)', 'MCS3: 26M(54M)', 'MCS4: 39M(81M)', 'MCS5: 52M(108M)', 'MCS6: 58.5M(121.5M)', 'MCS7: 65M(135M)', 'MCS8: 13M(27M)', 'MCS9: 26M(54M)', 'MCS10: 39M(81M)', 'MCS11: 52M(108M)', 'MCS12: 78M(162M)', 'MCS13: 104M(216M)', 'MCS14: 117M(243M)', 'MCS15: 130M(270M)');
	var txrate_11a_value = new Array(33,32,31,30,29,28,27,26);
	var txrate_11n_value = new Array(25,24,23,22,21,20,19,18,17,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1);
	var txrate_11ac_value = new Array(25,24,23,22,21,20,19,18,17,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1);
	function set_11a_txrate(obj){
		for(var i = 0; i < txrate_11a.length; i++){
			var ooption = document.createElement("option");
			
			obj.options[i+1] = null;
			ooption.text = txrate_11a[i];
			ooption.value = txrate_11a_value[i];
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
function setValueBGProtectionMode() {
//	$('#tr_bg_protection').show();
	var sel_bgpro = advCfg.bgprotection[0];
	$('#bg_protection').val(sel_bgpro);
}
function setValueBeaconInterval() {
	var val_bi = advCfg.beaconperiod[0];
	$('#beacon').val(val_bi);
}
function setValueDTIM() {
	var val_dtim = advCfg.dtim[0];
	$('#dtim').val(val_dtim);
}
function setValueFragThres() {
	var val_frag = advCfg.fragthres[0];
	$('#fragment').val(val_frag);
}
function setValueRTSThres() {
	var val_rts = advCfg.rtsthres[0];
	$('#rts').val(val_rts);
}
function setValueTXPower() {
	var sel_txpower = advCfg.txpower[0];
	$('#tx_power').val(sel_txpower);
}
function setValueShortPreamble() {
	var chk_sp = advCfg.spreamble[0];
	$('input[name=short_preamble][value='+chk_sp+']').attr('checked', true);
}
function setValueShortSlot() {
	var chk_ss = advCfg.sslot[0];
	$('input[name=short_slot][value='+chk_ss+']').attr('checked', true);
}
function setValueTxBurst() {
//	$('#tx_burst').show();
	var chk_txburst = advCfg.txburst[0];
	$('input[name=n_tx_burst][value='+chk_txburst+']').attr('checked', true);
}
function setValuePktAggregate() {
//	$('#pkt_aggr').show();
	var chk_pkt = advCfg.pktaggregate[0];
	$('input[name=pkt_aggregate][value='+chk_pkt+']').attr('checked', true);
}
function setValue80211HSupport() {
//	$('#11hsupport').show();
	var chk_11h = advCfg.ieee11h[0];
	$('input[name=ieee_80211h][value='+chk_11h+']').attr('checked', true);
}
function setValueCountryCode() {
//	$('#country').show();
	var sel_country = RF_Domain;
	$('#country_code').val(sel_country).attr('disabled','disabled');
}
function setValueWMMCapable() {
//	$('#div_wmm_capable').show();
	var chk_wmm = qosCfg.wmmenable[0];
	$('input[name=wmm_capable][value='+chk_wmm+']').attr('checked', true);
}
function setValueAPSDCapable() {
//	$('#div_apsd_capable').show();
	var chk_apsd = qosCfg.apsd[0];
	$('input[name=apsd_capable][value='+chk_apsd+']').attr('checked', true);
}
function setValueDLSCapable() {
//	$('#div_dls_capable').show();
	var chk_dls = qosCfg.dls[0];
	$('input[name=dls_capable][value='+chk_dls+']').attr('checked', true);
}
function setValueVideoTurbine() {
	var chk_turbine = mediaCfg.turbine[0];
	$('input[name=wifi_video_turbine][value='+chk_turbine+']').attr('checked', true);
}
function setValueM2UConverter() {
	var chk_m2u = m2uCfg.m2u[0];
	$('input[name=m2u_enable][value='+chk_m2u+']').attr('checked', true);
}
	function setValue2040Coexistence(){
//		$('#2040coexi1').show();
		var chk_coexi = lanCfg.coexi[0];
		$('input[name=n_coexistence1][value='+chk_coexi+']').attr('checked', true);
	}
	function setValueGuardInterval(){
		var chk_gi = htCfg.shortgi[0];
		$('input[name=n_gi1][value='+chk_gi+']').attr('checked', true);
	}
	function setValueReverseDirectionGrant(){
//		$('#rdg1').show();
		var chk_rdg = htCfg.rdg[0];
		$('input[name=n_rdg1][value='+chk_rdg+']').attr('checked', true);
	}
	function setValueAggregationMSDU(){
//		$('#amsdu1').show();
		var chk_amsdu = htCfg.amsdu[0];
		$('input[name=n_amsdu1][value='+chk_amsdu+']').attr('checked', true);
	}
	function setValueAutoBlockACK(){
//		$('#autoba1').show();
		var chk_autoba = htCfg.autoba[0];
		$('input[name=n_autoba1][value='+chk_autoba+']').attr('checked', true);
	}
	function setValueDeclineBARequest(){
//		$('#declineba1').show();
		var chk_declineba = htCfg.declineba[0];
		$('input[name=n_declineba1][value='+chk_declineba+']').attr('checked', true);
	}
	function setValueAggregationMPDU(){
//		$('#ampdu1').show();
		var chk_ampdu = htCfg.ampdu[0];
		$('input[name=n_ampdu1][value='+chk_ampdu+']').attr('checked', true);
	}
	function setValueMCS(){
		switch(lanCfg.standard5G[0]){
		case '0'://n
			$('#wlan1_11n_txrate').val(lanCfg.rate[0]);
			break;
		case '1'://a
			$('#wlan1_11a_txrate').val(lanCfg.rate[0]);
			break;
		case '2'://na
			$('#wlan1_11na_txrate').val(lanCfg.rate[0]);
			break;
		case '4'://naac
			$('#wlan1_11acna_txrate').val(lanCfg.rate[0]);
			break;
		case '5'://nac
			$('#wlan1_11acn_txrate').val(lanCfg.rate[0]);
			break;
		case '3'://ac
			$('#wlan1_11ac_txrate').val(lanCfg.rate[0]);
			break;
		}
	}
	function setValueExtensionChannel(){
		var val_channel = lanCfg.channel[0];
		var freqMap = {'0':'0','36':'40','40':'36','44':'48','48':'44','52':'56','56':'52','60':'64','64':'60','149':'153','153':'149','157':'161','161':'157','165':'0'};
		var ch_text = {'0':get_words('_sel_autoselect'),'36':"5180",'40':"5200",'44':"5220",'48':"5240",'52':"5260",'56':"5280",'60':"5300",'64':"5320",'149':"5745",'153':"5765",'157':"5785",'161':"5805",'165':"5825"};
		
		$('#n_extcha1').children().remove();
		var channel_i = freqMap[val_channel];
		
		var opt = $('<option/>');
		opt.text(channel_i==0?ch_text[channel_i]:ch_text[channel_i] + "MHz (Channel " + channel_i+")");
		opt.val(channel_i);
		if(lanCfg.exchannel[0]==channel_i)
			opt.attr('selected', true);//這裡之後要改為多判斷datamodel的值
		$('#n_extcha1').append(opt);
		
		if(val_channel=='0')
		{
			$('#n_extcha1').attr('disabled','disabled');
		}
		else if(lanCfg.chanwidth[0]==1)
		{
			$('#n_extcha1').attr('disabled','');
		}
	}
function setVisibleHTPhysicalMode()
{
	var val_wmode = lanCfg.standard5G[0];
	switch(val_wmode){
	case '1'://a
		$('#show_11a_txrate1').show();
		$('#div_11n1').hide();
		break;
	case '0'://n
		$('#show_11n_txrate1').show();
		$('#div_11n1').show();
		break;
	case '2'://na
		$('#show_11na_txrate1').show();
		$('#div_11n1').show();
		break;
	case '4'://naac
		$('#show_11acna_txrate1').show();
		$('#div_11n1').show();
		break;
	default:
		alert('error: '+val_wmode);
	}

}
$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(model);
	if(lanCfg.enable[0]==1)
		$('#radioOnField').show();
	else
		$('#radioOffField').show();
	//Advanced Wireless
	setValueBGProtectionMode();
	setValueBeaconInterval();
	setValueDTIM();
	setValueFragThres();
	setValueRTSThres();
	setValueTXPower();
	setValueShortPreamble();
	setValueShortSlot();
	setValueTxBurst();
	setValuePktAggregate();
	setValue80211HSupport();
	setValueCountryCode();
	
	//HT Physical Mode
	setVisibleHTPhysicalMode()
	setValue2040Coexistence();
	setValueGuardInterval();
	//Extension Channel is in setEventFrequency();
	setValueReverseDirectionGrant();
	setValueAggregationMSDU();
	setValueAutoBlockACK();
	setValueDeclineBARequest();
	setValueAggregationMPDU();
	setValueMCS();
	setValueExtensionChannel();
	
	//Qos Setting
	//$('#qos_setting').show();//close
	setValueWMMCapable();
	setValueAPSDCapable();
	setValueDLSCapable();
	//$('#wmm_param').show();//close
	//setValueWMMParameters();//close
	
	//Wi-Fi Multimedia
	//$('#wifi_multimedia').show();//close
	setValueVideoTurbine();
	
	//Multicast-to-Unicast Converter
	setValueM2UConverter();
	
//	setValue();
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
				<script>document.write(menu.build_structure(1,3,1))</script>
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
								<div class="headerbg" id="advancedTitle">
								<script>show_words('aw_title_2')</script>
								</div>
								<div class="hr"></div>
								<div class="section_content_border">
								<div class="header_desc" id="advancedIntroduction">
									<script>show_words('_desc_advanced')</script>
									<p></p>
								</div>
<div id="radioOnField" style="display: none;">
<form method="post" name="wireless_basic" action="/goform/wirelessBasic" onsubmit="return CheckValue();">
	<div class="box_tn">
		<div class="CT"><script>show_words('_advwls')</script></div>
		<table cellspacing="0" cellpadding="0" class="formarea">
			<tr id="tr_bg_protection" style="display:none"> 
				<td class="CL"><script>show_words('_lb_bg_protection');</script></td>
				<td class="CR">
					<select id="bg_protection" name="bg_protection" size="1">
						<option value="0"><script>show_words('_auto');</script></option>
						<option value="1"><script>show_words('_on');</script></option>
						<option value="2"><script>show_words('_off');</script></option>
					</select>
				</td>
			</tr>
			
			<tr> 
				<td class="CL"><script>show_words('aw_BP');</script></td>
				<td class="CR">
					<input type="text" id="beacon" name="beacon" size="5" maxlength="4" value="" /> ms <font color="#808080"><script>show_words('_hint_beacon');</script></font>
				</td>
			</tr>
			<tr> 
				<td class="CL"><script>show_words('_help_txt164');</script></td>
				<td class="CR">
					<input type="text" id="dtim" name="dtim" size="5" maxlength="3" value="" /><font color="#808080"><script>show_words('_hint_dtim');</script></font>
				</td>
			</tr>
			<tr> 
				<td class="CL"><script>show_words('_lb_frag_thres');</script></td>
				<td class="CR">
					<input type="text" id="fragment" name="fragment" size="5" maxlength="4" value="" /> <font color="#808080"><script>show_words('_hint_frag_thres');</script></font>
				</td>
			</tr>
			<tr> 
				<td class="CL"><script>show_words('aw_RT');</script></td>
				<td class="CR">
					<input type="text" id="rts" name="rts" size="5" maxlength="4" value="" /> <font color="#808080"><script>show_words('_hint_rts_thres');</script></font>
				</td>
			</tr>
			<tr>
				<td class="CL"><script>show_words('_lb_txpower');</script></td>
				<td class="CR">
					<!--<input type=text name=tx_power size=5 maxlength=3 value="100" /> <font color="#808080">(range 1 - 100, default 100)</font>-->
					<select id="tx_power" name="tx_power">
					<option value="0" selected=""><script>show_words('_pwr_full');</script></option>
					<option value="1"><script>show_words('_pwr_half');</script></option>
					<option value="2"><script>show_words('_pwr_low');</script></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="CL"><script>show_words('_lb_short_preamble');</script></td>
				<td class="CR">
					<input type="radio" name="short_preamble" value="1" /><script>show_words('_enable');</script> &nbsp;
					<input type="radio" name="short_preamble" value="0" /><script>show_words('_disable');</script>
				</td>
			</tr>
			<tr> 
				<td class="CL"><script>show_words('_lb_short_slot');</script></td>
				<td class="CR">
					<input type="radio" name="short_slot" value="1" disabled /><script>show_words('_enable');</script> &nbsp;
					<input type="radio" name="short_slot" value="0" disabled /><script>show_words('_disable');</script>
				</td>
			</tr>
			<tr id="tx_burst" style="display:none"> 
				<td class="CL"><script>show_words('_lb_tx_burst');</script></td>
				<td class="CR">
					<input type="radio" name="n_tx_burst" value="1" /><script>show_words('_enable');</script> &nbsp;
					<input type="radio" name="n_tx_burst" value="0" /><script>show_words('_disable');</script>
				</td>
			</tr>
			<tr id="pkt_aggr" style="display:none"> 
				<td class="CL"><script>show_words('_lb_pkt_aggregate');</script></td>
				<td class="CR">
					<input type="radio" name="pkt_aggregate" value="1" /><script>show_words('_enable');</script> &nbsp;
					<input type="radio" name="pkt_aggregate" value="0" /><script>show_words('_disable');</script>
				</td>
			</tr>
			<tr id="11hsupport" style="display:none"> 
				<td class="CL"><script>show_words('_lb_80211h_support');</script></td>
				<td class="CR">
					<input type="radio" name="ieee_80211h" value="1" /><script>show_words('_enable');</script> &nbsp;
					<input type="radio" name="ieee_80211h" value="0" /><script>show_words('_disable');</script> <font color="#808080">(<script>show_words('_hint_only_a_band');</script>)</font>
				</td>
			</tr>
			<tr id="country" style="display:none"> 
				<td class="CL"><script>show_words('_lb_country_code');</script></td>
				<td class="CR">
					<select id="country_code" name="country_code">
						<option value="US/NA">US (United States)</option>
						<option value="JP">JP (Japan)</option>
						<option value="FR">FR (France)</option>
						<option value="TW">TW (Taiwan)</option>
						<option value="IE">IE (Ireland)</option>
						<option value="HK">HK (Hong Kong)</option>
						<option value="" selected="">NONE</option>
					</select>
				</td>
			</tr>
		</table>
	</div>

<div id="div_11n1" class="box_tn" style="display:none;">
	<div class="CT"><script>show_words('_help_txt144')</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
		<tr id="2040coexi1" style="display:none">
			<td class="CL"><script>show_words('_lb_coexistence');</script></td>
			<td class="CR">
				<input type="radio" name="n_coexistence1" value="0" /><script>show_words('_disable')</script>
				<input type="radio" name="n_coexistence1" value="1" /><script>show_words('_enable')</script>
			</td>
		</tr>
		<tr id="guard_interval1">
			<td class="CL"><script>show_words('_help_txt155')</script></td>
			<td class="CR">
				<input type="radio" name="n_gi1" value="0" /><script>show_words('_long');</script>&nbsp;
				<input type="radio" name="n_gi1" value="1" /><script>show_words('KR50');</script>
			</td>
		</tr>
		<!-- 11n txrate -->
		<tr id="show_11n_txrate1" style="display:none">
			<td class="CL"><script>show_words('_lb_mcs')</script></td>
			<td class="CR">
				<select id="wlan1_11n_txrate" name="wlan1_11n_txrate">
					<option value="0" selected><script>show_words('KR50')</script></option>
					<script>set_11n_txrate(get_by_id("wlan1_11n_txrate"));</script>
				</select>
			</td>
		</tr>
		<!-- 11n/a txrate -->
		<tr id="show_11na_txrate1" style="display:none;">
			<td class="CL"><script>show_words('_lb_mcs')</script></td>
			<td class="CR">
				<select id="wlan1_11na_txrate" name="wlan1_11na_txrate">
					<option value="0" selected><script>show_words('KR50')</script></option>
					<script>set_11na_txrate(get_by_id("wlan1_11na_txrate"));</script>
				</select>
			</td>
		</tr>
		<!-- 11ac/n/a txrate -->
		<tr id="show_11acna_txrate1" style="display:none;">
			<td class="CL"><script>show_words('_lb_mcs')</script></td>
			<td class="CR">
				<select id="wlan1_11acna_txrate" name="wlan1_11acna_txrate">
					<option value="0" selected><script>show_words('KR50')</script></option>
					<script>set_11acna_txrate(get_by_id("wlan1_11acna_txrate"));</script>
				</select>
			</td>
		</tr>
		<tr id="rdg1" style="display:none;">
			<td class="CL"><script>show_words('_lb_rdg');</script></td>
			<td class="CR">
				<input type="radio" name="n_rdg1" value="0" /><script>show_words('_disable');</script>&nbsp;
				<input type="radio" name="n_rdg1" value="1" /><script>show_words('_enable');</script>
			</td>
		</tr>
		<tr id="ext_channel1">
			<td class="CL"><script>show_words('_lb_exten_channel');</script></td>
			<td class="CR">
				<select id="n_extcha1" name="n_extcha1" size="1" disabled="">
					<option value="0" selected=""><script>show_words('_sel_autoselect');</script></option>
				</select>
			</td>
		</tr>
		<tr id="amsdu1" style="display:none">
			<td class="CL"><script>show_words('_lb_a_msdu');</script></td>
			<td class="CR">
				<input type="radio" name="n_amsdu1" value="0" /><script>show_words('_disable');</script>&nbsp;
				<input type="radio" name="n_amsdu1" value="1" /><script>show_words('_enable');</script>
			</td>
		</tr>
		<tr id="autoba1" style="display:none">
			<td class="CL"><script>show_words('_lb_autoba');</script></td>
			<td class="CR">
				<input type="radio" name="n_autoba1" value="0" /><script>show_words('_disable');</script>&nbsp;
				<input type="radio" name="n_autoba1" value="1" /><script>show_words('_enable');</script>
			</td>
		</tr>
		<tr id="declineba1" style="display:none">
			<td class="CL"><script>show_words('_lb_declineba');</script></td>
			<td class="CR">
				<input type="radio" name="n_declineba1" value="0" /><script>show_words('_disable');</script>&nbsp;
				<input type="radio" name="n_declineba1" value="1" /><script>show_words('_enable');</script>
			</td>
		</tr>
		<tr id="ampdu1">
			<td class="CL"><script>show_words('_lb_a_mpdu');</script></td>
			<td class="CR">
				<input type="radio" name="n_ampdu1" value="1" /><script>show_words('_enable');</script>&nbsp;
				<input type="radio" name="n_ampdu1" value="0" /><script>show_words('_disable');</script>
			</td>
		</tr>
	</table>
</div>

	<div id="qos_setting" class="box_tn" style="display:none"><!--  -->
		<div class="CT"><script>show_words('_bx_advanced_2');</script></div>
		<table cellspacing="0" cellpadding="0" class="formarea">
			<tr id="div_wmm_capable"> 
				<td class="CL"><script>show_words('_lb_wmm_capable');</script></td>
				<td class="CR">
					<input type="radio" name="wmm_capable" value="1" /><script>show_words('_enable');</script> &nbsp;
					<input type="radio" name="wmm_capable" value="0" /><script>show_words('_disable');</script>
				</td>
			</tr>
			<tr id="div_apsd_capable" name="div_apsd_capable" style="display: none;">
				<td class="CL"><script>show_words('_lb_apsd_capable');</script></td>
				<td class="CR">
					<input type="radio" name="apsd_capable" value="1" /><script>show_words('_enable');</script> &nbsp;
					<input type="radio" name="apsd_capable" value="0" /><script>show_words('_disable');</script>
				</td>
			</tr>
			<tr id="div_dls_capable" name="div_dls_capable">
				<td class="CL"><script>show_words('_lb_dls_capable');</script></td>
				<td class="CR">
					<input type="radio" name="dls_capable" value="1" /><script>show_words('_enable');</script> &nbsp;
					<input type="radio" name="dls_capable" value="0" /><script>show_words('_disable');</script>
				</td>
			</tr>
		<!-- -->
			<tr id="wmm_param" style="display:none"> 
				<td class="CL"><script>show_words('_lb_wmm_param');</script></td>
				<td class="CR">
					<input type="button" id="wmm_list" name="wmm_list" value="" onclick="open_wmm_window()" />
					<script>$('#wmm_list').val(get_words('_lb_wmm_config'));</script>
				</td>
			</tr>
			<input type="hidden" name="rebootAP" value="0" />
		</table>
	</div>

	<div class="box_tn" id="wifi_multimedia" style="display:none">
		<div class="CT"><script>show_words('_bx_advanced_3');</script></div>
		<table name="div_wfvt" cellspacing="0" cellpadding="0" class="formarea">
		<tr> 
			<td class="CL"><script>show_words('_lb_video_turbine');</script></td>
			<td class="CR">
				<input type="radio" name="wifi_video_turbine" value="1" /><script>show_words('_enable');</script> &nbsp;
				<input type="radio" name="wifi_video_turbine" value="0" /><script>show_words('_disable');</script>
				<input type="radio" name="wifi_video_turbine" value="2" /><script>show_words('_auto');</script>
			</td>
		</tr>
		</table>
	</div>

	<div id="div_m2u" class="box_tn">
		<div class="CT"><script>show_words('_bx_advanced_4')</script></div>
		<table cellspacing="0" cellpadding="0" class="formarea">
			<tr> 
				<td class="CL"><script>show_words('_lb_multi_uni');</script></td>
				<td class="CR">
					<input type="radio" name="m2u_enable" value="1" /><script>show_words('_enable');</script> &nbsp;
					<input type="radio" name="m2u_enable" value="0" /><script>show_words('_disable');</script>
				</td>
			</tr>
		</table>
	</div>
	
	<div id="buttonField" class="box_tn">
		<table cellspacing="0" cellpadding="0" class="formarea">
			<tr align="center">
				<td colspan="2" class="btn_field">
					<input type="button" class="button_submit" id="btn_apply" value="Apply" onclick="check_value();" />
					<script>$('#btn_apply').val(get_words('_apply'));</script>
					<input type="reset" class="button_submit" id="btn_cancel" value="Cancel" onclick="window.location.reload()" />
					<script>$('#btn_cancel').val(get_words('_cancel'));</script>
				</td>
			</tr>
		</table>
	</div>
</form>
</div>

<div id="radioOffField" class="box_tn" style="display: none;">
	<table cellspacing="0" cellpadding="0" class="formarea">
		<tr>
			<td colspan="2" align="center" class="CELL"><font color="red" id="Msg" name="Msg"><script>show_words('_MSG_woff');</script></font></td>
		</tr>
	</table>
</div>

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