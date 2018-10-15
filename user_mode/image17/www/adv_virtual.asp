<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
	<script>
		var funcWinOpen = window.open;
	</script>
<title>TRENDNET | modelName | Advanced | Virtual Server</title>
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
	var selectIDArray = new Array("", "inbound_filter", "schedule");

	var menu = new menuObject();
	menu.setSupportUSB(dev_info.KCode_USB);

	var hw_version 	= dev_info.hw_ver;
	var version 	= dev_info.fw_ver;
	var model		= dev_info.model;
	var login_Info 	= dev_info.login_info;

	var main = new ccpObject();
	main.set_param_url('get_set.ccp');
	main.set_ccp_act('get');
	main.add_param_arg('IGD_WANDevice_i_VirServRule_i_',1100);
	main.add_param_arg('IGD_ScheduleRule_i_',1000);
	main.add_param_arg('IGD_WANDevice_i_InboundFilter_i_',1100);
	main.add_param_arg('IGD_LANDevice_i_ConnectedAddress_i_',1110);
	main.add_param_arg('IGD_',1000);
	main.add_param_arg('IGD_WANDevice_i_PortFwd_i_',1100);
	main.add_param_arg('IGD_WANDevice_i_PortTriggerRule_i_',1100);
	main.add_param_arg('IGD_AdministratorSettings_',1000);
	main.add_param_arg('IGD_Storage_',1100);
	if(dev_info.KCode_USB)
		main.add_param_arg('IGD_FTPServer_',1100);
	main.get_config_obj();
	
	var dev_mode = main.config_val("igd_DeviceMode_");
	
	var array_enable 		= main.config_str_multi("vsRule_Enable_");
	var array_name 			= main.config_str_multi("vsRule_VirtualServerName_");
	var array_intnlip 		= main.config_str_multi("vsRule_InternalIPAddr_");
	var array_PubPort 		= main.config_str_multi("vsRule_PublicPort_");
	var array_PriPort 		= main.config_str_multi("vsRule_PrivatePort_");
	var array_Protcol	 	= main.config_str_multi("vsRule_Protocol_");
	var array_Schdule 		= main.config_str_multi("vsRule_ScheduleIndex_");
	var array_Policy 		= main.config_str_multi("vsRule_Policy_");
	var array_Protcol_num	= main.config_str_multi("vsRule_ProtoNum_");
	
	var array_pf_enable		= main.config_str_multi("pfRule_Enable_");
	var array_pf_ports_udp	= main.config_str_multi("pfRule_UDPOpenPorts_");
	var array_pf_ports_tcp	= main.config_str_multi("pfRule_TCPOpenPorts_");
	var array_pt_enable		= main.config_str_multi("ptRule_Enable_");
	var array_pt_proto		= main.config_str_multi("ptRule_FirewallProtocol_");
	var array_pt_ports		= main.config_str_multi("ptRule_FirewallPorts_");

	var array_sch_inst 		= main.config_inst_multi("IGD_ScheduleRule_i_");
	var array_schedule_name	= main.config_str_multi("schRule_RuleName_");
		
	var wa_http_en 			= main.config_val("igdStorage_Http_Remote_Access_Enable_");
	var wa_http_port 		= main.config_val("igdStorage_Http_Remote_Access_Port_");
	var wa_https_port		= main.config_val("igdStorage_Https_Remote_Access_Port_");
	if(dev_info.KCode_USB)
		var kcode_ftp_wan		= main.config_val("igdFTPServer_AccessWanEnable_");
	var schedule_cnt = 0;
	
	if(array_schedule_name != null)
		schedule_cnt = array_schedule_name.length;

	var inbound_cnt = 0;
	var array_ib_inst		= main.config_inst_multi("IGD_WANDevice_i_InboundFilter_i_");
	var array_ib_name		= main.config_str_multi("ibFilter_Name_");
	
	if(array_ib_name != null)
		inbound_cnt = array_ib_name.length;
	
	var submit_button_flag = 0;
	var rule_max_num = 24;
	var inbound_used = 0;
	var DataArray = new Array();
	var TotalCnt=0;
	
	function onPageLoad()
	{
		var login_who=login_Info;
		if(login_who!= "w" || dev_mode == "1"){
			DisableEnableForm(form2,true);	
		}

		var table_str = '';
			table_str += "<select id=schedule name=schedule style='width:80px;'>";
			table_str += '<option value=\"255\">'+get_words('_always')+'</option>';
			table_str += '<option value=\"254\">'+get_words('_never')+'</option>';
			table_str += add_option('Schedule', array_Schdule);
			table_str += "</select>";
			$('#sche_list').html(table_str);
		
		var table_str = '';
			table_str += "<select id=inbound_filter name=inbound_filter style='width:80px;'>";
			table_str += '<option value=\"255\">'+get_words('_allowall')+'</option>';
			table_str += '<option value=\"254\">'+get_words('_denyall')+'</option>';
			table_str += add_option('Inbound', array_Policy);
			table_str += "</select>";
			$('#pol_list').html(table_str);

		$('#protocol').hide();
	}
	
	function Data(enable, name, ip_addr, pubPort, priPort, protcol, schdule, policy, protcol_num, onList)
	{
		this.Enable = enable;
		this.Name = name;
		this.Ip_addr = ip_addr;
		this.PubPort = pubPort;
		this.PriPort = priPort;
		this.Protcol = protcol;
		this.Schdule = schdule;
		this.Policy = policy;
		this.Protcol_num = protcol_num;
		this.OnList = onList ;
	}

	function set_virtualserver()
	{
		var index = 0;
		for (var i = 0; i < rule_max_num; i++) {
			if(array_name[i] != "" && array_name[i])
			{
				TotalCnt++;
				DataArray[DataArray.length++] = new Data(array_enable[i], array_name[i], array_intnlip[i], array_PubPort[i], array_PriPort[i], array_Protcol[i], array_Schdule[i], array_Policy[i], array_Protcol_num[i], i);
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
			obj.add_param_arg('IGD_WANDevice_i_VirServRule_i_','1.1.'+idx+'.0');
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
			DataArray[i].Ip_addr = DataArray[i+1].Ip_addr;
			DataArray[i].PubPort = DataArray[i+1].PubPort;
			DataArray[i].PriPort = DataArray[i+1].PriPort;
			DataArray[i].Protcol = DataArray[i+1].Protcol;
			DataArray[i].Schdule = DataArray[i+1].Schdule;
			DataArray[i].Policy = DataArray[i+1].Policy;
			DataArray[i].Protcol_num = DataArray[i+1].Protcol_num;
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

		obj.add_param_arg('IGD_WANDevice_i_VirServRule_i_','1.1.'+TotalCnt+'.0');
		
		obj.ajax_submit();
		AddVStoDatamodel();
	}

	function update_DataArray()
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

		var protocol;
		switch($('#protocol_select').val()){
		case '6': protocol=0; break;//TCP
		case '17': protocol=1; break;//UDP
		case '256': protocol=2; break;//BOTH
		}
		if(insert){
			DataArray[index] = new Data(is_chk, $('#name').val(), $('#ip').val(), $('#public_portS').val(), $('#private_portS').val(), protocol, $('#schedule').val(), $('#inbound_filter').val(), $('#protocol').val(), index+1);			
		}else if (index != -1){	
			DataArray[index].Enable =is_chk;
			DataArray[index].Name = $('#name').val();
			DataArray[index].Ip_addr = $('#ip').val();
			DataArray[index].PubPort = $('#public_portS').val();
			DataArray[index].PriPort = $('#private_portS').val();
			DataArray[index].Protcol = protocol;
			DataArray[index].Schdule = $('#schedule').val();
			DataArray[index].Policy = $('#inbound_filter').val();
			DataArray[index].Protcol_num = $('#protocol').val();
			DataArray[index].OnList = index;
		}
	}


	function clear_reserved()
	{
		$("input[name=sel_del]").each(function () { this.checked = false; });
		set_checked(0, $('#enable')[0]);
		$('#name').val("");
		$('#ip').val("");
		$('#public_portS').val("");
		$('#private_portS').val("");
		$('#protocol_select').val(0);
		$('#schedule').val(255);
		$('#inbound_filter').val(255);
		$('#edit').val(-1);
		protocol_change();
		$('#add').attr('disabled', '');
        $('#clear').attr('disabled', true);
	}

	function edit_row(idx)
    {
		set_checked(DataArray[idx].Enable, $('#enable')[0]);
        $('#name').val(DataArray[idx].Name);
        $('#ip').val(DataArray[idx].Ip_addr);
        $('#public_portS').val(DataArray[idx].PubPort);
		$('#private_portS').val(DataArray[idx].PriPort);
		$('#protocol_select').val(DataArray[idx].Protcol_num);
		set_checked(DataArray[idx].Schdule == 255 ? 0 : 1, $('#sch_enable_2')[0]);
		$('#schedule').val(DataArray[idx].Schdule);
		set_checked(DataArray[idx].Policy == 255 ? 0 : 1, $('#inb_enable_1')[0]);
		$('#inbound_filter').val(DataArray[idx].Policy);
		$('#protocol').val(DataArray[idx].Protcol);
		$('#edit').val(idx);
		$('#add').val(get_words('_save'));

		protocol_change();
		$('#clear').attr('disabled', '');
		setEnable("inb_enable_1");
		setEnable("sch_enable_2");
    }

	function AddVStoDatamodel()
	{
		if(DataArray.length>0)
		{
			var obj = new ccpObject();
			obj.set_param_url('get_set.ccp');
			obj.set_ccp_act('set');
			obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
			obj.set_param_next_page('adv_virtual.asp');

			for(var i=0; i<DataArray.length; i++)
			{
				var instStr = "1.1."+ (i+1) +".0";
				obj.add_param_arg('vsRule_Enable_',instStr,DataArray[i].Enable);
				obj.add_param_arg('vsRule_VirtualServerName_',instStr,DataArray[i].Name);
				obj.add_param_arg('vsRule_InternalIPAddr_',instStr,DataArray[i].Ip_addr);
				obj.add_param_arg('vsRule_PublicPort_',instStr,DataArray[i].PubPort);
				obj.add_param_arg('vsRule_PrivatePort_',instStr,DataArray[i].PriPort);
				obj.add_param_arg('vsRule_Protocol_',instStr,DataArray[i].Protcol);
				obj.add_param_arg('vsRule_ProtoNum_',instStr,DataArray[i].Protcol_num);
				obj.add_param_arg('vsRule_ScheduleIndex_',instStr,DataArray[i].Schdule);
				obj.add_param_arg('vsRule_Policy_',instStr,DataArray[i].Policy);
			}
			var paramStr = obj.get_param();

			totalWaitTime = 20; //second
			redirectURL = location.pathname;
			wait_page();
			jq_ajax_post(paramStr.url, paramStr.arg);
		}
	}
	
    function protocol_change()
	{
		var sel = $('#protocol_select').val();
		if(sel != -1){ //TCP, UDP, Both
			$("#protocol").attr('disabled', true);
			$("#public_portS").attr('disabled', '');
			$("#private_portS").attr('disabled', '');
			$("#protocol").val($('#protocol_select').val());
		}else { // Other
			$("#public_portS").val('0');
			$("#private_portS").val('0');
			$("#protocol").attr('disabled', '');
		}
		
		if(login_Info != "w")
		{
			$("#protocol").attr('disabled',true);
			$("#public_portS").attr('disabled', true);
			$("#private_portS").attr('disabled', true);
		}
	}

	function detect_protocol_change_port(proto)
	{
		if((proto == 0)||(proto == 1)||(proto == 2)){
			$("#protocol").attr('disabled',true);
			$("#public_portS").attr('disabled', '');
			$("#private_portS").attr('disabled', '');
		}else{
			$("#public_portS").attr('disabled', true);
			$("#private_portS").attr('disabled', true);
		}
		
		if(login_Info != "w")
		{
			$("#protocol").attr('disabled',true);
			$("#public_portS").attr('disabled', true);
			$("#private_portS").attr('disabled', true);
		}
	}

	function send_request()
	{
		var tcp_timeline = '';
		var udp_timeline = '';

		if (!is_form_modified("form3") && !confirm(get_words('_ask_nochange'))) {
			return false;
		}
		var count = 0;
		
		var tmp_name = $('#name').val();
		if (tmp_name != "")
		{
			for (var j = 0; j < DataArray.length; j++)
			{
				if($('#edit').val()!=j){
					if (tmp_name == DataArray[j].Name){
						alert(addstr(get_words('av_alert_16'), tmp_name));
						return false;
					}
				}
			}
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
		
		// add port trigger ports into timeline
		try {
			for (var i=0; i<array_pt_enable.length; i++) {
				if (array_pt_enable[i] == '0')
					continue;
				var pt_ports = array_pt_ports[i].split(',');
				for (var j=0; j<pt_ports.length; j++) {
					var range = pt_ports[j].split('-');
					if (array_pt_proto[i] == '0') {
						tcp_timeline = add_into_timeline(tcp_timeline, range[0], range[1]);
					} else if (array_pt_proto[i] == '1') {
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

		//add Remote management port to time line
		var remote_port=main.config_val("adminCfg_RemoteAdminHttpPort_");
		var remote_port_enable=main.config_val("adminCfg_RemoteManagementEnable_");
		var remote_https_port=main.config_val("adminCfg_RemoteAdminHttpsPort_");
		
		try
		{
			if(remote_port_enable == '1')
			{
				var tcp_ports = remote_port;
				var tcp_ports_https = remote_https_port;
				tcp_timeline = add_into_timeline(tcp_timeline, tcp_ports, null);
				tcp_timeline = add_into_timeline(tcp_timeline, tcp_ports_https, null);
			}
		}
		catch (e)
		{
			alert('error occur in adding port remote ports into timeline');
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
		
		var ip = $('#ip').val();
		var protocol = $('#protocol_select').val();
		var check_name = $('#name').val();

		var ip_addr_msg = replace_msg(all_ip_addr_msg,get_words('help256'));
		var temp_ip_obj = new addr_obj(ip.split("."), ip_addr_msg, false, false);
		var temp_vs;

		if (check_name != "")
		{
			if (!chk_chars(check_name))
			{
				alert(get_words('_vs_title') + addstr(get_words('_adv_txt_02'), check_name));
				return false;
			}

			//check dhcp ip range equal to lan-ip or not?
			if(!check_LAN_ip(dev_info.lanIP.split('.'), temp_ip_obj.addr, get_words('help256'))){
				return false;
			}

			//check ip addr
			if (ip != '255.255.255.255')
			{
				if (!check_address(temp_ip_obj))
					return false;
			}

			// check port
			if(protocol != -1)//do check if not choose specific protocol(Other)
			{
				if (!check_port($('#public_portS').val())) {
					alert(get_words('virtual_pub_port_err'));
					return false;
				}else if (!check_port($('#private_portS').val())) {
					alert(get_words('virtual_pri_port_err'));
					return false;
				}
			}
			
			// check protocol number 
			if (!check_integer(protocol, 0, 256)) {
				alert(get_words('virtual_proto_num_err'));
				return false;
			}

			var protocol_type = 'Both';
			if (protocol == '6') {			//tcp
				tcp_timeline = add_into_timeline(tcp_timeline, $('#public_portS').val(), null);
				protocol_type = "TCP";
			} else if (protocol == '17') {	//udp
				udp_timeline = add_into_timeline(udp_timeline, $('#public_portS').val(), null);
				protocol_type = "UDP";
			} else if (protocol == '256') {	//all
				tcp_timeline = add_into_timeline(tcp_timeline, $('#public_portS').val(), null);
				udp_timeline = add_into_timeline(udp_timeline, $('#public_portS').val(), null);
			}

			if ((check_timeline(tcp_timeline) == false) || (check_timeline(udp_timeline) == false)){
				alert(addstr(get_words('ag_conflict5'), protocol_type, $('#public_portS').val()));
				return false;
			}
		} 
		else {
			alert(get_words('vs_name_empty'));
			return false;
		}

		if(submit_button_flag == 0){
			update_DataArray();
			paintTable();
			clear_reserved();
			AddVStoDatamodel();
			
			submit_button_flag = 1;
		}
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

	function do_select(id, type, value)
	{
		var obj = null;
		
		if (type == 'idx') {
			obj = $('#'+id+' option:eq('+value+')')
		} else if (type == 'val') {
			obj = $('#'+id+' option[value='+value+']');
		}
		
		if (obj == null)
			return;
		obj.attr("selected", true);
	}

	function paintTable()
	{	
		var contain = ""
			contain += '<table border="0" align="center" cellpadding="0" cellspacing="0" id="table1" class="formarea">';
			contain += '<div class="CT">'+get_words('_adv_txt_05')+'</div>';
			contain += '<tr><td height="22" align="center" class="CTS"><b>'+get_words('_enable')+'</b></td>';
			contain += '<td height="22" align="center" class="CTS"><b>'+get_words('_adv_txt_01')+'</b></td>';
			contain += '<td height="22" align="center" class="CTS"><b>'+get_words('_ipaddr')+'</b></td>';			
			contain += '<td height="22" align="center" class="CTS" ><b>'
					+ get_words('_protocol')+',<br />'+ get_words('av_PubP')+'/\n&nbsp;'+ get_words('av_PriP')+'</b></td>';
			contain += '<td height="22" align="center" class="CTS"><b>'+get_words('INBOUND_FILTER')+'</b></td>';
			contain += '<td height="22" align="center" class="CTS"><b>'+get_words('_sched')+'</b></td>';
			contain += '<td height="22" align="center" class="CTS"><b>'+get_words('_edit')+'</b></td>';
			contain += '<td height="22" align="center" class="CTS"><b>'+get_words('_delete')+'</b></td>';
			contain += '</tr>';

		for(var i = 0; i < DataArray.length; i++)
		{
			var is_chk='';
			if (DataArray[i].Enable == 1)
				is_chk = "checked";
			contain += '<tr align="center">'+
					'<td align="center" class="CELL">' +(i+1)+ '&nbsp;<input type="checkbox" id="sel_enable" name="sel_enable" disabled ' +is_chk+' />'+
					'</td><td align="center" class="CELL break_word">' + DataArray[i].Name +
					'</td><td align="center" class="CELL break_word">' + DataArray[i].Ip_addr +
					'</td><td align="center" class="CELL break_word">' + array_proto_name[DataArray[i].Protcol] + ','
															 + DataArray[i].PubPort + '/' + DataArray[i].PriPort +
					'</td><td align="center" class="CELL break_word">'+ array_ib_list[DataArray[i].Policy-1] +
					'</td><td align="center" class="CELL break_word">'+ array_sche_list[DataArray[i].Schdule-1] +
					'</td><td align="center" class="CELL">'+
					'<a href="javascript:edit_row('+ i +')"><img src="edit.gif" border="0" title="'+get_words('_edit')+'" /></a>'+
					'</td><td align="center" class="CELL">'+
					'<a href="javascript:del_row(' + i +')"><img src="delete.gif"  border="0" title="'+get_words('_delete')+'" /></a>'+
					'</td>';
		}

		contain += '</tr>';
		contain += '</table>';
		$('#VSTable').html(contain);
		
		set_form_default_values("form3");
	}

$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(model);
	setEnable("inb_enable_1");
	setEnable("sch_enable_2");
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
				<script>document.write(menu.build_structure(1,5,1))</script>
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
							<script>show_words('_vs_title');</script>
							</div>
							<div class="hr"></div>
							<div class="section_content_border">
							<div class="header_desc" id="introduction">
								<script>show_words('_adv_txt_03');</script>
								<p></p>
							</div>

			<form id="form2" method="post" name="form2">
				<input id="html_response_page" name="html_response_page" type="hidden" value="back.asp" /> 
				<input id="html_response_return_page" name="html_response_return_page" type="hidden" value="adv_virtual.asp" /> 
				<input id="reboot_type" name="reboot_type" type="hidden" value="filter" />	
				<input type="hidden" id="del" name="del" value="-1" />
				<input type="hidden" id="edit" name="edit" value="-1" />
				<input type="hidden" id="max_row" name="max_row" value="-1" />
			</form>

			<form id="form3">
<div class="box_tn">
	<div class="CT"><script>show_words('_adv_txt_04');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL"><script>show_words('_adv_txt_00')</script></td>
		<td class="CR">
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
		<td class="CL"><script>show_words('_ipaddr');</script></td>
		<td class="CR">
			<input type="text" id="ip" name="ip" size="16" maxlength="15" value="" />
		</td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_protocol');</script></td>
		<td class="CR">
			<select id="protocol_select" name="protocol_select" width=80 value="" onChange="protocol_change();detect_protocol_change_port(this.selectedIndex)";>
				<option value=6 selected><script>show_words('_TCP');</script></option>
				<option value=17><script>show_words('_UDP');</script></option>
				<option value=256><script>show_words('_both');</script></option>
				<!--
					<option value=-1><script>show_words('_other');</script></option>
				-->
			</select>
			<input type="text" id="protocol" name="protocol" size="5" maxlength="3" value="" />
		</td>
		<script>
			var array_proto_name = new Array();
			array_proto_name[0] = get_words('_TCP');
			array_proto_name[1] = get_words('_UDP');
			array_proto_name[2] = get_words('_both');
			//array_proto_name[-1] = get_words('_other');
		</script>
	</tr>
	<tr>
		<td class="CL"><script>show_words('av_PubP');</script></td>
		<td class="CR"><input type="text" id="public_portS" name="public_portS" size="16" maxlength="5" value="" /></td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('av_PriP');</script></td>
		<td class="CR"><input type="text" id="private_portS" name="private_portS" size="16" maxlength="5" value="" /></td>
	</tr>
	<tr>
		<td class="CL">
			<script>show_words('INBOUND_FILTER');</script></td>
		<td class="CR">
			<input type="checkbox" id="inb_enable_1" value="1" /> &nbsp;
			<span id="pol_list"></span>
			<input type="button" id="ln_btn_1" onclick="location.assign('/adv_inbound_filter.asp');" />
			<script>$('#ln_btn_1').val(get_words('tc_new_inb'));</script>
		</td>
		<script>
			var array_ib_list = (array_ib_name==null)? new Array(): array_ib_name;
			array_ib_list[255-1] = get_words('_allowall');
			array_ib_list[254-1] = get_words('_denyall');
		</script>
	</tr>
	<tr>
		<td class="CL">
			<script>show_words('_sched');</script></td>
		<td class="CR">
			<input type="checkbox" id="sch_enable_2" value="1" /> &nbsp;
			<span id="sche_list"></span>
			<input type="button" id="ln_btn_2" onclick="location.assign('/adv_schedule.asp');" />
			<script>$('#ln_btn_2').val(get_words('tc_new_sch'));</script>
		</td>
		<script>
			var array_sche_list = (array_schedule_name==null)? new Array(): array_schedule_name;					
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

			<span id="VSTable"></span>
			<script>
				set_virtualserver();
				paintTable();
				do_select('protocol_select', 'idx', array_Protcol);
				protocol_change();
			</script>
		
			<form id="form1" name="form1" method="post" action="get_set.ccp">
				<input type="hidden" id="action" name="action" value="setpar" />
				<input type="hidden" name="ccpSubEvent" value="CCP_SUB_WEBPAGE_APPLY" />
				<input type="hidden" name="nextPage" value="adv_virtual.asp" />
				<input type="hidden" id="html_response_return_page" name="html_response_return_page" value="adv_virtual.asp" />
				<input type="hidden" name="ccp_act" value="set" />
				<input type="hidden" name="num_inst" value="1" />
				<input type="hidden" name="oid_1" value="IGD_WANDevice_i_VirServRule_i_" />
				<input type="hidden" name="inst_1" value="11100" />
				<input type="hidden" id="reboot_type" name="reboot_type" value="filter" />
				<script>
				for(var i=1; i<=rule_max_num; i++)
				{
					document.write('<input type="hidden" id="vsRule_Enable_'+i+'" name="vsRule_Enable_1.1.'+i+'.0" value="" />');
					document.write('<input type="hidden" id="vsRule_VirtualServerName_'+i+'" name="vsRule_VirtualServerName_1.1.'+i+'.0" value="" />');
					document.write('<input type="hidden" id="vsRule_InternalIPAddr_'+i+'" name="vsRule_InternalIPAddr_1.1.'+i+'.0" value="" />');
					document.write('<input type="hidden" id="vsRule_PublicPort_'+i+'" name="vsRule_PublicPort_1.1.'+i+'.0" value="" />');
					document.write('<input type="hidden" id="vsRule_PrivatePort_'+i+'" name="vsRule_PrivatePort_1.1.'+i+'.0" value="" />');
					document.write('<input type="hidden" id="vsRule_Protocol_'+i+'" name="vsRule_Protocol_1.1.'+i+'.0" value="" />');
					document.write('<input type="hidden" id="vsRule_ProtoNum_'+i+'" name="vsRule_ProtoNum_1.1.'+i+'.0" value="" />');
					document.write('<input type="hidden" id="vsRule_ScheduleIndex_'+i+'" name="vsRule_ScheduleIndex_1.1.'+i+'.0" value="" />');
					document.write('<input type="hidden" id="vsRule_Policy_'+i+'" name="vsRule_Policy_1.1.'+i+'.0" value="" />');
				}
				</script>
			</form>

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