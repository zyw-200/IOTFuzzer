<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | Advanced | DMZ</title>
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

	var main = new ccpObject();
	main.set_param_url('get_set.ccp');
	main.set_ccp_act('get');
	main.add_param_arg('IGD_WANDevice_i_',1100);
	main.add_param_arg('IGD_LANDevice_i_LANHostConfigManagement_',1110);
	
	main.get_config_obj();
	
	var serverIP = 		main.config_val("lanHostCfg_IPAddress_");
	var dmz_enable = 	main.config_val("wanDev_DMZEnable_");
	var dmz_ip = 		main.config_val("wanDev_DMZIPAddress_");
	
function check_dmz_value(){
	var dmz_ip = [$('#ipField1').html(),$('#ipField2').html(),$('#ipField3').html(),$('#ipField4').val()];
	var ip_addr_msg = replace_msg(all_ip_addr_msg, get_words('help256'));
	var ip_obj = new addr_obj(dmz_ip, ip_addr_msg, false, false);
	if($('#DMZEnabled').val()=='1')
	{
		if (!check_address(ip_obj))
		{
			return false;
		}
	}
	return true;
}
function dmz_apply(){
	if(check_dmz_value()){
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('adv_dmz.asp');
		
		obj.add_param_arg('wanDev_DMZEnable_','1.1.0.0',$('#DMZEnabled').val());
		
		var dmz_ip = 
			$('#ipField1').html()+'.'+
			$('#ipField2').html()+'.'+
			$('#ipField3').html()+'.'+
			$('#ipField4').val();
		obj.add_param_arg('wanDev_DMZIPAddress_','1.1.0.0',dmz_ip);
		
		var param = obj.get_param();
		
		totalWaitTime = 20; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(param.url, param.arg);
	}
}
function setValueDMZSettings(){
	$('#DMZEnabled').val(dmz_enable);
}
function setValueDMZIPAddress(){
	var s_ip = serverIP.split('.');
	var ips = dmz_ip.split('.');
	if("0.0.0.0"==dmz_ip){//use server
		$('#ipField1').html(s_ip[0]);
		$('#ipField2').html(s_ip[1]);
		$('#ipField3').html(s_ip[2]);
		$('#ipField4').val(s_ip[3]);
	}
	else{
		$('#ipField1').html(ips[0]);
		$('#ipField2').html(ips[1]);
		$('#ipField3').html(ips[2]);
		$('#ipField4').val(ips[3]);
	}
}
function setEventDMZSettings(){
	var func = function(){
		var enable = $('#DMZEnabled').val();
		if(enable=='1')
			$('#dmzipfield').show();
		else
			$('#dmzipfield').hide();
	}
	func();
	$('#DMZEnabled').change(func);
}
$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(model);
	//DMZ Settings
	setValueDMZSettings();
	setValueDMZIPAddress();
	
	setEventDMZSettings();
	
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
				<script>document.write(menu.build_structure(1,5,0))</script>
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
								<div class="headerbg" id="scheduleTitle">
								<script>show_words('_dmz_settings');</script>
								</div>
								<div class="hr"></div>
								<div class="section_content_border">
								<div class="header_desc" id="scheduleIntroduction">
									<script>show_words('_DMZ_DESC');</script>
									<p></p>
								</div>

<div class="box_tn">
	<div class="CT"><script>show_words('help488');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL" id="dmzSet"><script>show_words('_dmz_enable');</script></td>
		<td class="CR">
			<select id="DMZEnabled" name="DMZEnabled" size="1">
				<option value="0"><script>show_words('_disable');</script></option>
				<option value="1"><script>show_words('_enable');</script></option>
			</select>
		</td>
	</tr>
	<tr id="dmzipfield" style="display: table-row;">
		<td class="CL" id="dmzIPAddr">
			<script>show_words('af_DI');</script>
		</td>
		<td class="CR">
			<span id="ipField1"></span>.
			<span id="ipField2"></span>.
			<span id="ipField3"></span>.
			<!--<input type="text" size="3" maxlength="3" name="ipField1" value="" disabled="disabled" />.
			<input type="text" size="3" maxlength="3" name="ipField2" value="" disabled="disabled" />.
			<input type="text" size="3" maxlength="3" name="ipField3" value="" disabled="disabled" />.-->
			<input type="text" size="3" maxlength="3" id="ipField4" name="ipField4" value="" />
		</td>
	</tr>
	</table>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr align="center">
		<td colspan="2" class="btn_field">
		<input type="button" class="button_submit" value="Apply" id="addDMZ" name="addDMZ" onclick="dmz_apply();" />
		<script>$('#addDMZ').val(get_words('_apply'));</script>
		<input type="reset" class="button_submit" value="Reset" id="btn_reset" name="reset" onclick="window.location.reload()" />
		<script>$('#btn_reset').val(get_words('_reset'));</script>
		</td>
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