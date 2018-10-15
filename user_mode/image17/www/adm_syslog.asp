<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | Administrator | System Log</title>
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

	var main = new ccpObject();
	main.set_param_url('get_set.ccp');
	main.set_ccp_act('get');
	main.add_param_arg('IGD_SystemLogInfo_',1100);
	main.get_config_obj();
	
	var sysLogInfo = {
		'sysAct':		main.config_val('sysLog_OptionSystemActivity_'),
		'debugInfo':	main.config_val('sysLog_OptionDebugInfo_'),
		'attack':		main.config_val('sysLog_OptionAttack_'),
		'droppedPack':	main.config_val('sysLog_OptionDroppedPacket_'),
		'notice':		main.config_val('sysLog_OptionNotice_')
	};

function send_action(act, page)
{
	var obj = new ccpObject();
	obj.set_param_url('log.ccp');
	obj.set_ccp_act(act);
	
	if(page!=0)
		obj.set_param_query_page(page);
		
	obj.get_config_obj();
//	paint_page_info();
	paint_content(obj);
}


function paint_content(obj)
{
	var logCnt = obj.config_val("currLogCnt");
	var loopMax = 0;
	
	if(logCnt == 0)
		return;
		
	if(logCnt>10)
		loopMax = 10;
	else
		loopMax = logCnt;
		
	var logTime = obj.config_str_multi("logTime");
	var logType = obj.config_str_multi("logType");
	var logMsg = obj.config_str_multi("logMsg");
	
	/*
	** Date:	2013-03-18
	** Author:	Moa Chung
	** Reason:	Administrator → System log：System log is not word warp.
	** Note:	TEW-810DR pre-test no.105
	**/
	$("#syslog").html('');
	for(var i=logTime.length-1; i>=0; i--){
		$("#syslog").append(logTime[i]+" "+logType[i]+" "+logMsg[i]+'<br/>');
	}
}
function refresh_log(){
	send_action('doLogReflash', 0);
	send_action('doLogQuery', 1);
}
function clear_log(){
	send_action("doLogClear", 0);
	location.href='adm_syslog.asp';
}
function check_apply(){
	var obj = new ccpObject();
	obj.set_param_url('get_set.ccp');
	obj.set_ccp_act('set');
	obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
	obj.set_param_next_page('adm_syslog.asp');
	
	var chk_enable = $('#logEnabled').attr('checked');
	obj.add_param_arg('sysLog_OptionSystemActivity_','1.1.0.0',(chk_enable?1:0));
//	obj.add_param_arg('sysLog_OptionDebugInfo_','1.1.0.0',(chk_enable?1:0));
	obj.add_param_arg('sysLog_OptionAttack_','1.1.0.0',(chk_enable?1:0));
//	obj.add_param_arg('sysLog_OptionDroppedPacket_','1.1.0.0',(chk_enable?1:0));
	obj.add_param_arg('sysLog_OptionNotice_','1.1.0.0',(chk_enable?1:0));
	
	var param = obj.get_param();
	
	totalWaitTime = 10; //second
	redirectURL = location.pathname;
	wait_page();
	jq_ajax_post(param.url, param.arg);
}
function setValueEnableSystemLog(){
	if(
		(
		parseInt(sysLogInfo.sysAct)
//		& parseInt(sysLogInfo.debugInfo)
		& parseInt(sysLogInfo.attack)
//		& parseInt(sysLogInfo.droppedPack)
		& parseInt(sysLogInfo.notice)
		)==1
	){
		$('#logEnabled').attr('checked', true);
	}
	else{
		$('#logEnabled').attr('checked', false);
	}
}
$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(dev_info.model);
	setValueEnableSystemLog();
	refresh_log();
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
				<script>document.write(menu.build_structure(1,0,2))</script>
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
								<div class="headerbg" id="syslogTitle">
								<script>show_words('_system_log');</script>
								</div>
								<div class="hr"></div>
								<div class="section_content_border">
								<div class="header_desc" id="syslogIntroduction">
									<script>show_words('_SYSLOG_DESC');</script>
									<p></p>
								</div>

<div class="box_tn">
	<div class="CT" id="syslogSysLog"><script>show_words('_system_log');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL"><script>show_words('_enable_system_log');</script></td>
		<td class="CR">
			<input type="checkbox" name="logEnabled" id="logEnabled" value="ON" />
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center" class="btn_field">
			<input class="button_submit" type="button" value="" id="syslogApply" name="Apply" onclick="check_apply();" />
			<script>$('#syslogApply').val(get_words('_adv_txt_17'));</script>
		</td>
	</tr>
	</table>
	
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td colspan="2" align="center" class="CELL">
			<textarea style="font-size:9pt" name="syslog" id="syslog" cols="85" rows="15" wrap="hard" readonly="1"></textarea>
		</td>
	</tr>
	<tr align="center">
		<td colspan="2" class="btn_field">
		<form method="post" name="SubmitClearLog" action="/goform/clearlog">
			<input class="button_submit" type="button" value="Refresh" id="syslogSysLogRefresh" name="refreshlog" onclick="refresh_log();" />
			<script>$('#syslogSysLogRefresh').val(get_words('sl_reload'));</script>
			<input class="button_submit" type="button" value="Clear" id="syslogSysLogClear" name="clearlog" onclick="clear_log();" />
			<script>$('#syslogSysLogClear').val(get_words('_clear'));</script>
		</form>
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