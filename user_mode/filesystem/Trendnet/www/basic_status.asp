<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | Basic | Status</title>
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
	var has_usb		= '1';

	var main = new ccpObject();
	main.set_param_url('get_set.ccp');
	main.set_ccp_act('get');
	main.add_param_arg('IGD_LANDevice_i_ConnectedAddress_i_',1100);
	main.add_param_arg('IGD_WLANConfiguration_i_',1000);
	main.add_param_arg('IGD_WANDevice_i_WANStatus_',1110);
	for (var i=1; i<=8; i++)
	{
		main.add_param_arg('IGD_WLANConfiguration_i_WEP_','1'+i+'10');
		main.add_param_arg('IGD_WLANConfiguration_i_WPS_','1'+i+'10');
		main.add_param_arg('IGD_WLANConfiguration_i_WPA_','1'+i+'10');
		main.add_param_arg('IGD_WLANConfiguration_i_WLANStatus_','1'+i+'10');
	}
	main.get_config_obj();

	var wifiCfg = {
		'enable':			main.config_str_multi("igdWlanStatus_WlanEnable_"),
		'ssid':				main.config_str_multi("wlanCfg_SSID_"),
		'secu':				main.config_str_multi("wlanCfg_SecurityMode_"),
		'routingzone':		main.config_str_multi("wlanCfg_RouteBetweenZone_")
	};
	var lanHostCfg = {
		'name':				main.config_str_multi("igdLanHostStatus_HostName_")||"",
		'ipaddr':			main.config_str_multi("igdLanHostStatus_HostIPv4Address_")||"",
		'mac':				main.config_str_multi("igdLanHostStatus_HostMACAddress_")||"",
		'type':				main.config_str_multi("igdLanHostStatus_HostAddressType_")||"",
		'expire':			main.config_str_multi("igdLanHostStatus_HostExpireTime_")||""
	};
	var wepCfg = {
		'keyLength':		main.config_str_multi("wepInfo_KeyLength_"),
		'authType':			main.config_str_multi("wepInfo_AuthenticationMode_")
	};
	var wpaCfg = {
		'wpamode' :			main.config_str_multi("wpaInfo_WPAMode_"),
		'auth' :			main.config_str_multi("wpaInfo_AuthenticationMode_"),
		'wpacipher' :		main.config_str_multi("wpaInfo_EncryptionMode_")
	};
	var cable_stats = main.config_val("igdWanStatus_CableStatus_");

