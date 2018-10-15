<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | Network | Lan Setting</title>
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
	var is_wps;

	var main = new ccpObject();
	main.set_param_url('get_set.ccp');
	main.set_ccp_act('get');
	main.add_param_arg('IGD_',1000);
	main.add_param_arg('IGD_LANDevice_i_LANHostConfigManagement_',1100);
	main.add_param_arg('IGD_LANDevice_i_DHCPStaticAddress_i_',1100);
	main.add_param_arg('IGD_WANDevice_i_WANStatus_',1110);
	main.add_param_arg('IGD_LANDevice_i_ConnectedAddress_i_',1100);
	main.add_param_arg('IGD_WANDevice_i_StaticIP_',1110);
	main.add_param_arg('IGD_WANDevice_i_DHCP_',1110);
	main.add_param_arg('IGD_WANDevice_i_PPPoE_i_',1110);
	main.add_param_arg('IGD_WANDevice_i_PPTP_',1110);
	main.add_param_arg('IGD_WANDevice_i_L2TP_',1110);
	main.add_param_arg('IGD_WANDevice_i_',1100);
	main.get_config_obj();
	
	var lanCfg = {
		'mac':				main.config_val('lanHostCfg_MACAddress_'),
		'ip':				main.config_val('lanHostCfg_IPAddress_'),
		'mask':				main.config_val('lanHostCfg_SubnetMask_'),
		'dhcp':				main.config_val('lanHostCfg_DHCPServerEnable_'),
		'hostnum':			main.config_val('lanHostCfg_LANHostNumberOfEntries_'),
		'hostv6num':		main.config_val('lanHostCfg_LANHostV6NumberOfEntries_')
	};	
	
	var lhostCfg = {
		'lanIp':			main.config_val('lanHostCfg_IPAddress_'),
		'lanSubnet':		main.config_val('lanHostCfg_SubnetMask_'),
		'lanDomain':		main.config_val('lanHostCfg_DomainName_'),
		'lanDeviceName':	main.config_val('lanHostCfg_DeviceName_'),
		'lanDnsRelay':		main.config_val('lanHostCfg_DNSRelay_'),
		'lanDhcp':			main.config_val('lanHostCfg_DHCPServerEnable_'),
		'lanMinAddr':		main.config_val('lanHostCfg_MinAddress_'),
		'lanMaxAddr':		main.config_val('lanHostCfg_MaxAddress_'),
		'lanDhcpLease':		main.config_val('lanHostCfg_DHCPLeaseTime_'),
		'lanAntiARP':		main.config_val('lanHostCfg_AntiARPAttack_'),
		'lanAPIp':			main.config_val('lanHostCfg_APIPAddress_'),
		'lanAPSubnet':		main.config_val('lanHostCfg_APSubnetMask_'),
		'lanAPGateway':		main.config_val('lanHostCfg_APGateway_'),
		'lanAPDeviceName':	main.config_val('lanHostCfg_APDeviceName_'),
		'lanAPDNS1':		main.config_val('lanHostCfg_APPrimaryDNSAddress_'),
		'lanAPDNS2':		main.config_val('lanHostCfg_APSecondaryDNSAddress_'),
		'dhcpBroadcast':	main.config_val('lanHostCfg_AlwaysBroadcast_'),
		'netBIOSAnnounce':	main.config_val('lanHostCfg_NetBIOSAnnouncement_'),
		'netBIOSLearn':		main.config_val('lanHostCfg_NetBIOSLearn_'),
		'netBIOSScope':		main.config_val('lanHostCfg_NetBIOSScope_'),
		'netBIOSNodeType':	main.config_val('lanHostCfg_NetBIOSNodeType_'),
		'primaryWINS':		main.config_val('lanHostCfg_PrimaryWINSAddress_'),
		'secondaryWINS':	main.config_val('lanHostCfg_SecondaryWINSAddress_')
	};	
	
	var dev_mode = main.config_val("igd_DeviceMode_");
	
	var reservedHost = {
		'enable':			main.config_str_multi("reserveDHCP_Enable_"),
		'name':				main.config_str_multi("reserveDHCP_HostName_"),
		'ipaddr':			main.config_str_multi("reserveDHCP_Yiaddr_"),
		'mac':				main.config_str_multi("reserveDHCP_Chaddr_")
	};

	var reserveCnt 	= 0;
	if(reservedHost.name != null)
		reserveCnt = reservedHost.name.length;

	//var array_rules_inst 		= config_inst_multi("IGD_LANDevice_i_LANHostConfigManagement_DHCPStaticAddress_i_");

	var lanHostInfo = {
		'name':				main.config_str_multi("igdLanHostStatus_HostName_")||"",
		'ipaddr':			main.config_str_multi("igdLanHostStatus_HostIPv4Address_")||"",
		'mac':				main.config_str_multi("igdLanHostStatus_HostMACAddress_")||"",
		'type':				main.config_str_multi("igdLanHostStatus_HostAddressType_")||"",
		'expire':			main.config_str_multi("igdLanHostStatus_HostExpireTime_")||""
	};
	
	var wanCfg = {
		'proto':		main.config_val("igdWanStatus_WanProto_"),
		'networkSt':	main.config_val("igdWanStatus_NetworkStatus_"),
		'ipaddr':		main.config_val("igdWanStatus_IPAddress_")
	};	
	
	var submit_button_flag = 0;
	var rule_max_num = 24;
	var resert_rule = rule_max_num;
	var ReadyGroupArray = [null];
	var ListArray = [null];
	
	function ReservationsObj(enable,name,ip,mac){
		this.no;
		this.enable = enable;
		this.name = name;
		this.ip = ip;
		this.mac = mac;
		this.del = false;
	};
