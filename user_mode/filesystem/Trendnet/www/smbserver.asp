<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | USB | Samba Server</title>
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

	var mainObj = new ccpObject();
	mainObj.set_param_url('get_set.ccp');
	mainObj.set_ccp_act('get');
	
	mainObj.add_param_arg('IGD_SMBServer_',1100);
	mainObj.add_param_arg('IGD_SMBServer_Admin_',1110);
	mainObj.add_param_arg('IGD_SMBServer_User_i_',1100);

	mainObj.get_config_obj();
	
	var smbCfg = {
		'enable': 		mainObj.config_val('igdSMBServer_Enable_'),
		'hostname': 	mainObj.config_val('igdSMBServer_HostName_'),
		'groupname':	mainObj.config_val('igdSMBServer_GroupName_'),
		'desc':			mainObj.config_val('igdSMBServer_Description_'),
//		'auth':			mainObj.config_val('igdSMBServer_AuthenticationEnable_'),
		'Admin_name':	mainObj.config_val('igdSMBServerAdmin_Username_'),
		'Admin_passwd':	mainObj.config_val('igdSMBServerAdmin_Password_')
	};

	var smbUserCfg = {
		'name':		mainObj.config_str_multi('igdSMBServerUser_Username_'),
		'passwd':	mainObj.config_str_multi('igdSMBServerUser_Password_'),
		'permi':	mainObj.config_str_multi('igdSMBServerUser_Permission_')
	}

	var User_DataArray = new Array();
	var DataArray = new Array();
	var list_max_num = 25;
	var User_cnt = 0;
	if(smbUserCfg.name != null)
		User_cnt = smbUserCfg.name.length;

	function Data(enable, name, passwd, permi, onList)
	{
		this.Enable = enable;
		this.Name = name;
		this.Passwd = passwd;
		this.Permi = permi;
		this.OnList = onList;
	}

	function User_Data(name, passwd, permi, onList)
	{
		this.Enable = enable;
		this.Name = name;
		this.Passwd = passwd;
		this.Permi = permi;
		this.OnList = onList;
	}

	function set_reservation(){
		var index = 1;
		for (var i = 0; i < User_cnt; i++)
		{
			if(smbUserCfg.name[i].length > 0)
				DataArray[DataArray.length++] = new Data("0",smbUserCfg.name[i],smbUserCfg.passwd[i], smbUserCfg.permi[i], i);
		}
		$('#max_row').val(index-1);
	}

	function deleteRedundentDatamodel()
	{
		var delCnt = 0;
		var idx = User_cnt;
		if(User_cnt > DataArray.length)
			delCnt = User_cnt - DataArray.length;

		if(delCnt == 0)
			return;

		var del = new ccpObject();
		del.set_param_url('get_set.ccp');
		del.set_ccp_act('del');
		del.add_param_event('CCP_SUB_WEBPAGE_APPLY');

		while(delCnt > 0)
		{
			del.add_param_arg('IGD_SMBServer_User_i_','1.1.'+ idx +'.0');
			delCnt--;
			idx--;
		}

		del.ajax_submit(true);
	}

	function del_row()
	{
		var idx = get_checked_value(get_by_name('sel_del'));
		if (idx)
			idx = parseInt(idx,10);
		else if (idx==0 && !get_by_id('sel_del').checked)
			return;

		if(!confirm(get_words('li_msg145') + " " + DataArray[idx].Name))
			return;

		for(var i=idx; i<(DataArray.length-1); i++)
		{
			//DataArray[i].Enable = DataArray[i+1].Enable
			DataArray[i].Name = DataArray[i+1].Name;
			DataArray[i].Passwd = DataArray[i+1].Passwd;
			DataArray[i].Permi = DataArray[i+1].Permi;
			DataArray[i].OnList = DataArray[i+1].OnList;
		}

		if(DataArray.length > 0)
			DataArray.length -- ;

		paintTable();
		clear_reserved();

		var del = new ccpObject();
		del.set_param_url('get_set.ccp');
		del.set_ccp_act('del');
		del.add_param_event('CCP_SUB_WEBPAGE_APPLY');

		del.add_param_arg('IGD_SMBServer_User_i_','1.1.'+ User_cnt +'.0');
		del.ajax_submit(true);
		AddUsertoDatamodel();
		//deleteRedundentDatamodel();
		//$('#modified').val("true");
	}

	function update_DataArray(){
		var index = parseInt(get_by_id("edit").value);
		var insert = false;
		var is_enable = "0";

		if(index == "-1"){      //save
			if(DataArray.length == list_max_num){
				alert(addstr(get_words('_list_full'),get_words('serv_userlist')));
			}else{
				index = DataArray.length;
				$('#max_row').val(index);
				insert = true;
			}
		}

		if(insert){
			DataArray[index] = new Data(is_enable, $('#user_name').val(), $('#user_passwd').val(), $('#permission').val(), index, index+1);			
		}else if (index != -1){
			DataArray[index].Enable = is_enable;
			DataArray[index].Name = $('#user_name').val();
			DataArray[index].Passwd = $('#user_passwd').val();
			DataArray[index].Permi = $('#permission').val();
			DataArray[index].OnList = index;
		}
	}

	function save_reserved(){
		var index = 0;
		var user = $('#user_name').val();
		var user_pass = $('#user_passwd').val();
		var permi = $('#permission').val();

		if(user == ""){
			alert(get_words('NAME_ERROR',LangMap.msg));
			return false;
		}

		for(m = 0; m < DataArray.length; m++){
			if(get_by_id("edit").value == "-1"){     //add
				if(user.length > 0){
					if((user == DataArray[m].Name)){
						alert(get_words('_username')+" '"+ user +"' "+get_words('li_msg151'));
						return false;
					}
				}
			}
			if(user.length > 0 && m != parseInt(get_by_id("edit").value)){
				if((user == DataArray[m].Name)){
					alert(get_words('_username')+" '"+ user +"' "+get_words('li_msg151'));
					return false;
				}
			}
		}

        update_DataArray();
		paintTable();
		clear_reserved();
		AddUsertoDatamodel();
		//$('#modified').val("true");
		return true;
	}

	function clear_reserved(){
		$('#user_name').val("");
		$('#user_passwd').val("");
		$('#permission').val(1);
		$('#edit').val(-1);
	}

	function edit_row(idx)
    {
        $('#user_name').val(DataArray[idx].Name);
        $('#user_passwd').val(DataArray[idx].Passwd);
        $('#permission').val(DataArray[idx].Permi);
		$('#edit').val(idx);
		$('#add').hide();
		$('#edit_btn').show();
    }

	function paintTable()
	{
		var contain = ""
		var is_enable = "";
		for(var i = 0; i < DataArray.length; i++){
			contain += '<tr>'+
					'<td class="CELL"><input type="radio" id="sel_del" name="sel_del" onClick="edit_row('+i+')" value="'+i+'" /></td>'+
					'<td class="CELL">'+DataArray[i].Name+'</td>'+
					'<td class="CELL">'+string_to_star(DataArray[i].Passwd.length)+'</td>'+
					'<td class="CELL">'+((DataArray[i].Permi == 1)?get_words('serv_read'):get_words('serv_write'))+'</td>'+
					'</tr>';
		}
		$('#UserTable').append(contain);
	}

	function string_to_star(len)
	{
		var stars = '';
		for (var j = 0; j<len;j++)
		{
			stars += '*';
		}
		return (stars)
	}

	function loadSettings(){
		//set_checked(smbCfg.storagemode,get_by_name("s_mode"));
		set_checked(smbCfg.enable,get_by_name("smb"));
		//set_checked(smbCfg.auth,get_by_name("auth"));
		$('#hostname').val(smbCfg.hostname);
		$('#workgroup').val(smbCfg.groupname);
		$('#descript').val(smbCfg.desc);
		$('#admin_name').val(smbCfg.Admin_name);
		//$('#admin_passwd').val(smbCfg.Admin_passwd);
		//$('#admin_passwd_v').val(smbCfg.Admin_passwd);
		$('#permission').val(1);
		disable_smb_server();
	}

	function submit_all()
	{
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('smbserver.asp');
		if ($('#admin_passwd').val() != "")
			obj.add_param_arg('igdSMBServerAdmin_Password_','1.1.1.0',$('#admin_passwd').val());

		obj.add_param_arg('igdSMBServer_Enable_','1.1.0.0',get_checked_value(get_by_name("smb")));
		//obj.add_param_arg('igdSMBServer_AuthenticationEnable_','1.1.0.0',get_checked_value(get_by_name("auth")));
		obj.add_param_arg('igdSMBServer_HostName_','1.1.0.0',$('#hostname').val());
		obj.add_param_arg('igdSMBServer_GroupName_','1.1.0.0',$('#workgroup').val());
		obj.add_param_arg('igdSMBServer_Description_','1.1.0.0',$('#descript').val());
		obj.add_param_arg('igdSMBServerAdmin_Username_','1.1.1.0',$('#admin_name').val());

		obj.get_config_obj();
	}

	function AddUsertoDatamodel()
	{
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('set');
		obj.add_param_event('CCP_SUB_WEBPAGE_APPLY');
		obj.set_param_next_page('smbserver.asp');

		for(var i=0; i<DataArray.length; i++)
		{
			var instStr = "1.1."+ (i+1) +".0";
			obj.add_param_arg('igdSMBServerUser_Username_',instStr,DataArray[i].Name);
			obj.add_param_arg('igdSMBServerUser_Password_',instStr,DataArray[i].Passwd);
			obj.add_param_arg('igdSMBServerUser_Permission_',instStr,DataArray[i].Permi);
		}
		obj.get_config_obj();
	}

    function send_request(){
		var hostname_ = $('#hostname').val();
		var workgroup_ = $('#workgroup').val();
		var descript_ = $('#descript').val();
		//var storage_mode = get_by_name("s_mode");
		var admin = $('#admin_name').val();
		var passwd1 = $('#admin_passwd').val();
		var passwd2 = $('#admin_passwd_v').val();

		if (!chk_chars(hostname_)) {
			alert(addstr(get_words('li_msg162'),get_words('_ServerName')));
			return;
		}

		if(!chk_chars(workgroup_))
		{
			alert(addstr(get_words('li_msg162'),get_words('serv_workgrp')));
			return;
		}
/*
		if(descript_ == "")
		{
			alert(addstr(get_words('_s_not_empty'),get_words('serv_desc')));
			return;
		}
*/
		if(admin == "")
		{
			alert(addstr(get_words('_s_not_empty'),get_words('ADMIN')));
			return;
		}
		if(passwd1 != passwd2)
		{
			alert(get_words('MATCH_PWD_ERROR2'));
			return;
		}

		submit_all();
	}

	//20121220 Silvia add for verfify NetBIOS / SMB server name
	//20131119 Silvia modify to RE, filter "
	function chk_chars(tmp_name)
	{
		if(tmp_name == "")
			return false;

		var re = /[!@#$%^&()\-_'{}.~\\*+=|:;\/?<>,\[\]\"]/;
		if(re.test(tmp_name))
			return false;
		return true;
	}

	function disable_smb_server()
	{
		var smb = get_by_name("smb");
		//get_by_name("auth")[0].disabled = smb[1].checked;
		//get_by_name("auth")[1].disabled = smb[1].checked;
		$('#hostname').attr("disabled",smb[1].checked);
		$('#workgroup').attr("disabled",smb[1].checked);
		$('#descript').attr("disabled",smb[1].checked);	
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
				<script>document.write(menu.build_structure(1,6,0))</script>
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
								<div class="headerbg" id="manStatusTitle">
								<script>show_words('_samba_server');</script>
								</div>
								<div class="hr"></div>
								<div class="section_content_border">
								<div class="header_desc" id="manStatusIntroduction">
									<p></p>
								</div>

<input type="hidden" id="del" name="del" value="-1">
<input type="hidden" id="edit" name="edit" value="-1">
<input type="hidden" id="max_row" name="max_row" value="-1">
<div class="box_tn">
	<div class="CT"><script>show_words('serv_info');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<!-- ----------------- System Info ----------------- -->
	<tr>
		<td class="CL"><script>show_words('serv_samba_');</script></td>
		<td class="CR">
			<input type="hidden" id="smb_server" name="smb_server" value="">
			<input type="radio" name="smb" value="1" onClick="disable_smb_server()">
			<script>show_words('_enable')</script>&nbsp;&nbsp;&nbsp;
			<input type="radio" name="smb" value="0" onClick="disable_smb_server()">
			<script>show_words('_disable')</script>
		</td>
	</tr>
<!--
	<tr>
		<td class="CL"><script>show_words('_auth');</script></td>
		<td class="CR">
			<input type="hidden" id="auth_enable" name="auth_enable" value="">
			<input type="radio" name="auth" value="1">
			<script>show_words('_enable')</script>&nbsp;&nbsp;&nbsp;
			<input type="radio" name="auth" value="0">
			<script>show_words('_disable')</script>
		</td>
	</tr>
-->
	<tr>
		<td class="CL"><script>show_words('_ServerName');</script></td>
		<td class="CR">
			<input type="text" id="hostname" name="hostname" size="20" maxlength="15" value="" />
		</td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('serv_workgrp');</script></td>
		<td class="CR">
			<input name="workgroup" type="text" id="workgroup" size="20" maxlength="15" value="" />
		</td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('serv_desc');</script></td>
		<td class="CR">
			<input name="descript" type="text" id="descript" size="20" maxlength="48" value="" />
		</td>
	</tr>
	</table>
</div>

<div class="box_tn">
	<div class="CT"><script>show_words('serv_setadmin');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<!-- ----------------- System Info ----------------- -->
	<tr>
		<td class="CL"><script>show_words('ADMIN')</script></td> 
		<td class="CR"><input type="text" id="admin_name" name="admin_name" size="20" maxlength="15" >
		</td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_NewPwd')</script></td>  
		<td class="CR"><input name="admin_passwd" type="password" id="admin_passwd" size="20" maxlength="15">
		</td>
	</tr>

	<tr>
		<td class="CL"><script>show_words('serv_repasswd')</script></td> 
		<td class="CR"><input name="admin_passwd_v" type="password" id="admin_passwd_v" size="20" maxlength="15">
		</td>
	</tr>
	</table>
	<div id="buttonField" class="box_tn">
		<table cellspacing="0" cellpadding="0" class="formarea">
			<tr align="center">
				<td colspan="2" class="btn_field">
					<input type="button" class="button_submit" id="submit" value="Apply" onclick="send_request();" />
					<script>$('#submit').val(get_words('_apply'));</script>
					<input type="reset" class="button_submit" id="btn_cancel" value="Cancel" onclick="window.location.reload()" />
					<script>$('#btn_cancel').val(get_words('_cancel'));</script>
				</td>
			</tr>
		</table>
	</div>
</div>

<div class="box_tn">
	<div class="CT"><script>show_words('serv_userlist');</script></div>
	<table cellspacing="0" cellpadding="0" class="formarea">
	<!-- ----------------- System Info ----------------- -->
	<tr>
		<td class="CL"><script>show_words('_username')</script></td>
		<td class="CR"><input type="text" id="user_name" name="user_name" size="15" maxlength="15" value=""></td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('_password')</script></td>
		<td class="CR"><input name="user_passwd" type="password" id="user_passwd" size="15" maxlength="15" value="" onChange=""></td>
	</tr>
	<tr>
		<td class="CL"><script>show_words('serv_permission')</script></td>
		<td class="CR">
			<select id="permission" name="permission" size="1">
				<option value="1"><script>show_words('serv_read')</script></option>
				<option value="7"><script>show_words('serv_write')</script></option>
			</select>
		</td>
	</tr>
	</table>
	<div id="buttonField" class="box_tn">
		<table cellspacing="0" cellpadding="0" class="formarea">
		<tr align="center">
			<td colspan="2" class="btn_field">
				<input name="add" type="button" class="button_submit" id="add" onClick="save_reserved()" value="">
				<script>$('#add').val(get_words('_add'));</script>
				<input type="button" class="button_submit" id="edit_btn" onClick="save_reserved()" value="" style="display:none;">
				<script>$('#edit_btn').val(get_words('_edit'));</script>
				<input type="reset" class="button_submit" id="btn_cancel" value="Clear" onclick="window.location.reload()" />
				<script>$('#btn_cancel').val(get_words('_clear'));</script>
			</td>
		</tr>
		</table>
	</div>
	
</div>

<div class="box_tn">
	<div class="CT"><script>show_words('serv_curr_userlist');</script></div>
	<table id="UserTable" cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CELL" width="40px"></td>
		<td class="CELL"><script>show_words('_username');</script></td>
		<td class="CELL"><script>show_words('_password');</script></td>
		<td class="CELL"><script>show_words('serv_permission');</script></td>
	</tr>
	</table>
	<script>
		set_reservation();
		paintTable();
	</script>

	<div class="CT" align="right">
		<strong><script>show_words('serv_total_user')</script></strong>&nbsp;&nbsp;&nbsp;
		<input name="del_user" type="button" class="button_submit" id="del_user" onClick="del_row()" value="" />
		<script>$('#del_user').val(get_words('_delete'));</script>
	</div>
</div>

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
<script>
	loadSettings();
	var login_who=login_Info;
	if(login_who != "w")
	{
		get_by_id("submit").disabled = "true";
		//get_by_id("reset").disabled = "true";
		get_by_id("add").disabled = "true";
		get_by_id("del_user").disabled = "true";
	}
</script>
</body>
</html>