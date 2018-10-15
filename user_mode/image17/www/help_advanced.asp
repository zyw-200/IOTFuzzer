<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<script>
	var funcWinOpen = window.open;
</script>
<title>TRENDNET | modelName | Help | Advanced</title>
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
				<script>document.write(menu.build_structure(1,8,3))</script>
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
	<script>show_words('help1');</script>
	</div>
	<div class="CUL">
	<ul>
		<li>
			<a href="#DMZ" class="normal"><script>show_words('help488');</script></a>
		</li>
		<li>
			<a href="#VirtualServer" class="normal"><script>show_words('_virtserv');</script></a>
		</li>
		<li>
			<a href="#Routing" class="normal"><script>show_words('_routing');</script></a>
		</li>
		<li>
			<a href="#AccessControl" class="normal"><script>show_words('_acccon');</script></a>
		</li>
		<li>
			<a href="#ALG" class="normal"><script>show_words('_alg');</script></a>
		</li>
		<li>
			<a href="#SpecialApplications" class="normal"><script>show_words('_specapps');</script></a>
		</li>
		<li>
			<a href="#Gaming" class="normal"><script>show_words('_gaming');</script></a>
		</li>
		<li>
			<a href="#InboundFilter" class="normal"><script>show_words('_inboundfilter');</script></a>
		</li>
		<li>
			<a href="#Schedule" class="normal"><script>show_words('_sched');</script></a>
		</li>
		<li>
			<a href="#AdvancedNetwork" class="normal"><script>show_words('_advnetwork');</script></a>
		</li>
	</ul>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="DMZ" id="DMZ"><script>show_words('help488');</script></div>
	<div class="CUL">
	<dl>
		<dt id="h.adv.1"><script>show_words('_help_txt226');</script></dt>
		<dd id="h.adv.2">
		<script>show_words('_help_txt227');</script>
		</dd>
		<dd id="h.adv.3">
		<script>show_words('_help_txt228');</script>
		</dd>
		<dd id="h.adv.4">
		<script>show_words('_help_txt229');</script>
		</dd>
		<dd id="h.adv.5">
		<script>show_words('_help_txt230');</script>
		</dd>
		<dd id="h.adv.6">
		<script>show_words('_help_txt231');</script>
		</dd>
		<dd id="h.adv.7">
		<script>show_words('_help_txt232');</script>
		</dd>
		<dd id="h.adv.8">
		<script>show_words('_help_txt233');</script>
		</dd>
		<dd id="h.adv.9">
		<script>show_words('_help_txt234');</script>
		</dd>
		<dt><script>show_words('af_ED');</script></dt>
		<dd id="h.adv.11">
		<script>show_words('_help_txt235');</script>
		</dd>
		<dt id="h.adv.12"><script>show_words('af_DI');</script></dt>
		<dd id="h.adv.13">
		<script>show_words('_help_txt236');</script>
		</dd>
	</dl>
	<div align="right" style="padding:0px 3px 3px 0px; color:#000000; ">
	<a href="#" class="normal"><b><script>show_words('_top');</script></b></a>
	</div>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="VirtualServer" id="VirtualServer"><script>show_words('_virtserv');</script></div>
	<div class="CUL">
	<dl>
		<dt id="h.adv.14"><script>show_words('_help_txt237');</script></dt>
		<dt><script>show_words('_enable');</script></dt>
		<dd id="h.adv.15">
		<script>show_words('_help_txt238');</script>
		</dd>
		<dt id="h.adv.16"><script>show_words('_name');</script></dt>
		<dd id="h.adv.17">
		<script>show_words('_help_txt239');</script>
		</dd>
		<dt id="h.adv.18"><script>show_words('_ipaddr');</script></dt>
		<dd id="h.adv.19">
		<script>show_words('_help_txt240');</script>
		</dd>
		<dt id="h.adv.20"><script>show_words('_protocol');</script></dt>
		<dd id="h.adv.21">
		<script>show_words('_help_txt241');</script>
		</dd>
		<dt id="h.adv.22"><script>show_words('av_PriP');</script></dt>
		<dd id="h.adv.23">
		<script>show_words('_help_txt242');</script>
		</dd>
		<dt id="h.adv.24"><script>show_words('av_PubP');</script></dt>
		<dd id="h.adv.25">
		<script>show_words('_help_txt243');</script>
		</dd>
		<dt id="h.adv.26"><script>show_words('_sched');</script></dt>
		<dd id="h.adv.27">
		<script>show_words('_help_txt244');</script>
		</dd>
		<dt id="h.adv.28"><script>show_words('_clear');</script></dt>
		<dd id="h.adv.29">
		<script>show_words('_help_txt245');</script>
		</dd>
	</dl>
	<div align="right" style="padding:0px 3px 3px 0px; color:#000000; ">
	<a href="#" class="normal"><b><script>show_words('_top');</script></b></a>
	</div>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="Routing" id="Routing"><script>show_words('_routing');</script></div>
	<div class="CUL">
	<dl>
		<dt id="h.adv.30"><script>show_words('_help_txt246');</script></dt>
		<dd id="h.adv.31">
		<script>show_words('_help_txt247');</script>
		</dd>
		<dt id="h.adv.32"><script>show_words('_DestIP');</script></dt>
		<dd id="h.adv.33">
		<script>show_words('_help_txt248');</script>
		</dd>
		<dt id="h.adv.34"><script>show_words('_gateway');</script></dt>
		<dd id="h.adv.35">
		<script>show_words('_help_txt249');</script>
		</dd>
		<dt id="h.adv.36"><script>show_words('_metric');</script></dt>
		<dd id="h.adv.37">
		<script>show_words('_help_txt250');</script>
		</dd>
		<dt id="h.adv.38"><script>show_words('_interface');</script></dt>
		<dd id="h.adv.39">
		<script>show_words('_help_txt251');</script>
		</dd>
		<dt id="h.adv.40"><script>show_words('_clear');</script></dt>
		<dd id="h.adv.41">
		<script>show_words('_help_txt252');</script>
		</dd>
		<dt id="h.adv.42"><script>show_words('ar_RoutesList');</script></dt>
		<dd id="h.adv.43">
		<script>show_words('_help_txt253');</script>
		</dd>
	</dl>
	<div align="right" style="padding:0px 3px 3px 0px; color:#000000; ">
	<a href="#" class="normal"><b><script>show_words('_top');</script></b></a>
	</div>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="AccessControl" id="AccessControl">Access Control</div>
	<div class="CUL">
	<dl>
		<dt><script>show_words('_enable');</script></dt>
		<dd id="h.adv.44">
		<script>show_words('_help_txt254');</script>
		</dd>
		<dd id="h.adv.45">
		<script>show_words('_help_txt255');</script>
		</dd>
	</dl>
	<div align="right" style="padding:0px 3px 3px 0px; color:#000000; ">
	<a href="#" class="normal"><b><script>show_words('_top');</script></b></a>
	</div>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="ALG" id="ALG"><script>show_words('af_algconfig');</script></div>
	<div class="CUL">
	<dl>
		<dt><script>show_words('_alg');</script></dt>
		<dd>
		<script>show_words('_help_txt256');</script>
		</dd>
	</dl>
	<div align="right" style="padding:0px 3px 3px 0px; color:#000000; ">
	<a href="#" class="normal"><b><script>show_words('_top');</script></b></a>
	</div>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="SpecialApplications" id="SpecialApplications"><script>show_words('_specapps');</script></div>
	<div class="CUL">
	<dl>
		<dt id="h.adv.46"><script>show_words('_help_txt257');</script></dt>
		<dt><script>show_words('_enable');</script></dt>
		<dd id="h.adv.47">
		<script>show_words('_help_txt258');</script>
		</dd>
		<dt id="h.adv.48"><script>show_words('_name');</script></dt>
		<dd id="h.adv.49">
		<script>show_words('_help_txt259');</script>
		</dd>
		<dt id="h.adv.50"><script>show_words('_protocol');</script></dt>
		<dd id="h.adv.51">
		<script>show_words('_help_txt260');</script>
		</dd>
		<dt id="h.adv.52"><script>show_words('as_TPRange_b');</script></dt>
		<dd id="h.adv.53">
		<script>show_words('_help_txt261');</script>
		</dd>
		<dt id="h.adv.54"><script>show_words('_sched');</script></dt>
		<dd id="h.adv.55">
		<script>show_words('_help_txt262');</script>
		</dd>
		<dt id="h.adv.56"><script>show_words('_clear');</script></dt>
		<dd id="h.adv.57">
		<script>show_words('_help_txt263');</script>
		</dd>
		<dt id="h.adv.58"><script>show_words('_adv_txt_19');</script></dt>
		<dd id="h.adv.59">
		<script>show_words('_help_txt264');</script>
		</dd>
	</dl>
	<div align="right" style="padding:0px 3px 3px 0px; color:#000000; ">
	<a href="#" class="normal"><b><script>show_words('_top');</script></b></a>
	</div>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="Gaming" id="Gaming"><script>show_words('_gaming');</script></div>
	<div class="CUL">
	<dl>
		<dt id="h.adv.60"><script>show_words('_help_txt265');</script></dt>
		<dd id="h.adv.61">
		<script>show_words('_help_txt266');</script>
		</dd>
		<dt id="h.adv.62"><script>show_words('_adv_txt_00');</script></dt>
		<dd id="h.adv.63">
		<script>show_words('_help_txt267');</script>
		</dd>
		<dt id="h.adv.64"><script>show_words('_adv_txt_01');</script></dt>
		<dd id="h.adv.65">
		<script>show_words('_help_txt268');</script>
		</dd>
		<dt id="h.adv.66"><script>show_words('_ipaddr');</script></dt>
		<dd id="h.adv.67">
		<script>show_words('_help_txt269');</script>
		</dd>
		<dt id="h.adv.68"><script>show_words('_help_txt270');</script></dt>
		<dd id="h.adv.69">
		<script>show_words('_help_txt271');</script>
		</dd>
		<dt id="h.adv.70"><script>show_words('_help_txt272');</script></dt>
		<dd id="h.adv.71">
		<script>show_words('_help_txt273');</script>
		</dd>
		<dt id="h.adv.72"><script>show_words('_inboundfilter');</script></dt>
		<dd id="h.adv.73">
		<script>show_words('_help_txt274');</script>
		</dd>
		<dt id="h.adv.74"><script>show_words('_sched');</script></dt>
		<dd id="h.adv.75">
		<script>show_words('_help_txt275');</script>
		</dd>
		<dt id="h.adv.76"><script>show_words('_clear');</script></dt>
		<dd id="h.adv.77">
		<script>show_words('_help_txt276');</script>
		</dd>
		<dt id="h.adv.78"><script>show_words('_adv_txt_10');</script></dt>
		<dd id="h.adv.79">
		<script>show_words('_help_txt277');</script>
		</dd>
	</dl>
	<div align="right" style="padding:0px 3px 3px 0px; color:#000000; ">
	<a href="#" class="normal"><b><script>show_words('_top');</script></b></a>
	</div>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="InboundFilter" id="InboundFilter">Inbound Filter</div>
	<div class="CUL">
	<dl>
		<dt id="h.adv.80"><script>show_words('_help_txt278');</script></dt>
		<dd id="h.adv.81">
		<script>show_words('_help_txt279');</script>
		</dd>
		<dt id="h.adv.82"><script>show_words('_name');</script></dt>
		<dd id="h.adv.83">
		<script>show_words('_help_txt280');</script>
		</dd>
		<dt id="h.adv.84"><script>show_words('ai_Action');</script></dt>
		<dd id="h.adv.85">
		<script>show_words('_help_txt281');</script>
		</dd>
		<dt id="h.adv.86"><script>show_words('at_ReIPR');</script></dt>
		<dd id="h.adv.87">
		<script>show_words('_help_txt282');</script>
		</dd>
		<dt id="h.adv.88"><script>show_words('_clear');</script></dt>
		<dd id="h.adv.89">
		<script>show_words('_help_txt283');</script>
		</dd>
		<dt id="h.adv.90"><script>show_words('ai_title_IFRL');</script></dt>
		<dd id="h.adv.91">
		<script>show_words('_help_txt284');</script>
		</dd>
		<dd id="h.adv.92">
		<script>show_words('_help_txt285');</script>
		</dd>
		<dt id="h.adv.93"><script>show_words('_allowall');</script></dt>
		<dd id="h.adv.94">
		<script>show_words('_help_txt286');</script>
		</dd>
		<dt id="h.adv.95"><script>show_words('_denyall');</script></dt>
		<dd id="h.adv.96">
		<script>show_words('_help_txt287');</script>
		</dd>
	</dl>
	<div align="right" style="padding:0px 3px 3px 0px; color:#000000; ">
	<a href="#" class="normal"><b><script>show_words('_top');</script></b></a>
	</div>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="Schedule" id="Schedule"><script>show_words('_sched');</script></div>
	<div class="CUL">
	<dl>
		<dt id="h.adv.97"><script>show_words('_help_txt288');</script></dt>
		<dd id="h.adv.98">
		<script>show_words('_help_txt289');</script>
		</dd>
		<dt id="h.adv.99"><script>show_words('_name');</script></dt>
		<dd id="h.adv.100">
		<script>show_words('_help_txt290');</script>
		</dd>
		<dt id="h.adv.101"><script>show_words('_days');</script></dt>
		<dd id="h.adv.102">
		<script>show_words('_help_txt291');</script>
		</dd>
		<dt id="h.adv.103"><script>show_words('tsc_24hrs');</script></dt>
		<dd id="h.adv.104">
		<script>show_words('_help_txt292');</script>
		</dd>
		<dt id="h.adv.105"><script>show_words('tsc_start_time');</script></dt>
		<dd id="h.adv.106">
		<script>show_words('_help_txt293');</script>
		</dd>
		<dd id="h.adv.107">
		<script>show_words('_help_txt294');</script>
		</dd>
		<dt id="h.adv.108"><script>show_words('_clear');</script></dt>
		<dd id="h.adv.109">
		<script>show_words('_help_txt295');</script>
		</dd>
		<dt id="h.adv.110"><script>show_words('tsc_SchRuLs');</script></dt>
		<dd id="h.adv.111">
		<script>show_words('_help_txt296');</script>
		</dd>
	</dl>
	<div align="right" style="padding:0px 3px 3px 0px; color:#000000; ">
	<a href="#" class="normal"><b><script>show_words('_top');</script></b></a>
	</div>
	</div>
</div>

<div class="box_tn">
	<div class="CT" name="AdvancedNetwork" id="AdvancedNetwork"><script>show_words('_advnetwork');</script></div>
	<div class="CUL">
	<dl>
		<dt><script>show_words('ta_upnp');</script></dt>
		<dd>
		<script>show_words('_help_txt297');</script>
		</dd>
	</dl>
	<dl>
		<dt><script>show_words('anet_wan_ping');</script></dt>
		<dd>
		<script>show_words('_help_txt298');</script>
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