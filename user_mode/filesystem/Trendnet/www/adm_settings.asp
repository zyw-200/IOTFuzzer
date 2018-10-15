<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<script>
	var funcWinOpen = window.open;
</script>
<title>TRENDNET | modelName | Administrator | Settings Management</title>
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

	var count = 120;
	var arr_page = [
		'err_checksum',
		'err_hwid',
		'err_file',
		'success'
	];
	
	var arr_fwupgrade_msg = [
		get_words('fw_checksum_err'),
		get_words('fw_bad_hwid'),
		get_words('fw_unknow_file_format'),
		get_words('fw_cfg_upgrade_success')
	];

function save_conf(){
	send_submit("form17");
}

function loadConfirm(){
	if(dev_info.login_info != "w"){
		window.location.href ="back.asp";
	}else{
		var btn_restore = get_by_id("load");
		if (btn_restore.disabled) {
			//alert ("A restore is already in progress.");
			alert(get_words('ta_alert_4'));
			return false;
		}
		if (get_by_id("file").value == "") {
			//alert(msg[LOAD_FILE_ERROR]);
			alert(get_words('ta_alert_5'));
			return false;
		}
		var file_name=get_by_id("file").value;
		var ext_file_name=file_name.substring(file_name.lastIndexOf('.')+1,file_name.length);
		if (ext_file_name!="bin"){
			alert(get_words('rs_intro_1'));
			return false;
		}
		btn_restore.disabled = true;
		//if(confirm(msg[LOAD_SETTING])){
		var inf = get_by_id("restore_info");
		if(confirm(get_words('YM38'))){
			inf.innerHTML = get_words('ta_alert_6')+"...";
			//inf.innerHTML = "Please wait, uploading configuration file...";
			try {
				send_submit("form1");
				return true;
			} catch (e) {
				alert(get_words('_error')+": " + e.message);
				//alert("Error: " + e.message);
				inf.innerHTML = "&nbsp;";
				btn_restore.disabled = false;
			}
			return false;
		}else{
			inf.innerHTML = "&nbsp;";
			btn_restore.disabled = false;
		}
	}
}

function restoreConfirm(){
	if(dev_info.login_info != "w"){
		window.location.href ="back.asp";
	}else{
		//if(confirm(msg[RESTORE_DEFAULT])){	
		if(confirm(get_words('up_rb_4')+"\n"+get_words('up_rb_5'))){	
			send_submit("form3");
		}
	}
}

function confirm_reboot(){
	if(dev_info.login_info != "w"){
		window.location.href ="back.asp";
	}else{
		//if(confirm(msg[REBOOT_ROUTER])){
		if(confirm(get_words('up_rb_1')+"\n"+get_words('up_rb_2'))){	
			send_submit("form6");
		}
	}
}

$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(dev_info.model);
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
				<script>document.write(menu.build_structure(1,0,4))</script>
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
								<script>show_words('_settings_management');</script>
								</div>
								<div class="hr"></div>
								<div class="section_content_border">
								<div class="header_desc" id="setmanIntroduction">
									<script>show_words('_SETTINGS_MANAGER_DESC');</script>
									<p></p>
								</div>

<div class="box_tn">
	<div class="CT"><script>show_words('_help_txt212');</script></div>
	<form id="form17" name="form17" method=POST action="cfg_op.ccp">
	<input type="hidden" name="ccp_act" value="save" />
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL"><script>show_words('_export');</script></td>
		<td class="CR"><input type="button" class="button_inbox" id="btn_export" value="" name="Export" onclick="save_conf();" /></td>
		<script>$('#btn_export').val(get_words('_export'));</script>
	</tr>
	</table>
	</form>
</div>

<div class="box_tn">
	<div class="CT"><script>show_words('_help_txt214');</script></div>
	<form id="form1" name="form1" method="POST" action="cfg_op.ccp" enctype="multipart/form-data">
	<input type="hidden" name="ccp_act" value="load" />
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL"><script>show_words('_settings_file_location');</script></td>
		<td class="CR"><input type="file" id="file" name="file" size="40" maxlength="256" /></td>
	</tr>
	<tr align="center">
	<td colspan="2" class="btn_field">
		<input type="button" class="button_submit" id="load" value="" onclick="loadConfirm();" />
		<script>$('#load').val(get_words('_import'));</script>
		<input type="reset" class="button_submit" id="set_cancel" value="Cancel" onclick="buttonEnable()" />
		<script>$('#set_cancel').val(get_words('_cancel'));</script>
		<span class="msg_inprogress" id="restore_info">&nbsp;</span>
	</td>
	</tr>
	</table>
	</form>
</div>

<div class="box_tn">
	<div class="CT"><script>show_words('_help_txt216');</script></div>
	<form id="form2" name="form2" method="post" action="">
	<input type="hidden" id="html_response_page" name="html_response_page" value="reboot.asp" />
	<input type="hidden" id="html_response_return_page" name="html_response_return_page" value="adm_settings.asp" />
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL"><script>show_words('_reset');</script></td>
		<td class="CR"><input type="button" class="button_inbox" id="restore" name="LoadDefault" value="Load Default" onclick="restoreConfirm();" /></td>
			<script>$('#restore').val(get_words('_qos_txt08'));</script>
	</tr>
	</table>
	</form>
	<form id="form3" name="form3" method="post" action="cfg_op.ccp">
	<input type="hidden" name="ccp_act" value="restore" />
	</form>
</div>

<div class="box_tn">
	<div class="CT"><script>show_words('_help_txt218');</script></div>
	<form id="form6" name="form6" method="post" action="cfg_op.ccp">
	<input type="hidden" name="ccp_act" value="reboot" />
	<input type="hidden" name="ccpSubEvent" value="CCP_SUB_SOFT_REBOOT" />
	<table cellspacing="0" cellpadding="0" class="formarea">
	<tr>
		<td class="CL"><script>show_words('_help_txt218');</script></td>
		<td class="CR"><input type="button" class="button_inbox" value="Reboot" id="restart" name="restart" onclick="confirm_reboot();" /></td>
			<script>$('#restart').val(get_words('help664'));</script>
	</tr>
	</table>
	</form>
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