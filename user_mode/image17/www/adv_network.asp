<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | Advanced | Network</title>
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
	
	var main = new ccpObject();
	main.set_param_url('get_set.ccp');
	main.set_ccp_act('get');
	main.add_param_arg('IGD_AdvNetwork_',1100);
	main.add_param_arg('IGD_WANDevice_i_InboundFilter_i_',1100);
	main.add_param_arg('IGD_WANDevice_i_',1100);
	main.add_param_arg('IGD_',1000);
	main.get_config_obj();
	
	var advCfg = {
		'upnp':				main.config_val("advNetwork_UpnpEnable_"),
		'wping':			main.config_val("wanDev_WanPingEnable_")
	}
function check_apply(){
	var obj = new ccpObject();
	obj.set_param_url('get_set.ccp');
	obj.set_ccp_act('set');
	obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
	obj.set_param_next_page('adv_network.asp');
	
	//UPnP
//	console.log('UPnP',$('#upnpEnbl').val());
	obj.add_param_arg('advNetwork_UpnpEnable_','1.1.0.0',$('#upnpEnbl').val());
	
	//WAN Ping
//	console.log('WANPing',$('#icmpEnabled').val());
	obj.add_param_arg('wanDev_WanPingEnable_','1.1.0.0',$('#icmpEnabled').val());
	
//	console.log('Query', basic);
	
	var paramForm = obj.get_param();
	
	totalWaitTime = 12; //second
	redirectURL = location.pathname;
	wait_page();
	jq_ajax_post(paramForm.url, paramForm.arg);
	
}
function ip_ping(){
	if ($('#pingTestIP').val() == ""){
		alert(get_words('tsc_pingt_msg2'));
		return;
	}
	
	disablePinItem(true);
	
	var obj = new ccpObject();
	obj.set_param_url('ping.ccp');
	obj.set_ccp_act('ping_v4');
	obj.set_param_option('ping_addr',$('#pingTestIP').val());
	
	obj.get_config_obj();

	/*
	** Date:	2013-03-18
	** Author:	Moa Chung
	** Reason:	Advanced →  Advanced Network：Ping log is not word warp.
	** Note:	TEW-810DR pre-test no.99
	**/
	$('textarea').append('Testing...........\n<br/>');
	setTimeout("queryPingRet()",1*1000);
}

function queryPingRet(){
	var obj = new ccpObject();
	obj.set_param_url('ping.ccp');
	obj.set_ccp_act('queryPingV4Ret');
	
	obj.get_config_obj();
	var ret = obj.config_val("ping_result");
	switch(ret)
	{
		case "waiting":
			setTimeout("queryPingRet()",1*1000);
		return;

		case "success":
			disablePinItem(false);
			$('textarea').append(addstr(get_words('_ping_success'), $('#pingTestIP').val())+'\n<br/>');
		return;
		case "fail":
			disablePinItem(false);
			$('textarea').append(addstr(get_words('_ping_fail'), $('#pingTestIP').val())+'\n<br/>');
		return;
	}
}
function disablePinItem(opt){
	$('#pingTestIP').attr('disabled',opt);
	$('#PingButton').attr('disabled',opt);
}
function setValueUPnP(){
	var sel_upnp = advCfg.upnp;
	$('#upnpEnbl').val(sel_upnp);
}
function setValueWANPing(){
	var sel_wping = advCfg.wping;
	$('#icmpEnabled').val(sel_wping);
}
$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(model);
	//UPnP
	setValueUPnP();
	
	//WAN Ping
	setValueWANPing();
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
				<script>document.write(menu.build_structure(1,0,3))</script>
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
							<div class="headerbg" id="advancedNetworkTitle">
							<script>show_words('_advnetwork');</script>
							</div>
							<div class="hr"></div>
							<div class="section_content_border">
							<div class="header_desc" id="advancedNetworkTitle">
								<script>show_words('_ADV_NETWRK_DESC');</script>
								<p></p>
							</div>

<div class="box_tn">
	<div class="CT"><script>show_words('ta_upnp');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr id="EnableAC">
		<td class="CL"><script>show_words('ta_upnp');</script></td>
		<td class="CR">
			<select id="upnpEnbl" name="upnpEnbl" size="1">
				<option value="0"><script>show_words('_disable');</script></option>
				<option value="1"><script>show_words('_enable');</script></option>
			</select>
		</td>
	</tr>
	</table>
</div>

<div class="box_tn">
	<div class="CT"><script>show_words('anet_wan_ping');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
		<tr id="EnableAC">
			<td class="CL"><script>show_words('_wan_ping_respond');</script></td>
			<td class="CR">
				<select id="icmpEnabled" name="icmpEnabled" size="1">
					<option value="0"><script>show_words('_disable');</script></option>
					<option value="1"><script>show_words('_enable');</script></option>
				</select>
			</td>
		</tr>
	</table>
</div>

<div id="buttonField" class="box_tn">
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td align="center" class="btn_field">
		<input type="button" class="button_submit" id="an.btn.1" value="Apply" onclick="check_apply();" />
		<script>$('#an.btn.1').val(get_words('_apply'));</script>
		<input type="button" class="button_submit" id="an.btn.2" value="Reset" onclick="window.location.reload();" />
		<script>$('#an.btn.2').val(get_words('_reset'));</script>
	</td>
	</tr>
	</table>
</div>

<div class="box_tn">
	<div class="CT"><script>show_words('tsc_pingt');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL"><script>show_words('tsc_pingt_h');</script></td>
		<td class="CR"><input type="text" name="pingTestIP" id="pingTestIP" /></td>
	</tr>
	</table>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr align="center">
		<td class="btn_field">
			<input type="button" class="button_submit" id="PingButton" value="Ping" onclick="ip_ping();" />
			<script>$('#PingButton').val(get_words('_ping'));</script>
			<input type="reset" class="button_submit" id="PingReset" value="Reset" onclick="window.location.reload();" />
		<script>$('#PingReset').val(get_words('_reset'));</script>
		</td>
	</tr>
	</table>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr align="center">
		<td colspan="2" class="CELL"><textarea cols="76" rows="10" wrap="hard" readonly="1"></textarea></td>
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