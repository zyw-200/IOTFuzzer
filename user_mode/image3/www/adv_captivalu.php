<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_captivalu";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_captivalu";
set("/runtime/web/help_page",$MY_NAME);
/* --------------------------------------------------------------------------- */
if($ACTION_POST != "")
{
    require("/www/model/__admin_check.php");
    require("/www/__action_adv.php");
    $ACTION_POST = "";
    exit;
}

/* --------------------------------------------------------------------------- */
require("/www/model/__html_head.php");
require("/www/comm/__js_ip.php");
set("/runtime/web/next_page",$first_frame);
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
echo "<!--debug\n";
echo "-->";
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>
function init()
{
	AdjustHeight();
}

function check()
{
}

/*function formCheckHtml()
{
	var f_html=get_obj("frm_html");
	if(f_html.cap_html.value.slice(-5)!=".html")
	{
		alert("<?=$a_invalid_html?>");
		return false;
	}
}

function checkhtml()
{
	var f_html=get_obj("frm_html");
<?
set("/runtime/web/next_page",$MY_NAME);
?>
}*/

function formCheckPic()
{
	var f_pic=get_obj("frm_pic");
	if(f_pic.cap_pic.value.slice(-4)!=".jpg" && f_pic.cap_pic.value.slice(-4)!=".JPG" )
	{
		alert("<?=$a_invalid_pic?>");
		return false;
	}
}
function do_clear()
{
	var f=get_obj("frm_pic");
	var f_action=get_obj("frm_action");
	f_action.submit();
}

function checkpic()
{
<?
set("/runtime/web/next_page",$MY_NAME);
?>
}
</script>
<body <?=$G_BODY_ATTR?> onload="init();">

<table id="table_frame" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
	<tr>
		<td valign="top">
			<table id="table_header" <?=$G_TABLE_ATTR_CELL_ZERO?>>
				<tr>
					<td id="td_header" valign="middle"><?=$m_context_title?></td>
				</tr>
			</table>
<!-- ________________________________ Main Content Start ______________________________ -->
	<table id="table_set_main" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
	<!--form id="frm_html" name="frm_html" method="POST" action="upload_captival_html._int" enctype="multipart/form-data" onsubmit="return checkhtml();">
		<tr><td height="10"></td></tr>
		<tr><td  bgcolor="#cccccc" colspan="2" height="16"><b><?=$m_upload_html_titles?></b></td></tr>
		<tr>
			<td width="160" height="30"><?=$m_upload_html_file?> :</td>
			<td>
				<input type="file" name="cap_html" id="cap_html" class="text" size="30">
				<input type="submit" name="html_load"  value=" <?=$m_upload?> " onClick="return formCheckHtml()">
			</td>
		<tr>
	</form-->
	<form id="frm_action" name="frm_action" method="POST" action="<?=$MY_NAME?>.php" onsubmit="return false;">
	<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
	</form>
	<form id="frm_pic" name="frm_pic" method="POST" action="upload_captival_picture._int" enctype="multipart/form-data" onsubmit="return checkpic();">
		<tr><td height="10"></td></tr>
		<tr><td  bgcolor="#cccccc" colspan="2" height="16"><b><?=$m_upload_pic_titles?></b></td></tr>
		<tr>
			<td width="160" height="30"><?=$m_upload_pic_file?> :</td>
			<td>
				<input type="file" name="cap_pic" id="cap_pic" class="text" size="30">
				<input type="submit" name="pic_load"  value=" <?=$m_upload?> " onClick="return formCheckPic()">
				<input type="button" id="r_clear" name="r_clear" value="<?=$m_clear?>" onclick="do_clear()">
			</td>
		</tr>
	</form>
	</table>
<!-- ________________________________  Main Content End _______________________________ -->
		</td>
	</tr>
</table>
</body>
</html>

