<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<script>
	//Jerry, redirect to doc root
	{
		var loc = window.location.pathname;
		var arr = loc.split('/');
		try {
			if (arr.length > 2) //more than one directory
			{
				location.replace('/wizard_router.asp');
			}
		} catch (e) {
		}
	}
</script>
<title>TRENDNET | modelName | Wizard</title>
<style type="text/css">
/*
 * Styles used only on this page.
 * WAN mode radio buttons
 */
#wan_modes p {
	margin-bottom: 1px;
}
#wan_modes input {
	float: left;
	margin-right: 1em;
}
#wan_modes label.duple {
	float: none;
	width: auto;
	text-align: left;
}
#wan_modes .itemhelp {
	margin: 0 0 1em 2em;
}

/*
 * Wizard buttons at bottom wizard "page".
 */
#wz_buttons {
	margin-top: 1em;
	border: none;
}

#wz_progress {
  background-color:#bca;
  border:2px solid green;
}

body{ font-size:12px;
margin: 8px;
}
.langmenu{
position: absolute;
display: none;
background: white;
border: 1px solid #555555;
border-width: 5px 0px 5px 0px;
padding: 10px;
font: normal 12px Verdana;
z-index: 100;
}

.langmenu .column{
float: left;
width: 120px; /*width of each menu column*/
margin-right: 5px;
}

.langmenu .column ul{
margin: 0;
padding: 0;
list-style-type: none;
}

.langmenu .column ul li{
padding-bottom: 8px;
}

