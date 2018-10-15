<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | Administrator | Time</title>
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

	/* get time information */
	var main = new ccpObject();
	main.set_param_url('get_set.ccp');
	main.set_ccp_act('get');
	main.add_param_arg('IGD_Time_',1100);
	main.get_config_obj();
	
	var objTime = {
		'enable':			main.config_val('sysTime_NTPEnable_'),
		'ntpServ1':			main.config_val('sysTime_NTPServer1_'),
		'ntpServ2':			main.config_val('sysTime_NTPServer2_'),
		'ntpServ3':			main.config_val('sysTime_NTPServer3_'),
		'ntpSync':			main.config_val('sysTime_NTPSync_'),
		'curLocTime':		main.config_val('sysTime_CurrentLocalTime_'),
		'curLocTimeStr':	main.config_val('sysTime_CurrentLocalTimeStr_'),
		'locTimeZone':		main.config_val('sysTime_LocalTimeZone_'),
		'dlSavUsed':		main.config_val('sysTime_DaylightSavingsUsed_'),
		'dlSavOffset':		main.config_val('sysTime_DaylightSavingsOffset_'),
		'locTimeZoneIdx':	main.config_val('sysTime_LocalTimeZoneIndex_'),
		'dlSavMStart':		main.config_val('sysTime_DaylightSavingMStart_'),
		'dlSavWStart':		main.config_val('sysTime_DaylightSavingWStart_'),
		'dlSavDStart':		main.config_val('sysTime_DaylightSavingDStart_'),
		'dlSavDDStart':		main.config_val('sysTime_DaylightSavingDDStart_'),
		'dlSavTStart':		main.config_val('sysTime_DaylightSavingTStart_'),
		'dlSavMEnd':		main.config_val('sysTime_DaylightSavingMEnd_'),
		'dlSavWEnd':		main.config_val('sysTime_DaylightSavingWEnd_'),
		'dlSavDEnd':		main.config_val('sysTime_DaylightSavingDEnd_'),
		'dlSavDDEnd':		main.config_val('sysTime_DaylightSavingDDEnd_'),
		'dlSavTEnd':		main.config_val('sysTime_DaylightSavingTEnd_')
	};


var nNow, gTime;
function get_time(){
	if (gTime){
		return;
	}
	//gTime = "<% CmoGetStatus("system_time"); %>";
	gTime = objTime.curLocTime;
	var time = gTime.split(",");
	gTime = month_device[time[1]-1] + " " + time[2] + ", " + time[0] + " " + time[3] + ":" + time[4] + ":" + time[5];		
	nNow = new Date(gTime);
}
function STime(){
	nNow.setTime(nNow.getTime() + 1000);
	//new Date(yr_num, mo_num, day_num, hr_num, min_num, sec_num)
	//var date = new Date();
	var fulldate = ' '+change_week(nNow.getDay()) +' '+change_mon(nNow.getMonth()) +', '+nNow.getDate() +', '+nNow.getFullYear()
				+ ' ' +len_checked(nNow.getHours())+ ':' +len_checked(nNow.getMinutes())+ ':' +len_checked(nNow.getSeconds());
	$("#show_systime").html(fulldate);
	//get_by_id("show_systime").innerHTML = nNow.toLocaleString();
	setTimeout('STime()', 1000);
}

