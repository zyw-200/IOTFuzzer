<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | Basic | Guest Network</title>
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
	var selectIDArray = new Array("", "sch_AR", "sch_AREdit", "sch_URL", "sch_URLEdit", "sch_URL_Allow", "sch_URLEdit_Allow");

	var menu = new menuObject();
	menu.setSupportUSB(dev_info.KCode_USB);

	var hw_version 	= dev_info.hw_ver;
	var version 	= dev_info.fw_ver;
	var model		= dev_info.model;
	var login_Info 	= dev_info.login_info;

	var main = new ccpObject();
	main.set_param_url('get_set.ccp');
	main.set_ccp_act('get');
	main.add_param_arg('IGD_URLFilter_',1100);
	main.add_param_arg('IGD_URLFilter_URLDenyList_i_',1100);
	main.add_param_arg('IGD_URLFilter_URLAllowList_i_',1100);
	main.add_param_arg('IGD_ScheduleRule_i_',1000);
	main.add_param_arg('IGD_LANDevice_i_ConnectedAddress_i_',1100);
	main.add_param_arg('IGD_AccessControl_AddressRule_i_',1100);

	main.get_config_obj();
	//IGD_LANDevice_i_ConnectedAddress_i_
	var lanHostInfo = {
		'name'	:		main.config_str_multi("igdLanHostStatus_HostName_")||"",
		'ipaddr':		main.config_str_multi("igdLanHostStatus_HostIPv4Address_")||"",
		'mac'	:		main.config_str_multi("igdLanHostStatus_HostMACAddress_")||"",
		'type'	:		main.config_str_multi("igdLanHostStatus_HostAddressType_")||"",
		'expire':		main.config_str_multi("igdLanHostStatus_HostExpireTime_")||""
	};
	
	//IGD_AccessControl_AddressRule_i_
	var acAR_Cnt = 0;
	var acAR_Inst		= main.config_inst_multi("IGD_AccessControl_AddressRule_i_");
	if(acAR_Inst != null)
		acAR_Cnt = acAR_Inst.length;
	var AddressRuleObj = {
		count		:	acAR_Cnt,
		inst		:	acAR_Inst,
		enable		:	main.config_str_multi("addressRule_Enable_"),
		name		:	main.config_str_multi("addressRule_Name_"),
		type		:	main.config_str_multi("addressRule_AddressType_"),
		address		:	main.config_str_multi("addressRule_Address_"),
		schedule	:	main.config_str_multi("addressRule_ScheduleIndex_")
	};
	//IGD_URLFilter_URLDenyList_i_
	var acURL_Inst		= main.config_inst_multi("IGD_URLFilter_URLDenyList_i_");
	var acURL_Enable	= main.config_str_multi("urlFilterDenyList_Enable_");
	var acURL_Name		= main.config_str_multi("urlFilterDenyList_Name_");
	var acURL_URL		= main.config_str_multi("urlFilterDenyList_ManagedURL_");
	var acURL_Schedule	= main.config_str_multi("urlFilterDenyList_ScheduleIndex_");
	var acURL_Cnt = 0;
	
	if(acURL_Inst != null)
		acURL_Cnt = acURL_Inst.length;
	
	var URLDenyFilterObj = {
		count		:	acURL_Cnt,
		inst		:	acURL_Inst,
		enable		:	acURL_Enable,
		name		:	acURL_Name,
		url			:	acURL_URL,
		schedule	:	acURL_Schedule
	};
	
	//IGD_URLFilter_URLAllowList_i_
	var acAURL_Inst		= main.config_inst_multi("IGD_URLFilter_URLAllowList_i_");
	var acAURL_Enable	= main.config_str_multi("urlFilterAllowList_Enable_");
	var acAURL_Name		= main.config_str_multi("urlFilterAllowList_Name_");
	var acAURL_URL		= main.config_str_multi("urlFilterAllowList_ManagedURL_");
	var acAURL_Schedule	= main.config_str_multi("urlFilterAllowList_ScheduleIndex_");
	var acAURL_Cnt = 0;
	
	if(acAURL_Inst != null)
		acAURL_Cnt = acAURL_Inst.length;
	
	var URLAllowFilterObj = {
		count		:	acAURL_Cnt,
		inst		:	acAURL_Inst,
		enable		:	acAURL_Enable,
		name		:	acAURL_Name,
		url			:	acAURL_URL,
		schedule	:	acAURL_Schedule
	};
	
	//IGD_ScheduleRule_i_
	var array_sch_inst 		= main.config_inst_multi("IGD_ScheduleRule_i_");
	var array_schedule_name	= main.config_str_multi("schRule_RuleName_");
	var schedule_cnt = 0;
	if(array_schedule_name != null) {
		schedule_cnt = array_schedule_name.length;
	}
	
	var sch_obj = {
		cnt:		schedule_cnt,
		inst: 		array_sch_inst,
		name:		array_schedule_name,
		allweek: 	main.config_str_multi("schRule_AllWeekSelected_"),
		days: 		main.config_str_multi("schRule_SelectedDays_"),
		allday: 	main.config_str_multi("schRule_AllDayChecked_"),
		timeformat: main.config_str_multi("schRule_TimeFormat_"),
		start_h: 	main.config_str_multi("schRule_StartHour_"),
		start_mi: 	main.config_str_multi("schRule_StartMinute_"),
		start_me: 	main.config_str_multi("schRule_StartMeridiem_"),
		end_h: 		main.config_str_multi("schRule_EndHour_"),
		end_mi: 	main.config_str_multi("schRule_EndMinute_"),
		end_me: 	main.config_str_multi("schRule_EndMeridiem_")
	};	
	
	//IGD_URLFilter_
	var url_action = main.config_val("urlFilter_Action_");
	
	var acElements = new Array("URLBlock_Action"
		,"addNewURLBlock_Allow","URLList_Allow"
		,"addNewURLBlock","URLList");
	

