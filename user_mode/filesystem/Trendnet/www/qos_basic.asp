<html>
<head>
<script>
	var funcWinOpen = window.open;
</script>
<title>TRENDNET | modelName | Advanced | QoS Engine</title>
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

	var hw_version 	= dev_info.hw_ver;
	var version 	= dev_info.fw_ver;
	var model		= dev_info.model;
	var login_Info 	= dev_info.login_info;
	var cli_mac 	= dev_info.cli_mac;

	var main = new ccpObject();
	main.set_param_url('get_set.ccp');
	main.set_ccp_act('get');
	main.add_param_arg('IGD_WANDevice_i_TrafficControl_',1110);
	main.add_param_arg('IGD_WANDevice_i_TrafficControl_Rule_i_',1110);
	main.add_param_arg('IGD_WANDevice_i_',1100);
	main.add_param_arg('IGD_Firewall_',1100);
	main.add_param_arg('IGD_WANDevice_i_TrafficControl_UPGroup_i_',1110);
	
	main.get_config_obj();
	
	var hw_nat_enable = main.config_val("wanConnDev_HardwareNatEnable_");
	var trafficshap_enable = main.config_val('wanTrafficShp_EnableTrafficShaping_');
	var spi_enable = main.config_val("firewallSetting_SPIEnable_");

	var qos_obj = {
		'enable':	main.config_val('wanTrafficShp_EnableTrafficShaping_'),
		'upload':	main.config_val('wanTrafficShp_UploadBandWidth_')
	};
	
	var upload_group ={
		'name':	main.config_str_multi('trafficUPGroup_Name_'),
		'attr':	main.config_str_multi('trafficUPGroup_Attribute_')
	};
	
	var qos_rule_obj = {
		'enable':			main.config_str_multi('trafficRule_Enable_'),
		'name':				main.config_str_multi('trafficRule_Name_'),
		'priority':			main.config_str_multi('trafficRule_Priority_'),
		'localIPStart':		main.config_str_multi('trafficRule_LocalIPAddrStart_'),
		'localIPEnd':		main.config_str_multi('trafficRule_LocalIPAddrEnd_'),
		'remoteIPStart':	main.config_str_multi('trafficRule_RemoteIPAddrStart_'),
		'remoteIPEnd':		main.config_str_multi('trafficRule_RemoteIPAddrEnd_'),
		'localPortStart':	main.config_str_multi('trafficRule_LocalPortStart_'),
		'localPortEnd':		main.config_str_multi('trafficRule_LocalPortEnd_'),
		'remotePortStart':	main.config_str_multi('trafficRule_RemotePortStart_'),
		'remotePortEnd':	main.config_str_multi('trafficRule_RemotePortEnd_'),
		'appTCPPorts':		main.config_str_multi('trafficRule_AppTCPPorts_'),
		'appUDPPorts':		main.config_str_multi('trafficRule_AppUDPPorts_'),
		'protocolIdx':		main.config_str_multi('trafficRule_Protocol_'),
		'protocalNum':		main.config_str_multi('trafficRule_ProtoNum_'),
		'mac':				main.config_str_multi('trafficRule_MACAddress_'),
		'dscp':				main.config_str_multi('trafficRule_DSCP_'),
		'appname':			main.config_str_multi('trafficRule_AppName_'),
		'groupAttr':		main.config_str_multi('trafficRule_GroupAttr_'),
		'pkLStart':			main.config_str_multi('trafficRule_PacketLenStart_'),
		'pkLEnd':			main.config_str_multi('trafficRule_PacketLenEnd_'),
		'Remarkdscp':		main.config_str_multi('trafficRule_RemarkDSCP_')
	};
	
	var total_num = 0;
	for(var i=0; i<qos_rule_obj.name.length; i++)
	{
		if(qos_rule_obj.name[i] != "")
			total_num++;
	}
	
	var uploadBandwidth_custom = "k";
	var downloadBandwidth_custom = "k";
	var QOS_MAX_HARD_LIMITED = "100M";	// Our max bandwidth that we can deal with.
	var QOS_MIN_HARD_LIMITED = "32k";	// Our min bandwidth that we hope.
	var QoSappl = new Array();
	
	function onPageLoad()
	{
		//Group
		var content = '<select name="af_index" id="af_index">';
		for(var i=0; i<upload_group.name.length; i++)
		{
			content += '<option value="' + upload_group.attr[i] + '">'+ upload_group.name[i] +'(' + get_words('tf_Upload') + ')</option>';
		}
		content += "</select>";
		$("#group_list").html(content);
		
		//Application
		for(var i=0; i<default_QoSappl.length; i++)
		{
			if(default_QoSappl[i].desc.indexOf("game") == "-1")
				QoSappl[QoSappl.length++] = new Qosappl_obj(default_QoSappl[i].id, default_QoSappl[i].name, default_QoSappl[i].desc, default_QoSappl[i].tcp, default_QoSappl[i].udp) ;
		}
		for(var i=0; i<default_QoSappl.length; i++)
		{
			if(default_QoSappl[i].desc.indexOf("game") != "-1")
			{
				var name = "[Game] - " + default_QoSappl[i].name;
				QoSappl[QoSappl.length++] = new Qosappl_obj(default_QoSappl[i].id, name, default_QoSappl[i].desc, default_QoSappl[i].tcp, default_QoSappl[i].udp);
			}
		}
		var table_str = '';
			table_str += '<select name="layer7" id="layer7" size="15" onChange="layer7Change(this.selectedIndex)" >';
			for(var j=0; j<QoSappl.length; j++)
				table_str += '<option value="'+ QoSappl[j].id +'" index="'+j+'">'+QoSappl[j].name +'</option>';
			table_str += '</select>';
			$('#app_list').html(table_str);
			
		//read if its edit or add
		if(window.location.href.indexOf("?") != -1)
		{
			var argsHref = window.location.href.split("?")[1];
			$('#mode').val(argsHref.split("&")[0].split("=")[1]); // ?mode=edit&info=index
			$('#no').val(argsHref.split("&")[1].split("=")[1]);	  // ?mode=edit&info=index
		}
		
		var qoslink = "qos_advanced.asp";
				
		if($('#mode').val() == "edit")
		{
			loadValue(parseInt($('#no').val(),10));
			qoslink += "?mode=" + $('#mode').val() + "&info=" + $('#no').val();
		}
		$('a#qos_link').attr('href', qoslink);
			
		
    }
	
	function loadValue(idx)
	{
		$('#comment').val(qos_rule_obj.name[idx]);
		switch (qos_rule_obj.protocolIdx[idx])
		{
			case "1":
				set_selectIndex('TCP', $("#protocol")[0]);
				protocolChange();				
				$('#sFromPort').val(qos_rule_obj.localPortStart[idx] =="0" ? "" : qos_rule_obj.localPortStart[idx]);
				$('#sToPort').val(qos_rule_obj.localPortEnd[idx]=="0" ? "" : qos_rule_obj.localPortEnd[idx]);
				break;
			case "2":
				set_selectIndex('UDP', $("#protocol")[0]);
				protocolChange();
				$('#sFromPort').val(qos_rule_obj.localPortStart[idx] =="0" ? "" : qos_rule_obj.localPortStart[idx]);
				$('#sToPort').val(qos_rule_obj.localPortEnd[idx]=="0" ? "" : qos_rule_obj.localPortEnd[idx]);
				break;
			case "4":
				set_selectIndex('ICMP', $("#protocol")[0]);
				protocolChange();				
				break;
			case "6":
				set_selectIndex('Application', $("#protocol")[0]);
				protocolChange();
				set_selectIndex(qos_rule_obj.appname[idx], $("#layer7")[0]);
				//$('#layer7').val(qos_rule_obj.appname[idx]);				
				break;
			case "7":
				set_selectIndex('MAC', $("#protocol")[0]);
				protocolChange();
				$('#mac_address').val(qos_rule_obj.mac[idx]);				
				break;
		}
		set_selectIndex(qos_rule_obj.groupAttr[idx], $("#af_index")[0]);
		
	}

	function protocolChange()
	{
		$('#macAddress').hide();
		$('#portRangeTR1').hide();
		$('#portRangeTR2').hide();
		$('#appTR').hide();
		
		if($('#protocol').val() == "MAC")
			$('#macAddress').show();
		else if($('#protocol').val() == "TCP" || $('#protocol').val() == "UDP")
		{
			$('#portRangeTR1').hide();
			$('#portRangeTR2').show();
		}
		else if($('#protocol').val() == "ICMP")
			return;
		else if($('#protocol').val() == "Application")
			$('#appTR').show();
	}
	
	function layer7Change(index)
	{
		$('#Layer7Intro').html(QoSappl[index].desc);
	}

	function checkInjection(str)
	{
		var len = str.length;
		for (var i=0; i<str.length; i++) {
			if ( str.charAt(i) == '\r' || str.charAt(i) == '\n' || str.charAt(i) == ';' || str.charAt(i) == ','){
				return false;
			}else
				continue;
		}
		return true;
	}

	function send_request()
	{
		if(!checkdata())
			return false;
		
		var idx = total_num+1;
		//var idx = parseInt(qos_rule_obj.name.length, 10) + 1;
		if($('#no').val() != "")
			idx = parseInt($('#no').val(), 10) + 1;
		
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('qos_basic.asp');
		var inst = '1.1.1.'+idx;
		obj.add_param_arg('trafficRule_Enable_',inst,'1');
		obj.add_param_arg('trafficRule_Name_',inst,$('#comment').val());
		obj.add_param_arg('trafficRule_GroupAttr_',inst,$('#af_index').val());
		
		if($('#protocol').val() == "MAC")
		{
			obj.add_param_arg('trafficRule_Protocol_',inst,'7');
			obj.add_param_arg('trafficRule_MACAddress_',inst,$('#mac_address').val());
			obj.add_param_arg('trafficRule_LocalPortStart_',inst,'');
			obj.add_param_arg('trafficRule_LocalPortEnd_',inst,'');
			obj.add_param_arg('trafficRule_AppName_',inst,'');
		}
		else if($('#protocol').val() == "TCP")
		{
			obj.add_param_arg('trafficRule_Protocol_',inst,'1');
			obj.add_param_arg('trafficRule_ProtoNum_',inst,'6');
			obj.add_param_arg('trafficRule_LocalPortStart_',inst,$('#sFromPort').val());
			obj.add_param_arg('trafficRule_LocalPortEnd_',inst,$('#sToPort').val());
			obj.add_param_arg('trafficRule_MACAddress_',inst,'');
			obj.add_param_arg('trafficRule_AppName_',inst,'');
		}
		else if($('#protocol').val() == "UDP")
		{
			obj.add_param_arg('trafficRule_Protocol_',inst,'2');
			obj.add_param_arg('trafficRule_ProtoNum_',inst,'17');
			obj.add_param_arg('trafficRule_LocalPortStart_',inst,$('#sFromPort').val());
			obj.add_param_arg('trafficRule_LocalPortEnd_',inst,$('#sToPort').val());
			obj.add_param_arg('trafficRule_MACAddress_',inst,'');
			obj.add_param_arg('trafficRule_AppName_',inst,'');
		}
		else if($('#protocol').val() == "Application")
		{
			obj.add_param_arg('trafficRule_Protocol_',inst,'6');
			obj.add_param_arg('trafficRule_AppName_',inst,$('#layer7').val());
			obj.add_param_arg('trafficRule_MACAddress_',inst,'');
			obj.add_param_arg('trafficRule_AppTCPPorts_',inst,QoSappl[$('#layer7>option:selected').attr('index')].tcp);
			obj.add_param_arg('trafficRule_AppUDPPorts_',inst,QoSappl[$('#layer7>option:selected').attr('index')].udp);
		}
		else if($('#protocol').val() == "ICMP")
		{
			obj.add_param_arg('trafficRule_Protocol_',inst,'4');
			obj.add_param_arg('trafficRule_ProtoNum_',inst,'1');
			obj.add_param_arg('trafficRule_MACAddress_',inst,'');
			obj.add_param_arg('trafficRule_LocalPortStart_',inst,'');
			obj.add_param_arg('trafficRule_LocalPortEnd_',inst,'');
			obj.add_param_arg('trafficRule_AppName_',inst,'');
		}
		obj.add_param_arg('trafficRule_DSCP_',inst,'-1');
		obj.add_param_arg('trafficRule_RemarkDSCP_',inst,'-1');

		obj.get_config_obj();
		window.close();
		window.opener.location.reload(true);
	}
	
	function checkdata()
	{
		var name = $('#comment').val();
		var MAC = $('#mac_address').val();
		
		if(name == "")
		{
			alert(get_words('_qos_txt38'));
			return false;
		}
		
		if(!check_name(name))
		{
			alert(get_words('_qos_txt37'));
			return false;			
		}

		if($('#protocol').val() == "MAC")
		{
			if(!check_mac(MAC))
			{
				alert(get_words('_qos_txt36'));
				return false;
			}
		}
		
		if($('#protocol').val() == "TCP" || $('#protocol').val() == "UDP")
		{
			if($('#dFromPort').val() == "" && $('#sFromPort').val() == "")
			{
				alert(get_words('_qos_txt35'));
				return false;
			}
			/*
			if(!check_integer($('#dFromPort').val(), 1, 65535))
			{
				alert("The destination port range is invalid.");
				return false;
			}
			if(!check_integer($('#dToPort').val(), 1, 65535))
			{
				alert("The destination port range is invalid.");
				return false;
			}
			*/
			if(!check_integer($('#sFromPort').val(), 1, 65535))
			{
				alert(get_words('_qos_txt34'));
				return false;
			}
			if($('#sToPort').val()!=""){
				if(!check_integer($('#sToPort').val(), 1, 65535))
				{
					alert(get_words('_qos_txt34'));
					return false;
				}
			}
			
			//var dToPort = parseInt($('#dToPort').val(), 10);
			//var dFromPort = parseInt($('#dFromPort').val(), 10);
			var sToPort = parseInt($('#sToPort').val(), 10);
			var sFromPort = parseInt($('#sFromPort').val(), 10);		
			/*
			if(dToPort && (dToPort <= dFromPort))
			{
				alert("The destination port range is invalid.");
				return false;
			}
			*/
			if(sToPort && (sToPort <= sFromPort))
			{
				alert(get_words('_qos_txt34'));
				return false;
			}
		}
		
		if($('#protocol').val() == "Application")
		{
			if($('#layer7').val() == null)
			{
				alert(get_words('_qos_txt33'));
				return false;
			}
		}
		//check dest. port conflict
		var idx = total_num+1;
		if($('#no').val() != "")
			idx = parseInt($('#no').val(), 10) + 1;
		var tcp_timeline = '';
		var udp_timeline = '';
		//add datamodel val to timeline
		for(var i=0;i<qos_rule_obj.enable.length;i++)
		{
			if(qos_rule_obj.protocolIdx[i] == "6")//Application
			{
				if(i!=(idx-1)) //非修改的那項
				{
					var tcp_ports = qos_rule_obj.appTCPPorts[i].split(',');
					for (var j=0; j<tcp_ports.length && tcp_ports!=''; j++) {
						var range = tcp_ports[j].split('-');
						tcp_timeline = add_into_timeline(tcp_timeline, range[0], range[1]);
					}
					var udp_ports = qos_rule_obj.appUDPPorts[i].split(',');
					for (var j=0; j<udp_ports.length && udp_ports!=''; j++) {
						var range = udp_ports[j].split('-');
						udp_timeline = add_into_timeline(udp_timeline, range[0], range[1]);
					}
				}
			}
			if(qos_rule_obj.protocolIdx[i] == "1")//TCP
			{
				if(i!=(idx-1)) //非修改的那項
				{
					if(qos_rule_obj.remotePortStart[i]!=''&&qos_rule_obj.remotePortStart[i]!=0)
						tcp_timeline = add_into_timeline(tcp_timeline, qos_rule_obj.remotePortStart[i], qos_rule_obj.remotePortEnd[i]);
				}
			}
			if(qos_rule_obj.protocolIdx[i] == "2")//UDP
			{
				if(i!=(idx-1)) //非修改的那項
				{
					if(qos_rule_obj.remotePortStart[i]!=''&&qos_rule_obj.remotePortStart[i]!=0)
						udp_timeline = add_into_timeline(udp_timeline, qos_rule_obj.remotePortStart[i], qos_rule_obj.remotePortEnd[i]);
				}
			}
		}
		if($('#protocol').val() == "Application")
		{
			//add user edit val to timeline
			var tcp_ports = QoSappl[$('#layer7>option:selected').attr('index')].tcp.split(',');
			for (var j=0; j<tcp_ports.length && tcp_ports!=''; j++) {
				var range = tcp_ports[j].split('-');
				tcp_timeline = add_into_timeline(tcp_timeline, range[0], range[1]);
			}
			var udp_ports = QoSappl[$('#layer7>option:selected').attr('index')].udp.split(',');
			for (var j=0; j<udp_ports.length && udp_ports!=''; j++) {
				var range = udp_ports[j].split('-');
				udp_timeline = add_into_timeline(udp_timeline, range[0], range[1]);
			}
			
			//check port conflict
			if (check_timeline(tcp_timeline) == false) {
				alert(addstr(get_words('ag_conflict5'), 'TCP', QoSappl[$('#layer7>option:selected').attr('index')].tcp));
				return false;
			}
			
			if (check_timeline(udp_timeline) == false) {
				alert(addstr(get_words('ag_conflict5'), 'UDP', QoSappl[$('#layer7>option:selected').attr('index')].udp));
				return false;
			}
		}
		
		//check src ports
		var src_tcp_timeline = '';
		var src_udp_timeline = '';
		for(var i=0;i<qos_rule_obj.enable.length;i++)
		{
			if(qos_rule_obj.protocolIdx[i] == "1")//TCP
			{
				if(i!=(idx-1)) //非修改的那項
				{
					if(qos_rule_obj.localPortStart[i]!=''&&qos_rule_obj.localPortStart[i]!=0)
						src_tcp_timeline = add_into_timeline(src_tcp_timeline, qos_rule_obj.localPortStart[i], qos_rule_obj.localPortEnd[i]);
				}
			}
			if(qos_rule_obj.protocolIdx[i] == "2")//UDP
			{
				if(i!=(idx-1)) //非修改的那項
				{
					if(qos_rule_obj.localPortStart[i]!=''&&qos_rule_obj.localPortStart[i]!=0)
						src_udp_timeline = add_into_timeline(src_udp_timeline, qos_rule_obj.localPortStart[i], qos_rule_obj.localPortEnd[i]);
				}
			}
		}
		if($('#protocol').val() == "TCP")
		{
			src_tcp_timeline = add_into_timeline(src_tcp_timeline, $('#sFromPort').val(), $('#sToPort').val());
		}
		if($('#protocol').val() == "UDP")
		{
			src_udp_timeline = add_into_timeline(src_udp_timeline, $('#sFromPort').val(), $('#sToPort').val());
		}
		if (check_timeline(src_tcp_timeline) == false) {
			if($('#protocol').val() == "TCP")
				alert(addstr(get_words('ag_conflict5'), 'TCP', ($('#sToPort').val()==''?$('#sFromPort').val():$('#sFromPort').val()+'-'+$('#sToPort').val())));
			return false;
		}
		if (check_timeline(src_udp_timeline) == false) {
			if($('#protocol').val() == "UDP")
				alert(addstr(get_words('ag_conflict5'), 'UDP', ($('#sToPort').val()==''?$('#sFromPort').val():$('#sFromPort').val()+'-'+$('#sToPort').val())));
			return false;
		}
		return true;
	}

	
