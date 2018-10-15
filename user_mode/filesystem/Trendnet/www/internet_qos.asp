<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
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
	main.add_param_arg('IGD_WANDevice_i_TrafficControl_',1110);
	main.add_param_arg('IGD_WANDevice_i_TrafficControl_Rule_i_',1110);
	main.add_param_arg('IGD_WANDevice_i_',1100);
	main.add_param_arg('IGD_Firewall_',1100);
	main.add_param_arg('IGD_WANDevice_i_TrafficControl_UPGroup_i_',1110);
	
	main.get_config_obj();
	
	var hw_nat_enable = main.config_val("wanDev_HardwareNatEnable_");
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
	
	var array_enable 			= new Array();
	var array_name 				= new Array();
	var array_priority 			= new Array();
	var array_localIPStart 		= new Array();
	var array_localIPEnd 		= new Array();
	var array_remoteIPStart	 	= new Array();
	var array_remoteIPEnd 		= new Array();
	var array_localPortStart 	= new Array();
	var array_localPortEnd		= new Array();
	var array_remotePortStart 	= new Array();
	var array_remotePortEnd 	= new Array();
	var array_appTCPPorts 		= new Array();
	var array_appUDPPorts 		= new Array();
	var array_protocolIdx		= new Array();
	var array_protocalNum		= new Array();
	var array_mac				= new Array();
	var array_dscp				= new Array();
	var array_appname			= new Array();
	var array_groupAttr			= new Array();
	var array_pkLStart			= new Array();
	var array_pkLEnd			= new Array();
	var array_Remarkdscp		= new Array();
	
	var def_enable 				= new Array("1","1","1","1","1","1","1","1","1","1","1","1","1");
	var def_name 				= new Array("ICMP_HIGH","VoIP_H323_HIGH","VoIP_SIP_HIGH","VoIP_Skype1_HIGH","VoIP_Skype2_HIGH","RTP_HIGH",
											"SSH_HIGH","MSN_Messenger_MIDDLE","Yahoo_MIDDLE","PoP3_LOW","SMTP_LOW","P2P_eMule_LOW","P2P_BT_LOW");
	var def_priority 			= new Array("","","","","","","","","","","","","");
	var def_localIPStart 		= new Array("","","","","","","","","","","","","");
	var def_localIPEnd 			= new Array("","","","","","","","","","","","","");
	var def_remoteIPStart	 	= new Array("","","","","","","","","","","","","");
	var def_remoteIPEnd 		= new Array("","","","","","","","","","","","","");
	var def_localPortStart	 	= new Array("","","","","","","","","","","","","");
	var def_localPortEnd		= new Array("","","","","","","","","","","","","");
	var def_remotePortStart 	= new Array("","","","","","","","","","","","","");
	var def_remotePortEnd 		= new Array("","","","","","","","","","","","","");
	var def_appTCPPorts 		= new Array("","1720","5060,5061","","","","22","1863","5050","110","25","4662","6881-6889");
	var def_appUDPPorts 		= new Array("","","5060,5061","1023","","5004","","","","","25","12155","");
	var def_protocolIdx			= new Array("4","6","6","6","6","6","6","6","6","6","6","6","6");
	var def_protocalNum			= new Array("1","","","","","","","","","","","","");
	var def_mac					= new Array("","","","","","","","","","","","","");
	var def_dscp				= new Array("-1","-1","-1","-1","-1","-1","-1","-1","-1","-1","-1","-1","-1");
	var def_appname				= new Array("","h323","sip","skypeout","skypetoskype","rtp","ssh","msnmessenger","yahoo","pop3","smtp","edonkey","bittorrent");
	var def_groupAttr			= new Array("30","30","30","30","30","30","30","20","20","10","10","10","10");
	var def_pkLStart			= new Array("","","","","","","","","","","","","");
	var def_pkLEnd				= new Array("","","","","","","","","","","","","");
	var def_Remarkdscp			= new Array("46","46","46","46","46","46","46","18","18","10","10","10","10");

	var submit_button_flag = 0;
	var rule_max_num = 10;
	var DataArray = new Array();
	var TotalCnt=0;
	
	var uploadBandwidth_custom = "k";
	var downloadBandwidth_custom = "k";
	var QOS_MAX_HARD_LIMITED = "100M";	// Our max bandwidth that we can deal with.
	var QOS_MIN_HARD_LIMITED = "32k";	// Our min bandwidth that we hope.

	function onPageLoad()
	{
		set_selectIndex(qos_obj.enable, $("#QoSSelect")[0]);
		set_selectIndex(qos_obj.upload, $("#UploadBandwidth")[0]);
		if($("#UploadBandwidth").val() == "0") // 0 => custom
			uploadBandwidth_custom = qos_obj.upload=="0" ? "k" : (qos_obj.upload+"k");
		QoSSelectChange();
	
		//QoS Group
		var content = "";
		content += '<div class="box_tn" id="qos_group">';
		content += '<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0">';
		content += '<tr><td colspan="2" class="CT">'+get_words('_help_txt108')+'</td></tr>';
		content += '<tr><td class="CTS" id="QoSGroupStr"><b>'+get_words('_qos_txt21')+'</b></td>';
		content += '<td class="CTS" id="QoSGroupAttrStr"><b>'+get_words('_qos_txt20')+'</b></td>';
		content += '<td class="CTS" style="display:none;"><b>'+get_words('_edit')+'</b></td></tr>';
		
		for(var i=0; i<upload_group.name.length; i++)
		{
			content += "<tr>";
			content += "<td class=\"CL\">" + upload_group.name[i] + "(" + get_words('tf_Upload') + ")</td>";
			content += "<td class=\"CR\">" + addstr(get_words('_qos_txt19'),upload_group.attr[i]) +"</td>";
			content += "<td style=\"display:none;\"></td></tr>";
		}
		content += "</table></div>";
		$("#group_list").html(content);
	
		//QoS List
		paintTable();
		
		//hide or show content
		displayContent();
    }

	
	function paintTable()
	{	
		var contain = '<div class="box_tn" id="qos_rules">';
			contain += '<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" id="table1">';
			contain += '<tr><td colspan="6" class="CT">'+get_words('_help_txt116')+'</td></tr>';
			contain += '<tr align="center">';
			contain += '<td class="CELL"><input type=checkbox id=checkall name=checkall onclick="checkallbox();" /></td>';
			contain += '<td class="CELL"><b>'+get_words('_item_no')+'</b></td>';
			contain += '<td class="CELL"><b>'+get_words('_name')+'</b></td>';
			contain += '<td class="CELL"><b>'+get_words('_qos_txt18')+'.</b></td>';			
			contain += '<td class="CELL"><b>'+get_words('_qos_txt17')+'.</b></td>';
			contain += '<td class="CELL"><b>'+get_words('_edit')+'</b></td>';
			contain += '</tr>';
			
			
		for(var i=0; i<qos_rule_obj.name.length; i++)
		{
		if(qos_rule_obj.name[i] != "")
		{	
			var desc = "";
			
			contain += "<tr align=\"center\">"+
						"<td class=\"CELL\"><input type=checkbox id="+i+" name="+i+" />"+
						"</td><td class=\"CELL\">" + (i+1) +
						"</td><td class=\"CELL\">" + qos_rule_obj.name[i];
			for(var j=0; j<upload_group.name.length; j++)
			{
				if(qos_rule_obj.groupAttr[i] == upload_group.attr[j])
					contain += "</td><td class=\"CELL\">" + upload_group.name[j] +"(" + get_words('tf_Upload') + ")";
			}
			
			switch (qos_rule_obj.protocolIdx[i])
			{
				case "1":
					desc += get_words('_qos_txt16') + ": " + get_words('_TCP') + "<br>";
					break;
				case "2":
					desc += get_words('_qos_txt16') + ": " + get_words('_UDP') + "<br>";
					break;
				case "4":
					desc += get_words('_qos_txt16') + ": " + get_words('_ICMP') + "<br>";
					break;
				case "6":
					desc += get_words('_qos_txt16') + ": " + get_words('_app') + "<br>";
					break;
			}
				
			if(qos_rule_obj.mac[i] != "")
				desc += get_words('_macaddr') + ": " + qos_rule_obj.mac[i] + "<br>";
			
			if(qos_rule_obj.localPortStart[i] != "0" && qos_rule_obj.localPortEnd[i] != "0")
				desc += get_words('_qos_txt15') + ": " + qos_rule_obj.localPortStart[i] + " - " + qos_rule_obj.localPortEnd[i] + "<br>";
			else if(qos_rule_obj.localPortStart[i] != "0")
				desc += get_words('_qos_txt14') + ": " + qos_rule_obj.localPortStart[i] + "<br>";
			
			if(qos_rule_obj.appname[i] != "")
				desc += get_words('_app') + ": " + qos_rule_obj.appname[i] + "<br>";
			
			contain += "</td><td class=\"CELL\">" + desc;
			contain += "</td><td class=\"CELL\"><a href=\"javascript:EditRule("+ i +")\"><img src=\"edit.gif\" border=\"0\" title=\""+get_words('_edit')+"\" /></a>";
			contain += "</td></tr>"
		}
		}
		
		contain += '<tr align="left" style="background-color: #4f5158;"><td colspan="6">';
		contain += '<input name="add1" type="button" class="ButtonSmall" id="add1" onClick="return AddRule()" value="'+get_words('_add')+'" />';
		contain += '<input name="delete" type="button" class="ButtonSmall" id="delete" onClick="return DelRule()" value="'+get_words('_delete')+'" />';
		contain += '</td></tr>';
		contain += '</table></div>';
		$('#VSTable').html(contain);
		
		//set_form_default_values("form1");
	}
	
	
	function QoSSetupCheck()
	{
		var value;
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('internet_qos.asp');
		
		obj.add_param_arg('wanTrafficShp_EnableTrafficShaping_','1.1.1.0',$('#QoSSelect').val());
		
		if($('#QoSSelect').val() == "1" || $('#QoSSelect').val() == "2")
		{
			if($('#UploadBandwidth').val() == "0"){ // 0 => custom
				if($('#UploadBandwidth_Custom').val() == "")
				{
					alert(get_words('_qos_txt13'));
					return false;
				}
				
				value = $('#UploadBandwidth_Custom').val().toLowerCase();
				if((value.indexOf('k') == -1 && value.indexOf('m') == -1) || !isNaN(value)){
					alert(get_words('_qos_txt12'));
					return false;
				}
				if(value.length <= 1){
					alert(get_words('_qos_txt12'));
						return false;
				}
				if((value.indexOf('k') != -1 || value.indexOf('m') != -1) && isNaN(value.substr(0,value.length-1))){
					alert(get_words('_qos_txt12'));
					return false;
				}
				if(getTrueValue(value) > getTrueValue(QOS_MAX_HARD_LIMITED)){
					alert(get_words('_qos_txt10') + "(" + get_words('_qos_txt11') + QOS_MAX_HARD_LIMITED + ")");
					return false;
				}
				if(getTrueValue(value) < getTrueValue(QOS_MIN_HARD_LIMITED)){
					var ret = confirm(get_words('_qos_txt09'));
					if(ret == false)
						return false;
				}
				//20130307 pascal add for saving speed in interger value
				var custom;
				if(value.indexOf('k') != -1)
					custom = parseInt(value.split("k")[0], 10);
				else if(value.indexOf('m') != -1)
					custom = 1024*(parseInt(value.split("m")[0], 10));
					
				obj.add_param_arg('wanTrafficShp_UploadBandWidth_','1.1.1.0',custom);
				//obj.add_param_arg('wanTrafficShp_UploadBandWidth_','1.1.1.0',$('#UploadBandwidth_Custom').val());
			}
			else
				obj.add_param_arg('wanTrafficShp_UploadBandWidth_','1.1.1.0',$('#UploadBandwidth').val());
		}
		var paramStr = obj.get_param();
		
		totalWaitTime = 10; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(paramStr.url, paramStr.arg);
	}
	
	function QoSSelectChange()
	{
		if($('#QoSSelect').val() == "0")
		{
			$('#UploadBandwidth').attr('disabled',true);
			$('#DownloadBandwidth').attr('disabled',true);
			$('#UploadBandwidth_Custom').attr('disabled',true);
			$('#DownloadBandwidth_Custom').attr('disabled',true);
		}
		else if($('#QoSSelect').val() == "1" || $('#QoSSelect').val() == "2")
		{
			$('#UploadBandwidth').attr('disabled',false);
			$('#DownloadBandwidth').attr('disabled',false);
			$('#UploadBandwidth_Custom').attr('disabled',false);
			$('#DownloadBandwidth_Custom').attr('disabled',false);
			UploadBWChange(); //LoadUploadBW();
			DownloadBWChange(); //LoadDownloadBW();
			
		}
		
	}
	
	function displayContent()
	{
		if($('#QoSSelect').val() == "0")
		{
			$('#qos_group').hide();
			$('#qos_rules').hide();
			$('#qos_restore').hide();
		}
		else if($('#QoSSelect').val() == "1" || $('#QoSSelect').val() == "2")
		{
			$('#qos_group').show();
			$('#qos_rules').show();
			$('#qos_restore').show();
		}
	}

	function UploadBWChange()
	{
		if($('#UploadBandwidth').val() == "0") // 0 => custom
		{
			$('#UploadBandwidth_Custom').val(uploadBandwidth_custom);
			$('#UploadBandwidth_Custom').show();
		}
		else
			$('#UploadBandwidth_Custom').hide();
	}

	function DownloadBWChange()
	{
		if($('#DownloadBandwidth').val() == "0") // 0 => custom
		{
			$('#DownloadBandwidth_Custom').val(downloadBandwidth_custom);
			$('#DownloadBandwidth_Custom').show();
		}
		else
			$('#DownloadBandwidth_Custom').hide();
	}
	
	function getTrueValue(str)
	{
		var rc;
		rc = parseInt(str);
		if(str.charAt(str.length-1) == 'k' || str.charAt(str.length-1) == 'K')
			rc = rc * 1024;
		else if(str.charAt(str.length-1) == 'm' || str.charAt(str.length-1) == 'M')
			rc = rc * 1024 * 1024;
		return rc;
	}
	
	function checkallbox()
	{
		var check= $('#checkall').attr('checked');
		for (var i=0; i<qos_rule_obj.name.length; i++)
			$('#'+i).attr('checked', check);
	}

	function AddRule()
	{
		funcWinOpen("qos_basic.asp", "Add", "toolbar=no, location=yes, scrollbars=yes, resizable=yes, width=600, height=700");	
	}
	
	function EditRule(index)
	{
		funcWinOpen("qos_basic.asp?mode=edit&info="+index, "Edit", "toolbar=no, location=yes, scrollbars=yes, resizable=yes, width=600, height=700");
	}
	
	function DelRule()
	{
		for(var i=0; i<qos_rule_obj.name.length; i++)
		{
			if(!$('#'+i).attr('checked')) //not checked
			{
				/*
				** Date:	2013-03-19
				** Author:	Moa Chung
				** Reason:	fixed bug, it will be enable=0 when rule deleted.
				** Note:	none
				**/
				array_enable[array_enable.length++] = qos_rule_obj.enable[i];
				array_name[array_name.length++] = qos_rule_obj.name[i];
				array_priority[array_priority.length++] = qos_rule_obj.priority[i];
				array_localIPStart[array_localIPStart.length++] = qos_rule_obj.localIPStart[i];
				array_localIPEnd[array_localIPEnd.length++] = qos_rule_obj.localIPEnd[i];
				array_remoteIPStart[array_remoteIPStart.length++] = qos_rule_obj.remoteIPStart[i];
				array_remoteIPEnd[array_remoteIPEnd.length++] = qos_rule_obj.remoteIPEnd[i];
				array_localPortStart[array_localPortStart.length++] = qos_rule_obj.localPortStart[i];
				array_localPortEnd[array_localPortEnd.length++] = qos_rule_obj.localPortEnd[i];
				array_remotePortStart[array_remotePortStart.length++] = qos_rule_obj.remotePortStart[i];
				array_remotePortEnd[array_remotePortEnd.length++] = qos_rule_obj.remotePortEnd[i];
				array_appTCPPorts[array_appTCPPorts.length++] = qos_rule_obj.appTCPPorts[i];
				array_appUDPPorts[array_appUDPPorts.length++] = qos_rule_obj.appUDPPorts[i];
				array_protocolIdx[array_protocolIdx.length++] = qos_rule_obj.protocolIdx[i];
				array_protocalNum[array_protocalNum.length++] = qos_rule_obj.protocalNum[i];
				array_mac[array_mac.length++] = qos_rule_obj.mac[i];
				array_dscp[array_dscp.length++] = qos_rule_obj.dscp[i];
				array_appname[array_appname.length++] = qos_rule_obj.appname[i];
				array_groupAttr[array_groupAttr.length++] = qos_rule_obj.groupAttr[i];
				array_pkLStart[array_pkLStart.length++] = qos_rule_obj.pkLStart[i];
				array_pkLEnd[array_pkLEnd.length++] = qos_rule_obj.pkLEnd[i];
				array_Remarkdscp[array_Remarkdscp.length++] = qos_rule_obj.Remarkdscp[i];
			}
		}
		
		send_request();
	}
	
	function send_request()
	{
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('internet_qos.asp');
		
		for(var i=1; i<=array_name.length; i++)
		{
			obj.add_param_arg('trafficRule_Enable_','1.1.1.'+i,'1');
			obj.add_param_arg('trafficRule_Name_','1.1.1.'+i,array_name[i-1]);
			obj.add_param_arg('trafficRule_Priority_','1.1.1.'+i,array_priority[i-1]);
			obj.add_param_arg('trafficRule_LocalIPAddrStart_','1.1.1.'+i,array_localIPStart[i-1]);
			obj.add_param_arg('trafficRule_LocalIPAddrEnd_','1.1.1.'+i,array_localIPEnd[i-1]);
			obj.add_param_arg('trafficRule_RemoteIPAddrStart_','1.1.1.'+i,array_remoteIPStart[i-1]);
			obj.add_param_arg('trafficRule_RemoteIPAddrEnd_','1.1.1.'+i,array_remoteIPEnd[i-1]);
			obj.add_param_arg('trafficRule_LocalPortStart_','1.1.1.'+i,array_localPortStart[i-1]);
			obj.add_param_arg('trafficRule_LocalPortEnd_','1.1.1.'+i,array_localPortEnd[i-1]);
			obj.add_param_arg('trafficRule_RemotePortStart_','1.1.1.'+i,array_remotePortStart[i-1]);
			obj.add_param_arg('trafficRule_RemotePortEnd_','1.1.1.'+i,array_remotePortEnd[i-1]);
			obj.add_param_arg('trafficRule_AppTCPPorts_','1.1.1.'+i,array_appTCPPorts[i-1]);
			obj.add_param_arg('trafficRule_AppUDPPorts_','1.1.1.'+i,array_appUDPPorts[i-1]);
			obj.add_param_arg('trafficRule_Protocol_','1.1.1.'+i,array_protocolIdx[i-1]);
			obj.add_param_arg('trafficRule_ProtoNum_','1.1.1.'+i,array_protocalNum[i-1]);
			obj.add_param_arg('trafficRule_MACAddress_','1.1.1.'+i,array_mac[i-1]);
			obj.add_param_arg('trafficRule_DSCP_','1.1.1.'+i,array_dscp[i-1]);
			obj.add_param_arg('trafficRule_AppName_','1.1.1.'+i,array_appname[i-1]);
			obj.add_param_arg('trafficRule_GroupAttr_','1.1.1.'+i,array_groupAttr[i-1]);
			obj.add_param_arg('trafficRule_PacketLenStart_','1.1.1.'+i,array_pkLStart[i-1]);
			obj.add_param_arg('trafficRule_PacketLenEnd_','1.1.1.'+i,array_pkLEnd[i-1]);
			obj.add_param_arg('trafficRule_RemarkDSCP_','1.1.1.'+i,array_Remarkdscp[i-1]);
		}
		for(var j=array_name.length+1; j<=qos_rule_obj.name.length; j++)
		{
			obj.add_param_arg('trafficRule_Enable_','1.1.1.'+j,'0');
			obj.add_param_arg('trafficRule_Name_','1.1.1.'+j,'');
			obj.add_param_arg('trafficRule_Priority_','1.1.1.'+j,'');
			obj.add_param_arg('trafficRule_LocalIPAddrStart_','1.1.1.'+j,'');
			obj.add_param_arg('trafficRule_LocalIPAddrEnd_','1.1.1.'+j,'');
			obj.add_param_arg('trafficRule_RemoteIPAddrStart_','1.1.1.'+j,'');
			obj.add_param_arg('trafficRule_RemoteIPAddrEnd_','1.1.1.'+j,'');
			obj.add_param_arg('trafficRule_LocalPortStart_','1.1.1.'+j,'');
			obj.add_param_arg('trafficRule_LocalPortEnd_','1.1.1.'+j,'');
			obj.add_param_arg('trafficRule_RemotePortStart_','1.1.1.'+j,'');
			obj.add_param_arg('trafficRule_RemotePortEnd_','1.1.1.'+j,'');
			obj.add_param_arg('trafficRule_AppTCPPorts_','1.1.1.'+j,'');
			obj.add_param_arg('trafficRule_AppUDPPorts_','1.1.1.'+j,'');
			obj.add_param_arg('trafficRule_Protocol_','1.1.1.'+j,'');
			obj.add_param_arg('trafficRule_ProtoNum_','1.1.1.'+j,'');
			obj.add_param_arg('trafficRule_MACAddress_','1.1.1.'+j,'');
			obj.add_param_arg('trafficRule_DSCP_','1.1.1.'+j,'');
			obj.add_param_arg('trafficRule_AppName_','1.1.1.'+j,'');
			obj.add_param_arg('trafficRule_GroupAttr_','1.1.1.'+j,'');
			obj.add_param_arg('trafficRule_PacketLenStart_','1.1.1.'+j,'');
			obj.add_param_arg('trafficRule_PacketLenEnd_','1.1.1.'+j,'');
			obj.add_param_arg('trafficRule_RemarkDSCP_','1.1.1.'+j,'');
		}
		var paramStr = obj.get_param();
		
		totalWaitTime = 10; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(paramStr.url, paramStr.arg);
	}
	
	function restoret()
	{
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('internet_qos.asp');
		
		for(var i=1; i<=def_name.length; i++)
		{
			obj.add_param_arg('trafficRule_Enable_','1.1.1.'+i,'1');
			obj.add_param_arg('trafficRule_Name_','1.1.1.'+i,def_name[i-1]);
			obj.add_param_arg('trafficRule_Priority_','1.1.1.'+i,def_priority[i-1]);
			obj.add_param_arg('trafficRule_LocalIPAddrStart_','1.1.1.'+i,def_localIPStart[i-1]);
			obj.add_param_arg('trafficRule_LocalIPAddrEnd_','1.1.1.'+i,def_localIPEnd[i-1]);
			obj.add_param_arg('trafficRule_RemoteIPAddrStart_','1.1.1.'+i,def_remoteIPStart[i-1]);
			obj.add_param_arg('trafficRule_RemoteIPAddrEnd_','1.1.1.'+i,def_remoteIPEnd[i-1]);
			obj.add_param_arg('trafficRule_LocalPortStart_','1.1.1.'+i,def_localPortStart[i-1]);
			obj.add_param_arg('trafficRule_LocalPortEnd_','1.1.1.'+i,def_localPortEnd[i-1]);
			obj.add_param_arg('trafficRule_RemotePortStart_','1.1.1.'+i,def_remotePortStart[i-1]);
			obj.add_param_arg('trafficRule_RemotePortEnd_','1.1.1.'+i,def_remotePortEnd[i-1]);
			obj.add_param_arg('trafficRule_AppTCPPorts_','1.1.1.'+i,def_appTCPPorts[i-1]);
			obj.add_param_arg('trafficRule_AppUDPPorts_','1.1.1.'+i,def_appUDPPorts[i-1]);
			obj.add_param_arg('trafficRule_Protocol_','1.1.1.'+i,def_protocolIdx[i-1]);
			obj.add_param_arg('trafficRule_ProtoNum_','1.1.1.'+i,def_protocalNum[i-1]);
			obj.add_param_arg('trafficRule_MACAddress_','1.1.1.'+i,def_mac[i-1]);
			obj.add_param_arg('trafficRule_DSCP_','1.1.1.'+i,def_dscp[i-1]);
			obj.add_param_arg('trafficRule_AppName_','1.1.1.'+i,def_appname[i-1]);
			obj.add_param_arg('trafficRule_GroupAttr_','1.1.1.'+i,def_groupAttr[i-1]);
			obj.add_param_arg('trafficRule_PacketLenStart_','1.1.1.'+i,def_pkLStart[i-1]);
			obj.add_param_arg('trafficRule_PacketLenEnd_','1.1.1.'+i,def_pkLEnd[i-1]);
			obj.add_param_arg('trafficRule_RemarkDSCP_','1.1.1.'+i,def_Remarkdscp[i-1]);
		}
		for(var j=def_name.length+1; j<=qos_rule_obj.name.length; j++)
		{
			obj.add_param_arg('trafficRule_Enable_','1.1.1.'+i,'0');
			obj.add_param_arg('trafficRule_Name_','1.1.1.'+i,'');
			obj.add_param_arg('trafficRule_Priority_','1.1.1.'+i,'');
			obj.add_param_arg('trafficRule_LocalIPAddrStart_','1.1.1.'+i,'');
			obj.add_param_arg('trafficRule_LocalIPAddrEnd_','1.1.1.'+i,'');
			obj.add_param_arg('trafficRule_RemoteIPAddrStart_','1.1.1.'+i,'');
			obj.add_param_arg('trafficRule_RemoteIPAddrEnd_','1.1.1.'+i,'');
			obj.add_param_arg('trafficRule_LocalPortStart_','1.1.1.'+i,'');
			obj.add_param_arg('trafficRule_LocalPortEnd_','1.1.1.'+i,'');
			obj.add_param_arg('trafficRule_RemotePortStart_','1.1.1.'+i,'');
			obj.add_param_arg('trafficRule_RemotePortEnd_','1.1.1.'+i,'');
			obj.add_param_arg('trafficRule_AppTCPPorts_','1.1.1.'+i,'');
			obj.add_param_arg('trafficRule_AppUDPPorts_','1.1.1.'+i,'');
			obj.add_param_arg('trafficRule_Protocol_','1.1.1.'+i,'');
			obj.add_param_arg('trafficRule_ProtoNum_','1.1.1.'+i,'');
			obj.add_param_arg('trafficRule_MACAddress_','1.1.1.'+i,'');
			obj.add_param_arg('trafficRule_DSCP_','1.1.1.'+i,'');
			obj.add_param_arg('trafficRule_AppName_','1.1.1.'+i,'');
			obj.add_param_arg('trafficRule_GroupAttr_','1.1.1.'+i,'');
			obj.add_param_arg('trafficRule_PacketLenStart_','1.1.1.'+i,'');
			obj.add_param_arg('trafficRule_PacketLenEnd_','1.1.1.'+i,'');
			obj.add_param_arg('trafficRule_RemarkDSCP_','1.1.1.'+i,'');
		}
		var paramStr = obj.get_param();
		
		totalWaitTime = 10; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(paramStr.url, paramStr.arg);
	}