function setEventHAAddressType()
{
	var func = function(){
	var sel_gp = $('input[name=ar_group]:checked').val();
		if(sel_gp=='0') {
			$('#ar_ip_field').show();
			$('#ar_mac_field').hide();
		}
		else if(sel_gp=='1') {
			$('#ar_ip_field').hide();
			$('#ar_mac_field').show();
		}
	}
	$('input[name=ar_group]').change(func);
	func();
}
function setValueHAIPAddressSel()
{
	for(var i=0;i<lanHostInfo.ipaddr.length;i++)
	{
		if(lanHostInfo.ipaddr[i]!='')
		{
			$('#name_selectIP').append('<option value="'+lanHostInfo.ipaddr[i]+'">'+lanHostInfo.name[i]+' ('+lanHostInfo.ipaddr[i]+')'+'</option>');
		}
	}
}
function setEventHAIPAddressSel()
{
	var func = function(){
		$('#AR_IP').val($('#name_selectIP').val());
	}
	$('#name_selectIP').change(func);
	func();
}
function setValueHAMACAddressSel()
{
	for(var i=0;i<lanHostInfo.ipaddr.length;i++)
	{
		if(lanHostInfo.ipaddr[i]!='')
		{
			$('#name_selectMAC').append('<option value="'+lanHostInfo.mac[i]+'">'+lanHostInfo.name[i]+' ('+lanHostInfo.mac[i]+')'+'</option>');
		}
	}
}
function setEventHAMACAddressSel()
{
	var func = function(){
		$('#AR_MAC').val($('#name_selectMAC').val());
	}
	$('#name_selectMAC').change(func);
	func();
}
function setEventHAAddressType_edit()
{
	var func = function(){
	var sel_gp = $('input[name=edit_ar_group]:checked').val();
		if(sel_gp=='0') {
			$('#edit_ar_ip_field').show();
			$('#edit_ar_mac_field').hide();
		}
		else if(sel_gp=='1') {
			$('#edit_ar_ip_field').hide();
			$('#edit_ar_mac_field').show();
		}
	}
	$('input[name=edit_ar_group]').change(func);
	func();
}
function setValueHAIPAddressSel_edit()
{
	for(var i=0;i<lanHostInfo.ipaddr.length;i++)
	{
		if(lanHostInfo.ipaddr[i]!='')
		{
			$('#edit_name_selectIP').append('<option value="'+lanHostInfo.ipaddr[i]+'">'+lanHostInfo.name[i]+' ('+lanHostInfo.ipaddr[i]+')'+'</option>');
		}
	}
}
function setEventHAIPAddressSel_edit()
{
	var func = function(){
		$('#edit_AR_IP').val($('#edit_name_selectIP').val());
	}
	$('#edit_name_selectIP').change(func);
	func();
}
function setValueHAMACAddressSel_edit()
{
	for(var i=0;i<lanHostInfo.name.length;i++)
	{
		if(lanHostInfo.name[i]!='')
		{
			$('#edit_name_selectMAC').append('<option value="'+lanHostInfo.mac[i]+'">'+lanHostInfo.name[i]+' ('+lanHostInfo.mac[i]+')'+'</option>');
		}
	}
}
function setEventHAMACAddressSel_edit()
{
	var func = function(){
		$('#edit_AR_MAC').val($('#edit_name_selectMAC').val());
	}
	$('#edit_name_selectMAC').change(func);
	func();
}

function check_AddressRule(name, addr, type, idx)
{

	if(name=='')
	{
		alert(addstr(get_words('up_ai_se_2'), get_words('_adv_txt_01')));
		return false;
	}
	if(addr=='')
	{
		if(type=='0')
			alert(addstr(get_words('up_ai_se_2'), get_words('_ipaddr')));
		else if(type=='1')
				alert(addstr(get_words('up_ai_se_2'), get_words('help605')));
		return false;
	}

	for(var i=0 ; i<AddressRuleObj.count ; i++)
	{
		if(AddressRuleObj.name[i]==name && idx!=i)
		{	
			alert(addstr(get_words('_webfilterrule_dup'), name));
			return false;
		}
		if(AddressRuleObj.address[i]==addr && idx!=i)
		{
			if(type=='0')
				alert(addstr(get_words('_ipaddr_used'), addr));
			else if(type=='1')
				alert(addstr(get_words('_macaddr_used'), addr));
			return false;
		}
		if(type=='0')//ip
		{
			var ip_addr_msg = replace_msg(all_ip_addr_msg,get_words('_ipaddr'));
			var temp_ip_obj = new addr_obj(addr.split("."), ip_addr_msg, false, false);
			if (!check_address(temp_ip_obj)){
				return false;
			}
		}
		else if(type=='1')//mac
		{
			if (!check_mac(addr)){
				alert(get_words('KR3'));
				return false;
			}
		}
	}
	return true;
}
function addAR()
{
	var addr='';
	var address_type = $('input[name=ar_group]:checked').val();
	if(address_type=='0') {
		addr = $('#AR_IP').val();
	}
	else if(address_type=='1') {
		addr = $('#AR_MAC').val();
	}
	if(!check_AddressRule($("#ARName").val(), addr, address_type))
		return;
	for(var idx=0 ; idx<AddressRuleObj.count ; idx++)
	{
		//find empty one
		if(AddressRuleObj.address[idx].length==0)
			break;
	}
	if(idx == AddressRuleObj.count)
	{
		alert(get_words('_rule_full'));
		//can't find empty one
		return;
	}
	
	var obj = new ccpObject();
	obj.set_param_url('get_set.ccp');
	obj.set_ccp_act('set');
	obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
	obj.set_param_next_page('basic_parental_control.asp');
	
	var inst = '1.1.'+(idx+1)+'.0';
	obj.add_param_arg('addressRule_Enable_',inst,($("#AREnable").attr('checked')? "1":"0"));
	obj.add_param_arg('addressRule_Name_',inst,$("#ARName").val());
	obj.add_param_arg('addressRule_AddressType_',inst,address_type);
	obj.add_param_arg('addressRule_Address_',inst,addr);
	obj.add_param_arg('addressRule_ScheduleIndex_',inst,$("#sch_AR").val());
	
	var param = obj.get_param();

	totalWaitTime = 20; //second
	redirectURL = location.pathname;
	wait_page();
	jq_ajax_post(param.url, param.arg);
}
function reset_ARRule()
{
	$("#AREnable").attr("checked","");
	$("#ARName").val("");
	$("#AR_IP").val("");
	$("#AR_MAC").val("");
	$("#ARscheduleField").html(makeScheduleHTML("sch_AR"),0);
}
function ar_editRow(rowIdx)
{
	$("#editARIdx").val(rowIdx-1);
	
	//Fill edit data
	if(AddressRuleObj.enable[rowIdx-1]=="1")
		$("#edit_AREnable")[0].checked = true;
	else
		$("#edit_AREnable")[0].checked = false;
	
	$("#edit_ARName").val(AddressRuleObj.name[rowIdx-1]);
	var ar_check = AddressRuleObj.type[rowIdx-1];
	$('input[name=edit_ar_group][value=0]').attr('checked',(ar_check=='0'));
	$('input[name=edit_ar_group][value=1]').attr('checked',(ar_check=='1'));
	$('input[name=edit_ar_group][value=1]').trigger('change');
	if(ar_check=='0')
		$("#edit_AR_IP").val(AddressRuleObj.address[rowIdx-1]);
	else if(ar_check=='1')
		$("#edit_AR_MAC").val(AddressRuleObj.address[rowIdx-1]);
	
	$("#edit_ARscheduleField").html(makeScheduleHTML("sch_AREdit",AddressRuleObj.schedule[rowIdx-1]));
	
	$("#editAddressRuleBlock").show();
	$("#addAddressRuleBlock").hide();

	set_checked(AddressRuleObj.schedule[rowIdx-1] == 255 ? 0 : 1, $('#sch_enable_2')[0]);
	setEnable("sch_enable_2");
}
function ar_setEdit()
{
	var row = $("#editARIdx").val();
	var addr='';
	var address_type = $('input[name=edit_ar_group]:checked').val();
	if(address_type=='0') {
		addr = $('#edit_AR_IP').val();
	}
	else if(address_type=='1') {
		addr = $('#edit_AR_MAC').val();
	}
	if(!check_AddressRule($("#edit_ARName").val(), addr, address_type, row))
		return;
	
	AddressRuleObj.type[row] = address_type;
	AddressRuleObj.name[row] = $("#edit_ARName").val();
	AddressRuleObj.address[row] = addr;
	
	if($("#edit_AREnable")[0].checked == true)
	{
		AddressRuleObj.enable[row] = "1";
	}
	else
	{
		AddressRuleObj.enable[row] = "0";
	}
	
	AddressRuleObj.schedule[row] = $("#sch_AREdit").val();
	
	ar_submitEdit(row);
}
function ar_deleteRow(rowIdx)
{
	AddressRuleObj.enable[rowIdx-1] = "0";
	AddressRuleObj.name[rowIdx-1] = "";
	AddressRuleObj.type[rowIdx-1] = "0";
	AddressRuleObj.address[rowIdx-1] = "";
	AddressRuleObj.schedule[rowIdx-1] = "255";
	
	ar_submitEdit(rowIdx-1);
}
function ar_submitEdit(row)
{
	var inst = '1.1.'+(parseInt(row,10)+1)+'.0';
	var obj = new ccpObject();
	obj.set_param_url('get_set.ccp');
	obj.set_ccp_act('set');
	obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
	obj.set_param_next_page('basic_parental_control.asp');
	
	obj.add_param_arg('addressRule_Enable_',inst,AddressRuleObj.enable[row]);
	obj.add_param_arg('addressRule_Name_',inst,AddressRuleObj.name[row]);
	obj.add_param_arg('addressRule_AddressType_',inst,AddressRuleObj.type[row]);
	obj.add_param_arg('addressRule_Address_',inst,AddressRuleObj.address[row]);
	obj.add_param_arg('addressRule_ScheduleIndex_',inst,AddressRuleObj.schedule[row]);
	
	var param = obj.get_param();

	totalWaitTime = 20; //second
	redirectURL = location.pathname;
	wait_page();
	jq_ajax_post(param.url, param.arg);
}
function ar_cancelEdit()
{
	$("#editARIdx").val(-1);
	$("#editAddressRuleBlock").hide();
	$("#addAddressRuleBlock").show();
}
function showARLists()
{
	var inHtml = '';

	for(var idx=0 ; idx < AddressRuleObj.count ; idx++)
	{
		if(AddressRuleObj.address[idx].length==0)
			continue;
		var checked = "checked";
		if(AddressRuleObj.enable[idx] == "0")
			checked = "";

		var tmp_ar_sche = (AddressRuleObj.schedule[idx]=='255'?get_words('_always'):(AddressRuleObj.schedule[idx]=='254'?get_words('_never'):sch_obj.name[AddressRuleObj.schedule[idx]-1]));

		inHtml += '' +
			'<tr align="center">'+
				'<td align="center" class="CELL">'+
					'<input disabled="disabled" type="checkbox" id="addressRuleEnable_' + idx + '" ' + checked +'/></td>' +
				'<td align="center" class="CELL" id="addressRuleName_' + idx +'">' + AddressRuleObj.name[idx] + '</td>'+
				'<td align="center" class="CELL" id="addressRuleAddress">' + AddressRuleObj.address[idx] + '</td>'+
				'<td align="center" class="CELL" id="addressRuleSchedule">' + tmp_ar_sche + '</td>' +
				'<td align="center" class="CELL" id="addressRuleEdit">'+
					'<a href="javascript:ar_editRow('+ (idx+1) +')"><img src="edit.gif" border="0" title="'+get_words('_edit')+'" /></a></td>' +
				'<td align="center" class="CELL" id="addressRuleDelete">'+
					'<a href="javascript:ar_deleteRow('+ (idx+1) +')"><img src="delete.gif" border="0" title="'+get_words('_delete')+'" /></a></td>' +
					'</td>'
			'</tr>';
		
		
	}
	document.write(inHtml);
}

