<?
$MY_NAME="__no_changed";
$MY_MSG_FILE=$MY_NAME.".php";
/* vi: set sw=4 ts=4: */
$NO_NEED_AUTH="1";
require("/www/model/__html_head.php");

echo $G_TAG_SCRIPT_START."\n";
if($DONOCHANGE == "")
{
	if($f_rule_del!="")
		{
			echo "function init() {parent.parent.location.href=\"__no_changed.php?DONOCHANGE=1\";}";
		}
		else
		{
	echo "function init() {parent.location.href=\"__no_changed.php?DONOCHANGE=1\";}";
		}	
	//echo "function init() {self.location.href==\"__no_changed.php?DONOCHANGE=1\";}";		
	echo $G_TAG_SCRIPT_END."\n";
	echo "<body onload=\"init();\" ".$G_BODY_ATTR.">\n";
	echo "</body></html>\n";
	exit;
}
else
{
	echo "function init()\n";
	echo "{\n";
	echo "var f=get_obj(\"frm\");\n";
	echo "f.bt.focus();\n";	
	echo "}\n";		
}
echo $G_TAG_SCRIPT_END."\n";
?>
<script>
function click_bt()
{
	self.location.href="index.php";
}
</script>
<body onload="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm">
<center>
<?require("/www/model/__logo.php");?>
<table <?=$G_MAIN_TABLE_ATTR?>>
<tr valign=middle align=center>
	<td>
	<br>
	<!-- ________________________________ Main Content Start ______________________________ -->
	<table width=80%>
	<tr>
		<td id="box_header">
			<h1><?=$m_nochg_title?></h1><br><br>
			<center><?=$m_nochg_dsc?><br><br><br>
			<input type="button" name='bt' value='<?=$m_continue?>' onclick='click_bt();'>
			</center><br>
		</td>
	</tr>
	</table>
	<!-- ________________________________  Main Content End _______________________________ -->
	<br>
	</td>
</tr>
</table>
</center>
</form>
</body>
</html><? exit; ?>