function setValueInternet(){
	var obj = new ccpObject();
	obj.set_param_url('ping.ccp');
	obj.set_ccp_act('queryWanConnect');
	
	var func = function(data){
		obj.gConfig = data;
		var inet_val = obj.config_val("WANisReady");
		if(cable_stats!='Connected')
		{
			$('#icon_nocableinet').show();
			$('#icon_noinet').hide();
			$('#icon_hasinet').hide();
			$('#inet_connection').html(get_words('_no_inet_connection'));
		}
		else if(inet_val=='true')
		{
			$('#icon_nocableinet').hide();
			$('#icon_noinet').hide();
			$('#icon_hasinet').show();
			$('#inet_connection').html(get_words('_has_inet_connection'));
		}
		else
		{
			$('#icon_nocableinet').hide();
			$('#icon_noinet').show();
			$('#icon_hasinet').hide();
			$('#inet_connection').html(get_words('_inet_Orange'));
		}
	};
	obj.ajax_submit(true, func);
}
function setValueGuestNetwork(){
	var ssid_val = wifiCfg.ssid[3];
	var ssid1_val = wifiCfg.ssid[7];
	var enable_val = wifiCfg.enable[3];
	var enable1_val = wifiCfg.enable[7];
	$('#gn_ssid_24g').html((enable_val=='1'?ssid_val:get_words('_disabled')));
	$('#gn_ssid_5g').html((enable1_val=='1'?ssid1_val:get_words('_disabled')));
	if((enable_val=='1')||(enable1_val=='1'))
	{
		$('#icon_nogn').hide();
		$('#icon_hasgn').show();
	}
	else
	{
		$('#icon_nogn').show();
		$('#icon_hasgn').hide();
	}
}
function setValueUSB(){
	if(has_usb=='1')
		$('#usb_field').show();

	var diskObj = new ccpObject();
	var param1 = {
		url: "web_access.ccp",
		arg: "ccp_act=get_disk_info&file_type=3&get_path=/"
	};
	diskObj.get_config_obj(param1);
	var disk_info = diskObj.config_str_multi("disk_info");
	if(disk_info != null)	//success
	{
		$('#icon_nousb').hide();
		$('#icon_hasusb').show();
		$('#usb_status').html(get_words('_usb_connected'));
	} else { //error
		$('#icon_nousb').show();
		$('#icon_hasusb').hide();
		$('#usb_status').html(get_words('_no_usb_connected'));
	}
}
function setValueWirelessSSID(){
	var ssid_val = wifiCfg.ssid[0];
	var ssid1_val = wifiCfg.ssid[4];
	var enable_val = wifiCfg.enable[0];
	var enable1_val = wifiCfg.enable[4];
	$('#ssid_24g').html((enable_val=='1'?ssid_val:get_words('_disabled')));
	$('#ssid_5g').html((enable1_val=='1'?ssid1_val:get_words('_disabled')));
	if((enable_val=='1') || (enable1_val=='1'))
	{
		$('#icon_nossid').hide();
		$('#icon_hasssid').show();
	}
	else
	{
		$('#icon_nossid').show();
		$('#icon_hasssid').hide();
	}
}
function setValueWirelessSecurity(){
	var authType = [];
	var ao = {'0':0,'1':4};
	for(var j in ao){
		var i = ao[j];
		if(wifiCfg.secu[i]==0)//None
		{
			authType[j] = get_words('_none');
		}
		else if(wifiCfg.secu[i]==1)//WEP_MODE
		{
			if(wepCfg.authType[i] == 0)//OPEN
			{
				authType[j] = get_words('_wifiser_mode0');
			}
			else if(wepCfg.authType[i] == 1)//SHARE
			{
				authType[j] = get_words('_wifiser_mode1');
			}
			else if(wepCfg.authType[i] == 2)//AUTO
			{
				authType[j] = get_words('_wifiser_mode2');
			}
		}
		else if(wifiCfg.secu[i]==2)//WPA_P
		{
			if(wpaCfg.wpamode[i] == 0)//AUTO
				authType[j] = get_words('_wifiser_mode5');
			if(wpaCfg.wpamode[i] == 1)//WPA2
				authType[j] = get_words('_wifiser_mode4');
			if(wpaCfg.wpamode[i] == 2)//WPA
				authType[j] = get_words('_wifiser_mode3');
		}
		else if(wifiCfg.secu[i]==3)//WPA_E
		{
			if(wpaCfg.wpamode[i] == 0)//AUTO
				authType[j] = get_words('_wifiser_mode7');
			if(wpaCfg.wpamode[i]==1)//WPA2
				authType[j] = get_words('_wifiser_mode6')+ "-" + get_words('LW23');
			if(wpaCfg.wpamode[i]==2)//WPA
				authType[j] = get_words('_WPA')+ "-" + get_words('LW23');
		}
		else if(wifiCfg.secu[i]==4)//WPA_AUTO
		{
			if(wpaCfg.auth[i] == 0)//PSK
			{
				authType[j] = get_words('_wifiser_mode5');
			}
			else if(wpaCfg.auth[i] == 1)//EAP
			{
			authType[j] = get_words('_wifiser_mode7');
			}
		}
	}
	$('#secu_24g').html(authType[0])
	$('#secu_5g').html(authType[1])
}
function setValueWirelessSecurityLevel(){
	var level = [];
	var ao = {'0':0,'1':4};
	for(var j in ao){
		var i = ao[j];
		if(wifiCfg.secu[i]==0)//None
		{
			level[j] = get_words('_security_no');
		}
		else if(wifiCfg.secu[i]==1)//WEP_MODE
		{
			level[j] = get_words('_security_low');
		}
		else if(wifiCfg.secu[i]==2 || wifiCfg.secu[i]==3)//WPA_P, WPA_E
		{
			if(wpaCfg.wpamode[i] == 0)//AUTO
				level[j] = get_words('_security_high');
			if(wpaCfg.wpamode[i] == 1)//WPA2
				level[j] = get_words('_security_high');
			if(wpaCfg.wpamode[i] == 2)//WPA
				level[j] = get_words('_security_middle');
		}
		else if(wifiCfg.secu[i]==4)//WPA_AUTO
		{
			level[j] = get_words('_security_high');
		}
	}
	$('#secu_level_24g').html(level[0]);
	$('#secu_level_5g').html(level[1]);
}
function setValueConnectedDevices(){
	$('#connected_devices').html('<div class="hr2"></div>');
	for(var i=0;i<lanHostCfg.ipaddr.length;i++){
		if(lanHostCfg.ipaddr[i]!='')
		{
			$('#connected_devices').append('<span style="width:154px;display:inline-block;text-align:center;" title="'+lanHostCfg.mac[i]+"\n"+lanHostCfg.ipaddr[i]+'"><img src="/image/icons_connected_devices.png" /><p style="font-size:10px;">'+(lanHostCfg.name[i]||'&nbsp;')+'</p></span>');
			//todo: hover show tips
			//' / '+lanHostCfg.mac[i]+' / '+lanHostCfg.ipaddr[i]+
			if(i%2==1 && (lanHostCfg.ipaddr.length-1)>i)
				$('#connected_devices').append('<div class="hr2"></div>');
		}
	}
}
$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(model);
	//Internet
	setValueInternet();
	
	//GuestNetwork
	setValueGuestNetwork();

	//USB
	setValueUSB();

	//Wireless
	setValueWirelessSSID();
	setValueWirelessSecurity();
	setValueWirelessSecurityLevel();
	
	//ConnectedDevices
	setValueConnectedDevices();
	
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
			<td style="background-image:url('/image/bg_l.gif');background-repeat:repeat-y;vertical-align:top;width:" width="270">
				<div style="padding-left:6px;">
				<script>document.write(menu.build_structure(0,0,-1));</script>
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
								<script>show_words('_networkstate');</script>
								</div>
								<div class="hr"></div>
								<div class="section_content_border">
