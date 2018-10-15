<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | Help | Network</title>
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
				<script>document.write(menu.build_structure(1,8,1))</script>
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
	<script>show_words('_network_help');</script>
	</div>
	<div class="CUL">
	<ul>
		<li>
			<a href="#WANSetting" class="normal"><script>show_words('_wan_setting');</script></a>
		</li>
		<li>
			<a href="#LANSetting" class="normal"><script>show_words('_lan_setting');</script></a>
		</li>
		<li>
			<a href="#IPv6Setting" class="normal"><script>show_words('_ipv6_setting');</script></a>
		</li>
		<li>
			<a href="#QoSSetting" class="normal"><script>show_words('_qos_help');</script></a>
		</li>
		<li>
			<a href="#DHCPClientList" class="normal"><script>show_words('bd_DHCP');</script></a>
		</li>
	</ul>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="WANSetting" id="WANSetting"><script>show_words('_wan_setting');</script></div>
	<div class="CUL">
	<dl>
		<dt id="h.n.1">WAN <script>show_words('_wan_conn_type');</script></dt>
		<dd id="h.n.2">
		<script>show_words('_help_txt2');</script>
		</dd>
		<dt id="h.n.3"><script>show_words('_static');</script></dt>
		<dd id="h.n.4">
		<script>show_words('_help_txt4');</script>
		</dd>
		<dt>DHCP</dt>
		<dt id="h.n.5"><script>show_words('_DHCP');</script></dt>
		<dd id="h.n.6">
		<script>show_words('_help_txt6');</script>
		</dd>
		<dd>
		<script>show_words('_help_txt7');</script>
		</dd>
		<dt id="h.n.8"><script>show_words('_PPPoE');</script></dt>
		<dd id="h.n.9">
		<script>show_words('_help_txt9');</script>
		</dd>
		<dd id="h.n.10">
		<script>show_words('_help_txt10');</script>
		</dd>
		<dd id="h.n.11">
		<script>show_words('_help_txt11');</script>
		</dd>
		<dd id="h.n.12">
		<script>show_words('_help_txt12');</script>
		</dd>
		<dd id="h.n.13">
		<script>show_words('_help_txt13');</script>
		</dd>
		<dd id="h.n.14">
		<script>show_words('_help_txt14');</script>
		</dd>
		<dt id="h.n.15"><script>show_words('_L2TP');</script></dt>
		<dd id="h.n.16">
		<script>show_words('_help_txt16');</script>
		</dd>
		<dd id="h.n.17">
		<script>show_words('_help_txt17');</script>
		</dd>
		<dd id="h.n.18">
		<script>show_words('_help_txt18');</script>
		</dd>
		<dd id="h.n.19">
		<script>show_words('_help_txt19');</script>
		</dd>
		<dd id="h.n.20">
		<script>show_words('_help_txt20');</script>
		</dd>
		<dd id="h.n.21">
		<script>show_words('_help_txt21');</script>
		</dd>
		<dd id="h.n.22">
		<script>show_words('_help_txt22');</script>
		</dd>
		<dd id="h.n.23">
		<b><script>show_words('_help_txt34');</script></b>
		</dd>
		<dd id="h.n.24">
		<script>show_words('_help_txt24');</script>
		</dd>
		<dd id="h.n.25">
		<script>show_words('_help_txt25');</script>
		</dd>
		<dt id="h.n.26"><script>show_words('_PPTP');</script></dt>
		<dd id="h.n.27">
		<script>show_words('_help_txt27');</script>
		</dd>
		<dd id="h.n.28">
		<script>show_words('_help_txt28');</script>
		</dd>
		<dd id="h.n.29">
		<script>show_words('_help_txt29');</script>
		</dd>
		<dd id="h.n.30">
		<script>show_words('_help_txt30');</script>
		</dd>
		<dd id="h.n.31">
		<script>show_words('_help_txt31');</script>
		</dd>
		<dd id="h.n.32">
		<script>show_words('_help_txt32');</script>
		</dd>
		<dd id="h.n.33">
		<script>show_words('_help_txt33');</script>
		</dd>
		<dd>
		<b id="h.n.34"><script>show_words('_help_txt34');</script></b>
		</dd>
		<dd id="h.n.35">
		<script>show_words('_help_txt35');</script>
		</dd>
		<dd id="h.n.36">
		<script>show_words('_help_txt36');</script>
		</dd>
		<dt id="h.n.37"><script>show_words('_help_txt37');</script></dt>
		<dd id="h.n.38">
		<script>show_words('_help_txt38');</script>
		</dd>
	</dl>
	<div align="right" style="padding:0px 3px 3px 0px; color:#000000; ">
	<a href="#" class="normal"><b><script>show_words('_top');</script></b></a>
	</div>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="LANSetting" id="LANSetting"><script>show_words('_lan_setting');</script></div>
	<div class="CUL">
	<dl>
		<dt id="h.n.39"><script>show_words('_help_txt39');</script></dt>
		<dt id="h.n.40"><script>show_words('_ipaddr');</script></dt>
		<dd id="h.n.41">
		<script>show_words('_help_txt41');</script>
		</dd>
		<dt id="h.n.42"><script>show_words('_subnet');</script></dt>
		<dd id="h.n.43">
		<script>show_words('_help_txt43');</script>
		</dd>
		<dt id="h.n.44"><script>show_words('bd_title_DHCPSSt');</script></dt>
		<dd id="h.n.45">
		<script>show_words('_help_txt45');</script>
		</dd>
		<dt id="h.n.46"><script>show_words('bd_EDSv');</script></dt>
		<dd id="h.n.47">
		<script>show_words('_help_txt47');</script>
		</dd>
		<dd id="h.n.48">
		<script>show_words('_help_txt48');</script>
		</dd>
		<dt id="h.n.49"><script>show_words('bd_DIPAR');</script></dt>
		<dd id="h.n.50">
		<script>show_words('_help_txt50');</script>
		</dd>
		<dd id="h.n.51">
		<script>show_words('_help_txt51');</script>
		</dd>
		<dd id="h.n.52">
		<script>show_words('_help_txt52');</script>
		</dd>
		<dt id="h.n.53"><script>show_words('_subnet');</script></dt>
		<dd id="h.n.54">
		<script>show_words('_help_txt54');</script>
		</dd>
		<dt id="h.n.55"><script>show_words('_gateway');</script></dt>
		<dd id="h.n.56">
		<script>show_words('_help_txt56');</script>
		</dd>
		<dt id="h.n.57"><script>show_words('bd_DLT');</script></dt>
		<dd id="h.n.58">
		<script>show_words('_help_txt58');</script>
		</dd>
		<dt id="h.n.59"><script>show_words('_help_txt59');</script></dt>
		<dd id="h.n.60">
		<script>show_words('_help_txt60');</script>
		</dd>
		<dt id="h.n.61"><script>show_words('bd_CName');</script></dt>
		<dd id="h.n.62">
		<script>show_words('_help_txt62');</script>
		</dd>
		<dt id="h.n.63"><script>show_words('_ipaddr');</script>:</dt>
		<dd id="h.n.64">
		<script>show_words('_help_txt64');</script>
		</dd>
		<dt id="h.n.65"><script>show_words('_macaddr');</script></dt>
		<dd id="h.n.66">
		<script>show_words('_help_txt66');</script>
		</dd>
		<dd id="h.n.67">
		<script>show_words('_help_txt67');</script>
		</dd>
		<dt id="h.n.68"><script>show_words('_clear');</script></dt>
		<dd id="h.n.69">
		<script>show_words('_help_txt69');</script>
		</dd>
		<dt id="h.n.70"><script>show_words('bd_title_list');</script></dt>
		<dd id="h.n.71">
		<script>show_words('_help_txt71');</script>
		</dd>
	</dl>
	<div align="right" style="padding:0px 3px 3px 0px; color:#000000; ">
	<a href="#" class="normal"><b><script>show_words('_top');</script></b></a>
	</div>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="IPv6Setting" id="IPv6Setting"><script>show_words('_ipv6_setting');</script></div>
	<div class="CUL">
	<dl>
		<dt><script>show_words('IPV6_TEXT29a');</script></dt>
		<dd>
		<script>show_words('_help_txt85');</script>
		</dd>
		<dt id="h.n.86"><b><script>show_words('_static');</script></b></dt><dt>
		</dt><dd id="h.n.87"><script>show_words('_help_txt87');</script>
		</dd>
		<dt id="h.n.82"><b><script>show_words('_help_txt82');</script></b></dt><dt>
		</dt><dd id="h.n.83"><script>show_words('_help_txt83');</script>
		</dd>
		<dt id="h.n.84"><b><script>show_words('_help_txt84');</script></b> </dt><dt>
		</dt><dd id="h.n.85"><script>show_words('_help_txt85');</script>
		</dd>
		<dt id="h.n.110"><b><script>show_words('_help_txt110');</script></b> </dt><dt>
		</dt><dd id="h.n.111"><script>show_words('_help_txt111');</script>
		</dd>
		<dt id="h.n.112"><b><script>show_words('_PPPoE');</script></b> </dt><dt>
		</dt><dd id="h.n.113"><script>show_words('_help_txt113');</script>
		</dd>
		<dd id="h.n.114">
		<script>show_words('_help_txt114');</script>
		</dd>
		<dd id="h.n.115">
		<script>show_words('_help_txt115');</script>
		</dd>
		<dt id="h.n.88"><b><script>show_words('IPV6_TEXT36');</script></b></dt><dt> 
		</dt><dd id="h.n.89"><script>show_words('_help_txt89');</script>
		</dd>
		<dt id="h.n.90"><b><script>show_words('_help_txt90');</script></b></dt><dt>
		</dt><dd id="h.n.91"><script>show_words('_help_txt91');</script>
		</dd>
		<dt id="h.n.92"><b><script>show_words('IPV6_TEXT139');</script></b></dt><dt>
		</dt><dd id="h.n.93"><script>show_words('_help_txt93');</script>
		</dd>
		<dt><script>show_words('_help_txt94');</script></dt>
		<dd>
		<script>show_words('_help_txt95');</script>
		</dd>
		<dt><script>show_words('_help_txt96');</script></dt>
		<dd>
		<script>show_words('_help_txt97');</script>
		</dd>
		<dt><script>show_words('_help_txt98');</script></dt>
		<dd>
		<script>show_words('_help_txt99');</script>
		</dd>
		<dd>
		<script>show_words('_help_txt100');</script>
		</dd>
		<dd>
		<script>show_words('_help_txt101');</script>
		</dd>
		<dd>
		<script>show_words('_help_txt102');</script>
		</dd>
		<dd>
		<script>show_words('_help_txt103');</script>
		</dd>
		<dd>
		<script>show_words('_help_txt104');</script>
		</dd>
	</dl>
	<div align="right" style="padding:0px 3px 3px 0px; color:#000000; ">
	<a href="#" class="normal"><b><script>show_words('_top');</script></b></a>
	</div>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="QoSSetting" id="QoSSetting"><script>show_words('_help_txt105');</script></div>
	<div class="CUL">
	<dl>
		<dt><script>show_words('_help_txt106');</script></dt>
		<dd>
		<script>show_words('_help_txt107');</script>
		</dd>
		<dt><script>show_words('_help_txt108');</script></dt>
		<dd>
		<script>show_words('_help_txt109');</script>
		</dd>
		<dt><script>show_words('_help_txt116');</script></dt>
		<dd>
		<script>show_words('_help_txt117');</script>
		</dd>
	</dl>
	<div align="right" style="padding:0px 3px 3px 0px; color:#000000; ">
	<a href="#" class="normal"><b><script>show_words('_top');</script></b></a>
	</div>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="DHCPClientList" id="DHCPClientList"><script>show_words('bd_DHCP');</script></div>
	<div class="CUL">
	<dl>
		<dt id="h.n.72"><script>show_words('bd_DHCP');</script></dt>
		<dd id="h.n.73">
		<script>show_words('_help_txt73');</script>
		</dd>
	</dl>
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