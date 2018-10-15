<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | Help | Wireless</title>
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
				<script>document.write(menu.build_structure(1,8,2))</script>
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
	<script>show_words('_wireless_help');</script>
	</div>
	<div class="CUL">
	<ul>
		<li>
			<a href="#Basic" class="normal"><script>show_words('_basic');</script></a>
		</li>
		<li>
			<a href="#Advanced" class="normal"><script>show_words('_advanced');</script></a>
		</li>
		<li>
			<a href="#Security" class="normal"><script>show_words('ES_security');</script></a>
		</li>
		<li>
			<a href="#WPS" class="normal"><script>show_words('_WPS');</script></a>
		</li>
		<li>
			<a href="#StationList" class="normal"><script>show_words('_statlst');</script></a>
		</li>
	</ul>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="Basic" id="Basic"><script>show_words('_basic');</script></div>
	<div class="CUL">
	<dl>
		<dt id="h.w.1"><script>show_words('_help_txt121');</script></dt>
		<dd id="h.w.2">
		<script>show_words('_help_txt122');</script></dd>
		<dt id="h.w.3"><script>show_words('WLANMODE');</script></dt>
		<dd id="h.w.4">
		<script>show_words('_help_txt123');</script>
		<script>show_words('_help_txt124');</script>
		</dd>
		<dd id="h.w.5">
		<script>show_words('_help_txt125');</script>
		</dd>
		<dt id="h.w.6"><script>show_words('_help_txt126');</script></dt>
		<dd id="h.w.7">
		<script>document.write(addstr(get_words('_help_txt127'), dev_info.model));</script>
		</dd>
		<dd id="h.w.8">
		<script>document.write(addstr(get_words('_help_txt128'), dev_info.model));</script>
		</dd>
		<dd id="h.w.87">
		<script>document.write(addstr(get_words('_help_txt129'), dev_info.model));</script>
		</dd>
		<dd id="h.w.88">
		<script>document.write(addstr(get_words('_help_txt130'), dev_info.model, dev_info.model, dev_info.model, dev_info.model));</script>
		</dd>
		<dd id="h.w.9">
		<script>document.write(addstr(get_words('_help_txt131'), dev_info.model));</script>
		</dd>
		<dd id="h.w.10">
		<script>show_words('_help_txt132');</script>
		</dd>
		<dt id="h.w.11"><script>show_words('wwl_wnn');</script></dt>
		<dd id="h.w.12">
		<script>show_words('_help_txt133');</script>
		</dd>
		<dt id="h.w.13"><script>show_words('_help_txt134');</script></dt>
		<dd id="h.w.14">
		<script>show_words('_help_txt135');</script>
		</dd>
		<dt id="h.w.15"><script>show_words('_help_txt136');</script></dt>
		<dd id="h.w.16">
		<script>show_words('_help_txt137');</script>
		</dd>
		<dt id="h.w.17"><script>show_words('_help_txt138');</script></dt>
		<dd id="h.w.18">
		<script>show_words('_help_txt139');</script>
		</dd>
		<dt id="h.w.19"><script>show_words('_help_txt140');document.write(dev_info.model);</script></dt>
		<dd id="h.w.20">
		<script>show_words('_help_txt141');document.write(dev_info.model);</script>
		</dd>
		<dt id="h.w.21"><script>show_words('enable_WDS');</script></dt>
		<dd id="h.w.22">
		<script>show_words('_help_txt142');</script>
		</dd>
		<dt id="h.w.23"><script>show_words('aw_WDSMAC');</script></dt>
		<dd id="h.w.24">
		<script>show_words('_help_txt143');</script>
		</dd>
		<dt id="h.w.25"><script>show_words('_help_txt144');</script></dt>
		<dd id="h.w.26">
		<script>show_words('_help_txt145');</script>
		</dd>
		<dd id="h.w.27">
		<b><script>show_words('_help_txt146');</script></b><br>
		?? <script>show_words('_help_txt147');</script><br>
		?? <script>show_words('_help_txt148');</script>
		</dd>
		<dd>
		<b id="h.w.28"><script>show_words('_help_txt147');</script>:</b>
		<script>show_words('_help_txt149');</script>
		</dd>
		<dd>
		<b id="h.w.30"><script>show_words('_help_txt148');</script>:</b>
		<script>show_words('_help_txt150');</script>
		</dd>
		<dd>
		<b id="h.w.32"><script>show_words('_help_txt151');</script>:</b><br>
		<script>show_words('_help_txt152');</script><br>
		?? 20<br>
		?? 20/40<br>
		<script>show_words('_help_txt153');</script><br>
		<script>show_words('_help_txt154');</script>
		</dd>
		<dd>
		<b id="h.w.34"><script>show_words('_help_txt155');</script>:</b><br>
		<script>show_words('_help_txt156');</script><br>
		?? <script>show_words('_help_txt157');</script><br>
		?? <script>show_words('KR50');</script><br>
		<script>show_words('_help_txt158');</script><br>
		<script>show_words('_help_txt159');</script>
		</dd>
		<dd>
		<b id="h.w.36"><script>show_words('_help_txt160');</script>:</b><br>
		<script>show_words('_help_txt161');</script>
		</dd>
	</dl>
	<div align="right" style="padding:0px 3px 3px 0px; color:#000000; ">
	<a href="#" class="normal"><b><script>show_words('_top');</script></b></a>
	</div>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="Advanced" id="Advanced"><script>show_words('_advanced');</script></div>
	<div class="CUL">
	<dl>
		<dt id="h.w.38"><script>show_words('_help_txt162');</script></dt>
		<dd id="h.w.39">
		<script>show_words('_help_txt163');</script>
		</dd>
		<dt id="h.w.40"><script>show_words('_help_txt164');</script></dt>
		<dd id="h.w.41">
		<script>show_words('_help_txt165');</script>
		</dd>
		<dt id="h.w.42"><script>show_words('_help_txt166');</script></dt>
		<dd id="h.w.43">
		<script>show_words('_help_txt167');</script>
		</dd>
		<dt id="h.w.44"><script>show_words('aw_RT');</script></dt>
		<dd id="h.w.45">
		<script>show_words('_help_txt168');</script>
		</dd>
		<dt id="h.w.46"><script>show_words('_help_txt169');</script></dt>
		<dd id="h.w.47">
		<script>show_words('_help_txt170');</script>
		</dd>
		<dt id="h.w.48"><script>show_words('_help_txt171');</script></dt>
		<dd id="h.w.49">
		<script>show_words('_help_txt172');</script>
		</dd>
		<dt id="h.w.50"><script>show_words('_help_txt173');</script></dt>
		<dd id="h.w.51">
		<script>show_words('_help_txt174');</script>
		</dd>
	</dl>
	<div align="right" style="padding:0px 3px 3px 0px; color:#000000; ">
	<a href="#" class="normal"><b><script>show_words('_top');</script></b></a>
	</div>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="Security" id="Security"><script>show_words('ES_security');</script></div>
	<div class="CUL">
	<dl>
		<dt id="h.w.52"><script>show_words('sd_SecTyp');</script></dt>
		<dd id="h.w.53">
		<script>show_words('_help_txt175');</script></dd>
		<dt id="h.w.54"><script>show_words('LS321');</script></dt>
		<dd id="h.w.55">
		<script>show_words('_help_txt176');</script>
		</dd>
		<dt id="h.w.56"><script>show_words('help372');</script></dt>
		<dd id="h.w.57">
		<script>show_words('_help_txt177');</script>
		</dd>
		<dd id="h.w.58">
		<script>show_words('_help_txt178');</script>
		</dd>
		<dd id="h.w.59">
		<script>show_words('_help_txt179');</script>
		</dd>
		<dd id="h.w.60">
		<script>show_words('_help_txt180');</script>
		</dd>
		<dt id="h.w.61"><script>show_words('_WPApersonal');</script></dt>
		<dd id="h.w.62">
		<script>show_words('_help_txt181');</script>
		</dd>
		<dd id="h.w.63">
		<script>show_words('_help_txt182');</script>
		</dd>
		<dt id="h.w.64"><script>show_words('_WPAenterprise');</script></dt>
		<dd id="h.w.65">
		<script>show_words('_help_txt183');</script>
		</dd>
		<dd id="h.w.66">
		<script>show_words('_help_txt184');</script>
		</dd>
		<dd id="h.w.67">
		<script>show_words('_help_txt185');</script>
		</dd>
		<dd id="h.w.68">
		<script>show_words('_help_txt186');</script>
		</dd>
		<dd id="h.w.69">
		<script>show_words('_help_txt187');</script>
		</dd>
		<dt id="h.w.70"><script>show_words('_help_txt188');</script></dt>
		<dd id="h.w.71">
		<script>show_words('_help_txt189');</script>
		</dd>
		<dd id="h.w.72">
		<script>show_words('_help_txt190');</script>
		</dd>
		<dt id="h.w.73"><script>show_words('_help_txt191');</script></dt>
		<dd id="h.w.74">
		<script>show_words('_help_txt192');</script>
		</dd>
		<dt id="h.w.75"><script>show_words('sd_macaddr');</script></dt>
		<dd id="h.w.76">
		<script>show_words('_help_txt193');</script>
		</dd>
	</dl>
	<div align="right" style="padding:0px 3px 3px 0px; color:#000000; ">
	<a href="#" class="normal"><b><script>show_words('_top');</script></b></a>
	</div>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="WPS" id="WPS"><script>show_words('_WPS');</script></div>
	<div class="CUL">
	<dl>
		<dt id="h.w.77"><script>show_words('_WPS');</script></dt>
		<dd id="h.w.78">
		<b><script>show_words('_enable');</script></b><br>
		<script>show_words('_help_txt194');</script>
		</dd>
		<dd>
		<b id="h.w.79"><script>show_words('LW6');</script></b><br>
		<span id="h.w.80"><script>show_words('_help_txt195');</script></span>
		</dd>
		<dt id="h.w.81"><script>show_words('LW7');</script></dt>
		<dd id="h.w.82">
		<script>show_words('_help_txt196');</script>
		</dd>
		<dd id="h.w.83">
		<b><script>show_words('LW9');</script></b><br>
		<script>show_words('_help_txt197');</script>
		</dd>
		<dd id="h.w.84">
		<b><script>show_words('_help_txt198');</script></b><br>
		<script>show_words('_help_txt199');</script>
		</dd>
		<dd id="h.w.85">
		<b><script>show_words('LW11');</script></b><br>
		<script>show_words('_help_txt200');</script>
		</dd>
	</dl>
	<div align="right" style="padding:0px 3px 3px 0px; color:#000000; ">
	<a href="#" class="normal"><b><script>show_words('_top');</script></b></a>
	</div>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="StationList" id="StationList"><script>show_words('_statlst');</script></div>
	<div class="CUL">
	<dl>
		<dd id="h.w.86">
		<script>show_words('_help_txt201');</script>
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