function check_value(){
	if($('#enableNTP').attr('checked') && $('#NTPServerIP').val() == ''){
		//alert("Please enter NTP Server");
		alert(get_words('YM185'));
		return false;
	}
	return true;
}
function check_apply(){
	if(check_value()){
		var dat = '';
		var year = $('#manual_year_select').val();
		var mon = $('#manual_month_select').val();
		var day = $('#manual_day_select').val();
		var hour = $('#manual_hour_select').val();
		var minu = $('#manual_min_select').val();
		var sec = $('#manual_sec_select').val();
		dat = year +","+ mon +","+ day +","+ hour +","+ minu +","+ sec;
		
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('adm_time.asp');
		
		obj.add_param_arg('sysTime_DaylightSavingsUsed_','1.1.0.0',($('#DSTenable').attr('checked')?'1':'0'));
		obj.add_param_arg('sysTime_DaylightSavingsOffset_','1.1.0.0',$('#DSToffset').val());
		obj.add_param_arg('sysTime_DaylightSavingMStart_','1.1.0.0',$('#tz_daylight_start_month_select').val());
//		obj.add_param_arg('sysTime_DaylightSavingDDStart_','1.1.0.0',$('#tz_daylight_start_day_select').val());
		obj.add_param_arg('sysTime_DaylightSavingWStart_','1.1.0.0',$('#tz_daylight_start_week_select').val());
		obj.add_param_arg('sysTime_DaylightSavingDStart_','1.1.0.0',$('#tz_daylight_start_dayweek_select').val());
		obj.add_param_arg('sysTime_DaylightSavingTStart_','1.1.0.0',$('#tz_daylight_start_time_select').val());
		obj.add_param_arg('sysTime_DaylightSavingMEnd_','1.1.0.0',$('#tz_daylight_end_month_select').val());
//		obj.add_param_arg('sysTime_DaylightSavingDDEnd_','1.1.0.0',$('#tz_daylight_end_day_select').val());
		obj.add_param_arg('sysTime_DaylightSavingWEnd_','1.1.0.0',$('#tz_daylight_end_week_select').val());
		obj.add_param_arg('sysTime_DaylightSavingDEnd_','1.1.0.0',$('#tz_daylight_end_dayweek_select').val());
		obj.add_param_arg('sysTime_DaylightSavingTEnd_','1.1.0.0',$('#tz_daylight_end_time_select').val());
		
		obj.add_param_arg('sysTime_NTPEnable_','1.1.0.0',($('#enableNTP').attr('checked')?'1':'0'));
		obj.add_param_arg('sysTime_NTPServer1_','1.1.0.0',$('#NTPServerIP').val());
		obj.add_param_arg('sysTime_CurrentLocalTimeStr_','1.1.0.0',dat);
		obj.add_param_arg('sysTime_LocalTimeZone_','1.1.0.0',$('#time_zone').val());
		obj.add_param_arg('sysTime_LocalTimeZoneIndex_','1.1.0.0',$('#time_zone').attr('selectedIndex'));
		obj.add_param_arg('sysTime_NTPSync_','1.1.0.0',$('#NTPSync').val());
		
		var param = obj.get_param();
		
		totalWaitTime = 10; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(param.url, param.arg);
	}
}
function setValueSystemTime(){
	get_time();
	STime();
}
function setValueEnableDaylightSaving(){
	$('#DSTenable').attr('checked', (objTime.dlSavUsed=='1'?true:false));
}
function setEventEnableDaylightSaving(){
	var func = function(){
		var chk_ds = $('#DSTenable').attr('checked')
		if(chk_ds){
			$('#DSToffsetField').show();
			$('#DSTdateField').show();
		}
		else{
			$('#DSToffsetField').hide();
			$('#DSTdateField').hide();
		}
	};
	func();
	
	$('#DSTenable').change(func);
}
function setValueDaylightSavingOffset(){
	var val_offset = objTime.dlSavOffset;
	$('#DSToffset').val(val_offset);
}
function setValueDaylightSavingDates(){
	/*
	** Date:	2013-03-27
	** Author:	Moa Chung
	** Reason: 	for our datamodel use week and dayofweek to handle day light saving.
	**/
	setValueDSTStartMonth();
//	setValueDSTStartDay();
	setValueDSTStartWeek();
	setValueDSTStartDayWeek();
	setValueDSTStartHour();
	setValueDSTEndMonth();
//	setValueDSTEndDay();
	setValueDSTEndWeek();
	setValueDSTEndDayWeek();
	setValueDSTEndHour();
}
function setValueDSTStartMonth(){
	var chk_ms = objTime.dlSavMStart;
	$('#tz_daylight_start_month_select').val(chk_ms);
}
function set_week_list(){
	for(var i = 0; i < Week.length; i++){
		document.write("<option value=" + (i+1) + " >" + Week[i] + "</option>");
	}
}
function set_seq_list(){
	for(var i = 0; i < sequence.length; i++){
		document.write("<option value=" + (i+1) + " >" + sequence[i] + "</option>");
	}
}
function setValueDSTStartDay(){
	var chk_ds = objTime.dlSavDDStart;
	$('#tz_daylight_start_day_select').val(chk_ds);
}
function setValueDSTStartWeek(){
	var chk_ds = objTime.dlSavWStart;
	$('#tz_daylight_start_week_select').val(chk_ds);
}
function setValueDSTStartDayWeek(){
	var chk_ds = objTime.dlSavDStart;
	$('#tz_daylight_start_dayweek_select').val(chk_ds);
}
function setValueDSTStartHour(){
	var chk_ts = objTime.dlSavTStart;
	$('#tz_daylight_start_time_select').val(chk_ts);
}
function setValueDSTEndMonth(){
	var chk_me = objTime.dlSavMEnd;
	$('#tz_daylight_end_month_select').val(chk_me);
}
function setValueDSTEndDay(){
	var chk_de = objTime.dlSavDDEnd;
	$('#tz_daylight_end_day_select').val(chk_de);
}
function setValueDSTEndWeek(){
	var chk_de = objTime.dlSavWEnd;
	$('#tz_daylight_end_week_select').val(chk_de);
}
function setValueDSTEndDayWeek(){
	var chk_de = objTime.dlSavDEnd;
	$('#tz_daylight_end_dayweek_select').val(chk_de);
}
function setValueDSTEndHour(){
	var chk_te = objTime.dlSavTEnd;
	$('#tz_daylight_end_time_select').val(chk_te);
}
function setValueEnableNTPServer(){
	var chk_enable = objTime.enable;
	$('#enableNTP').attr('checked', (chk_enable=='1'?true:false));
}
function setEventEnableNTPServer(){
	var func = function(){
		var chk_enable = $('#enableNTP').attr('checked');
		if(chk_enable){
			$('#ntpServerField').show();
			$('#TimeZoneField').show();
			$('#ntpSyncField').show();
			$('#manualField').hide();
		}
		else{
			$('#ntpServerField').hide();
			$('#TimeZoneField').hide();
			$('#ntpSyncField').hide();
			$('#manualField').show();
		}
	};
	func();
	$('#enableNTP').change(func);
}
function setValueNTPServer(){
	var val_serv = objTime.ntpServ1;
	$('#NTPServerIP').val(val_serv);
}
function setValueTimeZone(){
	var val_tz = objTime.locTimeZone;
	$('#time_zone').val(val_tz);
	$('#time_zone').attr('selectedIndex', objTime.locTimeZoneIdx);
}
function setValueNTPSynchronization(){
	var val_sync = objTime.ntpSync;
	$('#NTPSync').val(val_sync);
}
function setValueDateAndTime(){
	var datetime= new Date();
	var curLocalTime = datetime.getFullYear() +"/"+ (datetime.getMonth()+1) + "/" + datetime.getDate() + "/" +
			datetime.getHours() + "/" + datetime.getMinutes() + "/" + datetime.getSeconds();
	var dat = curLocalTime.split('/');
	if (dat != null) {
		var i=0;
		$('#manual_year_select').val(dat[i++]);
		$('#manual_month_select').val(dat[i++]);
		$('#manual_day_select').val(dat[i++]);
		$('#manual_hour_select').val(dat[i++]);
		$('#manual_min_select').val(dat[i++]);
		$('#manual_sec_select').val(dat[i++]);
	}
}
$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(dev_info.model);
	//Time Configuration
	setValueSystemTime();
	
	//Daylight Saving Time
	setValueEnableDaylightSaving();
	setEventEnableDaylightSaving();
	setValueDaylightSavingOffset();
	setValueDaylightSavingDates();
	
	//NTP Settings
	setValueEnableNTPServer();
	setEventEnableNTPServer();
	setValueNTPServer();
	setValueTimeZone();
	setValueNTPSynchronization();
	
	//Date and Time Settings
	setValueDateAndTime();
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
			<td><img src="/image/bg_topr.gif" width="680" height="7" /></td>
		</tr>
		<!-- End of upper frame -->

		<tr>
			<!-- left menu -->
			<td style="background-image:url('/image/bg_l.gif');background-repeat:repeat-y;vertical-align:top;" width="270">
				<div style="padding-left:6px;">
				<script>document.write(menu.build_structure(1,0,5))</script>
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
								<div class="headerbg" id="manTimeSettingTitle">
								<script>show_words('_time_setting');</script>
								</div>
								<div class="hr"></div>
								<div class="section_content_border">
								<div class="header_desc" id="manTimeSettingIntruduction">
									<script>show_words('_TIME_DESC');</script>
									<p></p>
								</div>

