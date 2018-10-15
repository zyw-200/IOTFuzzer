<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | Advanced | Application Rules</title>
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
	var selectIDArray = new Array("", "schedule");

	var menu = new menuObject();
	menu.setSupportUSB(dev_info.KCode_USB);

	var hw_version 	= dev_info.hw_ver;
	var version 	= dev_info.fw_ver;
	var model		= dev_info.model;
	var login_Info 	= dev_info.login_info;

	var main = new ccpObject();
	main.set_param_url('get_set.ccp');
	main.set_ccp_act('get');
	main.add_param_arg('IGD_WANDevice_i_PortTriggerRule_i_',1100);
	main.add_param_arg('IGD_ScheduleRule_i_',1000);
	main.add_param_arg('IGD_',1000);
	main.add_param_arg('IGD_WANDevice_i_PortFwd_i_',1100);
	main.add_param_arg('IGD_WANDevice_i_VirServRule_i_',1100);
	main.add_param_arg('IGD_AdministratorSettings_',1100);
	main.add_param_arg('IGD_Storage_',1100);
	if(dev_info.KCode_USB)
		main.add_param_arg('IGD_FTPServer_',1100);
	main.get_config_obj();
	var dev_mode = main.config_val("igd_DeviceMode_");

	var array_enable 		= main.config_str_multi("ptRule_Enable_");
	var array_name 			= main.config_str_multi("ptRule_ApplicationName_");
	var array_tgport 		= main.config_str_multi("ptRule_TriggerPorts_");
	var array_fwport 		= main.config_str_multi("ptRule_FirewallPorts_");
	var array_tgprotocol 	= main.config_str_multi("ptRule_TriggerProtocol_");
	var array_fwprotocol 	= main.config_str_multi("ptRule_FirewallProtocol_");
	var array_Schdule 		= main.config_str_multi("ptRule_ScheduleIndex_");

	var array_vs_enable		= main.config_str_multi("vsRule_Enable_");
	var array_vs_proto	 	= main.config_str_multi("vsRule_Protocol_");
	var array_vs_ports 		= main.config_str_multi("vsRule_PublicPort_");
	var array_pf_enable		= main.config_str_multi("pfRule_Enable_");
	var array_pf_ports_udp	= main.config_str_multi("pfRule_UDPOpenPorts_");
	var array_pf_ports_tcp	= main.config_str_multi("pfRule_TCPOpenPorts_");

	var array_sch_inst 		= main.config_inst_multi("IGD_ScheduleRule_i_");
	var array_schedule_name	= main.config_str_multi("schRule_RuleName_");

	var wa_http_en 			= main.config_val("igdStorage_Http_Remote_Access_Enable_");
	var wa_https_en 		= main.config_val("igdStorage_Https_Remote_Access_Enable_");
	var wa_http_port 		= main.config_val("igdStorage_Http_Remote_Access_Port_");
	var wa_https_port		= main.config_val("igdStorage_Https_Remote_Access_Port_");
	if(dev_info.KCode_USB)
		var kcode_ftp_wan		= main.config_val("igdFTPServer_AccessWanEnable_");

	var schedule_cnt = 0;

	if(array_schedule_name != null)
		schedule_cnt = array_schedule_name.length;

	var submit_button_flag = 0;
	var rule_max_num = 24;
	var DataArray = new Array();
	var TotalCnt=0;
	
	function onPageLoad(){
		var login_who= login_Info;
		if(login_who!= "w" || dev_mode == "1"){
			DisableEnableForm(form1,true);	
		} 

		var table_str = '';
			table_str += "<select id=schedule name=schedule style='width:80'>";
			table_str += '<option value=\"255\">'+get_words('_always')+'</option>';
			table_str += '<option value=\"254\">'+get_words('_never')+'</option>';
			table_str += add_option('Schedule', array_Schdule);
			table_str += "</select>";
			$('#sche_list').html(table_str);

		trigger_st();
	}
	
	function Data(enable, name, tgport, fwport, tgprotocol, fwprotocol, schdule, onList)
	{
		this.Enable = enable;
		this.Name = name;
		this.TGport = tgport;
		this.FWport = fwport;
		this.TGprotocol = tgprotocol ;
		this.FWprotocol = fwprotocol;
		this.Schdule = schdule;
		this.OnList = onList ;
	}

	function set_application()
	{
		var index = 0;
		for (var i = 0; i < rule_max_num; i++) {
			if(array_name[i] != "" && array_name[i])
			{
				TotalCnt++;
				DataArray[DataArray.length++] = new Data(array_enable[i], array_name[i], array_tgport[i], array_fwport[i], array_tgprotocol[i], array_fwprotocol[i], array_Schdule[i], i);
			}
		}
		$('#max_row').val(index-1);
	}
	
	function deleteRedundentDatamodel()
	{
		var delCnt = 0;
		var idx = TotalCnt;
		if(TotalCnt > DataArray.length)
			delCnt = TotalCnt - DataArray.length;

		if(delCnt == 0)
			return;

		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('del');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		
		while(delCnt > 0)
		{
			obj.add_param_arg('IGD_WANDevice_i_PortTriggerRule_i_','1.1.'+idx+'.0');
			delCnt --;
			idx --;
		}

		obj.ajax_submit();
	}

	function del_row(idx)
	{
		if(!confirm(get_words('YM25') + ": " + DataArray[idx].Name + "?"))
			return;

		for(var i=idx; i<(DataArray.length-1); i++)
		{
			DataArray[i].Enable = DataArray[i+1].Enable;
			DataArray[i].Name = DataArray[i+1].Name;
			DataArray[i].TGport = DataArray[i+1].TGport;
			DataArray[i].FWport = DataArray[i+1].FWport;
			DataArray[i].TGprotocol = DataArray[i+1].TGprotocol;
			DataArray[i].FWprotocol = DataArray[i+1].FWprotocol;
			DataArray[i].Schdule = DataArray[i+1].Schdule;
			DataArray[i].OnList = DataArray[i+1].OnList;
		}

		if(DataArray.length > 0)
			DataArray.length -- ;

		paintTable();
		clear_reserved();

		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('del');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');

		obj.add_param_arg('IGD_WANDevice_i_PortTriggerRule_i_','1.1.'+TotalCnt+'.0');

		obj.ajax_submit();
		AddApptoDatamodel();
	}

	function update_DataArray(st)
	{
		var index = parseInt($('#edit').val());
		var insert = false;
		var is_chk = get_checked_value($('#enable')[0]);

		if(index == "-1"){      //save
			if(DataArray.length == rule_max_num){
				alert(get_words('TEXT015'));
			}else{
				index = DataArray.length;
				$('#max_row').val(index);
				insert = true;
			}
		}

		if(insert){
			DataArray[index] = new Data(is_chk, $('#name').val(), $('#trigger_port').val(), $('#public_port').val(), $('#trigger').val(), $('#public').val(), $('#schedule').val(), index+1);			
		}else if (index != -1){			
			DataArray[index].Enable = is_chk;
			DataArray[index].Name = $('#name').val();
			DataArray[index].TGport = $('#trigger_port').val();
			DataArray[index].FWport = $('#public_port').val();
			DataArray[index].TGprotocol = $('#trigger').val();
			DataArray[index].FWprotocol = $('#public').val();
			DataArray[index].Schdule = $('#schedule').val();
			DataArray[index].OnList = index;
		}
	}


	function clear_reserved()
	{
		$("input[name=sel_del]").each(function () { this.checked = false; });
		set_checked(0, $('#enable')[0]);
		$('#name').val("");
		$('#application').val(0);
		$('#trigger_port').val("");
		$('#public_port').val("");
		$('#trigger').val(0);
		$('#public').val(0);
		$('#schedule').val(255);
		$('#edit').val(-1);
		$('#add').attr('disabled', '');
        $('#clear').attr('disabled', true);
	}

	function edit_row(idx)
    {
		set_checked(DataArray[idx].Enable, $('#enable')[0]);
        $('#name').val(DataArray[idx].Name);
        $('#trigger_port').val(DataArray[idx].TGport);
		$('#public_port').val(DataArray[idx].FWport);
		 $('#trigger').val(DataArray[idx].TGprotocol);
		$('#public').val(DataArray[idx].FWprotocol);
		set_checked(DataArray[idx].Schdule == 255 ? 0 : 1, $('#sch_enable_1')[0]);
		$('#schedule').val(DataArray[idx].Schdule);
		$('#edit').val(idx);
		$('#add').val(get_words('_save'));

		$('#clear').attr('disabled', '');
		setEnable("sch_enable_1");
    }

	function AddApptoDatamodel(st)
	{
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('adv_port_trigger.asp');

		for(var i=0; i<DataArray.length; i++)
		{
			var instStr = "1.1."+ (i+1) +".0";
			obj.add_param_arg('ptRule_Enable_',instStr,((st)?st:DataArray[i].Enable));
			obj.add_param_arg('ptRule_ApplicationName_',instStr,DataArray[i].Name);
			obj.add_param_arg('ptRule_TriggerPorts_',instStr,DataArray[i].TGport);
			obj.add_param_arg('ptRule_FirewallPorts_',instStr,DataArray[i].FWport);
			obj.add_param_arg('ptRule_TriggerProtocol_',instStr,DataArray[i].TGprotocol);
			obj.add_param_arg('ptRule_FirewallProtocol_',instStr,DataArray[i].FWprotocol);
			obj.add_param_arg('ptRule_ScheduleIndex_',instStr,DataArray[i].Schdule);
		}
		var paramStr = obj.get_param();
		
		totalWaitTime = 20; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(paramStr.url, paramStr.arg);
	}
	
	function set_porttrigger_st()
	{
		var sel = $("#trigger_enable option:selected").val();
		if (!is_form_modified("form3") && !confirm(get_words('_ask_nochange'))) {
			return false;
		}

		if(submit_button_flag == 0){
			update_DataArray();
			paintTable();
			clear_reserved();
			AddApptoDatamodel(sel);
			submit_button_flag = 1;
		}
	}
	
	function send_request()
	{
		var tcp_timeline = '';
		var udp_timeline = '';

		if (!is_form_modified("form3") && !confirm(get_words('_ask_nochange'))) {
			return false;
		}
		
		// add virtual server ports into timeline
		try {
			for (var i=0; i<array_vs_enable.length; i++) {
				if (array_vs_enable[i] == '0')
					continue;
				var vs_ports = array_vs_ports[i].split(',');
				for (var j=0; j<vs_ports.length; j++) {
					var range = vs_ports[j].split('-');
					if (array_vs_proto[i] == '0') {
						tcp_timeline = add_into_timeline(tcp_timeline, range[0], range[1]);
					} else if (array_vs_proto[i] == '1') {
						udp_timeline = add_into_timeline(udp_timeline, range[0], range[1]);
					} else {
						tcp_timeline = add_into_timeline(tcp_timeline, range[0], range[1]);
						udp_timeline = add_into_timeline(udp_timeline, range[0], range[1]);
					}
				}
			}
		} catch (e) {
			alert('error occur in adding port trigger ports into timeline');
		}
		
		// add port forward ports into timeline
		try {
			for (var i=0; i<array_pf_enable.length; i++)
			{
				if (array_pf_enable[i] == '0')
					continue;
				var pf_tcp_ports = array_pf_ports_tcp[i].split(',');
				for (var j=0; j<pf_tcp_ports.length; j++) {
					var range = pf_tcp_ports[j].split('-');
					tcp_timeline = add_into_timeline(tcp_timeline, range[0], range[1]);
				}
				var pf_udp_ports = array_pf_ports_udp[i].split(',');
				for (var j=0; j<pf_udp_ports.length; j++) {
					var range = pf_udp_ports[j].split('-');
					udp_timeline = add_into_timeline(udp_timeline, range[0], range[1]);
				}
			}
		} catch (e) {
			alert('error occur in adding port forward ports into timeline');
		}

		var remote_port_enable=main.config_val("adminCfg_RemoteManagementEnable_");
		var remote_https_en=main.config_val("adminCfg_RemoteAdminHttpsEnable_");

		//add Remote management port to time line
		try
		{
			if(remote_port_enable == '1')
			{
				var tcp_ports = main.config_val("adminCfg_RemoteAdminHttpPort_");
				tcp_timeline = add_into_timeline(tcp_timeline, tcp_ports, null);
			}
			if(remote_https_en == '1')
			{
				var tcp_ports = main.config_val("adminCfg_RemoteAdminHttpsPort_");
				tcp_timeline = add_into_timeline(tcp_timeline, tcp_ports, null);
			}
		}
		catch (e)
		{
			alert('error occur in adding port trigger ports into timeline');
		}

		//add web access ports into timeline
		if(wa_http_en == 1)
		{
			tcp_timeline = add_into_timeline(tcp_timeline, wa_http_port, null);
			tcp_timeline = add_into_timeline(tcp_timeline, wa_https_port, null);
		}
		
		//add kcode-ftp access from wan into timeline
		if(dev_info.KCode_USB && (kcode_ftp_wan=='1'))
			tcp_timeline = add_into_timeline(tcp_timeline, '21', null);
		
		var count = 0;
		var check_name = $('#name').val();
		var proto_type = $("#public option:selected").val();// $('#public').val();
		var trigger_port = $('#trigger_port').val().split("-");
		var public_port = $('#public_port').val().split(",");
		var is_enable = 0;
		var temp_appl;

		if (check_name != "")
		{
			if (!chk_chars(check_name))
			{
				alert(get_words('_specappsr') + addstr(get_words('_adv_txt_02'), check_name));
				return false;
			}

			var chk_trigger_port="";
			if ($('#trigger_port').val() == ""){
				alert(get_words('PUBLIC_PORT_ERROR', LangMap.msg));
				return false;
			}else if ($('#public_port').val() == ""){
				alert(get_words('TRIGGER_PORT_ERROR', LangMap.msg));
				return false;
			}
			
			if(is_number($('#trigger_port').val()))
				$('#trigger_port').val(parseInt($('#trigger_port').val(),10));
			if(is_number($('#public_port').val()))
				$('#public_port').val(parseInt($('#public_port').val(),10));
				
			if (!check_port(trigger_port[0])){
				alert(get_words('PUBLIC_PORT_ERROR', LangMap.msg));
				return false;
			}		  
				chk_trigger_port += parseInt(trigger_port[0],10);
			if (trigger_port.length > 1){
				if (!check_port(trigger_port[1])){
					alert(get_words('TRIGGER_PORT_ERROR', LangMap.msg));
					return false;
				}	
				chk_trigger_port += "-" + parseInt(trigger_port[1],10);
			}
			$('#trigger_port').val(chk_trigger_port);
			
			var tmp_public = $('#public_port').val().split("-");
			if (tmp_public.length ==2 && tmp_public[0] == ""){
				alert(get_words('TRIGGER_PORT_ERROR', LangMap.msg));
				return false;
			}
			
			var chk_public_port="";
			for (var j = 0; j < public_port.length; j++)
			{
				var port = public_port[j].split("-");
				for(var k=0; k<port.length; k++)
				{
					if(port[k]=="")
					{
						alert(get_words('ac_alert_invalid_port'));
						return false;
					}
				}
				
				var temp_port1 = "";
				var temp_port2 = "";
				temp_port1 = port[0];
				
				if (port.length > 1)
					temp_port2 = port[1];
				
				if (temp_port1 != ""){
					if (!check_port(temp_port1)){
						alert(get_words('TRIGGER_PORT_ERROR', LangMap.msg));
						return false;
					}
					chk_public_port += parseInt(temp_port1,10);
				}
				if (temp_port2 != ""){
					if (!check_port(temp_port2)){
						alert(get_words('TRIGGER_PORT_ERROR', LangMap.msg));
						return false;
					}
					chk_public_port += "-" + parseInt(temp_port2,10);
				}
				if(public_port.length>1 && j<public_port.length-1)
					chk_public_port += ",";
			}
			
			$('#public_port').val(chk_public_port);

			//check application firewall port and remote management port conflict problem
			var remote_port = "";
			var remote_port_enable = "";
			if (proto_type == 0) {
				var tcp_ports = $('#public_port').val().split(',');
				for (var j=0; j<tcp_ports.length; j++) {
					var range = tcp_ports[j].split('-');
					tcp_timeline = add_into_timeline(tcp_timeline, range[0], range[1]);
				}
			} else if (proto_type == 1) {
				var udp_ports = $('#public_port').val().split(',');
				for (var j=0; j<udp_ports.length; j++) {
					var range = udp_ports[j].split('-');
					udp_timeline = add_into_timeline(udp_timeline, range[0], range[1]);
				}
			} else {
				var tcp_ports = $('#public_port').val().split(',');
				for (var j=0; j<tcp_ports.length; j++) {
					var range = tcp_ports[j].split('-');
					tcp_timeline = add_into_timeline(tcp_timeline, range[0], range[1]);
				}
				var udp_ports = $('#public_port').val().split(',');
				for (var j=0; j<udp_ports.length; j++) {
					var range = udp_ports[j].split('-');
					udp_timeline = add_into_timeline(udp_timeline, range[0], range[1]);
				}
			}

			for(jj=0;jj<DataArray.length;jj++)
			{
				if($('#edit').val()!=jj){
					if(DataArray[jj].Name==$('#name').val()){
						alert(get_words('ag_inuse'));
						return false;
						break;
					}
					if(DataArray[jj].TGprotocol == $('#trigger').val()){
						if(DataArray[jj].TGport==$('#trigger_port').val()){
							alert(get_words('TEXT057'));	
							return false;
						}
					}
					if(DataArray[jj].FWprotocol == $('#public').val()){
						if(DataArray[jj].FWport == $('#public_port').val()){
							alert(get_words('TEXT057'));	
							return false;
						}
					}
				}
			}
			if (check_timeline(tcp_timeline) == false) {
				alert(addstr(get_words('ag_conflict5'), 'Firewall', $('#public_port').val()));
				return false;
			}
		
			if (check_timeline(udp_timeline) == false) {
				alert(addstr(get_words('ag_conflict5'), 'Trigger', $('#public_port').val()));
				return false;
			}
			count++;
		}  else {
			alert(get_words('pt_name_empty'));
			//change tag from pf_name_empty to pt_name_empty
			return false;
		}
		
		if(submit_button_flag == 0){
			update_DataArray();
			paintTable();
			clear_reserved();
			AddApptoDatamodel();
			submit_button_flag = 1;
		}
		
		return false;
	}

	function add_option(id, def_val)
	{
		var obj = null;
		var arr = null;
		var nam = null;
		var str = '';
		
		if (id == 'Schedule') {
			obj = schedule_cnt;
			arr = array_sch_inst;
			nam = array_schedule_name;
		} else if (id == 'Inbound') {
			obj = inbound_cnt;
			arr = array_ib_inst;
			nam = array_ib_name;
		}
		
		if (obj == null)
			return;

		for (var i = 0; i < obj; i++){
			var str_sel = '';
			
			if (id == 'Schedule') {
				var inst = inst_array_to_string(arr[i]);
				if (def_val == inst.charAt(1))
					str_sel = 'selected';

				str += ("<option value=" + inst.charAt(1) + " " + str_sel + ">" + nam[i] + "</option>");
			} else if (id == 'Inbound') {
				var inst = inst_array_to_string(arr[i]);
				if (def_val == inst.charAt(2))
					str_sel = 'selected';

				str += ("<option value=" + inst.charAt(2) + " " + str_sel + ">" + nam[i] + "</option>");
			}
		}

		return str;
	}

	function trigger_st()
	{
		if ($("#trigger_enable option:selected").val() == 0)
		{
			$('#table1').hide();
			$('#tb_addrule').hide();
		} else {
			$('#table1').show();
			$('#tb_addrule').show();
		}
	}
			
	function paintTable()
	{
		var get_chk_counts = 0;
		var contain = '<div class="box_tn" id="table1">';
			contain += '<div class="CT">'+get_words('_adv_txt_19')+'</div>';
			contain += '<table align="center" cellpadding="0" cellspacing="0" class="formarea">';
			contain += '<tr class="break_word"><td height="22" align="center" class="CTS"><b>'+get_words('_enable')+'</b></td>';
			contain += '<td height="22" align="center" class="CTS"><b>'+get_words('_adv_txt_01')+'</b></td>';
			contain += '<td height="22" align="center" class="CTS"><b>'+get_words('_adv_txt_21')+get_words('_adv_txt_22')+'</b></td>';
			contain += '<td height="22" align="center" class="CTS"><b>'+get_words('GW_NAT_TRIGGER_PORT')+get_words('_adv_txt_22')+'</b></td>';
			contain += '<td height="22" align="center" class="CTS"><b>'+get_words('_sched')+'</b></td>';
			contain += '<td height="22" align="center" class="CTS"><b>'+get_words('_edit')+'</b></td>';
			contain += '<td height="22" align="center" class="CTS"><b>'+get_words('_delete')+'</b></td>';
			contain += '</tr>';

		for(var i = 0; i < DataArray.length; i++)
		{
			var is_chk='';
			if (DataArray[i].Enable == 1)
			{
				is_chk = "checked";
				get_chk_counts++;
			}
			contain += '<tr align="center" class="break_word">'+
					'<td align="center" class="CELL"><input type=checkbox id=sel_enable name=sel_enable disabled ' +is_chk+' />'+
					'</td><td align="center" class="CELL">' + DataArray[i].Name +
					'</td><td align="center" class="CELL">' + array_proto_name[DataArray[i].TGprotocol] + ' - ' + DataArray[i].TGport +
					'</td><td align="center" class="CELL">' + array_proto_name[DataArray[i].FWprotocol] + ' - ' + DataArray[i].FWport +
					'</td><td align="center" class="CELL">' + array_sche_list[DataArray[i].Schdule-1] +
					'</td><td align="center" class="CELL">' +
					'<a href="javascript:edit_row('+ i +')"><img src="edit.gif" border="0" title="'+get_words('_edit')+'" /></a>'+
					'</td><td align="center" class="CELL">' +
					'<a href="javascript:del_row(' + i +')"><img src="delete.gif"  border="0" title="'+get_words('_delete')+'" /></a>'+
					'</td>';
		}
		contain += '</tr>';
		contain += '</table>';
		contain += '</div>';
		$('#PFTable').html(contain);
		
		var set_trigger_enable = (get_chk_counts > 0)?1:0;
		set_selectIndex(set_trigger_enable, $('#trigger_enable')[0]);

		set_form_default_values("form3");
	}