function makeScheduleHTML(schId, selectIdx)
{
	var html='<select id="' + schId + '" name="' + schId + '">';
	html+='<option id="sch_always" value="255"'+(255==selectIdx?' selected="selected"':'')+'>'+get_words('_always')+'</option>';
	html+='<option id="sch_never" value="254"'+(254==selectIdx?' selected="selected"':'')+'>'+get_words('_never')+'</option>';
	for(var idx=0 ; idx< sch_obj.cnt ; idx++)
	{
	
		html+= '<option value=' + (idx+1) + ' ' + (idx+1==selectIdx?'selected="selected"':'') + ' >' + sch_obj.name[idx] + '</option>';
	}
	
	html += '</select>';
	return html;
}

function check_URL_filter(name, url, idx)
{
	if(name=='')
	{
		alert(addstr(get_words('up_ai_se_2'), get_words('_adv_txt_01')));
		return false;
	}
	if(url=='')
	{
		alert(addstr(get_words('up_ai_se_2'), get_words('help725')));
		return false;
	}
	for(var i=0 ; i<URLAllowFilterObj.count ; i++)
	{
		if(URLAllowFilterObj.name[i]==name && idx!=i)
		{
			alert(addstr(get_words('_webfilterrule_dup'), name));
			return false;
		}
		if(URLAllowFilterObj.url[i]==url && idx!=i)
		{
			alert(addstr(get_words('awf_alert_5'), url));
			return false;
		}
	}
	var tmp_url = url;
	var pos = tmp_url.indexOf("http://");  
	var pos1 = tmp_url.indexOf("https://");  
	var lpos = tmp_url.lastIndexOf("/"); 

	var strGet_url = "";
	if(pos != -1){     
		if(lpos < 7){
			strGet_url = tmp_url.substring(pos+7);
			//alert("http://"+strGet_url);
		}else{	    
			strGet_url = tmp_url.substring(pos+7,lpos);  
		}
	}else{
		if(pos1 != -1){ 
			//alert("?定的URL?效");
			alert(get_words('GW_URL_INVALID'));
			return false;
		}else{
			if(lpos != -1){
				strGet_url = tmp_url.substring(0,lpos);
				//alert(strGet_url);
			}else{	    
				strGet_url = tmp_url; 
			}
		}
	}
	return true;
}

	/** Display table functions */	