<div class="box_tn">
	<div class="CT"><script>show_words('tt_TimeConf');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL"><script>show_words('_system_time');</script></td>
		<td class="CR"><span id="show_systime"></span></td>
	</tr>
	</table>
</div>

<div id="DSTField" class="box_tn">
	<div class="CT" id="manDayLightSavingTime"><script>show_words('_daylight_saving_time');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr id="enableDSTField">
		<td class="CL" id="manDSTEnable"><script>show_words('tt_dsen2');</script></td>
		<td class="CR"><input type="checkbox" id="DSTenable" name="DSTenable" /></td>
	</tr>
	<tr id="DSToffsetField">
		<td class="CL"><script>show_words('tt_dsoffs');</script></td>
		<td class="CR">
			<select name="DSToffset" id="DSToffset">
				<option value="-7200">-2:00</option>
				<option value="-5400">-1:30</option>
				<option value="-3600">-1:00</option>
				<option value="-1800">-0:30</option>
				<option value="1800">+0:30</option>
				<option value="3600">+1:00</option>
				<option value="5400">+1:30</option>
				<option value="7200">+2:00</option>
			</select>
		</td>
	</tr>
	<tr id="DSTdateField">
		<td class="CL"><script>show_words('tt_dsdates');</script></td>
		<td class="CR">
			<table cellspacing="0" cellpadding="0">
			<tr>
				<td>&nbsp;</td>
				<td><script>show_words('tt_Month');</script></td>
				<td><script>show_words('ZM21');</script></td>
				<td><script>show_words('ZM22');</script></td>
				<td><script>show_words('tt_Hour');</script></td>
			</tr>
			<tr>
				<td><script>show_words('tt_dststart');</script>
				<input type="hidden" id="DSTStart" name="DSTStart" value="030102" />
				</td>
				<td>
					<select name="tz_daylight_start_month_select" id="tz_daylight_start_month_select">
						<option value="1"><script>show_words('tt_Jan');</script></option>
						<option value="2"><script>show_words('tt_Feb');</script></option>
						<option value="3"><script>show_words('tt_Mar');</script></option>
						<option value="4"><script>show_words('tt_Apr');</script></option>
						<option value="5"><script>show_words('tt_May');</script></option>
						<option value="6"><script>show_words('tt_Jun');</script></option>
						<option value="7"><script>show_words('tt_Jul');</script></option>
						<option value="8"><script>show_words('tt_Aug');</script></option>
						<option value="9"><script>show_words('tt_Sep');</script></option>
						<option value="10"><script>show_words('tt_Oct');</script></option>
						<option value="11"><script>show_words('tt_Nov');</script></option>
						<option value="12"><script>show_words('tt_Dec');</script></option>
					</select>
				</td>
				<td>
					<select name="tz_daylight_start_week_select" id="tz_daylight_start_week_select">
						<script>set_seq_list();</script>
					</select>
				</td>
				<td>
					<select name="tz_daylight_start_dayweek_select" id="tz_daylight_start_dayweek_select">
						<script>set_week_list();</script>
					</select>
				</td>
				<td>
					<select name="tz_daylight_start_time_select" id="tz_daylight_start_time_select">
						<option value="0">0</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
						<option value="13">13</option>
						<option value="14">14</option>
						<option value="15">15</option>
						<option value="16">16</option>
						<option value="17">17</option>
						<option value="18">18</option>
						<option value="19">19</option>
						<option value="20">20</option>
						<option value="21">21</option>
						<option value="22">22</option>
						<option value="23">23</option>
					</select>
				</td>
			</tr>
			<tr>
				<td><script>show_words('tt_dstend');</script>
				<input type="hidden" id="DSTEnd" name="DSTEnd" value="100102" />
				</td>
				<td>
					<select name="tz_daylight_end_month_select" id="tz_daylight_end_month_select">
						<option value="1"><script>show_words('tt_Jan');</script></option>
						<option value="2"><script>show_words('tt_Feb');</script></option>
						<option value="3"><script>show_words('tt_Mar');</script></option>
						<option value="4"><script>show_words('tt_Apr');</script></option>
						<option value="5"><script>show_words('tt_May');</script></option>
						<option value="6"><script>show_words('tt_Jun');</script></option>
						<option value="7"><script>show_words('tt_Jul');</script></option>
						<option value="8"><script>show_words('tt_Aug');</script></option>
						<option value="9"><script>show_words('tt_Sep');</script></option>
						<option value="10"><script>show_words('tt_Oct');</script></option>
						<option value="11"><script>show_words('tt_Nov');</script></option>
						<option value="12"><script>show_words('tt_Dec');</script></option>
					</select>
				</td>
				<td>
					<select name="tz_daylight_end_week_select" id="tz_daylight_end_week_select">
						<script>set_seq_list();</script>
					</select>
				</td>
				<td>
					<select name="tz_daylight_end_dayweek_select" id="tz_daylight_end_dayweek_select">
						<script>set_week_list();</script>
					</select>
				<td>
					<select name="tz_daylight_end_time_select" id="tz_daylight_end_time_select">
						<option value="0">0</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
						<option value="13">13</option>
						<option value="14">14</option>
						<option value="15">15</option>
						<option value="16">16</option>
						<option value="17">17</option>
						<option value="18">18</option>
						<option value="19">19</option>
						<option value="20">20</option>
						<option value="21">21</option>
						<option value="22">22</option>
						<option value="23">23</option>
				   </select>
				</td>
			</tr>
			</table>
		</td>
	</tr>
	</table>
