<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | Tools | Schedule</title>
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

	var schd = new ccpObject();
	schd.set_param_url('used_check.ccp');
	schd.set_ccp_act('getStOfSchedule');
	schd.get_config_obj();
	
	var usedSchd = schd.config_val("usedSchd");
	
	var main = new ccpObject();
	main.set_param_url('get_set.ccp');
	main.set_ccp_act('get');
	main.add_param_arg('IGD_ScheduleRule_i_',1000);
	main.get_config_obj();
	var scheCfg = {
		'id': 				main.config_str_multi("id"),
		'name':				main.config_str_multi("schRule_RuleName_"),
		'allweek':			main.config_str_multi("schRule_AllWeekSelected_"),
		'days':				main.config_str_multi("schRule_SelectedDays_"),
		'allday':			main.config_str_multi("schRule_AllDayChecked_"),
		'timeformat':		main.config_str_multi("schRule_TimeFormat_"),
		'start_h':			main.config_str_multi("schRule_StartHour_"),
		'start_mi':			main.config_str_multi("schRule_StartMinute_"),
		'end_h':			main.config_str_multi("schRule_EndHour_"),
		'end_mi':			main.config_str_multi("schRule_EndMinute_")
	}
	var sche_rule_cnt = (scheCfg.name!=null?scheCfg.name.length:0);

	var array_inst = main.config_inst_multi("IGD_ScheduleRule_i_");
	var current_rule_cnt = 0;
	if(array_inst != null)
		current_rule_cnt = array_inst.length;

	var submit_button_flag = 0;
	var data_list = new Array();
	var rule_max_num = 32;
	var from_edit = 0;
	var non_update_name = "";	// GraceYang 2009.06.23 added
	
	var cur_edit_index = -1;
	var cur_edit_inst = 0;
	var arrayIdx = -1;
	
