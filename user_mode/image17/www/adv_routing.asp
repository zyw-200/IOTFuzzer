<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | Advanced | Routing</title>
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
	main.add_param_arg('IGD_Status_RoutingTable_i_',1100);
	main.add_param_arg('IGD_WANDevice_i_',1100);
	main.add_param_arg('IGD_Layer3Forwarding_Forwarding_i_',1100);
	main.add_param_arg('IGD_Layer3Forwarding_',1100);
	
	main.get_config_obj();

rtList = {
	'addr':			new String(main.config_val('igdRtList_Destination_')).split(','),
	'mask':			new String(main.config_val('igdRtList_Netmask_')).split(','),
	'gw':			new String(main.config_val('igdRtList_Gateway_')).split(','),
	'iface':		new String(main.config_val('igdRtList_Interface_')).split(','),
	'metric':		new String(main.config_val('igdRtList_Metric_')).split(','),
	'type':			new String(main.config_val('igdRtList_Type_')).split(','),
	'creator':		new String(main.config_val('igdRtList_Creator_')).split(',')
}

var route_obj = {
	'enable':	main.config_str_multi("fwdRule_Enable_"),
	'name':		main.config_str_multi("fwdRule_Name_"),
	'ip':		main.config_str_multi("fwdRule_DestIPAddress_"),
	'mask':		main.config_str_multi("fwdRule_DestSubnetMask_"),
	'gw':		main.config_str_multi("fwdRule_GatewayIPAddress_"),
	'metric':	main.config_str_multi("fwdRule_ForwardingMetric_"),
	'iface':	main.config_str_multi("fwdRule_SourceInterface_")
};
var ripObj = {
	'trans':	main.config_str_multi("l3fwd_DynamicRouteTransmit_"),
	'revc':		main.config_str_multi("l3fwd_DynamicRouteReceive_"),
	'pwd':		main.config_str_multi("l3fwd_V2Password_")
};

var connect_type= main.config_val("wanDev_CurrentConnObjType_");

var current_rules = 0;
if(route_obj.ip != null)
	current_rules = route_obj.ip.length;

var submit_button_flag = 0;
var rule_max_num = 32;
var DataArray = new Array();
var TotalCnt=0;

var array_proto_name = new Array('LAN','WAN','WAN('+get_words('_physical_port')+')');

	function onPageLoad()
	{
		if (login_Info != "w") {
			DisableEnableForm(form1,true);
		}
		routingTable_list();
		setValueEnableRIP();
		setEventEnableRIP();
		setValueRIPmode();
		setEventRIPmode();
		setValueRIPv2Password();
		set_form_default_values("form1");
	}

