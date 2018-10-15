<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | Help | Administrator</title>
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
				<script>document.write(menu.build_structure(1,8,4))</script>
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
	<script>show_words('_administrator_help');</script>
	</div>
	<div class="CUL">
	<ul>
		<li>
			<a href="#Management" class="normal"><script>show_words('_management');</script></a>
		</li>
		<li>
			<a href="#UploadFirmware" class="normal"><script>show_words('_upload_firm');</script></a>
		</li>
		<li>
			<a href="#SettingManagement" class="normal"><script>show_words('_settings_management');</script></a>
		</li>
		<li>
			<a href="#Time" class="normal"><script>show_words('_time_cap');</script></a>
		</li>
		<li>
			<a href="#Status" class="normal"><script>show_words('ES_status');</script></a>
		</li>
		<li>
			<a href="#IPv6Status" class="normal"><script>show_words('_ipv6_status');</script></a>
		</li>
	</ul>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="Management" id="Management"><script>show_words('_management');</script></div>
	<div class="CUL">
	<dl>
		<dt><script>show_words('_password_admin');</script></dt>
		<dd>
		<script>show_words('_help_txt202');</script>
		</dd>
		<dt><script>show_words('DEVICE_NAME');</script></dt>
		<dd>
		<script>show_words('_help_txt203');</script>
		</dd>
		<dt><script>show_words('td_EnDDNS');</script></dt>
		<dd>
		<script>show_words('_help_txt204');</script>
		</dd>
		<dt><script>show_words('_help_txt205');</script></dt>
		<dd>
		<script>show_words('_help_txt206');</script>
		</dd>
		<dt><script>show_words('_hostname');</script></dt>
		<dd>
		<script>show_words('_help_txt207');</script>
		</dd>
		<dt><script>show_words('_help_txt208');</script></dt>
		<dd>
		<script>show_words('_help_txt209');</script>
		</dd>
		<dt><script>show_words('_password');</script></dt>
		<dd>
		<script>show_words('_help_txt210');</script>
		</dd>
	</dl>
	<div align="right" style="padding:0px 3px 3px 0px; color:#000000; ">
	<a href="#" class="normal"><b><script>show_words('_top');</script></b></a>
	</div>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="UploadFirmware" id="UploadFirmware"><script>show_words('_upload_firm');</script></div>
	<div class="CUL">
	<dl>
		<dt id="h.adm.15"><script>show_words('_upload_firm');</script></dt>
		<dd id="h.adm.16">
		<script>show_words('_help_txt211');</script>
		</dd>
	</dl>
	<div align="right" style="padding:0px 3px 3px 0px; color:#000000; ">
	<a href="#" class="normal"><b><script>show_words('_top');</script></b></a>
	</div>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="SettingManagement" id="SettingManagement"><script>show_words('_settings_management');</script></div>
	<div class="CUL">
	<dl>
		<dt id="h.adm.17"><script>show_words('_help_txt212');</script></dt>
		<dd id="h.adm.18">
		<script>show_words('_help_txt213');</script>
		</dd>
		<dt id="h.adm.19"><script>show_words('_help_txt214');</script></dt>
		<dd id="h.adm.20">
		<script>show_words('_help_txt215');</script>
		</dd>
		<dt id="h.adm.21"><script>show_words('_help_txt216');</script></dt>
		<dd id="h.adm.22">
		<script>show_words('_help_txt217');</script>
		</dd>
		<dt id="h.adm.23"><script>show_words('_help_txt218');</script></dt>
		<dd id="h.adm.24">
		<script>show_words('_help_txt219');</script>
		</dd>
	</dl>
	<div align="right" style="padding:0px 3px 3px 0px; color:#000000; ">
	<a href="#" class="normal"><b><script>show_words('_top');</script></b></a>
	</div>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="Time" id="Time"><script>show_words('_time_cap');</script></div>
	<div class="CUL">
	<dl>
		<dt id="h.adm.25"><script>show_words('tt_TimeConf');</script></dt>
		<dt id="h.adm.26"><script>show_words('tt_CurTime');</script></dt>
		<dd id="h.adm.27">
		<script>show_words('_help_txt220');</script>
		</dd>
		<dt id="h.adm.28"><script>show_words('tt_TimeZ');</script></dt>
		<dd id="h.adm.29">
		<script>show_words('_help_txt221');</script>
		</dd>
		<dt id="h.adm.30"><script>show_words('tt_auto');</script></dt>
		<dt id="h.adm.31"><script>show_words('tt_EnNTP');</script></dt>
		<dd id="h.adm.32">
		<script>show_words('_help_txt222');</script>
		</dd>
		<dt id="h.adm.33"><script>show_words('tt_NTPSrvU');</script></dt>
		<dd id="h.adm.34">
		<script>show_words('_help_txt223');</script>
		</dd>
		<dt id="h.adm.35"><script>show_words('tt_StDT');</script></dt>
		<dd id="h.adm.36">
		<script>show_words('_help_txt224');</script>
		</dd>
	</dl>
	<div align="right" style="padding:0px 3px 3px 0px; color:#000000; ">
	<a href="#" class="normal"><b><script>show_words('_top');</script></b></a>
	</div>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="Status" id="Status"><script>show_words('ES_status');</script></div>
	<div class="CUL">
	<dl>
		<dd id="h.adm.37">
		<script>show_words('_help_txt225');</script>
		</dd>
	</dl>
	<div align="right" style="padding:0px 3px 3px 0px; color:#000000; ">
	<a href="#" class="normal"><b><script>show_words('_top');</script></b></a>
	</div>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="IPv6Status" id="IPv6Status"><script>show_words('_ipv6_status');</script></div>
	<div class="CUL">
	<dl>
		<dd id="h.adm.38">
		The IPv6 status.
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