<div style="float:left; min-height:300px">
	<div class="boxshadow" style="width:250px; height:100%;">
		<div class="h4"><script>show_words('sa_Internet');</script></div>
		<div class="hr2"></div>
		<table width="100%" border="0" cellpadding="3" cellspacing="1">
		<tr>
			<td width="16%"><img src="/image/icons_internet_2.png" class="img-swap" id="icon_nocableinet" style="display:none;" /><img src="/image/icons_internet_0.png" class="img-swap" id="icon_noinet" style="display:none;" /><img src="/image/icons_internet_1.png" class="img-swap" id="icon_hasinet" style="display:none;" /></td>
			<td width="84%" id="inet_connection"></td>
		</tr>
		</table>
	</div>
	<div class="boxshadow" style="width:250px;height:100%;">
		<div class="h4"><script>show_words('_guest_network');</script></div>
		<div class="hr2"></div>
		<table width="100%" border="0" cellpadding="3" cellspacing="1">
		<tr>
			<td width="16%"><img src="/image/icons_guest_network_0.png" class="img-swap" id="icon_nogn" style="display:none;" /><img src="/image/icons_guest_network_1.png" class="img-swap" id="icon_hasgn" style="display:none;" /></td>
			<td width="84%">
				<strong><script>show_words('KR16');</script></strong> <script>show_words('_guest_network');</script> <strong>(<script>show_words('_ssid');</script>)</strong>: <span id="gn_ssid_24g" class="break_word"></span><p style="line-height:5px;">&nbsp;</p>
				<strong><script>show_words('KR17');</script></strong> <script>show_words('_guest_network');</script> <strong>(<script>show_words('_ssid');</script>)</strong>: <span id="gn_ssid_5g" class="break_word"></span>
			</td>
		</tr>
		</table>
	</div>
	<div id="usb_field" class="boxshadow" style="width:220px;height:100%;display:none;">
		<div class="h4"><script>show_words('_usb');</script></div><div class="hr2"></div>
		<table width="100%" border="0" cellpadding="3" cellspacing="1">
		<tr>
			<td width="16%"><img src="/image/icons_usb_0.png" class="img-swap" id="icon_nousb" style="display:none;" /><img src="/image/icons_usb_1.png" class="img-swap" id="icon_hasusb" style="display:none;" /></td>
			<td width="84%" id="usb_status"></td>
		</tr>
		</table>
	</div>
  
</div>
<div style="float:right;min-height:300px">
	<div class="boxshadow" style="width:330px;height:100%;">
		<div class="h4"><script>show_words('_wireless');</script></div>
		<div class="hr2"></div>
		<table width="100%" border="0" cellpadding="3" cellspacing="1">
		<tr>
			<td width="16%"><img src="/image/icons_wireless_ssid_red.png" class="img-swap" id="icon_nossid" style="width:60px;" /><img src="/image/icons_wireless_ssid_green.png" class="img-swap" id="icon_hasssid" style="width:60px;display:none;" /></td>
			<td width="84%">
			<b><script>show_words('KR16');</script> <script>show_words('_name_ssid');</script>: </b><div id="ssid_24g" style="display:inline-block;" class="break_word"></div><p style="line-height:5px;">&nbsp;</p>
			<b><script>show_words('KR17');</script> <script>show_words('_name_ssid');</script>: </b><div id="ssid_5g" style="display:inline-block;" class="break_word"></div>
			</td>
		</tr>
		</table>
		<div class="hr2"></div>
		<table width="100%" border="0" cellpadding="3" cellspacing="1">
		<tr>
			<td width="16%" valign="top"><img src="/image/icons_wireless_security.png" /></td>
			<td width="84%" valign="top"><p><b><script>show_words('KR16');</script>: </b><span id="secu_24g"></span> <span id="secu_level_24g"></span><br>
			<b><script>show_words('KR17');</script>: </b><span id="secu_5g"></span> <span id="secu_level_5g"></span></p></td>
		</tr>
		</table>
	</div>
	<div class="boxshadow" style="width:330px;height:100%;">
		<div class="h4"><script>show_words('_connected_devices');</script></div>
		<span id="connected_devices">
		</span>
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