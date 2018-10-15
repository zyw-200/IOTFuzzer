<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="-1" />
<title>TRENDNET | modelName | Login</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<link href="/css/css_router.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="uk.js"></script>
<script type="text/javascript" src="public.js"></script>
<script type="text/javascript" src="public_msg.js"></script>
<script type="text/javascript" src="pandoraBox.js"></script>
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/xml.js"></script>
<script type="text/javascript" src="js/object.js"></script>
<script type="text/javascript" src="js/ddaccordion.js"></script>
<script type="text/javascript" src="js/ccpObject.js"></script>
<script type="text/javascript">
	var salt = "345cd2ef";
	var def_title = document.title;
	var misc = new ccpObject();
	var dev_info = misc.get_router_info();
	document.title = def_title.replace("modelName", dev_info.model);

	var hw_version = dev_info.hw_ver;
	var version = dev_info.fw_ver;
	var model = dev_info.model;
	var login_Info = dev_info.login_info;
	var cli_mac = dev_info.cli_mac;
	var auth = misc.config_val("graph_auth");

	if (misc.config_val("es_configured") != '' && misc.config_val("es_configured") == 0)
	{
		location.replace("wizard_router.asp");
	}

	var main = new ccpObject();
	main.set_param_url('curr_lang.ccp');
	main.set_ccp_act('get');
	
	main.get_config_obj();
	/*
	** Date:	2013-03-20
	** Author:	Moa Chung
	** Reason:	The language is space at login page , when you use first time.
	** Note:	TEW-810DR pre-test no.37
	**/
	var br_lang = main.config_val('igd_CurrentLanguage_')!='0' ? main.config_val('igd_CurrentLanguage_') : "1";
	
	/* for infinity reload, so add '#' to determine*/
	if(document.location.href.indexOf('#')==-1)
		lang_submit(br_lang);
		
	var submit_button_flag = true;
	function encode_base64(psstr) {
		return encode(psstr, psstr.length);
	}

	function encode(psstrs, iLen) {
		var map1 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/{}|[]\:;'<>?,.~!@#$%^&*()_-+=\"";
		var oDataLen = (iLen * 4 + 2) / 3;
		var oLen = ((iLen + 2) / 3) * 4;
		var out = '';
		var ip = 0;
		var op = 0;
		while (ip < iLen) {
			var xx = psstrs.charCodeAt(ip++);
			var yy = ip < iLen ? psstrs.charCodeAt(ip++) : 0;
			var zz = ip < iLen ? psstrs.charCodeAt(ip++) : 0;
			var aa = xx >>> 2;
			var bb = ((xx & 3) << 4) | (yy >>> 4);
			var cc = ((yy & 0xf) << 2) | (zz >>> 6);
			var dd = zz & 0x3F;
			out += map1.charAt(aa);
			op++;
			out += map1.charAt(bb);
			op++;
			out += op < oDataLen ? map1.charAt(cc) : '=';
			op++;
			out += op < oDataLen ? map1.charAt(dd) : '=';
			op++;
		}
		return out;
	}

	function check()
	{
		if (submit_button_flag) {

			if (auth == 1) {
				$('#graph_code').val($('#graph_code').val());
			}
			submit_button_flag = false;
			$('#username').val(encode_base64($('#login_n').val()));
			$('#password').val(encode_base64($('#log_pass').val()));
			if (!is_ascii($('#log_pass').val()))
			{
				$('#password').val($('#password').val() + 'X');
				$('#log_pass').val($('#log_pass').val() + 'X');
			}

			var param = {
				url : 'login.ccp',
				arg : 'username=' + $('#username').val() + '&password=' + $('#password').val() +
				'&graph_id=' + $('#graph_id').val() +
				'&login_n=' + $('#login_n').val() +
				'&log_pass=' + $('#log_pass').val() +
				'&graph_code=' + $('#graph_code').val()
			};

			send_submit("form1");
		}
		return true;
	}

	function chk_KeyValue(e) {
		//var salt = "345cd2ef";
		if (browserName == "Netscape") {
			var pKey = e.which;
		}
		if (browserName == "Microsoft Internet Explorer") {
			var pKey = event.keyCode;
		}
		if (pKey == 13) {
			if (check()) {
				send_submit("form1");
			}
		}
	}

	function AuthShow() {
		$('#show_graph').hide();
		$('#show_graph2').hide();
		if (auth == 1) {
			$('#show_graph').show();
			$('#show_graph2').show();

			var d = Math.random();
			$('#img_0').attr('src', '0.bmp?' + d);
			$('#img_1').attr('src', '1.bmp?' + d);
			$('#img_2').attr('src', '2.bmp?' + d);
			$('#img_3').attr('src', '3.bmp?' + d);
			$('#img_4').attr('src', '4.bmp?' + d);
		}
	}

	/*
	 ** Date: 2013-02-05
	 ** Author: Moa Chung
	 ** Reason: add multi-lang selection
	 **/
	function change_lang() {
		var Nlang = $('#lang_select').val();
		if (Nlang != br_lang) {
			lang_submit(Nlang);
			return;
		}
	}

	function lang_submit(Nlang) {
		var time = new Date().getTime();
		var temp_cURL = document.URL.split('#');
		var ajax_param = {
			type : "POST",
			async : false,
			url : 'curr_lang.ccp',
			data : 'ccp_act=set&ccpSubEvent=CCP_SUB_WEBPAGE_APPLY&curr_language=' + Nlang + '&igd_CurrentLanguage_1.0.0.0=' + Nlang + "&" + time + "=" + time,
			success : function(data) {
				window.location.href = temp_cURL[0] + '#' + Nlang;
				/*				var isSafari = navigator.userAgent.search("Safari") > -1;
				 if (isSafari)
				 location.replace('/wizard_router.asp');
				 else
				 window.location.reload(true);
				 */
				window.location.reload(true);
			}
		};
		$.ajax(ajax_param);
	}

	var browserName = navigator.appName;
	document.onkeyup = chk_KeyValue;