</div>

<div class="box_tn">
	<div class="CT"><script>show_words('_ntp_settings');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr id="enableNTPField">
		<td class="CL"><script>show_words('tt_EnNTP');</script></td>
		<td class="CR"><input type="checkbox" id="enableNTP" name="enableNTP" /></td>
	</tr>
	<tr id="ntpServerField" style="display: none;">
		<td class="CL"><script>show_words('_ntp_server');</script></td>
		<td class="CR">
			<select id="NTPServerIP" name="NTPServerIP">
				<option selected="selected" value=""><script>show_words('tt_SelNTPSrv');</script></option>
				<option id="pool.ntp.org" value="pool.ntp.org">pool.ntp.org</option>
				<option id="time-a.nist.gov" value="time-a.nist.gov">time-a.nist.gov</option>
				<option id="time-b.nist.gov" value="time-b.nist.gov">time-b.nist.gov</option>
				<option id="time.nist.gov" value="time.nist.gov">time.nist.gov</option>
				<option id="time.windows.com" value="time.windows.com">time.windows.com</option>
			</select>
	  </td>
	</tr>
	<tr id="TimeZoneField" style="display: none;">
		<td class="CL"><script>show_words('tt_TimeZ');</script></td>
		<td class="CR">
			<select id="time_zone">
				<option value="-192" default="false"><script>show_words('up_tz_00')</script></option>
				<option value="-176" default="false"><script>show_words('up_tz_01')</script></option>
				<option value="-160" default="false"><script>show_words('up_tz_02')</script></option>
				<option value="-144" default="false"><script>show_words('up_tz_03')</script></option>
				<option value="-128" default="true"><script>show_words('up_tz_04')</script></option>
				<option value="-112" default="false"><script>show_words('up_tz_05')</script></option>
				<option value="-112" default="false"><script>show_words('up_tz_06')</script></option>
				<option value="-96" default="false"><script>show_words('up_tz_07')</script></option>
				<option value="-96" default="false"><script>show_words('up_tz_08')</script></option>
				<option value="-96" default="false"><script>show_words('up_tz_09')</script></option>
				<option value="-96" default="false"><script>show_words('up_tz_10')</script></option>
				<option value="-80" default="false"><script>show_words('up_tz_11')</script></option>
				<option value="-80" default="false"><script>show_words('up_tz_12')</script></option>
				<option value="-80" default="false"><script>show_words('up_tz_13')</script></option>
				<option value="-64" default="false"><script>show_words('up_tz_14')</script></option>
				<option value="-64" default="false"><script>show_words('up_tz_15')</script></option>
				<option value="-64" default="false"><script>show_words('up_tz_16')</script></option>
				<option value="-56" default="false"><script>show_words('up_tz_17')</script></option>
				<option value="-48" default="false"><script>show_words('up_tz_18')</script></option>
				<option value="-48" default="false"><script>show_words('up_tz_19')</script></option>
				<option value="-48" default="false"><script>show_words('up_tz_20')</script></option>
				<option value="-32" default="false"><script>show_words('up_tz_21')</script></option>
				<option value="-16" default="false"><script>show_words('up_tz_22')</script></option>
				<option value="-16" default="false"><script>show_words('up_tz_23')</script></option>
				<option value="0" default="false"><script>show_words('up_tz_24')</script></option>
				<option value="0" default="false"><script>show_words('up_tz_25')</script></option>
				<option value="16" default="false"><script>show_words('up_tz_26')</script></option>
				<option value="16" default="false"><script>show_words('up_tz_27')</script></option>
				<option value="16" default="false"><script>show_words('up_tz_28')</script></option>
				<option value="16" default="false"><script>show_words('up_tz_29')</script></option>
				<option value="16" default="false"><script>show_words('up_tz_29b')</script></option>
				<option value="16" default="false"><script>show_words('up_tz_30')</script></option>
				<option value="32" default="false"><script>show_words('up_tz_31')</script></option>
				<option value="32" default="false"><script>show_words('up_tz_32')</script></option>
				<option value="32" default="false"><script>show_words('up_tz_33')</script></option>
				<option value="32" default="false"><script>show_words('up_tz_34')</script></option>
				<option value="32" default="false"><script>show_words('up_tz_35')</script></option>
				<option value="32" default="false"><script>show_words('up_tz_36')</script></option>
				<option value="48" default="false"><script>show_words('up_tz_37')</script></option>
				<option value="48" default="false"><script>show_words('up_tz_38')</script></option>
				<option value="48" default="false"><script>show_words('up_tz_40')</script></option>
				<option value="48" default="false"><script>show_words('up_tz_41')</script></option>
				<option value="64" default="false"><script>show_words('up_tz_39')</script></option>
				<option value="64" default="false"><script>show_words('up_tz_42')</script></option>
				<option value="64" default="false"><script>show_words('up_tz_43')</script></option>
				<option value="72" default="false"><script>show_words('up_tz_44')</script></option>
				<option value="80" default="false"><script>show_words('up_tz_46')</script></option>
				<option value="88" default="false"><script>show_words('up_tz_47')</script></option>
				<option value="92" default="false"><script>show_words('up_tz_48')</script></option>
				<option value="96" default="false"><script>show_words('up_tz_45')</script></option>
				<option value="96" default="false"><script>show_words('up_tz_50')</script></option>
				<option value="96" default="false"><script>show_words('up_tz_51')</script></option>
				<option value="96" default="false"><script>show_words('up_tz_49')</script></option>
				<option value="104" default="false"><script>show_words('up_tz_52')</script></option>
				<option value="112" default="false"><script>show_words('up_tz_74')</script></option>
				<option value="112" default="false"><script>show_words('up_tz_53')</script></option>
				<option value="128" default="false"><script>show_words('up_tz_59')</script></option>
				<option value="128" default="false"><script>show_words('up_tz_55')</script></option>
				<option value="128" default="false"><script>show_words('up_tz_57')</script></option>
				<option value="128" default="false"><script>show_words('up_tz_54')</script></option>
				<option value="128" default="false"><script>show_words('up_tz_58')</script></option>
				<option value="144" default="false"><script>show_words('up_tz_56')</script></option>
				<option value="144" default="false"><script>show_words('up_tz_60')</script></option>
				<option value="144" default="false"><script>show_words('up_tz_61')</script></option>
				<option value="152" default="false"><script>show_words('up_tz_63')</script></option>
				<option value="152" default="false"><script>show_words('up_tz_64')</script></option>
				<option value="160" default="false"><script>show_words('up_tz_62')</script></option>
				<option value="160" default="false"><script>show_words('up_tz_65')</script></option>
				<option value="160" default="false"><script>show_words('up_tz_66')</script></option>
				<option value="160" default="false"><script>show_words('up_tz_67')</script></option>
				<option value="160" default="false"><script>show_words('up_tz_68')</script></option>
				<option value="176" default="false"><script>show_words('up_tz_69')</script></option>
				<option value="176" default="false"><script>show_words('up_tz_70')</script></option>
				<option value="192" default="false"><script>show_words('up_tz_75')</script></option>
				<option value="192" default="false"><script>show_words('up_tz_71')</script></option>
				<option value="192" default="false"><script>show_words('up_tz_72')</script></option>
				<option value="208" default="false"><script>show_words('up_tz_73')</script></option>
			</select>
		</td>
	</tr>
	<tr id="ntpSyncField" style="display: none;">
		<td class="CL"><script>show_words('_ntp_sync');</script></td>
		<td class="CR"><input size="4" id="NTPSync" name="NTPSync" value="" type="text" /> &nbsp;(1~300) <script>show_words('tt_Minute');</script></td>
	</tr>
	</table>