function add_sche(){
	if(check_value()){
		
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('queryInst');
		obj.add_param_arg('IGD_ScheduleRule_i_',1000);
		obj.get_config_obj();
		inst = obj.config_val("newInst");
		
		var basic="";
		
		var week="";
		for(var i=0;i<7;i++)
			week+=$('#week_'+i).attr('checked')?'1':'0';
		
		obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('adv_schedule.asp');
		obj.add_param_arg('schRule_RuleName_',inst,$('#ruleName').val());
		obj.add_param_arg('schRule_AllWeekSelected_',inst,$('input[name=allWeek]:checked').val());
		obj.add_param_arg('schRule_SelectedDays_',inst,week);
		obj.add_param_arg('schRule_AllDayChecked_',inst,($('#allhrs').attr('checked')?'1':'0'));
		obj.add_param_arg('schRule_TimeFormat_',inst,'1');
		obj.add_param_arg('schRule_StartHour_',inst,$('#start_hour').val());
		obj.add_param_arg('schRule_StartMinute_',inst,$('#start_min').val());
		obj.add_param_arg('schRule_EndHour_',inst,$('#end_hour').val());
		obj.add_param_arg('schRule_EndMinute_',inst,$('#end_min').val());
		obj.add_param_arg('schRule_StartMeridiem_',inst,'1');
		obj.add_param_arg('schRule_TimeFormatReal_',inst,'0');
		obj.get_config_obj();
		
		var paramForm = obj.get_param();
		
		totalWaitTime = 20; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(paramForm.url, paramForm.arg);
	}
}
function save_sche(){
	if(check_value()){
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('adv_schedule.asp');
		
		var week="";
		for(var i=0;i<7;i++)
			week+=$('#week_'+i).attr('checked')?'1':'0';
		var inst = '1.'+cur_edit_inst.substr(1,1)+'.0.0';
		obj.add_param_arg('schRule_RuleName_',inst,$('#ruleName').val());
		obj.add_param_arg('schRule_AllWeekSelected_',inst,$('input[name=allWeek]:checked').val());
		obj.add_param_arg('schRule_SelectedDays_',inst,week);
		obj.add_param_arg('schRule_AllDayChecked_',inst,($('#allhrs').attr('checked')?'1':'0'));
		obj.add_param_arg('schRule_TimeFormat_',inst,'1');
		obj.add_param_arg('schRule_StartHour_',inst,$('#start_hour').val());
		obj.add_param_arg('schRule_StartMinute_',inst,$('#start_min').val());
		obj.add_param_arg('schRule_EndHour_',inst,$('#end_hour').val());
		obj.add_param_arg('schRule_EndMinute_',inst,$('#end_min').val());
		obj.add_param_arg('schRule_StartMeridiem_',inst,'1');
		obj.add_param_arg('schRule_TimeFormatReal_',inst,'0');
		
		var paramForm = obj.get_param();
		
		totalWaitTime = 20; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(paramForm.url, paramForm.arg);
	}
}
function check_value(){
	var used_str = new String(usedSchd);
	for(var i=0;i<sche_rule_cnt;i++){
//		if(cur_edit_index!=-1 && i==cur_edit_index-1)
//			continue;
		var temp_obj = $('#ruleName').val();
		if(used_str.charAt(cur_edit_index-1) == '1')
		{
			for(var i=0;i<current_rule_cnt;i++)
			{
				if((cur_edit_index == inst_array_to_string(array_inst[i][1])) && (scheCfg.name[i] != temp_obj))
				{
					alert(addstr(get_words('GW_SCHEDULES_IN_USE_INVALID'), scheCfg.name[i]));	//GW_SCHEDULES_IN_USE_INVALID_s2, GW_SCHEDULES_IN_USE_INVALID
					return false;
				}
			}
		}
		if($('#ruleName').val() == scheCfg.name[i] && (cur_edit_index-1) !=i){
			alert(addstr(get_words('GW_SCHEDULES_NAME_CONFLICT_INVALID'), temp_obj));
			return false;
		}
	}
	if ($('#ruleName').val().length <= 0){
		alert(get_words('GW_SCHEDULES_NAME_INVALID'));
		return false;
	}
	if($('input[name=allWeek]:checked').val()=='0' && !$('#week_0').attr('checked') && !$('#week_1').attr('checked') && !$('#week_2').attr('checked') && !$('#week_3').attr('checked') && !$('#week_4').attr('checked') && !$('#week_5').attr('checked') && !$('#week_6').attr('checked'))
	{
		alert(get_words("GW_SCHEDULES_DAY_INVALID"));
		return false;
	}
	if(!$('#allhrs').attr('checked') && parseInt($('#start_hour').val())*60+parseInt($('#start_min').val()) >= parseInt($('#end_hour').val()*60)+parseInt($('#end_min').val())){
		alert(get_words('GW_SCHEDULES_TIME_INVALID').replace('%s', $('#ruleName').val()));
		return false;
	}
	return true;
}
function edit(inst, idx){
	cur_edit_index = idx+1;
	cur_edit_inst = inst;
	$('#SaveEditButton').show();
	$('#AddEditButton').hide();
	
	$('#ruleName').val(scheCfg.name[idx]);
	$('input[name=allWeek][value='+scheCfg.allweek[idx]+']').attr('checked', true);
	setEventDays();
	
	for(var i=0;i<7;i++)
		$('#week_'+i).attr('checked', (scheCfg.days[idx].substring(i,i+1)=='1'?true:false));
	
	$('#allhrs').attr('checked', (scheCfg.allday[idx]=='1'?true:false));
	setEventAllDay();
	
	$('#start_hour').val(scheCfg.start_h[idx]);
	$('#start_min').val(scheCfg.start_mi[idx]);
	$('#end_hour').val(scheCfg.end_h[idx]);
	$('#end_min').val(scheCfg.end_mi[idx]);
}
function sche_delete(inst, idx){
	var delete_inst = idx+1;
	
	/*
	** Date: 2013-05-10
	** Author: Moa Chung
	** Reason: add foolproof of deny delete when schedule is used.
	*/
	if(usedSchd.charAt(idx) == '1')
	{
		alert(addstr(get_words('GW_SCHEDULES_IN_USE_INVALID'), scheCfg.name[idx]));	//GW_SCHEDULES_IN_USE_INVALID_s2, GW_SCHEDULES_IN_USE_INVALID
		return;
	}
	
	var obj = new ccpObject();
	obj.set_param_url('get_set.ccp');
	obj.set_ccp_act('del');
	obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
	obj.set_param_next_page('adv_schedule.asp');
	
	obj.add_param_arg('IGD_ScheduleRule_i_','1.'+inst.substr(1,1)+'.0.0');
	var paramForm = obj.get_param();

	totalWaitTime = 20; //second
	redirectURL = location.pathname;
	wait_page();
	jq_ajax_post(paramForm.url, paramForm.arg);

}
	function setEventDays(){
		var func = function(){
			var chk_allweek = $('input[name=allWeek]:checked').val();
			if(chk_allweek=='1'){
				$('#week_0,#week_1,#week_2,#week_3,#week_4,#week_5,#week_6').attr('disabled','disabled');
			}
			else{
				$('#week_0,#week_1,#week_2,#week_3,#week_4,#week_5,#week_6').attr('disabled','');
			}
		};
		func();
		$('input[name=allWeek]').change(func);
		$('input[name=allWeek]').click(func);
	}
	function setEventAllDay(){
		var func = function(){
			var chk_allday = $('#allhrs').attr('checked');
			if(chk_allday){
				$('#start_hour,#end_hour,#start_min,#end_min').attr('disabled','disabled');
			}
			else{
				$('#start_hour,#end_hour,#start_min,#end_min').attr('disabled','');
			}
		};
		func();
		$('#allhrs').change(func);
	}

	function week2word(week){
		var word = [get_words('_Sun'),get_words('_Mon'),get_words('_Tue'),get_words('_Wed'),get_words('_Thu'),get_words('_Fri'),get_words('_Sat')];
		return word[parseInt(week)];
	}
	function getdays(idx){
		if(scheCfg.allweek[idx]=='1')
			return get_words('tsc_AllWk');
		else{
			var w=[];
			for(var i=0;i<7;i++)
				if(scheCfg.days[idx].substring(i,i+1)=='1')
				w.push(week2word(i));
			return w.join(', ');
		}
	}
	/* 
	 * 在字串前補0到給定長度
	 */
	function p02l(word, length){
		if(length==undefined)
			length=2;
		while(word.length<length){
			word = '0'+word;
		}
		return word;
	}
	function gettimeRanges(idx){
		if(scheCfg.allday[idx]=='1')
			return get_words('_24hr');
		else{
			var w=p02l(scheCfg.start_h[idx])+':'+p02l(scheCfg.start_mi[idx])+'~'+p02l(scheCfg.end_h[idx])+':'+p02l(scheCfg.end_mi[idx]);
			return w;
		}
	}