function mapIface(idx)
{
	switch (idx) {
	case '1':
		return 'LAN';
	case '2':
		return 'Local Loopback';
	default:
		return 'WAN';
	}
}
function routingTable_list()
{
	if (rtList.addr == null || rtList.addr=='')
		return;

	var str = '';
	for (var i = 0; i < rtList.addr.length ; i++){
		str += '<tr align="center">';
		str += ("<td class=\"CELL\"><font face=\"Arial\" size=\"2\">"+ ((rtList.addr[i] == "239.255.255.250") ? "239.0.0.0":rtList.addr[i] )+"</font></td>");
		//str += ("<td class=\"CELL\"><font face=\"Arial\" size=\"2\">"+ compare_addr(rtList.addr[i])+"</font></td>");
		str += ("<td class=\"CELL\"><font face=\"Arial\" size=\"2\">"+ ((rtList.addr[i] == "239.255.255.250") ? "255.0.0.0":rtList.mask[i] )+"</font></td>");
		str += ("<td class=\"CELL\"><font face=\"Arial\" size=\"2\">"+ rtList.gw[i] +"</font></td>");
		str += ("<td class=\"CELL\"><font face=\"Arial\" size=\"2\">"+ rtList.metric[i] +"</font></td>");
		str += ("<td class=\"CELL\"><font face=\"Arial\" size=\"2\">"+ mapIface(rtList.iface[i]) +"</font></td>");
//		str += ("<td class=\"CELL\"><font face=\"Arial\" size=\"2\">"+ (rtList.type[i]==0? get_words('_dynamic'):get_words('_static')) +"</font></td>");	//wantype_up(rtList.type[i]) +"</font></td>");
//		str += ("<td class=\"CELL\"><font face=\"Arial\" size=\"2\">"+ compare_creator(i)+"</font></td>");	//creator_up(rtList.creator[i]) +"</font></td>");
		str += ("</tr>");
	}
	$('#routing_table').append(str);
}
	//0/name/192.168.31.0/255.255.255.0/192.168.31.1/192.168.31.1/20
	function Data(enable, name, ip_addr, net_mask, gateway, _interface, metric, onList)
	{
		this.Enable = enable;
		this.Name = name;
		this.Ip_addr = ip_addr;
		this.Net_mask = net_mask;
		this.Gateway = gateway;
		this.Interface = _interface;
		this.Metric = metric;
		this.OnList = onList ;
	}

	function set_routes()
	{
		var index = 0;
		for (var i = 0; i < rule_max_num; i++) {
			if(route_obj.enable[i]=="1")
			{
				TotalCnt++;
				DataArray[DataArray.length++] = new Data("1", route_obj.name[i], route_obj.ip[i], route_obj.mask[i], route_obj.gw[i], route_obj.iface[i], route_obj.metric[i], i);
			}
		}
		$('#max_row').val(TotalCnt);
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
			obj.add_param_arg('IGD_Layer3Forwarding_Forwarding_i_','1.1.'+idx+'.0');
			delCnt --;
			idx --;
		}

		obj.ajax_submit();
	}

	function del_row()
	{
		var del_idx = [];
		var checked_row = $('input[name=sel_del]:checked');
		if(checked_row.length==0)
		{
			alert(get_words('_adv_rtd'));
			return;
		}
		for(var i=0;i<checked_row.length;i++){
			del_idx.push(parseInt($(checked_row[i]).val(),10));
		}
		del_idx.reverse();
//		console.log(del_idx);
		for(var di in del_idx){
			var idx = del_idx[di];
			DataArray[idx].Enable = '0';
			DataArray[idx].Name = '';
			DataArray[idx].Ip_addr = '0.0.0.0';
			DataArray[idx].Net_mask = '0.0.0.0';
			DataArray[idx].Gateway = '0.0.0.0';
			DataArray[idx].Interface = '1';
			DataArray[idx].Metric = '1';
		}
		AddRoutetoDatamodel();
	}

	function update_DataArray(){
		var index = parseInt(get_by_id("edit").value);
		var insert = false;

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
			DataArray[index] = new Data("1", $('#name').val(), $('#Destination').val(), $('#Sub_Mask').val(), $('#Sub_gateway').val(), $('#interface').val(), $('#metric').val(), index+1);			
		}else if (index != -1){			
			DataArray[index].Enable = "1";
			DataArray[index].Name = index;
			DataArray[index].Ip_addr = $('#Destination').val();
			DataArray[index].Net_mask = $('#Sub_Mask').val();
			DataArray[index].Gateway = $('#Sub_gateway').val();
			DataArray[index].Interface = $('#interface').val();
			DataArray[index].Metric = $('#metric').val();
			DataArray[index].OnList = index;
		}
	}


	function clear_reserved(){
		
		$("input[name=sel_del]").each(function () { this.checked = false; });
		$('#name').val("");
		$('#Destination').val("0.0.0.0");
		$('#Sub_Mask').val("0.0.0.0");
		$('#Sub_gateway').val("0.0.0.0");
		$('#interface').val(1);
		$('#metric').val("1");
		$('#edit').val(-1);
		get_by_id("add").disabled = false;
	}

	function AddRoutetoDatamodel()
	{
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('adv_routing.asp');

		for(var i=0; i<DataArray.length; i++)
		{
			var instStr = "1.1."+ (i+1) +".0";
			obj.add_param_arg('fwdRule_Enable_',instStr,DataArray[i].Enable);
			obj.add_param_arg('fwdRule_Name_',instStr,DataArray[i].Name);
			obj.add_param_arg('fwdRule_DestIPAddress_',instStr,DataArray[i].Ip_addr);
			obj.add_param_arg('fwdRule_DestSubnetMask_',instStr,DataArray[i].Net_mask);
			obj.add_param_arg('fwdRule_SourceInterface_',instStr,DataArray[i].Interface);
			obj.add_param_arg('fwdRule_GatewayIPAddress_',instStr,DataArray[i].Gateway);
			obj.add_param_arg('fwdRule_ForwardingMetric_',instStr,DataArray[i].Metric);
		}
		var paramStr = obj.get_param();
		
		totalWaitTime = 20; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(paramStr.url, paramStr.arg);
	}

	function my_chk_addr(addrObj, maskObj)
	{
		var mask = new Array(255,255,255,0);
		if (addrObj == null || addrObj.addr.length != 4) {
			alert(addrObj.e_msg[INVALID_IP]);
			return false;
		}
		
		if((addrObj.addr[0] == "127") || ((addrObj.addr[0] >= 224) && (addrObj.addr[0] <= 239))){
			alert(addrObj.e_msg[MULTICASE_IP_ERROR]);
			return false;
		}
		
		// check the ip is "0.0.0.0"
		if (!addrObj.allow_zero && addrObj.addr[0] == '0' && addrObj.addr[1] == '0' && addrObj.addr[2] == '0' && addrObj.addr[3] == '0') {
			alert(addrObj.e_msg[ZERO_IP]);
			return false;
		}
		
		if (maskObj != null){
			mask = maskObj.addr;
		}
					
		var ip = addrObj.addr;
		var count_zero = 0;
		var count_bcast = 0;
		for (var i = 0; i < 4; i++){	// check the IP address is a network address or a broadcast address																							
			if (((~mask[i] + 256) & ip[i]) == 0){	// (~mask[i] + 256) = reverse mask[i]
				count_zero++;						
			}
			
			if ((mask[i] | ip[i]) == 255){
				count_bcast++;
			}							
		}
	
		if ((count_zero == 4 && !addrObj.is_network) || (count_bcast == 4)){
			alert(addrObj.e_msg[INVALID_IP]);
			return false;
		}
		return true;
	}

	//20130111 Silvia add, chk Dest ip is the same with subnet or not
	function check_subnet(ip_obj,mask_obj)
	{
		var tmp_ip = ip_obj.split(".");
		var tmp_mask = mask_obj.split(".");
		var tmp_subnet;
		var array_range = new Array(ip_obj.length);

		for (var i = 0; i < tmp_ip.length; i++)
		{
			array_range[i] = tmp_ip[i].toString(2) & tmp_mask[i].toString(2);
		}

		tmp_subnet = array_range[0]+"."+array_range[1]+"."+array_range[2]+"."+array_range[3];
		if (ip_obj != tmp_subnet)
		{
			alert(addstr(get_words('GW_ROUTES_DESTINATION_IP_ADDRESS_INVALID'),ip_obj));
			return false;
		}
		return true;
	}
	
	function send_request()
	{
		if (!is_form_modified("form1") && !confirm(get_words('_ask_nochange'))) {
			return false;
		}
		var count = 0;
		for (var i = 0; i < rule_max_num; i++) {

			if (get_by_id("Destination").value == "" )
					get_by_id("Destination").value="0.0.0.0";
			if (get_by_id("Sub_Mask").value == "" )
					get_by_id("Sub_Mask").value="0.0.0.0";
			if (get_by_id("Sub_gateway").value == "")
					get_by_id("Sub_gateway").value="0.0.0.0";

					var static_ip = get_by_id("Destination").value;
					var static_mask = get_by_id("Sub_Mask").value;
					var static_gateway = get_by_id("Sub_gateway").value;
					var metric = get_by_id("metric").value;
    			
					var ip_addr_msg = replace_msg(all_ip_addr_msg,get_words('help256'));
					var gateway_msg = replace_msg(all_ip_addr_msg,get_words('wwa_gw'));

					var static_ip_obj = new addr_obj(static_ip.split("."), ip_addr_msg, false, true);
					var static_mask_obj = new addr_obj(static_mask.split("."), subnet_mask_msg, false, false);
					var static_gateway_obj = new addr_obj(static_gateway.split("."), gateway_msg, false, true);
					var temp_metric = new varible_obj(metric, metric_msg, 1, 16, false);
					$('#name').val($('#max_row').val());
					var check_name = get_by_id("name").value;
				
					if (!check_route_address(static_ip_obj))
						return false;
					if(!check_route_mask(static_mask_obj))
						return false;

					if (!check_address(static_gateway_obj))
						return false;	// when gateway is invalid
					if (!check_varible(temp_metric))
						return false;
					if (!check_subnet(static_ip,static_mask))
						return false;

					if(trim_string(get_by_id("name").value) == ""){
						alert(get_words('aa_alert_9'));
						return false;
					}else {
						if(Find_word(check_name,"'") || Find_word(check_name,'"') || Find_word(check_name,"/"))
						{				
							alert(get_words('TEXT003').replace("+ i +","'" + check_name + "'"));
							return false;
						}
					
					for (jj=0; jj<DataArray.length; jj++) {
					if($('#edit').val()!=jj){
						if (get_by_id("Destination").value != "0.0.0.0") {
							if ((DataArray[jj].Ip_addr == static_ip) && (DataArray[jj].Interface == get_by_id("interface").value)) {
								alert(get_words('_r_alert_new1')+", '"+ get_by_id("name").value + "'" + get_words('help264') +" '" + DataArray[jj].Name + "'." );
								return false;
							}
						}
							    if(get_by_id("name").value == DataArray[jj].Name){
							        alert(addstr(get_words('av_alert_16'),get_by_id("name"+i).value));
							        return false;
							    }
							}
						}
					}					
					count++;			
		}

		if (submit_button_flag == 0) {
			update_DataArray();
			paintTable();
			clear_reserved();
			AddRoutetoDatamodel();
			//send_submit("form2");
			
			submit_button_flag = 1;
			return true;
		}

		return false;
	}
	
	function paintTable()
	{
		var contain = ""
			contain += '<div class="CT">'+get_words('_static_route_list')+'</div>'
			contain += '<table align="center" cellpadding="0" cellspacing="0" id="table1" class="formarea">';
			contain += '<tr class="break_word">';
			contain += '<td height="22" align="center" class="CTS"><b>'+get_words('_item_no')+'</b></td>';
//			contain += '<td height="22" align="center" class="CTS"><b>'+get_words('_name')+'</b></td>';
			contain += '<td height="22" align="center" class="CTS"><b>'+get_words('aa_AT_0')+'</b></td>';
			contain += '<td height="22" align="center" class="CTS"><b>'+get_words('_netmask')+'</b></td>';
			contain += '<td height="22" align="center" class="CTS"><b>'+get_words('_gateway')+'</b></td>';
			contain += '<td height="22" align="center" class="CTS"><b>'+get_words('_metric')+'</b></td>';
			contain += '<td height="22" align="center" class="CTS"><b>'+get_words('_interface')+'</b></td>';
			contain += '</tr>';
			
		for(var i = 0; i < DataArray.length; i++){
		if(DataArray[i].Enable=="1"){
			contain += '<tr align="center" class="break_word">'+
					"<td class=\"CELL\">"+(i+1)+"<input type='checkbox' id='sel_del' name='sel_del' value='"+ i +"' />&nbsp;&nbsp;"+
//					"</td><td align=center class=\"CELL\">" + DataArray[i].Name +
					"</td><td align=center class=\"CELL\">" + DataArray[i].Ip_addr +
					"</td><td align=center class=\"CELL\">" + DataArray[i].Net_mask +
					"</td><td align=center class=\"CELL\">" + DataArray[i].Gateway +
					"</td><td align=center class=\"CELL\">"+ DataArray[i].Metric +
					"</td><td align=center class=\"CELL\">"+ array_proto_name[DataArray[i].Interface] +
					"</td>";
			contain += '</tr>';
		}
		}
			
		contain += '<tr>';
		contain += '<td colspan="6" align="center" class="btn_field">';
		contain += '<input name="delete" type="button" class="ButtonSmall" id="delete" onClick="return del_row()" value="'+get_words("_delete")+'" />';
		contain += '</td>';
		contain += '</tr>';
		contain += '</table>';
		$('#StaticRoutingTable').html(contain);
		
		if(DataArray.length==0)
			$('#delete').attr('disabled',true);
	}
	
	/**
	 * RIP setting
	 */
	 function setValueEnableRIP(){
		var val_t = ripObj.trans;
		if(val_t=='0')
			$('#rip_enable').val(val_t);
		else
			$('#rip_enable').val('1');
	 }
	 function setEventEnableRIP(){
		var func = function(){
			var sel_e = $('#rip_enable').val();
			if(sel_e=='0'){
				$('#rip_mode').hide();
				$('#ripv2_password').hide();
			}
			else{
				$('#rip_mode').show();
				setEventRIPmode();
			}
		};
		func();
		$('#rip_enable').change(func);
	 }
	 function setValueRIPmode(){
		var val_t = ripObj.trans;
		if(val_t!='0')
			$('#ripv'+val_t).attr('checked', 'checked');
	 }
	 function setEventRIPmode(){
		var func = function(){
			var sel_v = $('input[name=rip_ver]:checked').val();
			if(sel_v=='2')
				$('#ripv2_password').show();
			else
			{
				/*
				** Date:	2013-03-18
				** Author:	Moa Chung
				** Reason:	Advanced → Routing：RIP can not be enabled.
				** Note:	TEW-810DR pre-test no.85
				**/
				$('#ripv1').attr('checked', true);
				$('#ripv2_password').hide();
			}
		};
		func();
		$('input[name=rip_ver]').change(func);
		
	 }
	 function setValueRIPv2Password(){
		var val_pwd = ripObj.pwd;
			$('#rip_pwd').val(val_pwd);
			$('#rip_pwd_t').val(val_pwd);
	 }
	 function rip_apply(){
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('adv_routing.asp');
		
		var rip_enable = $('#rip_enable').val();
		var rip_version = $('input[name=rip_ver]:checked').val();
		var rip_pwd = $('#rip_pwd').val();
		if(rip_enable=='0'){
			obj.add_param_arg('l3fwd_DynamicRouteTransmit_','1.1.0.0','0');
			obj.add_param_arg('l3fwd_DynamicRouteReceive_','1.1.0.0','0');
			obj.add_param_arg('l3fwd_V2Password_','1.1.0.0','');
		}
		else if(rip_version=='1'){
			obj.add_param_arg('l3fwd_DynamicRouteTransmit_','1.1.0.0','1');
			obj.add_param_arg('l3fwd_DynamicRouteReceive_','1.1.0.0','1');
			obj.add_param_arg('l3fwd_V2Password_','1.1.0.0','');
		}
		else if(rip_version=='2'){
			obj.add_param_arg('l3fwd_DynamicRouteTransmit_','1.1.0.0','2');
			obj.add_param_arg('l3fwd_DynamicRouteReceive_','1.1.0.0','2');
			obj.add_param_arg('l3fwd_V2Password_','1.1.0.0',rip_pwd);
		}
		
		var paramForm = obj.get_param();
		
		totalWaitTime = 20; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(paramForm.url, paramForm.arg);
	 }
