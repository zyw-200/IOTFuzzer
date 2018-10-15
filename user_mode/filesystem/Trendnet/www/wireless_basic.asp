<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | Wireless 2.4GHz | Basic</title>
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
	var cli_mac 	= dev_info.cli_mac;
	var RF_Domain 	= dev_info.domain;
	var ch2_lst 	= dev_info.ch2_lst;
	var ch5_lst 	= dev_info.ch5_lst;
	var submit_c	= "";
	var is_wps;//wps enable or not
	
	var main = new ccpObject();
	main.set_param_url('get_set.ccp');
	main.set_ccp_act('get');
	
	main.add_param_arg('IGD_',1000);
	
	for (i=1;i<=4;i++){
		main.add_param_arg('IGD_WLANConfiguration_i_','1'+i+'00');
		main.add_param_arg('IGD_WLANConfiguration_i_WPS_','1'+i+'10');
		main.add_param_arg('IGD_WLANConfiguration_i_BridgeSetting_','1'+i+'00');
		main.add_param_arg('IGD_WLANConfiguration_i_BridgeSetting_RemoteMAC_i_','1'+i+'10');
	}
	
	main.get_config_obj();

	var lanCfg = {
		'enable':			main.config_str_multi("wlanCfg_Enable_"),	//gz_enable
		'schedule':			main.config_str_multi("wlanCfg_ScheduleIndex_"),	//gz_schedule
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

	var wdsCfg = {
		'apmac':			main.config_str_multi("wlanRmMac_MACAddress_"),
		'encryptype':		main.config_str_multi("wlanBdg_SecurityMode_"),
		'keytype':			main.config_str_multi("wlanBdg_KeyType_"),
		'wepkey':			main.config_str_multi("wlanBdg_WEPKey_"),
		'wpakey':			main.config_str_multi("wlanBdg_WPAPassphrase_")
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

	var wifi_txRate = (lanCfg.rate[0]? lanCfg.rate[0]:"0");

	function check_value()
	{
		is_wps = wpsCfg.enable[0];
		var ssid_vs = $('input[name=wlan0_ssid_broadcast]:checked').val();
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
		return true;
	}

	function check_apply()
	{
		if(check_value())
		{
			var obj = new ccpObject();
			obj.set_param_url('get_set.ccp');
			obj.set_ccp_act('set');
			obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
			obj.set_param_next_page('wireless_basic.asp');
			//Wireless Network
			switch($('#dot11_mode').val())
			{
				case 'bg':
					obj.add_param_arg('wlanCfg_Standard_','1.1.0.0','3');
					obj.add_param_arg('wlanCfg_TransmitRate_','1.1.0.0',$('#wlan0_11bg_txrate').val());
					break;
				case "n":
					obj.add_param_arg('wlanCfg_Standard_','1.1.0.0','2');
					obj.add_param_arg('wlanCfg_TransmitRate_','1.1.0.0',$('#wlan0_11n_txrate').val());
					break;
				case "bgn":
					obj.add_param_arg('wlanCfg_Standard_','1.1.0.0','5');
					obj.add_param_arg('wlanCfg_TransmitRate_','1.1.0.0',$('#wlan0_11bgn_txrate').val());
					break;
			}

			obj.add_param_arg('wlanCfg_SSID_','1.1.0.0',urlencode($('#show_ssid_0').val()));
			obj.add_param_arg('wlanCfg_SSID_','1.2.0.0',urlencode($('#show_ssid_1').val()));
			if($('#show_ssid_1').val()=='')
				obj.add_param_arg('wlanCfg_SSID_','1.2.0.0','0');
			else
				obj.add_param_arg('wlanCfg_SSID_','1.2.0.0','1');

			obj.add_param_arg('wlanCfg_SSID_','1.3.0.0',urlencode($('#show_ssid_2').val()));
			if($('#show_ssid_2').val()=='')
				obj.add_param_arg('wlanCfg_SSID_','1.3.0.0','0');
			else
				obj.add_param_arg('wlanCfg_SSID_','1.3.0.0','1');

			obj.add_param_arg('wlanCfg_SSID_','1.4.0.0',urlencode($('#show_ssid_3').val()));
			if($('#show_ssid_3').val()=='')
				obj.add_param_arg('wlanCfg_SSID_','1.4.0.0','0');
			else
				obj.add_param_arg('wlanCfg_SSID_','1.4.0.0','1');

			obj.add_param_arg('wlanCfg_BeaconAdvertisementEnabled_','1.1.0.0',$('input[name=wlan0_ssid_broadcast]:checked').val());
			obj.add_param_arg('wlanCfg_AutoChannel_','1.1.0.0',(($('#sel_wlan0_channel').val()==0)?"1":"0"));
			obj.add_param_arg('wlanCfg_Channel_','1.1.0.0',$('#sel_wlan0_channel').val());
			
			//Wireless Distribution System(WDS)
			var wdsEnable = $('#wds_mode').val();
			obj.add_param_arg('wlanCfg_WDSEnable_','1.1.0.0',wdsEnable);
			obj.add_param_arg('wlanCfg_WDSEnable_','1.2.0.0',wdsEnable);
			obj.add_param_arg('wlanCfg_WDSEnable_','1.3.0.0',wdsEnable);
			obj.add_param_arg('wlanCfg_WDSEnable_','1.4.0.0',wdsEnable);

			obj.add_param_arg('wlanRmMac_MACAddress_','1.1.1.1',$('#wds_mac_1').val());
			obj.add_param_arg('wlanRmMac_MACAddress_','1.1.1.2',$('#wds_mac_2').val());
			obj.add_param_arg('wlanRmMac_MACAddress_','1.1.1.3',$('#wds_mac_3').val());
			obj.add_param_arg('wlanRmMac_MACAddress_','1.1.1.4',$('#wds_mac_4').val());
			
			//HT Physical Mode
			obj.add_param_arg('wlanCfg_OperatingMode_','1.1.0.0',$('input[name=n_opmode]:checked').val());
			obj.add_param_arg('wlanCfg_ChannelWidth_','1.1.0.0',$('input[name=n_bandwidth]:checked').val());
			obj.add_param_arg('wlanCfg_BSSCoexistenceEnable_','1.1.0.0',$('input[name=n_coexistence]:checked').val());
			obj.add_param_arg('wlanCfg_ShortGIEnable_','1.1.0.0',$('input[name=n_gi]:checked').val());
			obj.add_param_arg('wlanCfg_ReverseDirectionGrant_','1.1.0.0',$('input[name=n_rdg]:checked').val());
			obj.add_param_arg('wlanCfg_ExChannel_','1.1.0.0',$('#n_extcha').val());
			obj.add_param_arg('wlanCfg_AMSDU_','1.1.0.0',$('input[name=n_amsdu]:checked').val());
			obj.add_param_arg('wlanCfg_AutoBlockACK_','1.1.0.0',$('input[name=n_autoba]:checked').val());
			obj.add_param_arg('wlanCfg_DeclineBA_','1.1.0.0',$('input[name=n_declineba]:checked').val());
			
			//Other
			obj.add_param_arg('wlanCfg_TxStream_','1.1.0.0',$('#tx_stream').val());
			obj.add_param_arg('wlanCfg_RxStream_','1.2.0.0',$('#rx_stream').val());
			
			/* other setting(not in page) */
			obj.add_param_arg('wpsCfg_Enable_','1.1.1.0',is_wps);
			
			var paramForm = obj.get_param();
			
			totalWaitTime = 30; //second
			redirectURL = location.pathname;
			wait_page();
			jq_ajax_post(paramForm.url, paramForm.arg);
		}
	}

//	var txrate_11b = new Array('11Mbps', '5.5Mbps', '2Mbps', '1Mbps');
//	var txrate_11g = new Array('54Mbps', '48Mbps', '36Mbps', '24Mbps', '18Mbps', '12Mbps', '9Mbps', '6Mbps');
//	var txrate_11n = new Array('MCS15: 130M(270M)', 'MCS14: 117M(243M)', 'MCS13: 104M(216M)', 'MCS12: 78M(162M)', 'MCS11: 52M(108M)', 'MCS10: 39M(81M)', 'MCS9: 26M(54M)', 'MCS8: 13M(27M)', 'MCS7: 65M(135M)', 'MCS6: 58.5M(121.5M)', 'MCS5: 52M(108M)', 'MCS4: 39M(81M)', 'MCS3: 26M(54M)', 'MCS2: 19.5M(40.5M)', 'MCS1: 13M(27M)', 'MCS0: 6.5M(13.5M)');
//	var txrate_11b_value = new Array(25,26,27,28);
//	var txrate_11g_value = new Array(17,18,19,20,21,22,23,24);
//	var txrate_11n_value = new Array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16);
	var txrate_11b = new Array('1Mbps', '2Mbps', '5.5Mbps', '11Mbps');
	var txrate_11g = new Array('6Mbps', '9Mbps', '12Mbps', '18Mbps', '24Mbps', '36Mbps', '48Mbps', '54Mbps');
	//2013.01.25 brian say not support MCS>15
	var txrate_11n = new Array('MCS0: 6.5M(13.5M)', 'MCS1: 13M(27M)', 'MCS2: 19.5M(40.5M)', 'MCS3: 26M(54M)', 'MCS4: 39M(81M)', 'MCS5: 52M(108M)', 'MCS6: 58.5M(121.5M)', 'MCS7: 65M(135M)', 'MCS8: 13M(27M)', 'MCS9: 26M(54M)', 'MCS10: 39M(81M)', 'MCS11: 52M(108M)', 'MCS12: 78M(162M)', 'MCS13: 104M(216M)', 'MCS14: 117M(243M)', 'MCS15: 130M(270M)');//, 'MCS16: 19.5M(40.5M)', 'MCS17: 39M(81M)', 'MCS18: 58.5M(121.5M)', 'MCS19: 78M(162M)', 'MCS20: 117M(243M)', 'MCS21: 156M(324M)', 'MCS22: 175.5M(364.5M)', 'MCS23: 195M(405M)','MCS32: 6M'
	var txrate_11b_value = new Array(37,36,35,34);
	var txrate_11g_value = new Array(33,32,31,30,29,28,27,26);
	var txrate_11n_value = new Array(25,24,23,22,21,20,19,18,17,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1);
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
		
		obj.get_config_obj();
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

	function setValueWirelessName()
	{
		$('#show_ssid_0').val(lanCfg.ssid[0]);
		$('#show_ssid_1').val(lanCfg.ssid[1]);
		$('#show_ssid_2').val(lanCfg.ssid[2]);
		$('#show_ssid_3').val(lanCfg.ssid[3]);
	}

	function setValueBoardcastSSID()
	{
		var chk_beacon = lanCfg.beaconEnab[0];
		$('input[name=wlan0_ssid_broadcast][value='+chk_beacon+']').attr('checked', true);
	}

	function setValueFrequency()
	{
		if (ch2_lst == "UNKNOWN")
			alert(get_words('HWerr'));
		
		var current_channel =lanCfg.channel[0];
		var ch = ('0, '+ch2_lst).split(", ");
		var obj = $('#sel_wlan0_channel');
		var count = 0;
		for (var i = 0; i < ch.length; i++){
			var opt = $('<option/>');
			var g = parseInt(ch[i], 10)*5+2407+'';
			opt.text(ch[i]=='0'?get_words("_sel_autoselect"):''.concat(g, ' MHz (Channel ', ch[i], ')'));
			opt.val(ch[i]);
			obj.append(opt);
			if (ch[i] == current_channel && lanCfg.autochan[0]==0){
				opt.attr('selected', true);
			}
			else if(ch[i] == 0 && lanCfg.autochan[0]==1)
			{
				opt.attr('selected', true);
			}
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
				$('#div_11n').hide();
				break;
			case 'g':
				$('#show_11g_txrate').show();
				$('#div_11n').hide();
				break;
			case 'n':
				$('#show_11n_txrate').show();
				$('#div_11n').show();
				break;
			case 'bgn':
				$('#show_11bgn_txrate').show();
				$('#div_11n').show();
				break;
			case 'bg':
				$('#show_11bg_txrate').show();
				$('#div_11n').hide();
				break;
			case 'ng':
				$('#show_11gn_txrate').show();
				$('#div_11n').show();
				break;
			default:
				alert('error: '+sel_wmode);
			}
		};
		func();
		$('#dot11_mode').change(func);
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

	function setValueWDS()
	{
		var sel_wds = lanCfg.wdsenable[0];
		$('#wds_mode').val(sel_wds);
	}

	function setValueAPMACAddress(){
		if(wdsCfg.apmac!=null){
			for(var i=0;i<4;i++)
			{
				$('#wds_mac_'+(i+1)).val(wdsCfg.apmac[i]);
			}
		}
	}

	function setEventWDS()
	{
		var func = function(){
			var sel_wds = $('#wds_mode option:selected').val();
			if(sel_wds == '0')
				$('#wds_mac_list_1,#wds_mac_list_2,#wds_mac_list_3,#wds_mac_list_4').hide();
			else
				$('#wds_mac_list_1,#wds_mac_list_2,#wds_mac_list_3,#wds_mac_list_4').show();
		};
		func()
		$('#wds_mode').change(func);
	}
	
	function setValueOperatingMode(){
		var chk_op = htCfg.operating[0];
		$('input[name=n_opmode][value='+chk_op+']').attr('checked', true);
		
	}
	function setValueChannelBandWidth(){
		var chk_bw = lanCfg.chanwidth[0];
		$('input[name=n_bandwidth][value='+chk_bw+']').attr('checked', true);
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
	function setValue2040Coexistence(){
//		$('#2040coexi').show();
		var chk_coexi = lanCfg.coexi[0];
		$('input[name=n_coexistence][value='+chk_coexi+']').attr('checked', true);
	}
	function setValueGuardInterval(){
		var chk_gi = lanCfg.shortgi[0];
		$('input[name=n_gi][value='+chk_gi+']').attr('checked', true);
	}
	function setValueReverseDirectionGrant(){
//		$('#rdg').show();
		var chk_rdg = htCfg.rdg[0];
		$('input[name=n_rdg][value='+chk_rdg+']').attr('checked', true);
	}
	function setValueAggregationMSDU(){
//		$('#amsdu').show();
		var chk_amsdu = htCfg.amsdu[0];
		$('input[name=n_amsdu][value='+chk_amsdu+']').attr('checked', true);
	}
	function setValueAutoBlockACK(){
//		$('#autoba').show();
		var chk_autoba = htCfg.autoba[0];
		$('input[name=n_autoba][value='+chk_autoba+']').attr('checked', true);
	}
	function setValueDeclineBARequest(){
//		$('#declineba').show();
		var chk_declineba = htCfg.declineba[0];
		$('input[name=n_declineba][value='+chk_declineba+']').attr('checked', true);
	}
	function setValueFortyIntolerant(){
		var chk_f40mhz = otherCfg.f40mhz[0];
		$('input[name=f_40mhz][value='+chk_f40mhz+']').attr('checked', true);
	}
	function setValueWiFiOptimum(){
		var chk_wifiopt = otherCfg.wifiopt[0];
		$('input[name=wifi_opt][value='+chk_wifiopt+']').attr('checked', true);
	}
	function setValueHTTxStream(){
		var sel_tx = otherCfg.txstream[0];
		$('#tx_stream').val(sel_tx);
	}
	function setValueHTRxStream(){
		var sel_rx = otherCfg.rxstream[0];
		$('#rx_stream').val(sel_rx);
	}
	
$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(model);
	if(lanCfg.enable[0]==1)
		$('#radioOnField').show();
	else
		$('#radioOffField').show();
	//Wireless Network
	setValueWMode();
	setValueWirelessName();
	setValueBoardcastSSID();
	setValueFrequency();
	setEventWMode();
	setEventFrequency();
	
	//Wireless Distribution System(WDS)
	setValueWDS();
	setValueAPMACAddress();
	setEventWDS();
	
	//HT Physical Mode
	setValueOperatingMode();
	setValueChannelBandWidth();
	setValue2040Coexistence();
	setValueGuardInterval();
	//Extension Channel is in setEventFrequency();
	setValueReverseDirectionGrant();
	setValueAggregationMSDU();
	setValueAutoBlockACK();
	setValueDeclineBARequest();
	setEventChannelBandWidth();
	
	//Other
//	$('#div_11n_plugfest').show();//close
//	setValueFortyIntolerant();//close
//	setValueWiFiOptimum();//close
	setValueHTTxStream();
	setValueHTRxStream();
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
				<script>document.write(menu.build_structure(1,-1,0))</script>
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
	<div class="CT"><script>show_words('_wireless_network')</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL"><script>show_words('_lb_radio_onoff')</script></td>
		<td class="CR">
			<input type="button" id="radioButtonOff" name="radioButtonOff" class="button_inbox" value="" onclick="switchRadio(false)" /> &nbsp; &nbsp;	 
			<script>$('#radioButtonOff').val(get_words('_btn_radio_off'));</script>
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
		<td class="CL"><script>show_words('_lb_multi_ssid_1')</script></td>
		<td class="CR">
			<input name="submit_SSID1" type="text" id="show_ssid_1" size="32" maxlength="32" value="" />
		</td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_lb_multi_ssid_2')</script></td>
		<td class="CR">
			<input name="submit_SSID2" type="text" id="show_ssid_2" size="32" maxlength="32" value="" />
		</td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_lb_multi_ssid_3')</script></td>
		<td class="CR">
			<input name="submit_SSID3" type="text" id="show_ssid_3" size="32" maxlength="32" value="" />
		</td>
	</tr>
	<tr style="display:none;"> 
		<td class="CL"><script>show_words('_lb_multi_ssid_4')</script></td>
		<td class="CR">
			<input name="submit_SSID4" type="text" id="show_ssid_4" size="32" maxlength="32" value="" />
		</td>
	</tr>
	<tr style="display:none;"> 
		<td class="CL"><script>show_words('_lb_multi_ssid_5')</script></td>
		<td class="CR">
			<input name="submit_SSID5" type="text" id="show_ssid_5" size="32" maxlength="32" value="" />
		</td>
	</tr>
	<tr style="display:none;"> 
		<td class="CL"><script>show_words('_lb_multi_ssid_6')</script></td>
		<td class="CR">
			<input name="submit_SSID6" type="text" id="show_ssid_6" size="32" maxlength="32" value="" />
		</td>
	</tr>
	<tr style="display:none;"> 
		<td class="CL"><script>show_words('_lb_multi_ssid_7')</script></td>
		<td class="CR">
			<input name="submit_SSID7" type="text" id="show_ssid_7" size="32" maxlength="32" value="" />
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
	</table>
</div>

<div class="box_tn">
	<div class="CT"><script>show_words('_wds_long')</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr> 
		<td class="CL"><script>show_words('help743')</script></td>
		<td class="CR">
			<select name="wds_mode" id="wds_mode" size="1">
		<option id="wds_mode_0" value="0"><script>show_words('_disable')</script></option>
		<!--<option id="wds_mode_4" value=4>Lazy Mode</option>
		<option id="wds_mode_2" value=2>Bridge Mode</option>
		<option id="wds_mode_3" value=3>Repeater Mode</option>-->
		<option id="wds_mode_3" value="3"><script>show_words('_enable')</script></option>
			</select>
		</td>
	</tr>
	<tr id="wds_mac_list_1" name="wds_mac_list_1" style="display: none;">
		<td class="CL"><script>show_words('_lb_apmacaddr');</script></td>
		<td class="CR"><input type="text" id="wds_mac_1" name="wds_1" size="20" maxlength="17" value="" /></td>
		</tr>
	<tr id="wds_mac_list_2" name="wds_mac_list_2" style="display: none;">
		<td class="CL"><script>show_words('_lb_apmacaddr');</script></td>
		<td class="CR"><input type="text" id="wds_mac_2" name="wds_2" size="20" maxlength="17" value="" /></td>
	</tr>
	<tr id="wds_mac_list_3" name="wds_mac_list_3" style="display: none;">
		<td class="CL"><script>show_words('_lb_apmacaddr');</script></td>
		<td class="CR"><input type="text" id="wds_mac_3" name="wds_3" size="20" maxlength="17" value="" /></td>
	</tr>
	<tr id="wds_mac_list_4" name="wds_mac_list_4" style="display: none;">
		<td class="CL"><script>show_words('_lb_apmacaddr');</script></td>
		<td class="CR"><input type="text" id="wds_mac_4" name="wds_4" size="20" maxlength="17" value="" /></td>
	</tr>
		<input type="hidden" name="wds_list" value="1" />
	</table>
</div>

<div id="div_11n" class="box_tn" style="display:none">
	<div class="CT"><script>show_words('_help_txt144')</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
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
		<tr id="2040coexi" style="display:none">
			<td class="CL"><script>show_words('_lb_coexistence');</script></td>
			<td class="CR">
				<input type="radio" name="n_coexistence" value="0" /><script>show_words('_disable')</script>
				<input type="radio" name="n_coexistence" value="1" /><script>show_words('_enable')</script>
			</td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('_help_txt155')</script></td>
			<td class="CR">
				<input type="radio" name="n_gi" value="0" /><script>show_words('_long');</script>&nbsp;
				<input type="radio" name="n_gi" value="1" /><script>show_words('KR50');</script>
			</td>
		</tr>
		<!-- 11n txrate -->
		<tr id="show_11n_txrate" style="display:none">
			<td class="CL"><script>show_words('_lb_mcs')</script></td>
			<td class="CR">
				<select id="wlan0_11n_txrate" name="wlan0_11n_txrate">
					<option value="0" selected><script>show_words('KR50')</script></option>
					<script>set_11n_txrate(get_by_id("wlan0_11n_txrate"));</script>
				</select>
			</td>
		</tr>
		<!-- 11g/n txrate -->
	<!--	<tr id="show_11gn_txrate" style="display:none;">
			<td class="CL"><script>show_words('_lb_mcs')</script></td>
			<td class="CR">
				<select id="wlan0_11gn_txrate" name="wlan0_11gn_txrate">
					<option value="0" selected><script>show_words('KR50')</script></option>
					<script>set_11gn_txrate(get_by_id("wlan0_11gn_txrate"));</script>
				</select>
			</td>
		</tr>
	-->
		<!-- 11b/g/n txrate -->
		<tr id="show_11bgn_txrate" style="display:none;">
			<td class="CL"><script>show_words('_lb_mcs')</script></td>
			<td class="CR">
				<select id="wlan0_11bgn_txrate" name="wlan0_11bgn_txrate">
					<option value="0" selected><script>show_words('KR50')</script></option>
					<script>set_11bgn_txrate(get_by_id("wlan0_11bgn_txrate"));</script>
				</select>
			</td>
		</tr>
		<tr id="rdg" style="display:none;">
			<td class="CL"><script>show_words('_lb_rdg');</script></td>
			<td class="CR">
				<input type="radio" name="n_rdg" value="0" /><script>show_words('_disable');</script>&nbsp;
				<input type="radio" name="n_rdg" value="1" /><script>show_words('_enable');</script>
			</td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('_lb_exten_channel');</script></td>
			<td class="CR">
				<select id="n_extcha" name="n_extcha" size="1" disabled="">
					<option value="0" selected=""><script>show_words('_sel_autoselect');</script></option>
				</select>
			</td>
		</tr>
		<tr id="amsdu" style="display:none">
			<td class="CL"><script>show_words('_lb_a_msdu');</script></td>
			<td class="CR">
				<input type="radio" name="n_amsdu" value="0" /><script>show_words('_disable');</script>&nbsp;
				<input type="radio" name="n_amsdu" value="1" /><script>show_words('_enable');</script>
			</td>
		</tr>
		<tr id="autoba" style="display:none">
			<td class="CL"><script>show_words('_lb_autoba');</script></td>
			<td class="CR">
				<input type="radio" name="n_autoba" value="0" /><script>show_words('_disable');</script>&nbsp;
				<input type="radio" name="n_autoba" value="1" /><script>show_words('_enable');</script>
			</td>
		</tr>
		<tr id="declineba" style="display:none">
			<td class="CL"><script>show_words('_lb_declineba');</script></td>
			<td class="CR">
				<input type="radio" name="n_declineba" value="0" /><script>show_words('_disable');</script>&nbsp;
				<input type="radio" name="n_declineba" value="1" /><script>show_words('_enable');</script>
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
		<div class="CT"><script>show_words('_wireless_network')</script></div>
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