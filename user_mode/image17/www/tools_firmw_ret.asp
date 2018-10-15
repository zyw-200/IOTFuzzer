<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
<title></title>
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

	var arr_page = [
		'err_checksum',
		'err_hwid',
		'err_file',
		'success'
	];

	function toggle_page(id) {
		if (arr_page == null || (arr_page instanceof Array) == false)
			return;
		for (var i=0; i<arr_page.length; i++) {
			if (id == i)
				$('#'+arr_page[i]).show();
			else
				$('#'+arr_page[i]).hide();
		}
	}

	$(document).ready( function () {
		$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(dev_info.model);
		var msg = getUrlEntry('msg');
		if (msg == 'cfgupgrade') {
			$('#cfg_failed').show();
		} else if (msg == 'fwupgrade') {
			$('#fw_failed').show();
		}
		//var ret = getUrlEntry('ret');
		//toggle_page(ret);
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
				<script>document.write(menu.build_structure(1,-1,0))</script>
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
								<div class="section_content_border">

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td><p>&nbsp;</p>
		<table width="100%" border="0" align="center">
		<tr>
			<td height="100">
			<div id="box_header2">
				<div align="center" id="err_checksum" style="display:none" class="CELL">
					Checksum error!
					<input type="button" class="ButtonSmall" id='btn_err_checksum' onclick="location.replace('adm_upload_firmware.asp')" />
					<script>$('#btn_err_checksum').val(get_words('_back'))</script>
				</div>
				<div align="center" id="err_hwid" style="display:none" class="CELL">
					Bad hardware ID!
					<input type="button" class="ButtonSmall"  id='btn_err_hwid' onclick="location.replace('adm_upload_firmware.asp')" />
					<script>$('#btn_err_hwid').val(get_words('_back'))</script>
				</div>
				<div align="center" id="err_file" style="display:none" class="CELL">
					Bad file!
					<input type="button" class="ButtonSmall"  id='btn_err_file' onclick="location.replace('adm_upload_firmware.asp')" />
					<script>$('#btn_err_file').val(get_words('_back'))</script>
				</div>
				<div align="center" id="success" style="display:none" class="CELL">
					Upgrading firmware! Please wait for several seconds. (count down)
				</div>
				<div align="center" id="fw_failed" style="display:none" class="CELL">
					<div id="box_header2">							
						<p><script>show_words('ub_intro_1')</script></p>
						<p><script>show_words('ub_intro_3')</script></p>
						<p><script>show_words('ub_intro_2')</script></p>
						<br/>
						<input type="button" class="ButtonSmall"  id='btn_err_fw_failed' onclick="location.replace('adm_upload_firmware.asp')" />
						<script>$('#btn_err_fw_failed').val(get_words('_back'))</script>
					</div>
				</div>
				<div align="center" id="cfg_failed" style="display:none" class="CELL">
					<div id="box_header2">
						<p><script>show_words('rs_intro_1')</script></p>
						<p><script>show_words('rs_intro_2')</script></p>
						<br/>
						<input type="button" class="ButtonSmall"  id='btn_err_cfg_failed' onclick="location.replace('adm_settings.asp')" />
						<script>$('#btn_err_cfg_failed').val(get_words('_back'))</script>
					</div>
				</div>
			</div>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
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