$(document).ready(function() {
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(model);
	//AuthShow(); 	//remove it, why it occurs an error at line: 103 (sometimes) ???
}); 
</script>
</style>
</head>
<body onLoad="document.form1.log_pass.focus();">
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
			<td style="width:12px;"><img src="/image/bg_topl_login.gif" width="12" height="12" /></td>
			<td style="width:927px;"><img src="/image/bg_top_login.gif" width="927" height="12" /></td>
			<td style="width:11px;"><img src="/image/bg_topr_login.gif" width="11" height="12" /></td>
		</tr>
		<!-- End of upper frame -->

		<!-- main content -->
		<tr>
			<td style="background-image:url('/image/bg_l_login.gif');background-repeat:repeat-y;vertical-align:top;" width="12">
			<td style="background-image:url('/image/bg_login.gif');background-repeat:repeat-x repeat-y;vertical-align:top;" width="927">

			<table align="center" class="tbl_main" style="width:500px; margin-left:auto; margin-right:auto; margin-top:30px">
			<tr>
			<td valign="top">
				<form name="form1" id="form1" action="login.ccp" method="post">
				<input type="hidden" id="html_response_page" name="html_response_page" value="login.asp" />
				<input type="hidden" id="login_name" name="login_name" />
				<input type="hidden" id="login_pass" name="login_pass" />
				<input type="hidden" id="username" name="username" />
				<input type="hidden" id="password" name="password" />
				<input type="hidden" id="graph_id" name="graph_id" value="" />
				<input type="hidden" id="alert_id" name="alert_id" value="" />
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tabBigTitle">
				<tr>
					<td colspan="2" class="CT" ><img src="image/icons_login.png" style="float:left; margin:0 -8px 0 10px"/>
					<div style="margin:22px 0 0 0; font-size:26px;text-transform:uppercase;"><script>document.write(model);</script> <script>show_words('li_Login')</script></div></td>
				</tr>
				<tr>
					<td class="CL"><strong><script>show_words('_username')</script>&nbsp;&#58;</strong></td>
					<td class="CR"><input type="text" id="login_n" name="login_n" value="admin" style="width:200px;"/></td>
				</tr>
				<tr>
					<td class="CL"><strong><script>show_words('_password')</script>&nbsp;&#58;</strong></td>
					<td class="CR"><input type="password" id="log_pass" name="log_pass" value="" style="width:200px;" onfocus="select();" onchange="key_word(this,'log_pass_t')"/><input type="text" id="log_pass_t" value="" style="width:200px;" onfocus="select();" class="init-hide" onchange="key_word(this,'log_pass')"/>&nbsp;&nbsp;
						<input type="checkbox" modified="ignore" onclick="showHideBox(this,'log_pass,log_pass_t',true);" />&nbsp;<script>show_words('_Display')</script>
					</td>
				</tr>

				<tr id="show_graph" style="display:none;">
					<td colspan="2" class="CELL">
						<b><script>show_words('_authword')</script>&nbsp;</b>
						<input type="password" id="graph_code" name="graph_code" value="" maxlength="8" style="width:200px;" onfocus="select();"/>
					</td>
				</tr>
				<tr>
					<td class="CL"><strong><script>show_words('_Language')</script>&nbsp;&#58;</strong></td>
					<td class="CR">
						<select id="lang_select" size="1" style="width:200px;" onchange="change_lang()">
							<option value="1" selected="">English</option>
							<option value="2">Español</option>
							<option value="3">Deutsch</option>
							<option value="6">Русский</option>
							<option value="4">Français</option>
						</select>
						<script>$('#lang_select').val(br_lang);</script>
					</td>
				</tr>
				<tr id="show_graph2" style="display:yes">
					<td class="CL">
						<table border="0" cellspacing=0 cellpadding=0>
						<tr>
							<td><img id="img_0" /></td>
							<td><img id="img_1" /></td>
							<td><img id="img_2" /></td>
							<td><img id="img_3" /></td>
							<td><img id="img_4" /></td>
						</tr>
						</table>
					</td>
					<td class="CR">
						<input class="ButtonSmall" type="button" name="Refresh" id="Refresh" value="" onClick="window.location.reload(true);" style="width:120;" />
						<script>$('#Refresh').val(get_words('regenerate'));</script>
					</td>
				</tr>
				</table>
				</form>
			</td>
			</tr>
			</table>

			<br/>
			<div style="margin-left:390px">
				<input class="ButtonSmall loginBtn" type="button" name="login" id="login" value="" onclick="check()" />
				<script>$('#login').val(get_words('_login'));</script>
			</div>
			<br/><br/>
			</td>
			<td style="background-image:url('/image/bg_r_login.gif');background-repeat:repeat-y;vertical-align:top;" width="11">
		</tr>
		<!-- End of main content -->

		<!-- lower frame -->
		<tr>
			<td style="width:12px;"><img src="/image/bg_butl_login.gif" width="12" height="12" /></td>
			<td style="width:927px;"><img src="/image/bg_but_login.gif" width="927" height="12" /></td>
			<td style="width:11px;"><img src="/image/bg_butr_login.gif" width="11" height="12" /></td>
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
</div>
</body>
<script>
	AuthShow();
</script>
</html>