function showURLLists_Allow()
{
	var inHtml = '';

	for(var idx=0 ; idx < URLAllowFilterObj.count ; idx++)
	{
		if(URLAllowFilterObj.url[idx].length==0)
			continue;
		var checked = "checked";
		if(URLAllowFilterObj.enable[idx] == "0")
			checked = "";

		var tmp_url_sche = (URLAllowFilterObj.schedule[idx]=='255'?get_words('_always'):(URLAllowFilterObj.schedule[idx]=='254'?get_words('_never'):sch_obj.name[URLAllowFilterObj.schedule[idx]-1]));

		inHtml += '' +
			'<tr align="center">'+
				'<td align="center" class="CELL">'+
					'<input disabled="disabled" type="checkbox" id="urlFilterEnable_' + idx + '_Allow" ' + checked +'/></td>' +
				'<td align="center" class="CELL" id="urlFilterName_' + idx +'_Allow">' + URLAllowFilterObj.name[idx] + '</td>'+
				'<td align="center" class="CELL" id="urlFilterURL_Allow">' + URLAllowFilterObj.url[idx] + '</td>'+
				'<td align="center" class="CELL" id="urlFilterSchedule_Allow">' + tmp_url_sche + '</td>' +
				'<td align="center" class="CELL" id="urlFilterEdit_Allow">'+
					'<a href="javascript:url_editRow_Allow('+ (idx+1) +')"><img src="edit.gif" border="0" title="'+get_words('_edit')+'" /></a></td>' +
				'<td align="center" class="CELL" id="urlFilterDelete_Allow">'+
					'<a href="javascript:url_deleteRow_Allow('+ (idx+1) +')"><img src="delete.gif" border="0" title="'+get_words('_delete')+'" /></a></td>' +
					'</td>'
			'</tr>';
		
		
	}
	document.write(inHtml);
}
function showURLLists()
{
	var inHtml = '';

	for(var idx=0 ; idx < URLDenyFilterObj.count ; idx++)
	{
		if(URLDenyFilterObj.url[idx].length==0)
			continue;
		var checked = "checked";
		if(URLDenyFilterObj.enable[idx] == "0")
			checked = "";
			
		var tmp_url_sche = (URLDenyFilterObj.schedule[idx]=='255'?get_words('_always'):(URLDenyFilterObj.schedule[idx]=='254'?get_words('_never'):sch_obj.name[URLDenyFilterObj.schedule[idx]-1]));

		inHtml += '' +
			'<tr align="center">'+
				'<td align="center" class="CELL">'+
					'<input disabled="disabled" type="checkbox" id="urlFilterEnable_' + idx + '" ' + checked +'/></td>' +
				'<td align="center" class="CELL" id="urlFilterName_' + idx +'">' + URLDenyFilterObj.name[idx] + '</td>'+
				'<td align="center" class="CELL" id="urlFilterURL">' + URLDenyFilterObj.url[idx] + '</td>'+
				'<td align="center" class="CELL" id="urlFilterSchedule">' + tmp_url_sche + '</td>' +
				'<td align="center" class="CELL" id="urlFilterEdit">'+
					'<a href="javascript:url_editRow('+ (idx+1) +')"><img src="edit.gif" border="0" title="'+get_words('_edit')+'" /></a></td>' +
				'<td align="center" class="CELL" id="urlFilterDelete">'+
					'<a href="javascript:url_deleteRow('+ (idx+1) +')"><img src="delete.gif" border="0" title="'+get_words('_delete')+'" /></a></td>' +
					'</td>'
			'</tr>';
		
		
	}
	document.write(inHtml);
}

	/** Edit functions */
function url_editRow_Allow(rowIdx)
{
	$("#editURLIdx_Allow").val(rowIdx-1);
	
	//Fill edit data
	if(URLAllowFilterObj.enable[rowIdx-1]=="1")
		$("#edit_URLEnable_Allow")[0].checked = true;
	else
		$("#edit_URLEnable_Allow")[0].checked = false;
	
	$("#edit_URLName_Allow").val(URLAllowFilterObj.name[rowIdx-1]);
	$("#edit_URLRule_Allow").val(URLAllowFilterObj.url[rowIdx-1]);
	
	$("#edit_URLscheduleField_Allow").html(makeScheduleHTML("sch_URLEdit_Allow",URLAllowFilterObj.schedule[rowIdx-1]));
	
	$("#editURLBlock_Allow").show();
	$("#addNewURLBlock_Allow").hide();
	

	set_checked(URLAllowFilterObj.schedule[rowIdx-1] == 255 ? 0 : 1, $('#sch_enable_6')[0]);
	setEnable("sch_enable_6");
}
function url_editRow(rowIdx)
{
	$("#editURLIdx").val(rowIdx-1);
	
	//Fill edit data
	if(URLDenyFilterObj.enable[rowIdx-1]=="1")
		$("#edit_URLEnable")[0].checked = true;
	else
		$("#edit_URLEnable")[0].checked = false;
	
	$("#edit_URLName").val(URLDenyFilterObj.name[rowIdx-1]);
	$("#edit_URLRule").val(URLDenyFilterObj.url[rowIdx-1]);
	
	$("#edit_URLscheduleField").html(makeScheduleHTML("sch_URLEdit",URLDenyFilterObj.schedule[rowIdx-1]));
	
	$("#editURLBlock").show();
	$("#addNewURLBlock").hide();


	set_checked(URLDenyFilterObj.schedule[rowIdx-1] == 255 ? 0 : 1, $('#sch_enable_4')[0]);
	setEnable("sch_enable_4");
}
	
function url_setEdit_Allow()
{
	var row = $("#editURLIdx_Allow").val();
	if(!check_URL_filter($("#edit_URLName_Allow").val(), $("#edit_URLRule_Allow").val(), row))
		return;
	
	URLAllowFilterObj.name[row] = $("#edit_URLName_Allow").val();
	URLAllowFilterObj.url[row] = $("#edit_URLRule_Allow").val();
	URLDenyFilterObj.name[row] = $("#edit_URLName_Allow").val();
	URLDenyFilterObj.url[row] = $("#edit_URLRule_Allow").val();
	
	if($("#edit_URLEnable_Allow")[0].checked == true)
	{
		URLAllowFilterObj.enable[row] = "1";
		URLDenyFilterObj.enable[row] = "1";
	}
	else
	{
		URLAllowFilterObj.enable[row] = "0";
		URLDenyFilterObj.enable[row] = "0";
	}
	
	URLAllowFilterObj.schedule[row] = $("#sch_URLEdit_Allow").val();
	URLDenyFilterObj.schedule[row] = $("#sch_URLEdit_Allow").val();
	
	url_submitEdit_Allow(row);
}
function url_setEdit()
{
	var row = $("#editURLIdx").val();
	if(!check_URL_filter($("#edit_URLName").val(), $("#edit_URLRule").val(), row))
		return;
	
	URLAllowFilterObj.name[row] = $("#edit_URLName").val();
	URLAllowFilterObj.url[row] = $("#edit_URLRule").val();
	URLDenyFilterObj.name[row] = $("#edit_URLName").val();
	URLDenyFilterObj.url[row] = $("#edit_URLRule").val();
	
	if($("#edit_URLEnable")[0].checked == true)
	{
		URLAllowFilterObj.enable[row] = "1";
		URLDenyFilterObj.enable[row] = "1";
	}
	else
	{
		URLAllowFilterObj.enable[row] = "0";
		URLDenyFilterObj.enable[row] = "0";
	}
	
	URLAllowFilterObj.schedule[row] = $("#sch_URLEdit").val();
	URLDenyFilterObj.schedule[row] = $("#sch_URLEdit").val();
	
	url_submitEdit(row);
}
	