function add_reserved(){
	//check value
	
	var ip = $('#lanIp').val();
	var mask = $('#lanNetmask').val();
	var reserved_enable = $('#ruleEnableG').attr('checked');
	var reserved_name = $('#ruleNameG').val();
	var reserved_ip = $('#ipaddressG').val();
	var reserved_mac = $('#macaddressG').val();
	var start_ip = $('#dhcpStart').val();
	var end_ip = $('#dhcpEnd').val();
	
	var ip_addr_msg = replace_msg(all_ip_addr_msg,get_words('_ipaddr'));
	var Res_ip_addr_msg = replace_msg(all_ip_addr_msg,get_words('lan_reserveIP')); 
	var start_ip_addr_msg = replace_msg(all_ip_addr_msg,get_words('TEXT035')); 
	var end_ip_addr_msg = replace_msg(all_ip_addr_msg,get_words('end_ip'));
	
	var temp_ip_obj = new addr_obj(ip.split("."), ip_addr_msg, false, false);
	var temp_mask_obj = new addr_obj(mask.split("."), subnet_mask_msg, false, false);
	var temp_res_ip_obj = new addr_obj(reserved_ip.split("."), Res_ip_addr_msg, false, false);
	var start_obj = new addr_obj(start_ip.split("."), start_ip_addr_msg, false, false);
	var end_obj = new addr_obj(end_ip.split("."), end_ip_addr_msg, false, false);
	
	var temp_res_ip2_obj = new addr_obj(reserved_ip.split("."), Res_ip_addr_msg, false, false);
	if(reserved_enable == true){
		if (!check_domain(temp_res_ip2_obj, temp_mask_obj, temp_ip_obj)){
			alert(get_words('TEXT033')+" " + reserved_ip + " "+get_words('GW_DHCP_SERVER_RESERVED_IP_IN_POOL_INVALID_a'));
			return false;
		}	
	}
	
	if(reserved_name == ""){
		//alert(msg[STATIC_DHCP_NAME]);
		alert(get_words('GW_INET_ACL_NAME_INVALID'));
		return false;
	}else if(!check_LAN_ip(temp_ip_obj.addr, temp_res_ip_obj.addr, get_words('TEXT033'))){
		return false;
	}else if (!check_address(temp_res_ip_obj, temp_mask_obj, temp_ip_obj)) {
		return false;
	}else if (!check_domain(temp_res_ip_obj, temp_mask_obj, temp_ip_obj)){
		alert(get_words('TEXT033')+" " + reserved_ip + " "+get_words('GW_DHCP_SERVER_RESERVED_IP_IN_POOL_INVALID_a'));
		return false;
	}else if (!check_mac(reserved_mac)){
		alert(get_words('KR3'));
		return false;
	}
	if (check_resip_order(temp_res_ip_obj,start_obj, end_obj)) {
		alert(get_words('TEXT033')+" " + reserved_ip + " "+get_words('GW_DHCP_SERVER_RESERVED_IP_IN_POOL_INVALID_a'));
		return false;
	}
	//check ReadyGroupArray
	for(var i=1;i<ReadyGroupArray.length;i++){
		if(ReadyGroupArray[i].name == reserved_name){
			var alt_msg = addstr1(get_words('GW_QOS_RULES_NAME_ALREADY_USED'), reserved_name);
			alert(alt_msg);
			return false;
		}
		if(ReadyGroupArray[i].ip == reserved_ip){
			var alt_msg = addstr1(get_words('GW_DHCP_SERVER_RESERVED_IP_UNIQUENESS_INVALID'), " ("+ reserved_ip +") ");
			alert(alt_msg);
			return false;
		}
		if(ReadyGroupArray[i].mac == reserved_mac){
			var alt_msg = addstr1(get_words('GW_DHCP_SERVER_RESERVED_MAC_UNIQUENESS_INVALID'), reserved_mac);
			alert(alt_msg);
			return false;
		}
	}
	//check ListArray
	for(var i=1;i<ListArray.length;i++){
		if(ListArray[i].name == reserved_name){
			var alt_msg = addstr1(get_words('GW_QOS_RULES_NAME_ALREADY_USED'), reserved_name);
			alert(alt_msg);
			return false;
		}
		if(ListArray[i].ip == reserved_ip){
			var alt_msg = addstr1(get_words('GW_DHCP_SERVER_RESERVED_IP_UNIQUENESS_INVALID'), " ("+ reserved_ip +") ");
			alert(alt_msg);
			return false;
		}
		if(ListArray[i].mac == reserved_mac){
			var alt_msg = addstr1(get_words('GW_DHCP_SERVER_RESERVED_MAC_UNIQUENESS_INVALID'), reserved_mac);
			alert(alt_msg);
			return false;
		}
	}
	
	var obj = new ReservationsObj(
		$('#ruleEnableG').attr('checked'),
		$('#ruleNameG').val(),
		$('#ipaddressG').val(),
		$('#macaddressG').val()
		
	);
	ReadyGroupArray.push(obj);
	$('#ruleEnableG').attr('checked',false);
	$('#ruleNameG').val('');
	$('#ipaddressG').val('');
	$('#macaddressG').val('');
	$('#readyreserv').show();
	Paint_ReadyTable();
}
function Paint_ReadyTable(){
	var RT_header = '<tr>\
<td class="CTS" style="width:20">'+get_words('_item_no')+'</td>\
<td class="CTS" style="width:40">'+get_words('_enable')+'</td>\
<td class="CTS" style="width:121">'+get_words('bd_CName')+'</td>\
<td class="CTS" style="width:87">'+get_words('_ipaddr')+'</td>\
<td class="CTS" style="width:99">'+get_words('_macaddr')+'</td>\
<td class="CTS" style="width:40">'+get_words('_delete')+'</td>\
</tr>';
	$('#ReadyTable').children().remove();
	$('#ReadyTable').append(RT_header);
	for(var i=1;i<ReadyGroupArray.length;i++){
		ReadyGroupArray[i].no = i;
		var obj = 
'<tr name="rg_list">\
<td class="CELL">'+ReadyGroupArray[i].no+'</td>\
<td class="CELL"><input type="checkbox" disabled="" '+(ReadyGroupArray[i].enable?'checked="checked"':'')+' /></td>\
<td class="CELL">'+ReadyGroupArray[i].name+'</td>\
<td class="CELL">'+ReadyGroupArray[i].ip+'</td>\
<td class="CELL">'+ReadyGroupArray[i].mac+'</td>\
<td class="CELL"><input type="checkbox" id="sel_tr'+i+'" name="sel_tr" value="ON" onchange="change_rg_del('+i+');" '+(ReadyGroupArray[i].del?'checked="checked"':'')+' /></td>\
</tr>';
		$('#ReadyTable').append(obj);
	}
}
function change_rg_del(idx){
	ReadyGroupArray[idx].del = $('#sel_tr'+idx).attr('checked');
}

