<?
$MY_NAME="__scan";
$MY_MSG_FILE=$MY_NAME.".php";
/* vi: set sw=4 ts=4: */
require("/www/model/__html_head.php");
$SUBMIT_STR	=query("/runtime/web/sub_str");

echo $G_TAG_SCRIPT_START."\n";
if($DOSAVE == "")
{
	echo "function init() {parent.location.href=\"__scan.php?DOSAVE=1\";}";
	echo $G_TAG_SCRIPT_END."\n";
	echo "<body onload=\"init();\" ".$G_BODY_ATTR.">\n";
	echo "</body></html>\n";
	exit;
}
else
{
	echo "function init()\n";
	echo "{\n";
	echo "var str=\"index.xgi?random=\"+generate_random_str();\n";
	if ($XGISET_STR!="")
	{
		echo "	str+=\"&".$XGISET_STR."\";\n";
	}
	if ($SUBMIT_STR!="")
	{
		echo "	str+=exe_str(\"submit COMMIT;".$SUBMIT_STR."\");\n";
	}
	if ($XGISET_AFTER_COMMIT_STR!="")
	{
		echo "	str+=\"&".$XGISET_AFTER_COMMIT_STR."\";\n";
	}
	echo "self.location.href=str;\n";
	echo "}\n";
}
echo $G_TAG_SCRIPT_END."\n";
?>
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
			<h1>
<?
	if($SUBMIT_STR == "submit SCAN" || $SUBMIT_STR == "submit WDS_SCAN" )
	{
		echo $m_scan_title;
	}
	else
	{
		echo $m_detect_title;
	}
?>
			</h1><br><br><br>
			<center><?=$m_scan_dsc?><br><br><br><br>
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