function url_deleteRow_Allow(rowIdx)
{
	URLAllowFilterObj.enable[rowIdx-1] = "0";
	URLAllowFilterObj.name[rowIdx-1] = "";
	URLAllowFilterObj.url[rowIdx-1] = "";
	URLAllowFilterObj.schedule[rowIdx-1] = "255";
	URLDenyFilterObj.enable[rowIdx-1] = "0";
	URLDenyFilterObj.name[rowIdx-1] = "";
	URLDenyFilterObj.url[rowIdx-1] = "";
	URLDenyFilterObj.schedule[rowIdx-1] = "255"
	
	url_submitEdit_Allow(rowIdx-1);
}
function url_deleteRow(rowIdx)
{
	URLAllowFilterObj.enable[rowIdx-1] = "0";
	URLAllowFilterObj.name[rowIdx-1] = "";
	URLAllowFilterObj.url[rowIdx-1] = "";
	URLAllowFilterObj.schedule[rowIdx-1] = "255";
	URLDenyFilterObj.enable[rowIdx-1] = "0";
	URLDenyFilterObj.name[rowIdx-1] = "";
	URLDenyFilterObj.url[rowIdx-1] = "";
	URLDenyFilterObj.schedule[rowIdx-1] = "255"
	
	url_submitEdit(rowIdx-1);
}
	
function url_submitEdit_Allow(row)
{
	var inst = '1.1.'+(parseInt(row,10)+1)+'.0';
	var obj = new ccpObject();
	obj.set_param_url('get_set.ccp');
	obj.set_ccp_act('set');
	obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
	obj.set_param_next_page('adv_access_control.asp');
	
	obj.add_param_arg('urlFilterAllowList_Enable_',inst,URLAllowFilterObj.enable[row]);
	obj.add_param_arg('urlFilterAllowList_Name_',inst,URLAllowFilterObj.name[row]);
	obj.add_param_arg('urlFilterAllowList_ManagedURL_',inst,URLAllowFilterObj.url[row]);
	obj.add_param_arg('urlFilterAllowList_ScheduleIndex_',inst,URLAllowFilterObj.schedule[row]);
	obj.add_param_arg('urlFilterDenyList_Enable_',inst,URLDenyFilterObj.enable[row]);
	obj.add_param_arg('urlFilterDenyList_Name_',inst,URLDenyFilterObj.name[row]);
	obj.add_param_arg('urlFilterDenyList_ManagedURL_',inst,URLDenyFilterObj.url[row]);
	obj.add_param_arg('urlFilterDenyList_ScheduleIndex_',inst,URLDenyFilterObj.schedule[row]);
	
	var uniObj = $.unique(URLAllowFilterObj.name);
	if(uniObj.length==1 && uniObj[0]=="") //all disable
		obj.add_param_arg('accessCtrl_GlobalEnable_','1.1.0.0','0');
	else
		obj.add_param_arg('accessCtrl_GlobalEnable_','1.1.0.0','1');
	
	var param = obj.get_param();

	totalWaitTime = 20; //second
	redirectURL = location.pathname;
	wait_page();
	jq_ajax_post(param.url, param.arg);
}
function url_submitEdit(row)
{
	var inst = '1.1.'+(parseInt(row,10)+1)+'.0';
	var obj = new ccpObject();
	obj.set_param_url('get_set.ccp');
	obj.set_ccp_act('set');
	obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
	obj.set_param_next_page('adv_access_control.asp');
	
	obj.add_param_arg('urlFilterAllowList_Enable_',inst,URLAllowFilterObj.enable[row]);
	obj.add_param_arg('urlFilterAllowList_Name_',inst,URLAllowFilterObj.name[row]);
	obj.add_param_arg('urlFilterAllowList_ManagedURL_',inst,URLAllowFilterObj.url[row]);
	obj.add_param_arg('urlFilterAllowList_ScheduleIndex_',inst,URLAllowFilterObj.schedule[row]);
	obj.add_param_arg('urlFilterDenyList_Enable_',inst,URLDenyFilterObj.enable[row]);
	obj.add_param_arg('urlFilterDenyList_Name_',inst,URLDenyFilterObj.name[row]);
	obj.add_param_arg('urlFilterDenyList_ManagedURL_',inst,URLDenyFilterObj.url[row]);
	obj.add_param_arg('urlFilterDenyList_ScheduleIndex_',inst,URLDenyFilterObj.schedule[row]);
	
	var uniObj = $.unique(URLAllowFilterObj.name);
	if(uniObj.length==1 && uniObj[0]=="") //all disable
		obj.add_param_arg('accessCtrl_GlobalEnable_','1.1.0.0','0');
	else
		obj.add_param_arg('accessCtrl_GlobalEnable_','1.1.0.0','1');
	
	var param = obj.get_param();

	totalWaitTime = 20; //second
	redirectURL = location.pathname;
	wait_page();
	jq_ajax_post(param.url, param.arg);
}

function url_cancelEdit_Allow()
{
	$("#editURLIdx_Allow").val(-1);
	$("#editURLBlock_Allow").hide();
	$("#addNewURLBlock_Allow").show();
}
function url_cancelEdit()
{
	$("#editURLIdx").val(-1);
	$("#editURLBlock").hide();
	$("#addNewURLBlock").show();
}

function addURL_Allow()
{
	if(!check_URL_filter($("#URLName_Allow").val(), $("#URLRule_Allow").val()))
		return;
	urlapply_action();
	for(var idx=0 ; idx<URLAllowFilterObj.count ; idx++)
	{
		//find empty one
		if(URLAllowFilterObj.url[idx].length==0)
			break;
	}
	if(idx == URLAllowFilterObj.count)
	{
		alert(get_words('_rule_full'));
		//can't find empty one
		return;
	}
	
	var obj = new ccpObject();
	obj.set_param_url('get_set.ccp');
	obj.set_ccp_act('set');
	obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
	obj.set_param_next_page('adv_access_control.asp');
	
	var inst = '1.1.'+(idx+1)+'.0';
	obj.add_param_arg('accessCtrl_GlobalEnable_','1.1.0.0','1');
	obj.add_param_arg('urlFilterAllowList_Enable_',inst,($("#URLEnable_Allow")[0].checked? "1":"0"));
	obj.add_param_arg('urlFilterAllowList_Name_',inst,$("#URLName_Allow").val());
	obj.add_param_arg('urlFilterAllowList_ManagedURL_',inst,$("#URLRule_Allow").val());
	obj.add_param_arg('urlFilterAllowList_ScheduleIndex_',inst,$("#sch_URL_Allow").val());
	obj.add_param_arg('urlFilterDenyList_Enable_',inst,($("#URLEnable_Allow")[0].checked? "1":"0"));
	obj.add_param_arg('urlFilterDenyList_Name_',inst,$("#URLName_Allow").val());
	obj.add_param_arg('urlFilterDenyList_ManagedURL_',inst,$("#URLRule_Allow").val());
	obj.add_param_arg('urlFilterDenyList_ScheduleIndex_',inst,$("#sch_URL_Allow").val());
	var param = obj.get_param();

	totalWaitTime = 20; //second
	redirectURL = location.pathname;
	wait_page();
	jq_ajax_post(param.url, param.arg);
}
function addURL()
{
	if(!check_URL_filter($("#URLName").val(), $("#URLRule").val()))
		return;
	urlapply_action();
	for(var idx=0 ; idx<URLDenyFilterObj.count ; idx++)
	{
		//find empty one
		if(URLDenyFilterObj.url[idx].length==0)
			break;
	}
	if(idx == URLDenyFilterObj.count)
	{
		alert(get_words('_rule_full'));
		//can't find empty one
		return;
	}
	
	var obj = new ccpObject();
	obj.set_param_url('get_set.ccp');
	obj.set_ccp_act('set');
	obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
	obj.set_param_next_page('adv_access_control.asp');
	
	var inst = '1.1.'+(idx+1)+'.0';
	obj.add_param_arg('accessCtrl_GlobalEnable_','1.1.0.0','1');
	obj.add_param_arg('urlFilterAllowList_Enable_',inst,($("#URLEnable")[0].checked? "1":"0"));
	obj.add_param_arg('urlFilterAllowList_Name_',inst,$("#URLName").val());
	obj.add_param_arg('urlFilterAllowList_ManagedURL_',inst,$("#URLRule").val());
	obj.add_param_arg('urlFilterAllowList_ScheduleIndex_',inst,$("#sch_URL").val());
	obj.add_param_arg('urlFilterDenyList_Enable_',inst,($("#URLEnable")[0].checked? "1":"0"));
	obj.add_param_arg('urlFilterDenyList_Name_',inst,$("#URLName").val());
	obj.add_param_arg('urlFilterDenyList_ManagedURL_',inst,$("#URLRule").val());
	obj.add_param_arg('urlFilterDenyList_ScheduleIndex_',inst,$("#sch_URL").val());
	var param = obj.get_param();

	totalWaitTime = 20; //second
	redirectURL = location.pathname;
	wait_page();
	jq_ajax_post(param.url, param.arg);
}