function check_dhcp_value(){
	var start_obj, end_obj;
	var temp_mac = "";
	var ip = (dev_mode=="0") ? get_by_id("lanIp").value: get_by_id("lanIp").value;
	var mask = (dev_mode=="0") ? get_by_id("lanNetmask").value: get_by_id("lanNetmask").value;
	
	var ip_addr_msg = replace_msg(all_ip_addr_msg,get_words('_ipaddr'));
	var wan_ip_addr_msg = replace_msg(all_ip_addr_msg,get_words('_ipaddr'));
	var temp_ip_obj = new addr_obj(ip.split("."), ip_addr_msg, false, false);
	var temp_mask_obj = new addr_obj(mask.split("."), subnet_mask_msg, false, false);
	var dhcp_les = get_by_id("dhcpLease").value;
	
	var wan_proto 			= wanCfg.proto;
	var wan_port_status 	= wanCfg.networkSt;
	var wan_ip_addr 		= wanCfg.ipaddr;
	var wan_ip_addr_obj 	= new addr_obj(wan_ip_addr.split("."), wan_ip_addr_msg, false, false); 
	
	if (!check_mask(temp_mask_obj) || !check_address(temp_ip_obj, temp_mask_obj)) {
		return false;
	}
	var dhcpsvr = $('#lanDhcpType').val();

   if(wan_proto == "static" && wan_ip_addr != "0.0.0.0"){ // when wan static ip doesn't empty
		if (check_domain(temp_ip_obj, temp_mask_obj, wan_ip_addr_obj)){
			//alert("LAN and WAN IP Address cann't be set to the same subnet.");
			alert(get_words('bln_alert_3'));
			return false;
		}
		
	/*}else if(wan_proto != "static" && get_by_id("wan_current_ipaddr").value != "0.0.0.0/0.0.0.0/0.0.0.0/0.0.0.0/0.0.0.0"){ // /PPTP/L2TP/PPPoE plug in WAN port
		if (check_domain(temp_ip_obj, temp_mask_obj, wan_ip_addr_obj)){
			//alert("LAN and WAN IP Address cann't be set to the same subnet.");
			alert(get_words('bln_alert_3'));
			return false;
		}*/
	}else if(wan_proto == "pppoe" && wan_ip_addr != "0.0.0.0"){ // when wan pppoe ip doesn't empty
		if (check_domain(temp_ip_obj, temp_mask_obj, wan_ip_addr_obj)){
			//alert("LAN and WAN IP Address cann't be set to the same subnet.");
			alert(get_words('bln_alert_3'));
			return false;
		}
	}else if(wan_proto == "pptp" && wan_ip_addr != "0.0.0.0"){ // when wan pptp ip doesn't empty
		if (check_domain(temp_ip_obj, temp_mask_obj, wan_ip_addr_obj)){
			//alert("LAN and WAN IP Address cann't be set to the same subnet.");
			alert(get_words('bln_alert_3'));
			
			return false;
		}
	}else if(wan_proto == "l2tp" && wan_ip_addr != "0.0.0.0"){ // when wan l2tp ip doesn't empty
		if (check_domain(temp_ip_obj, temp_mask_obj, wan_ip_addr_obj)){
			//alert("LAN and WAN IP Address cann't be set to the same subnet.");
			alert(get_words('bln_alert_3'));
			return false;
		}
	}
	

	if ((dhcpsvr=='1') && (dev_mode == "0")){			//add dev_mode check by vic, 2010/11/08
		var start_ip = get_by_id("dhcpStart").value;
		var end_ip = get_by_id("dhcpEnd").value;
		
		var start_ip_addr_msg = replace_msg(all_ip_addr_msg,get_words('TEXT035'));
		var end_ip_addr_msg = replace_msg(all_ip_addr_msg,get_words('end_ip'));

		var start_obj = new addr_obj(start_ip.split("."), start_ip_addr_msg, false, false);
		var end_obj = new addr_obj(end_ip.split("."), end_ip_addr_msg, false, false);

		// customer request DHCP range should be limited to class C
		var checkRangeC = function(start_ip, end_ip){
			var start_ip_ary = start_ip.split('.'),
				end_ip_ary = end_ip.split('.');

			for(var i = 0; i < 3; i++){
				if(start_ip_ary[i] != end_ip_ary[i]){
					alert(get_words('DHCP_range_class_c'));
					return false;
				}
			}

			if(start_ip_ary[3] > 254 || start_ip_ary[3] < 1){
				alert(addstr(get_words('MSG029'), get_words('_dhcp_start_ip')) + ' 1-254');
				return false;
			}
			else if(end_ip_ary[3] > 254 || end_ip_ary[i] < 1){
				alert(addstr(get_words('MSG029'), get_words('_dhcp_end_ip')) + ' 1-254');
				return false;
			}
			return true;

		};

		//check dhcp ip range equal to lan-ip or not?
		if(!check_LAN_ip(temp_ip_obj.addr, start_obj.addr, "Start IP address")){
			return false;
		}

		if(!check_LAN_ip(temp_ip_obj.addr, end_obj.addr, "End IP Address")){
			return false;
		}
		
		//check dhcp ip range and lan ip the same mask or not?
		if (!check_address(start_obj, temp_mask_obj, temp_ip_obj) || !check_address(end_obj, temp_mask_obj, temp_ip_obj)){
			return false;
		}

		if (!check_domain(temp_ip_obj, temp_mask_obj, start_obj)){
			//alert(msg[START_INVALID_DOMAIN]);
			alert(get_words('TEXT037'));
			return false;
		}

		if (!check_domain(temp_ip_obj, temp_mask_obj, end_obj)){
			//alert(msg[END_INVALID_DOMAIN]);
			alert(get_words('TEXT038'));
			return false;
		}
		
		if (!check_ip_order(start_obj, end_obj)){
			//alert(msg[IP_RANGE_ERROR]);
			alert(get_words('TEXT039'));
			return false;
		}

		if(!checkRangeC(start_ip, end_ip))
			return false;
		
		if (check_lanip_order(temp_ip_obj,start_obj, end_obj)){
			alert(get_words('network_dhcp_ip_in_server'));
			//alert(msg[NETWORK_DHCP_IP_IN_SERVER]);
			return false;
		}
		var less_msg = replace_msg(check_num_msg, get_words('bd_DLT'), 1, 999999);
		var temp_less = new varible_obj(dhcp_les, less_msg, 1, 999999, false);
		if (!check_varible(temp_less)){
			return false;
		}
	}
	return true;
}
function dhcp_apply(){
	if(check_dhcp_value()){
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.set_param_option('old_ip',lanCfg.ip);
		obj.set_param_option('old_mask',lanCfg.mask);
		obj.set_param_option('new_ip',$('#lanIp').val());
		obj.set_param_option('new_mask',$('#lanNetmask').val());
		obj.set_param_next_page('internet_lan.asp');
		
		obj.add_param_arg('lanHostCfg_IPAddress_','1.1.1.0',$('#lanIp').val());
		obj.add_param_arg('lanHostCfg_SubnetMask_','1.1.1.0',$('#lanNetmask').val());
		obj.add_param_arg('lanHostCfg_DHCPServerEnable_','1.1.1.0',$('#lanDhcpType').val());
		obj.add_param_arg('lanHostCfg_MinAddress_','1.1.1.0',$('#dhcpStart').val());
		obj.add_param_arg('lanHostCfg_MaxAddress_','1.1.1.0',$('#dhcpEnd').val());
		
		var minLease = parseInt($('#dhcpLease').val());
		obj.add_param_arg('lanHostCfg_DHCPLeaseTime_','1.1.1.0',minLease);
		var param = obj.get_param();
		
		if((lanCfg.ip != $('#lanIp').val()) ||
			(lanCfg.mask != $('#lanNetmask').val()) ||
			(lanCfg.dhcp != $('#lanDhcpType').val()))
		{
			alert(get_words('_LAN_CHK_REBOOT_MSG'));
			ajax_submit(param,false);
			
			var rebootObj = new ccpObject();
			rebootObj.set_param_url('cfg_op.ccp');
			rebootObj.set_ccp_act('reboot');
			rebootObj.set_param_option('fromLan','1');
			rebootObj.set_param_option('newIP',$('#lanIp').val());
			rebootObj.get_config_obj();
		}
		else
		{
			obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
			param = obj.get_param();
			totalWaitTime = 18; //second
			redirectURL = 'http://'+$('#lanIp').val()+location.pathname;
			wait_page();
			jq_ajax_post(param.url, param.arg);
		}
	}
}
function ready_apply(){
	var obj = new ccpObject();
	obj.set_param_url('get_set.ccp');
	obj.set_ccp_act('set');
	obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
	obj.set_param_next_page('internet_lan.asp');
	
	for(var i=1;i<ReadyGroupArray.length;i++){
		obj.add_param_arg('reserveDHCP_Enable_','1.1.'+(reserveCnt+i)+'.0',(ReadyGroupArray[i].enable?"1":"0"));
		obj.add_param_arg('reserveDHCP_HostName_','1.1.'+(reserveCnt+i)+'.0',ReadyGroupArray[i].name);
		obj.add_param_arg('reserveDHCP_Yiaddr_','1.1.'+(reserveCnt+i)+'.0',ReadyGroupArray[i].ip);
		obj.add_param_arg('reserveDHCP_Chaddr_','1.1.'+(reserveCnt+i)+'.0',ReadyGroupArray[i].mac);
	}
	var param = obj.get_param();
	
	totalWaitTime = 18; //second
	redirectURL = location.pathname;
	wait_page();
	jq_ajax_post(param.url, param.arg);

}
function delete_ready(){
	var deleted_objs = $('tr[name=rg_list]>td>input[name=sel_tr]:checked');
	for(var i=0;i<deleted_objs.length;i++){
		var i_id = parseInt($(deleted_objs[i]).attr('id').replace('sel_tr',''));
		 ReadyGroupArray.splice(i_id, 1, 0);

	}
	for(var i=0; i<ReadyGroupArray.length; i++){
		if(ReadyGroupArray[i] == 0){
			ReadyGroupArray.splice(i, 1);
			i--;
		}
	}
	Paint_ReadyTable();
}