$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(model);
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
								<div class="headerbg" id="setmanTitle">
								<script>show_words('help660');</script>
								</div>
								<div class="hr"></div>
								<div class="section_content_border">
								<div class="header_desc" id="basicIntroduction">
									<script>show_words('_qos_txt01');</script>
									<p></p>
								</div>

            <form id="form1" name="form1" method="post" action="">
            <input type="hidden" id="html_response_page" name="html_response_page" value="reboot.asp" />
            <input type="hidden" id="html_response_message" name="html_response_message" value="" />
			<script>$("#html_response_message").val(get_words('sc_intro_sv'));</script>
            <input type="hidden" id="html_response_return_page" name="html_response_return_page" value="internet_qos.asp" />
            <input type="hidden" id="reboot_type" name="reboot_type" value="qos" />
			<input type="hidden" id="del" name="del" value="-1" />
			<input type="hidden" id="edit" name="edit" value="-1" />
			<input type="hidden" id="max_row" name="max_row" value="-1" />
			
<div class="box_tn">
	<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="formarea">
	<tr><td colspan="2" class="CT"><script>show_words('help660')</script></td></tr>
	<tr>
		<td class="CL"><script>show_words('help660')</script></td>
		<td class="CR">
			<select name="QoSSelect" id="QoSSelect" size="1" onChange="QoSSelectChange()">
				<option value="0" id="QoSDisableStr"><script>show_words('_disable')</script></option>
				<option value="1" id="QoSEnableStr"><script>show_words('_enable')</script></option>
				<!--<option value=2 id="QoSDSCPMakerOnlyStr">DSCP mark only</option>-->
			</select>
		</td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_qos_txt04')</script></td>
		<td class="CR">
			<select name="UploadBandwidth" id="UploadBandwidth" size="1" onChange="UploadBWChange()">
				<option value="0" id="QoSBWCustomStr"><script>show_words('_qos_txt05')</script></option>
				<option value="64">64k</option>
				<option value="96">96k</option>
				<option value="128">128k</option>
				<option value="192">192k</option>
				<option value="256">256k</option>
				<option value="384" >384k</option>
				<option value="512">512k</option>
				<option value="768">768k</option>
				<option value="1024">1M</option>
				<option value="2048">2M</option>
				<option value="4096">4M</option>
				<option value="8192">8M</option>
				<option value="10240">10M</option>
				<option value="12288">12M</option>
				<option value="16384">16M</option>
				<option value="20480">20M</option>
				<option value="24576">24M</option>
				<option value="32768">32M</option>
				<option value="40960">40M</option>
				<option value="61440">60M</option>
				<option value="81920">80M</option>
			</select>

			<input type="text" name="UploadBandwidth_Custom" id="UploadBandwidth_Custom" size="6" maxlength="8" style="display:none;" />
			<script>show_words('_qos_txt06')</script>

		</td>
	</tr>
	<tr style="display:none">
		<td class="CL"><script>show_words('_qos_txt07')</script></td>
		<td class="CR">
			<select name="DownloadBandwidth" id="DownloadBandwidth" size="1" onChange="DownloadBWChange()">
				<option value="0" id="QoSBWCustomStr2"><script>show_words('_qos_txt05')</script></option>
				<option value="64">64k</option>
				<option value="96">96k</option>
				<option value="128">128k</option>
				<option value="192">192k</option>
				<option value="256">256k</option>
				<option value="384" >384k</option>
				<option value="512">512k</option>
				<option value="768">768k</option>
				<option value="1024">1M</option>
				<option value="2048">2M</option>
				<option value="4096">4M</option>
				<option value="8192">8M</option>
				<option value="10240">10M</option>
				<option value="12288">12M</option>
				<option value="16384">16M</option>
				<option value="20480">20M</option>
				<option value="24576">24M</option>
				<option value="32768">32M</option>
				<option value="40960">40M</option>
			</select>

			<input type="text" name="DownloadBandwidth_Custom" id="DownloadBandwidth_Custom" size="6" maxlength="8" style="display:none" value="40M" />
			<script>show_words('_qos_txt06')</script>

		</td>
	</tr>
	<tr>
		<td align="left" style="background-color: #4f5158;" colspan="2">
			<input name="add" type="button" class="ButtonSmall" id="add" onClick="return QoSSetupCheck()" value="" />
			<script>$('#add').val(get_words('_adv_txt_17'));</script>
		</td>
	</tr>
	</table>
</div>
			<span id="group_list"></span>
			</form>
			<br>
			<span id="VSTable"></span>
			
<div id="qos_restore" class="box_tn">
	<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0">
	<tr>
		<td class="btn_field" colspan="2">
			<input name="restore" type="button" class="ButtonSmall" id="restore" onClick="restoret()" value="" />
			<script>$('#restore').val(get_words('_qos_txt08'));</script>
		</td>
	</tr>
	</table>
</div>
		<br/>
			</div>
								<!-- End of main content -->

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