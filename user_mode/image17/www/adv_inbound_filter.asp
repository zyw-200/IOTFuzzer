<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | Advanced | Inbound Filter</title>
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
	var max_ib_len = 10;
	var RULES_IN_IFLTER = 8;
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

	var ib = new ccpObject();
	ib.set_param_url('used_check.ccp');
	ib.set_ccp_act('getStOfIbfilter');
	ib.get_config_obj();
	
	var usedIbfilter = ib.config_val("usedIbfilter");
	
	var main = new ccpObject();
	main.set_param_url('get_set.ccp');
	main.set_ccp_act('get');
	main.add_param_arg('IGD_',1000);
	main.add_param_arg('IGD_WANDevice_i_InboundFilter_i_',1100);
	
	main.get_config_obj();
	
	var dev_mode = main.config_val("igd_DeviceMode_");

	var array_rule_inst = main.config_inst_multi("IGD_WANDevice_i_InboundFilter_i_");
	var array_name = main.config_str_multi("ibFilter_Name_");
	var array_action = main.config_str_multi("ibFilter_Action_");
	var array_ipaddr = main.config_str_multi("ibFilter_IPAddress_");

	var submit_button_flag = 0;
	var rule_max_num = 10;
	var DataArray = new Array();
	var TotalCnt=0;

	function onPageLoad()
	{
		var login_who= login_Info;
		if(login_who!= "w" || dev_mode == "1"){
			DisableEnableForm(form1,true);
		} 
	}

	function Data(name, action, ipaddr, onList)
	{
		this.Name = name;
		this.IbFaction = action;
		this.IbFactionIP = ipaddr;
		this.OnList = onList ;
	}

	function set_arrayvalue()
	{
		var index = 0;
		for (var i = 0; i < rule_max_num; i++)
		{
			if (array_name)
			{
				if(array_name[i] != "" && array_name[i])
				{
					TotalCnt++;
					DataArray[DataArray.length++] = new Data(array_name[i], array_action[i], array_ipaddr[i], i);
				}
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
			obj.add_param_arg('IGD_WANDevice_i_InboundFilter_i_','1.1.'+idx+'.0');
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
			DataArray[i].Name = DataArray[i+1].Name;
			DataArray[i].IbFaction = DataArray[i+1].IbFaction;
			DataArray[i].IbFactionIP = DataArray[i+1].IbFactionIP;
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
		
		obj.add_param_arg('IGD_WANDevice_i_InboundFilter_i_','1.1.'+TotalCnt+'.0');

		obj.ajax_submit();
		AddApptoDatamodel();
	}

	function update_DataArray()
	{
		var index = parseInt($('#edit').val());
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
			DataArray[index] = new Data($('#ingress_filter_name').val(), get_checked_value(get_by_name('action_select')), $('#ip').val(), index+1);			
		}else if (index != -1){
			DataArray[index].Name = $('#ingress_filter_name').val();
			DataArray[index].IbFaction = get_checked_value(get_by_name('action_select'));
			DataArray[index].IbFactionIP = $('#ip').val();
			DataArray[index].OnList = index;
		}
	}

	function clear_reserved()
	{
		$("input[name=sel_del]").each(function () { this.checked = false; });
		$('#ingress_filter_name').val("");
		set_checked(0, get_by_name('action_select'));
		$('#ip').val("");
		$('#edit').val(-1);
		$('#add').attr('disabled', '');
        $('#clear').attr('disabled', true);
	}

	function edit_row(idx)
	{
        $('#ingress_filter_name').val(DataArray[idx].Name);
        set_checked(DataArray[idx].IbFaction, get_by_name('action_select'));
		$('#ip').val(DataArray[idx].IbFactionIP);
		$('#edit').val(idx);
		$('#add').val(get_words('_save'));
		$('#clear').attr('disabled', '');
	}

	function AddApptoDatamodel(st)
	{
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('adv_inbound_filter.asp');
		
		for(var i=0; i<DataArray.length; i++)
		{
			var instStr = "1.1."+ (i+1) +".0";
			obj.add_param_arg('ibFilter_Name_',instStr,DataArray[i].Name);
			obj.add_param_arg('ibFilter_Action_',instStr,DataArray[i].IbFaction);
			obj.add_param_arg('ibFilter_IPAddress_',instStr,DataArray[i].IbFactionIP);
		}

		var paramStr=obj.get_param();

		totalWaitTime = 20; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(paramStr.url, paramStr.arg);
	}
	
	function send_request()
	{
		if (!is_form_modified("form1") && !confirm(get_words('_ask_nochange'))) {
			return false;
		}

		var check_name = $('#ingress_filter_name').val();
		var ip = $('#ip').val();
		var ip_addr_msg = replace_msg(all_ip_addr_msg, get_words('ES_IP_ADDR'));
		var temp_ip_obj = new addr_obj(ip.split("."), ip_addr_msg, false, false);

		if (check_name != "")
		{
			for (var j = 0; j < DataArray.length; j++)
			{
				if($('#edit').val()!=j){
					if (check_name == DataArray[j].Name){
						alert(addstr(get_words('_adv_txt_26'),get_words('_inboundfilter'),check_name));
						return false;
					}
					if (ip == DataArray[j].IbFactionIP){
						alert(get_words('ag_inuse')+"( "+ip+" )");
						return false;
					}
				}
			}

			if (!chk_chars(check_name))
			{
				alert(get_words('_specappsr') + addstr(get_words('_adv_txt_02'), check_name));
				return false;
			}

			if (!check_address(temp_ip_obj)){
				return false;
			}
		}else{
			alert(get_words('GW_FIREWALL_RULE_NAME_INVALID'));
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

	function paintTable()
	{
		var contain = '<div class="box_tn" id="table1">';
			contain += '<div class="CT">'+get_words('_adv_txt_27')+'</div>';
			contain += '<table cellspacing="0" cellpadding="0" class="formarea">';
			contain += '<tr><td height="22" align="center" class="CTS"><b>'+get_words('_adv_txt_08')+'</b></td>';
			contain += '<td height="22" align="center" class="CTS"><b>'+get_words('_adv_txt_14')+'</b></td>';
			contain += '<td height="22" align="center" class="CTS"><b>'+get_words('_ipaddr')+'</b></td>';
			contain += '<td height="22" align="center" class="CTS"><b>'+get_words('_edit')+'</b></td>';
			contain += '<td height="22" align="center" class="CTS"><b>'+get_words('_delete')+'</b></td>';
			contain += '</tr>';

		for(var i = 0; i < DataArray.length; i++)
		{
			contain += '<tr align="center">'+
					'<td align="center" class="CELL">' + DataArray[i].Name +
					'</td><td align="center" class="CELL">' + ((DataArray[i].IbFaction == '0')? get_words('_allow'): get_words('_deny')) +
					'</td><td align="center" class="CELL">' + DataArray[i].IbFactionIP +
					'</td><td align="center" class="CELL">'+
					'<a href="javascript:edit_row('+ i +')"><img src="edit.gif" border="0" title="'+get_words('_edit')+'" /></a>'+
					'</td><td align="center" class="CELL">'+
					'<a href="javascript:del_row(' + i +')"><img src="delete.gif" border="0" title="'+get_words('_delete')+'" /></a>'+
					'</td>';
		}

		contain += '</tr>';
		contain += '</table>';
		contain += '</div>';
		$('#IBFTable').html(contain);

		set_form_default_values("form1");
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
				<script>document.write(menu.build_structure(1,4,1))</script>
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
							<script>show_words('_inboundfilter');</script>
							</div>
							<div class="hr"></div>
							<div class="section_content_border">
							<div class="header_desc" id="introduction">
								<script>show_words('_adv_txt_23');</script>
								<p></p>
							</div>

			<input type="hidden" name="ccp_act" value="set" />
			<input type="hidden" name="nextPage" value="adv_inbound_filter.asp" />
			<input type="hidden" id="ibFilter_inst" value="" />
			<input type="hidden" id="edit" name="edit" value="-1" />
<form id="form1" name="form1" method="post" action="get_set.ccp">
<div class="box_tn">
	<div class="CT"><script>show_words('_adv_txt_24');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL"><script>show_words('_adv_txt_08')</script></td>
		<td class="CR">
			<input type="text" id="ingress_filter_name" size="20" maxlength="15" />
		</td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_adv_txt_14')</script></td>
		<td class="CR">
			<input type="radio" id="action_select" name="action_select" value="0" checked>
			<script>show_words('_allow')</script>
			<input type="radio" id="action_select" name="action_select" value="1" />
			<script>show_words('_deny')</script>
		</td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_ipaddr')</script></td>
		<td class="CR">
			 <input type="text" id="ip" size="20" maxlength="15" />
		</td>
	</tr>
	<tr align="center">
		<td colspan="2" class="btn_field">
			<input name="add" type="button" class="ButtonSmall" id="add" onClick="return send_request()" />
			<script>$('#add').val(get_words('_add'));</script>
			<input name="clear" type="button" disabled class="ButtonSmall" id="clear" onClick="return send_request()" />
			<script>$('#clear').val(get_words('_clear'));</script>
		</td>
	</tr>
	</table>
</div>
</form>
<span id="IBFTable"></span>
<script>
	set_arrayvalue();
	paintTable();
</script>
			<br/>
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