$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(model);
});
</script>
</head>
<body onload="onPageLoad();">
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
				<script>document.write(menu.build_structure(1,1,2))</script>
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
							<script>show_words('_static_routing_settings');</script>
							</div>
							<div class="hr"></div>
							<div class="section_content_border">
							<div class="header_desc" id="acIntroduction">
								<script>show_words('_ROUTING_DESC');</script>
								<p></p>
							</div>

			<input type="hidden" id="dhcp_list" name="dhcp_list" value="" />
			<form id="form2" name="form2" method="post" action="get_set.ccp">
				<input type="hidden" name="ccp_act" value="set" />
				<input type="hidden" name="ccpSubEvent" value="CCP_SUB_WEBPAGE_APPLY" />
				<input type="hidden" name="nextPage" value="adv_routing.asp" />
				<script>
					var str = "";
					for(var i=1; i<=rule_max_num; i++)
					{
						var inst = '1.1.'+i+'.0';
						str += '<input type="hidden" name="fwdRule_Enable_'+inst+'" id="fwdRule_Enable_'+inst+'" value="" />';
						str += '<input type="hidden" name="fwdRule_Name_'+inst+'" id="fwdRule_Name_'+inst+'" value="" />';
						str += '<input type="hidden" name="fwdRule_DestIPAddress_'+inst+'" id="fwdRule_DestIPAddress_'+inst+'" value="" />';
						str += '<input type="hidden" name="fwdRule_DestSubnetMask_'+inst+'" id="fwdRule_DestSubnetMask_'+inst+'" value="" />';
						str += '<input type="hidden" name="fwdRule_SourceInterface_'+inst+'" id="fwdRule_SourceInterface_'+inst+'" value="1" />';
						str += '<input type="hidden" name="fwdRule_GatewayIPAddress_'+inst+'" id="fwdRule_GatewayIPAddress_'+inst+'" value="" />';
						str += '<input type="hidden" name="fwdRule_ForwardingMetric_'+inst+'" id="fwdRule_ForwardingMetric_'+inst+'" value="" />';
					}
					document.write(str);
				</script>
			</form>
			<form id="form1" name="form1" method="post" action="">
				<input type="hidden" id="html_response_page" name="html_response_page" value="back.asp" />
				<input type="hidden" id="html_response_message" name="html_response_message" value="" />
				<script>get_by_id("html_response_message").value = get_words('sc_intro_sv');</script>
				<input type="hidden" id="html_response_return_page" name="html_response_return_page" value="adv_routing.asp" />
				<input type="hidden" id="reboot_type" name="reboot_type" value="filter" />
				<input type="hidden" id="del" name="del" value="-1" />
				<input type="hidden" id="edit" name="edit" value="-1" />
				<input type="hidden" id="max_row" name="max_row" value="-1" />
			<p></p>
			<a name="show_list"></a>
