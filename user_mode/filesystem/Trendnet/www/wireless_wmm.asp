<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>Wireless WMM Settings</title>
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="-1" />
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
	document.title = get_words('_tl_wmm_settings');
	
	var main = new ccpObject();
	main.set_param_url('get_set.ccp');
	main.set_ccp_act('get');
	main.add_param_arg('IGD_WLANConfiguration_i_WMM_i_',1100);
	main.get_config_obj();
	
var wmmCfg = {
	'aifsn':			main.config_str_multi("wmmInfo_Aifsn_"),
	'cwmin':			main.config_str_multi("wmmInfo_CWMin_"),
	'cwmax':			main.config_str_multi("wmmInfo_CWMax_"),
	'txop':				main.config_str_multi("wmmInfo_Txop_"),
	'acm':				main.config_str_multi("wmmInfo_ACM_"),
	'ackpolicy':		main.config_str_multi("wmmInfo_AckPolicy_")
};
function check_value(){
	var obj = new ccpObject();
	obj.set_param_url('get_set.ccp');
	obj.set_ccp_act('set');
	obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
	obj.set_param_next_page('wireless_wmm.asp');
	var cols = ['Aifsn','CWMin','CWMax','Txop','ACM','AckPolicy'];
	var rows = ['acbe','acbk','acvi','acvo'];
	for(var c=0;c<cols.length;c++)
	{
		for(var r=0;r<rows.length;r++)
		{
			var name = 'ap_'+cols[c].toLowerCase()+'_'+rows[r];
			
			if(cols[c]=='ACM' || cols[c]=='AckPolicy'){
				obj.add_param_arg('wmmInfo_'+cols[c]+'_','1.1.'+(r+1)+'.0',($('input[name='+name+']').is(":checked")?1:0));
			}
			else if(cols[c]=='CWMin' || cols[c]=='CWMax'){
				obj.add_param_arg('wmmInfo_'+cols[c]+'_','1.1.'+(r+1)+'.0',$('select[name='+name+']').val());
			}
			else
			{
				obj.add_param_arg('wmmInfo_'+cols[c]+'_','1.1.'+(r+1)+'.0',$('input[name='+name+']').val());
			}
		}
	}
	for(var c=0;c<cols.length-1;c++)
	{
		for(var r=0;r<rows.length;r++)
		{
			var name = 'sta_'+cols[c].toLowerCase()+'_'+rows[r];
			
			if(cols[c]=='ACM'){
				obj.add_param_arg('wmmInfo_'+cols[c]+'_','1.1.'+(r+5)+'.0',($('input[name='+name+']').is(":checked")?1:0));
			}
			else if(cols[c]=='CWMin' || cols[c]=='CWMax')
			{
				obj.add_param_arg('wmmInfo_'+cols[c]+'_','1.1.'+(r+5)+'.0',$('select[name='+name+']').val());
			}
			else
			{
				obj.add_param_arg('wmmInfo_'+cols[c]+'_','1.1.'+(r+5)+'.0',$('input[name='+name+']').val());
			}
		}
	}
//	console.log('Query',basic);
	
	obj.get_config_obj();
}
$(function(){
	//load value
	var cols = ['aifsn','cwmin','cwmax','txop','acm','ackpolicy'];
	var rows = ['acbe','acbk','acvi','acvo'];

	//Access Point
	for(var c=0;c<cols.length;c++) {
		for(var r=0;r<rows.length;r++) {
			var name = 'ap'+'_'+cols[c]+'_'+rows[r];
			if(cols[c]=='acm' || cols[c]=='ackpolicy'){
				$('input[name='+name+']').attr('checked', (wmmCfg[cols[c]][r]==1?true:false));
			}
			else if(cols[c]=='cwmin' || cols[c]=='cwmax'){
				$('select[name='+name+']').val(wmmCfg[cols[c]][r]);
			}
			else{
				$('input[name='+name+']').val(wmmCfg[cols[c]][r]);
			}
//			console.log(name);
//			console.log('wmmCfg.'+cols[c]+'['+r+']', eval('wmmCfg.'+cols[c]+'['+r+']'));
		}
	}
	//Station
	for(var c=0;c<cols.length-1;c++) {
		for(var r=0;r<rows.length;r++) {
			var name = 'sta'+'_'+cols[c]+'_'+rows[r];
			if(cols[c]=='acm'){
				$('input[name='+name+']').attr('checked', (wmmCfg[cols[c]][r+4]==1?true:false));
			}
			else if(cols[c]=='cwmin' || cols[c]=='cwmax'){
				$('select[name='+name+']').val(wmmCfg[cols[c]][r+4]);
			}
			else{
				$('input[name='+name+']').val(wmmCfg[cols[c]][r+4]);
			}
//			console.log(name);
//			console.log('wmmCfg.'+cols[c]+'['+(r+4)+']',eval('wmmCfg.'+cols[c]+'['+(r+4)+']'));
		}
	}

});
</script>
<body>
	<table align="center" width="540" border="0" cellspacing="0" cellpadding="0">
		<tr> 
			<td align="center" colspan="7" class="CT"><font color="#FFFFFF" id="wmmAP"><b><script>show_words('_lb_wmm_param_ap');</script></b></font></td>
		</tr>
		<tr>
			<td align="center" nowrap class="CELL">&nbsp;</td>
			<td align="center" nowrap class="CELL">Aifsn</td>
			<td align="center" nowrap class="CELL">CWMin</td>
			<td align="center" nowrap class="CELL">CWMax</td>
			<td align="center" nowrap class="CELL">Txop</td>
			<td align="center" nowrap class="CELL">ACM</td>
			<td align="center" nowrap class="CELL">AckPolicy</td>
		</tr>
		<tr>
			<td align="center" nowrap class="CELL"><b>AC_BE</b></td>
			<td align="center" nowrap class="CELL"><input type="text" name="ap_aifsn_acbe" size="4" maxlength="4" /></td>
			<td align="center" nowrap class="CELL">
				<select name="ap_cwmin_acbe" size="1">
					<option value="1">1</option>
					<option value="3">3</option>
					<option value="7">7</option>
					<option value="15">15</option>
				</select>
			</td>
			<td align="center" nowrap class="CELL">
				<select name="ap_cwmax_acbe" size="1">
					<option value="1">1</option>
					<option value="3">3</option>
					<option value="7">7</option>
					<option value="15">15</option>
					<option value="31">31</option>
					<option value="63">63</option>
				</select>
			</td>
			<td align="center" nowrap class="CELL"><input type="text" name="ap_txop_acbe" size="4" maxlength="4" /></td>
			<td align="center" nowrap class="CELL"><input type="checkbox" name="ap_acm_acbe" value="1" /></td>
			<td align="center" nowrap class="CELL"><input type="checkbox" name="ap_ackpolicy_acbe" value="1" /></td>
		</tr>
		<tr>
			<td align="center" nowrap class="CELL"><b>AC_BK</b></td>
			<td align="center" nowrap class="CELL"><input type="text" name="ap_aifsn_acbk" size="4" maxlength="4" /></td>
			<td align="center" nowrap class="CELL">
				<select name="ap_cwmin_acbk" size="1">
					<option value="1">1</option>
					<option value="3">3</option>
					<option value="7">7</option>
					<option value="15">15</option>
				</select>
			</td>
			<td align="center" nowrap class="CELL">
				<select name="ap_cwmax_acbk" size="1">
					<option value="1">1</option>
					<option value="3">3</option>
					<option value="7">7</option>
					<option value="15">15</option>
					<option value="31">31</option>
					<option value="63">63</option>
					<option value="127">127</option>
					<option value="255">255</option>
					<option value="511">511</option>
					<option value="1023">1023</option>
				</select>
			</td>
			<td align="center" nowrap class="CELL"><input type="text" name="ap_txop_acbk" size="4" maxlength=4></td>
			<td align="center" nowrap class="CELL"><input type="checkbox" name="ap_acm_acbk" value="1" /></td>
			<td align="center" nowrap class="CELL"><input type="checkbox" name="ap_ackpolicy_acbk" value="1" /></td>
		</tr>
		<tr>
			<td align="center" nowrap class="CELL"><b>AC_VI</b></td>
			<td align="center" nowrap class="CELL"><input type="text" name="ap_aifsn_acvi" size="4" maxlength="4" /></td>
			<td align="center" nowrap class="CELL">
				<select name="ap_cwmin_acvi" size="1">
					<option value="1">1</option>
					<option value="3">3</option>
					<option value="7">7</option>
				</select>
			</td>
			<td align="center" nowrap class="CELL">
				<select name="ap_cwmax_acvi" size="1">
					<option value="1">1</option>
					<option value="3">3</option>
					<option value="7">7</option>
					<option value="15">15</option>
				</select>
			</td>
			<td align="center" nowrap class="CELL"><input type="text" name="ap_txop_acvi" size="4" maxlength="4" /></td>
			<td align="center" nowrap class="CELL"><input type="checkbox" name="ap_acm_acvi" value="1" /></td>
			<td align="center" nowrap class="CELL"><input type="checkbox" name="ap_ackpolicy_acvi" value="1" /></td>
		</tr>
		<tr>
			<td align="center" nowrap class="CELL"><b>AC_VO</b></td>
			<td align="center" nowrap class="CELL"><input type="text" name="ap_aifsn_acvo" size="4" maxlength="4" /></td>
			<td align="center" nowrap class="CELL">
				<select name="ap_cwmin_acvo" size="1">
					<option value="1">1</option>
					<option value="3">3</option>
				</select>
			</td>
			<td align="center" nowrap class="CELL">
				<select name="ap_cwmax_acvo" size="1">
					<option value="1">1</option>
					<option value="3">3</option>
					<option value="7">7</option>
				</select>
			</td>
			<td align="center" nowrap class="CELL"><input type="text" name="ap_txop_acvo" size="4" maxlength="4" /></td>
			<td align="center" nowrap class="CELL"><input type="checkbox" name="ap_acm_acvo" value="1" /></td>
			<td align="center" nowrap class="CELL"><input type="checkbox" name="ap_ackpolicy_acvo" value="1" /></td>
		</tr>
	</table>
	<br/>
	<table align="center" width="540" border="0" cellspacing="0" cellpadding="0">
		<tr> 
			<td align="center" colspan="7" class="CT"><font color="#FFFFFF" id="wmmSTA"><b><script>show_words('_lb_wmm_param_station');</script></b></font></td>
		</tr>
		<tr>
			<td align="center" nowrap class="CELL">&nbsp;</td>
			<td align="center" nowrap class="CELL">Aifsn</td>
			<td align="center" nowrap class="CELL">CWMin</td>
			<td align="center" nowrap class="CELL">CWMax</td>
			<td align="center" nowrap class="CELL">Txop</td>
			<td align="center" nowrap class="CELL">ACM</td>
		</tr>
		<tr>
			<td align="center" nowrap class="CELL"><b>AC_BE</b></td>
			<td align="center" nowrap class="CELL"><input type="text" name="sta_aifsn_acbe" size="4" maxlength="4" /></td>
			<td align="center" nowrap class="CELL">
				<select name="sta_cwmin_acbe" size="1">
					<option value="1">1</option>
					<option value="3">3</option>
					<option value="7">7</option>
					<option value="15">15</option>
				</select>
			</td>
			<td align="center" nowrap class="CELL">
				<select name="sta_cwmax_acbe" size="1">
					<option value="1">1</option>
					<option value="3">3</option>
					<option value="7">7</option>
					<option value="15">15</option>
					<option value="31">31</option>
					<option value="63">63</option>
					<option value="127">127</option>
					<option value="255">255</option>
					<option value="511">511</option>
					<option value="1023">1023</option>
				</select>
			</td>
			<td align="center" nowrap class="CELL"><input type="text" name="sta_txop_acbe" size="4" maxlength="4" /></td>
			<td align="center" nowrap class="CELL"><input type="checkbox" name="sta_acm_acbe" value="1" /></td>
		</tr>
		<tr>
			<td align="center" nowrap class="CELL"><b>AC_BK</b></td>
			<td align="center" nowrap class="CELL"><input type="text" name="sta_aifsn_acbk" size="4" maxlength="4" /></td>
			<td align="center" nowrap class="CELL">
				<select name="sta_cwmin_acbk" size="1">
					<option value="1">1</option>
					<option value="3">3</option>
					<option value="7">7</option>
					<option value="15">15</option>
				</select>
			</td>
			<td align="center" nowrap class="CELL">
				<select name="sta_cwmax_acbk" size="1">
					<option value="1">1</option>
					<option value="3">3</option>
					<option value="7">7</option>
					<option value="15">15</option>
					<option value="31">31</option>
					<option value="63">63</option>
					<option value="127">127</option>
					<option value="255">255</option>
					<option value="511">511</option>
					<option value="1023">1023</option>
				</select>
			</td>
			<td align="center" nowrap class="CELL"><input type="text" name="sta_txop_acbk" size="4" maxlength="4" /></td>
			<td align="center" nowrap class="CELL"><input type="checkbox" name="sta_acm_acbk" value="1" /></td>
		</tr>
		<tr>
			<td align="center" nowrap class="CELL"><b>AC_VI</b></td>
			<td align="center" nowrap class="CELL"><input type="text" name="sta_aifsn_acvi" size="4" maxlength="4" /></td>
			<td align="center" nowrap class="CELL">
				<select name="sta_cwmin_acvi" size="1">
					<option value="1">1</option>
					<option value="3">3</option>
					<option value="7">7</option>
				</select>
			</td>
			<td align="center" nowrap class="CELL">
				<select name="sta_cwmax_acvi" size="1">
					<option value="1">1</option>
					<option value="3">3</option>
					<option value="7">7</option>
					<option value="15">15</option>
				</select>
			</td>
			<td align="center" nowrap class="CELL"><input type="text" name="sta_txop_acvi" size="4" maxlength="4" /></td>
			<td align="center" nowrap class="CELL"><input type="checkbox" name="sta_acm_acvi" value="1" /></td>
		</tr>
		<tr>
			<td align="center" nowrap class="CELL"><b>AC_VO</b></td>
			<td align="center" nowrap class="CELL"><input type="text" name="sta_aifsn_acvo" size="4" maxlength="4" /></td>
			<td align="center" nowrap class="CELL">
				<select name="sta_cwmin_acvo" size="1">
					<option value="1">1</option>
					<option value="3">3</option>
				</select>
			</td>
			<td align="center" nowrap class="CELL">
				<select name="sta_cwmax_acvo" size="1">
					<option value="1">1</option>
					<option value="3">3</option>
					<option value="7">7</option>
				</select>
			</td>
			<td align="center" nowrap class="CELL"><input type="text" name="sta_txop_acvo" size="4" maxlength="4" /></td>
			<td align="center" nowrap class="CELL"><input type="checkbox" name="sta_acm_acvo" value="1" /></td>
		</tr>
	</table>

	<table align="center" width="540" border="0" cellpadding="0" cellspacing="0">
		<tr align="center">
			<td colspan="7" class="btn_field">
				<input type="button" class="button_submit" style="{width:120px;}" value="Apply" id="wmmApply" onClick="check_value()" />
				<input type="reset"	class="button_submit" style="{width:120px;}" value="Cancel" id="wmmCancel"	onClick="window.location.reload()" />
				<input type="button" class="button_submit" name="Close" value="Close" id="wmmClose" style="{width:120px;}" onClick="window.close()" />
			</td>
		</tr>
	</table>
</div>
</html>