$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(model);
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
			<td style="background-image:url('/image/bg_l.gif');background-repeat:repeat-y;vertical-align:top;" width="270">
				<div style="padding-left:6px;">
				<script>document.write(menu.build_structure(1,5,2))</script>
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
								<script>show_words('_specapps');</script>
								</div>
								<div class="hr"></div>
								<div class="section_content_border">
								<div class="header_desc" id="introduction">
									<script>show_words('_adv_txt_13');</script>
									<p></p>
								</div>

			<form id="form1" name="form1" method="post" action="get_set.ccp">
				<input type="hidden" name="ccp_act" value="set" />
				<input type="hidden" name="nextPage" value="adv_port_trigger.asp" />	
				<input type="hidden" id="del" name="del" value="-1" />
				<input type="hidden" id="edit" name="edit" value="-1" />
				<input type="hidden" id="max_row" name="max_row" value="-1" />
			</form>
<form id="form3">
<div class="box_tn">
	<div class="CT"><script>show_words('_adv_txt_15');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL"><script>show_words('_adv_txt_18');</script></td>
		<td class="CR">
			<select id="trigger_enable" name="trigger_enable" onchange="trigger_st()">
				<option value="0"><script>show_words('_disable');</script></option>
				<option value="1"><script>show_words('_enable');</script></option>
			</select>
		</td>
	</tr>
	<tr align="center">
			<td colspan="2" class="btn_field">
		<input name="apply" type="button" class="ButtonSmall" id="apply" onClick="set_porttrigger_st()" />
			<script>$('#apply').val(get_words('_adv_txt_17'));</script>
		</td>
	</tr>
	</table>