<div class="box_tn">
	<div class="CT"><script>show_words('_add_static_route');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr style="display:none;">
		<td class="CL"><del><script>show_words('_name');</script></del></td>
		<td class="CR"><input type="text" id="name" name="name" size="16" maxlength="15" /></td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_dest_ip_addr');</script></td>
		<td class="CR"><input type="text" id="Destination" name="Destination" size="16" maxlength="15" value="0.0.0.0" /></td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_dest_ip_mask');</script></td>
		<td class="CR"><input type="text" id="Sub_Mask" name="Sub_Mask" size="16" maxlength="15" value="0.0.0.0" /></td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_gateway');</script></td>
		<td class="CR"><input type="text" id="Sub_gateway" name="Sub_gateway" size="16" maxlength="15" value="0.0.0.0" /></td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_metric');</script></td>
		<td class="CR"><input type="text" id="metric" name="metric" size="3" maxlength="2" value="1" />
			<script>show_words('_metric_msg');</script>
		</td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_interface');</script></td>
		<td class="CR"><select size="1" id="interface" name="interface">
			<script>
				document.write("<option value=\"1\">WAN</option>");
				if(connect_type=="6" || connect_type=="7" || connect_type=="8")
					document.write("<option value=\"2\">WAN("+get_words('_physical_port')+")</option>");
			</script>
		</select>
		</td>
	</tr>
	<tr>
		<td align="center" colspan="2" class="btn_field">
		<input name="add" type="button" class="ButtonSmall" id="add" onClick="return send_request()" value="" />
		<script>$('#add').val(get_words('_add'));</script>
		<input name="cancel" type="button" class="ButtonSmall" id="cancel" onClick="clear_reserved()" value="" />
		<script>$('#cancel').val(get_words('_cancel'));</script>
		</td>
	</tr>
	</table>
