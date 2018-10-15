<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="tool_fw";
$MY_MSG_FILE	=$MY_NAME.".php";
set("/runtime/web/next_page",$MY_NAME);
set("/runtime/web/help_page",$MY_NAME);
/* --------------------------------------------------------------------------- */
if ($ACTION_POST!="")
{
	require("/www/model/__admin_check.php");
	require("/www/__action.php");
	$ACTION_POST="";
	
	//$db_dirty=0;

	if($db_dirty > 0)	{$SUBMIT_STR="submit ";}
	else				{$SUBMIT_STR="";}

	$NEXT_PAGE=$MY_NAME;
	if($SUBMIT_STR!="")	{require($G_SAVING_URL);}
	else				{require($G_NO_CHANGED_URL);}
}
$reboot=query("/runtime/reboot");
/* --------------------------------------------------------------------------- */
require("/www/model/__html_head.php");
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.	
$cfg_wtp_enable = query("/sys/wtp/enable");
//$cfg_ipv6 = query("/inet/entry:1/ipv6/valid");
$runtime_wapi = query("/runtime/web/display/wapi");
/* --------------------------------------------------------------------------- */
?>

<html>
<script>
/* page init functoin */
function init()
{
	var key_input  = get_obj("key");
	var key_submit = get_obj("key_submit");
	if("<?=$runtime_wapi?>" == "1")
	{
		var ap_cert = get_obj("ap_cert");
		var ap_cert_load = get_obj("ap_cert_load");
	}
	
	if("<?=$cfg_wtp_enable?>" == 1)
	{
		fields_disabled(frm_image, true);
		fields_disabled(frm_certificate, true);
		fields_disabled(frm_key, true);
	}
	//if("<?=$cfg_ipv6?>"!="1")
	//{
	if("<?query("/runtime/sys/stunnel/ext_key_status");?>" == "enable")
	{
		key_input.disabled  = false;
		key_submit.disabled = false;
	}
	else
	{
		key_input.disabled  = true;
		key_submit.disabled = true;
	}
	//}
	if("<?=$runtime_wapi?>" == "1")
	{
		if("<?query("/runtime/sys/wapi/ext_as_status");?>" == "enable")
		{
			ap_cert.disabled = false;
			ap_cert_load.disabled = false;
		}
		else
		{
			ap_cert.disabled = true;
			ap_cert_load.disabled = true;
		}
	}
	if("<?=$reboot?>"=="1")
	{
		alert("<?=$a_reboot_device?>");
	}
	if("<?=$reboot?>"=="2")
	{
		alert("<?=$a_reboot_device_cert?>");
	}
	AdjustHeight();
}

/* parameter checking */
function check()
{
	
}
function langCheck()
{
	var f=get_obj("frm_lang");

	if(is_blank(f.lang.value))
	{
		alert("<?=$a_blank_fw_file?>");
		f.lang.focus();
		return false;
	}		
}
function formCheck()
{
	var f=get_obj("frm_image");

	if(is_blank(f.firmware.value))
	{
		alert("<?=$a_blank_fw_file?>");
		f.firmware.focus();
		return false;
	}		
}

function formCheckCert()
{
	var f=get_obj("frm_certificate");	
	
	if(is_blank(f.certificate.value))
	{
		alert("<?=$a_blank_fw_file?>");
		f.certificate.focus();
		return false;
	}	

	if(f.certificate.value.search(".pem")==-1)
	{
		alert("<?=$a_format_error_file?>");
		f.certificate.focus();
		return false;
	}			
}

function formCheckKey()
{
	var f=get_obj("frm_key");	
		
	if(is_blank(f.key.value))
	{
		alert("<?=$a_blank_fw_file?>");
		f.key.focus();
		return false;
	}	
	
	if(f.key.value.search(".pem")==-1)
	{
		alert("<?=$a_format_error_file?>");
		f.key.focus();
		return false;
	}		
}

function formCheckAsCert()
{
	var f=get_obj("frm_ascert");
	if(is_blank(f.as_cert.value))
	{
		alert("<?=$a_blank_fw_file?>");
		f.as_cert.focus();
		return false;
	}
	if(f.as_cert.value.slice(-4)!=".cer")
	{
		alert("<?=$a_format_error_file?>");	
		f.as_cert.focus();
		return false;
	}
}
function formCheckApCert()
{
	var f=get_obj("frm_apcert");
	if(is_blank(f.ap_cert.value))
	{
		alert("<?=$a_blank_fw_file?>");
		f.ap_cert.focus();
		return false;
	}
	if(f.ap_cert.value.slice(-4)!=".cer")
	{
		alert("<?=$a_format_error_file?>");
		f.ap_cert.focus();
		return false;
	}
}
</script>
<body onload="init();" bgcolor="#CCDCE2" leftmargin="0" marginheight="0" marginwidth="0" topmargin="0" style="overflow-x: auto; overflow-y: auto;" >
<table id="table_frame" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top">
		<table id="table_header" cellspacing="0" cellpadding="0">
		<tr>
			<td id="td_header" valign="middle"><?=$m_context_title?></td>
		</tr>	
		</table>
		<table id="table_set_main"  border="0"  cellspacing="0" cellpadding="0">