function reset_URLRule_Allow()
{
	$("#URLEnable_Allow")[0].checked = false;
	$("#URLName_Allow").val("");
	$("#URLRule_Allow").val("");
	$("#URLscheduleField_Allow").html(makeScheduleHTML("sch_URL"),0);
}
function reset_URLRule()
{
	$("#URLEnable")[0].checked = false;
	$("#URLName").val("");
	$("#URLRule").val("");
	$("#URLscheduleField").html(makeScheduleHTML("sch_URL"),0);
}

function urlapply_action(){
	var sel_action = $('#url_domain_filter_type').val();
	
	var obj = new ccpObject();
	obj.set_param_url('get_set.ccp');
	obj.set_ccp_act('set');
	obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
	obj.set_param_next_page('adv_access_control.asp');
	obj.add_param_arg('urlFilter_Action_','1.1.0.0',sel_action);
	var param = obj.get_param();

	totalWaitTime = 20; //second
	redirectURL = location.pathname;
	wait_page();
	jq_ajax_post(param.url, param.arg);
}

function url_domain_action(){
	var sel_action = $('#url_domain_filter_type').val();
	if(sel_action=='0')
	{
		$('#addNewURLBlock_Allow, #URLList_Allow').hide();
		$('#addNewURLBlock, #URLList').show();
		setEnable("sch_enable_3");
	}
	else if(sel_action=='1')
	{
		$('#addNewURLBlock_Allow, #URLList_Allow').show();
		$('#addNewURLBlock, #URLList').hide();
		setEnable("sch_enable_5");
	}
	else
	{
		$('#addNewURLBlock_Allow, #URLList_Allow').hide();
		$('#addNewURLBlock, #URLList').hide();
	}
	$('#editURLBlock_Allow, #editURLBlock').hide();
}
$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(model);
	//Household Access
	setEventHAAddressType();
	setValueHAIPAddressSel();
	setEventHAIPAddressSel();
	setValueHAMACAddressSel();
	setEventHAMACAddressSel();
	setEventHAAddressType_edit();
	setValueHAIPAddressSel_edit();
	setEventHAIPAddressSel_edit();
	setValueHAMACAddressSel_edit();
	setEventHAMACAddressSel_edit();
		//load IGD_URLFilter_Action
		$('#url_domain_filter_type').val(url_action);
		url_domain_action();
	setEnable("sch_enable_1");
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
			<td style="background-image:url('/image/bg_l.gif');background-repeat:repeat-y;vertical-align:top;width:270px;" width="270">
				<div style="padding-left:6px;">
				<script>document.write(menu.build_structure(0,3,-1));</script>
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
								<div class="headerbg" id="manStatusTitle">
								<script>show_words('_parental_control');</script>
								</div>
								<div class="hr"></div>
								<div class="section_content_border">
									<div class="header_desc" id="basicIntroduction">
										<p></p>
									</div>

<div id="addAddressRuleBlock" class="box_tn">
	<div id="addAddressRule" class="CT">
		<script>show_words('_add_ha_rule');</script>
	</div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL">
			<script>show_words('_enabled');</script></td>
		<td colspan="2" class="CR">
			<input type="checkbox" id="AREnable" />
		</td>
	</tr>
	<tr>
		<td class="CL">
			<script>show_words('_adv_txt_01');</script></td>
		<td colspan="2" class="CR">
			<input type="text" id="ARName" maxlength="20" size="25" value=""/>
		</td>
	</tr>
	<tr>
		<td class="CL">
			<script>show_words('aa_AT');</script></td>
		<td colspan="2" class="CR">
			<input type="radio" id="ar_ip" name="ar_group" value="0" default="false">
			<script>show_words('aa_AT_0');</script>
			<input type="radio" id="ar_mac" name="ar_group" value="1" checked="checked" default="true">
			<script>show_words('aa_AT_1');</script>
		</td>
	</tr>
	<tr id="ar_ip_field" style="display:none;">
		<td class="CL">
			<script>show_words('_ipaddr');</script></td>
		<td colspan="2" class="CR">
			<input type="text" id="AR_IP" maxlength="15" size="20" value=""/>
			<span> &lt;&lt; </span>
			<select name="selectIP" id="name_selectIP">
				<option value=""><script>show_words('_hostname');</script></option>
			</select>
		</td>
	</tr>
	<tr id="ar_mac_field" style="display:none;">
		<td class="CL">
			<script>show_words('_macaddr');</script></td>
		<td colspan="2" class="CR">
			<input type="text" id="AR_MAC" maxlength="17" size="20" value=""/>
			<span> &lt;&lt; </span>
			<select name="selectMAC" id="name_selectMAC">
				<option value=""><script>show_words('_hostname');</script></option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="CL">
			<script>show_words('_sched');</script></td>
		<td colspan="2" class="CR">
			<input type="checkbox" id="sch_enable_1" value="1" /> &nbsp;
			<span id="ARscheduleField"></span>
			<script>
				/** Make schedule select */
				$("#ARscheduleField").html(makeScheduleHTML("sch_AR"),0);</script>
			<input type="button" id="ln_btn_1" onclick="location.assign('/adv_schedule.asp');" />
			<script>$('#ln_btn_1').val(get_words('tc_new_sch'));</script>
		</td>
	</tr>
	<tr align="center">
		<td colspan="3" class="btn_field">
			<!-- Add / Cancel Button here -->
			<input type="button" id="btnAddARRule" value="Add" onClick="addAR();" class="button_submit"/>
			<input type="button" id="btnCancelARRule" value="Reset" onCLick="reset_ARRule();" class="button_submit"/>
			<script> /** change button value here */ 
				$("#btnAddARRule").val(get_words('_add'));
				$("#btnCancelARRule").val(get_words('_reset'));
			</script>
		</td>
	</tr>
	</table>