function save_reserved(){
	//check value
	
	var ip = $('#lanIp').val();
	var mask = $('#lanNetmask').val();
	var reserved_enable = $('#ruleEnable').attr('checked');
	var reserved_name = $('#ruleName').val();
	var reserved_ip = $('#ipaddress').val();
	var reserved_mac = $('#macaddress').val();
	var start_ip = $('#dhcpStart').val();
	var end_ip = $('#dhcpEnd').val();
	
	var ip_addr_msg = replace_msg(all_ip_addr_msg,get_words('_ipaddr'));
	var Res_ip_addr_msg = replace_msg(all_ip_addr_msg,get_words('lan_reserveIP')); 
	var start_ip_addr_msg = replace_msg(all_ip_addr_msg,get_words('TEXT035')); 
	var end_ip_addr_msg = replace_msg(all_ip_addr_msg,get_words('end_ip'));
	
	var temp_ip_obj = new addr_obj(ip.split("."), ip_addr_msg, false, false);
	var temp_mask_obj = new addr_obj(mask.split("."), subnet_mask_msg, false, false);
	var temp_res_ip_obj = new addr_obj(reserved_ip.split("."), Res_ip_addr_msg, false, false);
	var start_obj = new addr_obj(start_ip.split("."), start_ip_addr_msg, false, false);
	var end_obj = new addr_obj(end_ip.split("."), end_ip_addr_msg, false, false);
	
	var temp_res_ip2_obj = new addr_obj(reserved_ip.split("."), Res_ip_addr_msg, false, false);
	if(reserved_enable == true){
		if (!check_domain(temp_res_ip2_obj, temp_mask_obj, temp_ip_obj)){
			alert(get_words('TEXT033')+" " + reserved_ip + " "+get_words('GW_DHCP_SERVER_RESERVED_IP_IN_POOL_INVALID_a'));
			return false;
		}	
	}
	
	if(reserved_name == ""){
		//alert(msg[STATIC_DHCP_NAME]);
		alert(get_words('GW_INET_ACL_NAME_INVALID'));
		return false;
	}else if(!check_LAN_ip(temp_ip_obj.addr, temp_res_ip_obj.addr, get_words('TEXT033'))){
		return false;
	}else if (!check_address(temp_res_ip_obj, temp_mask_obj, temp_ip_obj)) {
		return false;
	}else if (!check_domain(temp_res_ip_obj, temp_mask_obj, temp_ip_obj)){
		alert(get_words('TEXT033')+" " + reserved_ip + " "+get_words('GW_DHCP_SERVER_RESERVED_IP_IN_POOL_INVALID_a'));
		return false;
	}else if (!check_mac(reserved_mac)){
		alert(get_words('KR3'));
		return false;
	}
	if (check_resip_order(temp_res_ip_obj,start_obj, end_obj)) {
		alert(get_words('TEXT033')+" " + reserved_ip + " "+get_words('GW_DHCP_SERVER_RESERVED_IP_IN_POOL_INVALID_a'));
		return false;
	}
	//check ListArray
	var cur_idx = parseInt($('#ruleID').val());
	for(var i=1;i<ListArray.length;i++){
		if(i==cur_idx)
			continue;
		if(reserved_name.length>0 && ListArray[i].name == reserved_name){
			var alt_msg = addstr1(get_words('GW_QOS_RULES_NAME_ALREADY_USED'), reserved_name);
			alert(alt_msg);
			return false;
		}
		if(reserved_ip.length>0 && ListArray[i].ip == reserved_ip){
			var alt_msg = addstr1(get_words('GW_DHCP_SERVER_RESERVED_IP_UNIQUENESS_INVALID'), " ("+ reserved_ip +") ");
			alert(alt_msg);
			return false;
		}
		if(reserved_mac.length>0 && ListArray[i].mac == reserved_mac){
			var alt_msg = addstr1(get_words('GW_DHCP_SERVER_RESERVED_MAC_UNIQUENESS_INVALID'), reserved_mac);
			alert(alt_msg);
			return false;
		}
	}
	ListArray[cur_idx].enable = $('#ruleEnable').attr('checked');
	ListArray[cur_idx].name = $('#ruleName').val();
	ListArray[cur_idx].ip = $('#ipaddress').val();
	ListArray[cur_idx].mac = $('#macaddress').val();
	
	var obj = new ccpObject();
	obj.set_param_url('get_set.ccp');
	obj.set_ccp_act('set');
	obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
	obj.set_param_next_page('internet_lan.asp');
	
	obj.add_param_arg('reserveDHCP_Enable_','1.1.'+cur_idx+'.0',(ListArray[cur_idx].enable?"1":"0"));
	obj.add_param_arg('reserveDHCP_HostName_','1.1.'+cur_idx+'.0',ListArray[cur_idx].name);
	obj.add_param_arg('reserveDHCP_Yiaddr_','1.1.'+cur_idx+'.0',ListArray[cur_idx].ip);
	obj.add_param_arg('reserveDHCP_Chaddr_','1.1.'+cur_idx+'.0',ListArray[cur_idx].mac);
	var param = obj.get_param();
	
	totalWaitTime = 18; //second
	redirectURL = location.pathname;
	wait_page();
	jq_ajax_post(param.url, param.arg);

}
function deleteAll_reserved(){
	var obj = new ccpObject();
	obj.set_param_url('get_set.ccp');
	obj.set_ccp_act('del');
	obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
	obj.set_param_next_page('internet_lan.asp');
	
	for(var i=1;i<25;i++){
		obj.add_param_arg('IGD_LANDevice_i_DHCPStaticAddress_i_','1.1.'+i+'.0');
	}
	var param = obj.get_param();

	totalWaitTime = 18; //second
	redirectURL = location.pathname;
	wait_page();
	jq_ajax_post(param.url, param.arg);
}
function delete_sel_reserved(){
	if($('[name=del_select]:checked').length==0)
	{
		//alert(get_words('_lan_dr'));
		alert('no select');
		return;
	}
	//delete all
	var obj = new ccpObject();
	obj.set_param_url('get_set.ccp');
	obj.set_ccp_act('del');
	obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
	for(var i=1;i<25;i++){
		obj.add_param_arg('IGD_LANDevice_i_DHCPStaticAddress_i_','1.1.'+i+'.0');
	}
	obj.get_config_obj();
	
	obj = new ccpObject();
	obj.set_param_url('get_set.ccp');
	obj.set_ccp_act('set');
	obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
	obj.set_param_next_page('internet_lan.asp');
	var counter = 1;
	for(var i=1;i<ListArray.length;i++){
		if(!ListArray[i].del){
			//alert(ListArray[i].mac);
			obj.add_param_arg('reserveDHCP_Enable_','1.1.'+counter+'.0',(ListArray[i].enable?"1":"0"));
			obj.add_param_arg('reserveDHCP_HostName_','1.1.'+counter+'.0',ListArray[i].name);
			obj.add_param_arg('reserveDHCP_Yiaddr_','1.1.'+counter+'.0',ListArray[i].ip);
			obj.add_param_arg('reserveDHCP_Chaddr_','1.1.'+counter+'.0',ListArray[i].mac);
			counter++;
		}
	}
	var param = obj.get_param();
	
	totalWaitTime = 18; //second
	redirectURL = location.pathname;
	wait_page();
	jq_ajax_post(param.url, param.arg);
	
}
function reset_reserved(){
	$('input[name=del_select]').attr('checked', false);
}
function change_re_del(idx){
	ListArray[idx].del = $('#select'+idx).attr('checked');
}

