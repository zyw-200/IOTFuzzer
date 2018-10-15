<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="tool_config";
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

/* --------------------------------------------------------------------------- */
require("/www/model/__html_head.php");
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.	
$cfg_wtp_enable = query("/sys/wtp/enable");
/* --------------------------------------------------------------------------- */
?>

<script>
/* page init functoin */
function init()
{
	if("<?=$cfg_wtp_enable?>" == 1)
	{
		fields_disabled(frm_load_config, true);
		fields_disabled(frm_save_config, true);
	}
	AdjustHeight();	
}

/* parameter checking */
function check()
{
	
}

function save_cfg()
{
	self.location.href="../config.bin";
}

function load_cfg(f)
{
	var f=get_obj("configuration");
	if(f.value=="")
	{
		alert("<?=$a_empty_cfg_file_path?>");
		return false;
	}
	if(f.value.slice(-4)!=".dcf")
	{
		alert("<?=$a_error_cfg_file_path?>");
		f.focus();
		return false;
	}
	if(!confirm("<?=$a_sure_to_reload_cfg?>")) return false;
}

function load_cwm(f)
{
	var f=get_obj("cwm");
	if(f.value=="")
	{
		alert("<?=$a_empty_cwm_file_path?>");
		return false;
	}
	if(f.value.slice(-4)!=".dat")
	{
		alert("<?=$a_error_cwm_file_path?>");
		f.focus();
		return false;
	}
	if(!confirm("<?=$a_sure_to_reload_cfg?>")) return false;
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
				<form name="frm_load_config" id="frm_load_config" method="POST" action="upload_config._int" enctype=multipart/form-data onsubmit="return load_cfg(this.form);">
				<tr>
				<td  bgcolor="#cccccc" colspan="2" height="16"><b><?=$m_upload_config_title?></b></td>
				</tr>
				<tr>
					<td width="100" height="70"><?=$m_load_cfg?> :</td>
					<td>
					<input type="file" name="configuration" id="configuration" class="text" size="30">
					<input type="submit" value=" <?=$m_b_load?> " name=load>
					</td>
				</tr>
				</form>
				</table>	
			</td>
		</tr>		
		<tr>
			<td>
				<table border="0"  width="99%" cellspacing="0" cellpadding="0">				
				<form name="frm_save_config" id="frm_save_config">
				<tr>
				<td  bgcolor="#cccccc" colspan="2" height="16"><b><?=$m_download_config_title?></b></td>
				</tr>					
				<tr>
					<td width="200" height="70"><?=$m_save_cfg?></td>
					<td><input type="button"value="<?=$m_save?>" onclick="save_cfg()"></td>
				</tr>
				</form>
				</table>
			</td>
		</tr>	
		<tr>
			<td>
				<table border="0"  width="99%" cellspacing="0" cellpadding="0">
				<form name="frm_load_cwm" id="frm_load_cwm" method="POST" action="upload_cwm_cfg._int" enctype=multipart/form-data onsubmit="return load_cwm(this.form);">
				<tr>
				<td  bgcolor="#cccccc" colspan="2" height="16"><b><?=$m_upload_cwm_title?></b></td>
				</tr>
				<tr>
					<td width="100" height="70"><?=$m_load_cfg?> :</td>
					<td>
					<input type="file" name="cwm" id="cwm" class="text" size="30">
					<input type="submit" value=" <?=$m_b_load?> " name=load>
					</td>
				</tr>
				</form>
				</table>
			</td>
		</tr>
<!-- ________________________________  Main Content End _______________________________ -->
												
		</table>
	</td>	
</tr>
</table>	
</form>
</body>
</html>
