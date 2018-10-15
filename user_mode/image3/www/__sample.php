<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "__sample";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "__sample";

/* --------------------------------------------------------------------------- */
if($ACTION_POST != "")
{
	require("/www/model/__admin_check.php");
	require("/www/__action.php");
	$ACTION_POST = "";
	exit;
}

/* --------------------------------------------------------------------------- */
require("/www/model/__html_head.php");
set("/runtime/web/next_page",$first_frame);
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
echo "<!--debug\n";
if($xx_reload == 0 || $xx_reload == 1) // change x
{
	$cfg_xx = $xx_reload;
}
else // first loading
{
	$cfg_xx = 0;
}
echo "xx_reload = ". $xx_reload ."\n";
echo "cfg_xx = ". $cfg_xx ."\n";
echo "-->";
$cfg_sample = query("/sys/devicename");
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>
/* page init functoin */
function init()
{
    var f = get_obj("frm");
    if(f.sample)
    	f.sample.value = "<?=$cfg_sample?>";
}

/* parameter checking */
function check()
{
	var f = get_obj("frm");
	return true;
}

function submit()
{
	if(check()) get_obj("frm").submit();
}

<?=$G_TAG_SCRIPT_END?>

<body <?=$G_BODY_ATTR?> onload="init();">

<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php">

<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">

<table id="table_frame" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
	<tr>
		<td valign="top">
			<table id="table_header" <?=$G_TABLE_ATTR_CELL_ZERO?>>
			<tr>
				<td id="td_header" valign="middle">Sample<?=$m_context_title?></td>
			</tr>
			</table>
<!-- ________________________________ Main Content Start ______________________________ -->
<!--
			<table id="table_set_main"  border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
				<!tr>
					<td width="30%" id="td_left">
						Device Name</td>
					<td id="td_right"><input type="text" class="text" id="sample" name="sample" value="" size="60" /></td>
				</tr>
				<tr>
					<td id="td_left">2-1</td>
					<td id="td_right">2-2</td>
				</tr>
			</table>
<?=$G_APPLY_BUTTON?>
-->
<!-- ________________________________  Main Content End _______________________________ -->
		</td>
	</tr>
</table>

</form>
</body>
</html>
