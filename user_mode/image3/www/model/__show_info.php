<?require("/www/model/__auth_check.php");?>
<body onload="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm">
<center>
<?require("/www/model/__logo.php");?>
<table <? 
					if($MY_NAME=="wr_login_fail") 
					{	echo $G_TABLE_ATTR_CELL_ZERO;}
					else
					{	echo $G_LOG_MAIN_TABLE_ATTR;}
					?> >
<tr valign="middle" align="center">
	<td>
	<br>
<!-- ________________________________ Main Content Start ______________________________ -->
	<table width=<? 
					if($MY_NAME=="wr_login_fail") 
					{	echo "400";}
					else
					{	echo "80%";}
					?>>
	<tr>
		<td id="box_header">
			<h1><?=$m_context_title?></h1><br><br>
			<center>
			<? if($MY_NAME=="wr_login_fail") 
					{	echo "<table><tr><td><img src=\"/pic/X.gif\" width=\"20\" height=\"20\" border=\"0\"></td><td>";}?>
			<?
			if($REQUIRE_FILE!="")
			{
				require($LOCALE_PATH."/".$REQUIRE_FILE);
			}
			else
			{
				echo $m_context;
				echo $m_context2;//jana added
				if($m_context_next!="")
				{
					echo $m_context_next;
				}
				if($MY_NAME=="wr_login_fail") 
				{
					echo "</td></tr></table><br><br>\n";
				}
				else
				{
				echo "<br><br><br>\n";
				}
				if($USE_BUTTON=="1")
				{echo "<input type=button name='bt' value='".$m_button_dsc."' onclick='click_bt();'>\n"; }
				
			}
			?>
			</center>
			<br>
		</td>
	</tr>
	</table>
<!-- ________________________________  Main Content End _______________________________ -->
	<br>
	</td>
</tr>
</table>
<center>
</form>
</body>
</html>