</div>

<div id="manualField" class="box_tn" style="display: block;">
	<div class="CT"><script>show_words('_date_time_settings');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL"><script>show_words('tt_DaT');</script></td>
		<td class="CR">
			<table id="tools_time_set" cellspacing="2" cellpadding="2" border="0" summary="">
			<tr>
				<td><script>show_words('tt_Year');</script></td>
				<td>
					<select id="manual_year_select" name="manual_year_select">
						<option value="2000">2000</option>
						<option value="2001">2001</option>
						<option value="2002">2002</option>
						<option value="2003">2003</option>
						<option value="2004" selected="selected">2004</option>
						<option value="2005">2005</option>
						<option value="2006">2006</option>
						<option value="2007">2007</option>
						<option value="2008">2008</option>
						<option value="2009">2009</option>
						<option value="2010">2010</option>
						<option value="2011">2011</option>
						<option value="2012">2012</option>
						<option value="2013">2013</option>
						<option value="2014">2014</option>
						<option value="2015">2015</option>
						<option value="2016">2016</option>
						<option value="2017">2017</option>
						<option value="2018">2018</option>
						<option value="2019">2019</option>
						<option value="2020">2020</option>
						<option value="2021">2021</option>
						<option value="2022">2022</option>
						<option value="2023">2023</option>
						<option value="2024">2024</option>
						<option value="2025">2025</option>
						<option value="2026">2026</option>
						<option value="2027">2027</option>
						<option value="2028">2028</option>
						<option value="2029">2029</option>
						<option value="2030">2030</option>
						<option value="2031">2031</option>
						<option value="2032">2032</option>
						<option value="2033">2033</option>
						<option value="2034">2034</option>
						<option value="2035">2035</option>
						<option value="2036">2036</option>
						<option value="2037">2037</option>
					</select>
				</td>
				<td><script>show_words('tt_Month');</script></td>
				<td>
					<select id="manual_month_select" name="manual_month_select">
						<option value="1" selected="selected"><script>show_words('tt_Jan');</script></option>
						<option value="2"><script>show_words('tt_Feb');</script></option>
						<option value="3"><script>show_words('tt_Mar');</script></option>
						<option value="4"><script>show_words('tt_Apr');</script></option>
						<option value="5"><script>show_words('tt_May');</script></option>
						<option value="6"><script>show_words('tt_Jun');</script></option>
						<option value="7"><script>show_words('tt_Jul');</script></option>
						<option value="8"><script>show_words('tt_Aug');</script></option>
						<option value="9"><script>show_words('tt_Sep');</script></option>
						<option value="10"><script>show_words('tt_Oct');</script></option>
						<option value="11"><script>show_words('tt_Nov');</script></option>
						<option value="12"><script>show_words('tt_Dec');</script></option>
					</select>
				</td>
				<td><script>show_words('tt_Day');</script></td>
				<td>
					<select id="manual_day_select" name="manual_day_select">
						<option value="1" selected="selected">01</option>
						<option value="2">02</option>
						<option value="3">03</option>
						<option value="4">04</option>
						<option value="5">05</option>
						<option value="6">06</option>
						<option value="7">07</option>
						<option value="8">08</option>
						<option value="9">09</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
						<option value="13">13</option>
						<option value="14">14</option>
						<option value="15">15</option>
						<option value="16">16</option>
						<option value="17">17</option>
						<option value="18">18</option>
						<option value="19">19</option>
						<option value="20">20</option>
						<option value="21">21</option>
						<option value="22">22</option>
						<option value="23">23</option>
						<option value="24">24</option>
						<option value="25">25</option>
						<option value="26">26</option>
						<option value="27">27</option>
						<option value="28">28</option>
						<option value="29">29</option>
						<option value="30">30</option>
						<option value="31">31</option>
					</select>
				</td>
			</tr>
			<tr>
				<td><script>show_words('tt_Hour');</script></td>
				<td>
					<select id="manual_hour_select" name="manual_hour_select">
						<option value="0" selected="selected">00</option>
						<option value="1">01</option>
						<option value="2">02</option>
						<option value="3">03</option>
						<option value="4">04</option>
						<option value="5">05</option>
						<option value="6">06</option>
						<option value="7">07</option>
						<option value="8">08</option>
						<option value="9">09</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
						<option value="13">13</option>
						<option value="14">14</option>
						<option value="15">15</option>
						<option value="16">16</option>
						<option value="17">17</option>
						<option value="18">18</option>
						<option value="19">19</option>
						<option value="20">20</option>
						<option value="21">21</option>
						<option value="22">22</option>
						<option value="23">23</option>
					</select>
				</td>
				<td><script>show_words('tt_Minute');</script></td>
				<td>
					<select id="manual_min_select" name="manual_min_select">
						<option value="0" selected="selected">00</option>
						<option value="1">01</option>
						<option value="2">02</option>
						<option value="3">03</option>
						<option value="4">04</option>
						<option value="5">05</option>
						<option value="6">06</option>
						<option value="7">07</option>
						<option value="8">08</option>
						<option value="9">09</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
						<option value="13">13</option>
						<option value="14">14</option>
						<option value="15">15</option>
						<option value="16">16</option>
						<option value="17">17</option>
						<option value="18">18</option>
						<option value="19">19</option>
						<option value="20">20</option>
						<option value="21">21</option>
						<option value="22">22</option>
						<option value="23">23</option>
						<option value="24">24</option>
						<option value="25">25</option>
						<option value="26">26</option>
						<option value="27">27</option>
						<option value="28">28</option>
						<option value="29">29</option>
						<option value="30">30</option>
						<option value="31">31</option>
						<option value="32">32</option>
						<option value="33">33</option>
						<option value="34">34</option>
						<option value="35">35</option>
						<option value="36">36</option>
						<option value="37">37</option>
						<option value="38">38</option>
						<option value="39">39</option>
						<option value="40">40</option>
						<option value="41">41</option>
						<option value="42">42</option>
						<option value="43">43</option>
						<option value="44">44</option>
						<option value="45">45</option>
						<option value="46">46</option>
						<option value="47">47</option>
						<option value="48">48</option>
						<option value="49">49</option>
						<option value="50">50</option>
						<option value="51">51</option>
						<option value="52">52</option>
						<option value="53">53</option>
						<option value="54">54</option>
						<option value="55">55</option>
						<option value="56">56</option>
						<option value="57">57</option>
						<option value="58">58</option>
						<option value="59">59</option>
					</select>
				</td>
				<td><script>show_words('tt_Second');</script></td>
				<td>
					<select id="manual_sec_select" name="manual_sec_select">
						<option value="0" selected="selected">00</option>
						<option value="1">01</option>
						<option value="2">02</option>
						<option value="3">03</option>
						<option value="4">04</option>
						<option value="5">05</option>
						<option value="6">06</option>
						<option value="7">07</option>
						<option value="8">08</option>
						<option value="9">09</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
						<option value="13">13</option>
						<option value="14">14</option>
						<option value="15">15</option>
						<option value="16">16</option>
						<option value="17">17</option>
						<option value="18">18</option>
						<option value="19">19</option>
						<option value="20">20</option>
						<option value="21">21</option>
						<option value="22">22</option>
						<option value="23">23</option>
						<option value="24">24</option>
						<option value="25">25</option>
						<option value="26">26</option>
						<option value="27">27</option>
						<option value="28">28</option>
						<option value="29">29</option>
						<option value="30">30</option>
						<option value="31">31</option>
						<option value="32">32</option>
						<option value="33">33</option>
						<option value="34">34</option>
						<option value="35">35</option>
						<option value="36">36</option>
						<option value="37">37</option>
						<option value="38">38</option>
						<option value="39">39</option>
						<option value="40">40</option>
						<option value="41">41</option>
						<option value="42">42</option>
						<option value="43">43</option>
						<option value="44">44</option>
						<option value="45">45</option>
						<option value="46">46</option>
						<option value="47">47</option>
						<option value="48">48</option>
						<option value="49">49</option>
						<option value="50">50</option>
						<option value="51">51</option>
						<option value="52">52</option>
						<option value="53">53</option>
						<option value="54">54</option>
						<option value="55">55</option>
						<option value="56">56</option>
						<option value="57">57</option>
						<option value="58">58</option>
						<option value="59">59</option>
					</select>
				</td>
			</tr>
			</table>
		</td>
	</tr>
	</table>
</div>

<div class="box_tn">
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr align="center" valign="middle">
		<td class="btn_field">
			<input type="button" class="button_submit" id="apply" value="Apply" onclick="check_apply();" />
			<script>$('#apply').val(get_words('_adv_txt_17'));</script>
			<input type="reset" class="button_submit" id="cancel" value="Cancel" onclick="window.location.reload()" />
			<script>$('#cancel').val(get_words('_cancel'));</script>
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