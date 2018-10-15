<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | Help | Menu</title>
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
$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(dev_info.model);
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
				<script>document.write(menu.build_structure(1,8,0))</script>
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

<div class="section_content_border">
<div class="box_tn">
	<div class="CT">
	<script>show_words('_help_txt1');</script>
	</div>
	<div class="CUL">
	<ul>
		<li>
			<a href="#Network" class="normal"><script>show_words('_network');</script></a>
		</li>
		<li>
			<a href="#Wireless" class="normal"><script>show_words('_wireless');</script></a>
		</li>
		<li>
			<a href="#Advanced" class="normal"><script>show_words('_advanced');</script></a>
		</li>
		<li>
			<a href="#Administrator" class="normal"><script>show_words('ADMIN');</script></a>
		</li>
	</ul>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="Network" id="Network"><script>show_words('_network_help');</script></div>
	<div class="CUL">
	<ul>
		<li>
			<a href="/help_network.asp#WANSetting" class="normal"><script>show_words('_wan_setting');</script></a>
		</li>
		<li>
			<a href="/help_network.asp#LANSetting" class="normal"><script>show_words('_lan_setting');</script></a>
		</li>
		<li>
			<a href="/help_network.asp#IPv6Setting" class="normal"><script>show_words('_ipv6_setting');</script></a>
		</li>
		<li>
			<a href="/help_network.asp#QoSSetting" class="normal"><script>show_words('_qos_help');</script></a>
		</li>
		<li>
			<a href="/help_network.asp#DHCPClientList" class="normal"><script>show_words('bd_DHCP');</script></a>
		</li>
	</ul>
	<div align="right" style="padding:0px 3px 3px 0px; color:#000000;">
	<a href="#" class="normal"><b><script>show_words('_top');</script></b></a>
	</div>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="Wireless" id="Wireless"><script>show_words('_wireless_help');</script></div>
	<div class="CUL">
	<ul>
		<li>
			<a href="/help_wireless.asp#Basic" class="normal"><script>show_words('_basic');</script></a>
		</li>
		<li>
			<a href="/help_wireless.asp#Advanced" class="normal"><script>show_words('_advanced');</script></a>
		</li>
		<li>
			<a href="/help_wireless.asp#Security" class="normal"><script>show_words('ES_security');</script></a>
		</li>
		<li>
			<a href="/help_wireless.asp#WPS" class="normal"><script>show_words('_WPS');</script></a>
		</li>
		<li>
			<a href="/help_wireless.asp#StationList" class="normal"><script>show_words('_statlst');</script></a>
		</li>
	</ul>
	<div align="right" style="padding:0px 3px 3px 0px; color:#000000;">
	<a href="#" class="normal"><b><script>show_words('_top');</script></b></a>
	</div>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="Advanced" id="Advanced"><script>show_words('help1');</script></div>
	<div class="CUL">
	<ul>
		<li>
			<a href="/help_advanced.asp#DMZ" class="normal"><script>show_words('help488')</script></a>
		</li>
		<li>
			<a href="/help_advanced.asp#VirtualServer" class="normal"><script>show_words('VIRTUAL_SERVERS')</script></a>
		</li>
		<li>
			<a href="/help_advanced.asp#Routing" class="normal"><script>show_words('_routing');</script></a>
		</li>
		<li>
			<a href="/help_advanced.asp#AccessControl" class="normal"><script>show_words('_acccon');</script></a>
		</li>
		<li>
			<a href="/help_advanced.asp#ALG" class="normal"><script>show_words('_alg');</script></a>
		</li>
		<li>
			<a href="/help_advanced.asp#SpecialApplications" class="normal"><script>show_words('_specapps');</script></a>
		</li>
		<li>
			<a href="/help_advanced.asp#Gaming" class="normal"><script>show_words('_gaming');</script></a>
		</li>
		<li>
			<a href="/help_advanced.asp#InboundFilter" class="normal"><script>show_words('_inboundfilter');</script></a>
		</li>
		<li>
			<a href="/help_advanced.asp#Schedule" class="normal"><script>show_words('_sched');</script></a>
		</li>
		<li>
			<a href="/help_advanced.asp#AdvancedNetwork" class="normal"><script>show_words('_advnetwork');</script></a>
		</li>
	</ul>
	<div align="right" style="padding:0px 3px 3px 0px; color:#000000; ">
	<a href="#" class="normal"><b><script>show_words('_top');</script></b></a>
	</div>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="Administrator" id="Administrator"><script>show_words('_administrator_help');</script></div>
	<div class="CUL">
	<ul>
		<li>
			<a href="/help_administrator.asp#Management" class="normal"><script>show_words('_management');</script></a>
		</li>
		<li>
			<a href="/help_administrator.asp#UploadFirmware" class="normal"><script>show_words('_upload_firm');</script></a>
		</li>
		<li>
			<a href="/help_administrator.asp#SettingManagement" class="normal"><script>show_words('_settings_management');</script></a>
		</li>
		<li>
			<a href="/help_administrator.asp#Time" class="normal"><script>show_words('_time_cap');</script></a>
		</li>
		<li>
			<a href="/help_administrator.asp#Status" class="normal"><script>show_words('ES_status');</script></a>
		</li>
		<li>
			<a href="/help_administrator.asp#IPv6Status" class="normal"><script>show_words('_ipv6_status');</script></a>
		</li>
	</ul>
	<div align="right" style="padding:0px 3px 3px 0px; color:#000000; ">
	<a href="#" class="normal"><b><script>show_words('_top');</script></b></a>
	</div>
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