function edit_list(idx){
	$('#addDHCPReserveField').show();
	var obj = ListArray[idx];
	$('#ruleID').val(idx);
	$('#ruleEnable').attr('checked', obj.enable);
	$('#ruleName').val(obj.name);
	$('#ipaddress').val(obj.ip);
	$('#macaddress').val(obj.mac);
}
function CopyMyPCsMAC(id){
	$('#'+id).val(cli_mac);
}
/**
 * auto change dhcp range on router ip changed.
 */
function check_dhcp_range()
{
	var lan_ip = $('#lanIp').val().split(".");
	var start_ip3 = $('#dhcpStart').val().split(".");
	var end_ip3 = $('#dhcpEnd').val().split(".");
	var enrty_IP = lan_ip[2];
	$('#dhcpStart').val(lan_ip[0] +"."+lan_ip[1]+"." + enrty_IP +"." + start_ip3[3]);
	$('#dhcpEnd').val(lan_ip[0] +"."+lan_ip[1]+"." + enrty_IP +"." + end_ip3[3]);
}

function setValueHostname(){
	var val_name = lhostCfg.lanDeviceName;
	$('#hostname').val(val_name);
}
function setValueIPAddress(){
	var val_ip = lhostCfg.lanIp;
	$('#lanIp').val(val_ip);
}
function setValueSubnetMask(){
	var val_mask = lhostCfg.lanSubnet;
	$('#lanNetmask').val(val_mask);
}
function setValueDefaultGateway(){
	var val_gw = lhostCfg.lanAPGateway;
	$('#lanGateway').val(val_gw);
}
function setValuePrimaryDNSServer(){
	var val_dns1 = lhostCfg.lanAPDNS1;
	$('#lanPriDns').val(val_dns1);
}
function setValueSecondaryDNSServer(){
	var val_dns2 = lhostCfg.lanAPDNS2;
	$('#lanSecDns').val(val_dns2);
}
function setValueMACAddress(){
	var val_mac = lanCfg.mac;
	$('#lanMac').html(val_mac);
}
function setValueDHCPServer(){
	var sel_dhcp = lhostCfg.lanDhcp;
	$('#lanDhcpType').val(sel_dhcp);
}
function setValueDHCPStartIP(){
	var val_min = lhostCfg.lanMinAddr;
	$('#dhcpStart').val(val_min);
}
function setValueDHCPEndIP(){
	var val_max = lhostCfg.lanMaxAddr;
	$('#dhcpEnd').val(val_max);
}
function setValueDHCPLeaseTime(){
	var val_lease_min = lhostCfg.lanDhcpLease;
	$('#dhcpLease').val(val_lease_min);
}
function setEventDHCPServer(){
	var func = function(){
		var sel_dhcp = $('#lanDhcpType option:selected').val();
			if(sel_dhcp == '0')
			{
				$('#start').hide();
				$('#end').hide();
				$('#lease').hide();
			}
			else
			{
				$('#start').show();
				$('#end').show();
				$('#lease').show();
			}
	};
	func();
	$('#lanDhcpType').change(func);
}
function setValueDHCPRComputerNameSelA(){
	var obj = $('#name_selectG');
	for(var i=0;i<lanHostInfo.name.length;i++){
		obj.append($('<option>').val(i+1).html(lanHostInfo.name[i]));
	}
}
function setEventDHCPRComputerNameA(){
	var func = function(){
		var sel_dhcpr = $('#name_selectG option:selected').val();
		if(sel_dhcpr != '0')
		{
//			setValueDHCPREnableA(true);//692不會自己勾,但dlink會
			setValueDHCPRComputerNameA(sel_dhcpr);
			setValueDHCPRIPAddressA(sel_dhcpr);
			setValueDHCPRMACAddressA(sel_dhcpr);
		}
	};
	func();
	$('#name_selectG').change(func);
}
function setValueDHCPREnableA(checked){
	$('#ruleEnableG').attr('checked',checked);
}
function setValueDHCPRComputerNameA(idx){
	$('#ruleNameG').val(lanHostInfo.name[idx-1]);
}
function setValueDHCPRIPAddressA(idx){
	$('#ipaddressG').val(lanHostInfo.ipaddr[idx-1]);
}
function setValueDHCPRMACAddressA(idx){
	$('#macaddressG').val(lanHostInfo.mac[idx-1]);
}
function setValueDHCPRComputerNameSelE(){
	var obj = $('#name_select');
	for(var i=0;i<lanHostInfo.name.length;i++){
		var option = document.createElement("option");
		option.text = lanHostInfo.name[i];
		option.value = i+1;
		obj.append(option);
	}
}
function setEventDHCPRComputerNameE(){
	var func = function(){
		var sel_dhcpr = $('#name_select option:selected').val();
		if(sel_dhcpr != '0')
		{
//			setValueDHCPREnableE(true);//692不會自己勾,但dlink會
			setValueDHCPRComputerNameE(sel_dhcpr);
			setValueDHCPRIPAddressE(sel_dhcpr);
			setValueDHCPRMACAddressE(sel_dhcpr);
		}
	};
	func();
	$('#name_select').change(func);
}
function setValueDHCPREnableE(checked){
	$('#ruleEnable').attr('checked',checked);
}
function setValueDHCPRComputerNameE(idx){
	$('#ruleName').val(lanHostInfo.name[idx-1]);
}
function setValueDHCPRIPAddressE(idx){
	$('#ipaddress').val(lanHostInfo.ipaddr[idx-1]);
}
function setValueDHCPRMACAddressE(idx){
	$('#macaddress').val(lanHostInfo.mac[idx-1]);
}
function setValueDHCPReservationsList(){
	for(var i=0;i<reserveCnt;i++){
		var obj = new ReservationsObj(
			(reservedHost.enable[i]=='1'?true:false),
			reservedHost.name[i],
			reservedHost.ipaddr[i],
			reservedHost.mac[i]
		);
		obj.no = i+1;
		ListArray.push(obj);
		
	var list = '<tr name="re_list">\
<td class="CELL" align="left" style="word-break : break-all;"> '+(i+1)+'&nbsp; <input type="checkbox" id="delRule'+i+'" name="delRule'+i+'" disabled="disabled" '+(obj.enable?'checked="checked"':"")+' /> </td>\
<td id="ruleName'+i+'" class="CELL" align="center" style="word-break : break-all;">'+obj.name+'</td>\
<td id="ipaddress'+i+'" class="CELL" align="center" style="word-break : break-all;">'+obj.ip+'</td>\
<td id="macaddress'+i+'" class="CELL" align="center" style="word-break : break-all;">'+obj.mac+'</td>\
<td align="center" class="CELL"><a href="javascript:edit_list('+(i+1)+');"><img src="edit.gif" /></a></td>\
<td align="center" class="CELL"><input type="checkbox" id="select'+(i+1)+'" name="del_select" onchange="change_re_del('+(i+1)+');" /></td>\
</tr>';
	$('#ListTable').append(list);
	}
}

