<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | Wireless 5GHz | Security</title>
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
	
	for (var i =5; i<= 8; i++)
	{
		main.add_param_arg('IGD_WLANConfiguration_i_','1'+i+'00');
		main.add_param_arg('IGD_WLANConfiguration_i_MACFilter_i_','1'+i+'00');
	}
	
	main.get_config_obj();
	
	var lanCfg = {
		'enable':			main.config_str_multi("wlanCfg_Enable_")	//gz_enable
	};
	var SSID = main.config_str_multi("wlanCfg_SSID_");
	var MACAction = main.config_str_multi("wlanCfg_MACAction_");
	var MACFilter = main.config_str_multi("wlanFMAC_MACAddress_");
	
	var changed = 0;
	var old_MBSSID;
	var max_SSID = 4;
	var max_MACrule = 24;
	var MACarray1 = new Array();
	var MACarray2 = new Array();
	var MACarray3 = new Array();
	var MACarray4 = new Array();
	
	
	function onPageLoad()
	{
		if(lanCfg.enable[0]==1)
			$('#radioOnField').show();
		else
			$('#radioOffField').show();
		//SSID List
		var total_ssid=0;
		for(var i=0; i<max_SSID; i++)
		{
			if(SSID[i]!="")
			{
				$('#ssidIndex').append('<option value='+i+'>'+SSID[i]+'</option>');
				total_ssid++;
			}
		}
		//Set MAC address into four array for checking value
		arrayMAC();
		//MAC address List for 4 SSID
		paintTable();
		
		//show one SSID and content
		selectMBSSIDChanged();
		set_form_default_values("form1");
	}
	
	//check which SSID is selected right now
	function selectMBSSIDChanged()
	{
		if(changed){
			ret = confirm(get_words('_wifiser_mode39'));
			if(!ret){
				$('#ssidIndex').val(old_MBSSID);
				return false;
			}
			else
				changed = 0;
			}
		old_MBSSID = $('#ssidIndex').val();
		MBSSIDChange($('#ssidIndex').val());
	}
	
	function MBSSIDChange(index)
	{
		// update Access Policy for MBSSID[selected]
		ShowAP(index);
		
		// clear all new access policy list field
		for(i=0; i<max_SSID; i++)
			$("#newap_text_"+i).val("");
		
		return true;
	}
	
	function ShowAP(index)
	{
		for(var i=0; i<max_SSID; i++){
			set_selectIndex(MACAction[index], $('#apselect_'+i)[0]);
			if(index != i)
			{
				$('#AccessPolicy_'+i).hide();
				$('#AccessPolicy3_'+i).hide();
			}
			else
			{
				$('#AccessPolicy_'+i).show();
				$('#AccessPolicy3_'+i).show();
			}
		}
	}

	function setChange(c){
		changed = c;
	}
	
	function del_row(row){	
		if(!confirm(get_words('YM35')+" "+ MACFilter[row] +"?"))
			return;
		
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('wireless2_security.asp');
		var inst;
		if(row>=0 && row<=23)
		{
			inst=5;
		}
		else if(row>=24 && row<=47)
		{
			inst=6;
			row-=24;
		}
		else if(row>=48 && row<=71)
		{
			inst=7;
			row-=48;
		}
		else
		{
			inst=8;
			row-=72;
		}
		row++;
		obj.add_param_arg('wlanFMAC_MACAddress_','1.'+inst+'.'+row+'.0','00:00:00:00:00:00');
		var param = obj.get_param();
		
		totalWaitTime = 20; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(param.url, param.arg);
	}

	function paintTable()
	{
		//MACFilter
		var aptable;
		for(aptable=0; aptable < max_SSID; aptable++){
			var contain = "";
			contain += "<div class='box_tn' style='display:none' id='AccessPolicy3_"+ aptable +"'><table width='100%' border='0' align='center' cellpadding='0' cellspacing='0' class='formarea'>";	
			contain += "<tr><td colspan='2' class='CT'>"+get_words('_wifiser_mode40')+"</td></tr>"
			contain += "<tr align=\"center\">";
			contain += "<td width=\"80%\" class='CTS'>"+get_words('_macaddr')+"</td>";
			contain += "<td width=\"20%\" class='CTS'>"+get_words('_delete')+"</td>";
			contain += "</tr>";
			
			for(var i=(aptable*max_MACrule); i < (aptable*max_MACrule+max_MACrule); i++){
				if(MACFilter[i]!="00:00:00:00:00:00"){
					contain += "<tr align=center>"+
							"<td align=center class='CELL'>"+ MACFilter[i] +
							"</td><td class='CELL'><center><a href=\"javascript:del_row(" + i +")\"><img src=\"delete.gif\"  border=\"0\" title=\""+get_words('_delete')+"\" /></a></center>" +
							"</td></tr>";
				}
			}
			contain += "</table></div>";
			$('#WSTable'+aptable).html(contain);
		}
	}
	
	function arrayMAC()
	{
		//Each SSID has 24 MAC address
		for(var i=0; i<max_SSID; i++)
		{
			var temMAC = new Array();
			
			for(var j=0; j<max_MACrule; j++)
			{
				if(MACFilter[i*max_MACrule+j] != "00:00:00:00:00:00")
					temMAC[temMAC.length++] = MACFilter[i*max_MACrule+j];
			}
			switch (i)
			{
				case 0:
					MACarray1 = temMAC;
					break;
				case 1:
					MACarray2 = temMAC;
					break;
				case 2:
					MACarray3 = temMAC;
					break;
				case 3:
					MACarray4 = temMAC;
					break;
			}
		}
	}
	
	function send_request()
	{
		//check data
		if (!checkData())
			return false;
		
		var idx = parseInt($('#ssidIndex').val(),10) +5;
		
		//submit
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('wireless2_security.asp');
		
		obj.add_param_arg('wlanCfg_MACAction_','1.'+idx+'.0.0',$("#apselect_"+(idx-5)).val());
		
		var temMAC = new Array();
		
		switch(idx-4)
		{
			case 1:
				temMAC = MACarray1;
				break;
			case 2:
				temMAC = MACarray2;
				break;
			case 3:
				temMAC = MACarray3;
				break;
			case 4:
				temMAC = MACarray4;
				break;
		}
		
		if($('#newap_text_'+(idx-5)).val() != "")
			temMAC[temMAC.length++] = $('#newap_text_'+(idx-5)).val();
			
		for(var i=0; i < max_MACrule; i++)
		{
			if(temMAC[i] != null)
				obj.add_param_arg('wlanFMAC_MACAddress_','1.'+idx+'.'+(i+1)+'.0',temMAC[i]);
			else
				obj.add_param_arg('wlanFMAC_MACAddress_','1.'+idx+'.'+(i+1)+'.0','00:00:00:00:00:00');
		}
		var paramForm = obj.get_param();
		
		totalWaitTime = 20; //second
		redirectURL = location.pathname;
		wait_page();
		jq_ajax_post(paramForm.url, paramForm.arg);
	}

	function checkData()
	{
		//Check Access Policy
		if(!chkMACPolicy())
			return false;
		
		return true;	
	}
	
	function chkMACPolicy()
	{
		var idx = $('#ssidIndex').val();
		var chkMAC = new Array();
		
		if(idx == "0")
			chkMAC = MACarray1;
		if(idx == "1")
			chkMAC = MACarray2;
		if(idx == "2")
			chkMAC = MACarray3;
		if(idx == "3")
			chkMAC = MACarray4;
			
		var keyvalue = $('#newap_text_'+idx).val();
		if(keyvalue != "")
		{
			if(chkMAC.length>=24)
			{
				alert(get_words('_wifiser_mode26'));
			}
			if (!check_mac(keyvalue))
			{
				alert(get_words('LS47'));
				return false;
			}
			if(keyvalue.toLowerCase() == "ff:ff:ff:ff:ff:ff")
			{
				alert(get_words('_wifiser_mode25'));
				return false;
			}
			if(chkMAC.indexOf(keyvalue)>=0)
			{
				alert(addstr(get_words('GW_MAC_FILTER_MAC_UNIQUENESS_INVALID'),keyvalue));
				return false;
			}
			/*
			if( broadcastMAC(apMacObj) == true || multicastMAC(apMacObj) == true ) {
				alert("Multicast/Broadcast MAC address is not allowed.");
				return false;
			}
			*/
		}	
		return true;
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
				<script>document.write(menu.build_structure(1,3,3))</script>
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
								<script>show_words('_wifiser_title');</script>
								</div>
								<div class="hr"></div>
								<div class="section_content_border">
								<div class="header_desc" id="basicIntroduction">
									<script>show_words('_wifiser_mode42');</script>
									<p></p>
								</div>
			<form id="form1" name="form1" method="post" action="">
				<input type="hidden" id="html_response_page" name="html_response_page" value="back.asp" />
				<input type="hidden" id="html_response_message" name="html_response_message" value="" />
				<script>$('#html_response_message').val(get_words('sc_intro_sv'));</script>
				<input type="hidden" id="html_response_return_page" name="html_response_return_page" value="wireless_security.asp" />
				<input type="hidden" id="reboot_type" name="reboot_type" value="wireless" />
				<input type="hidden" id="wlan0_ssid" name="wlan0_ssid" value='' />
				<input type="hidden" id="wlan1_ssid" name="wlan1_ssid" value='' />
				<input type="hidden" id="wps_pin" name="wps_pin" value='' />
				<input type="hidden" id="wps_configured_mode" name="wps_configured_mode" value='' />
				<input type="hidden" id="wlan0_wep_display" name="wlan0_wep_display" value='' />
				<input type="hidden" id="wlan1_wep_display" name="wlan1_wep_display" value='' />
				<input type="hidden" id="wlan0_schedule" name="wlan0_schedule" value='' />
				<input type="hidden" id="wlan1_schedule" name="wlan1_schedule" value='' />
				<input type="hidden" id="apply" name="apply" value="0" />
				
<div id="radioOnField" style="display: none;">
	<div class="box_tn">
		<div class="CT"><script>show_words('_wifiser_title0');</script></div>
		<table cellspacing="0" cellpadding="0" class="formarea">
		<tr>
			<td class="CL"><script>show_words('_wifiser_title1')</script></td>
			<td class="CR">
				<select id="ssidIndex" name="ssidIndex" size="1" onchange="selectMBSSIDChanged()"></select>
			</td>
		</tr>
		</table>
	</div>

	<!--	AccessPolicy for mbssid 		-->
	<div class="box_tn">
		<span id="mac_filter"></span>
		<script>
		var aptable;
		var contain = ""

		for(aptable = 0; aptable < max_SSID; aptable++){
			contain += "<table style='display:none;' id=AccessPolicy_"+ aptable +" width='100%' border='0' align='center' cellpadding='0' cellspacing='0' class='formarea'>";	
			contain += "<tr><td colspan='2' class='CT'>"+get_words('_wifiser_mode22')+"</td></tr>"
			contain += "<input type=\"hidden\" id=newap_"+ aptable + "_num name=newap_"+ aptable + "_num value=0>";
			contain += "<tr><td class=\"CL\">"+get_words('_wifiser_mode23')+"</td>";
			contain += "<td class=\"CR\"><select name=apselect_"+ aptable + " id=apselect_"+aptable+" size=1 onchange=setChange(1) >";
			contain += "<option value=0 >"+get_words('_disable')+"</option>";
			contain += "<option value=1 >"+get_words('_wifiser_mode43')+"</option>";
			contain += "<option value=2 >"+get_words('_wifiser_mode24')+"</option>";
			contain += "</select> </td>";
			contain += "</tr>";	
			contain += "<tr><td class=\"CL\">"+get_words('_macaddr')+"</td>";
			contain += "<td class=\"CR\"><input name=newap_text_"+aptable+" id=newap_text_"+aptable+" size=16 maxlength=20>&nbsp;(Ex: 00:11:22:33:44:55)</td>";
			contain += "</tr>";
			contain += "</table>";
		}

		$('#mac_filter').html(contain);

		</script>
	</div>

	<div class="box_tn">
		<table cellspacing="0" cellpadding="0" class="formarea">
		<tr align="center">
			<td colspan="2" class="btn_field">
			<input name="button" type="button" class="ButtonSmall" id="button" onClick="return send_request()" value="" />
			<script>$('#button').val(get_words('_apply'));</script>
			<input name="button2" type="button" class="ButtonSmall" id="button2" onclick="page_cancel('form1', 'wireless2_security.asp');" value="" />
			<script>$('#button2').val(get_words('ES_cancel'));</script>
			</td>
		</tr>
		</table>
	</div>

	<span id="WSTable0"></span>
	<span id="WSTable1"></span>
	<span id="WSTable2"></span>
	<span id="WSTable3"></span>
</div>
</form>

<div id="radioOffField" class="box_tn" style="display: none;">
	<table cellspacing="0" cellpadding="0" class="formarea">
		<tr>
			<td colspan="2" align="center" class="CELL"><font color="red" id="Msg" name="Msg"><script>show_words('_wifiser_mode41');</script></font></td>
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