function setValueScheduleRuleList(){
	var obj = $('#rule_list');
	for(var i=0;i<sche_rule_cnt;i++){
	var child='<tr>\
<td id="ruleName'+i+'" align="center" class="CELL">'+scheCfg.name[i]+'</td>\
<td id="days'+i+'" align="center" class="CELL">'+getdays(i)+'</td>\
<td id="timeRanges'+i+'" align="center" class="CELL">'+gettimeRanges(i)+'</td>\
<td align="center" class="CELL"><a href="javascript:edit(\''+inst_array_to_string(array_inst[i])+'\', '+i+');"><img src="edit.gif" /></a></td>\
<td align="center" class="CELL"><a href="javascript:sche_delete(\''+inst_array_to_string(array_inst[i])+'\', '+i+');"><img src="delete.gif" /></a></td>\
</tr>';
	obj.append(child);
	}
}
$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(model);
	//Add Schedule Rule
	setEventDays();
	setEventAllDay();
	
	//Schedule Rule List
	setValueScheduleRuleList();
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
				<script>document.write(menu.build_structure(1,1,4))</script>
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
							<script>show_words('_schedule_rules');</script>
							</div>
							<div class="hr"></div>
							<div class="section_content_border">
							<div class="header_desc" id="scheduleIntroduction">
								<script>show_words('_SCHEDULE_DESC');</script>
								<p></p>
							</div>