$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(model);
	//LAN Interface Setting
	setValueHostname();
	setValueIPAddress();
	setValueSubnetMask();
	setValueDefaultGateway();
	setValuePrimaryDNSServer();
	setValueSecondaryDNSServer();
	setValueMACAddress();
	
//	setEventIPAddress();

	//DHCP Server Setting
	setValueDHCPServer();
	setEventDHCPServer();
	setValueDHCPStartIP();
	setValueDHCPEndIP();
	setValueDHCPLeaseTime();
	
	//Others Setting
	//$('#other_setting').show();
	//setValue8021DSpanningTree();
	//setValueLLTD();
	//setValueIGMPproxy();
	//setValueUPNP();
	//setValuePPPOErelay();
	//setValueDNSproxy();
	
	//Add DHCP Reservation
	setValueDHCPRComputerNameSelA();
	setEventDHCPRComputerNameA();
	setValueDHCPRComputerNameSelE();
	setEventDHCPRComputerNameE();
	
	//DHCP Reservations List
	setValueDHCPReservationsList();
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
				<script>document.write(menu.build_structure(1,1,0))</script>
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
							<div class="headerbg" id="lanTitle">
							<script>show_words('_lan_setting_l');</script>
							</div>
							<div class="hr"></div>
							<div class="section_content_border">
							<div class="header_desc" id="lanIntroduction">
								<script>show_words('_LAN_DESC');</script>
								<p></p>
							</div>