</div>

<div id="editAddressRuleBlock" class="box_tn" style="display:none;">
	<div id="editAddressRule" class="CT">
		<script>show_words('_edit_ha_rule');</script>
	</div>
	<input type="hidden" id="editARIdx" value="-1" />
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL">
			<script>show_words('_enabled');</script></td>
		<td colspan="2" class="CR">
			<input type="checkbox" id="edit_AREnable" />
		</td>
	</tr>
	<tr>
		<td class="CL">
			<script>show_words('_adv_txt_01');</script></td>
		<td colspan="2" class="CR">
			<input type="text" id="edit_ARName" maxlength="20" size="25" value=""/>
		</td>
	</tr>
	<tr>
		<td class="CL">
			<script>show_words('aa_AT');</script></td>
		<td colspan="2" class="CR">
			<input type="radio" id="edit_ar_ip" name="edit_ar_group" value="0">
			<script>show_words('aa_AT_0');</script>
			<input type="radio" id="edit_ar_mac" name="edit_ar_group" value="1">
			<script>show_words('aa_AT_1');</script>
		</td>
	</tr>
	<tr id="edit_ar_ip_field" style="display:none;">
		<td class="CL">
			<script>show_words('_ipaddr');</script></td>
		<td colspan="2" class="CR">
			<input type="text" id="edit_AR_IP" maxlength="15" size="20" value="" />
			<span> &lt;&lt; </span>
			<select name="selectIP" id="edit_name_selectIP">
				<option value="0"><script>show_words('_hostname');</script></option>
			</select>
		</td>
	</tr>
	<tr id="edit_ar_mac_field" style="display:none;">
		<td class="CL">
			<script>show_words('aa_MAC');</script></td>
		<td colspan="2" class="CR">
			<input type="text" id="edit_AR_MAC" maxlength="17" size="20" value="" />
			<span> &lt;&lt; </span>
			<select name="selectMAC" id="edit_name_selectMAC">
				<option value="0"><script>show_words('_hostname');</script></option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="CL">
			<script>show_words('_sched');</script></td>
		<td colspan="2" class="CR">
			<input type="checkbox" id="sch_enable_2" value="1" /> &nbsp;
			<span id="edit_ARscheduleField"></span>
			<script>
				/** Make schedule select */
				$("#edit_ARscheduleField").html(makeScheduleHTML("sch_AREdit"),0);</script>
			<input type="button" id="ln_btn_2" onclick="location.assign('/adv_schedule.asp');" />
			<script>$('#ln_btn_2').val(get_words('tc_new_sch'));</script>
		</td>
	</tr>
	<tr align="center">
		<td colspan="3" class="btn_field">
			<!-- Add / Cancel Button here -->
			<input type="button" id="btnEditARRule" value="Add" onClick="ar_setEdit();" class="button_submit"/>
			<input type="button" id="btnCancelEditARRule" value="Cancel" onClick="ar_cancelEdit();" class="button_submit"/>
			<script> /** change button value here */ 
				$("#btnEditARRule").val(get_words('_edit'));
				$("#btnCancelEditARRule").val(get_words('_cancel'));
			</script>
		</td>
	</tr>
	</table>
</div>

<div id="ARList" class="box_tn">
	<div class="CT">
		<script>show_words('_ha_rule_list');</script>
	</div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr align="center">
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('_enabled');</script></td>
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('_adv_txt_01');</script></td>
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('_address');</script></td>
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('_sched');</script></td>
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('_edit');</script></td>
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('_delete');</script></td>
	</tr>
	<script>
		/** display Rules */
		showARLists();
	</script>
	</table>
</div>

<div id="URLBlock_Action" class="box_tn">
	<div id="URL_Action" class="CT">
		<script>show_words('_websfilter');</script>
	</div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL">
			<script>show_words('_websfilter')</script>
		</td>
		<td colspan="2" class="CR">
			<select id="url_domain_filter_type" name="url_domain_filter_type" onchange="url_domain_action()">
				<option value="2"><script>show_words('_disable')</script></option>
				<option value="0"><script>show_words('dlink_wf_op_0')</script></option>
				<option value="1"><script>show_words('dlink_wf_op_1')</script></option>
			</select>
		</td>
	</tr>
	<tr align="center">
		<td colspan="3" class="btn_field">
			<input type="button" id="btnURLAction" value="Add" onClick="urlapply_action();" class="button_submit"/>
			<script> /** change button value here */ 
				$("#btnURLAction").val(get_words('_apply'));
			</script>
		</td>
	</tr>
	</table>
</div>

<div id="addNewURLBlock_Allow" class="box_tn">
	<div id="addNewURL_Allow" class="CT">
		<script>show_words('_add_weburl_rule');</script>
	</div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL">
			<script>show_words('_enable');</script></td>
		<td colspan="2" class="CR">
			<input type="checkbox" id="URLEnable_Allow" />
		</td>
	</tr>
	<tr>
		<td class="CL">
			<script>show_words('_adv_txt_01');</script></td>
		<td colspan="2" class="CR">
			<input type="text" id="URLName_Allow" maxlength="20" size="25" value=""/>
		</td>
	</tr>
	<tr>
		<td class="CL">
			<script>show_words('help725');</script></td>
		<td colspan="2" class="CR">
			<input type="text" id="URLRule_Allow" maxlength="32" size="40" value=""/>
			(<script>show_words('_url_ex');</script>)
		</td>
	</tr>
	<tr>
		<td class="CL">
			<script>show_words('_sched');</script></td>
		<td colspan="2" class="CR">
			<input type="checkbox" id="sch_enable_5" value="1" /> &nbsp;
			<span id="URLscheduleField_Allow"></span>
			<script>
				/** Make schedule select */
				$("#URLscheduleField_Allow").html(makeScheduleHTML("sch_URL_Allow"),0);</script>
			<input type="button" id="ln_btn_5" onclick="location.assign('/adv_schedule.asp');" />
			<script>$('#ln_btn_5').val(get_words('tc_new_sch'));</script>
		</td>
	</tr>
	<tr align="center">
		<td colspan="3" class="btn_field">
			<!-- Add / Cancel Button here -->
			<input type="button" id="btnAddURLRule_Allow" value="Add" onClick="addURL_Allow();" class="button_submit"/>
			<input type="button" id="btnCancelURLRule_Allow" value="Reset" onCLick="reset_URLRule_Allow();" class="button_submit"/>
			<script> /** change button value here */ 
				$("#btnAddURLRule_Allow").val(get_words('_add'));
				$("#btnCancelURLRule_Allow").val(get_words('_reset'));
			</script>
		</td>
	</tr>
	</table>
