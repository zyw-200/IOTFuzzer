<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | Network | DHCP Client List</title>
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
	var is_wps;

	var main = new ccpObject();
	main.set_param_url('get_set.ccp');
	main.set_ccp_act('get');
	
	main.add_param_arg('IGD_LANDevice_i_ConnectedAddress_i_',1100);
	
	main.get_config_obj();

	var lanHostCfg = {
		'name':				main.config_str_multi("igdLanHostStatus_HostName_"),
		'ipaddr':			main.config_str_multi("igdLanHostStatus_HostIPv4Address_"),
		'mac':				main.config_str_multi("igdLanHostStatus_HostMACAddress_"),
		'type':				main.config_str_multi("igdLanHostStatus_HostAddressType_"),
		'expire':			main.config_str_multi("igdLanHostStatus_HostExpireTime_")
	};

	function padout(number) {
		return (number < 10) ? '0' + number : number;
	}

	function setValueDHCP(){
		var dhcp_list = $('#dhcp_list')
		for(var i=0;i<lanHostCfg.name.length;i++){
			var exp_str = "";
			if(lanHostCfg.type[i] == "1")
			{
				exp_str=get_words('_never');
			}
			else if(lanHostCfg.type[i] == "0" && lanHostCfg.expire[i] != '')
			{
				var wTime = parseInt(lanHostCfg.expire[i]);
				var days = Math.floor(wTime / 86400);
					wTime %= 86400;
				var hours = Math.floor(wTime / 3600);
					wTime %= 3600;
				var mins = Math.floor(wTime / 60);
					wTime %= 60;
				exp_str = days + " " + ((days <= 1) ? get_words('tt_Day'): get_words('gw_days')) + " " + hours + ":" + padout(mins) + ":" + padout(wTime);
			}
			if(exp_str!='')
				dhcp_list.append('<tr><td class="CELL">'+lanHostCfg.mac[i]+'</td><td class="CELL">'+lanHostCfg.ipaddr[i]+'</td><td class="CELL">'+exp_str+'</td></tr>');
		}
	}
$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(model);
	//WPS Config
	setValueDHCP();
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
				<script>document.write(menu.build_structure(1,1,7))</script>
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
								<div class="headerbg" id="inetDhcpTitle">
								<script>show_words('bd_DHCP');</script>
								</div>
								<div class="hr"></div>
								<div class="section_content_border">
								<div class="header_desc" id="inetDhcpIntroduction">
								<script>show_words('_desc_dhcp_client_list');</script>
								<p></p>
								</div>

<div class="box_tn">
	<div class="CT"><script>show_words('_dhcp_clients');</script></div>
	<table id="dhcp_list" border="0" cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CTS"><script>show_words('_macaddr');</script></td>
		<td class="CTS"><script>show_words('_ipaddr');</script></td>
		<td class="CTS"><script>show_words('_lb_expire_in');</script></td>
	</tr>
	</table>
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
		<td><img src="image/bg_butl.gif" width="270" height="12" /></td>
		<td><img src="image/bg_butr.gif" width="680" height="12" /></td>
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
</table><br>
</div>
</body>
</html>