<div class="box_tn">
	<div class="CT"><script>show_words('_help_txt39');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr style="display:none">
		<td class="CL" id="lHostname"><script>show_words('LS424');</script></td>
		<td class="CR"><input id="hostname" name="hostname" maxlength="16" value="" /></td>
	</tr>
	<tr>
		<td class="CL" id="lIp"><script>show_words('_ipaddr');</script></td>
		<td class="CR"><input id="lanIp" name="lanIp" maxlength="15" value="" onchange="check_dhcp_range();" /></td>
	</tr>
	<tr>
		<td class="CL" id="lNetmask"><script>show_words('_subnet');</script></td>
		<td class="CR"><input id="lanNetmask" name="lanNetmask" maxlength="15" value="" /></td>
	</tr>
	<tr id="brGateway" style="display:none;">
		<td class="CL" id="lGateway"><script>show_words('_defgw');</script></td>
		<td class="CR"><input id="lanGateway" name="lanGateway" maxlength="15" value="" /></td>
	</tr>
	<tr id="brPriDns" style="display:none;">
		<td class="CL" id="lPriDns"><script>show_words('_dns1');</script></td>
		<td class="CR"><input id="lanPriDns" name="lanPriDns" maxlength="15" value="" /></td>
	</tr>
	<tr id="brSecDns" style="display:none;">
		<td class="CL" id="lSecDns"><script>show_words('_dns2');</script></td>
		<td class="CR"><input id="lanSecDns" name="lanSecDns" maxlength="15" value="" /></td>
	</tr>
	<tr>
		<td class="CL" id="lMac"><script>show_words('_macaddr');</script></td>
		<td class="CR" id="lanMac"></td>
	</tr>
	</table>
</div>

<div class="box_tn">
	<div class="CT"><script>show_words('_dhcp_server_setting');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL"><script>show_words('_dhcpsrv');</script></td>
		<td class="CR">
		<select id="lanDhcpType" name="lanDhcpType" size="1">
			<option value="0"><script>show_words('_disable');</script></option>
			<option value="1"><script>show_words('_enable');</script></option>
		</select>
		</td>
	</tr>
	<tr id="start" style="visibility: visible; display: table-row;">
		<td class="CL" id="lDhcpStart"><script>show_words('_dhcp_start_ip');</script></td>
		<td class="CR"><input id="dhcpStart" name="dhcpStart" maxlength="18" value="" /></td>
	</tr>
	<tr id="end" style="visibility: visible; display: table-row;">
		<td class="CL" id="lDhcpEnd"><script>show_words('_dhcp_end_ip');</script></td>
		<td class="CR"><input id="dhcpEnd" name="dhcpEnd" maxlength="18" value="" /></td>
	</tr>
	<!--<tr id="mask">
		<td class="CL" id="lDhcpNetmask" align="right">DHCP Subnet Mask</td>
		<td class="CR"><input name="dhcpMask" maxlength="15" value="255.255.255.0" onchange="change(document.lanCfg.lanNetmask,this.value)" /></td>
	</tr>
	<tr id="pridns">
		<td class="CL" id="lDhcpPriDns" align="right">DHCP Primary DNS</td>
		<td class="CR"><input name="dhcpPriDns" maxlength="15" value="" /></td>
	</tr>
	<tr id="secdns">
		<td class="CL" id="lDhcpSecDns" align="right">DHCP Secondary DNS</td>
		<td class="CR"><input name="dhcpSecDns" maxlength="15" value="" /></td>
	</tr>
	<tr id="gateway">
		<td class="CL" id="lDhcpGateway" align="right">DHCP Default Gateway</td>
		<td class="CR"><input name="dhcpGateway" maxlength="15" value="192.168.20.1" onchange="changeSubnet(this.value);change(document.lanCfg.lanIp,this.value)" /></td>
	</tr>-->
	<tr id="lease" style="visibility: visible; display: table-row;">
		<td class="CL" id="lDhcpLease"><script>show_words('bd_DLT');</script></td>
		<td class="CR"><input id="dhcpLease" name="dhcpLease" maxlength="8" value="" /> (<script>show_words('_mintues_lower');</script>)
		</td>
	</tr>
	</table>
</div>

<div id="other_setting" class="box_tn" style="display:none">
	<div class="CT"><script>show_words('_other_setting');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
		<tr id="80211d" style="display:none">
			<td class="CL"><script>show_words('_8021d_spanning_tree');</script></td>
			<td class="CR">
				<select name="stpEnbl" size="1">
					<option value="0"><script>show_words('_disable');</script></option>
					<option value="1"><script>show_words('_enable');</script></option>
				</select>
			</td>
		</tr>
		<tr id="lltd" style="display:none">
			<td class="CL"><script>show_words('_lltd');</script></td>
			<td class="CR">
				<select name="lltdEnbl" size="1">
					<option value="0"><script>show_words('_disable');</script></option>
					<option value="1"><script>show_words('_enable');</script></option>
				</select>
			</td>
		</tr>
		<tr id="igmpProxy" style="display: table-row; visibility: visible;">
			<td class="CL"><script>show_words('_igmp_proxy');</script></td>
			<td class="CR">
				<select name="igmpEnbl" size="1">
					<option value="0"><script>show_words('_disable');</script></option>
					<option value="1"><script>show_words('_enable');</script></option>
				</select>
			</td>
		</tr>
		<tr id="upnp">
			<td class="CL"><script>show_words('ta_upnp');</script></td>
			<td class="CR">
				<select name="upnpEnbl" size="1">
					<option value="0"><script>show_words('_disable');</script></option>
					<option value="1"><script>show_words('_enable');</script></option>
				</select>
			</td>
		</tr>
		<tr id="pppoerelay" style="display:none">
			<td class="CL"><script>show_words('_pppoe_relay');</script></td>
			<td class="CR">
				<select name="pppoeREnbl" size="1">
					<option value="0"><script>show_words('_disable');</script></option>
					<option value="1"><script>show_words('_enable');</script></option>
				</select>
			</td>
		</tr>
		<tr id="dnsproxy" style="display:none">
			<td class="CL"><script>show_words('_dns_proxy');</script></td>
			<td class="CR">
				<select name="dnspEnbl" size="1">
					<option value="0"><script>show_words('_disable');</script></option>
					<option value="1"><script>show_words('_enable');</script></option>
				</select>
			</td>
		</tr>
	</table>
</div>

<div class="box_tn">
	<table cellspacing="0" cellpadding="0" class="formarea">
		<tr align="center">
		<td colspan="2" class="btn_field">
			<input type="button" class="button_submit" value="Apply" onclick="dhcp_apply();" />
			<input type="reset" class="button_submit" value="Cancel" onclick="window.location.reload()" />
		</td>
	</tr>
	</table>
</div>

