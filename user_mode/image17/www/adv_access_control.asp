<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<script>
	var funcWinOpen = window.open;
</script>
<title>TRENDNET | modelName | Advanced | Access Control</title>
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
	var maxACRule = 24;
	var total_page = 8;
	var pfRuleMax = 8;
	var editRow = -1;
	var editPageLoad = 0;
	var pPage = 1;
	var p5MachList = null;
	var is_modified = 0;
	var def_title = document.title;
	var misc = new ccpObject();
	var dev_info = misc.get_router_info();
	document.title = def_title.replace("modelName", dev_info.model);
	var selectIDArray = new Array("", "sch_Port", "sch_PortEdit", "sch_IP", "sch_IPEdit", "sch_URL", "sch_URLEdit", "sch_URL_Allow", "sch_URLEdit_Allow");
	
	var menu = new menuObject();
	menu.setSupportUSB(dev_info.KCode_USB);

	/** misc functions */
	function get_by_name(name){
		return document.getElementsByName(name);
	}
	
	function mySqrt(num, base)
	{
		if(num % base != 0)
			return -1;
		var ret = 1;
		
		while(num / base != 1)
		{
			ret++;
			num = num / base;
		}
		return ret;
	}
	
	function calculate_ipMask(maskBit)
	{
		/** calculate the mask ip */
		var ipMask = new Array(0,0,0,0);
		
		for(var idx=0 ; idx<4 ; idx++)
		{
			if(maskBit<=0)
				continue;
			
			if(maskBit>=8)
			{
				ipMask[idx] = 255;
			}
			else
			{
				maskBit = 8 - maskBit;
				ipMask[idx] = 256 - Math.pow(2,maskBit);
			}
			maskBit -= 8;
		}
		
		return ipMask[0] + "." + ipMask[1] + "." + ipMask[2] + "." + ipMask[3];
	}
	
	function ipMast_to_Num(mask)
	{
		if(mask==null || mask.length==0)
			return 0;
			
		var masks = mask.split(".");
		
		if(masks.length != 4)
			return 0;
		
		var maskBit = 0;
		
		for(var idx=0 ; idx<masks.length ; idx++)
		{
			if(masks[idx].length==0)
				return 0;
			
			var intMask = parseInt(masks[idx]);
			
			if(intMask == 255)
				maskBit += 8;
			else
			{
				maskBit += (8 - mySqrt(256- intMask,2));
			}
		}
		
		return maskBit;
	}
	/*
	** Date:	2013-05-10
	** Author:	Moa Chung
	** Reason:	schedule option to Always and Never
	**/
	function makeScheduleHTML(schId, selectIdx)
	{
		var html='<select id="' + schId + '" name="' + schId + '">';
		html+='<option value="255"'+(255==selectIdx?' selected="selected"':'')+'>'+get_words('_always')+'</option>';
		html+='<option value="254"'+(254==selectIdx?' selected="selected"':'')+'>'+get_words('_never')+'</option>';
		for(var idx=0 ; idx< sch_obj.cnt ; idx++)
		{
		
			html+= '<option value=' + (idx+1) + ' ' + (idx+1==selectIdx?'selected="selected"':'') + ' >' + sch_obj.name[idx] + '</option>';
		}
		
		html += '</select>';
		return html;
	}
	
	/** misc function End */
	
	
	/** Page Load */
	var main = new ccpObject();
	main.set_param_url('get_set.ccp');
	main.set_ccp_act('get');
	main.add_param_arg('IGD_URLFilter_URLDenyList_i_',1100);
	main.add_param_arg('IGD_AccessControl_',1100);
	main.add_param_arg('IGD_AccessControl_PortRange_i_',1100);
	main.add_param_arg('IGD_AccessControl_IPRule_i_',1100);
	main.add_param_arg('IGD_ScheduleRule_i_',1000);
	main.add_param_arg('IGD_URLFilter_URLAllowList_i_',1100);
	main.add_param_arg('IGD_URLFilter_',1100);
	main.get_config_obj();
	
	//IGD_AccessControl_
	var acGlobalEnable 	= main.config_val('accessCtrl_GlobalEnable_');
	
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
	
	//IGD_AccessControl_PortRange_
	var acPort_Inst 	= main.config_inst_multi("IGD_AccessControl_PortRange_i_");
	var acPort_Cnt = 0;
	
	if(acPort_Inst != null)
		acPort_Cnt = acPort_Inst.length;
	
	var PortRangeObj = {
		count		:	acPort_Cnt,
		enable		:	main.config_str_multi("portRange_Enable_"),
		name		:	main.config_str_multi("portRange_Name_"),
		schedule	:	main.config_str_multi("portRange_ScheduleIndex_"),
		ipStart		:	main.config_str_multi("portRange_IPStart_"),
		ipEnd		:	main.config_str_multi("portRange_IPEnd_"),
		isUser		:	main.config_str_multi("portRange_UserDefine_"),
		tcpPorts	:	main.config_str_multi("portRange_TCPPorts_"),
		udpPorts	:	main.config_str_multi("portRange_UDPPorts_"),
		WWWEnable	:	main.config_str_multi("portRange_WWWEnable_"),
		SMTPEnable	:	main.config_str_multi("portRange_SMTPEnable_"),
		POP3Enable	:	main.config_str_multi("portRange_POP3Enable_"),
		FTPEnable	:	main.config_str_multi("portRange_FTPEnable_"),
		TELNETEnable:	main.config_str_multi("portRange_TELNETEnable_"),
		DNSEnable	:	main.config_str_multi("portRange_DNSEnable_"),
		TCPEnable	:	main.config_str_multi("portRange_TCPEnable_"),
		UDPEnable	:	main.config_str_multi("portRange_UDPEnable_")
	};
	
	//IGD_AccessControl_IPRule_i_
	var acIP_Inst 	= main.config_inst_multi("IGD_AccessControl_IPRule_i_");
	var acIPCnt		= 0;
	
	if(acIP_Inst != null)
		acIPCnt = acIP_Inst.length;
		
	var IPRuleObj = {
		count		:	acIPCnt,
		enable		:	main.config_str_multi("ipRule_Enable_"),
		name		:	main.config_str_multi("ipRule_Name_"),
		ipStart		:	main.config_str_multi("ipRule_IPStart_"),
		ipEnd		:	main.config_str_multi("ipRule_IPEnd_"),
		ipMask		:	main.config_str_multi("ipRule_IPMask_"),
		schedule	:	main.config_str_multi("ipRule_ScheduleIndex_")
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
	
	$(document).ready( function () {
		/** Hide all elements first if disable */
		switchAccessControl(acGlobalEnable, true);
		
	});
	
	/** Define of specialService list */
	
	/** spsName, spsDesp content can use get from language table and fill in */
	var spsName = new Array(get_words('_adv_txt_30'),get_words('_email_sending'),get_words('_email_receiving'),get_words('_file_transfer_l'),get_words('_telnet_service'),get_words('_dns_query'),get_words('_tcp_protocol'),get_words('_udp_protocol'));
	var spsDesp = new Array("HTTP, TCP Port80","SMTP, TCP Port 25","POP3, TCP Port 110","FTP, TCP Port 21","TCP Port 23","UDP Port 53","All TCP Port","All UDP Port");
	var spsPort = [{"TCP":"80,8080","UDP":""},{"TCP":"25","UDP":""},{"TCP":"110","UDP":""},{"TCP":"21","UDP":""},{"TCP":"23","UDP":""},{"TCP":"","UDP":"53"},{"TCP":"1-65535","UDP":""},{"TCP":"","UDP":"1-65535"}];
	var spsChkId = new Array("WWWEnable","SMTPEnable","POP3Enable","FTPEnable","TELNETEnable","DNSEnable","TCPEnable","UDPEnable");
	
	function showSpecialService()
	{
		var inHtml = "";
		
		for(var idx=0 ; idx<spsName.length ; idx++)
		{
			inHtml += "<tr id=\"specianRule_" + idx + "\">";			
			inHtml += "<td class=\"CELL\">" + spsName[idx] + "</td>";
			inHtml += "<td class=\"CELL\">" + spsDesp[idx] + "</td>";
			inHtml += "<td class=\"CELL\"><input type=\"checkbox\" id=\"" + spsChkId[idx] + "\" /></td>";
			inHtml += "</tr>";
		}
		document.write(inHtml);
	}
	
	function edit_showSpecialService()
	{
		var inHtml = "";
		
		for(var idx=0 ; idx<spsName.length ; idx++)
		{
			inHtml += "<tr id=\"edit_specianRule_" + idx + "\">";			
			inHtml += "<td class=\"CELL\">" + spsName[idx] + "</td>";
			inHtml += "<td class=\"CELL\">" + spsDesp[idx] + "</td>";
			inHtml += "<td class=\"CELL\"><input type=\"checkbox\" id=\"edit_" + spsChkId[idx] + "\" /></td>";
			inHtml += "</tr>";
		}
		document.write(inHtml);
	}
	
	
	
	/** Radio switch functions */
	
	/** This array can't contain "Edit" elements */
	var acElements = new Array("addNewPolicyMain","policyList"
		,"addNewIPBlock","ipList"
		,"URLBlock_Action"
		,"addNewURLBlock_Allow","URLList_Allow"
		,"addNewURLBlock","URLList");
	
	function switchAccessControl(val, isPageLoad)
	{
		if(!isPageLoad)
			$("#status_menu").show();
		get_by_name("AccessControlEnable")[1-val].checked = true;
		for(var idx=0 ; idx<acElements.length ; idx++)
		{
			if(val==1)
				$("#" + acElements[idx]).show(); 
			else
				$("#" + acElements[idx]).hide();
		}
		//load IGD_URLFilter_Action
		$('#url_domain_filter_type').val(url_action);
		url_domain_action();
	}
	
	function switchPortRule(val)
	{
		/** 1=User Define, 0=Special Service */
		if(val==1)
		{
			/** display TCP/UDP setting */
			$("#userDefineTCP").show();
			$("#userDefineUDP").show();
			
			/** Hide others */
			for(var idx=0 ; idx<spsName.length ; idx++)
				$("#specianRule_"+idx+"").hide();
			
			$("#specialServiceTitle").hide();
		}
		else
		{
			$("#userDefineTCP").hide();
			$("#userDefineUDP").hide();
			for(var idx=0 ; idx<spsName.length ; idx++)			
				$("#specianRule_"+idx+"").show();
			
			$("#specialServiceTitle").show();
		}
	}
	
	function edit_switchPortRule(val)
	{
		/** 1=User Define, 0=Special Service */
		if(val==1)
		{
			/** display TCP/UDP setting */
			$("#edit_userDefineTCP").show();
			$("#edit_userDefineUDP").show();
			
			/** Hide others */
			for(var idx=0 ; idx<spsName.length ; idx++)
				$("#edit_specianRule_"+idx+"").hide();
			
			$("#edit_specialServiceTitle").hide();
		}
		else
		{
			$("#edit_userDefineTCP").hide();
			$("#edit_userDefineUDP").hide();
			for(var idx=0 ; idx<spsName.length ; idx++)			
				$("#edit_specianRule_"+idx+"").show();
			
			$("#edit_specialServiceTitle").show();
		}
	}
	
	/** Edit functions */
	function url_editRow_Allow(rowIdx)
	{
		$("#editURLIdx_Allow").val(rowIdx-1);
		/** Fill edit data */
		if(URLAllowFilterObj.enable[rowIdx-1]=="1")
			$("#edit_URLEnable_Allow")[0].checked = true;
		else
			$("#edit_URLEnable_Allow")[0].checked = false;
		
		$("#edit_URLName_Allow").val(URLAllowFilterObj.name[rowIdx-1]);
		$("#edit_URLRule_Allow").val(URLAllowFilterObj.url[rowIdx-1]);
		
		$("#edit_URLscheduleField_Allow").html(makeScheduleHTML("sch_URLEdit_Allow",URLAllowFilterObj.schedule[rowIdx-1]));
		
		/** TODO:Schedule Selection */
		
		$("#editURLBlock_Allow").show();
		$("#addNewURLBlock_Allow").hide();

		set_checked(URLAllowFilterObj.schedule[rowIdx-1] == 255 ? 0 : 1, $('#sch_enable_8')[0]);
		setEnable("sch_enable_8");
	}
	function url_editRow(rowIdx)
	{
		$("#editURLIdx").val(rowIdx-1);
		/** Fill edit data */
		if(URLDenyFilterObj.enable[rowIdx-1]=="1")
			$("#edit_URLEnable")[0].checked = true;
		else
			$("#edit_URLEnable")[0].checked = false;
		
		$("#edit_URLName").val(URLDenyFilterObj.name[rowIdx-1]);
		$("#edit_URLRule").val(URLDenyFilterObj.url[rowIdx-1]);
		
		$("#edit_URLscheduleField").html(makeScheduleHTML("sch_URLEdit",URLDenyFilterObj.schedule[rowIdx-1]));
		
		/** TODO:Schedule Selection */
		
		$("#editURLBlock").show();
		$("#addNewURLBlock").hide();

		set_checked(URLDenyFilterObj.schedule[rowIdx-1] == 255 ? 0 : 1, $('#sch_enable_6')[0]);
		setEnable("sch_enable_6");
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
		
		/** TODO : Set schedule */
		URLAllowFilterObj.schedule[row] = $("#sch_URLEdit_Allow").val();
		URLDenyFilterObj.schedule[row] = $("#sch_URLEdit_Allow").val();
		
		url_submitEidt_Allow(row);
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
		
		/** TODO : Set schedule */
		URLAllowFilterObj.schedule[row] = $("#sch_URLEdit").val();
		URLDenyFilterObj.schedule[row] = $("#sch_URLEdit").val();
		
		url_submitEidt(row);
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
		
		url_submitEidt_Allow(rowIdx-1);
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
		
		url_submitEidt(rowIdx-1);
	}
	
	function url_submitEidt_Allow(row)
	{
		var inst = '1.1.'+(parseInt(row,10) + 1)+'.0';
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
		addGlobalEnableParam(obj);
		var param = obj.get_param();

		totalWaitTime = 20; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(param.url, param.arg);
	}
	
	function url_submitEidt(row)
	{
		var inst = '1.1.'+(parseInt(row,10) + 1)+'.0';
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
		addGlobalEnableParam(obj);
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
	
	/** Edit - Port */
	function cleanPortServices(edit)
	{
		$("#" + edit +"WWWEnable")[0].checked = false;
		$("#" + edit +"SMTPEnable")[0].checked = false;	
		$("#" + edit +"POP3Enable")[0].checked = false;	
		$("#" + edit +"FTPEnable")[0].checked = false;	
		$("#" + edit +"TELNETEnable")[0].checked = false;	
		$("#" + edit +"DNSEnable")[0].checked = false;	
		$("#" + edit +"TCPEnable")[0].checked = false;	
		$("#" + edit +"UDPEnable")[0].checked = false;
	}
	
	function cleanUserDef(edit)
	{
		$("#" + edit + "edit_portRuleTCP").val("");
		$("#" + edit + "edit_portRuleUDP").val("");
	}
	
	function port_editRow(rowIdx)
	{
		$("#editPortIdx").val(rowIdx-1);
		
		/** Fill edit data */		
		$("#edit_scheduleField").html(makeScheduleHTML("sch_PortEdit",PortRangeObj.schedule[rowIdx-1]));
		
		if(PortRangeObj.enable[rowIdx-1]=="1")
			$("#edit_ruleEnable")[0].checked = true;
		else
			$("#edit_ruleEnable")[0].checked = false;
		
		$("#edit_ruleName").val(PortRangeObj.name[rowIdx-1]);
		$("#edit_ipaddr_start").val(PortRangeObj.ipStart[rowIdx-1]);
		$("#edit_ipaddr_end").val(PortRangeObj.ipEnd[rowIdx-1]);
		
		var isUserDefine = PortRangeObj.isUser[rowIdx-1];
		get_by_name("edit_ruleDefine")[isUserDefine].checked = true;
	
		edit_switchPortRule(isUserDefine);

		// clear checkbox
		for(var i=0 ; i<spsChkId.length ; i++)
		{
			$("#edit_"+spsChkId[i]).attr('checked','');
		}
		
		if(isUserDefine=="0")
		{
			/** Fill in service enable */
			//edit_
			if(PortRangeObj.WWWEnable[rowIdx-1]=="1")
				$("#edit_WWWEnable")[0].checked = true;
			if(PortRangeObj.SMTPEnable[rowIdx-1]=="1")
				$("#edit_SMTPEnable")[0].checked = true;
			if(PortRangeObj.POP3Enable[rowIdx-1]=="1")
				$("#edit_POP3Enable")[0].checked = true;
			if(PortRangeObj.FTPEnable[rowIdx-1]=="1")
				$("#edit_FTPEnable")[0].checked = true;
			if(PortRangeObj.TELNETEnable[rowIdx-1]=="1")
				$("#edit_TELNETEnable")[0].checked = true;
			if(PortRangeObj.DNSEnable[rowIdx-1]=="1")
				$("#edit_DNSEnable")[0].checked = true;
			if(PortRangeObj.TCPEnable[rowIdx-1]=="1")
				$("#edit_TCPEnable")[0].checked = true;
			if(PortRangeObj.UDPEnable[rowIdx-1]=="1")
				$("#edit_UDPEnable")[0].checked = true;
		}
		else
		{
			/** Fill in TCP/UDP data */
			$("#edit_portRuleTCP").val(PortRangeObj.tcpPorts[rowIdx-1]);
			$("#edit_portRuleUDP").val(PortRangeObj.udpPorts[rowIdx-1]);
		}
		
		
		/** TODO:Schedule Selection */
		$('#sch_PortEdit').val(PortRangeObj.schedule[rowIdx-1]);
		
		$("#editPolicyMain").show();
		$("#addNewPolicyMain").hide();

		set_checked(PortRangeObj.schedule[rowIdx-1] == 255 ? 0 : 1, $('#sch_enable_2')[0]);
		setEnable("sch_enable_2");
	}
	
	function port_setEdit()
	{
		var ip_addr_msgs = replace_msg(all_ip_addr_msg,get_words('IPv6_addrSr'));
		var ip_addr_msge = replace_msg(all_ip_addr_msg,get_words('IPv6_addrEr'));
		var temp_start_ip = new addr_obj($("#edit_ipaddr_start").val().split("."), ip_addr_msgs, false, false);
		var temp_end_ip = new addr_obj($("#edit_ipaddr_end").val().split("."), ip_addr_msge, false, false);
		if (!check_ip_order(temp_start_ip, temp_end_ip)){
			alert(get_words('TEXT039'));
			return;
		}
		
		var row = $("#editPortIdx").val();
		PortRangeObj.name[row] = $("#edit_ruleName").val();
		PortRangeObj.ipStart[row] = $("#edit_ipaddr_start").val();
		PortRangeObj.ipEnd[row] = $("#edit_ipaddr_end").val();
		
		if($("#edit_ruleEnable")[0].checked == true)
			PortRangeObj.enable[row] = "1";
		else
			PortRangeObj.enable[row] = "0";
		
		var userDef = (get_by_name("edit_ruleDefine")[1].checked?"1":"0")
		
		PortRangeObj.isUser[row] = userDef;
	
		if(userDef == "1")
			cleanPortServices("edit_");
		else
			cleanUserDef("edit_");
			
		PortRangeObj.WWWEnable[row]= ($("#edit_WWWEnable")[0].checked?"1":"0");
		PortRangeObj.SMTPEnable[row]= ($("#edit_SMTPEnable")[0].checked?"1":"0");
		PortRangeObj.POP3Enable[row]= ($("#edit_POP3Enable")[0].checked?"1":"0");
		PortRangeObj.FTPEnable[row]= ($("#edit_FTPEnable")[0].checked?"1":"0");
		PortRangeObj.TELNETEnable[row]= ($("#edit_TELNETEnable")[0].checked?"1":"0");
		PortRangeObj.DNSEnable[row]= ($("#edit_DNSEnable")[0].checked?"1":"0");
		PortRangeObj.TCPEnable[row]= ($("#edit_TCPEnable")[0].checked?"1":"0");
		PortRangeObj.UDPEnable[row]= ($("#edit_UDPEnable")[0].checked?"1":"0");
		
		if(userDef == "0")
		{
			var tcp = [];
			var udp = [];
			
			for(var i=0 ; i<spsChkId.length-2 ; i++)
			{
				if($("#edit_"+spsChkId[i]).attr('checked'))
				{
					if(spsPort[i].TCP!='')
						tcp.push(spsPort[i].TCP);
					if(spsPort[i].UDP!='')
						udp.push(spsPort[i].UDP);
				}
			}
			/*last 2 are special case,so handle it independently.*/
			if($("#edit_"+spsChkId[6]).attr('checked'))
				tcp = [spsPort[6].TCP];
			if($("#edit_"+spsChkId[7]).attr('checked'))
				udp = [spsPort[7].UDP];
			PortRangeObj.tcpPorts[row] = (tcp.length==0?'':tcp.join(","));
			PortRangeObj.udpPorts[row] = (udp.length==0?'':udp.join(","));
		}
		else
		{
			PortRangeObj.tcpPorts[row] = $("#edit_portRuleTCP").val();		
			PortRangeObj.udpPorts[row] = $("#edit_portRuleUDP").val();		
		}
		
		/** TODO : Set schedule */
		PortRangeObj.schedule[row] = $("#sch_PortEdit").val();

		port_submitEidt(row);
	}
	
	function port_deleteRow(rowIdx)
	{
		PortRangeObj.enable[rowIdx-1]= "0";
		PortRangeObj.name[rowIdx-1]= "";
		PortRangeObj.schedule[rowIdx-1]= "255";
		PortRangeObj.ipStart[rowIdx-1]= "0.0.0.0";
		PortRangeObj.ipEnd[rowIdx-1]= "0.0.0.0";
		PortRangeObj.isUser[rowIdx-1]= "0";
		PortRangeObj.tcpPorts[rowIdx-1]= "";
		PortRangeObj.udpPorts[rowIdx-1]= "";
	
		PortRangeObj.WWWEnable[rowIdx-1]= "0";
		PortRangeObj.SMTPEnable[rowIdx-1]= "0";
		PortRangeObj.POP3Enable[rowIdx-1]= "0";
		PortRangeObj.FTPEnable[rowIdx-1]= "0";
		PortRangeObj.TELNETEnable[rowIdx-1]= "0";
		PortRangeObj.DNSEnable[rowIdx-1]= "0";
		PortRangeObj.TCPEnable[rowIdx-1]= "0";
		PortRangeObj.UDPEnable[rowIdx-1]= "0";
		PortRangeObj.schedule[rowIdx-1]= "255";
		
		port_submitEidt(rowIdx-1);
	}
	
	function port_submitEidt(row)
	{
		var inst = '1.1.'+(parseInt(row,10) + 1)+'.0';
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('adv_access_control.asp');
		
		obj.add_param_arg('portRange_Enable_',inst,PortRangeObj.enable[row]);
		obj.add_param_arg('portRange_Name_',inst,PortRangeObj.name[row]);
		obj.add_param_arg('portRange_IPStart_',inst,PortRangeObj.ipStart[row]);
		obj.add_param_arg('portRange_IPEnd_',inst,PortRangeObj.ipEnd[row]);
		obj.add_param_arg('portRange_UserDefine_',inst,PortRangeObj.isUser[row]);
		obj.add_param_arg('portRange_TCPPorts_',inst,PortRangeObj.tcpPorts[row]);
		obj.add_param_arg('portRange_UDPPorts_',inst,PortRangeObj.udpPorts[row]);
		obj.add_param_arg('portRange_WWWEnable_',inst,PortRangeObj.WWWEnable[row]);
		obj.add_param_arg('portRange_SMTPEnable_',inst,PortRangeObj.SMTPEnable[row]);
		obj.add_param_arg('portRange_POP3Enable_',inst,PortRangeObj.POP3Enable[row]);
		obj.add_param_arg('portRange_FTPEnable_',inst,PortRangeObj.FTPEnable[row]);
		obj.add_param_arg('portRange_TELNETEnable_',inst,PortRangeObj.TELNETEnable[row]);
		obj.add_param_arg('portRange_DNSEnable_',inst,PortRangeObj.DNSEnable[row]);
		obj.add_param_arg('portRange_TCPEnable_',inst,PortRangeObj.TCPEnable[row]);
		obj.add_param_arg('portRange_UDPEnable_',inst,PortRangeObj.UDPEnable[row]);
		obj.add_param_arg('portRange_ScheduleIndex_',inst,PortRangeObj.schedule[row]);
		addGlobalEnableParam(obj);
		var param = obj.get_param();

		totalWaitTime = 20; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(param.url, param.arg);
	}
	
	function port_cancelEdit()
	{
		cleanPortServices("edit_");
		cleanUserDef("edit_");
		
		$("#editPolicyMain").hide();
		$("#addNewPolicyMain").show();
	}
	
	/** IP Rules */
	function ip_editRow(rowIdx)
	{
		$("#editIPIdx").val(rowIdx-1);
		
		/** Fill edit data */		
		$("#edit_IPscheduleField").html(makeScheduleHTML("sch_IPEdit",IPRuleObj.schedule[rowIdx-1]));
		
		if(IPRuleObj.enable[rowIdx-1]=="1")
			$("#edit_IPEnable")[0].checked = true;
		else
			$("#edit_IPEnable")[0].checked = false;
		
		$("#edit_IPName").val(IPRuleObj.name[rowIdx-1]);
		
		var inHtml = IPRuleObj.ipStart[rowIdx-1];
		if(IPRuleObj.ipEnd[rowIdx-1] != null && IPRuleObj.ipEnd[rowIdx-1] != IPRuleObj.ipStart[rowIdx-1])
			inHtml += '-' + IPRuleObj.ipEnd[rowIdx-1] + '';
		else if(IPRuleObj.ipMask[rowIdx-1] != "0.0.0.0")
			inHtml += '/' + ipMast_to_Num(IPRuleObj.ipMask[rowIdx-1])
		
		$("#edit_ipRule").val(inHtml);
		
		$('#ipSchedule').val(IPRuleObj.schedule[rowIdx-1]);
		
		$("#edit_IPBlock").show();
		$("#addNewIPBlock").hide();

		set_checked(IPRuleObj.schedule[rowIdx-1] == 255 ? 0 : 1, $('#sch_enable_4')[0]);
		setEnable("sch_enable_4");
	}
	
	function ip_setEdit()
	{
		var row = $("#editIPIdx").val();
			
		IPRuleObj.name[row] = $("#edit_IPName").val();
		
		if($("#edit_IPEnable")[0].checked == true)
			IPRuleObj.enable[row] = "1";
		else
			IPRuleObj.enable[row] = "0";
		
		var ipRule = $("#edit_ipRule").val();
		var ipStart = ipRule;
		var ipEnd = ipRule;
		var ipMask = "0.0.0.0";
		var maskBit = 0;
		
		/** Check for mask input */
		if(ipRule.indexOf("/")!= -1 && ipRule.indexOf("-")!= -1)
		{
			alert("IP Address format error");
			return;
		}
		
		if(ipRule.indexOf("/")!= -1)
		{
			var ipArr = ipRule.split("/");
			ipStart = ipArr[0];
			ipEnd = ipStart;
			maskBit = parseInt(ipArr[1],10);
			
			if(ipArr[1].length==0 || maskBit>32 || maskBit==0)
			{
				alert("IP Mask Error");
				return;
			}
			
			ipMask = calculate_ipMask(maskBit);
			
		}
		else if(ipRule.indexOf("-")!= -1)
		{
			var ipArr = ipRule.split("-");			
			ipStart = ipArr[0];
			ipEnd = ipArr[1];
			if(ipStart.length == 0 || ipEnd.length ==0)
			{
				alert("Input format error");
				return;
			}
			var start_ip_addr_msg = replace_msg(all_ip_addr_msg,get_words('TEXT035'));
			var end_ip_addr_msg = replace_msg(all_ip_addr_msg,get_words('end_ip'));
			var start_obj = new addr_obj(ipStart.split("."), start_ip_addr_msg, false, false);
			var end_obj = new addr_obj(ipEnd.split("."), end_ip_addr_msg, false, false);
			if (!check_ip_order(start_obj, end_obj)){
				alert(get_words('IP_RANGE_ERROR', msg));
				return;
			}
		}
		
		IPRuleObj.ipStart[row] = ipStart;
		IPRuleObj.ipEnd[row] = ipEnd;
		IPRuleObj.ipMask[row] = ipMask;
		IPRuleObj.schedule[row] = $("#sch_IPEdit").val();
		
		/** TODO : Set schedule */
		
		ip_submitEidt(row);
	}
	
	function ip_deleteRow(rowIdx)
	{
		IPRuleObj.enable[rowIdx-1]= "0";
		IPRuleObj.name[rowIdx-1]= "";
		IPRuleObj.schedule[rowIdx-1]= "";
		IPRuleObj.ipStart[rowIdx-1]= "0.0.0.0";
		IPRuleObj.ipEnd[rowIdx-1]= "0.0.0.0";
		IPRuleObj.ipMask[rowIdx-1]= "0";
		IPRuleObj.schedule[rowIdx-1]= "255";
		
		ip_submitEidt(rowIdx-1);
	}
	
	function ip_submitEidt(row)
	{
		var inst = '1.1.'+(parseInt(row,10) + 1)+'.0';
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('adv_access_control.asp');
		
		obj.add_param_arg('ipRule_Enable_',inst,IPRuleObj.enable[row]);
		obj.add_param_arg('ipRule_Name_',inst,IPRuleObj.name[row]);
		obj.add_param_arg('ipRule_IPStart_',inst,IPRuleObj.ipStart[row]);
		obj.add_param_arg('ipRule_IPEnd_',inst,IPRuleObj.ipEnd[row]);
		obj.add_param_arg('ipRule_IPMask_',inst,IPRuleObj.ipMask[row]);
		obj.add_param_arg('ipRule_ScheduleIndex_',inst,IPRuleObj.schedule[row]);
		addGlobalEnableParam(obj);
		var param = obj.get_param();

		totalWaitTime = 20; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(param.url, param.arg);
	}
	
	function ip_cancelEdit()
	{
		$("#edit_IPBlock").hide();
		$("#addNewIPBlock").show();
	}
	
	/** IP Rules End */
	
	/** Edit functions End */
	
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
	
	function showPortRangList()
	{//PortRangeObj
		var inHtml = '';

		for(var idx=0 ; idx < PortRangeObj.count ; idx++)
		{
			if(PortRangeObj.name[idx].length==0)
				continue;
			var checked = "checked";
			if(PortRangeObj.enable[idx] == "0")
				checked = "";
			var tmp_port_sche = (PortRangeObj.schedule[idx]=='255'?get_words('_always'):(PortRangeObj.schedule[idx]=='254'?get_words('_never'):sch_obj.name[PortRangeObj.schedule[idx]-1]));
			inHtml += '' +
				'<tr align="center">'+
					'<td align="center" class="CELL">'+
						'<input disabled="disabled" type="checkbox" id="portEnable_' + idx + '" ' + checked +'/></td>' +
					'<td align="center" class="CELL" id="portName_' + idx +'">' + PortRangeObj.name[idx] + '</td>'+
					'<td align="center" class="CELL" id="portSchedule">' + tmp_port_sche + '</td>'+
					'<td align="center" class="CELL" id="portIPRange">' + PortRangeObj.ipStart[idx] + '-' + PortRangeObj.ipEnd[idx] + '</td>'+
					'<td align="center" class="CELL" id="portEdit">'+
						'<a href="javascript:port_editRow('+ (idx+1) +')"><img src="edit.gif" border="0" title="'+get_words('_edit')+'" /></a></td>' +
					'<td align="center" class="CELL" id="portDelete">'+
						'<a href="javascript:port_deleteRow('+ (idx+1) +')"><img src="delete.gif" border="0" title="'+get_words('_delete')+'" /></a></td>' +
						'</td>'
				'</tr>';
		}
		document.write(inHtml);
	}
	
	function showIPRuleList()
	{
		var inHtml = '';

		for(var idx=0 ; idx < PortRangeObj.count ; idx++)
		{
			if(IPRuleObj.name[idx].length==0)
				continue;
			var checked = "checked";
			if(IPRuleObj.enable[idx] == "0")
				checked = "";
			
			inHtml += '' +
				'<tr align="center">'+
					'<td align="center" class="CELL">'+
						'<input disabled="disabled" type="checkbox" id="ipEnable_' + idx + '" ' + checked +'/></td>' +
					'<td align="center" class="CELL" id="ipName_' + idx +'">' + IPRuleObj.name[idx] + '</td>'+					
					'<td align="center" class="CELL" id="ipIPRange">' + IPRuleObj.ipStart[idx];
					
					
					if(IPRuleObj.ipEnd[idx] != null && IPRuleObj.ipEnd[idx] != IPRuleObj.ipStart[idx])
						inHtml += '-' + IPRuleObj.ipEnd[idx] + '';
					else if(IPRuleObj.ipMask[idx] != "0.0.0.0")
						inHtml += '/' + ipMast_to_Num(IPRuleObj.ipMask[idx])
			
			var tmp_ip_sche = (IPRuleObj.schedule[idx]=='255'?get_words('_always'):(IPRuleObj.schedule[idx]=='254'?get_words('_never'):sch_obj.name[IPRuleObj.schedule[idx]-1]));
			
			inHtml +='</td><td align="center" class="CELL" id="ipSchedule">' + tmp_ip_sche + '</td>'+
						'<td align="center" class="CELL" id="ipEdit">'+
						'<a href="javascript:ip_editRow('+ (idx+1) +')"><img src="edit.gif" border="0" title="'+get_words('_edit')+'" /></a></td>' +
					'<td align="center" class="CELL" id="portDelete">'+
						'<a href="javascript:ip_deleteRow('+ (idx+1) +')"><img src="delete.gif" border="0" title="'+get_words('_delete')+'" /></a></td>' +
						'</td>'
				'</tr>';
		}
		document.write(inHtml);
	}
	
	/** Display table functions End*/	
	
	/** Submit functions */
	function addPortRuls()
	{
		var ip_addr_msgs = replace_msg(all_ip_addr_msg,get_words('IPv6_addrSr'));
		var ip_addr_msge = replace_msg(all_ip_addr_msg,get_words('IPv6_addrEr'));
		var start_ip = $("#ipaddr_start").val();
		var end_ip = $("#ipaddr_end").val();
		var temp_start_ip = new addr_obj(start_ip.split("."), ip_addr_msgs, false, false);
		var temp_end_ip = new addr_obj(end_ip.split("."), ip_addr_msge, false, false);
		
		for(var idx=0 ; idx<PortRangeObj.count ; idx++)
		{
			//find empty one
			if(PortRangeObj.name[idx].length==0)
				break;
		}
		if(idx == PortRangeObj.count)
		{
			alert(get_words('_rule_full'));
			//can't find empty one
			return;
		}

		if($("#ruleName").val()=='')
		{
			alert(addstr(get_words('up_ai_se_2'), get_words('_adv_txt_01')));
			return false;
		}
		
		if(is_ipv4_valid($("#ipaddr_start").val())==false || is_ipv4_valid($("#ipaddr_end").val())==false)
		{
			alert(get_words('LS46'));
			return;
		}
		
		/* check start ip must less than end ip*/
		if (!check_ip_order(temp_start_ip, temp_end_ip)){
			alert(get_words('TEXT039'));
			return ;
		}
		
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('adv_access_control.asp');
		
		var inst = '1.1.'+(idx+1)+'.0';
		obj.add_param_arg('portRange_Enable_',inst,($("#ruleEnable")[0].checked? "1":"0"));
		obj.add_param_arg('portRange_Name_',inst,$("#ruleName").val());
		obj.add_param_arg('portRange_IPStart_',inst,$("#ipaddr_start").val());
		obj.add_param_arg('portRange_IPEnd_',inst,$("#ipaddr_end").val());
		obj.add_param_arg('portRange_UserDefine_',inst,(get_by_name("ruleDefine")[1].checked?"1":"0"));
		obj.add_param_arg('portRange_ScheduleIndex_',inst,$("#sch_Port").val());
		addGlobalEnableParam(obj);
		
		if(get_by_name("ruleDefine")[0].checked)
		{
			var tcp = [];
			var udp = [];
			
			for(var i=0 ; i<spsChkId.length-2 ; i++)
			{
				if($("#"+spsChkId[i]).attr('checked'))
				{
					if(spsPort[i].TCP!='')
						tcp.push(spsPort[i].TCP);
					if(spsPort[i].UDP!='')
						udp.push(spsPort[i].UDP);
				}
			}
			/*last 2 are special case,so handle it independently.*/
			if($("#"+spsChkId[6]).attr('checked'))
				tcp = [spsPort[6].TCP];
			if($("#"+spsChkId[7]).attr('checked'))
				udp = [spsPort[7].UDP];
			obj.add_param_arg('portRange_TCPPorts_',inst,(tcp.length==0?'':tcp.join(",")));
			obj.add_param_arg('portRange_UDPPorts_',inst,(udp.length==0?'':udp.join(",")));
		}
		else
		{
			obj.add_param_arg('portRange_TCPPorts_',inst,$("#portRuleTCP").val());
			obj.add_param_arg('portRange_UDPPorts_',inst,$("#portRuleUDP").val());
		}
		
		for(var i=0 ; i<spsChkId.length ; i++)
		{
			if(get_by_name("ruleDefine")[0].checked)
				obj.add_param_arg('portRange_'+spsChkId[i],inst,($("#"+spsChkId[i])[0].checked?"1":"0"));
			else
				obj.add_param_arg('portRange_'+spsChkId[i],inst,'');
		}
		var param = obj.get_param();

		totalWaitTime = 20; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(param.url, param.arg);
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
	
	function addURL_Allow()
	{
		if(!check_URL_filter($("#URLName_Allow").val(), $("#URLRule_Allow").val()))
			return;
		apply_action();
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
		obj.add_param_arg('urlFilterAllowList_Enable_',inst,($("#URLEnable_Allow")[0].checked? "1":"0"));
		obj.add_param_arg('urlFilterAllowList_Name_',inst,$("#URLName_Allow").val());
		obj.add_param_arg('urlFilterAllowList_ManagedURL_',inst,$("#URLRule_Allow").val());
		obj.add_param_arg('urlFilterAllowList_ScheduleIndex_',inst,$("#sch_URL_Allow").val());
		obj.add_param_arg('urlFilterDenyList_Enable_',inst,($("#URLEnable_Allow")[0].checked? "1":"0"));
		obj.add_param_arg('urlFilterDenyList_Name_',inst,$("#URLName_Allow").val());
		obj.add_param_arg('urlFilterDenyList_ManagedURL_',inst,$("#URLRule_Allow").val());
		obj.add_param_arg('urlFilterDenyList_ScheduleIndex_',inst,$("#sch_URL_Allow").val());
		addGlobalEnableParam(obj);
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
		apply_action();
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
		obj.add_param_arg('urlFilterAllowList_Enable_',inst,($("#URLEnable")[0].checked? "1":"0"));
		obj.add_param_arg('urlFilterAllowList_Name_',inst,$("#URLName").val());
		obj.add_param_arg('urlFilterAllowList_ManagedURL_',inst,$("#URLRule").val());
		obj.add_param_arg('urlFilterAllowList_ScheduleIndex_',inst,$("#sch_URL").val());
		obj.add_param_arg('urlFilterDenyList_Enable_',inst,($("#URLEnable")[0].checked? "1":"0"));
		obj.add_param_arg('urlFilterDenyList_Name_',inst,$("#URLName").val());
		obj.add_param_arg('urlFilterDenyList_ManagedURL_',inst,$("#URLRule").val());
		obj.add_param_arg('urlFilterDenyList_ScheduleIndex_',inst,$("#sch_URL").val());
		addGlobalEnableParam(obj);
		var param = obj.get_param();

		totalWaitTime = 20; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(param.url, param.arg);
	}
	
	function add_IPRule()
	{
		var ipRule = $("#ipRule").val();
		
		if(ipRule==null || ipRule.length==0){
			alert("IP Address can't be empty");
			return;
		}
	
		if($("#IPName").val().length == 0)
		{
			alert("Rule Name can't be empty");
			return;
		}
		
		var ipStart = ipRule;
		var ipEnd = ipRule;
		var ipMask = "0.0.0.0";
		var maskBit=0;
		
		
		/** Check for mask input */
		if(ipRule.indexOf("/")!= -1 && ipRule.indexOf("-")!= -1)
		{
			alert(get_words('_err_ip_addr_format'));
			return;
		}
		
		if(ipRule.indexOf("/")!= -1)
		{
			var ipArr = ipRule.split("/");
			ipStart = ipArr[0];
			ipEnd = ipStart;
			maskBit = parseInt(ipArr[1],10);
			
			if(ipArr[1].length==0 || maskBit>32 || maskBit==0)
			{
				alert(get_words('_err_ip_mask'));
				return;
			}
			
			ipMask = calculate_ipMask(maskBit);
			
		}
		else if(ipRule.indexOf("-")!= -1)
		{
			var ipArr = ipRule.split("-");			
			ipStart = ipArr[0];
			ipEnd = ipArr[1];
			if(ipStart.length == 0 || ipEnd.length ==0)
			{
				alert(get_words('_err_input_format'));
				return;
			}
			var start_ip_addr_msg = replace_msg(all_ip_addr_msg,get_words('TEXT035'));
			var end_ip_addr_msg = replace_msg(all_ip_addr_msg,get_words('end_ip'));
			var start_obj = new addr_obj(ipStart.split("."), start_ip_addr_msg, false, false);
			var end_obj = new addr_obj(ipEnd.split("."), end_ip_addr_msg, false, false);
			if (!check_ip_order(start_obj, end_obj)){
				alert(get_words('IP_RANGE_ERROR', msg));
				return;
			}
		}
		
		if(is_ipv4_valid(ipStart)==false || is_ipv4_valid(ipEnd)==false)
		{
			alert(get_words('_err_ip_addr'));
			return;
		}
		
		for(var idx=0 ; idx<IPRuleObj.count ; idx++)
		{
			//find empty one
			if(IPRuleObj.name[idx].length==0)
				break;
		}
		if(idx==IPRuleObj.count)
		{
			alert(get_words('_rule_full'));
			return;
		}
		
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('adv_access_control.asp');
		
		var inst = '1.1.'+(idx+1)+'.0';
		obj.add_param_arg('ipRule_Enable_',inst,($("#IPEnable")[0].checked? "1":"0"));
		obj.add_param_arg('ipRule_Name_',inst,$("#IPName").val());
		obj.add_param_arg('ipRule_IPStart_',inst,ipStart);
		obj.add_param_arg('ipRule_IPEnd_',inst,ipEnd);
		obj.add_param_arg('ipRule_IPMask_',inst,ipMask);
		obj.add_param_arg('ipRule_ScheduleIndex_',inst,$("#sch_IP").val());
		addGlobalEnableParam(obj);
		var param = obj.get_param();

		totalWaitTime = 20; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(param.url, param.arg);
	}
	
	function saveStatus()
	{
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('adv_access_control.asp');
		addGlobalEnableParam(obj);
		var param = obj.get_param();

		totalWaitTime = 20; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(param.url, param.arg);
	}
	function addGlobalEnableParam(obj)
	{
		obj.add_param_arg('accessCtrl_GlobalEnable_','1.1.0.0',(get_by_name("AccessControlEnable")[0].checked?"1":"0"));
	}
	
	/** Reset functions */
	
	function reset_PortRule()
	{
		$("#ruleEnable")[0].checked = false;
		$("#ruleName").val("");
		$("#ipaddr_start").val("");
		$("#ipaddr_end").val("");
		get_by_name("ruleDefine")[0].checked = true;
		switchPortRule(0);
		$("#scheduleField").html(makeScheduleHTML("sch_Port"),0);
	}
	
	function reset_IPRule()
	{
		$("#IPEnable")[0].checked = false;
		$("#IPName").val("");
		$("#ipRule").val("");
		$("#IPscheduleField").html(makeScheduleHTML("sch_IP"),0);
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
	
	/** Reset functions End */
	
	function check_modified()
	{
		if ((is_modified==1) && is_form_modified("form1") && confirm (get_words('up_fm_dc_1')))
			window.location.href='adv_access_control.asp';
	}
	function url_domain_action(){
		var sel_action = $('#url_domain_filter_type').val();
		if($('input[name=AccessControlEnable][value=1]').attr('checked'))
		{
			if(sel_action=='0')
			{
				$('#addNewURLBlock_Allow, #URLList_Allow').hide();
				$('#addNewURLBlock, #URLList').show();
				setEnable("sch_enable_5");
			}
			else if(sel_action=='1')
			{
				$('#addNewURLBlock_Allow, #URLList_Allow').show();
				$('#addNewURLBlock, #URLList').hide();
				setEnable("sch_enable_7");
			}
			else
			{
				$('#addNewURLBlock_Allow, #URLList_Allow').hide();
				$('#addNewURLBlock, #URLList').hide();
			}
			$('#editURLBlock_Allow, #editURLBlock').hide();
		}
	}
	function apply_action(){
		var sel_action = $('#url_domain_filter_type').val();
		
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('adv_access_control.asp');
		
		obj.add_param_arg('urlFilter_Action_','1.1.0.0',sel_action);
		addGlobalEnableParam(obj);
		var param = obj.get_param();

		totalWaitTime = 20; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(param.url, param.arg);
	}

$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(dev_info.model);
	setEnable("sch_enable_1");
	setEnable("sch_enable_3");
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
				<script>document.write(menu.build_structure(1,4,0))</script>
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
							<div class="headerbg" id="tabBigTitle">
							<script>show_words('_acccon');</script>
							</div>
							<div class="hr"></div>
							<div class="section_content_border">
							<div class="header_desc" id="acIntroduction">
								<script>show_words('_AC_DESC');</script>
								<p></p>
							</div>

<div id="status_menu" class="box_tn" style="display:none;">
	<table cellpadding="0" cellspacing="0" align="center" class="formarea">
	<tr>
		<td align="center" class="btn_field">
			<!-- Save / Cancel Button here -->
			<input type="button" id="btnSave" value="Save Status" onClick="saveStatus();"  class="button_submit" />
			<input type="button" id="btnCancel" value="Cancel"  class="button_submit" onClick="window.location.reload()"/>
			<script>
				/** Change button value here */
				$("#btnSave").val(get_words('_apply'));
				$("#btnCancel").val(get_words('_cancel'));
			</script>
		</td>
	</tr>
	</table>
</div>

<div id="AC_Main" class="box_tn">
	<div class="CT"><script>show_words('_acccon');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
		<tr>
			<td class="CL">
				<script>show_words('aa_EAC');</script></td>
			<td class="CR">
				<input type="radio" name="AccessControlEnable" value="1" onClick="switchAccessControl(this.value,false);"/>
				<script>show_words('_enable');</script>&nbsp;
				<input type="radio" name="AccessControlEnable" value="0" onClick="switchAccessControl(this.value,false);"/>
				<script>show_words('_disable')</script>
			</td>
		</tr>
	</table>
</div>

<div id="addNewPolicyMain" class="box_tn">
	<div id="addPortRangeAndService" class="CT">
		<script>show_words('_add_port_block_rule');</script>
	</div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL">
			<script>show_words('_adv_txt_00');</script></td>
		<td colspan="2" class="CR">
			<input type="checkbox" id="ruleEnable" />
		</td></tr>
	<tr>
		<td class="CL">
			<script>show_words('_adv_txt_01');</script></td>
		<td colspan="2" class="CR">
			<input type="text" id="ruleName" maxlength="15" value=""/>
		</td></tr>
	<tr>
		<td class="CL">
			<script>show_words('_sched');</script></td>
		<td colspan="2" class="CR">
			<input type="checkbox" id="sch_enable_1" value="1" /> &nbsp;
			<span id="scheduleField"></span>
			<script>
				/** Make schedule select */
				$("#scheduleField").html(makeScheduleHTML("sch_Port"),0);</script>
			<input type="button" id="ln_btn_1" onclick="location.assign('/adv_schedule.asp');" />
			<script>$('#ln_btn_1').val(get_words('tc_new_sch'));</script>
		</td></tr>
	<tr>
		<td class="CL">
			<script>show_words('_client_ip_addr');</script></td>
		<td colspan="2" class="CR">
			<input type="text" id="ipaddr_start" maxlength="20" value=""/>
			~<input type="text" id="ipaddr_end" maxlength="20" value=""/>
		</td></tr>
	<tr>
		<td class="CL">
			<script>show_words('_service');</script></td>
		<td colspan="2" class="CR">
			<input type="radio" name="ruleDefine" value="0" onClick="switchPortRule(this.value);" checked />
			<script>show_words('_adv_txt_29');</script>&nbsp;
			<input type="radio" name="ruleDefine" value="1" onClick="switchPortRule(this.value);" />
			<script>show_words('_aa_bsecure_manually');</script>
		</td></tr>
	<tr id="userDefineTCP" style="display:none" >
		<td class="CL">
			<script>show_words('_tcp_ports');</script></td>
		<td colspan="2" class="CR">
			<input type="text" id="portRuleTCP" value="" />
			<script>show_words('_ex_21_or_3_500');</script>
		</td></tr>
	<tr id="userDefineUDP" style="display:none" >
		<td class="CL">
			<script>show_words('_udp_ports');</script></td>
		<td colspan="2" class="CR">
			<input type="text" id="portRuleUDP" value="" />
			<script>show_words('_ex_21_or_3_500');</script>
		</td></tr>
	<tr id="specialServiceTitle">
		<td class="CTS"><script>show_words('_service');</script>
			<script> /** Add which_lang here */</script></td>
		<td class="CTS"><script>show_words('_description');</script>
			<script> /** Add which_lang here */</script></td>
		<td class="CTS"><script>show_words('_enabled');</script>
			<script> /** Add which_lang here */</script></td>
		</tr>
	<script>showSpecialService();</script>
	<tr align="center">
		<td colspan="3" class="btn_field">
			<!-- Add / Cancel Button here -->
			<input type="button" id="btnAddPortRule" value="Add" onClick="addPortRuls();" class="button_submit"/>
			<input type="button" id="btnCancelPortRule" value="Cancel" onCLick="reset_PortRule();" class="button_submit"/>
			<script>/** change button value here */ 
				$("#btnAddPortRule").val(get_words('_add'));
				$("#btnCancelPortRule").val(get_words('_cancel'));
			</script>
		</td>
	</tr>
	</table>
</div>

<div id="editPolicyMain" class="box_tn" style="display:none;">
	<div id="editPortRangeAndService" class="CT">
		<script>show_words('_edit_port_block_rule');</script>
	</div>
	<input type="hidden" id="editPortIdx" />
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL">
			<script>show_words('_adv_txt_00');</script></td>
		<td colspan="2" class="CR">
			<input type="checkbox" id="edit_ruleEnable" />
		</td></tr>
	<tr>
		<td class="CL">
			<script>show_words('_adv_txt_01');</script></td>
		<td colspan="2" class="CR">
			<input type="text" id="edit_ruleName" maxlength="15" value=""/>
		</td></tr>
	<tr>
		<td class="CL">
			<script>show_words('_sched');</script></td>
		<td colspan="2" class="CR">
			<input type="checkbox" id="sch_enable_2" value="1" /> &nbsp;
			<span id="edit_scheduleField"></span>
			<input type="button" id="ln_btn_2" onclick="location.assign('/adv_schedule.asp');" />
			<script>$('#ln_btn_2').val(get_words('tc_new_sch'));</script>
		</td></tr>
	<tr>
		<td class="CL">
			<script>show_words('_client_ip_addr');</script></td>
		<td colspan="2" class="CR">
			<input type="text" id="edit_ipaddr_start" maxlength="20" value=""/>
			~<input type="text" id="edit_ipaddr_end" maxlength="20" value=""/>
		</td></tr>
	<tr>
		<td class="CL">
			<script>show_words('_service');</script></td>
		<td colspan="2" class="CR">
			<input type="radio" name="edit_ruleDefine" value="0" onClick="edit_switchPortRule(this.value);" checked />
			<script>show_words('_special_service');</script>&nbsp;
			<input type="radio" name="edit_ruleDefine" value="1" onClick="edit_switchPortRule(this.value);" />
			<script>show_words('_user_define');</script>
		</td></tr>
	<tr id="edit_userDefineTCP" style="display:none" >
		<td class="CL">
			<script>show_words('_tcp_ports');</script></td>
		<td colspan="2" class="CR">
			<input type="text" id="edit_portRuleTCP" value="" />
			<script>show_words('_ex_21_or_3_500');</script>
		</td></tr>
	<tr id="edit_userDefineUDP" style="display:none" >
		<td class="CL">
			<script>show_words('_udp_ports');</script></td>
		<td colspan="2" class="CR">
			<input type="text" id="edit_portRuleUDP" value="" />
			<script>show_words('_ex_21_or_3_500');</script>
		</td></tr>
	<tr id="edit_specialServiceTitle">
		<td class="CTS"><script>show_words('_service');</script>
			<script> /** Add which_lang here */</script></td>
		<td class="CTS"><script>show_words('_description');</script>
			<script> /** Add which_lang here */</script></td>
		<td class="CTS"><script>show_words('_enabled');</script>
			<script> /** Add which_lang here */</script></td>
		</tr>
	<script>edit_showSpecialService();</script>
	<tr align="center">
		<td colspan="3" class="btn_field">
			<!-- Add / Cancel Button here -->
			<input type="button" id="btnSavePortRule" value="Save" onClick="port_setEdit();" class="button_submit"/>
			<input type="button" id="btnCancelEditPortRule" onClick="port_cancelEdit();" value="Cancel" class="button_submit"/>
			<script>/** change button value here */ 
				//$("#btnSavePortRule").val(get_words('_add'));
				$("#btnCancelEditPortRule").val(get_words('_cancel'));
			</script>
		</td>
	</tr>
	</table>
</div>

<div id="policyList" class="box_tn">
	<div class="CT"><script>show_words('_port_block_rule');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr align="center">
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('_enable');</script></td>
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('_adv_txt_01');</script></td>
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('_sched');</script></td>
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('IPv6_fw_ipr');</script></td>
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('_edit');</script></td>
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('_delete');</script></td>
	</tr>
	<script>
		showPortRangList();/** display Rules */
	</script>
	</table>
</div>

<div id="addNewIPBlock" class="box_tn">
	<div id="addNewIPB" class="CT">
		<script>show_words('_add_ip_block_rule');</script>
	</div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL">
			<script>show_words('_adv_txt_00');</script></td>
		<td colspan="2" class="CR">
			<input type="checkbox" id="IPEnable" />
		</td></tr>
	<tr>
		<td class="CL">
			<script>show_words('_adv_txt_01');</script></td>
		<td colspan="2" class="CR">
			<input type="text" id="IPName" maxlength="20" size="25" value=""/>
		</td></tr>
	<tr>
		<td class="CL">
			<script>show_words('_ipaddr');</script></td>
		<td colspan="2" class="CR">
			<input type="text" id="ipRule" maxlength="31" size="35" value=""/>
			<br/>
			(ex: 192.168.10.1, 192.168.10.0/24, 192.168.10.1-192.168.10.20)
			<script> /** example text */ </script>
		</td></tr>
	<tr>
		<td class="CL">
			<script>show_words('_sched');</script></td>
		<td colspan="2" class="CR">
			<input type="checkbox" id="sch_enable_3" value="1" /> &nbsp;
			<span id="IPscheduleField"></span>
			<script>
				/** Make schedule select */
				$("#IPscheduleField").html(makeScheduleHTML("sch_IP"),0);</script>
			<input type="button" id="ln_btn_3" onclick="location.assign('/adv_schedule.asp');" />
			<script>$('#ln_btn_3').val(get_words('tc_new_sch'));</script>
		</td></tr>
	<tr align="center">
		<td colspan="3" class="btn_field">
			<!-- Add / Cancel Button here -->
			<input type="button" id="btnAddIPRule" value="Add" onClick="add_IPRule();" class="button_submit"/>
			<input type="button" id="btnCancelIPRule" value="Reset" onClick="reset_IPRule();" class="button_submit"/>
			<script> /** change button value here */ 
				$("#btnAddIPRule").val(get_words('_add'));
				$("#btnCancelIPRule").val(get_words('_reset'));
			</script>
		</td>
	</tr>
	</table>
</div>

<div id="edit_IPBlock" class="box_tn" style="display:none">
	<div id="edit_IPB" class="CT">
		<script>show_words('_edit_ip_block_rule');</script>
		</div>
	<input type="hidden" id="editIPIdx" />
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL">
			<script>show_words('_adv_txt_00');</script></td>
		<td colspan="2" class="CR">
			<input type="checkbox" id="edit_IPEnable" />
		</td></tr>
	<tr>
		<td class="CL">
			<script>show_words('_adv_txt_01');</script></td>
		<td colspan="2" class="CR">
			<input type="text" id="edit_IPName" maxlength="20" size="25" value=""/>
		</td></tr>
	<tr>
		<td class="CL">
			<script>show_words('_ipaddr');</script></td>
		<td colspan="2" class="CR">
			<input type="text" id="edit_ipRule" maxlength="31" size="35" value=""/>
			<br/>
			(ex: 192.168.10.1, 192.168.10.0/24, 192.168.10.1-192.168.10.20)
<!--		(ex: 192.168.0.1, 192.168.0.0/24, 192.168.0.1-192.168.0.20)	-->
			<script> /** example text */ </script>
		</td></tr>
	<tr>
		<td class="CL">
			<script>show_words('_sched');</script></td>
		<td colspan="2" class="CR">
			<input type="checkbox" id="sch_enable_4" value="1" /> &nbsp;
			<span id="edit_IPscheduleField"></span>
			<input type="button" id="ln_btn_4" onclick="location.assign('/adv_schedule.asp');" />
			<script>$('#ln_btn_4').val(get_words('tc_new_sch'));</script>
		</td></tr>
	<tr align="center">
		<td colspan="3" class="btn_field">
			<!-- Add / Cancel Button here -->
			<input type="button" id="btnEditIPRule" value="Edit" onClick="ip_setEdit();" class="button_submit"/>
			<input type="button" id="btnCancelEditIPRule" value="Cancel" onClick="ip_cancelEdit();" class="button_submit"/>
			<script> /** change button value here */ 
				$("#btnEditIPRule").val(get_words('_edit'));
				$("#btnCancelEditIPRule").val(get_words('_cancel'));
			</script>
		</td>
	</tr>
	</table>
</div>

<div id="ipList" class="box_tn">
	<div class="CT"><script>show_words('_ip_block_rule_list');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr align="center">
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('_enable');</script></td>
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('_adv_txt_01');</script></td>
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('_ipaddr');</script></td>
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('_sched');</script></td>
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('_edit');</script></td>
		<td class="CTS" style="word-break:break-all;">
			<script>show_words('_delete');</script></td>
	</tr>
	<script>
		showIPRuleList();/** display Rules */
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
			<script>show_words('_adv_txt_31')</script>
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
			<input type="button" id="btnURLAction" value="Add" onClick="apply_action();" class="button_submit"/>
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
			<script>show_words('_adv_txt_00');</script></td>
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
		</td>
	</tr>
	<tr>
		<td class="CL">
			<script>show_words('_sched');</script></td>
		<td colspan="2" class="CR">
			<input type="checkbox" id="sch_enable_7" value="1" /> &nbsp;
			<span id="URLscheduleField_Allow"></span>
			<input type="button" id="ln_btn_7" onclick="location.assign('/adv_schedule.asp');" />
			<script>$('#ln_btn_7').val(get_words('tc_new_sch'));</script>
			<script>
				/** Make schedule select */
				$("#URLscheduleField_Allow").html(makeScheduleHTML("sch_URL_Allow"),0);</script>
		</td></tr>
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
			<script>show_words('_adv_txt_00');</script></td>
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
		</td>
	</tr>
	<tr>
		<td class="CL">
			<script>show_words('_sched');</script></td>
		<td colspan="2" class="CR">
			<input type="checkbox" id="sch_enable_8" value="1" /> &nbsp;
			<span id="edit_URLscheduleField_Allow"></span>
			<input type="button" id="ln_btn_8" onclick="location.assign('/adv_schedule.asp');" />
			<script>$('#ln_btn_8').val(get_words('tc_new_sch'));</script>
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
			<input type="text" id="URLRule" maxlength="32" size="40" value=""/>&nbsp;
			(<script>show_words('_url_ex');</script>)
		</td>
	</tr>
	<tr>
		<td class="CL">
			<script>show_words('_sched');</script></td>
		<td colspan="2" class="CR">
			<input type="checkbox" id="sch_enable_5" value="1" /> &nbsp;
			<span id="URLscheduleField"></span>
			<input type="button" id="ln_btn_5" onclick="location.assign('/adv_schedule.asp');" />
			<script>$('#ln_btn_5').val(get_words('tc_new_sch'));</script>
			<script>
				/** Make schedule select */
				$("#URLscheduleField").html(makeScheduleHTML("sch_URL"),0);</script>
		</td></tr>
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
			<input type="text" id="edit_URLRule" maxlength="32" size="40" value=""/>&nbsp;
			(<script>show_words('_url_ex');</script>)
		</td>
	</tr>
	<tr>
		<td class="CL">
			<script>show_words('_sched');</script></td>
		<td colspan="2" class="CR">
			<input type="checkbox" id="sch_enable_6" value="1" /> &nbsp;
			<span id="edit_URLscheduleField"></span>
			<input type="button" id="ln_btn_6" onclick="location.assign('/adv_schedule.asp');" />
			<script>$('#ln_btn_6').val(get_words('tc_new_sch'));</script>
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