</script>
</head>
<body>
<table align="center" width="540" border="0" cellpadding="0" cellspacing="0">	
	<table align="center" width="499">
	<tr>
		<td align="right"><script>show_words('_qos_txt22')</script> : [<script>show_words('_basic')</script>] / <a id="qos_link" href=""><font color="blue">[<script>show_words('_qos_txt23')</script>]</font></a></td>
	</tr>
	</table>
	<tr>		
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td width="78%" valign="top"><table width="98%" border="0" cellpadding="0" cellspacing="0" class="tabBigTitle">		
		<td valign="top">
            <form id="form1" name="form1" method="post" action="">
            <input type="hidden" id="html_response_page" name="html_response_page" value="reboot.asp" />
            <input type="hidden" id="html_response_message" name="html_response_message" value="" />
			<script>$("#html_response_message").val(get_words('sc_intro_sv'));</script>
            <input type="hidden" id="html_response_return_page" name="html_response_return_page" value="qos_basic.asp" />
            <input type="hidden" id="reboot_type" name="reboot_type" value="qos" />
			<input type="hidden" id="del" name="del" value="-1" />
			<input type="hidden" id="edit" name="edit" value="-1" />
			<input type="hidden" id="max_row" name="max_row" value="-1" />
			<p>
			<input type="hidden" name="dp_index" id="dp_index" value="1" />
			<div class="box_tn">
			<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0">
			<tr><td colspan="2" class="CT"><script>show_words('_qos_txt24')</script></td></tr>
			<tr>
				<td align="right" class="CL"><script>show_words('_name')</script></td>
				<td class="CR">
					<input type="text" name="comment" id="comment" size="16" maxlength="32" />
				</td>
			</tr>
			<tr>
				<td align="right" class="CL"><script>show_words('_qos_txt16')</script></td>
				<td class="CR">
					<select onChange="protocolChange()" name="protocol" id="protocol">
					<option value="MAC"><script>show_words('_macaddr')</script></option>
					<option value="TCP"><script>show_words('_TCP')</script></option>
					<option value="UDP"><script>show_words('_UDP')</script></option>
					<option value="ICMP" selected><script>show_words('_ICMP')</script></option>
					<option value="Application"><script>show_words('_app')</script></option>
					</select>&nbsp;&nbsp;
				</td>
			</tr>
			<tr>
				<td align="right" class="CL"><script>show_words('_qos_txt21')</script></td>
				<td class="CR">
					<span id="group_list"></span>
				</td>
			</tr>
			<tr style="display:none" id="macAddress">
				<td align="right" class="CL"><script>show_words('_macaddr')</script></td>
				<td class="CR">
					<input type="text" size="18" name="mac_address" id="mac_address" />
				</td>
			</tr>
			<tr style="display:none" id="destIPaddress">
				<td align="right" class="CL"><script>show_words('_qos_txt25')</script></td>
				<td class="CR">
					<input type="text" size="16" name="dip_address" id="dip_address" />
				</td>
			</tr>
			<tr style="display:none" id="sourceIPaddress">
				<td align="right" class="CL"><script>show_words('_qos_txt26')</script></td>
				<td class="CR">
					<input type="text" size="16" name="sip_address" id="sip_address" />
				</td>
			</tr>
			<tr style="display:none" id="packetLength">
				<td align="right" class="CL"><script>show_words('_qos_txt27')</script></td>
				<td class="CR">
					<input type="text" size="4" name="pktlenfrom" id="pktlenfrom" /> -
					<input type="text" size="4" name="pktlento" id="pktlento" />
					<font color="#808080" id=""><script>show_words('_qos_txt28')</script></font>
				</td>
			</tr>
			<tr style="display:none">
				<td align="right" class="CL"><script>show_words('_qos_txt29')</script></td>
				<td class="CR">
					<select name="dscp" id="dscp">
					<option value="-1" selected></option>
					<option value="0">BE (Default)</option>
					<option value="10">AF11</option>
					<option value="12">AF12</option>
					<option value="14">AF13</option>
					<option value="18">AF21</option>
					<option value="20">AF22</option>
					<option value="22">AF23</option>
					<option value="26">AF31</option>
					<option value="28">AF32</option>
					<option value="30">AF33</option>
					<option value="34">AF41</option>
					<option value="36">AF42</option>
					<option value="38">AF43</option>
					<option value="46">EF</option>
					</select>&nbsp;&nbsp;
				</td>
			</tr>
			<tr style="display:none" id="portRangeTR1">
				<td align="right" class="CL"><script>show_words('_qos_txt30')</script></td>
				<td class="CR">
					<input type="text" size="5" name="dFromPort" id="dFromPort" />-
					<input type="text" size="5" name="dToPort" id="dToPort" />
				</td>
			</tr>
			<tr style="display:none" id="portRangeTR2">
				<td align="right" class="CL"><script>show_words('_qos_txt31')</script></td>
				<td class="CR">
					<input type="text" size="5" name="sFromPort" id="sFromPort" />-
					<input type="text" size="5" name="sToPort" id="sToPort" />
				</td>
			</tr>
			<tr style="display:none" id="appTR">
				<td align="right" class="CL"><script>show_words('_app')</script></td>
				<td class="CR">
					<span id="app_list"></span>	
					<br>
					<span id="Layer7Intro"></span>
				</td>
			</tr>
			<tr style="display:none" id="remarkDSCP">
				<td align="right" class="CL"><script>show_words('_qos_txt32')</script>:</td>
				<td class="CR">
					<select name="remark_dscp" id="remark_dscp">
					<option value="-1" id="QoSClassifierAutoStr" selected><script>show_words('KR50')</script></option>
					<option value="0">BE (000000)</option>
					<option value="10">AF11</option>
					<option value="12">AF12</option>
					<option value="14">AF13</option>
					<option value="18">AF21</option>
					<option value="20">AF22</option>
					<option value="22">AF23</option>
					<option value="26">AF31</option>
					<option value="28">AF32</option>
					<option value="30">AF33</option>
					<option value="34">AF41</option>
					<option value="36">AF42</option>
					<option value="38">AF43</option>
					<option value="46">EF (101110)</option>
					</select>&nbsp;&nbsp;
				</td>
			</tr>
			</table>
			</div>
			<div class="box_tn">
			<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0">
			<input type="hidden" name="mode" id="mode" value="" />
			<input type="hidden" name="no" id="no" value="" />
			<tr>
				<td align="center" class="btn_field">
					<input name="add1" type="button" class="ButtonSmall" id="add1" onClick="return send_request()" value="" />
					<script>$('#add1').val(get_words('_adv_txt_17'));</script>
				</td>
			</tr>			
			</table>
			</div>
			</form>
			
		</td>
		</tr>
    </table></td>
		</tr>
		</table></td>		
	</tr>
</table>
</body>
<script>
onPageLoad();

</script>
</html>