.langmenu .column ul li a{
text-decoration: none;
}
#progressbar {
	height: 20px;
	background: #666666;
	border-width: thin;
	border-style: solid;
}
.CL {
	padding-left: 100px;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="pragma" content="no-cache" />
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
var submit_button_flag = 0;
var salt = "345cd2ef";
var def_title = document.title;
var misc = new ccpObject();
var dev_info = misc.get_router_info();
document.title = def_title.replace("modelName", dev_info.model);
var hw_version 	= dev_info.hw_ver;
var version 	= dev_info.fw_ver;
var login_Info 	= dev_info.login_info;
var cli_mac 	= dev_info.cli_mac;
var wan_mac		= dev_info.wan_mac;

// not only first login but also menu click, so remove
//	if (dev_info.es_conf == '1') {
//		location.replace('login.asp');
//	}


	var main = new ccpObject();
	main.set_param_url('easy_setup.ccp');
	main.set_ccp_act('get');
	main.add_param_arg('IGD_',1000);
	main.set_param_option('es_step',0);
	main.get_config_obj();
	
var br_lang = main.config_val('igd_CurrentLanguage_')? main.config_val('igd_CurrentLanguage_'):"0";
var alreadyconf = main.config_val('igd_AlreadyConfiguration_')? main.config_val('igd_AlreadyConfiguration_'):"0";
var is_support = 0;

//20120228 silvia add get url
function get_ip()
{
	var web_url;
	var temp_cURL = document.URL.split('/');

	var next_page;
	if(alreadyconf=='1')
		next_page='basic_status.asp';
	else
		next_page='login.asp';
		
	if (temp_cURL[0] == "https:")
		web_url="https://"+temp_cURL[1]+temp_cURL[2]+'/'+next_page;
	else
		web_url="http://"+temp_cURL[1]+temp_cURL[2]+'/'+next_page;
	return web_url;
}
function check_browser()	//chk support bookmark and lang
{
	var chkMSIE = (navigator.userAgent.match(/msie/gi) == 'MSIE') ? true : false ;
	var isMSIE = (-[1,]) ? false : true;

	if(window.sidebar && window.sidebar.addPanel){ //Firefox
		is_support = 1;
	}else if (chkMSIE && window.external) {  //IE favorite
		is_support = 2;
	}
	return is_support;
}
function chk_browser_lang()
{
	check_browser();
	if (is_support == 2)	// ie only
		curr_lang = window.navigator.userLanguage;
	else	// other browser
		curr_lang = window.navigator.language;

	currLindex = lang_compare(curr_lang);
	lang_change(currLindex);
	return currLindex;
}
function user_sel_lang(index)
{
	lang_change(index);
	var temp_cURL = document.URL.split('#');
	if (temp_cURL.length>1)
	{
		lang_change(temp_cURL[1]);
	}
}

/* language change */
function lang_change(Nlang)
{
	var indexL =Nlang.split('#');
	var temp_cURL = document.URL.split('#');
	if (indexL.length>1)
	{
		Nlang = indexL[1];
		$('#curr_language').val(Nlang);
		if (indexL[1] != br_lang)
		{
			send_change_lang_ajax_submit(Nlang);
			return;
		}
	}else if ((Nlang != br_lang) || (temp_cURL.length == 1) || (br_lang == 0))
	{
		send_change_lang_ajax_submit(Nlang);
		return;
	}
}
function send_change_lang_ajax_submit(Nlang)
{
	var time=new Date().getTime();
	var temp_cURL = document.URL.split('#');
	var ajax_param = {
		type: 	"POST",
		async:	false,
		url: 	'curr_lang.ccp',
		data: 	'ccp_act=set&ccpSubEvent=CCP_SUB_WEBPAGE_APPLY&curr_language='+Nlang+
				'&igd_CurrentLanguage_1.0.0.0='+Nlang+"&"+time+"="+time,
		success: function(data) {
			window.location.href = temp_cURL[0] +'#'+Nlang;
/*				var isSafari = navigator.userAgent.search("Safari") > -1;
			if (isSafari)
				location.replace('/wizard_router.asp');
			else
				window.location.reload(true);
*/
			window.location.reload(true);
		}
	};
	$.ajax(ajax_param);
}

function wizard_cancel(){
	if(!confirm(get_words('_wizquit')))
		return false;
	var obj = new ccpObject();
	obj.set_param_url('easy_setup.ccp');
	obj.set_ccp_act('wzcancel');
	obj.set_param_option('es_step',0);
	if(submit_button_flag == 0){
		obj.get_config_obj();
		submit_button_flag = 1;

		var result = obj.config_val("result");
		if(result == 'OK')
		{
			location.replace(get_ip());
		}
		return true;
	}else{
		return false;
	}
}
function show_page(page){
	$('div[name=page]').hide();
	$('#'+page).show();
	display_p2_desc_chk(page);
}
<!-- p0 -->
function show_page_p0(){
	show_page('p0');
	change_step(1);
}
<!-- p1 -->
var probe_count  = 0;
var progressBarMaxWidth = 500;
function show_page_p1(){
	$('#progressbar').width(0);
	show_page('p1');
	change_step(1);
	if(check_cable_status()){
		probe_count = 0;
		do_probe();
		setTimeout('get_probe_state()',1500);
		draw_progress_bar();
	}
	return true;
}
function check_cable_status()
{
	var obj = new ccpObject();
	obj.set_param_url('easy_setup.ccp');
	obj.set_ccp_act('get');
	obj.add_param_arg('IGD_WANDevice_i_WANStatus_',1110);
	obj.set_param_option('es_step',0);
	obj.get_config_obj();
	
	var cableSt = obj.config_val('igdWanStatus_CableStatus_');
	if (cableSt == 'Connected') {	//skip try again page	//usar
		return true;
	}
	alert(get_words("_tnw_14"));
	return false;
}
function draw_progress_bar()
{
	var curWidth = $('#progressbar').width();
	var fieldWidth = curWidth + (progressBarMaxWidth)/90;

	if (progressBarMaxWidth<=fieldWidth) {
		$('#progressbar').width(progressBarMaxWidth);
		return;
	}
	else {
		$('#progressbar').width(fieldWidth);
		setTimeout('draw_progress_bar()', 100);
	}
}
function do_probe()
{
	var time=new Date().getTime();
	var ajax_param = {
		type: 	"POST",
		async:	true,
		url: 	'easy_setup.ccp',
		data: 	'ccp_act=do_probe&es_step=0'+"&"+time+"="+time
	};
	$.ajax(ajax_param);
}
function get_probe_state_hldr(data)
{
	wz_probe_wan = get_node_value(data, 'probe_status');
//	console.log("wz_probe_wan", wz_probe_wan);
	if (wz_probe_wan == '10' || probe_count++ >= 9) {		//probe finished: 10
		$('#progressbar').width(progressBarMaxWidth);
		show_page_p3();//setp 4
		return;
	}
	if (wz_probe_wan != '' && wz_probe_wan != '0') {
		$('#progressbar').width(progressBarMaxWidth);
		check_probe_wan();
		return;
	}
	setTimeout('get_probe_state()', 1000);
}
function get_probe_state()
{
	var time=new Date().getTime();
	var ajax_param = {
		type: 	"POST",
		async:	true,
		url: 	'easy_setup.ccp',
		data: 	'ccp_act=get_probe_state&es_step=0'+"&"+time+"="+time,
		success: function(data) {
			get_probe_state_hldr(data);
		},
		error: function(xhr, ajaxOptions, thrownError){
			if (xhr.status == 200) {
				try {
					setTimeout(function() {
						document.write(xhr.responseText);
					}, 0);
				} catch (e) {
				}
			}
		}
	};
	$.ajax(ajax_param);
}
<!-- check probe_wan -->
function check_probe_wan()
{
	switch(wz_probe_wan) {
		case '1':		//dhcp
			show_page_p2();//setp 3
			break;
		case '2':		//pppoe
			show_page_p3c();
			break;
		default:	//unknown type
			show_page_p3();//setp 4
			break;
	}
}
function display_p2_desc_chk(page)
{
if(page == "p2")
	$('#p2_desc').html(get_words('_tnw_04_desc'));
else
	$('#p2_desc').html("");
}
<!-- p2 -->
function show_page_p2(){
	show_page('p2');
	change_step(2);
	var obj = new ccpObject();
	obj.set_param_url('easy_setup.ccp');
	obj.set_ccp_act('get');
	obj.set_param_option('es_step',0);
	obj.add_param_arg('IGD_Status_General_',1110);
	obj.add_param_arg('IGD_WANDevice_i_WANStatus_',1110);
	obj.add_param_arg('IGD_LANDevice_i_LANHostConfigManagement_',1110);
	obj.add_param_arg('IGD_LANDevice_i_ConnectedAddress_i_',1110);
	obj.add_param_arg('IGD_WLANConfiguration_i_WLANStatus_',1110);
	obj.add_param_arg('IGD_WLANConfiguration_i_',1100);
	obj.add_param_arg('IGD_WLANConfiguration_i_',1500);
	obj.add_param_arg('IGD_LANDevice_i_WLANConfiguration_i_',1100);
	obj.add_param_arg('IGD_',1000);
	
	obj.add_param_arg('IGD_WLANConfiguration_i_WEP_',1110);
	obj.add_param_arg('IGD_WLANConfiguration_i_WPS_',1110);
	obj.add_param_arg('IGD_WLANConfiguration_i_WPA_',1110);
	obj.add_param_arg('IGD_WLANConfiguration_i_WEP_',1510);
	obj.add_param_arg('IGD_WLANConfiguration_i_WPS_',1510);
	obj.add_param_arg('IGD_WLANConfiguration_i_WPA_',1510);
	
	obj.get_config_obj();
	
	var wepCfg = 
	{
		'keyLength':	obj.config_val("wepInfo_KeyLength_"),
		'authType':		obj.config_str_multi("wepInfo_AuthenticationMode_")
	};

	var wpaCfg = 
	{
		'wpamode' :		obj.config_str_multi("wpaInfo_WPAMode_"),
		'auth' :		obj.config_str_multi("wpaInfo_AuthenticationMode_")
	};
	var p2_ip = obj.config_val('igdWanStatus_IPAddress_');
	var p2_subnetmask = obj.config_val('igdWanStatus_SubnetMask_');
	var p2_defgw = obj.config_val('igdWanStatus_DefaultGateway_');
	var p2_dns1 = obj.config_val('igdWanStatus_PrimaryDNSAddress_');
	var p2_dns2 = obj.config_val('igdWanStatus_SecondaryDNSAddresss_');
	var p2_mac = obj.config_val('igdWanStatus_MACAddress_');
	var p2_ssid = obj.config_str_multi('wlanCfg_SSID_');
	var p2_wsec = obj.config_str_multi('wlanCfg_SecurityMode_');
	var p2_wtype = obj.config_val('igdWlanStatus_WlanAuthMode_');
	var authType = new Array();
	
	for (var i = 0; i <=1; i++)	// 0 -->2.4G, 4--> 5G
	{
		if(p2_wsec[i]==0)//None
		{
			authType[i] = get_words('_none');
		}
		else if(p2_wsec[i]==1)//WEP_MODE
		{
			if(wepCfg.authType[i] == 0)//OPEN
			{
				authType[i] = get_words('_wifiser_mode0');
			}
			else if(wepCfg.authType[i] == 1)//SHARE
			{
				authType[i] = get_words('_wifiser_mode1');
			}
			else if(wepCfg.authType[i] == 2)//AUTO
			{
				authType[i] = get_words('_wifiser_mode2');
			}
		}
		else if(p2_wsec[i]==2)//WPA_P
		{
			if(wpaCfg.wpamode[i] == 0)//AUTO
				authType[i] = get_words('_wifiser_mode5');
			if(wpaCfg.wpamode[i] == 1)//WPA2
				authType[i] = get_words('_wifiser_mode6') + "-" + get_words('LW24');
			if(wpaCfg.wpamode[i] == 2)//WPA
				authType[i] = get_words('_WPAenterprise');
		}
		else if(p2_wsec[i]==3)//WPA_E
		{
			if(wpaCfg.wpamode[i] == 0)//AUTO
				authType[i] = get_words('_wifiser_mode7');
			if(wpaCfg.wpamode[i]==1)//WPA2
				authType[i] = get_words('_wifiser_mode6')+ "-" + get_words('LW23');
			if(wpaCfg.wpamode[i]==2)//WPA
				authType[i] = get_words('_WPA');
		}
		else if(p2_wsec[i]==4)//WPA_AUTO
		{
			if(wpaCfg.auth[i] == 0)//PSK
			{
				authType[i] = get_words('_wifiser_mode5');
			}
			else if(wpaCfg.auth[i] == 1)//EAP
			{
			authType[i] = get_words('_wifiser_mode7');
			}
		}
	}
	$('#p2_ipaddr').html(p2_ip);
	$('#p2_macaddr').html(p2_mac);
	$('#p2_subnet').html(p2_subnetmask);
	$('#p2_gw').html(p2_defgw);
	$('#p2_dns').html( p2_dns2 != "" ? (p2_dns1 + "/" + p2_dns2) : p2_dns1);

	$('#p2_ssid2').html(p2_ssid[0]);
	$('#p2_authtype2').html(authType[0]);
	$('#p2_ssid5').html(p2_ssid[1]);
	$('#p2_authtype5').html(authType[1]);
}

function wizard_ok()
{
	var next_page;
	if(alreadyconf=='1')
		next_page='adm_status.asp';
	else
		next_page='login.asp';
	var paramSubmit = {
		url: "easy_setup.ccp",
		arg: 'ccp_act=set&es_step=0&ccpSubEvent=CCP_SUB_WEBPAGE_APPLY&nextPage='+next_page+'&igd_AlreadyConfiguration_1.0.0.0=1'
	}
	paramSubmit.arg += "&sysTime_NTPEnable_1.1.0.0=1";
	paramSubmit.arg += "&sysTime_NTPServer1_1.1.0.0=pool.ntp.org";
	
	//get_config_obj(paramSubmit);
	$('#p2_apply,#p2_finish,#p2_print').attr('disabled', true);
	var func = function(data){
		var result = get_node_value(data, "result");
		if(result == 'OK')
		{
			show_page_wan_ok();
		}
	}
	async_ajax(paramSubmit.url, paramSubmit.arg, func);
//	async_ajax(paramSubmit.url, paramSubmit.arg);
}
function async_ajax(url, arg, func){
	var time=new Date().getTime();
	var ajax_param = {
		type: 	"POST",
		async:	true,
		url: 	url,
		data: 	arg+"&"+time+"="+time,
		success: function(data) {
			if(func!=undefined)
				func(data);
		}
	};
	try {
			$.ajax(ajax_param);
	} catch (e) {
	}
}

<!-- p3 -->
function show_page_p3(){
	show_page('p3');
	change_step(2);
}
function sel_page_p3(){
	var p3_val = $('input[name=p3_wan_type]:checked').val();
	switch(p3_val)
	{
	case '0': show_page_p3a(); break;
	case '1': show_page_p3b(); break;
	case '2': show_page_p3c(); break;
	case '3': show_page_p3d(); break;
	case '4': show_page_p3e(); break;
	}
}
<!-- p3a -->
var already_clone;
function show_page_p3a(){
	show_page('p3a');
	change_step(2);
	
	var obj = new ccpObject();
	obj.set_param_url('easy_setup.ccp');
	obj.set_ccp_act('get');
	obj.set_param_option('es_step',0);
	obj.add_param_arg('IGD_WANDevice_i_DHCP_',1110);
	obj.add_param_arg('IGD_WANDevice_i_',1100);
	
	obj.get_config_obj();
	already_clone = obj.config_val("wanDev_MACAddressOverride_");
	var dn = obj.config_val('dhcpCfg_HostName_');
	var mac = wan_mac.split(':');
	$('#p3a_device_name').val(dn);
	$('#p3a_mac1').val(mac[0]);
	$('#p3a_mac2').val(mac[1]);
	$('#p3a_mac3').val(mac[2]);
	$('#p3a_mac4').val(mac[3]);
	$('#p3a_mac5').val(mac[4]);
	$('#p3a_mac6').val(mac[5]);
}
function clone_mac_action(){
	var mac = cli_mac.split(':');
	$('#p3a_mac1').val(mac[0]);
	$('#p3a_mac2').val(mac[1]);
	$('#p3a_mac3').val(mac[2]);
	$('#p3a_mac4').val(mac[3]);
	$('#p3a_mac5').val(mac[4]);
	$('#p3a_mac6').val(mac[5]);
	already_clone = 1;
}
function wz_p3a_save(){
	var input_mac="";
	input_mac=[$('#p3a_mac1').val(),$('#p3a_mac2').val(),$('#p3a_mac3').val(),$('#p3a_mac4').val(),$('#p3a_mac5').val(),$('#p3a_mac6').val()].join(":");
	/* 
	** check value
	**/
	if (!check_mac(input_mac)){
		alert (get_words('KR3')+":" + input_mac + ".");
		return;
	} 
	input_mac = trim_string(input_mac);
	if(!is_mac_valid(input_mac, true)) {
		alert(get_words('KR3')+":" + input_mac + ".");
		return;
	}
	
	var obj = new ccpObject();
	obj.set_param_url('easy_setup.ccp');
	obj.set_ccp_act('set');
	obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
	obj.set_param_option('es_step',0);
	
	obj.add_param_arg('wanDev_CurrentConnObjType_','1.1.0.0','1');
	
	obj.add_param_arg('dhcpCfg_HostName_','1.1.1.0',$('#p3a_device_name').val());
//	console.log('',wan_mac);
//	console.log('',input_mac);
	
	if(input_mac==wan_mac)
		already_clone=0;
	else
		already_clone=1;
	if(already_clone==0){//not override
		obj.add_param_arg('wanDev_MACAddressOverride_','1.1.0.0','0');
		obj.add_param_arg('wanDev_MACAddressClone_','1.1.0.0','');
	}
	else{
		obj.add_param_arg('wanDev_MACAddressOverride_','1.1.0.0','1');
		obj.add_param_arg('wanDev_MACAddressClone_','1.1.0.0',input_mac);
	}
	
	var	param = obj.get_param();
	async_ajax(param.url, param.arg);
	show_page_wan_detecting();
}

<!-- p3b -->
function show_page_p3b(){
	show_page('p3b');
	change_step(2);
}
function wz_p3b_save(){
	/* 
	** check value
	**/
	var ip = $('#p3b_wan_ip').val();
	var mask = $('#p3b_wan_mask').val();
	var gateway = $('#p3b_wan_gw').val();
	var dns1 = $('#p3b_wan_dns1').val();
	var dns2 = $('#p3b_wan_dns2').val();
	var ip_addr_msg = replace_msg(all_ip_addr_msg,get_words('_ipaddr'));
	var gateway_msg = replace_msg(all_ip_addr_msg,get_words('wwa_gw'));
	var dns1_addr_msg = replace_msg(all_ip_addr_msg,get_words('wwa_pdns'));
	var dns2_addr_msg = replace_msg(all_ip_addr_msg,get_words('wwa_sdns'));
	var temp_ip_obj = new addr_obj(ip.split("."), ip_addr_msg, false, false);
	var temp_mask_obj = new addr_obj(mask.split("."), subnet_mask_msg, false, false);
	var temp_gateway_obj = new addr_obj(gateway.split("."), gateway_msg, false, false);
	var temp_dns1_obj = new addr_obj(dns1.split("."), dns1_addr_msg, false, false);
	var temp_dns2_obj = new addr_obj(dns2.split("."), dns2_addr_msg, true, false);
	if (!check_lan_setting(temp_ip_obj, temp_mask_obj, temp_gateway_obj)){
		return;
	}
	if (!check_address(temp_dns1_obj)){
		return;
	}
	if (dns2 != "" && dns2 != "0.0.0.0"){
		if (!check_address(temp_dns2_obj)){
			return;
		}
	}

	var obj = new ccpObject();
	obj.set_param_url('easy_setup.ccp');
	obj.set_ccp_act('set');
	obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
	obj.set_param_option('es_step',0);
	
	obj.add_param_arg('wanDev_CurrentConnObjType_','1.1.0.0','0');
	
	var staticip = $('#p3b_wan_ip').val()==''?'0.0.0.0':$('#p3b_wan_ip').val();
	var staticmask = $('#p3b_wan_mask').val()==''?'0.0.0.0':$('#p3b_wan_mask').val();
	var staticgw = $('#p3b_wan_gw').val()==''?'0.0.0.0':$('#p3b_wan_gw').val();
	obj.add_param_arg('staticIPCfg_ExternalIPAddress_','1.1.1.0',staticip);
	obj.add_param_arg('staticIPCfg_SubnetMask_','1.1.1.0',staticmask);
	obj.add_param_arg('staticIPCfg_DefaultGateway_','1.1.1.0',staticgw);
	
	var pridns = $('#p3b_wan_dns1').val()==''?'0.0.0.0':$('#p3b_wan_dns1').val();
	var secdns = $('#p3b_wan_dns2').val()==''?'0.0.0.0':$('#p3b_wan_dns2').val();
	if(pridns=='0.0.0.0' && secdns=='0.0.0.0'){
		obj.add_param_arg('staticIPCfg_DNSEnabled_','1.1.1.0','0');
	}else{
		obj.add_param_arg('staticIPCfg_DNSEnabled_','1.1.1.0','1');
	}
	obj.add_param_arg('staticIPCfg_DNSServers_','1.1.1.0',(pridns+','+secdns));
	
	var	param = obj.get_param();
	async_ajax(param.url, param.arg);
	show_page_wan_detecting();
}

<!-- p3c -->
function show_page_p3c(){
	show_page('p3c');
	change_step(2);
}
function wz_p3c_save(){
	/* 
	** check value
	**/
	/*
	** Date:	2013-03-18
	** Author:	Moa Chung
	** Reason:	Base Setup wizard：PPPoe shoud cancel the setting of static IP.(Wan setting do not support static ip)
	** Note:	TEW-810DR pre-test no.35
	**/
/*	var pppoe_type = $('#pppoe_conn_type:checked').val();
    var ip = $('#pppoe_ip_addr').val();
	var ip_addr_msg = replace_msg(all_ip_addr_msg,get_words('_ipaddr'));
	var temp_ip_obj = new addr_obj(ip.split("."), ip_addr_msg, false, false);
	if(pppoe_type=='1')
	{
		if (!check_address(temp_ip_obj)){
			return;
		}
	}
*/
	if($('#pppoe_user_name').val() == ""){
		alert(get_words('PPP_USERNAME_EMPTY', LangMap.msg));
		return;
	}
	if($('#pppoe_user_pwd').val() == "" || $('#pppoe_verify_pwd').val() == ""){
		alert(get_words('GW_WAN_PPPOE_PASSWORD_INVALID'));
		return;
	}
	if (!check_pwd("pppoe_user_pwd", "pppoe_verify_pwd")){
		return;
	}
	
	var obj = new ccpObject();
	obj.set_param_url('easy_setup.ccp');
	obj.set_ccp_act('set');
	obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
	obj.set_param_option('es_step',0);
	var basic="";
	
	var type = $('#pppoe_conn_type:checked').val();
	
	obj.add_param_arg('wanDev_CurrentConnObjType_','1.1.0.0',2);
	
	obj.add_param_arg('pppoeCfg_IPAddressType_','1.1.1.0',type);
	obj.add_param_arg('pppoeCfg_Username_','1.1.1.0',$('#pppoe_user_name').val());
	obj.add_param_arg('pppoeCfg_Password_','1.1.1.0',$('#pppoe_user_pwd').val());
	
/*
	if(type=='1'){
		obj.add_param_arg('pppoeCfg_ExternalIPAddress_','1.1.1.0',$('#pppoe_ip_addr').val());
	}
*/
	
	var	param = obj.get_param();
	async_ajax(param.url, param.arg);
	show_page_wan_detecting();
}
<!-- p3d -->
function show_page_p3d(){
	show_page('p3d');
	change_step(2);
}
function wz_p3d_save(){
	/* 
	** check value
	**/
	var pptp_type  = $('#pptp_conn_type:checked').val();
	var ip = $('#pptp_ip_addr').val();
	var mask = $('#pptp_subnet_mask').val();  
	var gateway = $('#pptp_gateway').val();
	var server_ip = $('#pptp_server_ip').val();
	var ip_addr_msg = replace_msg(all_ip_addr_msg,get_words('_ipaddr'));
	var gateway_msg = replace_msg(all_ip_addr_msg,get_words('wwa_gw'));
	var dns1_addr_msg = replace_msg(all_ip_addr_msg,get_words('wwa_pdns'));
	var dns2_addr_msg = replace_msg(all_ip_addr_msg,get_words('wwa_sdns'));
	var server_ip_addr_msg = replace_msg(all_ip_addr_msg,get_words('_server_ip'));
	var temp_ip_obj = new addr_obj(ip.split("."), ip_addr_msg, false, false);
	var temp_mask_obj = new addr_obj(mask.split("."), subnet_mask_msg, false, false);
	var temp_gateway_obj = new addr_obj(gateway.split("."), gateway_msg, false, false);
	var temp_server_ip_obj = new addr_obj(server_ip.split("."), server_ip_addr_msg, false, false);
	if (pptp_type=='1'){
		if (!check_lan_setting(temp_ip_obj, temp_mask_obj, temp_gateway_obj)){
			return;
		}
	}
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
	
	if($('#pptp_user_name').val() == ""){
		alert(get_words('PPP_USERNAME_EMPTY', LangMap.msg));
		return;
	 }
	if ($('#pptp_user_pwd').val() == "" || $('#pptp_verify_pwd').val() == ""){
		alert(get_words('GW_WAN_PPTP_PASSWORD_INVALID'));
		return;
	}
	if (!check_pwd("pptp_user_pwd", "pptp_verify_pwd")){
		return;
	}


	var obj = new ccpObject();
	obj.set_param_url('easy_setup.ccp');
	obj.set_ccp_act('set');
	obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
	obj.set_param_option('es_step.asp',0);
	
	obj.add_param_arg('wanDev_CurrentConnObjType_','1.1.0.0','3');
	
	var type = $('#pptp_conn_type:checked').val();
	
	if(type=='1'){
		obj.add_param_arg('pptpCfg_ExternalIPAddress_','1.1.1.0',$('#pptp_ip_addr').val());
		obj.add_param_arg('pptpCfg_SubnetMask_','1.1.1.0',$('#pptp_subnet_mask').val());
		obj.add_param_arg('pptpCfg_DefaultGateway_','1.1.1.0',$('#pptp_gateway').val());
	}
	/*
	** Date:	2013-03-18
	** Author:	Moa Chung
	** Reason:	Base Setup wizard：PPTP set Static IP mode. You will see dynamic mode at wan setting page.
	** Note:	TEW-810DR pre-test no.32
	**/
	obj.add_param_arg('pptpCfg_IPAddressType_','1.1.1.0',type);
	
//	obj.add_param_arg('pppoeCfg_Username_','1.1.1.0',$('#pppoe_user_name2').val());//i dont know what's this
	obj.add_param_arg('pptpConn_ServerIP_','1.1.1.1',$('#pptp_server_ip').val());
	obj.add_param_arg('pptpConn_Username_','1.1.1.1',$('#pptp_user_name').val());
	obj.add_param_arg('pptpConn_Password_','1.1.1.1',$('#pptp_user_pwd').val());
	
	var	param = obj.get_param();
	async_ajax(param.url, param.arg);
	show_page_wan_detecting();
}
<!-- p3e -->
function show_page_p3e(){
	show_page('p3e');
	change_step(2);
}
function wz_p3e_save(){
	/* 
	** check value
	**/
	var l2tp_type = $('#l2tp_conn_type:checked').val();
	var ip = $('#l2tp_ip_addr').val();
	var mask = $('#l2tp_subnet_mask').val();
	var gateway = $('#l2tp_gateway').val();
	var server_ip = $('#l2tp_server_ip').val();
	var ip_addr_msg = replace_msg(all_ip_addr_msg,get_words('_ipaddr'));
	var gateway_msg = replace_msg(all_ip_addr_msg,get_words('wwa_gw'));
	var server_ip_addr_msg = replace_msg(all_ip_addr_msg,get_words('_server_ip'));
	var temp_ip_obj = new addr_obj(ip.split("."), ip_addr_msg, false, false);
	var temp_mask_obj = new addr_obj(mask.split("."), subnet_mask_msg, false, false);
	var temp_gateway_obj = new addr_obj(gateway.split("."), gateway_msg, false, false);
	var temp_server_ip_obj = new addr_obj(server_ip.split("."), server_ip_addr_msg, false, false);
	if (l2tp_type=='1'){
		if (!check_lan_setting(temp_ip_obj, temp_mask_obj, temp_gateway_obj)){
			return;
		}
	}
	/*
	** Date:	2013-04-23
	** Author:	Moa Chung
	** Reason:	allow l2tp server ip enter domain name and ip
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
			alert(get_words('YM112'));
			return false;
		}
	}
	
	if($('#l2tp_user_name').val() == ""){
		alert(get_words('GW_WAN_L2TP_USERNAME_INVALID'));
		return;
	}

	if ($('#l2tp_user_pwd').val() == "" || $('#l2tp_verify_pwd').val() == ""){
		alert(get_words('GW_WAN_L2TP_PASSWORD_INVALID'));
		return;
	}

	if (!check_pwd("l2tp_user_pwd", "l2tp_verify_pwd")){
		return;
	}
	
	var obj = new ccpObject();
	obj.set_param_url('easy_setup.ccp');
	obj.set_ccp_act('set');
	obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
	obj.set_param_option('es_step',0);
	
	obj.add_param_arg('wanDev_CurrentConnObjType_','1.1.0.0','4');
	
	var type = $('#l2tp_conn_type:checked').val();
	
	if(type=='1'){
		obj.add_param_arg('l2tpCfg_ExternalIPAddress_','1.1.1.0',$('#l2tp_ip_addr').val());
		obj.add_param_arg('l2tpCfg_SubnetMask_','1.1.1.0',$('#l2tp_subnet_mask').val());
		obj.add_param_arg('l2tpCfg_DefaultGateway_','1.1.1.0',$('#l2tp_gateway').val());
	}
	/*
	** Date:	2013-03-18
	** Author:	Moa Chung
	** Reason:	Base Setup wizard：L2tp  set Static IP mode. You will see dynamic mode at wan setting page.
	** Note:	TEW-810DR pre-test no.34
	**/
	obj.add_param_arg('l2tpCfg_IPAddressType_','1.1.1.0',type);
	
	obj.add_param_arg('l2tpConn_ServerIP_','1.1.1.1',$('#l2tp_server_ip').val());
	obj.add_param_arg('l2tpConn_Username_','1.1.1.1',$('#l2tp_user_name').val());
	obj.add_param_arg('l2tpConn_Password_','1.1.1.1',$('#l2tp_user_pwd').val());
	
	var	param = obj.get_param();
	async_ajax(param.url, param.arg);
	show_page_wan_detecting();
}
function show_page_wan_ok(){
	show_page('wan_ok');
	change_step(3);
	start_count_down();
}
function start_count_down(){
	var sec = parseInt($('#show_second').html());
	if(sec>0){
		$('#show_second').html(--sec);
		setTimeout("start_count_down()", 1000);
	} else{
		location.assign('/basic_status.asp')
//		$('input[name=back_btn]').removeAttr('disabled');
	}
}
function show_page_wan_detecting()
{
	show_page('wan_detecting');
	change_step(3);
	check_wan()
}
<!-- check internet exist? -->
var query_wan_times = 30;
function mul0(str, num)
{
	if (!num) return "";
	var newStr = str;
	while (--num) newStr += str;
	return newStr;
}
function check_wan(){
	$('#detect_msg_str').html(get_words('mydlink_tx05')+mul0('.', 4-query_wan_times%3));
	var st = query_wan_connection();
	if(st=='true'){
		show_page_p2();
	}
	else{
		/*
		** Date:	2013-03-20
		** Author:	Moa Chung
		** Reason:	Base Setup wizard：Wan server and settings are correct , it will show no internet at saving page.
		** Note:	TEW-810DR pre-test no.29
		**/
		if ((query_wan_times % 5) ==0)
			do_fakeping();
			
		if (query_wan_times <= 0){
			query_wan_times = 30;
			show_page('wan_failed');
		}
		else{
			query_wan_times--;
			setTimeout("check_wan()", 1000);
		}
	}
}
function query_wan_connection()
{
	var obj = new ccpObject();
	obj.set_param_url('ping.ccp');
	obj.set_ccp_act('queryWanConnect');
	obj.get_config_obj();
	var ret = obj.config_val("WANisReady");
	return ret;
}
function do_fakeping()
{
	var obj = new ccpObject();
	obj.set_param_url('ping.ccp');
	obj.set_ccp_act('fakeping');
	obj.set_param_option('fakeping',1);
	var paramPing = obj.get_param();
	ping_wan(paramPing);
}
function ping_wan(p)
{
	var time=new Date().getTime();
	var ajax_param = {
		type: 	"POST",
		async:	true,
		url: 	p.url,
		data: 	p.arg+"&"+time+"="+time
	};

	$.ajax(ajax_param);
}

function change_step(step)
{
	switch(step){
	case 1:
		$('#btn_step2,#btn_step3').removeClass('openheader').addClass('closeheader');
		$('#btn_step1').addClass('openheader').removeClass('closeheader');
		$('#btn_step1').find('img').attr('src', $('#btn_step1').find('img').attr('src').replace('_0.','_1.'));
		$('#btn_step2').find('img').attr('src', $('#btn_step2').find('img').attr('src').replace('_1.','_0.'));
		$('#btn_step3').find('img').attr('src', $('#btn_step3').find('img').attr('src').replace('_1.','_0.'));
		$('#manStatusTitle').html(get_words('_check_connection'));
		break;
	case 2:
		$('#btn_step1,#btn_step3').removeClass('openheader').addClass('closeheader');
		$('#btn_step2').addClass('openheader').removeClass('closeheader');
		$('#btn_step1').find('img').attr('src', $('#btn_step1').find('img').attr('src').replace('_1.','_0.'));
		$('#btn_step2').find('img').attr('src', $('#btn_step2').find('img').attr('src').replace('_0.','_1.'));
		$('#btn_step3').find('img').attr('src', $('#btn_step3').find('img').attr('src').replace('_1.','_0.'));
		$('#manStatusTitle').html(get_words('_confirm_settings'));
		break;
	case 3:
		$('#btn_step1,#btn_step2').removeClass('openheader').addClass('closeheader');
		$('#btn_step3').addClass('openheader').removeClass('closeheader');
		$('#btn_step1').find('img').attr('src', $('#btn_step1').find('img').attr('src').replace('_1.','_0.'));
		$('#btn_step2').find('img').attr('src', $('#btn_step2').find('img').attr('src').replace('_1.','_0.'));
		$('#btn_step3').find('img').attr('src', $('#btn_step3').find('img').attr('src').replace('_0.','_1.'));
		$('#manStatusTitle').html(get_words('_save_settings'));
		break;
	}
}

	function change_lang() {
		var Nlang = $('#lang_select').val();
		if (Nlang != br_lang) {
			lang_submit(Nlang);
			return;
		}
	}

	function lang_submit(Nlang) {
		var time = new Date().getTime();
		var temp_cURL = document.URL.split('#');
		var ajax_param = {
			type : "POST",
			async : false,
			url : 'curr_lang.ccp',
			data : 'ccp_act=set&ccpSubEvent=CCP_SUB_WEBPAGE_APPLY&curr_language=' + Nlang + '&igd_CurrentLanguage_1.0.0.0=' + Nlang + "&" + time + "=" + time,
			success : function(data) {
				window.location.href = temp_cURL[0] + '#' + Nlang;
				/*				var isSafari = navigator.userAgent.search("Safari") > -1;
				 if (isSafari)
				 location.replace('/wizard_router.asp');
				 else
				 window.location.reload(true);
				 */
				window.location.reload(true);
			}
		};
		$.ajax(ajax_param);
	}
$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(dev_info.model);
	//chk_browser_lang();
	window.location.replace('#' + br_lang);


	// set wizard title align, not a good way
	var wiz_title = get_words('wwa_setupwiz');
	if(wiz_title.length > 20){
		$('#wiz_title').css('padding-top', '3px').html(wiz_title);
	}
	else{
		$('#wiz_title').css('padding-top', '15px').html(wiz_title);
	}

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
					<div class="arrowlistmenu">
						<div class="homenav" style="margin-bottom:20px;">
						<div id="wiz_title" class="wizard-title"></div>
						<div class="clearfix"></div>
						</div>
						<div class="borderbottom"> </div>
						<div><div id="btn_step1" onclick="show_page_p0();" class="menuheader expandable openheader"><img src="/image/but_wizard1_1.png" class="CatImage" /><span class="CatTitle"><script>show_words('_check_connection')</script></span></div></div>
						<div><div id="btn_step2" class="menuheader expandable closeheader"><img src="/image/but_wizard2_0.png" class="CatImage" /><span class="CatTitle"><script>show_words('_confirm_settings')</script></span></div></div>
						<div><div id="btn_step3" class="menuheader expandable closeheader"><img src="/image/but_wizard3_0.png" class="CatImage" /><span class="CatTitle"><script>show_words('_save_settings')</script></span></div></div>
					</div>
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
								<script>show_words('_check_connection')</script>
								</div>
								<div class="hr"></div>
								<div class="section_content_border">
									<div class="header_desc" id="basicIntroduction">
										<span id="p2_desc"></span>
										<p></p>
									</div>
<div id="p0" name="page" class="box_tn">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tabBigTitle">
	<tr>
		<td class="CT">	
			<font><script>document.write(get_words('_tnw_01'));</script></font>
		</td>
	</tr>
	<tr>
		<td align="center" class="CELL">
			<div align="right">
				<strong><script>show_words('_Language')</script>&nbsp;&#58;</strong>
				<select id="lang_select" size="1" style="width:100px;" onchange="change_lang()">
							<option value="1" selected="">English</option>
							<option value="2">Español</option>
							<option value="3">Deutsch</option>
							<option value="6">Русский</option>
							<option value="4">Français</option>
				</select>
				<script>$('#lang_select').val(br_lang);</script>

			</div>
			<div style="padding:50px;">
				<p><b><script>show_words('_tnw_02');</script></b></p>
			</div>
		</td>
	</tr>
	<tr>
		<td align="center" class="CELL">
			<input name="p0_cancel" id="p0_cancel" type="button" class="ButtonSmall" onclick="wizard_cancel()" value="Cancel" />
			<script>$('#p0_cancel').val(get_words('_cancel'));</script>
			<input name="p0_apply" id="p0_apply" type="button" class="ButtonSmall" onclick="show_page_p1();" value="Next &gt;" />
			<script>$('#p0_apply').val(get_words('_next')+' >');</script>
		</td>
	</tr>
</table>
</div>

<div id="p1" name="page" class="box_tn" style="display:none;">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="CT">	
			<font><script>document.write(get_words('_tnw_03'));</script></font>
		</td>
	</tr>
	<tr>
		<td align="center" class="CELL" style="padding:50px;">
			<div align="center">
			<div align="left" style="width:500px;border:1px solid #000000;background-color:#ffffff;" >
				<div id="progressbar">&nbsp;</div>
			</div>
			</div>
		</td>
	</tr>
	<tr>
		<td align="center" class="CELL">
			<input name="p1_apply" id="p1_apply" type="button" class="ButtonSmall" onclick="show_page_p0()" value="&lt; Back" />
			<script>$('#p1_apply').val('< '+get_words('_back'));</script>
			<input name="p1_cancel" id="p1_cancel" type="button" class="ButtonSmall" onclick="wizard_cancel()" value="Cancel" />
			<script>$('#p1_cancel').val(get_words('_cancel'));</script>
		</td>
	</tr>
</table>
</div>

<div id="p2" name="page" class="box_tn" style="display:none;">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="2" class="CT">	
			<font><script>document.write(get_words('_confirm_settings'));</script></font>
		</td>
	</tr>
<!-- 2013-10-01 modify by Silvia for customization -->
	<tr>
		<td align="right" class="CELL" style="width:60%;">
			<b><script>show_words('help569');</script> <script>show_words('_ipaddr');</script> :</b></td>
		<td align="left" class="CELL" style="width:40%;" id="p2_ipaddr"></td>
	</tr>
	
<!-- 2013-10-01 add by Silvia -->
	<tr>
		<td align="right" class="CELL" style="width:60%;">
			<b><script>show_words('help569');</script> <script>show_words('_subnet');</script> :</b></td>
		<td align="left" class="CELL" style="width:40%;" id="p2_subnet"></td>
	</tr>
	<tr>
		<td align="right" class="CELL" style="width:60%;">
			<b><script>show_words('help569');</script> <script>show_words('wwa_gw');</script> :</b></td>
		<td align="left" class="CELL" style="width:40%;" id="p2_gw"></td>
	</tr>
	<tr>
		<td align="right" class="CELL" style="width:60%;">
			<b><script>show_words('_tnw_04_f02');</script> :</b></td>
		<td align="left" class="CELL" style="width:40%;" id="p2_dns"></td>
	</tr>
<!-- end of Silvia add 2013-10-01 -->
	
	<tr>
		<td align="right" class="CELL" style="width:60%;">
			<b><script>show_words('WAN');</script> <script>show_words('_macaddr');</script> :</b></td>
		<td align="left" class="CELL" style="width:40%;" id="p2_macaddr"></td>
	</tr>
	<tr>
		<td align="right" class="CELL" style="width:60%;">
			<b><script>show_words('KR16');</script> <script>show_words('wwl_wnn');</script> :</b></td>
		<td align="left" class="CELL" style="width:40%;" id="p2_ssid2"></td>
	</tr>
	<tr>
		<td align="right" class="CELL" style="width:60%;">
			<b><script>show_words('KR16');</script> <script>show_words('_tnw_04_f01');</script> :</b></td>
		<td align="left" class="CELL" style="width:40%;" id="p2_authtype2"></td>
	</tr>
<!-- 2013-10-01 add by Silvia -->
	<tr>
		<td align="right" class="CELL" style="width:60%;">
			<b><script>show_words('KR17');</script> <script>show_words('wwl_wnn');</script> :</b></td>
		<td align="left" class="CELL" style="width:40%;" id="p2_ssid5"></td>
	</tr>
	<tr>
		<td align="right" class="CELL" style="width:60%;">
			<b><script>show_words('KR17');</script> <script>show_words('_tnw_04_f01');</script> :</b></td>
		<td align="left" class="CELL" style="width:40%;" id="p2_authtype5"></td>
	</tr>
<!-- end of Silvia add 2013-10-01 -->
<!-- end of Silvia modify 2013-10-01 -->
	<tr>
		<td colspan="2" align="center" class="CELL">
			<input name="p2_apply" id="p2_apply" type="button" class="ButtonSmall" onclick="show_page_p0()" value="&lt; Back" />
			<script>$('#p2_apply').val('< '+get_words('_back'));</script>
			<input name="p2_finish" id="p2_finish" type="button" class="ButtonSmall" onclick="wizard_ok()" value="Save" />
			<script>$('#p2_finish').val(get_words('_save'));</script>
			<input name="p2_print" id="p2_print" type="button" class="ButtonSmall" onclick="window.print();return false;" value="Print" />
			<script>$('#p2_print').val(get_words('_print'));</script>
		</td>
	</tr>
</table>
</div>

<div id="p3" name="page" class="box_tn" style="display:none;">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="CT">	
			<font><script>document.write(get_words('_tnw_05'));</script></font>
		</td>
	</tr>
	<tr>
		<td align="left" class="CELL" style="padding-left:200px;">
			<input type="radio" name="p3_wan_type" value="0" />
			<b><script>show_words('_tnw_dhcp');</script></b>
		</td>
	</tr>
	<tr>
		<td align="left" class="CELL" style="padding-left:200px;">
			<input type="radio" name="p3_wan_type" value="1" />
			<b><script>show_words('_tnw_static');</script></b>
		</td>
	</tr>
	<tr>
		<td align="left" class="CELL" style="padding-left:200px;">
			<input type="radio" name="p3_wan_type" value="2" />
			<b><script>show_words('_tnw_pppoe');</script></b>
		</td>
	</tr>
	<tr>
		<td align="left" class="CELL" style="padding-left:200px;">
			<input type="radio" name="p3_wan_type" value="3" />
			<b><script>show_words('_tnw_pptp');</script></b>
		</td>
	</tr>
	<tr>
		<td align="left" class="CELL" style="padding-left:200px;">
			<input type="radio" name="p3_wan_type" value="4" />
			<b><script>show_words('_tnw_l2tp');</script></b>
		</td>
	</tr>
	<tr>
		<td align="center" class="CELL">
			<input name="p3_back" id="p3_back" type="button" class="ButtonSmall" onclick="show_page_p0()" value="&lt; Back" />
			<script>$('#p3_back').val('< '+get_words('_back'));</script>
			<input name="p3_apply" id="p3_apply" type="button" class="ButtonSmall" onclick="sel_page_p3()" value="Next &gt;" />
			<script>$('#p3_apply').val(get_words('_next')+' >');</script>
			<input name="p3_cancel" id="p3_cancel" type="button" class="ButtonSmall" onclick="wizard_cancel()" value="Cancel" />
			<script>$('#p3_cancel').val(get_words('_cancel'));</script>
		</td>
	</tr>
</table>
</div>

<div id="p3a" name="page" class="box_tn" style="display:none;">
<!-- DHCP -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="2" class="CT">	
			<font><script>document.write(get_words('_tnw_06'));</script></font>
		</td>
	</tr>
	<tr>
		<td align="right" class="CL">
			<b><script>show_words('_hostname');</script>:</b>
		</td>
		<td align="left" class="CR">
			<input type="text" id="p3a_device_name" name="device_name" size="40" maxlength="39" value="" />
			<b><script>show_words('_optional');</script></b>
		</td>
	</tr>
	<tr>
		<td align="right" class="CL">
			<b><script>show_words('_mac');</script>:</b>
		</td>
		<td align="left" class="CR">
			<font face="Arial" size="2">
				<input type="text" id="p3a_mac1" name="mac1" size="2" maxlength="2" />
				-
				<input type="text" id="p3a_mac2" name="mac2" size="2" maxlength="2" />
				-
				<input type="text" id="p3a_mac3" name="mac3" size="2" maxlength="2" />
				-
				<input type="text" id="p3a_mac4" name="mac4" size="2" maxlength="2" />
				-
				<input type="text" id="p3a_mac5" name="mac5" size="2" maxlength="2" />
				-
				<input type="text" id="p3a_mac6" name="mac6" size="2" maxlength="2" />
				<b><script>show_words('_optional');</script></b>
			</font>
		</td>
	</tr>
	<tr>
		<td align="right" class="CL">
			<span id="macCloneMac"></span>
		</td>
		<td align="left" class="CR">
			<input id="p3a_clone" type="button" value="Clone MAC Address" onclick="clone_mac_action()" />
			<script>$('#p3a_clone').val(get_words('_tnw_clone'));</script>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center" class="CELL">
			<input name="p3a_back" id="p3a_back" type="button" class="ButtonSmall" onclick="show_page_p3()" value="&lt; Back" />
			<script>$('#p3a_back').val('< '+get_words('_back'));</script>
			<input name="p3a_apply" id="p3a_apply" type="button" class="ButtonSmall" onclick="wz_p3a_save()" value="Save" />
			<script>$('#p3a_apply').val(get_words('_save'));</script>
			<input name="p3a_cancel" id="p3a_cancel" type="button" class="ButtonSmall" onclick="wizard_cancel()" value="Cancel" />
			<script>$('#p3a_cancel').val(get_words('_cancel'));</script>
		</td>
	</tr>
</table>
</div>

<div id="p3b" name="page" class="box_tn" style="display:none;">
<!-- Static -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="2" class="CT">	
			<font><script>document.write(get_words('_tnw_07'));</script></font>
		</td>
	</tr>
	<tr>
		<td align="right" class="CELL" style="width:40%;"><b><script>show_words('_wan_ipaddr');</script>:</b></td>
		<td align="left" class="CELL" style="width:60%;">
			<input type="text" id="p3b_wan_ip" size="16" maxlength="18" value="" />
		</td>
	</tr>
	<tr>
		<td align="right" class="CELL" style="width:40%;"><b><script>show_words('_wan_submask');</script>:</b></td>
		<td align="left" class="CELL" style="width:60%;">
			<input type="text" id="p3b_wan_mask" size="16" maxlength="18" value="" />
		</td>
	</tr>
	<tr>
		<td align="right" class="CELL" style="width:40%;"><b><script>show_words('_wan_gwaddr');</script>:</b></td>
		<td align="left" class="CELL" style="width:60%;">
			<input type="text" id="p3b_wan_gw" size="16" maxlength="18" value="" />
		</td>
	</tr>
	<tr>
		<td align="right" class="CELL" style="width:40%;"><b><script>show_words('_dns1');</script>:</b></td>
		<td align="left" class="CELL" style="width:60%;">
			<input type="text" id="p3b_wan_dns1" size="16" maxlength="18" value="" />
		</td>
	</tr>
	<tr>
		<td align="right" class="CELL" style="width:40%;"><b><script>show_words('_dns2');</script>:</b></td>
		<td align="left" class="CELL" style="width:60%;">
			<input type="text" id="p3b_wan_dns2" size="16" maxlength="18" value="" />
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center" class="CELL">
			<input name="p3b_back" id="p3b_back" type="button" class="ButtonSmall" onclick="show_page_p3()" value="&lt; Back" />
			<script>$('#p3b_back').val('< '+get_words('_back'));</script>
			<input name="p3b_apply" id="p3b_apply" type="button" class="ButtonSmall" onclick="wz_p3b_save()" value="Save" />
			<script>$('#p3b_apply').val(get_words('_save'));</script>
			<input name="p3b_cancel" id="p3b_cancel" type="button" class="ButtonSmall" onclick="wizard_cancel()" value="Cancel" />
			<script>$('#p3b_cancel').val(get_words('_cancel'));</script>
		</td>
	</tr>
</table>
</div>

<div id="p3c" name="page" class="box_tn" style="display:none;">
<!-- PPPoE -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="2" class="CT">
			<font><script>document.write(get_words('_tnw_08'));</script></font>
		</td>
	</tr>
<!--
	<tr>
		<td align="right" class="CELL" style="width:40%;"></td>
		<td align="left" class="CELL" style="width:60%;">
			<font face="Arial">
				<input type="radio" id="pppoe_conn_type" name="pppoe_conn_type" value="0" onclick="$('#pppoe_ip_addr').attr('disabled','disabled');" checked="" />
			</font>
			<b><script>show_words('carriertype_ct_0');</script></b>&nbsp;
			<font face="Arial">
				<input type="radio" id="pppoe_conn_type" name="pppoe_conn_type" value="1" onclick="$('#pppoe_ip_addr').removeAttr('disabled');" />
			</font>
			<b><script>show_words('_sdi_staticip');</script></b>&nbsp;
		</td>
	</tr>
-->
	<tr>
		<td align="right" class="CELL" style="width:40%;"><b><script>show_words('_username');</script>:</b></td>
		<td align="left" class="CELL" style="width:60%;">
			<input type="text" id="pppoe_user_name" size="30" maxlength="63" value="" />
		</td>
	</tr>
	<tr>
		<td align="right" class="CELL" style="width:40%;"><b><script>show_words('_password');</script>:</b></td>
		<td align="left" class="CELL" style="width:60%;">
			<input type="password" id="pppoe_user_pwd" size="30" maxlength="64" value="" />
		</td>
	</tr>
	<tr>
		<td align="right" class="CELL" style="width:40%;"><b><script>show_words('_verifypw');</script>:</b></td>
		<td align="left" class="CELL" style="width:60%;">
			<input type="password" id="pppoe_verify_pwd" size="30" maxlength="64" value="" />
		</td>
	</tr>
<!--
	<tr>
		<td align="right" class="CELL" style="width:40%;"><b><script>show_words('_ipaddr');</script>:</b></td>
		<td align="left" class="CELL" style="width:60%;">
			<input type="text" id="pppoe_ip_addr" size="16" maxlength="18" value="0.0.0.0" disabled>
		</td>
	</tr>
-->
	<tr>
		<td colspan="2" align="center" class="CELL">
			<input name="p3c_back" id="p3c_back" type="button" class="ButtonSmall" onclick="show_page_p3()" value="&lt; Back" />
			<script>$('#p3c_back').val('< '+get_words('_back'));</script>
			<input name="p3c_apply" id="p3c_apply" type="button" class="ButtonSmall" onclick="wz_p3c_save()" value="Save" />
			<script>$('#p3c_apply').val(get_words('_save'));</script>
			<input name="p3c_cancel" id="p3c_cancel" type="button" class="ButtonSmall" onclick="wizard_cancel()" value="Cancel" />
			<script>$('#p3c_cancel').val(get_words('_cancel'));</script>
		</td>
	</tr>
</table>
</div>
<div id="p3d" name="page" class="box_tn" style="display:none;">
<!-- PPTP -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="2" class="CT">
			<font><script>document.write(get_words('_tnw_09'));</script></font>
		</td>
	</tr>
	<tr>
		<td align="right" class="CELL" style="width:40%;"></td>
		<td align="left" class="CELL" style="width:60%;">
			<font face="Arial">
				<input type="radio" id="pptp_conn_type" name="pptp_conn_type" value="0" onclick="$('input[name=pptp_static_g]').attr('disabled','disabled');" checked="" />
			</font>
			<b><script>show_words('carriertype_ct_0');</script></b>&nbsp;
			<font face="Arial">
				<input type="radio" id="pptp_conn_type" name="pptp_conn_type" value="1" onclick="$('input[name=pptp_static_g]').removeAttr('disabled');" />
			</font>
			<b><script>show_words('_sdi_staticip');</script></b>&nbsp;
		</td>
	</tr>
	<tr>
		<td align="right" class="CELL" style="width:40%;"><b><script>show_words('_my_ip');</script>:</b></td>
		<td align="left" class="CELL" style="width:60%;">
			<input type="text" id="pptp_ip_addr" name="pptp_static_g" size="16" maxlength="63" value="0.0.0.0" disabled>
		</td>
	</tr>
	<tr>
		<td align="right" class="CELL" style="width:40%;"><b><script>show_words('_subnet');</script>:</b></td>
		<td align="left" class="CELL" style="width:60%;">
			<input type="text" id="pptp_subnet_mask" name="pptp_static_g" size="16" maxlength="63" value="0.0.0.0" disabled>
		</td>
	</tr>
	<tr>
		<td align="right" class="CELL" style="width:40%;"><b><script>show_words('_gateway');</script>:</b></td>
		<td align="left" class="CELL" style="width:60%;">
			<input type="text" id="pptp_gateway" name="pptp_static_g" size="16" maxlength="63" value="0.0.0.0" disabled>
		</td>
	</tr>
<!-- pppoe should not show here
	<tr>
		<td align="right" class="CELL" style="width:40%;"><b>User Name:</b></td>
		<td align="left" class="CELL" style="width:60%;">
			<input type="text" id="pppoe_user_name2" size="30" maxlength="63" value="" />
		</td>
	</tr>
-->
	<tr>
		<td align="right" class="CELL" style="width:40%;"><b><script>show_words('_server_ip');</script>:</b></td>
		<td align="left" class="CELL" style="width:60%;">
			<input type="text" id="pptp_server_ip" size="16" maxlength="63" value="" />
		</td>
	</tr>
	<tr>
		<td align="right" class="CELL" style="width:40%;"><b><script>show_words('_pptp_account');</script>:</b></td>
		<td align="left" class="CELL" style="width:60%;">
			<input type="text" id="pptp_user_name" size="30" maxlength="63" value="" />
		</td>
	</tr>
	<tr>
		<td align="right" class="CELL" style="width:40%;"><b><script>show_words('_pptp_password');</script>:</b></td>
		<td align="left" class="CELL" style="width:60%;">
			<input type="password" id="pptp_user_pwd" name="pptp_user_pwd" size="30" maxlength="63" value="" />
		</td>
	</tr>
	<tr>
		<td align="right" class="CELL" style="width:40%;"><b><script>show_words('_pptp_password_re');</script>:</b></td>
		<td align="left" class="CELL" style="width:60%;">
			<input type="password" id="pptp_verify_pwd" name="pptp_verify_pwd" size="30" maxlength="63" value="" />
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center" class="CELL">
			<input name="p3d_back" id="p3d_back" type="button" class="ButtonSmall" onclick="show_page_p3()" value="&lt; Back" />
			<script>$('#p3d_back').val('< '+get_words('_back'));</script>
			<input name="p3d_apply" id="p3d_apply" type="button" class="ButtonSmall" onclick="wz_p3d_save()" value="Save" />
			<script>$('#p3d_apply').val(get_words('_save'));</script>
			<input name="p3d_cancel" id="p3d_cancel" type="button" class="ButtonSmall" onclick="wizard_cancel()" value="Cancel" />
			<script>$('#p3d_cancel').val(get_words('_cancel'));</script>
		</td>
	</tr>
</table>
</div>

<div id="p3e" name="page" class="box_tn" style="display:none;">
<!-- L2TP -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="2" class="CT">
			<font><script>document.write(get_words('_tnw_10'));</script></font>
		</td>
	</tr>
	<tr>
		<td align="right" class="CELL" style="width:40%;"></td>
		<td align="left" class="CELL" style="width:60%;">
			<font face="Arial">
				<input type="radio" id="l2tp_conn_type" name="l2tp_conn_type" value="0" onclick="$('input[name=l2tp_static_g]').attr('disabled','disabled');" checked="" />
			</font>
			<b><script>show_words('carriertype_ct_0');</script></b>&nbsp;
			<font face="Arial">
				<input type="radio" id="l2tp_conn_type" name="l2tp_conn_type" value="1" onclick="$('input[name=l2tp_static_g]').removeAttr('disabled');" />
			</font>
			<b><script>show_words('_sdi_staticip');</script></b>&nbsp;
		</td>
	</tr>
	<tr>
		<td align="right" class="CELL" style="width:40%;"><b><script>show_words('_my_ip');</script>:</b></td>
		<td align="left" class="CELL" style="width:60%;">
			<input type="text" id="l2tp_ip_addr" name="l2tp_static_g" size="16" maxlength="63" value="0.0.0.0" disabled>
		</td>
	</tr>
	<tr>
		<td align="right" class="CELL" style="width:40%;"><b><script>show_words('_subnet');</script>:</b></td>
		<td align="left" class="CELL" style="width:60%;">
			<input type="text" id="l2tp_subnet_mask" name="l2tp_static_g" size="16" maxlength="63" value="0.0.0.0" disabled>
		</td>
	</tr>
	<tr>
		<td align="right" class="CELL" style="width:40%;"><b><script>show_words('_gateway');</script>:</b></td>
		<td align="left" class="CELL" style="width:60%;">
			<input type="text" id="l2tp_gateway" name="l2tp_static_g" size="16" maxlength="63" value="0.0.0.0" disabled>
		</td>
	</tr>
	<tr>
		<td align="right" class="CELL" style="width:40%;"><b><script>show_words('_server_ip');</script>:</b></td>
		<td align="left" class="CELL" style="width:60%;">
			<input type="text" id="l2tp_server_ip" size="16" maxlength="63" value="" />
		</td>
	</tr>
	<tr>
		<td align="right" class="CELL" style="width:40%;"><b><script>show_words('_l2tp_account');</script>:</b></td>
		<td align="left" class="CELL" style="width:60%;">
			<input type="text" id="l2tp_user_name" size="30" maxlength="63" value="" />
		</td>
	</tr>
	<tr>
		<td align="right" class="CELL" style="width:40%;"><b><script>show_words('_l2tp_password');</script>:</b></td>
		<td align="left" class="CELL" style="width:60%;">
			<input type="password" id="l2tp_user_pwd" name="l2tp_user_pwd" size="30" maxlength="63" value="" />
		</td>
	</tr>
	<tr>
		<td align="right" class="CELL" style="width:40%;"><b><script>show_words('_l2tp_password_re');</script>:</b></td>
		<td align="left" class="CELL" style="width:60%;">
			<input type="password" id="l2tp_verify_pwd" name="l2tp_verify_pwd" size="30" maxlength="63" value="" />
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center" class="CELL">
			<input name="p3e_back" id="p3e_back" type="button" class="ButtonSmall" onclick="show_page_p3()" value="&lt; Back" />
			<script>$('#p3e_back').val('< '+get_words('_back'));</script>
			<input name="p3e_apply" id="p3e_apply" type="button" class="ButtonSmall" onclick="wz_p3e_save()" value="Save" />
			<script>$('#p3e_apply').val(get_words('_save'));</script>
			<input name="p3e_cancel" id="p3e_cancel" type="button" class="ButtonSmall" onclick="wizard_cancel()" value="Cancel" />
			<script>$('#p3e_cancel').val(get_words('_cancel'));</script>
		</td>
	</tr>
</table>
</div>

<div id="wan_ok" name="page" class="box_tn" style="display:none;">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="CT">	
			<font>&nbsp;</font>
		</td>
	</tr>
	<tr>
		<td align="center" class="CELL" style="padding:50px;">
			<p>
				<span id="msg_str"><b><script>show_words('_tnw_11');</script></b></span>
			</p>
			<p>
				<b><script>show_words('_tnw_12');</script> <span id="show_second">25</span> <script>show_words('_tnw_13');</script></b>
			</p>
		</td>
	</tr>
	<tr>
		<td align="center" class="CELL init-hide">
			<input type="button" id="back_btn" name="back_btn" class="ButtonSmall" onclick="location.replace(get_ip());" disabled="" value="Back" />
			<script>$('#back_btn').val(get_words('_back'));</script>
		</td>
	</tr>
</table>
</div>

<div id="wan_detecting" name="page" class="box_tn" style="display:none;">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="CT">	
			<font>&nbsp;</font>
		</td>
	</tr>
	<tr>
		<td align="center" class="CELL" style="padding:50px;">
			<b><span id="detect_msg_str"></span></b>
		</td>
	</tr>
</table>
</div>

<div id="wan_failed" name="page" class="box_tn" style="display:none;">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="CT">	
			<font>&nbsp;</font>
		</td>
	</tr>
	<tr>
		<td align="center" class="CELL" style="padding:50px;">
			<span id="msg_str"><b><script>show_words('mydlink_tx03');</script></b></span>
		</td>
	</tr>
	<tr>
		<td align="center" class="CELL">
			<input type="button" id="failed_retry" name="failed_retry" class="ButtonSmall" onclick="show_page_p0();" value="Retry" />
			<script>$('#failed_retry').val(get_words('_retry'));</script>
			<input type="button" id="failed_cancel" name="failed_cancel" class="ButtonSmall" onclick="wizard_cancel();" value="Cancel" />
			<script>$('#failed_cancel').val(get_words('_cancel'));</script>
		</td>
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