</div>

<div class="box_tn">
	<div class="CT"><script>show_words('_adv_txt_16');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL"><script>show_words('_adv_txt_00')</script></td>
		<td class="CR" width="340">
			<input type="checkbox" id="enable" name="enable" value="1" />
		</td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_adv_txt_01');</script></td>
		<td class="CR">
			<input type="text" id="name" name="name" size="16" maxlength="15" />
		</td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_adv_txt_20');</script></td>
		<td class="CR">
			<select class="wordstyle" id="trigger">
			<option value="0"><script>show_words('_TCP');</script></option>
			<option value="1"><script>show_words('_UDP');</script></option>
			<option value="2"><script>show_words('at_Any');</script></option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_adv_txt_21');</script></td>
		<td class="CR">
			<input type="text" id="trigger_port" name="trigger_port" style="width:150;" size="16" maxlength="15" />
		</td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('as_TPR');</script></td>
		<td class="CR">
			<select class="wordstyle" id="public">
			<option value="0"><script>show_words('_TCP');</script></option>
			<option value="1"><script>show_words('_UDP');</script></option>
			<option value="2"><script>show_words('at_Any');</script></option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('GW_NAT_TRIGGER_PORT');</script></td>
		<td class="CR">
			<input type="text" id="public_port" name="public_port" style="width:150;" size="16" maxlength="15" />
		</td>
	</tr>
	<script>
		var array_proto_name =  new Array(get_words('_TCP'), get_words('_UDP'), get_words('at_Any'));
	</script>
	<tr>
		<td class="CL">
			<script>show_words('_sched');</script></td>
		<td class="CR">
			<input type="checkbox" id="sch_enable_1" value="1" /> &nbsp;
			<span id="sche_list"></span>
			<input type="button" id="ln_btn_1" onclick="location.assign('/adv_schedule.asp');" />
			<script>$('#ln_btn_1').val(get_words('tc_new_sch'));</script>
		</td>
		<script>
			var array_sche_list =(array_schedule_name==null)? new Array(): array_schedule_name;
			array_sche_list[255-1] = get_words('_always');
			array_sche_list[254-1] = get_words('_never');
		</script>
	</tr>
	<tr align="center">
		<td colspan="2" class="btn_field">
			<input name="add" type="button" class="ButtonSmall" id="add" onClick="return send_request()" />
			<script>$('#add').val(get_words('_add'));</script>
			<input name="clear" type="button" disabled class="ButtonSmall" id="clear" onClick="document.location.reload();" />
			<script>$('#clear').val(get_words('_clear'));</script>
		</td>
	</tr>
	</table>
</div>

	</form>
	<br/>
	<span id="PFTable"></span>
	<script>
		set_application();
		paintTable();
	</script>
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
<script>
	onPageLoad();
</script>
</html>