<!-- ________________________________ Main Content Start ______________________________ -->
		<tr>
			<td>
				<table border="0" width="99%" cellspacing="0" cellpadding="0">
				<form name="frm_image" id="frm_image" method="POST" action="upload_image._int" enctype="multipart/form-data" onsubmit="return check();">
				<tr>
				<td  bgcolor="#cccccc" colspan="2" height="16"><b><?=$m_upload_fw_title?></b></td>
				</tr>
				<tr>
				<td id="td_left"></td>
				<td id="td_right" height="25"><b><?=$m_firmware_version?>
				<?query("/runtime/sys/info/firmwareversion");?>
      		    </b></td>
				</tr>				
				<tr>
					<td width="160" height="70"><?=$m_upload_firmware_file?> :</td>
					<td>
					<input type="file" name="firmware" id="firmware" class="text" size="30">
					<input type="submit" name="fw_load" value=" <?=$m_b_fw_upload?> " onClick="return formCheck()">
					</td>
				</tr>
				</form>
<? if(query("/runtime/web/display/language") !="1")	{echo "<!--";} ?>
				<form name="frm_lang" id="frm_lang" method="POST" action="upload_image._int" enctype="multipart/form-data" onsubmit="return check();">
				<tr>
				<td  bgcolor="#cccccc" colspan="2" height="16"><b><?=$m_upload_lang_title?></b></td>
				</tr>				
				<tr>
					<td width="160" height="70"><?=$m_upload_lang_file?> :</td>
					<td>
					<input type="file" name="lang" id="lang" class="text" size="30">
					<input type="submit" name="fw_load" value=" <?=$m_b_fw_upload?> " onClick="return langCheck()">
					</td>
				</tr>
				</form>
<? if(query("/runtime/web/display/language") !="1")	{echo "-->";} ?>					
				</table>	
			</td>
		</tr>		
		<tr>
			<td>
				<table border="0"  width="99%" cellspacing="0" cellpadding="0">				
				<tr>
				<td  bgcolor="#cccccc" colspan="2" height="16"><b><?=$m_upload_ssl_titles?></b></td>
				</tr>
				<form name="frm_certificate" id="frm_certificate" method="POST" action="upload_stunnel_cert._int" enctype="multipart/form-data" onsubmit="return check();">
				<tr>
					<td width="160" height="70"><?=$m_upload_certificatee_file?> :</td>
					<td>
					<input type="file" name="certificate" id="certificate" class="text" size="30">
					<input type="submit" name="certificate_load"  value=" <?=$m_b_fw_upload?> " onClick="return formCheckCert()">
					</td>
				</tr>
				</form>
				<form name="frm_key" id="frm_key" method="POST" action="upload_stunnel_key._int" enctype="multipart/form-data" onsubmit="return check();">
				<tr>
					<td width="160" height="70"><?=$m_upload_key_file?> :</td>
					<td>
					<input type="file" name="key" id="key" class="text" size="30">
					<input type="submit" name="key_submit" id="key_submit" value=" <?=$m_b_fw_upload?> " onClick="return formCheckKey()">
					</td>
				</tr>
				</form>				
				</table>
			</td>
		</tr>	
<? if(query("/runtime/web/display/wapi") != "1") {echo "<!--";}?>
<tr>
	<td>
	<table border="0"  width="99%" cellspacing="0" cellpadding="0">
		<tr>
			<td  bgcolor="#cccccc" colspan="2" height="16"><b><?=$m_upload_wapi_title?></b></td>
		</tr>
		<form name="frm_ascert" id="frm_ascert" method="POST" action="upload_wapi_ascert._int" enctype="multipart/form-data" onsubmit="return check();">
		<tr>
			<td><?=$m_wapi_status?></td>
<?
if(query("/sys/wapi/cert_status") == 1)
{
	$cfg_cert = $m_valid_cert;
}
else if(query("/sys/wapi/cert_status") == 2)
{
	$cfg_cert = $m_invalid_cert;
}
else
{
	$cfg_cert = $m_no_cert;
}
?>			<td height="40"><?=$cfg_cert?></td>	
		</tr>
		<tr>
			<td width="160" height="70"><?=$m_upload_ascert_file?></td>
			<td>
				<input type="file" name="as_cert" id="as_cert" class="text" size="30">
				<input type="submit" name="as_cert_load"  value=" <?=$m_b_fw_upload?> " onClick="return formCheckAsCert()">
			</td>
		</tr>
		</form>

		<form name="frm_apcert" id="frm_apcert" method="POST" action="upload_wapi_apcert._int" enctype="multipart/form-data" onsubmit="return check();">
		<tr>
			<td width="160" height="70"><?=$m_upload_apcert_file?></td>
			<td>
				<input type="file" name="ap_cert" id="ap_cert" class="text" size="30">
				<input type="submit" name="ap_cert_load" value=" <?=$m_b_fw_upload?> " onClick="return formCheckApCert()">
			</td>
		</tr>
		</form>
	</table>
	</td>
</tr>
<? if(query("/runtime/web/display/wapi") != "1") {echo "-->";}?>
<!-- ________________________________  Main Content End _______________________________ -->
												
		</table>
	</td>	
</tr>
</table>	
</form>
</body>
</html>