<div class="box_tn" id="addDHCPReserveFieldG">
	<div class="CT"><script>show_words('_add_dhcp_reservation');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
		<tr>
			<td class="CL"><script>show_words('_enable');</script></td>
			<td class="CR">
			<input type="checkbox" id="ruleEnableG" name="ruleEnableG" />
			</td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('bd_CName');</script></td>
			<td class="CR">
				<input type="text" id="ruleNameG" name="ruleNameG" maxlength="15" value="" />
				<span> &lt;&lt; </span>
				<select name="selectG" id="name_selectG">
					<option value="0"><script>show_words('_hostname');</script></option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('_ipaddr');</script></td>
			<td class="CR"><input type="text" id="ipaddressG" name="ipaddressG" maxlength="20" value="" /></td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('_macaddr');</script></td>
			<td class="CR"><input type="text" id="macaddressG" name="macaddressG" maxlength="17" value="" />&nbsp;&nbsp;(Ex: 00:11:22:33:44:55)</td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('_copy_pc_mac');</script></td>
			<td class="CR"><input type="button" class="button_inbox" id="mac_copyG" name="mac_copyG" value="Copy" onclick="CopyMyPCsMAC('macaddressG')" /> &nbsp; &nbsp;</td>
			<script>$('#mac_copyG').val(get_words('_copy'));</script>
		</tr>
	</table>
	<table cellspacing="0" cellpadding="0" class="formarea">
		<tr align="center">
			<td colspan="2" class="btn_field">
				<input type="button" class="button_submit" id="add_res" value="Add" onclick="add_reserved()" />
				<script>$('#add_res').val(get_words('_add'));</script>
				<input type="reset" class="button_submit" id="clear_res" value="Clear" onclick="window.location.reload()" />
				<script>$('#clear_res').val(get_words('_clear'));</script>
			</td>
		</tr>
	</table>
	<div id="readyreserv" style="display:none"> 
	<div class="CT"><script>show_words('_dhcp_reservation_ready_group');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea" id="ReadyTable">
		<tr>
			<td class="CTS" style="width:20px;"><script>show_words('_item_no');</script></td>
			<td class="CTS" style="width:40px;"><script>show_words('_enable');</script></td>
			<td class="CTS" style="width:121px;"><script>show_words('bd_CName');</script></td>
			<td class="CTS" style="width:87px;"><script>show_words('_ipaddr');</script></td>
			<td class="CTS" style="width:99px;"><script>show_words('_macaddr');</script></td>
			<td class="CTS" style="width:40px;"><script>show_words('_delete');</script></td>
		</tr>
	</table>
	<table cellspacing="0" cellpadding="0" class="formarea">
		<tr align="center">
			<td colspan="6" class="btn_field">
				<input type="button" class="button_submit" id="btn_ready_apply" value="Apply" onclick="ready_apply()" />
				<script>$('#btn_ready_apply').val(get_words('_apply'));</script>
				<input type="reset" class="button_submit" id="btn_ready_reset" value="Reset" onclick="window.location.reload()" />
				<script>$('#btn_ready_reset').val(get_words('_reset'));</script>
				<input type="button" class="button_submit" id="btn_ready_delete" value="Delete" onclick="delete_ready()" />
				<script>$('#btn_ready_delete').val(get_words('_delete'));</script>
			</td>
		</tr>
	</table>
	</div>
</div>

<div class="box_tn" id="addDHCPReserveField" style="display:none;">
	<div class="CT"><script>show_words('_edit_dhcp_reservation');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
		<tr>
			<td class="CL"><script>show_words('_enable');</script></td>
			<td class="CR">
				<input type="checkbox" id="ruleEnable" name="ruleEnable" />
			</td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('bd_CName');</script></td>
			<td class="CR"><input type="text" id="ruleName" name="ruleName" maxlength="15" value="" />
			<span> &lt;&lt; </span>
			<select name="select" id="name_select">
				<option value="0"><script>show_words('_hostname');</script></option>
			</select>
			</td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('_ipaddr');</script></td>
			<td class="CR"><input type="text" id="ipaddress" name="ipaddress" maxlength="20" value="" /></td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('_macaddr');</script></td>
			<td class="CR"><input type="text" id="macaddress" name="macaddress" maxlength="17" value="" />&nbsp;&nbsp;(Ex: 00:11:22:33:44:55)</td>
		</tr>
		<tr>
			<td class="CL"><script>show_words('_copy_pc_mac');</script></td>
			<td class="CR"><input type="button" class="button_inbox" id="mac_copy" name="mac_copy" value="Copy" onclick="CopyMyPCsMAC('macaddress')" /> &nbsp; &nbsp;</td>
				<script>$('#mac_copy').val(get_words('_copy'));</script>
		</tr>
		<input type="hidden" id="ruleID" value="" />
	</table>
	<table cellspacing="0" cellpadding="0" class="formarea">
		<tr align="center">
			<td colspan="2" class="btn_field">
				<input type="button" class="button_submit" id="AddEditButton" value="Save" onclick="save_reserved();" />
				<script>$('#AddEditButton').val(get_words('_save'));</script>
				<input type="reset" class="button_submit" id="btn_edit_clear" value="Clear" onclick="window.location.reload()" />
				<script>$('#btn_edit_clear').val(get_words('_clear'));</script>
			</td>
		</tr>
	</table>
</div>

<div class="box_tn" id="addDHCPReserveList" style="display: block;">
	<div class="CT"><script>show_words('bd_title_list');</script></div>
	<table id="ListTable" cellspacing="0" cellpadding="0" class="formarea">
		<tr align="center">
			<td class="CTS"><script>show_words('_enable');</script></td>
			<td class="CTS"><script>show_words('bd_CName');</script></td>
			<td class="CTS"><script>show_words('_ipaddr');</script></td>
			<td class="CTS"><script>show_words('_macaddr');</script></td>
			<td class="CTS"><script>show_words('_edit');</script></td>
			<td class="CTS"><script>show_words('_delete');</script></td>
		</tr>
	</table>
	<table cellspacing="0" cellpadding="0" class="formarea" id="finaltable">
		<tr align="center">
			<td colspan="6" class="btn_field">
				<input type="button" class="button_submit" value="Delete Selected" id="deleteSelRsvIP" name="deleteSelRsvIP" onclick="delete_sel_reserved()" />
				<script>$('#deleteSelRsvIP').val(get_words('_delete_selected'));</script>
				<input type="button" class="button_submit" value="Delete All" id="deleteAllRsvIP" name="deleteAllRsvIP" onclick="deleteAll_reserved();" />
				<script>$('#deleteAllRsvIP').val(get_words('_delete_all'));</script>
				<input type="reset" class="button_submit" value="Reset" id="reset" name="reset" onclick="reset_reserved();" />
				<script>$('#reset').val(get_words('_reset'));</script>
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