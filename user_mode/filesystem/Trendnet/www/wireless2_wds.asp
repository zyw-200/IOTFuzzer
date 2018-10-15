<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | Wireless 2.4GHz | WDS</title>
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
	var submit_c	= "";
	
	var main = new ccpObject();
	main.set_param_url('get_set.ccp');
	main.set_ccp_act('get');
	
	main.add_param_arg('IGD_',1000);
	
	for (i=5;i<=8;i++){
		main.add_param_arg('IGD_WLANConfiguration_i_','1'+i+'00');
		main.add_param_arg('IGD_WLANConfiguration_i_BridgeSetting_','1'+i+'00');
		main.add_param_arg('IGD_WLANConfiguration_i_BridgeSetting_RemoteMAC_i_','1'+i+'10');
		main.add_param_arg('IGD_WLANConfiguration_i_WEP_','1'+i+'10');
		main.add_param_arg('IGD_WLANConfiguration_i_WPA_','1'+i+'10');
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

	var wepCfg = {
		'authmode':			main.config_str_multi("wepInfo_AuthenticationMode_")
	}

	var wpaCfg = {
		'mode':				main.config_str_multi("wpaInfo_WPAMode_"),
		'authmode':			main.config_str_multi("wpaInfo_AuthenticationMode_")
	}

	function check_is_open_wds(idx)
	{
		if(lanCfg.sMode[idx]=='1'){//wep
			if(wepCfg.authmode[idx]=='2')//both
				return false;
		}
		else if(lanCfg.sMode[idx]=='2'){//wpa_p
			if(wpaCfg.mode[idx]=='0' && wpaCfg.authmode[idx]=='0')//auto && psk
				return false;
		}
		else if(lanCfg.sMode[idx]=='3'){//wpa_e
			return false;
		}
		else if(lanCfg.sMode[idx]=='4'){//wpa_auto
			if(wpaCfg.mode[idx]=='1')//wpa1
				return false;
		}
		return true;
	}
	function check_value()
	{
		for(var i=0;i<4;i++)
		{
			if(lanCfg.enable[i]=='1' && !check_is_open_wds(i))
			{
				alert(get_words('_wds_cant_enble'));
				return false;
			}
		}
		return true;
	}

	function check_apply()
	{
		var basic="";
		if(check_value())
		{
			var obj = new ccpObject();
			obj.set_param_url('get_set.ccp');
			obj.set_ccp_act('set');
			obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
			obj.set_param_next_page('wireless2_wds.asp');
			//Wireless Distribution System(WDS)
			var wdsEnable = $('#wds_mode').val();
			obj.add_param_arg('wlanCfg_WDSEnable_','1.5.0.0',wdsEnable);
			obj.add_param_arg('wlanCfg_WDSEnable_','1.6.0.0',wdsEnable);
			obj.add_param_arg('wlanCfg_WDSEnable_','1.7.0.0',wdsEnable);
			obj.add_param_arg('wlanCfg_WDSEnable_','1.8.0.0',wdsEnable);

			obj.add_param_arg('wlanRmMac_MACAddress_','1.5.1.1',$('#wds_mac_1').val());
			obj.add_param_arg('wlanRmMac_MACAddress_','1.5.1.2',$('#wds_mac_2').val());
			obj.add_param_arg('wlanRmMac_MACAddress_','1.5.1.3',$('#wds_mac_3').val());
			obj.add_param_arg('wlanRmMac_MACAddress_','1.5.1.4',$('#wds_mac_4').val());
			
			var paramForm = obj.get_param();
			
			totalWaitTime = 20; //second
			redirectURL = location.pathname;
			wait_page();
			jq_ajax_post(paramForm.url, paramForm.arg);
		}
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
$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(model);
	if(lanCfg.enable[0]==1)
		$('#radioOnField').show();
	else
		$('#radioOffField').show();
	//Wireless Distribution System(WDS)
	setValueWDS();
	setValueAPMACAddress();
	setEventWDS();
	
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
				<script>document.write(menu.build_structure(1,3,0))</script>
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
									<script>show_words('_wifi_bridging')</script>
									</div>
									<div class="hr"></div>
									<div class="section_content_border">
									<div class="header_desc" id="basicIntroduction">
										<p><script>show_words('_help_txt300')</script></p>
									</div>

<div id="radioOnField" style="display:none;">
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
		<td class="CL"><script>show_words('_lb_apmacaddr');</script> 1</td>
		<td class="CR"><input type="text" id="wds_mac_1" name="wds_1" size="20" maxlength="17" value="" /></td>
		</tr>
	<tr id="wds_mac_list_2" name="wds_mac_list_2" style="display: none;">
		<td class="CL"><script>show_words('_lb_apmacaddr');</script> 2</td>
		<td class="CR"><input type="text" id="wds_mac_2" name="wds_2" size="20" maxlength="17" value="" /></td>
	</tr>
	<tr id="wds_mac_list_3" name="wds_mac_list_3" style="display: none;">
		<td class="CL"><script>show_words('_lb_apmacaddr');</script> 3</td>
		<td class="CR"><input type="text" id="wds_mac_3" name="wds_3" size="20" maxlength="17" value="" /></td>
	</tr>
	<tr id="wds_mac_list_4" name="wds_mac_list_4" style="display: none;">
		<td class="CL"><script>show_words('_lb_apmacaddr');</script> 4</td>
		<td class="CR"><input type="text" id="wds_mac_4" name="wds_4" size="20" maxlength="17" value="" /></td>
	</tr>
		<input type="hidden" name="wds_list" value="1" />
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