</div>

<div id="editURLBlock_Allow" class="box_tn" style="display:none;">
	<div id="editNewURL_Allow" class="CT">
		<script>show_words('_edit_weburl_rule');</script>
	</div>
	<input type="hidden" id="editURLIdx_Allow" value="-1" />
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL">
			<script>show_words('_enable');</script></td>
		<td colspan="2" class="CR">
			<input type="checkbox" id="edit_URLEnable_Allow" />
		</td>
	</tr>
	<tr>
		<td class="CL">
			<script>show_words('_adv_txt_01');</script></td>
		<td colspan="2" class="CR">
			<input type="text" id="edit_URLName_Allow" maxlength="20" size="25" value=""/>
		</td>
	</tr>
	<tr>
		<td class="CL">
			<script>show_words('help725');</script></td>
		<td colspan="2" class="CR">
			<input type="text" id="edit_URLRule_Allow" maxlength="32" size="40" value=""/>
			(<script>show_words('_url_ex');</script>)
		</td>
	</tr>
	
	<tr>
		<td class="CL">
			<script>show_words('_sched');</script></td>
		<td colspan="2" class="CR">
			<input type="checkbox" id="sch_enable_6" value="1" /> &nbsp;
			<span id="edit_URLscheduleField_Allow"></span>
			<input type="button" id="ln_btn_6" onclick="location.assign('/adv_schedule.asp');" />
			<script>$('#ln_btn_6').val(get_words('tc_new_sch'));</script>
		</td></tr>
	<tr align="center">
		<td colspan="3" class="btn_field">
			<!-- Add / Cancel Button here -->
			<input type="button" id="btnEditURLRule_Allow" value="Add" onClick="url_setEdit_Allow();" class="button_submit"/>
			<input type="button" id="btnCancelEditURLRule_Allow" value="Cancel" onClick="url_cancelEdit_Allow();" class="button_submit"/>
			<script> /** change button value here */ 
				$("#btnEditURLRule_Allow").val(get_words('_edit'));
				$("#btnCancelEditURLRule_Allow").val(get_words('_cancel'));
			</script>
		</td>
	</tr>
	</table>
</div>

<div id="URLList_Allow" class="box_tn">
	<div class="CT"><script>show_words('_weburl_rule_list');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr align="center">
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('_enable');</script></td>
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('_adv_txt_01');</script></td>
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('help725');</script></td>
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('_sched');</script></td>
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('_edit');</script></td>
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('_delete');</script></td>
	</tr>
	<script>
		/** display Rules */
		showURLLists_Allow();
	</script>
	</table>
</div>

<div id="addNewURLBlock" class="box_tn">
	<div id="addNewURL" class="CT">
		<script>show_words('_add_weburl_rule');</script>
	</div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL">
			<script>show_words('_enable');</script></td>
		<td colspan="2" class="CR">
			<input type="checkbox" id="URLEnable" />
		</td>
	</tr>
	<tr>
		<td class="CL">
			<script>show_words('_adv_txt_01');</script></td>
		<td colspan="2" class="CR">
			<input type="text" id="URLName" maxlength="20" size="25" value=""/>
		</td>
	</tr>
	<tr>
		<td class="CL">
			<script>show_words('help725');</script></td>
		<td colspan="2" class="CR">
			<input type="text" id="URLRule" maxlength="32" size="40" value=""/>
			(<script>show_words('_url_ex');</script>)
		</td>
	</tr>
	<tr>
		<td class="CL">
			<script>show_words('_sched');</script></td>
		<td colspan="2" class="CR">
			<input type="checkbox" id="sch_enable_3" value="1" /> &nbsp;
			<span id="URLscheduleField"></span>
			<script>
				/** Make schedule select */
				$("#URLscheduleField").html(makeScheduleHTML("sch_URL"),0);</script>
			<input type="button" id="ln_btn_3" onclick="location.assign('/adv_schedule.asp');" />
			<script>$('#ln_btn_3').val(get_words('tc_new_sch'));</script>
		</td>
	</tr>
	<tr align="center">
		<td colspan="3" class="btn_field">
			<!-- Add / Cancel Button here -->
			<input type="button" id="btnAddURLRule" value="Add" onClick="addURL();" class="button_submit"/>
			<input type="button" id="btnCancelURLRule" value="Reset" onCLick="reset_URLRule();" class="button_submit"/>
			<script> /** change button value here */ 
				$("#btnAddURLRule").val(get_words('_add'));
				$("#btnCancelURLRule").val(get_words('_reset'));
			</script>
		</td>
	</tr>
	</table>
</div>

<div id="editURLBlock" class="box_tn" style="display:none;">
	<div id="editNewURL" class="CT">
		<script>show_words('_edit_weburl_rule');</script>
	</div>
	<input type="hidden" id="editURLIdx" value="-1" />
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL">
			<script>show_words('_enable');</script></td>
		<td colspan="2" class="CR">
			<input type="checkbox" id="edit_URLEnable" />
		</td>
	</tr>
	<tr>
		<td class="CL">
			<script>show_words('_adv_txt_01');</script></td>
		<td colspan="2" class="CR">
			<input type="text" id="edit_URLName" maxlength="20" size="25" value=""/>
		</td>
	</tr>
	<tr>
		<td class="CL">
			<script>show_words('help725');</script></td>
		<td colspan="2" class="CR">
			<input type="text" id="edit_URLRule" maxlength="32" size="40" value=""/>
			(<script>show_words('_url_ex');</script>)
		</td>
	</tr>
	<tr>
		<td class="CL">
			<script>show_words('_sched');</script></td>
		<td colspan="2" class="CR">
			<input type="checkbox" id="sch_enable_4" value="1" /> &nbsp;
			<span id="edit_URLscheduleField"></span>
			<input type="button" id="ln_btn_4" onclick="location.assign('/adv_schedule.asp');" />
			<script>$('#ln_btn_4').val(get_words('tc_new_sch'));</script>
		</td></tr>
	<tr align="center">
		<td colspan="3" class="btn_field">
			<!-- Add / Cancel Button here -->
			<input type="button" id="btnEditURLRule" value="Add" onClick="url_setEdit();" class="button_submit"/>
			<input type="button" id="btnCancelEditURLRule" value="Cancel" onClick="url_cancelEdit();" class="button_submit"/>
			<script> /** change button value here */ 
				$("#btnEditURLRule").val(get_words('_edit'));
				$("#btnCancelEditURLRule").val(get_words('_cancel'));
			</script>
		</td>
	</tr>
	</table>
</div>

<div id="URLList" class="box_tn">
	<div class="CT">
		<script>show_words('_weburl_rule_list');</script>
	</div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr align="center">
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('_enable');</script></td>
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('_adv_txt_01');</script></td>
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('help725');</script></td>
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('_sched');</script></td>
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('_edit');</script></td>
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('_delete');</script></td>
	</tr>
	<script>
		/** display Rules */
		showURLLists();
	</script>
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