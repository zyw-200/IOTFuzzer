<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | Wireless 2.4GHz | Station List</title>
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
	var submit_c	= "";
	var is_wps;
	var PIN;
	
	var main = new ccpObject();
	main.set_param_url('get_set.ccp');
	main.set_ccp_act('get');
	
	main.add_param_arg('IGD_WLANConfiguration_i_',1100);
	main.add_param_arg('IGD_WLANConfiguration_i_WLANStatus_Client_i_',1110);
	main.add_param_arg('IGD_WLANConfiguration_i_WLANStatus_Client_i_',1210);
	main.add_param_arg('IGD_WLANConfiguration_i_WLANStatus_Client_i_',1310);
	main.add_param_arg('IGD_WLANConfiguration_i_WLANStatus_Client_i_',1410);
	
	main.get_config_obj();
	
	var lanCfg = {
		'enable':			main.config_str_multi("wlanCfg_Enable_")
	};

	var clientCfg = {
		'mac':				main.config_str_multi('igdWlanHostStatus_MACAddress_'),
		'ip':				main.config_str_multi('igdWlanHostStatus_IPAddress_'),
		'mode':				main.config_str_multi('igdWlanHostStatus_Mode_'),
		'rate':				main.config_str_multi('igdWlanHostStatus_Rate_'),
		'rssi':				main.config_str_multi('igdWlanHostStatus_Signal_')
	};
	function setValueLan(){
		var lan_list = $('#lan_list')
		if(clientCfg.mac!=null)
		{
			for(var i=0;i<clientCfg.mac.length;i++){
				var tmp_mac = clientCfg.mac[i].split("/");
				var tmp_ip = clientCfg.ip[i].split("/");
				var tmp_mode = clientCfg.mode[i].split("/");
				var tmp_rate = clientCfg.rate[i].split("/");
				var tmp_rssi = clientCfg.rssi[i].split("/");
				for(var s=0;s<tmp_mac.length;s++)
					if(tmp_mac[s]!='')
						lan_list.append('<tr><td class="CELL">'+tmp_mac[s]+'</td><td class="CELL">'+tmp_mode[s]+'</td><td class="CELL">'+tmp_rate[s]+'</td><td class="CELL">'+tmp_rssi[s]+'</td></tr>');
			}
		}
	}
$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(model);
	if(lanCfg.enable[0]==1)
		$('#radioOnField').show();
	else
		$('#radioOffField').show();
	//WPS Config
	setValueLan();
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
				<script>document.write(menu.build_structure(1,2,5))</script>
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
									<script>show_words('_statlst')</script>
									</div>
									<div class="hr"></div>
									<div class="section_content_border">
									<div class="header_desc" id="basicIntroduction">
										<script>show_words('_desc_station_list')</script>
										<p></p>
									</div>

<div id="radioOnField" class="box_tn" style="display: none;">
	<div class="CT"><script>show_words('_wifiser_mode44')</script></div>
	<table id="lan_list" cellspacing="0" cellpadding="0" class="formarea">
		<tr>
			<td class="CTS"><script>show_words('_macaddr');</script></td>
			<td class="CTS"><script>show_words('_mode');</script></td>
			<td class="CTS"><script>show_words('_rate');</script></td>
			<td class="CTS"><script>show_words('_singal');</script></td>
		</tr>
	</table>
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
