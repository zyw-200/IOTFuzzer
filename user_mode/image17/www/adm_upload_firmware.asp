<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<script>
	var funcWinOpen = window.open;
</script>
<title>TRENDNET | modelName | Administrator | Upload Firmware</title>
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
	var count = 120;

	var menu = new menuObject();
	menu.setSupportUSB(dev_info.KCode_USB);

	var hw_version 	= dev_info.hw_ver;
	var version 	= dev_info.fw_ver;
	var model		= dev_info.model;
	var login_Info 	= dev_info.login_info;

	var submit_button_flag = 0;
	
function send_request(){
	//temporary disable fw upload button, Jerry 2010-08-09
	//return false;
	
	if (get_by_id("file").value === "") {
		//alert("You must enter the name of a firmware file first.");
		alert(get_words('tf_FWF1'));
		return false;
	}
	//if (!confirm("Note: Some firmware upgrades reset the router's configuration options to the factory defaults.\n Before performing an upgrade, be sure to save the current configuration from the Tools-System screen.\n Do you still want to upgrade?")) {
	if (!confirm(get_words('tf_USSW'))) {
		return false;
	}
	//if (!confirm("Do you really want to reprogram the device using the firmware file \"" +
			//get_by_id("file").value + "\"?")) {
	if (!confirm(get_words('tf_really_FWF')+" \""+ $('#file').val() + " \"?" )) {
			return false;
	}
	
	var paramSubmit={
		url: "get_set.ccp",
		arg: "ccp_act=doEvent&ccpSubEvent=CCP_SUB_UPLOADFW"
	};
	//ajax_submit(paramSubmit);	//since dir-651 should have enough space, no need to send event to clear device.
	$('#upgrade_button').attr('disabled','disabled');
	
	//setTimeout("doUploadFile()",5*1000);
	doUploadFile();
}

function doUploadFile()
{
	try {
		if(submit_button_flag == 0){
			submit_button_flag = 1;
			$('#form1').submit();
		}
	} catch (e) {
		alert("%[.error:Error]%: " + e.message);
		//alert(get_words('_error')+": " + e.message);
		$('#upgrade_button').removeAttr('disabled');
	}
}

function buttonEnable()
{
	$('#upgrade_button').removeAttr('disabled');
	$('#reset').removeAttr('disabled');
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
				<script>document.write(menu.build_structure(1,1,5))</script>
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
								<div class="headerbg" id="manUploadFirmwareTitle">
								<script>show_words('_upgrade_firmw');</script>
								</div>
								<div class="hr"></div>
								<div class="section_content_border">
								<div class="header_desc" id="manStatusIntroduction">
									<script>show_words('_FIRMW_DESC');</script></br></br>
									<script>show_words('_FIRMW_DESC_sub');</script>
									<p></p>
								</div>

<div class="box_tn">
	<div class="CT"><script>show_words('_upgrade_firmw');</script></div>
	<form id="form1" name="form1" method="POST" action="fwupgrade.ccp" enctype="multipart/form-data">
		<iframe id="upload_target" name="upload_target" src="" style="display:none;width:0;height:0;border:0px solid #fff;"></iframe>
		<input type="hidden" name="action" value="fwupgrade" />
		<table cellspacing="0" cellpadding="0" class="formarea">
			<tr>
				<td class="CL"><script>show_words('_location');</script>:</td>
				<td class="CR"> <input id="file" name="file" size="20" maxlength="256" type="file" onchange="buttonEnable();" /> </td>
			</tr>
			<tr align="center">
				<td colspan="2" class="btn_field">
					<input type="button" class="button_submit" id="upgrade_button" name="upgrade_button" value="Apply" onclick="send_request();" disabled>
					<script>$('#upgrade_button').val(get_words('_apply'));</script>
					<input type="reset" class="button_submit" id="reset" name="reset" value="Cancel" onclick="document.location.reload();" disabled>
					<script>$('#reset').val(get_words('_cancel'));</script>
				</td>
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