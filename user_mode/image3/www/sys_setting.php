<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="sys_setting";
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
/* --------------------------------------------------------------------------- */
?>
<html>
<script>
/* page init functoin */
function init()
{
	AdjustHeight();
}

/* parameter checking */
function check()
{
	
}

function do_reboot()
{
	if(!confirm("<?=$a_sure_to_reboot?>")) return;
	var str="";
	str="/sys_cfg_valid.xgi?";
	str+=exe_str("submit REBOOT");
	parent.location.href=str;
}

function do_factory_reset()
{
	if(!confirm("<?=$a_sure_to_factory_reset?>")) return;
	var str="/sys_cfg_valid.xgi?";
	str+=exe_str("submit FRESET");
	parent.location.href=str;
}
function do_clear_language()
{
	if(!confirm("<?=$a_sure_to_clear_lang?>")) return;
	var str="/index.xgi?";
	str+=exe_str("submit CLEAR_LANG_PACK");
	parent.location.href=str;
}
</script>
<body onload="init();" bgcolor="#CCDCE2" leftmargin="0" marginheight="0" marginwidth="0" topmargin="0" style="overflow-x: auto; overflow-y: auto;" >
<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return check();">
<table id="table_frame" border=0 cellspacing="0" cellpadding="0">
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
			<td width="250" height="50"><?=$m_reboot?></td>	
			<td><input type="button" value="<?=$m_b_reboot?>" onclick="do_reboot()"></td>	
		</tr>	
		
		<tr>	
			<td height="50"><?=$m_factory_reset?></td>	
			<td><input type="button" value="<?=$m_b_restore?>" name="restore" onclick="do_factory_reset(this.form)"></td>	
		</tr>			
<? if(query("/runtime/web/display/language") !="1")	{echo "<!--";} ?>				
		<tr>	
			<td height="50"><?=$m_clear_lang_pack?></td>	
			<td><input type="button" value="<?=$m_clear?>" name="clear" onclick="do_clear_language()"></td>	
		</tr>	
<? if(query("/runtime/web/display/language") !="1")	{echo "-->";} ?>				
<!-- ________________________________  Main Content End _______________________________ -->
												
		</table>
	</td>	
</tr>
</table>	
</form>
</body>
</html>