</div>
			</form>
			
<div class="box_tn">
	<span id="StaticRoutingTable"></span>
</div>
<script>set_routes();paintTable();</script>
<!-- RIP -->
<div class="box_tn">
	<div class="CT"><script>show_words('help670');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL"><script>show_words('_enable_rip');</script></td>
		<td class="CR">
			<select id="rip_enable">
				<option value="0"><script>show_words('_disable');</script></option>
				<option value="1"><script>show_words('_enable');</script></option>
			</select>
		</td>
	</tr>
	<tr id="rip_mode" style="display:none;">
		<td class="CL"><script>show_words('_rip_mode');</script></td>
		<td class="CR">
			<input type="radio" id="ripv1" name="rip_ver" size="16" maxlength="15" value="1" />v1
			<input type="radio" id="ripv2" name="rip_ver" size="16" maxlength="15" value="2" />v2
		</td>
	</tr>
	<tr id="ripv2_password" style="display:none;">
		<td class="CL"><script>show_words('_password');</script></td>
		<td class="CR"><input type="password" id="rip_pwd" name="rip_pwd" size="16" maxlength="15" onchange="key_word(this,'rip_pwd_t')" /><input type="text" id="rip_pwd_t" size="16" maxlength="15" class="init-hide" onchange="key_word(this,'rip_pwd')" />
		<input type="checkbox" modified="ignore" onclick="showHideBox(this,'rip_pwd,rip_pwd_t',true);" />&nbsp;<script>show_words('_Display')</script></td>
	</tr>
	<tr>
		<td align="center" colspan="2" class="btn_field">
			<input type="button" class="button_submit" id="btn_rip_apply" value="Apply" onclick="rip_apply();" />&nbsp;&nbsp;
			<script>$('#btn_rip_apply').val(get_words('_apply'));</script>
			<input type="reset" class="button_submit" id="btn_rip_cancel" value="Cancel" onclick="window.location.reload()" />
			<script>$('#btn_rip_cancel').val(get_words('_cancel'));</script>
		</td>
	</tr>
	</table>
</div>
<!-- Routing Table -->
<div class="box_tn">
	<div class="CT"><script>show_words('sr_RTable');</script></div>
	<table id="routing_table" align="center" cellpadding="0" cellspacing="0" class="formarea">
	<tr>
		<td class="CTS"><script>show_words('aa_AT_0');</script></td>
		<td class="CTS"><script>show_words('_netmask');</script></td>
		<td class="CTS"><script>show_words('_gateway');</script></td>
		<td class="CTS"><script>show_words('_metric');</script></td>
		<td class="CTS"><script>show_words('_interface');</script></td>
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