<div id="AccessControlMain" class="box_tn">
	<div class="CT"><script>show_words('_add_sche_rule');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL"><script>show_words('_adv_txt_01');</script></td>
		<td class="CR"><input type="text" name="ruleName" id="ruleName" maxlength="15" value="" /></td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_days');</script></td>
		<td class="CR"><input type="radio" name="allWeek" value="0" checked><script>show_words('tsc_sel_days');</script>&nbsp;
			<input type="radio" name="allWeek" value="1" /><script>show_words('tsc_AllWk');</script></td>
	</tr>
	<tr>
		<td class="CL">&nbsp;</td>
		<td class="CR">
			<input type="checkbox" id="week_0" name="week_0" /><script>show_words('_Sun');</script>
			<input type="checkbox" id="week_1" name="week_1" /><script>show_words('_Mon');</script>
			<input type="checkbox" id="week_2" name="week_2" /><script>show_words('_Tue');</script>
			<input type="checkbox" id="week_3" name="week_3" /><script>show_words('_Wed');</script>
			<input type="checkbox" id="week_4" name="week_4" /><script>show_words('_Thu');</script>
			<input type="checkbox" id="week_5" name="week_5" /><script>show_words('_Fri');</script>
			<input type="checkbox" id="week_6" name="week_6" /><script>show_words('_Sat');</script>
		</td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_tsc_allday_24hr');</script></td>
		<td class="CR"><input type="checkbox" id="allhrs" name="allhrs" /></td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('tsc_start_time');</script></td>
		<td class="CR">
			<span id="start_hour_field"><select id="start_hour" name="start_hour"><option value="0">00</option><option value="1">01</option><option value="2">02</option><option value="3">03</option><option value="4">04</option><option value="5">05</option><option value="6">06</option><option value="7">07</option><option value="8">08</option><option value="9">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option></select></span> : <span id="start_min_field"><select id="start_min" name="start_min"><option value="0">00</option><option value="1">01</option><option value="2">02</option><option value="3">03</option><option value="4">04</option><option value="5">05</option><option value="6">06</option><option value="7">07</option><option value="8">08</option><option value="9">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option><option value="51">51</option><option value="52">52</option><option value="53">53</option><option value="54">54</option><option value="55">55</option><option value="56">56</option><option value="57">57</option><option value="58">58</option><option value="59">59</option></select></span>
		</td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('tsc_end_time');</script></td>
		<td class="CR">
			<span id="end_hour_field"><select id="end_hour" name="end_hour"><option value="0">00</option><option value="1">01</option><option value="2">02</option><option value="3">03</option><option value="4">04</option><option value="5">05</option><option value="6">06</option><option value="7">07</option><option value="8">08</option><option value="9">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option></select></span> : <span id="end_min_field"><select id="end_min" name="end_min"><option value="0">00</option><option value="1">01</option><option value="2">02</option><option value="3">03</option><option value="4">04</option><option value="5">05</option><option value="6">06</option><option value="7">07</option><option value="8">08</option><option value="9">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option><option value="51">51</option><option value="52">52</option><option value="53">53</option><option value="54">54</option><option value="55">55</option><option value="56">56</option><option value="57">57</option><option value="58">58</option><option value="59">59</option></select></span>
		</td>
	</tr>
	</table>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr align="center">
		<td class="btn_field">
			<input type="button" class="button_submit" id="SaveEditButton" value="Save" onclick="save_sche();" style="display:none;" />
			<script>$('#SaveEditButton').val(get_words('_save'));</script>
			<input type="button" class="button_submit" id="AddEditButton" value="Add" onclick="add_sche();" />
			<script>$('#AddEditButton').val(get_words('_add'));</script>
			<input type="reset" class="button_submit" id="ClearEditButton" value="Clear" onclick="window.location.reload(true)" />
			<script>$('#ClearEditButton').val(get_words('_clear'));</script>
		</td>
	</tr>
	</table>
</div>

<div class="box_tn">
	<div class="CT"><script>show_words('_schedule_rule_list');</script></div>
	<table id="rule_list" cellspacing="0" cellpadding="0" class="formarea">
	<tr align="center">
		<td class="CTS"><script>show_words('_adv_txt_01');</script></td>
		<td class="CTS"><script>show_words('_days');</script></td>
		<td class="CTS"><script>show_words('_time_stamp');</script></td>
		<td class="CTS"><script>show_words('_edit');</script></td>
		<td class="CTS"><script>show_words('_delete');</script></td>
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